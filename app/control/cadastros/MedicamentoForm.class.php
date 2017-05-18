<?php

// Revisado 18.05.17

class MedicamentoForm extends TPage
{
    private $form;
    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder( "form_Medicamentos" );
        $this->form->setFormTitle( "Formulário de Cadastro de Medicamento" );
        $this->form->class = "tform";

        $id               = new THidden( "id" );
        $nome             = new TEntry( "nome" );
        $tipomedicamento_id   = new TCombo( " tipomedicamento_id" );

        //$nome->forceUpperCase();

        $items = array();
        TTransaction::open('dbsic');
        $repository = new TRepository('TipoMedicamentoRecord');

        $criteria = new TCriteria;
        
        $criteria->setProperty('order', 'nome');
        
        $cadastros = $repository->load($criteria);
  
        foreach ($cadastros as $object) {
            $items[$object->id] = $object->nome;
        }

        $tipomedicamento_id->addItems($items);
        TTransaction::close(); 

        $nome->addValidation( "Nome", new TRequiredValidator );
        $tipomedicamento_id->addValidation( "Tipo do Medicamento", new TRequiredValidator );

        $this->form->addFields( [ new TLabel( "Nome: <font color=red><b>*</b></font> ") ], [ $nome ] );
        $this->form->addFields( [ new TLabel( "Tipo:<font color=red><b>*</b></font>" ) ], [ $tipomedicamento_id ]);
        $this->form->addFields( [ $id ] );
       
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para a listagem", new TAction( [ "MedicamentoList", "onReload" ] ), "fa:table blue" );
      
        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( new TXMLBreadCrumb( "menu.xml", "MedicamentoList" ) );
        $container->add( $this->form );
        parent::add( $container );
    }
    public function onSave()
    {
        try
        {
            $this->form->validate();
            TTransaction::open( "dbsic" );

            $object = $this->form->getData( "MedicamentoRecord" );
            $object->store();
           TTransaction::close();
            $action = new TAction( [ "MedicamentoList", "onReload" ] );
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
                $object = new MedicamentoRecord( $param[ "key" ] );
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