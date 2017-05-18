<?php

// Revisado 18.05.17

class EstabelecimentoForm extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        //Criacao do formulario
        $this->form = new BootstrapFormBuilder( "form_estabelecimento" );
        $this->form->setFormTitle( "Formulário de Estabelecimentos" );
        $this->form->class = "tform";

        //Criacao dos campos do fomulario
        $id               = new THidden( "id" );
        $municipio        = new TCombo( "municipio_id" );
        $nome             = new TEntry('nome');
        $endereco         = new TEntry('endereco');
        $bairro           = new TEntry('bairro');
        $cep              = new TEntry('cep');
        $latitude         = new TEntry('latitude');
        $longitude        = new TEntry('longitude');




        TTransaction::open('dbsic');
        $repository = new TRepository('MunicipioRecord');

        $criteria = new TCriteria();

        $objects = $repository->load($criteria);
        $item = array();
        if ($objects) {
            foreach ($objects as $object) {
                $item[$object->id] = $object->nome;
            }
        }
        $municipio->addItems($item);



        //Definicao das mascaras dos campos especiais
        $cep->setMask('99999-999');

        //Definicao de tipo de caixa das letras
        $nome->forceUpperCase();
        $endereco->forceUpperCase();
        $bairro->forceUpperCase();



        //Definicao de propriedades dos campos
        $municipio->setDefaultOption( "..::SELECIONE::.." );
        $nome->setProperty( "title", "O campo é obrigatório" );
        $endereco->setProperty("title",'Informe o seu endereço');
        $bairro->setProperty("title",'Informe o seu bairro');
        $cep->placeholder = "Exemplo 99999-999";




        //Definicao dos tamanhos de alguns campos do formulario
        $municipio->setSize("38%");
        $nome->setSize( "38%" );
        $endereco->setSize( "38%" );
        $bairro->setSize( "38%" );
        $cep->setSize( "38%" );
        $latitude->setSize( "38%" );
        $longitude->setSize( "38%" );


        //Definicao de campos obrigatorios e requeridos especiais

        $nome->addValidation( "Nome", new TRequiredValidator );
        $municipio->addValidation("Municipio",new TRequiredValidator);


        //Insercao dos campos na aba de informacoes pessoais do formulario
        $this->form->addFields([new TLabel("Município:","#FF0000") ],[$municipio]);
        $this->form->addFields( [ new TLabel( "Nome:", "#FF0000" ) ], [ $nome ] );
        $this->form->addFields( [ new TLabel( "Endereço:" ) ], [ $endereco ]);
        $this->form->addFields( [ new TLabel( "Bairro:" ) ], [ $bairro ]);
        $this->form->addFields( [ new TLabel( "Cep:" ) ], [ $cep ]);
        $this->form->addFields( [ new TLabel( "Latitude:" ) ], [ $latitude ]);
        $this->form->addFields( [ new TLabel( "Longitude:" ) ], [ $longitude ]);
        $this->form->addFields( [ $id] );



        //Criacao dos botoes com sua determinada acoes no fomulario
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para a listagem", new TAction( [ "EstabelecimentoList", "onReload" ] ), "fa:table blue" );

        //Criacao do container que recebe o formulario
        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( new TXMLBreadCrumb( "menu.xml", "EstabelecimentoList" ) );
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

            //Resgata os dados inseridos no formulario a partir do modelo
            $object = $this->form->getData( "EstabelecimentoRecord" );

            //Remove as mascaras dos campos especiais
            $object->cep = preg_replace( "/[^0-9]/", "", $object->cep );


            $object->store();

            TTransaction::close();

            $action = new TAction( [ "EstabelecimentoList", "onReload" ] );

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
                TTransaction::open( "dbsic" );

                $object = new EstabelecimentoRecord( $param[ "key" ] );


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
