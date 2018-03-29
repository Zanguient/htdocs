/**
 * Verificar se o Estabelecimento foi selecionado antes de filtrar Produto.
 * @returns {Boolean}
 */
function verifEstab() {
	
	var ret = true;
	
	if( $("._consulta_filtro[objcampo='estab']").val() === '' ) {
		showAlert('Selecione um Estabelecimento.');
		ret = false;
	}
	
	return ret;
				
}
	
(function($){
	
	var final_pag	 = false;	//variável para verificar se chegou na última página
	var pagina_atual = 1;
	var pagina_inc   = 0;
	var qtd_por_pag  = 30;
	var filtro_ativ	 = false;	//verificar se o filtro está ativo
	
	
	/**
	 * Paginação.
	 */
	function paginacaoScroll() {
		
		/**
		 * Carregar páginas
		 */
		function carregarPagina() {

			var filtro	 = '';
			var status	 = '';
			var estab	 = '';
			var data_ini = '';
			var data_fim = '';
			
			//Verifica se o filtro está ativo. Se sim, a paginação será filtrada também.
			if ( filtro_ativ ) {
				filtro	 = $('.filtro-obj').val();
				status	 = $('#filter-status').val();
				estab	 = $('.estab').val();
				data_ini = $('#data-ini').val();
				data_fim = $('#data-fim').val();
			}

			if ( final_pag || ($('.filtro-obj').val() !== '') ) {
				pagina_atual = 1;
				pagina_inc = 0;
				return false;
			}

			pagina_atual += 1;
			pagina_inc = pagina_atual * qtd_por_pag - qtd_por_pag;

			//ajax
			data = {
				'qtd_por_pagina'	: qtd_por_pag, 
				'pagina'			: pagina_inc,
				'filtro'			: filtro,
				'status'			: status,
				'estab'				: estab,
				'data_ini'			: data_ini,
				'data_fim'			: data_fim
			};
			
			success = function(resposta) {

				if(resposta) {						

					$('table.lista-obj-15010 tbody')
						.append(resposta);

				}
				else {
					final_pag = true;
				}

			};
			
			error = function(xhr) {
				final_pag = true;
				showErro(xhr);
			};
			
			execAjax1(
				'POST', 
				'/_15010/paginacaoScroll',
				data,
				success,
				error
			);
			//

		}
		
		/**
		 * Eventos do scroll.
		 */
		function eventoScroll() {

			setTimeout(function() {		//espera necessária devido ao plugin dataTable

				var scroll_timer = 0;	//verificar timeout

				//carregar página com scroll
				$('.dataTables_scrollBody').scroll(function() {

					var div = $(this);

					clearTimeout(scroll_timer);

					scroll_timer = setTimeout(function() {
						if( ( div.scrollTop() + div.height() ) >= div.children('table').height() )
							carregarPagina();
					}, 200);

				});

			}, 1000);
			
		}
		
		eventoScroll();
	}

	/**
	 * Filtrar tabela de acordo com as opções escolhidas.
	 */
	function filtrarRefinado() {
		
		/**
		 * Verificar se um dos períodos está vazio.
		 * @param {string} data_ini
		 * @param {string} data_fim
		 * @returns {Boolean}
		 */
		function verifPeriodo(data_ini, data_fim) {
			
			var ret = false;
			
			if ( data_ini === '' && data_fim !== '' ) {
				
				$('#data-ini')
					.addClass('invalid');
			
				ret = false;
				
			}
			else if ( data_ini !== '' && data_fim === '' ) {					
			
				$('#data-fim')
					.addClass('invalid');
			
				ret = false;
				
			}
			else {
				
				$('#data-ini, #data-fim')
					.removeClass('invalid');
			
				ret = true;
				
			}
			
			return ret;
		}
	
		/**
		 * Evento do botão filtrar.
		 */
		function eventoBtnFiltrar() {
			
			$('#table-filter')
				.on('click', '#btn-table-filter', function() {

					var data_ini = $('#data-ini').val();
					var data_fim = $('#data-fim').val();

					if ( !verifPeriodo(data_ini, data_fim) ) {
						return false;
					}

					//se o filtro for alterado, a paginação deve reiniciar
					final_pag		= false;
					pagina_atual	= 1;
					pagina_inc		= 0;
					filtro_ativ		= true;

					var data = {
						'filtro'	: $('.filtro-obj').val(),
						'status'	: $('#filter-status').val(),
						'estab'		: $('.estab').val(),
						'data_ini'	: data_ini, 
						'data_fim'	: data_fim
					};

					//ajax
					execAjax1(
						'POST',
						'/_15010/filtrar',
						data,

						function(data) {

							if (data) {
								
								$('.dataTables_scrollBody')
									.scrollTop(0);
								
								$('table.lista-obj-15010 tbody')
									.empty()
									.append(data);
								
							}
						}
					);

					uriHistory(data);
				});	
		}
		
		eventoBtnFiltrar();
	}
	
	/** 
	 * Filtrar tabela a partir do valor digitado no campo.
	 */
	function filtrar() {

		/**
		 * Filtro.
		 */
		function iniciarFiltrar() {

			var status	 = '';
			var estab	 = '';
			var data_ini = '';
			var data_fim = '';
			
			//Verifica se o filtro está ativo. Se sim, a paginação será filtrada também.
			if ( filtro_ativ ) {
				status	 = $('#filter-status').val();
				estab	 = $('.estab').val();
				data_ini = $('#data-ini').val();
				data_fim = $('#data-fim').val();
			}
			
			//se o filtro for alterado, a paginação deve reiniciar
			final_pag		= false;
			pagina_atual	= 1;
			pagina_inc		= 0;
			filtro_ativ		= true;

			var dado = {
				'filtro'	: $('.filtro-obj').val(),
				'status'	: status,
				'estab'		: estab,
				'data_ini'	: data_ini,
				'data_fim'	: data_fim
			};
			
			execAjax1(
				'POST',
				'/_15010/filtrar',
				dado,
				function(data) {
					
					if ( data ) {

						$('table.lista-obj-15010 tbody')
							.empty()
							.append(data);
						
					}
				}
			);

			uriHistory(dado);
		}

	    /**
		 * Eventos da filtragem.
		 */
		function eventoFiltrar() {

			$('.btn-filtro-obj').click(function() {
				iniciarFiltrar();
			});				

			$('.filtro-obj').keydown(function(e) {
				if (e.keyCode === 13) 
					iniciarFiltrar();
			});
		}

		eventoFiltrar();
	}

	/**
	 * Filtrar tabela ao carregar página se algum filtro tiver sido feito antes.
	 */
	function filtrarAoCarregar() {

		// Se houver parâmetros na url, o que significa que um filtro já foi feito antes.
		if ( location.href.indexOf('?') > -1 ) {
			
			setTimeout(function() { 
				$('#btn-table-filter').click(); 
			}, 1000);
		}
	}
	
	/**
	 * Definir valor do estabelecimento no parâmetro do produto.
	 */
	function selecEstab() {
		
		setTimeout(function() {
			
			$("._consulta_filtro[objcampo='estab']")
				.val( $('select.estab').val() );
		
		}, 500);
		
		$('select.estab').change(function() {
			
			$("._consulta_filtro[objcampo='estab']")
				.val( $(this).val() );
			
		});
		
	}
	
	
	function aposClonar() {
		
		$('.add-item-dinamico')
			.click(function() {
				
				var itens_dinamicos = $(this).prev('.item-dinamico-container').children('.item-dinamico'),
					count_itens		= $(itens_dinamicos).length,
					last_item		= $(itens_dinamicos).last()
				;
		
				$(last_item)
					.find('.qtd')
					.alterClass('qtd', 'qtd-'+count_itens)
				;
				$(last_item)
					.find('.tamanho-produto')
					.addClass('tamanho-produto-'+count_itens)
					//.alterClass('tamanho-produto', 'tamanho-produto-'+count_itens)
				;
				$(last_item)
					.find('.tamanho-posicao')
					.addClass('tamanho-posicao-'+count_itens)
					// .alterClass('tamanho-posicao', 'tamanho-posicao-'+count_itens)
				;
				$(last_item)
					.find('.medida-prod')
					.alterClass('medida-prod', 'medida-prod-'+count_itens)
				;
				
				$(last_item)
					.find('.consulta-descricao')
					.removeAttr('readonly')
					.focus()
					.next('.btn-filtro-consulta')
					.show()
					.next('.btn-apagar-filtro-consulta')
					.hide()
				;
				
				$(last_item)
					.find('._consulta_recebevalor')
					.each(function() {
						
						$(this).attr('objclass', $(this).attr('objclass')+'-'+count_itens);
				
					})
				;
				
				limiteTextarea( $(last_item).find('textarea.obs'), 200, $('span.contador span') ); //função em input.js
				
			})
		;
		
	}
	
		
	/**
	 * Encerrar/desencerrar requisição.
	 */
	function encerrar() {
		
		function acao(btn_encerrar) {
			
			var status		 = '',
				msg_sucesso	 = ''
			;
			
			if ( $(btn_encerrar).attr('aria-pressed') === 'false' ) {
				status		 = '2';
				msg_sucesso	 = 'Requisição encerrada com sucesso.';
			}
			else {
				status		 = '1';
				msg_sucesso	 = 'Requisição desencerrada com sucesso.';
			}

			//ajax
			var type	= 'POST',
				url		= '/_15010/encerrar',
				data	= {
					'requisicao_id'	: $(btn_encerrar).data('requisicao-id'),
					'status'		: status
				},
				success	= function(resposta) {
					showSuccess(msg_sucesso);

					if ( status == '1' ) {
						
						$(btn_encerrar).find('.texto').text( 
							$(btn_encerrar).data('text-active')
						);
					}
					else {
						
						$(btn_encerrar).find('.texto').text( 
							$(btn_encerrar).data('text-inactive')
						);
					}
				}
			;
			
			execAjax1(type, url, data, success);
			
		}
		
		$('button.encerrar')
			.off('click')
			.click(function() {
				acao( $(this) );
			})
		;
		
	}
	
	/**
	 * Ativar Datatable.
     * @param {element} table
     * @returns {void}
     */
	function ativarDatatable(table) {
		
		if (table.length == 0) 
			return false;

		var table = table || $('.table');
        
		var data_table = $.extend({}, table_default);
			data_table.scrollY = '65vh';

		$(table).DataTable(data_table);
		
	}

	function verificarAoGravar() {

		$('button.js-gravar').click(function(e) {

			if ( !($('._input_estab').val() > 0 )) {

				e.preventDefault();
				showAlert('Escolha um Estabelecimento.');
				return false;

			}
			else if ( $('._ccusto_id').val() == '' ) {

				e.preventDefault();
				showAlert('Escolha um Centro de Custo.');
				return false;

			}			

		});

	}


	/**
	 * Definir URL com parâmetro do filtro e guardar em localStorage.
	 * @param json dado
	 */
	function uriHistory(dado) {

		window.history.replaceState('', '', encodeURI(urlhost + '/_15010?'+ $.param(dado)));

		localStorage.setItem('15010FiltroUrl', location.href);
	}

	
	$(function() {
		
		limiteTextarea( $('textarea.obs'), 200, $('span.contador span') ); //função em input.js
		paginacaoScroll();
		filtrarRefinado();
		filtrar();
		filtrarAoCarregar();
		selecEstab();
		aposClonar();
		encerrar();
		verificarAoGravar();
		ativarDatatable( $('table.lista-obj-15010') );
		
	});
})(jQuery);