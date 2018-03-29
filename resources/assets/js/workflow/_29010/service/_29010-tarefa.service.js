
	TarefaService.$inject = ['$ajax', '$filter'];

    function TarefaService($ajax, $filter) {

    	// MÉTODOS (REFERÊNCIAS)
        this.consultarUsuario            = consultarUsuario;
    	this.consultarTarefa             = consultarTarefa;
        this.excluirArquivoTmpPorUsuario = excluirArquivoTmpPorUsuario;
        this.gravarEmailUsuario          = gravarEmailUsuario;

    	// MÉTODOS
    	
    	/**
    	 * Consultar usuário.
    	 */
	    function consultarUsuario() {

	    	return $ajax.post('/_11010/listarTodos', null, {contentType: 'application/json'});
		}

        /**
         * Consultar tarefa.
         */
        function consultarTarefa(param) {

            return $ajax.post('/_29010/consultarTarefa', JSON.stringify(param), {contentType: 'application/json'});
        }

        /**
         * Excluir arquivos da pasta temporária (por usuário).
         */
        function excluirArquivoTmpPorUsuario(param) {

            return $ajax.post('/excluirArquivoTmpPorUsuario', JSON.stringify(param), {contentType: 'application/json'});
        }

        /**
         * Gravar email do usuário.
         */
        function gravarEmailUsuario(param) {

            return $ajax.post('/_29010/gravarEmailUsuario', JSON.stringify(param), {contentType: 'application/json'});
        }
	}