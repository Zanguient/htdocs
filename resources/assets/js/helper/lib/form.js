/**
 * Script para form.
 */

/**
 * Desabilitar (readonly) os inputs se o fieldset for 'readonly'.
 */
function inputReadonly() {
	
	$('fieldset').each(function() {
		
		if ( $(this).attr('readonly') ) {
		
			$(this)
				.find('input:not(:hidden), textarea')
				.attr('readonly', true)
				.closest('fieldset:read-only')
				.find('button, select')
				.attr('disabled', true);
		}
	});
}

(function($) {
	$(function() {
		
		inputReadonly();
		
	});
})(jQuery);