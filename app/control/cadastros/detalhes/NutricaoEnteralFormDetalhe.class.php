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

        $this->form->addAction('Salvar', $action, 'fa:floppy-o');
        $this->form->addAction('Voltar para Paciente',new TAction(array('PacienteList','onReload')),'fa:table blue');

        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        
        $column_name = new TDataGridColumn('paciente_nome', 'Paciente', 'left');
        $column_name2 = new TDataGridColumn('tipo_nutricao_nome', 'Tipo Nutrição', 'left');
        $column_name3 = new TDataGridColumn('administracao_nutricao_nome', 'Administração Nutrição', 'left');
        $column_inicio = new TDataGridColumn('datainicio', 'Início', 'left');
        $column_fim = new TDataGridColumn('datafim', 'Fim', 'left');
        $column_totalcalorias = new TDataGridColumn('totalcalorias', 'Total Calorias', 'left');
        $column_percentualdiario = new TDataGridColumn('percentualdiario', 'Percentual Diario', 'left');
       
        $this->datagrid->addColumn($column_name);
        $this->datagrid->addColumn($column_name2);
        $this->datagrid->addColumn($column_name3);
        $this->datagrid->addColumn($column_inicio);
        $this->datagrid->addColumn($column_fim);
        
        $action_edit = new TDataGridAction(array('NutricaoEnteralFormDetalhe', 'onEdit'));
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel('Editar');
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);
        
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);
        
        $this->datagrid->createModel();
        
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
 
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb( "menu.xml", "PacienteList" ) );
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid));
        $container->add($this->pageNavigation);

        parent::add($container);
        
    }


    
     public function onEdit( $param = NULL )
    {
        try
        {
            if( isset( $param[ "key" ] ) )
            {
                $key = $param['key'];
                TTransaction::open( "dbsic" );
                $object = new NutricaoEnteralRecord( $key );
               

                TTransaction::close();

                $this->onReload($param);

                $this->form->setData( $object );

            }
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            new TMessage( "error", "Ocorreu um erro ao tentar carregar o registro para edição!<br><br>" . $ex->getMessage() );
        }
    }

    public function onSave(){
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
            $object = $this->form->getData($this->activeRecord);
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onReload( $param = NULL ){
        try{

            TTransaction::open( "dbsic" );

            $repository = new TRepository( "NutricaoEnteralRecord" );
            if ( empty( $param[ "order" ] ) ){
                $param[ "order" ] = "id";
                $param[ "direction" ] = "asc";
            }
            $limit = 10;
            
            $criteria = new TCriteria();
            $criteria->add(new TFilter('paciente_id', '=', filter_input(INPUT_GET, 'fk')));
            $criteria->setProperties( $param );
            $criteria->setProperty( "limit", $limit );
            
            $objects = $repository->load( $criteria, FALSE );

            $this->datagrid->clear();
            if ( !empty( $objects ) ){

                foreach ( $objects as $object ){

                    $object->datainicio = TDate::date2br($object->datainicio);
                    $object->datafim = TDate::date2br($object->datafim);
                    $this->datagrid->addItem( $object );
                }
            }
            $criteria->resetProperties();

            $count = $repository->count($criteria);
            $this->pageNavigation->setCount($count); 
            $this->pageNavigation->setProperties($param); 
            $this->pageNavigation->setLimit($limit);

            TTransaction::close();
            $this->loaded = true;
        }catch ( Exception $ex ){
            TTransaction::rollback();
            new TMessage( "error", $ex->getMessage() );
        }
    }

     public function onDelete( $param = NULL ){
        if( isset( $param[ "key" ] ) ){
            $action1 = new TAction( [ $this, "Delete" ] );
            $action2 = new TAction( [ $this, "onReload" ] );
            $action1->setParameter( "key", $param[ "key" ] );

            new TQuestion( "Deseja realmente apagar o registro?", $action1, $action2 );
        }
    }

    function Delete( $param = NULL ){
        try{
            TTransaction::open( "dbsic" );
            $object = new NutricaoEnteralRecord( $param[ "key" ] );
            $object->delete();
            TTransaction::close();
            $this->onReload();
            new TMessage("info", "Registro apagado com sucesso!");
        }catch ( Exception $ex ){
            TTransaction::rollback();
            new TMessage("error", $ex->getMessage());
        }
    }



    
    
}
