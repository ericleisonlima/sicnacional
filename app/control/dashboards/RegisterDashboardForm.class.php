<?php
/*
 * @author Pedro Henrique
 * @date 27/04/2016
 */

class RegisterDashboardForm extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;

    public function __construct()
    {
        parent::__construct();

        //Criacao do formulario
        $this->form = new BootstrapFormBuilder( "form_register_dashboard" );
        $this->form->setFormTitle( "Formulário de dashboards" );
        $this->form->class = "tform";

        //Criacao dos campos do fomulario
        $id         = new THidden( "id" );
        $quantifier = new TCombo( "quantifier" );
        $dataview   = new TCombo( "dataview" );
        $title      = new TEntry( "title" );
        $icon       = new TEntry( "icon" );
        $color      = new TEntry( "color" );
        $page       = new TEntry( "page" );
        $action     = new TEntry( "action" );

        $items01 = [];
        $items02 = [
            "amount"   => "Quantidade total",
            "percent" => "Percentual total"
        ];

        try
        {
            TTransaction::open( "dbsic" );

            $repository = new TRepository( "VwApplicationViewsRecord" );

            $criteria = new TCriteria();
            $criteria->setProperty( "order", "TABLE_NAME" );

            $objects = $repository->load( $criteria );

            if ( isset( $objects ) )
            {
                foreach ( $objects as $object )
                {
                    $items01[ $object->TABLE_NAME ] = $object->TABLE_NAME;
                }
            }

            TTransaction::close();
        }
        catch (Exception $ex)
        {
            TTransaction::rollback();
        }

        $dataview->setDefaultOption( "..::SELECIONE::.." );
        $quantifier->setDefaultOption( "..::SELECIONE::.." );

        $dataview->addItems( $items01 );
        $quantifier->addItems( $items02 );

        //Definicao de tipo de caixa das letras
        //$title->forceUpperCase();
        $icon->forceLowerCase();
        $color->forceLowerCase();

        //Definicao de propriedades dos campos
        $dataview->setProperty( "title", "O campo é obrigatório" );
        $quantifier->setProperty( "title", "O campo é obrigatório" );
        $title->setProperty( "title", "O campo é obrigatório" );
        $icon->setProperty( "title", "O campo é obrigatório" );
        $color->setProperty( "title", "O campo é obrigatório" );

        //Definicao dos tamanhos de alguns campos do formulario
        $dataview->setSize( "38%" );
        $quantifier->setSize( "38%" );
        $title->setSize( "38%" );
        $icon->setSize( "38%" );
        $color->setSize( "38%" );
        $page->setSize( "38%" );
        $action->setSize( "38%" );

        //Classe que formata o texto de messagem de obrigatoriedade de campo
        $label01 = new RequiredTextFormat( [ "View de dados", "#F00", "bold" ] );
        $label02 = new RequiredTextFormat( [ "Quantificador", "#F00", "bold" ] );
        $label03 = new RequiredTextFormat( [ "Título", "#F00", "bold" ] );
        $label04 = new RequiredTextFormat( [ "Icone", "#F00", "bold" ] );
        $label05 = new RequiredTextFormat( [ "Cor", "#F00", "bold" ] );

        //Definicao de campos obrigatorios e requeridos especiais
        $dataview->addValidation( $label01->getText(), new TRequiredValidator );
        $quantifier->addValidation( $label02->getText(), new TRequiredValidator );
        $title->addValidation( $label03->getText(), new TRequiredValidator );
        $icon->addValidation( $label04->getText(), new TRequiredValidator );
        $color->addValidation( $label05->getText(), new TRequiredValidator );

        //Inserindo os campos ocultos no formulario
        $this->form->addFields( [ new TLabel( "View de dados:", "#F00" ) ], [ $dataview ] );
        $this->form->addFields( [ new TLabel( "Quantificador:", "#F00" ) ], [ $quantifier ] );
        $this->form->addFields( [ new TLabel( "Título:", "#F00" ) ], [ $title ] );
        $this->form->addFields( [ new TLabel( "Icone:", "#F00" ) ], [ $icon ] );
        $this->form->addFields( [ new TLabel( "Cor:", "#F00" ) ], [ $color ] );
        $this->form->addFields( [ new TLabel( "Página:" ) ], [ $page ] );
        $this->form->addFields( [ new TLabel( "Ação:" ) ], [ $action ] );
        $this->form->addFields( [ $id ] );

        //Criacao dos botoes com sua determinada acoes no fomulario
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Novo", new TAction( [ $this, "onEdit" ] ), "fa:eraser red" );
        $this->form->addAction( "Ir para dashboard", new TAction( [ "RegisterDashboardCreate", "onReload" ] ), "fa:table blue" );

        //Criacao do datagrid de listagem de searchValue
        $this->datagrid = new BootstrapDatagridWrapper( new TDataGrid() );
        $this->datagrid->datatable = "true";
        $this->datagrid->style = "width: 100%";
        $this->datagrid->setHeight( 320 );

        //Criacao das colunas do datagrid
        $column_title = new TDataGridColumn( "title", "Titulo", "left" );
        $column_icon = new TDataGridColumn( "icon", "Icone", "left" );
        $column_color = new TDataGridColumn( "color", "Cor", "left" );

        //Insercao das colunas no datagrid
        $this->datagrid->addColumn( $column_title );
        $this->datagrid->addColumn( $column_icon );
        $this->datagrid->addColumn( $column_color );

        //Insercao das acoes de ordenacao nas colunas do datagrid
        $order_title = new TAction( [ $this, "onReload" ] );
        $order_title->setParameter( "order", "title" );
        $column_title->setAction( $order_title );

        //Criacao da acao de edicao no datagrid
        $action_edit = new TDataGridAction( [ $this, "onEdit" ] );
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

        //Criacao do container que recebe o formulario
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
            //Validacao do formulario
            $this->form->validate();

            TTransaction::open( "dbsic" );

            //Resgata os dados inseridos no formulario a partir do modelo
            $object = $this->form->getData( "DashboardRecord" );

            $object->store();

            TTransaction::close();

            $action = new TAction( [ "RegisterDashboardForm", "onReload" ] );

            new TMessage( "info", "Registro salvo com sucesso!", $action );

            //TApplication::gotoPage("RegisterProductList", "onReload");
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();

            new TMessage( "error", "Ocorreu um erro ao tentar salvar o registro!<br>" . $ex->getMessage() );
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if( isset( $param[ "key" ] ) )
            {
                TTransaction::open( "dbsic" );

                $object = new DashboardRecord( $param[ "key" ] );

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

            $repository = new TRepository( "DashboardRecord" );

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

            $this->loaded = true;
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();

            new TMessage( "error", $ex->getMessage() );
        }

        // Chama a funcao que desabilita o campo searchValue no formulario
        // self::onChange( (array)$this->form->getData() );
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

    public function Delete( $param = NULL )
    {
        try
        {
            TTransaction::open( "dbsic" );

            $object = new DashboardRecord( $param[ "key" ] );

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
