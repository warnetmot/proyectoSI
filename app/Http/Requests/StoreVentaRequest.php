<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVentaRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cambiar a true para permitir el acceso
    }

    public function rules()
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'comprobante_id' => 'required|exists:comprobantes,id',
            'numero_comprobante' => 'required|string|max:50|unique:ventas,numero_comprobante',
            'impuesto' => 'required|numeric|min:0|max:100',
            'total' => 'required|numeric|min:0.01',
            'arrayidproducto' => 'required|array|min:1',
            'arrayidproducto.*' => 'required|exists:productos,id',
            'arraycantidad' => 'required|array|min:1',
            'arraycantidad.*' => 'required|integer|min:1',
            'arrayprecioventa' => 'required|array|min:1',
            'arrayprecioventa.*' => 'required|numeric|min:0.01',
            'arraydescuento' => 'required|array|min:1',
            'arraydescuento.*' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'cliente_id.required' => 'Debe seleccionar un cliente',
            'comprobante_id.required' => 'Debe seleccionar un tipo de comprobante',
            'numero_comprobante.required' => 'El número de comprobante es obligatorio',
            'numero_comprobante.unique' => 'Este número de comprobante ya fue registrado',
            'arrayidproducto.required' => 'Debe agregar al menos un producto',
            'arraycantidad.*.min' => 'La cantidad debe ser al menos 1',
            'arrayprecioventa.*.min' => 'El precio debe ser mayor a 0',
        ];
    }
}