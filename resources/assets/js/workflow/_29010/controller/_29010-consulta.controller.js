_29010ConsultaController.$inject = ['_29010ConsultaService'];

function _29010ConsultaController(_29010ConsultaService) {

	var ctrl = this;

	// MÉTODOS (REFERÊNCIAS)
	ctrl.consultar   = consultar;
	ctrl.selecionar  = selecionar;
	ctrl.fecharModal = fecharModal;

	// VARIÁVEIS
	ctrl.listaWorkflow = [];

	this.$onInit = function() {

		ctrl.create29011.consulta29010 = this;
	};


	// MÉTODOS

	/**
	 * Consultar workflow.
	 */
	function consultar() {

		// Verificação para consultar apenas uma vez.
		// if (ctrl.listaCor.length == 0) {

			_29010ConsultaService
				.consultar()
				.then(function(response) {
					ctrl.listaWorkflow = response;
				});
		// }

		setTimeout(function() {

			// Fix para vs-repeat.
			$('.table-container-consulta-workflow')
				.find('.scroll-table')
				.trigger('resize')
				.scrollTop(0);

			// Foco no input de filtrar.
			$('.input-filtrar-workflow').select();

		}, 500);
	}

	/**
	 * Selecionar workflow.
	 */
	function selecionar(workflow) {

		ctrl.create29011.infoGeral.infoGeral.WORKFLOW_MODELO = workflow;
		ctrl.fecharModal();
	}

	/**
	 * Fechar modal.
	 */
	function fecharModal() {

		$('#modal-consulta-workflow')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast');

		ctrl.filtrarWorkflow = '';
	}

}