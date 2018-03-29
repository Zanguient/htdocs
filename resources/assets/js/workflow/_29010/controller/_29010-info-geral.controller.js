
	InfoGeralController.$inject = ['Historico', '$scope'];

	function InfoGeralController(Historico, $scope) {

		var ctrl = this;

		// Métodos iniciados ao carregar página.
		ctrl.$onInit = function() {

			ctrl.workflowIndex29010.infoGeral  = this;
			ctrl.workflowCreate29010.infoGeral = this;
		};

		// VARIÁVEIS
		ctrl.infoGeral 			= {};
		ctrl.infoGeral.STATUS 	= '1';
		ctrl.Historico 			= new Historico('$ctrl.Historico', $scope);
	}