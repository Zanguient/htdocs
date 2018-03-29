<fieldset 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<div class="row">

		<div class="form-group" ng-if="$ctrl.Create.indicadorPorCCusto.ID > 0">

			<label>{{ Lang::get($menu.'.label-id') }}:</label>

			<input 
				type="text" 
				class="form-control input-menor" 
				disabled 
				ng-model="$ctrl.Create.indicadorPorCCusto.ID"
				ng-value="$ctrl.Create.indicadorPorCCusto.ID | lpad:[5,'0']">

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-periodo') }}:</label>

			<input 
				type="date" 
				class="form-control js-input-focus"
				required 
				ng-model="$ctrl.Create.indicadorPorCCusto.DATA_INI_INPUT">

			<span>{{ Lang::get($menu.'.label-a') }}</span>

			<input 
				type="date" 
				class="form-control"
				required 
				ng-model="$ctrl.Create.indicadorPorCCusto.DATA_FIM_INPUT">

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-ccusto') }}:</label>

			<div class="input-group">
			
				<input 
					type="text" 
					class="form-control input-maior normal-case" 
					required 
					readonly 
					ng-model="$ctrl.Create.indicadorPorCCusto.CCUSTO"
					ng-value="
						($ctrl.Create.indicadorPorCCusto.CCUSTO.MASK)
							? $ctrl.Create.indicadorPorCCusto.CCUSTO.MASK +' - '+ $ctrl.Create.indicadorPorCCusto.CCUSTO.DESCRICAO
							: ''
					"
					ng-class="{alterando: $ctrl.tipoTela != 'exibir'}"
					ng-click="$ctrl.CreateCCusto.exibirModal()">

				<button 
					type="button" 
					class="btn input-group-addon btn-filtro" 
					tabindex="-1"
					ng-click="$ctrl.CreateCCusto.exibirModal()">

					<span class="fa fa-search"></span>

				</button>

			</div>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-indicador') }}:</label>

			<div class="input-group">

				<input 
					type="text" 
					class="form-control input-maior normal-case" 
					required 
					readonly 
					ng-model="$ctrl.Create.indicadorPorCCusto.INDICADOR"
					ng-value="
						($ctrl.Create.indicadorPorCCusto.INDICADOR.ID)
							? ($ctrl.Create.indicadorPorCCusto.INDICADOR.ID | lpad:[5,'0']) +' - '+ $ctrl.Create.indicadorPorCCusto.INDICADOR.TITULO
							: ''
					"
					ng-class="{alterando: $ctrl.tipoTela != 'exibir'}"
					ng-click="$ctrl.CreateIndicador.exibirModal()">

				<button 
					type="button" 
					class="btn input-group-addon btn-filtro" 
					tabindex="-1"
					ng-click="$ctrl.CreateIndicador.exibirModal()">

					<span class="fa fa-search"></span>

				</button>

			</div>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-perc-indicador') }}:</label>

			<input 
				type="number" 
				class="form-control input-menor" 
				min="0"
				step="0.01" 
				required 
				ng-model="$ctrl.Create.indicadorPorCCusto.PERC_INDICADOR"
				string-to-number>

		</div>

	</div>

</fieldset>