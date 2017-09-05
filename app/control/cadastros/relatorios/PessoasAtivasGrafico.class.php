<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

include_once 'app/lib/FusionCharts/FusionCharts.php';

class PessoasAtivasGrafico extends TPage
{

    private $ano;

    private $table;

    //GRAPH INFO
    private $graphLabel;

    private $itemOption1;
    private $itemOption2;

    public function __construct()
    {

        parent::__construct();

        $this->ano = $_REQUEST['ano'];
    

            $this->table = 'vw_pacientes_ativos_anoRecord.class';
     

        echo "<script LANGUAGE='Javascript' SRC='app/lib/FusionCharts/FusionCharts.js'></script>";
        echo "<script LANGUAGE='Javascript' SRC='app/lib/FusionCharts/FusionChartsExportComponent.js'></script>";

        $this->header();

        $this->buildGraph('item_1');
        $this->buildGraph('item_2');
        $this->buildGraph('item_3');
        $this->buildGraph('item_4');
        $this->buildGraph('item_5');
        $this->buildGraph('item_6');
        $this->buildGraph('item_7');
        $this->buildGraph('item_8');
        $this->buildGraph('item_9');
        $this->buildGraph('item_10');
        $this->buildGraph('item_11');
        $this->buildGraph('item_12');

      
    }

    function header()
    {

        echo
        "<div style='font-size: 15px; text-shadow: 0 1px 1px #000000;'>
                <h2 align='center' ><span style='color: blue;'>PESSOAS ATIVAS POR ANO</span></h2>
        </div> <br>";

        $label = $this->ano . " / ";


        echo
            "<h3 align='center'>
            <b>" . $label . "</b>
        </h3>";

    }

    function buildGraph($itemName)
    {

        $this->setGraphInfo($itemName);

        TTransaction::open('dbsic');
        $conn = TTransaction::get();

        if ($this->ano) {
            $sth = $conn->prepare('
                        SELECT 
                            date_part(\'year\', f.data_visita) AS ano,
                            count(f.' . $itemName . ') AS qtde,
                            f.' . $itemName . ' AS item
                        FROM ' . $this->table . ' f
                            WHERE date_part(\'year\', f.data_visita) = ' . $this->ano . ' 
                        GROUP BY ano, f.' . $itemName . ';');
        } else if ($this->dataInicio && $this->dataFim) {
            $sth = $conn->prepare('
                        SELECT 
                            count(f.' . $itemName . ') AS qtde,
                            f.' . $itemName . ' AS item
                        FROM ' . $this->table . ' f
                            WHERE 
                                f.data_visita >= \'' . $this->dataInicio . '\' AND
                                f.data_visita <= \'' . $this->dataFim . '\'
                        GROUP BY f.' . $itemName . ';');
        }

        $sth->execute();

        $result = $sth->fetchAll();

        $strXML = "<chart id='chart_" . $itemName . "' caption='' subCaption='' xAxisName='' yAxisName='' showLabels='0' showValues='1' showLegend='1'  legendPosition='RIGHT' chartrightmargin='20'  basefontsize='11'  formatNumberScale='0' bgratio='0' startingangle='100' animation='1'>";

        if ($result) {
            foreach ($result as $item) {
                if ($item['item'] == $this->itemOption1) {
                    $strXML .= "<set label='" . $item['item'] . "' value='" . $item['qtde'] . "'  color='b3ffcc' />";
                } else if ($item['item'] == $this->itemOption2) {
                    $strXML .= "<set label='" . $item['item'] . "' value='" . $item['qtde'] . "'  color='99b3ff' />";
                }
            }
        }

        $strXML .= "</chart>";

        echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6' style='margin-top:50px'>";
        echo "<h5 align='center'><b>" . $this->graphLabel . "</b></h5>";
        echo renderChart("app/lib/FusionCharts/Pie2D.swf", "", $strXML, "Chart_" . $itemName, "100%", 250, false, false);
        echo "</div>";

    }

    function setGraphInfo($itemName)
    {

        if ($this->revendedora == 'REVENDEDORA REGISTRADA') {

            switch ($itemName) {

                case 'item_1':
                    $this->graphLabel = 'COMPROVAÇÃO DO COMÉRCIO DE AGROTÓXICOS';
                    $this->itemOption1 = 'SIM';
                    $this->itemOption2 = 'NÃO';

                    break;
                case 'item_2':
                    $this->graphLabel = 'CERTIFICADO DE COMPRA E VENDA DE AGROTÓXICOS';
                    $this->itemOption1 = 'CONFORME';
                    $this->itemOption2 = 'NÃO CONFORME';

                    break;
                case 'item_3':
                    $this->graphLabel = 'CONTROLE DE COMPRA E VENDA DE AGROTÓXICOS';
                    $this->itemOption1 = 'CONFORME';
                    $this->itemOption2 = 'NÃO CONFORME';

                    break;
                case 'item_4':
                    $this->graphLabel = 'REGISTRO DE PRODUTOS NO MAPA';
                    $this->itemOption1 = 'CONFORME';
                    $this->itemOption2 = 'NÃO CONFORME';

                    break;
                case 'item_5':
                    $this->graphLabel = 'PRODUTOS CADASTRADOS JUNTO À IDIARN';
                    $this->itemOption1 = 'CONFORME';
                    $this->itemOption2 = 'NÃO CONFORME';

                    break;
                case 'item_6':
                    $this->graphLabel = 'FRACIONAMENTO DE AGROTÓXICOS';
                    $this->itemOption1 = 'SIM';
                    $this->itemOption2 = 'NÃO';

                    break;
                case 'item_7':
                    $this->graphLabel = 'COMERCIO DE PRODUTOS ADULTERADOS/FALSIFICADOS';
                    $this->itemOption1 = 'SIM';
                    $this->itemOption2 = 'NÃO';

                    break;
                case 'item_8':
                    $this->graphLabel = 'AGROTÓXICOS IMPRÓPRIOS PARA USO';
                    $this->itemOption1 = 'SIM';
                    $this->itemOption2 = 'NÃO';

                    break;
                case 'item_9':
                    $this->graphLabel = 'RECEITA AGRONÔMICA';
                    $this->itemOption1 = 'CONFORME';
                    $this->itemOption2 = 'NÃO CONFORME';

                    break;
                case 'item_10':
                    $this->graphLabel = 'E.P.I. PARA MANUSEIO DE PRODUTOS AGROTÓXICOS';
                    $this->itemOption1 = 'CONFORME';
                    $this->itemOption2 = 'NÃO CONFORME';

                    break;
                case 'item_11':
                    $this->graphLabel = 'NOTA FISCAL DOS PRODUTOS ENCONTRADOS NA REVENDA';
                    $this->itemOption1 = 'CONFORME';
                    $this->itemOption2 = 'NÃO CONFORME';

                    break;
                case 'item_12':
                    $this->graphLabel = 'ARMAZENAMENTO DE AGROTÓXICOS';
                    $this->itemOption1 = 'CONFORME';
                    $this->itemOption2 = 'NÃO CONFORME';

                    break;
                case 'item_13':
                    $this->graphLabel = 'RESPONSABILIDADE TÉCNICA PARA A VENDA DE AGROTÓXICOS';
                    $this->itemOption1 = 'CONFORME';
                    $this->itemOption2 = 'NÃO CONFORME';

                    break;
                case 'item_14':
                    $this->graphLabel = 'OUTROS';
                    $this->itemOption1 = 'CONFORME';
                    $this->itemOption2 = 'NÃO CONFORME';

                    break;

            }

        } else if ($this->revendedora == 'REVENDEDORA NAO REGISTRADA') {
            $this->itemOption1 = 'SIM';
            $this->itemOption2 = 'NAO';

            switch ($itemName) {

                case 'item_1':
                    $this->graphLabel = 'A EMPRESA COMERCIALIZAVA OU ARMAZENAVA AGROTÓXICOS SEM POSSUIR REGISTRO NO IDIARN PARA TAL FINALIDADE';
                    break;
                case 'item_2':
                    $this->graphLabel = 'A EMPRESA COMERCIALIZAVA AGROTÓXICOS NÃO REGISTRADOS PELO MAPA';
                    break;
                case 'item_3':
                    $this->graphLabel = 'A EMPRESA COMERCIALIZAVA AGROTÓXICOS NÃO CADASTRADOS NO IDIARN';
                    break;
                case 'item_4':
                    $this->graphLabel = 'A EMPRESA COMERCIALIZAVA AGROTÓXICOS FRACIONADOS';
                    break;
                case 'item_5':
                    $this->graphLabel = 'A EMPRESA COMERCIALIZAVA AGROTÓXICOS ADULTERADOS/FALSIFICADOS';
                    break;
                case 'item_6':
                    $this->graphLabel = 'A EMPRESA COMERCIALIZAVA AGROTÓXICOS SEM APRESENTAÇÃO DA RECEITA AGRONÔMICA';
                    break;
                case 'item_7':
                    $this->graphLabel = 'A EMPRESA COMERCIALIZAVA PRODUTOS DOMISSANITÁRIOS/VETERINÁRIOS COMO AGROTÓXICOS/USO AGRÍCOLA';
                    break;
                case 'item_8':
                    $this->graphLabel = 'A EMPRESA COMERCIALIZAVA PRODUTOS OBSOLETOS (IMPOSSÍVEL IDENTIFICAR O FABRICANTE OU BANIDOS)';
                    break;
                case 'item_9':
                    $this->graphLabel = 'A EMPRESA COMERCIALIZAVA AGROTÓXICOS COM A DATA DE VALIDADE VENCIDA';
                    break;
                case 'item_10':
                    $this->graphLabel = 'A EMPRESA COMERCIALIZAVA AGROTÓXICOS EM EMBALAGENS IRREGULARES (VAZAMENTO, DEFORMADOS, SEM LACRE EXTERNO, ETC.)';
                    break;
                case 'item_11':
                    $this->graphLabel = 'A EMPRESA COMERCIALIZAVA AGROTÓXICOS COM RÓTULOS E/OU BULAS AUSENTES OU IRREGULARES (LETRAS E SÍMBOLOS DIMINUTOS, ETC.)';
                    break;
                case 'item_12':
                    $this->graphLabel = 'A EMPRESA ARMAZENAVA EMBALAGENS VAZIAS DE AGROTÓXICOS SEM AUTORIZAÇÃO PARA TAL ATIVIDADE';
                    break;

            }

        }

    }

    function onShow()
    {
    }

    function show()
    {
        parent::show();
    }

}