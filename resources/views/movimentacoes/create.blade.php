@extends('layouts.app')

@section('title', 'Nova Movimentação')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-exchange-alt me-2"></i>
                    Nova Movimentação de Estoque
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('movimentacoes.store') }}" method="POST">
                    @csrf

                    <!-- Seleção de Item -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="item_id" class="form-label">Item *</label>
                                <select class="form-select @error('item_id') is-invalid @enderror" 
                                        id="item_id" name="item_id" required>
                                    <option value="">Selecione um item</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" data-quantidade="{{ $item->quantidade }}"
                                            {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nome }} ({{ $item->quantidade }} {{ $item->unidade }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('item_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tipo de Movimentação -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo de Movimentação *</label>
                                <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="entrada" {{ old('tipo') == 'entrada' ? 'selected' : '' }}>
                                        Entrada (Adicionar ao estoque)
                                    </option>
                                    <option value="saida" {{ old('tipo') == 'saida' ? 'selected' : '' }}>
                                        Saída (Remover do estoque)
                                    </option>
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Quantidade e Data/Hora -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="quantidade" class="form-label">Quantidade *</label>
                                <input type="number" class="form-control @error('quantidade') is-invalid @enderror" 
                                       id="quantidade" name="quantidade" value="{{ old('quantidade') }}" 
                                       min="1" step="1" required>
                                @error('quantidade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="data_movimentacao" class="form-label">Data e Hora *</label>
                                <input type="datetime-local" class="form-control @error('data_movimentacao') is-invalid @enderror" 
                                       id="data_movimentacao" name="data_movimentacao" 
                                       value="{{ old('data_movimentacao', now()->format('Y-m-d\TH:i')) }}" required>
                                @error('data_movimentacao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição *</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                  id="descricao" name="descricao" rows="3" required>{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Descreva o motivo da movimentação (ex: Compra, Venda, Ajuste de estoque, etc.).</div>
                    </div>

                    <!-- Botões -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('movimentacoes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Registrar Movimentação
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/app.js') }}"></script>
@endsection
