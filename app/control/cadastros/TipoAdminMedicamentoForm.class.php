<?php



class TipoAdminMedicamentoForm extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        //Criacao do formulario
        $this->form = new BootstrapFormBuilder( "form_tipoadminmedicamento" );
        $this->form->setFormTitle( "Formulário de Administração de Medicamento." );
        $this->form->class = "tform";

        //Criacao dos campos do fomulario
        $id = new THidden( "id" );
        $tipo = new TEntry( "descricao" );
        $tipo->forceUpperCase();
       

        //Definicao de propriedades dos campos
        $tipo = new TEntry( "descricao" );
        $tipo->setProperty('title', 'O campo e obrigatorio');
        

        $tipo->setSize('38%');
        

        //Definicao de campos obrigatorios e requeridos especiais
        $tipo->addValidation( "Administração", new TRequiredValidator );
    

        //Insercao dos campos no formulario
        $this->form->addFields([new TLabel('Administração<font color=red>*</font>')], [$tipo]);
       
        $this->form->addFields( [ $id ] );

        //Criacao dos botoes com sua determinada acoes no fomulario
        $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para a listagem", new TAction( [ "TipoAdminMedicamentoList", "onReload" ] ), "fa:table blue" );

        //Criacao do container que recebe o formulario
        $container = new TVBox();
        $container->style = "width: 90%";
        // $container->add(new TXMLBreadCrumb( "menu.xml", "CidList" ) );
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

            $object = $this->form->getData('TipoAdministracaoMedicamentoRecord');
            $object->store();

            TTransaction::close();

            $action = new TAction( [ "TipoAdminMedicamentoList", "onReload" ] );
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
                $object = new TipoAdministracaoMedicamentoRecord($param['key']);
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
