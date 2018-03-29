ClientePorRepresentanteService.$inject = ['$ajax', '$filter'];

function ClientePorRepresentanteService($ajax, $filter) {

	// MÉTODOS (REFERÊNCIAS)
	this.consultarClientePorRepresentante = consultarClientePorRepresentante;
	

	// MÉTODOS

	/**
	 * Consultar cliente por representante.
	 */
    function consultarClientePorRepresentante(representanteId) {

		var url = '/_12070/consultarClientePorRepresentante',
			data = {
				representanteId: representanteId
			}
		;

		return $ajax.post(url, JSON.stringify(data), {contentType: 'application/json'});

	}

}
