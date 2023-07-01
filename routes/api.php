<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\UserController;

// Rutas para listar y mostrar user
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/listing', [UserController::class, 'listing']); 

    Route::get('/dtailUser/{id}', [UserController::class, 'dtailUser']); 
    // Ruta para guardar un nuevo user
    Route::post('/saveOrUpdate', [UserController::class, 'save']);
    // Ruta para eliminar un user
    Route::delete('/user/{id}', [UserController::class, 'destroy']);
    // Agrega más rutas aquí    
});


Route::post('/save', [ApiController::class, 'save']);
Route::get('/login', [ApiController::class, 'login']);
