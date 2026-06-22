<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'precio',
        'stock',
        'categoria_id',
    ];

    /**
     * Obtener los atributos que deben ser casteados.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    /**
     * Obtener la categoría del producto.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Scope para filtrar productos en stock.
     */
    public function scopeEnStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope para filtrar productos por categoría.
     */
    public function scopePorCategoria($query, int $categoriaId)
    {
        return $query->where('categoria_id', $categoriaId);
    }
}
