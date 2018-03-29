@extends('helper.include.view.modal', ['id' => 'modal-pedido-item'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.legend-pedido-item') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button 
		type="button" 
		class="btn btn-success" 
		data-hotkey="f3"
		ng-click="$ctrl.incluirPedidoItem()"
	>
		<span class="glyphicon glyphicon-ok"></span> 
		{{ Lang::get('master.confirmar') }}
	</button>

	<button 
		type="button" 
		class="btn btn-danger btn-cancelar" 
		data-hotkey="f11"
		ng-click="$ctrl.fecharModal()"
	>
		<span class="glyphicon glyphicon-ban-circle"></span> 
		{{ Lang::get('master.cancelar') }}
	</button>

@overwrite

@section('modal-body')

	<form class="form-inline">
		
		<div class="row">
			
			<div class="form-group">

				<label>{{ Lang::get($menu.'.label-modelo') }}:</label>

				<span 
					class="modelo-amostra-selec" 
					style="background-image: url(/assets/temp/modelo/{{ Auth::user()->CODIGO . '-' }}@{{ $ctrl.pedidoItem.modelo.MODELO_CODIGO }})"
					ng-if="$ctrl.pedidoItem.modelo.MODELO_CODIGO"
				></span>

				<div class="input-group">

					<input 
						type="text" 
						class="form-control input-maior modelo-selec" 
						autocomplete="off"
						required 
						readonly 
						ng-model="$ctrl.pedidoItem.modelo"
						ng-value="
							($ctrl.pedidoItem.modelo.MODELO_CODIGO) 
								? $ctrl.pedidoItem.modelo.MODELO_CODIGO +' - '+ $ctrl.pedidoItem.modelo.MODELO_DESCRICAO 
								: ''
						"
						ng-click="$ctrl.alterarModelo()"
					/>
					
					<button 
						type="button" 
						class="input-group-addon btn-filtro" 
						tabindex="-1" 
						ng-click="$ctrl.alterarModelo()"
					>
						<span class="fa fa-search"></span>
					</button>

				</div>

			</div>

			<div class="form-group">

				<label>{{ Lang::get($menu.'.label-cor') }}:</label>

				<span 
					class="cor-amostra-selec" 
					style="background-color: @{{ $ctrl.pedidoItem.cor.AMOSTRA | toColor }}"
					ng-class="{'sem-amostra': $ctrl.pedidoItem.cor.AMOSTRA < 1}"
					ng-if="$ctrl.pedidoItem.cor.CODIGO"
				></span>

				<div class="input-group">

					<input 
						type="text"
						class="form-control input-maior cor-selec"
						autocomplete="off" 
						required 
						readonly 
						ng-model="$ctrl.pedidoItem.cor"
						ng-value="
							('{{ $pu218 }}' != '1' && $ctrl.pedidoItem.cor.CODIGO)
								? $ctrl.pedidoItem.cor.CODIGO +' - '+ $ctrl.pedidoItem.cor.DESCRICAO
								: ('{{ $pu218 }}' == '1' && $ctrl.pedidoItem.cor.CODIGO)
								? $ctrl.pedidoItem.cor.CODIGO +' ('+ $ctrl.pedidoItem.cor.CONDICAO +')'
								: ''
						"
						ng-click="$ctrl.alterarCor()"
					/>
					
					<button 
						type="button" 
						class="input-group-addon btn-filtro btn-filtro-cor" 
						tabindex="-1"
						ng-click="$ctrl.alterarCor()"
					>
						<span class="fa fa-search"></span>
					</button>

				</div>

			</div>

			@php /* 
			<!--
			<div class="form-group">

				<label>{{ Lang::get($menu.'.label-produto') }}:</label>

				<input 
					type="text"
					class="form-control input-maior"
					autocomplete="off" 
					required 
					readonly 
					ng-model="$ctrl.pedidoItem.produto"
					ng-value="($ctrl.pedidoItem.produto.CODIGO) ? $ctrl.pedidoItem.produto.CODIGO +' - '+ $ctrl.pedidoItem.produto.DESCRICAO : ''"
				/>

			</div>
			-->
			@php */

		</div>

		<div class="row">

			<div class="form-group">

				<label>{{ Lang::get($menu.'.label-tamanho-grade') }}:</label>

				@include('vendas._12040.create.modal-pedido-item-table')

			</div>
		</div>

		<div class="row">

			<div class="form-group">

				<label>{{ Lang::get($menu.'.label-quantidade-total') }}:</label>


				<div class="input-group left-icon">

					<input 
						type="text" 
						class="form-control input-menor"
						readonly
						ng-model="$ctrl.quantidadeTotal"
						ng-value="$ctrl.quantidadeTotal | number"
					/>

					<div class="input-group-addon">@{{ $ctrl.pedidoItem.produto.UM }}</div>

				</div>

			</div>

			<div class="form-group">

				<label>{{ Lang::get($menu.'.label-valor-total') }}:</label>

				<div class="input-group left-icon">

					<div class="input-group-addon">R$</div>

					<input 
						type="text" 
						class="form-control" 
						readonly
						ng-model="$ctrl.valorTotal"
						ng-value="$ctrl.valorTotal | number:2"
					/>

				</div>

			</div>

		</div>
	</form>

	<modelo-por-cliente-27020></modelo-por-cliente-27020>

	<cor-por-modelo-27030></cor-por-modelo-27030>

@overwrite