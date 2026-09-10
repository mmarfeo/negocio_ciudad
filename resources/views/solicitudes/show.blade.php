<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $solicitud->nombre_negocio ?: 'Solicitud' }} — Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="container" style="max-width: 860px; margin-top: 2.5rem; margin-bottom: 3rem;">
    <a href="{{ route('solicitudes.index') }}" class="small text-muted">&larr; Todas las solicitudes</a>

    <div class="d-flex justify-content-between align-items-start mt-2 mb-4">
        <div>
            <h3 class="mb-0">{{ $solicitud->nombre_negocio ?: 'Sin nombre' }}</h3>
            <p class="text-muted small mb-0">
                Pedida por {{ $solicitud->user->name ?? '—' }}
                el {{ $solicitud->created_at?->format('d/m/Y H:i') }}
                · <span class="badge badge-secondary">{{ $solicitud->estadoLabel() }}</span>
            </p>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    {{-- ACCIÓN PRINCIPAL --}}
    <div class="card p-3 mb-4">
        @if ($solicitud->negocio_id)
            <p class="mb-2">Ya se creó el negocio a partir de esta solicitud.</p>
            <a href="{{ route('negocios.edit', $solicitud->negocio_id) }}" class="btn btn-accent">Abrir el negocio para ajustar / publicar</a>
        @else
            <p class="mb-2">Cuando tengas todo listo, esto crea el negocio precargado con estos datos y te lleva al formulario para terminar de ajustarlo.</p>
            <form method="POST" action="{{ route('solicitudes.crearNegocio', $solicitud) }}"
                  onsubmit="return confirm('¿Crear el negocio a partir de esta solicitud?');">
                @csrf
                <button type="submit" class="btn btn-accent">Crear negocio desde esta solicitud</button>
            </form>
        @endif
    </div>

    {{-- BRIEF --}}
    <h5>Datos del negocio</h5>
    <table class="table table-sm bg-white mb-4">
        <tbody>
            @foreach ($brief['negocio'] as $campo => $valor)
                <tr><th style="width: 220px;">{{ $campo }}</th><td>{{ $valor }}</td></tr>
            @endforeach
            <tr><th>Plantilla sugerida</th><td>{{ $brief['plantilla'] }}</td></tr>
            @if (!empty($brief['colores']))
                <tr><th>Colores</th><td><code>{{ json_encode($brief['colores']) }}</code></td></tr>
            @endif
        </tbody>
    </table>

    {{-- LOGO --}}
    @if (!empty($brief['logo']))
        <h5>Logo</h5>
        <p><img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($brief['logo']) }}"
                alt="logo" style="max-height: 120px; border: 1px solid #ddd; padding: 6px; background:#fff;"
                onerror="this.onerror=null;this.src='{{ asset('img/placeholder.svg') }}'"></p>
    @endif

    {{-- PRODUCTOS --}}
    @if (!empty($brief['productos']))
        <h5>Productos / servicios ({{ count($brief['productos']) }})</h5>
        <div class="row mb-4">
            @foreach ($brief['productos'] as $p)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        @if (!empty($p['imagen']))
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($p['imagen']) }}"
                                 class="card-img-top" alt="{{ $p['nombre'] ?? '' }}" style="height:140px;object-fit:cover;"
                                 onerror="this.onerror=null;this.src='{{ asset('img/placeholder.svg') }}'">
                        @endif
                        <div class="card-body p-2">
                            <strong class="small">{{ $p['nombre'] ?? '—' }}</strong>
                            @if (!empty($p['precio']))<div class="small text-muted">$ {{ $p['precio'] }}</div>@endif
                            @if (!empty($p['descripcion']))<div class="small">{{ $p['descripcion'] }}</div>@endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="row">
        {{-- NOTAS DEL DEV --}}
        <div class="col-md-7">
            <h5>Notas de trabajo</h5>
            <form method="POST" action="{{ route('solicitudes.notas', $solicitud) }}">
                @csrf
                <textarea name="notas_dev" rows="5" class="form-control mb-2">{{ $solicitud->notas_dev }}</textarea>
                <button class="btn btn-outline-secondary btn-sm">Guardar notas</button>
            </form>
        </div>

        {{-- ESTADO --}}
        <div class="col-md-5">
            <h5>Estado</h5>
            <form method="POST" action="{{ route('solicitudes.estado', $solicitud) }}">
                @csrf
                <select name="estado" class="form-control mb-2">
                    @foreach ($estados as $key => $label)
                        <option value="{{ $key }}" {{ $solicitud->estadoActual() === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-secondary btn-sm">Actualizar estado</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
