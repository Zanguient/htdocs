@extends('helper.include.view.modal', ['id' => 'modal-setup'])

@section('modal-header-left')

<h4 class="modal-title">
	Setup:
</h4>

@overwrite

@section('modal-header-right')
	
	<button type="button" ng-click="vm.Acoes.fecharSetap()" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
		<span class="glyphicon glyphicon-chevron-left"></span> Voltar
	</button>

@overwrite

@section('modal-body')

<div class="form-group grup-setap">
	
	<div
		class="item-setup item-setup_@{{setup.SETUP_ID}}"
		ng-repeat="setup in vm.SETUP.SETUP track by $index"
		
		data-ID="@{{setup.ID}}"
		data-SETUP="@{{setup.SETUP}}"
		data-SETUP_ID="@{{setup.SETUP_ID}}"
		data-FINALIZADO="@{{setup.FINALIZADO}}"
		data-TEMPO_DECORRIDO="@{{setup.TEMPO_DECORRIDO}}"
		data-DATAHORA_INICIO="@{{setup.DATAHORA_INICIO}}"
		data-ULTIMO="@{{setup.ULTIMO}}"

		ng-if="setup.SETUP > 0"
	>
		
		<span class="desc-setup">@{{setup.DESCRICAO}}</span>
		<span class="tempo-setup">00:00:00</span>
		
		<button
			type="button" class="btn btn-success"
			ng-if="setup.FINALIZADO == 1 && setup.ANTERIOR == 0 && setup.TEMPO_DECORRIDO == 0"
			ng-click="vm.Acoes.iniciarSetup(setup.SETUP_ID,0,vm.SETUP)"
		>
			<span class="glyphicon glyphicon-play"></span>
		</button>

		<button
			type="button" class="btn btn-danger"
			ng-if="setup.FINALIZADO == 0 && setup.ULTIMO == 1 && setup.TEMPO_DECORRIDO == 0"
			ng-click="vm.Acoes.iniciarSetup(setup.SETUP_ID2,1,vm.SETUP)"
		>
			<span class="glyphicon glyphicon-stop"></span>
		</button>

		

	</div>


</div>

@overwrite