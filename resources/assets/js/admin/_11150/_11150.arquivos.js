angular
    .module('app')
    .factory('Arquivos', Arquivos);

Arquivos.$inject = [
        '$ajax',
        '$window',
        '$timeout',
        '$httpParamSerializer',
        '$rootScope',
        '$compile',
        'Upload'
    ];



function Arquivos($ajax, $window, $timeout,$httpParamSerializer,$rootScope, $compile, Upload) {

	/**
     * Constructor, with class name
     */
    function Arquivos(data) {
        if (data) {
            this.setData(data);
        }
    }

    var arquivoPadrao = {
    		ID      : 0,
			NOME 	: null,
			TABELA 	: null,
			TIPO 	: null,
			TAMANHO	: null,
			BINARIO	: null,
			CONTEUDO: null,
			CSS     : null
	};

    /**
     * Public method, assigned to prototype
     */
    Arquivos.prototype = {
    	data: [],
    	data_excluir: [],
    	comentario: '',
    	vGravar: false,
    	editando: false,
    	de: '',
    	para: '',
    	cc:'',
    	cco: '',
    	assunto: '',
    	setData: function(data) {
            angular.extend(this, data);
        },
    	addArquivo: function() {

    		if(typeof this.data != 'array' && typeof this.data != 'object'){
    			this.data = [];
    		}

    		var validar = true;
    		angular.forEach(this.data, function(iten, key) {

    			if(iten.NOME == null){
    				validar = false;
    			}
    		});

    		if(validar){
				var arquivoNovo = {};
				angular.copy(arquivoPadrao, arquivoNovo);
				this.data.push(arquivoNovo);
			}

			setTimeout(function(){
				var imputs = $('.arquivo-binario');
				if (imputs.length > 0){
					$(imputs[0]).trigger('click');
				}
			},200);

		},
		gravar:function(painel_id, caso_id, user, feed, tipo){
			that = this;
			var dados = {};

			dados.FEED_ID = 0;
			dados.FILES   = 1;

			dados.TIPO	= tipo;

			if(that.editando == true){
				dados.FEED_ID = feed.ID;
			}

			dados.SUBFEED = 0;
			if(tipo == 99){
				dados.SUBFEED = feed.ID;
				dados.TIPO	  = feed.TIPO;
			}

			if(feed.SUBFEED > 0){
				dados.SUBFEED = feed.SUBFEED;
			}

			dados.PAINEL_ID 	  = painel_id;
			dados.CASO_ID 		  = caso_id;
			dados.DE 			  = that.de; 
			dados.PARA			  = that.para; 
			dados.EM_COPIA 		  = that.cc; 
			dados.EM_COPIA_OCULTA = that.cco; 
			dados.MENSAGEM	      = that.comentario; 
			dados.ASSUNTO 		  = that.assunto;
			dados.COMENTARIO      = that.coment;
			dados.USUARIO_ID	  = user.CODIGO;

			dados.ARQUIVOS   	  = that.data;
			dados.EXCLUIR         = that.data_excluir;

			console.log(dados);

			var msg = CKEDITOR.instances.editor1.getData();

			dados.MENSAGEM	      = msg;

			if((msg+' ').length > 10){
				$('.carregando-pagina').fadeIn(200);


	            var upload = Upload
	                            .upload({
	                                url : '/_11150/gravarFeed', 
	                                data: dados
	                            });

	            upload
	                .finally(
	                    function(e) {

							$('#modal-file').modal('hide');  

							that.data = [];
							that.data_excluir = [];
							that.comentario = '';
							that.editando = false;
							that.vGravar = false;
	                    
	                        $('.carregando-pagina').fadeOut(200);

	                        setTimeout(function() {
	                            $('.carregando-pagina .progress .progress-bar')
	                                .attr({'aria-valuenow': 0,'aria-valuemax': 0})
	                                .css('width', 0);

	                            if(that.coment == 0){
	                            	$('.atualizar-files').trigger('click');
	                            }else{
	                            	$('#tab-files').trigger('click');
	                            }

	                        }, 300);
	                    }
	                );

	            return upload;
	        }else{
	        	showErro('A mensagem deve ter no minimo 10 caracteres!');
	        }
		},
		canselar:function(){
			that = this;

			$('#modal-file').modal('hide'); 
			that.data = [];
    		that.data_excluir = [];
    		that.comentario = '';
    		that.vGravar = true;
    		that.editando = false;
		},
		processarArquivo: function(event, arquivo) {

			that = this;
			var arquivoAdicionado = false;

			angular.forEach(event.target.files, function(file, key) {

				var size = (file.size / 1048576);

				if(size <= 2){

					var validar = true;
		    		angular.forEach(that.data, function(iten, key) {
		    			if(iten.NOME == null){
		    				validar = false;
		    			}
		    		});

		    		if(validar){
						var arquivoNovo = {};
						angular.copy(arquivoPadrao, arquivoNovo);
						that.data.push(arquivoNovo);

						arquivo = that.data[that.data.length - 1];
					}

					that.vGravar = true;

					arquivo.NOME 	 = file.name;
					arquivo.TABELA 	 = 'TBCASO_REGISTRO';
					arquivo.TIPO 	 = file.type;
					arquivo.TAMANHO	 = file.size;

					arquivo.BINARIO = file;

					arquivo.CSS = 'unknown';

					if(arquivo.TIPO.indexOf('pdf'				) >= 0 ){arquivo.CSS = 'pdf'; }
					if(arquivo.TIPO.indexOf('octet-stream'		) >= 0 ){arquivo.CSS = 'exe'; }
					if(arquivo.TIPO.indexOf('zip'   			) >= 0 ){arquivo.CSS = 'zip'; }
					if(arquivo.TIPO.indexOf('msword'   			) >= 0 ){arquivo.CSS = 'doc'; }
					if(arquivo.TIPO.indexOf('vnd.ms-excel'   	) >= 0 ){arquivo.CSS = 'xls'; }
					if(arquivo.TIPO.indexOf('vnd.ms-powerpoint' ) >= 0 ){arquivo.CSS = 'ppt'; }
					if(arquivo.TIPO.indexOf('gif'   			) >= 0 ){arquivo.CSS = 'gif'; }
					if(arquivo.TIPO.indexOf('png'   			) >= 0 ){arquivo.CSS = 'png'; }
					if(arquivo.TIPO.indexOf('jpg'   			) >= 0 ){arquivo.CSS = 'jpg'; }
					if(arquivo.TIPO.indexOf('jpeg'   			) >= 0 ){arquivo.CSS = 'jpeg';}
					if(arquivo.TIPO.indexOf('mpeg'   			) >= 0 ){arquivo.CSS = 'mpeg';}
					if(arquivo.TIPO.indexOf('text/plain'   		) >= 0 ){arquivo.CSS = 'txt'; }
					if(arquivo.TIPO.indexOf('sheet'   			) >= 0 ){arquivo.CSS = 'xls'; }
					if(arquivo.TIPO.indexOf('wordprocessingml'  ) >= 0 ){arquivo.CSS = 'doc'; }
					if(arquivo.TIPO.indexOf('presentation'   	) >= 0 ){arquivo.CSS = 'ppt'; }

					arquivoAdicionado = true;
				}else{
					showErro('Não é possível adicionar anexos maiores de 2MB e "'+file.name+'" tem '+size.toLocaleString('pt-BR')+'MB, diminua a resolução ou comprima o arquivo e tente novamente.');
				}
			});

			if(arquivoAdicionado == true){
				setTimeout(function() {
					$('.arquivo-container .scroll .form-group:last-of-type input.arquivo-binario').focus();
				}, 100);
			}else{
				that.data.splice(that.data.length - 1, 1);
			}

		},
		excluirArquivo: function(arquivo) {

			// Só adiciona para excluir do banco de dados se o arquivo tiver ID, ou seja, já está gravado no banco.
			if (arquivo.ID > 0) {
				this.data_excluir = (typeof this.data_excluir != 'undefined') ? this.data_excluir : [];
				this.data_excluir.push(arquivo);
			}

			this.data.splice(this.data.indexOf(arquivo), 1);
			// Adiciona um arquivo vazio se não tiver mais nenhum outro.
			if (this.data.length == 0){
				this.vGravar = false;
			}
		}
	}

	/**
     * Return the constructor function
     */
    return Arquivos;

}   
    