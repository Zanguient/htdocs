<fieldset id="pedido-item-escolhido" ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-pedido-item-escolhido') }}</legend>
	
	<div class="button-container">

		<button 
			type="button" 
			class="btn btn-sm btn-primary incluir-item" 
			data-hotkey="f1" 
			data-toggle="modal" 
			data-target="#modal-pedido-item"
		>
			<span class="glyphicon glyphicon-plus"></span> 
			{{ Lang::get($menu.'.button-incluir-item') }}
		</button>

		<button 
			type="button" 
			class="btn btn-sm btn-danger excluir-item" 
			data-hotkey="f5"
			ng-disabled="$ctrl.listaPedidoItemEscolhidoSelec.length == 0"
			ng-click="$ctrl.excluirPedidoItemEscolhido()"
		>
			<span class="glyphicon glyphicon-trash"></span> 
			{{ Lang::get($menu.'.button-excluir-item') }}
		</button>

	</div>

	@include('vendas._12040.create.pedido-item-escolhido-table', ['pu218' => $pu218])

	@include('vendas._12040.create.pedido-item-escolhido-table-qtd', ['pu218' => $pu218])

	@include('vendas._12040.create.pedido-item-escolhido-resumo')

</fieldset>

<pedido-item-12040
	pedido-item-escolhido="$ctrl.pedidoItemEscolhido"
	cor-escolhida="$ctrl.corEscolhida"
	soma-quantidade-geral="$ctrl.somaQuantidadeGeral()"
	definir-data-cliente="$ctrl.definirDataCliente()"
></pedido-item-12040>