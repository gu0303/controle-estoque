<?php

namespace App\Http\Controllers;

use App\Models\MovimentacaoEstoque;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimentacaoEstoqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MovimentacaoEstoque::with(['item', 'user']);

        if ($request->filled('item_id')) {
            $query->porItem($request->item_id);
        }

        if ($request->filled('tipo')) {
            $query->porTipo($request->tipo);
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->porPeriodo($request->data_inicio, $request->data_fim);
        }

        $movimentacoes = $query->orderBy('data_movimentacao', 'desc')->paginate(20);
        $items = Item::all();

        return view('movimentacoes.index', compact('movimentacoes', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::all();
        return view('movimentacoes.create', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'tipo' => 'required|in:entrada,saida',
            'quantidade' => 'required|integer|min:1',
            'descricao' => 'required|string',
            'data_movimentacao' => 'required|date'
        ]);

        $item = Item::findOrFail($request->item_id);

        // Validação específica para saída
        if ($request->tipo === 'saida' && $request->quantidade > $item->quantidade) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Quantidade insuficiente em estoque. Disponível: ' . $item->quantidade);
        }

        DB::transaction(function () use ($request, $item) {
            // Criar movimentação
            MovimentacaoEstoque::create([
                'item_id' => $request->item_id,
                'user_id' => auth()->id(),
                'tipo' => $request->tipo,
                'quantidade' => $request->quantidade,
                'descricao' => $request->descricao,
                'data_movimentacao' => now()
            ]);

            // Atualizar quantidade do item
            if ($request->tipo === 'entrada') {
                $item->increment('quantidade', $request->quantidade);
            } else {
                $item->decrement('quantidade', $request->quantidade);
            }

        });
        return redirect()->route('movimentacoes.index')
            ->with('success', 'Movimentação registrada com sucesso!');
    }
    
    /**
     * Display the specified resource.
     */
        public function show($id){
        $movimentacao = MovimentacaoEstoque::with(['item', 'user'])->findOrFail($id);
        return view('movimentacoes.show', compact('movimentacao'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MovimentacaoEstoque $movimentacao)
    {
        // Movimentações não devem ser editadas por questões de auditoria
        abort(403, 'Movimentações não podem ser editadas.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MovimentacaoEstoque $movimentacao)
    {
        // Movimentações não devem ser editadas por questões de auditoria
        abort(403, 'Movimentações não podem ser editadas.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MovimentacaoEstoque $movimentacao)
    {
        // Movimentações não devem ser excluídas por questões de auditoria
        abort(403, 'Movimentações não podem ser excluídas.');
    }

    /**
     * Formulário específico para baixa de itens
     */
    public function baixa()
    {
        $items = Item::where('quantidade', '>', 0)->get();
        return view('movimentacoes.baixa', compact('items'));
    }

    /**
     * Processar baixa de item
     */
    public function processarBaixa(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantidade' => 'required|integer|min:1',
            'descricao' => 'required|string'
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($request->quantidade > $item->quantidade) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Quantidade insuficiente em estoque. Disponível: ' . $item->quantidade);
        }

        DB::transaction(function () use ($request, $item) {
            // Criar movimentação de saída
            MovimentacaoEstoque::create([
                'item_id' => $request->item_id,
                'user_id' => auth()->id(),
                'tipo' => 'saida',
                'quantidade' => $request->quantidade,
                'descricao' => $request->descricao,
                'data_movimentacao' => now()
            ]);

            // Decrementar quantidade do item
            $item->decrement('quantidade', $request->quantidade);
        });

        return $this->index($request)->with("success", "Baixa registrada com sucesso!");
    }
}