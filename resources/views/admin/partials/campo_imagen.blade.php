{{--
    Campo de imagen reutilizable (Fase 5, Paso 3): subir archivo o elegir
    una foto del banco propio. Requiere $campo (nombre del field) y $label.
    El modal/JS que abre la galería vive una sola vez al final de
    admin/negocio_form.blade.php. $urlActual es opcional: si no se pasa, se
    calcula de $propiedades (header/tarjetas); las fotos de producto (Etapa
    2 del marketplace) viven en otro lado (pivot negocio_producto), así que
    ahí el controller ya manda la URL resuelta.
--}}
@php
    $urlActual = $urlActual ?? ($propiedades->exists ? $propiedades->imagenUrl($campo) : null);
@endphp
<div class="form-group">
    <label>{{ $label }}</label>
    <div class="d-flex align-items-center flex-wrap" style="gap: .5rem;">
        <img data-preview-for="{{ $campo }}" src="{{ $urlActual ?: '#' }}" class="border rounded {{ $urlActual ? '' : 'd-none' }}" style="height: 48px; width: 48px; object-fit: cover;" alt="">
        <input type="file" name="{{ $campo }}" class="form-control-file campo-imagen-file" data-campo="{{ $campo }}" accept="image/*" style="width: auto;">
        <input type="hidden" name="galeria_{{ $campo }}" value="{{ old("galeria_{$campo}") }}" class="campo-imagen-galeria" data-campo="{{ $campo }}">
        <button type="button" class="btn btn-sm btn-outline-secondary btn-abrir-galeria" data-campo="{{ $campo }}">Elegir de la galería</button>
    </div>
</div>
