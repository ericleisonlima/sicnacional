<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_erros', 1);
//error_reporting(E_ALL);

include_once 'app/lib/FusionCharts/FusionCharts.php';
class GeraGraficoStats extends TPage {

    private $form;
    public function __construct() {

        parent::__construct();
        $this->form = new TQuickForm('form_grafico');        
        $this->form->addQuickAction('Voltar', new TAction(array('crnGeraCasoMicroGraph', 'onReload')), 'ico_back.png');
        parent::add($this->form);

    }

    function onReload() {

        TPage::include_js('app/lib/FusionCharts/FusionCharts.js');
        TPage::include_js('app/lib/FusionCharts/FunctionDashCharts.js');

        TTransaction::open('pg_ceres');

        $municipio_id = new MunicipioRecord($_REQUEST['municipio_id']);
        echo "<div style='font-size: 15px; text-shadow: 0 1px 1px #000000;'>
        <h2 align='center' ><span style='color: red;'>". $municipio_id->nome . "</span></h2>
        </div>";

        $repository = new TRepository('Vw_crn_cras_graf_relat_mensalRecord');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('municipio_id', '=', $_REQUEST['municipio_id']));
        $criteria->add(new TFilter('ano', '=', $_REQUEST['ano']));
        $criteria->setProperty('order', 'mes_id');
        
        $cadastros = $repository->load($criteria);

        $mes = '';
        $uni = 0;
        $i = 0;

        if ($cadastros) {

            foreach ($cadastros as $reg) {

                $dados = $reg->toArray();

                if($i != 0){

                    if( $mes != $dados['mes']  ){

                        $this->loadGraph($arrData,  $mes, $uni); 
                        $uni++;
                        $arrData = '';

                    }
                }

                $arrData[$i][1] = $dados['mes'];
                $arrData[$i][2] = $dados['tot_fam_microcef'];
                $arrData[$i][3] = $dados['bpc_acomp_crianca']; 
                $arrData[$i][4] = $dados['bpc_acomp_adolescente'];
                $arrData[$i][5] = $dados['volant_paif_tot_atendim'];
                $arrData[$i][6] = $dados['volant_paif_tot_fam_atendim'];
                $arrData[$i][7] = $dados['volant_servic_crian_0_a_6'];
                $arrData[$i][8] = $dados['volant_servic_crian_7_a_14'];
                $arrData[$i][9] = $dados['volant_servic_crian_15_a_17'];
                $arrData[$i][10] = $dados['volant_servic_idoso'];
                $arrData[$i][11] = $dados['volant_palestra'];
                $arrData[$i][12] = $dados['volant_pessoa_deficiencia'];

                $mes = $dados['mes'];  

                if( count($cadastros) == $i+1 ){
                    $this->loadGraph($arrData,  $mes, $uni); 
                    $uni++;
                    $arrData = '';
                }       


                $i++;            

            }



        } else {

            new TMessage('info', 'N&atilde;o existem dados para essa pesquisa', new TAction(array('crnGeraCasoMicroGraph', 'onReload')));
        }

        TTransaction::close();
    }

    function loadGraph($arrData,  $titulo, $id_grafico) {

        $strXML = "<chart id='chart' caption='Mês: " . strtoupper($titulo) . "'  formatNumberScale='0'  xAxisName='INDICADOR' yAxisName='QUANTIDADE ' showValues='1' showBorder='0' >";

        $strMes = "<categories>";

        $strData2 = "<dataset color='1E90FF' seriesName='CASOS MICROCEFÁLIA'>";
        $strData3 = "<dataset color='4682B4'  seriesName='BPC CRIANÇAS'>";
        $strData4 = "<dataset color='98FB98'  seriesName='BPC ADOLESÇENTES'>";
        $strData5 = "<dataset color='BDB76B'  seriesName='E.V. TOTAL DE ATEND. NO MÊS'>";
        $strData6 = "<dataset color='EEE8AA'  seriesName='E.V.FAMÍLIAS EM ACOMP. PAIF'>";
        $strData7 = "<dataset color='FFFF00'  seriesName='E.V.FORT. DE VINC. CRIANÇAS 0-6'>";
        $strData8 = "<dataset color='FF8C00'  seriesName='E.V.FORT. DE VINC. CRIANÇAS 7-14'>";
        $strData9 = "<dataset color='CAE1FF'  seriesName='E.V.FORT. DE VINC. CRIANÇAS 15-17'>";
        $strData10 = "<dataset color='FF82AB'  seriesName='E.V.FORT. DE VINC. IDOSOS'>";
        $strData11 = "<dataset color='CD853F'  seriesName='E.V.TREINAMENTOS NÃO CONTINUADOS'>";
        $strData12 = "<dataset color='FF0000'  seriesName='E.V.PESSOAS COM DEF. PARTICIPANTES'>";

        foreach ($arrData as $arSubData) {


            $strMes .= "<category  name='" . $arSubData[1] . "' />";
            $strData2 .= "<set color='1E90FF' value='" . $arSubData[2] . "' />";
            $strData3   .= "<set color='4682B4'  value='" . $arSubData[3] . "' />";
            $strData4   .= "<set color='98FB98'  value='" . $arSubData[4] . "' />";
            $strData5   .= "<set color='BDB76B'  value='" . $arSubData[5] . "' />";
            $strData6   .= "<set color='EEE8AA'  value='" . $arSubData[6] . "' />";
            $strData7   .= "<set color='FFFF00'  value='" . $arSubData[7] . "' />";
            $strData8   .= "<set color='FF8C00'  value='" . $arSubData[8] . "' />";
            $strData9   .= "<set color='CAE1FF'  value='" . $arSubData[9] . "' />";
            $strData10   .= "<set color='FF82AB'  value='" . $arSubData[10] . "' />";
            $strData11   .= "<set color='CD853F'  value='" . $arSubData[11] . "' />";
            $strData12   .= "<set color='FF0000'  value='" . $arSubData[12] . "' />";
        }

        $strMes .= "</categories>";
        $strData2   .= "</dataset>";
        $strData3   .= "</dataset>";
        $strData4   .= "</dataset>";
        $strData5   .= "</dataset>";
        $strData6   .= "</dataset>";
        $strData7   .= "</dataset>";
        $strData8   .= "</dataset>";
        $strData9   .= "</dataset>";
        $strData10   .= "</dataset>";
        $strData11   .= "</dataset>";
        $strData12   .= "</dataset>";

        $strXML .= 
        $strMes . 
        $strData2 . 
        $strData3 . 
        $strData4 .
        $strData5 .
        $strData6 .
        $strData7 .
        $strData8 .
        $strData9 .
        $strData10 .
        $strData11 .
        $strData12 .
        "</chart>";

        echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='margin-top:50px'>";
        echo renderChart("app/lib/FusionCharts/MSColumn3D.swf", "", $strXML, "chart". $id_grafico , "100%", 400, false, false);
        echo "</div>";

    }

    

}

?>