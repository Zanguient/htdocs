@extends('helper.include.view.modal', ['id' => 'modal-liberacao'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.titulo-liberacao') }}
	</h4>

@overwrite

@section('modal-header-right')

	@include('vendas._12040.index.botao-acao-liberacao')

@overwrite

@section('modal-body')

	<div class="row">
		
		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-chave') }}:</label>
			<input 
				type="number" 
				class="form-control" 
				min="1"
				required 
				ng-model="$ctrl.liberacao.CHAVE" />
		</div>

	</div>

	<div 
		class="row bloco-cor"
		ng-repeat="cor in $ctrl.liberacao.COR track by $index">

		<div class="form-group">

			<label ng-if="$index == 0">{{ Lang::get($menu.'.label-cor') }}:</label>

			<span 
				class="cor-amostra-selec" 
				style="background-color: @{{ cor.AMOSTRA | toColor }}"
				ng-class="{'sem-amostra': cor.AMOSTRA < 1}"
				ng-if="cor.CODIGO"></span>

			<div class="input-group">

				<input 
					type="text"
					class="form-control cor-selec"
					autocomplete="off" 
					required 
					readonly 
					ng-model="cor"
					ng-value="
						(cor.CODIGO)
							? cor.CODIGO +' - '+ cor.DESCRICAO
							: ''
					"
					ng-disabled="cor.CODIGO && $ctrl.liberacao.COR.length > 1"
					ng-click="$ctrl.consultarCorLiberacao()" />
				
				<button 
					type="button" 
					class="input-group-addon btn-filtro btn-filtro-cor" 
					tabindex="-1"
					ng-disabled="cor.CODIGO && $ctrl.liberacao.COR.length > 1"
					ng-click="$ctrl.consultarCorLiberacao()">

					<span class="fa fa-search"></span>
				</button>

			</div>
		</div>

		<div class="form-group">

			<label ng-if="$index == 0">{{ Lang::get($menu.'.label-quantidade') }}:</label>

			<input 
				type="number" 
				class="form-control input-menor" 
				min="1" 
				required 
				ng-model="cor.QUANTIDADE" />

			<button 
				type="button" 
				class="btn btn-danger btn-excluir-cor" 
				title="{{ Lang::get($menu.'.title-excluir-cor') }}"
				ng-click="$ctrl.excluirCor($index)"
				ng-if="$ctrl.liberacao.COR.length > 1">

				<span class="glyphicon glyphicon-trash"></span>
			</button>

		</div>
			
	</div>

	<div class="row">

		<button 
			type="button" 
			class="btn btn-sm btn-info" 
			title="{{ Lang::get($menu.'.title-add-cor') }}"
			data-hotkey="alt+a"
			ng-click="$ctrl.addCor()"
			ng-disabled="!$ctrl.liberacao.COR[0].CODIGO">

			<span class="glyphicon glyphicon-plus"></span>
			{{ Lang::get($menu.'.button-add-cor') }}
		</button>
	</div>

	<consultar-cor-27030></consultar-cor-27030>

@overwrite
