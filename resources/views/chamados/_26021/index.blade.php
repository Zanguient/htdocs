@extends('master')

@section('titulo')
    {{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/26021.css') }}">
@endsection

@section('conteudo')
<div ng-controller="Ctrl as $ctrl" ng-cloak>

	<input 
		type="hidden" 
		ng-init="
			$ctrl.permissaoMenu.INCLUIR  = {{ $permissaoMenu->INCLUIR }};
			$ctrl.permissaoMenu.ALTERAR  = {{ $permissaoMenu->ALTERAR }};
			$ctrl.permissaoMenu.EXCLUIR  = {{ $permissaoMenu->EXCLUIR }};
			$ctrl.permissaoMenu.IMPRIMIR = {{ $permissaoMenu->IMPRIMIR }};
		">

	@include('chamados._26021.index.top')
	@include('chamados._26021.index.table-filter')
	@include('chamados._26021.index.table')
	
	<form class="form-inline" ng-submit="$ctrl.Create.gravar()">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		
		@include('chamados._26021.create.create')

		@include('chamados._26021.create.modal-pesq-pesquisa')
		@include('chamados._26021.create.modal-pesq-cliente')

	</form>

</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_26021.js') }}"></script>
@append
