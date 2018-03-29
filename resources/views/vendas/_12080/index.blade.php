@extends('master')

@section('titulo')
    {{ Lang::get('vendas/_12080.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/12080.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

	

</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_12080.js') }}"></script>
@append
