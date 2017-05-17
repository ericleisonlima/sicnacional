<?php

class CadastroCidForm extends TPage
{
    private $form;
    
    public function __construct()
    {
        pare nt::__construct();
        
        //Criacao do formulario
        $this->form = new BootstrapFormBuilder( "form_cadastro_cid" );
        $this->form->setFormTitle( "Cadastro CID" );
        $this->form->class = "tform";
        
        //Criacao dos campos do fomulario
        $id = new THidden( "id" );
        $codigoCID = new TEntry( "codigocid" );
        $nome = new TEntry( "nome" );
        
        //Definicao das mascaras dos campos especiais
        $codigoCID->setMask( "S99.9" );
        
        //definicao de tipo de caixa das letras
        $codigoCID->forceUpperCase();
        $nome->forceUpperCase();
        
        //Definicao de propriedades dos campos
        $codigoCID->setProperty('title', 'O campo e obrigatorio');
        $nome->setProperty('title', 'O campo e obrigatorio');
        
        $codigoCID->setSize('38%');
        $nome->setSize('38%');
        
        //Definicao de campos obrigatorios e requeridos especiais
        $codigoCID->addValidation( "Classificacao", new TRequiredValidator );
        $nome->addValidation( "Doenca", new TRequiredValidator );
        
        //Insercao dos campos no formulario
        $this->form->addFields( [ $id ] );
        $this->form->addFields([new TLabel('Classificacao* :')], [$codigoCID]);
        $this->form->addFields([new TLabel('Doenca* :')], [$nome]);
        
        //Criacao dos botoes com sua determinada acoes no fomulario
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para a listagem", new TAction( [ "CadastroCidList", "onReload" ] ), "fa:table blue" );
        
        //Criacao do container que recebe o formulario
        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( new TXMLBreadCrumb( "menu.xml", "CadastroCidList" ) );
        $container->add( $this->form );
        parent::add( $container );
    }
    public function onSave()
    {
        try
        {
            //Validacao do formulario
            $this->form->validate();
            
            TTransaction::open( "dbsic" );
            
            $object = $this->form->getData('CidRecord');
            $object->store();
            
            TTransaction::close();
            
            $action = new TAction( [ "CadastroCidList", "onReload" ] );
            new TMessage( "info", "Registro salvo com sucesso!", $action );
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            new TMessage( "error", "Ocorreu um erro ao tentar salvar o registro!<br><br>" . $ex->getMessage() );
        }
    }
    public function onEdit( $param )
    {
        try
        {
            if( isset( $param[ "key" ] ) )
            {
                TTransaction::open( "dbsic" );
                $object = new CidRecord($param['key']);
                $this->form->setData($object);       

                TTransaction::close();
            }
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            new TMessage( "error", "Ocorreu um erro ao tentar carregar o registro para edição!<br><br>" . $ex->getMessage() );
        }
    }
}
