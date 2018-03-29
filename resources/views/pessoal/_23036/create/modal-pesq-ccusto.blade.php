@extends('helper.include.view.modal', ['id' => 'modal-pesq-ccusto'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.h4-ccusto') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button 
		type="button" 
		class="btn btn-default btn-voltar"
		data-hotkey="f11"
		ng-click="$ctrl.CreateCCusto.fecharModal()">

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
						id="input-filtrar-ccusto"
						class="form-control input-maior js-input-filtrar-ccusto" 
						placeholder="{{ Lang::get($menu.'.placeholder-filtrar') }}" 
						autocomplete="off"
						ng-change="$ctrl.CreateCCusto.fixVsRepeatPesqCCusto()"
						ng-model="$ctrl.CreateCCusto.filtrarCCusto" 
						ng-init="$ctrl.CreateCCusto.filtrarCCusto = ''">

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

			<div class="table-ec table-pesq-ccusto">

				<table class="table table-striped table-bordered table-body">

					<thead>
						
						<tr>
							<th class="chk"></th>
							<th class="mask">{{ Lang::get($menu.'.th-id') }}</th>
							<th class="descricao">{{ Lang::get($menu.'.th-descricao') }}</th>
						</tr>

					</thead>
										
					<tbody vs-repeat vs-scroll-parent=".table-ec">
						
						<tr 
							ng-repeat="ccusto in $ctrl.CreateCCusto.listaCCusto | filter: $ctrl.CreateCCusto.filtrarCCusto"
							ng-click="$ctrl.CreateCCusto.selecionarCCusto(ccusto)"
							ng-keypress="$event.keyCode == 13 ? $ctrl.CreateCCusto.selecionarCCusto(ccusto) : null">

							<td class="chk">

								<input 
									type="checkbox" 
									ng-checked="$ctrl.Create.avaliacao.BASE.CCUSTO.indexOf(ccusto) > -1">
							</td>

							<td 
								class="mask"
								ng-bind="ccusto.MASK"></td>

							<td 
								class="descricao"
								ng-bind="ccusto.DESCRICAO"></td>

						</tr>

					</tbody>

				</table>

			</div>

		</div>

	</form>

@overwrite