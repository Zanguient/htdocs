@extends('helper.include.view.modal', ['id' => 'modal-add-Inputs'])

@section('modal-header-left')

<h4 class="modal-title">
	Adicionar - Inputs:
</h4>

@overwrite

@section('modal-header-right')

	<button type="button"  class="btn btn-success btn-cancelar" ng-click="vm.Create.edtInput(vm.Create.Input.ID)" ng-if="vm.Create.Input.EDIT == 1">
		<span class="glyphicon glyphicon-ok"></span>
		Salvar
	</button>

	<button type="button"  class="btn btn-primary btn-cancelar" ng-click="vm.Create.addInput()" ng-if="vm.Create.Input.EDIT == 0">
		<span class="glyphicon glyphicon-ok"></span>
		Adicionar
	</button>

	<button type="button" class="btn btn-danger btn-cancelar" data-dismiss="modal" data-hotkey="f11">
		<span class="glyphicon glyphicon-ban-circle"></span>
		{{ Lang::get('master.cancelar') }}
	</button>

@overwrite

@section('modal-body')

	<div class="form-group">
		<label>Tipo:</label>
		<select name="relatorio-grupo" ng-model="vm.Create.Input.TIPO" placeholder="0 ou 1" class="form-control relatorio-grupo input-medio"  autofocus="" required="">
			<option value="1" >Texto</option>
			<option value="2" >Número</option>
			<option value="3" >Data</option>
			<option value="4" >Check</option>
			<option value="5" >Radio</option>
			<option value="6" >Range</option>
			<option value="7" >Search</option>
			<option value="8" >Time</option>			
		</select>
  	</div>
	
	<div class="line-group item_info info_padrao" style="display: none;">

		<div class="form-group" style="width: 200px;">
			<label>Nome:</label>
			<input type="text" ng-model="vm.Create.Input.NOME" name="titulo" class="form-control input-medio" autofocus="" required="">
	  	</div>

		<div class="form-group" style="width: 200px;">
			<label>Vínculo:</label>
			<select name="relatorio-grupo" ng-model="vm.Create.Input.VINCULO" class="form-control relatorio-grupo input-medio" autofocus="" required="">
				<option value="">Selecione</option>
				<option ng-repeat="x in vm.Create.Inputs" ng-value="x.NOME">@{{x.NOME}}</option>		
			</select>
	  	</div>

		<div class="form-group" style="width: 200px;">
			<label>Texto:</label>
			<input type="text" ng-model="vm.Create.Input.TEXTO" name="titulo" class="form-control input-medio" autofocus="" required="">
	  	</div>

		<div class="form-group">
			<label>Obrigatório:</label>
			<select name="relatorio-grupo" ng-model="vm.Create.Input.REQUERED" class="form-control relatorio-grupo input-menor"  autofocus="" required="">
				<option value="1" >Sim</option>
				<option value="2" >Não</option>		
			</select>
	  	</div>	

  	</div>

  	<div class="line-group item_info info_tamanho" style="display: none;">

	  	<div class="form-group" style="width: 200px;">
			<label>Tamanho:</label>
			<select name="relatorio-grupo" ng-model="vm.Create.Input.TAMANHO" class="form-control relatorio-grupo input-medio"  autofocus="" required="">
				<option value="1" >Pequeno</option>
				<option value="2" >Médio</option>
				<option value="3" >Grande</option>			
			</select>
	  	</div>	

  	</div>

  	<div class="line-group item_info info_min_max" style="display: none;">

		<div class="form-group" style="width: 200px;">
			<label>Minimo:</label>
			<input type="number" ng-model="vm.Create.Input.MIN" name="titulo" class="form-control input-menor" autofocus="" required="">
	  	</div>

	  	<div class="form-group" style="width: 200px;">
			<label>Maximo:</label>
			<input type="number" ng-model="vm.Create.Input.MAX" name="titulo" class="form-control input-menor" autofocus="" required="">
	  	</div>

	  	<div class="form-group" style="width: 200px;">
			<label>Passos:</label>
			<input type="number" ng-model="vm.Create.Input.STEP" name="titulo" min="1" max="999" class="form-control input-menor" autofocus="" required="">
	  	</div>

  	</div>

  	<div class="line-group item_info info_new_item" style="display: none;">
		
		<button ng-click="vm.Create.addNewItem()" class="btn btn-primary js-gravar" data-hotkey="f10" data-loading-text="Gravando...">
				<span class="glyphicon glyphicon-plus"></span>
				 Adicionar
		</button><br><br>

		<div ng-repeat="item in vm.Create.Input.ITENS track by $index">
			<div class="form-group" style="width: 200px;">
				<label>Nome:</label>
				<input type="text" ng-model="item.TEXTO" name="titulo" class="form-control input-medio" autofocus="" required="">
		  	</div>

		  	<div class="form-group" style="width: 200px;">
				<label>Nome:</label>
				<input type="text" ng-model="item.VALOR" name="titulo" class="form-control input-medio" autofocus="" required="">
		  	</div>

		  	<div class="form-group" style="width: 200px;">
				<label></label>
				<div class="item-checkbox"><input ng-model="item.SELECTED" type="checkbox" class="form-control"><span class="label-checkbox">Selecionado</span></div>
		  	</div>
		</div>
  	</div>

  	<div class="line-group item_info info_search" style="display: none;">
		<div class="form-group" style="width: 200px;">
			<label>Tabela:</label>
			<input type="text" ng-model="item.TEXTO" name="titulo" class="form-control input-medio" autofocus="" required="">
	  	</div>
  	</div>	

@overwrite