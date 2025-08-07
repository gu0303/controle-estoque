@extends('layouts.app')

@section('title', 'Categorias')

@section('actions')
    <a href="{{ route('categorias.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nova Categoria
    </a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-tags me-2"></i>
            Lista de Categorias
        </h6>
    </div>
    <div class="card-body">
        @if($categorias->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Itens</th>
                            <th>Criado em</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->id }}</td>
                            <td>
                                <strong>{{ $categoria->nome }}</strong>
                            </td>
                            <td>{{ $categoria->descricao ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $categoria->items_count }} itens</span>
                            </td>
                            <td>{{ $categoria->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('categorias.show', $categoria) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($categoria->items_count == 0)
                                    <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="d-flex justify-content-center">
                {{ $categorias->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhuma categoria encontrada</h5>
                <p class="text-muted">Comece criando sua primeira categoria.</p>
                <a href="{{ route('categorias.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nova Categoria
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

