<?php

namespace App\Http\Controllers;

use App\Models\OnboardingSesion;
use App\Services\NegocioWriter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Fase 8, Bloque D -- modelo "página web a pedido".
 *
 * El chat de alta (ChatController) dejó de publicar: junta los datos y deja
 * una solicitud (OnboardingSesion en estado `enviada`). Este controlador es
 * las dos puntas de eso:
 *  - `mias()`  el usuario ve el estado de sus pedidos (solo 'auth').
 *  - el resto  el desarrollador gestiona las solicitudes ('auth'+'es_admin'):
 *              ve el brief, crea el negocio a partir de la solicitud, mueve
 *              el estado.
 */
class SolicitudWebController extends Controller
{
    private $writer;

    public function __construct(NegocioWriter $writer)
    {
        $this->writer = $writer;
    }

    /** Panel del usuario: el estado de sus propias solicitudes. */
    public function mias()
    {
        $solicitudes = OnboardingSesion::solicitudes()
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('solicitudes.mias', compact('solicitudes'));
    }

    /** Panel del desarrollador: todas las solicitudes, filtrables por estado. */
    public function index(Request $request)
    {
        $estado = $request->query('estado', 'enviada');

        $solicitudes = OnboardingSesion::solicitudes()
            ->when(in_array($estado, array_keys(OnboardingSesion::ESTADOS), true),
                fn ($q) => $q->conEstado($estado))
            ->with('user')
            ->latest()
            ->paginate(30)
            ->withQueryString();

        $conteos = OnboardingSesion::solicitudes()
            ->selectRaw("CASE WHEN estado = 'completado' THEN 'publicada' ELSE estado END AS e, COUNT(*) AS n")
            ->groupBy('e')
            ->pluck('n', 'e');

        return view('solicitudes.index', [
            'solicitudes' => $solicitudes,
            'estadoActivo' => $estado,
            'estados' => OnboardingSesion::ESTADOS,
            'conteos' => $conteos,
        ]);
    }

    /** El brief de una solicitud + acciones. */
    public function show(OnboardingSesion $solicitud)
    {
        abort_if($solicitud->enProgreso(), 404);

        return view('solicitudes.show', [
            'solicitud' => $solicitud,
            'brief' => $solicitud->brief(),
            'estados' => OnboardingSesion::ESTADOS,
        ]);
    }

    /** Crea el negocio real a partir de la solicitud y abre el form para ajustarlo. */
    public function crearNegocio(OnboardingSesion $solicitud)
    {
        abort_if($solicitud->enProgreso(), 404);

        if ($solicitud->negocio_id) {
            return redirect()->route('negocios.edit', $solicitud->negocio_id)
                ->with('status', 'Esta solicitud ya tenía un negocio creado.');
        }

        $negocio = $this->writer->publicarSolicitud($solicitud);

        return redirect()->route('negocios.edit', $negocio)
            ->with('status', 'Negocio creado desde la solicitud. Ajustá lo que falte y publicá.');
    }

    /** Mueve el estado de la solicitud (en_construccion / publicada / rechazada). */
    public function actualizarEstado(Request $request, OnboardingSesion $solicitud)
    {
        $datos = $request->validate([
            'estado' => ['required', Rule::in(array_keys(OnboardingSesion::ESTADOS))],
        ]);

        abort_if($solicitud->enProgreso(), 404);

        $solicitud->estado = $datos['estado'];
        $solicitud->save();

        return back()->with('status', 'Estado actualizado a "'.$solicitud->estadoLabel().'".');
    }

    public function guardarNotas(Request $request, OnboardingSesion $solicitud)
    {
        $datos = $request->validate(['notas_dev' => 'nullable|string|max:5000']);

        $solicitud->notas_dev = $datos['notas_dev'] ?: null;
        $solicitud->save();

        return back()->with('status', 'Notas guardadas.');
    }
}
