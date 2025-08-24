<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use App\Models\PrestacaoContaDocumento;
use App\Models\Repasse;
use App\Services\ConsolidacaoPdfService;
use Exception;
use Illuminate\Support\Facades\Auth;

class PrestacaoContasController extends Controller
{
    public function escolherRepasse()
    {
        $escola = Auth::user()->escola;

        $repasses = $escola->repasses()
                            ->orderBy('ano_exercicio', 'desc')
                            ->orderBy('numero_parcela', 'desc')
                            ->paginate(10);

        return view('gestor.prestacao-contas.escolher-repasse', compact('repasses'));
    }

    public function index(Repasse $repasse)
    {
        if ($repasse->escola_id !== Auth::user()->escola_id)
        {
            abort(403);
        }

        if ($repasse->status !== 'Aberto')
        {
            return redirect()->route('gestor.prestacao-contas.escolher-repasse')
                ->withErrors(['geral' => 'Este repasse não está aberto para organizar a prestação de contas.']);
        }

        $documentosEnviados = $repasse->documentosPrestacaoContas()->get()->keyBy('tipo_documento');

        $checklistGeral = [
            'parecer_conclusivo' => '1) Parecer Conclusivo',
            'ata_reuniao' => '2) Ata de Reunião',
            'plano_aplicacao' => '3) Plano de Aplicação',
            'termo_doacao' => '4) Termo de Doação (se houver)',
            'extratos_bancarios' => '5) Extratos Bancários (Banese)',
            'recibo_devolucao_rendimento' => '6) Recibo de devolução do rendimento',
            'demonstrativo_financeiro' => '7) Demonstrativo Financeiro',
        ];

        $pagamentos = $repasse->pagamentos()->with('documentosKitDespesas')->get();

        return view('gestor.prestacao-contas.index', compact(
            'repasse',
            'documentosEnviados',
            'checklistGeral',
            'pagamentos'
        ));
    }

    public function upload(Request $request, Repasse $repasse)
    {
        if ($repasse->escola_id !== Auth::user()->escola_id) {
            abort(403);
        }

        $request->validate([
            'tipo_documento' => 'required|string',
            'arquivo' => 'required|file|mimes:pdf|max:5120',
        ]);

        $tipoDocumento = $request->input('tipo_documento');
        $arquivo = $request->file('arquivo');
        $nomeOriginal = $arquivo->getClientOriginalName();

        $path = $arquivo->store("repasses/{$repasse->id}/prestacao_contas", 'private');

        PrestacaoContaDocumento::updateOrCreate(
            [
                'repasse_id' => $repasse->id,
                'tipo_documento' => $tipoDocumento,
            ],
            [
                'path_arquivo' => $path,
                'nome_original' => $nomeOriginal,
            ]
        );

        return back()->with('success', 'Documento enviado com sucesso!');
    }

    public function uploadKitDespesa(Request $request, Repasse $repasse, Pagamento $pagamento)
    {
        if ($repasse->escola_id !== Auth::user()->escola_id || $pagamento->repasse_id !== $repasse->id) {
            abort(403, 'Acesso não autorizado.');
        }

        $request->validate([
            'tipo_documento_kit' => 'required|string',
            'arquivos_kit.*' => 'required|file|mimes:pdf|max:5120',
            'arquivos_kit' => 'max:3',
        ]);

        $tipoDocumento = $request->input('tipo_documento_kit');

        foreach ($request->file('arquivos_kit') as $arquivo) {
            $nomeOriginal = $arquivo->getClientOriginalName();
            $path = $arquivo->store("repasses/{$repasse->id}/pagamento_{$pagamento->id}", 'private');

            PrestacaoContaDocumento::create([
                'repasse_id' => $repasse->id,
                'pagamento_id' => $pagamento->id,
                'tipo_documento' => $tipoDocumento,
                'path_arquivo' => $path,
                'nome_original' => $nomeOriginal,
            ]);
        }

        return back()->with('success', 'Documento(s) do Kit de Despesa enviado(s) com sucesso!');
    }

    public function consolidarDocumentos(Repasse $repasse, ConsolidacaoPdfService $consolidador)
    {
        if ($repasse->escola_id !== Auth::user()->escola_id) {
            abort(403);
        }

        try {
            $dadosArquivo = $consolidador->gerarPdfConsolidado($repasse);

            return response($dadosArquivo['content'])
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $dadosArquivo['fileName'] . '"');

        } catch (Exception $e) {
            return back()->withErrors(['geral' => 'Erro ao consolidar PDF: ' . $e->getMessage()]);
        }
    }
}
