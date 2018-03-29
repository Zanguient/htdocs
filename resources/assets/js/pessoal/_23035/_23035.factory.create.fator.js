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