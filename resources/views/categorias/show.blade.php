@extends('layouts.app')

@section('title', 'Categoria: ' . $categoria->nome)

@section('actions')
    <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-warning">
        <i class="fas fa-edit me-2"></i>Editar
    </a>
    <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Voltar
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>
                    Informações da Categoria
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td>{{ $categoria->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nome:</strong></td>
                        <td>{{ $categoria->nome }}</td>
                    </tr>
                    <tr>
                        <td><strong>Descrição:</strong></td>
                        <td>{{ $categoria->descricao ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total de Itens:</strong></td>
                        <td>
                            <span class="badge bg-info">{{ $categoria->items->count() }} itens</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Criado em:</strong></td>
                        <td>{{ $categoria->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Atualizado em:</strong></td>
                        <td>{{ $categoria->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-box me-2"></i>
                    Itens desta Categoria
                </h6>
                @if(auth()->user()->isAdministrador() || auth()->user()->isGerenteEstoque())
                <a href="{{ route('items.create') }}?categoria_id={{ $categoria->id }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-2"></i>Novo Item
                </a>
                @endif
            </div>
            <div class="card-body">
                @if($categoria->items->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Quantidade</th>
                                    <th>Unidade</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoria->items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->nome }}</strong>
                                        @if($item->descricao)
                                            <br><small class="text-muted">{{ Str::limit($item->descricao, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->quantidade <= 5 ? 'bg-danger' : 'bg-success' }}">
                                            {{ $item->quantidade }}
                                        </span>
                                    </td>
                                    <td>{{ $item->unidade }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('items.show', $item) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->isAdministrador() || auth()->user()->isGerenteEstoque())
                                            <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhum item nesta categoria</h5>
                        <p class="text-muted">Adicione itens a esta categoria para começar.</p>
                        @if(auth()->user()->isAdministrador() || auth()->user()->isGerenteEstoque())
                        <a href="{{ route('items.create') }}?categoria_id={{ $categoria->id }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Adicionar Item
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

