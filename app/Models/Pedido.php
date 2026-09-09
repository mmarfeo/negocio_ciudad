<?php

namespace App\Models;

use App\Models\Concerns\PerteneceANegocio;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use PerteneceANegocio;

    protected $fillable = [
        'negocio_id', 'nombre_cliente', 'telefono_cliente', 'email_cliente',
        'tipo_entrega', 'direccion_entrega', 'notas',
        'subtotal', 'comision', 'total', 'estado',
        'mp_preference_id', 'mp_payment_id',
    ];

    public function items()
    {
        return $this->hasMany(PedidoItem::class);
    }
}
