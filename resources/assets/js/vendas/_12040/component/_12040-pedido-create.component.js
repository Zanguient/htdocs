		
angular
	.module('app')
	.service('PedidoCreateService', PedidoCreateService)
	.controller('PedidoCreateController', PedidoCreateController)
	.component('pedidoCreate12040', {
		templateUrl: '/_12040/viewPedidoCreate',
		require: {
			pedidoIndex12040: '^pedidoIndex12040'
		},
		bindings: {
			permissaoMenu: '=',
			tipoTela: '=',
			situacaoPedido: '=',
			consultarPedido: '&',
			fecharModal: '&'
		},
		controller: 'PedidoCreateController'
	})
;