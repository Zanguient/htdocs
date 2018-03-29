		
angular
	.module('app')
	.service('InfoGeralService', InfoGeralService)
	.controller('InfoGeralController', InfoGeralController)
	.component('infoGeral12040', {
		templateUrl: '/_12040/viewInfoGeral',
		require: {
			pedidoIndex12040: '^pedidoIndex12040',
			pedidoCreate12040: '^pedidoCreate12040'
		},
		bindings: {
			tipoTela: '='
		},
		controller: 'InfoGeralController'
	})
;