/**
 * App do objeto _23037 - Avaliação de desempenho.
 */

'use strict';

angular
	.module('app', [
		'vs-repeat', 
        'gc-find',
		'gc-ajax',
		'gc-transform',
		'gc-form'
	])
;
/**
 * Controller do objeto _23037 - Avaliação de desempenho.
 */

angular
	.module('app')
	.value('gScope', {})
	.controller('Ctrl', Ctrl);

Ctrl.$inject = [
	'$scope',
	'gScope',
	'Historico',
	'Index',
	'Create',
	'CreateColaborador',
	'CreateFator',
	'CreateFormacao',
	'CreateResumo'
];

function Ctrl( 
	$scope,
	gScope,
	Historico,
	Index,
	Create,
	CreateColaborador,
	CreateFator,
	CreateFormacao,
	CreateResumo
) {

	// Public instance.
	gScope.Ctrl = this;

	// Local instance.
	var $ctrl = this;

	// Global variables.
	$ctrl.tipoTela      = 'listar';
	$ctrl.permissaoMenu = {};
	$ctrl.Historico     = new Historico('$ctrl.Historico', $scope);
	$ctrl.tipoFuncao    = 'base'; 	// resposta ou base

	// Objects.
	$ctrl.Index 			= new Index();
	$ctrl.Create 		 	= new Create();
	$ctrl.CreateColaborador = new CreateColaborador();
	$ctrl.CreateFator 	 	= new CreateFator();
	$ctrl.CreateFormacao 	= new CreateFormacao();
	$ctrl.CreateResumo 	 	= new CreateResumo();
}
/**
 * Factory index do objeto _23037 - Avaliação de desempenho.
 */

angular
	.module('app')
	.factory('Index', Index);    

Index.$inject = [
	'$ajax',
	'gScope',
	'$timeout'
];

function Index($ajax, gScope, $timeout) {

	// Private variables.
	var obj = null;

	/**
	 * Constructor, with class name.
	 */
	function Index() {

		obj = this;

		// Public variables
		this.filtroBase 				= {};
		this.filtroResposta				= {};
		this.listaBase 					= [];
		this.listaResposta 				= [];

		// Public methods
		this.init 						= init;
		this.filtrarBase 	  	 		= filtrarBase;
		this.filtrarResposta  	 		= filtrarResposta;
		this.exibirAvaliacao  	 		= exibirAvaliacao;
		this.verListaResposta 	 		= verListaResposta;
		this.exibirModalListaResposta 	= exibirModalListaResposta;
		this.fecharModalListaResposta 	= fecharModalListaResposta;
		this.fixVsRepeatListaResposta 	= fixVsRepeatListaResposta;
		this.exibirResposta   	 		= exibirResposta;

		// Init methods.
		this.init();
	}
	
	function init() {
		
		this.filtroBase.STATUS			= '1';
		this.filtroBase.TODOS_CCUSTO	= 0;
		this.filtroBase.DATA_INI_INPUT 	= moment().subtract(1, "month").toDate();
		this.filtroBase.DATA_FIM_INPUT 	= moment().toDate();

		this.filtroResposta.TODOS_CCUSTO	= 0;
		this.filtroResposta.DATA_INI_INPUT 	= moment().subtract(1, "month").toDate();
		this.filtroResposta.DATA_FIM_INPUT 	= moment().toDate();
	}

	function filtrarBase() {

		obj.filtroBase.DATA_INI = moment(obj.filtroBase.DATA_INI_INPUT).format('YYYY-MM-DD');
		obj.filtroBase.DATA_FIM = moment(obj.filtroBase.DATA_FIM_INPUT).format('YYYY-MM-DD');

		$ajax
			.post('/_23037/consultarBase', obj.filtroBase)
			.then(function(response) {

				obj.listaBase = response;

				formatarCampo();
			});

		function formatarCampo() {

			var base = {};

			for (var i in obj.listaBase) {

				base = obj.listaBase[i];

				base.DATA_AVALIACAO_HUMANIZE = moment(base.DATA_AVALIACAO).format('MMM YYYY');
			}
		}
	}

	function filtrarResposta() {

		obj.filtroResposta.DATA_INI = moment(obj.filtroResposta.DATA_INI_INPUT).format('YYYY-MM-DD');
		obj.filtroResposta.DATA_FIM = moment(obj.filtroResposta.DATA_FIM_INPUT).format('YYYY-MM-DD');

		$ajax
			.post('/_23037/consultarAvaliacao', obj.filtroResposta)
			.then(function(response) {

				obj.listaResposta = response;

				formatarCampo();
			});

		function formatarCampo() {

			var resp = {};

			for (var i in obj.listaResposta) {

				resp = obj.listaResposta[i];

				resp.DATA_AVALIACAO_HUMANIZE  = moment(resp.DATA_AVALIACAO).format('MMM YYYY');
				resp.DATAHORA_INSERT_HUMANIZE = moment(resp.DATAHORA_INSERT).format('DD/MM/YYYY HH:mm:ss');
			}
		}
	}

	function exibirAvaliacao(base) {

		var create = gScope.Ctrl.Create;
		gScope.Ctrl.tipoTela = 'exibir';

		$ajax
			.post('/_23037/consultarModeloItem', base)
			.then(function(response) {

				create.avaliacao.AVALIACAO_DES_RESP_BASE_ID	= base.ID;
				create.avaliacao.TITULO 					= base.TITULO;
				create.avaliacao.INSTRUCAO_INICIAL 			= base.INSTRUCAO_INICIAL;
				create.avaliacao.STATUS 					= base.STATUS;
				create.avaliacao.META_MEDIA_GERAL 			= parseFloat(base.META_MEDIA_GERAL);
				create.avaliacao.DATA_AVALIACAO 			= base.DATA_AVALIACAO;
				create.avaliacao.DATA_AVALIACAO_INPUT 		= moment(base.DATA_AVALIACAO).toDate();

				create.avaliacao.FATOR 		 = response.FATOR;
				create.avaliacao.FATOR_TIPO  = response.FATOR_TIPO;
				create.avaliacao.FATOR_NIVEL = response.FATOR_NIVEL;
				create.avaliacao.FORMACAO 	 = response.FORMACAO;
				create.avaliacao.RESUMO 	 = response.RESUMO;

				gScope.Ctrl.CreateResumo.calcularPesoFinal();

				angular.copy(create.avaliacao, create.avaliacaoBkp);

				gScope.Ctrl.tipoFuncao = 'base';
				create.exibirModalAvaliacao();
			});
	}

	function verListaResposta(modalAberto) {

		modalAberto = (typeof modalAberto == 'undefined') ? false : modalAberto;

		gScope.Ctrl.tipoTela = 'listar';

		if (!modalAberto)
			obj.exibirModalListaResposta();
	}

	function exibirModalListaResposta() {

		$('#modal-resposta').modal('show');
		obj.fixVsRepeatListaResposta();
	}

	function fecharModalListaResposta() {

		$('#modal-resposta')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast');		
	}

	/**
     * Fix para vs-repeat: exibir a tabela completa.
     */
    function fixVsRepeatListaResposta() {

        $timeout(function(){
            $('#modal-resposta .table-resposta').scrollTop(0);
        }, 200);
    }

	function exibirResposta(avaliacao) {

		var create = gScope.Ctrl.Create;
		gScope.Ctrl.tipoTela = 'exibir';

		$ajax
			.post('/_23037/consultarAvaliacaoItem', avaliacao)
			.then(function(response) {

				create.avaliacao           		= avaliacao;
				create.avaliacao.FATOR     		= response.FATOR;
				create.avaliacao.FATOR_TIPO     = response.FATOR_TIPO;
				create.avaliacao.FATOR_NIVEL    = response.FATOR_NIVEL;
				create.avaliacao.FORMACAO  		= response.FORMACAO;
				create.avaliacao.RESUMO    		= response.RESUMO;
				create.avaliacao.GESTOR 		= angular.copy(gScope.Ctrl.CreateColaborador.gestorPadrao);

				carregarColaborador();
				formatarCampo();
				gScope.Ctrl.CreateResumo.calcularPesoFinal();

				angular.copy(create.avaliacao, create.avaliacaoBkp);
				gScope.Ctrl.tipoFuncao = 'resposta';
				create.exibirModalAvaliacao();
			});


		function carregarColaborador() {

			create.avaliacao.COLABORADOR = {
				CODIGO 							: create.avaliacao.COLABORADOR_ID,
				PESSOAL_NOME 					: create.avaliacao.COLABORADOR_NOME,
				CARGO_CODIGO 					: create.avaliacao.COLABORADOR_CARGO_ID,
				CARGO_DESCRICAO 				: create.avaliacao.COLABORADOR_CARGO,
				GESTOR_ID						: create.avaliacao.COLABORADOR_GESTOR_ID,
				GESTOR 							: create.avaliacao.COLABORADOR_GESTOR,
				CENTRO_DE_CUSTO_CODIGO			: create.avaliacao.COLABORADOR_CCUSTO_CODIGO,
				CENTRO_DE_CUSTO_DESCRICAO		: create.avaliacao.COLABORADOR_CCUSTO_DESCRICAO,
				DATA_ADMISSAO 					: create.avaliacao.COLABORADOR_ADMISSAO,
				PESSOAL_ESCOLARIDADE 			: create.avaliacao.COLABORADOR_ESCOLARIDADE_ID,
				PESSOAL_ESCOLARIDADE_DESCRICAO 	: create.avaliacao.COLABORADOR_ESCOLARIDADE_DESC
			};
		}

		function formatarCampo() {

			create.avaliacao.DATA_AVALIACAO_INPUT 				= moment(create.avaliacao.DATA_AVALIACAO).toDate();
			create.avaliacao.DATA_AVALIACAO_HUMANIZE_LONG 		= moment(create.avaliacao.DATA_AVALIACAO).format('MMMM YYYY');
			create.avaliacao.DATAHORA_INSERT_INPUT 				= moment(create.avaliacao.DATAHORA_INSERT).toDate();
			create.avaliacao.DATAHORA_INSERT_HUMANIZE			= moment(create.avaliacao.DATAHORA_INSERT).format('DD/MM/YYYY HH:mm:ss');
			create.avaliacao.COLABORADOR.DATA_ADMISSAO_INPUT 	= moment(create.avaliacao.COLABORADOR.DATA_ADMISSAO).toDate();
			create.avaliacao.COLABORADOR.DATA_ADMISSAO_HUMANIZE = moment(create.avaliacao.COLABORADOR.DATA_ADMISSAO).format('DD/MM/YYYY');
			create.avaliacao.META_MEDIA_GERAL 					= parseFloat(create.avaliacao.META_MEDIA_GERAL);

			for (var i in create.avaliacao.FATOR)
				create.avaliacao.FATOR[i].PONTO = parseFloat(create.avaliacao.FATOR[i].PONTO);
		}
	}


	/**
	 * Return the constructor function
	 */
	return Index;
};
/**
 * Factory create do objeto _23037 - Avaliação de desempenho.
 */

angular
	.module('app')
	.factory('Create', Create);    

Create.$inject = [
	'$ajax',
	'gScope'
];

function Create($ajax, gScope) {

	// Private variables.
	var obj = null;

	/**
	 * Constructor, with class name.
	 */
	function Create() {

		obj = this;

		// Public variables
		this.avaliacao 		= {};
		this.avaliacaoBkp 	= {};

		// Public methods
		this.init 	            		 = init;
		this.formatarCampo 				 = formatarCampo;
		this.gravarAvaliacao    		 = gravarAvaliacao;
		this.excluirAvaliacao   		 = excluirAvaliacao;
		this.limparCampo        		 = limparCampo;
		this.habilitarResponder 		 = habilitarResponder;
		this.cancelarAlteracaoAvaliacao  = cancelarAlteracaoAvaliacao;
		this.exibirModalAvaliacao 		 = exibirModalAvaliacao;
		this.fecharModalAvaliacao 		 = fecharModalAvaliacao;
		this.imprimirAvaliacao 		 	 = imprimirAvaliacao;

		// Init methods.
		this.init();
	}

	function init() {

		obj.formatarCampo();
	}

	function formatarCampo() {

		obj.avaliacao.DATA_AVALIACAO_INPUT = moment().toDate();
	}

	function gravarAvaliacao() {

		obj.avaliacao.DATA_AVALIACAO = moment(obj.avaliacao.DATA_AVALIACAO_INPUT).format('YYYY-MM-DD');
		
		$ajax
			.post('/_23037/gravarAvaliacao', obj.avaliacao)
			.then(function(response) {

				showSuccess('Gravado com sucesso.');
				gScope.Ctrl.Index.verListaResposta(true);
				obj.fecharModalAvaliacao();
			});
	}

	function excluirAvaliacao() {

		confirmar();

		function confirmar() {

			addConfirme(
				'<h4>Confirmação</h4>',
				'Confirma a exclusão?',
				[obtn_sim, obtn_nao],
				[
					{
						ret: 1,
						func: function() {

							efetivar();
						}
					},
					{
						ret: 2,
						func: function() {}
					}
				]
			);
		}

		function efetivar() {

			$ajax
				.post('/_23037/excluirAvaliacao', obj.avaliacao)
				.then(function(response) {

					showSuccess('Excluído com sucesso.');
					gScope.Ctrl.Index.verListaResposta(true);
					gScope.Ctrl.Index.filtrarResposta();
					fecharModalAvaliacao();
				});
		}
	}

	function limparCampo() {

		obj.avaliacao 				= {};
		obj.avaliacao.COLABORADOR	= {};
		obj.avaliacao.FATOR     	= [];
		obj.avaliacao.FATOR_TIPO	= [];
		obj.avaliacao.FATOR_NIVEL	= [];
		obj.avaliacao.FORMACAO  	= [];
		obj.avaliacao.RESUMO 		= [];
		obj.avaliacao.GESTOR 		= angular.copy(gScope.Ctrl.CreateColaborador.gestorPadrao);

		obj.formatarCampo();
	}

	function habilitarResponder() {

		gScope.Ctrl.tipoTela = 'responder';
	}

	function cancelarAlteracaoAvaliacao() {

		angular.copy(obj.avaliacaoBkp, obj.avaliacao);
		gScope.Ctrl.tipoTela = 'exibir';
	}

	function exibirModalAvaliacao() {

		$('#modal-avaliacao').modal('show');
	}

	function fecharModalAvaliacao() {

		$('#modal-avaliacao')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast');

		obj.limparCampo();		
	}

	function imprimirAvaliacao() {

		preparar();

		setTimeout(function() {

			printHtml(
				'print-avaliacao-desempenho', 
				'Relatório de avaliação de desempenho', 
				'', 
				$('#usuario-descricao').val(), 
				'1.0', 
				2, 
				'/assets/css/23037-print.css'
			);
			
		}, 500);

		function preparar() {

			var fator = {}, 
				nivel = {};

			for (var i in obj.avaliacao.FATOR) {

				fator = obj.avaliacao.FATOR[i];

				for (var j in obj.avaliacao.FATOR_NIVEL) {

					nivel = obj.avaliacao.FATOR_NIVEL[j];

					if ((nivel.FATOR_ID == fator.FATOR_ID) 
						&& ((fator.PONTO >= nivel.FAIXA_INICIAL) && (fator.PONTO <= nivel.FAIXA_FINAL))
					) {
					
						fator.NIVEL_PRINT = nivel;
					}
				}
			}
		}
	}

	/**
	 * Return the constructor function
	 */
	return Create;
};
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
//# sourceMappingURL=_23037.js.map
