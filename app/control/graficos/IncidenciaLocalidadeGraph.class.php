<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

class IncidenciaLocalidadeGraph extends TPage {
    private $form;
    public function __construct() {
        parent::__construct();
        $html = new THtmlRenderer('app/resources/google_bar_chart.html');

        TTransaction::open('dbsic');
        $repository = new TRepository('vwIncidenciaLocalidadeRecord');
        $criteria = new TCriteria;
        //$criteria->add(new TFilter('acao_id', '=', $_REQUEST['acao_id']));
        //$criteria->setProperty('order', 'nome_atividade, nome_indicador');
        $cadastros = $repository->load($criteria);
        if ($cadastros) {

            foreach ($cadastros as $reg) {

                //$data = $reg->toArray();
                //$data = array();
                $data[] = [ 'Day', 'Value 1', 'Value 2', 'Value 3' ];

                $data['municipio_nome'] = $data['paciente_qtd'];
                //$data[] = [ 'Day 2',   120,       140,       160 ];
                //$data[] = [ 'Day 3',   140,       160,       180 ];
                
                //$data[2] = ;          
            }


        } else {

            new TMessage('info', 'N&atilde;o existem dados para essa pesquisa', new TAction(array('GraficoAtividadesPPAList', 'onShow')));
        }

        TTransaction::close();
            /*
        $data[] = [ 'Day', 'Value 1', 'Value 2', 'Value 3' ];
        */
        $panel = new TPanelGroup('Incidência por localidade');
        $panel->add($html);
        
        $html->enableSection('main', array('data'   => json_encode($data),
           'width'  => '100%',
           'height'  => '300px',
           'title'  => 'Accesses by day',
           'ytitle' => 'Accesses', 
           'xtitle' => 'Day'));
        
        $container = new TVBox;
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($panel);
        parent::add($container);


    }


    function loadGraph($arrData,  $titulo, $id_grafico) {

        $strXML = "<chart id='chart' caption='Atividade: " . strtoupper($titulo) . "'  formatNumberScale='0'  xAxisName='INDICADOR' yAxisName='QUANTIDADE ' showValues='1' showBorder='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter1' exportFileName='DashBoard Previsao Servidores Aposentadoria'>";

        $strCategories = "<categories>";

        $strDataCurr = "<dataset color='28D42E' seriesName='Previsto'>";
    //$strDataPrev = "<dataset color='FF0000'  seriesName='Realizado'>";

        foreach ($arrData as $arSubData) {

//link='n-index.php?class=RelatorioAposendadoriaporRegionalPDF%26nome_regional=" . $dados3['nome_regional'] . "' />"

            $strCategories .= "<category name='" . $arSubData[1] . "' />";
        //$strDataCurr   .= "<set  link='n-index.php?class=RelatorioParticipantePDF%26ppa_id=" . $arSubData[1]."' color='28D42E' value='" . $arSubData[2] . "' />";
            $strDataCurr .= "<set   color='28D42E' value='" . $arSubData[2] . "' />";
        //$strDataPrev   .= "<set link='n-index.php?class=RelatorioParticipantePDF%26pex_ppa_id=" . $_REQUEST['pex_ppa_id'] . '%26pex_pt_id='. $_REQUEST['pex_pt_id'] . "' color='FF0000'  value='" . $arSubData[3] . "' />";
        }

        $strCategories .= "</categories>";
        $strDataCurr   .= "</dataset>";
    //$strDataPrev   .= "</dataset>";

        $strXML .= $strCategories . $strDataCurr . "</chart>";

        echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='margin-top:50px'>";
        echo renderChart("app/lib/FusionCharts/MSColumn3D.swf", "", $strXML, "chart". $id_grafico , "100%", 400, false, false);
        echo "</div>";

    }

}



