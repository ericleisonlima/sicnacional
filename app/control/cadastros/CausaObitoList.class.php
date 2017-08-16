<?php

// Revisado 18.05.17

class CausaObitoList extends TPage
{
  private $form;
  private $datagrid;
  private $pageNavigation;
  private $loaded;

  public function __construct()
  {
    parent::__construct();

    $this->form = new BootstrapFormBuilder("form_list_causa_obito");
    $this->form->setFormTitle ( "Listagem de Causas de Óbitos");
    $this->form->class = "tform";
    $id = new THidden ("id");

    $opcao = new TCombo( "opcao" );
    $dados = new TEntry( "dados" );

    $opcao->setDefaultOption( "..::SELECIONE::.." );
    $opcao->setValue( "descricao" );
    $dados->setProperty( "title", "Informe os dados de acordo com a opção" );
    // $dados->forceUpperCase();

    $opcao->setSize( "38%" );
    $dados->setSize( "38%" );

    $opcao->addItems( [ "descricao" => "Descrição"] );
    $this->form->addFields( [ new TLabel( "Opção de filtro:" ) ], [ $opcao ] );
    $this->form->addFields( [ new TLabel( "Dados da busca:" ) ], [ $dados ] );

    $this->form->addAction ("Buscar", new TAction([$this, "onSearch"]), "fa:search" );
    $this->form->addAction ( "Novo", new TAction( ['CausaObitoForm', "onEdit"]), "bs:plus-sign green");

    $this->datagrid = new BootstrapDatagridWrapper (new TDataGrid());
    $this->datagrid->datatable = "true";
    $this->datagrid->style = "width: 100%";
    $this->datagrid->setHeight (320);
    //$column_id = new TDataGridColumn("id", "ID", "center", 50);
    $column_descricao = new TDataGridColumn ("descricao", "Descrição", "left");

    //$this->datagrid->addColumn ($column_id);
    $this->datagrid->addColumn ($column_descricao);

    $order_descricao = new TAction ([$this, "onReload"]);
    $order_descricao->setParameter ("order", "descricao");
    $column_descricao->setAction ($order_descricao);

    $action_edit = new TDataGridAction (["CausaObitoForm", "onEdit"]);
    $action_edit->setButtonClass ( "btn btn-default" );
    $action_edit->setLabel ( "Editar" );
    $action_edit->setImage ( "fa:pencil-square-o blue fa-lg" );
    $action_edit->setField ( "id" );
    $this->datagrid->addAction ( $action_edit );

    $action_del = new TDataGridAction ( [ $this, "onDelete"] );
    $action_del->setButtonClass ( "btn btn-default" );
    $action_del->setLabel ( "Deletar" );
    $action_del->setImage ("fa:trash-o red fa-lg");
    $action_del->setField ( "id" );
    $this->datagrid->addAction ( $action_del );

    $this->datagrid->createModel();

    $this->pageNavigation = new TPageNavigation();
    $this->pageNavigation->setAction(new TAction ([ $this, "onReload" ]));
    $this->pageNavigation->setWidth ($this->datagrid->getWidth());

    $container = new TVBox();
    $container->style = "width: 90%";
    // $container->add(new TXMLBreadCrumb("menu.xml", __CLASS__));
    $container->add($this->form);
    $container->add(TPanelGroup::pack(NULL, $this->datagrid ) );
    $container->add($this->pageNavigation);

    parent::add ($container);
}
public function onReload ($param = NULL ) {

  try {
    TTransaction::open ("dbsic");
    $repository = new TRepository ("CausaObitoRecord");

    if (empty ($param["order"]))
    {
      $param["order"] = "id";
      $param["direction"] = "asc";
    }
    $limit = 10;

    $criteria = new TCriteria();
    $criteria->setProperties( $param );
    $criteria->setProperty ("limit", $limit);

    $objects = $repository->load( $criteria, FALSE);
    $this->datagrid->clear();

    if (!empty ($objects) )
    {
      foreach ($objects as $object ) {
        $this->datagrid->addItem ($object);
      }
    }
    $criteria->resetProperties();
    $count = $repository->count($criteria);
    $this->pageNavigation->setCount($count);
    $this->pageNavigation->setProperties($param);
    $this->pageNavigation->setLimit($limit);
    TTransaction::close();
    $this->loaded = true;

  } catch (Exception $e) {
    TTransaction::rollback();
    new TMessage ("error", $e->getMessage() );
  }


}

public function onSearch()
{
  $data = $this->form->getData();
        try
        {
            if( !empty( $data->opcao ) && !empty( $data->dados ) )
            {
                TTransaction::open( "dbsic" );
                $repository = new TRepository( "CausaObitoRecord" );
                if ( empty( $param[ "order" ] ) )
                {
                    $param[ "order" ] = "id";
                    $param[ "direction" ] = "asc";
                }
                $limit = 10;
                $criteria = new TCriteria();
                $criteria->setProperties( $param );
                $criteria->setProperty( "limit", $limit );
                if( $data->opcao == "descricao" )
                {
                    $criteria->add( new TFilter( $data->opcao, "LIKE", "%" . $data->dados . "%" ) );
                  }
                else
                {
                    // new TMessage( "error", "O valor informado não é valido para um " . strtoupper( $data->opcao ) . "." );
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
                // new TMessage( "error", "Selecione uma opção e informe os dados da busca corretamente!" );
            }
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            $this->form->setData( $data );
            new TMessage( "error", $ex->getMessage() );
        }
    }

public function onDelete( $param = NULL )
    {
        if( isset( $param[ "key" ] ) )
        {
            //Criacao das acoes a serem executadas na mensagem de exclusao
            $action1 = new TAction( [ $this, "Delete" ] );
            $action2 = new TAction( [ $this, "onReload" ] );

            //Definicao sos parametros de cada acao
            $action1->setParameter( "key", $param[ "key" ] );
            new TQuestion( "Deseja realmente apagar o registro?", $action1, $action2 );
        }
    }

    function Delete( $param = NULL )
    {
        try
        {
            TTransaction::open( "dbsic" );
            $object = new CausaObitoRecord( $param[ "key" ] );
            $object->delete();
            TTransaction::close();
            $this->onReload();
            new TMessage("info", "Registro apagado com sucesso!");
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            new TMessage("error", $ex->getMessage());
        }
    }

    public function show()
    {
        $this->onReload();
        parent::show();
    }
}
