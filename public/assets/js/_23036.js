/**
 * App do objeto _23036 - Cadastro de avaliação de desempenho.
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
 * Controller do objeto _23036 - Cadastro de avaliação de desempenho.
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
	'CreateModelo',
	'CreateCCusto'
];

function Ctrl( 
	$scope,
	gScope,
	Historico,
	Index,
	Create,
	CreateModelo,
	CreateCCusto
) {

	// Public instance.
	gScope.Ctrl = this;

	// Local instance.
	var $ctrl = this;

	// Global variables.
	$ctrl.tipoTela      = 'listar';
	$ctrl.permissaoMenu = {};
	$ctrl.Historico     = new Historico('$ctrl.Historico', $scope);

	// Objects.
	$ctrl.Index  		 	= new Index();
	$ctrl.Create 		 	= new Create();
	$ctrl.CreateModelo 	 	= new CreateModelo();
	$ctrl.CreateCCusto 	 	= new CreateCCusto();
}
/**
 * Factory index do objeto _23036 - Cadastro de avaliação de desempenho.
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
		this.filtro 			= {};
		this.listaAvaliacaoBase = [];

		// Public methods
		this.init 				= init;
		this.filtrar 			= filtrar;
		this.habilitarIncluir   = habilitarIncluir;
		this.exibirBase         = exibirBase;

		// Init methods.
		this.init();
	}
	
	function init() {
		
		this.filtro.STATUS			= '1';
		this.filtro.DATA_INI_INPUT 	= moment().subtract(1, "month").toDate();
		this.filtro.DATA_FIM_INPUT 	= moment().toDate();
	}

	function filtrar() {

		obj.filtro.DATA_INI = moment(obj.filtro.DATA_INI_INPUT).format('YYYY-MM-DD');
		obj.filtro.DATA_FIM = moment(obj.filtro.DATA_FIM_INPUT).format('YYYY-MM-DD');

		$ajax
			.post('/_23036/consultarBaseAvaliacao', obj.filtro)
			.then(function(response) {

				obj.listaAvaliacaoBase = response;

				formatarCampo();
			});

		function formatarCampo() {

			var base = {};

			for (var i in obj.listaAvaliacaoBase) {

				base = obj.listaAvaliacaoBase[i];

				base.DATA_AVALIACAO_HUMANIZE = moment(base.DATA_AVALIACAO).format('MMM YYYY');
			}
		}
	}

	function habilitarIncluir() {

		gScope.Ctrl.tipoTela = 'incluir';
	}

	function exibirBase(base) {

		var create = gScope.Ctrl.Create;
		gScope.Ctrl.tipoTela = 'exibir';

		$ajax
			.post('/_23036/consultarBaseCCustoAvaliacao', base)
			.then(function(response) {

				create.avaliacao.BASE 		 = base;
				create.avaliacao.BASE.CCUSTO = response;
				
				formatarCampo();
				carregarModelo();

				create.exibirModalBase();				
			});

		function formatarCampo() {

			create.avaliacao.BASE.DATA_AVALIACAO_INPUT = moment(create.avaliacao.BASE.DATA_AVALIACAO).toDate();
		}

		function carregarModelo() {

			var modelo = {};

			for (var i in gScope.Ctrl.CreateModelo.listaModelo) {

				modelo = gScope.Ctrl.CreateModelo.listaModelo[i];

				if (modelo.ID == create.avaliacao.BASE.AVALIACAO_DES_MODELO_ID) {
					
					create.avaliacao.BASE.MODELO = modelo;
					break;
				}
			}
		}
	}


	/**
	 * Return the constructor function
	 */
	return Index;
};
/**
 * Factory create do objeto _23036 - Cadastro de avaliação de desempenho.
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
		this.avaliacao 			= {};
		this.avaliacao.BASE		= {};
		this.avaliacao.BASE_BKP = {};

		// Public methods
		this.init 	            		 = init;
		this.formatarCampo 				 = formatarCampo;
		this.gravarBase         		 = gravarBase;
		this.excluirBase        		 = excluirBase;
		this.limparCampo        		 = limparCampo;
		this.habilitarAlteracaoBase 	 = habilitarAlteracaoBase;
		this.cancelarAlteracaoBase  	 = cancelarAlteracaoBase;
		this.exibirModalBase    		 = exibirModalBase;
		this.fecharModalBase    		 = fecharModalBase;

		// Init methods.
		this.init();
	}

	function init() {

		obj.avaliacao.BASE.STATUS = '1';
		obj.formatarCampo();
	}

	function formatarCampo() {

		obj.avaliacao.BASE.DATA_AVALIACAO_INPUT = moment().toDate();
	}

	function gravarBase() {

		obj.avaliacao.BASE.DATA_AVALIACAO = moment(obj.avaliacao.BASE.DATA_AVALIACAO_INPUT).format('YYYY-MM-DD');
		
		$ajax
			.post('/_23036/gravarBase', obj.avaliacao.BASE)
			.then(function(response) {

				showSuccess('Gravado com sucesso.');
				gScope.Ctrl.Index.filtrar();
				obj.fecharModalBase();
			});
	}

	function excluirBase() {

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
				.post('/_23036/excluirBase', obj.avaliacao.BASE)
				.then(function(response) {

					showSuccess('Excluído com sucesso.');
					gScope.Ctrl.Index.filtrar();
					fecharModalBase();
				});
		}
	}

	function limparCampo() {

		obj.avaliacao.BASE			= {};
		obj.avaliacao.BASE.STATUS 	= '1';
		obj.avaliacao.BASE.MODELO	= {};
		obj.avaliacao.BASE.CCUSTO	= [];

		obj.formatarCampo();
	}

	function habilitarAlteracaoBase() {

		angular.copy(obj.avaliacao.BASE, obj.avaliacao.BASE_BKP);
		gScope.Ctrl.tipoTela = 'alterar';
	}

	function cancelarAlteracaoBase() {

		angular.copy(obj.avaliacao.BASE_BKP, obj.avaliacao.BASE);
		
		// Setar o modelo novamente, pois no processo de cópia ele perde o $$hashKey.
		obj.avaliacao.BASE.MODELO = selectById(gScope.Ctrl.CreateModelo.listaModelo, obj.avaliacao.BASE.MODELO.ID);

		gScope.Ctrl.tipoTela = 'exibir';
	}

	function exibirModalBase() {

		$('#modal-create').modal('show');
	}

	function fecharModalBase() {

		$('#modal-create')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast');

		obj.limparCampo();		
	}

	/**
	 * Return the constructor function
	 */
	return Create;
};
/**
 * Factory create (modelo) do objeto _23036 - Cadastro de avaliação de desempenho.
 */

angular
	.module('app')
	.factory('CreateModelo', CreateModelo);    

CreateModelo.$inject = [
	'$ajax',
	'gScope'
];

function CreateModelo($ajax, gScope) {

	// Private variables.
	var obj = null;

	/**
	 * Constructor, with class name.
	 */
	function CreateModelo() {

		obj = this;

		// Public variables
		this.create 			 	 	  = gScope.Ctrl.Create;
		this.create.avaliacao.BASE.MODELO = {};
		this.listaModelo 			 	  = [];

		// Public methods
		this.consultarModelo 	 = consultarModelo;
		this.selecionarModelo 	 = selecionarModelo;

		// Init methods.
		this.consultarModelo();
	}
	
	function consultarModelo() {

		$ajax
			.post('/_23036/consultarModelo')
			.then(function(response) {

				obj.listaModelo = response;
			});
	}

	function selecionarModelo() {

		var base 	= obj.create.avaliacao.BASE,
			modelo 	= obj.create.avaliacao.BASE.MODELO;

		base.TITULO 			= modelo.TITULO;
		base.INSTRUCAO_INICIAL	= modelo.INSTRUCAO_INICIAL;
		base.META_MEDIA_GERAL 	= modelo.META_MEDIA_GERAL;
	}

	/**
	 * Return the constructor function
	 */
	return CreateModelo;
};
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
//# sourceMappingURL=_23036.js.map
