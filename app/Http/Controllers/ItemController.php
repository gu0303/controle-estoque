<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    public function store(Request $request){
    $validated = $request->validate([
        'nome' => 'required|string|max:255',
        'descricao' => 'nullable|string',
        'quantidade' => 'required|integer|min:0',
        'unidade' => 'required|string|max:10',
        'categoria_id' => 'required|exists:categorias,id',
        'estoque_minimo' => 'nullable|integer|min:0',
        'preco_unitario' => 'nullable|numeric|min:0',
        'localizacao' => 'nullable|string|max:255',
        'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Upload da imagem
    if ($request->hasFile('imagem')) {
        $path = $request->file('imagem')->store('itens', 'public');
        $validated['imagem'] = $path;
    }

    Item::create($validated);

    return redirect()->route('items.index')->with('success', 'Item criado com sucesso!');
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
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['quantidade']); // Quantidade não atualiza aqui

        // Processar upload da imagem
        if ($request->hasFile('imagem')) {
            // Deletar imagem antiga, se existir
            if ($item->imagem && Storage::disk('public')->exists($item->imagem)) {
                Storage::disk('public')->delete($item->imagem);
            }

            $path = $request->file('imagem')->store('itens', 'public');
            $data['imagem'] = $path;
        }

        $item->update($data);

        return redirect()->route('items.show', $item)
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