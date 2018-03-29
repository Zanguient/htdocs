@extends('master')

@section('titulo')
    {{ Lang::get('admin/_11150.titulo') }}
@endsection

@section('estilo')
	<link rel="stylesheet" href="{{ elixir('assets/css/26010.css') }}"> 
    <link rel="stylesheet" href="{{ elixir('assets/css/11150.css') }}" />
    <link rel="stylesheet" href="{{ elixir('assets/css/codemirror.css') }}" />
@endsection

@section('conteudo')

<div id="divgeral" ng-controller="Ctrl as vm" ng-cloak>
	
	<ul class="list-inline acoes">
		<li>
			<button ng-if="vm.user.ADICIONAR > -1" disabled ng-click="vm.Acoes.addCaso()" ng-disabled="vm.btnAlterar.disabled == true || vm.user.ADICIONAR == 0" type="submit" class="btn btn-primary" data-hotkey="f6">
				<span class="glyphicon glyphicon-plus"></span> 
				Incluir
			</button>
		</li>
		<li>
			<a href="{{ url('/') }}" class="btn btn-default btn-voltar" data-hotkey="f11">
				<span class="glyphicon glyphicon-chevron-left"></span>
				Voltar
			</a>
		</li>

		<li>
			<button 
				type="button" 
				class="btn btn-sm btn-default" 
				ng-click="vm.getCasos(1)">
				<span class="glyphicon glyphicon-refresh"></span>
				Atualizar
			</button>
		</li>
	</ul>

	<div class="pesquisa-obj-container">
		<div class="input-group input-group-filtro-obj">
			<input ng-Keydown="vm.filterCaso($event,null)" type="search" ng-model="vm.filtroCaso" name="filtro_obj" class="form-control pesquisa filtro-obj" placeholder="Pesquise..." autocomplete="off" autofocus="">
			<button type="button" class="input-group-addon btn-filtro btn-filtro-obj btn-pesquisar">
				<span class="fa fa-search"></span>
			</button>
			<span style="float: right;margin-top: -24px;z-index: 2;margin-right: 33px;" class="glyphicon glyphicon-info-sign" title="Para pesquisar um caso que não esta na lista digite o número do caso e pressione 'ENTER'"></span>
			
		</div>
	</div>

	<input type="hidden" class="_painel_id" value="{{$painel_id}}">
	<input type="hidden" class="_caso_id" value="{{$caso_id}}">

	<ul id="tab" class="nav nav-tabs" role="tablist"> 
		<li role="presentation" class="active tab-detalhamento"  ng-click="vm.getCasos(1)">
            <a href="#tab-tabela-container" id="tab-tabela" role="tab" data-toggle="tab" aria-controls="tab-tabela-container" aria-expanded="false">
                @php // Casos (<span style="text-decoration: underline;">T</span>abela) Qtd:@{{vm.qtd_casos}}
                Casos
            </a>
        </li>
        <li role="presentation" class="tab-detalhamento" style="display: none;">
            <a href="#tab-acordeon-container" id="tab-acordeon" role="tab" data-toggle="tab" aria-controls="tab-acordeon-container" aria-expanded="false">
                Casos (Aco<span style="text-decoration: underline;">r</span>deon) Qtd:@{{vm.qtd_casos}}
            </a>
        </li>
        <li role="presentation" class="tab-detalhamento" ng-click="vm.getCasos(2)">
            <a href="#tab-tabela2-container" id="tab-tabela2" role="tab" data-toggle="tab" aria-controls="tab-tabela2-container" aria-expanded="false">
                Casos Fechados
            </a>
        </li>
        <li role="presentation" class="tab-detalhamento" ng-click="vm.PrepararFiltro()">
            <a href="#tab-tabela3-container" id="tab-tabela3" role="tab" data-toggle="tab" aria-controls="tab-tabela3-container" aria-expanded="false">
                Consultar Casos
            </a>
        </li>
    </ul>

<div role="tabpanel" class="tab-pane fade  active in" id="tab-tabela-container" aria-labelledby="tab-tabela">
	<div style="max-height: calc(100vh - 186px);" class="table-ec">
	    <div class="scroll-table">
	        <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
	            <thead>
		            <tr>
		            	<th ng-click="vm.TratarOrdem('STATUS')"><span style="display: inline-flex;"> - <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'STATUS'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-STATUS'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		            	<th ng-click="vm.TratarOrdem('NOTIFICACOES')"><span style="display: inline-flex;" title="Quantidade de Lembretes"  ><span class="glyphicon glyphicon-info-sign"></span><span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'NOTIFICACOES'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-NOTIFICACOES'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		            	<th ng-click="vm.TratarOrdem('STATUS')"><span style="display: inline-flex;">Status <span style="margin-left: 5px;margin-right: -5px;" ng-if="vm.ordem == 'STATUS'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-STATUS'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		                <th ng-click="vm.TratarOrdem('CODIGO')"><span style="display: inline-flex;">Caso <span style="margin-left: 5px; margin-right: -5px;" ng-if="vm.ordem == 'CODIGO'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-CODIGO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		                <th ng-click="vm.TratarOrdem('TIPO')"><span style="display: inline-flex;">Tipo <span style="margin-left: 5px; margin-right: -5px;" ng-if="vm.ordem == 'TIPO'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-TIPO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		                <th ng-click="vm.TratarOrdem('ORIGEM')"><span style="display: inline-flex;">Tipo Origem <span style="margin-left: 5px; margin-right: -5px;" ng-if="vm.ordem == 'ORIGEM'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-ORIGEM'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>  
		                <th ng-click="vm.TratarOrdem('DATAHORA_REGISTRO')"><span style="display: inline-flex;">Data de Abertura <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == 'DATAHORA_REGISTRO'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-DATAHORA_REGISTRO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th> 

		                <th ng-repeat="iten in vm.conf['CAMPO'] track by $index" class="wid-cliente" ng-click="vm.TratarOrdem('C' + $index)"><span style="display: inline-flex;">@{{ iten.DESCRICAO }} <span style="margin-left: 5px ; margin-right: -5px;" ng-if="vm.ordem == 'C' + $index" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; margin-right: -5px;" ng-if="vm.ordem == '-C' + $index" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th> 

		            </tr>
		        </thead>
	            <tbody>
	                <tr ng-Keydown="vm.Acoes.openCaso(item.ID,$event)" class="caso_iten_@{{item.ID}}" tabindex="0" ng-repeat="item in vm.lista = (vm.casos | filter:vm.filtroCaso | orderBy:vm.ordem)" ng-click="vm.Acoes.openCaso(item.ID,null)">
	                  <td ><div class="div-status" style="background-color: @{{ item.COR}};"></div></td>
	                  <td auto-title >@{{ item.NOTIFICACOES }}</td>
	                  <td auto-title >@{{ item.STATUS }}</td>
	                  <td auto-title >@{{ item.CODIGO}}</td>
	                  <td auto-title >@{{ item.TIPO }}</td>
	                  <td auto-title >@{{ item.ORIGEM }}</td>
	                  <td auto-title >@{{ item.DATAHORA_REGISTRO }}</td>

	                  <td ng-repeat="iten in vm.conf['CAMPO'] track by $index" auto-title class="wid-cliente">@{{ item['C'+$index] }}</td> 

	                </tr>               
	            </tbody>
	        </table>
	    </div>
	</div>
</div>

<div role="tabpanel" class="tab-pane fade" id="tab-acordeon-container" aria-labelledby="tab-acordeon">
	<fieldset>
	    <section class="chamados">
	        <div class="panel-group accordion" id="accordion" role="tablist" aria-multiselectable="true">

	            <div ng-repeat="status in vm.status track by $index" class="panel panel-default" style="border-left: 3px solid @{{ status.COR }};">

	                <div class="panel-heading panel-accordion-heading" role="tab" id="heading-status@{{ $index }}">
	                    <a style="color: @{{ status.COR }};" role="button" data-toggle="collapse" href="#collapse-status@{{ $index }}" data-parent="#accordion-caso" aria-controls="collapse-status@{{ $index }}">
	                        @{{ status.DESCRICAO }} - @{{ status.ABERTOS }}
	                    </a>
	                </div>

	                <div class="panel-collapse collapse" id="collapse-status@{{ $index }}" role="tabpanel" aria-labelledby="heading-status@{{ $index }}">
	                    <div class="panel-body">

	                    	<div ng-if="status.ID == iten.STATUS_ID" ng-repeat="iten in vm.casos  | filter:vm.filtroCaso as results" class="panel panel-default panel-item" style="border-left: 3px solid @{{ status.COR }};">
				                <div class="panel-heading panel-accordion-heading" role="tab" id="heading@{{ iten.ID }}">
				                    <a style="color: @{{ status.COR }}; width: 100%;display: inline-flex;" role="button" data-toggle="collapse" href="#collapse@{{ iten.ID }}" data-parent="#accordion-caso" aria-controls="collapse@{{ iten.ID }}">
				                        @{{ iten.ID }} - @{{ iten.C0 }}
				                    </a>
				                    <span style="display: inline-flex;float: right;padding: 5px;font-size: 20px;margin-top: -36px;background-color: @{{ status.COR }};border-radius: 25px;margin-right: 5px; cursor: pointer;" ng-click="vm.Acoes.openCaso(iten.ID,null)" class="glyphicon glyphicon-new-window" aria-hidden="true"></span>
				                </div>
				                
				                <div style="display: none;">
				                	@{{ vm.qtd_casos = results.length }}
				                </div>

				                <div class="panel-collapse collapse" id="collapse@{{ iten.ID }}" role="tabpanel" aria-labelledby="heading@{{ iten.ID }}">
				                    <div class="panel-body">

				                    	<div class="itens-inputs ng-scope">
					                    	<div class="form-group">
						                    	<label>Tipo:</label>
						                    	<input
						                    		type="text"
						                    		name="titulo"
						                    		class="form-control input-maior"
						                    		value="@{{ iten.TIPO }}"
						                    		autocomplete="off"
						                    		disabled="disabled">
					                    	</div>
				                    	</div>

				                    	<div class="itens-inputs ng-scope">
					                    	<div class="form-group">
						                    	<label>Tipo Origem:</label>
						                    	<input
						                    		type="text"
						                    		name="titulo"
						                    		class="form-control input-maior"
						                    		value="@{{ iten.ORIGEM }}"
						                    		autocomplete="off"
						                    		disabled="disabled">
					                    	</div>
				                    	</div>

				                    	<div class="itens-inputs ng-scope">
					                    	<div class="form-group">
						                    	<label>Data de Abertura:</label>
						                    	<input
						                    		type="text"
						                    		name="titulo"
						                    		class="form-control input-maior"
						                    		value="@{{ iten.DATAHORA_REGISTRO }}"
						                    		autocomplete="off"
						                    		disabled="disabled">
					                    	</div>
				                    	</div>
										
										<div class="corpo-caso-@{{iten.ID}}">

				                        </div>  
				                    </div>
				                </div>
				            </div>
	                    </div>
	                </div>
	            </div>	
	        </div>            
	    </section>
	</fieldset>
</div>

<div role="tabpanel" class="tab-pane fade" id="tab-tabela2-container" aria-labelledby="tab-tabela2">
	<div style="max-height: calc(100vh - 186px);" class="table-ec">
	    <div class="scroll-table">
	        <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
	            <thead>
		            <tr>
		            	<th ng-click="vm.TratarOrdem('STATUS')"><span style="display: inline-flex;"> - <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'STATUS'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-STATUS'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		            	<th ng-click="vm.TratarOrdem('NOTIFICACOES')"><span style="display: inline-flex;" title="Quantidade de Lembretes"  ><span class="glyphicon glyphicon-info-sign"></span><span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'NOTIFICACOES'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-NOTIFICACOES'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		            	<th ng-click="vm.TratarOrdem('STATUS')"><span style="display: inline-flex;">Status <span style="margin-left: 5px;margin-right: -5px;" ng-if="vm.ordem == 'STATUS'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-STATUS'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		                <th ng-click="vm.TratarOrdem('CODIGO')"><span style="display: inline-flex;">Caso <span style="margin-left: 5px; margin-right: -5px;" ng-if="vm.ordem == 'CODIGO'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-CODIGO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		                <th ng-click="vm.TratarOrdem('TIPO')"><span style="display: inline-flex;">Tipo <span style="margin-left: 5px; margin-right: -5px;" ng-if="vm.ordem == 'TIPO'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-TIPO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		                <th ng-click="vm.TratarOrdem('ORIGEM')"><span style="display: inline-flex;">Tipo Origem <span style="margin-left: 5px; margin-right: -5px;" ng-if="vm.ordem == 'ORIGEM'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-ORIGEM'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>  
		                <th ng-click="vm.TratarOrdem('DATAHORA_REGISTRO')"><span style="display: inline-flex;">Data de Abertura <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == 'DATAHORA_REGISTRO'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-DATAHORA_REGISTRO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th> 

		                <th ng-repeat="iten in vm.conf['CAMPO'] track by $index" class="wid-cliente" ng-click="vm.TratarOrdem('C' + $index)"><span style="display: inline-flex;">@{{ iten.DESCRICAO }} <span style="margin-left: 5px ; margin-right: -5px;" ng-if="vm.ordem == 'C' + $index" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; margin-right: -5px;" ng-if="vm.ordem == '-C' + $index" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th> 

		            </tr>
		        </thead>
	            <tbody>
	                <tr ng-Keydown="vm.Acoes.openCaso(item.ID,$event)" class="caso_iten_@{{item.ID}}" tabindex="0" ng-repeat="item in vm.lista = (vm.casos | filter:vm.filtroCaso | orderBy:vm.ordem)" ng-click="vm.Acoes.openCaso(item.ID,null)">
	                  <td ><div class="div-status" style="background-color: @{{ item.COR}};"></div></td>
	                  <td auto-title >@{{ item.NOTIFICACOES }}</td>
	                  <td auto-title >@{{ item.STATUS }}</td>
	                  <td auto-title >@{{ item.CODIGO}}</td>
	                  <td auto-title >@{{ item.TIPO }}</td>
	                  <td auto-title >@{{ item.ORIGEM }}</td>
	                  <td auto-title >@{{ item.DATAHORA_REGISTRO }}</td>

	                  <td ng-repeat="iten in vm.conf['CAMPO'] track by $index" auto-title class="wid-cliente">@{{ item['C'+$index] }}</td> 

	                </tr>               
	            </tbody>
	        </table>
	    </div>
	</div>
</div>

<div role="tabpanel" class="tab-pane fade" id="tab-tabela3-container" aria-labelledby="tab-tabela3">
	
	<div class="input-group input-group-filtro-obj">
		<input ng-Keydown="vm.filterCaso2($event,null)" type="search" ng-model="vm.FILTRO_CASO" name="filtro_obj" class="form-control pesquisa filtro-obj" placeholder="Pesquise..." autocomplete="off" autofocus="">
		<button type="button" class="input-group-addon btn-filtro btn-filtro-obj btn-pesquisar" ng-click="vm.filterCaso3()">
			<span class="fa fa-search"></span>
		</button>
	</div>

	

	<div style="max-height: calc(100vh - 230px);margin-top: 10px" class="table-ec">
	    <div class="scroll-table">
	        <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
	            <thead>
		            <tr>
		            	<th ng-click="vm.TratarOrdem('STATUS')"><span style="display: inline-flex;"> - <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'STATUS'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-STATUS'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		            	<th ng-click="vm.TratarOrdem('NOTIFICACOES')"><span style="display: inline-flex;" title="Quantidade de Lembretes"  ><span class="glyphicon glyphicon-info-sign"></span><span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'NOTIFICACOES'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-NOTIFICACOES'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		            	<th ng-click="vm.TratarOrdem('STATUS')"><span style="display: inline-flex;">Status <span style="margin-left: 5px;margin-right: -5px;" ng-if="vm.ordem == 'STATUS'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-STATUS'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		                <th ng-click="vm.TratarOrdem('CODIGO')"><span style="display: inline-flex;">Caso <span style="margin-left: 5px; margin-right: -5px;" ng-if="vm.ordem == 'CODIGO'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-CODIGO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		                <th ng-click="vm.TratarOrdem('TIPO')"><span style="display: inline-flex;">Tipo <span style="margin-left: 5px; margin-right: -5px;" ng-if="vm.ordem == 'TIPO'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-TIPO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		                <th ng-click="vm.TratarOrdem('ORIGEM')"><span style="display: inline-flex;">Tipo Origem <span style="margin-left: 5px; margin-right: -5px;" ng-if="vm.ordem == 'ORIGEM'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-ORIGEM'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>  
		                <th ng-click="vm.TratarOrdem('DATAHORA_REGISTRO')"><span style="display: inline-flex;">Data de Abertura <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == 'DATAHORA_REGISTRO'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-DATAHORA_REGISTRO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th> 

		                <th ng-repeat="iten in vm.conf['CAMPO'] track by $index" class="wid-cliente" ng-click="vm.TratarOrdem('C' + $index)"><span style="display: inline-flex;">@{{ iten.DESCRICAO }} <span style="margin-left: 5px ; margin-right: -5px;" ng-if="vm.ordem == 'C' + $index" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; margin-right: -5px;" ng-if="vm.ordem == '-C' + $index" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th> 

		            </tr>
		        </thead>
	            <tbody>
	                <tr ng-Keydown="vm.Acoes.openCaso(item.ID,$event)" class="caso_iten_@{{item.ID}}" tabindex="0" ng-repeat="item in vm.lista = (vm.casos | filter:vm.filtroCaso | orderBy:vm.ordem)" ng-click="vm.Acoes.openCaso(item.ID,null)">
	                  <td ><div class="div-status" style="background-color: @{{ item.COR}};"></div></td>
	                  <td auto-title >@{{ item.NOTIFICACOES }}</td>
	                  <td auto-title >@{{ item.STATUS }}</td>
	                  <td auto-title >@{{ item.CODIGO}}</td>
	                  <td auto-title >@{{ item.TIPO }}</td>
	                  <td auto-title >@{{ item.ORIGEM }}</td>
	                  <td auto-title >@{{ item.DATAHORA_REGISTRO }}</td>

	                  <td ng-repeat="iten in vm.conf['CAMPO'] track by $index" auto-title class="wid-cliente">@{{ item['C'+$index] }}</td> 

	                </tr>               
	            </tbody>
	        </table>
	    </div>
	</div>
</div>

	
	@include('admin._11150.modal.modal_caso')
	@include('admin._11150.modal.modal_file')
	@include('admin._11150.modal.modal_finalizar')
	@include('admin._11150.modal.modal_lembrete')	
	@include('admin._11150.modal.cad_contato')

</div>
@endsection

@section('script')

    <script src="{{ asset('assets/js/editor/ckeditor.js') }}"></script>
    <script src="{{ elixir('assets/js/codemirror.js') }}"></script>
    <script src="{{ elixir('assets/js/_11150.app.js') }}"></script>

    <script>
		/*
			var editor = CodeMirror.fromTextArea(document.getElementById("textareaCode"), {
			    lineNumbers: true,
			    matchBrackets: true,
			    theme:'abcdef',
			    extraKeys: {"Ctrl-Space": "autocomplete"},
	  			mode: {name: "javascript", globalVars: true}
			});
		*/
	</script>
@append
