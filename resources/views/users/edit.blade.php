@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<div class="card shadow">
    <div class="card-header">
        <h4>Editar usuário</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nome --}}
            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" name="name" id="name" 
                       class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" id="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Senha --}}
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>

                @if(auth()->id() === $user->id)
                    {{-- Usuário próprio: pode alterar --}}
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Digite apenas para alterar">
                    <small class="text-muted">Não altere para manter a senha atual.</small>
                @else
                    {{-- Outro usuário: não permite alterar --}}
                    <input type="password" class="form-control" value="********" disabled>
                    <small class="text-muted">Senha do usuário (não é possível alterar).</small>
                @endif

                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Confirmação --}}
            @if(auth()->id() === $user->id)
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="form-control" placeholder="Digite apenas para alterar">
            </div>
            @endif

            {{-- Tipo de Usuário --}}
            <div class="mb-3">
                <label for="role" class="form-label">Tipo</label>
                <select name="role" id="role" 
                        class="form-select @error('role') is-invalid @enderror" required>
                    <option value="administrador" {{ old('role', $user->role) == 'administrador' ? 'selected' : '' }}>Administrador</option>
                    <option value="gerente_estoque" {{ old('role', $user->role) == 'gerente_estoque' ? 'selected' : '' }}>Gerente de Estoque</option>
                    <option value="operador" {{ old('role', $user->role) == 'operador' ? 'selected' : '' }}>Operador</option>
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" 
                        class="form-select @error('status') is-invalid @enderror" required>
                    <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>Inativo</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Botão --}}
            <div class="d-flex justify-content-end">
                <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection
