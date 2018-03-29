<div ng-if="vm.tipoTela == 'incluir'">

	<button 
		type="submit" 
		class="btn btn-success" 
		data-hotkey="f10" 
		data-loading-text="{{ Lang::get('master.gravando') }}"
		ng-disabled="vm.permissao.INCLUIR == '0'"
	>
		<span class="glyphicon glyphicon-ok"></span> 
		{{ Lang::get('master.gravar') }}
	</button>

	<button 
		type="button" 
		class="btn btn-danger btn-voltar" 
		data-dismiss="modal" 
		data-hotkey="f11"
		ng-click="vm.limparTela()"
	>
		<span class="glyphicon glyphicon-ban-circle"></span> 
		{{ Lang::get('master.cancelar') }}
	</button>

</div>

<div ng-if="vm.tipoTela == 'exibir'">

	<button 
		type="button" 
		class="btn btn-primary" 
		data-hotkey="f9"
		ng-click="vm.tipoTela = 'alterar'"
		ng-disabled="vm.permissao.ALTERAR == '0'"
	>
		<span class="glyphicon glyphicon-edit"></span> 
		{{ Lang::get('master.alterar') }}
	</button>

	<button 
		type="button" 
		class="btn btn-default btn-voltar" 
		data-dismiss="modal" 
		data-hotkey="f11"
		ng-click="vm.limparTela()"
	>
		<span class="glyphicon glyphicon-chevron-left"></span> 
		{{ Lang::get('master.voltar') }}
	</button>
	
</div>

<div ng-if="vm.tipoTela == 'alterar'">

	<button 
		type="submit" 
		class="btn btn-success" 
		data-hotkey="f10" 
		data-loading-text="{{ Lang::get('master.gravando') }}"
		ng-disabled="vm.permissao.ALTERAR == '0'"
	>
		<span class="glyphicon glyphicon-ok"></span> 
		{{ Lang::get('master.gravar') }}
	</button>

	<button 
		type="button" 
		class="btn btn-danger btn-voltar" 
		data-hotkey="f11"
		ng-click="vm.tipoTela = 'exibir'"
	>
		<span class="glyphicon glyphicon-ban-circle"></span> 
		{{ Lang::get('master.cancelar') }}
	</button>
	
</div>