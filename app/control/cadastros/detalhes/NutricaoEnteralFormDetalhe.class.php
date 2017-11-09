<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_erros', 1);
//error_reporting(E_ALL);

class NutricaoEnteralFormDetalhe extends TWindow{

    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;

   public function __construct(){
        parent::__construct();
        parent::SetSize(0.800,0.800);
        
        $this->form = new BootstrapFormBuilder('form_detail_nutricao_enteral');
        $this->form->setFormTitle('Detalhamento de Nutrição Enteral');
        
        $id = new THidden('id');
        $paciente_id = new THidden('paciente_id'); 
        $paciente_id->setValue(filter_input(INPUT_GET, 'fk'));
        $tiponutricao_id = new TCombo("tiponutricao_id"); 
        $administracaonutricao_id = new TCombo("administracaonutricao_id"); 

        TTransaction::open('dbsic');
        $tempNome = new PacienteRecord( filter_input( INPUT_GET, 'fk' ) );
        
        if( $tempNome ){
            $paciente_nome = new TLabel( $tempNome->nome );
            $paciente_nome->setEditable(FALSE);
        }
        TTransaction::close(); 

        $items = array();
        
        TTransaction::open('dbsic');
        $repository = new TRepository('AdministraNutricaoRecord');

        $criteria = new TCriteria;
        $criteria->setProperty('order', 'nome');
        
        $cadastros = $repository->load($criteria);
  
        foreach ($cadastros as $object) {
            $items[$object->id] = $object->nome;
        }

        $administracaonutricao_id->addItems($items);
        TTransaction::close(); 

        $items = array();
        TTransaction::open('dbsic');
        $repository = new TRepository('TipoNutricaoRecord');

        $criteria = new TCriteria;
        $criteria->setProperty('order', 'nome');
        
        $cadastros = $repository->load($criteria);
  
        foreach ($cadastros as $object) {
            $items[$object->id] = $object->nome;
        }

        $tiponutricao_id->addItems($items);
        TTransaction::close(); 

        $inicio = new TDate('datainicio');
        $fim = new TDate('datafim');
        $totalcalorias = new TEntry('totalcalorias');
        $percentualdiario = new TEntry('percentualdiario');
        
        $id->setEditable(FALSE);

        $inicio->setMask('dd/mm/yyyy');
        $fim->setMask('dd/mm/yyyy');
        $inicio->setDatabaseMask('yyyy-mm-dd');
        $fim->setDatabaseMask('yyyy-mm-dd');
        $totalcalorias->setMask('9999999999');
        $percentualdiario->setMask('99999');

        $inicio->addValidation( "Início", new TRequiredValidator );
        $totalcalorias->addValidation( "Total de Calorias", new TRequiredValidator );
        $tiponutricao_id->addValidation( "Tipo de Nutrição", new TRequiredValidator );

        $this->form->addFields( [new TLabel('Paciente: '), $paciente_nome] );
        $this->form->addFields( [new TLabel('Tipo Nutrição<font color=red><b>*</b></font>')], [$tiponutricao_id ] );
        $this->form->addFields( [new TLabel('Administração Nutrição')], [$administracaonutricao_id] );
        $this->form->addFields( [new TLabel('Inicio<font color=red><b>*</b></font>')], [$inicio] );
        $this->form->addFields( [new TLabel('Fim')], [$fim] );
        $this->form->addFields( [new TLabel('Total Calorias<font color=red><b>*</b></font>')], [$totalcalorias] );
        $this->form->addFields( [new TLabel('Percentual Diario')], [$percentualdiario] );
        $this->form->addFields( [$id, $paciente_id]);
       
        $action = new TAction(array($this, 'onSave'));
        $action->setParameter('id', '' . filter_input(INPUT_GET, 'id') . '');
        $action->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');
        $action->setParameter('key', '' . filter_input(INPUT_GET, 'key') . '');

        $voltar = new TAction(array('PacienteDetail','onReload'));    
        $voltar->setParameter('id', '' . filter_input(INPUT_GET, 'id') . '');
        $voltar->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');
        $voltar->setParameter('key', '' . filter_input(INPUT_GET, 'key') . '');

        $this->form->addAction('Salvar', $action, 'fa:floppy-o');
        $this->form->addAction('Voltar para Pacientes',$voltar ,'fa:table blue');
    
        
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
 
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb( "menu.xml", "PacienteList" ) );
        $container->add($this->form);
        $container->add($this->pageNavigation);

        parent::add($container);
        
    }

    public function onSave($param){
        try{

            TTransaction::open('dbsic');
            $cadastro = $this->form->getData('NutricaoEnteralRecord');
            $this->form->validate();
            $cadastro->store();
            TTransaction::close();

            $param=array();
            $param['key'] = $cadastro->id;
            $param['id'] = $cadastro->id;
            $param['fk'] = $cadastro->paciente_id;
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
            TApplication::gotoPage('PacienteDetail','onReload', $param);

        }catch (Exception $e){
            $cadastro = $this->form->getData($this->activeRecord);
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onEdit($param) {

        TTransaction::open('dbsic');
        
        if (isset($param['key'])) {

            $key = $param['key'];
            $object = new NutricaoEnteralRecord($key);

            $object->datainicio = TDate::date2br($object->datainicio);
            $object->datafim = TDate::date2br($object->datafim);
            $this->form->setData($object);
            
        } else {
            $this->form->clear();
        }
        TTransaction::close();

    }

    public function onReload( $param = NULL ){
}

    
    
}
