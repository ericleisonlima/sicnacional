<?php

use Adianti\Database\TTransaction;
use Adianti\Database\TRepository;
use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;

class PacienteReportPDF extends FPDF {

    function Header() {
        $this->Image("app/images/logo_relatorio.png", 8, 9, 26, 14);

        $this->SetFont('Arial', 'B', 10);
        $this->SetY("10");
        $this->SetX("35");
        $this->Cell(0, 5, utf8_decode("REGISTRO BRASILEIRO DA SINDROME DO INTESTINO CURTO - RBSIC"), 0, 1, 'J');

        $this->SetX("35");
        $this->Cell(0, 5, utf8_decode(""), 0, 1, 'J');

        $this->SetX("35");
        $this->Cell(0, 5, utf8_decode("PEX - PLANILHA DE ATIVIDADES"), 0, 1, 'J');

        $this->Cell(0, 0, '', 0, 1, 'L');
        $this->Ln(8);
        
        $this->ColumnHeader();
    }
    
    
    
    function ColumnHeader() {
        /*
        TTransaction::open('dbsic');
        $cadastro = new PtpexRecord($_REQUEST['pex_pt_id']);
        if ($cadastro) {
            $programa_nome = $cadastro->nome;
        }
        TTransaction::close();
        */
       
        $this->SetX("10");
        $this->Cell(0, 6, "Nome", 0, 0, 'J');

        $this->setX("150");
        $this->Cell(0, 5, utf8_decode("Clínica"), 0, 1, 'J');
        
        $this->Cell(0, 0, '', 1, 1, 'L');
        $this->Ln(2); 
    }

    function ColumnDetail() {

        TTransaction::open('dbsic');

        $repository = new TRepository('vwPacienteEstabelecimentoMedicoRecord');
 
        $criteria = new TCriteria;
        $criteria->setProperty('order', 'paciente');
        //$criteria->add(new TFilter('pt_id', '=', $programa));

        $rows = $repository->load($criteria);

        if ($rows) {
            foreach ($rows as $row) {

                $this->SetFont('arial', '', 10);
                $this->SetX("10");
                $this->Cell(0, 5, utf8_decode( substr($row->paciente, 0, 100)  ) , 0, 0, 'J');              
               
                $this->SetX("150");
                $this->Cell(0,5, utf8_decode( substr ($row->estabelecimento, 0, 50)), 0, 1, 'J');
        
            }
        }

        TTransaction::close();
    }

//Page footer
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $data = date("d/m/Y H:i:s");
        $conteudo = "impresso em " . $data;
        $texto = "UNIVERSIDADE POTIGUAR < E-CODE />";
        $this->Cell(0, 0, '', 1, 1, 'L');

        $this->Cell(0, 5, $texto, 0, 0, 'L');
        $this->Cell(0, 5, 'Pag. ' . $this->PageNo() . ' de ' . '{nb}' . ' - ' . $conteudo, 0, 0, 'R');
        $this->Ln();
    }

}

$pdf = new PacienteReportPDF("P", "mm", "A4");

$pdf->SetTitle("Relatório de Pacientes");

//assunto
$pdf->SetSubject("Relatório de Pacientes");

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);
$pdf->ColumnDetail();
$file = "app/reports/PacienteReportPDF.pdf";
//abrir pdf
$pdf->Output($file);
$pdf->openFile($file);
?>