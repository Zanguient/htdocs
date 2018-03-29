/**
 * Factory create (fator) do objeto _23037 - Avaliação de desempenho.
 */

angular
	.module('app')
	.factory('CreateFator', CreateFator);    

CreateFator.$inject = [
	'$ajax',
	'gScope'
];

function CreateFator($ajax, gScope) {

	// Private variables.
	var obj = null;

	/**
	 * Constructor, with class name.
	 */
	function CreateFator() {

		obj = this;

		// Public variables
		this.create 			 	 		= gScope.Ctrl.Create;
		this.create.avaliacao.FATOR  		= [];
		this.create.avaliacao.FATOR_NIVEL 	= [];

		// Public methods
		this.exibirDescritivo 			= exibirDescritivo;
		this.calcularPontuacao 			= calcularPontuacao;
		this.calcularPontuacaoPorTipo 	= calcularPontuacaoPorTipo;
		this.calcularPontuacaoResumo 	= calcularPontuacaoResumo;

		// Init methods.
	}
	
	function exibirDescritivo(fator) {

		var descritivoContainer 		 = document.getElementById('descritivo-container-'+fator.ID);

		fator.exibeDescritivo 			 = !fator.exibeDescritivo;
		descritivoContainer.style.height = fator.exibeDescritivo ? descritivoContainer.scrollHeight+10+'px' : 0;
	}

	function calcularPontuacao() {

		var avaliacao = obj.create.avaliacao;

		avaliacao.PONTUACAO_TOTAL_FATOR = 0;

		for (var i in avaliacao.FATOR)
			avaliacao.PONTUACAO_TOTAL_FATOR += parseFloat(avaliacao.FATOR[i].PONTO) || 0;

		avaliacao.PONTUACAO_MEDIA_FATOR = avaliacao.PONTUACAO_TOTAL_FATOR / avaliacao.FATOR.length;

		calcularPontuacaoPorTipo();
		calcularPontuacaoResumo();
	}

	function calcularPontuacaoPorTipo() {

		var avaliacao 	= obj.create.avaliacao,
			tipo 		= {},
			qtdTipo 	= 0,
			fator 		= {};		

		for (var i in avaliacao.FATOR_TIPO) {

			tipo = avaliacao.FATOR_TIPO[i];

			qtdTipo = 0;
			tipo.PONTUACAO_TOTAL = 0;

			for (var j in avaliacao.FATOR) {

				fator = avaliacao.FATOR[j];

				if (fator.TIPO_ID == tipo.AVALIACAO_DES_FATOR_TIPO) {
					
					tipo.PONTUACAO_TOTAL += parseFloat(fator.PONTO) || 0;
					qtdTipo += 1;
				}
			}

			tipo.PONTUACAO_MEDIA = tipo.PONTUACAO_TOTAL / qtdTipo;
		}
	}

	function calcularPontuacaoResumo() {

		var avaliacao 			= obj.create.avaliacao,
			resumo 				= {},
			fator 				= {},
			pontuacaoTotalFator = 0,
			qtdFator 			= 0;

		for (var j in avaliacao.RESUMO) {

			resumo 				= avaliacao.RESUMO[j];
			qtdFator 			= 0;
			pontuacaoTotalFator = 0;

			for (var i in avaliacao.FATOR) {

				fator = avaliacao.FATOR[i];	

				if (resumo.FATOR_TIPO_ID && resumo.FATOR_TIPO_ID.includes(fator.TIPO_ID)) {

					pontuacaoTotalFator += parseFloat(fator.PONTO) || 0;
					qtdFator++;
				}
			}

			// Se houver algum tipo de fator definido para o item resumo.
			if (resumo.FATOR_TIPO_ID != '0' && resumo.FATOR_TIPO_ID != null && resumo.FATOR_TIPO_ID != '')
				resumo.PONTUACAO_GERAL = pontuacaoTotalFator / qtdFator;

			gScope.Ctrl.CreateResumo.calcularResultadoParcial(resumo);
		}
	}

	/**
	 * Return the constructor function
	 */
	return CreateFator;
};