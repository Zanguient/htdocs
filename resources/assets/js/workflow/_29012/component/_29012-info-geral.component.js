		
angular
	.module('app')
	.service('_29012InfoGeralService', _29012InfoGeralService)
	.controller('_29012InfoGeralController', _29012InfoGeralController)
	.component('infoGeral29012', {
		templateUrl: '/_29012/viewInfoGeral',
		require: {
			create29012: '^create29012'
		},
		bindings: {
			tipoTela: '='
		},
		controller: '_29012InfoGeralController'
	})
;