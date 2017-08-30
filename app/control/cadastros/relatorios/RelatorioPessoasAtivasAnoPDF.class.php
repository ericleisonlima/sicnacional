<?php

require_once '/app/lib/' ;
use Adianti\Database\TTransaction;
use Adianti\Database\TRepository;
use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;

class RelatorioPessoasAtivasAnoPDF extends FPDF {


    //Page header
    function Header() {
    
       // $this->Image("app/iz'mages/logo_relatorio.jpg", 8, 11, 26, 18);

       /* $this->SetFont('Arial', 'B', 12);
        $this->SetY("12");
        $this->SetX("25");
        $this->Cell(0, 5, utf8_decode("GOVERNO DO ESTADO DO RIO GRANDE DO NORTE"), 0, 1, 'C');*/

        $this->SetY("22");
        $this->SetX("25");
        $this->Cell(0, 5, utf8_decode("RELATORIO DE PESSOAS ATIVAS POR ANO"), 0, 1, 'C');

                    

        $this->Ln(3); // Ln <<< PULAR LINHAS

        $this->ColumnHeader();
    }

    function ColumnHeader() {
     
        $this->SetFont('Arial', 'B', 10);
        $this->SetX("5");
        $this->Cell(0, 5, utf8_decode(""), 0, 0, 'L');


        $this->SetX("5");
        $this->Cell(0, 5, utf8_decode("Ano"), 0, 0, 'L');


    }

    function ColumnDetail() {

     
        $ano =  $_REQUEST['ano'];
        $this->SetX("20");

        TTransaction::open('dbsic');

        $repository = new TRepository('vw_pacientes_ativos_anoRecord');
        
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ano', '=', $ano ));

      /*  if($ano){
            
        }*/

        $rows = $repository->load($criteria);

                if ($rows) {
                    
                    foreach ($rows as $row) {

                        $this->SetFont('Arial', '', 9);

                        $this->SetX("5");
                        $this->Cell(0, 5, utf8_decode($row->pacientesanos), 0, 1, 'L');

                 
                        $this->Ln(5);

                    }

                }


            TTransaction::close();
        }
    

            //Page footer
            function Footer() 
            {
            
                $this->SetY(-15);
            
                $this->SetFont('Arial', 'I', 8);
            
                $data = date("d/m/Y H:i:s");
                $conteudo = "impresso em " . $data;
                $texto = "http://sic.educacao.ws/sistema";
            
                $this->Cell(0, 0, '', 1, 1, 'L');

                $this->Cell(0, 5, $texto, 0, 0, 'L');
                $this->Cell(0, 5, $conteudo, 0, 0, 'R');
                $this->Ln();
            }
        }


//Instanciation of inherited class
$pdf = new RelatorioPessoasAtivasAnoPDF("L", "mm", "A4");

//define o titulo
$pdf->SetTitle("Relatorio de Pessoa Ativdas por Ano - RBSIC");

//assunto
$pdf->SetSubject("Relatorio de Pessoa Ativdas por Ano - RBSIC");


$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);
$pdf->ColumnDetail();
//$file = "app/reports/RelatorioPessoasAtivasAnoPDF".$_SESSION['medico_id'].".pdf";
$file = "app/reports/RelatorioPessoasAtivasAnoPDF.pdf";

//abrir pdf
$pdf->Output($file);
$pdf->openFile($file);


?>