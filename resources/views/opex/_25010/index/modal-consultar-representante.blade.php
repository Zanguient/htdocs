@extends('helper.include.view.modal', [
	'id' => 'modal-consultar-representante'
])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.modal-title-representante') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button 
		type="button" 
		class="btn btn-default btn-voltar" 
		data-hotkey="f11"
		ng-click="vm.fecharModalConsultarRepresentante()">
		
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
						class="form-control input-maior input-filtrar-representante" 
						placeholder="{{ Lang::get($menu.'.placeholder-filtrar') }}" 
						autocomplete="off"
						ng-model="vm.filtrarRepresentante"
						ng-change="vm.fixVsRepeatConsultarRepresentante()">

					<button 
						type="button" 
						class="input-group-addon btn-filtro" 
						tabindex="-1">

						<span class="fa fa-filter"></span>
					</button>

				</div>

			</div>

		</div>

		<div class="row">
		
			<div class="form-group">

				@include('opex._25010.index.modal-consultar-representante-table')

			</div>

		</div>
		
	</form>

@overwrite