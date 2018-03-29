@extends('master')

@section('titulo')
    {{ Lang::get('logistica/_14020.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/14020.css') }}" />
@endsection

@section('conteudo')
<style>
    #main {
        margin-top: -45px;
    }
</style>
<div ng-controller="Ctrl as vm" ng-cloak>
    <form class="form-inline">
        @include($menu.'.frete-detalhamento')
    </form>
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_14020.js') }}"></script>
@append
