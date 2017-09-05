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
    <?php

/*
 * classe GeraRelatorioSolicitacaoList
 * Cadastro de GeraRelatorioSolicitacao: Contem a listagem e o formulario de busca
 */
//ini_set('display_errors', 1);
//ini_set('display_startup_erros', 1);
//error_reporting(E_ALL);

include_once 'app/lib/FusionCharts/FusionCharts.php';

class DashAposentadoriaGraficoList extends TPage
{
    /*
     * metodo construtor
     * Cria a pagina e o Grafico
     */

    public function __construct()
    {

        parent::__construct();
    }

    function onReload()
    {

        echo "<div style='font-size: 15px; text-shadow: 0 1px 1px #000000;'>
                     <h2 align='center' ><span style='color: blue;'>QUANTITATIVO SERVIDORES APOSENTADORIA</span></h2>
                </div>";

        // inicia transacao com o banco 'pg_ceres'
        TTransaction::open('pg_ceres');

        // instancia um repositorio para Servidor
        $repository = new TRepository('vw_total_aposentadoriaRecord');

        // cria um criterio de selecao, ordenado pelo id
        $criteria = new TCriteria;
        $criteria->add(new TFilter('empresa_id', '=', $_SESSION['empresa_id']));
        $criteria->setProperty('order', 'situacao, total desc');
        // carrega os objetos de acordo com o criterio
        $cadastros = $repository->load($criteria);

        echo "<script LANGUAGE='Javascript' SRC='app/lib/FusionCharts/FusionCharts.js'></script>";
        echo "<script LANGUAGE='Javascript' SRC='app/lib/FusionCharts/FusionChartsExportComponent.js'></script>";


        //$strXML will be used to store the entire XML document generated
        //Generate the chart element
        // instancia um repositorio para Servidor
        $repository2 = new TRepository('vw_dash_monta_listagem_dashboard_indRecord');
        // cria um criterio de selecao, ordenado pelo id
        $criteria2 = new TCriteria;
        //adiciona o criterio
        // $criteria2->add(new TFilter('login', '=', $_SESSION['usuario']));
        //$criteria2->add(new TFilter('grafico_id', '=', $_GET['dash']));
        // carrega os objetos de acordo com o criterio
        // $cadastros2 = $repository2->load($criteria2);

        $results2 = $repository2->load($criteria2);

        global $valor_meta3;


        $nome_meta3 = "";
        $valor_meta3 = "";
        $tipometa3 = "";


        if ($results2) {
            // percorre os objetos retornados
            foreach ($results2 as $result2) {

                if ($result2->nome_meta == "META ESTADUAL DO PERCENTUAL DE SERVIDORES APOSENTADORIA") {
                    $nome_meta3 = $result2->nome_meta;
                    $valor_meta3 = intval($result2->valormeta);
                    $tipometa3 = $result2->nome_tipometa;
                }
            }
        }


        // instancia um repositorio para Servidor
        $repository3 = new TRepository('vw_total_aposentadoriaRecord');

        // cria um criterio de selecao, ordenado pelo id
        $criteria3 = new TCriteria;
        $criteria3->add(new TFilter('empresa_id', '=', $_SESSION['empresa_id']));
        // carrega os objetos de acordo com o criterio
        $cadastros3 = $repository3->load($criteria3);


        if ($cadastros3) {
            // percorre os objetos retornados
            foreach ($cadastros3 as $reg3) {
                //antes de armazenar verifica se algum campo eh requerido e nao foi informado
                $dados3 = $reg3->toArray();
                //Generate <set label='..' value='..' />
                if ($dados3['situacao'] == 'TODOS') {
                    $total = $dados3['total'];
                } else if ($dados3['situacao'] == 'APTO') {
                    $aptos = $dados3['total'];
                } else if ($dados3['situacao'] == 'INAPTO') {
                    $inaptos = $dados3['total'];
                }
            }
        }


        // finaliza a transacao
        TTransaction::close();

        $perc3 = ($aptos / $total) * 100;


        //INICIO GAUGE
        $label = "<h3 align='center'>"
            . "<b>SERVIDORES APTOS A APOSENTADORIA</b><br />"
            . "IDEAL: " . number_format($valor_meta3, 0) . "% / "
            . "ATUAL: " . number_format($perc3, 0) . "%.</h3>";


        $strXML2 = "<chart lowerLimit='0' gaugeFillMix='{light-10},{light-30},{light-20},{dark-5},{color},{light-30},{light-20},{dark-10}' gaugeFillRatio='' upperLimit='100' majorTMNumber='12' minorTMNumber='3' gaugeStartAngle='225'gaugeEndAngle='-45' lowerLimitDisplay='Bom' upperLimitDisplay='Ruim' numberSuffix='%' showValue='1'>";
        //Generate <set label='..' value='..' />
        $strXML2 .= "<colorRange>"
            . "<color minValue='0' maxValue='5' code='8BBA00'/>"
            . "<color minValue='5' maxValue='20' code='F6BD0F'/>"
            . "<color minValue='20' maxValue='100' code='FF654F'/>"
            . "</colorRange>";

        $strXML2 .= "<dials><dial value='" . number_format($perc3, 0) . "' link='' baseWidth='15' topWidth='2'/></dials>";
        //Finally, close <chart> element
        $strXML2 .= "</chart>";

        echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6' style='margin-top:20px'>";
        echo $label;
        echo renderChart("app/lib/FusionWidgets/AngularGauge.swf", "", $strXML2, "chart3", "100%", 250, false, false);
        echo "</div>";


        //FIM GAUGE
        //===============================================================================================//
        //INICIO GRAFICO PIZZA

        $label2 = "<h3 align='center'><b>SERVIDORES x APOSENTADORIA</b><br />"
            . "TOTAL: {$total} SERVIDORES EM ATIVIDADE</h3>";
        $strXML = "<chart id='chart' caption='' subCaption='' xAxisName='Situa��o' yAxisName='Quantidade de Servidores' showLabels='0' showValues='1' showLegend='1'  legendPosition='RIGHT' chartrightmargin='20'  basefontsize='11'  formatNumberScale='0' bgratio='0' startingangle='100' animation='1'>";

        if ($cadastros) {
            // percorre os objetos retornados
            foreach ($cadastros as $reg) {
                //antes de armazenar verifica se algum campo eh requerido e nao foi informado
                $dados = $reg->toArray();
                //Generate <set label='..' value='..' />
                if ($dados['situacao'] != 'TODOS') {
                    if ($dados['situacao'] == 'INAPTO') {
                        $strXML .= "<set label='" . $dados['situacao'] . "' value='" . $dados['total'] . "'  color='82d934' link='n-index.php?class=RelatorioAposendadoriaPDF%26situacao=" . $dados['situacao'] . "' />";
                    } else {
                        $strXML .= "<set label='" . $dados['situacao'] . "' value='" . $dados['total'] . "'  color='f44127' link='n-index.php?class=RelatorioAposendadoriaPDF%26situacao=" . $dados['situacao'] . "' />";
                    }
                }
            }
        }
        //Finally, close <chart> element
        $strXML .= "</chart>";


        //Create the chart - Pie 3D Chart with data from $strXML
        echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6' style='margin-top:20px'>";
        echo $label2;
        echo renderChart("app/lib/FusionCharts/Pie2D.swf", "", $strXML, "Chart01", "100%", 250, false, false);
        echo "</div>";


        //FIM GRAFICO PIZZA
        //==============================================================================================//
        //INICIO GRAFICO COLUNAS        
        // inicia transacao com o banco 'pg_ceres'
        TTransaction::open('pg_ceres');

        // instancia um repositorio para Servidor
        $repository_regional = new TRepository('vw_aposentadoria_por_regionalRecord');

        // cria um criterio de selecao, ordenado pelo id
        $criteria_regional = new TCriteria;
        $criteria_regional->add(new TFilter('empresa_id', '=', $_SESSION['empresa_id']));
        $criteria_regional->setProperty('order', 'nome_regional');

        $objRegional = $repository_regional->load($criteria_regional);

        $regional_aux = "";
        $dados3 = 0;

        //$strXML2 = "<chart id='chart' caption='PERCENTUAL DE APOSENTADORIA' subcaption='SITUAÇÃO ATUAL: {$qtd_eng} ENGENHEIROS AGRÔNOMO EM ATIVIDADE'  xAxisName='Cargos' yAxisName='Quantidade'  pieSliceDepth='10' showBorder='0' formatNumberScale='0' numberSuffix='' animation='1' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter1' exportFileName='DashBoard Beneficiario Leite Posto' >";

        $strXML2 = "<chart id='chart' caption='SERVIDORES APTOS E INAPTOS A APOSENTADORIA POR REGIONAL' xAxisName='REGIONAL' yAxisName='QUANTIDADE'  pieSliceDepth='10' showBorder='0' formatNumberScale='0' numberSuffix='' animation='1' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter1' exportFileName='DashBoard Servidores Aposentadoria Regional' >";

        if ($objRegional) {
            $strXML2 .= "<categories>";
            foreach ($objRegional as $reg3) {
                $dados3 = $reg3->toArray();

                if ($dados3['nome_regional'] != $regional_aux) {
                    $strXML2 .= "<category label='{$dados3['nome_regional']}' />";
                    $regional_aux = $dados3['nome_regional'];
                }
            }
            $strXML2 .= "</categories>";
        }

        // cria um criterio de selecao, ordenado pelo id
        $criteria_regional2 = new TCriteria;
        $criteria_regional2->add(new TFilter('situacao', '=', 'INAPTO'));
        $criteria_regional2->add(new TFilter('empresa_id', '=', $_SESSION['empresa_id']));
        $criteria_regional2->setProperty('order', 'nome_regional');


        // carrega os objetos de acordo com o criterio
        $objRegional2 = $repository_regional->load($criteria_regional2);

        $regional_aux = "";
        $dados3 = 0;
        $qtd_inapto = 0;
        if ($objRegional2) {
            $strXML2 .= "<dataset seriesName='INAPTOS A SE APOSENTAR' color='82d934' showValues='1'>";
            foreach ($objRegional2 as $reg3) {
                $dados3 = $reg3->toArray();

                if ($dados3['nome_regional'] != $regional_aux) {

                    $strXML2 .= "<set value='{$dados3['qtd']}' link='n-index.php?class=RelatorioAposendadoriaporRegionalPDF%26nome_regional=" . $dados3['nome_regional'] . "' />";
                    // $strXML2 .= "<set value='{$dados3['qtd']}' link='n-index.php?class=RelatorioAposendadoriaporRegionalPDF%26nome_regional=" . $dados3['nome_regional'] . "&situacao=" . $dados3['situacao'] . "' />";

                    //$regional_aux = $dados3['nome_regional'];
                }
                $regional_aux = $dados3['nome_regional'];
            }
            $strXML2 .= "</dataset>";
        }


        // cria um criterio de selecao, ordenado pelo id
        $criteria_regional3 = new TCriteria;
        $criteria_regional3->add(new TFilter('situacao', '=', 'APTO'));
        $criteria_regional3->add(new TFilter('empresa_id', '=', $_SESSION['empresa_id']));
        $criteria_regional3->setProperty('order', 'nome_regional');

        // carrega os objetos de acordo com o criterio
        $obj_regional3 = $repository_regional->load($criteria_regional3);


        $regional_aux = "";
        $dados3 = 0;
        $qtd_apto = 0;

        if ($obj_regional3) {
            $strXML2 .= "<dataset seriesName='APTOS A SE APOSENTAR' color='f44127' showValues='1'>";
            foreach ($obj_regional3 as $reg3) {
                $dados3 = $reg3->toArray();

                if ($dados3['nome_regional'] != $regional_aux) {

                    $strXML2 .= "<set value='{$dados3['qtd']}' link='n-index.php?class=RelatorioAposendadoriaporRegionalPDF%26nome_regional=" . $dados3['nome_regional'] . "' />";
                    //$regional_aux = $dados3['nome_regional'];
                }
                $regional_aux = $dados3['nome_regional'];
            }
            $strXML2 .= "</dataset>";
        }

        $strXML2 .= "</chart>";

        echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='margin-top:50px'>";
        echo renderChart("app/lib/FusionCharts/StackedColumn3D.swf", "", $strXML2, "Chart03", "100%", 450, false, false);
        echo "</div>";

        //FIM GRAFICO COLUNA
        //===========================================================================================//
        //INICIO GRAFICO BARRAS
        // inicia transacao com o banco 'pg_ceres'

        $conn = TTransaction::get(); // obtém a conexão

        $sth = $conn->prepare('select
                rc.id AS requisito_cargo_id,
                rc.nome AS nome_requisito_cargo,
                rc.nome_abreviado,
                CASE
                    WHEN NOT EXISTS (SELECT vw.qtd FROM vw_aposentadoria_por_requisito_cargo vw WHERE (vw.requisito_cargo_id = rc.id) and vw.situacao =\'APTO\' and vw.empresa_id = ? ) THEN 0
                    ELSE (SELECT vw.qtd FROM vw_aposentadoria_por_requisito_cargo vw WHERE (vw.requisito_cargo_id = rc.id) and vw.situacao =\'APTO\' and vw.empresa_id = ?)
                END as APTO ,
                CASE
                    WHEN NOT EXISTS (SELECT vw.qtd FROM vw_aposentadoria_por_requisito_cargo vw WHERE (vw.requisito_cargo_id = rc.id) and vw.situacao =\'INAPTO\' and vw.empresa_id =?) THEN 0
                    ELSE (SELECT vw.qtd FROM vw_aposentadoria_por_requisito_cargo vw WHERE (vw.requisito_cargo_id = rc.id) and vw.situacao =\'INAPTO\' and vw.empresa_id =?)
                END as INAPTO
                 from requisitocargo rc order by rc.nome');

        $sth->execute([$_SESSION['empresa_id'], $_SESSION['empresa_id'], $_SESSION['empresa_id'], $_SESSION['empresa_id']]);
        $objectsRecCargo = $sth->fetchAll();

        $req_aux = "";

        //Create the chart - Pie 3D Chart with data from $strXML
        $strXML3 = "<chart id='chart' caption='SERVIDORES APTOS E INAPTOS A APOSENTADORIA POR FORMAÇÃO' xAxisName='FORMAÇÃO' yAxisName='QUANTIDADE'  pieSliceDepth='10' showBorder='0' formatNumberScale='0' numberSuffix='' animation='1' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter1' exportFileName='DashBoard Servidores Aposentadoria Regional' >";

        $strXML3_aux_inaptos = "<dataset seriesName='INAPTOS A SE APOSENTAR' color='82d934' showValues='1'>";
        $strXML3_aux_aptos = "<dataset seriesName='APTOS A SE APOSENTAR' color='f44127' showValues='1'>";

        if ($objectsRecCargo) {
            $strXML3_aux = "<categories>";
            foreach ($objectsRecCargo as $item4) {

                if ($item4['nome_requisito_cargo'] != $req_aux) {
                    $strXML3_aux .= "<category label='{$item4['nome_requisito_cargo']}' />";

                    $strXML3_aux_aptos .= "<set value='{$item4['apto']}' link='n-index.php?class=RelatorioAposendadoriaporFormacaoPDF%26nome_requisito_cargo=" . $item4['nome_requisito_cargo'] . "'/>";
                    $strXML3_aux_inaptos .= "<set value='{$item4['inapto']}' />";

                    $req_aux = $item4['nome_requisito_cargo'];
                }
            }
            $strXML3_aux .= "</categories>";
            $strXML3_aux_aptos .= "</dataset>";
            $strXML3_aux_inaptos .= "</dataset>";
        }

        $strXML3 .= $strXML3_aux;
        $strXML3 .= $strXML3_aux_inaptos;
        $strXML3 .= $strXML3_aux_aptos;

        $strXML3 .= "</chart>";

        echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='margin-top:50px'>";
        echo renderChart("app/lib/FusionCharts/StackedBar3D.swf", "", $strXML3, "Chart04", "100%", 1000, false, false);
        echo "</div>";


        //FIM GRAFICO BARRAS
        $this->loaded = true;
    }

    /*
     * metodo show()
     * Exibe a pagina
     */

    function show()
    {
        // carrega os dados no datagrid
        $this->onReload();
        //chama o metodo show da super classe
        parent::show();
    }

}

?>


    function onShow()
    {
    }

    function show()
    {
        parent::show();
    }

}