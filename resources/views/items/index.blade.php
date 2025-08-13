@extends('layouts.app')

@section('title', 'Itens')

@section('actions')
    <a href="{{ route('items.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Novo Item
    </a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-box me-2"></i>
            Lista de Itens
        </h6>
    </div>
    <div class="card-body">
        <!-- Filtros -->
        <div class="row mb-3">
            <div class="col-md-4">
                <form method="GET" action="{{ route('items.index') }}">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}" placeholder="Buscar por nome...">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search'))
                        <a href="{{ route('items.index') }}" class="btn btn-outline-danger">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-md-3">
                <form method="GET" action="{{ route('items.index') }}">
                    <select class="form-select" name="categoria" onchange="this.form.submit()">
                        <option value="">Todas as categorias</option>
                        @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" 
                                {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nome }}
                        </option>
                        @endforeach
                    </select>
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                </form>
            </div>
            <div class="col-md-2">
                <form method="GET" action="{{ route('items.index') }}">
                    <select class="form-select" name="estoque" onchange="this.form.submit()">
                        <option value="">Todos os estoques</option>
                        <option value="baixo" {{ request('estoque') == 'baixo' ? 'selected' : '' }}>Estoque baixo</option>
                        <option value="zerado" {{ request('estoque') == 'zerado' ? 'selected' : '' }}>Estoque zerado</option>
                    </select>
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('categoria'))
                        <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                    @endif
                </form>
            </div>
            <div class="col-md-3 text-end">
                <span class="text-muted">
                    Total: {{ $items->total() }} itens
                </span>
            </div>
        </div>

        @if($items->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Categoria</th>
                            <th>Quantidade</th>
                            <th>Unidade</th>
                            <th>Estoque Mín.</th>
                            <th>Localização</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $item->nome }}</strong>
                                    @if($item->descricao)
                                        <br><small class="text-muted">{{ Str::limit($item->descricao, 50) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $item->categoria->nome }}</span>
                            </td>
                            <td>
                                <span class="fw-bold {{ $item->quantidade <= $item->estoque_minimo ? 'text-danger' : 'text-success' }}">
                                    {{ $item->quantidade }}
                                </span>
                            </td>
                            <td>{{ $item->unidade }}</td>
                            <td>{{ $item->estoque_minimo }}</td>
                            <td>{{ $item->localizacao ?? '-' }}</td>
                            <td>
                                @if($item->quantidade == 0)
                                    <span class="badge bg-danger">Sem estoque</span>
                                @elseif($item->quantidade <= $item->estoque_minimo)
                                    <span class="badge bg-warning">Estoque baixo</span>
                                @else
                                    <span class="badge bg-success">Disponível</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('items.show', $item) }}" class="btn btn-sm btn-info" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user()->isAdministrador() || auth()->user()->isGerenteEstoque())
                                    <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('movimentacoes.create') }}?item_id={{ $item->id }}" 
                                       class="btn btn-sm btn-success" title="Nova movimentação">
                                        <i class="fas fa-exchange-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="d-flex justify-content-center">
                {{ $items->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">
                    @if(request('search') || request('categoria') || request('estoque'))
                        Nenhum item encontrado com os filtros aplicados
                    @else
                        Nenhum item cadastrado
                    @endif
                </h5>
                <p class="text-muted">
                    @if(request('search') || request('categoria') || request('estoque'))
                        Tente ajustar os filtros ou 
                        <a href="{{ route('items.index') }}" class="text-decoration-none">limpar a busca</a>.
                    @else
                        Comece criando seu primeiro item.
                    @endif
                </p>
                @if(!request('search') && !request('categoria') && !request('estoque'))
                <a href="{{ route('items.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Novo Item
                </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Cards de resumo -->
@if($items->count() > 0)
<div class="row mt-4">
    <div class="col-md-3">
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

    <div class="col-md-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Com Estoque
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $itensComEstoque }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
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

    <div class="col-md-3">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Sem Estoque
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $itensSemEstoque }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}
</style>

