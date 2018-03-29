
    LiberacaoService.$inject = ['$ajax', '$filter'];

    function LiberacaoService($ajax, $filter) {

    	// MÉTODOS (REFERÊNCIAS)
    	this.gravarLiberacao = gravarLiberacao;


    	// MÉTODOS

        /**
         * Gravar liberação de nova quantidade mínima para cor.
         */
        function gravarLiberacao(param) {

            return $ajax.post('/_12040/gravarLiberacao', JSON.stringify(param), {contentType: 'application/json'});
        }

	}
