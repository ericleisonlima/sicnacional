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
}
