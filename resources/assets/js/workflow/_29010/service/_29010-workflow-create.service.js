	
	WorkflowCreateService.$inject = ['$ajax', 'Upload'];

    function WorkflowCreateService($ajax, Upload) {

    	// MÉTODOS (REFERÊNCIAS)
        this.store   = store;
        this.excluir = excluir;


        // MÉTODOS

        /**
         * Gravar.
         */
        function store(param) {

            $('.carregando-pagina').fadeIn(200);

            var upload = Upload
                            .upload({
                                url : '/_29010/store', 
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
         * Excluir.
         */
        function excluir(dados) {

            return $ajax
                    .post(
                        '/_29010/excluir', 
                        JSON.stringify(dados), 
                        {contentType: 'application/json'}
                    );
        }
	}