@extends('helper.include.view.modal', ['id' => 'modal-autenticar2'])

@section('modal-header-left')

<h4 class="modal-title">
	Autenticação:
</h4>

@overwrite

@section('modal-header-right')

	<button ng-click="vm.Acoes.logarUser2(vm.ESTACAO_MODAL)" type="button" class="btn btn-success btn-confirmar" id="btn-confirmar-up" data-hotkey="enter">
		<span class="glyphicon glyphicon-ok"></span>
		{{ Lang::get('master.confirmar') }}
	</button>
	<button type="button" class="btn btn-danger btn-cancelar" data-dismiss="modal" data-hotkey="f11">
		<span class="glyphicon glyphicon-ban-circle"></span>
		{{ Lang::get('master.cancelar') }}
	</button>

@overwrite

@section('modal-body')

<div class="form-group">
	<label>Operador:</label>
	<input type="password" ng-keyup="$event.keyCode == 13 && vm.Acoes.logarUser2(vm.ESTACAO_MODAL)" ng-model="vm.OPERADOR.barras" name="usuario_barra2" id="usuario-barra " class="form-control usuario_barra2" autocomplete="off" />
</div>

@overwrite