@extends('master')

@section('titulo')
    {{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/23037.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as $ctrl" ng-cloak>

	<input 
		type="hidden" 
		ng-init="
			$ctrl.permissaoMenu = {{ json_encode($permissaoMenu) }};
			$ctrl.pu225 		= {{ json_encode($pu225) }}
		">

	@include('pessoal._23037.index.top')
	@include('pessoal._23037.index.table-filter')
	@include('pessoal._23037.index.table')

	@include('pessoal._23037.index.resposta.resposta')

	<form class="form-inline" ng-submit="$ctrl.Create.gravarAvaliacao()">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		
		@include('pessoal._23037.create.create')

	</form>

	@include('pessoal._23037.create.modal-pesq-colaborador')

</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_23037.js') }}"></script>
@append