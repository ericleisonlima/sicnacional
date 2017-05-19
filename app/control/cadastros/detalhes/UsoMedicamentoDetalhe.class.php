<?php
class UsoMedicamentoDetalhe extends TStandardList{
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
        
        parent::setDatabase('dbsic');
        parent::setActiveRecord('UsoMedicamentoRecord');
        
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
        $this->form->addFields( [new TLabel('<font color=red><b>* Campos Obrigatórios </b></font>'), []] );
        $this->form->addFields( [ $id, $paciente_id ] );

        $action = new TAction(array($this, 'onSave'));
        $action->setParameter('id', '' . filter_input(INPUT_GET, 'id') . '');
        $action->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');

        $this->form->addAction('Salvar', $action, 'fa:floppy-o');
        $this->form->addAction('Voltar para Pacientes',new TAction(array('PacienteList','onReload')),'fa:table blue');

        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        
        $column_1 = new TDataGridColumn('paciente_nome', 'Paciente', 'left');
        $column_2 = new TDataGridColumn('datainicio', 'Início', 'left');
        $column_3 = new TDataGridColumn('datafim', 'Fim', 'left');
        $column_4 = new TDataGridColumn('medicamento_nome', 'Medicamento', 'left');
        $column_5 = new TDataGridColumn('administracao_nome', 'Tipo administração', 'left');
        $column_6 = new TDataGridColumn('posologia', 'Posologia', 'left');
        $column_7 = new TDataGridColumn('observacao', 'Observações', 'left');

        $this->datagrid->addColumn($column_1);
        $this->datagrid->addColumn($column_2);
        $this->datagrid->addColumn($column_3);
        $this->datagrid->addColumn($column_4);
        $this->datagrid->addColumn($column_5);
        $this->datagrid->addColumn($column_6);
        $this->datagrid->addColumn($column_7);
        
        $edit = new TDataGridAction( [ $this, "onEdit" ] );
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
        $this->datagrid->addAction($del);
        
        $this->datagrid->createModel();
        
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid));
        $container->add($this->pageNavigation);

        parent::add($container);
    }
    function onEdit( $param ){
        try{
            if( isset( $param[ "key" ] ) ){
                TTransaction::open( "dbsic" );
                $object = new UsoMedicamentoRecord( $param[ "key" ] );
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
            TApplication::gotoPage('UsoMedicamentoDetalhe','onReload', $param); 

        }catch (Exception $e){
            $object = $this->form->getData($this->activeRecord);
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onReload( $param = NULL ){
        try{

            TTransaction::open( "dbsic" );

            $repository = new TRepository( "UsoMedicamentoRecord" );
            if ( empty( $param[ "order" ] ) )
            {
                $param[ "order" ] = "id";
                $param[ "direction" ] = "asc";
            }
            $limit = 10;
            
            $criteria = new TCriteria();
            $criteria->add(new TFilter('paciente_id', '=', filter_input(INPUT_GET, 'fk')));
            $criteria->setProperties( $param );
            $criteria->setProperty( "limit", $limit );
            
            $objects = $repository->load( $criteria, FALSE );

            $this->datagrid->clear();
            if ( !empty( $objects ) ){
                foreach ( $objects as $object ){
                    $object->datainicio = TDate::date2br($object->datainicio);
                    $object->datafim = TDate::date2br($object->datafim);
                    $this->datagrid->addItem( $object );
                }
            }
            $criteria->resetProperties();

            $count = $repository->count($criteria);
            $this->pageNavigation->setCount($count); 
            $this->pageNavigation->setProperties($param); 
            $this->pageNavigation->setLimit($limit);

            TTransaction::close();
            $this->loaded = true;
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            new TMessage( "error", $ex->getMessage() );
        }
    }
        
    
}
