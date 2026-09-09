<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    protected $fillable = [
        'pedido_id', 'producto_id', 'nombre_producto', 'precio_unitario', 'cantidad', 'subtotal',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function producto()
    {
        return $this->belongsTo(ProductoCatalogo::class, 'producto_id');
    }
}
