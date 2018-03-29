/**
 * Factory create (resumo) do objeto _23037 - Avaliação de desempenho.
 */

angular
	.module('app')
	.factory('CreateResumo', CreateResumo);    

CreateResumo.$inject = [
	'$ajax',
	'gScope'
];

function CreateResumo($ajax, gScope) {

	// Private variables.
	var obj = null;

	/**
	 * Constructor, with class name.
	 */
	function CreateResumo() {

		obj = this;

		// Public variables
		this.create 			 	 = gScope.Ctrl.Create;
		this.create.avaliacao.RESUMO = [];

		// Public methods
		this.calcularPesoFinal 			= calcularPesoFinal;
		this.calcularResultadoParcial 	= calcularResultadoParcial;
		this.calcularResultadoFinal 	= calcularResultadoFinal;

		// Init methods.
	}

	function calcularPesoFinal() {

		var avaliacao = obj.create.avaliacao;

		avaliacao.PESO_FINAL_RESUMO = 0;
		
		for (var i in avaliacao.RESUMO)
			avaliacao.PESO_FINAL_RESUMO += parseFloat(avaliacao.RESUMO[i].PESO) || 0;
	}

	function calcularResultadoParcial(resumo) {

		var pontuacaoGeral = parseFloat(resumo.PONTUACAO_GERAL);

		resumo.RESULTADO = (pontuacaoGeral == 0) ? 0 : pontuacaoGeral * (parseFloat(resumo.PESO) / 100);

		calcularResultadoFinal();
	}

	function calcularResultadoFinal() {

		var avaliacao = obj.create.avaliacao;

		avaliacao.RESULTADO_FINAL_RESUMO = 0;

		for (var i in avaliacao.RESUMO)
			avaliacao.RESULTADO_FINAL_RESUMO += parseFloat(avaliacao.RESUMO[i].RESULTADO) || 0;

		// Definir se alcançou a meta.
		avaliacao.ALCANCOU_META_MEDIA_GERAL = (avaliacao.RESULTADO_FINAL_RESUMO >= parseFloat(avaliacao.META_MEDIA_GERAL)) ? '1' : '0';
	}

	/**
	 * Return the constructor function
	 */
	return CreateResumo;
};