		
angular
	.module('app')
	.service('_29012CreateService', _29012CreateService)
	.controller('_29012CreateController', _29012CreateController)
	.component('create29012', {
		templateUrl: '/_29012/viewCreate',
		require: {
			'index29012': '^index29012'
		},
		bindings: {
			tipoTela: '='
		},
		controller: '_29012CreateController'
	})
;