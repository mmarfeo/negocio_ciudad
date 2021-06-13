<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductController;
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


Route::get('/planes.html', function () {
    return view('planes');
});



Route::get('/', [ProductController::class, 'listado']);
Route::get('/inicio', [ProductController::class, 'listado']);

Route::get('/{slug}', [ProductController::class, 'InfoPlantilla']);

Route::get ('/inicio/{buscar?}', [ProductController::class, 'Buscar'])->name("Buscar");


/* Route::get('/misia_paula', function () {
    return view('planes');
});

Route::get('/independiente', function () {
    return view('02-independiente');
}); */
