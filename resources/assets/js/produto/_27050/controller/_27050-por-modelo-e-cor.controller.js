// CorPorModeloController.$inject = ['CorPorModeloService'];

// function CorPorModeloController(CorPorModeloService) {

// 	var ctrl = this;

// 	// MÉTODOS (REFERÊNCIAS)
// 	ctrl.consultarCorPorModelo 	= consultarCorPorModelo;
// 	ctrl.selecionarCorPorModelo = selecionarCorPorModelo;

// 	// VARIÁVEIS
// 	ctrl.listaCorPorModelo	= [];
// 	ctrl.corPorModeloSelec	= [];


// 	// MÉTODOS

// 	/**
// 	 * Consultar modelo por cliente.
// 	 */
// 	function consultarCorPorModelo() {

// 		// Verificação para consultar apenas uma vez.
// 		if ( ctrl.listaCorPorModelo == undefined || ctrl.listaCorPorModelo.length == 0 ) {

// 			CorPorModeloService
// 				.consultarCorPorModelo(parseInt(ctrl.modeloId))
// 				.then(function(response) { ctrl.listaCorPorModelo = response; })
// 			;

// 		}

// 	}

// 	/**
// 	 * Selecionar cor.
// 	 */
// 	function selecionarCorPorModelo(cor) {

// 		ctrl.corPorModeloSelec = cor;

// 		$('#modal-consultar-cor-por-modelo').modal('hide');

// 	}

// }