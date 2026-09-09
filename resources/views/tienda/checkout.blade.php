<!DOCTYPE html>
<html lang="es">

<head>
    @include('tienda.partials.head', ['negocio' => $negocio, 'propiedades' => $propiedades, 'titulo' => 'Finalizar compra'])
</head>

<body id="plantilla_tienda">

    @include('tienda.partials.nav', ['negocio' => $negocio])

    <section class="td-section">
        <div class="td-wrap">
            <h1 style="margin-bottom:1.5rem;">Finalizar compra</h1>

            @if ($errors->any())
                <div class="td-alert td-alert--error">
                    <ul style="margin:0; padding-left:1.1rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="td-cart-layout">
                <form method="POST" action="{{ route('tienda.procesarCheckout', [$negocio->ciudad_slug, $negocio->slug]) }}">
                    @csrf
                    <div class="td-form-group">
                        <label for="nombre_cliente">Nombre y apellido *</label>
                        <input type="text" id="nombre_cliente" name="nombre_cliente" value="{{ old('nombre_cliente') }}" required>
                    </div>
                    <div class="td-form-group">
                        <label for="telefono_cliente">Teléfono / WhatsApp *</label>
                        <input type="text" id="telefono_cliente" name="telefono_cliente" value="{{ old('telefono_cliente') }}" required>
                    </div>
                    <div class="td-form-group">
                        <label for="email_cliente">Email (opcional)</label>
                        <input type="email" id="email_cliente" name="email_cliente" value="{{ old('email_cliente') }}">
                    </div>
                    <div class="td-form-group">
                        <label>Entrega *</label>
                        <div class="td-radio-group">
                            <label><input type="radio" name="tipo_entrega" value="retiro" {{ old('tipo_entrega', 'retiro') === 'retiro' ? 'checked' : '' }} onchange="document.getElementById('campo-direccion').style.display='none'"> Retiro en el local</label>
                            <label><input type="radio" name="tipo_entrega" value="envio" {{ old('tipo_entrega') === 'envio' ? 'checked' : '' }} onchange="document.getElementById('campo-direccion').style.display='block'"> Envío</label>
                        </div>
                    </div>
                    <div class="td-form-group" id="campo-direccion" style="display: {{ old('tipo_entrega') === 'envio' ? 'block' : 'none' }};">
                        <label for="direccion_entrega">Dirección de envío</label>
                        <input type="text" id="direccion_entrega" name="direccion_entrega" value="{{ old('direccion_entrega') }}">
                        <p class="td-card__stock" style="margin-top:.4rem;">El costo de envío se coordina directo con el negocio, no está incluido en el total.</p>
                    </div>
                    <div class="td-form-group">
                        <label for="notas">Notas para el negocio (opcional)</label>
                        <textarea id="notas" name="notas" rows="3">{{ old('notas') }}</textarea>
                    </div>

                    <button type="submit" class="td-btn td-btn--block">
                        {{ $mercadoPagoDisponible ? 'Ir a pagar con Mercado Pago' : 'Confirmar pedido' }}
                    </button>
                    <p class="td-card__stock" style="margin-top:.75rem; text-align:center;">
                        @if ($mercadoPagoDisponible)
                            Después de confirmar te llevamos a Mercado Pago para pagar. El envío se coordina aparte con el negocio.
                        @else
                            Por ahora el pago se coordina directo con el negocio (todavía no hay cobro online en este catálogo) — al confirmar, tu pedido queda registrado.
                        @endif
                    </p>
                </form>

                <div class="td-cart-summary">
                    <h3 style="margin-bottom:1rem;">Tu pedido</h3>
                    @foreach ($items as $item)
                        <div class="td-cart-summary__row">
                            <span>{{ $item['cantidad'] }}× {{ $item['nombre'] }}</span>
                            <span>$ {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <div class="td-cart-summary__row td-cart-summary__row--total">
                        <span>Total</span>
                        <span>$ {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('tienda.partials.footer', ['negocio' => $negocio])

</body>

</html>
