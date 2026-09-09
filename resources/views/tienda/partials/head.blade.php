{{--
    <head> compartido por las páginas secundarias de la plantilla "Tienda"
    (producto/carrito/checkout/resultado) -- el home (11-tienda.blade.php)
    tiene el suyo propio, casi idéntico, porque además arma la metadescripción
    a partir de datos que no siempre están disponibles acá. Requiere $negocio,
    $propiedades y $titulo.
--}}
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>{{ $titulo }} — {{ $negocio->nombre }}</title>
<link rel="icon" type="image/x-icon" href="{{ $propiedades->imagenUrl('favicon_logo') ?? asset('img/negocio.ico') }}"/>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
<link href="{{ asset('css/11-tienda/estilos.css') }}" rel="stylesheet">

@if ($propiedades->estiloPersonalizado())
    <style>{!! $propiedades->estiloPersonalizado() !!}</style>
@endif
