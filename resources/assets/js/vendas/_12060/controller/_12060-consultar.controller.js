RepresentanteController.$inject = ['RepresentanteService', '$scope'];

function RepresentanteController(RepresentanteService, $scope) {

	var ctrl = this;

	// MÉTODOS (REFERÊNCIAS)
	ctrl.consultarRepresentantePorUrl  	= consultarRepresentantePorUrl;
	ctrl.consultarRepresentante  		= consultarRepresentante;
	ctrl.selecionarRepresentante 		= selecionarRepresentante;
	ctrl.fecharModal			 		= fecharModal;

	// VARIÁVEIS
	ctrl.listaRepresentante	= [];

	this.$onInit = function() {

		ctrl.pedidoIndex12040.filtro = this;

		ctrl.representanteIdUrl = parseInt(getURLParameter('representanteId'));
		ctrl.consultarRepresentantePorUrl();
	};


	// MÉTODOS

	/**
	 * Consultar e selecionar representante através de parâmetro na URL.
	 */
	function consultarRepresentantePorUrl() {

		RepresentanteService
			.consultarRepresentante()
			.then(function(response) {

				ctrl.listaRepresentante = response;

				var rep = null;

				for (var i in ctrl.listaRepresentante) {

					rep = ctrl.listaRepresentante[i];

					if (rep.CODIGO == ctrl.representanteIdUrl) {

						ctrl.pedidoIndex12040.filtro.representante = rep;
						ctrl.pedidoIndex12040.filtroCliente.consultarClientePorRepresentantePorUrl();
						break;
					}
				}
			});
	}

	/**
	 * Consultar representante.
	 */
	function consultarRepresentante() {

		// Verificação para consultar apenas uma vez.
		if ( ctrl.listaRepresentante.length == 0 ) {

			RepresentanteService
				.consultarRepresentante()
				.then(function(response) { 
					ctrl.listaRepresentante = response; 
				})
			;

		}

		setTimeout(function() {

			// Fix para vs-repeat.
			$('.table-container-representante')
				.find('.scroll-table')
				.trigger('resize')
				.scrollTop(0);

			// Foco no input para filtrar.
			$('.input-filtrar-representante').select();

		}, 500);

	}

	/**
	 * Selecionar representante.
	 */
	function selecionarRepresentante(representante) {

		ctrl.pedidoIndex12040.filtro.representante = representante;
		ctrl.fecharModal();
	}

	/**
	 * Fechar modal.
	 */
	function fecharModal() {

		$('#modal-consultar-representante')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast')
		;

		ctrl.filtrarRepresentante = '';
	}

}