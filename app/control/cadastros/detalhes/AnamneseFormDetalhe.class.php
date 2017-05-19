<?php


class AnamneseFormDetalhe extends TStandardList
{


    protected $form;
    protected $datagrid;
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;


   public function __construct(){
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder('form_detail_anamnese');
        $this->form->setFormTitle('Detalhamento de Anamnese');
       
        parent::setDatabase('dbsic');
        parent::setActiveRecord('AnamneseRecord');
        
        
        
        $id = new THidden('id');
        $paciente_id = new THidden('paciente_id'); 
        $paciente_id->setValue(filter_input(INPUT_GET, 'fk'));
        $estabelecimento_medico_id = new TCombo('estabelecimento_medico_id'); 
        
       
        TTransaction::open('dbsic');
        $tempVisita = new PacienteRecord( filter_input( INPUT_GET, 'fk' ) );
        
        if( $tempVisita ){
            $paciente_nome = new TLabel( $tempVisita->nome );
            $paciente_nome->setEditable(FALSE);
        }


        TTransaction::close(); 

        $items = array();
        TTransaction::open('dbsic');
        $repository = new TRepository('EstabelecimentoMedicoRecord');
        $criteria = new TCriteria;
        $criteria->setProperty('order', 'id');
        $cadastros = $repository->load($criteria);
        foreach ($cadastros as $object) {
            $items[$object->id] = $object->id;
        }
        $estabelecimento_medico_id->addItems($items);
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
        $tipotransplante = new TEntry('tipotrasnplante');
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

/*
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
*/
        
        $this->form->addFields( [new TLabel('Paciente'),$paciente_nome] );
        $this->form->addFields( [new TLabel('Estabelecimento Medico')], [$estabelecimento_medico_id] );
        $this->form->addFields( [new TLabel('Data do Registro')], [$dataregistro ] );
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
        $this->form->addFields( [$id, $paciente_id]);
       
        $action = new TAction(array($this, 'onSave'));
        $action->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');

        $this->form->addAction('Salvar', $action, 'fa:floppy-o');
        $this->form->addAction('Voltar para Paciente',new TAction(array('PacienteList','onReload')),'fa:table blue');

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
        

        $edit = new TDataGridAction( [ $this, "onEdit" ] );
        $edit->setButtonClass( "btn btn-default" );
        $edit->setLabel( "Editar" );
        $edit->setImage( "fa:pencil-square-o blue fa-lg" );
        $edit->setField( "id" );
        $edit->setParameter('fk', filter_input(INPUT_GET, 'fk'));
        $this->datagrid->addAction( $edit );
        
        $del = new TDataGridAction(array($this, 'onDelete'));
        $del->setButtonClass('btn btn-default');
        $del->setLabel(_t('Delete'));
        $del->setImage('fa:trash-o red fa-lg');
        $del->setField('id');
        $del->setParameter('fk', filter_input(INPUT_GET, 'fk'));
        $this->datagrid->addAction($del);
        
        $this->datagrid->createModel();
        
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

      
 
        $container = new TVBox;
        $container->style = 'width: 90%';
        //$container->add(new TXMLBreadCrumb('menu.xml', 'AnamneseFormDetalhe'));
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

             $object->dataregistro = TDate::date2br($object->dataregistro);
             $object->datacirurgia = TDate::date2br($object->datacirurgia);
             $object->datatransplante = TDate::date2br($object->datatransplante);
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
            $this->form->validate();
            $cadastro->store();
            TTransaction::close();

            $param=array();
            $param['key'] = $cadastro->id;
            $param['id'] = $cadastro->id;
            $param['fk'] = $cadastro->paciente_id;
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
            TApplication::gotoPage('AnamneseFormDetalhe','onReload', $param); 

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
    
    
}
