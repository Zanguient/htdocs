<fieldset 
	id="info-geral" 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-info-gerais') }}</legend>

	<div class="row">

		<div class="form-group" ng-if="$ctrl.infoGeral.ID > 0">

			<label>{{ Lang::get($menu.'.label-id') }}:</label>
			<input 
				type="text" 
				class="form-control input-menor" 
				disabled 
				ng-model="$ctrl.infoGeral.ID"
				ng-value="$ctrl.infoGeral.ID | lpad:[5,'0']">

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-workflow-modelo') }}:</label>

			<div class="input-group">

				<input 
					type="text" 
					class="form-control input-maior" 
					autocomplete="off"
					required 
					readonly 
					ng-model="$ctrl.infoGeral.WORKFLOW_MODELO"
					ng-value="
						($ctrl.infoGeral.WORKFLOW_MODELO) 
							? ($ctrl.infoGeral.WORKFLOW_MODELO.ID | lpad:[5,'0']) +' - '+ $ctrl.infoGeral.WORKFLOW_MODELO.TITULO 
							: ''
					"
					ng-click="$ctrl.alterarWorkflowModelo()">
				
				<button 
					type="button" 
					class="input-group-addon btn-filtro" 
					tabindex="-1" 
					ng-click="$ctrl.alterarWorkflowModelo()">

					<span class="fa fa-search"></span>
				</button>

			</div>

			<consulta29010></consulta29010>

		</div>

		<div class="form-group" ng-if="$ctrl.tipoTela != 'incluir'">

			<label>{{ Lang::get($menu.'.label-status') }}:</label>
			<label
				class="label lbl-status"
				ng-class="{
					'0': 'label-danger',
					'1': 'label-warning',
					'2': 'label-success',
					'3': 'label-default'
				}[$ctrl.infoGeral.STATUS_CONCLUSAO]"
				ng-bind="{
					'0': 'Parado',   
					'1': 'Iniciado',
					'2': 'Concluído',
					'3': 'Encerrado'
				}[$ctrl.infoGeral.STATUS_CONCLUSAO]"></label>

		</div>

		<div class="form-group" ng-if="$ctrl.tipoTela != 'incluir'">

			<label>{{ Lang::get($menu.'.label-progresso') }}:</label>

			<div class="progress">

				<div 
					class="progress-bar" 
					style="width: @{{ $ctrl.infoGeral.PROGRESSO; }}%"
					ng-class="{
						'texto-cor-escura' 	  : $ctrl.infoGeral.PROGRESSO < 16,
						'progress-bar-danger' : $ctrl.infoGeral.PROGRESSO <= 33,
						'progress-bar-warning': $ctrl.infoGeral.PROGRESSO > 33 && $ctrl.infoGeral.PROGRESSO <= 66,
						'progress-bar-success': $ctrl.infoGeral.PROGRESSO > 66
					}"
					ng-bind="$ctrl.infoGeral.PROGRESSO +'%'"></div>

			</div>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-data-ini-prevista') }}:</label>

			<input 
				type="datetime-local" 
				class="form-control" 
				required 
				ng-model="$ctrl.infoGeral.DATAHORA_INI_PREVISTA_TMP">
				
		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-data-fim-prevista') }}:</label>

			<input 
				type="datetime-local" 
				class="form-control" 
				ng-model="$ctrl.infoGeral.DATAHORA_FIM_PREVISTA_TMP">
				
		</div>

	</div>

	<div class="row">

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-titulo') }}:</label>

			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="2" 
					cols="50" 
					maxlength="50" 
					required 
					ng-model="$ctrl.infoGeral.TITULO">
				</textarea>

				<span class="contador"><span>@{{ 50 - $ctrl.infoGeral.TITULO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
			
			</div>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-descricao') }}:</label>
			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="4" 
					cols="70" 
					maxlength="200" 
					required 
					ng-model="$ctrl.infoGeral.DESCRICAO">
				</textarea>

				<span class="contador"><span>@{{ 200 - $ctrl.infoGeral.DESCRICAO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
			
			</div>

		</div>

	</div>

</fieldset>