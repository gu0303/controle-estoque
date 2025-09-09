@extends('layouts.app')

@section('title', 'Relatório - Estoque Atual')

@section('content')
<div class="container">
    <h1>Relatório de Estoque Atual</h1>

    <!-- Filtros -->
    <form method="GET" action="{{ route('relatorios.estoque-atual') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="categoria_id" class="form-label">Categoria</label>
            <select name="categoria_id" id="categoria_id" class="form-select">
                <option value="">Todas</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nome }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('relatorios.exportar-estoque-atual', request()->all()) }}" class="btn btn-success">Exportar CSV</a>
        </div>
    </form>

    <!-- Tabela -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Unidade</th>
                <th>Categoria</th>
                <th>Observação</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->nome }}</td>
                    <td>{{ $item->descricao }}</td>
                    <td>{{ $item->quantidade }}</td>
                    <td>{{ $item->unidade }}</td>
                    <td>{{ $item->categoria->nome ?? '-' }}</td>
                    <td>{{ $item->observacao }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Nenhum item encontrado</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
