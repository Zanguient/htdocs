
    PedidoItemEscolhidoService.$inject = ['$ajax'];

    function PedidoItemEscolhidoService($ajax) {

    	// MÉTODOS (REFERÊNCIAS)
    	this.consultarQtdLiberada = consultarQtdLiberada;

    	// MÉTODOS
    	
    	/**
    	 * Consultar a quantidade mínima liberada para uma cor.
    	 */
	    function consultarQtdLiberada(param) {

			return $ajax
					.post(
						'/_12040/consultarQtdLiberada', 
						JSON.stringify(param), 
						{contentType: 'application/json'}
					);
		}

	}
