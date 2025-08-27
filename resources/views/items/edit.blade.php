@extends('layouts.app')

@section('title', 'Editar Item')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-edit me-2"></i>
                    Editar Item: {{ $item->nome }}
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome do Item *</label>
                                <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                                       id="nome" name="nome" value="{{ old('nome', $item->nome) }}" required>
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
                                            {{ old('categoria_id', $item->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nome }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        {{-- Campo para imagem --}}
                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem do Item</label>
                            @if($item->imagem)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $item->imagem) }}" alt="{{ $item->nome }}" 
                                        class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('imagem') is-invalid @enderror" 
                                id="imagem" name="imagem" accept="image/*">
                            @error('imagem')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Selecione uma nova imagem para atualizar o item (opcional).</div>
                        </div>
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                  id="descricao" name="descricao" rows="3">{{ old('descricao', $item->descricao) }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Descrição detalhada do item (opcional).</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="quantidade" class="form-label">Quantidade Atual</label>
                                <input type="number" class="form-control" 
                                       id="quantidade" value="{{ $item->quantidade }}" readonly>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Para alterar a quantidade, use as 
                                    <a href="{{ route('movimentacoes.create') }}?item_id={{ $item->id }}" class="text-decoration-none">movimentações</a>.
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="unidade" class="form-label">Unidade de Medida *</label>
                                <select class="form-select @error('unidade') is-invalid @enderror" 
                                        id="unidade" name="unidade" required>
                                    <option value="">Selecione a unidade</option>
                                    <option value="un" {{ old('unidade', $item->unidade) == 'un' ? 'selected' : '' }}>Unidade (un)</option>
                                    <option value="kg" {{ old('unidade', $item->unidade) == 'kg' ? 'selected' : '' }}>Quilograma (kg)</option>
                                    <option value="g" {{ old('unidade', $item->unidade) == 'g' ? 'selected' : '' }}>Grama (g)</option>
                                    <option value="l" {{ old('unidade', $item->unidade) == 'l' ? 'selected' : '' }}>Litro (l)</option>
                                    <option value="ml" {{ old('unidade', $item->unidade) == 'ml' ? 'selected' : '' }}>Mililitro (ml)</option>
                                    <option value="m" {{ old('unidade', $item->unidade) == 'm' ? 'selected' : '' }}>Metro (m)</option>
                                    <option value="cm" {{ old('unidade', $item->unidade) == 'cm' ? 'selected' : '' }}>Centímetro (cm)</option>
                                    <option value="m²" {{ old('unidade', $item->unidade) == 'm²' ? 'selected' : '' }}>Metro Quadrado (m²)</option>
                                    <option value="m³" {{ old('unidade', $item->unidade) == 'm³' ? 'selected' : '' }}>Metro Cúbico (m³)</option>
                                    <option value="cx" {{ old('unidade', $item->unidade) == 'cx' ? 'selected' : '' }}>Caixa (cx)</option>
                                    <option value="pct" {{ old('unidade', $item->unidade) == 'pct' ? 'selected' : '' }}>Pacote (pct)</option>
                                    <option value="dz" {{ old('unidade', $item->unidade) == 'dz' ? 'selected' : '' }}>Dúzia (dz)</option>
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
                                       id="estoque_minimo" name="estoque_minimo" 
                                       value="{{ old('estoque_minimo', $item->estoque_minimo) }}" 
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
                                           id="preco_unitario" name="preco_unitario" 
                                           value="{{ old('preco_unitario', $item->preco_unitario) }}" 
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
                                       id="localizacao" name="localizacao" 
                                       value="{{ old('localizacao', $item->localizacao) }}" 
                                       placeholder="Ex: Prateleira A1, Setor B, etc.">
                                @error('localizacao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Localização física do item no estoque (opcional).</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informações adicionais -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-info-circle me-2"></i>Informações do Item
                                    </h6>
                                    <p class="card-text mb-1">
                                        <strong>Criado em:</strong> {{ $item->created_at->format('d/m/Y H:i') }}
                                    </p>
                                    <p class="card-text mb-1">
                                        <strong>Última atualização:</strong> {{ $item->updated_at->format('d/m/Y H:i') }}
                                    </p>
                                    <p class="card-text mb-0">
                                        <strong>Total de movimentações:</strong> {{ $item->movimentacoes->count() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-chart-line me-2"></i>Status do Estoque
                                    </h6>
                                    <p class="card-text mb-1">
                                        <strong>Quantidade atual:</strong> 
                                        <span class="badge {{ $item->quantidade <= $item->estoque_minimo ? 'bg-danger' : 'bg-success' }}">
                                            {{ $item->quantidade }} {{ $item->unidade }}
                                        </span>
                                    </p>
                                    <p class="card-text mb-1">
                                        <strong>Status:</strong>
                                        @if($item->quantidade == 0)
                                            <span class="badge bg-danger">Sem estoque</span>
                                        @elseif($item->quantidade <= $item->estoque_minimo)
                                            <span class="badge bg-warning">Estoque baixo</span>
                                        @else
                                            <span class="badge bg-success">Disponível</span>
                                        @endif
                                    </p>
                                    @if($item->preco_unitario)
                                    <p class="card-text mb-0">
                                        <strong>Valor total:</strong> R$ {{ number_format($item->quantidade * $item->preco_unitario, 2, ',', '.') }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('items.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Voltar
                        </a>
                        <div>
                            <a href="{{ route('movimentacoes.create') }}?item_id={{ $item->id }}" class="btn btn-success me-2">
                                <i class="fas fa-exchange-alt me-2"></i>Nova Movimentação
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Atualizar Item
                            </button>
                        </div>
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
</script>
@endsection

