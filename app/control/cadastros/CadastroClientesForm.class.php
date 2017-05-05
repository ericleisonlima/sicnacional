<?php

class CadastroClientesForm extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        // Criacao do formulario
        $this->form = new BootstrapFormBuilder( 'form_cadastro_clientes' );
        $this->form->setFormTitle( 'Cadastro de Clientes' );
        $this->form->class = 'tform';

        $this->form->addAction( 'Voltar para a listagem',
        new TAction( [ 'CadastroClientesList', 'onReload' ] ), 'fa:table blue' );

        // Criacao do container que recebe o formulario
        $container = new TVBox();
        $container->style = 'width: 90%';
        $container->add( new TXMLBreadCrumb( 'menu.xml', 'CadastroClientesList' ) );
        $container->add( $this->form );

        parent::add( $container );
    }

    public function onEdit( $param )
    {

    }
}
