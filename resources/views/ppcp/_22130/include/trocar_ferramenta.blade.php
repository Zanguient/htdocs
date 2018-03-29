@extends('helper.include.view.modal', ['id' => 'modal-troca-ferramenta'])

@section('modal-header-left')

<h4 class="modal-title">
	Trocar Ferramenta:
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
	<label>Serie - Descricao:</label>
	
	<div class="erros-talao" style="margin-top: 0px;">
		<div class="alert alert2 alert-warning" ng-if="vm.FERRAMENTAS.length == 0">
			<b>Não há ferramentas disponiveis</b></p>
	    </div>
	</div>

	<button
		ng-repeat="ferramenta in vm.FERRAMENTAS track by $index"

		ng-click="vm.Acoes.trocaFerramenta(ferramenta);"
		style="height: 50px;margin: 5px; width: 48%;"
		type="button"
		class="btn @{{ ferramenta.STATUS_CONFLITO == 1 ? 'btn-danger' : 'btn-success'}} btn-confirmar"
		id="btn-confirmar-ferramenta">
		@{{ferramenta.SERIE}} - @{{ferramenta.DESCRICAO}}

	</button>

</div>

@overwrite