
    PedidoCreateService.$inject = ['$ajax', '$filter'];

    function PedidoCreateService($ajax, $filter) {

    	// MÉTODOS (REFERÊNCIAS)
        this.store   = store;
    	this.excluir = excluir;


    	// MÉTODOS

    	/**
    	 * Gravar.
    	 */
	    function store(dados) {

			var url = '/_12040/store';

			return $ajax.post(url, JSON.stringify(dados), {contentType: 'application/json'});

		}

        /**
         * Excluir.
         */
        function excluir(dados) {

            var url = '/_12040/excluir';

            return $ajax.post(url, JSON.stringify(dados), {contentType: 'application/json'});

        }

	}
