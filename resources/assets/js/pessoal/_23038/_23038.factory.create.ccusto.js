/**
 * Factory create (centro de custo) do objeto _23038 - Registro de indicadores por centro de custo.
 */

angular
	.module('app')
	.factory('CreateCCusto', CreateCCusto);

CreateCCusto.$inject = [
	'$ajax',
	'gScope',
	'$timeout'
];

function CreateCCusto($ajax, gScope, $timeout) {

	// Private variables.
	var obj = null;

	/**
	 * Constructor, with class name.
	 */
	function CreateCCusto() {

		obj = this;

		// Public variables
		this.create 			 	 			= gScope.Ctrl.Create;
		this.create.indicadorPorCCusto.CCUSTO 	= {};
		this.listaCCusto		 	 			= [];

		// Public methods
		this.consultarCCusto 		= consultarCCusto;
		this.selecionarCCusto 		= selecionarCCusto;
		this.fixVsRepeatPesqCCusto 	= fixVsRepeatPesqCCusto;
		this.exibirModal 			= exibirModal;
		this.fecharModal 			= fecharModal;

		// Init methods.
	}

	function consultarCCusto() {

		$ajax
			.post('/_20030/pesquisaCCustoTodos')
			.then(function(response) {

				for (var i in response) {

					response[i].CODIGO = response[i].ID;
					delete response[i].ID;
				}

				obj.listaCCusto = response;
			});
	}

	/**
	 * Selecionar Centro de Custos (modal).
	 */
	function selecionarCCusto(ccusto) {

		obj.create.indicadorPorCCusto.CCUSTO = ccusto;
		obj.fecharModal();
	}

	/**
     * Fix para vs-repeat: exibir a tabela completa.
     */
    function fixVsRepeatPesqCCusto() {

        $timeout(function() {
            $('#modal-pesq-ccusto .table-ccusto').scrollTop(0);
        }, 200);

    }

	function exibirModal() {

		if (obj.listaCCusto.length == 0)
			obj.consultarCCusto();

		$('#modal-pesq-ccusto').modal('show');

		setTimeout(function() {
			$('.js-input-filtrar-ccusto').focus();
		}, 500);

		obj.fixVsRepeatPesqCCusto();
	}

	function fecharModal() {

		$('#modal-pesq-ccusto')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast');

		obj.filtrarCCusto = "";
	}

	/**
	 * Return the constructor function
	 */
	return CreateCCusto;
};
