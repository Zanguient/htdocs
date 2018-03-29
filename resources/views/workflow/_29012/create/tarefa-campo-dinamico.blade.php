<div 
	class="campo-container"
	ng-if="tarefa.CAMPO.length > 0">

	<label>{{ Lang::get($menu.'.label-preencha-campos') }}:</label>

	<div class="button-container">
		
		<button 
			type="button" 
			class="btn btn-sm btn-success" 
			title="{{ Lang::get($menu.'.title-gravar-campo') }}"
			ng-disabled="tarefa.STATUS_CONCLUSAO != '1'"
			ng-click="$ctrl.gravarWorkflowItemTarefaCampo(tarefa)">

			<span class="glyphicon glyphicon-ok"></span>
			{{ Lang::get($menu.'.button-gravar-campo') }}
		</button>

	</div>

	<div class="scroll">

		<div
			ng-repeat="campo in tarefa.CAMPO track by $index"
			ng-if="campo.STATUSEXCLUSAO != '1'">

			<div class="form-group">

				<label ng-bind="(campo.ROTULO)"></label>

				<div 
					class="textarea-grupo"
					ng-if="campo.TIPO == '1'">

					<textarea 
						class="form-control normal-case" 
						rows="2" 
						cols="51" 
						maxlength="1000" 
						ng-disabled="tarefa.STATUS_CONCLUSAO != '1'"
						ng-model="campo.VALOR">
					</textarea>

					<span class="contador"><span>@{{ 1000 - campo.VALOR.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
				
				</div>

				<input 
					type="number"
					class="form-control input-maior"
					step=".0001"
					placeholder="{{ Lang::get($menu.'.placeholder-numerico') }}" 
					ng-model="campo.VALOR"
					ng-disabled="tarefa.STATUS_CONCLUSAO != '1'"
					ng-if="campo.TIPO == '2'"
					string-to-number>

				<label 
					class="label-radio"
					ng-disabled="tarefa.STATUS_CONCLUSAO != '1'"
					ng-if="campo.TIPO == '3'">

					<input 
						type="radio" 
						ng-model="campo.VALOR"
						value="1">

					{{ Lang::get($menu.'.radio-sim') }}
				</label>

				<label
					class="label-radio"
					ng-disabled="tarefa.STATUS_CONCLUSAO != '1'"
					ng-if="campo.TIPO == '3'">

					<input 
						type="radio" 
						ng-model="campo.VALOR"
						value="0">

					{{ Lang::get($menu.'.radio-nao') }}
				</label>

			</div>

		</div>

	</div>

</div>