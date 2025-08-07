<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Categoria;
use App\Models\MovimentacaoEstoque;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard principal
     */
    public function index()
    {
        $totalItens = Item::count();
        $totalCategorias = Categoria::count();
        $totalUsuarios = User::count();
        $itensEstoqueBaixo = Item::where('quantidade', '<=', 5)->count();
        
        $ultimasMovimentacoes = MovimentacaoEstoque::with(['item', 'user'])
            ->orderBy('data_movimentacao', 'desc')
            ->limit(10)
            ->get();
            
        $itensMaisMovimentados = Item::withCount('movimentacoes')
            ->orderBy('movimentacoes_count', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalItens',
            'totalCategorias', 
            'totalUsuarios',
            'itensEstoqueBaixo',
            'ultimasMovimentacoes',
            'itensMaisMovimentados'
        ));
    }
}
