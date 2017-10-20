<?php

class NutricaoParenteralDetalhe extends TWindow{
    protected $form;

    protected $datagrid; 
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;

    function __construct(){
        parent::__construct();
        parent::SetSize(0.800,0.800);
        
        $this->form = new BootstrapFormBuilder('form_nutricao_parenteral');
        $this->form->setFormTitle('Nutrição Parenteral');
                
        $id                                 = new THidden('id');
        $paciente_id                        = new THidden('paciente_id'); 
        $paciente_id->setValue(filter_input(INPUT_GET, 'fk'));

        TTransaction::open('dbsic');
        $tempVisita = new PacienteRecord( filter_input( INPUT_GET, 'fk' ) );
        if( $tempVisita ){
            $paciente_nome = new TLabel( $tempVisita->nome );
            $paciente_nome->setEditable(FALSE);
        }
        TTransaction::close(); 

        $inicio                             = new TDate('datainicio');
        $fim                                = new TDate('datafim');
        //$tipoparenteral                     = new TCombo('tipoparenteral');

        $tipoparenteral = new TCombo('tipoparenteral');
        $items = array();
        $items['CICLICA'] = 'Ciclica';
        $items['CONTINUA'] = 'Contínua';
        $items['OUTRAS'] = 'Outras';

        $tipoparenteral->addItems($items);
        $tipoparenteral->setValue('CICLICA');
        $acaoRadio = new TAction(array($this, 'onChangeRadio'));
        $acaoRadio->setParameter('form_nutricao_parenteral', $this->form->getName());
        $tipoparenteral->setChangeAction($acaoRadio);

        $tipoparenteraloutros               = new TEntry('tipoparenteraloutros');

        $tipoparenteraloutros->setEditable(FALSE);
        $totalcalorias                      = new TEntry('totalcalorias');

        $percentualdiario      = new TSpinner('percentualdiario');
        $percentualdiario->setRange(0,100,5);
        $percentualdiario->setValue(25);

        $volumenpt                          = new TEntry('volumenpt');

        $tempoinfusao                       = new TEntry('tempoinfusao');
        $frequencia                         = new TEntry('frequencia');
        $acessovenosolpqual                 = new TEntry('acessovenosolpqual');
        //$numerodeacessovenoso               = new TEntry('numerodeacessovenoso');

        $numerodeacessovenoso      = new TSpinner('numerodeacessovenoso');
        $numerodeacessovenoso->setRange(1,100,1);
        $numerodeacessovenoso->setValue(1);
        $apresentouinfeccaoacessovenoso     = new TRadioGroup('apresentouinfeccaoacessovenoso');
        $vezesinfeccaoacessovenoso          = new TEntry('vezesinfeccaoacessovenoso');

        $totalcalorias->setMask('99999999999');
        $volumenpt->setMask('99999');
        $tempoinfusao->setMask('99999');

        $tipoparenteraloutros->setProperty( "title", "Informe os tipo de nutrição parenteral aplicada" );
        $frequencia->setProperty( "title", "Informe a frequência da nutrição parenteral por dia" );

        $acessovenosolp = new TRadioGroup('acessovenosolp');
        $acessovenosolp->addItems(array('SIM'=>'SIM', 'NAO'=>'NÃO'));
        $acessovenosolp->setLayout('horizontal');
        $acaoRadio = new TAction(array($this, 'onChangeRadio2'));
        $acaoRadio->setParameter('form_nutricao_parenteral', $this->form->getName());
        $acessovenosolp->setChangeAction($acaoRadio);
        $acessovenosolp->setValue('SIM');

        $apresentouinfeccaoacessovenoso->addItems(array('SIM'=>'SIM', 'NAO'=>'NÃO'));

        $apresentouinfeccaoacessovenoso->setLayout('horizontal');
        $apresentouinfeccaoacessovenoso->setValue('NAO');

        $inicio->setSize('20%');
        $fim->setSize('20%');

        $inicio->setMask('dd/mm/yyyy');
        $fim->setMask('dd/mm/yyyy');
        $inicio->setDatabaseMask('yyyy-mm-dd');
        $fim->setDatabaseMask('yyyy-mm-dd');

        $inicio->addValidation( "Início", new TRequiredValidator );
        $tipoparenteral->addValidation( "Tipo Parenteral", new TRequiredValidator );
        $volumenpt->addValidation( "Tipo da NTP", new TRequiredValidator );
        $acessovenosolp->addValidation( "Acesso Venoso", new TRequiredValidator );
        $apresentouinfeccaoacessovenoso->addValidation( "Apresentou Infecção no Acesso Venoso", new TRequiredValidator );
        
        $this->form->addFields( [new TLabel('Paciente'), $paciente_nome] );
        $this->form->addFields( [new TLabel('Inicio<font color=red><b>*</b></font>')], [$inicio] );
        $this->form->addFields( [new TLabel('Fim')], [$fim] );
        $this->form->addFields( [new TLabel('Tipo da NTP<font color=red><b>*</b></font>')], [$tipoparenteral] );
        $this->form->addFields( [new TLabel('Outros Tipos NTP')], [$tipoparenteraloutros] );
        $this->form->addFields( [new TLabel('Total de Calorias Aplicadas')], [$totalcalorias] );
        $this->form->addFields( [new TLabel('Percentual Diário Necessário')], [$percentualdiario, '%'] );
        $this->form->addFields( [new TLabel('Volume em ML<font color=red><b>*</b></font>')], [$volumenpt ] );
        $this->form->addFields( [new TLabel('Tempo da Infusão em Minutos')], [$tempoinfusao] );
        $this->form->addFields( [new TLabel('Frequência da NPT / Dia')], [$frequencia] );
        $this->form->addFields( [new TLabel('Acesso Venoso<font color=red><b>*</b></font>')], [$acessovenosolp] );
        $this->form->addFields( [new TLabel('Qualidade do Acesso Venoso')], [$acessovenosolpqual] );
        $this->form->addFields( [new TLabel('Quantidade de Acessos Venosos de longa permanência')], [$numerodeacessovenoso] );
        $this->form->addFields( [new TLabel('Apresentou Infecção no Acesso Venoso<font color=red><b>*</b></font>')], [$apresentouinfeccaoacessovenoso] );
        $this->form->addFields( [new TLabel('Quantidade de Infecções no Acesso Venoso')], [$vezesinfeccaoacessovenoso] );
        $this->form->addFields( [ $id, $paciente_id ] );

        $action = new TAction(array($this, 'onSave'));
        $action->setParameter('id', '' . filter_input(INPUT_GET, 'id') . '');
        $action->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');
        $action->setParameter('key', '' . filter_input(INPUT_GET, 'key') . '');

        $voltar = new TAction(array('PacienteDetail','onReload'));        
        $voltar->setParameter('id', '' . filter_input(INPUT_GET, 'id') . '');
        $voltar->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');
        $voltar->setParameter('key', '' . filter_input(INPUT_GET, 'key') . '');

        $this->form->addAction('Salvar', $action, 'fa:floppy-o');
        $this->form->addAction('Voltar para Pacientes',$voltar ,'fa:table blue');
        
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));


        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add($this->form);
        $container->add($this->pageNavigation);

        parent::add($container);
    }
        public static function onChangeRadio2($param)
   {
       switch ($param['acessovenosolp'])
       {
           case 'SIM':
           TEntry::clearField($param['form_nutricao_parenteral'], 'acessovenosolpqual');
           TEntry::clearField($param['form_nutricao_parenteral'], 'numerodeacessovenoso');
           TEntry::clearField($param['form_nutricao_parenteral'], 'apresentouinfeccaoacessovenoso');
           TEntry::clearField($param['form_nutricao_parenteral'], 'vezesinfeccaoacessovenoso');
           TEntry::enableField($param['form_nutricao_parenteral'], 'acessovenosolpqual');
           TEntry::enableField($param['form_nutricao_parenteral'], 'numerodeacessovenoso');
           TEntry::enableField($param['form_nutricao_parenteral'], 'apresentouinfeccaoacessovenoso');
           TEntry::enableField($param['form_nutricao_parenteral'], 'vezesinfeccaoacessovenoso');
           break;
       
           case 'NAO':
           TEntry::clearField($param['form_nutricao_parenteral'], 'acessovenosolpqual');
           TEntry::clearField($param['form_nutricao_parenteral'], 'numerodeacessovenoso');
           TEntry::clearField($param['form_nutricao_parenteral'], 'apresentouinfeccaoacessovenoso');     
           TEntry::clearField($param['form_nutricao_parenteral'], 'vezesinfeccaoacessovenoso');     
           TEntry::disableField($param['form_nutricao_parenteral'], 'acessovenosolpqual');
           TEntry::disableField($param['form_nutricao_parenteral'], 'numerodeacessovenoso');
           TEntry::disableField($param['form_nutricao_parenteral'], 'apresentouinfeccaoacessovenoso');
           TEntry::disableField($param['form_nutricao_parenteral'], 'vezesinfeccaoacessovenoso');
           break;
       }
   }

    public static function onChangeRadio($param){

        if($param['tipoparenteral'] == 'OUTRAS'){
            TEntry::clearField($param['form_nutricao_parenteral'], 'tipoparenteraloutros');
            TEntry::enableField($param['form_nutricao_parenteral'], 'tipoparenteraloutros');
        }else{
            TEntry::clearField($param['form_nutricao_parenteral'], 'tipoparenteraloutros');     
            TEntry::disableField($param['form_nutricao_parenteral'], 'tipoparenteraloutros');
            
            }
    }


    public function onSave($param){
        try{

            TTransaction::open('dbsic');
            $cadastro = $this->form->getData('NutricaoParenteralRecord');
            $this->form->validate();
            $cadastro->store();
            TTransaction::close();

            $param=array();
            $param['key'] = $cadastro->id;
            $param['id'] = $cadastro->id;
            $param['fk'] = $cadastro->paciente_id;
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
            TApplication::gotoPage('PacienteDetail','onReload', $param); 

        }catch (Exception $e){
            $cadastro = $this->form->getData($this->activeRecord);
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

      public function onEdit($param) {

        TTransaction::open('dbsic');
        
        if (isset($param['key'])) {

            $key = $param['key'];
            $object = new NutricaoParenteralRecord($key);

            $object->datainicio = TDate::date2br($object->datainicio);
            $object->datafim = TDate::date2br($object->datafim);
            $this->form->setData($object);
            
        } else {
            $this->form->clear();
        }
        TTransaction::close();

    }

    public function onReload( $param = NULL ){
       
    }

    
}
