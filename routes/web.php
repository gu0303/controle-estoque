<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MovimentacaoEstoqueController;
use App\Http\Controllers\RelatorioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard principal
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    // Perfil do usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gerenciamento de usuários (apenas administradores)
    Route::middleware(['check.role:administrador'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Gerenciamento de categorias (administradores e gerentes de estoque)
    Route::middleware(['check.role:administrador,gerente_estoque'])->group(function () {
        Route::resource('categorias', CategoriaController::class);
    });

    // Gerenciamento de itens (administradores e gerentes de estoque)
    Route::middleware(['check.role:administrador,gerente_estoque'])->group(function () {
        Route::resource('items', ItemController::class);
    });

    // Movimentações de estoque (todos os usuários autenticados)
    Route::resource('movimentacoes', MovimentacaoEstoqueController::class);
    
    // Rota específica para baixa de itens
    Route::get('/baixa', [MovimentacaoEstoqueController::class, 'baixa'])->name('movimentacoes.baixa');
    Route::post('/baixa', [MovimentacaoEstoqueController::class, 'processarBaixa'])->name('movimentacoes.processar-baixa');

    // Relatórios (administradores e gerentes de estoque)
    Route::middleware(['check.role:administrador,gerente_estoque'])->group(function () {
        Route::get('/relatorios/estoque-atual', [RelatorioController::class, 'estoqueAtual'])->name('relatorios.estoque-atual');
        Route::get('/relatorios/historico-movimentacoes', [RelatorioController::class, 'historicoMovimentacoes'])->name('relatorios.historico-movimentacoes');
        Route::get('/relatorios/estoque-baixo', [RelatorioController::class, 'estoqueBaixo'])->name('relatorios.estoque-baixo');
        
        // Exportações
        Route::get('/relatorios/exportar/estoque-atual', [RelatorioController::class, 'exportarEstoqueAtual'])->name('relatorios.exportar.estoque-atual');
        Route::get('/relatorios/exportar/movimentacoes', [RelatorioController::class, 'exportarMovimentacoes'])->name('relatorios.exportar.movimentacoes');
    });
});

require __DIR__.'/auth.php';
