<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);


class TipoExameList extends TPage
{
   
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $formgrid;
    private $loaded;
    private $deleteButton;
    private $transformCallback;

    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();

        
        $this->form = new BootstrapFormBuilder( "form_list_cadastro_tipoexame" );
        $this->form->setFormTitle( "Listagem de Tipos de Exame" );
        $this->form->class = "tform";

        $opcao = new TCombo( "opcao" );
        $dados = new TEntry( "dados" );
       
        $opcao->setDefaultOption( "..::SELECIONE::.." );
        $dados->setProperty( "title", "Informe os dados de acordo com a opção" );
        $dados->forceUpperCase();
        
        $opcao->setSize( "38%" );
        $dados->setSize( "38%" );
        
        $opcao->addItems( [ "nome" => "Nome"] );
        $this->form->addFields( [ new TLabel( "Opção de filtro:" ) ], [ $opcao ] );
        $this->form->addFields( [ new TLabel( "Dados da busca:" ) ], [ $dados ] );
        
        $this->form->addAction( "Buscar", new TAction( [ $this, "onSearch" ] ), "fa:search" );
        $this->form->addAction( "Novo", new TAction( [ "TipoExameForm", "onEdit" ] ), "bs:plus-sign green" );
        
        $this->datagrid = new BootstrapDatagridWrapper( new TDataGrid() );
        $this->datagrid->datatable = "true";
        $this->datagrid->style = "width: 100%";
        $this->datagrid->setHeight( 320 );
        

       

        $column_id = new TDataGridColumn( "id", "ID", "center", 50 );
        $column_nome = new TDataGridColumn( "nome", "Exame", "left" );
        $column_unidademedica = new TDataGridColumn( "unidademedica", "Unidade Medica", "left" );
        
        
        $this->datagrid->addColumn( $column_id );
        $this->datagrid->addColumn( $column_nome );
        $this->datagrid->addColumn( $column_unidademedica );
       

        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $order_nome = new TAction(array($this, 'onReload'));
        $order_nome->setParameter('order', 'nome');
        $column_nome->setAction($order_nome);

        // create EDIT action
        $action_edit = new TDataGridAction(array('TipoExameForm', 'onEdit'));
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel('Editar');
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);

        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel('Excluir');
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);

        // create the datagrid model
        $this->datagrid->createModel();

        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());


        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid));
        $container->add($this->pageNavigation);

        parent::add($container);
    }


    public function onReload( $param = NULL )
    {
        try
        {
            // Abrindo a conexao com o banco de dados
            TTransaction::open( "dbsic" );

            // Criando um repositorio para armazenar temporariamente os dados do banco
            $repository = new TRepository( "TipoExameRecord" );

            if ( empty( $param[ "order" ] ) )
            {
                $param[ "order" ] = "id";
                $param[ "direction" ] = "asc";
            }

            $limit = 10;

            // Criando um criterio de busca no banco de dados
            $criteria = new TCriteria();
            $criteria->setProperties( $param );
            $criteria->setProperty( "limit", $limit );

            // Buscando os dados no banco de acordo com os criterios passados
            $objects = $repository->load( $criteria, FALSE );

            // Limpando o datagrid
            $this->datagrid->clear();

            // Se existirem dados no banco, o datagrid sera prenchido por esse foreach
            if ( !empty( $objects ) )
            {
                foreach ( $objects as $object )
                {
                    $this->datagrid->addItem( $object );
                }
            }

            $criteria->resetProperties();

            // Salvando a contagem dos registros que estam no repositorio
            $count = $repository->count($criteria);

            $this->pageNavigation->setCount($count); // Definindo quantos registros tera por pagina do datagrid
            $this->pageNavigation->setProperties($param); // Definindo os paramentros de organizacao dos dados por pagina
            $this->pageNavigation->setLimit($limit); // Definindo o limite de registros por pagina do datagrid

            // Fechando a conexao com o banco de dados
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

                $repository = new TRepository( "TipoExameRecord" );

                if ( empty( $param[ "order" ] ) )
                {
                    $param[ "order" ] = "id";
                    $param[ "direction" ] = "asc";
                }

                $limit = 10;

                $criteria = new TCriteria();
                $criteria->setProperties( $param );
                $criteria->setProperty( "limit", $limit );

                if( $data->opcao == "nome" )
                {
                    $criteria->add( new TFilter( $data->opcao, "LIKE", "%" . $data->dados . "%" ) );
                }
                else if ( ( $data->opcao == "cpf" || $data->opcao == "rg" ) && ( is_numeric( $data->dados ) ) )
                {
                    $criteria->add( new TFilter( $data->opcao, "LIKE", $data->dados . "%" ) );
                }
                else
                {
                    new TMessage( "error", "O valor informado não é valido para um " . strtoupper( $data->opcao ) . "." );
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

                $this->pageNavigation->setCount( $count ); // count of records
                $this->pageNavigation->setProperties( $param ); // order, page
                $this->pageNavigation->setLimit( $limit ); //Limita a quantidade de registros

                TTransaction::close();

                $this->form->setData( $data );

                $this->loaded = true;

            }
            else
            {
                $this->onReload();

                $this->form->setData( $data );

                new TMessage( "error", "Selecione uma opção e informe os dados da busca corretamente!" );
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

            $object = new TipoExameRecord( $param[ "key" ] );

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
