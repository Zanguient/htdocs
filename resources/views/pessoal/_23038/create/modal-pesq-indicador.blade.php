@extends('helper.include.view.modal', ['id' => 'modal-pesq-indicador'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.h4-indicador') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button 
		type="button" 
		class="btn btn-default btn-voltar"
		data-hotkey="f11"
		ng-click="$ctrl.CreateIndicador.fecharModal()">

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
						id="input-filtrar-indicador"
						class="form-control input-maior js-input-filtrar-indicador" 
						placeholder="{{ Lang::get($menu.'.placeholder-filtrar') }}" 
						autocomplete="off"
						ng-change="$ctrl.CreateIndicador.fixVsRepeatPesqIndicador()"
						ng-model="$ctrl.CreateIndicador.filtrarIndicador" 
						ng-init="$ctrl.CreateIndicador.filtrarIndicador = ''">

					<button 
						type="button" 
						class="btn input-group-addon btn-filtro" 
						tabindex="-1">

						<span class="fa fa-search"></span>
					</button>

				</div>

			</div>

		</div>

		<div class="row">

			<div class="table-ec table-pesq-indicador">

				<table class="table table-striped table-bordered table-body">

					<thead>
						
						<tr>
							<th class="id text-right">{{ Lang::get($menu.'.th-id') }}</th>
							<th class="titulo">{{ Lang::get($menu.'.th-titulo') }}</th>
						</tr>

					</thead>
										
					<tbody vs-repeat vs-scroll-parent=".table-ec">
						
						<tr 
							ng-repeat="indicador in $ctrl.CreateIndicador.listaIndicador | orderBy: 'TITULO' | filter: $ctrl.CreateIndicador.filtrarIndicador"
							ng-click="$ctrl.CreateIndicador.selecionarIndicador(indicador)"
							ng-keypress="$event.keyCode == 13 ? $ctrl.CreateIndicador.selecionarIndicador(indicador) : null">

							<td 
								class="id text-right"
								ng-bind="indicador.ID | lpad:[5,'0']"></td>

							<td 
								class="descricao"
								ng-bind="indicador.TITULO"></td>

						</tr>

					</tbody>

				</table>

			</div>

		</div>

	</form>

@overwrite