@extends('master')

@section('titulo')
    {{ Lang::get('Admin/_11150.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11150.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

	@foreach($paineis as $painel)
		<a href="_11150/{{$painel->ID}}">
			<div class="iten-painel">
				<div class="descricao-iten-painel">{{$painel->DESCRICAO}}</div>
				<div class="container-iten-painel">
					<div class="indicador-iten-painel">Em aberto:</div>
					<div class="indicador-iten-painel">Abertos nas ultimas 24h:</div>
					<div class="indicador-iten-painel">Fechados nas ultimas 24h:</div>
				</div>
			</div>
		</a>
	@endforeach

</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_11150.app.js') }}"></script>
@append
