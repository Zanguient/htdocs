/**
 * App do objeto _23035 - Cadastro de modelo de avaliação de desempenho.
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
 * Controller do objeto _23035 - Cadastro de modelo de avaliação de desempenho.
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

	// Objects.
	$ctrl.Index 			= new Index();
	$ctrl.Create 			= new Create();
	$ctrl.CreateFator 		= new CreateFator();
	$ctrl.CreateFormacao	= new CreateFormacao();
	$ctrl.CreateResumo		= new CreateResumo();
}
/**
 * Factory index do objeto _23035 - Cadastro de modelo de avaliação de desempenho.
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
		this.listaModelo        = [];
		this.listaModeloFator   = [];

		// Public methods
		this.filtrar                = filtrar;
		this.consultarModeloFator   = consultarModeloFator;
		this.habilitarIncluir       = habilitarIncluir;
		this.exibir                 = exibir;

		// Init methods.
		this.filtrar();
	}
	

	function filtrar() {

		$ajax
			.post('/_23035/consultarModelo')
			.then(function(response) {

				obj.listaModelo = response;
			});
	}

	function consultarModeloFator() {

		$ajax
			.post('/_23035/consultarModeloFator')
			.then(function(response) {

				obj.listaModeloFator = response;
			});
	}

	function habilitarIncluir() {

		gScope.Ctrl.tipoTela = 'incluir';

		setTimeout(function() { 
			$('.js-input-descricao').focus(); 
		}, 500);
	}

	function exibir(modelo) {

		var create = gScope.Ctrl.Create;
		gScope.Ctrl.tipoTela = 'exibir';

		$ajax
			.post('/_23035/consultarModeloItem', modelo)
			.then(function(response) {

				create.modelo           = modelo;
				create.modelo.FATOR     = response.FATOR;
				create.modelo.FORMACAO  = response.FORMACAO;
				create.modelo.RESUMO    = response.RESUMO;
				
				angular.copy(create.modelo, create.modeloBkp);

				gScope.Ctrl.CreateResumo.somarPeso();

				create.exibirModal();
			});
	}


	/**
	 * Return the constructor function
	 */
	return Index;
};
/**
 * Factory create do objeto _23035 - Cadastro de modelo de avaliação de desempenho.
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
		this.modelo         = {};
		this.modeloBkp      = {};

		// Public methods.
		this.init 				= init;
		this.gravar             = gravar;
		this.excluir            = excluir;
		this.limparCampo        = limparCampo;
		this.habilitarAlteracao = habilitarAlteracao;
		this.cancelarAlteracao  = cancelarAlteracao;
		this.exibirModal        = exibirModal;
		this.fecharModal        = fecharModal;

		// Init methods.
		this.init();
	}

	function init() {

		obj.modelo.META_MEDIA_GERAL = 80;
	}

	function gravar() {

		$ajax
			.post('/_23035/gravar', obj.modelo)
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
				.post('/_23035/excluir', obj.modelo)
				.then(function(response){

					showSuccess('Excluído com sucesso.');
					gScope.Ctrl.Index.filtrar();
					fecharModal();
				});
		}
	}

	function limparCampo() {

		obj.modelo 			= {};
		obj.modelo.FATOR 	= [];
		obj.modelo.FORMACAO = [];
		obj.modelo.RESUMO 	= [];

		obj.modelo.META_MEDIA_GERAL = 80;
		gScope.Ctrl.CreateFator.addFator();
		gScope.Ctrl.CreateFormacao.addAllFormacao();
		gScope.Ctrl.CreateResumo.addResumo();
		gScope.Ctrl.CreateResumo.pesoFinal = 0;
	}

	function habilitarAlteracao() {

		gScope.Ctrl.tipoTela = 'alterar';
	}

	function cancelarAlteracao() {

		angular.copy(obj.modeloBkp, obj.modelo);
		gScope.Ctrl.CreateResumo.somarPeso();
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
 * Factory create (fator) do objeto _23035 - Cadastro de modelo de avaliação de desempenho.
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

		// Public variables.
		this.create 			 = gScope.Ctrl.Create;
		this.create.modelo.FATOR = [];
		this.fatorPadrao		 = {
			TIPO_ID		: 0,
			TIPO_TITULO	: '',
			TITULO		: '',
			DESCRICAO 	: ''
		};
		this.listaFator 		 = [];

		// Public methods.
		this.init 				= init;
		this.consultarFator 	= consultarFator;
		this.selecionarFator 	= selecionarFator;
		this.addFator 			= addFator;
		this.excluirFator 		= excluirFator;

		// Init methods.
		this.init();
	}
	
	function init() {

		consultarFator();
		addFator();
	}

	function consultarFator() {

        $ajax
            .post('/_23032/consultarFator')
            .then(function(response) {

                obj.listaFator = response;
            });
    }

    function selecionarFator(fator) {

    	var ftr = {};

    	for (var i in obj.listaFator) {

    		ftr = obj.listaFator[i];

    		if (ftr.ID == fator.AVALIACAO_DES_FATOR_ID) {
	    	
	    		fator.TIPO_ID 		= ftr.TIPO_ID;
				fator.TIPO_TITULO 	= ftr.TIPO_TITULO;
				fator.TITULO 		= ftr.TITULO;
				fator.DESCRICAO 	= ftr.DESCRICAO;

				break;
			}
    	}
    }

	function addFator() {

		var fatorNovo = {};
        angular.copy(obj.fatorPadrao, fatorNovo);
        obj.create.modelo.FATOR.push(fatorNovo);
	}

	function excluirFator(fator) {

		if (fator.ID > 0)
            fator.STATUSEXCLUSAO = '1';
        else
            obj.create.modelo.FATOR.splice(obj.create.modelo.FATOR.indexOf(fator), 1);

        // Adicionar fator quando não houver nenhum.
        if (obj.create.modelo.FATOR.length == 0)
            addFator();
        else {

            var ftr  = {},
                resta = false;

            // Verificar se tem algum fator que não tenha sido marcado para excluir.
            for (var i in obj.create.modelo.FATOR) {
                
                ftr = obj.create.modelo.FATOR[i];

                if (ftr.STATUSEXCLUSAO != '1') {

                    resta = true;
                    break;
                }
            }

            if (resta == false)
                addFator();
        }
	}


	/**
	 * Return the constructor function.
	 */
	return CreateFator;
};
/**
 * Factory create (formação) do objeto _23035 - Cadastro de modelo de avaliação de desempenho.
 */

angular
	.module('app')
	.factory('CreateFormacao', CreateFormacao);    

CreateFormacao.$inject = [
	'$ajax',
	'gScope',
	'$q'
];

function CreateFormacao($ajax, gScope, $q) {

	// Private variables.
	var obj = null;

	/**
	 * Constructor, with class name.
	 */
	function CreateFormacao() {

		obj = this;

		// Public variables.
		this.create 			 	= gScope.Ctrl.Create;
		this.create.modelo.FORMACAO = [];
		this.formacaoPadrao		 	= {
			DESCRICAO 	: '',
			PONTO 		: 0
		};
		this.listaFormacao 		 = [];

		// Public methods.
		this.init 				= init;
		this.consultarFormacao 	= consultarFormacao;
		this.selecionarFormacao = selecionarFormacao;
		this.addAllFormacao 	= addAllFormacao;
		this.addFormacao 		= addFormacao;
		this.excluirFormacao 	= excluirFormacao;

		// Init methods.
		this.init();
	}
	
	function init() {

		consultarFormacao().then(function() {
			addAllFormacao();
		});
	}

	function consultarFormacao() {

		return $q(function(resolve, error) {
			
			$ajax
				.post('/_23033/consultarFormacao')
				.then(function(response) {

					obj.listaFormacao = response;
					resolve(true);
				
				}, function(error) {
					reject(error);
				});
		});
	}

	function selecionarFormacao(formacao) {

		var frm = {};

		for (var i in obj.listaFormacao) {

			frm = obj.listaFormacao[i];

			if (frm.ID == formacao.AVALIACAO_DES_FORMACAO_ID) {
			
				formacao.DESCRICAO 	= frm.DESCRICAO;
				formacao.PONTO 		= frm.PONTO;

				break;
			}
		}
	}

	function addAllFormacao() {

		var formacao = {};

		angular.copy(obj.listaFormacao, obj.create.modelo.FORMACAO);

		for (var i in obj.create.modelo.FORMACAO) {

			formacao = obj.create.modelo.FORMACAO[i];
			formacao.AVALIACAO_DES_FORMACAO_ID = formacao.ID;
		}
		
	}

	function addFormacao() {

		var formacaoNovo = {};
		angular.copy(obj.formacaoPadrao, formacaoNovo);
		obj.create.modelo.FORMACAO.push(formacaoNovo);
	}

	function excluirFormacao(formacao) {

		if (formacao.ID > 0)
			formacao.STATUSEXCLUSAO = '1';
		else
			obj.create.modelo.FORMACAO.splice(obj.create.modelo.FORMACAO.indexOf(formacao), 1);

		// Adicionar formacao quando não houver nenhum.
		if (obj.create.modelo.FORMACAO.length == 0)
			addFormacao();
		else {

			var frm  = {},
				resta = false;

			// Verificar se tem algum formacao que não tenha sido marcado para excluir.
			for (var i in obj.create.modelo.FORMACAO) {
				
				frm = obj.create.modelo.FORMACAO[i];

				if (frm.STATUSEXCLUSAO != '1') {

					resta = true;
					break;
				}
			}

			if (resta == false)
				addFormacao();
		}
	}


	/**
	 * Return the constructor function.
	 */
	return CreateFormacao;
};
/**
 * Factory create (resumo) do objeto _23035 - Cadastro de modelo de avaliação de desempenho.
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

		// Public variables.
		this.create 			 	= gScope.Ctrl.Create;
		this.create.modelo.RESUMO 	= [];
		this.resumoPadrao		 	= {
			DESCRICAO: '',
			PONTO 	 : 0
		};
		this.listaResumo 		 = [];
		this.pesoFinal 	 		 = 0;

		// Public methods.
		this.init 					= init;
		this.consultarResumo 		= consultarResumo;
		this.selecionarResumo 		= selecionarResumo;
		this.somarPeso 				= somarPeso;
		this.addResumo 				= addResumo;
		this.excluirResumo 			= excluirResumo;

		// Init methods.
		this.init();
	}
	
	function init() {

		consultarResumo();
		addResumo();
	}

	function consultarResumo() {

        $ajax
            .post('/_23034/consultarResumo')
            .then(function(response) {

                obj.listaResumo = response.RESUMO;
            });
    }

    function selecionarResumo(resumo) {

    	var rsm = {};

    	for (var i in obj.listaResumo) {

    		rsm = obj.listaResumo[i];

    		if (rsm.ID == resumo.AVALIACAO_DES_RESUMO_ID) {
	    	
	    		resumo.DESCRICAO = rsm.DESCRICAO;
				resumo.PESO 	 = rsm.PESO;

				break;
			}
    	}

		somarPeso();
    }

    function somarPeso() {

    	var resumo = {};
    	obj.pesoFinal = 0;

    	for (var i in obj.create.modelo.RESUMO) {

    		resumo = obj.create.modelo.RESUMO[i];
    		
    		if (resumo.STATUSEXCLUSAO != '1')
    			obj.pesoFinal += resumo.PESO;
    	}
    }

	function addResumo() {

		var resumoNovo = {};
        angular.copy(obj.resumoPadrao, resumoNovo);
        obj.create.modelo.RESUMO.push(resumoNovo);
	}

	function excluirResumo(resumo) {

		if (resumo.ID > 0)
            resumo.STATUSEXCLUSAO = '1';
        else
            obj.create.modelo.RESUMO.splice(obj.create.modelo.RESUMO.indexOf(resumo), 1);

        // Adicionar resumo quando não houver nenhum.
        if (obj.create.modelo.RESUMO.length == 0)
            addResumo();
        else {

            var rsm  = {},
                resta = false;

            // Verificar se tem algum resumo que não tenha sido marcado para excluir.
            for (var i in obj.create.modelo.RESUMO) {
                
                rsm = obj.create.modelo.RESUMO[i];

                if (rsm.STATUSEXCLUSAO != '1') {

                    resta = true;
                    break;
                }
            }

            if (resta == false)
                addResumo();
        }

        somarPeso();
	}


	/**
	 * Return the constructor function.
	 */
	return CreateResumo;
};
//# sourceMappingURL=_23035.js.map
