<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\categoriaController;
use App\Http\Controllers\clienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\PresentacioneController;



Route::get('/', function () {
    return view('template');
});

Route::view('/panel', 'panel.index')->name('panel');

//Route::view('/marcas', 'marca.index')->name('marcas');

Route::resources([
    'presentaciones' => PresentacioneController::class,
    'marcas' => MarcaController::class,
    'categorias' => categoriaController::class,
    'productos' => ProductoController::class,
    'clientes' => clienteController::class
]);

Route::resource('categorias',categoriaController::class);

Route::get('/401', function () {
    return view('pages.401');
});

Route::get('/404', function () {
    return view('pages.404');
});

Route::get('/500', function () {
    return view('pages.500');
});

Route::get('/login', function () {
    return view('auth.login');
});