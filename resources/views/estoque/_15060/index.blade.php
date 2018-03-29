@extends('master')

@section('titulo')
    {{ Lang::get('estoque/_15060.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/15060.css') }}" />
@endsection

@section('conteudo')

<div class="display-container" ng-controller="Ctrl as vm" ng-cloak>

    @include('estoque._15060.index.form-filtrar')
    
    <div class="container-header">
        <div class="container container-left">
            @include('estoque._15060.index.table-estoque-localizacao')
        </div>
        <div class="container container-right">
            @include('estoque._15060.index.table-estoque-grade')
        </div>
    </div>
    <div class="container container-footer">
        @include('estoque._15060.index.table-estoque-transacao')
    </div>
</div>

@endsection

@section('script')
    <script src="{{ elixir('assets/js/_15060.js') }}"></script>
@append
