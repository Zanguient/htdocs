	
	WorkflowIndexService.$inject = ['$ajax', '$filter'];

    function WorkflowIndexService($ajax, $filter) {

    	// MÉTODOS (REFERÊNCIAS)
    	this.consultarWorkflow = consultarWorkflow;

    	// MÉTODOS
    	
    	/**
    	 * Consultar workflow.
    	 */
	    function consultarWorkflow(param) {

			return $ajax.post('/_29010/consultarWorkflow', JSON.stringify(param), {contentType: 'application/json'});
		}
	}