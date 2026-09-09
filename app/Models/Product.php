<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
        public $table = "negocios";
        public $primaryKey = "id";
        // La tabla real (creada a mano, no por migración) no tiene columnas
        // created_at/updated_at -- si Eloquent intenta escribirlas, el INSERT
        // falla con "Unknown column 'updated_at'".
        public $timestamps = false;

        protected $fillable = [
              'plantilla_id', 'url', 'activo', 'nombre', 'profesion', 'slug', 'cuit', 'telefono', 'celular', 'horario', 'direccion', 'email', 'ciudad' , 'ciudad_slug' , 'provincia' , 'producto_servicio' , 'rubro' , 'zona' , 'palabras_clave', 'dir_carpeta', 'nav_logo', 'user_id'
        ];

        public function propiedades()
        {
            return $this->hasOne(propiedades_plantillas::class, 'negocio_id');
        }

        /**
         * Normaliza un nombre de ciudad al segmento que va en la URL
         * pública (`/{ciudad}/{slug}`). Negocios sin ciudad cargada usan
         * 'sin-ciudad' en vez de bloquear la publicación (Fase 4).
         */
        public static function normalizarCiudadSlug(?string $ciudad): string
        {
            return Str::slug((string) $ciudad) ?: 'sin-ciudad';
        }

        /**
         * URL pública del negocio, con ciudad (Fase 4). Reemplaza al viejo
         * esquema de `{$product->url}{$product->slug}` armado a mano en las
         * vistas/controllers.
         */
        public function urlPublica(): string
        {
            $ciudadSlug = $this->ciudad_slug ?: self::normalizarCiudadSlug($this->ciudad);

            return '/'.$ciudadSlug.'/'.$this->slug;
        }

        public function productos()
        {
            // imagen/descripcion/stock (Etapa 2 del marketplace, plantilla
            // "Tienda"): a diferencia de nombre/slug/categoría, que son del
            // catálogo compartido (`productos_catalogo`), estos tres son
            // por-negocio en el pivot, igual que ya eran precio/disponible.
            return $this->belongsToMany(ProductoCatalogo::class, 'negocio_producto', 'negocio_id', 'producto_id')
                ->withPivot('precio', 'disponible', 'imagen', 'descripcion', 'stock')
                ->withTimestamps();
        }

        /**
         * URL del logo chico usado en la tarjeta del index. Soporta el
         * esquema nuevo (storage, "negocios/{id}/...") y el legacy (ruta
         * de asset ya armada a mano, ej. "img/02-independiente/Foo/logo.png").
         */
        public function navLogoUrl(): string
        {
            if (empty($this->nav_logo)) {
                return asset('img/negocio.png');
            }

            if (Str::startsWith($this->nav_logo, 'negocios/')) {
                return Storage::disk('public')->url($this->nav_logo);
            }

            return asset($this->nav_logo);
        }
    }

