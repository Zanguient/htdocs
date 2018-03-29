	
	_29011CreateService.$inject = ['$ajax', 'Upload'];

    function _29011CreateService($ajax, Upload) {

    	// MÉTODOS (REFERÊNCIAS)
        this.gravar   = gravar;
        this.excluir  = excluir;
        this.encerrar = encerrar;


        // MÉTODOS

        /**
         * Gravar.
         */
        function gravar(param) {

            $('.carregando-pagina').fadeIn(200);

            var upload = Upload
                            .upload({
                                url : '/_29011/gravar', 
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
                        '/_29011/excluir', 
                        JSON.stringify(dados), 
                        {contentType: 'application/json'}
                    );
        }

        /**
         * Encerrar.
         */
        function encerrar(dados) {

            return $ajax
                    .post(
                        '/_29011/encerrar', 
                        JSON.stringify(dados), 
                        {contentType: 'application/json'}
                    );
        }
	}