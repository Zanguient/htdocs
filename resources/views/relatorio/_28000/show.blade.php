@extends('master')

@section('titulo')
    {{ (28000 + $dados['INFO'][0]->ID) . ' - ' . $dados['INFO'][0]->NOME }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/12050.css') }}" />
    <link rel="stylesheet" href="{{ elixir('assets/css/28000.css') }}" />
    

    <link rel="stylesheet" href="../build/assets/images/buttons.dataTables.min.css" />

@endsection

@section('conteudo')
   
    <input type="hidden" name="tipo-pagina"     class="tipo-pagina"     value="1">
    <input type="hidden" name="user-relatorio"  class="user-relatorio"  value="{{ ucwords(mb_strtolower(Auth::user()->NOME ? Auth::user()->NOME : Auth::user()->USUARIO)) }}">


    @foreach($dados['INFO'] as $key => $value)

        <input type="hidden" name="titulo-relatorio"        class="titulo-relatorio"        value="{{ (28000 + $value->ID) . ' - ' . $value->NOME }}">

        <input type="hidden" name="relatorio-ID"            class="relatorio-ID"            value="{{$value->ID}}">
        <input type="hidden" name="relatorio-NOME"          class="relatorio-NOME"          value="{{$value->NOME}}">
        <input type="hidden" name="relatorio-DESCRICAO"     class="relatorio-DESCRICAO"     value="{{$value->DESCRICAO}}">
        <input type="hidden" name="relatorio-TIPO"          class="relatorio-TIPO"          value="{{$value->TIPO}}">
        <input type="hidden" name="relatorio-TEMPLATE_ID "  class="relatorio-TEMPLATE_ID "  value="{{$value->TEMPLATE_ID}}">
        <input type="hidden" name="relatorio-STATUS "       class="relatorio-STATUS "       value="{{$value->STATUS}}">
    @endforeach

    @foreach($dados['CONF'] as $key => $value)

        <input type="hidden" name="filtro-relatorio"        class="filtro-relatorio"        value="{{$value->filtro}}">

        <input type="hidden" name="relatorio-relatorio_id"  class="relatorio-relatorio_id"  value="{{$value->relatorio_id}}">
        <input type="hidden" name="relatorio-filtro"        class="relatorio-filtro"        value="{{$value->filtro}}">
        <input type="hidden" name="relatorio-agrupamento"   class="relatorio-agrupamento"   value="{{$value->agrupamento}}">
        <input type="hidden" name="relatorio-agrupar "      class="relatorio-agrupar "      value="{{$value->agrupar}}">
        <input type="hidden" name="relatorio-zebrado "      class="relatorio-zebrado "      value="{{$value->zebrado}}">
        <input type="hidden" name="relatorio-versao "       class="relatorio-versao "       value="{{$value->versao}}">
        <input type="hidden" name="relatorio-cor "          class="relatorio-cor "          value="{{$value->cor}}">
        <input type="hidden" name="relatorio-fonte "        class="relatorio-fonte "        value="{{$value->fonte}}">
        <input type="hidden" name="relatorio-fonteHTML "    class="relatorio-fonteHTML "    value="{{$value->fonteHTML}}">
        <input type="hidden" name="relatorio-totalizador "  class="relatorio-totalizador "  value="{{$value->totalizador}}">
        <input type="hidden" name="relatorio-paisagem "     class="relatorio-paisagem "     value="{{$value->PAISAGEM}}">
    @endforeach
                    
    @foreach($dados['CAMPOS'] as $key => $value)
        <input type="hidden"
        name                ="relatorio-CAMPOS" 
        class               ="relatorio-CAMPOS relatorio-CAMPOS-{{$value->CAMPO}}"
        data-PERCENTUAL     ="{{$value->PERCENTUAL}}"
        data-DESCRICAO      ="{{$value->DESCRICAO}}"
        data-CLASSE         ="{{$value->CLASSE}}"
        data-CAMPO          ="{{$value->CAMPO}}"
        data-ORDEM          ="{{$value->ORDEM}}"
        data-MASCARA        ="{{$value->MASCARA}}"
        data-VISIVEL        ="{{$value->VISIVEL}}"
        data-COR            ="{{$value->COR}}"
        data-TOTALIZAR      ="{{$value->TOTALIZAR}}"
        data-CASAS          ="{{$value->CASAS}}"
        data-INDEX          ="{{$value->INDEX}}"
        data-AGRUP          ="{{$value->AGRUPAR}}"
        data-TAGRUP         ="{{$value->TOTAL_GRUPO}}"
        data-TOTAL_TIPO     ="{{$value->TOTAL_TIPO}}"
        data-FORMULA        ="{{$value->FORMULA}}"
        data-PREFIX         ="{{$value->PREFIX}}"
        data-SUFIX          ="{{$value->SUFIX}}"
        data-TAMANHO        ="{{$value->COL_TAMANHO}}"
        data-LINK           ="{{$value->URL_LINK}}"
        >
    @endforeach


    @foreach($dados['IMPUTS'] as $key => $value)
        <input type="hidden"
        name                ="relatorio-IMPUTS" 
        class               ="relatorio-IMPUTS relatorio-IMPUTS-VALOR-{{$value->PARAMETRO}}"
        data-PARAMETRO      ="{{$value->PARAMETRO}}"
        data-DESCRICAO      ="{{$value->DESCRICAO}}"
        data-TIPO           ="{{$value->TIPO}}"
        >
    @endforeach

    @foreach($dados['SQL'] as $key => $value)
        <input type="hidden"
        name                ="relatorio-SQL" 
        class               ="relatorio-SQL"
        value               ="{{$value->SQL}}"
        >
    @endforeach



	<fieldset class="programacao">
		<legend>.</legend>
        
        

        <button type="button" class="btn btn-xs btn-default" id="filtrar-toggle" data-toggle="collapse" data-target="#relatorio-filtro" aria-expanded="true" aria-controls="programacao-filtro">
			{{ Lang::get('master.filtro-toggle') }}
			<span class="caret"></span>
		</button>
        
        <a href="{{ url('home') }}" class="btn btn-xs btn-default" style="left: 65px;" id="filtrar-toggle">
            <span class="glyphicon glyphicon-chevron-left"></span> 
            Voltar
        </a>

        <imput type="hidden" class="auto-filtro-relatorio" data-valor="{{isset($_GET['AUTO']) ? $_GET['AUTO'] : 0}}" ></imput>
  
		<div id="relatorio-filtro" class="table-filter collapse in" aria-expanded="true">
            <div style="display: inline-flex;">
                @php $adicionado = 0;
                @foreach($dados['IMPUTS'] as $key => $value)

                    @php $val_imput  = isset($_GET[$value->PARAMETRO]) ? $_GET[$value->PARAMETRO] : ''

                    @if($value->PARAMETRO == 'ESTABELECIMENTO')
                        {{-- Estabelecimento --}}
                        @include('admin._11020.include.listar2', [
                            'required'          => 'required',
                            'autofocus'         => 'autofocus',
                            'opcao_selec'       => 'true',
                            'form_group'        => 'true',
                            'class'             => 'relatorio-IMPUTS-'.$value->PARAMETRO
                        ])

                        @php $adicionado = 1;
                    @endif

                    @if($value->PARAMETRO == 'LOCALIZACAO')
                        {{-- Localização --}}
                        @include('estoque._15020.include.listar', [
                            'required'      => 'required',
                            'opcao_selec'   => 'true',
                            'chave'         => '[]',
                            'class'         => 'relatorio-IMPUTS-'.$value->PARAMETRO
                        ])

                        @php $adicionado = 1;
                    @endif

                    @if($value->PARAMETRO == 'FAMILIA')
                        {{-- Familia --}}
                        @include('helper.include.view.consulta',
                            [
                                'label_descricao'   => 'Família:',
                                'obj_consulta'      => 'Produto/include/_27010-familia',
                                'obj_ret'           => ['ID','DESCRICAO'],
                                'campos_sql'        => ['ID','DESCRICAO'],
                                'campos_imputs'     => [['relatorio-IMPUTS-'.$value->PARAMETRO,'ID']],
                                'filtro_sql'        => [
                                                        ['STATUS','1'],  /* {{ Família Ativa   }} */
                                                       ],
                                'campos_tabela'     => [['ID','80'],['DESCRICAO','200']],
                                'campos_titulo'     => ['ID','DESCRIÇÃO'],
                                'class1'            => 'input-medio-extra',
                                'required'          => 'required'
                            ]
                        )

                        @php $adicionado = 1;
                    @endif

                    @if($adicionado == 0)

                        @php $tipo = 'text';

                        @if($value->TIPO == 3)
                            @php $tipo = 'date';
                        @endif

                        <div class="consulta-container">
                            <div class="consulta">
                                <div class="form-group">

                                    <label for="consulta-descricao">{{$value->DESCRICAO}}:</label>
                                    <div class="input-group  ">
                                        <input type="{{$tipo}}" name="'relatorio-IMPUTS-'.$value->PARAMETRO" style="width: 180px !important;" class="input-maior {{'relatorio-IMPUTS-'.$value->PARAMETRO}}"  value="{{$val_imput}}">                                       
                                    </div>

                                </div>
                            </div>
                        </div>

                    @endif

                    @php $adicionado = 0;

                @endforeach
            
                <div class="consulta-container">
                    <div class="consulta">
                        <div class="form-group">

                            <label for="consulta-descricao">.</label>
                            <div class="input-group  ">
                                <button type="button" class="btn btn-info relatorio-filtrar" data-loading-text="Filtrando..." data-toggle="collapse" data-target="#relatorio-filtro" aria-expanded="true" aria-controls="programacao-filtro"><span class="glyphicon glyphicon-filter"></span>Filtrar</button>                                      
                            </div>

                        </div>
                    </div>
                </div>
                
            </div>
        </div>


	</fieldset> 

	<div class="preview-relatorio">
    </div>

    <div id="tblExport" class="" style="display: none;">
    </div>
    
@endsection

@section('script')


    <script src="../build/assets/images/jquery.dataTables.min.js"></script>
    <script src="../build/assets/images/dataTables.buttons.min.js"></script>
    <script src="../build/assets/images/buttons.flash.min.js"></script>
    <script src="../build/assets/images/jszip.min.js"></script>
    <script src="../build/assets/images/pdfmake.min.js"></script>
    <script src="../build/assets/images/vfs_fonts.js"></script>
    <script src="../build/assets/images/buttons.html5.min.js"></script>
    <script src="../build/assets/images/buttons.print.min.js"></script>
    
    <script src="{{ elixir('assets/js/_28000.js') }}"></script>

@append
