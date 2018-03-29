/**
 * Script com funções de:
 * - Escolher produtos para montar orçamento
 * */
(function($) {
	
		/** 
		 * Verificação de campos antes de enviar o form
		 */
		function verificarCampos() {
			
			$('button.js-gravar').click(function(e) {
	
				//se a lista de itens estiver vazia
				if ( $('.panel.orcamento .item').children().length === 0 ) {
					
					e.preventDefault();
					
					$('.alert')
						.addClass('alert-danger')
						.children('.texto')
						.text('Selecione algum produto.')
						.parent()
						.slideDown();
				
					return false;
					
				}

				//se a lista de empresas estiver vazia
				if ( $('.empresas-selec .panel-body').children().length <= 1 ) {
					
					e.preventDefault();
					
					$('.alert')
						.addClass('alert-danger')
						.children('.texto')
						.text('Selecione algum fornecedor.')
						.parent()
						.slideDown();
				
					return false;
					
				}
				
			});

		}
		
		verificarCampos();
		
		/**
		 * Verificar se os fornecedores selecionados possuem email cadastrado.
		 * 
		 * @returns {Boolean}
		 */
		function verificarEmailFornec() {
			
			var ret = true;
				
			$('.empresas-selec .panel-body .empresa-email')
				.each(function() {

					if ( $(this).val() === '' ) {

						$('.alert')
							.addClass('alert-danger')
							.children('.texto')
							.text('Existem fornecedores sem e-mail cadastrado.')
							.parent()
							.slideDown();

						ret = false;

					}

				});

			return ret;

		}

		/** 
		 * Enviar orçamento 
		 */
		function enviarOrcamento() {

			$('button.enviar-orcamento')
				.click(function(){

					if ( !verificarEmailFornec() )
						return false;

					var status_res = 0;
					var status_msg = '';
					var botao = $(this);

					botao.button('loading');
					$('.alert .texto').empty().parent().hide();

					$('.orc-id').each(function() {

						var email		= $(this).parent().find('.empresa-email').val();
                        var id_hash     = $(this).nextAll('input[name="_orcamento_hash"]').val();
						var usuario_id	= $('.usuario-id').val();
						var assunto		= 'Solicitação de proposta';
						var url_princ	= 'https://gc.delfa.com.br';					
						var corpo		= url_princ+'/0/'+ id_hash;
						var status		= '1';
						var data_hora	= 'now';
						var codigo		= 2; //template

						//ajax
						var type		= 'POST',
							data		= 'text',
							url			= '/_13020/enviaEmail',
							data		= {
								'email': email, 'usuario_id': usuario_id, 'assunto': assunto, 'url': url_princ, 
								'corpo': corpo, 'status': status, 'data_hora': data_hora, 'codigo': codigo
							},
							success		= function(resposta) { 

								//sucesso
								if (resposta['0'] === 'sucesso') {
									status_res = 0;
								}
								//erro
								else {
									status_res = 1;
								}

								status_msg = resposta['1'];

							},
							error		= function() { 
								status_res = 1;
								status_msg = 'Erro ao gravar agendamento de envio de email. Contacte o administrador do sistema.';
							},
							complete	= function() {

								//sucesso
								if (status_res === 0) {

									$('.alert .texto').html(status_msg).parent().removeClass('alert-danger').slideDown();

								}
								//erro
								else if (status_res === 1) {

									$('.alert').removeClass('alert-success').addClass('alert-danger');

									var excecao;
									if ( status_msg ) {
										excecao = status_msg.match(/exception 1 ...(.*) At trigger (.*)/i);
									}

									//se for exceção de trigger
									if ( excecao ) {
										$('.alert .texto').html(excecao['1']).parent().slideDown();
									}
									else {
										$('.alert .texto').html(status_msg).parent().slideDown();
									}
								}

								botao.button('reset');

							}
						;
						
						execAjax1(type, url, data, success, error, complete, false);
						
					});
				});

		}
		enviarOrcamento();


		{/** Limite do textarea */

			limiteTextarea( $('textarea.obs'), 200, $('span.contador span') ); //função em master.js
		}


		{ /** Escolher produtos para montar orçamento */

			//Verifica se a requisição já foi escolhida
			function verificarReqExiste() {

				var ret = false;

				if( $('.orcamento .item .panel').length ) {

					$('.orcamento .item .panel').each(function() {

						if( $(this).hasClass('req'+btn_prod.attr('id-req')) ) {
							ret = true;
							return false;
						}
						else
							ret = false;
					});
				}			
				else
					ret = false;

				return ret;

			}

			//Ativar clique para excluir produtos
			function ativarCliqueExcluir() {

				$('.orcamento .item .panel .excluir').off().on('click', function() {

					btn_remover = $(this);

					$('.requisicoes .produto').each(function() {

						if ( 
							($(this).attr('id-prod') === btn_remover.parent().attr('id-prod')) 
							&& ($(this).attr('id-req') === btn_remover.parent().attr('id-req')) 
						) {
							$(this).attr('aria-pressed','false').removeAttr('disabled');
						}

					});

					//atualizar tabela de valores (oculta)
					$('.tabela-valor').children('.prod').each(function() {

						var prod = $(this);
						var id	 = btn_remover.parent().attr('id-prod');
						var tam  = 'tam'+btn_remover.parent().find('.tamanho').text();

						if ( verificarProdRepet1(prod, id, tam) ) { //verifica produto repetido

							var qtd = $(this).children('.prod-qtd').val();
							if(qtd === "") qtd = 0;
							var qtd_tot = formataPadrao(qtd) - formataPadrao( btn_remover.siblings().children('.quantidade').text() );
							qtd_tot = qtd_tot.toFixed(4);	//4 dígitos
							qtd_tot = formataReal(qtd_tot);	//função em master.js

							var vlr = $(this).children('.prod-vlr').val();
							if (vlr === "") vlr = 0;
							var vlr_tot = formataPadrao(vlr) - formataPadrao( btn_remover.siblings().children('.valor').text() );
							vlr_tot = vlr_tot.toFixed(4);	//4 dígitos
							vlr_tot = formataReal(vlr_tot);	//função em master.js

							//remove o produto da tabela de valores (oculta) quando o mesmo for completamente removido do orçamento
							if ( formataPadrao(qtd_tot) <= 0 ) {
								$(this).remove();
							}
							else {

								$(this)
									.children('.prod-qtd')
									.val( qtd_tot );

								$(this)
									.children('.prod-vlr')
									.val( vlr_tot );
							}
						}

					});

					preencheResumo();

					//ESCONDER/REMOVER
					//verifica se já está em algum orçamento e marca o produto para exclusão
					if( btn_remover.prev().hasClass('orc-id') ) {

						btn_remover.next('.produto-excluir').val('1');

						//verifica se é o último produto do grupo (requisição)
						if( btn_remover.parent().siblings('.label').length === 0 || btn_remover.parent().siblings('.label:visible').length === 0 )
							btn_remover.parents('.panel').first().hide();
						else
							btn_remover.parent().hide();

					}
					//senão exclui o produto da lista
					else {

						//verifica se é o último produto do grupo (requisição)
						if( btn_remover.parent().siblings('.label').length === 0 )
							btn_remover.parents('.panel').first().remove();
						else
							btn_remover.parent().remove();
					}

				});

			}

			//Verificar produtos repetidos
			function verificarProdRepet() {

				var ret = false;

				if ( $('.tabela-valor').children().length === 0 ) {

					ret = false;

				}
				else {

					$('.tabela-valor').children('.prod').each(function() {

						var id = btn_prod.attr('id-prod');
						var tam = 'tam'+btn_prod.find('.tamanho').text();

						if ( ($(this).hasClass( id )) && ($(this).hasClass( tam )) ) {
							ret = true;
						}

					});

				}

				return ret;

			}

			//Verificar se o produto em questão é repetido a partir do id e tamanho.
			function verificarProdRepet1(prod, id, tam) {

				var ret = false;

				if ( prod.hasClass( id ) && prod.hasClass( tam ) ) {
					ret = true;
				}

				return ret;
			}

			//Atualizar tabela de valores (utilizado para montar o resumo dos itens)
			// e exibe o resultado das alterações.
			function atualizaTabela() {

				$('.tabela-valor').children('.prod').each(function() {

					if ( verificarProdRepet2( $(this) ) ) {

						var qtd = $(this).children('.prod-qtd').val();
						if (qtd === "") qtd = 0;
						var qtd_tot = formataPadrao(qtd) + formataPadrao( $(this).children('.prod-qtd').val() );
//						console.log(qtd_tot);
						qtd_tot = qtd_tot.toFixed(4);	//4 dígitos
						qtd_tot = formataReal(qtd_tot);	//função em master.js

						var vlr = $(this).children('.prod-vlr').val();
						if (vlr === "") vlr = 0;
						var vlr_tot = formataPadrao(vlr) + formataPadrao( $(this).children('.prod-vlr').val() );
						vlr_tot = vlr_tot.toFixed(4);	//4 dígitos
						vlr_tot = formataReal(vlr_tot);	//função em master.js

						$(this)
							.children('.prod-qtd')
							.val( qtd_tot );

						$(this)
							.children('.prod-vlr')
							.val( vlr_tot );
					}				
				});

				//Remover itens repetidos na tabela de valores (utilizado para montar o resumo dos itens)
				$('.tabela-valor').children('.prod').each(function() {

					if ( verificarProdRepet2( $(this) ) ) {
						$(this).remove();
					}
				});

				preencheResumo();

			}		

			//Verificar produtos repetidos (utilizado para montar o resumo dos itens).
			//Diferente da 'verificarProdRepet', esta função recebe um produto e verifica se existem produtos
			// iguais a ele na 'tabela-valor'.
			function verificarProdRepet2(prod) {

				var ret = false;
//				var jatem;
				
				//se tiver a mesma classe (composta por produto e tamanho)
				if( $(prod).siblings().hasClass( $(prod).attr('class') ) ) {		
//					jatem = $(prod).attr('class');
					ret = true;
				}

				return ret;
			}

			//Preenche resumo dos produtos
			function preencheResumo() {

				$('.tabela-valor').next('.panel.resumo').children('.panel-body').empty().append(
					'<div class="titulo-lista">'+
					'<span>Produto</span>'+
					'<span>Tam.</span>'+
					'<span>Qtd.</span>'+
					'<span>UM</span>'+
					'<span>R$</span>'+
					'</div>'
				);

				//Exibe o resumo dos itens a partir da '.tabela-valor'
				$('.tabela-valor').children('.prod').each(function() {

					$('.tabela-valor').next('.panel.resumo').children('.panel-body').append(
						'<div class="label label-default" id-prod="'+ $(this).children('.prod-id').val() +'">'+
							'<div><span>'+ $(this).children('.prod-id').val() +'</span></div>'+
							'<div title="'+ $(this).children('.prod-desc').val() +'"><span class="descricao">'+ $(this).children('.prod-desc').val() +'</span></div>'+
							'<div><span class="tamanho">'+ $(this).children('.prod-tamanho').val() +'</span></div>'+
							'<div><span class="quantidade">'+ $(this).children('.prod-qtd').val() +'</span></div>'+
							'<div><span class="um">'+ $(this).children('.prod-um').val() +'</span></div>'+
							'<div><span class="valor">'+ $(this).children('.prod-vlr').val() +'</span></div>'+
						'</div>'
					);

				});

			}

			atualizaTabela();

			var btn_remover;
			var btn_prod;

			$('.requisicoes .produto').click(function() {

				btn_prod = $(this);
				btn_prod.attr('disabled', 'true');

				if( !verificarReqExiste() ) {

					$('.orcamento .item')
						.append(
							'<div class="panel panel-default req'+ btn_prod.attr('id-req') +'">'+
							'<div class="panel-heading"><span>Req. '+ btn_prod.attr('id-req') +'</span></div>'+
							'<div class="panel-body">'+
							'<div class="titulo-lista">'+
							'<span>Produto</span>'+
							'<span>Tam.</span>'+
							'<span>Qtd.</span>'+
							'<span>UM</span>'+
							'<span>R$</span>'+
							'</div>'+
							'<div class="label label-default" id-prod="'+ btn_prod.attr('id-prod') +'" id-req="'+ btn_prod.attr('id-req') +'">'+btn_prod.html()+
							'<input type="hidden" name="_produto_excluir[]" class="produto-excluir" />'+
							'<button type="button" class="btn btn-danger excluir"><i class="glyphicon glyphicon-trash"></i></button>'+
							'<input type="hidden" name="_requisicao_id[]" value="'+ btn_prod.attr('id-req') +'" />'+
							'<input type="hidden" name="_prod_id[]" class="prod-id" value="'+ btn_prod.attr('id-prod') +'"></input>'+
							'<input type="hidden" name="_prod_desc[]" class="prod-desc" value="'+ btn_prod.find('.descricao').text() +'"></input>'+
							'<input type="hidden" name="_prod_obs[]" class="prod-obs" value="'+ btn_prod.find('.obs').attr('title') +'"></input>'+
							'<input type="hidden" name="_prod_um[]" class="prod-um" value="'+ btn_prod.find('.um').text() +'"></input>'+
							'<input type="hidden" name="_prod_tamanho[]" class="prod-tamanho" value="'+ btn_prod.find('.tamanho').text() +'"></input>'+
							'<input type="hidden" name="_prod_qtd[]" class="prod-qtd" value="'+ btn_prod.find('.quantidade').text() +'"></input>'+
							'<input type="hidden" name="_prod_valor[]" class="prod-vlr" value="'+ btn_prod.find('.valor').text() +'"></input>'+
							'<input type="hidden" name="_prod_licitacao[]" class="prod-lic" value=""></input>'+ //usado somente em Update						
							'<input type="hidden" name="_req_item_id[]" class="req-item-id" value=""></input>'+
							'<input type="hidden" name="_oper_codigo[]" class="_oper-codigo" value="'+ btn_prod.find('.operacao-codigo').val() +'"></input>'+
							'<input type="hidden" name="_oper_ccusto[]" class="_oper-ccusto" value="'+ btn_prod.find('.operacao-ccusto').val() +'"></input>'+
							'<input type="hidden" name="_oper_ccontabil[]" class="_oper-ccontabil" value="'+ btn_prod.find('.operacao-ccontabil').val() +'"></input>'+
							'</div>'+
							'</div>'+
							'</div>'
						);
				
					//sugestao de fornecedores
					if ( btn_prod.attr('emp-sug') !== '' ) {
						
						$('.empresa-sugestao .panel-body')
							.text(btn_prod.attr('emp-sug'))
							.parent()
							.parent()
							.show();
					}

				}				
				else {

					$('.orcamento .item .panel').each(function() {

						if( $(this).hasClass('req'+$(btn_prod).attr('id-req')) ) {

							$(this).children('.panel-body')
								.append(
									'<div class="label label-default" id-prod="'+ btn_prod.attr('id-prod') +'" id-req="'+ btn_prod.attr('id-req') +'">'+ btn_prod.html() +
									'<input type="hidden" name="_produto_excluir[]" class="produto-excluir" />'+
									'<button type="button" class="btn btn-danger excluir"><i class="glyphicon glyphicon-trash"></i></button>'+
									'<input type="hidden" name="_requisicao_id[]" value="'+ btn_prod.attr('id-req') +'" />'+
									'<input type="hidden" name="_prod_id[]" class="prod-id" value="'+ btn_prod.attr('id-prod') +'"></input>'+
									'<input type="hidden" name="_prod_desc[]" class="prod-desc" value="'+ btn_prod.find('.descricao').text() +'"></input>'+
									'<input type="hidden" name="_prod_obs[]" class="prod-obs" value="'+ btn_prod.find('.obs').attr('title') +'"></input>'+
									'<input type="hidden" name="_prod_um[]" class="prod-um" value="'+ btn_prod.find('.um').text() +'"></input>'+
									'<input type="hidden" name="_prod_tamanho[]" class="prod-tamanho" value="'+ btn_prod.find('.tamanho').text() +'"></input>'+
									'<input type="hidden" name="_prod_qtd[]" class="prod-qtd" value="'+ btn_prod.find('.quantidade').text() +'"></input>'+
									'<input type="hidden" name="_prod_valor[]" class="prod-vlr" value="'+ btn_prod.find('.valor').text() +'"></input>'+
									'<input type="hidden" name="_prod_licitacao[]" class="prod-lic" value=""></input>'+ //usado somente em Update
									'<input type="hidden" name="_req_item_id[]" class="req-item-id" value=""></input>'+
									'<input type="hidden" name="_oper_codigo[]" class="_oper-codigo" value="'+ btn_prod.find('.operacao-codigo').val() +'"></input>'+
									'<input type="hidden" name="_oper_ccusto[]" class="_oper-ccusto" value="'+ btn_prod.find('.operacao-ccusto').val() +'"></input>'+
									'<input type="hidden" name="_oper_ccontabil[]" class="_oper-ccontabil" value="'+ btn_prod.find('.operacao-ccontabil').val() +'"></input>'+
									'</div>'
								);		
						}
					});
				}				

				//Atualizar tabela de valores
				if( !verificarProdRepet() ) {

					var prod_obs = '';
					if( btn_prod.find('.obs').length > 0 ) { 
						prod_obs = btn_prod.find('.obs').attr('title'); 
					}
					
					$('.tabela-valor')
						.append(
							'<div class="prod '+ btn_prod.attr('id-prod') +' tam'+ btn_prod.find('.tamanho').text() +'">'+
							'<input type="hidden" name="_produto_id[]" class="prod-id" value="'+ btn_prod.attr('id-prod') +'"></input>'+
							'<input type="hidden" name="_produto_desc[]" class="prod-desc" value="'+ btn_prod.find('.descricao').text() +'"></input>'+
							'<input type="hidden" name="_produto_obs[]" class="prod-obs" value="'+ prod_obs +'"></input>'+
							'<input type="hidden" name="_produto_um[]" class="prod-um" value="'+ btn_prod.find('.um').text() +'"></input>'+
							'<input type="hidden" name="_produto_tamanho[]" class="prod-tamanho" value="'+ btn_prod.find('.tamanho').text() +'"></input>'+
							'<input type="hidden" name="_produto_qtd[]" class="prod-qtd" value="'+ btn_prod.find('.quantidade').text() +'"></input>'+
							'<input type="hidden" name="_produto_valor[]" class="prod-vlr" value="'+ btn_prod.find('.valor').text() +'"></input>'+
							'<input type="hidden" name="_produto_licitacao[]" class="prod-lic" value=""></input>'+ //usado somente em Update
							'<input type="hidden" name="_operacao_codigo[]" class="_operacao-codigo" value="'+ btn_prod.find('.operacao-codigo').val() +'"></input>'+
							'<input type="hidden" name="_operacao_ccusto[]" class="_operacao-ccusto" value="'+ btn_prod.find('.operacao-ccusto').val() +'"></input>'+
							'<input type="hidden" name="_operacao_ccontabil[]" class="_operacao-ccontabil" value="'+ btn_prod.find('.operacao-ccontabil').val() +'"></input>'+
							'</div>'
						);
				}
				else {

					$('.tabela-valor').children('.prod').each(function() {

						var prod = $(this);
						var id	 = btn_prod.attr('id-prod');
						var tam  = 'tam'+btn_prod.find('.tamanho').text();

						if ( verificarProdRepet1(prod, id, tam) ) {		//verifica produto repetido

							var qtd = $(this).children('.prod-qtd').val();
							if (qtd === "") qtd = 0;
							var qtd_tot = formataPadrao(qtd) + formataPadrao( btn_prod.find('.quantidade').text() );
							qtd_tot = qtd_tot.toFixed(4);	//4 dígitos
							qtd_tot = formataReal(qtd_tot);	//função em master.js

							var vlr = $(this).children('.prod-vlr').val();
							if (vlr === "") vlr = 0;
							var vlr_tot = formataPadrao(vlr) + formataPadrao( btn_prod.find('.valor').text() );
							vlr_tot = vlr_tot.toFixed(4);	//4 dígitos
							vlr_tot = formataReal(vlr_tot);	//função em master.js

							$(this)
								.children('.prod-qtd')
								.val( qtd_tot );

							$(this)
								.children('.prod-vlr')
								.val( vlr_tot );

						}

					});

				}

				ativarCliqueExcluir();
				atualizaTabela();
			});

			ativarCliqueExcluir();	//para a tela de alterar

		}



		{ /** Pesquisar empresa */

			var filtro;
			var campo_empresa;
			var btn_filtro_empresa;
			var input_group;
			var itens_empresa;
			var tempo_focus;

			function pesquisarEmpresa() {

				campo_empresa		= $('.empresa-descricao');
				input_group			= campo_empresa.parent('.input-group');
				btn_filtro_empresa	= $('.btn-filtro-empresa');
				filtro				= campo_empresa.val();

				if( !filtro ) {
					fechaListaEmpresa( input_group );					
					//return false;
				}
				
				//ajax
				var type	= "POST",
					url		= "/_13060/pesquisa",
					data	= {
						'filtro': filtro, 
						'status': '1', 
						'habilita_fornecedor': '1'
					},
					success	= function(data) {

						//se existem dados cadastrados
						if( data.indexOf('nao-cadastrado') === -1 ) {

							abreListaEmpresa( input_group ); 
							$('.lista-empresas').html(data);

							itens_empresa = $('ul.empresas li a');
							selecItemListaEmpresa( itens_empresa, campo_empresa );

							$(itens_empresa).focusout(function() {

								if(tempo_focus) clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {
									
									if( !$(itens_empresa).is(':focus') && 
										!$(campo_empresa).is(':focus') 
									) {
										$(campo_empresa).val('');
										fechaListaEmpresa( input_group );
									}
									
								}, 200);

							});

							$(campo_empresa).focusout(function() { 

								if(tempo_focus) clearTimeout(tempo_focus);

								tempo_focus = setTimeout(function() {
									
									if( !$(itens_empresa).is(':focus') ) {
										fechaListaEmpresa( input_group );
									}
									
								}, 200);

							});
						}

						else {
							fechaListaEmpresa( input_group );
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
			function selecItemListaEmpresa(itens, campo_empresa) {

				$(itens)
					.click(function(e) {
					
						e.preventDefault();
				
						$(campo_empresa)
							.val('')
							.focus();
					
						fechaListaEmpresa( input_group );

						//verifica se a empresa possui e-mail cadastrado
						if( !$(this).siblings('input[name="_emp_email"]').val() ) {

							$('.alert .texto')
								.empty()
								.text('Empresa '+ $(this).siblings('input[name="_emp_id"]').val() +' - '+ $(this).siblings('input[name="_emp_razao"]').val() +' não possui e-mail cadastrado.')
								.parent()
								.addClass('alert-danger')
								.slideDown();
						}
						//else {

							$('.empresas-selec .panel-body')
								.append(
									'<div class="label label-default">'+
									'<span>'+ $(this).siblings('input[name="_emp_id"]').val() +'</span>'+ 
									'<span title="'+ $(this).siblings('input[name="_emp_razao"]').val() +'">'+ $(this).siblings('input[name="_emp_razao"]').val() +'</span>'+
									'<span title="'+ $(this).siblings('input[name="_emp_email"]').val() +'">'+
									'<div class="dado-atual">'+ $(this).siblings('input[name="_emp_email"]').val() +'</div>'+
									'<input type="email" name="empresa_email" class="form-control dado-editar empresa-email" value="'+ $(this).siblings('input[name="_emp_email"]').val() +'" />'+
									'</span>'+
									'<span title="'+ $(this).siblings('input[name="_emp_fone"]').val() +'">'+
									'<div class="dado-atual">'+ $(this).siblings('input[name="_emp_fone"]').val() +'</div>'+
									'<input type="tel" name="empresa_fone" class="form-control fone dado-editar empresa-fone" value="'+ $(this).siblings('input[name="_emp_fone"]').val() +'" />'+
									'</span>'+
									'<span title="'+ $(this).siblings('input[name="_emp_contato"]').val() +'">'+
									'<div class="dado-atual">'+ $(this).siblings('input[name="_emp_contato"]').val() +'</div>'+
									'<input type="text" name="empresa_contato" class="form-control dado-editar empresa-contato" value="'+ $(this).siblings('input[name="_emp_contato"]').val() +'" />'+
									'</span>'+
									'<span title="'+ $(this).siblings('input[name="_emp_cidade"]').val() +'">'+ $(this).siblings('input[name="_emp_cidade"]').val() +'</span>'+
									'<span title="'+ $(this).siblings('input[name="_emp_uf"]').val() +'">'+ $(this).siblings('input[name="_emp_uf"]').val() +'</span>'+
									'<span></span>'+
									'<button type="button" class="btn btn-primary empresa-editar" title="Editar"><i class="glyphicon glyphicon-edit"></i></button>'+
									'<button type="button" class="btn btn-success empresa-gravar" title="Gravar"><i class="glyphicon glyphicon-ok"></i></button>'+
									'<button type="button" class="btn btn-danger empresa-cancelar" title="Cancelar"><i class="glyphicon glyphicon-ban-circle"></i></button>'+
									'<button type="button" class="btn btn-danger excluir" title="Excluir"><i class="glyphicon glyphicon-trash"></i></button>'+
									'<input type="hidden" name="_empresa_excluir[]" class="empresa-excluir" value="" />'+
									'<input type="hidden" name="_empresa_id[]" value="'+ $(this).siblings('input[name="_emp_id"]').val() +'" />'+
									'<input type="hidden" name="_orcamento_id[]" value="" />'+	//utilizado no update para verificar se o fornecedor já está na licitação
									'</div>'
								)
								.parent()
								.parent()
								.show();

							ativarCliqueExcluirEmpresa();
							
						//}

					});

			}

			//Ativar clique para excluir empresa
			function ativarCliqueExcluirEmpresa() {

				$('.empresas-selec .panel-body .label .excluir').off().on('click', function() {

					btn_remover = $(this);

//					if( btn_remover.parent().siblings('.label').length === 0 )
//						btn_remover.parents('.empresas-selec').hide();

					if( btn_remover.prev().hasClass('orc-id') ) {
						btn_remover.next('.empresa-excluir').val('1');
						btn_remover.parent().hide();
					}
					else {
						btn_remover.parent().remove();
					}
				});

			}
			
			ativarCliqueExcluirEmpresa();
			
			/**
			 * Eventos para o filtro de Empresa.
			 */
			function iniciarFiltroEmpresa() {
			
				$('.btn-filtro-empresa')
					.on({
					
						click: function() {
							pesquisarEmpresa();
						},
						
						focusout: function() {

							if(tempo_focus)
								clearTimeout(tempo_focus);

							tempo_focus = setTimeout(function() {

								if ( !$('.lista-empresas ul li a').is(':focus') && 
									 $('.empresa-descricao').val() 
								) {
									$('.empresa-descricao').val('');
									fechaListaEmpresa(input_group);
								}

							}, 200);
						}

					});

				$('.empresa-descricao')
					.on({
						
						keydown: function(e) {

							if ( e.keyCode === 13 ) {
								pesquisarEmpresa();
							}
							
						},
						
						focusout: function() {

							if(tempo_focus)
								clearTimeout(tempo_focus);

							tempo_focus = setTimeout(function() {

								if ( !$('.lista-empresas ul li a').is(':focus') && 
									 $('.empresa-descricao').val() &&
									 !$('.btn-filtro-empresa').is(':focus')
								) {
									$('.empresa-descricao').val('');
									fechaListaEmpresa(input_group);
								}

							}, 200);
						}
						
					});
				
				//mobile
				if ( $(window).width() <= 768  ) {
				
					$('.pesquisa-res-container')
						.click(function() {
							fechaListaEmpresa(input_group);
						});
				}
				
			}
			
			iniciarFiltroEmpresa();

		}
		
		/**
		 * Habilitar eventos e funções para edição de e-mail do fornecedor.
		 */
		function iniciarEditarEmail() {
		
			
			/**
			 * Editar empresa.
			 * 
			 * @param {button} btn
			 */
			function editarEmpresa(btn) {

				$(btn)
					.parent()
					.addClass('selec');
				$(btn)
					.parent()
					.find('div.dado-atual')
					.hide();
				$(btn)
					.parent()
					.find('input.dado-editar')
					.show();
				$(btn)
					.parent()
					.find('input.empresa-email')
					.focus();
				$(btn)
					.hide();
				$(btn)
					.nextAll('.excluir')
					.hide();
				$(btn)
					.nextAll('.empresa-gravar')
					.show();
				$(btn)
					.nextAll('.empresa-cancelar')
					.show();

			}

			/**
			 * Cancelar edição da empresa.
			 * 
			 * @param {button} btn
			 */
			function cancelarEditarEmpresa(btn) {

				$(btn)
					.parent()
					.removeClass('selec');
				$(btn)
					.parent()
					.find('div.dado-atual')
					.show();
				$(btn)
					.parent()
					.find('input.dado-editar')
					.hide();
				$(btn)
					.show();
				$(btn)
					.nextAll('.excluir')
					.show();
				$(btn)
					.next('.empresa-gravar')
					.hide();
				$(btn)
					.nextAll('.empresa-cancelar')
					.hide();
				
			}
			
			/**
			 * Gravar empresa.
			 * 
			 * @param {button} btn
			 */
			function gravarEmpresa(btn) {
				
				var id			= $(btn).nextAll('input[name="_empresa_id[]"]').val();
				var email		= $(btn).parent().find('input.empresa-email').val();
				var fone		= $(btn).parent().find('input.empresa-fone').val();
				var fone_limpo	= $(btn).parent().find('input.empresa-fone').cleanVal();
				var contato		= $(btn).parent().find('input.empresa-contato').val();
				
				execAjax1(
					'POST',
					'/_13020/editarDadosFornec', 
					{ 
						empresa_id		: id, 
						empresa_email	: email, 
						empresa_fone	: fone_limpo, 
						empresa_contato	: contato 
					},
					function(data) {
						
						$(btn)
							.parent()
							.find('.empresa-email')
							.prev('div')
							.empty()
							.text( email )
							.show()
							.parent('span')
							.attr('title', email);
						$(btn)
							.parent()
							.find('.empresa-fone')
							.prev('div')
							.empty()
							.text( fone )
							.show()
							.parent('span')
							.attr('title', fone);
						$(btn)
							.parent()
							.find('.empresa-contato')
							.prev('div')
							.empty()
							.text( contato )
							.show()
							.parent('span')
							.attr('title', contato);
						$(btn)
							.parent()
							.find('input')
							.hide();
						$(btn)
							.hide();
						$(btn)
							.siblings('.empresa-cancelar')
							.hide();
						$(btn)
							.siblings('.excluir')
							.show();
						$(btn)
							.siblings('.empresa-editar')
							.show();
						$(btn)
							.parent()
							.removeClass('selec');
					
						$('.alert .texto')
							.text('Dados da empresa alterados com sucesso.')
							.parent()
							.removeClass('alert-danger')
							.addClass('alert-success')
							.slideDown();
					
					}
				);
				
			}
			
			//eventos
			$('.empresas-selec')
				.on('click', '.empresa-editar', function() {
					editarEmpresa( $(this) );
				})
				.on('click', '.empresa-gravar', function() {
					gravarEmpresa( $(this) );
				})
				.on('click', '.empresa-cancelar', function() {
					cancelarEditarEmpresa( $(this).siblings('.empresa-editar') );
				})
				.on('keydown', 'input', 'return', function() {
					$('.empresa-gravar').click();
				})
				.on('keydown', 'input', 'esc', function() {
					cancelarEditarEmpresa( $(this).parent().siblings('.empresa-editar') );
				});
				
		}
		
		iniciarEditarEmail();
		
	
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
			.on('keydown', '.empresa-descricao', 'down', function() {
				
				$.tabNext();
				return false;
				
			});
			
	}
	
	$(function() {
		
		ativarTabSeta();
		
	});
	
	
})(jQuery);
//# sourceMappingURL=_13020.js.map
