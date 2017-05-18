<?php
/*
 * @author Pedro Henrique
 * @date 06/05/2017
 */

class CadastroClientesForm extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        //Criacao do formulario
        $this->form = new BootstrapFormBuilder( "form_cadastro_clientes" );
        $this->form->setFormTitle( "Cadastro de Clientes" );
        $this->form->class = "tform";

        //Criacao dos campos do fomulario
        $id               = new THidden( "id" );
        $nome             = new TEntry( "nome" );
        $cpf              = new TEntry( "cpf" );
        $rg               = new TEntry( "rg" );
        $genero           = new TCombo("genero");
        $nascimento       = new TDate( "nascimento" );
        $rua              = new TEntry( "rua" );
        $numero           = new TEntry( "numero" );
        $bairro           = new TEntry( "bairro" );
        $cidade           = new TEntry( "cidade" );
        $email            = new TEntry( "email" );
        $dataalteracao    = new THidden( "dataalteracao" );
        $usuarioalteracao = new THidden( "usuarioalteracao" );

        //Definicao das mascaras dos campos especiais
        $cpf->setMask( "000.000.000-00" );
        $nascimento->setMask( "dd/mm/yyyy" );

        //Definicao de tipo de caixa das letras
        $nome->forceUpperCase();
        $rua->forceUpperCase();
        $bairro->forceUpperCase();
        $cidade->forceUpperCase();

        //Definicao de propriedades dos campos
        $genero->setDefaultOption( "..::SELECIONE::.." );
        $nome->setProperty( "title", "O campo é obrigatório" );
        $cpf->setProperty( "title", "O campos é obrigatório" );

        $email->setProperty( "value", "example@email.com" );

        //Definicao dos tamanhos de alguns campos do formulario
        $nome->setSize( "38%" );
        $cpf->setSize( "38%" );
        $rg->setSize( "38%" );
        $nascimento->setSize( "38%" );
        $genero->setSize( "38%" );
        $rua->setSize( "38%" );
        $bairro->setSize( "38%" );
        $cidade->setSize( "38%" );

        //Definicao das opções dos combos
        $genero->addItems( [ "M" => "Masculino", "F" => "Feminino" ] );

        //Definicao de campos obrigatorios e requeridos especiais
        $nome->addValidation( "Nome", new TRequiredValidator );
        $cpf->addValidation( "CPF", new TRequiredValidator );
        $email->addValidation( "E-mail", new TEmailValidator );


        //Insercao dos campos na aba de informacoes pessoais do formulario
        $this->form->addFields( [ new TLabel( "Nome:", "#FF0000" ) ], [ $nome ] );
        $this->form->addFields( [ new TLabel( "CPF:", "#FF0000" ) ], [ $cpf ]);
        $this->form->addFields( [ new TLabel( "RG:" ) ], [ $rg ]);
        $this->form->addFields( [ new TLabel( "Nascimento:" ) ], [ $nascimento ] );
        $this->form->addFields( [ new TLabel( "Genêro:") ], [ $genero ] );
        $this->form->addFields( [ new TLabel( "Rua:" ) ], [ $rua ] );
        $this->form->addFields( [ new TLabel( "Bairro:" ) ], [ $bairro ] );
        $this->form->addFields( [ new TLabel( "Cidade:" ) ], [ $cidade ] );
        $this->form->addFields( [ new TLabel( "E-mail:", "#FF0000" ) ], [ $email ] );
        $this->form->addFields( [ new TLabel( "WhatsApp:" ) ], [ '<div style="color:#FF0000">Em Breve!</div>' ] );

        $this->form->addFields( [ $id, $usuarioalteracao, $dataalteracao ] );

        //Criacao dos botoes com sua determinada acoes no fomulario
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para a listagem", new TAction( [ "CadastroClientesList", "onReload" ] ), "fa:table blue" );

        //Criacao do container que recebe o formulario
        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( new TXMLBreadCrumb( "menu.xml", "CadastroClientesList" ) );
        $container->add( $this->form );

        parent::add( $container );
    }

    public function onSave()
    {
        try
        {
            //Validacao do formulario
            $this->form->validate();

            TTransaction::open( "db_compras" );

            //Resgata os dados inseridos no formulario a partir do modelo
            $object = $this->form->getData( "ClientesRecord" );

            //Remove as mascaras dos campos especiais
            $object->cpf = preg_replace( "/[^0-9]/", "", $object->cpf );
            $object->nascimento = TDate::date2us( $object->nascimento );

            //Resgata o usuario a data e hora da alteracao do registro
            $object->usuarioalteracao = TSession::getValue("login");
            $object->dataalteracao = date( "Y-m-d H:i:s" );

            $object->store();

            TTransaction::close();

            $action = new TAction( [ "CadastroClientesList", "onReload" ] );

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
                TTransaction::open( "db_compras" );

                $object = new ClientesRecord( $param[ "key" ] );

                $object->nascimento = TDate::date2br( $object->nascimento );

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
