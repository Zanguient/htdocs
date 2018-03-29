/**
 * App do objeto _23038 - Registro de indicadores por centro de custo.
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
 * Controller do objeto _23038 - Registro de indicadores por centro de custo
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
	'CreateCCusto',
	'CreateIndicador'
];

function Ctrl( 
	$scope,
	gScope,
	Historico,
	Index,
	Create,
	CreateCCusto,
	CreateIndicador
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
	$ctrl.Index  			= new Index();
	$ctrl.Create 			= new Create();
	$ctrl.CreateCCusto 		= new CreateCCusto();
	$ctrl.CreateIndicador 	= new CreateIndicador();
}
/**
 * Factory index do objeto _23038 - Registro de indicadores por centro de custo.
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
        this.filtro                  = {};
        this.listaIndicadorPorCCusto = [];

        // Public methods
        this.init             = init;
        this.filtrar          = filtrar;
        this.habilitarIncluir = habilitarIncluir;
        this.exibir           = exibir;

        // Init methods.
        this.init();
    }
    
    function init() {

        obj.filtro.DATA_INI_INPUT = moment().subtract('1', 'years').toDate();
        obj.filtro.DATA_FIM_INPUT = moment().toDate();
    }

    function filtrar() {

        obj.filtro.DATA_INI = obj.filtro.DATA_INI_INPUT ? moment(obj.filtro.DATA_INI_INPUT).format('YYYY-MM-DD') : null;
        obj.filtro.DATA_FIM = obj.filtro.DATA_FIM_INPUT ? moment(obj.filtro.DATA_FIM_INPUT).format('YYYY-MM-DD') : null;

        $ajax
            .post('/_23038/consultarIndicadorPorCCusto', obj.filtro)
            .then(function(response){

                obj.listaIndicadorPorCCusto = response;
                formatarCampo();
            });

        function formatarCampo() {

            var ind = {};

            for (var i in obj.listaIndicadorPorCCusto) {

                ind = obj.listaIndicadorPorCCusto[i];

                ind.DATA_INI_HUMANIZE = moment(ind.DATA_INI).format('DD/MM/YYYY');
                ind.DATA_FIM_HUMANIZE = moment(ind.DATA_FIM).format('DD/MM/YYYY');
            }
        }
    }

    function habilitarIncluir() {

        gScope.Ctrl.tipoTela = 'incluir';

        setTimeout(function() { 
            $('.js-input-focus').focus(); 
        }, 500);
    }

    function exibir(indicadorPorCCusto) {

        gScope.Ctrl.tipoTela = 'exibir';
        gScope.Ctrl.Create.exibir(indicadorPorCCusto);
    }

    /**
     * Return the constructor function
     */
    return Index;
};
/**
 * Factory create do objeto _23038 - Registro de indicadores por centro de custo.
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

		// Public variables.
		this.indicadorPorCCusto    = {};
		this.indicadorPorCCustoBkp = {};

		// Public methods.
		this.gravar             = gravar;
		this.excluir            = excluir;
		this.limparCampo        = limparCampo;
		this.exibir             = exibir;
		this.habilitarAlteracao = habilitarAlteracao;
		this.cancelarAlteracao  = cancelarAlteracao;
		this.exibirModal        = exibirModal;
		this.fecharModal        = fecharModal;

		// Init methods.
	}
	

	function gravar() {

		obj.indicadorPorCCusto.DATA_INI = moment(obj.indicadorPorCCusto.DATA_INI_INPUT).format('YYYY-MM-DD');
		obj.indicadorPorCCusto.DATA_FIM = moment(obj.indicadorPorCCusto.DATA_FIM_INPUT).format('YYYY-MM-DD');

		$ajax
			.post('/_23038/gravar', obj.indicadorPorCCusto)
			.then(function(response) {

				showSuccess('Gravado com sucesso.');
				gScope.Ctrl.Index.filtrar();
				fecharModal();
			});
	}

	function excluir() {

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
				.post('/_23038/excluir', obj.indicadorPorCCusto)
				.then(function(response){

					showSuccess('Excluído com sucesso.');
					gScope.Ctrl.Index.filtrar();
					fecharModal();
				});
		}
	}

	function limparCampo() {

		obj.indicadorPorCCusto = {};
	}

	function exibir(indicadorPorCCusto) {

		indicadorPorCCusto.DATA_INI_INPUT 	= moment(indicadorPorCCusto.DATA_INI).toDate();
		indicadorPorCCusto.DATA_FIM_INPUT 	= moment(indicadorPorCCusto.DATA_FIM).toDate();
		indicadorPorCCusto.CCUSTO 			= {};
		indicadorPorCCusto.CCUSTO.CODIGO    = indicadorPorCCusto.CCUSTO_CODIGO;
		indicadorPorCCusto.CCUSTO.MASK      = indicadorPorCCusto.CCUSTO_MASK;
		indicadorPorCCusto.CCUSTO.DESCRICAO = indicadorPorCCusto.CCUSTO_DESCRICAO;
		indicadorPorCCusto.INDICADOR 		= {};
		indicadorPorCCusto.INDICADOR.ID		= indicadorPorCCusto.INDICADOR_ID;
		indicadorPorCCusto.INDICADOR.TITULO	= indicadorPorCCusto.INDICADOR_TITULO;

		obj.indicadorPorCCusto    = indicadorPorCCusto;
		obj.indicadorPorCCustoBkp = angular.copy(indicadorPorCCusto);

		obj.exibirModal();
	}

	function habilitarAlteracao() {

		gScope.Ctrl.tipoTela = 'alterar';
		
		setTimeout(function() { 
			$('.js-input-focus').focus(); 
		}, 100);
	}

	function cancelarAlteracao() {

		angular.extend(obj.indicadorPorCCusto, obj.indicadorPorCCustoBkp);
		gScope.Ctrl.tipoTela = 'exibir';
	}

	function exibirModal() {

		$('#modal-create').modal('show');
	}

	function fecharModal() {

		$('#modal-create')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast');

		obj.limparCampo();        
	}


	/**
	 * Return the constructor function.
	 */
	return Create;
};
/**
 * Factory create (centro de custo) do objeto _23038 - Registro de indicadores por centro de custo.
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
		this.create 			 	 			= gScope.Ctrl.Create;
		this.create.indicadorPorCCusto.CCUSTO 	= {};
		this.listaCCusto		 	 			= [];

		// Public methods
		this.consultarCCusto 		= consultarCCusto;
		this.selecionarCCusto 		= selecionarCCusto;
		this.fixVsRepeatPesqCCusto 	= fixVsRepeatPesqCCusto;
		this.exibirModal 			= exibirModal;
		this.fecharModal 			= fecharModal;

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

		obj.create.indicadorPorCCusto.CCUSTO = ccusto;
		obj.fecharModal();
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

/**
 * Factory create (indicador) do objeto _23038 - Registro de indicadores por centro de custo.
 */

angular
	.module('app')
	.factory('CreateIndicador', CreateIndicador);

CreateIndicador.$inject = [
	'$ajax',
	'gScope',
	'$timeout'
];

function CreateIndicador($ajax, gScope, $timeout) {

	// Private variables.
	var obj = null;

	/**
	 * Constructor, with class name.
	 */
	function CreateIndicador() {

		obj = this;

		// Public variables
		this.create 			 	    		 = gScope.Ctrl.Create;
		this.create.indicadorPorCCusto.INDICADOR = {};
		this.listaIndicador		 	    		 = [];

		// Public methods
		this.consultarIndicador		    = consultarIndicador;
		this.selecionarIndicador 		= selecionarIndicador;
		this.fixVsRepeatPesqIndicador 	= fixVsRepeatPesqIndicador;
		this.exibirModal 			    = exibirModal;
		this.fecharModal 			    = fecharModal;

		// Init methods.
	}

	function consultarIndicador() {

		$ajax
			.post('/_23038/consultarIndicador')
			.then(function(response) {

				obj.listaIndicador = response;
			});
	}

	/**
	 * Selecionar indicadores (modal).
	 */
	function selecionarIndicador(indicador) {

		obj.create.indicadorPorCCusto.INDICADOR = indicador;
		obj.fecharModal();
	}

	/**
     * Fix para vs-repeat: exibir a tabela completa.
     */
    function fixVsRepeatPesqIndicador() {

        $timeout(function() {
            $('#modal-pesq-indicador .table-indicador').scrollTop(0);
        }, 200);

    }

	function exibirModal() {

		if (obj.listaIndicador.length == 0)
			obj.consultarIndicador();

		$('#modal-pesq-indicador').modal('show');

		setTimeout(function() {
			$('.js-input-filtrar-indicador').focus();
		}, 500);

		obj.fixVsRepeatPesqIndicador();
	}

	function fecharModal() {

		$('#modal-pesq-indicador')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast');

		obj.filtrarIndicador = "";
	}

	/**
	 * Return the constructor function
	 */
	return CreateIndicador;
};

//# sourceMappingURL=_23038.js.map
