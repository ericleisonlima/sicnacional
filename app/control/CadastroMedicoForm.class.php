



<?php
/*
 * @author Pedro Henrique
 * @date 06/05/2017
 */
class CadastroMedicoForm extends TPage
{
    private $form;
    public function __construct()
    {
        parent::__construct();
        //Criacao do formulario
        $this->form = new BootstrapFormBuilder( "form_cadastro_clientes" );
        $this->form->setFormTitle( "Cadastro de Médico" );
        $this->form->class = "tform";
        //Criacao dos campos do fomulario
        $id               = new THidden( "id" );
        $nome             = new TEntry( "nome" );
        $crm              = new TEntry( "crm" );
        $celular          = new TEntry( "celular" );
        $telefone         = new TEntry("telefone");
        $email            = new TEntry( "email" );
        $municipio        = new TCombo( "municipio" );
        //$dataalteracao    = new THidden( "dataalteracao" );
        //$usuarioalteracao = new THidden( "usuarioalteracao" );
        //Definicao das mascaras dos campos especiais
        $crm->setMask( "999999999900" );
       
        //Definicao de tipo de caixa das letras//
        /*
        $nome->forceUpperCase();
        $crm->forceUpperCase();
        $celular->forceUpperCase();
        $telefone->forceUpperCase();
        $email->forceUpperCase();
        */
        //Definicao de propriedades dos campos
        $nome->setProperty( "title", "O campo é obrigatório" );
        $crm->setProperty( "title", "O campo é obrigatório" );
        $celular->setProperty( "title", "O campos é obrigatório" );
        $telefone->setProperty( "title", "O campo é obrigatório" );
        $email->setProperty( "value", "example@email.com" );
        //Definicao dos tamanhos de alguns campos do formulario
        $nome->setSize( "38%" );
        $crm->setSize( "38%" );
        $celular->setSize( "38%" );
        $telefone->setSize( "38%" );
        $email->setSize( "38%" );
         //Definicao das opções dos combos
        
        //Definicao de campos obrigatorios e requeridos especiais
        $nome->addValidation( "Nome", new TRequiredValidator );
        $crm->addValidation( "Crm", new TRequiredValidator );
        $email->addValidation( "E-mail", new TEmailValidator );
        //Insercao dos campos na aba de informacoes pessoais do formulario
        $this->form->addFields( [ new TLabel( "Nome:") ], [ $nome ] );
        $this->form->addFields( [ new TLabel( "CRM:") ], [ $crm ]);
        $this->form->addFields( [ new TLabel( "Celular:" ) ], [ $celular ]);
        
      
     
       
        $this->form->addFields( [ new TLabel( "Telefone:" ) ], [ $telefone ] );
        $this->form->addFields( [ new TLabel( "E-mail:") ], [ $email ] );
        $this->form->addFields( [ new TLabel( "Municipio:") ], [ $municipio ] );
        //$this->form->addFields( [ $id, $usuarioalteracao, $dataalteracao ] );
        //Criacao dos botoes com sua determinada acoes no fomulario
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para a listagem", new TAction( [ "CadastroMedicoList", "onReload" ] ), "fa:table blue" );
        //Criacao do container que recebe o formulario
        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( new TXMLBreadCrumb( "menu.xml", "CadastroMedicoList" ) );
        $container->add( $this->form );
        parent::add( $container );
    }
    public function onSave()
    {
        try
        {
            //Validacao do formulario
            $this->form->validate();
            TTransaction::open( "db_sic" );
            //Resgata os dados inseridos no formulario a partir do modelo
            $object = $this->form->getData( "MedicoRecord" );
            //Remove as mascaras dos campos especiais
           
            //Resgata o usuario a data e hora da alteracao do registro
           // $object->usuarioalteracao = TSession::getValue("login");
            $object->store();
            TTransaction::close();
            $action = new TAction( [ "CadastroMedicoList", "onReload" ] );
            new TMessage( "info", "Registro salvo com sucesso!", $action );
            // TApplication::gotoPage("CadastroClientesList", "onReload");
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
                TTransaction::open( "db_sic" );
                $object = new MedicoRecord( $param[ "key" ] );
               
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
}