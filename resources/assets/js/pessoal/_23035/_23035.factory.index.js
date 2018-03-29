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