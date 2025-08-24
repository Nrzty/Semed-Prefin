<?php

namespace App\Services;

use App\Models\Repasse;
use Exception;
use IntlDateFormatter;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PlanoAplicacaoService
{
    private string $templateLocation = 'app/templates/modelo_plano.xls';
    private int $currentRow;
    private array $cellMap = [
        'header' => [
            'nome_uex' => 'S7',
            'exercicio' => 'B14',
            'identificacao_parcela' => 'M14',
            'valor_custeio' => 'B16',
            'valor_capital' => 'T16',
        ],
        'itens' => [
            'start_row' => 23,
            'columns' => [
                'item' => 'B',
                'descricao' => 'F',
                'categoria' => 'Y',
                'quantidade' => 'AA',
                'valor_total' => 'AD',
            ]
        ]
    ];
    private Spreadsheet $spreadsheet;
    private Worksheet $sheet;

    /**
     * @throws Exception
     */
    public function gerarPlanoAplicacao(Repasse $repasse): array
    {
        $this->carregarModelo();

        $escola = $repasse->escola;
        $itens = $repasse->itens;

        $this->preencherCabecalho($repasse, $escola);
        $this->preencherItens($itens);
        $this->preencherRodape($escola);

        return $this->baixarPlanilha($repasse, $escola);
    }

    /**
     * @throws Exception
     */
    private function carregarModelo(): void
    {
        $templateFile = storage_path($this->templateLocation);

        if (!file_exists($templateFile)) {
            throw new Exception("ERRO: Template não encontrado em {$this->templateLocation}!");
        }

        $this->spreadsheet = IOFactory::load($templateFile);
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    private function preencherCabecalho(Repasse $repasse, $escola): void
    {
        $this->sheet->setCellValue($this->cellMap['header']['nome_uex'], "CONSELHO ESCOLAR DA {$escola->nome_escola}");
        $this->sheet->setCellValue($this->cellMap['header']['exercicio'], $repasse->ano_exercicio);
        $this->sheet->setCellValue($this->cellMap['header']['identificacao_parcela'], $repasse->numero_parcela . "ª PARCELA DO PREFIN");

        $this->sheet->setCellValue($this->cellMap['header']['valor_custeio'], $repasse->valor_custeio);
        $this->sheet->setCellValue($this->cellMap['header']['valor_capital'], $repasse->valor_capital);

        $cnpjApenasNumeros = preg_replace('/[^0-9]/', '', $escola->cnpj);
        $cnpjColumns = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
        foreach (str_split($cnpjApenasNumeros) as $index => $char) {
            if (isset($cnpjColumns[$index])) {
                $this->sheet->setCellValue($cnpjColumns[$index] . '7', $char)->getStyle($cnpjColumns[$index] . '7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        }

        $this->sheet->mergeCells("S7:AE7");
        $this->sheet->mergeCells("B14:K14");
        $this->sheet->mergeCells("M14:AE14");
        $this->sheet->mergeCells("B16:R16");
        $this->sheet->mergeCells("T16:Z16");
    }

    private function preencherItens($itens): void
    {
        $this->currentRow = $this->cellMap['itens']['start_row'];
        $columns = $this->cellMap['itens']['columns'];

        if (!$itens || $itens->count() === 0) {
            return;
        }

        $this->sheet->removeRow($this->currentRow);

        foreach ($itens as $index => $item) {
            $this->sheet->insertNewRowBefore($this->currentRow, 1);

            $this->sheet->setCellValue($columns['item'] . $this->currentRow, $index + 1);
            $this->sheet->setCellValue($columns['descricao'] . $this->currentRow, $item->descricao);
            $this->sheet->setCellValue($columns['categoria'] . $this->currentRow, $item->categoria_despesa);
            $this->sheet->setCellValue("Z" . $this->currentRow, "UND")->getStyle("Z" . $this->currentRow)->getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);
            $this->sheet->setCellValue($columns['quantidade'] . $this->currentRow, $item->quantidade)->getStyle($columns['quantidade'] . $this->currentRow)->getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);;

            $this->sheet->setCellValue($columns['valor_total'] . $this->currentRow, $item->valor_total)->getStyle($columns['valor_total'] . $this->currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $this->sheet->mergeCells("B{$this->currentRow}:E{$this->currentRow}");
            $this->sheet->mergeCells("F{$this->currentRow}:X{$this->currentRow}");
            $this->sheet->mergeCells("AA{$this->currentRow}:AC{$this->currentRow}");
            $this->sheet->mergeCells("AD{$this->currentRow}:AE{$this->currentRow}");

            $range = "B{$this->currentRow}:AE{$this->currentRow}";
            $this->sheet->getStyle($range)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('FFFFFFFF');

            $this->currentRow++;
        }
    }

    private function preencherRodape($escola): void
    {
        $footerRow = $this->currentRow + 10;

        $this->sheet->setCellValue('E' . $footerRow, $escola->nome_diretor);
        $this->sheet->setCellValue('X' . $footerRow, $escola->nome_presidente_conselho);

        $formatter = new IntlDateFormatter('pt_BR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        $dataAtual = $formatter->format(now());
        $this->sheet->setCellValue('B' . $footerRow + 6, "ARACAJU, {$dataAtual}");
    }

    private function baixarPlanilha(Repasse $repasse, $escola): array
    {
        $fileName = "plano_aplicacao_{$escola->nome_escola}_{$repasse->ano_exercicio}-{$repasse->numero_parcela}.xlsx";
        $writer = new Xlsx($this->spreadsheet);

        ob_start();
        $writer->save('php://output');
        $fileContent = ob_get_contents();
        ob_end_clean();

        return ['fileName' => $fileName, 'content' => $fileContent];
    }
}
