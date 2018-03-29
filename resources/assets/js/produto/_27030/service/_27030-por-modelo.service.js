CorPorModeloService.$inject = ['$ajax', '$filter'];

function CorPorModeloService($ajax, $filter) {

	// MÉTODOS (REFERÊNCIAS)
	this.consultarCorPorModelo = consultarCorPorModelo;
	

	// MÉTODOS

	/**
	 * Consultar cor por modelo.
	 */
    function consultarCorPorModelo(param) {

		var url = '/_27030/consultarCorPorModelo';

		return $ajax.post(url, JSON.stringify(param), {contentType: 'application/json'});

	}

}
