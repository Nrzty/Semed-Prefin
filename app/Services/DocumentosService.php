<?php

namespace App\Services;

use App\Models\Repasse;
use Carbon\Carbon;
use Exception;
use IntlDateFormatter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DocumentosService
{
    private int $currentRow;

    private array $cellMap = [
        'header' => [
            'escola_nome' => 'A9',
            'cnpj' => 'X9',
            'parcela' => 'AE18',
            'exercicio' => 'AB7',
            'endereco' => 'A11',
            'periodo_execucao' => 'Z18',
            'valor_rendimento_1' => 'Q16',
            'valor_rendimento_2' => 'AA16',
            'valor_rendimento_3' => 'P19',
        ],
        'totais' => [
            'custeio_gasto' => 'E19',
            'capital_gasto' => 'H19',
            'custeio_creditado' => 'G16',
            'capital_creditado' => 'J16',
        ],
        'pagamentos' => [
            'start_row' => 24,
            'columns' => [
                'item' => 'A',
                'favorecido' => 'B',
                'cnpj_cpf' => 'F',
                'tipo_bem' => 'I',
                'origem' => 'O',
                'natureza' => 'R',
                'doc_tipo' => 'U',
                'doc_numero' => 'W',
                'doc_data' => 'Y',
                'cheque_numero' => 'AA',
                'cheque_data' => 'AD',
                'valor' => 'AF',
            ]
        ]
    ];

    private Spreadsheet $spreadsheet;
    private Worksheet $sheet;

    /**
     * @throws Exception
     */
    public function gerarDemonstrativo(Repasse $repasse): array
    {
        $this->carregarModelo();

        $this->preencherCabecalho($repasse);
        $this->preencherPagamentos($repasse->pagamentos()->orderBy('data_emissao_documento')->get());
        $this->preencherTotais($repasse);
        $this->preencherRodape($repasse->escola);

        return $this->baixarPlanilha($repasse);
    }

    /**
     * @throws Exception
     */
    private function carregarModelo(): void
    {
        $templateFile = storage_path('app/templates/modelo_demonstrativo.xls');

        if (!file_exists($templateFile)) {
            throw new Exception('ERRO: Template não encontrado em storage/app/templates!');
        }

        $reader = new Xls();
        $this->spreadsheet = $reader->load($templateFile);
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    private function preencherCabecalho(Repasse $repasse): void
    {
        $escola = $repasse->escola;

        $valorRendimento = $repasse->rendimento->valor_rendimento ?? 0.00;

        $this->sheet->setCellValue($this->cellMap['header']['escola_nome'], "CONSELHOR ESCOLAR DA {$escola->nome_escola}");
        $this->sheet->setCellValue($this->cellMap['header']['cnpj'], $escola->cnpj);
        $this->sheet->setCellValue($this->cellMap['header']['parcela'], $repasse->numero_parcela);
        $this->sheet->setCellValue($this->cellMap['header']['exercicio'], $repasse->ano_exercicio);
        $this->sheet->setCellValue($this->cellMap['header']['endereco'], "{$escola->logradouro}, {$escola->numero} - {$escola->bairro}");

        $this->sheet->setCellValue($this->cellMap['header']['valor_rendimento_1'], $valorRendimento);
        $this->sheet->setCellValue($this->cellMap['header']['valor_rendimento_2'], $valorRendimento);
        $this->sheet->setCellValue($this->cellMap['header']['valor_rendimento_3'], $valorRendimento);

        $inicio = $repasse->inicio_execucao->format('d/m/Y');
        $fim = $repasse->fim_execucao->format('d/m/Y');
        $this->sheet->setCellValue($this->cellMap['header']['periodo_execucao'], "{$inicio} a {$fim}");

        $this->sheet->mergeCells("AB7:AG7");
        $this->sheet->mergeCells("A11:L11");
        $this->sheet->mergeCells("Q16:U16");
        $this->sheet->mergeCells("AA16:AD16");
        $this->sheet->mergeCells("P19:V19");
        $this->sheet->mergeCells("Z18:AD19");
    }

    private function preencherPagamentos($pagamentos): void
    {
        $this->currentRow = $this->cellMap['pagamentos']['start_row'];
        $columns = $this->cellMap['pagamentos']['columns'];

        if ($pagamentos->count() > 0) {
            $this->sheet->removeRow($this->currentRow);
        }

        foreach ($pagamentos as $index => $pagamento) {
            $this->sheet->insertNewRowBefore($this->currentRow, 1); // Corrigido

            $this->sheet->setCellValue($columns['item'] . $this->currentRow, $index + 1);
            $this->sheet->setCellValue($columns['favorecido'] . $this->currentRow, $pagamento->nome_fornecedor);
            $this->sheet->setCellValue($columns['cnpj_cpf'] . $this->currentRow, $this->formatarCNPJ($pagamento->cnpj_cpf_fornecedor));
            $this->sheet->setCellValue($columns['tipo_bem'] . $this->currentRow, $pagamento->tipo_despesa);
            $this->sheet->setCellValue($columns['origem'] . $this->currentRow, "PREFIN");
            $this->sheet->setCellValue($columns['doc_tipo'] . $this->currentRow, "NF");
            $this->sheet->setCellValue($columns['doc_numero'] . $this->currentRow, $pagamento->numero_nota_fiscal);
            $this->sheet->setCellValue($columns['doc_data'] . $this->currentRow, Carbon::parse($pagamento->data_emissao_documento)->format('d/m/Y'));
            $this->sheet->setCellValue($columns['cheque_numero'] . $this->currentRow, $pagamento->numero_cheque);
            $this->sheet->setCellValue($columns['cheque_data'] . $this->currentRow, Carbon::parse($pagamento->data_pagamento_efetivo)->format('d/m/Y'));
            $this->sheet->setCellValue($columns['valor'] . $this->currentRow, $pagamento->valor_total_pagamento);

            $natureza = in_array($pagamento->tipo_despesa, ['Material de Custeio', 'Prestação de Serviço']) ? 'C' : 'K';
            $this->sheet->setCellValue($columns['natureza'] . $this->currentRow, $natureza);

            $this->sheet->mergeCells("B{$this->currentRow}:E{$this->currentRow}");
            $this->sheet->mergeCells("F{$this->currentRow}:H{$this->currentRow}");
            $this->sheet->mergeCells("I{$this->currentRow}:N{$this->currentRow}");
            $this->sheet->mergeCells("O{$this->currentRow}:Q{$this->currentRow}");
            $this->sheet->mergeCells("R{$this->currentRow}:T{$this->currentRow}");
            $this->sheet->mergeCells("W{$this->currentRow}:X{$this->currentRow}");
            $this->sheet->mergeCells("Y{$this->currentRow}:Z{$this->currentRow}");
            $this->sheet->mergeCells("AA{$this->currentRow}:AC{$this->currentRow}");
            $this->sheet->mergeCells("AD{$this->currentRow}:AE{$this->currentRow}");
            $this->sheet->mergeCells("AF{$this->currentRow}:AG{$this->currentRow}");

            $this->currentRow++;
        }
    }

    private function preencherTotais(Repasse $repasse): void
    {
        $this->sheet->setCellValue($this->cellMap['totais']['custeio_gasto'], $repasse->totalGastoCusteio());
        $this->sheet->setCellValue($this->cellMap['totais']['capital_gasto'], $repasse->totalGastoCapital());
        $this->sheet->setCellValue($this->cellMap['totais']['custeio_creditado'], $repasse->valor_custeio);
        $this->sheet->setCellValue($this->cellMap['totais']['capital_creditado'], $repasse->valor_capital);
    }

    private function preencherRodape($escola): void
    {
        $footerRow = $this->currentRow + 3;

        $this->sheet->setCellValue('J' . $footerRow, $escola->nome_diretor);
        $this->sheet->setCellValue('W' . $footerRow, $escola->nome_presidente_conselho);

        $formatter = new IntlDateFormatter('pt_BR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        $dataAtual = $formatter->format(now());
        $this->sheet->setCellValue('A' . $footerRow, "ARACAJU, {$dataAtual}");
    }

    private function baixarPlanilha(Repasse $repasse): array
    {
        $fileName = "demonstrativo_{$repasse->escola->nome_escola}_{$repasse->ano_exercicio}-{$repasse->numero_parcela}.xlsx";
        $writer = new Xlsx($this->spreadsheet);

        ob_start();
        $writer->save('php://output');
        $fileContent = ob_get_contents();
        ob_end_clean();

        return ['fileName' => $fileName, 'content' => $fileContent];
    }

    private function formatarCNPJ($cnpj): array|string|null
    {
        if (empty($cnpj)) {
            return '';
        }
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj);
    }
}
