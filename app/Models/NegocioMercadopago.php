<?php

namespace App\Models;

use App\Models\Concerns\PerteneceANegocio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

/**
 * Credenciales de Mercado Pago Connect (OAuth) de un negocio -- marketplace,
 * el cobro le llega directo a él. `access_token`/`refresh_token` se cifran
 * a mano con Crypt (Laravel 8 no tiene el cast `encrypted` nativo, llegó en
 * Laravel 9) para que no queden en texto plano en la base.
 */
class NegocioMercadopago extends Model
{
    use PerteneceANegocio;

    protected $table = 'negocio_mercadopago';

    protected $fillable = [
        'negocio_id', 'mp_user_id', 'public_key',
        'access_token', 'refresh_token', 'token_expires_at', 'conectado_en',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'conectado_en' => 'datetime',
    ];

    public function setAccessTokenAttribute(?string $valor): void
    {
        $this->attributes['access_token'] = $valor === null ? null : Crypt::encryptString($valor);
    }

    public function getAccessTokenAttribute(?string $valor): ?string
    {
        return $valor === null ? null : Crypt::decryptString($valor);
    }

    public function setRefreshTokenAttribute(?string $valor): void
    {
        $this->attributes['refresh_token'] = $valor === null ? null : Crypt::encryptString($valor);
    }

    public function getRefreshTokenAttribute(?string $valor): ?string
    {
        return $valor === null ? null : Crypt::decryptString($valor);
    }

    public function tokenVencido(): bool
    {
        return $this->token_expires_at !== null && $this->token_expires_at->isPast();
    }
}
