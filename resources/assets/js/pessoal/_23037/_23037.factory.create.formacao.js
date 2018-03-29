/**
 * Factory create (formação) do objeto _23037 - Avaliação de desempenho.
 */

angular
	.module('app')
	.factory('CreateFormacao', CreateFormacao);

CreateFormacao.$inject = [
	'$ajax',
	'gScope'
];

function CreateFormacao($ajax, gScope) {

	// Private variables.
	var obj = null;

	/**
	 * Constructor, with class name.
	 */
	function CreateFormacao() {

		obj = this;

		// Public variables
		this.create 			 		= gScope.Ctrl.Create;
		this.create.avaliacao.FORMACAO 	= [];

		// Public methods
		this.selecionarFormacao = selecionarFormacao;
		this.alterarFormacao 	= alterarFormacao;

		// Init methods.
	}

	/**
	 * Selecionar formação ao escolher colaborador.
	 */
	function selecionarFormacao(colaborador) {

		var escolaridadeColab 	 = parseInt(colaborador.PESSOAL_ESCOLARIDADE),
			escolaridadeEsperada = parseInt(colaborador.CARGO_ESCOLARIDADE),
			formacao 			 = {};

		// Está estudando.
		if (escolaridadeColab == 8 && escolaridadeEsperada == 9) {
			obj.create.avaliacao.FORMACAO_ESCOLHIDA_ID = 3;
		}
		// Tem a formação esperada.
		else if (escolaridadeColab >= escolaridadeEsperada) {
			obj.create.avaliacao.FORMACAO_ESCOLHIDA_ID = 4;
		}
		// Não tem a formação esperada.
		else if (escolaridadeColab < escolaridadeEsperada) {
			obj.create.avaliacao.FORMACAO_ESCOLHIDA_ID = 1;
		}

		for (var i in obj.create.avaliacao.FORMACAO) {

			formacao = obj.create.avaliacao.FORMACAO[i];

			if (formacao.ID == obj.create.avaliacao.FORMACAO_ESCOLHIDA_ID) {

				obj.alterarFormacao(formacao);
				break;
			}
		}
	}

	/**
	 * Alterações no resumo ao alterar formação.
	 */
	function alterarFormacao(formacao) {

		var resumo = {};

		for (var i in obj.create.avaliacao.RESUMO) {

			resumo = obj.create.avaliacao.RESUMO[i];
			
			// Se não houver algum tipo de fator definido para o item resumo.
			if (resumo.FATOR_TIPO_ID == '0' || resumo.FATOR_TIPO_ID == null || resumo.FATOR_TIPO_ID == '') {
				
				resumo.PONTUACAO_GERAL = formacao.PONTO;
				gScope.Ctrl.CreateResumo.calcularResultadoParcial(resumo);
			}
		}
	}


	/**
	 * Return the constructor function
	 */
	return CreateFormacao;
};