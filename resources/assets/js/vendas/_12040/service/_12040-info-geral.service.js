
    InfoGeralService.$inject = ['$ajax', '$filter'];

    function InfoGeralService($ajax, $filter) {

    	// MÉTODOS (REFERÊNCIAS)
    	this.consultarInfoGeral = consultarInfoGeral;
        this.gerarChave         = gerarChave;
        this.getPDF             = getPDF;


    	// MÉTODOS

    	/**
    	 * Consultar informações gerais.
    	 */
	    function consultarInfoGeral(clienteId) {

			var url = '/_12040/consultarInfoGeral',
                data = {
                    CLIENTE_ID: clienteId
                }
            ;

			return $ajax.post(url, JSON.stringify(data), {contentType: 'application/json'});
		}

        /**
         * Gerar chave para liberação de nova quantidade mínima para cor.
         */
        function gerarChave() {

            var url = '/_12040/gerarChave';

            return $ajax.post(url, JSON.stringify(data), {contentType: 'application/json'});
        }

        /**
         * PDF do pedido
         */
        function getPDF(dados) {

            var url = '/_12040/getPDF';

            return $ajax.post(url, dados, {contentType: 'application/json'});

        }

	}
