ConsultarCorController.$inject = ['ConsultarCorService'];

function ConsultarCorController(ConsultarCorService) {

	var ctrl = this;

	// MÉTODOS (REFERÊNCIAS)
	ctrl.consultarCor  = consultarCor;
	ctrl.selecionarCor = selecionarCor;
	ctrl.fecharModal   = fecharModal;

	// VARIÁVEIS
	ctrl.listaCor = [];

	this.$onInit = function() {

		ctrl.liberacao12040.consultarCor = this;
	};


	// MÉTODOS

	/**
	 * Consultar cor.
	 */
	function consultarCor() {

		// Verificação para consultar apenas uma vez.
		if (ctrl.listaCor.length == 0) {

			ConsultarCorService
				.consultarCor()
				.then(function(response) { 
					ctrl.listaCor = response; 
				});
		}

		setTimeout(function() {

			// Fix para vs-repeat.
			$('.table-container-consultar-cor')
				.find('.scroll-table')
				.trigger('resize')
				.scrollTop(0);

			// Foco no input de filtrar.
			$('.input-filtrar-cor').select();

		}, 500);
	}

	/**
	 * Selecionar cor.
	 */
	function selecionarCor(cor) {

		var corLiberacao = ctrl.liberacao12040.liberacao.COR;
		corLiberacao.splice(corLiberacao.length-1, 1, cor);
		ctrl.fecharModal();
	}

	/**
	 * Fechar modal.
	 */
	function fecharModal() {

		$('#modal-consultar-cor')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast')
		;

		ctrl.filtrarCor = '';
	}

}