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

	<a
		class="btn btn-default"
		href="{{ url('') }}/_29013?workflowItemId=@{{ $ctrl.infoGeral.infoGeral.ID }}"
		target="_blank">

		<span class="glyphicon glyphicon-new-window"></span> 
		{{ Lang::get($menu.'.button-abrir-painel') }}
	</a>

	<button 
		type="button" 
		class="btn btn-primary" 
		data-hotkey="f9"
		ng-click="$ctrl.tipoTela = 'alterar'"
		ng-disabled="($ctrl.permissaoMenu.ALTERAR != 1) || ($ctrl.infoGeral.infoGeral.STATUS_CONCLUSAO == '3')"
	>
		<span class="glyphicon glyphicon-edit"></span> 
		{{ Lang::get('master.alterar') }}
	</button>

	<button 
		type="button" 
		class="btn" 
		data-hotkey="alt+e" 
		ng-class="{
			'btn-grey160': $ctrl.infoGeral.infoGeral.STATUS_CONCLUSAO != '3',
			'btn-grey130': $ctrl.infoGeral.infoGeral.STATUS_CONCLUSAO == '3'
		}"
		ng-click="$ctrl.encerrar()"
	>	
		<span 
			class="glyphicon glyphicon-remove-circle" 
			ng-if="$ctrl.infoGeral.infoGeral.STATUS_CONCLUSAO != '3'"></span>
		<span 
			class="glyphicon glyphicon-ok-circle" 
			ng-if="$ctrl.infoGeral.infoGeral.STATUS_CONCLUSAO == '3'"></span>
		<span
			ng-if="$ctrl.infoGeral.infoGeral.STATUS_CONCLUSAO != '3'">{{ Lang::get('master.encerrar') }}</span>
		<span
			ng-if="$ctrl.infoGeral.infoGeral.STATUS_CONCLUSAO == '3'">{{ Lang::get('master.desencerrar') }}</span>
	</button>

	<button 
		type="button" 
		class="btn btn-danger" 
		data-hotkey="f11"
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
		data-tabela="TBWORKFLOW_ITEM" 
		data-tabela-id="@{{ $ctrl.infoGeral.infoGeral.ID }}">

        <span class="glyphicon glyphicon-time"></span> 
        {{ Lang::get('master.historico') }}
    </button>

	<button 
		type="button" 
		class="btn btn-default btn-voltar" 
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
		data-tabela="TBWORKFLOW_ITEM" 
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