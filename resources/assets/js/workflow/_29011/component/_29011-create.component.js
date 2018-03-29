		
angular
	.module('app')
	.service('_29011CreateService', _29011CreateService)
	.controller('_29011CreateController', _29011CreateController)
	.component('create29011', {
		templateUrl: '/_29011/viewCreate',
		require: {
			'index29011': '^index29011'
		},
		bindings: {
			permissaoMenu: '=',
			tipoTela: '='
		},
		controller: '_29011CreateController'
	})
;