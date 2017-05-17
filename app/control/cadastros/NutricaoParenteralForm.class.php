<?php
class NutricaoParenteralForm extends TStandardForm{
    protected $form;

    function __construct(){
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder('form_nutricao_parenteral');
        $this->form->setFormTitle('Nutrição Parenteral');
        
        parent::setDatabase('dbsic');
        parent::setActiveRecord('NutricaoParenteralRecord');
        
        $id                                 = new THidden('id');
        $paciente_id                        = new THidden('paciente_id');
        //$paciente_id = filter_input('INPUT_GET', 'id');
        $paciente_id->setValue(filter_input(INPUT_GET, 'id'));

        TTransaction::open('dbsic');
        $tempVisita = new PacienteRecord( filter_input( INPUT_GET, 'id' ) );
        
        if( $tempVisita ){
            $paciente_nome = new TLabel( $tempVisita->nome );
       }
       TTransaction::close(); 

        $inicio                             = new TDate('datainicio');
        $fim                                = new TDate('datafim');
        $tipoparenteral                     = new TEntry('tipoparenteral');
        $tipoparenteraloutros               = new TEntry('tipoparenteraloutros');
        $totalcalorias                      = new TEntry('totalcalorias');
        $percentualdiario                   = new TEntry('percentualdiario');
        $volumenpt                          = new TEntry('volumenpt');
        $tempoinfusao                       = new TEntry('tempoinfusao');
        $frequencia                         = new TEntry('frequencia');
        $acessovenosolp                     = new TEntry('acessovenosolp');
        $acessovenosolpqual                 = new TEntry('acessovenosolpqual');
        $numerodeacessovenoso               = new TEntry('numerodeacessovenoso');
        $apresentouinfeccaoacessovenoso     = new TEntry('apresentouinfeccaoacessovenoso');
        $vezesinfeccaoacessovenoso         = new TEntry('vezesinfeccaoacessovenoso');


        //$controller    = new TMultiSearch('controller');
        //$controller->addItems($this->getPrograms());
        //$controller->setMaxSize(1);
        //$controller->setMinLength(0);
        //$id->setEditable(false);

        $inicio->setMask('dd/mm/yyyy');
        $fim->setMask('dd/mm/yyyy');
        $inicio->setDatabaseMask('yyyy-mm-dd');
        $fim->setDatabaseMask('yyyy-mm-dd');

        $this->form->addField( $id, null );
        $this->form->addField(  $paciente_id, null);
        $this->form->addFields( [new TLabel('Paciente')], [$paciente_nome] );
        $this->form->addFields( [new TLabel('Inicio')], [$inicio] );
        $this->form->addFields( [new TLabel('Fim')], [$fim] );
        $this->form->addFields( [new TLabel('Tipo Parenteral')], [$tipoparenteral] );
        $this->form->addFields( [new TLabel('Outros Tipos Parenteral')], [$tipoparenteraloutros] );
        $this->form->addFields( [new TLabel('Total de Calorias')], [$totalcalorias] );
        $this->form->addFields( [new TLabel('Percentual Diário')], [$percentualdiario] );
        $this->form->addFields( [new TLabel('Volume NPT')], [$volumenpt] );
        $this->form->addFields( [new TLabel('Tempo Infusão')], [$tempoinfusao] );
        $this->form->addFields( [new TLabel('Frequência')], [$frequencia] );
        $this->form->addFields( [new TLabel('Acesso Venoso')], [$acessovenosolp] );
        $this->form->addFields( [new TLabel('Acesso Venoso Qual.')], [$acessovenosolpqual] );
        $this->form->addFields( [new TLabel('Acesso Venoso - Quantidade')], [$numerodeacessovenoso] );
        $this->form->addFields( [new TLabel('Apresentou Infecção no Acesso Venoso')], [$apresentouinfeccaoacessovenoso] );
        $this->form->addFields( [new TLabel('Quantidade de Infecção no Acesso Venoso')], [$vezesinfeccaoacessovenoso] );

        //$id->setSize('30%');
        //$name->setSize('70%');
        //$controller->setSize('70%');  

        $this->form->addAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addAction('Voltar',new TAction(array('PacienteList','onReload')),'fa:table blue');

        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add(new TXMLBreadCrumb('menu.xml','NutricaoParenteralList'));
        $container->add($this->form);
        
        parent::add($container);
    }
    function onEdit($param) {
    try {
        if (isset($param['id'])) {

            $key = $param['id'];
            TTransaction::open('dbsic');
            $object = new NutricaoParenteralRecord($key);

            $this->form->setData($object);
            TTransaction::close();
        } else {
            $this->form->clear();
        }
    } catch (Exception $e) {

        new TMessage('error', '<b>Error</b> ' . $e->getMessage());
        TTransaction::rollback();
    }
}
/*
    function onSave() {
        TTransaction::open('dbsic');
        $msg = '';
        $cadastro = $this->form->getData('NutricaoParenteralRecord');
        $dados = $cadastro->toArray();
        var_dump($cadastro);
        exit();
        $icone = 'info';

        try {

            if ($msg == '') {
                $cadastro->store();
                $msg = 'Dados armazenados com sucesso';

                TTransaction::close();
            } else {
                $icone = 'error';
            }

            if ($icone == 'error') {
                new TMessage($icone, $msg);
                $this->form->setData($cadastro); 
            } else {
                
                new TMessage("info", "Registro salvo com sucesso!");
                TApplication::gotoPage('PacienteList', 'onReload'); // reload
            }
        } catch (Exception $e) { 
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
            $this->form->setData($cadastro);
        }
    }
*/







   public function onSave(){
        try{

            TTransaction::open('dbsic');
            $cadastro = $this->form->getData('NutricaoParenteralRecord');
            var_dump($cadastro->paciente_id);
            exit();

            $this->form->validate();
            $cadastro->store();
            TTransaction::close();
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
            TApplication::gotoPage('NutricaoParenteralList', 'onReload');

        }catch (Exception $e){
            $object = $this->form->getData($this->activeRecord);
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
}
