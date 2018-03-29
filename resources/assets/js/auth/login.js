/**
 * Script para login.
 * */

jQuery.noConflict();
var $ = jQuery;

/**
 * Tornar o texto do campo usuário em maiúsculo.
 
function loginUpper() {
	
	$('#usuario').change(function() {
		this.value = this.value.toUpperCase();
	});
	
}
*/

/**
 * Verificação de campos
 
function verificarCampo() {
	
	$('#usuario').change(function() {
		verifUsuario( $(this).val() );
	});
	
	$('form').submit(function(e) {
		
		if(!verifUsuario( $('#usuario').val() )) {
			e.preventDefault();
			return false;
		}
		
	});
	
	/**
	 * Verifica campo Usuário.
	 * 
	 * @param {type} valor
	 * @returns {Boolean}
	 
	function verifUsuario(valor) {
		
		if( valor.length > 10 ) {
			$('.alert')
				.empty()
				.html('Apenas 10 caracteres são permitidos para o campo <b>Usuário</b>.')
				.slideDown();
			return false;
		}
		else {
			$('.alert').slideUp();
			return true;
		}
		
	}
}
*/

(function($) {
	$(function() {
		
		sessionStorage.clear();
		localStorage.clear();
		//loginUpper();
		//verificarCampo();
		
	});
})(jQuery);