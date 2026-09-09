<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\NegocioAdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PanelInternoController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\MercadoPagoWebhookController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
});
 */

/* Route::get('/', function () {
    return view('index');
}); */


Route::get('/planes', function () {
    return view('planes');
});



Route::get('/', [ProductController::class, 'listado']);
Route::get('/inicio', [ProductController::class, 'listado']);

// Login casero (Fase 3.5) -- sin laravel/ui/breeze, ver AuthController.
Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
Route::post('/registro', [AuthController::class, 'registrar'])->name('registro.enviar');
Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.enviar');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Alta/edición de negocios y chat con Gemini: a partir de acá, solo dueños
// registrados. Tiene que ir antes del catch-all de más abajo.
Route::middleware('auth')->group(function () {
    Route::get('/mis-negocios', [NegocioAdminController::class, 'misNegocios'])->name('negocios.mios');
    Route::get('/admin/negocios/crear', [NegocioAdminController::class, 'create'])->name('negocios.create');
    Route::post('/admin/negocios', [NegocioAdminController::class, 'store'])->name('negocios.store');
    Route::get('/admin/negocios/{negocio}/editar', [NegocioAdminController::class, 'edit'])->name('negocios.edit');
    Route::put('/admin/negocios/{negocio}', [NegocioAdminController::class, 'update'])->name('negocios.update');
    // Banco propio de fotos para elegir por sección (Fase 5, Paso 3).
    Route::get('/admin/galeria/{categoria}', [NegocioAdminController::class, 'galeria'])->name('negocios.galeria');
    // Marketplace con Mercado Pago Connect (plantilla "Tienda"): conexión
    // OAuth de la cuenta de MP del negocio -- ver App\Services\MercadoPagoService.
    Route::get('/admin/negocios/{negocio}/mercadopago/conectar', [MercadoPagoController::class, 'conectar'])->name('mp.conectar');
    Route::get('/admin/negocios/{negocio}/mercadopago/callback', [MercadoPagoController::class, 'callback'])->name('mp.callback');
    Route::delete('/admin/negocios/{negocio}/mercadopago', [MercadoPagoController::class, 'desconectar'])->name('mp.desconectar');
    // Texto sugerido por LLM (Fase 5, Paso 5) desde el formulario manual.
    // Mismo throttle que el chat, es la misma cuota gratuita de Gemini.
    Route::middleware('throttle:20,1')->post('/admin/negocios/sugerir-texto', [NegocioAdminController::class, 'sugerirTexto'])->name('negocios.sugerirTexto');

    // Chat de alta con Gemini (Fase 3). throttle para cuidar la cuota gratuita.
    Route::get('/chat/negocio', [ChatController::class, 'iniciar'])->name('chat.negocio');
    Route::middleware('throttle:20,1')->group(function () {
        Route::post('/chat/negocio/mensaje', [ChatController::class, 'mensaje'])->name('chat.mensaje');
        Route::post('/chat/negocio/confirmar', [ChatController::class, 'confirmar'])->name('chat.confirmar');
        Route::post('/chat/negocio/logo', [ChatController::class, 'subirLogo'])->name('chat.logo');
        // Foto opcional para el último producto cargado en el paso "productos".
        Route::post('/chat/negocio/producto-foto', [ChatController::class, 'subirFotoProducto'])->name('chat.productoFoto');
        // Vista previa en vivo (Fase 4): se pide una vez por paso, mismo
        // ritmo que /mensaje, así que comparte el throttle.
        Route::get('/chat/negocio/preview/{token}', [ChatController::class, 'preview'])->name('chat.preview');
        // Texto sugerido por LLM (Fase 5, Paso 5) desde el chat.
        Route::post('/chat/negocio/sugerir-texto', [ChatController::class, 'sugerirTexto'])->name('chat.sugerirTexto');
    });
});

// Panel interno (solo admin de la plataforma, no dueños de negocio comunes)
// -- documentación y roadmap para prepararse antes de hablar con socios.
Route::middleware(['auth', 'es_admin'])->group(function () {
    Route::get('/panel-socios', [PanelInternoController::class, 'index'])->name('panel.socios');
});

// Webhook de Mercado Pago (marketplace, ver MercadoPagoService::crearPreferencia())
// -- público, sin 'auth', exento de CSRF (ver VerifyCsrfToken::$except).
Route::post('/webhooks/mercadopago/{pedido}', [MercadoPagoWebhookController::class, 'handle'])->name('mp.webhook');

Route::get ('/inicio/{buscar?}', [ProductController::class, 'Buscar'])->name("Buscar");

// Plantilla "Tienda" (id 11, marketplace con Mercado Pago Connect): carrito
// y checkout, públicas (sin 'auth' -- el comprador nunca necesita cuenta).
// Tienen 3+ segmentos, así que NO chocan con el catch-all de 2 segmentos de
// abajo sin importar el orden -- TiendaController valida igual que
// ProductController::InfoPlantilla que el negocio exista y sea plantilla 11.
Route::prefix('{ciudad}/{slug}')->group(function () {
    Route::get('/producto/{productoSlug}', [TiendaController::class, 'producto'])->name('tienda.producto');
    Route::get('/carrito', [TiendaController::class, 'carritoVer'])->name('tienda.carrito');
    Route::post('/carrito/agregar', [TiendaController::class, 'agregar'])->name('tienda.agregar');
    Route::post('/carrito/actualizar', [TiendaController::class, 'actualizar'])->name('tienda.actualizar');
    Route::post('/carrito/quitar', [TiendaController::class, 'quitar'])->name('tienda.quitar');
    Route::get('/checkout', [TiendaController::class, 'checkout'])->name('tienda.checkout');
    Route::post('/checkout', [TiendaController::class, 'procesarCheckout'])->name('tienda.procesarCheckout');
    Route::get('/pedido/{pedido}/resultado', [TiendaController::class, 'resultado'])->name('tienda.resultado');
});

// Fase 4: URL por ciudad (/{ciudad}/{negocio}). Tiene que ir DESPUÉS de
// cualquier otra ruta de 2 segmentos (ej. /inicio/{buscar?}) porque, al
// tener 2 segmentos como ellas, Laravel matchea por orden de definición --
// si fuera la primera, "capturaría" /inicio/algo como si "inicio" fuera
// una ciudad.
Route::get('/{ciudad}/{slug}', [ProductController::class, 'InfoPlantilla']);


/* Route::get('/misia_paula', function () {
    return view('planes');
});

Route::get('/independiente', function () {
    return view('02-independiente');
}); */
