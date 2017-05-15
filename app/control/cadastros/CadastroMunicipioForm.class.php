<?php

class CadastroMunicipioForm extends TPage{
    
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;

    public function __construct() {

        parent::__construct();

        $this->form = new BootstrapFormBuilder("form_list_cadastro_clientes" );
        $this->form->setFormTitle( "Cadastro de Municipio" );
        $this->form->class = "tform";

        $id  = new THidden( "id" );
        $nome = new TEntry( "nome" );        
        $codibge = new TEntry( "codibge" );
        $uf = new TCombo( "uf" );

        $nome->setProperty ( 'title', 'Digitar nome' );
        $codibge->setProperty ('title', 'Digitar IBGE');
        $uf->setDefaultOption ( '..::SELECIONE::..' );

        $nome->setSize( '30%' );
        $id->setSize( '30%' );
        $codibge->setSize('30%');
        $uf->setSize('30%');

        $uf->addItems( [ 'M' => 'Masculino', 'F' => 'Feminino'] );
        $this->form->addFields( [ new TLabel( 'Nome:' )  ], [ $nome ] );
        $this->form->addFields( [ new TLabel( 'IBGE: ' )], [$codibge] );
        $this->form->addFields( [ new TLabel( 'Estado:' ) ], [ $uf ] );
        $this->form->addFields( [ new TLabel( '' ) ], [ $id ] ); 

        $this->form->addAction('Voltar', new TAction( ["MunicipioList", 'onReload'] ), 'ico_back.png');
        $this->form->addAction( 'Cadastrar', new TAction( [$this, 'onSave'] ), 'fa:save' );

        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( $this->form );

        parent::add( $container );
    }

    public function onSave() 
    {
        
        try{
            TTransaction::open('dbsic');
            $object = $this->form->getData('MunicipioRecord');
            $object->store();
            
            TTransaction::close();
            
            new TMessage( 'info', 'Sucess');
        }
        catch (Exception $se){
            new TMessage('erro', $se->getMessage());
            TTransaction::rollback();
        }
    }
    
}
