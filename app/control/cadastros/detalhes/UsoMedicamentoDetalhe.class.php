<?php


class UsoMedicamentoDetalhe extends TWindow{
    protected $form;
    protected $datagrid;
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;

    function __construct(){
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder('form_uso_medicamento');
        $this->form->setFormTitle('Medicação Ministrada');
        
        
        $id                                 = new THidden('id');
        $paciente_id                        = new THidden('paciente_id'); 
        $paciente_id->setValue(filter_input(INPUT_GET, 'fk'));

        TTransaction::open('dbsic');
        $tempVisita = new PacienteRecord( filter_input( INPUT_GET, 'fk' ) );
        if( $tempVisita ){
            $paciente_nome = new TLabel( $tempVisita->nome );
            $paciente_nome->setEditable(FALSE);
        }
        TTransaction::close(); 

        $inicio                             = new TDate('datainicio');
        $fim                                = new TDate('datafim');
        $medicamento_id                     = new TCombo('medicamento_id');
        $tipoadministracaomedicamento_id    = new TCombo('tipoadministracaomedicamento_id');
        $posologia                          = new TEntry('posologia');
        $observacao                         = new TEntry('observacao');

        //$percentualdiario->setMask('99999999999');

        $items = array();
        TTransaction::open('dbsic');
        $repository = new TRepository('MedicamentoRecord');
        $criteria = new TCriteria;
        $criteria->setProperty('order', 'nome');
        $cadastros = $repository->load($criteria);
        foreach ($cadastros as $object) {
            $items[$object->id] = $object->nome;
        }
        $medicamento_id->addItems($items);
        TTransaction::close(); 

        $items = array();
        TTransaction::open('dbsic');
        $repository = new TRepository('TipoAdministracaoMedicamentoRecord');
        $criteria = new TCriteria;
        $criteria->setProperty('order', 'descricao');
        $cadastros = $repository->load($criteria);
        foreach ($cadastros as $object) {
            $items[$object->id] = $object->descricao;
        }
        $tipoadministracaomedicamento_id->addItems($items);
        TTransaction::close(); 

        $inicio->setSize('20%');
        $fim->setSize('20%');

        $inicio->setMask('dd/mm/yyyy');
        $fim->setMask('dd/mm/yyyy');
        $inicio->setDatabaseMask('yyyy-mm-dd');
        $fim->setDatabaseMask('yyyy-mm-dd');

        $inicio->addValidation( "Início", new TRequiredValidator );
        $medicamento_id->addValidation( "Medicamento", new TRequiredValidator );
        $tipoadministracaomedicamento_id->addValidation( "Tipo administração", new TRequiredValidator );

        $this->form->addFields( [new TLabel('Paciente: '), $paciente_nome] );
        $this->form->addFields( [new TLabel('Medicamento <font color=red><b>*</b></font>')], [$medicamento_id] );
        $this->form->addFields( [new TLabel('Tipo administração <font color=red><b>*</b></font>'),$tipoadministracaomedicamento_id]  );
        $this->form->addFields( [new TLabel('Posologia')], [$posologia] );
        $this->form->addFields( [new TLabel('Inicio <font color=red><b>*</b></font>')], [$inicio] );
        $this->form->addFields( [new TLabel('Fim')], [$fim] );
        $this->form->addFields( [new TLabel('Observações')], [$observacao] );
       
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
        $this->form->addAction('Voltar para Pacientes', $voltar ,'fa:table blue'); 

        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add($this->form);
        $container->add($this->pageNavigation);

        parent::add($container);
    }

    public function onSave(){
        try{

            TTransaction::open('dbsic');
            $cadastro = $this->form->getData('UsoMedicamentoRecord');
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

      public function onEdit($param) {

        TTransaction::open('dbsic');
        
        if (isset($param['key'])) {

            $key = $param['key'];
            $object = new UsoMedicamentoRecord($key);

            $object->datainicio = TDate::date2br($object->datainicio);
            $object->datafim = TDate::date2br($object->datafim);
            $object->datatransplante = TDate::date2br($object->datatransplante);
            $this->form->setData($object);
            
        } else {
            $this->form->clear();
        }
        TTransaction::close();

    }

    public function onReload( $param = NULL ){
       
    }

  
    
}
