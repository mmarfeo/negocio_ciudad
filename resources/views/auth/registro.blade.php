<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Creá tu cuenta — Negocios en tu Ciudad</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="container" style="max-width: 560px; margin-top: 3rem; margin-bottom: 3rem;">
    <div class="text-center mb-4">
        <a href="/inicio"><img src="{{ asset('img/logo.png') }}" height="60" alt="Negocios en tu Ciudad"></a>
        <h3 class="mt-2">Creá tu cuenta de dueño de negocio</h3>
        <p class="text-muted small">La necesitás para crear y editar la página de tu negocio.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('registro.enviar') }}" class="card p-4" style="border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Nombre *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="form-group col-md-6">
                <label>Apellido *</label>
                <input type="text" name="apellido" class="form-control" value="{{ old('apellido') }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>DNI *</label>
                <input type="text" name="dni" class="form-control" value="{{ old('dni') }}" required>
            </div>
            <div class="form-group col-md-6">
                <label>Teléfono *</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" required>
            </div>
        </div>
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
            <label>Nombre de tu negocio (opcional)</label>
            <input type="text" name="nombre_negocio" class="form-control" value="{{ old('nombre_negocio') }}">
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Contraseña *</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group col-md-6">
                <label>Repetir contraseña *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-accent btn-block mt-2">Crear cuenta</button>
    </form>

    <p class="text-center mt-3">
        ¿Ya tenés cuenta? <a href="{{ route('login') }}">Iniciar sesión</a>
    </p>
</div>
</body>
</html>
