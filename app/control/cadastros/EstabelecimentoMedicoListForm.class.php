<?php
class EstabelecimentoMedicoListForm extends TPage{
    
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;
    
    public function __construct() 
    {
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder("form_cadastro_EstabelecimentoMedico" );
        $this->form->setFormTitle( "Estabelecimento Medico" );
        $this->form->class = "tform";
        
        $id  = new THidden( "id" );
        $estabelecimento_id = new TDBCombo("estabelecimento_id", "dbsic", "EstabelecimentoRecord", "id", "nome");
        $medico_id = new TCombo("medico_id");
        $responsavel = new TCombo( "responsavel" );
        $datainicio = new TDate("datainicio");
        $datafim = new TDate("datafim");
        
        //$estabelecimento_id->setDefaultOption ('');
        //$medico_id->setDefaultOption ('');
        $responsavel->setDefaultOption ( '..::SELECIONE::..' );
        $datainicio->setProperty ('title', 'Digitar data de inicio');
        $datafim->setProperty ( 'title', 'Digitar data de encerramento' );
        
        $estabelecimento_id->setSize( '30%' );
        $medico_id->setSize( '30%' );
        $responsavel->setSize('30%');
        $datainicio->setSize('30%');
        $datafim->setSize ('30%');

        //----------------------------------------------------------------------------------------------------
        $items = []; //array();
        TTransaction::open('dbsic');
            $repository = new TRepository('MedicoRecord');
            $criteria = new TCriteria;
            $criteria->setProperty('order', 'nome');

            $cadastros = $repository->load($criteria);

            foreach ($cadastros as $object) 
            {
                $items[$object->id] = $object->nome . " - " . $object->crm ;
            }
            $medico_id->addItems($items);
        TTransaction::close(); 

        //----------------------------------------------------------------------------------------------------

        $items = []; //array();
        TTransaction::open('dbsic');
            $repository = new TRepository('EstabelecimentoRecord');
            $criteria = new TCriteria;
            $criteria->setProperty('order', 'nome');

            $cadastros = $repository->load($criteria);

            foreach ($cadastros as $object) 
            {
                $items[$object->id] = $object->nome;
            }
            $estabelecimento_id->addItems($items);
        TTransaction::close(); 

        //----------------------------------------------------------------------------------------------------
        
        $responsavel->addItems( [ 'S' => 'SIM', 'N' => 'NÃO'] );
        $this->form->addFields( [ new TLabel( 'Medico:' ) ], [ $medico_id ] );
        $this->form->addFields( [ new TLabel( 'Estabelecimento:' ) ], [ $estabelecimento_id ] );
        $this->form->addFields( [ new TLabel( 'Responsavel:' ) ], [ $responsavel ] );        
        $this->form->addFields( [ new TLabel( 'Data Inicio: ' ) ], [ $datainicio ] );
        $this->form->addFields( [ new TLabel( 'Data Fim: ' ) ], [ $datafim ] );
        
        //$this->form->addAction( 'Buscar', new TAction( [$this, 'onSearch'] ), 'fa:search' );
        $this->form->addAction( 'Salvar', new TAction( [$this, 'onSave'] ), 'fa:save' );
     
        
        $this->datagrid = new BootstrapDatagridWrapper( new TDataGrid() );
        $this->datagrid->datatable = "true";
        $this->datagrid->style = "width: 100%";
        $this->datagrid->setHeight( 320 );
        
        $column_medico = new TDataGridColumn( "nome_medico", "Medico", "center", 50 );
        $column_estabelecimento = new TDataGridColumn( "nome_estabelecimento", "Estabelecimento", "center" );
        $column_responsavel = new TDataGridColumn( "responsavel", "Responsavel", "center" );
        
        $this->datagrid->addColumn( $column_medico );
        $this->datagrid->addColumn( $column_estabelecimento );
        $this->datagrid->addColumn( $column_responsavel );
        
        $order_medico = new TAction( [ $this, "onReload" ] );
        $order_medico->setParameter( "order", "medico_id" );
        $column_medico->setAction( $order_medico );
        
        $order_estabelecimento = new TAction( [ $this, "onReload" ] );
        $order_estabelecimento->setParameter( "order", "estabelecimento_id" );
        $column_estabelecimento->setAction( $order_estabelecimento );
        
        $order_responsavel = new TAction( [ $this, "onReload" ] );
        $order_responsavel->setParameter( "order", "responsavel" );
        $column_responsavel->setAction( $order_responsavel);

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
<<<<<<< HEAD
    
=======

>>>>>>> 758c1aba4c8269a6e4e2cc38900596cfd4e71f3d
    public function onSave() 
    {

        $object = $this->form->getData('EstabelecimentoMedicoRecord');
        
        try{



            TTransaction::open('dbsic');

          if($msg == '') {

                $cadastro->store();
                $msg = 'Dados armazenados com sucesso';
                
                TTransaction::close();
                
            
            
            $object->store();
            
            TTransaction::close();
            
            new TMessage( 'info', 'Sucess');

                                
            $param = array();
            $param ['id'] = $dados['id'];
                                
            new TMessage("info", "Registro salvo com sucesso!");
            TApplication::gotoPage('EstabelecimentoMedicoListForm','onReload', $param ); 
        }
          
        catch (Exception $se){
            new TMessage('erro', $se->getMessage());
            TTransaction::rollback();
        }
    }
    
   public function onReload( $param = NULL )
    {
        try
        {
            // Abrindo a conexao com o banco de dados
            TTransaction::open( "dbsic" );

            // Criando um repositorio para armazenar temporariamente os dados do banco
            $repository = new TRepository( "EstabelecimentoMedicoRecord" );

            if ( empty( $param[ "order" ] ) )
            {
                $param[ "order" ] = "id";
                $param[ "direction" ] = "asc";
            }

            $limit = 50;


            $criteria = new TCriteria();
            $criteria->setProperties( $param );
            $criteria->setProperty( "limit", $limit );


            $objects = $repository->load( $criteria, FALSE );


            $this->datagrid->clear();

            if ( !empty( $objects ) )
            {
                foreach ( $objects as $object )
                {
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

    public function onEdit( $param )
    {
          try
        {
            if( isset( $param[ "key" ] ) )
            {
                TTransaction::open( "sic" );

                $object = new EstabelecimentoMedicoRecord( $param[ "key" ] );


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
    

}