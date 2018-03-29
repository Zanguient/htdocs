/**
 * Script para o modal de excluir.
 */

(function($) {
	
	$(function() {
		
		$('#confirmDelete').on('show.bs.modal', function (e) {
			$message = $(e.relatedTarget).attr('data-message');
			$(this).find('.modal-body p').text($message);
			$title = $(e.relatedTarget).attr('data-title');
			$(this).find('.modal-title').text($title);

			// Pass form reference to modal for submission on yes/ok
			var form = $(e.relatedTarget).closest('form');
			$(this).find('.modal-header #confirm').data('form', form);
		});

		//Form confirm (yes/ok) handler, submits form
		$('#confirmDelete').find('.modal-header #confirm').on('click', function(){
			$(this).data('form').submit();
		});
		
	});
})(jQuery);