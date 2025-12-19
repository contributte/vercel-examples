<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [App\Http\Controllers\BlogController::class, 'HomePage']);
Route::get('/about', [App\Http\Controllers\BlogController::class, 'AboutPage']);
Route::get('/contact', [App\Http\Controllers\BlogController::class, 'ContactPage']);
Route::get('/posts/{article}', [App\Http\Controllers\BlogController::class, 'SingleArticle']);
