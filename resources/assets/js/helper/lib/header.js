(function($) {

	/**
	 * Definir margem do corpo a partir da altura do cabeçalho
	 */
	function margemCorpo() {
		
		$('body > .container-fluid')
			.css('padding-top', $('ul.acoes').height() + 25);
	
	}
	
	/**
	 * Exibir botão que mostra as ações em Mobile.
	 */
	function btnAcoesMobileExibir() {
		
		if ( $(window).width() <= 768 ) {
		
			if ( $('ul.acoes').length > 0 ) {

				$('[data-acoes="toggle"]')
					.addClass('exibir')
				;
				
			}
			
			// acoes em popup
			$('.popup-acoes-toggle')
				.addClass('exibir')
			;

			// acoes em modal
			$('.btn-toggle-acoes-modal').addClass('exibir');
			
		}
		else {
		
			$('[data-acoes="toggle"]').removeClass('exibir');
            $('.popup-acoes-toggle').removeClass('exibir');

			// acoes em modal
			$('.btn-toggle-acoes-modal').removeClass('exibir');
		}
		
	}
	
	/**
	 * Definir os eventos do botão que mostra as ações em Mobile.
	 */
	function btnAcoesMobile() {

		$(document)
			.on('click','[data-acoes="toggle"]', function() {

				$('ul.acoes').toggleClass('abrir');
			});

		$('.popup')
			.on('click', '.popup-acoes-toggle', function() {

				$('.popup-acoes').toggleClass('abrir');
				return false;
			});

		// acoes em modal
		$(document)
			.on('click', '.btn-toggle-acoes-modal', function() {

				$('.btn-toggle-acoes-modal').each(function() {
					
					if ( $(this).is(':visible') ) {

						$(this)
							.next('.acoes-modal')
							.toggleClass('abrir');

						return false;
					}
				});
			});
	}
	
	$(function() {
		
		//margemCorpo();
		btnAcoesMobileExibir();
		btnAcoesMobile();
		
		/** Ações ao redimensionar a tela */
		$(window).resize(function() {
			
			btnAcoesMobileExibir();
			
		});
		
	});

})(jQuery);