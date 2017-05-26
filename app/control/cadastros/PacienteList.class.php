<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_erros', 1);
//error_reporting(E_ALL);

class PacienteList extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;
    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder( "form_list_cadastro_paciente" );
        $this->form->setFormTitle( "Listagem de Pacientes" );
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
        $this->form->addAction( "Novo", new TAction( [ "PacienteForm", "onEdit" ] ), "bs:plus-sign green" );

        $this->datagrid = new BootstrapDatagridWrapper( new TDataGrid() );
        $this->datagrid->datatable = "true";
        $this->datagrid->style = "width: 100%";
        $this->datagrid->setHeight( 320 );

        $column_nome = new TDataGridColumn( "paciente_nome", "Nome", "left" );
        $column_tiposanguineo = new TDataGridColumn( "tiposanguineo", "Tipo Sanguíneo", "left" );
        $column_nome_municipio = new TDataGridColumn( "municipio_nome", "Municipio", "center" );
        $column_data_diagnostico = new TDataGridColumn( "datadiagnostico", "Data Diagnostico", "center" );

        $this->datagrid->addColumn( $column_nome );
        $this->datagrid->addColumn( $column_tiposanguineo);
        $this->datagrid->addColumn(  $column_nome_municipio );
        $this->datagrid->addColumn($column_data_diagnostico );

        $order_nome = new TAction( [ $this, "onReload" ] );
        $order_nome->setParameter( "order", "nome" );
        $column_nome->setAction( $order_nome );

        $order_tiposanguineo = new TAction( [ $this, "onReload" ] );
        $order_tiposanguineo->setParameter( "order", "tiposanguineo" );
        $column_tiposanguineo->setAction( $order_tiposanguineo );


        $action_edit = new TDataGridAction( [ "PacienteForm", "onEdit" ] );
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

        $action_doencabase = new TDataGridAction( [ "DoencaBaseDetalhe", "onReload" ] );
        $action_doencabase->setButtonClass( "btn btn-default" );
        $action_doencabase->setLabel( "Doenca Base" );
        $action_doencabase->setImage( "fa:heart-o fa-fw" );
        $action_doencabase->setField( "id" );
        $action_doencabase->setFk( "id" );
        
        $action_nutparen = new TDataGridAction( [ "NutricaoParenteralDetalhe", "onReload" ] );
        $action_nutparen->setButtonClass( "btn btn-default" );
        $action_nutparen->setLabel( "Nutrição Parenteral" );
        $action_nutparen->setImage( "fa:file-powerpoint-o fa-fw" );
        $action_nutparen->setField( "id" );
        $action_nutparen->setFk( "id" );

        $action_nut_en = new TDataGridAction( [ "NutricaoEnteralFormDetalhe", "onReload" ] );
        $action_nut_en->setButtonClass( "btn btn-default" );
        $action_nut_en->setLabel( "Nutrição Enteral" );
        $action_nut_en->setImage( "fa:file-o fa-fw" );
        $action_nut_en->setField( "id" );
        $action_nut_en->setFk( "id" );

        $action_uso_med = new TDataGridAction( [ "UsoMedicamentoDetalhe", "onReload" ] );
        $action_uso_med->setButtonClass( "btn btn-default" );
        $action_uso_med->setLabel( "Medicação Ministrada" );
        $action_uso_med->setImage( "fa:medkit fa-fw" );
        $action_uso_med->setField( "id" );
        $action_uso_med->setFk( "id" );

        $action_anamnese = new TDataGridAction( [ "AnamneseFormDetalhe", "onReload" ] );
        $action_anamnese->setButtonClass( "btn btn-default" );
        $action_anamnese->setLabel( "Anamnese" );
        $action_anamnese->setImage( "fa:stethoscope fa-fw" );
        $action_anamnese->setField( "id" );
        $action_anamnese->setFk( "id" );

        $action_exame = new TDataGridAction( [ "ExamePacienteDetalhe", "onReload" ] );
        $action_exame->setButtonClass( "btn btn-default" );
        $action_exame->setLabel( "Exames" );
        $action_exame->setImage( "fa:file-text-o fa-fw" );
        $action_exame->setField( "id" );
        $action_exame->setFk( "id" );

        $action_obito = new TDataGridAction( [ "ObitoPacienteDetalhe", "onEdit" ] );
        $action_obito->setButtonClass( "btn btn-default" );
        $action_obito->setLabel( "Óbito" );
        $action_obito->setImage( "fa:bed fa-fw" );
        $action_obito->setField( "id" );
        $action_obito->setFk( "id" );
        
        $action_group = new TDataGridActionGroup('Ações', 'bs:th');
        $action_group->addAction($action_anamnese);
        $action_group->addAction($action_doencabase);
        $action_group->addAction($action_exame);
        $action_group->addAction($action_nut_en);
        $action_group->addAction($action_nutparen);
        $action_group->addAction($action_uso_med);
        $action_group->addAction($action_obito);

        $this->datagrid->addActionGroup($action_group);

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
    public function onReload( $param = NULL )
    {
        try
        {

            TTransaction::open( "dbsic" );


            $repository = new TRepository( "VwPacienteMedicoRecord" );
            if ( empty( $param[ "order" ] ) )
            {
                $param[ "order" ] = "id";
                $param[ "direction" ] = "asc";
            }
            $limit = 10;


            $criteria = new TCriteria();
            $criteria->setProperties( $param );
            $criteria->setProperty( "limit", $limit );
            $criteria->add(new TFilter('medico_id', '=', TSession::getValue('medico_id')));

            $objects = $repository->load( $criteria, FALSE );

            $this->datagrid->clear();


            if ( !empty( $objects ) )
            {
                foreach ( $objects as $object )
                {
                     $object->datadiagnostico = TDate::date2br( $object->datadiagnostico );
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
                $repository = new TRepository( "PacienteRecord" );
                if ( empty( $param[ "order" ] ) )
                {
                    $param[ "order" ] = "id";
                    $param[ "direction" ] = "asc";
                }
                $limit = 10;
                $criteria = new TCriteria();
                $criteria->setProperties( $param );
                $criteria->setProperty( "limit", $limit );
                if( $data->opcao == "nome" && ( is_numeric( $data->dados ) ) )
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
