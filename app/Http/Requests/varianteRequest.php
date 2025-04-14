<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class varianteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'variante.codigo' => ['nullable', 'string', 'max:255'],
            'variante.sku' => ['nullable', 'string', 'max:255'],
            'variante.nombre' => ['nullable', 'string', 'max:255'],
            'variante.presentacion' => ['nullable', 'string', 'max:255'],
            'variante.cantidad' => ['nullable', 'integer', 'min:0'],
            'variante.unidad' => ['nullable', 'string', 'max:255'],
            'variante.descripcion' => ['nullable', 'string'],
            'variante.producto_id' => ['nullable', 'exists:productos,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $variante = $this->input('variante', []);

        $campos = ['codigo', 'sku', 'nombre', 'presentacion', 'unidad', 'descripcion'];

        foreach ($campos as $campo) {
            if (isset($variante[$campo])) {
                $variante[$campo] = strtoupper(trim($variante[$campo]));
            }
        }
        if (isset($variante['cantidad'])) {
            $variante['cantidad'] = trim($variante['cantidad']);
        }

        $this->merge(['variante' => $variante]);

        $this->merge([
            'variante' => array_merge($this->input('variante', []), [
                'codigo' => strtoupper(trim($this->input('variante.codigo'))),
                'sku' => strtoupper(trim($this->input('variante.sku'))),
                'nombre' => strtoupper(trim($this->input('variante.nombre'))),
                'presentacion' => strtoupper(trim($this->input('variante.presentacion'))),
                'cantidad' => trim($this->input('variante.cantidad')),
                'unidad' => strtoupper(trim($this->input('variante.unidad'))),
                'descripcion' => strtoupper(trim($this->input('variante.descripcion'))),
            ]),
        ]);
    }
}
