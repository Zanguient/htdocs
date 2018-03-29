		
angular
	.module('app')
	.service('_29012TarefaService', _29012TarefaService)
	.controller('_29012TarefaController', _29012TarefaController)
	.component('tarefa29012', {
		templateUrl: '/_29012/viewTarefa',
		require: {
			index29012 : '^index29012',
			create29012: '^create29012'
		},
		bindings: {
			tipoTela: '='
		},
		controller: '_29012TarefaController'
	})
;