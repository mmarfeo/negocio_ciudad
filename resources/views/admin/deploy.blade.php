<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Desplegar assets — Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="container" style="max-width: 720px; margin-top: 2.5rem; margin-bottom: 3rem;">
    <a href="{{ route('solicitudes.index') }}" class="small text-muted">&larr; Panel</a>

    <h3 class="mt-2 mb-1">Desplegar assets</h3>
    <p class="text-muted small">
        Copia <code>negocio_ciudad/public/{css,js,img,fonts,webfonts,sass}</code> a
        <code>public_html/</code> — el paso manual que históricamente se olvidaba y
        dejaba el CSS/JS viejo en producción.
    </p>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first('deploy') }}</div>
    @endif

    @if (session('resultado'))
        @php $r = session('resultado'); @endphp
        <div class="alert alert-success">
            <strong>Listo.</strong> Total copiado: {{ $r['total'] }} archivos.
            <ul class="mb-0 small">
                @foreach ($r['carpetas'] as $carpeta => $cantidad)
                    <li>{{ $carpeta }}/: {{ $cantidad }}</li>
                @endforeach
                @foreach ($r['archivos_raiz'] as $archivo)
                    <li>{{ $archivo }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (! $destinoConfigurado)
        <div class="alert alert-warning">
            Falta configurar <code>PUBLIC_HTML_PATH</code> en el <code>.env</code> de producción
            (la misma ruta que <code>PUBLIC_STORAGE_PATH</code>, sin el <code>/storage</code> final).
        </div>
    @else
        <form method="POST" action="{{ route('deploy.assets') }}"
              onsubmit="return confirm('¿Copiar los assets a public_html/? Sobrescribe los archivos que ya estén ahí.');">
            @csrf
            <button type="submit" class="btn btn-accent">Copiar assets a public_html/ ahora</button>
        </form>
    @endif
</div>
</body>
</html>
