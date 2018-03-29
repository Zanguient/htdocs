(function($){
	
	var final_pag	 = false;	//variável para verificar se chegou na última página
	var pagina_atual = 1;
	var pagina_inc   = 0;
	var qtd_por_pag  = 30;
	var filtro_ativ	 = false;	//verificar se o filtro está ativo
	
	/**
	* Iniciar funções relacionadas ao botão baixar.
	*/
	function iniciarBtnBaixar() {

		/**
		 * Clonar campos no popup de acordo com a quantidade de linhas selecionadas.
		 * 
		 * @param {int} qtd_linha_selec
		 */
		function clonarInput(qtd_linha_selec) {

		   for (var i = 1; i < qtd_linha_selec; i++) {

			   $('.popup')
				   .find('.grupo-req')
				   .first()
				   .clone(true)
				   .appendTo(
					   $('.modal-body')
				   );
		   }
		}

		/**
		 * Define o valor dos inputs no popup de acordo com a linha selecionada.
		 */
		function setarValorInput() {

		   var i			= 1;
		   var id			= '';
		   var prod			= '';
		   var prod_id		= '';
		   var loc_padrao	= '';
		   var qtd			= '';
		   var saldo		= '';
		   var operacao_requisicao	= '';

		   $('.chk-req-selec:checked')
			   .each(function() {

					var tr		= $(this).closest('tr');
					id			= $(tr).find('.req-id').text();
					prod		= $(tr).find('.req-produto').text();
					prod_id		= $(tr).find('._req_produto_id').val();
					loc_padrao	= $(tr).find('._req_localizacao_padrao').val();
					qtd			= $(tr).find('.req-qtd').text();
					saldo		= $(tr).find('.req-saldo').text();
					operacao_requisicao = $(tr).find('._operacao_requisicao').val();

					var popup = $('.popup');
					var grupo = popup.find('.grupo-req:nth-of-type('+i+')');

					grupo
						.find('input.req-id')
						.val(id);

					grupo
					   .find('input.req-produto')
					   .val(prod);

					grupo
					   .find('input.req-qtd')
					   .val(qtd);

					grupo
					   .find('input.req-saldo')
					   .val(saldo);

					//valor máximo permitido para baixar (de acordo com o saldo)
					grupo
					   .find('input.req-baixar')
					   .val(formataPadrao(saldo))
					   .attr('max', formataPadrao(saldo));
			   
					grupo
						.find('._consulta_filtro[objcampo="_operacao_prod_id"]')
						.val(prod_id);
				
					//localização padrão
					grupo
						.find('._loc_cadastrado')
						.val(loc_padrao);
				
					grupo
						.find('.loc')
						.children('option')
						.each(function() {
							
							if ( $(this).val() === loc_padrao ) {
								$(this).attr('selected', true);
							}
							else {
								$(this).removeAttr('selected');
							}
					
						});
					//
					
					grupo
						.find('input._operacao_requisicao_modal')
						.val(operacao_requisicao);

				   i++;

			   });
	   }

		/**
		 * Ativa ação do botão 'Baixar Estoque', 
		 * que abre o popup com os itens selecionados para dar baixa.
		 */
		function acaoBtnBaixar() {

			 //botão baixar estoque
			 $('.baixar-estoque')
				 .click(function() {

					 var qtd_linha_selec = $('.chk-req-selec:checked').length;

					 clonarInput(qtd_linha_selec);
					 setarValorInput();

					 $(this).popUp();

				 });

		}
	   
		/**
		 * Ativa ação do botão 'Voltar' do Popup.
		 */
		function acaoBtnVoltar() {

			//ações ao fechar popup
			 $('.popup-close')
				 .click(function() {

					 setTimeout(function() {

						 var grupo = $('.popup').find('.grupo-req');

						 $(grupo)
							 .not(':first')
							 .remove();

						 $(grupo)
							 .children('.form-group')
							 .find('input')
							 .val('');

						 //resetar consulta de operação
						 var consulta = $(grupo).children('.consulta-container');

						 $(consulta)
							 .find('input.consulta-descricao')
							 .val('');

						 $(consulta)
							 .find('._consulta_imputs')
							 .children('input')
							 .val('');

						 $(consulta)
							 .find('input._consulta_filtro')
							 .val('');

						 $(consulta)
							 .find('input._valor_selecionado_consulta')
							 .val('');

						 $(consulta)
							 .find('input.consulta-descricao')
							 .removeAttr('readonly')
							 .val('');

						 $(consulta)
							 .find('.btn-apagar-filtro-consulta')
							 .hide()
							 .prev('button')
							 .show();
						 //


					 }, 200);

				 });
		}

		acaoBtnBaixar();
		acaoBtnVoltar();
	}

	/**
	 * Habilitar botão 'Baixar Estoque' caso algum item da tabela
	 * esteja selecionado.
	 */
	function habilitarBtnBaixar() {

	   /**
		* Verifica se existe linha selecionada
		*/
	   function verifLinhaSelec() {

		   setTimeout(function() {

			   if( $('.chk-req-selec').is(':checked') )
				   $('.baixar-estoque').removeAttr('disabled');
			   else
				   $('.baixar-estoque').attr('disabled', true);

		   }, 200);
	   }

	   $('table')
		   .on('change', '.chk-req-selec', function() {
			   verifLinhaSelec();
		   });

	   verifLinhaSelec();

	}
	
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
			var dados	 = '';
			var url		 = '';
			var create	 = ($('#filter-status').length > 0) ? true : false; //verifica se está na tela create
			
			//Verifica se o filtro está ativo. Se sim, a paginação será filtrada também.
			if ( filtro_ativ ) {
				
				if (create)	{
					status = $('#filter-status').val();
				}
				
				filtro	 = $('.filtro-obj').val();
				estab	 = $('.estab').val();
				data_ini = $('#data-ini').val();
				data_fim = $('#data-fim').val();
			}

			if ( final_pag || ($('.filtro-obj').val() !== '') ) {
				pagina_atual = 1;
				pagina_inc   = 0;
				return false;
			}

			pagina_atual   += 1;
			pagina_inc		= pagina_atual * qtd_por_pag - qtd_por_pag;

			//ajax
			if (create) {
			
				url		= '/_15040/paginacaoScroll';
				dados	= {
					'qtd_por_pagina'	: qtd_por_pag, 
					'pagina'			: pagina_inc,
					'filtro'			: filtro,
					'status'			: status,
					'estab'				: estab,
					'data_ini'			: data_ini,
					'data_fim'			: data_fim
				};
				
			}
			else {
				
				url		= '/_15040/paginacaoScrollBaixa';
				dados	= {
					'qtd_por_pagina'	: qtd_por_pag, 
					'pagina'			: pagina_inc,
					'filtro'			: filtro,
					'estab'				: estab,
					'data_ini'			: data_ini,
					'data_fim'			: data_fim
				};
			}
			
			success = function(resposta) {

				if(resposta) {						

					$('table.lista-obj-15040 tbody')
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
				url,
				dados,
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
					var url		 = '';
					var dados	 = '';
					var create	 = ($('#filter-status').length > 0) ? true : false; //verifica se está na tela create

					if ( !verifPeriodo(data_ini, data_fim) ) {
						return false;
					}

					//se o filtro for alterado, a paginação deve reiniciar
					final_pag		= false;
					pagina_atual	= 1;
					pagina_inc		= 0;
					filtro_ativ		= true;
					
					//tela create
					if ( create ) {

						url	  = '/_15040/filtrar';
						dados = {
							'filtro'	: $('.filtro-obj').val(),
							'status'	: $('#filter-status').val(),
							'estab'		: $('.estab').val(),
							'data_ini'	: data_ini,
							'data_fim'	: data_fim
						};

					}
					else {

						url	  = '/_15040/filtrarBaixa';
						dados = {
							'filtro'	: $('.filtro-obj').val(),
							'estab'		: $('.estab').val(),
							'data_ini'	: data_ini,
							'data_fim'	: data_fim
						};
					}

					//ajax
					execAjax1(
						'POST',
						url,
						dados,
						function(data) {

							if (data) {
								
								$('.dataTables_scrollBody')
									.scrollTop(0);
								
								$('table.lista-obj-15040 tbody')
									.empty()
									.append(data);
							
								habilitarBtnBaixar();
								
							}
						}
					);

					uriHistory(dados, create);

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
			var url		 = '';
			var dados	 = '';
			var create	 = ($('#filter-status').length > 0) ? true : false; //verifica se está na tela create
			
			//Verifica se o filtro está ativo. Se sim, a paginação será filtrada também.
			if ( filtro_ativ ) {
				
				//tela create
				if ( create ) {
					status = $('#filter-status').val();
				}
				
				estab	 = $('.estab').val();
				data_ini = $('#data-ini').val();
				data_fim = $('#data-fim').val();
			}

			//se o filtro for alterado, a paginação deve reiniciar
			final_pag		= false;
			pagina_atual	= 1;
			pagina_inc		= 0;
			filtro_ativ		= true;
			
			//tela create
			if ( create ) {
				
				url	  = '/_15040/filtrar';
				dados = {
					'filtro'	: $('.filtro-obj').val(),
					'status'	: status,
					'estab'		: estab,
					'data_ini'	: data_ini,
					'data_fim'	: data_fim
				};
				
			}
			else {
				
				url	  = '/_15040/filtrarBaixa';
				dados = {
					'filtro'	: $('.filtro-obj').val(),
					'estab'		: estab,
					'data_ini'	: data_ini,
					'data_fim'	: data_fim
				};
			}
			
			execAjax1(
				'POST',
				url,
				dados,
				function(data) {
					
					if ( data ) {

						$('table.lista-obj-15040 tbody')
							.empty()
							.append(data);

						//tela create
						if ( create ) {
							habilitarBtnBaixar();
						}
					}
				}
			);

			uriHistory(dados, create);

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
	 * Definir URL com parâmetro do filtro e guardar em localStorage.
	 * @param json dado
	 * @param boolean create
	 */
	function uriHistory(dado, create) {

		if (create) {
			
			window.history.replaceState('', '', encodeURI(urlhost + '/_15040?'+ $.param(dado)));
			localStorage.setItem('15040FiltroUrl', location.href);
		}
		else {
		
			window.history.replaceState('', '', encodeURI(urlhost + '/_15040/create?'+ $.param(dado)));
			localStorage.setItem('15040CreateFiltroUrl', location.href);
		}
	}

	/**
	 * Ativar Datatable.
     * @param {element} table
	 * @param {string} height
     * @returns {void}
     */
	function ativarDatatable(table, height) {
		
		var table = table || $('.table');
        
		var data_table = $.extend({}, table_default);
			data_table.scrollY = height;

		$(table).DataTable(data_table);
		
	}
	
	$(function() {
		
		iniciarBtnBaixar();
		habilitarBtnBaixar();
		paginacaoScroll();
		filtrarRefinado();
		filtrar();
		filtrarAoCarregar();
		
		if ( $('.table-baixa-realizada').length > 0 ) {
			ativarDatatable( $('.table-baixa-realizada'), '70vh' );
		}
		else {
			ativarDatatable( $('.table-requisicao-pendente'), '53vh' );
		}
		
		ativarSelecLinhaCheckbox();
		
	});
})(jQuery);
//# sourceMappingURL=_15040.js.map
