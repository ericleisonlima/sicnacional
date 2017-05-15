<?php
class NutricaoParenteralList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;
    
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('sic_nacional');
        parent::setActiveRecord('NutricaoParenteralRecord');
        parent::setDefaultOrder('id', 'asc');

        parent::addFilterField('id', '=', 'id');
        //parent::addFilterField('paciente_id', 'like', 'paciente_id');
        
        $this->form = new BootstrapFormBuilder('form_search_nutricaoparenteral');
        $this->form->setFormTitle('Nutrição Parenteral');
        
        $dados        = new TEntry('dados ');
        $opcao       = new TCombo('opcao');

        $dados->setProperty( "title", "Informe os dados de acordo com a opção" );

        $opcao->setDefaultOption( "..::SELECIONE::.." );

        $dados->setSize(140);
        $opcao->setSize(140);
        
        $opcao->addItems( [ 
            "nome" => "Nome",
             "cpf" => "CPF",
              "rg" => "RG" ] );

        $this->form->addFields( [new TLabel('Opção de filtro:')], [$dados] );
        $this->form->addFields( [new TLabel('Dados da busca:')], [$opcao] );
        $dados->setSize('40%');
        
        $this->form->setData( TSession::getValue('nutricaoparenteral_filter_data') );
        
        $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $this->form->addAction(_t('New'),  new TAction(array('NutricaoParenteralForm', 'onEdit')), 'bs:plus-sign green');

        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        
        $column_id = new TDataGridColumn('id', 'Id', 'center', 50);
        $column_name = new TDataGridColumn('paciente_id', 'Paciente', 'left');

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_name);

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        
        $order_name = new TAction(array($this, 'onReload'));
        $order_name->setParameter('order', 'paciente_id');
        $column_name->setAction($order_name);
        
        $action_edit = new TDataGridAction(array('NutricaoParenteralForm', 'onEdit'));
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);
        
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);
        
        $this->datagrid->createModel();
        
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid));
        $container->add($this->pageNavigation);
        
        parent::add($container);
    }

    public function onSearch()
    {
        $data = $this->form->getData();
        try
        {
            if( !empty( $data->opcao ) && !empty( $data->dados ) )
            {
                TTransaction::open( "sicnacional" );
                $repository = new TRepository( "NutricaoParenteralRecord" ); // SERA A BUSCA EM PACIENTE!!!
                if ( empty( $param[ "order" ] ) )
                {
                    $param[ "order" ] = "id";
                    $param[ "direction" ] = "asc";
                }
                $limit = 10;
                $criteria = new TCriteria();
                $criteria->setProperties( $param );
                $criteria->setProperty( "limit", $limit );
                if( $data->opcao == "nome" )
                {
                    $criteria->add( new TFilter( $data->opcao, "LIKE", "%" . $data->dados . "%" ) );
                }
                else if ( ( $data->opcao == "cpf" || $data->opcao == "rg" ) && ( is_numeric( $data->dados ) ) )
                {
                    $criteria->add( new TFilter( $data->opcao, "LIKE", $data->dados . "%" ) );
                }
                else
                {
                    new TMessage( "error", "O valor informado não é valido para um " . strtoupper( $data->opcao ) . "." );
                }
                $objects = $repository->load( $criteria, FALSE );
                $this->datagrid->clear();
                if ( $objects )
                {
                    foreach ( $objects as $object )
                    {
                        $this->datagrid->addItem( $object );
                    }
                }
                $criteria->resetProperties();
                $count = $repository->count( $criteria );
                $this->pageNavigation->setCount( $count ); // count of records
                $this->pageNavigation->setProperties( $param ); // order, page
                $this->pageNavigation->setLimit( $limit ); //Limita a quantidade de registros
                TTransaction::close();
                $this->form->setData( $data );
                $this->loaded = true;
            }
            else
            {
                $this->onReload();
                $this->form->setData( $data );
                new TMessage( "error", "Selecione uma opção e informe os dados da busca corretamente!" );
            }
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            $this->form->setData( $data );
            new TMessage( "error", $ex->getMessage() );
        }
    }
}
