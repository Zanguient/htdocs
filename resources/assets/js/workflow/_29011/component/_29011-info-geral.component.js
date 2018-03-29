		
angular
	.module('app')
	.service('_29011InfoGeralService', _29011InfoGeralService)
	.controller('_29011InfoGeralController', _29011InfoGeralController)
	.component('infoGeral29011', {
		templateUrl: '/_29011/viewInfoGeral',
		require: {
			create29011: '^create29011'
		},
		bindings: {
			tipoTela: '='
		},
		controller: '_29011InfoGeralController'
	})
;