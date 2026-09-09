{{--
    Pie de página compartido por las 5 páginas de la plantilla "Tienda".
    Requiere $negocio. Antes el teléfono/celular y las redes sociales se
    guardaban por chat (pasos "contacto"/"redes") pero no se mostraban en
    ningún lado de esta plantilla -- hallazgo del dueño del proyecto,
    corregido acá.
--}}
@php
    $propiedadesFooter = $negocio->propiedades;
    $telefonoContacto = $negocio->telefono ?: $negocio->celular;
    $redesFooter = $propiedadesFooter ? array_filter([
        'instagram' => $propiedadesFooter->footer_redes_instagram,
        'facebook' => $propiedadesFooter->footer_redes_facebook,
        'twitter' => $propiedadesFooter->footer_redes_twitter,
        'youtube' => $propiedadesFooter->footer_redes_youtube,
        'linkedin' => $propiedadesFooter->footer_redes_linkedin,
    ]) : [];
@endphp
<footer class="td-footer">
    <div class="td-wrap td-footer__row">
        <div>
            <div class="td-footer__brand">{{ $negocio->nombre }}</div>
            @if ($negocio->direccion || $telefonoContacto)
                <div class="td-footer__meta">
                    {{ $negocio->direccion }}
                    @if ($negocio->direccion && $telefonoContacto) &middot; @endif
                    @if ($telefonoContacto)
                        <a href="tel:{{ $telefonoContacto }}">{{ $telefonoContacto }}</a>
                    @endif
                </div>
            @endif
        </div>

        @if (! empty($redesFooter))
            <div class="td-social">
                @if ($redesFooter['instagram'] ?? null)
                    <a href="{{ $redesFooter['instagram'] }}" target="_blank" rel="noopener" aria-label="Instagram">
                        <svg viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.6 0 4.9.07 1.2.06 2.1.26 2.6.5.7.3 1.2.6 1.7 1.1.5.5.8 1 1.1 1.7.2.5.4 1.4.5 2.6.06 1.3.07 1.7.07 4.9s0 3.6-.07 4.9c-.06 1.2-.26 2.1-.5 2.6-.3.7-.6 1.2-1.1 1.7-.5.5-1 .8-1.7 1.1-.5.2-1.4.4-2.6.5-1.3.06-1.7.07-4.9.07s-3.6 0-4.9-.07c-1.2-.06-2.1-.26-2.6-.5-.7-.3-1.2-.6-1.7-1.1-.5-.5-.8-1-1.1-1.7-.2-.5-.4-1.4-.5-2.6C2.2 15.6 2.2 15.2 2.2 12s0-3.6.07-4.9c.06-1.2.26-2.1.5-2.6.3-.7.6-1.2 1.1-1.7.5-.5 1-.8 1.7-1.1.5-.2 1.4-.4 2.6-.5C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.15 0-3.52 0-4.76.07-1 .05-1.55.21-1.9.35-.48.19-.82.41-1.18.77-.36.36-.58.7-.77 1.18-.14.35-.3.9-.35 1.9C3 9.28 3 9.65 3 12.8v-1.6c0 3.15 0 3.52.07 4.76.05 1 .21 1.55.35 1.9.19.48.41.82.77 1.18.36.36.7.58 1.18.77.35.14.9.3 1.9.35 1.24.06 1.61.07 4.76.07s3.52 0 4.76-.07c1-.05 1.55-.21 1.9-.35.48-.19.82-.41 1.18-.77.36-.36.58-.7.77-1.18.14-.35.3-.9.35-1.9.06-1.24.07-1.61.07-4.76s0-3.52-.07-4.76c-.05-1-.21-1.55-.35-1.9-.19-.48-.41-.82-.77-1.18a3.2 3.2 0 0 0-1.18-.77c-.35-.14-.9-.3-1.9-.35C15.52 4 15.15 4 12 4zm0 3.4a4.6 4.6 0 1 1 0 9.2 4.6 4.6 0 0 1 0-9.2zm0 1.8a2.8 2.8 0 1 0 0 5.6 2.8 2.8 0 0 0 0-5.6zm4.8-2a1.08 1.08 0 1 1 0 2.16 1.08 1.08 0 0 1 0-2.16z"/></svg>
                    </a>
                @endif
                @if ($redesFooter['facebook'] ?? null)
                    <a href="{{ $redesFooter['facebook'] }}" target="_blank" rel="noopener" aria-label="Facebook">
                        <svg viewBox="0 0 24 24"><path d="M13.5 21v-7.9h2.66l.4-3.09h-3.06V8.05c0-.9.25-1.5 1.53-1.5h1.63V3.8c-.28-.04-1.25-.12-2.37-.12-2.35 0-3.96 1.43-3.96 4.06v2.27H7.7v3.09h2.63V21h3.17z"/></svg>
                    </a>
                @endif
                @if ($redesFooter['twitter'] ?? null)
                    <a href="{{ $redesFooter['twitter'] }}" target="_blank" rel="noopener" aria-label="Twitter / X">
                        <svg viewBox="0 0 24 24"><path d="M18.9 3H21l-6.5 7.4L22 21h-6.4l-5-6.4-5.7 6.4H2.7l6.9-7.9L2 3h6.6l4.5 5.9L18.9 3zm-1.1 16h1.6L7.3 4.9H5.6L17.8 19z"/></svg>
                    </a>
                @endif
                @if ($redesFooter['youtube'] ?? null)
                    <a href="{{ $redesFooter['youtube'] }}" target="_blank" rel="noopener" aria-label="YouTube">
                        <svg viewBox="0 0 24 24"><path d="M22 12s0-3.1-.4-4.6a2.8 2.8 0 0 0-2-2C17.9 5 12 5 12 5s-5.9 0-7.6.4a2.8 2.8 0 0 0-2 2C2 8.9 2 12 2 12s0 3.1.4 4.6c.2 1 1 1.7 2 2C6.1 19 12 19 12 19s5.9 0 7.6-.4a2.8 2.8 0 0 0 2-2C22 15.1 22 12 22 12zM10 15.5v-7l6 3.5-6 3.5z"/></svg>
                    </a>
                @endif
                @if ($redesFooter['linkedin'] ?? null)
                    <a href="{{ $redesFooter['linkedin'] }}" target="_blank" rel="noopener" aria-label="LinkedIn">
                        <svg viewBox="0 0 24 24"><path d="M6.94 8.5H3.56V20h3.38V8.5zM5.25 3.5a1.96 1.96 0 1 0 0 3.92 1.96 1.96 0 0 0 0-3.92zM20.5 20h-3.37v-5.9c0-1.4-.03-3.2-1.95-3.2-1.96 0-2.26 1.53-2.26 3.1V20H9.55V8.5h3.24v1.57h.05c.45-.86 1.56-1.77 3.21-1.77 3.43 0 4.06 2.26 4.06 5.2V20z"/></svg>
                    </a>
                @endif
            </div>
        @endif
    </div>
</footer>
