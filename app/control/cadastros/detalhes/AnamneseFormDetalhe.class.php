<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

class AnamneseFormDetalhe extends TStandardList{

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
            $items[$object->id] = $object->estabelecimento_nome;
        }
        $estabelecimento_medico_id->addItems($items);
        TTransaction::close(); 

        $dataregistro = new TDate('dataregistro');
        $datacirurgia = new TDate('datacirurgia');
        $peso = new TEntry('peso');
        $altura = new TEntry('altura');

        $fumante = new TRadioGroup('fumante');
        $fumante->addItems(array('S'=>'SIM', 'N'=>'NÃO'));
        $fumante->setLayout('horizontal');

        $comprintdel = new TEntry('comprimentointestinodelgado');
        $larintdel = new TEntry('larguraintestinodelgado');
        
        $colonemcontinuidade = new TRadioGroup('colonemcontinuidade');
        $colonemcontinuidade->addItems(array('SIM'=>'SIM', 'NÃO'=>'NÃO'));
        $colonemcontinuidade->setLayout('horizontal');
        $colonemcontinuidade->setValue(1);
        $acaoRadio = new TAction(array($this, 'onChangeRadio'));
        $acaoRadio->setParameter('form_detail_anamnese', $this->form->getName());
        $colonemcontinuidade->setChangeAction($acaoRadio);

        $colonremanescente = new TEntry('colonremanescente');

        $estomia = new TRadioGroup('estomia');
        $estomia->addItems(array('GASTROSTOMIA'=>'GASTROSTOMIA', 'JEJUNO OU ILEOSTOMIA'=>'JEJUNO OU ILEOSTOMIA', 'COLOSTOMIA'=>'COLOSTOMIA'));
        $estomia->setLayout('horizontal');
        $estomia->setValue(1);

        $transplantado = new TRadioGroup('transplantado');
        $transplantado->addItems(array('SIM'=>'SIM', 'NÃO'=>'NÃO'));
        $transplantado->setLayout('horizontal');
        $transplantado->setValue(1);
        $acaoRadio2 = new TAction(array($this, 'onChangeRadio2'));
        $acaoRadio2->setParameter('form_detail_anamnese', $this->form->getName());
        $transplantado->setChangeAction($acaoRadio2);

        $datatransplante = new TDate('datatransplante');
        $tipotransplante = new TEntry('tipotrasnplante');
        $desfechotransplante = new TEntry('desfechotransplante');
        $diagnosticonutricional = new TEntry('diagnosticonutricional');
        $valvulaileocecal = new TRadioGroup('valvulaileocecal');

        $valvulaileocecal->addItems(array('SIM'=>'SIM', 'NÃO'=>'NÃO', 'DESCONHECE'=>'DESCONHECE'));
        $valvulaileocecal->setLayout('horizontal');

        $altura->setMask('9.99');
        $peso->setMask('999');

        $datatransplante->setMask('dd/mm/yyyy');
        $datacirurgia->setMask('dd/mm/yyyy');
        $datatransplante->setDatabaseMask('yyyy-mm-dd');
        $datacirurgia->setDatabaseMask('yyyy-mm-dd');
        $dataregistro->setMask('dd/mm/yyyy');
        $dataregistro->setDatabaseMask('yyyy-mm-dd');

        $dataregistro->addValidation( "Data do Registro", new TRequiredValidator );        
        $peso->addValidation( "Peso", new TRequiredValidator );
        $larintdel->addValidation( "Largura do Intestino Grosso", new TRequiredValidator );
        $comprintdel->addValidation( "Comprimento do Intestino Grosso", new TRequiredValidator );
        $colonemcontinuidade->addValidation( "Colon em Continuidade", new TRequiredValidator );
        $estomia->addValidation( "Estomia", new TRequiredValidator );
        $transplantado->addValidation( "Transplantado", new TRequiredValidator );

        $this->form->addFields( [new TLabel('Paciente'),$paciente_nome] );
        $this->form->addFields( [new TLabel('Estabelecimento Medico')], [$estabelecimento_medico_id] );
        $this->form->addFields( [new TLabel('Data do Registro <font color=red><b>*</b></font>')], [$dataregistro ] );
        $this->form->addFields( [new TLabel('Data da Cirurgia')], [$datacirurgia] );
        $this->form->addFields( [new TLabel('Peso <font color=red><b>*</b></font>')], [$peso] );
        $this->form->addFields( [new TLabel('Altura')], [$altura] );
        $this->form->addFields( [new TLabel('Fumante')], [$fumante] );
        $this->form->addFields( [new TLabel('Comprimento do Intestino Delgado <font color=red><b>*</b></font>')], [$comprintdel] );
        $this->form->addFields( [new TLabel('Largura do Intestino Delgado <font color=red><b>*</b></font>')], [$larintdel ] );
        $this->form->addFields( [new TLabel('Valvula Ileocecal')], [$valvulaileocecal ] );
        $this->form->addFields( [new TLabel('Colon em Continuidade <font color=red><b>*</b></font>')], [$colonemcontinuidade] );
        $this->form->addFields( [new TLabel('Colon Remanescente')], [$colonremanescente] );
        $this->form->addFields( [new TLabel('Estomia <font color=red><b>*</b></font>')], [$estomia] );
        $this->form->addFields( [new TLabel('Transplantado<font color=red><b>*</b></font>')], [$transplantado] );
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
        
        $column_name = new TDataGridColumn('paciente_nome', 'Paciente', 'left');
        $column_peso = new TDataGridColumn('peso', 'Peso ', 'left');
        $column_comprintdel = new TDataGridColumn('comprimentointestinodelgado', ' Comprimento do Intestino Delgado', 'left');
        $column_estomia = new TDataGridColumn('estomia', 'Estomia', 'left');
        $column_transplantado = new TDataGridColumn('transplantado', 'Transplantado', 'left');
        
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
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid));
        $container->add($this->pageNavigation);

        parent::add($container);
        
    }

    function onEdit($param) {

        TTransaction::open('dbsic');
        
        if (isset($param['key'])) {

            $key = $param['key'];
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

    public static function onChangeRadio($param){

     switch ($param['colonemcontinuidade']){
         case 'SIM':
         TEntry::clearField($param['form_detail_anamnese'], 'colonremanescente');
         TEntry::enableField($param['form_detail_anamnese'], 'colonremanescente');
         break;

         case 'NÃO':
         TEntry::clearField($param['form_detail_anamnese'], 'colonremanescente');     
         TEntry::disableField($param['form_detail_anamnese'], 'colonremanescente');
         break;
     }
 }

 public static function onChangeRadio2($param){
     switch ($param['transplantado']){
         case 'SIM':
         TEntry::clearField($param['form_detail_anamnese'], 'datatransplante');
         TEntry::clearField($param['form_detail_anamnese'], 'tipotrasnplante');
         TEntry::clearField($param['form_detail_anamnese'], 'desfechotransplante');
         TEntry::enableField($param['form_detail_anamnese'], 'datatransplante');
         TEntry::enableField($param['form_detail_anamnese'], 'tipotrasnplante');
         TEntry::enableField($param['form_detail_anamnese'], 'desfechotransplante');
         break;

         case 'NÃO':
         TEntry::clearField($param['form_detail_anamnese'], 'datatransplante');
         TEntry::clearField($param['form_detail_anamnese'], 'tipotrasnplante');
         TEntry::clearField($param['form_detail_anamnese'], 'desfechotransplante');     
         TEntry::disableField($param['form_detail_anamnese'], 'datatransplante');
         TEntry::disableField($param['form_detail_anamnese'], 'tipotrasnplante');
         TEntry::disableField($param['form_detail_anamnese'], 'desfechotransplante');
         break;
     }
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
        $criteria->add(new TFilter('paciente_id', '=', filter_input(INPUT_GET, 'fk')));
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
