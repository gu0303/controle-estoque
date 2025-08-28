@extends('layouts.app')

@section('title', 'Movimentações')

@section('actions')
    <a href="{{ route('movimentacoes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nova Movimentação
    </a>
    <a href='{{ route('dashboard') }}' class='btn btn-secondary'>
        <i class='fas fa-arrow-left me-2'></i>Dashboard
    </a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-exchange-alt me-2"></i>
            Histórico de Movimentações
        </h6>
    </div>
    <div class="card-body">
        @if($movimentacoes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Item</th>
                            <th>Tipo</th>
                            <th>Quantidade</th>
                            <th>Usuário</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movimentacoes as $movimentacao)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $movimentacao->data_movimentacao->format('d/m/Y') }}</strong>
                                    <br><small class="text-muted">{{ $movimentacao->data_movimentacao->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $movimentacao->item->nome }}</strong>
                                    <br><small class="text-muted">{{ $movimentacao->item->categoria->nome }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $movimentacao->tipo == 'entrada' ? 'bg-success' : 'bg-danger' }}">
                                    <i class="fas {{ $movimentacao->tipo == 'entrada' ? 'fa-plus' : 'fa-minus' }} me-1"></i>
                                    {{ ucfirst($movimentacao->tipo) }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold {{ $movimentacao->tipo == 'entrada' ? 'text-success' : 'text-danger' }}">
                                    {{ $movimentacao->tipo == 'entrada' ? '+' : '-' }}{{ $movimentacao->quantidade }} {{ $movimentacao->item->unidade }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $movimentacao->user->name }}</strong>
                                    <br><small class="text-muted">{{ ucfirst($movimentacao->user->role) }}</small>
                                </div>
                            </td>
                            <td>
                                <span title="{{ $movimentacao->descricao }}">
                                    {{ Str::limit($movimentacao->descricao, 50) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('movimentacoes.show', $movimentacao) }}" 
                                       class="btn btn-sm btn-info" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('items.show', $movimentacao->item) }}" 
                                       class="btn btn-sm btn-secondary" title="Ver item">
                                        <i class="fas fa-box"></i>
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
                {{ $movimentacoes->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhuma movimentação registrada</h5>
                <p class="text-muted">Comece criando sua primeira movimentação.</p>
                <a href="{{ route('movimentacoes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nova Movimentação
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

