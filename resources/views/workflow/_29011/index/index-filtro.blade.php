<div class="table-filter">

	<div>
		<label>{{ Lang::get('master.status') }}:</label>
		<select
			ng-model="$ctrl.filtro.status">

			<option value="">{{ Lang::get('master.todos') }}</option>
			<option value="1">{{ Lang::get('master.ativo') }}</option>
			<option value="0">{{ Lang::get('master.inativo') }}</option>
		</select>
	</div>
	
	<div>
		<label>{{ Lang::get('master.periodo') }}:</label>
		
		<input 
			type="date" 
			class="data-ini"
			ng-model="$ctrl.filtro.dataIni">

		<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>

		<input 
			type="date" 
			class="data-fim"
			ng-model="$ctrl.filtro.dataFim">
	</div>
	
	<button 
		type="button" 
		class="btn btn-sm btn-primary btn-filtrar" 
		id="btn-table-filter"
		data-hotkey="alt+f" 
		ng-click="$ctrl.consultarItem()">

		<span class="glyphicon glyphicon-filter"></span>
		{{ Lang::get('master.filtrar') }}
	</button>

</div>