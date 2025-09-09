@extends('layouts.app')

@section('title', 'Relatório - Histórico de Movimentações')

@section('content')
<div class="container">
    <h1>Histórico de Movimentações</h1>

    <!-- Filtros -->
    <form method="GET" action="{{ route('relatorios.historico-movimentacoes') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="item_id" class="form-label">Item</label>
            <select name="item_id" id="item_id" class="form-select">
                <option value="">Todos</option>
                @foreach($items as $it)
                    <option value="{{ $it->id }}" {{ request('item_id') == $it->id ? 'selected' : '' }}>
                        {{ $it->nome }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="user_id" class="form-label">Usuário</label>
            <select name="user_id" id="user_id" class="form-select">
                <option value="">Todos</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="tipo" class="form-label">Tipo</label>
            <select name="tipo" id="tipo" class="form-select">
                <option value="">Todos</option>
                <option value="entrada" {{ request('tipo') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                <option value="saida" {{ request('tipo') == 'saida' ? 'selected' : '' }}>Saída</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="data_inicio" class="form-label">De</label>
            <input type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}" class="form-control">
        </div>
        <div class="col-md-2">
            <label for="data_fim" class="form-label">Até</label>
            <input type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}" class="form-control">
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('relatorios.exportar-movimentacoes', request()->all()) }}" class="btn btn-success">Exportar CSV</a>
        </div>
    </form>

    <!-- Tabela -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Data/Hora</th>
                <th>Item</th>
                <th>Usuário</th>
                <th>Tipo</th>
                <th>Quantidade</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movimentacoes as $mov)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($mov->data_movimentacao)->format('d/m/Y H:i') }}</td>
                    <td>{{ $mov->item->nome ?? '-' }}</td>
                    <td>{{ $mov->user->name ?? '-' }}</td>
                    <td>{{ ucfirst($mov->tipo) }}</td>
                    <td>{{ $mov->quantidade }}</td>
                    <td>{{ $mov->descricao }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Nenhuma movimentação encontrada</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
