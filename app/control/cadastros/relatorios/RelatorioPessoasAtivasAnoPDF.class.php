<?php

use FPDF;
use Adianti\Database\TTransaction;
use Adianti\Database\TRepository;
use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;

class RelatorioPessoasAtivasAnoPDF extends FPDF {



    //Page header
    function Header() {
    
       // $this->Image("app/images/logo_relatorio.jpg", 8, 11, 26, 18);

       /* $this->SetFont('Arial', 'B', 12);
        $this->SetY("12");
        $this->SetX("25");
        $this->Cell(0, 5, utf8_decode("GOVERNO DO ESTADO DO RIO GRANDE DO NORTE"), 0, 1, 'C');*/

        $this->SetY("22");
        $this->SetX("25");
        $this->Cell(0, 5, utf8_decode("INSTITUTO DE DEFESA E INSPEÇÃO AGROPECUÁRIA DO ESTADO DO RN - IDIARNN"), 0, 1, 'C');

                    

        $this->Ln(3); // Ln <<< PULAR LINHAS

        $this->ColumnHeader();
    }

    function ColumnHeader() {
     
        $this->SetFont('Arial', 'B', 10);
        $this->SetX("5");
        $this->Cell(0, 5, utf8_decode(""), 0, 0, 'L');


        $this->SetX("125");
        $this->Cell(0, 5, utf8_decode("Nome do Distribuidor"), 0, 0, 'L');


        $this->SetX("180");
        $this->Cell(0, 5, utf8_decode("Data da Compra"), 0, 1, 'L');        

    }

    function ColumnDetail() {
     
         
        $this->SetX("20");

        TTransaction::open('pg_ceres');

        $repository = new TRepository('Vw_iv_compraRecord');
        
        $criteria = new TCriteria;

        $datacompra = $_REQUEST['datacompra'];

        if($datacompra){
            $criteria->add(new TFilter('datacompra', '=', $datacompra));
        }

        $rows = $repository->load($criteria);

                if ($rows) {
                    
                    foreach ($rows as $row) {

                        $this->SetFont('Arial', '', 9);

                        $this->SetX("5");
                        $this->Cell(0, 5, utf8_decode($row->nome_loja), 0, 0, 'L');

                        $this->SetX("125");
                        $this->Cell(0, 5, utf8_decode(substr($row->nome_distribuidor, 0, 29)), 0, 0, 'L');

                        $this->SetX("180");
                        $this->Cell(0, 5, utf8_decode(substr($row->datacompra, 0, 30)), 0, 0, 'L');

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
                $texto = "http://www.emater.rn.gov.br";
            
                $this->Cell(0, 0, '', 1, 1, 'L');

                $this->Cell(0, 5, $texto, 0, 0, 'L');
                $this->Cell(0, 5, $conteudo, 0, 0, 'R');
                $this->Ln();
            }

}



//Instanciation of inherited class
$pdf = new CompraRelatorioPDF("L", "mm", "A4");

//define o titulo
$pdf->SetTitle("Relatorio de Compras - IDIARN-RN");

//assunto
$pdf->SetSubject("Relatorio de Compras  - IDIARN-RN");


$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);
$pdf->ColumnDetail();
$file = "app/reports/CompraRelatorioPDF".$_SESSION['servidor_id'].".pdf";

//abrir pdf
$pdf->Output($file);
$pdf->openFile($file);

?>
