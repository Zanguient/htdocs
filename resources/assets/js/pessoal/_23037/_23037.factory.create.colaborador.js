/**
 * Factory create (colaborador) do objeto _23037 - Avaliação de desempenho.
 */

angular
	.module('app')
	.factory('CreateColaborador', CreateColaborador);    

CreateColaborador.$inject = [
	'$ajax',
	'gScope',
	'$timeout'
];

function CreateColaborador($ajax, gScope, $timeout) {

	// Private variables.
	var obj = null;

	/**
	 * Constructor, with class name.
	 */
	function CreateColaborador() {

		obj = this;

		// Public variables
		this.create 			 	 		= gScope.Ctrl.Create;
		this.create.avaliacao.COLABORADOR 	= {};
		this.listaColaborador		 		= [];
		this.gestorPadrao 					= {};

		// Public methods
		this.definirGestor 					 = definirGestor;
		this.consultarColaborador 			 = consultarColaborador;
		this.selecionarColaborador 			 = selecionarColaborador;
		this.consultarColaboradorIndicador 	 = consultarColaboradorIndicador;
		this.calcularIndicador 				 = calcularIndicador;
		this.fixVsRepeatPesqColaborador 	 = fixVsRepeatPesqColaborador;
		this.exibirModal 					 = exibirModal;
		this.fecharModal 					 = fecharModal;

		// Init methods.
		this.definirGestor();
	}

	function definirGestor() {

		obj.gestorPadrao = {
			ID 		 : document.getElementById('usuario-id').value,
			DESCRICAO: document.getElementById('usuario-descricao').value
		};

		obj.create.avaliacao.GESTOR = angular.copy(obj.gestorPadrao);
	}
	
	function consultarColaborador() {

		$ajax
			.post('/_23037/consultarColaborador')
			.then(function(response) {

				obj.listaColaborador = response;
			});
	}

	function selecionarColaborador(colaborador) {

		colaborador.DATA_ADMISSAO_INPUT	 = moment(colaborador.DATA_ADMISSAO).toDate();
		obj.create.avaliacao.COLABORADOR = colaborador;

		obj.consultarColaboradorIndicador(colaborador);
		gScope.Ctrl.CreateFormacao.selecionarFormacao(colaborador);
		obj.fecharModal();
	}

	function consultarColaboradorIndicador(colaborador) {

		var param = {
			COLABORADOR : colaborador,
			DATA_INI 	: moment().subtract(1, 'years').format('YYYY-MM-DD'),
			DATA_FIM	: moment().format('YYYY-MM-DD')
		};

		$ajax
			.post('/_23037/consultarColaboradorIndicador', param)
			.then(function(response) {

				obj.calcularIndicador(response.ABSENTEISMO[0].ABSENTEISMO, true);
				
				for (var i in response.INDICADOR)
					obj.calcularIndicador(response.INDICADOR[i].PERC_INDICADOR, false, response.INDICADOR[i].INDICADOR_ID);
			});
	}

	/**
	 * Calcular indicador de absenteísmo a partir da porcentagem de absenteísmo do colaborador.
	 * A porcentagem é inversamente proporcional ao indicador.
	 * Ex.: Indicador = 46 à 65
	 *		Porcentagem = 2.99 à 2.75
	 */
	function calcularIndicador(percentualIndicador, ehAbsenteismo, indicadorId) {
		
		var fator = {};

		percentualIndicador = parseFloat(percentualIndicador);
		ehAbsenteismo 		= (typeof ehAbsenteismo == 'undefined') ? false : ehAbsenteismo;
		indicadorId 		= (typeof indicadorId   == 'undefined') ? 0 	: indicadorId;

		for (var i in obj.create.avaliacao.FATOR) {
			
			fator = obj.create.avaliacao.FATOR[i];

			// Quando for absenteísmo, calcula e define somente ele.
			if (ehAbsenteismo && fator.TITULO.match(/absente/i)) {

				efetuar();
				break;
			}
			else if (indicadorId == fator.FATOR_ID) {
				
				efetuar();
			}
		}


		function efetuar() {

			var indicador1 				 = 0,
				indicador2 				 = 0,
				perc1 					 = 0,
				perc2 					 = 0,
				diferencaIndicador 		 = 0,
				menorIndicador 			 = 0,
				maiorIndicador 			 = 0,
				diferencaPercentualFaixa = 0,
				menorPercentual 		 = 0,
				maiorPercentual 		 = 0,
				diferencaPercentualAbs 	 = 0,
				resultado 				 = 0,
				nivel 					 = {};

			for (var j in obj.create.avaliacao.FATOR_NIVEL) {
		
				nivel = obj.create.avaliacao.FATOR_NIVEL[j];

				if (nivel.FATOR_ID == fator.FATOR_ID) {

					// Converter number (string) para float.
					nivel.DESCRITIVO_FAIXA_INICIAL 	= nivel.DESCRITIVO_FAIXA_INICIAL == null ? 0 : parseFloat(nivel.DESCRITIVO_FAIXA_INICIAL);
					nivel.DESCRITIVO_FAIXA_FINAL 	= nivel.DESCRITIVO_FAIXA_FINAL	 == null ? 0 : parseFloat(nivel.DESCRITIVO_FAIXA_FINAL);
					
					// Indicadores.
					indicador1 = nivel.FAIXA_INICIAL;
					indicador2 = nivel.FAIXA_FINAL;

					if (percentualIndicador >= nivel.DESCRITIVO_FAIXA_INICIAL && percentualIndicador <= nivel.DESCRITIVO_FAIXA_FINAL) {
						
						perc1 = nivel.DESCRITIVO_FAIXA_INICIAL;
						perc2 = nivel.DESCRITIVO_FAIXA_FINAL;

						break;
					}
				}
			}

			menorIndicador 		 	 = (indicador1 > indicador2) ? indicador2 : indicador1;
			maiorIndicador 		 	 = (indicador1 < indicador2) ? indicador2 : indicador1;
			diferencaIndicador 		 = maiorIndicador - menorIndicador;

			menorPercentual 		 = (perc1 > perc2) ? perc2 : perc1;
			maiorPercentual 		 = (perc1 < perc2) ? perc2 : perc1;
			diferencaPercentualFaixa = maiorPercentual - menorPercentual;

			// Se a ordem da faixa do descritivo do fator for decrescente.
			if (fator.ORDEM_PERC_NIVEL == '1')
				diferencaPercentualAbs = maiorPercentual - percentualIndicador;
			else
				diferencaPercentualAbs = percentualIndicador - menorPercentual;

			resultado = (diferencaPercentualFaixa == 0) 
							? 0 : (diferencaIndicador * diferencaPercentualAbs) / diferencaPercentualFaixa;

			resultado = resultado + menorIndicador;

			fator.PONTO = (resultado < 0) ? 0 : parseFloat(resultado.toFixed(2));
			fator.jaCalculado = true;
		}
	}

	/**
     * Fix para vs-repeat: exibir a tabela completa.
     */
    function fixVsRepeatPesqColaborador() {

        $timeout(function(){
            $('#modal-pesq-colaborador .table-colaborador').scrollTop(0);
        }, 200);

    }

	function exibirModal() {

		if (obj.listaColaborador.length == 0)
			obj.consultarColaborador();

		$('#modal-pesq-colaborador').modal('show');

		setTimeout(function() {
			$('.js-input-filtrar-colaborador').focus();
		}, 500);

		obj.fixVsRepeatPesqColaborador();
	}

	function fecharModal() {

		$('#modal-pesq-colaborador')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast');

		obj.filtrarColaborador = "";
	}

	/**
	 * Return the constructor function
	 */
	return CreateColaborador;
};