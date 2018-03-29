(function($) {
	
	/**
	 * Filtrar Estabelecimento e GP.
	 */
	function filtrarEstabGp()
    {	

        function dado()
        {
		
			//ajax
			var type	= 'POST',
				url		= '/_22060/filtrarEstabGp',
				data	= {
					estabelecimento_id	: $('.estab' ).val(),
					gp_id				: $('._gp_id').val(),
					up_id				: $('._up_id').val(),
					data_ini			: $('#data-ini').val(),
					data_fim			: $('#data-fim').val()
				},
				success	= function(resposta) {

					$('.estab-group').html(resposta);

				};

			execAjax1(type, url, data, success);
            
        }
		
        function evento() {

			$('#filtrar')
				.click(function() {

					dado();

				})
			;

		}

		evento();
    } 
	
	function filtrarUp()
    {	

        function dado(elem) {
			
			//ajax
			var type	= 'POST',
				url		= '/_22060/filtrarUp',
				data	= {
					estabelecimento_id	: $('.panel-estab .panel-heading a:not(.collapsed)').data('estab-id'),
					gp_id				: $(elem).data('gp-id'),
					up_id				: $('._up_id').val(),
					data_ini			: $('#data-ini').val(),
					data_fim			: $('#data-fim').val()
				},
				success	= function(resposta) {

					$('#'+$(elem).attr('aria-controls')+' .up-group').html(resposta);

				};

			execAjax1(type, url, data, success);
            
        }
		
        function evento() {

			$(document)
				.on('click', '.panel-gp > .panel-heading a.collapsed', function() {

					dado( $(this) );

				})
			;

		}

		evento();
    } 
	
	function filtrarEstacao()
    {	

        function dado(elem) {
			
			//ajax
			var type	= 'POST',
				url		= '/_22060/filtrarEstacao',
				data	= {
					estabelecimento_id	: $('.panel-estab .panel-heading a:not(.collapsed)').data('estab-id'),
					gp_id				: $('.panel-gp .panel-heading a:not(.collapsed)').data('gp-id'),
					up_id				: $(elem).data('up-id'),
					dividir_estacao		: $('#dividir-estacao').is(':checked'),
					data_ini			: $('#data-ini').val(),
					data_fim			: $('#data-fim').val()
				},
				success	= function(resposta) {

					$('#'+$(elem).attr('aria-controls')+' .estacao-group')
						.html(resposta)
					;

				};

			execAjax1(type, url, data, success);
            
        }
		
        function evento() {

			$(document)
				.on('click', '.panel-up > .panel-heading a.collapsed', function() {

					if ( $('#dividir-estacao').is(':checked') )
						dado( $(this), true );
					else {
						var talao = new filtrarTalao();
						talao.dado( $(this), false );
					}

				})
			;

		}

		evento();
    } 
	
	function filtrarTalao()
    {	
		this.dado = dado;
		
        function dado(elem, com_estacao) {
			
			//ajax
			var type	= 'POST',
				url		= '/_22060/filtrarTalao',
				data = {
					estabelecimento_id	: $('.panel-estab .panel-heading a:not(.collapsed)').data('estab-id'),
					gp_id				: $('.panel-gp .panel-heading a:not(.collapsed)').data('gp-id'),
					up_id				: $('.panel-up .panel-heading a:not(.collapsed)').data('up-id'),
					dividir_estacao		: $('#dividir-estacao').is(':checked'),
					data_ini			: $('#data-ini').val(),
					data_fim			: $('#data-fim').val()
				},
				success	= function(resposta) {

					if(com_estacao) {
						
						$('#'+$(elem).attr('aria-controls')+' .talao-group')
							.html(resposta)
						;
					}
					else {
						
						$('#'+$(elem).attr('aria-controls')+' .estacao-group')
							.html(resposta)
						;
					}

				};
				
			if(com_estacao) { 
				data.estacao = $(elem).data('estacao-id'); 
			}

			execAjax1(type, url, data, success);
            
        }
		
        function evento() {

			$(document)
				.on('click', '.panel-estacao > .panel-heading a.collapsed', function() {

					dado( $(this), true );

				})
			;

		}

		evento();
    } 
	
	function filtrarTalaoDetalhe()
    {	

        function dado(elem) {
			
			//ajax
			var type	= 'POST',
				url		= '/_22060/filtrarTalaoDetalhe',
				data	= {
					estabelecimento_id	: $('.panel-estab .panel-heading a:not(.collapsed)').data('estab-id'),
					gp_id				: $('.panel-gp .panel-heading a:not(.collapsed)').data('gp-id'),
					up_id				: $('.panel-up .panel-heading a:not(.collapsed)').data('up-id'),
					talao_id			: $(elem).data('talao-id'),
					data_ini			: $('#data-ini').val(),
					data_fim			: $('#data-fim').val()
				},
				success	= function(resposta) {

					$('#'+$(elem).attr('aria-controls')+' .talao-detalhe-group')
						.html(resposta)
					;

				};

			execAjax1(type, url, data, success);
            
        }
		
        function evento() {

			$(document)
				.on('click', '.panel-talao > .panel-heading a.collapsed', function() {

					dado( $(this) );

				})
			;

		}

		evento();
    }
	
	/**
	 * Limpar filtros quando um painel for fechado.
	 */
	function limparFiltro() {
		
		$(document)
			.on('click', '.panel:not(.panel-estab) > .panel-heading a', function() {

				if( $(this).hasClass('collapsed') ) {
				
					$('#'+$(this).attr('aria-controls')+' .panel-group')
						.empty()
					;
				}
				else {
					
					$(this)
						.parent()
						.siblings('.panel-collapse.in')
						.find('.panel-group')
						.empty()
					;
				}

			})
		;
	}
	
	/**
	 * Definir os parâmetros de um campo a partir de outro.
	 */
	function definirParam() {
		
		/**
		 * Define o GP selecionado como parâmetro para a UP.
		 */
		function gpParamUp() {

			$('._gp_id')
				.change(function() {

					$('.consulta_up_group')
						.siblings('._consulta_parametros')
						.children('._consulta_filtro[objcampo="GP"]')
						.val( $(this).val() )
					;

				})
			;

		}

		gpParamUp();
	}
	
	
	$(function() {
		
		filtrarEstabGp();
		filtrarUp();
		filtrarEstacao();
		filtrarTalao();
		filtrarTalaoDetalhe();
		//limparFiltro();
		definirParam();
		
	});
	
})(jQuery);