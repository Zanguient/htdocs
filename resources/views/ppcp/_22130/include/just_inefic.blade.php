@extends('helper.include.view.modal', ['id' => 'modal-just-inefic'])

@section('modal-header-left')

<h4 class="modal-title">
	Justificar:
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

	<div style="border: 1px solid;" 
		class="alert alert2 alert-warning"
		ng-if="vm.MODAL.JUSTIFICATIVA_INEFIC.length > 0">
        	<b>Justificativas registradas:</b></p>
			<div ng-bind-html="vm.MODAL.JUSTIFICATIVA_INEFIC">
    		</div>
    </div>

	

	<div style="border: 1px solid;" 
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
			ng-click="vm.Acoes.justIneficiencia(justificativa.ID,justificativa.DESCRICAO);"
			ng-repeat="justificativa in vm.DADOS.JUST_INEFC track by $index">
			@{{justificativa.DESCRICAO}}
		</div>
	</div>

</div>

@overwrite