<?php

// Revisado 18.05.17

class CausaObitoForm extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder( "form_causa_obito" );
        $this->form->setFormTitle( "Formulário de Causas de Óbitos" );
        $this->form->class = "tform";

        $id        = new THidden( "id" );
        $descricao = new TText( "descricao" );

        $descricao->addValidation( "descricao", new TRequiredValidator );

        $this->form->addFields( [new TLabel('Descricão<font color=red>*</font>')], [$descricao ]);
        $this->form->addFields( [ $id ] );

        $descricao->setSize('50%', 80);

        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para listagem", new TAction( [ "CausaObitoList", "onReload" ] ), "fa:table blue" );

        $container = new TVBox();
        $container->style = "width: 90%";
        // $container->add(new TXMLBreadCrumb("menu.xml", "CausaObitoList"));
        $container->add( $this->form );

        parent::add( $container );
    }

    public function onSave()
    {
        try
        {
            $this->form->validate();
            TTransaction::open( "dbsic" );

            $object = $this->form->getData( "CausaObitoRecord" );

            $object->store();

            TTransaction::close();

            $action = new TAction( [ "CausaObitoList", "onReload" ] );

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

                $object = new CausaObitoRecord( $param[ "key" ] );

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
