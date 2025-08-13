<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Item::with('categoria');

        // Filtro por busca
        if ($request->filled('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }

        // Filtro por categoria
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        // Filtro por estoque
        if ($request->filled('estoque')) {
            if ($request->estoque == 'baixo') {
                $query->whereRaw('quantidade <= estoque_minimo');
            } elseif ($request->estoque == 'zerado') {
                $query->where('quantidade', 0);
            }
        }

        $items = $query->orderBy('nome')->paginate(15);
        $categorias = Categoria::all();

        // Estatísticas para os cards
        $totalItens = Item::count();
        $itensComEstoque = Item::where('quantidade', '>', 0)->count();
        $itensEstoqueBaixo = Item::whereRaw('quantidade <= estoque_minimo')->count();
        $itensSemEstoque = Item::where('quantidade', 0)->count();

        return view('items.index', compact(
            'items', 
            'categorias', 
            'totalItens', 
            'itensComEstoque', 
            'itensEstoqueBaixo', 
            'itensSemEstoque'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('items.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'quantidade' => 'required|integer|min:0',
            'unidade' => 'required|string|max:10',
            'categoria_id' => 'required|exists:categorias,id',
            'estoque_minimo' => 'nullable|integer|min:0',
            'preco_unitario' => 'nullable|numeric|min:0',
            'localizacao' => 'nullable|string|max:255',
        ]);

        $item = Item::create($request->all());

        return redirect()->route('items.index')
            ->with('success', 'Item criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item->load(['categoria', 'movimentacoes.user']);
        
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $categorias = Categoria::all();
        return view('items.edit', compact('item', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'unidade' => 'required|string|max:10',
            'categoria_id' => 'required|exists:categorias,id',
            'estoque_minimo' => 'nullable|integer|min:0',
            'preco_unitario' => 'nullable|numeric|min:0',
            'localizacao' => 'nullable|string|max:255',
        ]);

        // Remove quantidade da atualização - só pode ser alterada via movimentações
        $data = $request->except(['quantidade']);
        
        $item->update($data);

        return redirect()->route('items.index')
            ->with('success', 'Item atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        // Verificar se o item tem movimentações
        if ($item->movimentacoes()->count() > 0) {
            return redirect()->route('items.index')
                ->with('error', 'Não é possível excluir um item que possui movimentações.');
        }

        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Item excluído com sucesso!');
    }
}