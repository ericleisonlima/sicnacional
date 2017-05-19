<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_erros', 1);
//error_reporting(E_ALL);

class TipoAdministracaoMedicamentoDetalhe extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;
    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder( "form_list_cadastro_tipo_administracao_medicamento" );
        $this->form->setFormTitle( "Listagem de Tipos de Administração de Medicamentos" );
        $this->form->class = "tform";

        $id = new THidden( "id" );
        $tipo = new TEntry( "descricao" );
        $tipo->forceUpperCase();

        $this->form->addFields( [ new TLabel( "Tipo de Administração do Medicamento:" ) ], [ $tipo ] );
        $this->form->addFields( [ $id ] );
        
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        
        $this->datagrid = new BootstrapDatagridWrapper( new TDataGrid() );
        $this->datagrid->datatable = "true";
        $this->datagrid->style = "width: 100%";
        $this->datagrid->setHeight( 320 );
        
        $column_id = new TDataGridColumn( "id", "ID", "center", 50 );
        $column_nome = new TDataGridColumn( "descricao", "Descrição", "left" );
        
        $this->datagrid->addColumn( $column_id );
        $this->datagrid->addColumn( $column_nome );

        $order_id = new TAction( [ $this, "onReload" ] );
        $order_id->setParameter( "order", "id" );
        $column_id->setAction( $order_id );

        $order_nome = new TAction( [ $this, "onReload" ] );
        $order_nome->setParameter( "order", "descricao" );
        $column_nome->setAction( $order_nome );

        $action_edit = new TDataGridAction( [ $this, "onEdit" ] );
        $action_edit->setButtonClass( "btn btn-default" );
        $action_edit->setLabel( "Editar" );
        $action_edit->setImage( "fa:pencil-square-o blue fa-lg" );
        $action_edit->setField( "id" );
        $this->datagrid->addAction( $action_edit );
        
        $action_del = new TDataGridAction( [ $this, "onDelete" ] );
        $action_del->setButtonClass( "btn btn-default" );
        $action_del->setLabel( "Deletar" );
        $action_del->setImage( "fa:trash-o red fa-lg" );
        $action_del->setField( "id" );
        $this->datagrid->addAction( $action_del );

        $this->datagrid->createModel();

        $this->pageNavigation = new TPageNavigation();
        $this->pageNavigation->setAction( new TAction( [ $this, "onReload" ] ) );
        $this->pageNavigation->setWidth( $this->datagrid->getWidth() );


        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( new TXMLBreadCrumb( "menu.xml", __CLASS__ ) );
        $container->add( $this->form );
        $container->add( TPanelGroup::pack( NULL, $this->datagrid ) );
        $container->add( $this->pageNavigation );
        

        parent::add( $container );
    }
    public function onSave()
    {
        try
        {
            $this->form->validate();
            TTransaction::open( "dbsic" );

            $object = $this->form->getData( "TipoAdministracaoMedicamentoRecord" );
            $object->store();
            TTransaction::close();
            $action = new TAction( [ $this , "onReload" ] );
            new TMessage( "info", "Registro salvo com sucesso!", $action );
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            new TMessage( "error", "Ocorreu um erro ao tentar salvar o registro!<br><br>" . $ex->getMessage() );
        }
    }
    public function onEdit( $param )
    {
        try
        {
            if( isset( $param[ "key" ] ) )
            {
                TTransaction::open( "dbsic" );
                $object = new TipoAdministracaoMedicamentoRecord( $param[ "key" ] );
                $this->form->setData( $object );
                TTransaction::close();
            }
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            new TMessage( "error", "Ocorreu um erro ao tentar carregar o registro para edição!<br><br>" . $ex->getMessage() );
        }
    }
    public function onReload( $param = NULL )
    {
        try
        {

            TTransaction::open( "dbsic" );


            $repository = new TRepository( "TipoAdministracaoMedicamentoRecord" );
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

            $action1 = new TAction( [ $this, "Delete" ] );
            $action2 = new TAction( [ $this, "onReload" ] );

            $action1->setParameter( "key", $param[ "key" ] );
            new TQuestion( "Deseja realmente apagar o registro?", $action1, $action2 );
        }
    }
    function Delete( $param = NULL )
    {
        try
        {
            TTransaction::open( "dbsic" );
            $object = new TipoAdministracaoMedicamentoRecord( $param[ "key" ] );
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
    public function show()
    {
        $this->onReload();
        parent::show();
    }
}