<div ng-if="$ctrl.tipoTela == 'exibir'">

	<button 
		type="button" 
		class="btn btn-primary" 
		data-hotkey="alt+r"
		ng-click="$ctrl.Create.habilitarResponder()"
		ng-disabled="$ctrl.permissaoMenu.ALTERAR != '1' || $ctrl.Create.avaliacao.STATUS == '0'"
		ng-attr-title="@{{ $ctrl.Create.avaliacao.STATUS == '0' ? 'Avaliação está inativa.' : '' }}"
		ng-if="$ctrl.tipoFuncao == 'base'">

		<span class="glyphicon glyphicon-pencil"></span> 
		{{ Lang::get($menu.'.button-responder') }}
	</button>

	<button 
		type="button" 
		class="btn btn-warning" 
		data-hotkey="alt+i"
		ng-click="$ctrl.Create.imprimirAvaliacao()"
		ng-disabled="$ctrl.permissaoMenu.IMPRIMIR != '1'"
		ng-if="$ctrl.tipoFuncao == 'resposta'">

		<span class="glyphicon glyphicon-print"></span> 
		{{ Lang::get('master.imprimir') }}
	</button>

	<button 
		type="button" 
		class="btn btn-primary" 
		data-hotkey="f9"
		ng-click="$ctrl.Create.habilitarResponder()"
		ng-disabled="$ctrl.permissaoMenu.ALTERAR != '1'"
		ng-if="$ctrl.tipoFuncao == 'resposta'">

		<span class="glyphicon glyphicon-edit"></span> 
		{{ Lang::get('master.alterar') }}
	</button>

	<button 
		type="button" 
		class="btn btn-danger" 
		data-hotkey="f12"
		ng-click="$ctrl.Create.excluirAvaliacao()"
		ng-disabled="$ctrl.permissaoMenu.EXCLUIR != '1'"
		ng-if="$ctrl.tipoFuncao == 'resposta'">

		<span class="glyphicon glyphicon-trash"></span> 
		{{ Lang::get('master.excluir') }}
	</button>

	<button 
		type="button" 
		class="btn gerar-historico" 
		data-hotkey="alt+h"
		data-consulta-historico 
		data-tabela="TBAVALIACAO_DES_RESPOSTA" 
		data-tabela-id="@{{ $ctrl.Create.avaliacao.ID }}"
		ng-if="$ctrl.tipoFuncao == 'resposta'">

        <span class="glyphicon glyphicon-time"></span> 
        {{ Lang::get('master.historico') }}
    </button>

	<button 
		type="button" 
		class="btn btn-default btn-voltar" 
		data-hotkey="f11"
		ng-click="$ctrl.Create.fecharModalAvaliacao()">

		<span class="glyphicon glyphicon-chevron-left"></span> 
		{{ Lang::get('master.voltar') }}
	</button>
	
</div>

<div ng-if="$ctrl.tipoTela == 'responder'">

	<button 
		type="submit" 
		class="btn btn-success" 
		data-hotkey="f10">

		<span class="glyphicon glyphicon-ok"></span> 
		{{ Lang::get('master.gravar') }}
	</button>

	<button 
		type="button" 
		class="btn btn-danger btn-cancelar"
		data-hotkey="f11"
		ng-click="$ctrl.Create.cancelarAlteracaoAvaliacao()">

		<span class="glyphicon glyphicon-ban-circle"></span> 
		{{ Lang::get('master.cancelar') }}
	</button>
	
</div>