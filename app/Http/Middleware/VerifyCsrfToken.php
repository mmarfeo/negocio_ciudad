<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // Mercado Pago llama a esta URL directo, sin token CSRF -- la
        // seguridad la da la verificación de firma en
        // MercadoPagoService::verificarFirmaWebhook(), no Laravel.
        'webhooks/mercadopago/*',
    ];
}
