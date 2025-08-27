<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectAuthenticatedUsersController extends Controller
{
    public function home (){

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');

        } elseif ($user->role === 'gestor'){
            return redirect()->route('gestor.dashboard');
        }

        Auth::logout();
        return redirect('/')->with('error', 'Usuário não autorizado.');
    }
}
