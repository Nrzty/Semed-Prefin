<?php

namespace App\Services;

use App\Models\Repasse;
use Exception;
use setasign\Fpdi\Tcpdf\Fpdi;

class ConsolidacaoPdfService
{
    private array $ordemDocumentosGerais = [
        'parecer_conclusivo',
        'ata_reuniao',
        'plano_aplicacao',
        'termo_doacao',
        'extratos_bancarios',
        'recibo_devolucao_rendimento',
        'demonstrativo_financeiro',
    ];

    /**
     * Define a ordem oficial dos documentos do Kit de Despesas.
     */
    private array $ordemKitDespesas = [
        'comparativo',
        'orcamento',
        'nota_fiscal',
        'cheque',
        'recibo',
        'certidao',
    ];

    /**
     * Consolida todos os documentos de prestação de contas de um repasse num único PDF.
     *
     * @throws Exception
     */
    public function gerarPdfConsolidado(Repasse $repasse): array
    {
        $pdf = new Fpdi();
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $documentos = $this->buscarDocumentosOrdenados($repasse);

        foreach ($documentos as $documento) {
            $path = storage_path('app/' . $documento->path_arquivo);

            if (!file_exists($path)) {
                continue;
            }

            $pageCount = $pdf->setSourceFile($path);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
            }
        }

        $fileName = "Prestacao_Contas_{$repasse->escola->nome_escola}_{$repasse->ano_exercicio}-{$repasse->numero_parcela}.pdf";
        $fileContent = $pdf->Output($fileName, 'S'); // 'S' retorna o documento como uma string.

        return ['fileName' => $fileName, 'content' => $fileContent];
    }


    private function buscarDocumentosOrdenados(Repasse $repasse)
    {
        $documentosGerais = $repasse->documentosPrestacaoContas()
            ->whereNull('pagamento_id')
            ->get()
            ->sortBy(fn($doc) => array_search($doc->tipo_documento, $this->ordemDocumentosGerais));

        $kitDespesas = $repasse->documentosPrestacaoContas()
            ->whereNotNull('pagamento_id')
            ->get()
            ->groupBy('pagamento_id')
            ->map(fn($docsPagamento) => $docsPagamento->sortBy(fn($doc) => array_search($doc->tipo_documento, $this->ordemKitDespesas)))
            ->flatten();

        return $documentosGerais->concat($kitDespesas);
    }
}
