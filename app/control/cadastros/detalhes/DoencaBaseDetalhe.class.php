
<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_erros', 1);
//error_reporting(E_ALL);

class DoencaBaseDetalhe extends TWindow
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;

    public function __construct()
    {
        parent::__construct();
        parent::SetSize(0.800,0.800);

        $this->form = new BootstrapFormBuilder( "form_list_doeca_base" );
        $this->form->setFormTitle( "Detalhamento das Doenças Base" );
        $this->form->class = "tform";
      

        $id = new THidden( "id" );
        $paciente_id = new THidden("paciente_id");
        $cid_id = new THidden("cid_id");        

        $cid_codigo = new TDBMultiSearch('cid_codigo', 'dbsic', 'CidRecord', 'id', 'nome', 'nome');
        $cid_codigo->style = "text-transform: uppercase;";
        $cid_codigo->setProperty('placeholder', '...........    ...::::::: DIGITE A DOENÇA OU CID :::::::..............');
        $cid_codigo->setMinLength(1);
        $cid_codigo->setMaxSize(1);
        $cid_codigo->setSize('40%');

        $paciente_id->setValue(filter_input(INPUT_GET, 'fk'));
        TTransaction::open('dbsic');
        $tempVisita = new PacienteRecord( filter_input( INPUT_GET, 'fk' ) );

          if( $tempVisita ){
            $paciente_nome = new TLabel( $tempVisita->nome );
            $paciente_nome->setEditable(FALSE);
        }

        
        TTransaction::close();


        $this->form->addFields( [new TLabel('Paciente: '), $paciente_nome] );

        $this->form->addFields( [ new TLabel( "CID" ),  $cid_codigo  ] );

        $this->form->addFields( [ $id, $cid_id ] );


        $action = new TAction(array($this, 'onSave'));
        $action->setParameter('key', '' . filter_input(INPUT_GET, 'key') . '');
        $action->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');

        
        $voltar = new TAction(array('PacienteDetail','onReload'));
        $voltar->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');


        $this->form->addAction('Salvar', $action, 'fa:floppy-o');
        $this->form->addAction('Voltar para Pacientes',$voltar,'fa:table blue');


        $this->datagrid->createModel();

        $this->pageNavigation = new TPageNavigation();
        $this->pageNavigation->setAction( new TAction( [ $this, "onReload" ] ) );


        $container = new TVBox();
        $container->style = "width: 90%";
        //$container->add( new TXMLBreadCrumb( "menu.xml", "PacienteList" ) );
        $container->add( $this->form );
        $container->add( $this->pageNavigation );


        parent::add( $container );
    }
    public function onSave( $param = NULL )
    {
        try
        {
            $this->form->validate();
            TTransaction::open( "dbsic" );

            $object = $this->form->getData( "DoencaBaseRecord" );
            $object->paciente_id =  filter_input(INPUT_GET, 'fk');
            $object->cid_id = key($object->cid_codigo);
            unset($object->cid_id_name);
            unset($object->paciente_nome);
            $object->store();


            TTransaction::close();
           
            $action = new TAction( [ $this , "onReload" ] );
            $action->setParameter('key', '' . $param['key'] . '');
            $action->setParameter('fk', '' . $param['fk'] . '');
            new TMessage( "info", "Registro salvo com sucesso!", $action );
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            new TMessage( "error", "Ocorreu um erro ao tentar salvar o registro!<br><br>" . $ex->getMessage() );
        }
    }

    public function onReload( $param = NULL )
    {

    }
    

}

