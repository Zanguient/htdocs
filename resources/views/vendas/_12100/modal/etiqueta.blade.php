@extends('helper.include.view.modal', ['id' => 'modal-etiqueta', 'class_size' => 'modal-lg'])

@section('modal-header-left')

<h4 class="modal-title">
	Modelos de Etiqueta
</h4>

@overwrite

@section('modal-header-right')

	<button type="button" class="btn btn-info" ng-disabled="vm.Etiqueta.AbilitarImprimir == 0" ng-click="vm.Etiqueta.Imprimir()">
	  <span class="glyphicon glyphicon-print"></span> Imprimir
	</button>
	
	<button type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
	  <span class="glyphicon glyphicon-chevron-left"></span> Voltar
	</button>

@overwrite

@section('modal-body')

<fieldset>
<legend>Modelo de Etiqueta</legend>
<div class="table-ec">
    <div class="scroll-table">
        <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body">
            <thead>
	            <tr>
					<th>ID</th>
					<th>Descrição</th>
	            </tr>
	        </thead>
            <tbody class="tabela-itens">
                <tr ng-repeat="item in vm.Etiqueta.Modelos" ng-class="{'selected': vm.Etiqueta.SELECT == item}" class=""  tabindex="0" ng-click="vm.Etiqueta.SELECT = item; vm.Etiqueta.ValidarImprimir();">
	                <td>@{{item.ID}}</td>
	                <td>@{{item.DESCRICAO}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</fieldset>

<br>

<fieldset>
	<legend>Itens da Nota Fiscal</legend>

	<div style="margin-bottom: 5px;">
		<button type="button" class="btn btn-default" ng-click="vm.Etiqueta.MarcarTodos();">
		  <span class="glyphicon glyphicon-ok" style="color: green;"></span> Marcar Todos
		</button>

		<button type="button" class="btn btn-default" ng-click="vm.Etiqueta.DesmarcarTodos();">
		  <span class="glyphicon glyphicon-remove" style="color: red;"></span> Desmarcar Todos
		</button>
	</div>

	<div style="max-height: calc(100vh - 500px); min-height: 300px;" class="table-ec">
	    <div class="scroll-table">
	        <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
	            <thead>
		            <tr>
						<th style="text-align: center;">Imprimir</th>
						<th style="text-align: left;"  >Produto</th>
						<th style="text-align: center;">Tamanho</th>
						<th style="text-align: right;" >Quantidade</th>
						<th style="text-align: right;" >Volumes</th>
		            </tr>
		        </thead>
	            <tbody class="tabela-itens">

	                <tr class="lista-itens" tabindex="0" ng-repeat="nota in vm.Etiqueta.NOTA">
		                <td style="text-align: center; cursor: pointer;" ng-click="vm.Etiqueta.MudarMarcado(nota); vm.Etiqueta.ValidarImprimir();">
		                	<span ng-if="nota.MARCADO == 1" class="glyphicon glyphicon-ok" style="color: green; font-size: 20px;"></span>
		                	<span ng-if="nota.MARCADO == 0" class="glyphicon glyphicon-remove"  style="color: red; font-size: 20px;"></span>
		                </td>
		                <td style="text-align: left;"  >@{{nota.PRODUTO_ID}} - @{{nota.PRODUTO_DESCRICAO}}</td>
		                <td style="text-align: center;">@{{nota.TAMANHO}}</td>
		                <td style="text-align: right;" >@{{nota.QUANTIDADE}}</td>
		                <td style="text-align: right;" >
							<input ng-change="vm.Etiqueta.ValidarImprimir();" class="" style="width: 120px;" type="number" step="1" max="@{{nota.VOLUMES2}}" min="1" ng-model="nota.VOLUMES1">
		                </td>
	                </tr>
	            </tbody>
	        </table>
	    </div>
	</div>

@overwrite