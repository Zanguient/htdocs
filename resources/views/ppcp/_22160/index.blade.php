@extends('master')

@section('titulo')
    {{ Lang::get('ppcp/_22160.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/22160.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak class="main-ctrl" style="display: none">
    
    <div class="main-container">
        <ul class="nav nav-tabs">
            <li class="active">
                <a 
                    data-toggle="tab" 
                    href="#consumo-baixar"
                    ng-click="vm.ConsumoBaixarFiltro.consultar()">
                    Baixas Pendentes
                </a>
            </li>
            <li>
                <a 
                    data-toggle="tab" 
                    href="#consumo-baixado"
                    ng-click="vm.ConsumoBaixadoFiltro.consultar()">
                    Baixas Realizadas
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="consumo-baixar" class="tab-pane fade in active">
                @include('ppcp._22160.index.panel-consumo-baixar')
            </div>
            <div id="consumo-baixado" class="tab-pane fade">
                @include('ppcp._22160.index.panel-consumo-baixado')
            </div>
        </div> 
    </div>

    
    @include('ppcp._22160.index.modal-balanca')
    @include('ppcp._22160.index.modal-operador')
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/direct-print.js') }}"></script>
    <script src="{{ elixir('assets/js/_22160.js') }}"></script>
@append
