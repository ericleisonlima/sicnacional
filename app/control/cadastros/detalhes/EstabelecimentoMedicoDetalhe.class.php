<?php

// Revisado 18.05.17

class EstabelecimentoMedicoDetalhe extends TStandardList{
    protected $form;
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;
    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder( "form_estabelecimento_medeico" );
        $this->form->setFormTitle( "Formulário de Cadastro de Médicos nos Estabelecimentos" );
        $this->form->class = "tform";

        parent::setDatabase('dbsic');
        parent::setActiveRecord('EstabelecimentoMedicoRecord');

        $id                         = new THidden( "id" );
        $estabelecimento_id         = new THidden( "estabelecimento_id" );

        $medico_id                  = new TCombo( "medico_id" );
        $responsavel                = new TRadioGroup( "responsavel" );
        $datainicio                 = new TDate( "datainicio" );
        $datafim                    = new TDate( "datafim" );

        $estabelecimento_id->setValue(filter_input(INPUT_GET, 'fk'));

        $datainicio->setMask('dd/mm/yyyy');
        $datafim->setMask('dd/mm/yyyy');
        $datainicio->setDatabaseMask('yyyy-mm-dd');
        $datafim->setDatabaseMask('yyyy-mm-dd');

        TTransaction::open('dbsic');
        $tempNome = new EstabelecimentoRecord( filter_input( INPUT_GET, 'fk' ) );
        if( $tempNome ){
            $tempNome = new TLabel( $tempNome->nome );
            $tempNome->setEditable(FALSE);
        }
        TTransaction::close(); 


        $responsavel->addItems(array('S'=>'SIM', 'N'=>'NAO'));
        $responsavel->setLayout('horizontal');
        $responsavel->setValue('N');

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

        $medico_id->addValidation( "Médico", new TRequiredValidator );
        $responsavel->addValidation( "Responsável", new TRequiredValidator );
        $datainicio->addValidation( "Data Início", new TRequiredValidator );

        $this->form->addFields( [new TLabel('Estabelecimento'), $tempNome ] );
        $this->form->addFields( [ new TLabel( "Médico: <font color=red><b>*</b></font> ") ], [ $medico_id ] );
        
        $this->form->addFields( [ new TLabel( "Responsável: <font color=red><b>*</b></font>" ) ], [ $responsavel ] );
        $this->form->addFields( [ new TLabel( "Data Início: <font color=red><b>*</b></font>" ) ], [ $datainicio ] );
        $this->form->addFields( [ new TLabel( "Data Fim:" ) ], [ $datafim ] );
        $this->form->addFields( [ $id, $estabelecimento_id ] );


        $action = new TAction(array($this, 'onSave'));
        $action->setParameter('id', '' . filter_input(INPUT_GET, 'id') . '');
        $action->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');
        $this->form->addAction('Salvar', $action, 'fa:floppy-o');

        $this->form->addAction( "Voltar para a listagem", new TAction( [ "EstabelecimentoList", "onReload" ] ), "fa:table blue" );
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        
        $column_name = new TDataGridColumn('medico_nome', 'Médico', 'left',80);
        $column_inicio = new TDataGridColumn('datainicio', 'Início', 'center',50);
        $column_fim = new TDataGridColumn('datafim', 'Fim', 'center',50);
        $column_responsavel = new TDataGridColumn('responsavel', 'Responsável', 'center',50);

        $this->datagrid->addColumn($column_name);
        $this->datagrid->addColumn($column_inicio);
        $this->datagrid->addColumn($column_fim);
        $this->datagrid->addColumn($column_responsavel);
        

        $action_edit = new TDataGridAction( [ $this, "onEdit" ] );
        $action_edit->setButtonClass( "btn btn-default" );
        $action_edit->setLabel( "Editar" );
        $action_edit->setImage( "fa:pencil-square-o blue fa-lg" );
        $action_edit->setField( "id" );
        $action_edit->setParameter('fk', filter_input(INPUT_GET, 'fk'));
        $this->datagrid->addAction( $action_edit );

        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('id');
        $action_del->setParameter('fk', filter_input(INPUT_GET, 'fk'));
        $this->datagrid->addAction($action_del);
        
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

    public function onSave(){
        try{
            $this->form->validate();
            TTransaction::open( "dbsic" );

            $object = $this->form->getData( "EstabelecimentoMedicoRecord" );
            $object->store();
            TTransaction::close();
            $param = array();
            $param['fk'] = $object->estabelecimento_id;
            $param['did'] = filter_input(INPUT_GET, 'did');

            new TMessage("info", "Registro salvo com sucesso!");
            TApplication::gotoPage('EstabelecimentoMedicoDetalhe', 'onReload', $param); // reload
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            new TMessage( "error", "Ocorreu um erro ao tentar salvar o registro!<br><br>" . $ex->getMessage() );
        }
    }

    public function onEdit( $param ){
        try{
            if( isset( $param[ "key" ] ) ){
                TTransaction::open( "dbsic" );
                $object = new EstabelecimentoMedicoRecord( $param[ "key" ] );
                $object->nascimento = TDate::date2br( $object->nascimento );
                $this->form->setData( $object );
                TTransaction::close();
            }
        }catch ( Exception $ex ){
            TTransaction::rollback();
            new TMessage( "error", "Ocorreu um erro ao tentar carregar o registro para edição!<br><br>" . $ex->getMessage() );
        }
    }

    public function onReload($param = NULL){
         TTransaction::open('dbsic');

        $repository = new TRepository('EstabelecimentoMedicoRecord');

        $criteria = new TCriteria;
        $criteria->setProperty('order', 'id');
        //$criteria->add(new TFilter('paciente_id', '=', filter_input(INPUT_GET, 'fk')));
        $criteria->add(new TFilter('estabelecimento_id', '=', filter_input(INPUT_GET, 'fk')));
        $cadastros = $repository->load($criteria);

        $this->datagrid->clear();

        if ($cadastros) {
            foreach ($cadastros as $cadastro) {

                $cadastro->datainicio = TDate::date2br($cadastro->datainicio);
                $cadastro->datafim = TDate::date2br($cadastro->datafim);
                $this->datagrid->addItem($cadastro);
            }
        }
        TTransaction::close();
        $this->loaded = true;

    }

    function onDelete($param) {
        $key = $param['key'];
        $action1 = new TAction(array($this, 'Delete'));
        $action1->setParameter('key', $key);
        $action1->setParameter('fk', filter_input(INPUT_GET, 'fk'));

        new TQuestion('Deseja realmente excluir o registro ?', $action1);
    }


    function Delete($param) {
        $key = $param['key'];

        try {
            TTransaction::open('dbsic');
            $cadastro = new EstabelecimentoMedicoRecord($key);

            $cadastro->delete();
            new TMessage("info", "Registro deletado com sucesso!");

            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
        $this->onReload();
    }


}