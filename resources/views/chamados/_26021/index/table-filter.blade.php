<div class="table-filter">

	<div>
		<label>{{ Lang::get('master.status') }}:</label>
		<select
			ng-model="$ctrl.Index.filtro.STATUS">

			<option value="">{{ Lang::get('master.todos') }}</option>
			<option value="1">{{ Lang::get($menu.'.option-primeira-avaliacao') }}</option>
			<option value="2">{{ Lang::get($menu.'.option-segunda-avaliacao') }}</option>
		</select>
	</div>

	<div>
		<label>{{ Lang::get($menu.'.label-cliente') }}:</label>

		<div class="input-group">

			<input 
				type="text" 
				class="form-control input-maior-min js-input-filtro-cliente" 
				autocomplete="off" 
				readonly 
				ng-model="$ctrl.Index.filtro.CLIENTE"
				ng-value="
					($ctrl.Index.filtro.CLIENTE.ID) 
						? ($ctrl.Index.filtro.CLIENTE.ID | lpad:[5,'0']) +' - '+ $ctrl.Index.filtro.CLIENTE.RAZAOSOCIAL 
						: ''
				"
				ng-click="$ctrl.Create.alterarCliente(true)"
				ng-keydown="$ctrl.Index.eventoFiltrarCliente($event)">
			
			<button 
				type="button" 
				class="input-group-addon btn-filtro btn-filtro-limpar" 
				tabindex="-1" 
				ng-click="$ctrl.Create.limparClienteSelecionado()"
				ng-if="$ctrl.Index.filtro.CLIENTE.ID">

				<span class="fa fa-close"></span>
			</button>

			<button 
				type="button" 
				class="input-group-addon btn-filtro" 
				tabindex="-1" 
				ng-click="$ctrl.Create.alterarCliente(true)">

				<span class="fa fa-search"></span>
			</button>

		</div>
	</div>
	
	<div>
		<label>{{ Lang::get('master.periodo') }}:</label>
		
		<input 
			type="date" 
			class="data-ini"
			ng-model="$ctrl.Index.filtro.DATA_INI_INPUT">

		<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>

		<input 
			type="date" 
			class="data-fim"
			ng-model="$ctrl.Index.filtro.DATA_FIM_INPUT">
	</div>
	
	<button 
		type="button" 
		class="btn btn-sm btn-primary btn-filtrar" 
		id="btn-table-filter"
		data-hotkey="alt+f" 
		ng-click="$ctrl.Index.filtrar()">

		<span class="glyphicon glyphicon-filter"></span>
		{{ Lang::get('master.filtrar') }}
	</button>

</div>