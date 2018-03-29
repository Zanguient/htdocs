@extends('master')

@section('titulo')
    {{ Lang::get('vendas/_12050.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/12050.css') }}" />
@endsection

@section('conteudo')

<div id="AppCtrl" ng-controller="Ctrl as vm" ng-cloak style="margin-top: 14px;">

    <form class="form-inline">
    	
    	<fieldset class="programacao">
    		<legend>.</legend>
            
            <button type="button" class="btn btn-xs btn-default" id="filtrar-toggle" data-toggle="collapse" data-target="#relatorio-filtro" aria-expanded="true" aria-controls="programacao-filtro">
    			{{ Lang::get('master.filtro-toggle') }}
    			<span class="caret"></span>
    		</button>
      
    		<div id="relatorio-filtro" class="table-filter collapse in" aria-expanded="true">
                      
                {{-- Estabelecimento --}}
                @include('admin._11020.include.listar2', [
                    'required'			=> 'required',
                    'autofocus'			=> 'autofocus',
                    'opcao_selec'		=> 'true',
                    'form_group'        => 'true',
                ])

                {{-- Familia --}}
                @include('helper.include.view.consulta',
                    [
                        'label_descricao'   => 'Família:',
                        'obj_consulta'      => 'Produto/include/_27010-familia',
                        'obj_ret'           => ['ID','DESCRICAO'],
                        'campos_sql'        => ['ID','DESCRICAO'],
                        'campos_imputs'     => [['_familia_id','ID'],['_familia_descricao','DESCRICAO']],
                        'filtro_sql'        => [
                                                ['STATUS','1'],       /* {{ Família Ativa   }} */
                                                ['TIPOPRODUTO_ID',2], /* {{ Produto Acabado }} */
                                               ],
                        'campos_tabela'     => [['ID','80'],['DESCRICAO','200']],
                        'campos_titulo'     => ['ID','DESCRIÇÃO'],
                        'class1'            => 'input-medio-extra',
                        'required'		    => 'required'
                    ]
                )

                @php $data_inicio = date("Y-m-").'01';
                @php $data_fim    = date("Y-m-d");

                <div class="form-group filtro-periodo">
                    <label>Período:</label>
                    <input type="date" class="form-control data-ini" value="{{ $data_inicio }}">
                    <label class="periodo-a">à</label>
                    <input type="date" class="form-control data-fim" value="{{ $data_fim }}">
                </div>

                <div class="form-group filtro-periodo filtro-tipo">
                    <label>Período dos pedidos:</label>
                    <div class="div-chec-tipo">
                        <label><input class="checkbox-perildo checkbox-perildod" type="checkbox" value="1" checked="">Data de Emissão</label>
                        <label><input class="checkbox-perildo checkbox-perildop" type="checkbox" value="2" >Previsão de Faturamento</label>
                    </div>
                </div>
               
               <button type="button" class="btn btn-primary" id="btn-imprimir">
                    <span class="glyphicon glyphicon-filter"></span>
                    {{ Lang::get('master.filtrar') }}
                </button> 
            </div>


    	</fieldset> 
        
        <div class="tabela-relatorio-titulo"></div>
        <div class="tabela-relatorio"></div>
    </form>

    @include('vendas._12050.show.detalharDefeito')

</div>


@include('helper.include.view.pdf-imprimir')

@endsection

@section('script')
    <script src="{{ asset('assets/js/loader.js') }}"></script>
    <script src="{{ elixir('assets/js/data-table.js') }}"></script>
    <script src="{{ elixir('assets/js/_12050.js') }}"></script>
    <script src="{{ elixir('assets/js/_12050.app.js') }}"></script>
@append
