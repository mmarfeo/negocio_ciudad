<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class propiedades_plantillas extends Model
{
    public $table = "plantillas_propiedades";
    public $primaryKey = "id";
    // Misma tabla "hecha a mano" que negocios -- probablemente tampoco
    // tiene created_at/updated_at. Se desactiva por las dudas, mismo
    // motivo que en App\Models\Product.
    public $timestamps = false;

    protected $fillable = [

        'plantilla_id',	'negocio_id',	'nav_logo',	'favicon_logo',	'header_img_1',	'header_img_2',	'header_img_3',	'header_titulo_1',	'header_titulo_2',	'header_titulo_3',	'header_subtitulo_1',	'header_subtitulo_2',	'header_subtitulo_3',	'body_titulo',	'body_titulo_2',	'body_titulo_3',	'body_titulo_4',	'body_parrafo_1',	'body_parrafo_2',	'body_parrafo_3',	'body_parrafo_4',	'body_subtitulo',	'body_tarjeta_img_1',	'body_tarjeta_img_2',	'body_tarjeta_img_3',	'body_tarjeta_img_4',	'body_tarjeta_img_5',	'body_tarjeta_img_6',	'body_tarjeta_img_7',	'body_tarjeta_img_8',	'body_tarjeta_img_9',	'body_tarjeta_img_10',	'body_tarjeta_titulo_1',	'body_tarjeta_titulo_2',	'body_tarjeta_titulo_3',	'body_tarjeta_titulo_4',	'body_tarjeta_titulo_5',	'body_tarjeta_titulo_6',	'body_tarjeta_titulo_7',	'body_tarjeta_titulo_8',	'body_tarjeta_parrafo_1',	'body_tarjeta_parrafo_2',	'body_tarjeta_parrafo_3',	'body_tarjeta_parrafo_4',	'body_tarjeta_parrafo_5',	'body_tarjeta_parrafo_6',	'body_tarjeta_parrafo_7',	'body_tarjeta_parrafo_8',	'body_tarjeta_precio_1',	'body_tarjeta_precio_2',	'body_tarjeta_precio_3',	'body_tarjeta_precio_4',	'body_tarjeta_precio_5',	'body_tarjeta_precio_6',	'body_tarjeta_precio_7',	'body_tarjeta_precio_8',	'body_titulo_nuestros_trabajos',	'body_tipos_medios_pago',	'body_maps',	'footer_redes_facebook',	'footer_redes_instagram',	'footer_redes_youtube',	'footer_redes_twitter',	'footer_redes_linkedin',	'color_fondo',	'color_texto',	'colores'

    ];

    protected $casts = [
        'colores' => 'array',
    ];

    /**
     * Selectores CSS de cada zona editable (header/body/footer) por
     * plantilla -- cada una tiene una estructura de HTML distinta, así que
     * "header" o "footer" no apuntan al mismo elemento en las 4. Fase 5,
     * Paso 2 (colores por sección).
     */
    private const ZONAS_CSS_POR_PLANTILLA = [
        2 => ['header' => '#mainNav, header.masthead', 'body' => 'body, section.portfolio', 'footer' => 'footer.footer, .copyright'],
        3 => ['header' => 'nav.navbar', 'body' => 'body, .jumbotron', 'footer' => 'footer'],
        4 => ['header' => 'header.hero', 'body' => 'body', 'footer' => 'footer'],
        5 => ['header' => 'nav.navbar', 'body' => 'header.masthead', 'footer' => '.copyright'],
        10 => ['header' => '.vd-nav', 'body' => 'body#plantilla_vidriera', 'footer' => '.vd-footer'],
        11 => ['header' => '.td-nav', 'body' => 'body#plantilla_tienda', 'footer' => '.td-footer'],
    ];

    public const ZONAS = ['header' => 'Encabezado', 'body' => 'Cuerpo', 'footer' => 'Pie de página'];

    /**
     * Carpeta legacy en public/img/ para cada plantilla (esquema viejo:
     * img/{carpeta}/{negocio->dir_carpeta}/{archivo}, subido a mano por FTP).
     */
    protected const CARPETAS_LEGACY = [
        2 => '02-independiente',
        3 => '03-productos',
        4 => '04-servicios',
        5 => '05-tarjeta',
    ];

    public function negocio()
    {
        return $this->belongsTo(Product::class, 'negocio_id');
    }

    /**
     * Resuelve la URL pública de un campo de imagen, sea que haya sido
     * subido con el sistema de storage nuevo (ruta "negocios/{id}/...")
     * o que todavía tenga el nombre de archivo suelto del esquema legacy.
     */
    public function imagenUrl(string $campo): ?string
    {
        $valor = $this->{$campo};

        if (empty($valor)) {
            return null;
        }

        if (Str::startsWith($valor, 'negocios/')) {
            return Storage::disk('public')->url($valor);
        }

        // Fase 5, Paso 3: foto elegida del banco propio en vez de subida.
        if (Str::startsWith($valor, 'galeria/')) {
            return asset("img/{$valor}");
        }

        $negocio = $this->negocio;
        $carpeta = self::CARPETAS_LEGACY[$this->plantilla_id] ?? null;

        if (! $negocio || ! $carpeta || empty($negocio->dir_carpeta)) {
            return null;
        }

        return asset("img/{$carpeta}/{$negocio->dir_carpeta}/{$valor}");
    }

    /**
     * CSS suelto (sin <style>) para pisar los colores elegidos por sección
     * (header/body/footer, ver ZONAS_CSS_POR_PLANTILLA). Null si el negocio
     * no personalizó nada -- en ese caso la plantilla se ve exactamente
     * como siempre.
     *
     * Fase 5, Paso 2: antes esto era un solo color de fondo/letra para toda
     * la página (columnas `color_fondo`/`color_texto`). Ahora se guarda por
     * zona en la columna JSON `colores`. Si un negocio ya eligió colores
     * con el sistema viejo y todavía no pasó por el nuevo, `colores` viene
     * vacío y se cae a esos dos campos aplicados a todo el body -- se
     * siguen viendo exactamente igual que antes de este cambio.
     */
    public function estiloPersonalizado(): ?string
    {
        $colores = $this->colores;

        if (empty($colores) || ! is_array($colores)) {
            return $this->estiloPersonalizadoLegacy();
        }

        $zonasCss = self::ZONAS_CSS_POR_PLANTILLA[$this->plantilla_id] ?? null;
        if (! $zonasCss) {
            return $this->estiloPersonalizadoLegacy();
        }

        $bloques = [];

        foreach (self::ZONAS as $zona => $label) {
            $colorZona = $colores[$zona] ?? null;
            $selector = $zonasCss[$zona] ?? null;
            if (empty($colorZona) || ! $selector) {
                continue;
            }

            $reglas = [];
            if (! empty($colorZona['fondo'])) {
                $reglas[] = 'background-color: '.$colorZona['fondo'].' !important;';
            }
            if (! empty($colorZona['texto'])) {
                $reglas[] = 'color: '.$colorZona['texto'].' !important;';
            }

            if ($reglas) {
                $bloques[] = $selector.' { '.implode(' ', $reglas).' }';
            }
        }

        return $bloques ? implode("\n", $bloques) : null;
    }

    /**
     * Comportamiento previo a Fase 5 Paso 2: un solo color de fondo/letra
     * para toda la página, vía las columnas sueltas `color_fondo`/`color_texto`.
     */
    private function estiloPersonalizadoLegacy(): ?string
    {
        if (empty($this->color_fondo) && empty($this->color_texto)) {
            return null;
        }

        $reglas = [];

        if (! empty($this->color_fondo)) {
            $reglas[] = 'background-color: '.$this->color_fondo.' !important;';
        }
        if (! empty($this->color_texto)) {
            $reglas[] = 'color: '.$this->color_texto.' !important;';
        }

        return 'body { '.implode(' ', $reglas).' }';
    }
}
