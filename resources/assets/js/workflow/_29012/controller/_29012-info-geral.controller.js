
	_29012InfoGeralController.$inject = ['Historico', '$scope'];

	function _29012InfoGeralController(Historico, $scope) {

		var ctrl = this;

		// MÉTODOS (REFERÊNCIAS).
		

		// VARIÁVEIS
		ctrl.infoGeral = {};
		ctrl.Historico = new Historico('$ctrl.Historico', $scope);

		// Métodos iniciados ao carregar página.
		ctrl.$onInit = function() {

			ctrl.create29012.infoGeral = this;
		};


		// MÉTODOS

	}