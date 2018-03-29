	
	_29011InfoGeralService.$inject = ['$ajax', '$filter'];

    function _29011InfoGeralService($ajax, $filter) {

    	// MÉTODOS (REFERÊNCIAS)
    	this.consultarWorkflowTarefa = consultarWorkflowTarefa;

    	// MÉTODOS
    	
    	/**
    	 * Consultar tarefas do workflow.
    	 */
	    function consultarWorkflowTarefa(param) {

            return $ajax 
                    .post(
                        '/_29011/consultarWorkflowTarefa',
                        JSON.stringify(param),
                        {contentType: 'application/json'}
                    );
        }
	}