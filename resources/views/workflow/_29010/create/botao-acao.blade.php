<div ng-if="$ctrl.tipoTela == 'incluir'">

	<button 
		type="submit" 
		class="btn btn-success" 
		data-hotkey="f10" 
		data-loading-text="{{ Lang::get('master.gravando') }}"
		ng-click="$ctrl.gravarFechar = false"
	>
		<span class="glyphicon glyphicon-ok"></span> 
		{{ Lang::get('master.gravar') }}
	</button>

	<button 
		type="submit" 
		class="btn btn-success" 
		data-hotkey="alt+g" 
		data-loading-text="{{ Lang::get('master.gravando') }}"
		ng-click="$ctrl.gravarFechar = true"
	>
		<span class="glyphicon glyphicon-ok"></span> 
		{{ Lang::get('master.gravar-fechar') }}
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
		ng-click="$ctrl.tipoTela = 'alterar'"
		ng-disabled="$ctrl.permissaoMenu.ALTERAR != 1"
	>
		<span class="glyphicon glyphicon-edit"></span> 
		{{ Lang::get('master.alterar') }}
	</button>

	<button 
		type="button" 
		class="btn btn-danger" 
		data-hotkey="f12"
		ng-click="$ctrl.excluir()"
		ng-disabled="$ctrl.permissaoMenu.EXCLUIR != 1"
	>
		<span class="glyphicon glyphicon-trash"></span> 
		{{ Lang::get('master.excluir') }}
	</button>

	<button 
		type="button" 
		class="btn gerar-historico" 
		data-hotkey="alt+h"
		data-consulta-historico 
		data-tabela="TBWORKFLOW" 
		data-tabela-id="@{{ $ctrl.infoGeral.infoGeral.ID }}">

        <span class="glyphicon glyphicon-time"></span> 
        {{ Lang::get('master.historico') }}
    </button>

	<button 
		type="button" 
		class="btn btn-default btn-voltar" 
		data-dismiss="modal" 
		data-hotkey="f11"
		ng-click="$ctrl.fecharModal()"
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
		ng-click="$ctrl.gravarFechar = false"
	>
		<span class="glyphicon glyphicon-ok"></span> 
		{{ Lang::get('master.gravar') }}
	</button>

	<button 
		type="submit" 
		class="btn btn-success" 
		data-hotkey="alt+g" 
		data-loading-text="{{ Lang::get('master.gravando') }}"
		ng-click="$ctrl.gravarFechar = true"
	>
		<span class="glyphicon glyphicon-ok"></span> 
		{{ Lang::get('master.gravar-fechar') }}
	</button>

	<button 
		type="button" 
		class="btn gerar-historico" 
		data-hotkey="alt+h"
		data-consulta-historico 
		data-tabela="TBWORKFLOW" 
		data-tabela-id="@{{ $ctrl.infoGeral.infoGeral.ID }}">

        <span class="glyphicon glyphicon-time"></span> 
        {{ Lang::get('master.historico') }}
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