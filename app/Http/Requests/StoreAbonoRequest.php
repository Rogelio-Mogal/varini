<?php

namespace App\Http\Requests;

use App\Models\Cliente;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Venta;   // ← para calcular saldos
use App\Models\VentaCredito;

class StoreAbonoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id'          => ['required', 'exists:clientes,id'],
            'tipo_abono'        => ['required', Rule::in(['monto', 'venta'])],
            'total_formas_pago' => ['required', 'numeric', 'gt:0'],
            'formas_pago'       => ['required', 'array'],
            'formas_pago.*.monto' => ['nullable', 'numeric', 'min:0'],
            'formas_pago.*.metodo' => ['nullable', 'string'],
            'referencia' => ['nullable', 'string'],

            // IDs de ventas (solo obligatorios cuando tipo = venta)
            'ventas_seleccionadas'   => ['required_if:tipo_abono,venta', 'array'],
            'ventas_seleccionadas.*' => ['integer', 'exists:ventas,id'],
        ];
    }

    /**  Reglas “after” con lógica avanzada */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $total = (float) $this->input('total_formas_pago');
            $tipo  = $this->input('tipo_abono');

            /* --- A. Al menos una forma de pago con monto > 0 --- */
            $hayForma = collect($this->input('formas_pago'))
                        ->pluck('monto')
                        ->filter(fn ($m) => $m > 0)
                        ->isNotEmpty();

            if (!$hayForma) {
                $validator->errors()->add('formas_pago', 'Debe registrar al menos una forma de pago con monto mayor a 0.');
            }

            /* --- B. Lógica según tipo --- */
            if ($tipo === 'monto') {
                $clienteId = $this->input('cliente_id');
                $cliente = Cliente::find($clienteId);
                $deuda = $cliente?->deuda_credito ?? 0;
                
                if ($total > $deuda) {
                    $validator->errors()->add('total_formas_pago', 'El abono no puede superar la deuda ($'.number_format($deuda, 2).').');
                }
            }

            if ($tipo === 'venta') {
                // Suma de saldos de las ventas seleccionadas *desde la BD*
                $ids = $this->input('ventas_seleccionadas', []);
                //$sumaSaldos = Venta::whereIn('id', $ids)->sum('monto_credito');

                 $sumaSaldos = VentaCredito::whereIn('venta_id', $ids)
                ->where('activo', true)
                ->sum('saldo_actual');

                if ($total > $sumaSaldos) {
                    $validator->errors()->add('total_formas_pago', 'El abono supera la suma de saldos seleccionados ($'.number_format($sumaSaldos,2).').');
                }
            }
        });
    }

    /** Mensajes personalizados (opcional) */
    public function messages(): array
    {
        return [
            'ventas_seleccionadas.required_if' => 'Debe seleccionar al menos una venta.',
            'ventas_seleccionadas.*.exists'    => 'Una de las ventas seleccionadas no existe.',
        ];
    }
}
