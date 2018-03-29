<fieldset ng-disabled="vm.formulario.STATUS == '0' || vm.formulario.DESTINATARIO_STATUS_RESPOSTA == '1'">

	<legend>@{{ vm.formulario.ID +' - '+ vm.formulario.TITULO }}</legend>

	<label class="lbl-descricao">@{{ vm.formulario.DESCRICAO }}</label>
	<label class="lbl-periodo">{{ Lang::get($menu.'.label-periodo-valido') }}:</label>
	<label class="lbl-periodo-data">@{{ vm.formulario.PERIODO_INI }} {{ Lang::get('master.periodo-a') }} @{{ vm.formulario.PERIODO_FIM }}</label>
	<span class="status status-@{{ vm.formulario.STATUS }}"></span>

	<label class="lbl-ja-respondeu" ng-if="vm.formulario.DESTINATARIO_STATUS_RESPOSTA == '1'">
		{{ Lang::get($menu.'.label-ja-respondeu') }}
	</label>

	@include('opex._25011.create.bloco-autenticacao')

</fieldset>