@extends('master')

@section('titulo')
    {{ Lang::get('estoque/_15070.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/15070.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak class="main-ctrl" style="display: none">


    <ul class="list-inline acoes">
        <li>
            <button
                type="button" 
                class="btn btn-warning" 
                id="etiqueta" 
                data-hotkey="alt+i" 
                ng-click="vm.Remessa.etiqueta()"
                ng-disabled="!(vm.Remessa.DADOS.indexOf(vm.Remessa.SELECTED) > -1) || !(vm.Remessa.SELECTED.QUANTIDADE_CONSUMO > 0)"
                >
                <span class="glyphicon glyphicon-print"></span>
                Imprimir Etiqueta
            </button>
        </li>
    </ul>        
    
    @include('estoque._15070.index.form-filtrar')
    
<!--        <ul class="list-inline acoes">                
            <li>
                <div 
                    class="btn btn-default" 
                    ng-click="vm.Filtro.VISUALIZACAO_POR_PRODUTO = !vm.Filtro.VISUALIZACAO_POR_PRODUTO"
                    ng-class="{'item-active' : vm.Filtro.CHECK_OCULTAR_DISPONIVEL}"
                    >
                <i class="check fa" ng-class="vm.FerramentaProgramada.CHECK_OCULTAR_DISPONIVEL ? 'fa-eye' : 'fa-eye-slash'"></i>
                    Visualização por Produto
                </div>
            </li>               
        </ul>    -->

            <div 
                class="alert alert-danger ng-binding ng-scope" 
                ng-if="vm.Remessa.DADOS.indexOf(vm.Remessa.SELECTED) > -1"
                style="
                    position: absolute;
                    right: 0;
                    top: 8px;
                    margin: 0;
                    color: rgb(255, 255, 255);
                    background: rgb(60, 140, 60);
                    font-weight: bold;
                    font-size: 12px;
                    max-width: 50%;
                    padding: 3px 8px 3px 8px;
                "
                >
                Remessa: <span ttitle="Id da Remessa: @{{ vm.Remessa.SELECTED.REMESSA_ID }}">@{{ vm.Remessa.SELECTED.REMESSA }}</span> - 
                Data: @{{ vm.Remessa.SELECTED.REMESSA_DATA_TEXT }} - 
                Família:  <span ttitle="@{{ vm.Remessa.SELECTED.REMESSA_FAMILIA_ID }} - @{{ vm.Remessa.SELECTED.REMESSA_FAMILIA_DESCRICAO }}">@{{ vm.Remessa.SELECTED.REMESSA_FAMILIA_DESCRICAO }}</span>
            </div>

        <ul class="nav nav-tabs">
            <li class="active">
                <a 
                    data-toggle="tab" 
                    href="#selecionar-remessa"
                    ng-click="vm.Filtro.TAB_ACTIVE = 'REMESSA'">
                    Selecionar Remessa
                </a>
            </li>
            <li>
                <a 
                    data-toggle="tab" 
                    href="#visualizacao-por-talao"
                    ng-if="vm.Remessa.DADOS.indexOf(vm.Remessa.SELECTED) > -1"
                    ng-click="vm.Remessa.consultarConsumos(); vm.Filtro.TAB_ACTIVE = 'TALAO'; vm.Filtro.REMESSA_ID = vm.Remessa.SELECTED.REMESSA_ID;">
                    Visualizar por Talão
                </a>
            </li>
            <li>
                <a 
                    data-toggle="tab" 
                    href="#visualizacao-por-consumo"
                    id="tab-visualizacao-por-consumo"
                    ng-if="vm.Remessa.DADOS.indexOf(vm.Remessa.SELECTED) > -1"
                    ng-click="vm.Remessa.consultarConsumos(); vm.Filtro.TAB_ACTIVE = 'CONSUMO'; vm.Filtro.REMESSA_ID = vm.Remessa.SELECTED.REMESSA_ID;">
                    Visualizar por Produto
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="selecionar-remessa" class="tab-pane fade in active">
                <div class="main-container">
                    @include('estoque._15070.index.selecionar-remessa.table-remessa')
                </div>
            </div>
            <div id="visualizacao-por-talao" class="tab-pane fade">
                <div class="main-container">
                    <div class="header">
                        @php /*
                        @include('estoque._15070.index.visualizacao-por-talao.table-remessa')
                        @php */
                        @include('estoque._15070.index.visualizacao-por-talao.table-talao')
                    </div>
                    <div class="footer">
                        @include('estoque._15070.index.visualizacao-por-talao.table-consumo')
                    </div>
                    
                </div>
            </div>
            <div id="visualizacao-por-consumo" class="tab-pane fade">
                <div class="main-container">
                    <div class="header resize-item">
                        @php /*
                        @include('estoque._15070.index.visualizacao-por-consumo.table-remessa')
                        @php */
                        @include('estoque._15070.index.visualizacao-por-consumo.table-consumo')
                    </div>
                    <div class="footer">
                        @include('estoque._15070.index.visualizacao-por-consumo.table-talao')
                    </div>
                    
                </div>
            </div>
        </div>
    
    @include('estoque._15070.index.modal-registrar-saida-avulsa')
    @include('estoque._15070.index.modal-registrar-saida-por-peca')
    @include('estoque._15070.index.modal-transacao')
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/direct-print.js') }}"></script>
    <script src="{{ elixir('assets/js/_15070.js') }}"></script>
@append
