<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use App\Models\Repasse;
use App\Services\DocumentosService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentoController extends Controller
{
    public function index()
    {
        $escola = Auth::user()->escola;

        $repasses = $escola->repasses()
                          ->orderBy("ano_exercicio","desc")
                          ->orderBy("ano_exercicio", "desc")
                          ->paginate(10); 
    
        return view('gestor.documentos.index', compact('repasses'));
    }

    public function gerarDemonstrativo(Repasse $repasse, DocumentosService $DocumentosService) 
    {                      
        try {
            $dadosArquivo = $DocumentosService->gerarDemonstrativo($repasse);
            
            return response($dadosArquivo['content'])
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->header('Content-Disposition', 'attachment; filename="' . $dadosArquivo['fileName'] . '"');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['geral' => $e->getMessage()]);
        }
    }
}
