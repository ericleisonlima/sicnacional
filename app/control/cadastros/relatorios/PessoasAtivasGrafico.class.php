<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

include_once 'app/lib/FusionCharts/FusionCharts.php';

class PessoasAtivasGrafico extends TPage
{

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