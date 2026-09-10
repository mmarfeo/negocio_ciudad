<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitudes de página — Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="container" style="max-width: 960px; margin-top: 2.5rem; margin-bottom: 3rem;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Solicitudes de página</h3>
            <p class="text-muted small mb-0">Pedidos del chat de alta — panel del desarrollador</p>
        </div>
        <div>
            <a href="{{ route('negocios.create') }}" class="btn btn-outline-info btn-sm">+ Alta manual</a>
            <a href="{{ route('panel.socios') }}" class="btn btn-outline-secondary btn-sm">Panel interno</a>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <ul class="nav nav-pills mb-3">
        @foreach ($estados as $key => $label)
            <li class="nav-item">
                <a class="nav-link {{ $estadoActivo === $key ? 'active' : '' }}"
                   href="{{ route('solicitudes.index', ['estado' => $key]) }}">
                    {{ \Illuminate\Support\Str::of($label)->before('—')->trim() }}
                    <span class="badge badge-light">{{ $conteos[$key] ?? 0 }}</span>
                </a>
            </li>
        @endforeach
    </ul>

    <table class="table table-hover bg-white">
        <thead>
            <tr>
                <th>Negocio</th>
                <th>Solicitante</th>
                <th>Contacto</th>
                <th>Pedida</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($solicitudes as $s)
                <tr>
                    <td>
                        <strong>{{ $s->nombre_negocio ?: 'Sin nombre' }}</strong>
                        @if ($s->plantilla_sugerida)
                            <span class="text-muted small">· plantilla {{ $s->plantilla_sugerida }}</span>
                        @endif
                    </td>
                    <td>{{ $s->user->name ?? '—' }}</td>
                    <td class="small">{{ $s->contacto ?: '—' }}</td>
                    <td class="small">{{ $s->created_at?->format('d/m/Y') }}</td>
                    <td class="text-right">
                        <a href="{{ route('solicitudes.show', $s) }}" class="btn btn-accent btn-sm">Abrir</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No hay solicitudes en este estado.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $solicitudes->links() }}
</div>
</body>
</html>
