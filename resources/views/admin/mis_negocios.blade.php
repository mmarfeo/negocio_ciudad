<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis negocios — Negocios en tu Ciudad</title>
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
            <h3 class="mb-0">Hola, {{ auth()->user()->name }}</h3>
            <p class="text-muted small mb-0">Tus negocios</p>
        </div>
        <div class="text-right">
            @if (auth()->user()->es_admin)
                <a href="{{ route('panel.socios') }}" class="btn btn-outline-info btn-sm">Panel interno</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">Cerrar sesión</button>
            </form>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @forelse ($negocios as $negocio)
        <div class="card mb-3 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $negocio->nombre }}</strong>
                    <div class="text-muted small">{{ $negocio->ciudad }}</div>
                </div>
                <div>
                    <a href="{{ $negocio->urlPublica() }}" target="_blank" class="btn btn-outline-info btn-sm">Ver página</a>
                    <a href="{{ route('negocios.edit', $negocio) }}" class="btn btn-accent btn-sm">Editar</a>
                </div>
            </div>
        </div>
    @empty
        <div class="nc-empty">
            <p>Todavía no creaste ningún negocio.</p>
        </div>
    @endforelse

    <div class="text-center mt-4">
        <a href="{{ route('chat.negocio') }}" class="btn btn-accent">💬 Crear con el asistente</a>
        <a href="{{ route('negocios.create') }}" class="btn btn-outline-info">Crear con formulario</a>
    </div>
</div>
</body>
</html>
