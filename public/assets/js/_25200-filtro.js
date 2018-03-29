	{ 
        /** Pesquisar Centro de Custo */
		
		var filtro;
		var campo_indicadores;
		var btn_filtro_indicadores;
		var input_group;
		var itens_indicadores;
		var _indicadores_id;
		var tempo_focus;
		var indicadores_selecionado = false;

		/**
		 * Filtrar objeto.
		 */
		function filtrarIndicadores() {

			campo_indicadores		= $('#indicadores-descricao');	//campo
			btn_filtro_indicadores	= $('.btn-filtro-indicadores');

			input_group			= $(campo_indicadores).parent('.input-group');	//input-group
			_indicadores_id			= $('input[name="_indicadores_id"]');

			filtro = campo_indicadores.val();

			//esvazia campo hidden caso algum item já tenha sido escolhido antes
			if(indicadores_selecionado) 
				$(_indicadores_id).val('');

			if( !filtro ) {
				fechaListaIndicadores( input_group );
				$(_indicadores_id).val('');
				//return false;
			}
            
            var url_action = "/_25200/filtrar";
            var dados = {'filtro': filtro};
            var type = "POST";
            
            function success(data){
              abreListaIndicadores( input_group );
					
					$('.lista-indicadores')
						.html(data);

					//existem dados cadastrados
					if( data.indexOf('nao-cadastrado') === -1 ) {

						itens_indicadores = $('ul.indicadores li a');
                        
						selecItemListaIndicadores( $(itens_indicadores), campo_indicadores );

						$(itens_indicadores)
							.focusout(function() {

								if(tempo_focus) 
									clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {

									if( !$(itens_indicadores).is(':focus') && 
										!$(campo_indicadores).is(':focus') && 
										!$(btn_filtro_indicadores).is(':focus') 
									) {
										$(campo_indicadores).val('');
										fechaListaIndicadores( input_group );
									}

								}, 200);

							});

						$(campo_indicadores)
							.focusout(function() {

								if(tempo_focus) 
									clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {

									if( !$(itens_indicadores).is(':focus') &&
										!$(_indicadores_id).val() && 
										!$(btn_filtro_indicadores).is(':focus') 
									) {
										$(campo_indicadores).val('');
										fechaListaIndicadores( input_group );
									}

								}, 200);

							});
					}
					else {
						$(_indicadores_id).val('');

						$(campo_indicadores)
							.focusout(function() {
								if( $('.lista-indicadores').children().children().hasClass('nao-cadastrado') ) {
									$(campo_indicadores).val('');
									fechaListaIndicadores( input_group );
								}
							});
					}  
            }
            

            execAjax2(type,url_action,dados,success,false,btn_filtro_indicadores,false);
		}

		//Abre resultado da filtragem
		function abreListaIndicadores(indicadores) {
			
			$(indicadores)
				.next('.lista-indicadores-container')
				.addClass('ativo');
		
			$(btn_filtro_indicadores)
				.attr('tabindex', '-1');
		}

		//Fecha resultado da filtragem
		function fechaListaIndicadores(indicadores) {
			
			$(indicadores)
				.next('.lista-indicadores-container')
				.removeClass('ativo')
				.children('.lista-indicadores')
				.empty();
		
			$(btn_filtro_indicadores)
				.removeAttr('tabindex');
		}

		/**
		 * Preencher campos de acordo com o item selecionado.
		 * 
		 * @param {object} itens
		 * @param {object} campo
		 * @returns {undefined}
		 */
		function selecItemListaIndicadores(itens, campo) {

			$(itens).click(function(e) {
				
				e.preventDefault();
				
				$(campo)
					.val( $(this).text() )
					.focus();
			
				selecionadoIndicadores();
			
				$(_indicadores_id)
					.val( $(this).nextAll('.indicadores-id').val() )
					.trigger('change');
            
                $( ".indicadores-descricao" ).trigger( "change" );
                
				fechaListaIndicadores( input_group );
				indicadores_selecionado = true;
			});

		}
		
		/**
		 * Ações que devem acontecer após o item ser selecionado.
		 */
		function selecionadoIndicadores() {
			
			$('#indicadores-descricao')
				.attr('readonly', true);

			$('.btn-filtro-indicadores')
				.hide();

			$('.btn-apagar-filtro-indicadores')
				.show()
				.click(function() {

					$(this)
						.siblings('input')
						.removeAttr('readonly');

					$(this)
						.hide()
						.prev('button')
						.show();

					$('#indicadores-descricao')
						.val('')
						.focus();

					$('input[name="_indicadores_id"]')
						.val('');

				});
		}
		
		
		/**
		 * Eventos para o filtro de Indicadores.
		 */
		function iniciarFiltroIndicadores() {
			
			//Se o item j? estiver selecionado (tela de update), efetua as devidas a??es.
			if ( $('input[name="_indicadores_id"]').val() !== '' )
				selecionadoIndicadores();

			//Bot?o de filtrar
			$('.btn-filtro-indicadores').on({

				click: function() {

					if ( !$(_indicadores_id).val() )
						filtrarIndicadores();
				},

				focusout: function() {

					if(tempo_focus) clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if ( !$('input[name="_indicadores_id"]').val() && 
							 $('.lista-indicadores').is(':empty') 
						) {
						   $('#indicadores-descricao').val('');
						}

						if ( !$('input[name="_indicadores_id"]').val() && 
							 !$('.lista-indicadores ul li a').is(':focus') && 
							  $('#indicadores-descricao').val() 
						) {
							$('#indicadores-descricao').val('');
							fechaListaIndicadores(input_group);
						}
						
						if ( !$('input[name="_indicadores_id"]').val() && 
							 !$('.lista-indicadores ul li a').is(':focus') && 
							 !$('#indicadores-descricao').val() 
						) {
							fechaListaIndicadores(input_group);
						}

					}, 200);
				}

			});

			//Campo de filtro
			$('#indicadores-descricao').on({

				keydown: function(e) {

					//Eventos ap?s a escolha de um item
					if ( $(this).is('[readonly]') ) {

						//Deletar teclando 'Backspace' ou 'Delete'
						if ( (e.keyCode === 8) || (e.keyCode === 46) ) {
							$('.btn-apagar-filtro-indicadores').click();
						}
					}
					else {

						//Pesquisar com 'Enter'
						if (e.keyCode === 13) {
							filtrarIndicadores();
						}
					}
				},

				focusout: function() {

					//verificar quando o campo deve ser zerado
					if(tempo_focus)
						clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if ( !$('input[name="_indicadores_id"]').val() && 
							 $('.lista-indicadores').is(':empty') &&
							 !$('.btn-filtro-indicadores').is(':focus') 
						) {
							$('#indicadores-descricao').val('');
						}

					}, 200);
				}

			});
		
		}
		
		iniciarFiltroIndicadores();
	}
//# sourceMappingURL=_25200-filtro.js.map
