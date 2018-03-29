angular
	.module('app')
	.service('ConsultarCorService', ConsultarCorService)
	.controller('ConsultarCorController', ConsultarCorController)
	.component('consultarCor27030', {
		templateUrl: '/_27030/viewConsultarCor',
		require: {
			liberacao12040: '^liberacao12040'
		},
		controller: 'ConsultarCorController'
	})
;