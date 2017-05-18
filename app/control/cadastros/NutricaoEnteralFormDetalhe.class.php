<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

class NutricaoEnteralFormDetalhe extends TPage
{


    private $form;
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;


   public function __construct(){
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder('form_detail_nutricao_enteral');
        $this->form->setFormTitle('Detalhamento de Nutrição Enteral');
        //$this->form->class = "tform";
        
        
        
        $id = new THidden('id');
        $paciente_id = new THidden('paciente_id'); 
        $paciente_id->setValue(filter_input(INPUT_GET, 'id'));
        $tiponutricao_id = new THidden('tiponutricao_id'); 
        $tiponutricao_id->setValue(filter_input(INPUT_GET, 'id'));
        $administracaonutricao_id = new THidden('administracaonutricao_id'); 
        $administracaonutricao_id->setValue(filter_input(INPUT_GET, 'id'));

        TTransaction::open('dbsic');
        $tempVisita = new PacienteRecord( filter_input( INPUT_GET, 'id' ) );
        
        if( $tempVisita ){
            $paciente_nome = new TLabel( $tempVisita->nome );
            $paciente_nome->setEditable(FALSE);
        }
        TTransaction::close(); 

        TTransaction::open('dbsic');
        $tempVisita2 = new TipoNutricaoRecord( filter_input( INPUT_GET, 'id' ) );
        
        if( $tempVisita2 ){
            $paciente_nome2 = new TLabel( $tempVisita2->nome );
            $paciente_nome2->setEditable(FALSE);
        }
        TTransaction::close(); 

        TTransaction::open('dbsic');
        $tempVisita3 = new AdministraNutricaoRecord( filter_input( INPUT_GET, 'id' ) );
        
        if( $tempVisita3 ){
            $paciente_nome3 = new TLabel( $tempVisita3->nome );
            $paciente_nome3->setEditable(FALSE);
        }
        TTransaction::close(); 

        $inicio = new TDate('datainicio');
        $fim = new TDate('datafim');
        $totalcalorias = new TEntry('totalcalorias');
        $percentualdiario = new TEntry('percentualdiario');
        
        $id->setEditable(FALSE);
        $id->setSize('38%');
        $paciente_id->setSize('40%');
        $tiponutricao_id->setSize('40%');
        $administracaonutricao_id->setSize('40%');
        $totalcalorias->setSize('40%');
        $percentualdiario->setSize('40%');
        $inicio->setSize('20%');
        $fim->setSize('20%');

        $inicio->setMask('dd/mm/yyyy');
        $fim->setMask('dd/mm/yyyy');
        $inicio->setDatabaseMask('yyyy-mm-dd');
        $fim->setDatabaseMask('yyyy-mm-dd');


        $inicio->addValidation( "Início", new TRequiredValidator );
        $totalcalorias->addValidation( "Total de Calorias", new TRequiredValidator );

        
        $this->form->addFields( [new TLabel('Paciente')], [$paciente_nome] );
        $this->form->addFields( [new TLabel('Tipo Nutrição')], [$paciente_nome2 ] );
        $this->form->addFields( [new TLabel('Administração Nutrição')], [$paciente_nome3 ] );
        $this->form->addFields( [new TLabel('Inicio')], [$inicio] );
        $this->form->addFields( [new TLabel('Fim')], [$fim] );
        $this->form->addFields( [new TLabel('Total Calorias')], [$totalcalorias] );
        $this->form->addFields( [new TLabel('Percentual Diario')], [$percentualdiario] );
        $this->form->addFields( [$id, $paciente_id, $tiponutricao_id, $administracaonutricao_id]);
       
        $action = new TAction(array($this, 'onSave'));
        $action->setParameter('id', '' . filter_input(INPUT_GET, 'id') . '');

        $this->form->addAction('Salvar', $action, 'fa:floppy-o');
        $this->form->addAction('Voltar',new TAction(array('PacienteList','onReload')),'fa:table blue');

        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        
        //$column_id = new TDataGridColumn('id', 'Id', 'center');
        $column_name = new TDataGridColumn('paciente_nome', 'Paciente', 'left');
        $column_name2 = new TDataGridColumn('paciente_nome2', 'Tipo Nutrição', 'left');
        $column_name3 = new TDataGridColumn('paciente_nome3', 'Administração Nutrição', 'left');
        $column_inicio = new TDataGridColumn('datainicio', 'Início', 'left');
        $column_fim = new TDataGridColumn('datafim', 'Fim', 'left');
        $column_totalcalorias = new TDataGridColumn('totalcalorias', 'Total Calorias', 'left');
        $column_percentualdiario = new TDataGridColumn('percentualdiario', 'Percentual Diario', 'left');
       

        //$this->datagrid->addColumn($column_id);
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
        //$container->add(new TXMLBreadCrumb('menu.xml', 'NutricaoEnteralFormDetalhe'));
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid));
        $container->add($this->pageNavigation);

        parent::add($container);
        
    }


    function onEdit($param) {


        TTransaction::open('dbsic');
        
        if (isset($param['fk'])) {

            $key = $param['fk'];
            $object = new NutricaoEnteralRecord($key);
            $this->form->setData($object);
            
        } else {
            $this->form->clear();
        }
        TTransaction::close();

    }
    public function onSave(){
        try{

            TTransaction::open('dbsic');
            $cadastro = $this->form->getData('NutricaoEnteralRecord');
            $cadastro->paciente_id =  filter_input(INPUT_GET, 'id');
            $cadastro->tiponutricao_id =  filter_input(INPUT_GET, 'id');
            $cadastro->administracaonutricao_id =  filter_input(INPUT_GET, 'id');

            $this->form->validate();
            $cadastro->store();
            TTransaction::close();
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
            TApplication::gotoPage('NutricaoEnteralFormDetalhe', 'onReload');

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
            if ( empty( $param[ "order" ] ) )
            {
                $param[ "order" ] = "id";
                $param[ "direction" ] = "asc";
            }
            $limit = 10;
            
            $criteria = new TCriteria();
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
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            new TMessage( "error", $ex->getMessage() );
        }
    }



    public function onDelete( $param = NULL )
    {
        if( isset( $param[ "key" ] ) )
        {
            //Criacao das acoes a serem executadas na mensagem de exclusao
            $action1 = new TAction( [ $this, "Delete" ] );
            $action2 = new TAction( [ $this, "onReload" ] );

            //Definicao sos parametros de cada acao
            $action1->setParameter( "key", $param[ "key" ] );

            new TQuestion( "Deseja realmente apagar o registro?", $action1, $action2 );
        }
    }

    function Delete( $param = NULL )
    {
        try
        {
            TTransaction::open( "dbsic" );

            $object = new NutricaoEnteralRecord( $param[ "key" ] );

            $object->delete();

            TTransaction::close();

            $this->onReload();

            new TMessage("info", "Registro apagado com sucesso!");
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();

            new TMessage("error", $ex->getMessage());
        }
    }

    
    
}
