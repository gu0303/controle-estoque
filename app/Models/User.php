<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'boolean',
    ];

    /**
     * Verifica se o usuário é administrador
     */
    public function isAdministrador()
    {
        return $this->role === 'administrador';
    }

    /**
     * Verifica se o usuário é gerente de estoque
     */
    public function isGerenteEstoque()
    {
        return $this->role === 'gerente_estoque';
    }

    /**
     * Verifica se o usuário é operador
     */
    public function isOperador()
    {
        return $this->role === 'operador';
    }

    /**
     * Relacionamento com movimentações de estoque
     */
    public function movimentacoes()
    {
        return $this->hasMany(MovimentacaoEstoque::class);
    }
}

