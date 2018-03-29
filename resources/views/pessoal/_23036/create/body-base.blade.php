<fieldset 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-avaliacao') }}</legend>

	<div class="row">

		<div class="form-group" ng-if="$ctrl.Create.avaliacao.BASE.ID > 0">

			<label>{{ Lang::get($menu.'.label-id') }}:</label>

			<input 
				type="text" 
				class="form-control input-menor" 
				readonly 
				ng-model="$ctrl.Create.avaliacao.BASE.ID"
				ng-value="$ctrl.Create.avaliacao.BASE.ID | lpad:[5,'0']">

		</div>

		<div 
			class="form-group">

			<label>{{ Lang::get($menu.'.label-modelo') }}:</label>

			<select
				class="form-control normal-case select-modelo"
				ng-model="$ctrl.Create.avaliacao.BASE.MODELO"
				ng-change="$ctrl.CreateModelo.selecionarModelo()"
				required>

				<option
					ng-repeat="mod in $ctrl.CreateModelo.listaModelo"
					ng-value="mod"
					ng-bind="mod.TITULO"></option>
				
			</select>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-titulo') }}:</label>

			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="2" 
					cols="60" 
					maxlength="100" 
					required 
					ng-model="$ctrl.Create.avaliacao.BASE.TITULO"
					ng-readonly="$ctrl.tipoTela == 'responder'"></textarea>

				<span class="contador"><span ng-bind="100 - $ctrl.Create.avaliacao.BASE.TITULO.length"></span> {{ Lang::get('master.caract-restante') }}</span>

			</div>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-data-avaliacao') }}:</label>

			<input 
				type="month" 
				class="form-control" 
				required 
				ng-model="$ctrl.Create.avaliacao.BASE.DATA_AVALIACAO_INPUT"
				ng-disabled="$ctrl.tipoTela == 'responder'">

		</div>

		<div class="form-group">
			
			<label>{{ Lang::get($menu.'.label-status') }}:</label>

			<label class="switch">

				<input 
					type="checkbox" 
					ng-checked="$ctrl.Create.avaliacao.BASE.STATUS == '1'"
					ng-true-value="'1'"
					ng-false-value="'0'"
					ng-click="$ctrl.Create.avaliacao.BASE.STATUS = ($ctrl.Create.avaliacao.BASE.STATUS == '1') ? '0' : '1'">

				<div class="slider"></div>

			</label>

		</div>

	</div>

	<div class="row">
		
		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-instrucoes') }}:</label>

			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="5" 
					cols="100"   
					readonly 
					ng-model="$ctrl.Create.avaliacao.BASE.INSTRUCAO_INICIAL"></textarea>

			</div>

		</div>

	</div>

</fieldset>