<?php

class TipoNutricaoForm extends TStandardForm
{
    protected $form; // form

    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();

        $this->setDatabase('dbsic');              // defines the database
        $this->setActiveRecord('TipoNutricaoRecord');     // defines the active record

        // creates the form
        $this->form = new BootstrapFormBuilder('form_TipoNutricaoRecord');
        $this->form->setFormTitle('Cadastrar o tipo de nutrição');

        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');

        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel('Nome')], [$nome] );
        $id->setEditable(FALSE);
        $id->setSize('30%');
        $nome->setSize('70%');
        $nome->addValidation('Nome', new TRequiredValidator );

        // create the form actions
        $this->form->addAction('Salvar', new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addAction('Novo',  new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addAction('Voltar',new TAction(array('TipoNutricaoList','onReload')),'fa:table blue');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'TipoNutricaoList'));
        $container->add($this->form);

        parent::add($container);
    }
}
