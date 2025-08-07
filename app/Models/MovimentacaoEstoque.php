<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimentacaoEstoque extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_id',
        'user_id',
        'tipo',
        'quantidade',
        'descricao',
        'data_movimentacao',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_movimentacao' => 'datetime',
    ];

    /**
     * Relacionamento com item
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para filtrar por tipo de movimentação
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para filtrar por período
     */
    public function scopePorPeriodo($query, $dataInicio, $dataFim)
    {
        return $query->whereBetween('data_movimentacao', [$dataInicio, $dataFim]);
    }

    /**
     * Scope para filtrar por item
     */
    public function scopePorItem($query, $itemId)
    {
        return $query->where('item_id', $itemId);
    }

    /**
     * Scope para filtrar por usuário
     */
    public function scopePorUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
