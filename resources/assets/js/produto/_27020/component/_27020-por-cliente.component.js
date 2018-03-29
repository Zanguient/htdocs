angular
	.module('app')
	.service('ModeloPorClienteService', ModeloPorClienteService)
	.controller('ModeloPorClienteController', ModeloPorClienteController)
	.component('modeloPorCliente27020', {
		templateUrl: '/_27020/modalModeloPorCliente',
		require: {
			pedidoIndex12040: '^pedidoIndex12040',
			pedidoItem12040	: '^pedidoItem12040'
		},
		controller: 'ModeloPorClienteController'
	})
;