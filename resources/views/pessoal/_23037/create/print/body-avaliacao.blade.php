<fieldset class="fieldset-avaliacao">

	<legend>{{ Lang::get($menu.'.legend-avaliacao') }}</legend>

	<div class="row">

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-id') }}:</label>

			<span ng-bind="$ctrl.Create.avaliacao.ID | lpad:[5,'0']"></span>

		</div>
		
		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-data-avaliacao') }}:</label>

			<span ng-bind="$ctrl.Create.avaliacao.DATA_AVALIACAO_HUMANIZE_LONG"></span>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-titulo') }}:</label>

			<span ng-bind="$ctrl.Create.avaliacao.TITULO"></span>

		</div>

	</div>

	<div class="row">

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-colaborador') }}:</label>

			<span ng-bind="$ctrl.Create.avaliacao.COLABORADOR.PESSOAL_NOME"></span>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-cargo') }}:</label>

			<span ng-bind="$ctrl.Create.avaliacao.COLABORADOR.CARGO_DESCRICAO"></span>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-formacao') }}:</label>

			<span ng-bind="$ctrl.Create.avaliacao.COLABORADOR.PESSOAL_ESCOLARIDADE_DESCRICAO"></span>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-gerencia') }}:</label>

			<span>{{ ucwords(mb_strtolower(Auth::user()->NOME ? Auth::user()->NOME : Auth::user()->USUARIO)) }}</span>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-setor') }}:</label>

			<span ng-bind="$ctrl.Create.avaliacao.COLABORADOR.CENTRO_DE_CUSTO_DESCRICAO"></span>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-admissao') }}:</label>

			<span ng-bind="$ctrl.Create.avaliacao.COLABORADOR.DATA_ADMISSAO_HUMANIZE"></span>

		</div>

	</div>

	<div class="row">
		
		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-instrucoes') }}:</label>

			<span ng-bind="$ctrl.Create.avaliacao.INSTRUCAO_INICIAL"></span>

		</div>

	</div>

</fieldset>