@extends('helper.include.view.modal', ['id' => 'modal-remessa', 'class_size' => 'modal-big'])

@section('modal-header-left')

	<h4 class="modal-title">
		Visualização
	</h4>

@overwrite

@section('modal-header-right')
	<button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc">
		<span class="glyphicon glyphicon-chevron-left"></span> 
		Voltar
	</button>
@overwrite

@section('modal-body')

	<form class="form-inline">
		
        <div class="row">
            @include('ppcp._22120.index.form-filtrar')
            <div class="pesquisa-obj-container talao">
                <div class="input-group input-group-filtro-obj">
                    <input
                        type="search" 
                        name="filtro_obj" 
                        class="form-control pesquisa filtro-obj" 
                        placeholder="Pesquise..." 
                        autocomplete="off" 
                        ng-disabled="( vm.itens.length <= 0)"
                        ng-model="vm.filtrar_talao"
                        ng-init="vm.filtrar_talao = ''"
                        ng-change="vm.FiltrarChange()"
                        />
                    <button type="button" class="input-group-addon btn-filtro btn-filtro-obj btn-pesquisar">
                        <span class="fa fa-search"></span>
                    </button>
                </div>
            </div>   
        </div>
        <div id="container" ng-right-click="vm.limparFiltro()">
            <div class="remessas">
                <div 
                    class="remessa-container"
                    ng-class="{'ocultar' : vm.selectedItemAcao(remessa,'REMESSA_VIEW','REMESSA_ID')}"
                    ng-repeat="remessa in vm.itens">
                    <div
                    ng-init="remessa.ACAO = []" class="remessa-wrapper">
                        <label title="Id da remessa: @{{ remessa.REMESSA_ID }}">Remessa: @{{ remessa.REMESSA }} | Família: @{{ remessa.FAMILIA_ID }} - @{{ remessa.FAMILIA_DESCRICAO }} @{{ remessa.REMESSA_GP_ID > 0 ? '| GP:' + remessa.REMESSA_GP_ID + ' - ' + remessa.REMESSA_GP_DESCRICAO : '' }} | Data: @{{ remessa.REMESSA_DATA_TEXT }}</label>

                        @include('ppcp._22120.index.remessa.talao')

                        <div class="accordion-composicao panel-group accordion@{{ remessa.REMESSA_ID }}" id="accordion@{{ remessa.REMESSA_ID }}" role="tablist" aria-multiselectable="true">

                            @include('ppcp._22120.index.remessa.talao-detalhe')

                            @include('ppcp._22120.index.remessa.talao-consumo')
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</form>

@overwrite