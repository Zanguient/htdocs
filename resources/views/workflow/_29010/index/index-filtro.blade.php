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
	
	<button 
		type="button" 
		class="btn btn-sm btn-primary btn-filtrar" 
		id="btn-table-filter"
		data-hotkey="alt+f" 
		ng-click="$ctrl.consultarWorkflow()"
	>
		<span class="glyphicon glyphicon-filter"></span>
		{{ Lang::get('master.filtrar') }}
	</button>

</div>