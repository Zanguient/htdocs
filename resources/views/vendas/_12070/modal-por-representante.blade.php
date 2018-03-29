@extends('helper.include.view.modal', [
	'id' => 'modal-por-representante'
])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.modal-title') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button 
		type="button" 
		class="btn btn-default btn-voltar" 
		data-hotkey="f11"
		ng-click="$ctrl.fecharModal()"
	>
		<span class="glyphicon glyphicon-chevron-left"></span> 
		{{ Lang::get('master.voltar') }}
	</button>

@overwrite

@section('modal-body')

	<form class="form-inline">
		
		<div class="row">

			<div class="form-group">

				<div class="input-group">

					<input 
						type="search" 
						class="form-control input-maior input-filtrar-cliente" 
						placeholder="{{ Lang::get($menu.'.placeholder-filtrar') }}" 
						autocomplete="off"
						ng-model="$ctrl.filtrarCliente" 
					/>
					<button 
						type="button" 
						class="input-group-addon btn-filtro" 
						tabindex="-1"
						ng-click="$ctrl.consultarClientePorRepresentante()"
					>
						<span class="fa fa-filter"></span>
					</button>

				</div>

			</div>

		</div>

		<div class="row">
		
			<div class="form-group">

				@include('vendas._12070.modal-por-representante-table')

			</div>

		</div>
		
	</form>

@overwrite