<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

class ExamePacienteDetalhe extends TWindow{
    protected $form;
    protected $datagrid;
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;

    function __construct(){
        parent::__construct();
        parent::SetSize(0.800,0.800);
        
        $this->form = new TQuickForm('form_exame_paciente');
        $this->form->class = 'form_exame_paciente';
        $this->form->setFormTitle('Exames Realizados');
        
        $id             = new THidden('id');
        $paciente_id    = new THidden('paciente_id');
        $dataexame      = new TDate('dataexame');
        $valor          = new TEntry('valor');
        $type           = new TCombo('type');


        #####################
        ##----HEMOGRAMA----##

        ##--Eritrograma--#
        $eritrocitos            = new TEntry('eritrocitos');
        $hemoglobina            = new TEntry('hemoglobina');
        $hematocrito            = new TEntry('hematocrito');   
        $reticulocitos          = new TEntry('reticulocitos');  
        $vcm                    = new TEntry('vcm');                //Volume Corpuscular Medio
        $hcm                    = new TEntry('hcm');                //Hemoglobina Corpuscular Media
        $chcm                   = new TEntry('chcm');               //Concentracao Hemoglobina Corpuscular Media
        $rdw                    = new TEntry('rdw');                //Amplitude Volume Corpuscular
        $reticulocitos          = new TEntry('reticulocitos');
        $observacao_eritrograma = new TText('observacao_eritrograma');

        ##--Leucograma--##

        $leucocitos             = new TEntry('leucocitos');
        $neutrofilos            = new TEntry('neutrofilos');
        $bastoes                = new TEntry('bastoes');
        $linfocitos             = new TEntry('linfocitos');
        $monocitos              = new TEntry('monocitos');
        $eosinofilos            = new TEntry('eosinofilos');
        $basofilos              = new TEntry('basofilos');
        $outros                 = new TEntry('outros');
        $observacao_leucograma  = new TText('observacao_leucograma');

        ##--Plaquetograma--##

        $plaquetas                 = new TEntry('plaquetas'); 
        $vpm                       = new TEntry('vpm');           //Volume Plaquetário Médio 
        $observacao_plaquetograma  = new TEntry('observacao_plaquetograma');
      

        #######################
        ##----LIPODOGRAMA----##

        $colesterol_total   = new TEntry('colesterol_total');
        $hdl                = new TEntry('hdl');
        $ldl                = new TEntry('ldl');
        $vldl               = new TEntry('vldl');
        $tgl                = new TEntry('tgl');
        $lipidios_totais    = new TEntry('lipidios_totais');
        $quilomicrons       = new TEntry('quilomicrons');

        #####################
        ##----IONOGRAMA----##
        $sodio              = new TEntry('sodio');
        $potassio           = new TEntry('potassio');
        $calcio             = new TEntry('calcio');
        $magnesio           = new TEntry('magnesio');
        $ion_hidrogenio     = new TEntry('ion_hidrogenio');
        $cloro              = new TEntry('cloro');
        $bicarbonato        = new TEntry('bicarbonato');
        $fosfato            = new TEntry('fosfato');
        $ion_hidroxila      = new TEntry('ion_hidroxila');
        $sulfatos_acidos    = new TEntry('sulfatos_acidos');

        ##################
        ##----OUTROS----##

        $glicose_jejum                  = new TEntry('glicose_jejum');
        $teste_oral_tolerancia_glicose  = new TEntry('teste_oral_tolerancia_glicose');
        $hemoglobina_glicada            = new TEntry('hemoglobina_glicada');
        $ureia                          = new TEntry('ureia');
        $creatinina                     = new TEntry('creatinina');
        $tfg                            = new TEntry('tfg');
        $ast_tgo                        = new TEntry('ast_tgo');
        $alt_tgp                        = new TEntry('alt_tgp');
        $relacao_ast_alt                = new TEntry('relacao_ast_alt');
        $gama_gt                        = new TEntry('gama_gt');
        $fosfatase_alcalina             = new TEntry('fosfatase_alcalina');
        $proteina_total                 = new TEntry('proteina_total');
        $albumina                       = new TEntry('albumina');
        $globulina                      = new TEntry('globulina');
        $reacao_albumina_globulina      = new TEntry('reacao_albumina_globulina');
        $tranferrina_serica             = new TEntry('tranferrina_serica');
        $ferritina                      = new TEntry('ferritina');
        $ferro                          = new TEntry('ferro');
        $bilirrubina_total              = new TEntry('bilirrubina_total');
        $bilirrubina_direta             = new TEntry('bilirrubina_direta');
        $tempo_protrombinal             = new TEntry('tempo_protrombinal');
        $ttpa                           = new TEntry('ttpa');                   //Tempo de tromboplastina parcial ativada 
        $citrulina_serica               = new TEntry('citrulina_serica');
        $oxalato_urina_24h              = new TEntry('oxalato_urina_24h');
        $dlactato                       = new TEntry('dlactato');
        $dxilose_serica_60m             = new TEntry('dxilose_serica_60m');
        $vitaminab12                    = new TEntry('vitaminab12');
        $vitaminad                      = new TEntry('vitaminad');
        $cobre                          = new TEntry('cobre');
        $zinco                          = new TEntry('zinco');
        $pre_albumina                   = new TEntry('pre_albumina');

        ##--OnChange Action--##


        $type->setChangeAction(new TAction(array($this, 'onChangeType')));
        $combo_items = array();
        $combo_items['h'] ='Hemograma';
        $combo_items['l'] ='Lipodograma';
        $combo_items['i'] = 'Ionograma';
        $combo_items['o'] = 'Outros';
        $type->addItems($combo_items);
        $type->setValue('h');
        self::onChangeType( ['type' => 'h'] );



        $paciente_id->setValue(filter_input(INPUT_GET, 'fk'));

        TTransaction::open('dbsic');
        $tempVisita = new PacienteRecord( filter_input( INPUT_GET, 'fk' ) );
        if( $tempVisita ){
            $paciente_nome = new TLabel( $tempVisita->nome );
            $paciente_nome->setEditable(FALSE);
        }
        TTransaction::close(); 

   

        $dataexame->setMask('dd/mm/yyyy');
        $dataexame->setDatabaseMask('yyyy-mm-dd');
        $valor->setMask('99999');

        $dataexame->addValidation( "Data do Exame", new TRequiredValidator );

       
        $this->form->addQuickField('Tipo Exame',$type  );

        ##----HEMOGRAMA----##

        ##--Eritrograma--#

        $this->form->addQuickFields('Eritrocitos', [$eritrocitos, new TLabel('milhões/mm³')]);
        $this->form->addQuickFields('hemoglobina', [$hemoglobina, new TLabel('g/dL')] );
        $this->form->addQuickFields('hematocrito', [$hematocrito, new TLabel('%')]);
        $this->form->addQuickFields('Reticulócitos', [$reticulocitos, new TLabel('%')]);
        $this->form->addQuickFields('Volume Corpuscular Médio ',
            [$vcm, new TLabel('fL')]);                                    //Volume Corpuscular Medio
        $this->form->addQuickFields('Hemoglobina Corpuscular Média ',
            [$hcm, new TLabel('pg')]);                                    //Hemoglobina Corpuscular Media
        $this->form->addQuickFields('Concentração da Hemoglobina Corpuscular Média', 
            [$chcm, new TLabel('g/dL')]);                                 //Concentracao Hemoglobina Corpuscular Media
        $this->form->addQuickFields('Amplitude de Distribuição do Volume Corpuscular', 
            [$rdw, new TLabel('%')]);                                     //Amplitude Volume Corpuscular

        $this->form->addQuickField('Observações',$observacao_eritrograma);  

        ##--Leucograma--##
        $this->form->addQuickFields('Leucócitos', [$leucocitos, new TLabel('% e milhões/mm³')]);
        $this->form->addQuickFields('Neutrófilos', [$neutrofilos, new TLabel('% e milhões/mm³')] );
        $this->form->addQuickFields('Bastões', [$bastoes, new TLabel('% e milhões/mm³')]);
        $this->form->addQuickFields('Linfócitos', [$linfocitos, new TLabel('% e milhões/mm³')]);
        $this->form->addQuickFields('Monócitos', [$monocitos,  new TLabel('% e milhões/mm³')]);
        $this->form->addQuickFields('Eosinófilos', [$eosinofilos,  new TLabel('% e milhões/mm³')]);
        $this->form->addQuickFields('Basófilos', [$basofilos, new TLabel('% e milhões/mm³')]);
        $this->form->addQuickFields('Outros', [$outros,  new TLabel('% e milhões/mm³')]);
        $this->form->addQuickField('Observações', $observacao_leucograma);

        ##--Plaquetograma--##

        $this->form->addQuickFields('Plaquetas', [$plaquetas, new TLabel('milhões/mm³')]);
        $this->form->addQuickFields('Volume Plaquetário Médio',
         [$vpm, new TLabel('fL')]);                                                 //Volume Plaquetário Médio 
        $this->form->addQuickField('Observações', $observacao_plaquetograma);


        ##--LIPODOGRAMA--##

        //$this->form->addQuickField('LIPODOGRAMA', null, "30%");

        $this->form->addQuickField('colesterol_total', $colesterol_total);
        $this->form->addQuickFields('hdl', [$hdl, new TLabel('mg/dL')]);
        $this->form->addQuickFields('ldl', [$ldl, new TLabel('mg/dL')]);
        $this->form->addQuickFields('vldl', [$vldl, new TLabel('mg/dL')]);
        $this->form->addQuickFields('Lipídios Totais', [$lipidios_totais, new TLabel('mg/dL')]);
        $this->form->addQuickFields('Quilomicrons ', [$quilomicrons, new TLabel('mg/dL')]);


        ##----IONOGRAMA----##

        $this->form->addQuickFields('Sódio', [$sodio,  new TLabel('mEq/L')]);
        $this->form->addQuickFields('Potássio', [$potassio, new TLabel('mEq/L')]);
        $this->form->addQuickFields('Cálcio', [$calcio, new TLabel('mEq/L')]);
        $this->form->addQuickFields('Magnésio', [$magnesio, new TLabel('mEq/L')]);
        $this->form->addQuickFields('Ion Hidrogênio', [$ion_hidrogenio, new TLabel('mEq/L')]);
        $this->form->addQuickFields('Cloro ', [$cloro, new TLabel('mEq/L')]);
        $this->form->addQuickFields('Bicarbonato', [$bicarbonato,  new TLabel('mEq/L')]);
        $this->form->addQuickFields('Fosfato', [$fosfato, new TLabel('mEq/L')]);
        $this->form->addQuickFields('Ion Hidroxila', [$ion_hidroxila, new TLabel('mEq/L')]);
        $this->form->addQuickFields('Sulfatos e outros ácidos', [$sulfatos_acidos, new TLabel('mEq/L')]);


        ##----OUTROS----##

        $this->form->addQuickFields('Glicose de Jejum', [$glicose_jejum,  new TLabel('mg/dL')]);
        $this->form->addQuickFields('Teste Oral de Tolerâncua à Glicose',
         [$teste_oral_tolerancia_glicose, new TLabel('mg/dL')]);
        $this->form->addQuickFields('Hemoglobina Glicada', [$hemoglobina_glicada, new TLabel('%')]);
        $this->form->addQuickFields('Ureia', [$ureia, new TLabel('mg/dL')]);
        $this->form->addQuickFields('Creatinina', [$creatinina, new TLabel('mg/dL')]);
        $this->form->addQuickFields('TFG', [$tfg, new TLabel('mL/min/1,73m²')]);
        $this->form->addQuickFields('AST(TGO)', [$ast_tgo,  new TLabel('U/L')]);
        $this->form->addQuickFields('ALT(TGP)', [$alt_tgp, new TLabel('U/L')]);
        $this->form->addQuickField('Relação AST/ALT', $relacao_ast_alt);
        $this->form->addQuickFields('Gama GT', [$gama_gt, new TLabel('U/L')]);
        $this->form->addQuickFields('Fosfatase Alcalina', [$fosfatase_alcalina,  new TLabel('U/L')]);
        $this->form->addQuickFields('Proteína total', [$proteina_total, new TLabel('g/dL')]);
        $this->form->addQuickFields('Albumina', [$albumina, new TLabel('g/dL')]);
        $this->form->addQuickFields('Globulina', [$globulina, new TLabel('g/dL')]);
        $this->form->addQuickField('Reação Albumina/Globulina', $reacao_albumina_globulina);
        $this->form->addQuickFields('Transferrina Serica', [$tranferrina_serica, new TLabel('(mg/dL')]);
        $this->form->addQuickFields('Ferritina', [$ferritina,  new TLabel('ng/mL')]);
        $this->form->addQuickFields('Ferro', [$ferro, new TLabel('µg/dL')]);
        $this->form->addQuickFields('Bilirrubina Total', [$bilirrubina_total, new TLabel('mg/dL')]);
        $this->form->addQuickFields('Bilirrubina Direta', [$bilirrubina_direta, new TLabel('mg/dL')]);
        $this->form->addQuickField('Tempo de Protrombina INR', $tempo_protrombinal);
        $this->form->addQuickField('Tempo de Tromboplastina Parcial Ativada', $ttpa);
        $this->form->addQuickFields('Citrulina Sérica', [$citrulina_serica, new TLabel('µmol/L')]);
        $this->form->addQuickFields('Medida de Oxalato em Urina de 24 Horas', [$oxalato_urina_24h, new TLabel('mg/24h')]);
        $this->form->addQuickFields('D-Lactato', [$dlactato, new TLabel('mg/dL')]);
        $this->form->addQuickFields('D-Xilose Sérica após 60 Minutos da Administração',
         [$dxilose_serica_60m, new TLabel('mg/dL')]);
        $this->form->addQuickFields('Vitamina B12 ', [$vitaminab12, new TLabel('pg/mL')]);
        $this->form->addQuickFields('Vitamina D', [$vitaminad,  new TLabel('ng/mL')]);
        $this->form->addQuickFields('Cobre', [$cobre, new TLabel('µg/dL')]);
        $this->form->addQuickFields('Zinco', [$zinco, new TLabel('µg/dL')]);
        $this->form->addQuickFields('Pré-albumina', [$pre_albumina, new TLabel('g/dL')]);
    

        ##----ID'S----##
        $this->form->addQuickField( null, $paciente_id, "30%" );
        $this->form->addQuickField(null, $id, "30%" );

        ##----BOTÕES----##

        $action = new TAction(array($this, 'onSave'));
        $action->setParameter('id', '' . filter_input(INPUT_GET, 'id') . '');
        $action->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');
        $action->setParameter('key', '' . filter_input(INPUT_GET, 'key') . '');

        $voltar = new TAction(array('PacienteDetail','onReload'));        
        $voltar->setParameter('id', '' . filter_input(INPUT_GET, 'id') . '');
        $voltar->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');
        $voltar->setParameter('key', '' . filter_input(INPUT_GET, 'key') . '');


        $this->form->addQuickAction('Salvar', $action, 'fa:floppy-o');
        $this->form->addQuickAction('Voltar para Pacientes', $voltar,'fa:table blue');

        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add($this->form);
        $container->add($this->pageNavigation);

        parent::add($container);
    }
 
    public function onSave(){
        try{

            TTransaction::open('dbsic');
            $cadastro = $this->form->getData('ExamePacienteRecord');

            unset($type);

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
            $object = $this->form->getData('ExamePacienteRecord');
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
          public function onEdit($param) {

        TTransaction::open('dbsic');
        
        if (isset($param['key'])) {

            $key = $param['key'];
            $object = new ExamePacienteRecord($key);

            $object->dataregistro = TDate::date2br($object->dataregistro);
            $object->datacirurgia = TDate::date2br($object->datacirurgia);
            $object->datatransplante = TDate::date2br($object->datatransplante);
            $this->form->setData($object);
            
        } else {
            $this->form->clear();
        }
        TTransaction::close();

    }

    public static function onChangeType($param)
    {
        if ($param['type'] == 'h')
        {   

            ##--Eritrograma--#
            TQuickForm::showField('form_exame_paciente', 'eritrocitos');
            TQuickForm::showField('form_exame_paciente', 'hemoglobina');
            TQuickForm::showField('form_exame_paciente', 'hematocrito');
            TQuickForm::showField('form_exame_paciente', 'reticulocitos');
            TQuickForm::showField('form_exame_paciente', 'vcm');        //volume_corpuscular_medio
            TQuickForm::showField('form_exame_paciente', 'hcm');        //hemoglobina_corpuscular_media
            TQuickForm::showField('form_exame_paciente', 'chcm');       //concentracao_hemoglobina_corpuscular_media
            TQuickForm::showField('form_exame_paciente', 'rdw');        //amplitude_volume_corpuscular
            TQuickForm::showField('form_exame_paciente', 'observacao_eritrograma');

            ##--Leucograma--##
            TQuickForm::showField('form_exame_paciente', 'leucocitos');
            TQuickForm::showField('form_exame_paciente', 'neutrofilos');
            TQuickForm::showField('form_exame_paciente', 'bastoes');
            TQuickForm::showField('form_exame_paciente', 'linfocitos');
            TQuickForm::showField('form_exame_paciente', 'monocitos');
            TQuickForm::showField('form_exame_paciente', 'eosinofilos');
            TQuickForm::showField('form_exame_paciente', 'basofilos');
            TQuickForm::showField('form_exame_paciente', 'outros');
            TQuickForm::showField('form_exame_paciente', 'observacao_leucograma');

            ##--Plaquetograma--##
            TQuickForm::showField('form_exame_paciente', 'plaquetas');
            TQuickForm::showField('form_exame_paciente', 'vpm');
            TQuickForm::showField('form_exame_paciente', 'observacao_plaquetograma');
    

            ##--LIPODOGRAMA--##
            TQuickForm::hideField('form_exame_paciente', 'colesterol_total');
            TQuickForm::hideField('form_exame_paciente', 'hdl');
            TQuickForm::hideField('form_exame_paciente', 'ldl');
            TQuickForm::hideField('form_exame_paciente', 'vldl');
            TQuickForm::hideField('form_exame_paciente', 'lipidios_totais');
            TQuickForm::hideField('form_exame_paciente', 'quilomicrons');


            ##--IONOGRAMA--##
            TQuickForm::hideField('form_exame_paciente', 'sodio');
            TQuickForm::hideField('form_exame_paciente', 'potassio');
            TQuickForm::hideField('form_exame_paciente', 'calcio');
            TQuickForm::hideField('form_exame_paciente', 'magnesio');
            TQuickForm::hideField('form_exame_paciente', 'ion_hidrogenio');
            TQuickForm::hideField('form_exame_paciente', 'cloro');
            TQuickForm::hideField('form_exame_paciente', 'bicarbonato');
            TQuickForm::hideField('form_exame_paciente', 'fosfato');
            TQuickForm::hideField('form_exame_paciente', 'ion_hidroxila');
            TQuickForm::hideField('form_exame_paciente', 'sulfatos_acidos');


            ##--OUTROS--##
            TQuickForm::hideField('form_exame_paciente', 'glicose_jejum');
            TQuickForm::hideField('form_exame_paciente', 'teste_oral_tolerancia_glicose');
            TQuickForm::hideField('form_exame_paciente', 'hemoglobina_glicada');
            TQuickForm::hideField('form_exame_paciente', 'ureia');
            TQuickForm::hideField('form_exame_paciente', 'creatinina');
            TQuickForm::hideField('form_exame_paciente', 'tfg');
            TQuickForm::hideField('form_exame_paciente', 'ast_tgo');
            TQuickForm::hideField('form_exame_paciente', 'alt_tgp');
            TQuickForm::hideField('form_exame_paciente', 'relacao_ast_alt');
            TQuickForm::hideField('form_exame_paciente', 'gama_gt');
            TQuickForm::hideField('form_exame_paciente', 'fosfatase_alcalina');
            TQuickForm::hideField('form_exame_paciente', 'proteina_total');
            TQuickForm::hideField('form_exame_paciente', 'albumina');
            TQuickForm::hideField('form_exame_paciente', 'globulina');
            TQuickForm::hideField('form_exame_paciente', 'reacao_albumina_globulina');
            TQuickForm::hideField('form_exame_paciente', 'ferritina');            
            TQuickForm::hideField('form_exame_paciente', 'tranferrina_serica');
            TQuickForm::hideField('form_exame_paciente', 'ferro');
            TQuickForm::hideField('form_exame_paciente', 'bilirrubina_total');
            TQuickForm::hideField('form_exame_paciente', 'bilirrubina_direta');
            TQuickForm::hideField('form_exame_paciente', 'tempo_protrombinal');
            TQuickForm::hideField('form_exame_paciente', 'ttpa');
            TQuickForm::hideField('form_exame_paciente', 'citrulina_serica');
            TQuickForm::hideField('form_exame_paciente', 'oxalato_urina_24h');
            TQuickForm::hideField('form_exame_paciente', 'dlactato');
            TQuickForm::hideField('form_exame_paciente', 'dxilose_serica_60m');
            TQuickForm::hideField('form_exame_paciente', 'vitaminab12');
            TQuickForm::hideField('form_exame_paciente', 'vitaminad');
            TQuickForm::hideField('form_exame_paciente', 'cobre');
            TQuickForm::hideField('form_exame_paciente', 'zinco');
            TQuickForm::hideField('form_exame_paciente', 'pre_albumina');

        }
        else if ($param['type'] == 'l')
        {

            ##--Eritrograma--#
            TQuickForm::hideField('form_exame_paciente', 'eritrocitos');
            TQuickForm::hideField('form_exame_paciente', 'hemoglobina');
            TQuickForm::hideField('form_exame_paciente', 'hematocrito');
            TQuickForm::hideField('form_exame_paciente', 'reticulocitos');
            TQuickForm::hideField('form_exame_paciente', 'vcm');        //volume_corpuscular_medio
            TQuickForm::hideField('form_exame_paciente', 'hcm');        //hemoglobina_corpuscular_media
            TQuickForm::hideField('form_exame_paciente', 'chcm');       //concentracao_hemoglobina_corpuscular_media
            TQuickForm::hideField('form_exame_paciente', 'rdw');        //amplitude_volume_corpuscular
            TQuickForm::hideField('form_exame_paciente', 'observacao_eritrograma');

            ##--Leucograma--##
            TQuickForm::hideField('form_exame_paciente', 'leucocitos');
            TQuickForm::hideField('form_exame_paciente', 'neutrofilos');
            TQuickForm::hideField('form_exame_paciente', 'bastoes');
            TQuickForm::hideField('form_exame_paciente', 'linfocitos');
            TQuickForm::hideField('form_exame_paciente', 'monocitos');
            TQuickForm::hideField('form_exame_paciente', 'eosinofilos');
            TQuickForm::hideField('form_exame_paciente', 'basofilos');
            TQuickForm::hideField('form_exame_paciente', 'outros');
            TQuickForm::hideField('form_exame_paciente', 'observacao_leucograma');

            ##--Plaquetograma--##
            TQuickForm::hideField('form_exame_paciente', 'plaquetas');
            TQuickForm::hideField('form_exame_paciente', 'vpm');
            TQuickForm::hideField('form_exame_paciente', 'observacao_plaquetograma');


            ##--LIPODOGRAMA--##
            TQuickForm::showField('form_exame_paciente', 'colesterol_total');
            TQuickForm::showField('form_exame_paciente', 'hdl');
            TQuickForm::showField('form_exame_paciente', 'ldl');
            TQuickForm::showField('form_exame_paciente', 'vldl');
            TQuickForm::showField('form_exame_paciente', 'lipidios_totais');
            TQuickForm::showField('form_exame_paciente', 'quilomicrons');

            ##--IONOGRAMA--##
            TQuickForm::hideField('form_exame_paciente', 'sodio');
            TQuickForm::hideField('form_exame_paciente', 'potassio');
            TQuickForm::hideField('form_exame_paciente', 'calcio');
            TQuickForm::hideField('form_exame_paciente', 'magnesio');
            TQuickForm::hideField('form_exame_paciente', 'ion_hidrogenio');
            TQuickForm::hideField('form_exame_paciente', 'cloro');
            TQuickForm::hideField('form_exame_paciente', 'bicarbonato');
            TQuickForm::hideField('form_exame_paciente', 'fosfato');
            TQuickForm::hideField('form_exame_paciente', 'ion_hidroxila');
            TQuickForm::hideField('form_exame_paciente', 'sulfatos_acidos');

            ##--OUTROS--##
            TQuickForm::hideField('form_exame_paciente', 'glicose_jejum');
            TQuickForm::hideField('form_exame_paciente', 'teste_oral_tolerancia_glicose');
            TQuickForm::hideField('form_exame_paciente', 'hemoglobina_glicada');
            TQuickForm::hideField('form_exame_paciente', 'ureia');
            TQuickForm::hideField('form_exame_paciente', 'creatinina');
            TQuickForm::hideField('form_exame_paciente', 'tfg');
            TQuickForm::hideField('form_exame_paciente', 'ast_tgo');
            TQuickForm::hideField('form_exame_paciente', 'alt_tgp');
            TQuickForm::hideField('form_exame_paciente', 'relacao_ast_alt');
            TQuickForm::hideField('form_exame_paciente', 'gama_gt');
            TQuickForm::hideField('form_exame_paciente', 'fosfatase_alcalina');
            TQuickForm::hideField('form_exame_paciente', 'proteina_total');
            TQuickForm::hideField('form_exame_paciente', 'albumina');
            TQuickForm::hideField('form_exame_paciente', 'globulina');
            TQuickForm::hideField('form_exame_paciente', 'reacao_albumina_globulina');
            TQuickForm::hideField('form_exame_paciente', 'tranferrina_serica');
            TQuickForm::hideField('form_exame_paciente', 'ferritina');  
            TQuickForm::hideField('form_exame_paciente', 'ferro');
            TQuickForm::hideField('form_exame_paciente', 'bilirrubina_total');
            TQuickForm::hideField('form_exame_paciente', 'bilirrubina_direta');
            TQuickForm::hideField('form_exame_paciente', 'tempo_protrombinal');
            TQuickForm::hideField('form_exame_paciente', 'ttpa');
            TQuickForm::hideField('form_exame_paciente', 'citrulina_serica');
            TQuickForm::hideField('form_exame_paciente', 'oxalato_urina_24h');
            TQuickForm::hideField('form_exame_paciente', 'dlactato');
            TQuickForm::hideField('form_exame_paciente', 'dxilose_serica_60m');
            TQuickForm::hideField('form_exame_paciente', 'vitaminab12');
            TQuickForm::hideField('form_exame_paciente', 'vitaminad');
            TQuickForm::hideField('form_exame_paciente', 'cobre');
            TQuickForm::hideField('form_exame_paciente', 'zinco');
            TQuickForm::hideField('form_exame_paciente', 'pre_albumina');

        }

        else if ($param['type'] == 'i') {
                   

            ##--Eritrograma--#
            TQuickForm::hideField('form_exame_paciente', 'eritrocitos');
            TQuickForm::hideField('form_exame_paciente', 'hemoglobina');
            TQuickForm::hideField('form_exame_paciente', 'hematocrito');
            TQuickForm::hideField('form_exame_paciente', 'reticulocitos');
            TQuickForm::hideField('form_exame_paciente', 'vcm');        //volume_corpuscular_medio
            TQuickForm::hideField('form_exame_paciente', 'hcm');        //hemoglobina_corpuscular_media
            TQuickForm::hideField('form_exame_paciente', 'chcm');       //concentracao_hemoglobina_corpuscular_media
            TQuickForm::hideField('form_exame_paciente', 'rdw');        //amplitude_volume_corpuscular
            TQuickForm::hideField('form_exame_paciente', 'observacao_eritrograma');

            ##--Leucograma--##
            TQuickForm::hideField('form_exame_paciente', 'leucocitos');
            TQuickForm::hideField('form_exame_paciente', 'neutrofilos');
            TQuickForm::hideField('form_exame_paciente', 'bastoes');
            TQuickForm::hideField('form_exame_paciente', 'linfocitos');
            TQuickForm::hideField('form_exame_paciente', 'monocitos');
            TQuickForm::hideField('form_exame_paciente', 'eosinofilos');
            TQuickForm::hideField('form_exame_paciente', 'basofilos');
            TQuickForm::hideField('form_exame_paciente', 'outros');
            TQuickForm::hideField('form_exame_paciente', 'observacao_leucograma');

            ##--Plaquetograma--##
            TQuickForm::hideField('form_exame_paciente', 'plaquetas');
            TQuickForm::hideField('form_exame_paciente', 'vpm');
            TQuickForm::hideField('form_exame_paciente', 'observacao_plaquetograma');


            ##--LIPODOGRAMA--##
            TQuickForm::hideField('form_exame_paciente', 'colesterol_total');
            TQuickForm::hideField('form_exame_paciente', 'hdl');
            TQuickForm::hideField('form_exame_paciente', 'ldl');
            TQuickForm::hideField('form_exame_paciente', 'vldl');
            TQuickForm::hideField('form_exame_paciente', 'lipidios_totais');
            TQuickForm::hideField('form_exame_paciente', 'quilomicrons');

            ##--IONOGRAMA--##
            TQuickForm::showField('form_exame_paciente', 'sodio');
            TQuickForm::showField('form_exame_paciente', 'potassio');
            TQuickForm::showField('form_exame_paciente', 'calcio');
            TQuickForm::showField('form_exame_paciente', 'magnesio');
            TQuickForm::showField('form_exame_paciente', 'ion_hidrogenio');
            TQuickForm::showField('form_exame_paciente', 'cloro');
            TQuickForm::showField('form_exame_paciente', 'bicarbonato');
            TQuickForm::showField('form_exame_paciente', 'fosfato');
            TQuickForm::showField('form_exame_paciente', 'ion_hidroxila');
            TQuickForm::showField('form_exame_paciente', 'sulfatos_acidos');

             ##--OUTROS--##
            TQuickForm::hideField('form_exame_paciente', 'glicose_jejum');
            TQuickForm::hideField('form_exame_paciente', 'teste_oral_tolerancia_glicose');
            TQuickForm::hideField('form_exame_paciente', 'hemoglobina_glicada');
            TQuickForm::hideField('form_exame_paciente', 'ureia');
            TQuickForm::hideField('form_exame_paciente', 'creatinina');
            TQuickForm::hideField('form_exame_paciente', 'tfg');
            TQuickForm::hideField('form_exame_paciente', 'ast_tgo');
            TQuickForm::hideField('form_exame_paciente', 'alt_tgp');
            TQuickForm::hideField('form_exame_paciente', 'relacao_ast_alt');
            TQuickForm::hideField('form_exame_paciente', 'gama_gt');
            TQuickForm::hideField('form_exame_paciente', 'fosfatase_alcalina');
            TQuickForm::hideField('form_exame_paciente', 'proteina_total');
            TQuickForm::hideField('form_exame_paciente', 'albumina');
            TQuickForm::hideField('form_exame_paciente', 'globulina');
            TQuickForm::hideField('form_exame_paciente', 'reacao_albumina_globulina');
            TQuickForm::hideField('form_exame_paciente', 'tranferrina_serica');
            TQuickForm::hideField('form_exame_paciente', 'ferritina');  
            TQuickForm::hideField('form_exame_paciente', 'ferro');
            TQuickForm::hideField('form_exame_paciente', 'bilirrubina_total');
            TQuickForm::hideField('form_exame_paciente', 'bilirrubina_direta');
            TQuickForm::hideField('form_exame_paciente', 'tempo_protrombinal');
            TQuickForm::hideField('form_exame_paciente', 'ttpa');
            TQuickForm::hideField('form_exame_paciente', 'citrulina_serica');
            TQuickForm::hideField('form_exame_paciente', 'oxalato_urina_24h');
            TQuickForm::hideField('form_exame_paciente', 'dlactato');
            TQuickForm::hideField('form_exame_paciente', 'dxilose_serica_60m');
            TQuickForm::hideField('form_exame_paciente', 'vitaminab12');
            TQuickForm::hideField('form_exame_paciente', 'vitaminad');
            TQuickForm::hideField('form_exame_paciente', 'cobre');
            TQuickForm::hideField('form_exame_paciente', 'zinco');
            TQuickForm::hideField('form_exame_paciente', 'pre_albumina');
        }

        else{


            ##--Eritrograma--#
            TQuickForm::hideField('form_exame_paciente', 'eritrocitos');
            TQuickForm::hideField('form_exame_paciente', 'hemoglobina');
            TQuickForm::hideField('form_exame_paciente', 'hematocrito');
            TQuickForm::hideField('form_exame_paciente', 'reticulocitos');
            TQuickForm::hideField('form_exame_paciente', 'vcm');        //volume_corpuscular_medio
            TQuickForm::hideField('form_exame_paciente', 'hcm');        //hemoglobina_corpuscular_media
            TQuickForm::hideField('form_exame_paciente', 'chcm');       //concentracao_hemoglobina_corpuscular_media
            TQuickForm::hideField('form_exame_paciente', 'rdw');        //amplitude_volume_corpuscular
            TQuickForm::hideField('form_exame_paciente', 'observacao_eritrograma');

            ##--Leucograma--##
            TQuickForm::hideField('form_exame_paciente', 'leucocitos');
            TQuickForm::hideField('form_exame_paciente', 'neutrofilos');
            TQuickForm::hideField('form_exame_paciente', 'bastoes');
            TQuickForm::hideField('form_exame_paciente', 'linfocitos');
            TQuickForm::hideField('form_exame_paciente', 'monocitos');
            TQuickForm::hideField('form_exame_paciente', 'eosinofilos');
            TQuickForm::hideField('form_exame_paciente', 'basofilos');
            TQuickForm::hideField('form_exame_paciente', 'outros');
            TQuickForm::hideField('form_exame_paciente', 'observacao_leucograma');

            ##--Plaquetograma--##
            TQuickForm::hideField('form_exame_paciente', 'plaquetas');
            TQuickForm::hideField('form_exame_paciente', 'vpm');
            TQuickForm::hideField('form_exame_paciente', 'observacao_plaquetograma');


            ##--LIPODOGRAMA--##
            TQuickForm::hideField('form_exame_paciente', 'colesterol_total');
            TQuickForm::hideField('form_exame_paciente', 'hdl');
            TQuickForm::hideField('form_exame_paciente', 'ldl');
            TQuickForm::hideField('form_exame_paciente', 'vldl');
            TQuickForm::hideField('form_exame_paciente', 'lipidios_totais');
            TQuickForm::hideField('form_exame_paciente', 'quilomicrons');

            ##--IONOGRAMA--##
            TQuickForm::hideField('form_exame_paciente', 'sodio');
            TQuickForm::hideField('form_exame_paciente', 'potassio');
            TQuickForm::hideField('form_exame_paciente', 'calcio');
            TQuickForm::hideField('form_exame_paciente', 'magnesio');
            TQuickForm::hideField('form_exame_paciente', 'ion_hidrogenio');
            TQuickForm::hideField('form_exame_paciente', 'cloro');
            TQuickForm::hideField('form_exame_paciente', 'bicarbonato');
            TQuickForm::hideField('form_exame_paciente', 'fosfato');
            TQuickForm::hideField('form_exame_paciente', 'ion_hidroxila');
            TQuickForm::hideField('form_exame_paciente', 'sulfatos_acidos');

             ##--OUTROS--##
            TQuickForm::showField('form_exame_paciente', 'glicose_jejum');
            TQuickForm::showField('form_exame_paciente', 'teste_oral_tolerancia_glicose');
            TQuickForm::showField('form_exame_paciente', 'hemoglobina_glicada');
            TQuickForm::showField('form_exame_paciente', 'ureia');
            TQuickForm::showField('form_exame_paciente', 'creatinina');
            TQuickForm::showField('form_exame_paciente', 'tfg');
            TQuickForm::showField('form_exame_paciente', 'ast_tgo');
            TQuickForm::showField('form_exame_paciente', 'alt_tgp');
            TQuickForm::showField('form_exame_paciente', 'relacao_ast_alt');
            TQuickForm::showField('form_exame_paciente', 'gama_gt');
            TQuickForm::showField('form_exame_paciente', 'fosfatase_alcalina');
            TQuickForm::showField('form_exame_paciente', 'proteina_total');
            TQuickForm::showField('form_exame_paciente', 'albumina');
            TQuickForm::showField('form_exame_paciente', 'globulina');
            TQuickForm::showField('form_exame_paciente', 'reacao_albumina_globulina');
            TQuickForm::showField('form_exame_paciente', 'tranferrina_serica');
            TQuickForm::showField('form_exame_paciente', 'ferritina');  
            TQuickForm::showField('form_exame_paciente', 'ferro');
            TQuickForm::showField('form_exame_paciente', 'bilirrubina_total');
            TQuickForm::showField('form_exame_paciente', 'bilirrubina_direta');
            TQuickForm::showField('form_exame_paciente', 'tempo_protrombinal');
            TQuickForm::showField('form_exame_paciente', 'ttpa');
            TQuickForm::showField('form_exame_paciente', 'citrulina_serica');
            TQuickForm::showField('form_exame_paciente', 'oxalato_urina_24h');
            TQuickForm::showField('form_exame_paciente', 'dlactato');
            TQuickForm::showField('form_exame_paciente', 'dxilose_serica_60m');
            TQuickForm::showField('form_exame_paciente', 'vitaminab12');
            TQuickForm::showField('form_exame_paciente', 'vitaminad');
            TQuickForm::showField('form_exame_paciente', 'cobre');
            TQuickForm::showField('form_exame_paciente', 'zinco');
            TQuickForm::showField('form_exame_paciente', 'pre_albumina');


        }
    }


    public function onReload () {
    }

        
    
}
