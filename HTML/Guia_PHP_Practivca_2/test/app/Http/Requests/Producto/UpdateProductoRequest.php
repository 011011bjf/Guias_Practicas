<?php

namespace App\Http\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['sometimes', 'required', 'string', 'max:150'],
            'precio' => ['sometimes', 'required', 'numeric', 'min:0', 'max:999999.99'],
            'stock' => ['sometimes', 'required', 'integer', 'min:0'],
            'categoria_id' => ['sometimes', 'required', 'integer', 'exists:categorias,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del producto no puede estar vacío.',
            'precio.required' => 'El precio no puede estar vacío.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio no puede ser negativo.',
            'stock.required' => 'El stock no puede estar vacío.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',
            'categoria_id.required' => 'La categoría no puede estar vacía.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
        ];
    }
}
