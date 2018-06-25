
<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_erros', 1);
//error_reporting(E_ALL);

class PacienteForm extends TPage
{
    private $form;
    public function __construct()
    {
        parent::__construct();
       
        $this->form = new BootstrapFormBuilder( "form_cadastro_paciente" );
        $this->form->setFormTitle( "Cadastro de Paciente" );
        $this->form->class = "form_cadastro_pacienteform_cadastro_paciente";

        $notebook = new BootstrapNotebookWrapper(new TNotebook);
        $this->form->add($notebook);
        
        $page1 = new TTable;
        $page2 = new TTable;
        $page1->style = 'padding: 10px';
        $page2->style = 'padding: 10px';
        
        // adds two pages in the notebook
        $notebook->appendPage('Informações Pessoais', $page1);
        $notebook->appendPage('Informações Clinicas', $page2);

        $id = new THidden( "id" );

        ##--Informações Pessoais--#

        $nome                   = new TEntry( "nome" );
        $telefone               = new TEntry( "telefone" );
        $email                  = new TEntry( "email" );
        $nmoradores             = new TEntry("nmoradores");
        $profissao              = new TEntry("profissao");
        $naturalidade           = new TEntry("naturalidade");
        $residencia             = new TEntry("residencia");    
        $procedencia            = new TEntry("procedencia"); 
        $municipio_id           = new TCombo( "municipio_id" );  
        $etnia                  = new TCombo("etnia"); 
        $estado_civil           = new TCombo("estado_civil");  
        $nascimento             = new TDate( "datanascimento" );             
        $empregado              = new TRadioGroup ("empregado");

        ##--Informações Clinicas--##

        $qtd_cirurgia               = new TEntry("qtd_cirurgia");
        $peso_habitual              = new TEntry("peso_habitual");
        $altura                     = new TEntry("altura"); 
        $descricao_cirurgia         = new TText("descricao_cirurgia");
        $tiposanguineo              = new TCombo( "tiposanguineo" ); 
        $fatorsanguineo             = new TCombo( "fatorsanguineo" );
        $condicoes_diagnostico_id   = new TCombo("condicoes_diagnostico_id");
        $estabelecimento_medico_id  = new TCombo( "estabelecimento_medico_id" );
        $situacao_clinica_id        = new TCombo( "situacao_clinica_id" );
        $datadiagnostico            = new TDate( "datadiagnostico" );
        $data_inicio_fumante        = new TDate("data_inicio_fumante");
        $data_fim_fumante           = new TDate("data_fim_fumante");        
        $data_inicio_alcoolista     = new TDate("data_inicio_alcoolista");
        $data_fim_alcoolista        = new TDate("data_fim_alcoolista");
        $tipo_fumante               = new TRadioGroup("tipo_fumante");
        $tipo_alcoolista            = new TRadioGroup("tipo_alcoolista");
        $sexo                       = new TRadioGroup('sexo');

        ##--Radios Groups--##

        $sexo->addItems(array('M'=>'Masculino', 'F'=>'Feminino'));
        $sexo->setLayout('horizontal');

        $tipo_fumante->addItems(array('SIM'=>'Sim', 'NAO'=>'Não','EX-ALCOOLISTA'=>'Ex-Fumante'));
        $tipo_fumante->setLayout('horizontal'); 
        $tipo_fumante->setValue('NAO');


        $tipo_alcoolista->addItems(array('SIM'=>'Sim', 'NAO'=>'Não', 'EX-ALCOOLISTA'=>'Ex-Alcoolista'));
        $tipo_alcoolista->setLayout('horizontal'); 
        $tipo_alcoolista->setValue('NAO');

        $empregado->addItems(array('S'=>'Sim', 'N'=>'Não'));
        $empregado->setLayout('horizontal'); 
        $empregado->setValue('sim');

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

        $estado_civil->addItems( [ "SOLTEIRO" => "Solteiro", "CASADO" => "Casado", "VIUVO" => "Viuvo", 
            "UNIAO_ESTAVEL" => "União Estavel", "DIVORCIADO"=>"Divorciado", "OUTROS"=>"Outros" ] );    
        
        $etnia->addItems( [ "BRANCO" => "Branco", "PARDO" => "Pardo", "NEGRO" => "Negro", "AMARELO" => "Amarelo", "INDIGENA" => "Indígena" ] ); 

        $condicoes_diagnostico_id = new TDBCombo('condicoes_diagnostico_id', 'dbsic', 'CondicoesDiagnosticoRecord', 
            'id', 'descricao', 'descricao');

        $situacao_clinica_id = new TDBCombo('situacao_clinica_id', 'dbsic', 'SituacaoClinicaRecord', 'id', 
            'situacao', 'situacao');


        $municipio_id = new TDBCombo('municipio_id', 'dbsic', 'MunicipioRecord', 'id', 'nome', 'nome');
        $municipio_id->style = "text-transform: uppercase;";
        $municipio_id->setProperty('placeholder', '....::::DIGITE O MUNICÍPIO::::....');
        //$municipio_id->setMinLength('3');
        $municipio_id->setSize('100%');
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

        ##--Campos Informações Pessoais--##
        
        $page1->addRowSet( [ new TLabel( "Nome:<font color=red><b>*</b></font> ") ], [ $nome ] );
        $page1->addRowSet( [ new TLabel( "Nascimento:<font color=red>*</font>" ) ], [ $nascimento ] );
        $page1->addRowSet( [ new TLabel( "Sexo:" ) ], [ $sexo ] );
        $page1->addRowSet( [ new TLabel( "Etnia:" ) ], [ $etnia ] );
        $page1->addRowSet( [ new TLabel( "Naturalidade:" ) ], [ $naturalidade ] );
        $page1->addRowSet( [ new TLabel( "Procedencia:" ) ], [ $procedencia ] );
        $page1->addRowSet( [ new TLabel( "Residencia:" ) ], [ $residencia ] );
        $page1->addRowSet( [ new TLabel( "Número de Moradores:" ) ], [ $nmoradores ] );
        $page1->addRowSet( [ new TLabel( "Estado Civil:" ) ], [ $estado_civil ] );

        $page1->addRowSet( [ new TLabel( "Município:<font color=red><b>*</b></font>" ) ], [ $municipio_id ]);
        $page1->addRowSet( [ new TLabel( "Trabalha:" ) ], [ $empregado ] );
        $page1->addRowSet( [ new TLabel( "Profissão:" ) ], [ $profissao ] );        
        $page1->addRowSet( [ new TLabel( "E-Mail:" ) ], [ $email ] );
        $page1->addRowSet( [ new TLabel( "Telefone:" ) ], [ $telefone ] );
        

        ##--Campos Informações Clinicas--##
        $page2->addRowSet( [ new TLabel( "Data Diagnóstico:<font color=red>*</font>" ) ], [ $datadiagnostico ] );
        $page2->addRowSet( [ new TLabel( "Estabelecimento Médico:<font color=red><b>*</b></font>") ], 
            [ $estabelecimento_medico_id ] );
        $page2->addRowSet( [ new TLabel( "Situação Clinica:<font color=red><b>*</b></font>") ], 
            [ $situacao_clinica_id ] );
        $page2->addRowSet( [ new TLabel( "Condições Diagnóstico:<font color=red>* ") ], 
            [ $condicoes_diagnostico_id ] );
        $page2->addRowSet( [ new TLabel( "Peso Habitual:" ) ], [ $peso_habitual ] );
        $page2->addRowSet( [ new TLabel( "Cirurgias Realizadas:" ) ], [ $qtd_cirurgia ] );
        $page2->addRowSet( [ new TLabel( "Descrição das Cirurgias:" ) ], [ $descricao_cirurgia ] ); 
        $page2->addRowSet( [ new TLabel( "Fumante:" ) ], [ $tipo_fumante ] );
        $page2->addRowSet( [ new TLabel( "Inicio do Tabagismo:" ) ], [ $data_inicio_fumante ] );
        $page2->addRowSet( [ new TLabel( "Fim do Tabagismo:" ) ], [ $data_fim_fumante ] );
        $page2->addRowSet( [ new TLabel( "Alcoolista:" ) ], [ $tipo_alcoolista ] );
        $page2->addRowSet( [ new TLabel( "Inicio Alcoolista:" ) ], [ $data_inicio_alcoolista ] );
        $page2->addRowSet( [ new TLabel( "Fim Alcoolista:" ) ], [ $data_fim_alcoolista ] );
        $page2->addRowSet( [ new TLabel( "Altura" ) ], [ $altura ] );   
        $page2->addRowSet( [ new TLabel( "Tipo Sanguíneo:<font color=red>*</font>") ], [ $tiposanguineo ] );
        $page2->addRowSet( [ new TLabel( "Fator Sanguíneo:<font color=red>*</font>" ) ], [ $fatorsanguineo ] );
           


        ##--Set Fields--##

        $this->form->setFields(array(
            $nome, $nascimento, $sexo, $municipio_id, $datadiagnostico,
            $estabelecimento_medico_id, $situacao_clinica_id, 
            $tiposanguineo, $fatorsanguineo, $email, $telefone, $qtd_cirurgia, 
            $descricao_cirurgia, $tipo_fumante, $tipo_alcoolista, $empregado, $profissao, $data_inicio_fumante,
            $data_fim_fumante, $data_inicio_alcoolista, $data_fim_alcoolista, $etnia, $estado_civil, $residencia, 
            $procedencia, $nmoradores, $naturalidade, $altura
             ));

        ##--Buttões--##
        
        $this->form->addFields( [new TLabel('<font color=red><b>* Campos Obrigatórios </b></font>'), []] );
        $this->form->addFields( [ $id ] );

        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para a listagem", new TAction( [ "PacienteList", "onReload" ] ),
         "fa:table blue" );

        ##--Change Action--##

            $acaofumante = new TAction(array($this, 'onChangeFumante'));
            $acaofumante->setParameter('formName', $this->form->getName());
            $tipo_fumante->setChangeAction($acaofumante);

            $acaoalcoolista = new TAction(array($this, 'onChangeAlcoolista'));
            $acaoalcoolista->setParameter('formName', $this->form->getName());
            $tipo_alcoolista->setChangeAction($acaoalcoolista);

            $acaoemprego = new TAction(array($this, 'onChangeEmprego'));
            $acaoemprego->setParameter('formName', $this->form->getName());
            $empregado->setChangeAction($acaoemprego);


        ##--container--##
      
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

    public static function onChangeFumante($param)
    {
        switch ($param['tipo_fumante'])
        {
            case 'SIM':
            
            TCombo::clearField($param['formName'], 'data_fim_fumante');
            TCombo::disableField($param['formName'], 'data_fim_fumante');
            
            TCombo::enableField($param['formName'], 'data_inicio_fumante');
            
            break;
        
            case 'NAO':
            
            TCombo::clearField($param['formName'], 'data_fim_fumante');
            TCombo::disableField($param['formName'], 'data_inicio_fumante');

            TCombo::clearField($param['formName'], 'data_fim_fumante');
            TCombo::disableField($param['formName'], 'data_inicio_fumante'); 
             
            break;

            case 'EX-FUMANTE':
            
            TCombo::enableField($param['formName'], 'data_inicio_fumante');    
            TCombo::enableField($param['formName'], 'data_fim_fumante');    
 
            break;

        }
    }

    public static function onChangeAlcoolista($param)
    {
        switch ($param['tipo_alcoolista'])
        {
            case 'SIM':
            
            TCombo::clearField($param['form_cadastro_paciente'], 'data_fim_alcoolista');
            TCombo::disableField($param['form_cadastro_paciente'], 'data_fim_alcoolista');
            
            TCombo::enableField($param['form_cadastro_paciente'], 'data_inicio_alcoolista');
            
            break;
        
            case 'NAO':
            
            TCombo::clearField($param['form_cadastro_paciente'], 'data_fim_alcoolista');
            TCombo::disableField($param['form_cadastro_paciente'], 'data_inicio_alcoolista');

            TCombo::clearField($param['form_cadastro_paciente'], 'data_fim_alcoolista');
            TCombo::disableField($param['form_cadastro_paciente'], 'data_inicio_alcoolista'); 
             
            break;

            case 'EX-ALCOOLISTA':
            
            TCombo::enableField($param['form_cadastro_paciente'], 'data_inicio_alcoolista');    
            TCombo::enableField($param['form_cadastro_paciente'], 'data_fim_alcoolista');    
 
            break;

        }
    }

     public static function onChangeEmprego($param)
    {
        switch ($param['empregado'])
        {
            case 'SIM':
            
            TCombo::enableField($param['form_cadastro_paciente'], 'profissao');
            
            break;
        
            case 'NAO':
            
            TCombo::clearField($param['form_cadastro_paciente'], 'profissao');
            TCombo::disableField($param['form_cadastro_paciente'], 'profissao');

             
            break;

        }
    }

}