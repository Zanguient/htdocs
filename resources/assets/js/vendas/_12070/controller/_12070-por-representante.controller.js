ClientePorRepresentanteController.$inject = ['ClientePorRepresentanteService'];

function ClientePorRepresentanteController(ClientePorRepresentanteService) {

	var ctrl = this;

	// MÉTODOS (REFERÊNCIAS)
	ctrl.consultarClientePorRepresentantePorUrl	= consultarClientePorRepresentantePorUrl;
	ctrl.consultarClientePorRepresentante  		= consultarClientePorRepresentante;
	ctrl.selecionarClientePorRepresentante 		= selecionarClientePorRepresentante;
	ctrl.fecharModal			 				= fecharModal;

	// VARIÁVEIS
	ctrl.listaClientePorRepresentante	= [];

	this.$onInit = function() {

		ctrl.pedidoIndex12040.filtroCliente = this;

		ctrl.clienteIdUrl = parseInt(getURLParameter('clienteId'));
	};


	// MÉTODOS

	/**
	 * Consultar e selecionar cliente por representante através de parâmetro na URL.
	 */
	function consultarClientePorRepresentantePorUrl() {

		var representanteId = ctrl.pedidoIndex12040.filtro.representante.CODIGO;

		ClientePorRepresentanteService
			.consultarClientePorRepresentante(representanteId)
			.then(function(response) {

				ctrl.listaClientePorRepresentante = response;

				var cli = null;

				for (var i in ctrl.listaClientePorRepresentante) {

					cli = ctrl.listaClientePorRepresentante[i];

					if (cli.CODIGO == ctrl.clienteIdUrl) {

						ctrl.pedidoIndex12040.filtroCliente.cliente = cli;
						ctrl.pedidoIndex12040.consultarPedidoPorUrl();
						break;
					}
				}
			});
	}

	/**
	 * Consultar cliente por representante.
	 */
	function consultarClientePorRepresentante() {

		var representanteId = (ctrl.pedidoIndex12040.representanteId === null)
								? parseInt(ctrl.pedidoIndex12040.filtro.representante.CODIGO) 
								: parseInt(ctrl.pedidoIndex12040.representanteId)
		;

		ClientePorRepresentanteService
			.consultarClientePorRepresentante(representanteId)
			.then(function(response) { 
				ctrl.listaClientePorRepresentante = response; 
			})
		;

		setTimeout(function() {

			// Fix para vs-repeat.
			$('.table-container-por-representante')
				.find('.scroll-table')
				.trigger('resize')
				.scrollTop(0);

			// Foco no input para filtrar.
			$('.input-filtrar-cliente').select();

		}, 500);

	}

	/**
	 * Selecionar cliente por representante.
	 */
	function selecionarClientePorRepresentante(cliente) {

		ctrl.pedidoIndex12040.filtroCliente.cliente = cliente;
		ctrl.fecharModal();
	}

	/**
	 * Fechar modal.
	 */
	function fecharModal() {

		$('#modal-por-representante')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast')
		;

		ctrl.filtrarCliente = '';
	}

}