/**
 * Script para os eventos dos botões de ações principais.
 */

/**
 * Ação do botão gravar.
 */
function btnGravar() {
	
	$('form.js-gravar').submit(function(e){

//		var status_res = 0;
//		var status_msg = '';

		e.preventDefault();
		e.stopPropagation();

		$('button.js-gravar')
			.button('loading');
	
		//ajax
		var $form = $(this),
			url_action	= $form.attr('action'),
			url_redir	= $form.attr('url-redirect'),
			dados		= $form.serialize(),
			type		= 'POST',
			success		= function() {
				window.location = url_redir;
			},
			complete	= function() {
				$('button.js-gravar').button('reset');
			}
		;
		
		execAjax1(type, url_action, dados, success, null, complete);
/*
		var $form = $(this),
			url_action = $form.attr('action'),
			url_redir  = $form.attr('url-redirect'),
			dados      = $form.serialize();

		$.ajax({
			type		: 'POST',
			url			: url_action,
			data		: dados,
			success		: function(resposta) {

				status_res = 0;

			},
			error: function (xhr) {

				status_msg = xhr;
				status_res = 1;

			},
			complete: function() {

				//sucesso
				if (status_res === 0) {

					window.location = url_redir;

				}
				//erro
				else if (status_res === 1) {
					
					showErro(status_msg);

				}

				$('button.js-gravar')
					.button('reset');

			}	
		});
		*/
	});
}

(function($) {
	$(function() {
		
		btnGravar();
		
	});
})(jQuery);