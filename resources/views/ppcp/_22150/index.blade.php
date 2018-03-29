@extends('master')

@section('titulo')
    {{ Lang::get('ppcp/_22150.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/22150.css') }}" />
@endsection

@section('conteudo')

<div class="ctrl" ng-controller="Ctrl as vm" ng-cloak style="display: none;">

    <ul class="nav nav-tabs">
        <li class="active">
            <a data-toggle="tab" href="#home">
                Painel Principal
            </a>
        </li>
        <li>
            <a data-toggle="tab" href="#ferramenta-programada">
                Ferramentas Programadas
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
            @include('ppcp._22150.index.panel-ferramenta-pedente')
        </div>
        <div id="ferramenta-programada" class="tab-pane fade">
            @include('ppcp._22150.index.panel-ferramenta-programada')
        </div>
    </div>    
    
@include('ppcp._22150.index.modal-autenticar-operador')
@include('ppcp._22150.index.modal-entrada')
@include('ppcp._22150.index.modal-separacao')
@include('ppcp._22150.index.modal-saida')
@include('ppcp._22150.index.modal-ferramenta-alterar')
@include('ppcp._22150.index.modal-ferramenta-historico')
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_22150.js') }}"></script>
@append
