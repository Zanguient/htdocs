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