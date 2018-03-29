ProdutoPorModeloECorService.$inject = ['$ajax'];

function ProdutoPorModeloECorService($ajax) {

	// MÉTODOS (REFERÊNCIAS)
	this.consultarProdutoPorModeloECor = consultarProdutoPorModeloECor;
	

	// MÉTODOS

	/**
	 * Consultar produto por modelo e cor.
	 */
    function consultarProdutoPorModeloECor(modeloId, corId) {

		var url = '/_27050/consultarPorModeloECor',
			data = {
				modeloId: modeloId,
				corId 	: corId
			}
		;

		return $ajax.post(url, JSON.stringify(data), {contentType: 'application/json'});

	}

}
