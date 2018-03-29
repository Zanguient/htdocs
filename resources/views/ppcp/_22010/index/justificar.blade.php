@extends('helper.include.view.modal', ['id' => 'modal-justificar'])

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

	<div class="group-justificativa">
		<div class="div-justificativa"
		    ng-click="vm.TalaoDefeito.justificar(justificativa.DESCRICAO, justificativa.ID)"
			ng-repeat="justificativa in vm.TalaoDefeito.justificativa track by $index">
			@{{justificativa.DESCRICAO}}
		</div>
	</div>

</div>

@overwrite