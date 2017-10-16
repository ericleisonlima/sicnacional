<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

class ExamePacienteDetalhe extends TWindow{
    protected $form;
    protected $datagrid;
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;

    function __construct(){
        parent::__construct();
        parent::SetSize(0.800,0.800);
        
        $this->form = new BootstrapFormBuilder('form_exame_paciente');
        $this->form->setFormTitle('Exames Realizados');
        
        $id             = new THidden('id');
        $paciente_id    = new THidden('paciente_id');
        $dataexame      = new TDate('dataexame');
        $tipoexame_id   = new TCombo('tipoexame_id');
        $valor          = new TEntry('valor');

        $paciente_id->setValue(filter_input(INPUT_GET, 'fk'));

        TTransaction::open('dbsic');
        $tempVisita = new PacienteRecord( filter_input( INPUT_GET, 'fk' ) );
        if( $tempVisita ){
            $paciente_nome = new TLabel( $tempVisita->nome );
            $paciente_nome->setEditable(FALSE);
        }
        TTransaction::close(); 

        $items = array();
        TTransaction::open('dbsic');
        $repository = new TRepository('TipoExameRecord');
        $criteria = new TCriteria;
        $criteria->setProperty('order', 'nome');
        $cadastros = $repository->load($criteria);
        foreach ($cadastros as $object) {
            $items[$object->id] = $object->nome;
        }
        $tipoexame_id->addItems($items);
        TTransaction::close(); 

        $dataexame->setMask('dd/mm/yyyy');
        $dataexame->setDatabaseMask('yyyy-mm-dd');
        $valor->setMask('99999');

        $dataexame->addValidation( "Data do Exame", new TRequiredValidator );
        $tipoexame_id->addValidation( "Exame", new TRequiredValidator );

        $this->form->addFields( [new TLabel('Paciente: '), $paciente_nome] );
        $this->form->addFields( [new TLabel('Exame <font color=red><b>*</b></font>')], [$tipoexame_id] );
        $this->form->addFields( [new TLabel('Valor do Exame')],[$valor]  );
        $this->form->addFields( [new TLabel('Data do Exame <font color=red><b>*</b></font>')], [$dataexame] );
        $this->form->addFields( [ $id, $paciente_id ] );

        $action = new TAction(array($this, 'onSave'));
        $action->setParameter('id', '' . filter_input(INPUT_GET, 'id') . '');
        $action->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');
        $action->setParameter('key', '' . filter_input(INPUT_GET, 'key') . '');

        $voltar = new TAction(array('PacienteDetail','onReload'));        
        $voltar->setParameter('id', '' . filter_input(INPUT_GET, 'id') . '');
        $voltar->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');
        $voltar->setParameter('key', '' . filter_input(INPUT_GET, 'key') . '');


        $this->form->addAction('Salvar', $action, 'fa:floppy-o');
        $this->form->addAction('Voltar para Pacientes', $voltar,'fa:table blue');

        
        /*$edit = new TDataGridAction( [ $this, "onEdit" ] );
        $edit->setButtonClass( "btn btn-default" );
        $edit->setLabel( "Editar" );
        $edit->setImage( "fa:pencil-square-o blue fa-lg" );
        $edit->setField( "id" );
        $edit->setParameter('fk', filter_input(INPUT_GET, 'fk'));
        $this->datagrid->addAction( $edit );

        $del = new TDataGridAction(array($this, 'onDelete'));
        $del->setButtonClass('btn btn-default');
        $del->setLabel(_t('Delete'));
        $del->setImage('fa:trash-o red fa-lg');
        $del->setField('id');
        $del->setParameter('fk', filter_input(INPUT_GET, 'fk'));
        $this->datagrid->addAction($del);*/

        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add($this->form);
        $container->add($this->pageNavigation);

        parent::add($container);
    }
 
    public function onSave(){
        try{

            TTransaction::open('dbsic');
            $cadastro = $this->form->getData('ExamePacienteRecord');
            $this->form->validate();
            $cadastro->store();
            TTransaction::close();

            $param=array();
            $param['key'] = $cadastro->id;
            $param['id'] = $cadastro->id;
            $param['fk'] = $cadastro->paciente_id;
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
            TApplication::gotoPage('PacienteDetail','onReload', $param); 

        }catch (Exception $e){
            $object = $this->form->getData($this->activeRecord);
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }


    public function onReload () {
    }

        
    
}
