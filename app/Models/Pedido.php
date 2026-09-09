<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'negocio_id', 'nombre_cliente', 'telefono_cliente', 'email_cliente',
        'tipo_entrega', 'direccion_entrega', 'notas',
        'subtotal', 'comision', 'total', 'estado',
        'mp_preference_id', 'mp_payment_id',
    ];

    public function negocio()
    {
        return $this->belongsTo(Product::class, 'negocio_id');
    }

    public function items()
    {
        return $this->hasMany(PedidoItem::class);
    }
}
