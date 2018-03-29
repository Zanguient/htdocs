		
angular
	.module('app')
	.service('WorkflowCreateService', WorkflowCreateService)
	.controller('WorkflowCreateController', WorkflowCreateController)
	.component('workflowCreate29010', {
		templateUrl: '/_29010/viewWorkflowCreate',
		require: {
			'workflowIndex29010': '^workflowIndex29010'
		},
		bindings: {
			permissaoMenu: '=',
			tipoTela: '='
		},
		controller: 'WorkflowCreateController'
	})
;