<?php

// Revisado 18.05.17

class MedicoForm extends TPage
{
    private $form;
    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder( "form_cadastro_medicos" );
        $this->form->setFormTitle( "Formulário de Cadastro de Médicos" );
        $this->form->class = "tform";

        $id               = new THidden( "id" );
        $nome             = new TEntry( "nome" );
        $municipio_id     = new TCombo( "municipio_id" );
        $telefone         = new TEntry( "telefone" );
        $email            = new TEntry( "email" );
        $celular         = new TEntry( "celular" );
        $crm         = new TEntry( "crm" );

        $nome->forceUpperCase();
        //$nome->setProperty( "title", "O campo é obrigatório" );
        //$nome->setSize( "38%" );

        $items = array();
        TTransaction::open('dbsic');
        $repository = new TRepository('MunicipioRecord');

        $criteria = new TCriteria;
        $criteria->setProperty('order', 'nome');
        
        $cadastros = $repository->load($criteria);
  
        foreach ($cadastros as $object) {
            $items[$object->id] = $object->nome;
        }

        $municipio_id->addItems($items);
        TTransaction::close(); 

        $nome->addValidation( "Nome", new TRequiredValidator );
        $municipio_id->addValidation( "Municipio", new TRequiredValidator );
        $crm->addValidation( "Causa Obito", new TRequiredValidator );

        $this->form->addFields( [ new TLabel( "Nome: <font color=red><b>*</b></font> ") ], [ $nome ] );
        $this->form->addFields( [ new TLabel( "Municipio:<font color=red><b>*</b></font>" ) ], [ $municipio_id ]);
        $this->form->addFields( [ new TLabel( "crm:<font color=red><b>*</b></font>" ) ], [ $crm ] );
        $this->form->addFields( [ new TLabel( "E-Mail:" ) ], [ $email ] );
        $this->form->addFields( [ new TLabel( "Telefone:" ) ], [ $telefone ] );
        $this->form->addFields( [ new TLabel( "Celular:" ) ], [ $celular ] );
        $this->form->addFields( [ $id ] );
       
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para a listagem", new TAction( [ "MedicoList", "onReload" ] ), "fa:table blue" );
      
        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( new TXMLBreadCrumb( "menu.xml", "MedicoList" ) );
        $container->add( $this->form );
        parent::add( $container );
    }
    public function onSave()
    {
        try
        {
            $this->form->validate();
            TTransaction::open( "dbsic" );

            $object = $this->form->getData( "MedicoRecord" );
            $object->store();
           TTransaction::close();
            $action = new TAction( [ "MedicoList", "onReload" ] );
            new TMessage( "info", "Registro salvo com sucesso!", $action );
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
                $object = new MedicoRecord( $param[ "key" ] );
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
}