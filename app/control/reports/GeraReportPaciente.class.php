<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

class GeraReportPaciente extends TPage {

    private $form;

    public function __construct() {
        parent::__construct();

        $this->form = new TQuickForm('form');
        $this->form->setFormTitle('<font color="red" size="3px" face="Arial"><b>Gerar Planilha de Atividades </b></font>');
        $this->form->style = 'width: 80%';
        
        /*
        $ppa_id = new TDBCombo('pex_ppa_id', 'pg_ceres', 'PpapexRecord', 'id', 'nome', 'nome');
        $pt_id = new TCombo('pex_pt_id');
        $situacao = new TCombo('situacao');

        $item = array();
        $item['0'] = 'TODOS';
        $item['Ativo'] = 'ATIVO';
        $item['Inativo'] = 'INATIVO';
        
        $situacao->addItems($item);
        $situacao->setValue('0');
 

        //-----------------------------------------------------------------------

        $action = new TAction(array($this, 'onChangeAction'));
        $ppa_id->setChangeAction($action);
  
        //-----------------------------------------------------------------------

        $this->form->addQuickField('PPA <font color=red><b>*</b></font color>',  $ppa_id, 50 );
        $this->form->addQuickField('Programa <font color=red><b>*</b></font color>',  $pt_id, 50 );
        $this->form->addQuickField('Situação <font color=red><b>*</b></font color> ', $situacao, 50);
        */
        $this->form->addQuickAction('Gerar Relatório', new TAction(array($this, 'onGenerate')), null);

        parent::add($this->form);
    }

    function onGenerate() {

    new PacienteReportPDF();
    
    }

   
}
