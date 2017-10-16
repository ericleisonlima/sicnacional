<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);


class PacienteDetail extends TPage
{
    private $form;
    private $form2;
    private $datagrid;
    private $pageNavigation;
    private $loaded;

    private $framegrid1;
    private $framegrid2;
    private $framegrid3;
    private $framegrid4;
    private $framegrid5;
    private $framegrid6;
    private $framegrid7;


    public function __construct()
    {
        parent::__construct();

       //$redstar = '<font color="red"><b>*</b></font>';

        $this->form = new BootstrapFormBuilder( "form_list_atendimento" );
        $this->form->setFormTitle( "Registro do Paciente" );
        $this->form->class = "tform";

        $this->form2 = new BootstrapFormBuilder( "form_paciente_relatorio" );
        $this->form2->setFormTitle( "Relatorio Paciente" );
        $this->form2->class = "tform";


        $id                     = new THidden( "id" );
        
        //$medico_id        = new THidden( "profissional_id" ); // Deve ser capturado a partir da sessão
        //$paciente_nome          = new TEntry( "paciente_nome" );
        //$dataclassificacao      = new TDate( "dataatendimento" );
        //$exameclinico           = new TText( "exameclinico" );
        //$examescomplementares   = new TText( "examescomplementares" );
        //$diagnosticomedico      = new TText( "diagnosticomedico" );
        //$descricaotratamento    = new TText( "descricaotratamento" );

        $paciente_id            = new THidden( "paciente_id" );
        $paciente_id->setValue(filter_input(INPUT_GET, 'fk'));

        TTransaction::open('dbsic');
        $tempVisita = new PacienteRecord( filter_input( INPUT_GET, 'fk' ) );
        if( $tempVisita ){
            $paciente_nome = new TLabel( $tempVisita->nome );          
            $tiposanguineo = new TLabel( $tempVisita->tiposanguineo );
            $fatorsanguineo = new TLabel( $tempVisita->fatorsanguineo );            
            $municipio = new TLabel( $tempVisita->municipio );         
            $datadiagnostico = new TLabel( TDate::date2br($tempVisita->datadiagnostico) );
            $estabelecimento = new TLabel( $tempVisita->estabelecimento_nome );

            var_dump($tempVisita);
            exit();
 
        }
        TTransaction::close(); 

        $paciente_nome->setSize("23.5%");
        $datadiagnostico->setSize("20%");
        $tiposanguineo->setSize("21%");
        $estabelecimento->setSize("20.5%");

        $fk = filter_input( INPUT_GET, "fk" );
        $did = filter_input( INPUT_GET, "did" );

        //$id->setValue ($fk);

        //$dataclassificacao->setMask( "dd/mm/yyyy h:i:s" );
        //$dataclassificacao->setDatabaseMask("yyyy-mm-dd h:i:s");

        //$dataclassificacao->setValue( date( "d/m/Y h:i:s" ) );
        //$dataclassificacao->setEditable( false );

        // $paciente_nome->forceUpperCase();

        //$dataclassificacao->addValidation( TextFormat::set( "Data da Avaliação" ), new TRequiredValidator );

        $this->form->addFields( [new TLabel('Paciente: '), $paciente_nome, ('Data Diagnostico: '), $datadiagnostico] );
        $this->form->addFields( [new TLabel('Tipo Sanguineo: '), $tiposanguineo, ('Fator Sanguineo: '), $fatorsanguineo] );       
        $this->form->addFields( [new TLabel('Estabelecimento: '), $estabelecimento, ('Municipio: '), $municipio] );

        /*--- frame de Direcionamento ---*/
        $frame = new TFrame;
        $frame->setLegend( "Ações para o Paciente" );
        $frame->style .= ';margin:0%;width:90%';

        $add_button2 = TButton::create("buttondoen", [ $this,"onError" ], null, null);
        $onSaveFrame2 = new TAction( [ 'DoencaBaseDetalhe', "onReload" ] );
        $onSaveFrame2->setParameter( "fk", $fk );
        $onSaveFrame2->setParameter( "did", $did );
        $onSaveFrame2->setParameter( "frm", 1 );
        $add_button2->setAction( $onSaveFrame2 );

        $add_button2->setLabel( "Doença Base" );
        $add_button2->class = 'btn btn-success';
        $add_button2->setImage( "fa:plus white" );

        $add_button3 = TButton::create("buttonparen", [ $this,"onError" ], null, null);
        $onSaveFrame3 = new TAction( [ 'NutricaoParenteralDetalhe', "onReload" ] );
        $onSaveFrame3->setParameter( "fk", $fk );
        $onSaveFrame3->setParameter( "did", $did );
        $onSaveFrame3->setParameter( "frm", 1 );
        $add_button3->setAction( $onSaveFrame3 );

        $add_button3->setLabel( "Nutrição Parenteral" );
        $add_button3->class = 'btn btn-success';
        $add_button3->setImage( "fa:plus white" );

        $add_button4 = TButton::create("buttonente", [ $this,"onError" ], null, null);
        $onSaveFrame4 = new TAction( [ 'NutricaoEnteralFormDetalhe', "onReload" ] );
        $onSaveFrame4->setParameter( "fk", $fk );
        $onSaveFrame4->setParameter( "did", $did );
        $onSaveFrame4->setParameter( "frm", 1 );
        $add_button4->setAction( $onSaveFrame4 );

        $add_button4->setLabel( "Nutrição Enteral" );
        $add_button4->class = 'btn btn-success';
        $add_button4->setImage( "fa:plus white" );

        $add_button5 = TButton::create("buttonanamn", [ $this,"onError" ], null, null);
        $onSaveFrame5 = new TAction( [ 'AnamneseFormDetalhe', "onReload" ] );
        $onSaveFrame5->setParameter( "fk", $fk );
        $onSaveFrame5->setParameter( "did", $did );
        $onSaveFrame5->setParameter( "frm", 1 );
        $add_button5->setAction( $onSaveFrame5 );

        $add_button5->setLabel( "Anamnese" );
        $add_button5->class = 'btn btn-success';
        $add_button5->setImage( "fa:plus white" );

        $add_button6 = TButton::create("buttonmed", [ $this,"onError" ], null, null);
        $onSaveFrame6 = new TAction( [ 'UsoMedicamentoDetalhe', "onReload" ] );
        $onSaveFrame6->setParameter( "fk", $fk );
        $onSaveFrame6->setParameter( "did", $did );
        $onSaveFrame6->setParameter( "frm", 1 );
        $add_button6->setAction( $onSaveFrame6 );

        $add_button6->setLabel( "Medicamento" );
        $add_button6->class = 'btn btn-success';
        $add_button6->setImage( "fa:plus white" );

        $add_button7 = TButton::create("buttonexame", [ $this,"onError" ], null, null);
        $onSaveFrame7 = new TAction( [ 'ExamePacienteDetalhe', "onReload" ] );
        $onSaveFrame7->setParameter( "fk", $fk );
        $onSaveFrame7->setParameter( "did", $did );
        $onSaveFrame7->setParameter( "frm", 1 );
        $add_button7->setAction( $onSaveFrame7 );

        $add_button7->setLabel( "Exame" );
        $add_button7->class = 'btn btn-success';
        $add_button7->setImage( "fa:plus white" );

        $this->form->addField( $add_button2 );
        $this->form->addField( $add_button3 );
        $this->form->addField( $add_button4 );
        $this->form->addField( $add_button5 );
        $this->form->addField( $add_button6 );
        $this->form->addField( $add_button7 );

        $this->form->addContent( [ $frame ] );

        $hbox = new THBox;
        $hbox->add( $add_button2 );
        $hbox->add( $add_button3 );
        $hbox->add( $add_button4 );
        $hbox->add( $add_button5 );
        $hbox->add( $add_button6 );
        $hbox->add( $add_button7 );

        $hbox->style = 'margin: 0%';
        $vbox = new TVBox;
        $vbox->style='width:100%';
        $vbox->add( $hbox );
        $frame->add( $vbox );

        //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

       /*$frame1 = new TFrame;
        $frame1->setLegend( "Doença Base" );
        $frame1->style .= ';margin:0%;width:90%';*/

        $page = new TLabel( "Doença Base", '#7D78B6', 12, 'bi');
        $page->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        $this->form2->appendPage( "Doença Base" );
        $this->form2->addContent( [ $page ] );

        $this->datagrid = new BootstrapDatagridWrapper( new CustomDataGrid() );
        $this->datagrid->datatable = "true";
        $this->datagrid->style = "width: 100%";
        $this->datagrid->setHeight( 320 );


        $column_cidid = new TDataGridColumn( "cid_id", "CID", "left" );
        $column_cid_id_name = new TDataGridColumn( "cid_nome", "Doença", "left" );


        $this->datagrid->addColumn( $column_cidid );
        $this->datagrid->addColumn( $column_cid_id_name );

        $order_cidid = new TAction( [ $this, "onReload" ] );
        $order_cidid->setParameter( "order", "id" );
        $column_cidid->setAction( $order_cidid );

      /*$action_del = new TDataGridAction( [ $this, "onDelete" ] );
        $action_del->setButtonClass( "btn btn-default" );
        $action_del->setLabel( "Deletar" );
        $action_del->setImage( "fa:trash-o red fa-lg" );
        $action_del->setField( "id" );
        $action_del->setParameter('fk', filter_input(INPUT_GET, 'fk'));
        $this->datagrid->addAction( $action_del );*/
       
        $this->datagrid->createModel();

        $vbox1 = new TVBox;
        $vbox1->style='width:100%';
        //$vbox1->add( $hbox1 );
        //$vbox1->add( $this->datagrid );
         $vbox1->add( $this->datagrid ); 
        //$frame1->add( $vbox1 );

        //--------------------------------------------------------------------------------------------------------------------------



        $page2 = new TLabel( "Nutrição Parenteral", '#7D78B6', 12, 'bi');
        $page2->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        $this->form2->appendPage( "Nutrição Parenteral" );
        $this->form2->addContent( [ $page2 ] );

        $this->datagrid = new BootstrapDatagridWrapper( new CustomDataGrid() );
        $this->datagrid->datatable = "true";
        $this->datagrid->style = "width: 100%";
        $this->datagrid->setHeight( 320 );

        $column_name = new TDataGridColumn('paciente_nome', 'Paciente', 'left');
        $column_inicio = new TDataGridColumn('datainicio', 'Início', 'left');
        $column_fim = new TDataGridColumn('datafim', 'Fim', 'left');
        $column_tipoparenteral = new TDataGridColumn('tipoparenteral', 'Tipo Parenteral', 'center');
        $column_tipoparenteraloutros = new TDataGridColumn('tipoparenteraloutros', 'Tipo Parenteral Outros', 'center');
        $column_totalcalorias = new TDataGridColumn('totalcalorias', 'Total Calorias', 'center');
        $column_percentualdiario = new TDataGridColumn('percentualdiario', 'Percentual Diário', 'center');
        $column_volumenpt = new TDataGridColumn('volumenpt', 'Volume NPT', 'left');
        $column_tempoinfusao = new TDataGridColumn('tempoinfusao', 'Tempo Infusão', 'left');
        $column_frequencia = new TDataGridColumn('frequencia', 'frequencia', 'left');
        $column_acessovenosolp = new TDataGridColumn('acessovenosolp', 'acessovenosolp', 'left');
        $column_acessovenosolpqual = new TDataGridColumn('acessovenosolpqual', 'acessovenosolpqual', 'left');
        $column_numerodeacessovenoso = new TDataGridColumn('numerodeacessovenoso', 'numerodeacessovenoso', 'left');
        $column_apresentouinfeccaoacessovenoso = new TDataGridColumn('apresentouinfeccaoacessovenoso', 'apresentouinfeccaoacessovenoso', 'left');
        $column_vezesinfeccaoacessovenoso = new TDataGridColumn('vezesinfeccaoacessovenoso', 'vezesinfeccaoacessovenoso', 'left');

        $this->datagrid->addColumn($column_inicio);
        $this->datagrid->addColumn($column_fim);
        $this->datagrid->addColumn($column_tipoparenteral);
        $this->datagrid->addColumn($column_tipoparenteraloutros);
        $this->datagrid->addColumn($column_totalcalorias);
        $this->datagrid->addColumn($column_percentualdiario);
        $this->datagrid->addColumn($column_percentualdiario);
        $this->datagrid->addColumn($column_acessovenosolp);
        $this->datagrid->addColumn($column_frequencia);
        $this->datagrid->addColumn($column_acessovenosolp);
        $this->datagrid->addColumn($column_acessovenosolpqual);
        $this->datagrid->addColumn($column_numerodeacessovenoso);
        $this->datagrid->addColumn($column_apresentouinfeccaoacessovenoso);
        $this->datagrid->addColumn($column_vezesinfeccaoacessovenoso);

        
       /*$action_edit = new CustomDataGridAction( [ $this, "onEdit" ] );
        $action_edit->setButtonClass( "btn btn-default" );
        $action_edit->setLabel( "Editar" );
        $action_edit->setImage( "fa:pencil-square-o blue fa-lg" );
        $action_edit->setField( "id" );
        $action_edit->setParameter( "fk", $fk );
        $action_edit->setParameter( "did", $did );
        $action_edit->setParameter( "page", $page );
        $this->datagrid->addAction( $action_edit );

        $action_del = new CustomDataGridAction( [ $this, "onDelete" ] );
        $action_del->setButtonClass( "btn btn-default" );
        $action_del->setLabel( "Deletar" );
        $action_del->setImage( "fa:trash-o red fa-lg" );
        $action_del->setField( "id" );
        $action_del->setParameter( "fk", $fk );
        $action_del->setParameter( "did", $did );
        $action_del->setParameter( "page", $page );
        $this->datagrid->addAction( $action_del );*/


        $this->datagrid->createModel();

        $vbox2 = new TVBox;
        $vbox2->style='width:100%';
        //$vbox1->add( $hbox1 );
        $vbox2->add( $this->framegrid2 );
        


        //--------------------------------------------------------------------------------------------------------------------------


        $page3 = new TLabel( "Nutrição Enteral", '#7D78B6', 12, 'bi');
        $page3->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        $this->form2->appendPage( "Nutrição Enteral" );
        $this->form2->addContent( [ $page3 ] );

        $this->datagrid = new BootstrapDatagridWrapper( new CustomDataGrid() );
        $this->datagrid->datatable = "true";
        $this->datagrid->style = "width: 100%";
        $this->datagrid->setHeight( 320 );
        
        $column_name = new TDataGridColumn('paciente_nome', 'Paciente', 'left');
        $column_name2 = new TDataGridColumn('tipo_nutricao_nome', 'Tipo Nutrição', 'left');
        $column_name3 = new TDataGridColumn('administracao_nutricao_nome', 'Administração Nutrição', 'left');
        $column_inicio = new TDataGridColumn('datainicio', 'Início', 'left');
        $column_fim = new TDataGridColumn('datafim', 'Fim', 'left');
        $column_totalcalorias = new TDataGridColumn('totalcalorias', 'Total Calorias', 'left');
        $column_percentualdiario = new TDataGridColumn('percentualdiario', 'Percentual Diario', 'left');
       
        $this->datagrid->addColumn($column_name);
        $this->datagrid->addColumn($column_name2);
        $this->datagrid->addColumn($column_name3);
        $this->datagrid->addColumn($column_inicio);
        $this->datagrid->addColumn($column_fim);
        
        /*$action_edit = new TDataGridAction(array('NutricaoEnteralFormDetalhe', 'onEdit'));
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
        $this->datagrid->addAction($action_del);*/

        $this->datagrid->createModel();

        $vbox3 = new TVBox;
        $vbox3->style='width:100%';
        //$vbox1->add( $hbox1 );
        $vbox3->add( $this->datagrid );
      



        //-------------------------------------------------------------------------------------------------------------------------

        /*$frame4 = new TFrame;
        $frame4->setLegend( "Doença Base" );
        $frame->style .= ';margin:0%;width:90%';*/
 
         $page4 = new TLabel( "Anamnese", '#7D78B6', 12, 'bi');
         $page4->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
         $this->form2->appendPage( "Anamnese" );
         $this->form2->addContent( [ $page4 ] );
 
 
         $this->datagrid = new BootstrapDatagridWrapper( new CustomDataGrid() );
         $this->datagrid->datatable = "true";
         $this->datagrid->style = "width: 100%";
         $this->datagrid->setHeight( 320 );
 
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
         
         /*$edit = new TDataGridAction( [ $this, "onEdit" ] );
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
         $this->datagrid->addAction($del);*/
 
         $this->datagrid->createModel();
 
         $vbox4 = new TVBox;
         $vbox4->style='width:100%';
         //$vbox1->add( $hbox1 );
         $vbox4->add( $this->datagrid );

     
            //---------------------------------------------------------------------------------------------------------------------------
 
         $page5 = new TLabel( "Medicamentos", '#7D78B6', 12, 'bi');
         $page5->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
         $this->form2->appendPage( "Medicamentos" );
         $this->form2->addContent( [ $page5 ] );
 
         $this->datagrid = new BootstrapDatagridWrapper( new CustomDataGrid() );
         $this->datagrid->datatable = "true";
         $this->datagrid->style = "width: 100%";
         $this->datagrid->setHeight( 320 );
 
         
         $column_1 = new TDataGridColumn('paciente_nome', 'Paciente', 'left');
         $column_2 = new TDataGridColumn('datainicio', 'Início', 'left');
         $column_3 = new TDataGridColumn('datafim', 'Fim', 'left');
         $column_4 = new TDataGridColumn('medicamento_nome', 'Medicamento', 'left');
         $column_5 = new TDataGridColumn('administracao_nome', 'Tipo administração', 'left');
         $column_6 = new TDataGridColumn('posologia', 'Posologia', 'left');
         $column_7 = new TDataGridColumn('observacao', 'Observações', 'left');
 
         $this->datagrid->addColumn($column_1);
         $this->datagrid->addColumn($column_2);
         $this->datagrid->addColumn($column_3);
         $this->datagrid->addColumn($column_4);
         $this->datagrid->addColumn($column_5);
         $this->datagrid->addColumn($column_6);
         $this->datagrid->addColumn($column_7);
         
        /* $edit = new TDataGridAction( [ $this, "onEdit" ] );
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
         $this->datagrid->addAction($del);*/
 
         $this->datagrid->createModel();
 
         $vbox5 = new TVBox;
         $vbox5->style='width:100%';
         //$vbox1->add( $hbox1 );
         $vbox5->add( $this->datagrid );
      
         //---------------------------------------------------------------------------------------------------------------------------
 
        
         $page6 = new TLabel( "Exames", '#7D78B6', 12, 'bi');
         $page6->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
         $this->form2->appendPage( "Exames" );
         $this->form2->addContent( [ $page6 ] );
 
 
         $this->datagrid = new BootstrapDatagridWrapper( new CustomDataGrid() );
         $this->datagrid->datatable = "true";
         $this->datagrid->style = "width: 100%";
         $this->datagrid->setHeight( 320 );
 
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
         
         /*$edit = new TDataGridAction( [ $this, "onEdit" ] );
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
         $this->datagrid->addAction($del);*/
 
         $this->datagrid->createModel();
 
         $vbox6 = new TVBox;
         $vbox6->style='width:100%';
         //$vbox1->add( $hbox1 );
         $vbox6->add( $this->datagrid );

        //---------------------------------------------------------------------------------------------------------------------------
        $this->pageNavigation = new TPageNavigation();
        $this->pageNavigation->setAction( new TAction( [ $this, "onReload" ] ) );
        $this->pageNavigation->setWidth( $this->datagrid->getWidth() );

      

        $container = new TVBox();

        $container->style = "width: 100%";  
        $container->add( $this->form ); 
        $container->add( $this->form2 );

       // $container->add( $this->datagrid );

        //$container->add( TPanelGroup::pack( NULL, $this->datagrid ) );



        $container->add( $this->pageNavigation );



        parent::add( $container );

    }

   /* public function onSave( $param = null )
    {
        $object = $this->form->getData( "BauAtendimentoRecord" );

        try {
            $object->profissional_id = TSession::getValue('profissionalid');
            unset( $object->paciente_nome );
            unset( $object->cid_id );

            $this->form->validate();
            TTransaction::open( "database" );

            $object->store();
            TTransaction::close();

            $action = new TAction( [ "AtendimentoDetail", "onReload" ] );
            $action->setParameters( $param );

            new TMessage( "info", "Registro salvo com sucesso!", $action );

        } catch ( Exception $ex ) {

            TTransaction::rollback();
            $this->form->setData( $object );
            new TMessage( "error", "Ocorreu um erro ao tentar salvar o registro!<br><br><br><br>" . $ex->getMessage() );

        }
    }

    public function onEdit( $param = null )
    {
        try {

            if ( isset( $param[ "key" ] ) ) {
                TTransaction::open( "database" );

                $object = new BauAtendimentoRecord( $param[ "key" ] );
                $object->dataatendimento = TDate::date2br($object->dataatendimento) . ' ' . substr($object->dataatendimento, 11, strlen($object->dataatendimento));

                $this->onReload( $param );
                $this->form->setData( $object );
                TTransaction::close();

            }

        } catch ( Exception $ex ) {

            TTransaction::rollback();

            new TMessage( "error", "Ocorreu um erro ao tentar carregar o registro para edição!<br><br>" . $ex->getMessage() );

        }
    }

    public function onDelete( $param = null )
    {
        if( isset( $param[ "key" ] ) ) {

            $param = [
            "key" => $param[ "key" ],
            "fk"  => $param[ "fk" ],
            "did"  => $param[ "did" ]
            ];

            $action1 = new TAction( [ $this, "Delete" ] );
            $action2 = new TAction( [ $this, "onReload" ] );

            $action1->setParameters( $param );
            $action2->setParameters( $param );

            new TQuestion( "Deseja realmente apagar o registro?", $action1, $action2 );
        }
    }

    public function Delete( $param = null )
    {
        try {

            TTransaction::open( "database" );
            $object = new BauAtendimentoRecord( $param[ "key" ] );
            $object->delete();
            TTransaction::close();

            $this->onReload( $param );

            new TMessage( "info", "O Registro foi apagado com sucesso!" );

        } catch ( Exception $ex ) {
            TTransaction::rollback();
            new TMessage( "error", $ex->getMessage() );

        }
    }
*/
    public function onReload( $param )
    {
      /*  try {

            TTransaction::open( "database" );

            $repository = new TRepository( "BauAtendimentoRecord" );

            $properties = [
            "order" => "dataatendimento",
            "direction" => "desc"
            ];

            $limit = 10;

            $criteria = new TCriteria();
            $criteria->setProperties( $properties );
            $criteria->setProperty( "limit", $limit );
            $criteria->add( new TFilter( "bau_id", "=", $param[ "fk" ] ) );

            $objects = $repository->load( $criteria, FALSE );

            if ( isset( $objects ) ) {

                $this->datagrid->clear();

                foreach ( $objects as $object ) {

                    $object->dataatendimento = TDate::date2br($object->dataatendimento) . ' ' . substr($object->dataatendimento, 11, strlen($object->dataatendimento));

                    $this->datagrid->addItem( $object );

                }

            }

            $criteria->resetProperties();

            $count = $repository->count( $criteria );

            $this->pageNavigation->setCount( $count );
            $this->pageNavigation->setProperties( $properties );
            $this->pageNavigation->setLimit( $limit );

            $this->onReloadFrames( $param );
            TTransaction::close();

            $this->loaded = true;

        } catch ( Exception $ex ) {

            TTransaction::rollback();
            new TMessage( "error", $ex->getMessage() );
        }*/
    }

 /*   public function onClear()
    {
        $this->form->clear();
    }

    public function onSaveFrames( $param = null )
    {
        try {

            $object = $this->unSetFields( $param );
            $object->cid_id = key($object->cid_id);

            TTransaction::open( "database" );

            if ( isset( $object ) ) {
                $object->store();
            } else {
                $this->onError();
            }

            TTransaction::close();

            $this->onReload($param);

        } catch ( Exception $ex ) {

            TTransaction::rollback();

            new TMessage( "error", $ex->getMessage() );

        }
    }

    public function onDeleteFrames( $param = null ){
        try {

            TTransaction::open( "database" );


            $object = $this->getFrameItem( $param );

            if ( isset( $object ) ) {
                $object->delete();
            } else {
                $this->onError();
            }

            TTransaction::close();

            $this->onReload( $param );

        } catch ( Exception $ex ) {

            TTransaction::rollback();

            new TMessage( "error", $ex->getMessage() );

        }
    }

    public function onReloadFrames( $param = null )
    {
        try {

            TTransaction::open('database');

            $object = new PacienteRecord( $param[ "did" ] );

            if ( isset( $object ) ) {

                foreach ( $object->getComorbidades() as $comorbidade ) {
                    $this->framegrid1->addItem( $comorbidade );
                }

            }

            TTransaction::close();

        } catch( Exception $ex ) {

            new TMessage( "error", $ex->getMessage() );

        }
    }

    public function unSetFields( $param = null )
    {
        switch ( $param[ "frm" ] ) {

            case 1:

            $object = $this->form->getData( "BauComorbidadesRecord" );
                //unset( $object->medicamento_id );
                //unset( $object->principioativo_id );

            break;

            case 2:

            $object = $this->form->getData( "BauUsoMedicacoesRecord" );
            unset( $object->cid_id );
            unset( $object->principioativo_id );

            break;

            case 3:

            $object = $this->form->getData( "BauAlergiaMedicamentosaRecord" );
            unset( $object->cid_id );
            unset( $object->medicamento_id );

            break;

        }

        if ( isset( $object ) ) {

            unset( $object->id );
            unset( $object->profissional_id );
            unset( $object->paciente_nome );
            unset( $object->dataatendimento );
            unset( $object->exameclinico );
            unset( $object->examescomplementares );
            unset( $object->descricaotratamento );

            return $object;

        } else {

            return null;

        }

    }

    public function getFrameItem( $param = null )
    {
        switch ( $param[ "frm" ] ) {

            case 1:
            $object = new BauComorbidadesRecord( $param[ "key" ] );
            break;

            case 2:
            $object = new BauUsoMedicacoesRecord( $param[ "key" ] );
            break;

            case 3:
            $object = new BauAlergiaMedicamentosaRecord( $param[ "key" ] );
            break;

        }

        return isset( $object ) ? $object : null;
    }
    */

    public function onError()
    {
        $action = new TAction( [ "PacienteDetail", "onReload" ] );

        new TMessage( "error", "Uma instabilidade momentâneo no sistema impediu a ação, tente novamente mais tarde.", $action );
    }
}
