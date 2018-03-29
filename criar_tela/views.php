@extends('master')

@section('titulo')
    {{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/#TelaID#.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as $ctrl" ng-cloak>

	<div 
		class="div-frase"
		ng-bind="$ctrl.Index.dado.FRASE"></div>

</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/#TelaNO#.js') }}"></script>
@append
