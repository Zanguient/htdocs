angular
	.module('app')
	.service('ClientePorRepresentanteService', ClientePorRepresentanteService)
	.controller('ClientePorRepresentanteController', ClientePorRepresentanteController)
	.component('clientePorRepresentante12070', {
		templateUrl: '/_12070/modalConsultarClientePorRepresentante',
		require: {
			pedidoIndex12040: '^pedidoIndex12040'
		},
		controller: 'ClientePorRepresentanteController'
	})
;