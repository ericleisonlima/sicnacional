<?php

// Revisado 18.05.17

class AdministracaoNutricaoForm extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder( "form_administracao_nutricao" );
        $this->form->setFormTitle( "Formulário de Administração Nutricional" );
        $this->form->class = "tform";

        // create the form fields
        $id = new THidden('id');
        $nome = new TEntry('nome');

        $this->form->addFields( [new TLabel('Nome:<font color=red><b>*</b></font>')], [$nome] );
        $this->form->addFields( [new TLabel('<font color=red><b>*</b></font>Campos Obrigatórios'), []] );
        $this->form->addFields( [$id] );

        $nome->setSize('38%');
        $nome->addValidation('Nome', new TRequiredValidator );

        // create the form actions
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para listagem", new TAction( [ "AdministracaoNutricaoList", "onReload" ] ), "fa:table blue" );

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', 'AdministracaoNutricaoList'));
        $container->add($this->form);

        parent::add($container);
    }

    public function onSave()
    {
        try
        {
            //Validacao do formulario
            $this->form->validate();

            TTransaction::open( "dbsic" );

            $object = $this->form->getData( "AdministraNutricaoRecord" );

            $object->store();

            TTransaction::close();

            $action = new TAction( [ "AdministracaoNutricaoList", "onReload" ] );

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

                $object = new AdministraNutricaoRecord( $param[ "key" ] );

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
