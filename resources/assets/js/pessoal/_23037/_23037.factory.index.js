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