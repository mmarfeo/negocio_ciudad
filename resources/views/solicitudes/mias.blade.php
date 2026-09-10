<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis solicitudes — Negocios en tu Ciudad</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="container" style="max-width: 720px; margin-top: 2.5rem; margin-bottom: 3rem;">
    <div class="mb-4">
        <a href="/inicio"><img src="{{ asset('img/logo.png') }}" height="40" alt="Negocios en tu Ciudad"></a>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Mis solicitudes</h3>
            <p class="text-muted small mb-0">Estado de las páginas que pediste</p>
        </div>
        <a href="{{ route('negocios.mios') }}" class="btn btn-outline-secondary btn-sm">Mis negocios</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @forelse ($solicitudes as $s)
        <div class="card mb-3 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $s->nombre_negocio ?: 'Sin nombre' }}</strong>
                    <div class="text-muted small">Pedida el {{ $s->created_at?->format('d/m/Y') }}</div>
                </div>
                <div class="text-right">
                    @php
                        $badge = ['enviada' => 'secondary', 'en_construccion' => 'info', 'publicada' => 'success', 'rechazada' => 'danger'][$s->estadoActual()] ?? 'secondary';
                    @endphp
                    <span class="badge badge-{{ $badge }}">{{ $s->estadoLabel() }}</span>
                    @if ($s->estadoActual() === 'publicada' && $s->negocio)
                        <div class="mt-2">
                            <a href="{{ $s->negocio->urlPublica() }}" target="_blank" class="btn btn-outline-info btn-sm">Ver mi página</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="nc-empty">
            <p>Todavía no enviaste ninguna solicitud.</p>
            <a href="{{ route('chat.negocio') }}" class="btn btn-accent">💬 Pedir mi página con el asistente</a>
        </div>
    @endforelse
</div>
</body>
</html>
