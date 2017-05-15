<?php
class NutricaoParenteralForm extends TStandardForm{
    protected $form;

    function __construct(){
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder('form_nutricao_parenteral');
        $this->form->setFormTitle('Nutrição Parenteral');
        
        parent::setDatabase('sic_nacional');
        parent::setActiveRecord('NutricaoParenteralRecord');
        
        $id                                 = new THidden('id');
        $paciente_id                        = new TEntry('paciente_id');
        $inicio                             = new TDate('datainicio');
        $fim                                = new TDate('datafim');
        $tipoparenteral                     = new TEntry('tipoparenteral');
        $tipoparenteraloutros               = new TEntry('tipoparenteraloutros');
        $totalcalorias                      = new TEntry('totalcalorias');
        $percentualdiario                   = new TEntry('percentualdiario');
        $valumenpt                          = new TEntry('valumenpt');
        $tempoinfusao                       = new TEntry('tempoinfusao');
        $frequencia                         = new TEntry('frequencia');
        $acessovenosolp                     = new TEntry('acessovenosolp');
        $acessovenosolpqual                 = new TEntry('acessovenosolpqual');
        $numerodeacessovenoso               = new TEntry('numerodeacessovenoso');
        $apresentouinfeccaoacessovenoso     = new TEntry('apresntouinfeccaoacessovenoso');
        $vezesinfeccaoacessovenosso         = new TEntry('vezesinfeccaoacessovenosso');

        //$controller    = new TMultiSearch('controller');
        //$controller->addItems($this->getPrograms());
        //$controller->setMaxSize(1);
        //$controller->setMinLength(0);
        $id->setEditable(false);

        $this->form->addFields( [new TLabel('ID')], [$id] );
        $this->form->addFields( [new TLabel('Paciente')], [$paciente_id] );
        $this->form->addFields( [new TLabel('Inicio')], [$inicio] );
        $this->form->addFields( [new TLabel('Fim')], [$fim] );
        $this->form->addFields( [new TLabel('Tipo Parenteral')], [$tipoparenteral] );
        $this->form->addFields( [new TLabel('Outros Tipos Parenteral')], [$tipoparenteraloutros] );
        $this->form->addFields( [new TLabel('Total de Calorias')], [$totalcalorias] );
        $this->form->addFields( [new TLabel('Percentual Diário')], [$percentualdiario] );
        $this->form->addFields( [new TLabel('Volume NPT')], [$valumenpt] );
        $this->form->addFields( [new TLabel('Tempo Infusão')], [$tempoinfusao] );
        $this->form->addFields( [new TLabel('Frequência')], [$frequencia] );
        $this->form->addFields( [new TLabel('Acesso Venoso')], [$acessovenosolp] );
        $this->form->addFields( [new TLabel('Acesso Venoso Qual.')], [$acessovenosolpqual] );
        $this->form->addFields( [new TLabel('Acesso Venoso - Quantidade')], [$numerodeacessovenoso] );
        $this->form->addFields( [new TLabel('Apresentou Infecção no Acesso Venoso')], [$apresentouinfeccaoacessovenoso] );
        $this->form->addFields( [new TLabel('Quantidade de Infecção no Acesso Venoso')], [$vezesinfeccaoacessovenosso] );

        $id->setSize('30%');
        //$name->setSize('70%');
        //$controller->setSize('70%');  

        //$name->addValidation(_t('paciente_id'), new TRequiredValidator);
        //$controller->addValidation(('Controller'), new TRequiredValidator);

        $this->form->addAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        //$this->form->addAction(_t('New'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addAction('Voltar',new TAction(array('NutricaoParenteralList','onReload')),'fa:table blue');

        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add(new TXMLBreadCrumb('menu.xml','NutricaoParenteralList'));
        $container->add($this->form);
        
        parent::add($container);
    }
    
    public function onEdit($param){
        try{
            if (isset($param['key'])){
                $key=$param['key'];
                
                TTransaction::open($this->database);
                $class = $this->activeRecord;
                $object = new $class($key);
                $object->controller = array($object->controller => $object->controller);
                $this->form->setData($object);
                TTransaction::close();
                
                return $object;
            }else{
                $this->form->clear();
            }
        }catch (Exception $e){
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    
    public function onSave(){
        try{
            TTransaction::open($this->database);
            
            $cadastro = $this->form->getData();
            
            //$object = new NutricaoParenteralRecord;

            $cadastro = $this->form->getData('NutricaoParenteralRecord');
            /*
            $object->id = $data->id;
            $object->paciente_id = $data->paciente_id;
            $object->datainicio = $data->datainicio;
            $object->datafim = $data->datafim;
            $object->tipoparenteral = $data->tipoparenteral;
            $object->tipoparenteraloutros = $data->tipoparenteraloutros;
            $object->totalcalorias = $data->totalcalorias;
            $object->percentualdiario = $data->percentualdiario;
            $object->valumenpt = $data->valumenpt;
            $object->tempoinfusao = $data->tempoinfusao;
            $object->frequencia = $data->frequencia;
            $object->acessovenosolp = $data->acessovenosolp;
            $object->acessovenosolpqual = $data->acessovenosolpqual;
            $object->numerodeacessovenoso = $data->numerodeacessovenoso;
            $object->apresntouinfeccaoacessovenoso = $data->apresntouinfeccaoacessovenoso;
            $object->vezesinfeccaoacessovenosso = $data->vezesinfeccaoacessovenosso;
            */
            
            $this->form->validate();
            $cadastro->store();
            //$data->id = $object->id;
            $this->form->setData($cadastro);
            TTransaction::close();
            
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
            
            TApplication::gotoPage('NutricaoParenteralList', 'onReload');
            //return $object;

        }catch (Exception $e){
            $object = $this->form->getData($this->activeRecord);
            $this->form->setData($object);
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
