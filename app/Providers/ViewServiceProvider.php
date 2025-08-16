<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('layouts.partials.header', function ($view) {
            
            $nomeEscola = null;

            if (Auth::check() && Auth::user()->escola) {
                $nomeEscola = Auth::user()->escola->nome_escola;
            }

            $view->with('nomeEscola', $nomeEscola);
        });
    }
}
