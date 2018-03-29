
    PedidoIndexService.$inject = ['$ajax', '$filter'];

    function PedidoIndexService($ajax, $filter) {

    	// MÉTODOS (REFERÊNCIAS)
        this.verificarUsuarioEhRepresentante    = verificarUsuarioEhRepresentante;
        this.consultarRepresentanteDoCliente    = consultarRepresentanteDoCliente;
        this.consultarPedido                    = consultarPedido;
        this.consultarPedido2                   = consultarPedido2;
    	this.consultarPedidoItem                = consultarPedidoItem;


    	// MÉTODOS

    	/**
         * Verificar se usuário é representante.
         */
        function verificarUsuarioEhRepresentante() {

            var url = '/_12040/verificarUsuarioEhRepresentante';

            return $ajax.post(url, null, {contentType: 'application/json'});

        }

        /**
         * Consultar representante do cliente.
         */
        function consultarRepresentanteDoCliente() {

            var url = '/_12040/consultarRepresentanteDoCliente';

            return $ajax.post(url, null, {contentType: 'application/json'});

        }

        /**
         * Consultar pedido.
         */
        function consultarPedido(filtro) {

            var url = '/_12040/consultarPedido';

            return $ajax.post(url, JSON.stringify(filtro), {contentType: 'application/json'});

        }

        /**
         * Consultar pedido.
         */
        function consultarPedido2(filtro) {

            var url = '/_12040/consultarPedido2';

            return $ajax.post(url, JSON.stringify(filtro), {contentType: 'application/json'});

        }

        /**
         * Consultar item de pedido.
         */
        function consultarPedidoItem(filtro) {

            var url = '/_12040/consultarPedidoItem';

            return $ajax.post(url, JSON.stringify(filtro), {contentType: 'application/json'});

        }

	}
