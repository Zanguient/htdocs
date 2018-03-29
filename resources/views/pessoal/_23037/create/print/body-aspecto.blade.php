<fieldset>

	<legend>{{ Lang::get($menu.'.legend-aspectos') }}</legend>

	<div class="row">

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-pontos-positivos') }}:</label>

			<span ng-bind="$ctrl.Create.avaliacao.PONTO_POSITIVO"></span>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-pontos-melhorar') }}:</label>

			<span ng-bind="$ctrl.Create.avaliacao.PONTO_MELHORAR"></span>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-opiniao-avaliado') }}:</label>

			<span ng-bind="$ctrl.Create.avaliacao.OPINIAO_AVALIADO"></span>

		</div>

	</div>

</fieldset>