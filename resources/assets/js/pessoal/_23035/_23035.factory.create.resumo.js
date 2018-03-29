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