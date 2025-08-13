<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
        'quantidade',
        'unidade',
        'categoria_id',
        'estoque_minimo',
        'preco_unitario',
        'localizacao',
    ];

    /**
     * Relacionamento com categoria
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Relacionamento com movimentações de estoque
     */
    public function movimentacoes()
    {
        return $this->hasMany(MovimentacaoEstoque::class);
    }

    /**
     * Scope para buscar itens por nome ou descrição
     */
    public function scopeBuscar($query, $termo)
    {
        return $query->where('nome', 'like', "%{$termo}%")
                    ->orWhere('descricao', 'like', "%{$termo}%");
    }

    /**
     * Scope para filtrar por categoria
     */
    public function scopePorCategoria($query, $categoriaId)
    {
        return $query->where('categoria_id', $categoriaId);
    }
}
