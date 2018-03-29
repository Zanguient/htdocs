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