
	_29012TarefaService.$inject = ['$ajax', 'Upload'];

    function _29012TarefaService($ajax, Upload) {

    	// MÉTODOS (REFERÊNCIAS)
    	this.consultarWorkflowItemTarefa             = consultarWorkflowItemTarefa;
        this.excluirArquivoTmpPorUsuario             = excluirArquivoTmpPorUsuario;
        this.alterarSituacao                         = alterarSituacao;
        this.gravarWorkflowItemTarefaComentario      = gravarWorkflowItemTarefaComentario;
        this.gravarWorkflowItemArquivoDoDestinatario = gravarWorkflowItemArquivoDoDestinatario;
        this.gravarWorkflowItemTarefaCampo           = gravarWorkflowItemTarefaCampo;

    	// MÉTODOS

        /**
         * Consultar tarefa.
         */
        function consultarWorkflowItemTarefa(param) {

            return $ajax.post('/_29012/consultarWorkflowItemTarefa', JSON.stringify(param), {contentType: 'application/json'});
        }

        /**
         * Excluir arquivos da pasta temporária (por usuário).
         */
        function excluirArquivoTmpPorUsuario(param) {

            return $ajax.post('/excluirArquivoTmpPorUsuario', JSON.stringify(param), {contentType: 'application/json'});
        }

        /**
         * Alterar situação da tarefa.
         */
        function alterarSituacao(param) {

            return $ajax.post('/_29012/alterarSituacaoWorkflowItemTarefa', JSON.stringify(param), {contentType: 'application/json'});
        }

        /**
         * Gravar comentário para a tarefa.
         */
        function gravarWorkflowItemTarefaComentario(param) {

            return $ajax.post('/_29012/gravarWorkflowItemTarefaComentario', JSON.stringify(param), {contentType: 'application/json'});
        }

        /**
         * Gravar arquivo.
         */
        function gravarWorkflowItemArquivoDoDestinatario(param) {

            $('.carregando-pagina').fadeIn(200);

            var upload = Upload
                            .upload({
                                url : '/_29012/gravarWorkflowItemArquivoDoDestinatario', 
                                data: param
                            });

            upload
                .finally(
                    function(e) {
                    
                        $('.carregando-pagina').fadeOut(200);

                        setTimeout(function() {
                            $('.carregando-pagina .progress .progress-bar')
                                .attr({'aria-valuenow': 0,'aria-valuemax': 0})
                                .css('width', 0);
                        }, 300);
                    }
                );

            return upload;
        }

        /**
         * Gravar valores dos campos dinâmicos.
         */
        function gravarWorkflowItemTarefaCampo(param) {

            return $ajax.post('/_29012/gravarWorkflowItemTarefaCampo', JSON.stringify(param), {contentType: 'application/json'});
        }
	}