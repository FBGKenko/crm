<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class catalogoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'producto.claveCamarena' => 'nullable|string|max:255',
            'producto.nombreCorto' => 'required|string|max:255',
            'producto.descripcion' => 'nullable|string',
            'producto.identificadorUrl' => 'nullable|string|max:255',
            'producto.nombreWeb' => 'nullable|string|max:255',
            'producto.videoUsoUrl' => 'nullable|string',
            'producto.fichaTecnicaUrl' => 'nullable|string',
            'producto.descripcionWeb' => 'nullable|string',
            'producto.categoria_id' => 'nullable|exists:categorias,id',
            'producto.fechaBorrado' => 'nullable|date',
        ];
    }

    public function messages()
    {
        return [
            'producto.nombreCorto.required' => 'El nombre corto es obligatorio.',
            'producto.nombreCorto.max' => 'El nombre corto no debe exceder los 255 caracteres.',
            'producto.categoria_id.exists' => 'La categoría seleccionada no es válida.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'producto' => array_merge($this->input('producto', []), [
                'nombreCorto' => strtoupper(trim($this->input('producto.nombreCorto'))),
            ]),
        ]);
    }
}
