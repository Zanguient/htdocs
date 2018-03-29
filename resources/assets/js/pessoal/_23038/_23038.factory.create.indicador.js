/**
 * Factory create (indicador) do objeto _23038 - Registro de indicadores por centro de custo.
 */

angular
	.module('app')
	.factory('CreateIndicador', CreateIndicador);

CreateIndicador.$inject = [
	'$ajax',
	'gScope',
	'$timeout'
];

function CreateIndicador($ajax, gScope, $timeout) {

	// Private variables.
	var obj = null;

	/**
	 * Constructor, with class name.
	 */
	function CreateIndicador() {

		obj = this;

		// Public variables
		this.create 			 	    		 = gScope.Ctrl.Create;
		this.create.indicadorPorCCusto.INDICADOR = {};
		this.listaIndicador		 	    		 = [];

		// Public methods
		this.consultarIndicador		    = consultarIndicador;
		this.selecionarIndicador 		= selecionarIndicador;
		this.fixVsRepeatPesqIndicador 	= fixVsRepeatPesqIndicador;
		this.exibirModal 			    = exibirModal;
		this.fecharModal 			    = fecharModal;

		// Init methods.
	}

	function consultarIndicador() {

		$ajax
			.post('/_23038/consultarIndicador')
			.then(function(response) {

				obj.listaIndicador = response;
			});
	}

	/**
	 * Selecionar indicadores (modal).
	 */
	function selecionarIndicador(indicador) {

		obj.create.indicadorPorCCusto.INDICADOR = indicador;
		obj.fecharModal();
	}

	/**
     * Fix para vs-repeat: exibir a tabela completa.
     */
    function fixVsRepeatPesqIndicador() {

        $timeout(function() {
            $('#modal-pesq-indicador .table-indicador').scrollTop(0);
        }, 200);

    }

	function exibirModal() {

		if (obj.listaIndicador.length == 0)
			obj.consultarIndicador();

		$('#modal-pesq-indicador').modal('show');

		setTimeout(function() {
			$('.js-input-filtrar-indicador').focus();
		}, 500);

		obj.fixVsRepeatPesqIndicador();
	}

	function fecharModal() {

		$('#modal-pesq-indicador')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast');

		obj.filtrarIndicador = "";
	}

	/**
	 * Return the constructor function
	 */
	return CreateIndicador;
};
