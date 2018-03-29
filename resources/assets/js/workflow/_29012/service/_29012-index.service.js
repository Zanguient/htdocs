	
	_29012IndexService.$inject = ['$ajax'];

    function _29012IndexService($ajax) {

    	// MÉTODOS (REFERÊNCIAS)
    	this.consultarWorkflowItem = consultarWorkflowItem;

    	// MÉTODOS
    	
    	/**
    	 * Consultar item de workflow.
    	 */
	    function consultarWorkflowItem(param) {

			return $ajax
                    .post(
                        '/_29012/consultarWorkflowItem', 
                        JSON.stringify(param), 
                        {contentType: 'application/json'}
                    );
		}
	}