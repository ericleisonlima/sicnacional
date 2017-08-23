<?php

// Revisado 18.05.17

class ComorbidadesForm extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder( "form_comorbidade" );
        $this->form->setFormTitle( "Formulário de Comorbidades" );
        $this->form->class = "tform";

        $id = new THidden( "id" );
        $nome = new TEntry( "nome" );

        //$nome->forceUpperCase();

        $nome->setProperty('title', 'O campo e obrigatorio');

        $nome->setSize('38%');

        $nome->addValidation( "Nome", new TRequiredValidator );

        //Insercao dos campos no formulario
        $this->form->addFields( [ $id ] );
        $this->form->addFields([new TLabel('Nome<font color=red>*</font>')], [$nome]);

        //Criacao dos botoes com sua determinada acoes no fomulario
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para a listagem", new TAction( [ "ComorbidadesList", "onReload" ] ), "fa:table blue" );

        //Criacao do container que recebe o formulario
        $container = new TVBox();
        $container->style = "width: 90%";
        // $container->add(new TXMLBreadCrumb( "menu.xml", "ComorbidadesList" ) );
        $container->add( $this->form );
        parent::add( $container );
    }
    public function onSave()
    {
        try
        {
            //Validacao do formulario
            $this->form->validate();

            TTransaction::open( "dbsic" );

            $object = $this->form->getData('ComorbidadesRecord');
            $object->store();

            TTransaction::close();

            $action = new TAction( [ "ComorbidadesList", "onReload" ] );
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
                $object = new ComorbidadesRecord($param['key']);
                $this->form->setData($object);

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
