/**
 * Script para reset com as seguintes funções:
 * */

(function($) {
    
    function ajaxSetup() {
        var token = $('meta[name="csrf_token"]').attr('content');
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': token}
        });

        $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
            }
        });
    }
	
	/**
	 * Campos uppercase.
	 * 
	 * @returns {undefined}
	 */
	function camposUpper() {
		
		$('#usuario').change(function() {
			this.value = this.value.toUpperCase();
		});
	}
	
	
	/**
	 * Gravar senha.
	 * 
	 * @returns {undefined}
	 */
	function gravarSenha() {
                
        $('form.js-gravar').submit(function(e){

			var status_res = 0;
			var status_msg = '';
			
            e.preventDefault();
			
            $('button.js-gravar').button('loading');
            $('.alert .texto').empty().parent().hide();

            var $form = $(this),
                url_action = $form.attr('action'),
                url_redir  = $form.attr('url-redirect'),
                dados      = $form.serialize();

            $.ajax({
				type		: 'POST',
				url			: url_action,
				data		: dados,
				success		: function(resposta) {
					
					//sucesso
					if (resposta['0'] === 'sucesso') {
						status_res = 0;
					}
					//erro
					else {
						status_res = 1;
					}

					status_msg = resposta['1'];
					
				},
				error		: function() {
					status_res = 1;
					status_msg = 'Ocorreu um erro. Contacte o administrador do sistema.';
					console.log(status_msg);
				}

            });
			
			//resposta que só deve ser mostrada ao usuário após o envio dos dados.
			$(document).ajaxComplete(function() {
				
				//sucesso
				if (status_res === 0) {

					if ( status_msg ) {
						url_redir = url_redir +'/'+ status_msg;
					}

					window.location = url_redir;
					
				}
				//erro
				else if (status_res === 1) {

					$('.alert').removeClass('alert-success').addClass('alert-danger');

					var excecao;
					if ( status_msg ) {
						excecao = status_msg.match(/exception 1 ...(.*) At trigger (.*)/i);
					}

					//se for exceção de trigger
					if ( excecao ) {
						$('.alert .texto').html(excecao['1']).parent().fadeIn();
					}
					else {
						$('.alert .texto').html(status_msg).parent().fadeIn();
					}
				}

				$('button.js-gravar').button('reset');
				
			});	
			
        });
		
	}
	
	
	/**
	 * Fechar mensagem de alerta.
	 * @returns {undefined}
	 */
	function fecharAlerta() {
		
		$('.alert .close').click(function() {
			$(this).parent('.alert').fadeOut('low');
			setTimeout(function () {
				$(this).parent('.alert').children('.texto').empty();
			}, 500);
		});
		
	}
	
	camposUpper();	
	gravarSenha();
	fecharAlerta();

    $(function() {
		
		ajaxSetup();
		
	});
	
}) (jQuery);