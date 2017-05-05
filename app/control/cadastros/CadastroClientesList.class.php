<?php

class CadastroClientesList extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;

    public function __construct()
    {
        parent::__construct();

        // Criacao do formulario
        $this->form = new BootstrapFormBuilder( "form_list_cadastro_clientes" );
        $this->form->setFormTitle( "Listagem de Clientes" );
        $this->form->class = "tform";

        // Criacao dos campos do fomulario
        $opcao = new TCombo( "opcao" );
        $dados = new TEntry( "dados" );

        // Definicao de propriedades dos campos
        $opcao->setDefaultOption( '..::SELECIONE::..' );
        $dados->setProperty( 'title', 'Informe os dados de acordo com a opção' );

        // Definicao dos tamanhos do campos
        $opcao->setSize( '38%' );
        $dados->setSize( '38%' );

        // Definicao das opções dos combos
        $opcao->addItems( [ 'nome' => 'Nome', 'cpf' => 'CPF', 'rg' => 'RG' ] );

        $this->form->addFields( [ new TLabel( 'Opção de filtro:' ) ], [ $opcao ] );
        $this->form->addFields( [ new TLabel( 'Dados da busca:' ) ], [ $dados ] );

        // Criacao dos botoes com sua determinada acoes no fomulario
        $this->form->addAction( 'Buscar', new TAction( [ $this, 'onSearch' ] ), 'fa:search' );
        $this->form->addAction( 'Novo', new TAction( [ 'CadastroClientesForm', 'onEdit' ] ), 'bs:plus-sign green' );

        //Criacao do datagrid de listagem de dados
        $this->datagrid = new BootstrapDatagridWrapper( new TDataGrid() );
        $this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight( 320 );

        //Criacao das colunas do datagrid
        $column_id = new TDataGridColumn( 'id', 'ID', 'center', 50 );
        $column_nome = new TDataGridColumn( 'nome', 'Nome', 'left' );
        $column_cpf = new TDataGridColumn( 'cpf', 'CPF', 'left' );
        $column_rg = new TDataGridColumn( 'rg', 'RG', 'left' );
        $column_situacao = new TDataGridColumn( 'situacao', 'Situação', 'center' );

        //Insercao das colunas no datagrid
        $this->datagrid->addColumn( $column_id );
        $this->datagrid->addColumn( $column_nome );
        $this->datagrid->addColumn( $column_cpf );
        $this->datagrid->addColumn( $column_rg );
        $this->datagrid->addColumn( $column_situacao );

        $column_situacao->setTransformer( function($value, $object, $row)
        {
            $class = ( $value=='I' ) ? 'danger' : 'success';

            $label = ( $value=='I' ) ? 'Inativo' : 'Ativo';

            $div = new TElement( 'span' );
            $div->class = "label label-{$class}";
            $div->style = "text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add( $label );

            return $div;
        });

        //Insercao das acoes de ordenacao nas colunas do datagrid
        $order_id = new TAction( [ $this, 'onReload' ] );
        $order_id->setParameter( 'order', 'id' );
        $column_id->setAction( $order_id );

        $order_nome = new TAction( [ $this, 'onReload' ] );
        $order_nome->setParameter( 'order', 'nome' );
        $column_nome->setAction( $order_nome );

        $order_cpf = new TAction( [ $this, 'onReload' ] );
        $order_cpf->setParameter( 'order', 'cpf' );
        $column_cpf->setAction( $order_cpf );

        $order_rg = new TAction( [ $this, 'onReload' ] );
        $order_rg->setParameter( 'order', 'rg' );
        $column_rg->setAction( $order_rg );

        $order_situacao = new TAction( [ $this, 'onReload' ] );
        $order_situacao->setParameter( 'order', 'situacao' );
        $column_situacao->setAction( $order_situacao );

        //Criacao da acao de edicao no datagrid
        $action_edit = new TDataGridAction( [ 'CadastroClientesForm', 'onEdit' ] );
        $action_edit->setButtonClass( 'btn btn-default' );
        $action_edit->setLabel( 'Editar' );
        $action_edit->setImage( 'fa:pencil-square-o blue fa-lg' );
        $action_edit->setField( 'id' );
        $this->datagrid->addAction( $action_edit );

        //Criacao da acao de delecao no datagrid
        $action_del = new TDataGridAction( [ $this, 'onDelete' ] );
        $action_del->setButtonClass( 'btn btn-default' );
        $action_del->setLabel( 'Deletar' );
        $action_del->setImage( 'fa:trash-o red fa-lg' );
        $action_del->setField( 'id' );
        $this->datagrid->addAction( $action_del );

        //Criacao da acao de ativa/desativa no datagrid
        $action_onoff = new TDataGridAction( [ $this, 'onTurnOnOff' ] );
        $action_onoff->setButtonClass( 'btn btn-default' );
        $action_onoff->setLabel( 'Ativa/Desativar' );
        $action_onoff->setImage( 'fa:power-off fa-lg orange' );
        $action_onoff->setField( 'id' );
        $this->datagrid->addAction( $action_onoff );

        //Exibicao do datagrid
        $this->datagrid->createModel();
        // Criacao do container que recebe o formulario
        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( $this->form );

        // Adicionando o container com o form a pagina
        parent::add( $container );
    }

    public function onSearch()
    {

    }

    public function onReload()
    {

    }
}
