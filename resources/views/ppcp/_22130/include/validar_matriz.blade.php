@extends('helper.include.view.modal', ['id' => 'modal-validar-matriz'])

@section('modal-header-left')

<h4 class="modal-title">
	Validar Matriz:
</h4>

@overwrite

@section('modal-header-right')

	<button ng-if="vm.MODAL.FERRAMENTA_SITUACAO_TALAO.trim() == 'S'" ng-click="vm.Acoes.consultarMatriz()" type="button" class="btn btn-success btn-confirmar" id="btn-confirmar-up" data-hotkey="enter">
		<span class="glyphicon glyphicon-ok"></span>
		{{ Lang::get('master.confirmar') }}
	</button>
	<button type="button" class="btn btn-danger btn-cancelar" data-dismiss="modal" data-hotkey="f11">
		<span class="glyphicon glyphicon-ban-circle"></span>
		{{ Lang::get('master.cancelar') }}
	</button>

@overwrite

@section('modal-body')

<div class="form-group" ng-if="vm.MODAL.FERRAMENTA_SITUACAO_TALAO.trim() == 'S'">
	<label>Ferramenta:</label>
	<input type="password" ng-keyup="$event.keyCode == 13 && vm.Acoes.consultarMatriz()" ng-model="vm.FILTRO.MATRIZ_BARRAS" name="matriz_barra" id="usuario-barra" class="form-control matriz_barra" autocomplete="off" />
</div>

	<div class="erros-talao" style="margin-top: 0px;" ng-if="vm.MODAL.FERRAMENTA_SITUACAO_TALAO.trim() != 'S'">
		<div class="alert alert2 alert-warning">
			<b>Ferramenta ainda não está a caminho</b></p>
	    </div>
	</div>

@overwrite