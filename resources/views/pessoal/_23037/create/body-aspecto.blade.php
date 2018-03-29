<fieldset 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-aspectos') }}</legend>

	<div class="row">

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-pontos-positivos') }}:</label>

			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="4" 
					cols="60" 
					maxlength="500" 
					ng-model="$ctrl.Create.avaliacao.PONTO_POSITIVO"
					ng-disabled="$ctrl.tipoTela != 'responder'"></textarea>

				<span class="contador"><span>@{{ 500 - $ctrl.Create.avaliacao.PONTO_POSITIVO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>

			</div>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-pontos-melhorar') }}:</label>

			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="4" 
					cols="65" 
					maxlength="500" 
					ng-model="$ctrl.Create.avaliacao.PONTO_MELHORAR"
					ng-disabled="$ctrl.tipoTela != 'responder'"></textarea>

				<span class="contador"><span>@{{ 500 - $ctrl.Create.avaliacao.PONTO_MELHORAR.length }}</span> {{ Lang::get('master.caract-restante') }}</span>

			</div>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-opiniao-avaliado') }}:</label>

			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="4" 
					cols="60" 
					maxlength="500" 
					ng-model="$ctrl.Create.avaliacao.OPINIAO_AVALIADO"
					ng-disabled="$ctrl.tipoTela != 'responder'"></textarea>

				<span class="contador"><span>@{{ 500 - $ctrl.Create.avaliacao.OPINIAO_AVALIADO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>

			</div>

		</div>

	</div>

</fieldset>