<div ng-if="$ctrl.tipoTela == 'incluir'">

	<button 
		type="submit" 
		class="btn btn-success" 
		data-hotkey="f10" 
		data-loading-text="{{ Lang::get('master.gravando') }}"
		ng-click="$ctrl.tipoTela = 'incluir'"
	>
		<span class="glyphicon glyphicon-ok"></span> 
		{{ Lang::get('master.gravar') }}
	</button>

	<button 
		type="button" 
		class="btn btn-danger btn-cancelar" 
		data-hotkey="f11"
		ng-click="$ctrl.fecharModal()"
	>
		<span class="glyphicon glyphicon-ban-circle"></span> 
		{{ Lang::get('master.cancelar') }}
	</button>

</div>

<div ng-if="$ctrl.tipoTela == 'exibir'">

	<button 
		type="button" 
		class="btn btn-primary" 
		data-hotkey="f9"
		ng-click="$ctrl.ativarAlterar()"
		ng-disabled="$ctrl.permissaoMenu.ALTERAR != 1 || $ctrl.situacaoPedido == '1' || $ctrl.infoGeral.infoGeral.FORMA_ANALISE == '0'"
	>
		<span class="glyphicon glyphicon-edit"></span> 
		{{ Lang::get('master.alterar') }}
	</button>

	<button 
		type="button" 
		class="btn btn-danger" 
		data-hotkey="f12"
		ng-click="$ctrl.excluir()"
		ng-disabled="$ctrl.permissaoMenu.EXCLUIR != 1 || $ctrl.situacaoPedido == '1' || $ctrl.infoGeral.infoGeral.FORMA_ANALISE == '0'"
	>
		<span class="glyphicon glyphicon-trash"></span> 
		{{ Lang::get('master.excluir') }}
	</button>

	<button 
		type="button" 
		class="btn btn-default btn-voltar" 
		data-dismiss="modal" 
		data-hotkey="f11"
		ng-click="$ctrl.limparTela(); $ctrl.fecharModal()"
	>
		<span class="glyphicon glyphicon-chevron-left"></span> 
		{{ Lang::get('master.voltar') }}
	</button>

</div>

<div ng-if="$ctrl.tipoTela == 'alterar'">

	<button 
		type="submit" 
		class="btn btn-success" 
		data-hotkey="f10" 
		data-loading-text="{{ Lang::get('master.gravando') }}"
	>
		<span class="glyphicon glyphicon-ok"></span> 
		{{ Lang::get('master.gravar') }}
	</button>

	<button 
		type="button" 
		class="btn btn-danger btn-cancelar" 
		data-hotkey="f11"
		ng-click="$ctrl.limparTela(); $ctrl.fecharModal();"
	>
		<span class="glyphicon glyphicon-ban-circle"></span> 
		{{ Lang::get('master.cancelar') }}
	</button>

</div>