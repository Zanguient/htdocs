@extends('helper.include.view.modal', [
	'id' => 'modal-consulta-workflow'
])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.modal-consulta-workflow-title') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button 
		type="button" 
		class="btn btn-default btn-voltar" 
		data-hotkey="f11"
		ng-click="$ctrl.fecharModal()">

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
						class="form-control input-maior input-filtrar-workflow" 
						placeholder="{{ Lang::get($menu.'.placeholder-filtrar') }}" 
						autocomplete="off"
						ng-model="$ctrl.filtrarWorkflow">

					<button 
						type="button" 
						class="input-group-addon btn-filtro" 
						tabindex="-1"
						ng-click="$ctrl.consultar()">

						<span class="fa fa-filter"></span>
					</button>

				</div>

			</div>

		</div>

		<div class="row">
		
			<div class="form-group">

				@include('workflow._29010.modal-consulta-table')

			</div>

		</div>
		
	</form>

@overwrite