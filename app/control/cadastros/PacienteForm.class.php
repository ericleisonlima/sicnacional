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
        $nascimento       = new TDate( "datanascimento" );
        $tiposanguineo    = new TCombo( "tiposanguineo" );
        $telefone         = new TEntry( "telefone" );
        $email            = new TEntry( "email" );
        $fatorsanguineo               = new TCombo( "fatorsanguineo" );
        $datadiagnostico              = new TDate( "datadiagnostico" );
        $condicoes_diagnostico_id     = new TCombo("condicoes_diagnostico_id");
        $estabelecimento_medico_id    = new TCombo( "estabelecimento_medico_id" );
        $situacao_clinica_id    = new TCombo( "situacao_clinica_id" );

        $sexo = new TRadioGroup('sexo');
        $sexo->addItems(array('M'=>'Masculino', 'F'=>'Feminino'));
        $sexo->setLayout('horizontal');

        $nascimento->setMask( "dd/mm/yyyy" );
        $datadiagnostico->setMask( "dd/mm/yyyy" );
        $nascimento->setDatabaseMask('yyyy-mm-dd');
        $datadiagnostico->setDatabaseMask('yyyy-mm-dd');

        $nome->forceUpperCase();
        $nome->setProperty( "title", "O campo é obrigatório" );

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

        $items = array();
        TTransaction::open('dbsic');
        $repository = new TRepository('EstabelecimentoRecord');


        $criteria = new TCriteria;
        $criteria->setProperty('order', 'nome');
        //$criteria->add(new TFilter('medico_id', '=', TSession::getValue('medico_id')));
        
        $cadastros = $repository->load($criteria);
  
        foreach ($cadastros as $object) {
            $items[$object->id] = $object->nome;
        }

        $estabelecimento_medico_id->addItems($items);
        TTransaction::close(); 

        //----------------------------------------------------------------------------------------------------------

        $items = array();
        TTransaction::open('dbsic');
        $repository = new TRepository('SituacaoClinicaRecord');

        $criteria = new TCriteria;
        $criteria->setProperty('order', 'id');
       // $criteria->add(new TFilter('medico_id', '=', TSession::getValue('medico_id')));
        
        $cadastros = $repository->load($criteria);
  
        foreach ($cadastros as $object) {
            $items[$object->id] = $object->situacao;
        }

        $situacao_clinica_id->addItems($items);
        TTransaction::close(); 
        //-----------------------------------------------------------------------------------------------------------------------

        $tiposanguineo->addItems( [ "A" => "A", "B" => "B", "AB" => "AB", "O" => "O" ] );    
        $fatorsanguineo->addItems( [ "P" => "Positivo", "N" => "Negativo" ] );

        $nome->addValidation( "Nome", new TRequiredValidator );
        $municipio_id->addValidation( "Município", new TRequiredValidator );
        $nascimento->addValidation( "Data Nascimento", new TRequiredValidator );
        $tiposanguineo->addValidation( "Tipo Sanguíneo", new TRequiredValidator );
        $fatorsanguineo->addValidation( "Fator Sanguíneo", new TRequiredValidator );
        $datadiagnostico->addValidation( "Data Diagnóstico", new TRequiredValidator );
        $estabelecimento_medico_id->addValidation( "Estabelecimento Médico", new TRequiredValidator );
        $situacao_clinica_id->addValidation( "Situação Clinica", new TRequiredValidator );


        $this->form->addFields( [ new TLabel( "Nome:<font color=red><b>*</b></font> ") ], [ $nome ] );
        $this->form->addFields( [ new TLabel( "Nascimento:<font color=red>*</font>" ) ], [ $nascimento ] );
        $this->form->addFields( [ new TLabel( "Sexo:" ) ], [ $sexo ] );
        $this->form->addFields( [ new TLabel( "Município:<font color=red><b>*</b></font>" ) ], [ $municipio_id ]);
        $this->form->addFields( [ new TLabel( "Data Diagnóstico:<font color=red>*</font>" ) ], [ $datadiagnostico ] );
        $this->form->addFields( [ new TLabel( "Condições Diagnóstico:<font color=red>* ") ], [ $condicoes_diagnostico_id ] );
        $this->form->addFields( [ new TLabel( "Estabelecimento Médico:<font color=red><b>*</b></font>") ], [ $estabelecimento_medico_id ] );
        $this->form->addFields( [ new TLabel( "Situação Clinica:<font color=red><b>*</b></font>") ], [ $situacao_clinica_id ] );
        $this->form->addFields( [ new TLabel( "Tipo Sanguíneo:<font color=red>*</font>") ], [ $tiposanguineo ] );
        $this->form->addFields( [ new TLabel( "Fator Sanguíneo:<font color=red>*</font>" ) ], [ $fatorsanguineo ] );

        $this->form->addFields( [ new TLabel( "E-Mail:" ) ], [ $email ] );
        $this->form->addFields( [ new TLabel( "Telefone:" ) ], [ $telefone ] );
        
        //$this->form->addFields( [ new TLabel( "Causa Óbito:") ], [ $causa_obito ]);
        //$this->form->addFields( [ new TLabel( "Data Óbito:" ) ], [ $dataobito ] );
        
        

        $this->form->addFields( [new TLabel('<font color=red><b>* Campos Obrigatórios </b></font>'), []] );
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
            $object->store();
            
            TTransaction::close();
            $action = new TAction( [ "PacienteList", "onReload" ] );
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