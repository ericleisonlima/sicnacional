<?php

// Revisado 18.05.17

class CondicoesDiagnosticoForm extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder( "form_condicoes_diagnostico" );
        $this->form->setFormTitle( "Formulario de Condições de Diagnostico" );
        $this->form->class = "tform";
        $id        = new THidden( "id" );
        $descricao = new TText( "descricao" );

        $descricao->addValidation( "descricao", new TRequiredValidator );

        $this->form->addFields( [ $id ] );
        $this->form->addFields( [new TLabel('<font color=red><b>Diagnóstico</b></font>')], [$descricao ]);

        $descricao->setSize('50%', 80);

        $this->form->addAction( "Voltar para listagem", new TAction( [ "CondicoesDiagnosticoList", "onReload" ] ), "fa:table blue" );
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );

        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( new TXMLBreadCrumb( "menu.xml", "CondicoesDiagnosticoList" ) );
        $container->add( $this->form );
        parent::add( $container );
    }

    public function onSave()
    {
        try
        {
            $this->form->validate();
            TTransaction::open( "dbsic" );

            $object = $this->form->getData( "CondicoesDiagnosticoRecord" );

            $object->store();

            TTransaction::close();

            $action = new TAction( [ "CondicoesDiagnosticoList", "onReload" ] );

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

                $object = new CondicoesDiagnosticoRecord( $param[ "key" ] );

                //$object->descricao = TText( $object->descricao );

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
