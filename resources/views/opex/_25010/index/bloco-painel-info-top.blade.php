<div class="painel-info-top">

	<label ng-bind="(vm.painelTop.FORMULARIO_ID | lpad:['5',0]) +' - '+ vm.painelTop.FORMULARIO_TITULO"></label>

	<div 
		class="painel-filtro table-filter"
		ng-if="vm.formulario.TIPO == 3">

		<div>
			<label>{{ Lang::get('master.periodo') }}:</label>

			<input 
				type="date" 
				class="data-ini"
				ng-model="vm.painelFiltro.DATA_INI_INPUT">

			<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>

			<input 
				type="date" 
				class="data-fim"
				ng-model="vm.painelFiltro.DATA_FIM_INPUT">
		</div>

		<div>
			<label>{{ Lang::get($menu.'.label-representante') }}:</label>

			<div class="input-group">

				<input 
					type="text" 
					class="form-control input-maior-min js-input-filtrar-representante" 
					autocomplete="off" 
					readonly 
					ng-model="vm.painelFiltro.REPRESENTANTE"
					ng-value="
						(vm.painelFiltro.REPRESENTANTE.CODIGO) 
							? (vm.painelFiltro.REPRESENTANTE.CODIGO | lpad:[5,'0']) +' - '+ vm.painelFiltro.REPRESENTANTE.RAZAOSOCIAL 
							: ''
					"
					ng-click="vm.alterarRepresentante()"
					ng-keydown="vm.eventoAlterarRepresentante($event)">
				
				<button 
					type="button" 
					class="input-group-addon btn-filtro btn-filtro-limpar" 
					tabindex="-1" 
					ng-click="vm.limparRepresentanteSelecionado()"
					ng-if="vm.painelFiltro.REPRESENTANTE.CODIGO">

					<span class="fa fa-close"></span>
				</button>

				<button 
					type="button" 
					class="input-group-addon btn-filtro" 
					tabindex="-1" 
					ng-click="vm.alterarRepresentante()">

					<span class="fa fa-search"></span>
				</button>

			</div>
		</div>

		<div>
			<label>{{ Lang::get($menu.'.label-uf') }}:</label>

			<div class="input-group">

				<input 
					type="text" 
					class="form-control input-menor js-input-filtrar-uf" 
					autocomplete="off" 
					readonly 
					ng-model="vm.painelFiltro.UF"
					ng-value="(vm.painelFiltro.UF.UF) ? vm.painelFiltro.UF.UF : ''"
					ng-click="vm.alterarUF()"
					ng-keydown="vm.eventoConsultarUF($event)">
				
				<button 
					type="button" 
					class="input-group-addon btn-filtro btn-filtro-limpar" 
					tabindex="-1" 
					ng-click="vm.limparUFSelecionado()"
					ng-if="vm.painelFiltro.UF.UF">

					<span class="fa fa-close"></span>
				</button>

				<button 
					type="button" 
					class="input-group-addon btn-filtro" 
					tabindex="-1" 
					ng-click="vm.alterarUF()">

					<span class="fa fa-search"></span>
				</button>

			</div>
		</div>

		<button 
			type="button" 
			class="btn btn-sm btn-primary btn-filtrar" 
			data-hotkey="alt+l"
			ng-click="vm.painelCliente()">

			<span class="glyphicon glyphicon-filter"></span>
			{{ Lang::get('master.filtrar') }}
		</button>

	</div>
</div>