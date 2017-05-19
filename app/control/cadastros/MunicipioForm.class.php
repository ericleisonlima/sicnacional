<?php

// Revisado 19.05.17

class MunicipioForm extends TPage{
    
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;

    public function __construct() {

        parent::__construct();

        $this->form = new BootstrapFormBuilder("form_list_clientes" );
        $this->form->setFormTitle( "Formulário de Município" );
        $this->form->class = "tform";

        $id  = new THidden( "id" );
        $nome = new TEntry( "nome" );        
        $codibge = new TEntry( "codibge" );
        $uf = new TCombo( "uf" );

        $nome->setProperty ( 'title', 'Digitar Nome' );
        $codibge->setProperty ('title', 'Digitar Cod. IBGE');
        $uf->setDefaultOption ( '..::SELECIONE::..' );

        $nome->setSize( '30%' );
        $id->setSize( '30%' );
        $codibge->setSize('30%');
        $uf->setSize('30%');

        $uf->addItems( [ 'RN' => 'Rio Grande do Norte'] );

        $this->form->addFields( [ new TLabel( 'Nome:<font color=red><b>*</b></font> ' )  ], [ $nome ] );
        $this->form->addFields( [ new TLabel( 'Código IBGE: <font color=red><b>*</b></font> ' )], [$codibge] );
        $this->form->addFields( [ new TLabel( 'Estado:<font color=red><b>*</b></font> ') ], [ $uf ] );
        $this->form->addFields( [ new TLabel( '' ) ], [ $id ] ); 

        $nome->addValidation( "Nome", new TRequiredValidator );

        $this->form->addAction('Voltar', new TAction( ["MunicipioList", 'onReload'] ), 'ico_back.png');
        $this->form->addAction( 'Salvar', new TAction( [$this, 'onSave'] ), 'fa:save' );

        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( $this->form );

        parent::add( $container );
    }

    public function onEdit( $param )
    {
        try
        {
            if( isset( $param[ "key" ] ) )
            {
                TTransaction::open( "dbsic" );

                $object = new MunicipioRecord( $param[ "key" ] );

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

    public function onSave() 
    {
        
        try
        {
            $this->form->validate();

            TTransaction::open( "dbsic" );

            $object = $this->form->getData( "MunicipioRecord" );

            //$object->cpf = preg_replace( "/[^0-9]/", "", $object->cpf );
            //$object->nascimento = TDate::date2us( $object->nascimento );

            //$object->usuarioalteracao = TSession::getValue("login");/
            //$object->dataalteracao = date( "Y-m-d H:i:s" );

            $object->store();

            TTransaction::close();

            $action = new TAction( [ "MunicipioList", "onReload" ] );

            new TMessage( "info", "Registro salvo com sucesso!", $action );

        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();

            new TMessage( "error", "Ocorreu um erro ao tentar salvar o registro!<br><br>" . $ex->getMessage() );
        }
    }
    

    function onShow() {

    }
    
}

