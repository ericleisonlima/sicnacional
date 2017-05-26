<?php

class ObitoPacienteDetalhe extends TStandardList{
    protected $form;

    protected $datagrid; 
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;

    function __construct(){
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder('form_obito');
        $this->form->setFormTitle('Formulário de Dados do Óbito');
        
        parent::setDatabase('dbsic');
        parent::setActiveRecord('PacienteRecord');
        
        $id     = new THidden('id');
        $id->setValue(filter_input(INPUT_GET, 'fk'));

        TTransaction::open('dbsic');
        $tempVisita = new PacienteRecord( filter_input( INPUT_GET, 'fk' ) );
        if( $tempVisita ){
            $paciente_nome = new TLabel( $tempVisita->nome );
            $paciente_nome->setEditable(FALSE);
        }
        TTransaction::close(); 

        $causa_obito      = new TCombo( "causa_obito_id" );        
        $dataobito        = new TDate( "dataobito" );

        $dataobito->setMask( "dd/mm/yyyy" );
        $dataobito->setDatabaseMask('yyyy-mm-dd');

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

        $this->form->addFields( [new TLabel('Paciente'), $paciente_nome] );
        $this->form->addFields( [ new TLabel( "Causa Óbito:") ], [ $causa_obito ]);
        $this->form->addFields( [ new TLabel( "Data Óbito:" ) ], [ $dataobito ] );
        
        $this->form->addFields( [ $id ] );

        $action = new TAction(array($this, 'onSave'));
        $action->setParameter('id', '' . filter_input(INPUT_GET, 'id') . '');
        $action->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');

        $this->form->addAction('Salvar', $action, 'fa:floppy-o');
        $this->form->addAction('Voltar para Pacientes',new TAction(array('PacienteList','onReload')),'fa:table blue');

        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        
        $column_1 = new TDataGridColumn('nome', 'Paciente', 'left');
        $column_2 = new TDataGridColumn('dataobito', 'Data do óbito', 'left');
        $column_3 = new TDataGridColumn('causa_obito_nome', 'Causa do óbito', 'left');

        $this->datagrid->addColumn($column_1);
        $this->datagrid->addColumn($column_2);
        $this->datagrid->addColumn($column_3);
        
        $action_edit = new TDataGridAction( [ $this, "onEdit" ] );
        $action_edit->setButtonClass( "btn btn-default" );
        $action_edit->setLabel( "Editar" );
        $action_edit->setImage( "fa:pencil-square-o blue fa-lg" );
        $action_edit->setField( "id" );
        $action_edit->setParameter('fk', filter_input(INPUT_GET, 'fk'));
        $this->datagrid->addAction( $action_edit );
        
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
                $object = new PacienteRecord( $param[ "key" ] );
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
            $cadastro = $this->form->getData('PacienteRecord');
            $this->form->validate();
            $cadastro->store();
            TTransaction::close();

            $param=array();
            $param['key'] = $cadastro->id;
            $param['id'] = $cadastro->id;
            $param['fk'] = $cadastro->id;
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
            TApplication::gotoPage('ObitoPacienteDetalhe','onEdit', $param); 

        }catch (Exception $e){
            $object = $this->form->getData($this->activeRecord);
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onReload( $param = NULL ){
        try{

            TTransaction::open( "dbsic" );

            $repository = new TRepository( "PacienteRecord" );
            if ( empty( $param[ "order" ] ) )
            {
                $param[ "order" ] = "id";
                $param[ "direction" ] = "asc";
            }
            $limit = 10;
            
            $criteria = new TCriteria();
            $criteria->add(new TFilter('id', '=', filter_input(INPUT_GET, 'fk')));
            $criteria->setProperties( $param );
            $criteria->setProperty( "limit", $limit );
            
            $objects = $repository->load( $criteria, FALSE );

            $this->datagrid->clear();
            if ( !empty( $objects ) ){

                foreach ( $objects as $object ){
                    $object->datadiagnostico = TDate::date2br($object->datadiagnostico);
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
