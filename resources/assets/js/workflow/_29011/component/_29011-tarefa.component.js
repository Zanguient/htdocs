		
angular
	.module('app')
	.service('_29011TarefaService', _29011TarefaService)
	.controller('_29011TarefaController', _29011TarefaController)
	.component('tarefa29011', {
		templateUrl: '/_29011/viewTarefa',
		require: {
			index29011 : '^index29011',
			create29011: '^create29011'
		},
		bindings: {
			tipoTela: '='
		},
		controller: '_29011TarefaController'
	})
;