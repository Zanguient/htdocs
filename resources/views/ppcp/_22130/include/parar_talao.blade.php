@extends('helper.include.view.modal', ['id' => 'modal-parar-talao'])

@section('modal-header-left')

<h4 class="modal-title">
	Parar Talão @{{vm.TALAO_MODAL.REMESSA_TALAO_ID}} da Remessa @{{vm.TALAO_MODAL.REMESSA}}
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

	<div class="group-justificativa">
		<div class="div-justificativa"
			ng-class="{
						'marcar-justificativa' : justificativa.MARCAR == 1
					}"
			ng-click="vm.Acoes.paradaTalao(justificativa.ID,justificativa.DESCRICAO);"
			ng-repeat="justificativa in vm.DADOS.JUST_TALAO track by $index">
			@{{justificativa.DESCRICAO}}
		</div>
	</div>

</div>

@overwrite