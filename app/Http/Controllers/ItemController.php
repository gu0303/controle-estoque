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

        if ($request->filled('busca')) {
            $query->buscar($request->busca);
        }

        if ($request->filled('categoria_id')) {
            $query->porCategoria($request->categoria_id);
        }

        $items = $query->paginate(15);
        $categorias = Categoria::all();

        return view('items.index', compact('items', 'categorias'));
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
            'unidade' => 'required|string|max:50',
            'categoria_id' => 'required|exists:categorias,id',
            'observacao' => 'nullable|string',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('imagem')) {
            $data['caminho_imagem'] = $request->file('imagem')->store('items', 'public');
        }

        Item::create($data);

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
            'quantidade' => 'required|integer|min:0',
            'unidade' => 'required|string|max:50',
            'categoria_id' => 'required|exists:categorias,id',
            'observacao' => 'nullable|string',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('imagem')) {
            // Remove imagem anterior se existir
            if ($item->caminho_imagem) {
                Storage::disk('public')->delete($item->caminho_imagem);
            }
            $data['caminho_imagem'] = $request->file('imagem')->store('items', 'public');
        }

        $item->update($data);

        return redirect()->route('items.index')
            ->with('success', 'Item atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        if ($item->quantidade > 0) {
            return redirect()->route('items.index')
                ->with('error', 'Não é possível excluir um item com quantidade em estoque.');
        }

        // Remove imagem se existir
        if ($item->caminho_imagem) {
            Storage::disk('public')->delete($item->caminho_imagem);
        }

        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Item excluído com sucesso!');
    }
}