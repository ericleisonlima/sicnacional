<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

include_once 'app/lib/FusionCharts/FusionCharts.php';

class TipoSanguineoGrafico extends TPage
{

    private $tipo;
    private $quantidade;

    private $table;

    //GRAPH INFO
    private $graphLabel;

    private $itemOption1;
    private $itemOption2;

    public function __construct()
    {

        parent::__construct();

        echo "<script LANGUAGE='Javascript' SRC='app/lib/FusionCharts/FusionCharts.js'></script>";
        echo "<script LANGUAGE='Javascript' SRC='app/lib/FusionCharts/FusionChartsExportComponent.js'></script>";

        $this->header();

        $this->buildGraph('item_1');
      
    }

    function header()
    {

        echo
        "<div style='font-size: 15px; text-shadow: 0 1px 1px #000000;'>
                <h2 align='center' ><span style='color: blue;'>TIPOS SANGUINEOS</span></h2>
        </div> <br>";

        //$label = $this->tipo . " / ";

       /* if ($this->ano) {
            $label .= $this->ano;
        } else if ($this->dataInicio && $this->dataFim) {
            $label .= $this->dataInicio . ' ATÉ ' . $this->dataFim;
        }

        echo
            "<h3 align='center'>
            <b>" . $label . "</b>
        </h3>";*/

    }

    function buildGraph($itemName)
    {


        TTransaction::open('dbsic');

        $repository = new TRepository('vw_tipo_sanguineoRecord');
        $criteria = new TCriteria; 
        //$criteria->add(new TFilter('ano', '=', $_REQUEST['ano']));
        $criteria->setProperty('order', 'tipo');

        $cadastros = $repository->load($criteria);

        $strXML = "<chart id='chart_" . $itemName . "' caption='' subCaption='' xAxisName='' yAxisName='' showLabels='0' showValues='1' showLegend='1'  legendPosition='RIGHT' chartrightmargin='20'  basefontsize='11'  formatNumberScale='0' bgratio='0' startingangle='100' animation='1'>";

        if ($cadastros) {
            foreach ($cadastros as $item) {
                $strXML .= "<set label='" . $item->tipo . "' value='" . $item->quantidade . "' />";
            }
        }

        $strXML .= "</chart>";

        echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='margin-top:50px'>";
        //echo "<h5 align='center'><b>LABEL/b></h5>";
        echo renderChart("app/lib/FusionCharts/Pie2D.swf", "", $strXML, "Chart_" . $itemName, "100%", 250, false, false);
        echo "</div>";

    }

    function onShow()
    {
    }

    function show()
    {
        parent::show();
    }

}