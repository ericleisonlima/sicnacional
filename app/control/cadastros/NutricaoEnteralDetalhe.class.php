<?php


class NutricaoEnteralDetalhe extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        //Criacao do formulario
        $this->form = new BootstrapFormBuilder( "form_nutricaoenteral_detalhe" );
        $this->form->setFormTitle( "Nutrição Enteral Detalhe" );
        $this->form->class = "tform";

        $id                        = new THidden( "id" );
        $administracao             = new TCombo( "administracaonutricao_id" );
        $tiponutricao              = new TCombo( "tiponutricao_id" );
        $paciente                  = new TEntry( "paciente_id" );
        $datainicio                = new TDate("datainicio");
        $datafim                   = new TDate( "datafim" );
        $totalcaloria              = new TEntry( "totalcalorias" );
        $percentualdiario          = new TEntry( "percentualdiario" );
      

<<<<<<< HEAD
     
=======
>>>>>>> 758c1aba4c8269a6e4e2cc38900596cfd4e71f3d
        
        $administracao->setDefaultOption( "..::SELECIONE::.." );
        $tiponutricao->setDefaultOption("..::SELECIONE::..");
        $paciente->setEditable(FALSE);
 
      
        $administracao->setSize( "38%" );
        $tiponutricao->setSize( "38%" );
        $paciente->setSize( "38%" );
        $datainicio->setSize( "38%" );
        $datafim->setSize( "38%" );
        $totalcaloria->setSize( "38%" );
        $percentualdiario->setSize( "38%" );
      


        TTransaction::open('dbsic');
        $repository = new TRepository('AdministracaoNutricaoRecord');

        $criteria = new TCriteria();

        $objects = $repository->load($criteria);
        $item = array();
        if ($objects) {
            foreach ($objects as $object) {
                $item[$object->id] = $object->nome;
            }
        }
         TTransaction::close();

        $administracao->addItems($item);



             TTransaction::open('dbsic');
        $repository = new TRepository('TipoNutricaoRecord');

        $criteria = new TCriteria();

        $objects = $repository->load($criteria);
        $item = array();
        if ($objects) {
            foreach ($objects as $object) {
                $item[$object->id] = $object->nome;
            }
        }
         TTransaction::close();

        $tiponutricao->addItems($item);








    
       // $nome->addValidation( "Nome", new TRequiredValidator );
       // $cpf->addValidation( "CPF", new TRequiredValidator );
       // $email->addValidation( "E-mail", new TEmailValidator );


        $this->form->addFields( [ new TLabel( "Administrador:", "#FF0000" ) ], [ $administracao ] );
        $this->form->addFields( [ new TLabel( "Tipo de Nutrição:", "#FF0000" ) ], [ $tiponutricao ]);
        $this->form->addFields( [ new TLabel( "Nome do Paciente:","#FF0000" ) ], [ $paciente ]);
        $this->form->addFields( [ new TLabel( "Data Inicio:" ) ], [ $datainicio ] );
        $this->form->addFields( [ new TLabel( "Data Fim:") ], [ $datafim ] );
        $this->form->addFields( [ new TLabel( "Total Caloria:" ) ], [ $totalcaloria ] );
        $this->form->addFields( [ new TLabel( "Percentual Diario:" ) ], [ $percentualdiario ] );
       

        $this->form->addFields( [ $id ] );

        //Criacao dos botoes com sua determinada acoes no fomulario
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
       // $this->form->addAction( "Voltar para a listagem", new TAction( [ "CadastroClientesList", "onReload" ] ), "fa:table blue" );
$this->datagrid = new BootstrapDatagridWrapper( new TDataGrid() );
        $this->datagrid->datatable = "true";
        $this->datagrid->style = "width: 100%";
        $this->datagrid->setHeight( 320 );

        //Criacao das colunas do datagrid
        $column_tiponutricao = new TDataGridColumn( "tiponutricao_id", "Tipo Nutrição", "left", 90);
        $column_datainicio = new TDataGridColumn( "datainicio", "Data Inicio", "left" );
        $column_datafim = new TDataGridColumn( "datafim", "Data Fim", "left" );
        $column_totalcalorias = new TDataGridColumn( "totalcalorias", "Total Calorias", "left" );
        $column_percentualdiario = new TDataGridColumn( "percentualdiario", "Percentual Diario", "center" );

        //Insercao das colunas no datagrid
        $this->datagrid->addColumn( $column_tiponutricao );
        $this->datagrid->addColumn( $column_datainicio );
        $this->datagrid->addColumn( $column_datafim );
        $this->datagrid->addColumn( $column_totalcalorias );
        $this->datagrid->addColumn( $column_percentualdiario );

        
        $order_tiponutricao = new TAction( [ $this, "onReload" ] );
        $order_tiponutricao->setParameter( "order", "tiponutricao_id" );
        $column_tiponutricao->setAction( $order_tiponutricao );

        $order_datainicio = new TAction( [ $this, "onReload" ] );
        $order_datainicio->setParameter( "order", "datainicio" );
        $column_datainicio->setAction( $order_datainicio );


        $order_datafim = new TAction( [ $this, "onReload" ] );
        $order_datafim->setParameter( "order", "datafim" );
        $column_datafim->setAction( $order_datafim );

      
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

        // Criacao do container que recebe o formulario
        $container = new TVBox();
        $container->style = "width: 90%";
        //$container->add( new TXMLBreadCrumb( "menu.xml", __CLASS__ ) );
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

     
            $object = $this->form->getData( "NutricaoEnteralRecord" );


            $object->store();
           

            TTransaction::close();

            //$action = new TAction( [ "CadastroClientesList", "onReload" ] );

            new TMessage( "info", "Registro salvo com sucesso!" );

            // TApplication::gotoPage("CadastroClientesList", "onReload");
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

                $object = new NutricaoEnteralRecord( $param[ "key" ] );

                $object->nascimento = TDate::date2br( $object->nascimento );

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

    public function onReload()
    {
         try
        {
            // Abrindo a conexao com o banco de dados
            TTransaction::open( "dbsic" );

            // Criando um repositorio para armazenar temporariamente os dados do banco
            $repository = new TRepository( "NutricaoEnteralRecord" );

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

    public function onDelete()
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

    public function show()
    {
        $this->onReload();

        parent::show();
    }


}
