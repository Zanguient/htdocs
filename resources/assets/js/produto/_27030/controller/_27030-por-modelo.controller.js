CorPorModeloController.$inject = ['CorPorModeloService'];

function CorPorModeloController(CorPorModeloService) {

	var ctrl = this;

	// MÉTODOS (REFERÊNCIAS)
	ctrl.consultarCorPorModelo 	= consultarCorPorModelo;
	ctrl.selecionarCorPorModelo = selecionarCorPorModelo;
	ctrl.fecharModal 			= fecharModal;

	// VARIÁVEIS
	ctrl.listaCorPorModelo	= [];

	this.$onInit = function() {

		ctrl.pedidoItem12040.corPorModelo = this;

	};


	// MÉTODOS

	/**
	 * Consultar cor por modelo.
	 */
	function consultarCorPorModelo() {

		var param = {
			CLIENTE_ID 	: (ctrl.pedidoIndex12040.filtroCliente.cliente !== undefined) ? parseInt(ctrl.pedidoIndex12040.filtroCliente.cliente.CODIGO) : 0,
			MODELO_ID 	: parseInt(ctrl.pedidoItem12040.pedidoItem.modelo.MODELO_CODIGO)
		};

		CorPorModeloService
			.consultarCorPorModelo(param)
			.then(function(response) { 
				ctrl.listaCorPorModelo = response; 
			})
		;

		ctrl.filtrarCorPorModelo = '';

		setTimeout(function() {

			// Fix para vs-repeat.
			$('.table-container-cor-por-modelo')
				.find('.scroll-table')
				.trigger('resize')
				.scrollTop(0);

			// Foco no input de filtrar.
			$('.input-filtrar-cor-por-modelo').focus();

		}, 500);

	}

	/**
	 * Selecionar cor.
	 */
	function selecionarCorPorModelo(cor) {

		ctrl.pedidoItem12040.pedidoItem.cor = cor;
		ctrl.fecharModal();

	}

	/**
	 * Fechar modal.
	 */
	function fecharModal() {

		$('#modal-consultar-cor-por-modelo')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast')
		;
		
	}

}