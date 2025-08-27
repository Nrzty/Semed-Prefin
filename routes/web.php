<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Gestor\DocumentoController;
use App\Http\Controllers\Gestor\PagamentoController;
use App\Http\Controllers\Gestor\PrestacaoContasController;
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
    Route::get('repasses/{repasse}/planoAplicacao', [DocumentoController::class, 'gerarPlanoAplicacao'])->name('repasses.plano');

    // ROTA PARA PRESTAÇÃO DE CONTAS
    Route::get('prestacao-contas', [PrestacaoContasController::class, 'escolherRepasse'])->name('prestacao-contas.escolher-repasse');
    Route::get('repasses/{repasse}/prestacao-contas', [PrestacaoContasController::class, 'index'])->name('repasses.prestacao-contas.index');
    Route::post('repasses/{repasse}/prestacao-contas', [PrestacaoContasController::class, 'upload'])->name('repasses.prestacao-contas.upload');
    Route::post('repasses/{repasse}/pagamentos/{pagamento}/prestacao-contas', [PrestacaoContasController::class, 'uploadKitDespesa'])->name('repasses.prestacao-contas.upload-kit');
    Route::get('repasses/{repasse}/prestacao-contas/consolidar', [PrestacaoContasController::class, 'consolidarDocumentos'])->name('repasses.prestacao-contas.consolidar');
});

// ROTAS DO ADMINISTRADOR
Route::middleware('auth')->group(function (){
    Route::get('admin/dashboard', [AdminDashboardController::class,'index'])->name('admin.dashboard');
});

// BREEZE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
