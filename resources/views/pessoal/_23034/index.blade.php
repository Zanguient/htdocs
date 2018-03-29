@extends('master')

@section('titulo')
    {{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/23034.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as $ctrl" ng-cloak>

	<input 
		type="hidden" 
		ng-init="$ctrl.permissaoMenu = {{ json_encode($permissaoMenu) }}">

	@include('pessoal._23034.index.top')
	@include('pessoal._23034.index.table')
	
	<form class="form-inline" ng-submit="$ctrl.Create.gravar()">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		
		@include('pessoal._23034.create.create')

	</form>

</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_23034.js') }}"></script>
@append
