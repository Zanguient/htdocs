		
angular
	.module('app')
	//.service('InfoGeralService', InfoGeralService)
	.controller('InfoGeralController', InfoGeralController)
	.component('infoGeral29010', {
		templateUrl: '/_29010/viewInfoGeral',
		require: {
			workflowIndex29010: '^workflowIndex29010',
			workflowCreate29010: '^workflowCreate29010'
		},
		bindings: {
			tipoTela: '='
		},
		controller: 'InfoGeralController'
	})
;