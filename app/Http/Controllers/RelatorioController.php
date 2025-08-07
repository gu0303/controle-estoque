<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\MovimentacaoEstoque;
use App\Models\Categoria;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    /**
     * Relatório de estoque atual
     */
    public function estoqueAtual(Request $request)
    {
        $query = Item::with('categoria');

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        $items = $query->orderBy('nome')->get();
        $categorias = Categoria::all();

        return view('relatorios.estoque-atual', compact('items', 'categorias'));
    }

    /**
     * Relatório de histórico de movimentações
     */
    public function historicoMovimentacoes(Request $request)
    {
        $query = MovimentacaoEstoque::with(['item', 'user']);

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('data_movimentacao', [
                $request->data_inicio . ' 00:00:00',
                $request->data_fim . ' 23:59:59'
            ]);
        }

        $movimentacoes = $query->orderBy('data_movimentacao', 'desc')->get();
        $items = Item::all();
        $users = \App\Models\User::all();

        return view('relatorios.historico-movimentacoes', compact('movimentacoes', 'items', 'users'));
    }

    /**
     * Relatório de itens com estoque baixo
     */
    public function estoqueBaixo(Request $request)
    {
        $limite = $request->get('limite', 5);
        
        $items = Item::with('categoria')
            ->where('quantidade', '<=', $limite)
            ->orderBy('quantidade')
            ->get();

        return view('relatorios.estoque-baixo', compact('items', 'limite'));
    }

    /**
     * Exportar relatório de estoque atual para CSV
     */
    public function exportarEstoqueAtual(Request $request)
    {
        $query = Item::with('categoria');

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        $items = $query->orderBy('nome')->get();

        $filename = 'estoque_atual_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($items) {
            $file = fopen('php://output', 'w');
            
            // Cabeçalho do CSV
            fputcsv($file, [
                'ID',
                'Nome',
                'Descrição',
                'Quantidade',
                'Unidade',
                'Categoria',
                'Observação'
            ]);

            // Dados
            foreach ($items as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->nome,
                    $item->descricao,
                    $item->quantidade,
                    $item->unidade,
                    $item->categoria->nome,
                    $item->observacao
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar relatório de movimentações para CSV
     */
    public function exportarMovimentacoes(Request $request)
    {
        $query = MovimentacaoEstoque::with(['item', 'user']);

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('data_movimentacao', [
                $request->data_inicio . ' 00:00:00',
                $request->data_fim . ' 23:59:59'
            ]);
        }

        $movimentacoes = $query->orderBy('data_movimentacao', 'desc')->get();

        $filename = 'movimentacoes_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($movimentacoes) {
            $file = fopen('php://output', 'w');
            
            // Cabeçalho do CSV
            fputcsv($file, [
                'ID',
                'Item',
                'Usuário',
                'Tipo',
                'Quantidade',
                'Descrição',
                'Data/Hora'
            ]);

            // Dados
            foreach ($movimentacoes as $mov) {
                fputcsv($file, [
                    $mov->id,
                    $mov->item->nome,
                    $mov->user->name,
                    ucfirst($mov->tipo),
                    $mov->quantidade,
                    $mov->descricao,
                    $mov->data_movimentacao->format('d/m/Y H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
