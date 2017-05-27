<?php

// Revisado 18.05.17

class SituacaoClinicaForm extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder( "form_situacao_clinica" );
        $this->form->setFormTitle( "Formulário de Situação Clinica" );
        $this->form->class = "tform";
        $id        = new THidden( "id" );
        $situacao = new TEntry( "situacao" );

        $situacao->addValidation( "situacao", new TRequiredValidator );

        $this->form->addFields( [ $id ] );
        $this->form->addFields( [new TLabel('Situação<font color=red><b>*</b></font>')], [$situacao ]);

        $situacao->setSize('50%', 80);

        $this->form->addAction( "Voltar para listagem", new TAction( [ "SituacaoClinicaList", "onReload" ] ), "fa:table blue" );
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );

        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( new TXMLBreadCrumb( "menu.xml", "SituacaoClinicaList") );
        $container->add( $this->form );
        parent::add( $container );
    }

    public function onSave()
    {
        try
        {
            $this->form->validate();
            TTransaction::open( "dbsic" );

            $object = $this->form->getData( "SituacaoClinicaRecord" );

            $object->store();

            TTransaction::close();

            $action = new TAction( [ "SituacaoClinicaList", "onReload" ] );

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

                $object = new SituacaoClinicaRecord( $param[ "key" ] );

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
