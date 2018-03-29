ConsultarCorService.$inject = ['$ajax', '$filter'];

function ConsultarCorService($ajax, $filter) {

	// MÉTODOS (REFERÊNCIAS)
	this.consultarCor = consultarCor;
	

	// MÉTODOS

	/**
	 * Consultar cor.
	 */
    function consultarCor() {

		return $ajax.post('/_27030/consultarCor', null, {contentType: 'application/json'});

	}

}
