@extends('helper.include.view.modal', ['id' => 'modal-pesq-colaborador'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.h4-colaborador') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button 
		type="button" 
		class="btn btn-default btn-voltar"
		data-hotkey="f11"
		ng-click="$ctrl.CreateColaborador.fecharModal()">

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
						id="input-filtrar-colaborador"
						class="form-control input-maior js-input-filtrar-colaborador" 
						placeholder="{{ Lang::get($menu.'.placeholder-filtrar') }}" 
						autocomplete="off"
						ng-change="$ctrl.CreateColaborador.fixVsRepeatPesqColaborador()"
						ng-model="$ctrl.CreateColaborador.filtrarColaborador" 
						ng-init="$ctrl.CreateColaborador.filtrarColaborador = ''">

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

			<div class="table-ec table-colaborador">

				<table class="table table-striped table-bordered table-body">

					<thead>
						
						<tr>
							<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
							<th class="nome">{{ Lang::get($menu.'.th-nome') }}</th>
							<th class="setor">{{ Lang::get($menu.'.th-setor') }}</th>
						</tr>

					</thead>
										
					<tbody vs-repeat vs-scroll-parent=".table-ec">
						
						<tr 
							ng-repeat="colaborador in $ctrl.CreateColaborador.listaColaborador | orderBy: 'PESSOAL_NOME' | filter: $ctrl.CreateColaborador.filtrarColaborador"
							ng-click="$ctrl.CreateColaborador.selecionarColaborador(colaborador)"
							ng-keypress="$event.keyCode == 13 ? $ctrl.CreateColaborador.selecionarColaborador(colaborador) : null">

							<td 
								class="text-right id"
								ng-bind="colaborador.CODIGO | lpad:[6,'0']"></td>

							<td 
								class="nome"
								ng-bind="colaborador.PESSOAL_NOME"></td>

							<td 
								class="setor"
								ng-bind="colaborador.CENTRO_DE_CUSTO_DESCRICAO"></td>

						</tr>

					</tbody>

				</table>

			</div>

		</div>

	</form>

@overwrite