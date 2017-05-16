<?php
/*
 * @author Ericleison Lima
 * @date 16/05/2017
 */
class AnamneseDetalhe extends TPage
{
    private $form;
    public function __construct()
    {
        parent::__construct();
        //Criacao do formulario
        $this->form = new BootstrapFormBuilder( "form_cadastro_anamnese" );
        $this->form->setFormTitle( "Cadastro de Anamnese" );
        $this->form->class = "tform";


        //Criacao dos campos do fomulario
        $id               = new THidden( "id" );
        //$estabelecimento_medico_id             = new THidden( "estabelecimento_medico_id" );
        //$paciente_id              = new THidden( "paciente_id" );
        $dataregistro               = new TDate( "dataregistro" );
        $peso           = new TEntry("peso");
        $altura       = new TEntry( "altura" );
        $fumante              = new TCombo( "fumante" );
        $datacirurgia           = new TDate( "datacirurgia" );
        $comindel           = new TEntry( "comprimentointestinodelgado" );
        $larindel           = new TEntry( "larguraintestinodelgado" );
        $valvulaileocecal            = new TEntry( "valvulaileocecal" );
        $colonemcontinuidade    = new TCombo( "colonemcontinuidade" );
        $colonremanescente = new TCombo( "colonremanescente" );
        $estomia            = new TEntry( "estomia" );
        $transplantado    = new TCombo( "transplantado" );
        $datatransplante = new TDate( "datatransplante" );
        $tipotransplante            = new TCombo( "tipotransplante" );
        $desfechotransplante    = new TText( "desfechotransplante" );
        $diagnosticonutricional = new TText( "diagnosticonutricional" );


        TTransaction::open('sicnacional');
        $estabelecimento_medico_id = new AnamneseRecord( filter_input( INPUT_GET, 'fk' ) );
        $estabelecimentonome = new TLabel( $estabelecimento_medico_id->nome );
        $paciente_id = new AnamneseRecord( filter_input( INPUT_GET, 'fk' ) );
        $pacientenome = new TLabel( $paciente_id->nome );
        
        
        TTransaction::close();

        //Definicao das mascaras dos campos especiais
        
        $dataregistro->setMask( "dd/mm/yyyy" );
        $datacirurgia ->setMask( "dd/mm/yyyy" );
        $datatransplante->setMask( "dd/mm/yyyy" );



        //Definicao de tipo de caixa das letras
        $peso->forceUpperCase();
        $altura->forceUpperCase();
        $comindel->forceUpperCase();
        $larindel->forceUpperCase();
        $valvulaileocecal->forceUpperCase();
        $estomia->forceUpperCase();
        $desfechotransplante->forceUpperCase();
        $diagnosticonutricional->forceUpperCase();


        //Definicao de propriedades dos campos
        $fumante->setDefaultOption( "..::SELECIONE::.." );
        $colonemcontinuidade->setDefaultOption( "..::SELECIONE::.." );
        $colonremanescente->setDefaultOption( "..::SELECIONE::.." );
        $transplantado->setDefaultOption( "..::SELECIONE::.." );
        $tipotransplante->setDefaultOption( "..::SELECIONE::.." );
        $comindel->setProperty( "title", "O campo é obrigatório" );
        $larindel->setProperty( "title", "O campos é obrigatório" );
        $peso->setProperty( "title", "O campos é obrigatório" );
        $estomia->setProperty( "title", "O campos é obrigatório" );




        //Definicao dos tamanhos de alguns campos do formulario
       
        $dataregistro->setSize( "38%" );
        $peso->setSize( "38%" );
        $altura->setSize( "38%" );
        $fumante->setSize( "38%" );
        $datacirurgia->setSize( "38%" );
        $comindel->setSize( "38%" );
        $larindel->setSize( "38%" );
        $valvulaileocecal->setSize( "38%" );
        $colonemcontinuidade->setSize( "38%" );
        $colonremanescente->setSize( "38%" );
        $estomia->setSize( "38%" );
        $transplantado->setSize( "38%" );
        $datatransplante->setSize( "38%" );
        $tipotransplante->setSize( "38%" );
        $desfechotransplante->setSize( "38%" );
        $diagnosticonutricional->setSize( "38%" );



        //Definicao das opções dos combos
        $fumante->addItems( [ "S" => "Sim", "N" => "Não" ] );
        $colonemcontinuidade->addItems( [ "S" => "Sim", "N" => "Não" ] );
        $colonremanescente->addItems( [ "S" => "Sim", "N" => "Não" ] );
        $transplantado->addItems( [ "S" => "Sim", "N" => "Não" ] );
        $tipotransplante->addItems( [ "S" => "Sim", "N" => "Não" ] );



        //Definicao de campos obrigatorios e requeridos especiais
        $comindel->addValidation( "Comprimento do intestino Delgado", new TRequiredValidator );
        $larindel->addValidation( "Largura do intestino Delgado", new TRequiredValidator );
        $datatransplante->addValidation( "Data do Transplante", new TEmailValidator );
        $transplantado->addValidation( "Transplantado", new TEmailValidator );
        $colonemcontinuidade->addValidation( "Colon em Continuidade", new TEmailValidator );
        $peso->addValidation( "Peso", new TEmailValidator );



        //Insercao dos campos na aba de informacoes pessoais do formulario
        $this->form->addFields( [ new TLabel( "Data Registro:", "#FF0000" ) ], [ $dataregistro ] );
        $this->form->addFields( [ new TLabel( "Peso:", "#FF0000" ) ], [ $peso ]);
        $this->form->addFields( [ new TLabel( "Altura:" ) ], [ $altura ]);
        $this->form->addFields( [ new TLabel( "Fumante:" ) ], [ $fumante ] );
        $this->form->addFields( [ new TLabel( "Data da Cirurgia:") ], [ $datacirurgia ] );
        $this->form->addFields( [ new TLabel( "Comprimento do Intestino Grosso:" ) ], [ $comindel ] );
        $this->form->addFields( [ new TLabel( "Largura do Intestino Grosso:" ) ], [ $larindel ] );
        $this->form->addFields( [ new TLabel( "Valvula Ileocecal:", ) ], [ $valvulaileocecal ] );
        $this->form->addFields( [ new TLabel( "Calon em Continuidade:",  ) ], [ $colonemcontinuidade ]);
        $this->form->addFields( [ new TLabel( "Colon Remanescente:" ) ], [ $colonremanescente ]);
        $this->form->addFields( [ new TLabel( "Estomia:" ) ], [ $estomia ] );
        $this->form->addFields( [ new TLabel( "Transplantado:", "#FF0000") ], [ $transplantado ] );
        $this->form->addFields( [ new TLabel( "Data do Transplante:" ) ], [ $datatransplante ] );
        $this->form->addFields( [ new TLabel( "Tipo TTransplante:" ) ], [ $tipotransplante ] );
        $this->form->addFields( [ new TLabel( "Desfecho do Transplante:" ) ], [ $desfechotransplante ] );
        $this->form->addFields( [ new TLabel( "Diagnostico do Nutricional:",  ) ], [ $diagnosticonutricional ] );
        $this->form->addFields( [ $id, ] );
        //Criacao dos botoes com sua determinada acoes no fomulario

        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar", new TAction( [ "PacienteList", "onReload" ] ), "fa:table blue" );


        //Criacao do container que recebe o formulario
        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( new TXMLBreadCrumb( "menu.xml", "AnamneseDetalhe" ) );
        $container->add( $this->form );
        parent::add( $container );
    }



    public function onSave()
    {
        try
        {
            //Validacao do formulario
            $this->form->validate();
            TTransaction::open( "sicnacional" );
            //Resgata os dados inseridos no formulario a partir do modelo
            $object = $this->form->getData( "AnamneseRecord" );
            //Remove as mascaras dos campos especiais
            //$object->cpf = preg_replace( "/[^0-9]/", "", $object->cpf );
            $object->dataregistro = TDate::date2us( $object->dataregistro );
            $object->datacirurgia = TDate::date2us( $object->datacirurgia );
            $object->datatransplante = TDate::date2us( $object->datatransplante );
            //Resgata o usuario a data e hora da alteracao do registro
            //$object->usuarioalteracao = TSession::getValue("login");
            //$object->dataalteracao = date( "Y-m-d H:i:s" );
            $object->store();
            TTransaction::close();
            $action = new TAction( [ "PacienteList", "onReload" ] );
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
                TTransaction::open( "sicnacional" );
                $object = new AnamneseRecord( $param[ "key" ] );
                $object->dataregistro = TDate::date2us( $object->dataregistro );
                $object->datacirurgia = TDate::date2us( $object->datacirurgia );
                $object->datatransplante = TDate::date2us( $object->datatransplante );
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



    public function onReload( $param = NULL )
    {
        try
        {
            // Abrindo a conexao com o banco de dados
            TTransaction::open( "sicnacional" );
            // Criando um repositorio para armazenar temporariamente os dados do banco
            $repository = new TRepository( "AnamneseRecord" );
            if ( empty( $param[ "order" ] ) )
            {
                $param[ "order" ] = "id";
                $param[ "direction" ] = "asc";
            }
            $limit = 10;
            // Criando um criterio de busca no banco de dados
            $criteria = new TCriteria();
            $criteria->setProperties( $param );
            $criteria->setProperty( "limit", $limit );
            // Buscando os dados no banco de acordo com os criterios passados
            $objects = $repository->load( $criteria, FALSE );
            // Limpando o datagrid
            $this->datagrid->clear();
            // Se existirem dados no banco, o datagrid sera prenchido por esse foreach
            if ( !empty( $objects ) )
            {
                foreach ( $objects as $object )
                {
                    $this->datagrid->addItem( $object );
                }
            }
            $criteria->resetProperties();
            // Salvando a contagem dos registros que estam no repositorio
            $count = $repository->count($criteria);
            $this->pageNavigation->setCount($count); // Definindo quantos registros tera por pagina do datagrid
            $this->pageNavigation->setProperties($param); // Definindo os paramentros de organizacao dos dados por pagina
            $this->pageNavigation->setLimit($limit); // Definindo o limite de registros por pagina do datagrid
            // Fechando a conexao com o banco de dados
            TTransaction::close();
            $this->loaded = true;
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
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
            TTransaction::open( "sicnacional" );
            $object = new AnamneseRecord( $param[ "key" ] );
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