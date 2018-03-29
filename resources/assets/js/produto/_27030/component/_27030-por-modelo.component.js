angular
	.module('app')
	.service('CorPorModeloService', CorPorModeloService)
	.controller('CorPorModeloController', CorPorModeloController)
	.component('corPorModelo27030', {
		templateUrl: '/_27030/viewCorPorModelo',
		require: {
			pedidoIndex12040: '^pedidoIndex12040',
			pedidoItem12040: '^pedidoItem12040'
		},
		controller: 'CorPorModeloController'
	})
;