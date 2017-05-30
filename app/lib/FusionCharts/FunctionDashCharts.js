
function updateChart01(factoryIndex) {

    var strURL = 'app/control/direcao/DashBeneficiarioGraficoMunicipio.class.php?regional_id=' + factoryIndex;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL,
        success: function (msg) {
            $('#ChartBenefMunicipio').show().html(msg);
        }
    });
}

function updateChart02(factoryIndex) {

    var strURL1 = 'app/control/direcao/DashBeneficiarioTipoMunicipio.class.php?municipio_id=' + factoryIndex;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL1,
        success: function (msg) {
            $('#ChartBenefTipoMunic').show().html(msg);
        }
    });
    var strURL2 = 'app/control/direcao/DashBeneficiarioSexoMunicipio.class.php?municipio_id=' + factoryIndex;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL2,
        success: function (msg) {
            $('#ChartBenefSexoMunic').show().html(msg);
        }
    });
    var strURL3 = 'app/control/direcao/DashBeneficiarioGraficoPosto.class.php?municipio_id=' + factoryIndex;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL3,
        success: function (msg) {
            $('#ChartBenefPorPosto').show().html(msg);
        }
    });

}

function updateChart03(factoryIndex) {

    var strURL = 'app/control/direcao/DashBeneficiarioLeitePostoEntrega.class.php?id=' + factoryIndex;

    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL,
        success: function (msg) {
            $('#ChartPostoEntrega').show().html(msg);
        }
    });
}
////////////////////////////////// Inicio DashboardProjetoSituacaoSIAP   //////////////////////////////////////////////
function updateChart01DashboardProjetosSituacaoSIAP(factoryIndex, factoryIndex2, ano) {
    // carrega grafico 01 classe DashboardProjetoSituacaoSIAP
    var strURL0 = 'app/control/direcao/DashboardEficienciaContratacaoProjetosSIAP.class.php?regional_id=' + factoryIndex + '&dash=' + factoryIndex2 + '&ano=' + ano + '&ano=' + ano;
    $.ajax({
        type: 'POST',
        url: strURL0,
        success: function (msg) {
            $('#DashProjSituacaoSIAP01').show().html(msg);
        }
    });

    // carrega grafico 02 classe DashboardProjetoSituacaoSIAP
    var strURL4 = 'app/control/direcao/DashboardProjetosTipoAtividadeRegionalSIAP.class.php?regional_id=' + factoryIndex + '&dash=' + factoryIndex2 + '&ano=' + ano;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL4,
        success: function (msg) {
            $('#DashProjTipoAtivRegionalSIAP01').show().html(msg);
        }
    });
    // carrega grafico 03 classe DashboardProjetoSituacaoSIAP
    var strURL5 = 'app/control/direcao/DashboardProjetosTipoAtividadeInvestRegionalSIAP.class.php?regional_id=' + factoryIndex + '&dash=' + factoryIndex2 + '&ano=' + ano;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL5,
        success: function (msg) {
            $('#DashProjTipoAtivInvestRegionalSIAP01').show().html(msg);
        }
    });
    // carrega grafico 04 classe DashboardProjetoSituacaoSIAP
    var strURL6 = 'app/control/direcao/DashboardProjetosTipoAtividadeCusteioRegionalSIAP.class.php?regional_id=' + factoryIndex + '&dash=' + factoryIndex2 + '&ano=' + ano;
    $.ajax({
        type: 'POST',
        url: strURL6,
        success: function (msg) {
            $('#DashProjTipoAtivCusteioRegionalSIAP01').show().html(msg);
        }
    });

    // carrega grafico 05 classe DashboardProjetoSituacaoSIAP
    var strURL1 = 'app/control/direcao/DashboardProjetosTipoProjetoRegionalSIAP.class.php?regional_id=' + factoryIndex + '&ano=' + ano;
    $.ajax({
        type: 'POST',
        url: strURL1,
        success: function (msg) {
            $('#DashProjTipoProjRegionalSIAP01').show().html(msg);
        }
    });
    // carrega grafico 06 classe DashboardProjetoSituacaoSIAP
    var strURL2 = 'app/control/direcao/DashboardProjetosEnquadramentoRegionalSIAP.class.php?regional_id=' + factoryIndex + '&ano=' + ano;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL2,
        success: function (msg) {
            $('#DashbProjEnquadramentoRegionalSIAP01').show().html(msg);
        }
    });
    // carrega grafico 07 classe DashboardProjetoSituacaoSIAP
    var strURL3 = 'app/control/direcao/DashboardProjetosLinhaCreditoRegionalSIAP.class.php?regional_id=' + factoryIndex + '&ano=' + ano;
    $.ajax({
        type: 'POST',
        url: strURL3,
        success: function (msg) {
            $('#DashProjLinhaCredRegionalSIAP').show().html(msg);
        }
    });
    // carrega grafico 08 classe DashboardProjetoSituacaoSIAP
    var strURL = 'app/control/direcao/DashboardProjetosSituacaoSIAPMunicipio.class.php?regional_id=' + factoryIndex + '&ano=' + ano;
    $.ajax({
        type: 'POST',
        url: strURL,
        success: function (msg) {
            $('#DashProjSituacaoSIAPMunicipio').show().html(msg);
        }
    });
}

function updateChart02DashboardProjetosSituacaoSIAP(factoryIndex, ano) {
    //chama grafico 09 classe DashboardProjetoSituacaoSIAP
    var strURL = 'app/control/direcao/DashboardProjetosSituacaoSIAPMunicipioSituacao.class.php?municipio_id=' + factoryIndex + '&ano=' + ano;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL,
        success: function (msg) {
            $('#DashProjSituacaoSIAPMunSituacao').show().html(msg);
        }
    });
    //chama grafico 10 classe DashboardProjetoSituacaoSIAP
    var strURL2 = 'app/control/direcao/DashboardProjetosSituacaoSIAPMunicipioContratados.class.php?municipio_id=' + factoryIndex + '&ano=' + ano;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ ano);
    $.ajax({
        type: 'POST',
        url: strURL2,
        success: function (msg) {
            $('#DashProjSituacaoSIAPMunContratados').show().html(msg);
        }
    });
    //chama grafico 11 classe DashboardProjetoSituacaoSIAP
    var strURL3 = 'app/control/direcao/DashboardProjetosSituacaoSIAPMunicipioTecnico.class.php?municipio_id=' + factoryIndex + '&ano=' + ano;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL3,
        success: function (msg) {
            $('#DashProjSituacaoSIAPMunTecnico').show().html(msg);
        }
    });
}
//chama grafico 12 classe DashboardProjetoSituacaoSIAP
function updateChart06DashboardProjetosSituacaoSIAP(factoryIndex, factoryIndex2, ano) {

    var strURL6 = 'app/control/direcao/DashboardProjetosSituacaoSIAPMunicipioSituacaoTec.class.php?tecnico_id=' + factoryIndex + "&municipio_id=" + factoryIndex2 + '&ano=' + ano;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL6,
        success: function (msg) {
            $('#DashProjSituacaoSIAPMunSituacaoTec').show().html(msg);
        }
    });
}
////////////////////////////////// Fim  DashboardProjetoSituacaoSIAP   //////////////////////////////////////////////

////////////////////////////////// Inicio DashboardProjetoRendimentoSIAP   //////////////////////////////////////////////
function updateChart01DashboardProjetosRendimentoSIAP(factoryIndex, ano) {
//carrega grafico 04 DashboardRendimentoFinanceiroProjetosSIAP
    var strURL01 = 'app/control/direcao/DashboardProjetosTipoAtividadeRendRegionalSIAP.class.php?regional_id=' + factoryIndex + '&ano=' + ano;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL01,
        success: function (msg) {
            $('#ChartsProjTipoAtivRendRegional').show().html(msg);
        }
    });
    //carrega grafico 05 DashboardRendimentoFinanceiroProjetosSIAP
    var strURL02 = 'app/control/direcao/DashboardProjetosTipoAtividadeInvestRendRegionalSIAP.class.php?regional_id=' + factoryIndex + '&ano=' + ano;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL02,
        success: function (msg) {
            $('#ChartTipoAtivInvestRendRegional').show().html(msg);
        }
    });
    //carrega grafico 06
    var strURL03 = 'app/control/direcao/DashboardProjetosTipoAtividadeCusteioRegionalRendSIAP.class.php?regional_id=' + factoryIndex + '&ano=' + ano;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL03,
        success: function (msg) {
            $('#ChartTipoAtivCustRegionalRend').show().html(msg);
        }
    });
//carrega grafico 07
    var strURL1 = 'app/control/direcao/DashboardProjetosTipoProjetoRendRegionalSIAP.class.php?regional_id=' + factoryIndex + '&ano=' + ano;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL1,
        success: function (msg) {
            $('#ChartTipoProjetoRendRegionalSIAP').show().html(msg);
        }
    });
    //carrega grafico 08
    var strURL2 = 'app/control/direcao/DashboardProjetosEnquadramentoRendRegionalSIAP.class.php?regional_id=' + factoryIndex + '&ano=' + ano;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL2,
        success: function (msg) {
            $('#ChartProjEnqRendRegionalSIAP').show().html(msg);
        }
    });
    //carrega grafico 09
    var strURL3 = 'app/control/direcao/DashboardProjetosLinhaCreditoRendRegionalSIAP.class.php?regional_id=' + factoryIndex + '&ano=' + ano;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL3,
        success: function (msg) {
            $('#ChartProjLinhaCredRendRegionalSIAP').show().html(msg);
        }
    });
    //carrega grafico 10
    var strURL = 'app/control/direcao/DashboardProjetosRendSituacaoSIAPMunicipio.class.php?regional_id=' + factoryIndex + '&ano=' + ano;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL,
        success: function (msg) {
            $('#ChartProjRendSituacaoSIAPMunicipio').show().html(msg);
        }
    });
}

function updateChart02DashboardProjetosRendimentoSIAP(factoryIndex, ano) {
    //carrega grafico 11
    var strURL = 'app/control/direcao/DashboardProjetosRendSituacaoSIAPMunicipioContratados.class.php?municipio_id=' + factoryIndex + '&ano=' + ano;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL,
        success: function (msg) {
            $('#ChartProjRendSituacaoSIAPMunicContratados').show().html(msg);
        }
    });

//carrega grafico 12
    var strURL2 = 'app/control/direcao/DashboardProjetosRendSituacaoSIAPMunicipioTecnico.class.php?municipio_id=' + factoryIndex + '&ano=' + ano;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL2,
        success: function (msg) {
            $('#ChartProjRendSituacaoSIAPMunicTecnico').show().html(msg);
        }
    });
}

function updateChart01DashboardQuantitativoVeiculos(factoryIndex) {

    var strURL = 'app/control/direcao/DashboardQuantitativoTipoVeiculo.class.php?regional_id=' + factoryIndex;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL,
        success: function (msg) {
            $('#Chart02').show().html(msg);
        }
    });


    var strURL2 = 'app/control/direcao/DashboardQuantitativoVeiculosMunicipio.class.php?regional_id=' + factoryIndex;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL2,
        success: function (msg) {
            $('#Chart03').show().html(msg);
        }
    });

}

function updateChart01DashboardEngAgronomo(factoryIndex) {
    
    var strURL = 'app/control/direcao/DashboardServidoresPercentualEngenheirosAgronomoMun.class.php?regional_id=' + factoryIndex;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL,
        success: function (msg) {
            $('#ChartSerPerEngAgriMun').show().html(msg);
        }
    });

}

function updateChart01DashboardTecAgricola(factoryIndex) {

    var strURL = 'app/control/direcao/DashboardServidoresPercentualTecAgricolaMun.class.php?regional_id=' + factoryIndex;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL,
        success: function (msg) {
            $('#ChartSerPerTecAgriMun').show().html(msg);
        }
    });
}

function updateChart01DashboardPrevisaoAposentadoria(factoryIndex) {

    var strURL = 'index.php?class=DashboardPrevisaoAposentadoriaPorRegional&ano=' + factoryIndex;
    $.ajax({
        type: 'POST',
        url: strURL,
        success: function (msg) {
            $('#chart2').show().html(msg);
            alert("Esta é uma caixa de diálogo ALERT do JavaScript! " + factoryIndex);
        }
    });

    var strURL2 = 'index.php?class=DashboardPrevisaoAposentadoriaPorFormacao&ano=' + factoryIndex;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL2,
        success: function (msg) {
            $('#chart3').show().html(msg);
        }
    });

}


function updateChartDashboardAposentadoriaGraficoRegional(factoryIndex) {
    alert(factoryIndex);
    var strURL = 'app/control/direcao/DashAposentadoriaSubMunicipio.class.php?regional_id=' + factoryIndex;
    // alert ("Esta é uma caixa de diálogo ALERT do JavaScript! "+ factoryIndex);
    $.ajax({
        type: 'POST',
        url: strURL,
        success: function (msg) {
            $('#Chart02').show().html(msg);
        }
    });

}
