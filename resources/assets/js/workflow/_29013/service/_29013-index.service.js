	
	_29013IndexService.$inject = ['$ajax'];

    function _29013IndexService($ajax) {

    	// MÉTODOS (REFERÊNCIAS)
    	this.consultarWorkflowItem         = consultarWorkflowItem;
        this.consultarWorkflowItemTarefa   = consultarWorkflowItemTarefa;

    	// MÉTODOS
    	
    	/**
    	 * Consultar item de workflow.
    	 */
	    function consultarWorkflowItem(param) {

			return $ajax
                    .post(
                        '/_29013/consultarWorkflowItem', 
                        JSON.stringify(param), 
                        {contentType: 'application/json'}
                    );
		}

        /**
         * Consultar tarefa.
         */
        function consultarWorkflowItemTarefa(param) {

            return $ajax
                    .post(
                        '/_29013/consultarWorkflowItemTarefa', 
                        JSON.stringify(param), 
                        {contentType: 'application/json'}
                    );
        }
	}