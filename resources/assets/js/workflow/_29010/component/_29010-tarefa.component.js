		
angular
	.module('app')
	.service('TarefaService', TarefaService)
	.controller('TarefaController', TarefaController)
	.component('tarefa29010', {
		templateUrl: '/_29010/viewTarefa',
		require: {
			workflowIndex29010: '^workflowIndex29010',
			workflowCreate29010: '^workflowCreate29010'
		},
		bindings: {
			tipoTela: '='
		},
		controller: 'TarefaController'
	})
;