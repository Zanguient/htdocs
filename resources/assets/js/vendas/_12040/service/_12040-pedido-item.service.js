
    PedidoItemService.$inject = ['$ajax', '$filter'];

    function PedidoItemService($ajax, $filter) {

    	this.consultarTamanhoComPreco 		= consultarTamanhoComPreco;
    	this.consultarQtdEPrazoPorTamanho 	= consultarQtdEPrazoPorTamanho;

	    function consultarTamanhoComPreco(filtro) {

			var url = '/_12040/consultarTamanhoComPreco';

			return $ajax.post(url, JSON.stringify(filtro), {contentType: 'application/json'});

		}

		function consultarQtdEPrazoPorTamanho(filtro) {

			var url = '/_12040/consultarQtdEPrazoPorTamanho';

			return $ajax.post(url, JSON.stringify(filtro), {contentType: 'application/json'});

		}

	}
