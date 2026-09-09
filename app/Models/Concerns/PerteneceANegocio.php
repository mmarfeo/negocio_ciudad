<?php

namespace App\Models\Concerns;

use App\Models\Product;

/**
 * Para los modelos con una columna `negocio_id` que apunta a un negocio
 * (tabla `negocios`, modelo App\Models\Product): Pedido,
 * NegocioMercadopago, propiedades_plantillas.
 *
 * Centraliza la relación `negocio()` -- que estaba copiada idéntica en
 * cada uno -- y agrega un scope `deNegocio()` para no volver a escribir
 * `where('negocio_id', $id)` a mano en controladores/servicios.
 *
 * NO es un "global scope" de multi-tenancy: en esta app el negocio se
 * resuelve de formas distintas según el contexto (la URL pública
 * /{ciudad}/{slug}, el route-model-binding del panel, el pedido en la URL
 * del webhook de Mercado Pago), así que el filtrado se pide explícito con
 * `->deNegocio($negocio)` donde hace falta, o vía las relaciones de
 * Product (`$negocio->pedidos`, `$negocio->propiedades`, `$negocio->mercadopago`).
 */
trait PerteneceANegocio
{
    public function negocio()
    {
        return $this->belongsTo(Product::class, 'negocio_id');
    }

    /**
     * Filtra por negocio. Acepta un modelo Product o un id.
     *
     * @param  \App\Models\Product|int  $negocio
     */
    public function scopeDeNegocio($query, $negocio)
    {
        $id = $negocio instanceof Product ? $negocio->getKey() : $negocio;

        return $query->where($this->qualifyColumn('negocio_id'), $id);
    }
}
