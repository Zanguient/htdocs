<div ng-if="$ctrl.tipoTela == 'incluir'">

	<button 
		type="submit" 
		class="btn btn-success" 
		data-hotkey="alt+g"
		ng-click="$ctrl.Create.pesquisa.STATUS = '1'">

		<span class="glyphicon glyphicon-ok"></span> 
		{{ Lang::get('master.gravar') }}
	</button>

	<button 
		type="button" 
		class="btn btn-danger btn-cancelar" 
		data-hotkey="f11"
		ng-click="$ctrl.Create.fecharModal(true)">

		<span class="glyphicon glyphicon-ban-circle"></span> 
		{{ Lang::get('master.cancelar') }}
	</button>

</div>

<div ng-if="$ctrl.tipoTela == 'exibir'">

	<button 
		type="button" 
		class="btn btn-warning" 
		data-hotkey="alt+e"
		ng-click="
			$ctrl.tipoTela = 'alterar'; 
			$ctrl.Create.pesquisa.STATUS = '2';"
		ng-disabled="
			$ctrl.permissaoMenu.ALTERAR != '1' 
			|| $ctrl.Create.pesquisa.STATUS == '2'">

		<span class="glyphicon glyphicon-edit"></span> 
		{{ Lang::get($menu.'.button-segunda-avaliacao') }}
	</button>

	<button 
		type="button" 
		class="btn btn-primary" 
		data-hotkey="f9"
		ng-click="
			$ctrl.tipoTela = 'alterar';
			$ctrl.Create.pesquisa.STATUS = '1'"
		ng-disabled="
			$ctrl.permissaoMenu.ALTERAR != '1'
			|| $ctrl.Create.pesquisa.STATUS == '2'">

		<span class="glyphicon glyphicon-edit"></span> 
		{{ Lang::get('master.alterar') }}
	</button>

	<button 
		type="button" 
		class="btn btn-danger" 
		data-hotkey="f12"
		ng-click="$ctrl.Create.excluir()"
		ng-disabled="$ctrl.permissaoMenu.EXCLUIR != '1'">

		<span class="glyphicon glyphicon-trash"></span> 
		{{ Lang::get('master.excluir') }}
	</button>

	<button 
		type="button" 
		class="btn gerar-historico" 
		data-hotkey="alt+h"
		data-consulta-historico 
		data-tabela="TBFORMULARIO_PESQ_CLIENTE" 
		data-tabela-id="@{{ $ctrl.Create.pesquisa.ID }}">

        <span class="glyphicon glyphicon-time"></span> 
        {{ Lang::get('master.historico') }}
    </button>

	<button 
		type="button" 
		class="btn btn-default btn-voltar" 
		data-hotkey="f11"
		ng-click="$ctrl.Create.fecharModal(false)">

		<span class="glyphicon glyphicon-chevron-left"></span> 
		{{ Lang::get('master.voltar') }}
	</button>
	
</div>

<div ng-if="$ctrl.tipoTela == 'alterar'">

	<button 
		type="submit" 
		class="btn btn-success" 
		data-hotkey="f10"
		ng-disabled="$ctrl.pesquisa.STATUS == '0'">

		<span class="glyphicon glyphicon-ok"></span> 
		{{ Lang::get('master.gravar') }}
	</button>

	<button 
		type="button" 
		class="btn btn-danger btn-cancelar"
		data-hotkey="f11"
		ng-click="
			$ctrl.Create.pesquisa.STATUS = '1'; 
			$ctrl.Create.fecharModal(true);">

		<span class="glyphicon glyphicon-ban-circle"></span> 
		{{ Lang::get('master.cancelar') }}
	</button>
	
</div>