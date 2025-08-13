@extends('layouts.app')

@section('title', 'Novo Item')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-plus me-2"></i>
                    Novo Item
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('items.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome do Item *</label>
                                <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                                       id="nome" name="nome" value="{{ old('nome') }}" required>
                                @error('nome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="categoria_id" class="form-label">Categoria *</label>
                                <select class="form-select @error('categoria_id') is-invalid @enderror" 
                                        id="categoria_id" name="categoria_id" required>
                                    <option value="">Selecione uma categoria</option>
                                    @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" 
                                            {{ old('categoria_id', request('categoria_id')) == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nome }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($categorias->count() == 0)
                                    <div class="form-text text-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Nenhuma categoria encontrada. 
                                        <a href="{{ route('categorias.create') }}" class="text-decoration-none">Criar categoria</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                  id="descricao" name="descricao" rows="3">{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Descrição detalhada do item (opcional).</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="quantidade" class="form-label">Quantidade Inicial *</label>
                                <input type="number" class="form-control @error('quantidade') is-invalid @enderror" 
                                       id="quantidade" name="quantidade" value="{{ old('quantidade', 0) }}" 
                                       min="0" step="1" required>
                                @error('quantidade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="unidade" class="form-label">Unidade de Medida *</label>
                                <select class="form-select @error('unidade') is-invalid @enderror" 
                                        id="unidade" name="unidade" required>
                                    <option value="">Selecione a unidade</option>
                                    <option value="un" {{ old('unidade') == 'un' ? 'selected' : '' }}>Unidade (un)</option>
                                    <option value="kg" {{ old('unidade') == 'kg' ? 'selected' : '' }}>Quilograma (kg)</option>
                                    <option value="g" {{ old('unidade') == 'g' ? 'selected' : '' }}>Grama (g)</option>
                                    <option value="l" {{ old('unidade') == 'l' ? 'selected' : '' }}>Litro (l)</option>
                                    <option value="ml" {{ old('unidade') == 'ml' ? 'selected' : '' }}>Mililitro (ml)</option>
                                    <option value="m" {{ old('unidade') == 'm' ? 'selected' : '' }}>Metro (m)</option>
                                    <option value="cm" {{ old('unidade') == 'cm' ? 'selected' : '' }}>Centímetro (cm)</option>
                                    <option value="m²" {{ old('unidade') == 'm²' ? 'selected' : '' }}>Metro Quadrado (m²)</option>
                                    <option value="m³" {{ old('unidade') == 'm³' ? 'selected' : '' }}>Metro Cúbico (m³)</option>
                                    <option value="cx" {{ old('unidade') == 'cx' ? 'selected' : '' }}>Caixa (cx)</option>
                                    <option value="pct" {{ old('unidade') == 'pct' ? 'selected' : '' }}>Pacote (pct)</option>
                                    <option value="dz" {{ old('unidade') == 'dz' ? 'selected' : '' }}>Dúzia (dz)</option>
                                </select>
                                @error('unidade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="estoque_minimo" class="form-label">Estoque Mínimo</label>
                                <input type="number" class="form-control @error('estoque_minimo') is-invalid @enderror" 
                                       id="estoque_minimo" name="estoque_minimo" value="{{ old('estoque_minimo', 5) }}" 
                                       min="0" step="1">
                                @error('estoque_minimo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Quantidade mínima para alerta de estoque baixo.</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="preco_unitario" class="form-label">Preço Unitário</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control @error('preco_unitario') is-invalid @enderror" 
                                           id="preco_unitario" name="preco_unitario" value="{{ old('preco_unitario') }}" 
                                           min="0" step="0.01" placeholder="0,00">
                                </div>
                                @error('preco_unitario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Preço unitário do item (opcional).</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="localizacao" class="form-label">Localização</label>
                                <input type="text" class="form-control @error('localizacao') is-invalid @enderror" 
                                       id="localizacao" name="localizacao" value="{{ old('localizacao') }}" 
                                       placeholder="Ex: Prateleira A1, Setor B, etc.">
                                @error('localizacao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Localização física do item no estoque (opcional).</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Dica:</strong> Após criar o item, você poderá fazer movimentações de entrada e saída através do menu "Movimentações".
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('items.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Salvar Item
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
// Formatação do preço
document.getElementById('preco_unitario').addEventListener('input', function(e) {
    let value = e.target.value;
    // Remove caracteres não numéricos exceto ponto e vírgula
    value = value.replace(/[^0-9.,]/g, '');
    e.target.value = value;
});

// Auto-focus no primeiro campo
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('nome').focus();
});
</script>
@endsection

