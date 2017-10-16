<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_erros', 1);
//error_reporting(E_ALL);


class PacienteDetail extends TPage
{
    private $form1;
    private $form2;
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

        $this->form1 = new BootstrapFormBuilder( "form_list_atendimento" );
        $this->form1->setFormTitle( "Registro do Paciente" );
        $this->form1->class = "tform";

        $this->form2 = new BootstrapFormBuilder( "form_paciente_relatorio" );
        $this->form2->setFormTitle( "Relatorios do Paciente" );
        $this->form2->class = "tform";


        $id          = new THidden( "id" );
        $paciente_id = new THidden( "paciente_id" );
        $paciente_id->setValue(filter_input(INPUT_GET, 'fk'));

        TTransaction::open('dbsic');
        $tempVisita = new vw_PacienteEstabelecimentoMedicoRecord( filter_input( INPUT_GET, 'fk' ) );
        if( $tempVisita ){
            $paciente_nome = new TLabel( $tempVisita->paciente );          
            $tiposanguineo = new TLabel( $tempVisita->tipo_sanguineo );
            $fatorsanguineo = new TLabel( $tempVisita->fator_sanguineo );            
            $municipio = new TLabel( $tempVisita->municipio );         
            $datadiagnostico = new TLabel( TDate::date2br($tempVisita->data_diagnostico) );
            $estabelecimento = new TLabel( $tempVisita->estabelecimento);

        }
        TTransaction::close(); 

        $paciente_nome->setSize("25%");
        $datadiagnostico->setSize("20%");
        $tiposanguineo->setSize("21%");
        $estabelecimento->setSize("20.5%");

        $fk = filter_input( INPUT_GET, "fk" );
        $did = filter_input( INPUT_GET, "did" );

        $this->form1->addFields( [new TLabel('Paciente: '), $paciente_nome, ('Data Diagnostico: '), $datadiagnostico] );
        $this->form1->addFields( [new TLabel('Tipo Sanguineo: '), $tiposanguineo, ('Fator Sanguineo: '), $fatorsanguineo] );       
        $this->form1->addFields( [new TLabel('Estabelecimento: '), $estabelecimento, ('Municipio: '), $municipio] );

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

        $this->form1->addField( $add_button2 );
        $this->form1->addField( $add_button3 );
        $this->form1->addField( $add_button4 );
        $this->form1->addField( $add_button5 );
        $this->form1->addField( $add_button6 );
        $this->form1->addField( $add_button7 );

        $this->form1->addContent( [ $frame ] );

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

        //-----------------------------------------------------------------------------------------------------------------

        $page1 = new TLabel( "Doença Base", '#7D78B6', 12, 'bi');
        $page1->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        $this->form2->appendPage( "Doença Base" );
        $this->form2->addContent( [ $page1 ] );

        $this->framegrid1 = new BootstrapDatagridWrapper( new CustomDataGrid() );
        $this->framegrid1->datatable = "true";
        $this->framegrid1->style = "width: 100%";
        $this->framegrid1->setHeight( 320 );

        $column_cidid = new TDataGridColumn( "cid_id", "CID", "left" );
        $column_cid_id_name = new TDataGridColumn( "cid_nome", "Doença", "left" );

        $this->framegrid1->addColumn( $column_cidid );
        $this->framegrid1->addColumn( $column_cid_id_name );

        $this->framegrid1->createModel();

        $this->form2->addContent( [ $this->framegrid1 ] );

        //-----------------------------------------------------------------------------------------------------------------

        $page2 = new TLabel( "Nutrição Parenteral", '#7D78B6', 12, 'bi');
        $page2->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        $this->form2->appendPage( "Nutrição Parenteral" );
        $this->form2->addContent( [ $page2 ] );

        $this->framegrid2 = new BootstrapDatagridWrapper( new CustomDataGrid() );
        $this->framegrid2->datatable = "true";
        $this->framegrid2->style = "width: 100%";
        $this->framegrid2->setHeight( 320 );

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

        $this->framegrid2->addColumn($column_inicio);
        $this->framegrid2->addColumn($column_fim);
        $this->framegrid2->addColumn($column_tipoparenteral);
        $this->framegrid2->addColumn($column_tipoparenteraloutros);
        $this->framegrid2->addColumn($column_totalcalorias);
        $this->framegrid2->addColumn($column_percentualdiario);
        $this->framegrid2->addColumn($column_percentualdiario);
        $this->framegrid2->addColumn($column_acessovenosolp);
        $this->framegrid2->addColumn($column_frequencia);
        $this->framegrid2->addColumn($column_acessovenosolp);
        $this->framegrid2->addColumn($column_acessovenosolpqual);
        $this->framegrid2->addColumn($column_numerodeacessovenoso);
        $this->framegrid2->addColumn($column_apresentouinfeccaoacessovenoso);
        $this->framegrid2->addColumn($column_vezesinfeccaoacessovenoso);

        $this->framegrid2->createModel();

        $this->form2->addContent( [ $this->framegrid2 ] );

        //-----------------------------------------------------------------------------------------------------------------

        $page3 = new TLabel( "Nutrição Enteral", '#7D78B6', 12, 'bi');
        $page3->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        $this->form2->appendPage( "Nutrição Enteral" );
        $this->form2->addContent( [ $page3 ] );

        $this->framegrid3 = new BootstrapDatagridWrapper( new CustomDataGrid() );
        $this->framegrid3->datatable = "true";
        $this->framegrid3->style = "width: 100%";
        $this->framegrid3->setHeight( 320 );

        $column_name = new TDataGridColumn('paciente_nome', 'Paciente', 'left');
        $column_name2 = new TDataGridColumn('tipo_nutricao_nome', 'Tipo Nutrição', 'left');
        $column_name3 = new TDataGridColumn('administracao_nutricao_nome', 'Administração Nutrição', 'left');
        $column_inicio = new TDataGridColumn('datainicio', 'Início', 'left');
        $column_fim = new TDataGridColumn('datafim', 'Fim', 'left');
        $column_totalcalorias = new TDataGridColumn('totalcalorias', 'Total Calorias', 'left');
        $column_percentualdiario = new TDataGridColumn('percentualdiario', 'Percentual Diario', 'left');

        $this->framegrid3->addColumn($column_name);
        $this->framegrid3->addColumn($column_name2);
        $this->framegrid3->addColumn($column_name3);
        $this->framegrid3->addColumn($column_inicio);
        $this->framegrid3->addColumn($column_fim);

        $this->framegrid3->createModel();

        $this->form2->addContent( [ $this->framegrid3 ] );

        //-----------------------------------------------------------------------------------------------------------------

        $page4 = new TLabel( "Anamnese", '#7D78B6', 12, 'bi');
        $page4->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        $this->form2->appendPage( "Anamnese" );
        $this->form2->addContent( [ $page4 ] );

        $this->framegrid4 = new BootstrapDatagridWrapper( new CustomDataGrid() );
        $this->framegrid4->datatable = "true";
        $this->framegrid4->style = "width: 100%";
        $this->framegrid4->setHeight( 320 );

        $column_name = new TDataGridColumn('paciente_nome', 'Paciente', 'left');
        $column_peso = new TDataGridColumn('peso', 'Peso ', 'left');
        $column_comprintdel = new TDataGridColumn('comprimentointestinodelgado', ' Comprimento do Intestino Delgado', 'left');
        $column_estomia = new TDataGridColumn('estomia', 'Estomia', 'left');
        $column_transplantado = new TDataGridColumn('transplantado', 'Transplantado', 'left');

        $this->framegrid4->addColumn($column_name);
        $this->framegrid4->addColumn($column_peso);
        $this->framegrid4->addColumn($column_comprintdel);
        $this->framegrid4->addColumn($column_estomia);
        $this->framegrid4->addColumn($column_transplantado);

        $this->framegrid4->createModel();

        $this->form2->addContent( [ $this->framegrid4 ] );

        //-----------------------------------------------------------------------------------------------------------------

        $page5 = new TLabel( "Medicamento", '#7D78B6', 12, 'bi');
        $page5->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        $this->form2->appendPage( "Medicamento" );
        $this->form2->addContent( [ $page5 ] );

        $this->framegrid5 = new BootstrapDatagridWrapper( new CustomDataGrid() );
        $this->framegrid5->datatable = "true";
        $this->framegrid5->style = "width: 100%";
        $this->framegrid5->setHeight( 320 );

        $column_1 = new TDataGridColumn('paciente_nome', 'Paciente', 'left');
        $column_2 = new TDataGridColumn('datainicio', 'Início', 'left');
        $column_3 = new TDataGridColumn('datafim', 'Fim', 'left');
        $column_4 = new TDataGridColumn('medicamento_nome', 'Medicamento', 'left');
        $column_5 = new TDataGridColumn('administracao_nome', 'Tipo administração', 'left');
        $column_6 = new TDataGridColumn('posologia', 'Posologia', 'left');
        $column_7 = new TDataGridColumn('observacao', 'Observações', 'left');

        $this->framegrid5->addColumn($column_1);
        $this->framegrid5->addColumn($column_2);
        $this->framegrid5->addColumn($column_3);
        $this->framegrid5->addColumn($column_4);
        $this->framegrid5->addColumn($column_5);
        $this->framegrid5->addColumn($column_6);
        $this->framegrid5->addColumn($column_7);
        
        $this->framegrid5->createModel();

        $this->form2->addContent( [ $this->framegrid5 ] );

        //-----------------------------------------------------------------------------------------------------------------
        $page6 = new TLabel( "Exame", '#7D78B6', 12, 'bi');
        $page6->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        $this->form2->appendPage( "Exame" );
        $this->form2->addContent( [ $page6 ] );

        $this->framegrid6 = new BootstrapDatagridWrapper( new CustomDataGrid() );
        $this->framegrid6->datatable = "true";
        $this->framegrid6->style = "width: 100%";
        $this->framegrid6->setHeight( 320 );

        $column_1 = new TDataGridColumn('paciente_nome', 'Paciente', 'left');
        $column_2 = new TDataGridColumn('exame_nome', 'Exame', 'left');
        $column_3 = new TDataGridColumn('valor', 'Valor', 'left');
        $column_4 = new TDataGridColumn('dataexame', 'Data do Exame', 'left');

        $this->framegrid6->addColumn($column_1);
        $this->framegrid6->addColumn($column_2);
        $this->framegrid6->addColumn($column_3);
        $this->framegrid6->addColumn($column_4);
        
        
        $this->framegrid6->createModel();

        $this->form2->addContent( [ $this->framegrid6 ] );

        //-----------------------------------------------------------------------------------------------------------------

        $container = new TVBox();
        $container->style = "width: 100%";  
        $container->add( $this->form1 ); 
        $container->add( $this->form2 );
        
        parent::add( $container );

        TScript::create("
            $( document ).ready(function() {
                $('#form_list_atendimento').find('#tab_0').attr('id', 'tab_1000');    
            });
            ");

    }

/*

    public function onEdit( $param = null )
    {
        try {

            if ( isset( $param[ "key" ] ) ) {
                TTransaction::open( "database" );

                $object = new BauAtendimentoRecord( $param[ "key" ] );
                $object->dataatendimento = TDate::date2br($object->dataatendimento) . ' ' . substr($object->dataatendimento, 11, strlen($object->dataatendimento));

                $this->onReload( $param );
                $this->form1->setData( $object );
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
    public function onDoencaBase( $param )
    {
        try
        {

            TTransaction::open( "dbsic" );


            $repository = new TRepository( "DoencaBaseRecord" );

            if ( empty( $param[ "order" ] ) )
            {
                $param[ "order" ] = "id";
                $param[ "direction" ] = "asc";
            }
            $limit = 10;


            $criteria = new TCriteria();
            $criteria->add(new TFilter('paciente_id', '=', filter_input( INPUT_GET, "fk") ) );  
            $criteria->setProperties( $param );
            $criteria->setProperty( "limit", $limit );

            $objects = $repository->load( $criteria, FALSE );

            $this->framegrid1->clear();


            if ( !empty( $objects ) )
            {
                foreach ( $objects as $object )
                {
                    $this->framegrid1->addItem( $object );
                }
            }
            $criteria->resetProperties();

            $count = $repository->count($criteria);
            //$this->pageNavigation->setCount($count);
            //$this->pageNavigation->setProperties($param);
            //$this->pageNavigation->setLimit($limit);

            TTransaction::close();
            $this->loaded = true;
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            new TMessage( "error", $ex->getMessage() );
        }
    }


    public function onNutricaoParenteral ($param) {

         try
        {


          TTransaction::open( "dbsic" );


            $repository = new TRepository( "NutricaoParenteralRecord" );

            if ( empty( $param[ "order" ] ) )
            {
                $param[ "order" ] = "id";
                $param[ "direction" ] = "asc";
            }
            $limit = 10;


            $criteria = new TCriteria();
            $criteria->add(new TFilter('paciente_id', '=', filter_input( INPUT_GET, "fk") ) );  
            $criteria->setProperties( $param );
            $criteria->setProperty( "limit", $limit );

            $objects = $repository->load( $criteria, FALSE );

            $this->framegrid2->clear();


            if ( !empty( $objects ) )
            {
                foreach ( $objects as $object )
                {
                    $this->framegrid2->addItem( $object );
                }
            }
            $criteria->resetProperties();

            $count = $repository->count($criteria);
            //$this->pageNavigation->setCount($count);
            //$this->pageNavigation->setProperties($param);
            //$this->pageNavigation->setLimit($limit);

            TTransaction::close();
            $this->loaded = true;
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            new TMessage( "error", $ex->getMessage() );
        }

    }

    public function onNutricaoEnteral ($param) {

         try
        {


          TTransaction::open( "dbsic" );


            $repository = new TRepository( "NutricaoEnteralRecord" );

            if ( empty( $param[ "order" ] ) )
            {
                $param[ "order" ] = "id";
                $param[ "direction" ] = "asc";
            }
            $limit = 10;


            $criteria = new TCriteria();
            $criteria->add(new TFilter('paciente_id', '=', filter_input( INPUT_GET, "fk") ) );  
            $criteria->setProperties( $param );
            $criteria->setProperty( "limit", $limit );

            $objects = $repository->load( $criteria, FALSE );

            $this->framegrid3->clear();


            if ( !empty( $objects ) )
            {
                foreach ( $objects as $object )
                {
                    $this->framegrid3->addItem( $object );
                }
            }
            $criteria->resetProperties();

            $count = $repository->count($criteria);
            //$this->pageNavigation->setCount($count);
            //$this->pageNavigation->setProperties($param);
            //$this->pageNavigation->setLimit($limit);

            TTransaction::close();
            $this->loaded = true;
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            new TMessage( "error", $ex->getMessage() );
        }

    }

    public function onAnamnese ($param) {

         try
        {


          TTransaction::open( "dbsic" );


            $repository = new TRepository( "AnamneseRecord" );

            if ( empty( $param[ "order" ] ) )
            {
                $param[ "order" ] = "id";
                $param[ "direction" ] = "asc";
            }
            $limit = 10;


            $criteria = new TCriteria();
            $criteria->add(new TFilter('paciente_id', '=', filter_input( INPUT_GET, "fk") ) );  
            $criteria->setProperties( $param );
            $criteria->setProperty( "limit", $limit );

            $objects = $repository->load( $criteria, FALSE );

            $this->framegrid4->clear();


            if ( !empty( $objects ) )
            {
                foreach ( $objects as $object )
                {
                    $this->framegrid4->addItem( $object );
                }
            }
            $criteria->resetProperties();

            $count = $repository->count($criteria);
            //$this->pageNavigation->setCount($count);
            //$this->pageNavigation->setProperties($param);
            //$this->pageNavigation->setLimit($limit);

            TTransaction::close();
            $this->loaded = true;
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            new TMessage( "error", $ex->getMessage() );
        }

    }

      public function onMedicamento ($param) {

         try
        {


          TTransaction::open( "dbsic" );


            $repository = new TRepository( "UsoMedicamentoRecord" );

            if ( empty( $param[ "order" ] ) )
            {
                $param[ "order" ] = "id";
                $param[ "direction" ] = "asc";
            }
            $limit = 10;


            $criteria = new TCriteria();
            $criteria->add(new TFilter('paciente_id', '=', filter_input( INPUT_GET, "fk") ) );  
            $criteria->setProperties( $param );
            $criteria->setProperty( "limit", $limit );

            $objects = $repository->load( $criteria, FALSE );

            $this->framegrid5->clear();


            if ( !empty( $objects ) )
            {
                foreach ( $objects as $object )
                {
                    $this->framegrid5->addItem( $object );
                }
            }
            $criteria->resetProperties();

            $count = $repository->count($criteria);
            //$this->pageNavigation->setCount($count);
            //$this->pageNavigation->setProperties($param);
            //$this->pageNavigation->setLimit($limit);

            TTransaction::close();
            $this->loaded = true;
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            new TMessage( "error", $ex->getMessage() );
        }

    }

    public function onExame ($param) {

         try
        {


          TTransaction::open( "dbsic" );


            $repository = new TRepository( "ExamePacienteRecord" );

            if ( empty( $param[ "order" ] ) )
            {
                $param[ "order" ] = "id";
                $param[ "direction" ] = "asc";
            }
            $limit = 10;


            $criteria = new TCriteria();
            $criteria->add(new TFilter('paciente_id', '=', filter_input( INPUT_GET, "fk") ) );  
            $criteria->setProperties( $param );
            $criteria->setProperty( "limit", $limit );

            $objects = $repository->load( $criteria, FALSE );

            $this->framegrid6->clear();


            if ( !empty( $objects ) )
            {
                foreach ( $objects as $object )
                {
                    $this->framegrid6->addItem( $object );
                }
            }
            $criteria->resetProperties();

            $count = $repository->count($criteria);
            //$this->pageNavigation->setCount($count);
            //$this->pageNavigation->setProperties($param);
            //$this->pageNavigation->setLimit($limit);

            TTransaction::close();
            $this->loaded = true;
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();
            new TMessage( "error", $ex->getMessage() );
        }

    }

    public function onReload ($param) {
       
        $this->onDoencaBase($param);
        $this->onNutricaoParenteral($param);
        $this->onNutricaoEnteral($param);
        $this->onAnamnese($param);
        $this->onMedicamento($param);        
        $this->onExame($param);
        


     

    }





 /*   public function onClear()
    {
        $this->form1->clear();
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

            $object = $this->form1->getData( "BauComorbidadesRecord" );
                //unset( $object->medicamento_id );
                //unset( $object->principioativo_id );

            break;

            case 2:

            $object = $this->form1->getData( "BauUsoMedicacoesRecord" );
            unset( $object->cid_id );
            unset( $object->principioativo_id );

            break;

            case 3:

            $object = $this->form1->getData( "BauAlergiaMedicamentosaRecord" );
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
