<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Gestor\DocumentoController;
use App\Http\Controllers\Gestor\PagamentoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedirectAuthenticatedUsersController;
use App\Http\Controllers\GestorDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [RedirectAuthenticatedUsersController::class, 'home'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// ROTAS DO GESTOR
Route::middleware(['auth'])->prefix('gestor')->name('gestor.')->group(function (){
    Route::get('/dashboard', [GestorDashboardController::class,'index'])->name('dashboard');

    Route::resource('pagamentos', PagamentoController::class);

    // ROTA PRA GERAR OS DOCUMENTOS
    Route::get('documentos', [DocumentoController::class, 'index'])->name('documentos.index');
    Route::get('repasses/{repasse}/demonstrativo', [DocumentoController::class, 'gerarDemonstrativo'])->name('repasses.demonstrativo');
});

// ROTAS DO ADMINISTRADORa
Route::middleware('auth')->group(function (){
    Route::get('/admin/dashboard', function(){
        return 'Painel do Administrador';
    })->name('admin.dashboard');
});

// BREEZE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
