@extends('layouts.app')

@section('title', 'Baixa de Item')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger">
                    <i class="fas fa-minus-circle me-2"></i>
                    Baixa de Item do Estoque
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('movimentacoes.processar-baixa') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="item_id" class="form-label">Item *</label>
                        <select class="form-select @error('item_id') is-invalid @enderror" 
                                id="item_id" name="item_id" required onchange="updateItemInfo()">
                            <option value="">Selecione um item</option>
                            @foreach($items as $item)
                            <option value="{{ $item->id }}" 
                                    data-quantidade="{{ $item->quantidade }}"
                                    data-unidade="{{ $item->unidade }}"
                                    {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nome }} ({{ $item->quantidade }} {{ $item->unidade }} disponível)
                            </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="quantidade" class="form-label">Quantidade a Baixar *</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('quantidade') is-invalid @enderror" 
                                   id="quantidade" name="quantidade" value="{{ old('quantidade') }}" 
                                   min="1" required>
                            <span class="input-group-text" id="unidade-display">un</span>
                        </div>
                        @error('quantidade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <span id="quantidade-disponivel">Selecione um item para ver a quantidade disponível</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição/Motivo *</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                  id="descricao" name="descricao" rows="3" required 
                                  placeholder="Ex: Uso em projeto X, Venda para cliente Y, etc.">{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Descreva o motivo da baixa para controle.</div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Atenção:</strong> Esta ação irá reduzir a quantidade do item em estoque e não pode ser desfeita.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('movimentacoes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Voltar
                        </a>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-minus-circle me-2"></i>Confirmar Baixa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function updateItemInfo() {
    const select = document.getElementById('item_id');
    const selectedOption = select.options[select.selectedIndex];
    const quantidadeDisplay = document.getElementById('quantidade-disponivel');
    const unidadeDisplay = document.getElementById('unidade-display');
    const quantidadeInput = document.getElementById('quantidade');
    
    if (selectedOption.value) {
        const quantidade = selectedOption.dataset.quantidade;
        const unidade = selectedOption.dataset.unidade;
        
        quantidadeDisplay.textContent = `Quantidade disponível: ${quantidade} ${unidade}`;
        unidadeDisplay.textContent = unidade;
        quantidadeInput.max = quantidade;
    } else {
        quantidadeDisplay.textContent = 'Selecione um item para ver a quantidade disponível';
        unidadeDisplay.textContent = 'un';
        quantidadeInput.removeAttribute('max');
    }
}
</script>
@endsection

