<?php

namespace App\Models;

use App\Support\Plantillas;
use Illuminate\Database\Eloquent\Model;

/**
 * El progreso del chat de alta (Fase 3) y, desde la Fase 8 (Bloque D),
 * también la "solicitud" que queda cuando el usuario termina el chat: el
 * chat ya no publica la página, junta los datos y el desarrollador la arma
 * después desde el panel (ver App\Http\Controllers\SolicitudWebController).
 */
class OnboardingSesion extends Model
{
    protected $table = 'onboarding_sesiones';

    protected $fillable = [
        'token', 'user_id', 'paso', 'estado', 'nombre_negocio', 'contacto',
        'plantilla_sugerida', 'datos', 'productos', 'notas_dev', 'negocio_id',
    ];

    protected $casts = [
        'datos' => 'array',
        'productos' => 'array',
        'plantilla_sugerida' => 'integer',
    ];

    /**
     * Pipeline de la solicitud. `en_progreso` = el chat todavía no terminó.
     * `completado` es el valor viejo (Fase 3, cuando el chat publicaba) --
     * se muestra como `publicada` (ver estadoActual()).
     */
    public const ESTADOS = [
        'enviada' => 'Enviada — esperando al desarrollador',
        'en_construccion' => 'En construcción',
        'publicada' => 'Publicada',
        'rechazada' => 'Rechazada',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function negocio()
    {
        return $this->belongsTo(Product::class, 'negocio_id');
    }

    /** Normaliza el valor legacy 'completado' a 'publicada'. */
    public function estadoActual(): string
    {
        return $this->estado === 'completado' ? 'publicada' : $this->estado;
    }

    public function estadoLabel(): string
    {
        return self::ESTADOS[$this->estadoActual()] ?? $this->estadoActual();
    }

    /** true mientras la conversación del chat sigue abierta. */
    public function enProgreso(): bool
    {
        return $this->estado === 'en_progreso';
    }

    /** Solicitudes ya enviadas por el usuario (no las conversaciones a medias). */
    public function scopeSolicitudes($query)
    {
        return $query->whereNotIn('estado', ['en_progreso']);
    }

    public function scopeConEstado($query, string $estado)
    {
        if ($estado === 'publicada') {
            return $query->whereIn('estado', ['publicada', 'completado']);
        }

        return $query->where('estado', $estado);
    }

    /**
     * Datos de la solicitud ordenados para el brief que ve el desarrollador
     * (App\Http\Controllers\SolicitudWebController / vista admin.solicitudes.show).
     */
    public function brief(): array
    {
        $d = $this->datos ?? [];
        $plantillaId = $this->plantilla_sugerida ?: ($d['plantilla_id'] ?? null);
        $plantilla = $plantillaId ? Plantillas::get((int) $plantillaId) : null;

        return [
            'negocio' => array_filter([
                'Nombre' => $d['nombre'] ?? $this->nombre_negocio,
                'Rubro / a qué se dedica' => $d['rubro'] ?? null,
                'Descripción' => $d['descripcion'] ?? null,
                'Dirección' => $d['direccion'] ?? null,
                'Ciudad' => $d['ciudad'] ?? null,
                'Zona / barrio' => $d['zona'] ?? null,
                'Teléfono' => $d['telefono'] ?? null,
                'Celular / WhatsApp' => $d['celular'] ?? null,
                'Email' => $d['email'] ?? null,
                'Instagram' => $d['instagram'] ?? null,
                'Facebook' => $d['facebook'] ?? null,
            ]),
            'plantilla' => $plantilla ? ($plantilla['label'].' (id '.$plantillaId.')') : 'Sin elegir',
            'colores' => $d['colores'] ?? null,
            'logo' => $d['logo_path'] ?? null,
            'productos' => $this->productos ?? [],
        ];
    }

    /**
     * Convierte lo juntado en la sesión (`datos` + `productos`) en los
     * arrays que espera App\Services\NegocioWriter -- sin el slug, que lo
     * calcula quien persiste (necesita chequear unicidad contra la base).
     *
     * Lo usan la vista previa del chat (ChatController::datosDelPaso, que
     * además le aplica overrides de plantilla/paleta sin persistir) y la
     * publicación real desde el panel del desarrollador
     * (NegocioWriter::publicarSolicitud).
     *
     * @return array{0: array<string,mixed>, 1: array<string,mixed>, 2: int, 3: array}
     */
    public function mapearParaWriter(?int $plantillaIdOverride = null): array
    {
        $d = $this->datos ?? [];

        $plantillaId = $plantillaIdOverride ?? (int) ($d['plantilla_id'] ?? 3);
        // Cae a "Productos" (3) si el id no es una plantilla ofrecida
        // (misma fuente única que valida el paso "plantilla" del chat).
        if (! Plantillas::esDisponible($plantillaId)) {
            $plantillaId = 3;
        }

        $datosNegocio = [
            'nombre' => $d['nombre'] ?? null,
            'plantilla_id' => $plantillaId,
            'rubro' => $d['rubro'] ?? null,
            // `profesion` es una columna separada de `rubro` (el form manual
            // las distingue), pero el chat pregunta una sola cosa -- algunas
            // plantillas (02, 05) muestran profesion en vez de rubro.
            'profesion' => $d['rubro'] ?? null,
            'direccion' => $d['direccion'] ?? null,
            'ciudad' => $d['ciudad'] ?? null,
            'zona' => $d['zona'] ?? null,
            'telefono' => $d['telefono'] ?? null,
            'celular' => $d['celular'] ?? null,
            'email' => $d['email'] ?? null,
        ];

        $datosPropiedades = [
            'body_titulo' => ! empty($d['nombre']) ? 'Bienvenidos a '.$d['nombre'] : null,
            'body_subtitulo' => $d['descripcion'] ?? null,
            'footer_redes_instagram' => $d['instagram'] ?? null,
            'footer_redes_facebook' => $d['facebook'] ?? null,
            'colores' => $d['colores'] ?? null,
        ];

        $productos = $this->productos ?? [];

        // Plantillas con `tarjetas_desde_productos` (hoy solo "Productos"):
        // llenan las primeras tarjetas visuales con los productos cargados.
        if (Plantillas::tarjetasDesdeProductos($plantillaId)) {
            foreach (array_slice($productos, 0, 8) as $indice => $producto) {
                $n = $indice + 1;
                $datosPropiedades["body_tarjeta_titulo_{$n}"] = $producto['nombre'];
                $datosPropiedades["body_tarjeta_precio_{$n}"] = $producto['precio'];
            }
        }

        return [$datosNegocio, $datosPropiedades, $plantillaId, $productos];
    }
}
