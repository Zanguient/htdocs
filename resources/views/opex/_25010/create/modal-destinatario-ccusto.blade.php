@extends('helper.include.view.modal', ['id' => 'modal-destinatario-ccusto'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.legend-destinatario-ccusto') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="f11">
		<span class="glyphicon glyphicon-chevron-left"></span> 
		{{ Lang::get($menu.'.button-voltar') }}
	</button>

@overwrite

@section('modal-body')

	<form class="form-inline">
		
		<div class="row">

			<div class="form-group">
				<div class="input-group">
					<input 
						type="search"
						id="input-filtrar-ccusto" 
						class="form-control input-maior" 
						placeholder="{{ Lang::get($menu.'.placeholder-filtrar') }}" 
						autocomplete="off" 
						ng-model="vm.filtrarCCusto" 
						ng-init="vm.filtrarCCusto = ''" 
					/>
					<button type="button" class="btn input-group-addon btn-filtro" tabindex="-1"><span class="fa fa-search"></span></button>
				</div>
			</div>

		</div>

		<div class="row">

			<div class="table-ec table-selec-ccusto">

				<table class="table table-bordered table-header">

					<thead>
						<tr>
							<th class="chk">
								<input 
									type="checkbox" 
									ng-checked="vm.todosCCustoSelecionado == true"
									ng-click="vm.selecionarTodosCCusto()">
							</th>
							<th class="ccusto-mask">{{ Lang::get($menu.'.th-id') }}</th>
							<th class="ccusto-descricao">{{ Lang::get($menu.'.th-ccusto') }}</th>
						</tr>
					</thead>

					<tbody vs-repeat vs-scroll-parent=".table-selec-ccusto">
						<tr 
							ng-repeat="ccusto in vm.listaCCusto | filter: vm.filtrarCCusto"
							ng-click="vm.selecionarCCusto(ccusto)">

							<td class="chk">
								<input 
									type="checkbox" 
									ng-checked="vm.listaCCustoSelec.indexOf(ccusto) > -1">
							</td>
							<td class="ccusto-mask">@{{ ccusto.MASK }}</td>
							<td class="ccusto-descricao">@{{ ccusto.DESCRICAO }}</td>
						</tr>
					</tbody>

				</table>

			</div>

		</div>

	</form>

@overwrite