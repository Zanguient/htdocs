<div class="table-filter">
	
	<div ng-if="$ctrl.pu225 == '1'">

		<label class="lbl-checkbox">
			<input 
	   			type="checkbox" 
	   			class="form-control"
	   			ng-checked="$ctrl.Index.filtroResposta.TODOS_CCUSTO == 1"
	   			ng-click="$ctrl.Index.filtroResposta.TODOS_CCUSTO = ($ctrl.Index.filtroResposta.TODOS_CCUSTO == 0 ? 1 : 0)">

	   		{{ Lang::get($menu.'.label-todos-ccusto') }}
   		</label>
	</div>

	<div>
		<label>{{ Lang::get('master.periodo') }}:</label>
		
		<input 
			type="date" 
			class="data-ini"
			ng-model="$ctrl.Index.filtroResposta.DATA_INI_INPUT">

		<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>

		<input 
			type="date" 
			class="data-fim"
			ng-model="$ctrl.Index.filtroResposta.DATA_FIM_INPUT">
	</div>
	
	<button 
		type="button" 
		class="btn btn-sm btn-primary btn-filtrar" 
		id="btn-table-filter"
		data-hotkey="alt+f" 
		ng-click="$ctrl.Index.filtrarResposta()">

		<span class="glyphicon glyphicon-filter"></span>
		{{ Lang::get('master.filtrar') }}
	</button>

	<div class="filtro-resposta-container">

		<div class="input-group input-group-filtro-obj">

			<input 
				type="search" 
				class="form-control filtro-obj input-maior" 
				placeholder="{{ Lang::get('master.pesq-place') }}" 
				autocomplete="off"
				ng-model="$ctrl.filtroTabelaResposta" 
				ng-init="$ctrl.filtroTabelaResposta = ''">

			<button type="button" class="input-group-addon btn-filtro btn-filtro-obj" tabindex="-1">
				<span class="fa fa-search"></span>
			</button>

		</div>

	</div>

</div>