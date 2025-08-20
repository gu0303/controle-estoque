@extends('layouts.app')

@section('title', 'Movimentação #' . $movimentacao->id)

@section('actions')
    <a href='{{ route('items.show', $movimentacao->item->id) }}' class='btn btn-info'>
        <i class='fas fa-box me-2'></i>Ver Item
    </a>
    <a href='{{ route('movimentacoes.index') }}' class='btn btn-secondary'>
        <i class='fas fa-arrow-left me-2'></i>Voltar
    </a>
@endsection

@section('content')
<div class='row'>
    <!-- Informações da Movimentação -->
    <div class='col-md-6'>
        <div class='card shadow mb-4'>
            <div class='card-header py-3'>
                <h6 class='m-0 font-weight-bold text-primary'>
                    <i class='fas fa-info-circle me-2'></i>
                    Detalhes da Movimentação
                </h6>
            </div>
            <div class='card-body'>
                <table class='table table-borderless'>
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td>#{{ $movimentacao->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tipo:</strong></td>
                        <td>
                            <span class='badge {{ $movimentacao->tipo == 'entrada' ? 'bg-success' : 'bg-danger' }} fs-6'>
                                <i class='fas {{ $movimentacao->tipo == 'entrada' ? 'fa-plus' : 'fa-minus' }} me-1'></i>
                                {{ ucfirst($movimentacao->tipo) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Quantidade:</strong></td>
                        <td>
                            <span class='fw-bold {{ $movimentacao->tipo == 'entrada' ? 'text-success' : 'text-danger' }}'>
                                {{ $movimentacao->tipo == 'entrada' ? '+' : '-' }}{{ $movimentacao->quantidade }} {{ $movimentacao->item->unidade }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Data/Hora:</strong></td>
                        <td>{{ $movimentacao->data_movimentacao->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Usuário:</strong></td>
                        <td>
                            <div>
                                <strong>{{ $movimentacao->user->name }}</strong>
                                <br><small class='text-muted'>{{ ucfirst($movimentacao->user->role) }}</small>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Registrado em:</strong></td>
                        <td>{{ $movimentacao->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Descrição -->
        <div class='card shadow'>
            <div class='card-header py-3'>
                <h6 class='m-0 font-weight-bold text-primary'>
                    <i class='fas fa-comment me-2'></i>
                    Descrição
                </h6>
            </div>
            <div class='card-body'>
                <p class='mb-0'>{{ $movimentacao->descricao }}</p>
            </div>
        </div>
    </div>
    
    <!-- Informações do Item -->
    <div class='col-md-6'>
        <div class='card shadow mb-4'>
            <div class='card-header py-3'>
                <h6 class='m-0 font-weight-bold text-primary'>
                    <i class='fas fa-box me-2'></i>
                    Item Movimentado
                </h6>
            </div>
            <div class='card-body'>
                <table class='table table-borderless'>
                    <tr>
                        <td><strong>Nome:</strong></td>
                        <td>
                            <a href='{{ route('items.show', $movimentacao->item) }}' class='text-decoration-none'>
                                {{ $movimentacao->item->nome }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Categoria:</strong></td>
                        <td>
                            <span class='badge bg-info'>{{ $movimentacao->item->categoria->nome }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Descrição:</strong></td>
                        <td>{{ $movimentacao->item->descricao ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Unidade:</strong></td>
                        <td>{{ $movimentacao->item->unidade }}</td>
                    </tr>
                    <tr>
                        <td><strong>Estoque Atual:</strong></td>
                        <td>
                            <span class='fw-bold {{ $movimentacao->item->quantidade <= $movimentacao->item->estoque_minimo ? 'text-danger' : 'text-success' }}'>
                                {{ $movimentacao->item->quantidade }} {{ $movimentacao->item->unidade }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Localização:</strong></td>
                        <td>{{ $movimentacao->item->localizacao ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Impacto da Movimentação -->
        <div class='card shadow'>
            <div class='card-header py-3'>
                <h6 class='m-0 font-weight-bold text-primary'>
                    <i class='fas fa-chart-line me-2'></i>
                    Impacto no Estoque
                </h6>
            </div>
            <div class='card-body text-center'>
                <div class='row'>
                    <div class='col-4'>
                        <h6 class='text-muted'>Antes</h6>
                        <h4 class='text-info'>
                            @php
                                $estoqueAntes = $movimentacao->tipo == 'entrada' 
                                    ? $movimentacao->item->quantidade - $movimentacao->quantidade
                                    : $movimentacao->item->quantidade + $movimentacao->quantidade;
                            @endphp
                            {{ $estoqueAntes }}
                        </h4>
                        <small class='text-muted'>{{ $movimentacao->item->unidade }}</small>
                    </div>
                    
                    <div class='col-4'>
                        <h6 class='text-muted'>Movimentação</h6>
                        <h4 class='{{ $movimentacao->tipo == 'entrada' ? 'text-success' : 'text-danger' }}'>
                            {{ $movimentacao->tipo == 'entrada' ? '+' : '-' }}{{ $movimentacao->quantidade }}
                        </h4>
                        <small class='text-muted'>{{ $movimentacao->item->unidade }}</small>
                    </div>
                    
                    <div class='col-4'>
                        <h6 class='text-muted'>Depois</h6>
                        <h4 class='{{ $movimentacao->item->quantidade <= $movimentacao->item->estoque_minimo ? 'text-danger' : 'text-success' }}'>
                                {{ $movimentacao->item->quantidade }}
                        </h4>
                        <small class='text-muted'>{{ $movimentacao->item->unidade }}</small>
                    </div>
                </div>
                
                @if($movimentacao->item->quantidade <= $movimentacao->item->estoque_minimo)
                <div class='alert alert-warning mt-3 mb-0'>
                    <i class='fas fa-exclamation-triangle me-2'></i>
                    <strong>Atenção:</strong> O estoque atual está abaixo do mínimo recomendado ({{ $movimentacao->item->estoque_minimo }} {{ $movimentacao->item->unidade }}).
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Movimentações Relacionadas -->
<div class='row mt-4'>
    <div class='col-12'>
        <div class='card shadow'>
            <div class='card-header py-3 d-flex justify-content-between align-items-center'>
                <h6 class='m-0 font-weight-bold text-primary'>
                    <i class='fas fa-history me-2'></i>
                    Outras Movimentações do Item (Últimas 10)
                </h6>
                <a href='{{ route('movimentacoes.index') }}?item_id={{ $movimentacao->item->id }}' class='btn btn-sm btn-outline-primary'>
                    Ver Todas
                </a>
            </div>
            <div class='card-body'>
                @php
                    $outrasMovimentacoes = $movimentacao->item->movimentacoes()
                        ->where('id', '!=', $movimentacao->id)
                        ->orderBy('data_movimentacao', 'desc')
                        ->limit(10)
                        ->get();
                @endphp
                
                @if($outrasMovimentacoes->count() > 0)
                    <div class='table-responsive'>
                        <table class='table table-sm table-hover'>
                            <thead>
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Tipo</th>
                                    <th>Quantidade</th>
                                    <th>Usuário</th>
                                    <th>Descrição</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($outrasMovimentacoes as $mov)
                                <tr>
                                    <td>{{ $mov->data_movimentacao->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class='badge {{ $mov->tipo == 'entrada' ? 'bg-success' : 'bg-danger' }}' >
                                            <i class='fas {{ $mov->tipo == 'entrada' ? 'fa-plus' : 'fa-minus' }} me-1'></i>
                                            {{ ucfirst($mov->tipo) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class='{{ $mov->tipo == 'entrada' ? 'text-success' : 'text-danger' }}' >
                                            {{ $mov->tipo == 'entrada' ? '+' : '-' }}{{ $mov->quantidade }} {{ $mov->item->unidade }}
                                        </span>
                                    </td>
                                    <td>{{ $mov->user->name }}</td>
                                    <td>{{ Str::limit($mov->descricao, 40) }}</td>
                                    <td>
                                        <a href='{{ route('movimentacoes.show', $mov) }}' class='btn btn-sm btn-outline-info'>
                                            <i class='fas fa-eye'></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class='text-center py-3'>
                        <i class='fas fa-history fa-2x text-muted mb-2'></i>
                        <p class='text-muted mb-0'>Esta é a única movimentação registrada para este item.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Ações Rápidas -->
<div class='row mt-4'>
    <div class='col-12'>
        <div class='card shadow'>
            <div class='card-header py-3'>
                <h6 class='m-0 font-weight-bold text-primary'>
                    <i class='fas fa-bolt me-2'></i>
                    Ações Rápidas
                </h6>
            </div>
            <div class='card-body'>
                <div class='row'>
                    <div class='col-md-3 mb-3'>
                        <a href='{{ route('movimentacoes.create') }}?item_id={{ $movimentacao->item->id }}&tipo=entrada' 
                           class='btn btn-success btn-lg w-100'>
                            <i class='fas fa-plus-circle me-2'></i>
                            Nova Entrada
                        </a>
                    </div>
                    
                    <div class='col-md-3 mb-3'>
                        <a href='{{ route('movimentacoes.create') }}?item_id={{ $movimentacao->item->id }}&tipo=saida' 
                           class='btn btn-danger btn-lg w-100'>
                            <i class='fas fa-minus-circle me-2'></i>
                            Nova Saída
                        </a>
                    </div>
                    
                    <div class='col-md-3 mb-3'>
                        <a href='{{ route('items.show', $movimentacao->item->id) }}' class='btn btn-info btn-lg w-100'>
                            <i class='fas fa-box me-2'></i>
                            Ver Item
                        </a>
                    </div>
                    
                    <div class='col-md-3 mb-3'>
                        <a href='{{ route('movimentacoes.index') }}?item_id={{ $movimentacao->item->id }}' 
                           class='btn btn-secondary btn-lg w-100'>
                            <i class='fas fa-history me-2'></i>
                            Histórico
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

