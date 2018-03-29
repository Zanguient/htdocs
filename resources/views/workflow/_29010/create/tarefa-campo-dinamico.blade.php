<div class="campo-container">

	<label>{{ Lang::get($menu.'.label-campos-dinamicos') }}:</label>

	<div class="button-container">

		<button 
			type="button" 
			class="btn btn-sm btn-info" 
			title="{{ Lang::get($menu.'.title-add-campo') }}"
			ng-click="$ctrl.addCampo(tarefa)"
			ng-disabled="$ctrl.tipoTela == 'exibir'">

			<span class="glyphicon glyphicon-plus"></span>
			{{ Lang::get($menu.'.button-add-campo') }}
		</button>
		
	</div>

	<div class="scroll">

		<div
			ng-repeat="campo in tarefa.CAMPO track by $index"
			ng-if="campo.STATUSEXCLUSAO != '1'">

			<div class="form-group">

				<label>{{ Lang::get($menu.'.label-rotulo') }}:</label>

				<input 
					type="text"
					class="form-control normal-case"
					ng-model="campo.ROTULO"
					ng-disabled="$ctrl.tipoTela == 'exibir'">

			</div>

			<div class="form-group">

				<label>{{ Lang::get($menu.'.label-tipo') }}:</label>

				<select
					ng-model="campo.TIPO"
					ng-disabled="$ctrl.tipoTela == 'exibir'">
					
					<option value="1">{{ Lang::get($menu.'.option-texto') }}</option>
					<option value="2">{{ Lang::get($menu.'.option-numerico') }}</option>
					<option value="3">{{ Lang::get($menu.'.option-boolean') }}</option>
				</select>

				<button 
					type="button" 
					class="btn btn-danger" 
					title="{{ Lang::get($menu.'.title-excluir-campo') }}"
					ng-click="$ctrl.excluirCampo(tarefa, campo)"
					ng-disabled="$ctrl.tipoTela == 'exibir'">

					<span class="glyphicon glyphicon-trash"></span>
				</button>

			</div>

		</div>

	</div>

</div>