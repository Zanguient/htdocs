@extends('master')

@section('titulo')
	{{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/25010.css') }}" />
@endsection

@section('conteudo')

	<div ng-controller="ctrl as vm" ng-cloak>

    	@include('opex._25010.index.botao-acao')
    	@include('opex._25010.index.table-filter')
		@include('opex._25010.index.table-formulario')

		<form class="form-inline" ng-submit="vm.gravarFormulario()">
	    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
			@include('opex._25010.create.modal-create')
		</form>

		@include('opex._25010.create.modal-destinatario-usuario')
		@include('opex._25010.create.modal-destinatario-ccusto')
		@include('opex._25010.index.modal-painel')
		@include('opex._25010.index.modal-consultar-representante')
		@include('opex._25010.index.modal-consultar-uf')
		
	</div>

@endsection

@section('script')
	<script src="{{ asset('assets/js/loader.js') }}"></script>
    <script src="{{ elixir('assets/js/_25010.js') }}"></script>
@endsection
