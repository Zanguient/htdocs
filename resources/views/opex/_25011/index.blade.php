@extends('master')

@section('titulo')
    {{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/25011.css') }}" />
@endsection

@section('conteudo')

	<div ng-controller="ctrl as vm" ng-cloak>

    	@include('opex._25011.index.botao-acao')
		@include('opex._25011.index.table-formulario')

		<form class="form-inline" ng-submit="vm.gravarResposta()">
	    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
			@include('opex._25011.create.modal-create')
		</form>
		
	</div>

@endsection

@section('script')
    <script src="{{ elixir('assets/js/_25011.js') }}"></script>
@endsection
