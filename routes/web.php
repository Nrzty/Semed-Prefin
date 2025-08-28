<?php

    use App\Http\Controllers\Admin\AdminAnalisarPlanos;
    use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PlanoAnaliseController;
use App\Http\Controllers\Gestor\DocumentoController;
use App\Http\Controllers\Gestor\GestorDashboardController;
use App\Http\Controllers\Gestor\PagamentoController;
use App\Http\Controllers\Gestor\PlanoAplicacaoController;
use App\Http\Controllers\Gestor\PrestacaoContasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedirectAuthenticatedUsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'));

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Rota de redirecionamento pós-login
    Route::get('/dashboard', [RedirectAuthenticatedUsersController::class, 'home'])->name('dashboard');

    // -----------------------------------
    // ROTAS DO GESTOR
    // -----------------------------------
    Route::prefix('gestor')->name('gestor.')->group(function () {
        Route::get('/dashboard', [GestorDashboardController::class, 'index'])->name('dashboard');

        // Rotas de Pagamentos
        Route::resource('pagamentos', PagamentoController::class);

        // Rotas para Geração de Documentos
        Route::controller(DocumentoController::class)->group(function () {
            Route::get('documentos', 'index')->name('documentos.index');
            Route::get('repasses/{repasse}/demonstrativo', 'gerarDemonstrativo')->name('repasses.demonstrativo');
            Route::get('repasses/{repasse}/planoAplicacao', 'gerarPlanoAplicacao')->name('repasses.plano');
        });

        // Rotas para Plano de Aplicação
        Route::resource('plano-aplicacao', PlanoAplicacaoController::class)->only(['create', 'store']);

        // Rotas para Prestação de Contas
        Route::controller(PrestacaoContasController::class)->name('repasses.prestacao-contas.')->prefix('repasses/{repasse}/prestacao-contas')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'upload')->name('upload');
            Route::post('/pagamentos/{pagamento}', 'uploadKitDespesa')->name('upload-kit');
            Route::get('/consolidar', 'consolidarDocumentos')->name('consolidar');
        });
        Route::get('prestacao-contas/escolher-repasse', [PrestacaoContasController::class, 'escolherRepasse'])->name('prestacao-contas.escolher-repasse');
    });

    // -----------------------------------
    // ROTAS DO ADMINISTRADOR
    // -----------------------------------
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('planos')->name('planos.')->controller(AdminAnalisarPlanos::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{plano}', 'show')->name('show');
            // Futuras rotas de aprovação/reprovação poderiam vir aqui
            // Route::post('/{plano}/aprovar', 'aprovar')->name('aprovar');
            // Route::post('/{plano}/reprovar', 'reprovar')->name('reprovar');
        });

        // A rota abaixo se tornou redundante após a correção que fizemos.
        // O grupo acima ('admin/planos') já a substitui.
        // Route::get('analisarPlanos', [AdminAnalisarPlanos::class, 'index'])->name('analisarPlanos');
    });

    // -----------------------------------
    // ROTAS DE PERFIL (PROFILE)
    // -----------------------------------
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });
});

require __DIR__.'/auth.php';
