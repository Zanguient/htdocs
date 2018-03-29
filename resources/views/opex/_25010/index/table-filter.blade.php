<div class="table-filter">

	<div>
		<label>{{ Lang::get('master.status') }}:</label>
		<select
			ng-model="vm.filtro.STATUS">

			<option value="">{{ Lang::get('master.todos') }}</option>
			<option value="1">{{ Lang::get('master.ativo') }}</option>
			<option value="0">{{ Lang::get('master.inativo') }}</option>
		</select>
	</div>

	<div>
		<label>{{ Lang::get($menu.'.label-tipo') }}:</label>

		<select 
			class="form-control" 
			ng-model="vm.filtro.TIPO"
			ng-disabled="vm.urlTipoForm == 3">
			
			{{-- 
				Se o tipo 3 for passado na url, exibe todos os tipos (pesq. cliente);
				Senão, não exibe o tipo 3 (pesq. clima e satisf.).
			--}}
			<option 
				ng-repeat="tipoForm in vm.tipoFormulario track by $index"
				ng-if="((vm.urlTipoForm == 3) || (vm.urlTipoForm != 3 && tipoForm.ID != 3))"
				ng-value="tipoForm.ID"
				ng-bind="tipoForm.DESCRICAO"></option>

		</select>
	</div>
	
	<div>
		<label>{{ Lang::get('master.periodo') }}:</label>
		
		<input 
			type="date" 
			class="data-ini"
			ng-model="vm.filtro.DATA_INI">

		<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>

		<input 
			type="date" 
			class="data-fim"
			ng-model="vm.filtro.DATA_FIM">
	</div>
	
	<button 
		type="button" 
		class="btn btn-sm btn-primary btn-filtrar" 
		id="btn-table-filter"
		data-hotkey="alt+f" 
		ng-click="vm.listarFormulario()">

		<span class="glyphicon glyphicon-filter"></span>
		{{ Lang::get('master.filtrar') }}
	</button>

</div>