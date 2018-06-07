
<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

class PacienteForm extends TPage
{
    private $form;
    public function __construct()
    {
        parent::__construct();
       
        $this->form = new BootstrapFormBuilder( "form_cadastro_paciente" );
        $this->form->setFormTitle( "Cadastro de Paciente" );
        $this->form->class = "tform";

        $this->form->setNotebookWrapper( new BootstrapNotebookWrapper(new TNotebook) );

        $id = new THidden( "id" );

        ##--Informações Pessoais--#

        $nome                   = new TEntry( "nome" );
        $municipio_id           = new TCombo( "municipio_id" );
        $nascimento             = new TDate( "datanascimento" );        
        $telefone               = new TEntry( "telefone" );
        $email                  = new TEntry( "email" );
        $nmoradores             = new TEntry("nmoradores");
        $etnia                  = new TEntry("etnia"); 
        $profissao              = new TEntry("profissao");
        $naturalidade           = new TEntry("naturalidade");
        $residencia             = new TEntry("residencia");
        $estado_civil           = new TEntry("estado_civil");        
        $peso_habitual          = new TEntry("peso_habitual");
        $altura                 = new TEntry("altura");        
        $data_inicio_fumante    = new TDate("data_inicio_fumante");
        $data_fim_fumante       = new TDate("data_fim_fumante");        
        $data_inicio_alcoolista = new TDate("data_inicio_alcoolista");
        $data_fim_alcoolista    = new TDate("data_fim_alcoolista");
        $tipo_fumante           = new TRadioGroup("tipo_fumante");
        $tipo_alcoolista        = new TRadioGroup("tipo_alcoolista");
        $empregado              = new TRadioGroup ("empregado");

        ##--Informações Clinicas--##

        $qtd_cirurgia              = new TEntry("qtd_cirurgia");
        $descricao_cirurgia        = new TEntry("descricao_cirurgia");
        $tiposanguineo             = new TCombo( "tiposanguineo" ); 
        $fatorsanguineo            = new TCombo( "fatorsanguineo" );
        $condicoes_diagnostico_id  = new TCombo("condicoes_diagnostico_id");
        $estabelecimento_medico_id = new TCombo( "estabelecimento_medico_id" );
        $situacao_clinica_id       = new TCombo( "situacao_clinica_id" );
        $datadiagnostico           = new TDate( "datadiagnostico" );
        $sexo                      = new TRadioGroup('sexo');

        ##--Radios Groups--##

        $sexo->addItems(array('M'=>'Masculino', 'F'=>'Feminino'));
        $sexo->setLayout('horizontal');

        $tipo_fumante->addItems(array('S'=>'Sim', 'N'=>'Não'));
        $tipo_fumante->setLayout('horizontal');          

        $tipo_alcoolista->addItems(array('S'=>'Sim', 'N'=>'Não'));
        $tipo_alcoolista->setLayout('horizontal'); 

        $empregado->addItems(array('S'=>'Sim', 'N'=>'Não'));
        $empregado->setLayout('horizontal'); 

        ##--Mascaras--##

        $nascimento->setMask( "dd/mm/yyyy" );
        $datadiagnostico->setMask( "dd/mm/yyyy" );
        $nascimento->setDatabaseMask('yyyy-mm-dd');
        $datadiagnostico->setDatabaseMask('yyyy-mm-dd');

        $nome->forceUpperCase();
        $nome->setProperty( "title", "O campo é obrigatório" );

        ##--Combos--##

        $tiposanguineo->addItems( [ "A" => "A", "B" => "B", "AB" => "AB", "O" => "O" ] );    

        $fatorsanguineo->addItems( [ "P" => "Positivo", "N" => "Negativo" ] );

        $condicoes_diagnostico_id = new TDBCombo('condicoes_diagnostico_id', 'dbsic', 'CondicoesDiagnosticoRecord', 
            'id', 'descricao', 'descricao');

        $situacao_clinica_id = new TDBCombo('situacao_clinica_id', 'dbsic', 'SituacaoClinicaRecord', 'id', 
            'situacao', 'situacao');


        $municipio_id = new TDBCombo('municipio_id', 'dbsic', 'MunicipioRecord', 'id', 'nome', 'nome');
        $municipio_id->style = "text-transform: uppercase;";
        $municipio_id->setProperty('placeholder', '....::::DIGITE O MUNICÍPIO::::....');
        $municipio_id->setMinLength(3);
        $municipio_id->setSize('30%');
        $municipio_id->enableSearch();      

      
        TTransaction::open('dbsic');
        $repository = new TRepository('EstabelecimentoMedicoRecord');


        $criteria = new TCriteria;
        $criteria->setProperty('order', 'id');
        $criteria->add(new TFilter('medico_id', '=', TSession::getValue('medico_id')));
        
        $cadastros = $repository->load($criteria);
  
        foreach ($cadastros as $object) {
            $items[$object->id] = $object->estabelecimento_nome;
        }

        $estabelecimento_medico_id->addItems($items);
        TTransaction::close(); 

        ##--Validações--##
        
        $nome->addValidation( "Nome", new TRequiredValidator );
        $municipio_id->addValidation( "Município", new TRequiredValidator );
        $nascimento->addValidation( "Data Nascimento", new TRequiredValidator );
        $tiposanguineo->addValidation( "Tipo Sanguíneo", new TRequiredValidator );
        $fatorsanguineo->addValidation( "Fator Sanguíneo", new TRequiredValidator );
        $datadiagnostico->addValidation( "Data Diagnóstico", new TRequiredValidator );
        $estabelecimento_medico_id->addValidation( "Estabelecimento Médico", new TRequiredValidator );
        $situacao_clinica_id->addValidation( "Situação Clinica", new TRequiredValidator );

        ##--Campos--##
         $this->form->appendPage('Page 1');
         $this->form->addFields( [ new TLabel( "Nome:<font color=red><b>*</b></font> ") ], [ $nome ] );
         $this->form->addFields( [ new TLabel( "Nascimento:<font color=red>*</font>" ) ], [ $nascimento ] );
         $this->form->addFields( [ new TLabel( "Sexo:" ) ], [ $sexo ] );
         $this->form->addFields( [ new TLabel( "Município:<font color=red><b>*</b></font>" ) ], [ $municipio_id ]);
         $this->form->addFields( [ new TLabel( "Data Diagnóstico:<font color=red>*</font>" ) ], [ $datadiagnostico ] );
         $this->form->addFields( [ new TLabel( "Condições Diagnóstico:<font color=red>* ") ], 
            [ $condicoes_diagnostico_id ] );
         $this->form->addFields( [ new TLabel( "Estabelecimento Médico:<font color=red><b>*</b></font>") ], [ $estabelecimento_medico_id ] );
         $this->form->addFields( [ new TLabel( "Situação Clinica:<font color=red><b>*</b></font>") ], [ $situacao_clinica_id ] );
         $this->form->addFields( [ new TLabel( "Tipo Sanguíneo:<font color=red>*</font>") ], [ $tiposanguineo ] );
         $this->form->addFields( [ new TLabel( "Fator Sanguíneo:<font color=red>*</font>" ) ], [ $fatorsanguineo ] );

         $this->form->addFields( [ new TLabel( "E-Mail:" ) ], [ $email ] );
         $this->form->addFields( [ new TLabel( "Telefone:" ) ], [ $telefone ] );

        $this->form->appendPage('Page 2');
        $this->form->addFields( [ new TLabel( "Cirurgias Realizadas:" ) ], [ $qtd_cirurgia ] );
        $this->form->addFields( [ new TLabel( "Descrição das Cirurgias:" ) ], [ $descricao_cirurgia ] );
        $this->form->addFields( [ new TLabel( "Fumante:" ) ], [ $tipo_fumante ] );
        $this->form->addFields( [ new TLabel( "Alcoolista:" ) ], [ $tipo_alcoolista ] );
        $this->form->addFields( [ new TLabel( "Trabalha:" ) ], [ $empregado ] );
        $this->form->addFields( [ new TLabel( "Profissão:" ) ], [ $profissao ] );


        
        //$this->form->addFields( [ new TLabel( "Causa Óbito:") ], [ $causa_obito ]);
        //$this->form->addFields( [ new TLabel( "Data Óbito:" ) ], [ $dataobito ] );

        $this->form->addField( new TLabel('<font color=red><b>* Campos Obrigatórios </b></font>');
        $this->form->addFields( [ $id ] );

        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para a listagem", new TAction( [ "PacienteList", "onReload" ] ), "fa:table blue" );
      
        $container = new TVBox();
        $container->style = "width: 90%";
        //$container->add( new TXMLBreadCrumb( "menu.xml", "PacienteList" ) );
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

            //$object->municipio_id = key($object->municipio_id);

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
                $object->datadiagnostico = TDate::date2br( $object->datadiagnostico );
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