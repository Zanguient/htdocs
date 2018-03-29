<div ng-if="vm.tipoTela == 'exibir'">

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
		ng-disabled="vm.formulario.STATUS == '0' || vm.formulario.DESTINATARIO_STATUS_RESPOSTA == '1'"
	>
		<span class="glyphicon glyphicon-ok"></span> 
		{{ Lang::get('master.gravar') }}
	</button>

	<button 
		type="button" 
		class="btn btn-danger btn-cancelar"
		data-dismiss="modal" 
		data-hotkey="f11"
		ng-click="vm.limparCampos()"
	>
		<span class="glyphicon glyphicon-ban-circle"></span> 
		{{ Lang::get('master.cancelar') }}
	</button>
	
</div>