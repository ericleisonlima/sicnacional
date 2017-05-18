<?php
class EstabelecimentoMedicoDetalhe extends TPage
{
    private $form;
    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder( "form_cadastro_estab_med" );
        $this->form->setFormTitle( "Formulário de Cadastro de Médicos nos Estabelecimentos" );
        $this->form->class = "tform";

        $id                         = new THidden( "id" );
        $estabelecimento_id         = new TCombo( "estabelecimento_id" );
        $medico_id                  = new TCombo( "medico_id" );
        $responsavel                = new TRadioGroup( "responsavel" );
        $datainicio                 = new TDate( "datainicio" );
        $datafim                    = new TDate( "datafim" );

        $datainicio->setMask('dd/mm/yyyy');
        $datafim->setMask('dd/mm/yyyy');
        $datainicio->setDatabaseMask('yyyy-mm-dd');
        $datafim->setDatabaseMask('yyyy-mm-dd');

        $responsavel->addItems(array('S'=>'SIM', 'N'=>'NAO'));
        $responsavel->setLayout('horizontal');

        $items = array();
        TTransaction::open('dbsic');
        $repository = new TRepository('MedicoRecord');

        $criteria = new TCriteria;
        $criteria->setProperty('order', 'nome');
        
        $cadastros = $repository->load($criteria);
  
        foreach ($cadastros as $object) {
            $items[$object->id] = $object->nome;
        }

        $medico_id->addItems($items);
        TTransaction::close(); 

        $items = array();
        TTransaction::open('dbsic');
        $repository = new TRepository('EstabelecimentoRecord');

        $criteria = new TCriteria;
        $criteria->setProperty('order', 'nome');
        
        $cadastros = $repository->load($criteria);
  
        foreach ($cadastros as $object) {
            $items[$object->id] = $object->nome;
        }

        $estabelecimento_id->addItems($items);
        TTransaction::close(); 

        $medico_id->addValidation( "Médico", new TRequiredValidator );
        $estabelecimento_id->addValidation( "Estabelecimento", new TRequiredValidator );
        $responsavel->addValidation( "Responsável", new TRequiredValidator );
        $datainicio->addValidation( "Data Início", new TRequiredValidator );

        $this->form->addFields( [ new TLabel( "Médico: <font color=red><b>*</b></font> ") ], [ $medico_id ] );
        $this->form->addFields( [ new TLabel( "Estabelecimento:<font color=red><b>*</b></font>" ) ], [ $estabelecimento_id ]);
        $this->form->addFields( [ new TLabel( "Responsável:" ) ], [ $responsavel ] );
        $this->form->addFields( [ new TLabel( "Data Início:" ) ], [ $datainicio ] );
        $this->form->addFields( [ new TLabel( "Data Fim:" ) ], [ $datafim ] );
        $this->form->addFields( [ $id ] );
       
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para a listagem", new TAction( [ "EstabelecimentoMedicoList", "onReload" ] ), "fa:table blue" );
      
        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( new TXMLBreadCrumb( "menu.xml", "EstabelecimentoMedicoList" ) );
        $container->add( $this->form );
        parent::add( $container );
    }
    public function onSave()
    {
        try
        {
            $this->form->validate();
            TTransaction::open( "dbsic" );

            $object = $this->form->getData( "EstabelecimentoMedicoRecord" );
            $object->store();
           TTransaction::close();
            $action = new TAction( [ "EstabelecimentoMedicoList", "onReload" ] );
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
                $object = new EstabelecimentoMedicoRecord( $param[ "key" ] );
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