<?php

class TipoNutricaoList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;

    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();

        parent::setDatabase('dbsic');            // defines the database
        parent::setActiveRecord('TipoNutricaoRecord');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        //parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('nome', 'like', 'nome'); // filterField, operator, formField

        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_TipoNutricaoRecord');
        $this->form->setFormTitle('Tipos de nutrição');

        // create the form fields
        //$id = new TEntry('id');
        $nome = new TEntry('nome');

        //$this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel('Nome')], [$nome] );

        //$id->setSize('30%');
        $nome->setSize('70%');

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('TipoNutricaoRecord_filter_data') );

        // add the search form actions
        $this->form->addAction('Buscar', new TAction(array($this, 'onSearch')), 'fa:search');
        $this->form->addAction('Novo',  new TAction(array('TipoNutricaoForm', 'onEdit')), 'bs:plus-sign green');

        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->datatable = "true";
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        // creates the datagrid columns
        //$column_id = new TDataGridColumn('id', 'Id', 'center', 50);
        $column_nome = new TDataGridColumn('nome', 'Nome', 'center');


        // add the columns to the DataGrid
        //$this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome);


        // creates the datagrid column actions
        //$order_id = new TAction(array($this, 'onReload'));
        //$order_id->setParameter('order', 'id');
        //$column_id->setAction($order_id);

        $order_nome = new TAction(array($this, 'onReload'));
        $order_nome->setParameter('order', 'nome');
        $column_nome->setAction($order_nome);

        // create EDIT action
        $action_edit = new TDataGridAction(array('TipoNutricaoForm', 'onEdit'));
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel('Editar');
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);

        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel('Excluir');
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);

        // create the datagrid model
        $this->datagrid->createModel();

        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());


        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid));
        $container->add($this->pageNavigation);

        parent::add($container);
    }
}
