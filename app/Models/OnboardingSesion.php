<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnboardingSesion extends Model
{
    protected $table = 'onboarding_sesiones';

    protected $fillable = ['token', 'paso', 'estado', 'datos', 'productos', 'negocio_id'];

    protected $casts = [
        'datos' => 'array',
        'productos' => 'array',
    ];
}
