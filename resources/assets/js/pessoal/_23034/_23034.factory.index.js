/**
 * Factory index do objeto _23034 - Cadastro de resumo para avaliação de desempenho.
 */

angular
	.module('app')
	.factory('Index', Index);    

Index.$inject = [
	'$ajax',
	'gScope'
];

function Index($ajax, gScope) {

	// Private variables.
	var obj = null;

	/**
	 * Constructor, with class name.
	 */
	function Index() {

		obj = this;

		// Public variables
		this.listaResumo    = [];
		this.listaFatorTipo = [];

		// Public methods
		this.filtrar          = filtrar;
		this.habilitarIncluir = habilitarIncluir;
		this.exibir           = exibir;

		// Init methods.
		this.filtrar();
	}
	

	function filtrar() {

		$ajax
			.post('/_23034/consultarResumo')
			.then(function(response){

				obj.listaResumo     = response.RESUMO;
				obj.listaFatorTipo  = response.FATOR_TIPO;

				carregarResumoFatorTipo(response.RESUMO_FATOR_TIPO);
			});

		function carregarResumoFatorTipo(resumoFatorTipo) {

			var rsm = {},
				ftr = {};

			for (var i in obj.listaResumo) {

				rsm = obj.listaResumo[i];

				for (var j in resumoFatorTipo) {

					ftr = resumoFatorTipo[j];

					rsm.FATOR_TIPO = rsm.FATOR_TIPO ? rsm.FATOR_TIPO : [];

					if (rsm.ID == ftr.AVALIACAO_DES_RESUMO_ID)
						rsm.FATOR_TIPO.push(ftr);
				}
			}
		}
	}

	function habilitarIncluir() {

		gScope.Ctrl.tipoTela = 'incluir';

		setTimeout(function() { 
			$('.js-input-descricao').focus(); 
		}, 500);
	}

	function exibir(resumo) {

		gScope.Ctrl.tipoTela = 'exibir';
		gScope.Ctrl.Create.exibir(resumo);
	}


	/**
	 * Return the constructor function
	 */
	return Index;
};