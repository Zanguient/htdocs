/**
 * Script com funções de:
 * - Pesquisar produto 
 * - Adicionar campos para mais de um produto
 * */

(function($) {
	
	{ //Enviar Arquivo
		$(':file').change(function() {
			enviarArquivo($(this), 'RequisicaoDeCompra');	//função em 'arquivo.js'
		});
	}
	
	$(document).on('click', '.tamanho-produto', function(){
		$('.div-tamanho-produto').show();
	});

    $(document).on('click', '.settamanho', function(){

        var valor = $(this).attr('tamanho');
		var posicao = $(this).attr('posicao');

        $('.setar-tamanho')
			.val(valor)
			.next('.tam-posicao')
			.val(posicao);
	
        $('.setar-tamanho')
			.removeClass('setar-tamanho');

    });

    $(document).on('click', '.sett', function(){
        var but = $(this).parent().parent();
        but.children('.tamanho-produto').addClass('setar-tamanho');
        var ProdID = but.parent().parent().find('input[name="_produto_id[]"]').val();

        var fdata = new FormData();
        fdata.append('ProdID', ProdID);
		
		//ajax
		var type			= 'POST',
			url				= '/_13010/listarTamanho',
		    data			= fdata,
			cache			= false,
			contentType		= false,
			processData		= false,
			success			= function(data) {

                if (data){
					
                    var desc = data[0][0][0];
                    var total_tamanhos = data[0][0][1];
					var posicao;
                    var Tan;
                    var abilitado;

                    for (i = 1; i <= 20; i++) {

                    	tamanho   = data[0][i][0];
						abilitado = data[0][i][1];
						posicao   = data[0][i][2];

                        if (i < 10){
                            Tan = '.T0'+i;
                        }else{
                            Tan = '.T'+i;
                        }

                        // if ((data[0][i][1] === 0) || (total_tamanhos <= i)){
                        if ((data[0][i][0] === '') || (total_tamanhos+1 <= i)){
                            $(Tan).prop( "disabled", true );
                        }else{
                            $(Tan).prop( "disabled", false );
                        }
                        
                        tamanho = (tamanho == '') ? '00' : tamanho;

						if(abilitado == 0){
							$(Tan).prop( "disabled", true );
						}

                        $(Tan).attr("tamanho",tamanho);
                        $(Tan).attr("abilitado",abilitado);
						$(Tan).attr("posicao",posicao);
                        $(Tan).find(Tan).html(tamanho);
                        //$('.TGRADE').html('GRADE - ('+desc+')');


                    }

                    for (i = total_tamanhos+1; i <= 20; i++) {

                        if (i < 10){
                            Tan = '.T0'+i;
                        }else{
                            Tan = '.T'+i;
                        }

                        $(Tan).prop( "disabled", true );

                        tamanho = '00';
						posicao = '00';

                        $(Tan).attr("tamanho",tamanho);
						$(Tan).attr("posicao",posicao);
                        $(Tan).find(Tan).html(tamanho);

                    }

                }else{

                    for (i = 1; i <= 20; i++) {

                            if (i < 10){
                                Tan = '.T0'+i;
                            }else{
                                Tan = '.T'+i;
                            }

                            $(Tan).prop( "disabled", true );

                            tamanho = '00';

                            $(Tan).attr("tamanho",tamanho);
                            $(Tan).find(Tan).html(tamanho);

                    }
                }

		    },
            error			= function(e) {

                for (i = 1; i <= 20; i++) {

                        if (i < 10){
                            Tan = '.T0'+i;
                        }else{
                            Tan = '.T'+i;
                        }

                        $(Tan).prop( "disabled", true );

                        tamanho = '00';

                        $(Tan).attr("tamanho",tamanho);
                        $(Tan).find(Tan).html(tamanho);

                }
		    }
		;
		
		execAjax1(type, url, data, success, error, null, false, null, cache, contentType, processData);

    });
	
	{ /** Pesquisar Centro de Custo */
		
		var filtro;
		var campo_ccusto;
		var btn_filtro_ccusto;
		var input_group;
		var itens_ccusto;
		var _ccusto_id;
		var tempo_focus;
		var ccusto_selecionado = false;

		/**
		 * Filtrar objeto.
		 */
		function filtrarCCusto() {

			campo_ccusto		= $('#ccusto-descricao');	//campo
			btn_filtro_ccusto	= $('.btn-filtro-ccusto');

			input_group			= $(campo_ccusto).parent('.input-group');	//input-group
			_ccusto_id			= $('input[name="_ccusto_id"]');

			filtro = campo_ccusto.val();

			//esvazia campo hidden caso algum item já tenha sido escolhido antes
			if(ccusto_selecionado) 
				$(_ccusto_id).val('');

			if( !filtro ) {
				fechaListaCCusto( input_group );
				$(_ccusto_id).val('');
				//return false;
			}
			
			//ajax
			var type	= 'POST',
				url		= '/_20030/pesquisaCCusto',
				data	= {'filtro': filtro},
				success	= function(data) {

					abreListaCCusto( input_group );
					
					$('.lista-ccusto')
						.html(data);

					//existem dados cadastrados
					if( data.indexOf('nao-cadastrado') === -1 ) {

						itens_ccusto = $('ul.ccusto li a');

						selecItemListaCCusto( $(itens_ccusto), campo_ccusto );

						$(itens_ccusto)
							.focusout(function() {

								if(tempo_focus) 
									clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {

									if( !$(itens_ccusto).is(':focus') && 
										!$(campo_ccusto).is(':focus') && 
										!$(btn_filtro_ccusto).is(':focus') 
									) {
										$(campo_ccusto).val('');
										fechaListaCCusto( input_group );
									}

								}, 200);

							});

						$(campo_ccusto)
							.focusout(function() {

								if(tempo_focus) 
									clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {

									if( !$(itens_ccusto).is(':focus') &&
										!$(_ccusto_id).val() && 
										!$(btn_filtro_ccusto).is(':focus') 
									) {
										$(campo_ccusto).val('');
										fechaListaCCusto( input_group );
									}

								}, 200);

							});
					}
					else {
						$(_ccusto_id).val('');

						$(campo_ccusto)
							.focusout(function() {
								if( $('.lista-ccusto').children().children().hasClass('nao-cadastrado') ) {
									$(campo_ccusto).val('');
									fechaListaCCusto( input_group );
								}
							});
					}

				}
			;				

			execAjax2(type, url, data, success, null, btn_filtro_ccusto);

		}

		//Abre resultado da filtragem
		function abreListaCCusto(ccusto) {
			
			$(ccusto)
				.next('.lista-ccusto-container')
				.addClass('ativo');
		
			$(btn_filtro_ccusto)
				.attr('tabindex', '-1');
		}

		//Fecha resultado da filtragem
		function fechaListaCCusto(ccusto) {
			
			$(ccusto)
				.next('.lista-ccusto-container')
				.removeClass('ativo')
				.children('.lista-ccusto')
				.empty();
		
			$(btn_filtro_ccusto)
				.removeAttr('tabindex');
		}

		/**
		 * Preencher campos de acordo com o item selecionado.
		 * 
		 * @param {object} itens
		 * @param {object} campo
		 * @returns {undefined}
		 */
		function selecItemListaCCusto(itens, campo) {

			$(itens).click(function(e) {
				
				e.preventDefault();
				
				$(campo)
					.val( $(this).text() )
					.focus();
			
				selecionadoCCusto();
			
				$(_ccusto_id)
					.val( $(this).nextAll('.ccusto-id').val() )
					.trigger('change');
			
				fechaListaCCusto( input_group );
				ccusto_selecionado = true;
			});

		}
		
		/**
		 * Ações que devem acontecer após o item ser selecionado.
		 */
		function selecionadoCCusto() {
			
			$('#ccusto-descricao')
				.attr('readonly', true);

			$('.btn-filtro-ccusto')
				.hide();

			$('.btn-apagar-filtro-ccusto')
				.show()
				.click(function() {

					$(this)
						.siblings('input')
						.removeAttr('readonly');

					$(this)
						.hide()
						.prev('button')
						.show();

					$('#ccusto-descricao')
						.val('')
						.focus();

					$('input[name="_ccusto_id"]')
						.val('');

				});
		}
		
		
		/**
		 * Eventos para o filtro de CCusto.
		 */
		function iniciarFiltroCCusto() {
			
			//Se o item já estiver selecionado (tela de update), efetua as devidas ações.
			if ( $('input[name="_ccusto_id"]').val() !== '' )
				selecionadoCCusto();

			//Botão de filtrar
			$('.btn-filtro-ccusto').on({

				click: function() {

					if ( !$(_ccusto_id).val() )
						filtrarCCusto();
				},

				focusout: function() {

					if(tempo_focus) clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if ( !$('input[name="_ccusto_id"]').val() && 
							 $('.lista-ccusto').is(':empty') 
						) {
						   $('#ccusto-descricao').val('');
						}

						if ( !$('input[name="_ccusto_id"]').val() && 
							 !$('.lista-ccusto ul li a').is(':focus') && 
							  $('#ccusto-descricao').val() 
						) {
							$('#ccusto-descricao').val('');
							fechaListaCCusto(input_group);
						}
						
						if ( !$('input[name="_ccusto_id"]').val() && 
							 !$('.lista-ccusto ul li a').is(':focus') && 
							 !$('#ccusto-descricao').val() 
						) {
							fechaListaCCusto(input_group);
						}

					}, 200);
				}

			});

			//Campo de filtro
			$('#ccusto-descricao').on({

				keydown: function(e) {

					//Eventos após a escolha de um item
					if ( $(this).is('[readonly]') ) {

						//Deletar teclando 'Backspace' ou 'Delete'
						if ( (e.keyCode === 8) || (e.keyCode === 46) ) {
							$('.btn-apagar-filtro-ccusto').click();
						}
					}
					else {

						//Pesquisar com 'Enter'
						if (e.keyCode === 13) {
							filtrarCCusto();
						}
					}
				},

				focusout: function() {

					//verificar quando o campo deve ser zerado
					if(tempo_focus)
						clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if ( !$('input[name="_ccusto_id"]').val() && 
							 $('.lista-ccusto').is(':empty') &&
							 !$('.btn-filtro-ccusto').is(':focus') 
						) {
							$('#ccusto-descricao').val('');
						}

					}, 200);
				}

			});
		
		}
		
		iniciarFiltroCCusto();
	}


	{ /** Pesquisar Gestor */

		var filtro;
		var campo_gestor;
		var btn_filtro_gestor;
		var input_group;
		var itens_gestor;
		var _gestor_id;
		var _gestor_email;
		var tempo_focus;
		var gestor_selecionado = false;

		/**
		 * Filtrar objeto.
		 */
		function filtrarGestor() {

			campo_gestor		= $('#gestor-descricao');	//campo
			btn_filtro_gestor	= $('.btn-filtro-gestor');

			input_group			= $(campo_gestor).parent('.input-group');	//input-group
			_gestor_id			= $('input[name="_gestor_id"]');
			_gestor_email		= $('input[name="_gestor_email"]');

			filtro				= campo_gestor.val();

			//esvazia campo hidden caso algum item já tenha sido escolhido antes
			if(gestor_selecionado) 
				$(_gestor_id).val('');

			if( !filtro ) {
				fechaListaGestor( input_group );
				$(_gestor_id).val('');
				//return false;
			}
			
			//ajax
			var type	= 'POST',
				url		= '/_13010/pesquisaGestor',
				data	= {'filtro': filtro},
				success = function(data) {

					abreListaGestor( input_group ); 
					$('.lista-gestores').html(data);

					//se existem dados cadastrados
					if( data.indexOf('nao-cadastrado') === -1 ) {

						itens_gestor = $('ul.gestores li a');
						
						selecItemListaGestor( $(itens_gestor), campo_gestor );

						$(itens_gestor)
							.focusout(function() {

								if(tempo_focus) clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {

									if( !$(itens_gestor).is(':focus') && 
										!$(campo_gestor).is(':focus') && 
										!$(btn_filtro_gestor).is(':focus')
									) {
										$(campo_gestor).val('');
										fechaListaGestor( input_group );
									}
								}, 200);

							});

						$(campo_gestor)
							.focusout(function() { 

								if(tempo_focus) clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {
									
									if( !$(itens_gestor).is(':focus') && 
										!$(_gestor_id).val() && 
										!$(btn_filtro_gestor).is(':focus')
									) {
										$(campo_gestor).val('');
										fechaListaGestor( input_group );
									}
									
								}, 200);

							});
					}

					else {
						$(_gestor_id).val('');

						$(campo_gestor)
							.focusout(function() { 
								if( $('.lista-gestores').children().children().hasClass('nao-cadastrado') ) {
									$(campo_gestor).val('');
									fechaListaGestor( input_group );
								}
							});
					}

				}
			;
				
			execAjax2(type, url, data, success, null, btn_filtro_gestor);

		}

		//Abre resultado da filtragem
		function abreListaGestor(gestor) {
			
			$(gestor)
				.next('.lista-gestores-container')
				.addClass('ativo');
		
			$(btn_filtro_gestor)
				.attr('tabindex', '-1');
		
		}
		
		//Fecha resultado da filtragem
		function fechaListaGestor(gestor) {
			
			$(gestor)
				.next('.lista-gestores-container')
				.removeClass('ativo')
				.children('.lista-gestores')
				.empty();
				
			$(btn_filtro_gestor)
				.removeAttr('tabindex');
		
		}
		
		//Preencher campos de acordo com o item selecionado
		function selecItemListaGestor(itens, campo) {
			
			$(itens).click(function(e) {
				
				e.preventDefault();
				
				$(campo)
					.val( $(this).text() )
					.focus();
				
				selecionadoGestor();
				
				$(_gestor_id)
					.val( $(this).nextAll('.id').val() );
			
				$(_gestor_email)
					.val( $(this).nextAll('.email').val() );
			
				fechaListaGestor( input_group );
				gestor_selecionado = true;
			});
			
		}
		
		/**
		 * Ações que devem acontecer após o item ser selecionado.
		 * 
		 * @returns {undefined}
		 */
		function selecionadoGestor() {
			
			$('#gestor-descricao')
				.attr('readonly', true);


			$('.btn-filtro-gestor')
				.hide();

			$('.btn-apagar-filtro-gestor')
				.show()
				.click(function() {

					$(this)
						.siblings('input')
						.removeAttr('readonly');

					$(this)
						.hide()
						.prev('button')
						.show();

					$('#gestor-descricao')
						.val('')
						.focus();

					$('input[name="_gestor_id"]')
						.val('');
				
					$('input[name="_gestor_email"]')
						.val('');

				});
		}
		
		/**
		 * Eventos para o filtro de Gestor.
		 */
		function iniciarFiltroGestor() {
		
			//Se o item já estiver selecionado (tela de update), efetua as devidas ações.
			if ( $('input[name="_gestor_id"]').val() !== '' )
				selecionadoGestor();

			//Botão de filtrar
			$('.btn-filtro-gestor').on({

				click: function() {
					if ( !$(_gestor_id).val() )
						filtrarGestor();
				},

				focusout: function() {

					if(tempo_focus) clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if ( !$('input[name="_gestor_id"]').val() && $('.lista-gestor').is(':empty') ) {
						   $('#gestor-descricao').val('');
						}

						if ( !$('input[name="_gestor_id"]').val() && 
							 !$('.lista-gestores ul li a').is(':focus') && 
							  $('#gestor-descricao').val() 
						) {
							$('#gestor-descricao').val('');
							fechaListaGestor(input_group);
						}
						
						if ( !$('input[name="_gestor_id"]').val() && 
							 !$('.lista-gestores ul li a').is(':focus') && 
							 !$('#gestor-descricao').val() 
						) {
							fechaListaGestor(input_group);
						}

					}, 200);
				}

			});

			//Campo de filtro
			$('#gestor-descricao').on({

				keydown: function(e) {

					//Eventos após a escolha de um item
					if ( $(this).is('[readonly]') ) {

						//Deletar teclando 'Backspace' ou 'Delete'
						if ( (e.keyCode === 8) || (e.keyCode === 46) ) {
							$('.btn-apagar-filtro-gestor').click();
						}
					}
					else {

						//Pesquisar com 'Enter'
						if (e.keyCode === 13) {
							filtrarGestor();
						}
					}
				},

				focusout: function() {

					//verificar quando o campo deve ser zerado
					if(tempo_focus) clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if ( !$('input[name="_gestor_id"]').val() && 
							 $('.lista-gestores').is(':empty') &&
							 !$('.btn-filtro-gestor').is(':focus') 
						) {
							$('#gestor-descricao').val('');
						}

					}, 200);
				}

			});
			
		}
		
		iniciarFiltroGestor();
		
	}


	{ /** Pesquisar produto */
		
		var filtro;
		var campo_produto;
		var btn_filtro_produto;
		var btn_apagar_filtro_produto;
		var campo_um; 
		var campo_tam;
		var _produto_id;
		var _produto_desc;
		var input_group;
		var itens_produto;
		var tempo_focus;
		var produto_selecionado = false;
		//var produto_cadastrado = true;
		
		/**
		 * Filtrar objeto.
		 * 
		 * @param {object} campo
		 */
		function filtrarProduto(campo) {
			
			campo_produto				= campo;
			btn_filtro_produto			= campo_produto.nextAll('.btn-filtro-produto');
			btn_apagar_filtro_produto	= campo_produto.nextAll('.btn-apagar-filtro-produto');

			input_group			= campo_produto.parent('.input-group');
			campo_um			= input_group.parent('.form-group').next('.form-group').children('input[name="um[]"]');
            campo_tam			= input_group.parent('.form-group').next('.form-group').next('.form-group').find('input[name="tamanho[]"]');
			_produto_id			= input_group.nextAll('input[name="_produto_id[]"]');
			_produto_desc		= input_group.nextAll('input[name="_produto_descricao[]"]');

			filtro				= campo_produto.val();

			//esvazia campo hidden caso algum item já tenha sido escolhido antes
			if(produto_selecionado)
				$(_produto_id).val('');

			if( !filtro ) {
				fechaLista( input_group );
				$(_produto_id).val('');
				$(campo_um).removeAttr('readonly').val('');
				//return false;
			}
			
			//ajax
			var type	= "POST",
				url		= "/_13010/pesquisaProduto",
				data	= {'filtro': filtro},
				success = function(data) {

					abreLista( input_group ); 
					$('.lista-produtos').html(data);

					//se existem dados cadastrados
					if( data.indexOf('nao-cadastrado') === -1 ) {

						itens_produto = $('ul.produtos li a');
						
						selecItemLista( itens_produto, campo_produto, campo_um, _produto_id, _produto_desc, campo_tam );

						$(itens_produto)
							.focusout(function() {

								if(tempo_focus) clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {

									if( !$(itens_produto).is(':focus') && 
										!$(campo_produto).is(':focus') &&
										!$(btn_filtro_produto).is(':focus')
									) {
										$(campo_produto).val('');
										fechaLista( input_group );
									}

								}, 200);

							});

						$(campo_produto)
							.focusout(function() { 

								if(tempo_focus) clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {
									
									if( !$(itens_produto).is(':focus') && 
										//!$('.lista-produtos .sim').is(':focus') && 
										!$(_produto_id).val() &&
										!$(btn_filtro_produto).is(':focus')
									) {
										$(campo_produto).val('');
										fechaLista( input_group );
									}
									
								}, 200);

							});
					}

					else {

						$(campo_um)
							.removeAttr('readonly')
							.val('');

						$(campo_produto)
							.focusout(function() {

								if(tempo_focus) clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {

									fechaLista( input_group );
									$(campo_produto).val('');
									
								}, 200);
							});

					
						/*
						habilitaCliqueSimNao( $('.lista-produtos .sim'), $('.lista-produtos .nao') );

						$(campo_produto)
							.focusout(function() {

								if(tempo_focus) clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {
									
									if( $('.lista-produtos').children().children().hasClass('nao-cadastrado') && 
										!$('.lista-produtos .sim').is(':focus')
									) {
										fechaLista( input_group );
									}
									
								}, 200);

							});
						*/
					}

				}
			;
			
			execAjax2(type, url, data, success, null, btn_filtro_produto);

		}
		
		//Abre resultado da filtragem
		function abreLista(produto) {
			
			$(produto)
				.next('.lista-produtos-container')
				.addClass('ativo');
				
			$(btn_filtro_produto)
				.attr('tabindex', '-1');
		
		}
		
		//Fecha resultado da filtragem
		function fechaLista(produto) {
			
			$(produto)
				.next('.lista-produtos-container')
				.removeClass('ativo')
				.children('.lista-produtos')
				.empty();
		
			$(btn_filtro_produto)
				.removeAttr('tabindex');
		
		}
		
		//Preencher campos de acordo com o item selecionado
		function selecItemLista(itens, campo_produto, campo_um, _produto_id, _produto_desc, campo_tam) {
			
			$(itens)
				.click(function(e) {
				
					e.preventDefault();

					var desc = $(this).nextAll('.prod-descricao-id').val();

					$(campo_produto)
						.val( desc )
						.focus();
				
					$(campo_um)
						.val( $(this).nextAll('.um').val() );
					
					$(_produto_id)
						.val( $(this).nextAll('.id').val() );

					$(_produto_desc)
						.val( $(this).nextAll('.descricao').val() );

					$(campo_tam)
						.val('');

					selecionadoProduto(campo_produto, campo_um, btn_filtro_produto, btn_apagar_filtro_produto);

					fechaLista( input_group );
					produto_selecionado = true;

				});
			
		}
		
		/**
		 * Ações que devem acontecer após o item ser selecionado.
		 */
		function selecionadoProduto(campo_produto, campo_um, btn_filtro_produto, btn_apagar_filtro_produto) {
			
			$(campo_produto)
				.attr('readonly', true);
		
			$(campo_um)
				.attr('readonly', true);

			$(btn_filtro_produto)
				.hide();

			$(btn_apagar_filtro_produto)
				.show()
				.click(function() {

					$(this)
						.siblings('input')
						.removeAttr('readonly');

					$(this)
						.hide()
						.prev('button')
						.show();
				
					$(this)
						.parent()
						.parent('.form-group')
						.next()
						.find(campo_um)
						.removeAttr('readonly')
						.val('');
				
					$(this)
						.parent()
						.siblings('input[name="_produto_id[]"]')
						.val('');
				
					$(this)
						.parent()
						.siblings('input[name="_produto_descricao[]"]')
						.val('');

					$(this)
						.siblings('.produto-descricao')
						.val('')
						.focus();

				});
		}
		
		//Habilitar clique de Sim e Não quando o produto não estiver cadastrado
		/*
		function habilitaCliqueSimNao(btn_sim, btn_nao) {
			
			$(btn_sim).click(function() {
				fechaLista( input_group );
				$(campo_um).removeAttr('readonly').val('');
				$(_produto_id).val('');
				$(_produto_desc).val( $(campo_produto).val() );
				$(campo_produto).focus();
				produto_cadastrado = false;
			});
			
			$(btn_nao).click(function() {
				fechaLista( input_group );
				$(_produto_id).val('');
				$(campo_um).removeAttr('readonly').val('');
				$(campo_produto).val('').focus();
				produto_cadastrado = true;
			});
			
			//Ativar clique com enter
			$(btn_sim).bind('keydown', 'return', function() {
				$(this).click();
			});
			$(btn_nao).bind('keydown', 'return', function() {
				$(this).click();
			});
			
		}
		*/
		
		
		/**
		 * Eventos para o filtro de Produto.
		 */
		function iniciarFiltroProduto() {
		
			//Se o item já estiver selecionado (tela de update), efetua as devidas ações.
			$('input[name="_produto_id[]"]').each(function() {
				
				if ( $(this).val() !== '' ) {
			
					selecionadoProduto(
						$(this).siblings('.input-group').children('.produto-descricao'),
						$(this).parent('.form-group').next().children('input[name="um[]"]'),
						$(this).siblings('.input-group').children('.btn-filtro-produto'),
						$(this).siblings('.input-group').children('.btn-apagar-filtro-produto')
					);
			
				}
				
			});

			//Botão de filtrar
			$('.btn-filtro-produto').on({

				click: function() {
					
					if ( !$(this).parent().nextAll('input[name="_produto_id[]"]').val() )
						filtrarProduto( $(this).prev('input') );
					
				},

				focusout: function() {

					//Só prossegue se o produto estiver cadastrado.
					// if ( produto_cadastrado === false )
					// 	return false;
					
					if(tempo_focus) 
						clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if ( !$('input[name="_produto_id[]"]').val() /*&& $('.lista-produtos').is(':empty')*/ ) {
						   $('.produto-descricao').val('');
						}

						if ( !$('input[name="_produto_id[]"]').val() && 
							 !$('.lista-produtos ul li a').is(':focus') && 
							  $('.produto-descricao').val() 
						) {
							$('.produto-descricao').val('');
							fechaLista(input_group);
						}
						
						if ( !$('input[name="_produto_id[]"]').val() && 
							 !$('.lista-produtos ul li a').is(':focus') && 
							 !$('.produto-descricao').val() 
						) {
							fechaLista(input_group);
						}

					}, 200);
				}

			});

			//Campo de filtro
			$('.produto-descricao').on({

				keydown: function(e) {

					//Eventos após a escolha de um item
					if ( $(this).is('[readonly]') ) {

						//Deletar teclando 'Backspace' ou 'Delete'
						if ( (e.keyCode === 8) || (e.keyCode === 46) ) {
							$(this)
								.nextAll('.btn-apagar-filtro-produto')
								.click();
						}
					}
					else {

						//Pesquisar com 'Enter'
						if (e.keyCode === 13) {
							filtrarProduto( $(this) );
						}
					}
				},

				focusout: function() {

					var campo_prod_desc = $(this);

					//verificar quando o campo deve ser zerado
					if(tempo_focus) clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if ( !$(campo_prod_desc).parent().nextAll('input[name="_produto_id[]"]').val() && 
							 //$(this).parent().nextAll('.lista-produtos-container').children('.lista-produtos').is(':empty') &&
							 !$(campo_prod_desc).nextAll('.btn-filtro-produto').is(':focus') 
						) {
							$(campo_prod_desc).val('');
						}

					}, 200);
				}

			});
			
		}
		
		iniciarFiltroProduto();
		
	}
	
	
	
	
	{ 
		/**
		 * Ao add produto, o botão de apagar filtro do produto deve ser escondido. 
		 */
		$('.add-produto')
			.click(function() {
				
				var btn_add = $(this);
		
				setTimeout(function() {
					
					$(btn_add)
						.prev('.produto-container')
						.children('.produto')
						.last()
						.find('.btn-apagar-filtro-produto')
						.hide();
				
					$(btn_add)
						.prev('.produto-container')
						.children('.produto')
						.last()
						.find('.btn-filtro-produto')
						.show();

					$(btn_add)
						.prev('.produto-container')
						.find('.produto-descricao')
						.focus();
				
				}, 10);

			});
	}
	
	
	
	
	{ /** Pesquisar empresa */
		
		var filtro;
		var campo_empresa;
		var campo_fone;
		var campo_email;
		var campo_contato;
		var btn_filtro_empresa;
		var input_group;
		var itens_empresa;
		var _empresa_id;
		var tempo_focus;
		var empresa_selecionada = false;
		var empresa_cadastrada = true;
		
		/**
		 * Filtrar objeto.
		 */
		function filtrarEmpresa() {
			
			campo_empresa		= $('.empresa-descricao');	//campo			
			campo_fone			= campo_empresa.parent('.input-group').parent('.form-group').next('.form-group').children('.fone');	//campo_fone 
			campo_email			= campo_empresa.parent('.input-group').parent('.form-group').next('.form-group').next('.form-group').children('.email');	//campo_email
			campo_contato		= campo_empresa.parent('.input-group').parent('.form-group').next('.form-group').next('.form-group').next('.form-group').children('.contato');	//campo_contato
			btn_filtro_empresa	= $('.btn-filtro-empresa');
			input_group			= $(campo_empresa).parent('.input-group');	//input-group
			_empresa_id			= $('input[name="_empresa_id"]');
			filtro				= campo_empresa.val();

			//esvazia campo hidden caso algum item já tenha sido escolhido antes
			if(empresa_selecionada)
				$(_empresa_id).val('');

			if( !filtro ) {
				fechaListaEmpresa( input_group );
				$(campo_fone, campo_email, campo_contato).empty();
				$(_empresa_id).val('');
//				return false;
			}
			
			//ajax
			var type	= "POST",
				url		= "/_13060/pesquisa",
				data	= {
					'filtro': filtro, 
					'status': '1', 
					'habilita_fornecedor': '1'
				},
				success = function(data) {

					abreListaEmpresa( input_group ); 

					//se existem dados cadastrados
					if( data.indexOf('nao-cadastrado') === -1 ) {

						$('.lista-empresas').html(data);
						itens_empresa = $('ul.empresas li a');
						
						selecItemListaEmpresa( itens_empresa, campo_empresa, campo_fone, campo_email, campo_contato );

						$(itens_empresa)
							.focusout(function() {

								if(tempo_focus) clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {
									
									if( !$(itens_empresa).is(':focus') && 
										!$(campo_empresa).is(':focus') &&
										!$(btn_filtro_empresa).is(':focus')
									) {
										$(campo_empresa).val('');
										fechaListaEmpresa( input_group );
									}
									
								}, 200);

							});

						$(campo_empresa)
							.focusout(function() { 

								if(tempo_focus) clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {
									
									if( !$(itens_empresa).is(':focus') && 
										!$('.lista-empresas .sim').is(':focus') &&
										!$(btn_filtro_empresa).is(':focus') 
									) {
										fechaListaEmpresa( input_group );
									}
									
								}, 200);

							});
					}

					else {

						$('.lista-empresas')
							.html('<div class="nao-cadastrado">Empresa n&atildeo cadastrada. Deseja prosseguir?'+
								  '<div><button type="button" class="btn btn-primary sim">Sim</button>'+
								  '<button type="button" class="btn btn-danger nao">N&atildeo</button></div></div>');

						habilitaCliqueSimNaoEmpresa( $('.lista-empresas .sim'), $('.lista-empresas .nao') );

						$(campo_empresa).focusout(function() {

							if(tempo_focus) clearTimeout(tempo_focus);

							tempo_focus = setTimeout(function() {
								
								if( $('.lista-empresas').children().children().hasClass('nao-cadastrado') &&
									!$('.lista-empresas .sim').is(':focus')
								) {
									fechaListaEmpresa( input_group );
								}
								
							}, 200);

						});
					}

				}
			;
	
			execAjax2(type, url, data, success, null, btn_filtro_empresa);

		}
		
		//Abre resultado da filtragem
		function abreListaEmpresa(empresa) {
			
			$(empresa)
				.next('.lista-empresas-container')
				.addClass('ativo');
		
			$(btn_filtro_empresa)
				.attr('tabindex', '-1');
		
		}
		
		//Fecha resultado da filtragem
		function fechaListaEmpresa(empresa) {
			
			$(empresa)
				.next('.lista-empresas-container')
				.removeClass('ativo')
				.children('.lista-empresas')
				.empty();
		
			$(btn_filtro_empresa)
				.removeAttr('tabindex');
		}
		
		//Preencher campos de acordo com o item selecionado
		function selecItemListaEmpresa(itens, campo_empresa, campo_fone, campo_email, campo_contato) {
			
			$(itens).click(function(e) {
				
				e.preventDefault();
				
				$(campo_empresa)
					.val( $(this).next('.descricao').val() )
					.focus();
			
				$(campo_fone)
					.val( $(this).nextAll('input[name="_emp_fone"]').val() );
			
				$(campo_email)
					.val( $(this).nextAll('input[name="_emp_email"]').val() );
			
				$(campo_contato)
					.val( $(this).nextAll('input[name="_emp_contato"]').val() );
			
				$(_empresa_id)
					.val( $(this).nextAll('input[name="_emp_id"]').val() );
				
				selecionadoEmpresa();
				
				fechaListaEmpresa( input_group );
				empresa_selecionada = true;
			});
			
		}
		
		/**
		 * Ações que devem acontecer após o item ser selecionado.
		 */
		function selecionadoEmpresa() {
			
			$('.empresa-descricao')
				.attr('readonly', true);

			$('.btn-filtro-empresa')
				.hide();

			$('.btn-apagar-filtro-empresa')
				.show()
				.click(function() {

					$(this)
						.siblings('input')
						.removeAttr('readonly');

					$(this)
						.hide()
						.prev('button')
						.show();

					$('.empresa-descricao')
						.val('')
						.focus();

					$('input[name="_empresa_id"]')
						.val('');

				});
		}
		
		//Habilitar clique de Sim e Não quando o produto não estiver cadastrado
		function habilitaCliqueSimNaoEmpresa(btn_sim, btn_nao) {
			
			$(btn_sim).click(function() {
				fechaListaEmpresa( input_group );
				$(campo_empresa).focus();
				empresa_cadastrada = false;
			});
			
			$(btn_nao).click(function() {
				fechaListaEmpresa( input_group );
				$(_empresa_id).val('');
				$(campo_empresa).val('').focus();
				empresa_cadastrada = true;
			});
			
			//Ativar clique com enter
			$(btn_sim).bind('keydown', 'return', function() {
				$(this).click();
			});
			$(btn_nao).bind('keydown', 'return', function() {
				$(this).click();
			});
			
		}
		
		/**
		 * Eventos para o filtro de Empresa.
		 */
		function iniciarFiltroEmpresa() {
		
			//Se o item já estiver selecionado (tela de update), efetua as devidas ações.
			if ( ($('input[name="_empresa_id"]').val() !== '') || ($('.empresa-descricao').val() !== '') )
				selecionadoEmpresa();

			//Botão de filtrar
			$('.btn-filtro-empresa').on({

				click: function() {
					
					if ( !$(_empresa_id).val() )
						filtrarEmpresa();
					
				},

				focusout: function() {
					
					//Só prossegue se o produto estiver cadastrado.
					if ( empresa_cadastrada === false || ($('.empresa-descricao').val() !== '') )
						return false;

					if(tempo_focus) clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if ( !$('input[name="_empresa_id"]').val() && $('.lista-empresas').is(':empty') ) {
						   $('.empresa-descricao').val('');
						}

						if ( !$('input[name="_empresa_id"]').val() && 
							 !$('.lista-empresas ul li a').is(':focus') && 
							  $('.empresa-descricao').val() 
						) {
							$('.empresa-descricao').val('');
							fechaListaEmpresa(input_group);
						}
						
						if ( !$('input[name="_empresa_id"]').val() && 
							 !$('.lista-empresas ul li a').is(':focus') && 
							 !$('.empresa-descricao').val() 
						) {
							fechaListaEmpresa(input_group);
						}

					}, 200);
				}

			});

			//Campo de filtro
			$('.empresa-descricao').on({

				keydown: function(e) {

					//Eventos após a escolha de um item
					if ( $(this).is('[readonly]') ) {

						//Deletar teclando 'Backspace' ou 'Delete'
						if ( (e.keyCode === 8) || (e.keyCode === 46) ) {
							$('.btn-apagar-filtro-empresa').click();
						}
					}
					else {

						//Pesquisar com 'Enter'
						if (e.keyCode === 13) {
							filtrarEmpresa();
						}
					}
				},

				focusout: function() {
					
					//Só prossegue se o produto estiver cadastrado.
					if ( (empresa_cadastrada === false) || ($('.empresa-descricao').val() !== '') )
						return false;

					//verificar quando o campo deve ser zerado
					if(tempo_focus) clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if ( !$('input[name="_empresa_id"]').val() && 
							 $('.lista-empresas').is(':empty') &&
							 !$('.btn-filtro-empresa').is(':focus') 
						) {
							$('.empresa-descricao').val('');
						}

					}, 200);
				}

			});
			
			//Ação ao clicar em 'Editar'
			$('.editar-item-dinamico')
				.click(function() {
					
					if ( $('input[name="_empresa_id"]').val() === '' ) {
						$('.empresa-descricao')
							.removeAttr('readonly');
					}
							
				});
			
		}
		
		iniciarFiltroEmpresa();
		
	}
	
	
	{/** Excluir produto */
		var prod;
		var prod_id;
		
		$('.excluir-produto').click(function(){
			
			if( !confirm('Confirma exclusão?') ) return false;

			prod    = $(this);
			prod_id = $(prod).parent('.form-group').closest('.item-dinamico').find('.form-group').first().children('input[name="_req_item_id[]"]').val();

			$(prod).parent('.form-group').closest('.item-dinamico').find('.form-group').first().children('input[name="_req_item_excluir[]"]').val("1");

            if( $(prod).parents('.item-dinamico-container').children('.item-dinamico').length > 1 ){

				//$(prod).parents('.item-dinamico').siblings().children('.form-group').last().hide();
                $(prod).parent().closest('.item-dinamico').hide();

            }else{

                //$(prod).parent().parent('.item-dinamico').hide();
                $(prod).parent().closest('.item-dinamico').find('input').val('').first().focus();
		        $(prod).parent().closest('.item-dinamico').find('text, textarea').val('');

            }



			/*
		    $.ajax({
		        url: '/_13010/excluiProduto',
		        type: 'POST',
		        data: {'item': prod_id},
		        success: function(data) {
		        	
		        	if( data == 'sucesso' ) {
		        		
		        		if( $(prod).parents('.item-dinamico-container').children('.item-dinamico').length == 2 )
							$(prod).parents('.item-dinamico').siblings().children('.form-group').last().hide();
						
		        		$(prod).parent().parent('.item-dinamico').remove();
						
		        	}else{
		        		
		        		$('.diverro').html(data);
						$('.alert-danger').show();
						
		        	}
		        	
		        },
		        error: function(e) {
		        	console.log('Erro ao excluir produto: '+e);
		        }
		    });
		    //*/
		});

	}
	
	{/** Excluir arquivo */
		var arq;
		var arq_id;
		
		$('.excluir-arquivo').click(function(){

			if( !confirm('Confirma exclusão?') ) return false;

			prod    = $(this);
			prod_id = $(prod).parent('.form-group').parent('.item-dinamico').children('.form-group').first().children('input[name="_vinculo_Arquivo_id[]"]').val();

            if (prod_id > 0) {
			    $(prod).parent('.form-group').parent('.item-dinamico').children('.form-group').first().children('.marcaexcluiritem').val(1);
            }

            if( $(prod).parents('.anexo-container').children('.item-dinamico').length > 1 ){

                $(prod).parent().parent('.item-dinamico').hide();

            }else{

                $(prod).parent().parent('.item-dinamico').find('input').val('').first().focus();
		        $(prod).parent().parent('.item-dinamico').find('text').val('').first().focus();

            }

            
			arq    = $(this);
			arq_id = $(arq).parent('.form-group').parent('.item-dinamico').children('.form-group').first().children('input[name="_vinculo_Arquivo_id[]"]').val();

		    $.ajax({
		        url: '/_13010/excluiArquivo',
		        type: 'POST',
		        data: {'item': arq_id},
		        success: function(data) {
		        	
		        	if( data == 'sucesso' ) {
		        		
		        		if( $(arq).parents('.item-dinamico-container').children('.item-dinamico').length == 2 )
		        		{
							$(arq).parents('.item-dinamico').siblings().children('.form-group').last().hide();
						}						
						
		        		if( $(arq).parents('.item-dinamico-container').children('.item-dinamico').length == 1 )
		        		{
		        			$(arq).parent().parent('.item-dinamico').find('input').val('').first().focus();
		        			$(arq).parent().parent('.item-dinamico').find('text').val('').first().focus();
		        			    			
		        		}else
		        		{
		        			$(arq).parent().parent('.item-dinamico').remove();
		        		}
		        	}
		        		
		        },
		        error: function(e) {
		        	console.log('Erro ao excluir arquivo: '+e);
		        }
		    });
		    
		});

	}

    function CalcTotal(Campo) {

	   var qtd   = $(Campo).parent().parent().parent().find('.qtd').val();
       var valor = $(Campo).parent().parent().parent().find('.valor').val();

       //$(Campo).parent().parent().addClass('ccccccccc');
       //$(Campo).parent().parent().addClass('eeeeeeeee');

       var tot = $(Campo).val();

       var valor1 = parseFloat( formataPadrao(qtd));
           valor1 = valor1 ? valor1 : 0;

       var valor2 = parseFloat(formataPadrao(valor));
           valor2 = valor2 ? valor2 : 0;

       var total = (valor1 * valor2).toFixed(4);

       $(Campo).val(total);

       var string = total.toString();
       string = string.replace('.',',');

       $(Campo).val(string);
	}

    $('.valor-total').each(function(){

       CalcTotal(this);

    });

    $(document).on('change', '.qtd', function(){

        $(this).parent().parent().find('.valor-total').addClass('aaaaaaaaaa');

        var campo = $(this).parent().parent().find('.valor-total');
        CalcTotal(campo);

    });

    $(document).on('change', '.valor', function(){

        $(this).parent().parent().parent().find('.valor-total').addClass('bbbbbbb');

        var campo = $(this).parent().parent().parent().find('.valor-total');
        CalcTotal(campo);

    });


	//*
	$('.excluir-item-dinamico').each(function() {

		if( $(this).parents('.item-dinamico-container').children('.item-dinamico').length == 1 ) {
			
			$(this).children('.glyphicon-trash').parents('.form-group').show();
			
		}

		if( $(this).children('.glyphicon').hasClass('glyphicon-trash') ) {
		   	$(this).parent().prev().find('.glyphicon-remove').parents('.form-group').hide();
		}

	});
    //*/

	{/** Excluir arquivo */
//		var arq;
//		var arq_id;
//		$('.download-arquivo').click(function() {	
//			
//			//if( !confirm('Confirma download?') ) return false;
//			//*
//			arq    = $(this);
////			arq_id = $(arq).parent('.form-group').parent('.item-dinamico').children('.form-group').first().children('input[name="_vinculo_id[]"]').val();
//			arq_id = $(arq).siblings('._vinculo_id').val();
//			
//			var nome;
//			var tamanho;
//			var Dir;
//			
//		    $.ajax({
//		    	url: '/_13010/DownloadArquivo',
//		        type: 'POST',
//		        data: {'item': arq_id},
//		        success: function(data) {
//
//		        	var obj = JSON.parse(data);
//		        	
//		        	nome= obj.nome;
//		        	tamanho = obj.tamanho;
//		        	
//		        	Dir = '/download/'+tamanho+'/';		        	
//		        	
//		        	/*
//		        	window.open('/download/'+tamanho+'/'+nome, '_self');
//                   //*/
//		        	
//		        	//*
//					var url_arq = urlhost+$(arq).attr('href');
//					
//		        	//$.fileDownload(Dir+nome)
//					$.fileDownload(url_arq).fail(function () { alert('Erro no download');});
//		        	//*/
//
//		        },
//		        error: function (xhr) {
//						
//					var msg = $(xhr.responseText)
//									.find('.msg-erro')
//									.html();
//					
//					$('.alert-principal')
//						.removeClass('alert-success')
//						.addClass('alert-danger')
//						.children('.texto')
//						.html(msg)
//						.parent()
//						.slideDown();
//					
//				}
//		    });
//
//
//		    
//		    
//		});
//		
	}

	{/** Excluir arquivo */
		var arq;
		var arq_id;

        $('.view-arquivo').click(function(){

			//if( !confirm('Confirma download?') ) return false;
			//*
			arq    = $(this);
			arq_id = $(arq).parent('.form-group').parent('.item-dinamico').children('.form-group').first().children('input[name="_vinculo_id[]"]').val();

		    //ajax
			var type	= 'POST',
				url		= '/_13010/DownloadArquivo',
				data	= {'item': arq_id},
				success = function(data) {

		        	var obj = JSON.parse(data);

		        	var nome= obj.nome;
//		        	var tamanho = obj.tamanho;
					var tipo = nome.split(".").pop();
					
					var vis_arq = $('.visualizar-arquivo');
			
					$(vis_arq)
						.children('a')
						.attr('href', '/assets/temp/'+nome)
						.parent()
						.children('input.arquivo_nome_deletar')
						.val(nome)
						.parent()
						.children('object')
						.attr('data', '/assets/temp/'+nome)
						.removeClass()
						.addClass(tipo)
						.parent()				
						.fadeIn();

		        	ativarCliqueFecharArquivo();

		        }
		    ;
			
			execAjax1(type, url, data, success, null, null, false);
		    //*/

		});



	}

        $('#modal-edit').on('shown.bs.modal', function () {
			$('.cota').focus();
		});

		$('#modal-edit').on('hidden.bs.modal', function () {
			$('.toggle-btn').attr('checked', false);
			$('.toggle-item').fadeOut('normal');

            var Tan = '';

            for (i = 1; i <= 20; i++) {

                if (i < 10){
                    Tan = '.T0'+i;
                }else{
                    Tan = '.T'+i;
                }

                $(Tan).prop( "disabled", true );

                tamanho = '00';

                $(Tan).attr("tamanho",tamanho);
                $(Tan).find(Tan).html(tamanho);

            }
		});

		$('.toggle-btn').click(function () {
			$(".toggle-item").fadeToggle('normal');

		});

	/**
	 * Abre a lista de pesquisa 
	 * @param {type} input_group
	 */
	function abreListaPesquisa(input_group) {
		var btn_filtro = input_group.find('.btn-filtro');     

		$(input_group)
			.next('.pesquisa-res-container')
			.addClass('ativo')
		;

		$(btn_filtro)
			.attr('tabindex', '-1')
		;
	}

	/**
	 * Fecha a lista de pesquisa
	 * @param {type} input_group
	 */
	function fechaListaPesquisa(input_group) {
		var btn_filtro = input_group.find('.btn-filtro');  

		$(input_group)
			.next('.pesquisa-res-container')
			.removeClass('ativo')
			.children('.pesquisa-res')
			.empty();

		$(btn_filtro)
			.removeAttr('tabindex');
	}
	
	/** 
	 * Pesquisar Operações Fiscais 
	 * */
	function consultarOperacao() {

        var filtro;
        var operacao_campo;
        var operacao_btn_filtro;
        var operacao_btn_filtro_apagar;
        var input_group;
        var input_linha;
        var operacao_itens;
        var _operacao_id;
        var _produto_id;
        var _ccusto;
        var _ccontabil;
        var tempo_focus;
        var operacao_selecionado = false;

        /**
         * Filtrar objeto.
         * 
		 * @param {object} campo
         */
        function filtrarOperacao( campo ) {

            operacao_campo              = campo;	//campo
            operacao_btn_filtro         = operacao_campo.nextAll('.btn-filtro-operacao');
			operacao_btn_filtro_apagar	= operacao_campo.nextAll('.btn-apagar-filtro-operacao');

            input_group		= $(operacao_campo).parent('.input-group');	//input-group
            input_linha     = input_group.closest('.item-dinamico');
            _operacao_id    = input_linha.find('input[field="item[operacao][]"]');
            _produto_id     = input_linha.find('._produto-id');
            _ccusto         = input_linha.find('input[field="item[ccusto][]"]');
            _ccontabil      = input_linha.find('input[field="item[ccontabil][]"]');

            filtro = operacao_campo.val();    

            //esvazia campo hidden caso algum item já tenha sido escolhido antes
            if(operacao_selecionado) $(_operacao_id).val('');

            if( !filtro ) {
                fechaListaPesquisa( input_group );
                $(_operacao_id).val('');
                //return false;
            }
			
			//ajax
			var type	= "POST",
                url		= "/_21010/pesquisa",
                data	= {
                    'filtro'        : filtro,
                    'produto_id'    : _produto_id.val()
                },
				success	= function(data) {

                    abreListaPesquisa( input_group );

                    $('.lista-operacao')
                        .html(data)
                    ;

                    //existem dados cadastrados
                    if( data.indexOf('nao-cadastrado') === -1 ) {

                        operacao_itens = $('ul.operacao li a');

                        selecItemListaOperacao( $(operacao_itens), operacao_campo, _operacao_id, _ccusto, _ccontabil);

                        $(operacao_itens)
                            .focusout(function() {

                                if(tempo_focus) 
                                    clearTimeout(tempo_focus);

                                tempo_focus = setTimeout(function() {

                                    if( !$(operacao_itens).is(':focus') && 
                                        !$(operacao_campo).is(':focus') && 
                                        !$(operacao_btn_filtro).is(':focus') 
                                    ) {
                                        $(operacao_campo).val('');
                                        fechaListaPesquisa( input_group );
                                    }

                                }, 200);

                            });

                        $(operacao_campo)
                            .focusout(function() {

                                if(tempo_focus) 
                                    clearTimeout(tempo_focus);

                                tempo_focus = setTimeout(function() {

                                    if( !$(operacao_itens).is(':focus') &&
                                        !$(_operacao_id).val() && 
                                        !$(operacao_btn_filtro).is(':focus') 
                                    ) {
                                        $(operacao_campo).val('');
                                        fechaListaPesquisa( input_group );
                                    }

                                }, 200);

                            });
                    }
                    else {
                        $(_operacao_id).val('');

                        $(operacao_campo)
                            .focusout(function() {
                                if( $('.lista-operacao').children().children().hasClass('nao-cadastrado') ) {
                                    $(operacao_campo).val('');
                                    fechaListaPesquisa( input_group );
                                }
                            });
                    }
                    bootstrapInit();
                }
			;
			
			execAjax2(type, url, data, success, null, operacao_btn_filtro);

        }

        /**
         * Preencher campos de acordo com o item selecionado.
         * 
         * @param {object} itens
         * @param {object} campo
         * @returns {undefined}
         */
        function selecItemListaOperacao(itens, campo, operacao, ccusto, ccontabil) {

			
			$(itens)
				.click(function(e) {
				
					e.preventDefault();
				
					$(campo)
						.val( $(this).nextAll('.descricao').val() )
						.focus()
                    ;
					
					$(operacao)
						.val( $(this).nextAll('.codigo').val() )
                    ;

					$(ccusto)
						.val( $(this).nextAll('.ccusto').val() )
                    ;

					$(ccontabil)
						.val( $(this).nextAll('.ccontabil').val() )
                    ;
                    
					selecionadoOperacao(campo, operacao_btn_filtro, operacao_btn_filtro_apagar);

					fechaListaPesquisa( input_group );
					produto_selecionado = true;
            });

        }
        
		/**
		 * Ações que devem acontecer após o item ser selecionado.
		 *
         * @param input campo_produto
         * @param button btn_filtro
         * @param button btn_filtro_apagar
         */
		function selecionadoOperacao(campo_produto, btn_filtro, btn_filtro_apagar) {
			
			$(campo_produto)
				.attr('readonly', true)
            ;
		
			$(btn_filtro)
				.hide()
            ;

			$(btn_filtro_apagar)
				.show()
				.click(function() {

					$(this)
						.hide()
						.prev('button')
						.removeAttr('disabled')
						.show()
                    ;
                    
					$(this)
						.siblings('input')
						.removeAttr('readonly')
                        .val('')
                    ;                    
                    
					$(this)
						.parents('tr')
                        .first()
                        .find('input[field="item[operacao][]"]')
						.val('')
                    ;
                                
					$(this)
						.parents('tr')
                        .first()
                        .find('input[field="item[ccusto][]"]')
						.val('')
                    ;
                                
					$(this)
						.parents('tr')
                        .first()
                        .find('input[field="item[ccontabil][]"]')
						.val('')
                    ;
             
                    
				});
		}      

        /**
         * Eventos para o filtro de CCusto.
         */
        function iniciarFiltroOperacao() {
            var linha_item;
            var click_item;    
			
			$('.operacao-descricao').each(function() {
			
				if ( $(this).val() !== '' || $(this).attr('readonly') ) {
					
					selecionadoOperacao(
						$(this), 
						$(this).next('.btn-filtro-operacao'), 
						$(this).siblings('.btn-apagar-filtro-operacao')
					);
			
				}
				
			});
            
            //Botão de filtrar
            $('.btn-filtro-operacao').on({

                click: function() {
                    if ( !$(this).parents('tr').first().find('input[field="item[operacao][]"]').val() )
                        filtrarOperacao( $(this).prev('input') )
                    ;
                },
                
				focusout: function() {
                    linha_item = $(this).parents('tr').first();
                    
					if(tempo_focus) 
						clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if (
                           !linha_item
                                .find('input[field="item[operacao][]"]')
                                .val() 
                            &&
                            linha_item
                                .find('.lista-operacao')
                                .is(':empty')
                        ) {
                            linha_item
                                .find('.operacao-descricao')
                                .val('')
                            ;
						}

						if ( 
                           !linha_item
                                .find('input[field="item[operacao][]"]')
                                .val() 
                            && 
                           !linha_item
                                .find('.lista-operacao ul li a')
                                .is(':focus')
                            && 
							linha_item
                                .find('.operacao-descricao')
                                .val() 
						) {
							linha_item
                                .find('.operacao-descricao')
                                .val('')
                            ;
							fechaListaPesquisa(input_group);
						}
						
						if ( 
                           !linha_item
                                .find('input[field="item[operacao][]"]')
                                .val()
                            && 
                           !linha_item
                                .find('.lista-operacao ul li a')
                                .is(':focus')
                            && 
                           !linha_item
                                .find('.operacao-descricao')
                                .val() 
						) {
							fechaListaPesquisa(input_group);
						}

					}, 200);
				}
            });

            //Campo de filtro
            $('.operacao-descricao').on({

                keydown: function(e) {

                    //Eventos após a escolha de um item
                    if ( $(this).is('[readonly]') ) {

                        //Deletar teclando 'Backspace' ou 'Delete'
                        if ( (e.keyCode === 8) || (e.keyCode === 46) ) {
							$(this)
								.nextAll('.btn-apagar-filtro-operacao')
								.click();
                        }
                    }
                    else {

                        //Pesquisar com 'Enter'
                        if (e.keyCode === 13) {
                            filtrarOperacao( $(this) );
                        }
                    }
                },  
                
				focusout: function() {
                    linha_item = $(this).parents('tr').first();
                    click_item = $(this);
                    
					//verificar quando o campo deve ser zerado
					if(tempo_focus) clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if (
                           !linha_item
                                .find('input[field="item[operacao][]"]')
                                .val('') 
                            && 
							click_item
                                .parent()
                                .nextAll('.lista-operacao-container')
                                .children('.lista-operacao')
                                .is(':empty')
                            &&
                           !click_item
                                .nextAll('.btn-filtro-operacao')
                                .is(':focus') 
						) {
							click_item
                                .val('');
						}
					}, 200);
				}
            });
        }
    
		iniciarFiltroOperacao();
	}
	
	/**
	 * Passar entre itens com as teclas up/down.
	 */
	function ativarTabSeta() {

		$(document)
			.on('keydown', '.pesquisa-res ul li a', 'down', function() {
				
				$.tabNext();
				return false;
				
			})
			.on('keydown', '.pesquisa-res ul li a', 'up', function() {
								
				$.tabPrev();
				return false;
			
			})
			.on('keydown', '.ccusto input, .gestor input, .produto input, .empresa-descricao', 'down', function() {
				
				$.tabNext();
				return false;
				
			});
			
	}
	
	function habilitarEdicaoOperacao() {
		
		$('.produto .editar-item-dinamico')
			.click(function() {
				
				$(this)
					.closest('.produto')
					.find('.btn-apagar-filtro-operacao')
					.removeAttr('disabled')
				;
				
			})
		;
		
	}

	/**
	 * Filtro.
	 */
	function filtrar() {

		var filtro = $('.filtro-obj').val(),
			status = $('#filter-status').val();
		
		//ajax
		var type	= "POST",
			url		= "/_13010/filtraObj",
			data	= {'filtro': filtro, 'status': status},
			success	= function(data) {

				$('.dataTables_scrollBody').scrollTop(0);

				$('table.lista-obj tbody').empty();

				console.log(data);

				$('table.lista-obj tbody').append(data);
			},
			erro = function(xhr) {
				$('.dataTables_scrollBody').scrollTop(0);

				$('table.lista-obj tbody').empty();

				console.log('erro');
			}
		;

		execAjax1(type, url, data, success, erro);

		uriHistory(data);
	}

	function eventoFiltrar() {

		$('#btn-table-filter').click(function() {
			filtrar();
		});	

		// Evento de filtrar pelo campo está em table.js.
		// Aqui somente irá setar o filtro na url.
		$('.filtro-obj').keydown(function(e) {

			if (e.keyCode === 13) {

				var data = {
					'filtro': $('.filtro-obj').val(), 
					'status': $('#filter-status').val()
				};

				uriHistory(data);
			}
		});
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
	 */
	function uriHistory(dado) {

		window.history.replaceState('', '', encodeURI(urlhost + '/_13010?'+ $.param(dado)));

		localStorage.setItem('13010FiltroUrl', location.href);
	}


	$(function() {
		
		ativarTabSeta();
		consultarOperacao();
		habilitarEdicaoOperacao();
		eventoFiltrar();
		filtrarAoCarregar();
		
		//Limite do textarea
		limiteTextarea( $('textarea.obs'), 200, $('span.contador span') ); //função em master.js
		
	});

})(jQuery);