<div class="table-filter">

	<div ng-if="'{{ Auth::user()->CLIENTE_ID }}' == '' && $ctrl.representanteId === null">

		<label>{{ Lang::get($menu.'.label-representante') }}:</label>

		<div class="input-group">

			<input 
				type="text" 
				class="form-control input-maior" 
				autocomplete="off" 
				readonly 
				ng-model="$ctrl.filtro.representante"
				ng-value="($ctrl.filtro.representante.CODIGO) ? ($ctrl.filtro.representante.CODIGO | lpad:[5,'0']) +' - '+ $ctrl.filtro.representante.RAZAOSOCIAL : ''"
				ng-click="$ctrl.alterarRepresentante()"
			/>
			
			<button 
				type="button" 
				class="input-group-addon btn-filtro" 
				tabindex="-1" 
				ng-click="$ctrl.alterarRepresentante()"
			>
				<span class="fa fa-search"></span>
			</button>

		</div>

	</div>

	<div ng-if="'{{ Auth::user()->CLIENTE_ID }}' == ''">

		<label>{{ Lang::get($menu.'.label-cliente-filtro') }}:</label>

		<div class="input-group">

			<input 
				type="text" 
				class="form-control input-maior" 
				autocomplete="off" 
				readonly 
				ng-model="$ctrl.filtroCliente.cliente"
				ng-value="($ctrl.filtroCliente.cliente.CODIGO) ? ($ctrl.filtroCliente.cliente.CODIGO | lpad:[5,'0']) +' - '+ $ctrl.filtroCliente.cliente.RAZAOSOCIAL : ''"
				ng-click="$ctrl.alterarCliente()"
			/>
			
			<button 
				type="button" 
				class="input-group-addon btn-filtro" 
				tabindex="-1" 
				ng-click="$ctrl.alterarCliente()"
			>
				<span class="fa fa-search"></span>
			</button>

		</div>

	</div>

	<div >

		<label>{{ Lang::get('master.periodo') }}:</label>
		
		<input 
			type="date" 
			class="data-ini"
			ng-model="$ctrl.dataIni"
			ng-disabled="$ctrl.tipo_data == 3"
		/>

		<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>

		<input 
			type="date" 
			class="data-fim"
			ng-model="$ctrl.dataFim"
			ng-disabled="$ctrl.tipo_data == 3"
		/>

	</div>

	<div class="" style="background-color: beige;border-radius: 7px;padding: 3px;">
		<input type="radio" ng-model="$ctrl.tipo_data" ng-value="1"> <label > Dt. Emissão</label>
	    <input type="radio" ng-model="$ctrl.tipo_data" ng-value="2"> <label > Dt. Cliente</label>
	    <input type="radio" ng-model="$ctrl.tipo_data" title="ignorar período" ng-value="3"> <label > Todos</label>
	</div>
	
	<div>
		<label>Pedido:</label>
		<input 
				type="text" 
				class="form-control input-menor"  
				ng-model="$ctrl.pedidoIdUrl"
			/>
	</div>

	<div>
		<label>Pedido Cli:</label>
		<input 
				type="text" 
				class="form-control input-menor"  
				ng-model="$ctrl.pedidoCliente"
			/>
	</div>

	<div style="display: inline-flex;">
		<div class="">
			<div>
		        <input type="checkbox" ng-model="$ctrl.faturado">
	            <label > Só Pedidos a faturar</label>
	        </div>
	    </div>
	</div>

    
	
	<button 
		type="button" 
		class="btn btn-sm btn-primary btn-filtrar" 
		id="btn-table-filter"
		data-hotkey="alt+f" 
		ng-click="$ctrl.consultarPedido()"
	>
		<span class="glyphicon glyphicon-filter"></span>
		{{ Lang::get('master.filtrar') }}
	</button>

</div>

<representante-12060></representante-12060>
<cliente-por-representante-12070></cliente-por-representante-12070>