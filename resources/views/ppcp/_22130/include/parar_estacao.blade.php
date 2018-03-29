@extends('helper.include.view.modal', ['id' => 'modal-parar-estacao'])

@section('modal-header-left')

<h4 class="modal-title">
	Parar Estação: @{{vm.ESTACAO_MODAL.DESCRICAO}}
</h4>

@overwrite

@section('modal-header-right')

	<button type="button" class="btn btn-danger btn-cancelar" data-dismiss="modal" data-hotkey="f11">
		<span class="glyphicon glyphicon-ban-circle"></span>
		{{ Lang::get('master.cancelar') }}
	</button>

@overwrite

@section('modal-body')

<div class="form-group">
	
	<div class="alert alert2 alert-warning" ng-if="vm.ESTACAO_MODAL.LIVRE == false && false">
		Esta estação Esta ocupada com o talão @{{vm.ESTACAO_MODAL.TALAO_EM_PRODUCAO}}
	</div>

	<div
		class="alert alert2 alert-warning"
		ng-if="vm.OPERADOR.LOGADO == false">
        	<p ng-if="vm.OPERADOR.LOGADO == false"><b>O operador não esta logado</b></p>
			<span class="valor" ng-if="vm.OPERADOR.LOGADO == false">
				<button ng-click="vm.Acoes.modalLogin()" type="button" class="btn  btn-temp btn-warning" id="finalizar">
					<span class="glyphicon glyphicon-user"></span> Login
				</button>
			</span>
    </div>

	<div ng-if="vm.OPERADOR.LOGADO == true" class="group-justificativa">
		<div class="div-justificativa"
			ng-class="{
						'marcar-justificativa' : justificativa.MARCAR == 1
					}"
			ng-click="vm.Acoes.paradaEstacao(justificativa.ID,justificativa.DESCRICAO);"
			ng-repeat="justificativa in vm.DADOS.JUST_ESTACAO track by $index">
			@{{justificativa.DESCRICAO}}
		</div>
	</div>

</div>

@overwrite