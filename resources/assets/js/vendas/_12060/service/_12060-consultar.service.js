RepresentanteService.$inject = ['$ajax', '$filter'];

function RepresentanteService($ajax, $filter) {

	// MÉTODOS (REFERÊNCIAS)
	this.consultarRepresentante = consultarRepresentante;
	

	// MÉTODOS

	/**
	 * Consultar representante.
	 */
    function consultarRepresentante() {

		var url = '/_12060/consultarRepresentante';

		return $ajax.post(url, null, {contentType: 'application/json'});

	}

}
