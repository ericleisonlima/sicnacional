<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_erros', 1);
//error_reporting(E_ALL);

include_once 'app/lib/FusionCharts/FusionCharts.php';

class PessoasAtivasGrafico extends TPage {
    
    private $form;

    public function __construct() {

        parent::__construct();

        $this->form = new TQuickForm('form_grafico');        

        parent::add($this->form);
    }

    function onReload() {

    TPage::include_js('app/lib/FusionCharts/FusionCharts.js');
    TPage::include_js('app/lib/FusionCharts/FunctionDashCharts.js');

    TTransaction::open('dbsic');

    echo "<div style='font-size: 15px; text-shadow: 0 1px 1px #000000;'>
        <h2 align='center' ><span style='color: red;'></span></h2>
        <br>
        <h3 align='center' ><span style='color: red;'></span></h3>
    </div>";

    $repository = new TRepository('vw_pacientes_ativos_anoRecord');
    $criteria = new TCriteria; 
    //$criteria->add(new TFilter('ano', '=', $_REQUEST['ano']));
    $criteria->setProperty('order', 'ano');

    $cadastros = $repository->load($criteria);

    $atividade = '';
    $uni = 0;
    $i = 0;

    if ($cadastros) {

        foreach ($cadastros as $reg) {

            $dados = $reg->toArray();

            /*if($i != 0){
                
                if( $atividade != $dados['ano']  ){

                    $this->loadGraph($arrData,  $atividade, $uni); 
                    $uni++;
                    $arrData = '';

                }
            }*/

            $arrData[$i][1] = $dados['ano'];
            $arrData[$i][2] = $dados['pacientesano'];

            $atividade = $dados['ano'];  

            if( count($cadastros) == $i+1 ){
                $this->loadGraph($arrData,  $atividade, $uni); 
                $uni++;
                $arrData = '';
            }       

                            
            $i++;            
            
        }



    } else {

        //new TMessage('info', 'N&atilde;o existem dados para essa pesquisa', new TAction(array('GeraRelatorioPacientesAtivosAno', 'onReload')));
    }

    TTransaction::close();
}

function loadGraph($arrData, $id_grafico) {

    $strXML = "<chart id='chart' caption='Pessoas Ativas por Ano'  formatNumberScale='0'  yAxisName='QUANTIDADE ' showValues='1' showBorder='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter1' exportFileName='DashBoard'>";

    $strCategories = "<categories>";

    $strDataCurr = "<dataset color='28D42E' >";

    foreach ($arrData as $arSubData) {
        
//link='n-index.php?class=RelatorioAposendadoriaporRegionalPDF%26nome_regional=" . $dados3['nome_regional'] . "' />"

        $strCategories .= "<category name='" . $arSubData[1] . "' />";
        //$strDataCurr   .= "<set  link='n-index.php?class=RelatorioParticipantePDF%26ppa_id=" . $arSubData[1]."' color='28D42E' value='" . $arSubData[2] . "' />";
         $strDataCurr .= "<set color='28D42E' value='" . $arSubData[2] . "' />";
    }

    $strCategories .= "</categories>";
    $strDataCurr   .= "</dataset>";

    $strXML .= $strCategories . $strDataCurr . "</chart>";

    echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='margin-top:50px'>";
    echo renderChart("app/lib/FusionCharts/MSColumn3D.swf", "", $strXML, "chart". $id_grafico , "100%", 400, false, false);
    echo "</div>";

}
    function show() {
        $this->onReload();
        parent::show();
    }
}

?>