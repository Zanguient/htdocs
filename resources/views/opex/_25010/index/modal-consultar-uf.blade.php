@extends('helper.include.view.modal', [
	'id' => 'modal-consultar-uf'
])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.modal-title-uf') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button 
		type="button" 
		class="btn btn-default btn-voltar" 
		data-hotkey="f11"
		ng-click="vm.fecharModalConsultarUF()">
		
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
						class="form-control input-maior input-filtrar-uf" 
						placeholder="{{ Lang::get($menu.'.placeholder-filtrar') }}" 
						autocomplete="off"
						ng-model="vm.filtrarUF"
						ng-change="vm.fixVsRepeatConsultarUF()">

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

				@include('opex._25010.index.modal-consultar-uf-table')

			</div>

		</div>
		
	</form>

@overwrite