//	{ /** Pesquisar Centro de Custo */
//		
//		var filtro;
//		var campo_ccusto;
//		var btn_filtro_ccusto;
//		var input_group;
//		var itens_ccusto;
//		var _ccusto_id;
//		var tempo_focus;
//		var ccusto_selecionado = false;
//
//		/**
//		 * Filtrar objeto.
//		 */
//		function filtrarCCusto() {
//
//			campo_ccusto		= $('#ccusto-descricao');	//campo
//			btn_filtro_ccusto	= $('.btn-filtro-ccusto');
//
//			input_group			= $(campo_ccusto).parent('.input-group');	//input-group
//			_ccusto_id			= $('input[name="_ccusto_id"]');
//
//			filtro = campo_ccusto.val();
//
//			//esvazia campo hidden caso algum item já tenha sido escolhido antes
//			if(ccusto_selecionado) 
//				$(_ccusto_id).val('');
//
//			if( !filtro ) {
//				fechaListaCCusto( input_group );
//				$(_ccusto_id).val('');
//				//return false;
//			}
//            
//            var url_action = "/_20030/pesquisaCCusto2";
//            var dados = {'filtro': filtro};
//            var type = "POST";
//            
//            function success(data){
//              abreListaCCusto( input_group );
//					
//					$('.lista-ccusto')
//						.html(data);
//
//					//existem dados cadastrados
//					if( data.indexOf('nao-cadastrado') === -1 ) {
//
//						itens_ccusto = $('ul.ccusto li a');
//                        
//						selecItemListaCCusto( $(itens_ccusto), campo_ccusto );
//
//						$(itens_ccusto)
//							.focusout(function() {
//
//								if(tempo_focus) 
//									clearTimeout(tempo_focus);
//
//								tempo_focus = setTimeout(function() {
//
//									if( !$(itens_ccusto).is(':focus') && 
//										!$(campo_ccusto).is(':focus') && 
//										!$(btn_filtro_ccusto).is(':focus') 
//									) {
//										$(campo_ccusto).val('');
//										fechaListaCCusto( input_group );
//									}
//
//								}, 200);
//
//							});
//
//						$(campo_ccusto)
//							.focusout(function() {
//
//								if(tempo_focus) 
//									clearTimeout(tempo_focus);
//
//								tempo_focus = setTimeout(function() {
//
//									if( !$(itens_ccusto).is(':focus') &&
//										!$(_ccusto_id).val() && 
//										!$(btn_filtro_ccusto).is(':focus') 
//									) {
//										$(campo_ccusto).val('');
//										fechaListaCCusto( input_group );
//									}
//
//								}, 200);
//
//							});
//					}
//					else {
//						$(_ccusto_id).val('');
//
//						$(campo_ccusto)
//							.focusout(function() {
//								if( $('.lista-ccusto').children().children().hasClass('nao-cadastrado') ) {
//									$(campo_ccusto).val('');
//									fechaListaCCusto( input_group );
//								}
//							});
//					}  
//            }
//
//            execAjax2(type,url_action,dados,success,false,btn_filtro_ccusto,false);
//		}
//
//		//Abre resultado da filtragem
//		function abreListaCCusto(ccusto) {
//			
//			$(ccusto)
//				.next('.lista-ccusto-container')
//				.addClass('ativo');
//		
//			$(btn_filtro_ccusto)
//				.attr('tabindex', '-1');
//		}
//
//		//Fecha resultado da filtragem
//		function fechaListaCCusto(ccusto) {
//			
//			$(ccusto)
//				.next('.lista-ccusto-container')
//				.removeClass('ativo')
//				.children('.lista-ccusto')
//				.empty();
//		
//			$(btn_filtro_ccusto)
//				.removeAttr('tabindex');
//		}
//
//		/**
//		 * Preencher campos de acordo com o item selecionado.
//		 * 
//		 * @param {object} itens
//		 * @param {object} campo
//		 * @returns {undefined}
//		 */
//		function selecItemListaCCusto(itens, campo) {
//
//			$(itens).click(function(e) {
//				
//				e.preventDefault();
//				
//				$(campo)
//					.val( $(this).text() )
//					.focus();
//			
//				selecionadoCCusto();
//			
//				$(_ccusto_id)
//					.val( $(this).nextAll('.ccusto-id').val() )
//					.trigger('change');
//			
//				fechaListaCCusto( input_group );
//				ccusto_selecionado = true;
//			});
//
//		}
//		
//		/**
//		 * Ações que devem acontecer após o item ser selecionado.
//		 */
//		function selecionadoCCusto() {
//			
//			$('#ccusto-descricao')
//				.attr('readonly', true);
//
//			$('.btn-filtro-ccusto')
//				.hide();
//
//			$('.btn-apagar-filtro-ccusto')
//				.show()
//				.click(function() {
//
//					$(this)
//						.siblings('input')
//						.removeAttr('readonly');
//
//					$(this)
//						.hide()
//						.prev('button')
//						.show();
//
//					$('#ccusto-descricao')
//						.val('')
//						.focus();
//
//					$('input[name="_ccusto_id"]')
//						.val('');
//
//				});
//		}
//		
//		
//		/**
//		 * Eventos para o filtro de CCusto.
//		 */
//		function iniciarFiltroCCusto() {
//			
//			//Se o item j� estiver selecionado (tela de update), efetua as devidas a��es.
//			if ( $('input[name="_ccusto_id"]').val() !== '' )
//				selecionadoCCusto();
//
//			//Bot�o de filtrar
//			$('.btn-filtro-ccusto').on({
//
//				click: function() {
//
//					if ( !$(_ccusto_id).val() )
//						filtrarCCusto();
//				},
//
//				focusout: function() {
//
//					if(tempo_focus) clearTimeout(tempo_focus);
//
//					tempo_focus = setTimeout(function() {
//
//						if ( !$('input[name="_ccusto_id"]').val() && 
//							 $('.lista-ccusto').is(':empty') 
//						) {
//						   $('#ccusto-descricao').val('');
//						}
//
//						if ( !$('input[name="_ccusto_id"]').val() && 
//							 !$('.lista-ccusto ul li a').is(':focus') && 
//							  $('#ccusto-descricao').val() 
//						) {
//							$('#ccusto-descricao').val('');
//							fechaListaCCusto(input_group);
//						}
//						
//						if ( !$('input[name="_ccusto_id"]').val() && 
//							 !$('.lista-ccusto ul li a').is(':focus') && 
//							 !$('#ccusto-descricao').val() 
//						) {
//							fechaListaCCusto(input_group);
//						}
//
//					}, 200);
//				}
//
//			});
//
//			//Campo de filtro
//			$('#ccusto-descricao').on({
//
//				keydown: function(e) {
//
//					//Eventos ap�s a escolha de um item
//					if ( $(this).is('[readonly]') ) {
//
//						//Deletar teclando 'Backspace' ou 'Delete'
//						if ( (e.keyCode === 8) || (e.keyCode === 46) ) {
//							$('.btn-apagar-filtro-ccusto').click();
//						}
//					}
//					else {
//
//						//Pesquisar com 'Enter'
//						if (e.keyCode === 13) {
//							filtrarCCusto();
//						}
//					}
//				},
//
//				focusout: function() {
//
//					//verificar quando o campo deve ser zerado
//					if(tempo_focus)
//						clearTimeout(tempo_focus);
//
//					tempo_focus = setTimeout(function() {
//
//						if ( !$('input[name="_ccusto_id"]').val() && 
//							 $('.lista-ccusto').is(':empty') &&
//							 !$('.btn-filtro-ccusto').is(':focus') 
//						) {
//							$('#ccusto-descricao').val('');
//						}
//
//					}, 200);
//				}
//
//			});
//		
//		}
//		
//		iniciarFiltroCCusto();
//	}