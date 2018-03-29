/**
 * Factory create (centro de custo) do objeto _23036 - Cadastro de avaliação de desempenho.
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
		this.create 			 	 		= gScope.Ctrl.Create;
		this.create.avaliacao.BASE.CCUSTO 	= [];
		this.listaCCusto		 			= [];
		this.listaCCustoSelecEscolhido 		= [];
		this.listaCCustoExcluir 			= [];

		// Public methods
		this.consultarCCusto 			= consultarCCusto;
		this.selecionarCCusto 			= selecionarCCusto;
		this.selecionarCCustoEscolhido 	= selecionarCCustoEscolhido;
		this.excluirCCustoEscolhido 	= excluirCCustoEscolhido;
		this.fixVsRepeatPesqCCusto 		= fixVsRepeatPesqCCusto;
		this.exibirModal 				= exibirModal;
		this.fecharModal 				= fecharModal;

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

		var baseCCusto	= obj.create.avaliacao.BASE.CCUSTO,
			indexCCusto = baseCCusto.indexOf(ccusto);

		if (indexCCusto > -1)
			baseCCusto.splice(indexCCusto, 1);
		else
			baseCCusto.push(ccusto);
	}

	/**
	 * Selecionar Centro de Custos escolhidos.
	 */
	function selecionarCCustoEscolhido(ccusto) {

		var indexCCusto = obj.listaCCustoSelecEscolhido.indexOf(ccusto);

		if (indexCCusto > -1)
			obj.listaCCustoSelecEscolhido.splice(indexCCusto, 1);
		else
			obj.listaCCustoSelecEscolhido.push(ccusto);
	}

	/**
	 * Excluir Centro de Custos escolhidos.
	 */
	function excluirCCustoEscolhido() {

		var indexCCusto = -1,
			selec 		= {},
			escolhido 	= {},
			baseCCusto  = obj.create.avaliacao.BASE.CCUSTO;

		for (var i in obj.listaCCustoSelecEscolhido) {

			escolhido = obj.listaCCustoSelecEscolhido[i];

			for (var j in baseCCusto) {

				selec = baseCCusto[j];

				if (escolhido.ID == null) {

					indexCCusto = baseCCusto.indexOf(escolhido);

					if (indexCCusto > -1)
						baseCCusto.splice(indexCCusto, 1);
				}
				else if (selec.ID == escolhido.ID) {
					
					selec.STATUSEXCLUSAO = '1';
				}
			}
		}

		obj.listaCCustoSelecEscolhido = [];
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