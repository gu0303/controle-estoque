@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Cards de estatísticas -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total de Itens
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalItens }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Categorias
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCategorias }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tags fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isAdministrador())
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Usuários
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsuarios }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Estoque Baixo
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $itensEstoqueBaixo }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Últimas Movimentações -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-exchange-alt me-2"></i>
                    Últimas Movimentações
                </h6>
                <a href="{{ route('movimentacoes.index') }}" class="btn btn-sm btn-primary">
                    Ver Todas
                </a>
            </div>
            <div class="card-body">
                @if($ultimasMovimentacoes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Tipo</th>
                                    <th>Quantidade</th>
                                    <th>Usuário</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimasMovimentacoes as $mov)
                                <tr>
                                    <td>{{ $mov->item->nome }}</td>
                                    <td>
                                        <span class="badge {{ $mov->tipo == 'entrada' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($mov->tipo) }}
                                        </span>
                                    </td>
                                    <td>{{ $mov->quantidade }}</td>
                                    <td>{{ $mov->user->name }}</td>
                                    <td>{{ $mov->data_movimentacao->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Nenhuma movimentação encontrada.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Itens Mais Movimentados -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie me-2"></i>
                    Itens Mais Movimentados
                </h6>
            </div>
            <div class="card-body">
                @if($itensMaisMovimentados->count() > 0)
                    @foreach($itensMaisMovimentados as $item)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $item->nome }}</div>
                            <div class="text-muted small">{{ $item->movimentacoes_count }} movimentações</div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary">{{ $item->quantidade }} {{ $item->unidade }}</span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">Nenhum item encontrado.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Ações Rápidas -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt me-2"></i>
                    Ações Rápidas
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('movimentacoes.baixa') }}" class="btn btn-danger btn-lg w-100">
                            <i class="fas fa-minus-circle me-2"></i>
                            Baixa de Item
                        </a>
                    </div>
                    
                    @if(auth()->user()->isAdministrador() || auth()->user()->isGerenteEstoque())
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('items.create') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-plus-circle me-2"></i>
                            Novo Item
                        </a>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('categorias.create') }}" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-tag me-2"></i>
                            Nova Categoria
                        </a>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('relatorios.estoque-atual') }}" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-chart-bar me-2"></i>
                            Relatórios
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
</style>
