<?php

// Revisado 19.05.17


class TipoExameForm extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

       

<<<<<<< HEAD
        $this->form = new BootstrapFormBuilder( "form_tipoexame" );
        $this->form->setFormTitle( "Formulários de Tipo de Exame" );
=======
        $this->form = new BootstrapFormBuilder( "form_cadastro_tipoexame" );
        $this->form->setFormTitle( "Cadastro de Exames" );
>>>>>>> 1a28a164c94122582d223f8cc54128cf4034c3d3
        $this->form->class = "tform";

        // create the form fields
        $id = new THidden('id');
        $nome = new TEntry('nome');
        $unidademedica = new TEntry('unidademedica');

        // add the fields
        $this->form->addFields(  [$id] );
        $this->form->addFields( [new TLabel('Tipo de Exame <font color=red>*</font>')], [$nome] );
        $this->form->addFields( [new TLabel('Unidade Medica <font color=red>*</font>')], [$unidademedica] );


        $id->setEditable(FALSE);
        $nome->setSize('39%');
        $unidademedica->setSize('39%');
        $nome->addValidation('Tipo de Exame', new TRequiredValidator );
        $unidademedica->addValidation('Unidade Medica', new TRequiredValidator );

        // create the form actions
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para listagem", new TAction( [ "TipoExameList", "onReload" ] ), "fa:table blue" );
       
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'TipoExameList'));
        $container->add($this->form);

        parent::add($container);
    }

    public function onSave()
    {
        try
        {
            $this->form->validate();

            TTransaction::open( "dbsic" );

            $object = $this->form->getData( "TipoExameRecord" );

            //$object->usuarioalteracao = TSession::getValue("login");
            //$object->dataalteracao = date( "Y-m-d H:i:s" );

            $object->store();

            TTransaction::close();

            $action = new TAction( [ "TipoExameList", "onReload" ] );

            new TMessage( "info", "Registro salvo com sucesso!", $action );

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

                $object = new TipoExameRecord( $param[ "key" ] );

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
