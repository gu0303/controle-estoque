@extends('layouts.app')

@section('title', 'Relatório - Estoque Baixo')

@section('content')
<div class="container">
    <h1>Itens com Estoque Baixo</h1>

    <form method="GET" action="{{ route('relatorios.estoque-baixo') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="limite" class="form-label">Limite</label>
            <input type="number" name="limite" id="limite" class="form-control" value="{{ $limite }}" min="0">
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary">Atualizar</button>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Quantidade</th>
                <th>Unidade</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->nome }}</td>
                    <td>{{ $item->categoria->nome ?? '-' }}</td>
                    <td class="text-danger fw-bold">{{ $item->quantidade }}</td>
                    <td>{{ $item->unidade }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Nenhum item com estoque baixo encontrado</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
