<?php



class  GeraRelatorioPacientesAtivosAno  extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;

    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder( "form_relatorio_paciente_ano" );
        $this->form->setFormTitle( "Gerar Relatorio de Paciente" );
        $this->form->class = "tform";

        $ano = new TCombo( 'ano' );
        
        $items = array();
        $items['2017'] = '2017';
        $items['2018'] = '2018';
     
        $ano->addItems($items);
        
        $ano->setDefaultOption( "..::SELECIONE::.." );


        $this->form->addFields([new TLabel("Ano") ],[$ano]);

        $this->form->addAction( "Gerar", new TAction( [ $this, "onGenerate" ] ), "fa:table blue" );
        
        $ano->addValidation('Ano', new TRequiredValidator);

        //Criacao do navedor de paginas do datagrid
        /*$this->pageNavigation = new TPageNavigation();
        $this->pageNavigation->setAction( new TAction( [ $this, "onGenerate" ] ) );*/


        // Criacao do container que recebe o formulario
        $container = new TVBox();
        $container->style = "width: 90%";
        //$container->add( new TXMLBreadCrumb( "menu.xml", __CLASS__ ) );
        $container->add( $this->form );
        //$container->add( $this->pageNavigation );
        // Adicionando o container com o form a pagina
        parent::add( $container );
    }

    
    function onGenerate()

    {

    try
        {            
            $this->form->validate();
            //new RelatorioRevendedoraPDF();         
        }  
        catch( Exception $e )
        {
        
            new TMessage( 'error', $e->getMessage() );
        
            TTransaction::rollback();
       
        }
    }
}

?>