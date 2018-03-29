@extends('master')

@section('titulo')
    {{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/23036.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as $ctrl" ng-cloak>

	<input 
		type="hidden" 
		ng-init="$ctrl.permissaoMenu = {{ json_encode($permissaoMenu) }}">

	@include('pessoal._23036.index.top')
	@include('pessoal._23036.index.table-filter')
	@include('pessoal._23036.index.table')
	
	<form class="form-inline" ng-submit="$ctrl.Create.gravarBase()">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		
		@include('pessoal._23036.create.create')

	</form>

	@include('pessoal._23036.create.modal-pesq-ccusto')

</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_23036.js') }}"></script>
@append
