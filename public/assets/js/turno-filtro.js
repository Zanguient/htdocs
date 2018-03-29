	{ 
        /** Pesquisar Centro de Custo */
		
		var filtro;
		var campo_turno;
		var btn_filtro_turno;
		var input_group;
		var itens_turno;
		var _turno_id;
		var tempo_focus;
		var turno_selecionado = false;



		/**
		 * Filtrar objeto.
		 */
		function filtrarTurno() {

			campo_turno		= $('#turno-descricao');	//campo
			btn_filtro_turno	= $('.btn-filtro-turno');

			input_group			= $(campo_turno).parent('.input-group');	//input-group
			_turno_id			= $('input[name="_turno_id"]');

			filtro = campo_turno.val();

			//esvazia campo hidden caso algum item já tenha sido escolhido antes
			if(turno_selecionado) 
				$(_turno_id).val('');

			if( !filtro ) {
				fechaListaTurno( input_group );
				$(_turno_id).val('');
				//return false;
			}
            
            var url_action = "/turno/filtrar";
            var dados = {'filtro': filtro};
            var type = "POST";
            
            function success(data){
              abreListaTurno( input_group );
					
					$('.lista-turno')
						.html(data);

					//existem dados cadastrados
					if( data.indexOf('nao-cadastrado') === -1 ) {

						itens_turno = $('ul.turno li a');
                        
						selecItemListaTurno( $(itens_turno), campo_turno );

						$(itens_turno)
							.focusout(function() {

								if(tempo_focus) 
									clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {

									if( !$(itens_turno).is(':focus') && 
										!$(campo_turno).is(':focus') && 
										!$(btn_filtro_turno).is(':focus') 
									) {
										$(campo_turno).val('');
										fechaListaTurno( input_group );
									}

								}, 200);

							});

						$(campo_turno)
							.focusout(function() {

								if(tempo_focus) 
									clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {

									if( !$(itens_turno).is(':focus') &&
										!$(_turno_id).val() && 
										!$(btn_filtro_turno).is(':focus') 
									) {
										$(campo_turno).val('');
										fechaListaTurno( input_group );
									}

								}, 200);

							});
					}
					else {
						$(_turno_id).val('');

						$(campo_turno)
							.focusout(function() {
								if( $('.lista-turno').children().children().hasClass('nao-cadastrado') ) {
									$(campo_turno).val('');
									fechaListaTurno( input_group );
								}
							});
					}  
            }
            

            execAjax2(type,url_action,dados,success,false,btn_filtro_turno,false);
		}

		//Abre resultado da filtragem
		function abreListaTurno(turno) {
			
			$(turno)
				.next('.lista-turno-container')
				.addClass('ativo');
		
			$(btn_filtro_turno)
				.attr('tabindex', '-1');
		}

		//Fecha resultado da filtragem
		function fechaListaTurno(turno) {
			
			$(turno)
				.next('.lista-turno-container')
				.removeClass('ativo')
				.children('.lista-turno')
				.empty();
		
			$(btn_filtro_turno)
				.removeAttr('tabindex');
		}

		/**
		 * Preencher campos de acordo com o item selecionado.
		 * 
		 * @param {object} itens
		 * @param {object} campo
		 * @returns {undefined}
		 */
		function selecItemListaTurno(itens, campo) {

			$(itens).click(function(e) {
				
				e.preventDefault();
				
				$(campo)
					.val( $(this).text() )
					.focus();
			
				selecionadoTurno();
			
				$(_turno_id)
					.val( $(this).nextAll('.turno-id').val() )
					.trigger('change');
            
                $( ".turno-descricao" ).trigger( "change" );
                
				fechaListaTurno( input_group );
				turno_selecionado = true;
			});

		}
		
		/**
		 * Ações que devem acontecer após o item ser selecionado.
		 */
		function selecionadoTurno() {
			
			$('#turno-descricao')
				.attr('readonly', true);

			$('.btn-filtro-turno')
				.hide();

			$('.btn-apagar-filtro-turno')
				.show()
				.click(function() {

					$(this)
						.siblings('input')
						.removeAttr('readonly');

					$(this)
						.hide()
						.prev('button')
						.show();

					$('#turno-descricao')
						.val('')
						.focus();

					$('input[name="_turno_id"]')
						.val('');

				});
		}
		
		
		/**
		 * Eventos para o filtro de Turno.
		 */
		function iniciarFiltroTurno() {
			
			//Se o item j? estiver selecionado (tela de update), efetua as devidas a??es.
			if ( $('input[name="_turno_id"]').val() !== '' )
				selecionadoTurno();

			//Bot?o de filtrar
			$('.btn-filtro-turno').on({

				click: function() {

					if ( !$(_turno_id).val() )
						filtrarTurno();
				},

				focusout: function() {

					if(tempo_focus) clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if ( !$('input[name="_turno_id"]').val() && 
							 $('.lista-turno').is(':empty') 
						) {
						   $('#turno-descricao').val('');
						}

						if ( !$('input[name="_turno_id"]').val() && 
							 !$('.lista-turno ul li a').is(':focus') && 
							  $('#turno-descricao').val() 
						) {
							$('#turno-descricao').val('');
							fechaListaTurno(input_group);
						}
						
						if ( !$('input[name="_turno_id"]').val() && 
							 !$('.lista-turno ul li a').is(':focus') && 
							 !$('#turno-descricao').val() 
						) {
							fechaListaTurno(input_group);
						}

					}, 200);
				}

			});

			//Campo de filtro
			$('#turno-descricao').on({

				keydown: function(e) {

					//Eventos ap?s a escolha de um item
					if ( $(this).is('[readonly]') ) {

						//Deletar teclando 'Backspace' ou 'Delete'
						if ( (e.keyCode === 8) || (e.keyCode === 46) ) {
							$('.btn-apagar-filtro-turno').click();
						}
					}
					else {

						//Pesquisar com 'Enter'
						if (e.keyCode === 13) {
							filtrarTurno();
						}
					}
				},

				focusout: function() {

					//verificar quando o campo deve ser zerado
					if(tempo_focus)
						clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if ( !$('input[name="_turno_id"]').val() && 
							 $('.lista-turno').is(':empty') &&
							 !$('.btn-filtro-turno').is(':focus') 
						) {
							$('#turno-descricao').val('');
						}

					}, 200);
				}

			});
		
		}
		
		iniciarFiltroTurno();
	}
//# sourceMappingURL=turno-filtro.js.map
