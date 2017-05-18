<?php

class PacienteForm extends TPage
{
    private $form;
    public function __construct()
    {
        parent::__construct();
       

        $this->form = new BootstrapFormBuilder( "form_cadastro_paciente" );
        $this->form->setFormTitle( "Cadastro de Paciente" );
        $this->form->class = "tform";
      

        $id               = new THidden( "id" );
        $nome             = new TEntry( "nome" );
        $municipio_id     = new TCombo( "municipio_id" );
        $causa_obito      = new TCombo( "causa_obito_id" );        
        $nascimento       = new TDate( "datanascimento" );
        $tiposanguineo    = new TCombo( "tiposanguineo" );
        $dataobito        = new TDate( "dataobito" );
        $telefone         = new TEntry( "telefone" );
        $email            = new TEntry( "email" );
        $fatorsanguineo               = new TCombo( "fatorsanguineo" );
        $datadiagnostico              = new TDate( "datadiagnostico" );
        $condicoes_diagnostico_id     = new TCombo("condicoes_diagnostico_id");
        $estabelecimento_medico_id    = new TCombo( "estabelecimento_medico_id" );
            
        //$nascimento->setMask( "dd/mm/yyyy" );


        $nome->forceUpperCase();
  
        $nome->setProperty( "title", "O campo é obrigatório" );
      
        $nome->setSize( "38%" );
        $municipio_id->setSize( "38%" );
        $causa_obito->setSize( "38%" );
        $nascimento->setSize( "38%" );
        $tiposanguineo->setSize( "38%" );
        $dataobito->setSize( "38%" );
        $telefone->setSize( "38%" );
        $email->setSize( "38%" );
        $fatorsanguineo->setSize( "38%" );
        $datadiagnostico->setSize( "38%" );
        $condicoes_diagnostico_id->setSize( "38%" );
        $estabelecimento_medico_id->setSize( "38%" );

        //----------------------------------------------------------------------------------------------------
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


        //----------------------------------------------------------------------------------------------------

        $items = array();
        TTransaction::open('dbsic');
        $repository = new TRepository('CausaObitoRecord');

        $criteria = new TCriteria;
        $criteria->setProperty('order', 'descricao');
        
        $cadastros = $repository->load($criteria);
  
        foreach ($cadastros as $object) {
            $items[$object->id] = $object->descricao;
        }

        $causa_obito->addItems($items);
        TTransaction::close(); 

        //----------------------------------------------------------------------------------------------------

        $items = array();
        TTransaction::open('dbsic');
        $repository = new TRepository('CondicoesDiagnosticoRecord');

        $criteria = new TCriteria;
        $criteria->setProperty('order', 'descricao');
        
        $cadastros = $repository->load($criteria);
  
        foreach ($cadastros as $object) {
            $items[$object->id] = $object->descricao;
        }

        $condicoes_diagnostico_id->addItems($items);
        TTransaction::close(); 

        //----------------------------------------------------------------------------------------------------

        $items = array();
        TTransaction::open('dbsic');
        $repository = new TRepository('EstabelecimentoMedicoRecord');

        $criteria = new TCriteria;
        $criteria->setProperty('order', 'id');
        
        $cadastros = $repository->load($criteria);
  
        foreach ($cadastros as $object) {
            $items[$object->id] = $object->id;
        }

        $estabelecimento_medico_id->addItems($items);
        TTransaction::close(); 

        //----------------------------------------------------------------------------------------------------

        $tiposanguineo->addItems( [ "A" => "A", "B" => "B", "AB" => "AB", "O" => "O" ] );    
        $fatorsanguineo->addItems( [ "P" => "Positivo", "N" => "Negativo" ] );

/*
        $nome->addValidation( "Nome", new TRequiredValidator );
        $municipio_id->addValidation( "Municipio", new TRequiredValidator );
        $causa_obito->addValidation( "Causa Obito", new TRequiredValidator );
        $nascimento->addValidation( "Data Nascimento", new TRequiredValidator );
        $tiposanguineo->addValidation( "Tipo Sanguineo", new TRequiredValidator );
        $dataobito->addValidation( "Data Obito", new TRequiredValidator );
        $telefone->addValidation( "Telefone", new TRequiredValidator );
        $email->addValidation( "Email", new TRequiredValidator );
        $fatorsanguineo->addValidation( "Fator Sanguineo", new TRequiredValidator );
        $datadiagnostico->addValidation( "Data Diagnostico", new TRequiredValidator );
        $condicoes_diagnostico_id->addValidation( "Condicoes Diagnostico", new TRequiredValidator );
        $estabelecimento_medico_id->addValidation( "Estabelecimento Medico", new TRequiredValidator );
 */ 

        $this->form->addFields( [ new TLabel( "Nome: <font color=red><b>*</b></font> ") ], [ $nome ] );
        $this->form->addFields( [ new TLabel( "Nascimento:" ) ], [ $nascimento ] );
        $this->form->addFields( [ new TLabel( "Municipio:<font color=red><b>*</b></font>" ) ], [ $municipio_id ]);
        $this->form->addFields( [ new TLabel( "E-Mail:" ) ], [ $email ] );
        $this->form->addFields( [ new TLabel( "Telefone:" ) ], [ $telefone ] );
        $this->form->addFields( [ new TLabel( "Tipo Sanguineo:") ], [ $tiposanguineo ] );
        $this->form->addFields( [ new TLabel( "Fator Sanguineo:" ) ], [ $fatorsanguineo ] );
        $this->form->addFields( [ new TLabel( "Causa Obito: <font color=red><b>*</b></font>") ], [ $causa_obito ]);
        $this->form->addFields( [ new TLabel( "Data Obito:" ) ], [ $dataobito ] );
        $this->form->addFields( [ new TLabel( "Data Diagnostico:" ) ], [ $datadiagnostico ] );
        $this->form->addFields( [ new TLabel( "Condições Diagnostico:<font color=red><b>*</b></font>") ], [ $condicoes_diagnostico_id ] );
        $this->form->addFields( [ new TLabel( "Estabelecimento Medico:<font color=red><b>*</b></font>") ], [ $estabelecimento_medico_id ] );
        $this->form->addFields( [ $id ] );
       

        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para a listagem", new TAction( [ "PacienteList", "onReload" ] ), "fa:table blue" );
      
      
        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( new TXMLBreadCrumb( "menu.xml", "PacienteList" ) );
        $container->add( $this->form );
        parent::add( $container );
    }
    public function onSave()
    {
        try
        {

            $this->form->validate();
            TTransaction::open( "dbsic" );

            $object = $this->form->getData( "PacienteRecord" );

            //$object->nascimento = TDate::date2br( $object->nascimento );

            //$object->usuarioalteracao = TSession::getValue("login");
            //$object->dataalteracao = date( "Y-m-d H:i:s" );
            $object->store();
           TTransaction::close();
            $action = new TAction( [ "PacienteList", "onReload" ] );
            new TMessage( "info", "Registro salvo com sucesso!", $action );
             //TApplication::gotoPage("PacienteList", "onReload");
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
                $object = new PacienteRecord( $param[ "key" ] );
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