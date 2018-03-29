<div ng-if="$ctrl.tipoTela == 'exibir'">

	<button 
		type="button" 
		class="btn btn-warning" 
		data-hotkey="alt+a"
		ng-click="$ctrl.exibirModalArquivoTodos()">
		
		<span class="glyphicon glyphicon-picture"></span> 
		{{ Lang::get($menu.'.button-arquivo-workflow') }}
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
		data-dismiss="modal" 
		data-hotkey="f11"
		ng-click="$ctrl.fecharModal()">
		
		<span class="glyphicon glyphicon-chevron-left"></span> 
		{{ Lang::get('master.voltar') }}
	</button>

</div>