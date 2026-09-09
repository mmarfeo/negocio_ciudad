<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductoCatalogo extends Model
{
    protected $table = 'productos_catalogo';

    protected $fillable = ['nombre', 'slug', 'categoria', 'descripcion', 'imagen'];

    public function negocios()
    {
        return $this->belongsToMany(Product::class, 'negocio_producto', 'producto_id', 'negocio_id')
            ->withPivot('precio', 'disponible', 'imagen', 'descripcion', 'stock')
            ->withTimestamps();
    }

    /**
     * Busca un producto del catálogo por nombre (normalizado a slug) o lo
     * crea si no existe todavía. Evita duplicados tipo "Pizza" vs "pizzas".
     */
    public static function encontrarOCrear(string $nombre, ?string $categoria = null): self
    {
        $slug = Str::slug($nombre);

        return static::firstOrCreate(
            ['slug' => $slug],
            ['nombre' => trim($nombre), 'categoria' => $categoria]
        );
    }

    /**
     * Resuelve la URL pública de una foto de producto guardada en el pivot
     * `negocio_producto` (columna `imagen`, por-negocio) -- mismos dos
     * esquemas que `propiedades_plantillas::imagenUrl()` (storage nuevo o
     * banco de galería), sin el fallback a carpeta legacy: las fotos de
     * producto son de la Etapa 2 del marketplace, nunca existieron con el
     * esquema viejo de FTP. Usado desde controllers y directo en Blade
     * (grilla de la plantilla "Tienda"), por eso es estático.
     *
     * `onboarding/` es la carpeta temporal donde vive una foto de producto
     * recién subida DURANTE el chat, antes de que el negocio exista y se
     * "mude" a `negocios/{id}/...` (ver `ChatController::confirmar()`) --
     * mismo disco, incluirla acá es lo que permite que la vista previa en
     * vivo muestre la foto ya subida, no solo la página publicada.
     */
    public static function resolverImagenPivot(?string $valor): ?string
    {
        if (empty($valor)) {
            return null;
        }

        if (Str::startsWith($valor, ['negocios/', 'onboarding/'])) {
            return Storage::disk('public')->url($valor);
        }

        if (Str::startsWith($valor, 'galeria/')) {
            return asset("img/{$valor}");
        }

        return null;
    }
}
