<div id="autenticacao-container" ng-if="vm.formulario.TIPO == 2">

	<label class="lbl-autenticar">{{ Lang::get($menu.'.label-autenticar') }}:</label>
	
	<input 
		type="password" 
		class="input-maior" 
		maxlength="12"
		placeholder="{{ Lang::get($menu.'.placeholder-autenticar') }}" 
		ng-model="vm.autenticacao.CODIGO"
		ng-disabled="vm.tipoTela == 'alterar'"
		ng-keypress="vm.atalhoAutenticar($event)"
	/>
	
	<button 
		type="button"
		class="btn btn-success"
		data-hotkey="enter"
		ng-click="vm.autenticar()"
		ng-model="vm.autenticacao.INICIAR"
		ng-disabled="vm.tipoTela == 'alterar'"
	>
		<span class="glyphicon glyphicon-play"></span> 
		{{ Lang::get($menu.'.button-iniciar') }}
	</button>

	<label class="lbl-colaborador-nome" ng-if="vm.autenticacao.COLABORADOR_NOME">
		{{ Lang::get($menu.'.label-colaborador-autenticado') }}, @{{ vm.autenticacao.COLABORADOR_NOME }}.
	</label>

</div>