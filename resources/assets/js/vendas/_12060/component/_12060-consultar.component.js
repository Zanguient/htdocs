angular
	.module('app')
	.service('RepresentanteService', RepresentanteService)
	.controller('RepresentanteController', RepresentanteController)
	.component('representante12060', {
		templateUrl: '/_12060/modalConsultarRepresentante',
		require: {
			pedidoIndex12040: '^pedidoIndex12040'
		},
		controller: 'RepresentanteController'
	})
;