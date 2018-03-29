		
	angular
		.module('app')
		.service('PedidoItemService', PedidoItemService)
		.service('ProdutoPorModeloECorService', ProdutoPorModeloECorService)		
		.controller('PedidoItemController', PedidoItemController)
		.component('pedidoItem12040', {
			templateUrl: '/_12040/viewPedidoItem',
			require: {
				pedidoIndex12040: '^pedidoIndex12040',
				pedidoCreate12040: '^pedidoCreate12040'
			},
			bindings: {
				pedidoItemEscolhido: '=',
				corEscolhida: '=',
				somaQuantidadeGeral: '&',
				definirDataCliente: '&'
			},
			controller: 'PedidoItemController'
		})
	;