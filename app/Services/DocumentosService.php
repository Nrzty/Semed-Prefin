<?php 

namespace App\Services;

use App\Models\Repasse;
use IntlDateFormatter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DocumentosService{ 

    private array $cellMap = [
        'header' => [
            'escola_nome' => 'A9',
            'cnpj' => 'X9',
            'parcela' => 'AE18',
            'exercicio' => 'AH5',
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
        ],
        'footer' => [
            'data_emissao' => 'A36',
            'diretor_assinatura' => 'J36',
            'presidente_assinatura' => 'W36',
        ]
    ];

    private Spreadsheet $spreadsheet;
    private Worksheet $sheet;

    public function gerarDemonstrativo(Repasse $repasse)
    {    
        $this->carregarModelo();

        $this->preencherCabecalho($repasse);
        $this->preencherPagamentos($repasse->pagamentos()->orderBy('data_emissao_documento', 'asc')->get());
        $this->preencherTotais($repasse);
        $this->preencherRodape($repasse->escola);

        return $this->baixarPlanilha($repasse);
    }

    private function carregarModelo()
    {
        $templateFile = storage_path('app/templates/modelo_demonstrativo.xls');

        if (!file($templateFile)) {
            throw new \Exception('ERRO: Template não encontrado em storage/app/templates!');
        }
    
        $reader = new Xls();
        $this->spreadsheet = $reader->load($templateFile);
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    private function preencherCabecalho(Repasse $repasse)
    {
        $escola = $repasse->escola;
        $this->sheet->setCellValue($this->cellMap['header']['escola_nome'], "CONSELHOR ESCOLAR DA {$escola->nome_escola}");
        $this->sheet->setCellValue($this->cellMap['header']['cnpj'],$escola->cnpj);
        $this->sheet->setCellValue($this->cellMap['header']['parcela'],$repasse->numero_parcela);
        $this->sheet->setCellValue($this->cellMap['header']['exercicio'],$repasse->ano_exercicio);

    }

    private function preencherPagamentos($pagamentos)
    {
        $currentRow = $this->cellMap['pagamentos']['start_row'];
        $columns = $this->cellMap['pagamentos']['columns'];
    
        if ($pagamentos->count() > 0) 
        {
            $this->sheet->removeRow($currentRow);
        }

        foreach ($pagamentos as $index => $pagamento)
        { 
            $this->sheet->insertNewRowBefore($currentRow, 1);


            $this->sheet->setCellValue($columns['item'] . $currentRow, $index + 1);
            $this->sheet->setCellValue($columns['favorecido'] . $currentRow, $pagamento->nome_fornecedor);
            $this->sheet->setCellValue($columns['cnpj_cpf'] . $currentRow, $this->formatarCNPJ( $pagamento->cnpj_cpf_fornecedor));
            $this->sheet->setCellValue($columns['tipo_bem'] . $currentRow, $pagamento->tipo_despesa);
            $this->sheet->setCellValue($columns['origem'] . $currentRow, "PREFIN");
            $this->sheet->setCellValue($columns['doc_tipo'] . $currentRow, "NF");
            $this->sheet->setCellValue($columns['doc_numero'] . $currentRow, $pagamento->numero_nota_fiscal);
            $this->sheet->setCellValue($columns['doc_data'] . $currentRow, \Carbon\Carbon::parse($pagamento->data_emissao_documento)->format('d/m/Y'));
            $this->sheet->setCellValue($columns['cheque_numero'] . $currentRow, $pagamento->numero_cheque);
            $this->sheet->setCellValue($columns['cheque_data'] . $currentRow, \Carbon\Carbon::parse($pagamento->data_pagamento_efetivo)->format('d/m/Y'));
            $this->sheet->setCellValue($columns['valor'] . $currentRow, $pagamento->valor_total_pagamento);

            $natureza = in_array($pagamento->tipo_despesa, ['Material de Custeio', 'Prestação de Serviço']) ? 'C' : 'K';
            $this->sheet->setCellValue($columns['natureza'] . $currentRow, $natureza);
            
            $this->sheet->mergeCells("B{$currentRow}:E{$currentRow}"); 
            $this->sheet->mergeCells("F{$currentRow}:H{$currentRow}");
            $this->sheet->mergeCells("I{$currentRow}:N{$currentRow}"); 
            $this->sheet->mergeCells("O{$currentRow}:Q{$currentRow}"); 
            $this->sheet->mergeCells("R{$currentRow}:T{$currentRow}"); 
            $this->sheet->mergeCells("W{$currentRow}:X{$currentRow}"); 
            $this->sheet->mergeCells("Y{$currentRow}:Z{$currentRow}"); 
            $this->sheet->mergeCells("AA{$currentRow}:AC{$currentRow}");
            $this->sheet->mergeCells("AD{$currentRow}:AE{$currentRow}");
            $this->sheet->mergeCells("AF{$currentRow}:AG{$currentRow}");

            $currentRow++;
        }
    }

    private function preencherTotais(Repasse $repasse)
    {
        $this->sheet->setCellValue($this->cellMap['totais']['custeio_gasto'], $repasse->totalGastoCusteio());
        $this->sheet->setCellValue($this->cellMap['totais']['capital_gasto'], $repasse->totalGastoCapital());
        $this->sheet->setCellValue($this->cellMap['totais']['custeio_creditado'], $repasse->valor_custeio);
        $this->sheet->setCellValue($this->cellMap['totais']['capital_creditado'], $repasse->valor_capital);    
    }

    private function preencherRodape($escola): void
    {
        $this->sheet->setCellValue($this->cellMap['footer']['diretor_assinatura'], $escola->nome_diretor);
        $this->sheet->setCellValue($this->cellMap['footer']['presidente_assinatura'], $escola->nome_presidente_conselho);
        
        $formatter = new IntlDateFormatter('pt_BR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        $dataAtual = $formatter->format(now());
        $this->sheet->setCellValue($this->cellMap['footer']['data_emissao'], "ARACAJU, {$dataAtual}");
    }

    private function baixarPlanilha(Repasse $repasse)
    {   
        $fileName = "demonstrativo_{$repasse->escola->nome_escola}_{$repasse->ano_exercicio}-{$repasse->numero_parcela}.xlsx";
        $writer = new Xlsx($this->spreadsheet);

        ob_start();
        $writer->save('php://output');
        $fileContent = ob_get_contents();
        ob_end_clean();

        return ['fileName' => $fileName, 'content' => $fileContent];
    }

    private function formatarCNPJ($cnpj){
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj);
    }
}