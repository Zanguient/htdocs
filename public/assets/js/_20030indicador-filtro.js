	{ /** Pesquisar Centro de Custo */
		
		var filtro;
		var campo_ccustoindicador;
		var btn_filtro_ccustoindicador;
		var input_group;
		var itens_ccustoindicador;
		var _ccustoindicador_id;
		var tempo_focus;
		var ccustoindicador_selecionado = false;

		/**
		 * Filtrar objeto.
		 */
		function filtrarCCustoIndicador() {

			campo_ccustoindicador		= $('#ccustoindicador-descricao');	//campo
			btn_filtro_ccustoindicador	= $('.btn-filtro-ccustoindicador');

			input_group			= $(campo_ccustoindicador).parent('.input-group');	//input-group
			_ccustoindicador_id			= $('input[name="_ccustoindicador_id"]');

			filtro = campo_ccustoindicador.val();

			//esvazia campo hidden caso algum item j� tenha sido escolhido antes
			if(ccustoindicador_selecionado) 
				$(_ccustoindicador_id).val('');

			if( !filtro ) {
				fechaListaCCustoIndicador( input_group );
				$(_ccustoindicador_id).val('');
				//return false;
			}
            
            var url_action = "/_20030/pesquisaCCustoIndicador";
            var dados = {'filtro': filtro};
            var type = "POST";
            
            function success(data){
              abreListaCCustoIndicador( input_group );
					
					$('.lista-ccustoindicador')
						.html(data);

					//existem dados cadastrados
					if( data.indexOf('nao-cadastrado') === -1 ) {

						itens_ccustoindicador = $('ul.ccustoindicador li a');
                        
						selecItemListaCCustoIndicador( $(itens_ccustoindicador), campo_ccustoindicador );

						$(itens_ccustoindicador)
							.focusout(function() {

								if(tempo_focus) 
									clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {

									if( !$(itens_ccustoindicador).is(':focus') && 
										!$(campo_ccustoindicador).is(':focus') && 
										!$(btn_filtro_ccustoindicador).is(':focus') 
									) {
										$(campo_ccustoindicador).val('');
										fechaListaCCustoIndicador( input_group );
									}

								}, 200);

							});

						$(campo_ccustoindicador)
							.focusout(function() {

								if(tempo_focus) 
									clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {

									if( !$(itens_ccustoindicador).is(':focus') &&
										!$(_ccustoindicador_id).val() && 
										!$(btn_filtro_ccustoindicador).is(':focus') 
									) {
										$(campo_ccustoindicador).val('');
										fechaListaCCustoIndicador( input_group );
									}

								}, 200);

							});
					}
					else {
						$(_ccustoindicador_id).val('');

						$(campo_ccustoindicador)
							.focusout(function() {
								if( $('.lista-ccustoindicador').children().children().hasClass('nao-cadastrado') ) {
									$(campo_ccustoindicador).val('');
									fechaListaCCustoIndicador( input_group );
								}
							});
					}  
            }

            execAjax2(type,url_action,dados,success,false,btn_filtro_ccustoindicador,false);
		}

		//Abre resultado da filtragem
		function abreListaCCustoIndicador(ccustoindicador) {
			
			$(ccustoindicador)
				.next('.lista-ccustoindicador-container')
				.addClass('ativo');
		
			$(btn_filtro_ccustoindicador)
				.attr('tabindex', '-1');
		}

		//Fecha resultado da filtragem
		function fechaListaCCustoIndicador(ccustoindicador) {
			
			$(ccustoindicador)
				.next('.lista-ccustoindicador-container')
				.removeClass('ativo')
				.children('.lista-ccustoindicador')
				.empty();
		
			$(btn_filtro_ccustoindicador)
				.removeAttr('tabindex');
		}

		/**
		 * Preencher campos de acordo com o item selecionado.
		 * 
		 * @param {object} itens
		 * @param {object} campo
		 * @returns {undefined}
		 */
		function selecItemListaCCustoIndicador(itens, campo) {

			$(itens).click(function(e) {
				
				e.preventDefault();
				
				$(campo)
					.val( $(this).text() )
					.focus();
			
				selecionadoCCustoIndicador();
			
				$(_ccustoindicador_id)
					.val( $(this).nextAll('.ccustoindicador-id').val() )
					.trigger('change');
			
				fechaListaCCustoIndicador( input_group );
				ccustoindicador_selecionado = true;
			});

		}
		
		/**
		 * Ações que devem acontecer após o item ser selecionado.
		 */
		function selecionadoCCustoIndicador() {
			
			$('#ccustoindicador-descricao')
				.attr('readonly', true);

			$('.btn-filtro-ccustoindicador')
				.hide();

			$('.btn-apagar-filtro-ccustoindicador')
				.show()
				.click(function() {

					$(this)
						.siblings('input')
						.removeAttr('readonly');

					$(this)
						.hide()
						.prev('button')
						.show();

					$('#ccustoindicador-descricao')
						.val('')
						.focus();

					$('input[name="_ccustoindicador_id"]')
						.val('');

				});
		}
		
		
		/**
		 * Eventos para o filtro de CCustoIndicador.
		 */
		function iniciarFiltroCCustoIndicador() {
			
			//Se o item j� estiver selecionado (tela de update), efetua as devidas a��es.
			if ( $('input[name="_ccustoindicador_id"]').val() !== '' )
				selecionadoCCustoIndicador();

			//Bot�o de filtrar
			$('.btn-filtro-ccustoindicador').on({

				click: function() {

					if ( !$(_ccustoindicador_id).val() )
						filtrarCCustoIndicador();
				},

				focusout: function() {

					if(tempo_focus) clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if ( !$('input[name="_ccustoindicador_id"]').val() && 
							 $('.lista-ccustoindicador').is(':empty') 
						) {
						   $('#ccustoindicador-descricao').val('');
						}

						if ( !$('input[name="_ccustoindicador_id"]').val() && 
							 !$('.lista-ccustoindicador ul li a').is(':focus') && 
							  $('#ccustoindicador-descricao').val() 
						) {
							$('#ccustoindicador-descricao').val('');
							fechaListaCCustoIndicador(input_group);
						}
						
						if ( !$('input[name="_ccustoindicador_id"]').val() && 
							 !$('.lista-ccustoindicador ul li a').is(':focus') && 
							 !$('#ccustoindicador-descricao').val() 
						) {
							fechaListaCCustoIndicador(input_group);
						}

					}, 200);
				}

			});

			//Campo de filtro
			$('#ccustoindicador-descricao').on({

				keydown: function(e) {

					//Eventos ap�s a escolha de um item
					if ( $(this).is('[readonly]') ) {

						//Deletar teclando 'Backspace' ou 'Delete'
						if ( (e.keyCode === 8) || (e.keyCode === 46) ) {
							$('.btn-apagar-filtro-ccustoindicador').click();
						}
					}
					else {

						//Pesquisar com 'Enter'
						if (e.keyCode === 13) {
							filtrarCCustoIndicador();
						}
					}
				},

				focusout: function() {

					//verificar quando o campo deve ser zerado
					if(tempo_focus)
						clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if ( !$('input[name="_ccustoindicador_id"]').val() && 
							 $('.lista-ccustoindicador').is(':empty') &&
							 !$('.btn-filtro-ccustoindicador').is(':focus') 
						) {
							$('#ccustoindicador-descricao').val('');
						}

					}, 200);
				}

			});
		
		}
		
		iniciarFiltroCCustoIndicador();
	}
//# sourceMappingURL=_20030indicador-filtro.js.map
