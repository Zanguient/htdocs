@extends('master')

@section('titulo')
    {{ Lang::get('admin/_11180.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11180.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

	<ul class="list-inline acoes">
		<li>
			<button ng-click="vm.Acoes.add()" type="submit" class="btn btn-primary" data-hotkey="f6">
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

	<div style="max-height: calc(100vh - 186px);" class="table-ec">
	    <div class="scroll-table">
	        <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
	            <thead>
		            <tr>
		            	<th ng-click="vm.Acoes.TratarOrdem('ID')        "><span style="display: inline-flex;">ID <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'ID'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-ID'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		            	<th ng-click="vm.Acoes.TratarOrdem('NOME')      "><span style="display: inline-flex;">NOME <span style="margin-left: 5px;margin-right: -5px;" ng-if="vm.ordem == 'NOME'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-NOME'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		                <th ng-click="vm.Acoes.TratarOrdem('INVERT_URL')"><span style="display: inline-flex;">INVERT. BLO. <span style="margin-left: 5px; margin-right: -5px;" ng-if="vm.ordem == 'INVERT_URL'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-INVERT_URL'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		                <th ng-click="vm.Acoes.TratarOrdem('GRUPO')     "><span style="display: inline-flex;">PERFIL <span style="margin-left: 5px; margin-right: -5px;" ng-if="vm.ordem == 'GRUPO'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-GRUPO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>
		                <th ng-click="vm.Acoes.TratarOrdem('USB')       "><span style="display: inline-flex;">USB<span style="margin-left: 5px; margin-right: -5px;" ng-if="vm.ordem == 'USB'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-USB'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th>  
		                <th ng-click="vm.Acoes.TratarOrdem('CDDVD')     "><span style="display: inline-flex;">DVD <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == 'CDDVD'" class="glyphicon glyphicon-sort-by-attributes"></span><span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-CDDVD'" class="glyphicon glyphicon-sort-by-attributes-alt"></span></span></th> 
		            </tr>
		        </thead>
	            <tbody>
	                <tr class="blok_iten_@{{iten.ID}}" tabindex="0" ng-repeat="iten in vm.DADOS | filter:vm.filtro | orderBy:vm.ordem" ng-click="vm.Acoes.open(iten)">
	                  	<td auto-title >@{{iten.ID}}</td>
	                  	<td auto-title >@{{iten.NOME}}</td>
	                  	<td auto-title >
	                  		<span ng-if="iten.INVERT_URL == 1" class="glyphicon glyphicon-ok" style="color: green; position: inherit;"></span>
	                  		<span ng-if="iten.INVERT_URL == 0" class="glyphicon glyphicon-remove" style="color: red; position: inherit;"></span>
	                  	</td>
	                  	<td auto-title >@{{iten.GRUPO}}</td>
	                  	<td auto-title >
	                  		<span ng-if="iten.USB == 0" class="glyphicon glyphicon-ok" style="color: green; position: inherit;"></span>
	                  		<span ng-if="iten.USB == 1" class="glyphicon glyphicon-remove" style="color: red; position: inherit;"></span>
	                  	</td>
	                  	<td auto-title >
	                  		<span ng-if="iten.CDDVD == 0" class="glyphicon glyphicon-ok" style="color: green; position: inherit;"></span>
	                  		<span ng-if="iten.CDDVD == 1" class="glyphicon glyphicon-remove" style="color: red; position: inherit;"></span>
	                  	</td>
	                </tr>               
	            </tbody>
	        </table>
	    </div>
	</div>

	@include('admin._11180.modal.modal_blok')

</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_11180.js') }}"></script>
@append
