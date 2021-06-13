<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
        public $table = "negocios";
        public $primaryKey = "id";
  
        protected $fillable = [
              'plantilla_id', 'url', 'activo', 'nombre', 'profesion', 'slug', 'cuit', 'telefono', 'celular', 'horario', 'direccion', 'email', 'ciudad' , 'provincia' , 'producto_servicio' , 'rubro' , 'zona' , 'palabras_clave'
        ];
    }

