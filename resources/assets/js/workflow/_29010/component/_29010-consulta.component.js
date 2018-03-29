		
angular
	.module('app')
	.service('_29010ConsultaService', _29010ConsultaService)
	.controller('_29010ConsultaController', _29010ConsultaController)
	.component('consulta29010', {
		templateUrl: '/_29010/viewConsulta',
		require: {
			create29011: '^create29011'
		},
		controller: '_29010ConsultaController'
	})
;