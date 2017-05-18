<?php

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
        $this->form->addFields( [new TLabel('Escolaridade<font color=red><b>*</b></font> ')], [$descricao ]);

        $descricao->setSize('50%', 80);

        $this->form->addAction( "Voltar", new TAction( [ "EscolaridadeList", "onReload" ] ), "fa:table blue" );
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        
        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( $this->form );
        parent::add( $container );
    }

    public function onSave()
    {
        try
        {
            $this->form->validate();
            TTransaction::open( "dbsic" );

            $object = $this->form->getData( "EscolaridadeRecord" );

            $object->store();

            TTransaction::close();

            $action = new TAction( [ "EscolaridadeList", "onReload" ] );

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

                $object = new EscolaridadeRecord( $param[ "key" ] );

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
