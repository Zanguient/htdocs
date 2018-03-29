/**
 * Ativa o link em linhas de tabela.<br/>
 * Ex. de uso: &lt;tr link="#" /&gt;
 */
function tableClick() {

	$('table')
		.on('click', 'td', function() {
			var link = $(this).parent('tr').attr('link');
			if( link ) location.href = link;
		});
}

/** 
 * Esconder botão de carregar página exista apenas uma página. 
 */
function hideButtonScroll() {
			
	if ( $('button.carregar-pagina').length > 0 ) {

		if ( $('table tbody tr').siblings().length < 30 ) {
			$('button.carregar-pagina').hide();
		}
	}
}

/**
 * Ativar seleção de linha da tabela com radio.
 */
function ativarSelecLinhaRadio() {
	
	var radio_length	=	$('table')
								.find('input[type="radio"]')
								.length
							;
	
	if ( radio_length > 0 ) {
		
		$('table')
			.on('change', 'tbody tr td input[type="radio"]', function() {

				if ( !$(this).is(':checked') ) {

					$(this)
						.closest('tr')
						.removeClass('selected')
						.siblings('tr')
						.removeClass('selected')
					;

				}
				else {

					$(this)
						.closest('tr')
						.addClass('selected')
						.siblings('tr')
						.removeClass('selected')
					;

				}
				
			})
			.off('click').on('click', 'tbody tr', function() {
				
				$(this)
					.find('input[type="radio"]')
					.prop('checked', true)
					.change()
				;

			})
			.on('keydown', 'tbody tr', 'return', function() {
				
				$(this)
					.click()
				;
		
			})			
		;
		
	}
	
	delete radio_length;
	
}


/**
 * Ativar seleção de linha da tabela com checkbox.
 */
function ativarSelecLinhaCheckbox() {
	
	var chk_length	=	$('table')
							.find('input[type="checkbox"]')
							.length
						;
	
	if ( chk_length > 0 ) {
		
		$('table')
			.on('change', 'input[type="checkbox"]', function() {
	
				var tr	=	$(this)
								.closest('tr')
							;
				
				if ( $(tr).hasClass('selected') ) {
			
					$(tr)
						.removeClass('selected')
                        .trigger('trSelectedChanged')
					;
					
				}
				else {
					
					$(tr)
						.addClass('selected')
                        .trigger('trSelectedChanged')
					;
					
				}
			
			})
			.on('click', 'tbody tr', function() {

				var chk	=	$(this)
								.find('input[type="checkbox"]')
							;
								
				$(chk)
					.prop('checked', !$(chk).is(':checked'))
					.change()
				;

			})
			.on('keydown', 'tbody tr', 'return', function() {
				
				$(this)
					.click()
				;
		
			})
		;	
	}
	
	delete chk_length;
	
}

(function($) {
    $(function() {
        tableClick();
        hideButtonScroll();
    });
})(jQuery);