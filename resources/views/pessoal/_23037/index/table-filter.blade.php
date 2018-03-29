<div class="table-filter">

	<div>
		<label>{{ Lang::get('master.status') }}:</label>
		<select
			ng-model="$ctrl.Index.filtroBase.STATUS">

			<option value="">{{ Lang::get('master.todos') }}</option>
			<option value="1">{{ Lang::get('master.ativo') }}</option>
			<option value="0">{{ Lang::get('master.inativo') }}</option>
		</select>
	</div>

	<div ng-if="$ctrl.pu225 == '1'">

		<label class="lbl-checkbox">
			<input 
	   			type="checkbox" 
	   			class="form-control"
	   			ng-checked="$ctrl.Index.filtroBase.TODOS_CCUSTO == 1"
	   			ng-click="$ctrl.Index.filtroBase.TODOS_CCUSTO = ($ctrl.Index.filtroBase.TODOS_CCUSTO == 0 ? 1 : 0)">

	   		{{ Lang::get($menu.'.label-todos-ccusto') }}
   		</label>
	</div>
	
	<div>
		<label>{{ Lang::get('master.periodo') }}:</label>
		
		<input 
			type="date" 
			class="data-ini"
			ng-model="$ctrl.Index.filtroBase.DATA_INI_INPUT">

		<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>

		<input 
			type="date" 
			class="data-fim"
			ng-model="$ctrl.Index.filtroBase.DATA_FIM_INPUT">
	</div>
	
	<button 
		type="button" 
		class="btn btn-sm btn-primary btn-filtrar" 
		id="btn-table-filter"
		data-hotkey="alt+f" 
		ng-click="$ctrl.Index.filtrarBase()">

		<span class="glyphicon glyphicon-filter"></span>
		{{ Lang::get('master.filtrar') }}
	</button>

</div>