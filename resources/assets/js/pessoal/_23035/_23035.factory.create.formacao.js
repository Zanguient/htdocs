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