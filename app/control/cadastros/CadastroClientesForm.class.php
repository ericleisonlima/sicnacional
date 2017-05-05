<?php
/*
 * @author Pedro Henrique
 * @date 22/12/2016
 */

//ini_set ( 'display_errors', 1 );
//ini_set ( 'display_startup_erros', 1 );
//error_reporting ( E_ALL );

class CadastroClientesForm extends TPage
{
    private $form;
    
    public function __construct() 
    {
        parent::__construct();
        
        //Criacao do formulario
        $this->form = new BootstrapFormBuilder( 'form_cadastro_clientes' );
        $this->form->setFormTitle( 'Cadastro de Clientes' );
        $this->form->class = 'tform';
                
        //Criacao dos campos do fomulario
        $id               = new THidden( 'id' );
        $apelido          = new TEntry( 'apelido' );
        $nome             = new TEntry( 'nome' );
        $cpf              = new TEntry( 'cpf' );
        $rg               = new TEntry( 'rg' );
        $genero           = new TCombo('genero');
        $nascimento       = new TDate( 'nascimento' );
        $idade            = new TEntry( 'idade' );
        $imagem           = new TFile( 'imagem' );
        $rua              = new TEntry( 'rua' );
        $numero           = new TEntry( 'numero' );
        $bairro           = new TEntry( 'bairro' );
        $cep              = new TEntry( 'cep' );
        $cidade           = new TEntry( 'cidade' );
        $uf               = new TCombo( 'uf' );
        $telefone01       = new TEntry( 'telefone01' );
        $telefone02       = new TEntry( 'telefone02' );
        $email            = new TEntry( 'email' );
        $situacao         = new THidden( 'situacao' );
        $dataalteracao    = new THidden( 'dataalteracao' );
        $usuarioalteracao = new THidden( 'usuarioalteracao' );
        
        //Componentes e variaveis adicionais do formulario
        $label01 = new TLabel( 'Informações Pessoais', '#7D78B6', 12, 'bi' );
        $label02 = new TLabel( 'Informações Residênciais', '#7D78B6', 12, 'bi' );
        $label03 = new TLabel( 'Informações Contatais', '#7D78B6', 12, 'bi' );
        
        $label01->style = 'text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        $label02->style = 'text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        $label03->style = 'text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
                
        //Definicao das mascaras dos campos especiais
        $cpf->setMask( '000.000.000-00' );
        $cep->setMask( '00000-000' );
        $telefone01->setMask( '(00)00000-0000' );
        $telefone02->setMask( '(00)00000-0000' );
        $nascimento->setMask( 'dd/mm/yyyy' );
        
        //Definicao de tipo de caixa das letras
        $nome->forceUpperCase();
        $apelido->forceUpperCase();
        $rua->forceUpperCase();
        $bairro->forceUpperCase();
        $cidade->forceUpperCase();
        
        //Definicao de propriedades dos campos
        $nome->setProperty( 'title', 'O campo é obrigatório' );
        $cpf->setProperty( 'title', 'O campos é obrigatório' );
        
        $email->setProperty( 'value', 'example@email.com' );
        $cep->setProperty( 'value', '00000-000' );
        $numero->setProperty( 'value', '0' );
        
        $idade->setEditable( FALSE );
        
        $nascimento->setExitAction( new TAction( [ $this, 'onChange' ] ) );
        
        //Definicao dos tamanhos de alguns campos do formulario
        $nome->setSize( '100%' );
        $apelido->setSize( '50%' );
        $cpf->setSize( '38%' );
        $rg->setSize( '38%' );
        $nascimento->setSize( '100%' );
        $idade->setSize( '25%' );
        $genero->setSize( '38%' );
        $imagem->setSize( '42%' );
        $rua->setSize( '100%' );
        $numero->setSize( '50%' );
        $bairro->setSize( '100%' );
        $cep->setSize( '50%' );
        $cidade->setSize( '100%' );
        $uf->setSize( '50%' );
        
        //Definicao das opções dos combos
        $genero->addItems( [ 'M' => 'Masculino', 'F' => 'Feminino' ] );
        $uf->addItems( [ 'AC' => 'AC', 'AL' => 'AL', 'AP' => 'AP', 'AM' => 'AM', 'BA' => 'BA', 'CE' => 'CE', 
            'DF' => 'DF', 'ES' => 'ES', 'GO' => 'GO', 'MA' => 'MA', 'MT' => 'MT', 'MS' => 'MS', 'MG' => 'MS', 
            'PA' => 'PA', 'PB' => 'PB', 'PR' => 'PR', 'PE' => 'PE', 'PI' => 'PI', 'RJ' => 'RJ', 'RN' => 'RN', 
            'RS' => 'RS', 'RO' => 'RO', 'RR' => 'RR', 'SC' => 'SC', 'SP' => 'SP', 'SE' => 'SE', 'TO' => 'TO' ] );
        
        //Definicao de campos obrigatorios e requeridos especiais
        $nome->addValidation( 'Nome', new TRequiredValidator );
        $cpf->addValidation( 'CPF', new TCPFValidator );
        $email->addValidation( 'E-mail', new TEmailValidator );
        $numero->addValidation( 'Nº', new TNumericValidator );
        $cep->addValidation( 'CEP', new TCEPValidator );
        
        //Criacao da aba de informacoes pessoais
        $this->form->appendPage( 'Pessoais' );        
        $this->form->addContent( [ $label01 ] );
        
        //Insercao dos campos na aba de informacoes pessoais do formulario
        $this->form->addFields( [ new TLabel( 'Imagem (foto):') ], [ $imagem ] );
        $this->form->addFields( [ new TLabel( 'Nome:', '#FF0000' ) ], [ $nome ], [ new TLabel( 'Apelido:' ) ], [ $apelido ] );
        $this->form->addFields( [ new TLabel( 'CPF:', '#FF0000' ) ], [ $cpf ]);
        $this->form->addFields( [ new TLabel( 'RG:' ) ], [ $rg ]);
        $this->form->addFields( [ new TLabel( 'Nascimento:' ) ], [ $nascimento ], [ new TLabel( 'Idade:' ) ], [ $idade ] );
        $this->form->addFields( [ new TLabel( 'Genêro:') ], [ $genero ] );
        
        //Criacao da aba de informacoes residenciais
        $this->form->appendPage( 'Residênciais' );        
        $this->form->addContent( [ $label02 ] );
        
        //Inserindo os campos na aba de informacoes pessoais do formulario
        $this->form->addFields( [ new TLabel( 'Rua:' ) ], [ $rua ], [ new TLabel( 'Nº:', '#FF0000' ) ], [ $numero ] );
        $this->form->addFields( [ new TLabel( 'Bairro:' ) ], [ $bairro ], [ new TLabel( 'CEP:', '#FF0000' ) ], [ $cep ] );
        $this->form->addFields( [ new TLabel( 'Cidade:' ) ], [ $cidade ], [ new TLabel( 'UF:' ) ], [ $uf ] );
        
        //Criacao da aba de informacoes residenciais
        $this->form->appendPage( 'Contatais' );        
        $this->form->addContent( [ $label03 ] );
        
        //Inserindo os campos na aba de informacoes pessoais do formulario
        $this->form->addFields( [ new TLabel( 'Telefone 1:' ) ], [ $telefone01 ] );
        $this->form->addFields( [ new TLabel( 'Telefone 2:' ) ], [ $telefone02 ] );
        $this->form->addFields( [ new TLabel( 'E-mail:', '#FF0000' ) ], [ $email ] );
        $this->form->addFields( [ new TLabel( 'WhatsApp:' ) ], [ '<div style="color:#FF0000">Em Breve!</div>' ] );
        
        //Inserindo os campos ocultos no formulario
        $this->form->addFields( [ $id, $situacao, $usuarioalteracao, $dataalteracao ] );
        
        //Criacao dos botoes com sua determinada acoes no fomulario
        $this->form->addAction( 'Salvar', new TAction( [ $this, 'onSave' ] ), 'fa:floppy-o' );
        $this->form->addAction( 'Voltar para a listagem', new TAction( [ 'CadastroClientesList', 'onReload' ] ), 'fa:table blue' );
        
        //Criacao do container que recebe o formulario
        $container = new TVBox();
        $container->style = 'width: 90%';
        $container->add( new TXMLBreadCrumb( 'menu.xml', 'CadastroClientesList' ) );
        $container->add( $this->form );
        
        parent::add( $container );
    }
    
    public function onSave()
    {
        try
        {
            //Validacao do formulario
            $this->form->validate();
            
            TTransaction::open( 'db_muscle' );
            
            //Resgata os dados inseridos no formulario a partir do modelo
            $object = $this->form->getData( 'ClientesModel' );
            
            //Remove as mascaras dos campos especiais
            $object->cpf = preg_replace( "/[^0-9]/", "", $object->cpf );
            $object->cep = preg_replace( "/[^0-9]/", "", $object->cep );
            $object->telefone01 = preg_replace( "/[^0-9]/", "", $object->telefone01 );
            $object->telefone02 = preg_replace( "/[^0-9]/", "", $object->telefone02 );
            $object->nascimento = TDate::date2us( $object->nascimento );
            
            //Resgata o usuario a data e hora da alteracao do registro 
            $object->usuarioalteracao = $_SESSION[ 'template' ][ 'login' ];
            $object->dataalteracao = date( 'Y-m-d H:i:s' );
            
            //Insere a situacao padrao como inativo
            if( empty( $object->situacao ) )
            {
                $object->situacao = 'I';
            }
            
            $object->store();

            TTransaction::close();
            
            $action = new TAction( [ 'CadastroClientesList', 'onReload' ] );
            
            new TMessage( 'info', 'Registro salvo com sucesso!', $action );
            
            //TApplication::gotoPage('CadastroClientesList', 'onReload');
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            
            new TMessage( 'error', 'Ocorreu um erro ao tentar salvar o registro!<br><br>' . $ex->getMessage() );
        }
    }
    
    public function onEdit( $param )
    {
        try
        {
            if( isset( $param[ 'key' ] ) )
            {
                TTransaction::open( 'db_muscle' );
                
                $object = new ClientesModel( $param[ 'key' ] );
                
                $object->nascimento = TDate::date2br( $object->nascimento );
                
                $this->form->setData( $object );
                
                TTransaction::close();
            }
        } 
        catch ( Exception $ex ) 
        {
            TTransaction::rollback();
            
            new TMessage( 'error', 'Ocorreu um erro ao tentar carregar o registro para edição!<br><br>' . $ex->getMessage() );
        }
    }
    
    public static function onChange( $param = NULL ) 
    {        
        $object = new stdClass();
        
        $object->idade = TUsefulFunctions::ageCalculation( $param[ 'nascimento' ] );
        
        TForm::sendData( 'form_cadastro_clientes', $object );
    }
}