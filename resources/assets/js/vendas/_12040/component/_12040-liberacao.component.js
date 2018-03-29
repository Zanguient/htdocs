		
angular
	.module('app')
	.service('LiberacaoService', LiberacaoService)
	.controller('LiberacaoController', LiberacaoController)
	.component('liberacao12040', {
		templateUrl: '/_12040/viewLiberacao',
		controller: 'LiberacaoController'
	})
;