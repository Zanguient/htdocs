(function($) {

	function gravarEmailRecuperacao() {

		function gravar() {
		
			var email = $('#email').val();

			//ajax
			var type	= "POST",
	            url		= "/password/email",
	            data	= {
	                email: email
	            },
	            success = function(data) {
	            	showSuccess('O link para recuperar sua senha foi enviado para o e-mail '+email);
	            }
	        ;

	        execAjax1(type, url, data, success);
	    }

	    function evento() {

	    	$('form')
	    		.submit(function(e) {
	    			e.preventDefault();
	    			gravar();
	    		})
	    	;

	    }

	    evento();

    }

	$(function() {

		gravarEmailRecuperacao();

	});

})(jQuery);