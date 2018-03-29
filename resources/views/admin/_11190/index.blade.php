@extends('master')

@section('titulo')
    {{ Lang::get('admin/_11190.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11190.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

	<input type="hidden" class="menu-incluir"  value="{{$permissaoMenu->INCLUIR }}">
	<input type="hidden" class="menu-alterar"  value="{{$permissaoMenu->ALTERAR }}">
	<input type="hidden" class="menu-excluir"  value="{{$permissaoMenu->EXCLUIR }}">
	<input type="hidden" class="menu-imprimir" value="{{$permissaoMenu->IMPRIMIR}}">

	<ul class="list-inline acoes">
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
				ng-click="vm.Acoes.atualizar()">
				<span class="glyphicon glyphicon-refresh"></span>
				Atualizar
			</button>
		</li>
	</ul>

	<div class="pesquisa-obj-container">
		<div class="input-group input-group-filtro-obj">
			<input type="search" ng-model="vm.filtro" name="filtro_obj" class="form-control pesquisa filtro-obj" placeholder="Pesquise..." autocomplete="off" autofocus="">
			<button type="button" class="input-group-addon btn-filtro btn-filtro-obj btn-pesquisar">
				<span class="fa fa-search"></span>
			</button>
		</div>
	</div>

	 <ul id="tab" class="nav nav-tabs" role="tablist"> 
        <li role="presentation" class="active tab-detalhamento">
            <a href="#tab-notificacao-container" id="tab-notificacao" role="tab" data-toggle="tab" aria-controls="tab-notificacao-container" aria-expanded="false">
                Notificações
            </a>
        </li>
        <li role="presentation" class="tab-detalhamento" ng-if="vm.PERMICAO.INCLUIR == 1">
            <a href="#tab-env-notificacao-container" id="tab-env-notificacao" role="tab" data-toggle="tab" aria-controls="tab-env-notificacao-container" aria-expanded="false">
                Envio de Notificações
            </a>
        </li>
    </ul>

    <div role="tabpanel" class="tab-pane fade active in" id="tab-notificacao-container" aria-labelledby="tab-notificacao">
        <div style="max-height: calc(100vh - 300px);" class="table-ec">
		    <div class="scroll-table">
		        <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
		            <thead>
			            <tr>
			            	<th ng-click="vm.Acoes.TratarOrdem2('AGENDAMENTO') "><span style="display: inline-flex;">TIPO <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem2 == 'AGENDAMENTO'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem2 == '-AGENDAMENTO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
			            	<th ng-click="vm.Acoes.TratarOrdem2('ID')          "><span style="display: inline-flex;">ID <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem2 == 'ID'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem2 == '-ID'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
			            	<th ng-click="vm.Acoes.TratarOrdem2('DATA_HORA')   " style="min-width: 114px;"><span style="display: inline-flex;">Data/Hora <span style="margin-left: 5px;margin-right: -5px;" ng-if="vm.ordem2 == 'DATA_HORA'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem2 == '-DATA_HORA'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
			            	<th ng-click="vm.Acoes.TratarOrdem2('TITULO')      "><span style="display: inline-flex;">Título <span style="margin-left: 5px;margin-right: -5px;" ng-if="vm.ordem2 == 'TITULO'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem2 == '-TITULO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
			            	<th ng-click="vm.Acoes.TratarOrdem2('MENSAGEM')    "><span style="display: inline-flex;">Mensagem <span style="margin-left: 5px;margin-right: -5px;" ng-if="vm.ordem2 == 'MENSAGEM'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem2 == '-MENSAGEM'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
			            </tr>
			        </thead>
		            <tbody>
		                <tr class="iten_@{{iten.ID}}" tabindex="0" ng-repeat="iten in vm.lista2 = ( vm.NOTIFICACAO | filter:vm.filtro | orderBy:vm.ordem2 )" ng-click="vm.Acoes.open(iten)">
		                  	<td style="font-size: 20px;">
		                  		<span t-title="Agendamento: @{{iten.AGENDAMENTO}}" ng-if="iten.AGENDAMENTO != null" class="glyphicon glyphicon-time" aria-hidden="true"></span>
		                  		<span t-title="Notificação" ng-if="iten.AGENDAMENTO == null && iten.TIPO == 0" class="glyphicon glyphicon-comment" aria-hidden="true"></span>
		                  		<span t-title="Atualização" ng-if="iten.AGENDAMENTO == null && iten.TIPO == 1" class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
		                  	</td>
		                  	<td auto-title >@{{iten.ID | number:0}}</td>
		                  	<td auto-title >@{{iten.DATA_HORA}}</td>
		                  	<td auto-title >@{{iten.TITULO}}</td>
		                  	<td auto-title ng-bind-html="trustAsHtml(iten.MENSAGEM)"></td>
		                </tr>               
		            </tbody>
		        </table>
		    </div>
		</div>
    </div>

    <div role="tabpanel" class="tab-pane fade" id="tab-env-notificacao-container" aria-labelledby="tab-env-notificacao">

    	<div class="lista-funcoes">
			<button 
				type="button" 
				class="btn btn-sm btn-default" 
				ng-click="vm.Acoes.modal()">
				<span class="glyphicon glyphicon-comment"></span> Notificação
			</button>
			<button 
				type="button" 
				class="btn btn-sm btn-default" 
				ng-click="vm.Acoes.enviarNotificacoes('updateTela','comando')">
				<span class="glyphicon glyphicon-refresh"></span> Atualizar Tela
			</button>
			<button 
				type="button" 
				class="btn btn-sm btn-default" 
				ng-click="vm.Acoes.enviarNotificacoes('updateMenu','comando')">
				<span class="glyphicon glyphicon-th"></span> Atualizar Menus
			</button>
		</div>

        <div style="padding: 3px;">
			<button 
				type="button" 
				class="btn btn-sm btn-info" 
				ng-click="vm.Acoes.marcarTodos()">
				<span class="glyphicon glyphicon-ok"></span> Marcar Todos
			</button>
			<button 
				type="button" 
				class="btn btn-sm btn-info" 
				ng-click="vm.Acoes.desmarcarTodos()">
				<span class="glyphicon glyphicon-remove"></span> Desmarcar Todos
			</button>
			<button 
				type="button" 
				class="btn btn-sm btn-info" 
				ng-click="vm.Acoes.ivertMarcar()">
				<span class="glyphicon glyphicon-random"></span> Inverter Marcação
			</button>
		</div>

		<div style="max-height: calc(100vh - 300px);" class="table-ec">
		    <div class="scroll-table">
		        <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
		            <thead>
			            <tr>
			            	<th ng-click="vm.Acoes.TratarOrdem('ID')       "><span style="display: inline-flex;">ID <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'ID'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-ID'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
			            	<th ng-click="vm.Acoes.TratarOrdem('USUARIO')  "><span style="display: inline-flex;">Usuário <span style="margin-left: 5px;margin-right: -5px;" ng-if="vm.ordem == 'USUARIO'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-USUARIO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
			            	<th ng-click="vm.Acoes.TratarOrdem('NOME')     "><span style="display: inline-flex;">Nome <span style="margin-left: 5px;margin-right: -5px;" ng-if="vm.ordem == 'NOME'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-NOME'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
			            	<th ng-click="vm.Acoes.TratarOrdem('SETOR')    "><span style="display: inline-flex;">Setor <span style="margin-left: 5px;margin-right: -5px;" ng-if="vm.ordem == 'SETOR'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-SETOR'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
			                <th ng-click="vm.Acoes.TratarOrdem('SELECTED') "><span style="display: inline-flex;">Selecionado <span style="margin-left: 5px; margin-right: -5px;" ng-if="vm.ordem == 'SELECTED'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-SELECTED'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
			            </tr>
			        </thead>
		            <tbody>
		                <tr class="iten_@{{iten.ID}}" tabindex="0" ng-repeat="iten in vm.lista = ( vm.USERS | filter:vm.filtro | orderBy:vm.ordem )" ng-click="vm.Acoes.open(iten)">
		                  	<td auto-title >@{{iten.ID}}</td>
		                  	<td auto-title >@{{iten.USUARIO}}</td>
		                  	<td auto-title >@{{iten.NOME}}</td>
		                  	<td auto-title >@{{iten.SETOR}}</td>
		                  	<td auto-title ng-click="vm.Acoes.StatusIten(iten)">
		                  		<span ng-if="iten.SELECTED == 1" 		 class="glyphicon glyphicon-ok"     style="color: green; position: inherit; "></span>
		                  		<span ng-if="iten.SELECTED == 0" 		 class="glyphicon glyphicon-remove" style="color: red;   position: inherit; "></span>
		                  		<span ng-if="iten.SELECTED == undefined" class="glyphicon glyphicon-remove" style="color: red;   position: inherit; "></span>
		                  	</td>
		                </tr>               
		            </tbody>
		        </table>
		    </div>
		</div>
    </div>

    </fieldset>

	@include('admin._11190.modal_mensagem')

</div>
@endsection

@section('script')
	<script src="{{ asset('assets/js/editor/ckeditor.js') }}"></script>
    <script src="{{ elixir('assets/js/_11190.js') }}"></script>
@append
