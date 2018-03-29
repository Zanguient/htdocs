<fieldset 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend 
		ng-class="{
			'legend-pesquisa-sem-nota': $ctrl.Create.pesquisa.SATISFACAO >= 0,
			'legend-pesquisa-com-nota': ($ctrl.Create.pesquisa.SATISFACAO >= 0 && $ctrl.Create.pesquisa.NOTA_DELFA)
		}">

		{{ Lang::get($menu.'.legend-pesquisa') }}</legend>

	<div class="row">

		<div class="form-group" ng-if="$ctrl.Create.pesquisa.ID > 0">

			<label>{{ Lang::get($menu.'.label-id') }}:</label>

			<input 
				type="text" 
				class="form-control input-menor" 
				disabled 
				ng-model="$ctrl.Create.pesquisa.ID"
				ng-value="$ctrl.Create.pesquisa.ID | lpad:[5,'0']">

		</div>

		<div class="form-group" ng-if="$ctrl.Create.pesquisa.ID > 0">

			<label>{{ Lang::get($menu.'.label-data-criacao') }}:</label>

			<input 
				type="date" 
				class="form-control" 
				disabled 
				ng-model="$ctrl.Create.pesquisa.DATAHORA_INSERT_INPUT">

		</div>

		<div class="form-group" ng-if="$ctrl.Create.pesquisa.ID > 0">

			<label>{{ Lang::get($menu.'.label-cliente') }}:</label>

			<input 
				type="text" 
				class="form-control input-maior"
				disabled
				ng-model="$ctrl.Create.pesquisa.CLIENTE"
				ng-value="
					($ctrl.Create.pesquisa.CLIENTE.ID) 
						? ($ctrl.Create.pesquisa.CLIENTE.ID | lpad:[5,'0']) +' - '+ $ctrl.Create.pesquisa.CLIENTE.RAZAOSOCIAL 
						: ''
				">

		</div>

		<div class="form-group" ng-if="!$ctrl.Create.pesquisa.ID">

			<label>{{ Lang::get($menu.'.label-formulario') }}:</label>

			<div class="input-group">

				<input 
					type="text" 
					class="form-control input-maior" 
					autocomplete="off"
					required 
					readonly 
					ng-model="$ctrl.Create.pesquisa.MODELO"
					ng-value="
						($ctrl.Create.pesquisa.MODELO.ID) 
							? ($ctrl.Create.pesquisa.MODELO.ID | lpad:[5,'0']) +' - '+ $ctrl.Create.pesquisa.MODELO.TITULO 
							: ''
					"
					ng-click="$ctrl.Create.alterarModeloPesquisa()">
				
				<button 
					type="button" 
					class="input-group-addon btn-filtro" 
					tabindex="-1" 
					ng-click="$ctrl.Create.alterarModeloPesquisa()">

					<span class="fa fa-search"></span>
				</button>

			</div>

		</div>

		<div class="form-group" ng-if="!$ctrl.Create.pesquisa.ID">

			<label>{{ Lang::get($menu.'.label-cliente') }}:</label>

			<div class="input-group">

				<input 
					type="text" 
					class="form-control input-maior" 
					autocomplete="off"
					required 
					readonly 
					ng-model="$ctrl.Create.pesquisa"
					ng-value="
						($ctrl.Create.pesquisa.CLIENTE.ID) 
							? ($ctrl.Create.pesquisa.CLIENTE.ID | lpad:[5,'0']) +' - '+ $ctrl.Create.pesquisa.CLIENTE.RAZAOSOCIAL 
							: ''
					"
					ng-click="$ctrl.Create.alterarCliente()">
				
				<button 
					type="button" 
					class="input-group-addon btn-filtro" 
					tabindex="-1" 
					ng-click="$ctrl.Create.alterarCliente()">

					<span class="fa fa-search"></span>
				</button>

			</div>

		</div>

	</div>

	<div class="row" ng-if="$ctrl.Create.pesquisa.MODELO">

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-titulo') }}:</label>

			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="2" 
					cols="50" 
					maxlength="100"  
					ng-model="$ctrl.Create.pesquisa.MODELO.TITULO"
					disabled>
				</textarea>

				<span class="contador"><span>@{{ 100 - $ctrl.Create.pesquisa.MODELO.TITULO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
			
			</div>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-descricao') }}:</label>
			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="2" 
					cols="70" 
					maxlength="300" 
					ng-model="$ctrl.Create.pesquisa.MODELO.DESCRICAO"
					disabled>
				</textarea>

				<span class="contador"><span>@{{ 300 - $ctrl.Create.pesquisa.MODELO.DESCRICAO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
			
			</div>

		</div>

	</div>

</fieldset>