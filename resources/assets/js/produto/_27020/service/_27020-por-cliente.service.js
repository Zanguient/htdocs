ModeloPorClienteService.$inject = ['$ajax', '$filter'];

function ModeloPorClienteService($ajax, $filter) {

	// MÉTODOS (REFERÊNCIAS)
	this.consultarModeloPorCliente = consultarModeloPorCliente;
	

	// MÉTODOS

	/**
	 * Consultar modelo por cliente.
	 */
    function consultarModeloPorCliente(filtro) {

		var url = '/_27020/consultarModeloPorCliente';

		return $ajax.post(url, JSON.stringify(filtro), {contentType: 'application/json'});

	}

}
