_29010ConsultaService.$inject = ['$ajax', '$filter'];

function _29010ConsultaService($ajax, $filter) {

	// MÉTODOS (REFERÊNCIAS)
	this.consultar = consultar;
	

	// MÉTODOS

	/**
	 * Consultar workflow.
	 */
    function consultar() {

		return $ajax
				.post(
					'/_29010/consultar', 
					null, 
					{contentType: 'application/json'}
				);
	}

}
