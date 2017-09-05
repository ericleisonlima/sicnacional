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


        TTransaction::open('dbsic');
        $repository = new TRepository('vw_pacientes_ativos_anoRecord');

        $criteria = new TCriteria;
        $criteria->setProperty('order', 'ano');
        
        $cadastros = $repository->load($criteria);
  
        foreach ($cadastros as $object) {
            $items['TODOS'] = 'TODOS';
            $items[$object->ano] = $object->ano;
        }

        $ano->addItems($items);
        TTransaction::close(); 
        
        $ano->setDefaultOption( "..::SELECIONE::.." );

       /* var_dump($_SESSION);
        exit();*/

        $this->form->addFields([new TLabel("Ano") ],[$ano]);

        $this->form->addAction( "Gerar", new TAction(array('PessoasAtivasGrafico', 'onShow')), "fa:table blue" );
        
        $ano->addValidation('Ano', new TRequiredValidator);

        //Criacao do navedor de paginas do datagrid
       /* $this->pageNavigation = new TPageNavigation();
        $this->pageNavigation->setAction( new TAction( [ $this, "onGenerate" ] ) ); */


        // Criacao do container que recebe o formulario
        $container = new TVBox();
        $container->style = "width: 90%";
        $container->add( $this->form );
       // $container->add( $this->pageNavigation );
        // Adicionando o container com o form a pagina
        parent::add( $container );
    }

    
    /*function onGenerate()

    {

    try
        {            
            
            new RelatorioPessoasAtivasAnoPDF();         
        }  
        catch( Exception $e )
        {
        
            new TMessage( 'error', $e->getMessage() );
        
            TTransaction::rollback();
       
        }
    }*/
}

?>