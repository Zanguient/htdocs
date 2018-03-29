<fieldset id="info-geral" ng-disabled="$ctrl.tipoTela == 'exibir'">

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

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-status') }}:</label>

			<label class="switch">

				<input 
					type="checkbox" 
					ng-checked="$ctrl.infoGeral.STATUS == '1'"
					ng-true-value="'1'"
					ng-false-value="'0'"
					ng-click="$ctrl.infoGeral.STATUS = ($ctrl.infoGeral.STATUS == '1') ? '0' : '1'" />

				<div 
					class="slider"></div>

			</label>

		</div>

	</div>

</fieldset>