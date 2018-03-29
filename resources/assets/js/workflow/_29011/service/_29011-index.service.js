	
	_29011IndexService.$inject = ['$ajax', '$filter'];

    function _29011IndexService($ajax, $filter) {

    	// MÉTODOS (REFERÊNCIAS)
    	this.consultarItem = consultarItem;

    	// MÉTODOS
    	
    	/**
    	 * Consultar item de workflow.
    	 */
	    function consultarItem(param) {

			return $ajax
                    .post(
                        '/_29011/consultarItem', 
                        JSON.stringify(param), 
                        {contentType: 'application/json'}
                    );
		}
	}