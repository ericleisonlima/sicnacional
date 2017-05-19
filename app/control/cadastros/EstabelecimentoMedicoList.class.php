<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_erros', 1);
//error_reporting(E_ALL);

class EstabelecimentoMedicoList extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;
    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder( "form_list_estabelecimento_medico" );
        $this->form->setFormTitle( "Listagem de Cadastros dos Médicos nos Estabelecimentos" );
        $this->form->class = "tform";

        $opcao = new TCombo( "opcao" );
        $dados = new TEntry( "dados" );
       
        $opcao->setDefaultOption( "..::SELECIONE::.." );
        $dados->setProperty( "title", "Informe os dados de acordo com a opção" );
        // $dados->forceUpperCase();
        
        $opcao->setSize( "38%" );
        $dados->setSize( "38%" );
      
        $opcao->addItems( [ "nome" => "Nome"] );
        $this->form->addFields( [ new TLabel( "Opção de filtro:" ) ], [ $opcao ] );
        $this->form->addFields( [ new TLabel( "Dados da busca:" ) ], [ $dados ] );
        
        $this->form->addAction( "Buscar", new TAction( [ $this, "onSearch" ] ), "fa:search" );
        $this->form->addAction( "Novo", new TAction( [ "EstabelecimentoMedicoForm", "onEdit" ] ), "bs:plus-sign green" );
        
        $this->datagrid = new BootstrapDatagridWrapper( new TDataGrid() );
        $this->datagrid->datatable = "true";
        $this->datagrid->style = "width: 100%";
        $this->datagrid->setHeight( 320 );
        
        $column_id = new TDataGridColumn( "id", "ID", "left", 50 );
        $column_medico = new TDataGridColumn( "medico_nome", "Médico", "left" );
        $column_estabelecimento = new TDataGridColumn( "estabelecimento_nome", "Estabelecimento", "left" );
        $column_responsavel = new TDataGridColumn( "responsavel", "Responsável", "center" );
        $column_inicio = new TDataGridColumn( "datainicio", "Data Início", "left" );
        $column_fim = new TDataGridColumn( "datafim", "Data Fim", "left" );
        
        $this->datagrid->addColumn( $column_id );
        $this->datagrid->addColumn( $column_medico );
        $this->datagrid->addColumn( $column_estabelecimento);
        $this->datagrid->addColumn( $column_responsavel);
        $this->datagrid->addColumn( $column_inicio );
        $this->datagrid->addColumn( $column_fim );
     
        $order_id = new TAction( [ $this, "onReload" ] );
        $order_id->setParameter( "order", "id" );
        $column_id->setAction( $order_id );

        $action_edit = new TDataGridAction( [ "EstabelecimentoMedicoForm", "onEdit" ] );
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
    public function onReload( $param = NULL ){
        try
        {
            TTransaction::open( "dbsic" );

            $repository = new TRepository( "EstabelecimentoMedicoRecord" );
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
 

            if ( !empty( $objects ) )
            {
                foreach ( $objects as $object ){

                    $object->datainicio = TDate::date2br( $object->datainicio );
                    $object->datafim = TDate::date2br( $object->datafim );
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
    public function onSearch()
    {
        $data = $this->form->getData();
        try
        {
            if( !empty( $data->opcao ) && !empty( $data->dados ) )
            {
                TTransaction::open( "dbsic" );
                $repository = new TRepository( "EstabelecimentoMedicoRecord" );
                if ( empty( $param[ "order" ] ) )
                {
                    $param[ "order" ] = "id";
                    $param[ "direction" ] = "asc";
                }
                $limit = 10;
                $criteria = new TCriteria();
                $criteria->setProperties( $param );
                $criteria->setProperty( "limit", $limit );
                if( $data->opcao == "nome" && !( is_numeric( $data->dados ) ) )
                {
                    $criteria->add( new TFilter( $data->opcao, "LIKE", "%" . $data->dados . "%" ) );
                }
                else
                {
                    // new TMessage( "error", "O valor informado não é valido para um " . strtoupper( $data->opcao ) . "." );
                }
                $objects = $repository->load( $criteria, FALSE );
                $this->datagrid->clear();
                if ( $objects )
                {
                    foreach ( $objects as $object )
                    {
                        $this->datagrid->addItem( $object );
                    }
                }
                $criteria->resetProperties();
                $count = $repository->count( $criteria );
                $this->pageNavigation->setCount( $count );
                $this->pageNavigation->setProperties( $param ); 
                $this->pageNavigation->setLimit( $limit ); 
                TTransaction::close();
                $this->form->setData( $data );
                $this->loaded = true;
            }
            else
            {
                $this->onReload();
                $this->form->setData( $data );
                // new TMessage( "error", "Selecione uma opção e informe os dados da busca corretamente!" );
            }
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            $this->form->setData( $data );
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
            $object = new EstabelecimentoMedicoRecord( $param[ "key" ] );
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