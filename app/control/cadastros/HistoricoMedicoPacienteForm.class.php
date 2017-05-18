<?php
class HistoricoMedicoPacienteForm extends TPage
{
    private $form;
    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder( "form_cadastro_estab_med" );
        $this->form->setFormTitle( "Formulário de Cadastro do Histórico de Médicos do Paciente" );
        $this->form->class = "tform";

        $id                         = new THidden( "id" );
        $estabelecimento_medico_id                  = new TCombo( "estabelecimento_medico_id" );
        $paciente_id         = new TCombo( "paciente_id" );
        $datainicio                 = new TDate( "datainicio" );
        $datafim                    = new TDate( "datafim" );

        $datainicio->setMask('dd/mm/yyyy');
        $datafim->setMask('dd/mm/yyyy');
        $datainicio->setDatabaseMask('yyyy-mm-dd');
        $datafim->setDatabaseMask('yyyy-mm-dd');
/*
        TTransaction::open('pg_ceres');
        $conn = TTransaction::get();
        $result = $conn->query('SELECT DISTINCT m.nome, e.nome from vw_pex_atividade_execucao p');

        $itensPrograma = array();
        foreach ($result as $row) {
            $estabelecimento_medico_id[$row['pt_id']] = $row['pt_nome'];
        }

        TTransaction::close();
*/


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
        

        $items = array();
        TTransaction::open('dbsic');
        $repository = new TRepository('PacienteRecord');

        $criteria = new TCriteria;
        $criteria->setProperty('order', 'nome');
        
        $cadastros = $repository->load($criteria);

        foreach ($cadastros as $object) {
            $items[$object->id] = $object->nome;
        }

        $paciente_id->addItems($items);
        TTransaction::close(); 

        $estabelecimento_medico_id->addValidation( "Médico", new TRequiredValidator );
        $paciente_id->addValidation( "Paciente", new TRequiredValidator );
        $datainicio->addValidation( "Data Início", new TRequiredValidator );

        $this->form->addFields( [ new TLabel( "Médico: <font color=red><b>*</b></font> ") ], [ $estabelecimento_medico_id ] );
        $this->form->addFields( [ new TLabel( "Paciente:<font color=red><b>*</b></font>" ) ], [ $paciente_id ]);
        $this->form->addFields( [ new TLabel( "Data Início:" ) ], [ $datainicio ] );
        $this->form->addFields( [ new TLabel( "Data Fim:" ) ], [ $datafim ] );
        $this->form->addFields( [ $id ] );

        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para a listagem", new TAction( [ "HistoricoMedicoPacienteList", "onReload" ] ), "fa:table blue" );

        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( new TXMLBreadCrumb( "menu.xml", "HistoricoMedicoPacienteList" ) );
        $container->add( $this->form );
        parent::add( $container );
    }
    public function onSave()
    {
        try
        {
            $this->form->validate();
            TTransaction::open( "dbsic" );

            $object = $this->form->getData( "HistoricoMedicoPacienteRecord" );
            $object->store();
            TTransaction::close();
            $action = new TAction( [ "HistoricoMedicoPacienteList", "onReload" ] );
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
                $object = new HistoricoMedicoPacienteRecord( $param[ "key" ] );
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