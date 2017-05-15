<?php
/*
 * @author Pedro Henrique
 * @date 06/05/2017
 */
class AnamneseList extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;


    public function __construct()
    {
        parent::__construct();
        // Criacao do formulario
        $this->form = new BootstrapFormBuilder( "form_list_cadastro_anamnese" );
        $this->form->setFormTitle( "Listagem de Anamneses" );
        $this->form->class = "tform";
        // Criacao dos campos do fomulario
        $opcao = new TCombo( "opcao" );
        $dados = new TEntry( "dados" );
        // Definicao de propriedades dos campos
        $opcao->setDefaultOption( "..::SELECIONE::.." );
        $dados->setProperty( "title", "Informe os dados de acordo com a opção" );
        $dados->forceUpperCase();
        // Definicao dos tamanhos do campos
        $opcao->setSize( "38%" );
        $dados->setSize( "38%" );
        // Definicao das opções dos combos
        $opcao->addItems( [ "nome" => "Nome", "cpf" => "CPF", "rg" => "RG" ] );
        $this->form->addFields( [ new TLabel( "Opção de filtro:" ) ], [ $opcao ] );
        $this->form->addFields( [ new TLabel( "Dados da busca:" ) ], [ $dados ] );
        // Criacao dos botoes com sua determinada acoes no fomulario
        $this->form->addAction( "Buscar", new TAction( [ $this, "onSearch" ] ), "fa:search" );
        $this->form->addAction( "Novo", new TAction( [ "CadastroClientesForm", "onEdit" ] ), "bs:plus-sign green" );
        //Criacao do datagrid de listagem de dados
        $this->datagrid = new BootstrapDatagridWrapper( new TDataGrid() );
        $this->datagrid->datatable = "true";
        $this->datagrid->style = "width: 100%";
        $this->datagrid->setHeight( 320 );
        //Criacao das colunas do datagrid
        $column_id = new TDataGridColumn( "id", "ID", "center", 50 );
        $column_nome = new TDataGridColumn( "nome", "Nome", "left" );
        $column_cpf = new TDataGridColumn( "cpf", "CPF", "left" );
        $column_rg = new TDataGridColumn( "rg", "RG", "left" );
        $column_email = new TDataGridColumn( "email", "E-mail", "center" );
        //Insercao das colunas no datagrid
        $this->datagrid->addColumn( $column_id );
        $this->datagrid->addColumn( $column_nome );
        $this->datagrid->addColumn( $column_cpf );
        $this->datagrid->addColumn( $column_rg );
        $this->datagrid->addColumn( $column_email );
        //Insercao das acoes de ordenacao nas colunas do datagrid
        $order_id = new TAction( [ $this, "onReload" ] );
        $order_id->setParameter( "order", "id" );
        $column_id->setAction( $order_id );
        $order_nome = new TAction( [ $this, "onReload" ] );
        $order_nome->setParameter( "order", "nome" );
        $column_nome->setAction( $order_nome );
        $order_cpf = new TAction( [ $this, "onReload" ] );
        $order_cpf->setParameter( "order", "cpf" );
        $column_cpf->setAction( $order_cpf );
        $order_rg = new TAction( [ $this, "onReload" ] );
        $order_rg->setParameter( "order", "rg" );
        $column_rg->setAction( $order_rg );
        $order_email = new TAction( [ $this, "onReload" ] );
        $order_email->setParameter( "order", "situacao" );
        $column_email->setAction( $order_email );
        //Criacao da acao de edicao no datagrid
        $action_edit = new TDataGridAction( [ "CadastroClientesForm", "onEdit" ] );
        $action_edit->setButtonClass( "btn btn-default" );
        $action_edit->setLabel( "Editar" );
        $action_edit->setImage( "fa:pencil-square-o blue fa-lg" );
        $action_edit->setField( "id" );
        $this->datagrid->addAction( $action_edit );
        //Criacao da acao de delecao no datagrid
        $action_del = new TDataGridAction( [ $this, "onDelete" ] );
        $action_del->setButtonClass( "btn btn-default" );
        $action_del->setLabel( "Deletar" );
        $action_del->setImage( "fa:trash-o red fa-lg" );
        $action_del->setField( "id" );
        $this->datagrid->addAction( $action_del );
        //Exibicao do datagrid
        $this->datagrid->createModel();
        //Criacao do navedor de paginas do datagrid
        $this->pageNavigation = new TPageNavigation();
        $this->pageNavigation->setAction( new TAction( [ $this, "onReload" ] ) );
        $this->pageNavigation->setWidth( $this->datagrid->getWidth() );
        // Criacao do container que recebe o formulario
        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( new TXMLBreadCrumb( "menu.xml", __CLASS__ ) );
        $container->add( $this->form );
        $container->add( TPanelGroup::pack( NULL, $this->datagrid ) );
        $container->add( $this->pageNavigation );
        // Adicionando o container com o form a pagina
        parent::add( $container );
    }
    public function onReload( $param = NULL )
    {
        try
        {
            // Abrindo a conexao com o banco de dados
            TTransaction::open( "db_compras" );
            // Criando um repositorio para armazenar temporariamente os dados do banco
            $repository = new TRepository( "ClientesRecord" );
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
                TTransaction::open( "db_compras" );
                $repository = new TRepository( "ClientesRecord" );
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
            TTransaction::open( "db_compras" );
            $object = new ClientesRecord( $param[ "key" ] );
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