<?php

use FPDF;
use Adianti\Database\TTransaction;
use Adianti\Database\TRepository;
use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;



class RelatorioBancoSementesPDF extends FPDF {

//Page header
    function Header() {

        //endereco da imagem,posicao X(horizontal),posicao Y(vertical), tamanho altura, tamanho largura
        $this->Image("app/images/logo_relatorio.jpg", 8, 11, 18, 18);

        //Arial bold 15
        $this->SetFont('Arial', 'B', 12);
        $this->SetY("12");
        $this->SetX("25");
        $this->Cell(0, 5, utf8_decode("GOVERNO DO ESTADO DO RIO GRANDE DO NORTE"), 0, 1, 'C');

        $this->SetY("17");
        $this->SetX("25");
        $this->Cell(0, 5, utf8_decode( $_SESSION['empresa_nome'] ), 0, 1, 'C');

        $this->SetY("22");
        $this->SetX("25");
        $this->Cell(0, 5, utf8_decode("DIÁRIO DO EXTENSIONISTA RURAL - DER"), 0, 1, 'C');




        //define a fonte a ser usada
        $titulo = 'Lista de Banco de Sementes';
        $this->SetY("35");
        $this->SetX("15");
        $this->Cell(0, 5, utf8_decode($titulo), 0, 1, 'C');
        $this->Ln(3);

        $this->ColumnHeader();
    }

    function ColumnHeader() {

        //define a fonte a ser usada

    }

    function ColumnDetail() {
        $mesorregiao_id = $_REQUEST['mesorregiao'];
        $produtor = $_REQUEST['produtor'];

        $this->SetX("20");

        // inicia transacao com o banco 'pg_ceres'
        TTransaction::open('pg_ceres');

        // instancia um repositorio para Banco Sementes
        $repository = new TRepository('vw_banco_sementeRecord');
        // cria um criterio de selecao, ordenado pelo id
        $criteria = new TCriteria;


        if ($mesorregiao_id <> '0') {
            $criteria->add(new TFilter('mesorregiao_id', '=', $mesorregiao_id));
            $criteria->setProperty('order', 'nome_mesorregiao, nome_microregiao, municipio_nome');
        }


        // carrega os objetos de acordo com o criterio
        $rows = $repository->load($criteria);
        $meso = '';
        $micro = '';
        $mun = '';

        $totalprod = 0;
        $totalbanco = 0;


        $tlprodBanco = 0;
        $tlprodMunicipio = 0;
        $tlprodMicro = 0;
        $tlprodMeso = 0;
        $tlbancMunicipio = 0;
        $tlbancMicro = 0;
        $tlbancMeso = 0;


        if ($rows) {
            // percorre os objetos retornados
            foreach ($rows as $row) {
                $this->SetFont('Arial', 'B', 10);






                if ($mun != $row->municipio_nome) {
                    $this->SetX("10");
                    if ($tlbancMunicipio != 0) {
                        $this->Cell(0, 5, utf8_decode('Total de Bancos no Município: ' . $tlbancMunicipio . '   Total de Produtores no Município:' . $tlprodMunicipio), 0, 1, 'L');
                        $tlbancMunicipio = 0;
                        $tlprodMunicipio = 0;
                    }
                }



                if ($micro != $row->nome_microregiao) {
                    $this->SetX("10");
                    if ($tlbancMicro != 0) {
                        $this->Cell(0, 5, utf8_decode('Total de Bancos no Microregião: ' . $tlbancMicro . '   Total de Produtores no Microregião:' . $tlprodMicro), 0, 1, 'L');
                        $tlbancMicro = 0;
                        $tlprodMicro = 0;
                    }

                    $this->Cell(0, 5, utf8_decode('Microregião: ' . substr($row->nome_microregiao, 0, 35)), 0, 1, 'L');
                    $micro = $row->nome_microregiao;
                }

                if ($meso != $row->nome_mesorregiao) {
                    $this->SetX("10");
                    if ($tlbancMeso != 0) {
                        $this->Cell(0, 5, utf8_decode('Total de Bancos no Mesorregião: ' . $tlbancMeso . '   Total de Produtores no Mesorregião:' . $tlprodMeso), 0, 1, 'L');
                        $tlbancMeso = 0;
                        $tlprodMeso = 0;
                    }

                    $this->Cell(0, 5, utf8_decode('Mesorregião: ' . substr($row->nome_mesorregiao, 0, 35)), 0, 1, 'L');
                    $meso = $row->nome_mesorregiao;
                }

                if ($mun != $row->municipio_nome) {

                    $this->SetX("10");

                    $this->Cell(0, 5, utf8_decode('Município: ' . substr($row->municipio_nome, 0, 35)), 0, 1, 'L');
                    $mun = $row->municipio_nome;
                    $this->SetX("15");
                    $this->Cell(0, 5, utf8_decode("Comunidade"), 0, 0, 'L');

                    $this->SetX("80");
                    $this->Cell(0, 5, utf8_decode("Banco de Sementes"), 0, 0, 'L');

                    $this->SetX("150");
                    $this->Cell(0, 5, utf8_decode("N° Prod."), 0, 0, 'L');

                    $this->SetX("180");
                    $this->Cell(0, 5, utf8_decode("Presidente"), 0, 0, 'L');

                    $this->SetX("245");
                    $this->Cell(0, 5, utf8_decode("Latitude"), 0, 0, 'L');

                    $this->SetX("265");
                    $this->Cell(0, 5, utf8_decode("Longitude"), 0, 1, 'L');

                    $this->Cell(0, 0, '', 1, 1, 'L');
                    $this->Ln();

                }

                $this->SetFont('Arial', '', 10);
                $this->SetX("10");
                $this->Cell(0, 5, utf8_decode(substr($row->comunidade_nome, 0, 30)), 0, 0, 'L');

                $this->SetX("80");
                $this->Cell(0, 5, utf8_decode(substr($row->nome_bancosemente, 0, 30)), 0, 0, 'L');

                $this->SetX("155");
                $this->Cell(0, 5, utf8_decode(substr($row->produtores, 0, 30)), 0, 0, 'L');

                $this->SetX("180");
                $this->Cell(0, 5, utf8_decode(substr($row->presidente, 0, 30)), 0, 0, 'L');

                $this->SetX("245");
                $this->Cell(0, 5, utf8_decode(substr($row->latitude, 0, 10)), 0, 0, 'L');

                $this->SetX("265");
                $this->Cell(0, 5, utf8_decode(substr($row->longitude, 0, 10)), 0, 1, 'L');


                //incrementar os contadores
                $totalprod += $row->produtores;
                $tlprodMunicipio += $row->produtores;
                $tlprodBanco += $row->produtores;
                $tlprodMicro += $row->produtores;
                $tlprodMeso += $row->produtores;

                $tlbancMunicipio++;
                $tlbancMicro++;
                $tlbancMeso++;
                $totalbanco++;


                if ($produtor == 'on') {
                    $repository1 = new TRepository('vw_banco_semente_produtorRecord');
                    $criteria1 = new TCriteria;
                    $criteria1->add(new TFilter('bancosemente_id', '=', $row->bancosemente_id));
                    $rows2 = $repository1->load($criteria1);

                    if ($rows2) {

                        // percorre os objetos retornados
                        $this->Ln();
                        $this->SetX("25");
                        $this->Cell(0, 5, utf8_decode("CPF Produtor"), 0, 0, 'L');

                        $this->SetX("60");
                        $this->Cell(0, 5, utf8_decode("Nome Produtor"), 0, 0, 'L');

                        $this->SetX("150");
                        $this->Cell(0, 5, utf8_decode("DAP Produtor"), 0, 1, 'L');

                        $this->Cell(0, 0, '', 1, 0, 'L');
                        $this->Ln();
                        foreach ($rows2 as $row2) {
                            $this->SetX("25");
                            $this->Cell(0, 5, utf8_decode($row2->cpf_produtor), 0, 0, 'L');
                            $this->SetX("60");
                            $this->Cell(0, 5, utf8_decode($row2->nome_produtor), 0, 0, 'L');
                            $this->SetX("150");
                            $this->Cell(0, 5, utf8_decode($row2->dap_produtor), 0, 1, 'L');
                        }
                    }
                }
            }
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(0, 5, utf8_decode('Total de Bancos no Município: ' . $tlbancMunicipio . '   Total de Produtores no Município:' . $tlprodMunicipio), 0, 1, 'L');
            $this->Cell(0, 5, utf8_decode('Total de Bancos no Microregião: ' . $tlbancMicro . '   Total de Produtores no Microregião:' . $tlprodMicro), 0, 1, 'L');
            $this->Cell(0, 5, utf8_decode('Total de Bancos no Mesorregião: ' . $tlbancMeso . '   Total de Produtores no Mesorregião:' . $tlprodMeso), 0, 1, 'L');

            $this->Cell(0, 5, utf8_decode('Total de Bancos desta Listagem: ' . $totalbanco . '   Total de Produtores desta Listagem:' . $totalprod), 0, 1, 'L');
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
                $texto = $_SESSION['empresa_sitio'];
            
                $this->Cell(0, 0, '', 1, 1, 'L');

                $this->Cell(0, 5, $texto, 0, 0, 'L');
                $this->Cell(0, 5, $conteudo, 0, 0, 'R');
                $this->Ln();
            }

}

//Instanciation of inherited class
$pdf = new RelatorioBancoSementesPDF("L", "mm", "A4");

//define o titulo
$pdf->SetTitle("Relatorio Banco Sementes");

//assunto
$pdf->SetSubject("Relatorio Banco Sementes");

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);
$pdf->ColumnDetail();
$file = "app/reports/RelatorioBancoSementesPDF".$_SESSION['servidor_id'].".pdf";

//abrir pdf
$pdf->Output($file);
$pdf->openFile($file);
?>  