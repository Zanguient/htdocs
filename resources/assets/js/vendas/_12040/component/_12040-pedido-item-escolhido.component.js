		
	angular
		.module('app')
		.service('PedidoItemEscolhidoService', PedidoItemEscolhidoService)
		.controller('PedidoItemEscolhidoController', PedidoItemEscolhidoController)
		.component('pedidoItemEscolhido12040', {
			templateUrl: '/_12040/viewPedidoItemEscolhido',
			require: {
				pedidoIndex12040: '^pedidoIndex12040',
				pedidoCreate12040: '^pedidoCreate12040'
			},
			bindings: {
				tipoTela: '='
			},
			controller: 'PedidoItemEscolhidoController'
		})
	;