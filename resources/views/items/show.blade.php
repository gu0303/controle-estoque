@extends('layouts.app')

@section('title', 'Item: ' . $item->nome)

@section('actions')
    @if(auth()->user()->isAdministrador() || auth()->user()->isGerenteEstoque())
    <a href="{{ route('items.edit', $item) }}" class="btn btn-warning">
        <i class="fas fa-edit me-2"></i>Editar
    </a>
    @endif
    <a href="{{ route('movimentacoes.create') }}?item_id={{ $item->id }}" class="btn btn-success">
        <i class="fas fa-exchange-alt me-2"></i>Nova Movimentação
    </a>
    <a href="{{ route('items.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Voltar
    </a>
@endsection

@section('content')
<div class="row">
    <!-- Informações do Item -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>
                    Informações do Item
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td>{{ $item->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nome:</strong></td>
                        <td>{{ $item->nome }}</td>
                    </tr>
                    <tr>
                        <td><strong>Categoria:</strong></td>
                        <td>
                            <a href="{{ route('categorias.show', $item->categoria) }}" class="text-decoration-none">
                                <span class="badge bg-info">{{ $item->categoria->nome }}</span>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Descrição:</strong></td>
                        <td>{{ $item->descricao ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Unidade:</strong></td>
                        <td>{{ $item->unidade }}</td>
                    </tr>
                    <tr>
                        <td><strong>Localização:</strong></td>
                        <td>{{ $item->localizacao ?? '-' }}</td>
                    </tr>
                    @if($item->preco_unitario)
                    <tr>
                        <td><strong>Preço Unitário:</strong></td>
                        <td>R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Criado em:</strong></td>
                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Atualizado em:</strong></td>
                        <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Status do Estoque -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-bar me-2"></i>
                    Status do Estoque
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <h2 class="display-4 {{ $item->quantidade <= $item->estoque_minimo ? 'text-danger' : 'text-success' }}">
                        {{ $item->quantidade }}
                    </h2>
                    <p class="text-muted">{{ $item->unidade }}</p>
                </div>
                
                <div class="mb-3">
                    @if($item->quantidade == 0)
                        <span class="badge bg-danger fs-6">Sem estoque</span>
                    @elseif($item->quantidade <= $item->estoque_minimo)
                        <span class="badge bg-warning fs-6">Estoque baixo</span>
                    @else
                        <span class="badge bg-success fs-6">Disponível</span>
                    @endif
                </div>

                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h6 class="text-muted">Estoque Mínimo</h6>
                            <h5>{{ $item->estoque_minimo }}</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <h6 class="text-muted">Movimentações</h6>
                        <h5>{{ $item->movimentacoes->count() }}</h5>
                    </div>
                </div>

                @if($item->preco_unitario)
                <hr>
                <div class="text-center">
                    <h6 class="text-muted">Valor Total em Estoque</h6>
                    <h4 class="text-success">R$ {{ number_format($item->quantidade * $item->preco_unitario, 2, ',', '.') }}</h4>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Movimentações -->
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-exchange-alt me-2"></i>
                    Histórico de Movimentações
                </h6>
                <a href="{{ route('movimentacoes.create') }}?item_id={{ $item->id }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-2"></i>Nova Movimentação
                </a>
            </div>
            <div class="card-body">
                @if($item->movimentacoes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Tipo</th>
                                    <th>Quantidade</th>
                                    <th>Usuário</th>
                                    <th>Descrição</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->movimentacoes()->orderBy('data_movimentacao', 'desc')->limit(10)->get() as $mov)
                                <tr>
                                    <td>{{ $mov->data_movimentacao->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge {{ $mov->tipo == 'entrada' ? 'bg-success' : 'bg-danger' }}">
                                            <i class="fas {{ $mov->tipo == 'entrada' ? 'fa-plus' : 'fa-minus' }} me-1"></i>
                                            {{ ucfirst($mov->tipo) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="{{ $mov->tipo == 'entrada' ? 'text-success' : 'text-danger' }}">
                                            {{ $mov->tipo == 'entrada' ? '+' : '-' }}{{ $mov->quantidade }} {{ $item->unidade }}
                                        </span>
                                    </td>
                                    <td>{{ $mov->user->name }}</td>
                                    <td>{{ Str::limit($mov->descricao, 50) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($item->movimentacoes->count() > 10)
                    <div class="text-center mt-3">
                        <a href="{{ route('movimentacoes.index') }}?item_id={{ $item->id }}" class="btn btn-outline-primary">
                            Ver Todas as Movimentações ({{ $item->movimentacoes->count() }})
                        </a>
                    </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhuma movimentação registrada</h5>
                        <p class="text-muted">Este item ainda não possui movimentações de estoque.</p>
                        <a href="{{ route('movimentacoes.create') }}?item_id={{ $item->id }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Primeira Movimentação
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Gráfico de Movimentações (se houver dados) -->
        @if($item->movimentacoes->count() > 0)
        <div class="card shadow mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-line me-2"></i>
                    Evolução do Estoque (Últimos 30 dias)
                </h6>
            </div>
            <div class="card-body">
                <canvas id="estoqueChart" width="400" height="100"></canvas>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Ações Rápidas -->
<div class="row mt-4">
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
                        <a href="{{ route('movimentacoes.create') }}?item_id={{ $item->id }}&tipo=entrada" 
                           class="btn btn-success btn-lg w-100">
                            <i class="fas fa-plus-circle me-2"></i>
                            Entrada
                        </a>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('movimentacoes.baixa') }}?item_id={{ $item->id }}" 
                           class="btn btn-danger btn-lg w-100">
                            <i class="fas fa-minus-circle me-2"></i>
                            Baixa
                        </a>
                    </div>
                    
                    @if(auth()->user()->isAdministrador() || auth()->user()->isGerenteEstoque())
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('items.edit', $item) }}" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-edit me-2"></i>
                            Editar Item
                        </a>
                    </div>
                    @endif
                    
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('movimentacoes.index') }}?item_id={{ $item->id }}" 
                           class="btn btn-info btn-lg w-100">
                            <i class="fas fa-history me-2"></i>
                            Histórico
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if($item->movimentacoes->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dados para o gráfico (últimos 30 dias)
const ctx = document.getElementById('estoqueChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels ?? []) !!},
        datasets: [{
            label: 'Quantidade em Estoque',
            data: {!! json_encode($chartData ?? []) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Quantidade ({{ $item->unidade }})'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Data'
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endif
@endsection

