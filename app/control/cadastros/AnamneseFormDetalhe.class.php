<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

class AnamneseFormDetalhe extends TPage
{


    private $form;
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;


   public function __construct(){
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder('form_detail_anamnese');
        $this->form->setFormTitle('Detalhamento de Anamnese');
        //$this->form->class = "tform";
        
        
        
        $id = new THidden('id');
        $paciente_id = new THidden('paciente_id'); 
        $paciente_id->setValue(filter_input(INPUT_GET, 'id'));
        $estabelecimento_medico_id = new THidden('estabelecimento_medico_id'); 
        $estabelecimento_id->setValue(filter_input(INPUT_GET, 'id'));
       
        TTransaction::open('dbsic');
        $tempVisita = new PacienteRecord( filter_input( INPUT_GET, 'id' ) );
        
        if( $tempVisita ){
            $paciente_nome = new TLabel( $tempVisita->nome );
            $paciente_nome->setEditable(FALSE);
        }
        TTransaction::close(); 

        
        TTransaction::open('dbsic');
        $tempVisita2 = new EstabelecimentoMedicoRecord( filter_input( INPUT_GET, 'id' ) );
        
        if( $tempVisita2 ){
            $paciente_nome2 = new TLabel( $tempVisita2->estabelecimento_id );
            $paciente_nome2->setEditable(FALSE);
        }
        TTransaction::close(); 

      

        $dataregistro = new TDate('dataregistro');
        $datacirurgia = new TDate('datacirurgia');
        $peso = new TEntry('peso');
        $altura = new TEntry('altura');
        $fumante = new TEntry('fumante');
        $comprintdel = new TEntry('comprimentointestinodelgado');
        $larintdel = new TEntry('larguraintestinodelgado');
        $valvulaileocecal = new TEntry('valvulaileocecal');
        $colonemcontinuidade = new TEntry('colonemcontinuidade');
        $colonremanescente = new TEntry('colonremanescente');
        $estomia = new TEntry('estomia');
        $transplantado = new TEntry('transplantado');
        $datatransplante = new TDate('datatransplante');
        $tipotransplante = new TEntry('tipotransplante');
        $desfechotransplante = new TEntry('desfechotransplante');
        $diagnosticonutricional = new TEntry('diagnosticonutricional');

        
        $id->setEditable(FALSE);
        $id->setSize('38%');
        $paciente_id->setSize('40%');
        $estabelecimento_medico_id->setSize('40%');
        $dataregistro->setSize('40%');
        $datacirurgia->setSize('40%');
        $peso->setSize('40%');
        $altura->setSize('40%');
        $fumante->setSize('40%');
        $comprintdel->setSize('40%');
        $larintdel->setSize('40%');
        $valvulaileocecal->setSize('40%');
        $colonemcontinuidade->setSize('40%');
        $colonremanescente->setSize('40%');
        $estomia->setSize('40%');
        $transplantado->setSize('40%');
        $datatransplante->setSize('40%');
        $tipotransplante->setSize('40%');
        $desfechotransplante->setSize('40%');
        $diagnosticonutricional->setSize('40%');


        $datatransplante->setMask('dd/mm/yyyy');
        $datacirurgia->setMask('dd/mm/yyyy');
        $datatransplante->setDatabaseMask('yyyy-mm-dd');
        $datacirurgia->setDatabaseMask('yyyy-mm-dd');
        $dataregistro->setMask('dd/mm/yyyy');
        $dataregistro->setDatabaseMask('yyyy-mm-dd');


        $dataregistro->addValidation( "Data do Registro", new TRequiredValidator );
        $datacirurgia->addValidation( "Data da Cirurgia", new TRequiredValidator );
        $peso->addValidation( "Peso", new TRequiredValidator );
        $larintdel->addValidation( "Largura do Intestino Grosso", new TRequiredValidator );
        $comprintdel->addValidation( "Comprimento do Intestino Grosso", new TRequiredValidator );
        $colonemcontinuidade->addValidation( "Colon em Continuidade", new TRequiredValidator );
        $estomia->addValidation( "Estomia", new TRequiredValidator );
        $transplantado->addValidation( "Transplantado", new TRequiredValidator );
        $diagnosticonutricional->addValidation( "Diagnostico Nutricional", new TRequiredValidator );
        $fumante->addValidation( "Fumante", new TRequiredValidator );

        
        $this->form->addFields( [new TLabel('Paciente')], [$paciente_nome] );
        $this->form->addFields( [new TLabel('Estabelecimento Medico')], [$paciente_nome2 ] );
        $this->form->addFields( [new TLabel('Data dp Registro')], [$dataregistro ] );
        $this->form->addFields( [new TLabel('Data da Cirurgia')], [$datacirurgia] );
        $this->form->addFields( [new TLabel('Peso')], [$peso] );
        $this->form->addFields( [new TLabel('Altura')], [$altura] );
        $this->form->addFields( [new TLabel('Fumante')], [$fumante] );
        $this->form->addFields( [new TLabel('Comprimento do Intestino Delgado')], [$comprintdel] );
        $this->form->addFields( [new TLabel('Largura do Intestino Delgado')], [$larintdel ] );
        $this->form->addFields( [new TLabel('Valvula Ileocecal')], [$valvulaileocecal ] );
        $this->form->addFields( [new TLabel('Colon em Continuidade')], [$colonemcontinuidade] );
        $this->form->addFields( [new TLabel('Colon Remanescente')], [$colonremanescente] );
        $this->form->addFields( [new TLabel('Estomia')], [$estomia] );
        $this->form->addFields( [new TLabel('Transplantado')], [$transplantado] );
        $this->form->addFields( [new TLabel('Data do Transplante')], [$datatransplante] );
        $this->form->addFields( [new TLabel('Tipo Transplante')], [$tipotransplante ] );
        $this->form->addFields( [new TLabel('Desfecho do Transplante')], [$desfechotransplante ] );
        $this->form->addFields( [new TLabel('Diagnostico Nutricional')], [$diagnosticonutricional] );
        $this->form->addFields( [$id, $paciente_id, $estabelecimento_medico_id]);
       
        $action = new TAction(array($this, 'onSave'));
        $action->setParameter('id', '' . filter_input(INPUT_GET, 'id') . '');

        $this->form->addAction('Salvar', $action, 'fa:floppy-o');
        $this->form->addAction('Voltar',new TAction(array('PacienteList','onReload')),'fa:table blue');

        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        
        //$column_id = new TDataGridColumn('id', 'Id', 'center');
        $column_name = new TDataGridColumn('paciente_nome', 'Paciente', 'left');
        $column_peso = new TDataGridColumn('peso', 'Peso ', 'left');
        $column_comprintdel = new TDataGridColumn('comprimentointestinodelgado', ' Comprimento do Intestino Delgado', 'left');
        $column_estomia = new TDataGridColumn('estomia', 'Estomia', 'left');
        $column_transplantado = new TDataGridColumn('transplantado', 'Transplantado', 'left');
        
       

        //$this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_name);
        $this->datagrid->addColumn($column_peso);
        $this->datagrid->addColumn($column_comprintdel);
        $this->datagrid->addColumn($column_estomia);
        $this->datagrid->addColumn($column_transplantado);
        
        $action_edit = new TDataGridAction(array('AnamneseFormDetalhe', 'onEdit'));
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel('Editar');
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);
        

        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);
        
        $this->datagrid->createModel();
        
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

      
 
        $container = new TVBox;
        $container->style = 'width: 90%';
        //$container->add(new TXMLBreadCrumb('menu.xml', 'NutricaoEnteralFormDetalhe'));
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid));
        $container->add($this->pageNavigation);

        parent::add($container);
        
    }


    function onEdit($param) {


        TTransaction::open('dbsic');
        
        if (isset($param['fk'])) {

            $key = $param['fk'];
            $object = new AnamneseRecord($key);
            $this->form->setData($object);
            
        } else {
            $this->form->clear();
        }
        TTransaction::close();

    }
    public function onSave(){
        try{

            TTransaction::open('dbsic');
            $cadastro = $this->form->getData('AnamneseRecord');
            $cadastro->paciente_id =  filter_input(INPUT_GET, 'id');
            $cadastro->estabelecimento_medico_id =  filter_input(INPUT_GET, 'id');
          

            $this->form->validate();
            $cadastro->store();
            TTransaction::close();
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
            TApplication::gotoPage('AnamneseFormDetalhe', 'onReload');

        }catch (Exception $e){
            $object = $this->form->getData($this->activeRecord);
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onReload( $param = NULL ){
        try{

            TTransaction::open( "dbsic" );

            $repository = new TRepository( "AnamneseRecord" );
            if ( empty( $param[ "order" ] ) )
            {
                $param[ "order" ] = "id";
                $param[ "direction" ] = "asc";
            }
            $limit = 10;
            
            $criteria = new TCriteria();
            $criteria->setProperties( $param );
            $criteria->setProperty( "limit", $limit );
            
            $objects = $repository->load( $criteria, FALSE );

            $this->datagrid->clear();

            if ( !empty( $objects ) ){

                foreach ( $objects as $object ){

                    $object->dataregistro = TDate::date2br($object->dataregistro);
                    $object->datacirurgia = TDate::date2br($object->datacirurgia);
                    $object->datatransplante = TDate::date2br($object->datatransplante);
                    $this->datagrid->addItem( $object );
                }
            }
            $criteria->resetProperties();

            $count = $repository->count($criteria);
            $this->pageNavigation->setCount($count); 
            $this->pageNavigation->setProperties($param); 
            $this->pageNavigation->setLimit($limit);

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
            TTransaction::open( "dbsic" );

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

    
    
}
