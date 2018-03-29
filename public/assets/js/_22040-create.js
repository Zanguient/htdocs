/**
 * Script com funções do obj _22040
 */

if(window.name === 'remessa-componente') {

    $('.navbar-toggle').remove();
    $('.navbar-left').text('Geração de Remessa de Componente');
    $('.navbar-right').remove();
    $('.navbar-brand' ).attr('href','javascript:void(0);');
    $('.duplicar-tela-mobile').remove();
    $('.go-fullscreen-mobile').remove();
        
}



(function($) {
    
    var objGerarManual = new gerarManual();
    var objGerarAuto = new gerarAuto();
	
	//Utilizada para informar se a verificação de atualização da página é necessária.
	//Ex.: ao clicar em 'Gravar', não é necessário.
	var verif_atualizar = true;
	
	//Utilizada para definir as casas decimais para a qtd
	var decimal = 4;
	
	//Input para ser clonado :)					
	var input_clone = $('.input-clone').clone().removeAttr('class');
    
    var verificacao_perfil = true;
    
	/**
 	 * Destruir objeto dataTable.
 	 */
	function destruirDataTable() {

		$('.dataTables_scrollBody').each(function() {
		
			$(this)
				.find('table')
				.dataTable()
				.fnDestroy();
		
		});

	}

	/**
	 * Ativar Datatable na tabela principal.
	 */
	function ativarDatatable() {
		
		var data_table = $.extend({}, table_default);
			data_table.scrollY = '23vh';

		$('.table-22040').DataTable(data_table);
		
	}
	
	/**
	 * Ativar Datatable nas tabelas menores.
	 */
	function ativarDatatableMenor() {
		
		var data_table			= $.extend({}, table_default);
			data_table.scrollY  = '197px';
			data_table.language = {emptyTable : "Escolha itens da tabela acima"};

		$('.conteudo-filtro .estacao-bloco .table').DataTable(data_table);
		
	}
	
	/**
	 * Verificações e ações ao limpar campos.
	 */
	function limpar() {
		
		//Necessário para a verificação quando o GP for apagado.
		//Guardará os valores antigos para o uso caso não seja confirmada a ação de apagar o GP.
		$('.consulta_gp_grup .consulta-descricao')
			.change(function() {
				
				var cons_inputs		=	$(this)
											.parent()
											.siblings('._consulta_imputs')
										;
									
				var gp_valor		=	$(this)
											.val()
										;
									
				var gp_id			=	$(cons_inputs)
											.find('._gp_id')
											.val()
										;
		
				var gp_desc			=	$(cons_inputs)
											.find('._gp_descricao')
											.val()
										;
									
				var familia_id		=	$('#familia')
											.siblings('._familia-id')
											.val()
										;
										
				var familia_desc	=	$('#familia')
											.siblings('._familia-descricao')
											.val()
										;
										
				var perfil			=	$('._perfil-valor')
											.val()
										;
				
				$(this)
					.data({
						'old-gp-valor'			: gp_valor,
						'old-gp-id'				: gp_id,
						'old-gp-descricao'		: gp_desc,
						'old-familia-id'		: familia_id,
						'old-familia-descricao'	: familia_desc,
						'old-perfil'			: perfil
					})
				;
				
			})
		;
		
		//Ação ao apagar GP
		$('.consulta_gp_grup .btn-apagar-filtro')
			.click(function() {
				
				var input				=	$(this)
												.siblings('input')
											;
										
				var gp_val_old			=	$(input)
												.data('old-gp-valor')
											;
								
				var gp_id_old			=	$(input)
												.data('old-gp-id')
											;
				
				var gp_desc_old			=	$(input)
												.data('old-gp-descricao')
											;
									
				var familia_id_old		=	$(input)
												.data('old-familia-id')
											;
										
				var familia_desc_old	=	$(input)
												.data('old-familia-descricao')
											;
											
				var perfil_old			=	$(input)
												.data('old-perfil')
											;
											
				//Evitar que seja acionado ao selecionar a remessa.
				//Isso porque ao selecionar a remessa, este botão é acionado.
				if ( (typeof gp_val_old !== 'undefined') ) {
				
					//confirmar antes de limpar
					if ( !confirm('Se continuar, todas as alterações NÃO gravadas serão perdidas.') ) {
						
						//repor os valores do GP de acordo com os valores anteriores
						setTimeout(function() {
							
							var cons_desc	=	$('.consulta_gp_grup .consulta-descricao');
							var cons_inputs	=	$(cons_desc)
													.parent()
													.siblings('._consulta_imputs')
												;

							//repor id oculto do GP
							$(cons_inputs)
								.find('._gp_id')
								.val(gp_id_old)
							;

							//repor descrição oculta do GP
							$(cons_inputs)
								.find('._gp_descricao')
								.val(gp_desc_old)
							;
							
							//definir que o campo está selecionado
							$(cons_inputs)
								.siblings('._consulta_parametros')
								.find('._valor_selecionado_consulta')
								.val('1')
							;
							
							//repor descrição do GP, desabilitar campo e esconder/exibir botões
							$(cons_desc)
								.val(gp_val_old)
								.attr('readonly', true)
								.siblings('.btn-filtro-consulta')
								.hide()
								.siblings('.btn-apagar-filtro')
								.show()
							;
							
							//repor família
							$('#familia')
								.siblings('._familia-id')
								.val(familia_id_old)
								.siblings('._familia-descricao')
								.val(familia_desc_old)
							;
							
							$('._perfil-valor')
								.val(perfil_old)
							;
							
							//habilitar botão 'filtrar'
							$('#btn-table-filter')
								.removeAttr('disabled')
							;
							
							habilitarBtnAcao(true);
							
						}, 500);
						
						return false;
						
					}
					
				}
				
				
				
				//limpar família
				$('#familia')
					.val('')
					.siblings('input')
					.val('')
				;
				
				//limpar tabelas
				$('.table-22040 tbody, .up-container')
					.empty()
				;

			})
		;
		
		//Habilitar botões ao alterar o GP
		$('._gp_id')
			.change(function() {
				
				if ( $(this).val() !== '' ) {
					
					$('#btn-table-filter')
						.removeAttr('disabled')
					;
					
				}
				else {
					
					$('#btn-table-filter')
						.attr('disabled', true)
					;
					
					habilitarBtnAcao(false);
					
				}
			})
		;
		
		//Ações ao limpar Remessa
		$('#limpar-remessa')
			.click(function() {
				
				//confirmar antes de limpar
				if ( !confirm('Se continuar, todas as alterações NÃO gravadas serão perdidas.') ) {
					return false;			
				}
				
				//remover 'data' com valores que seriam recuperados caso não seja confirmada a limpeza.
				$('.consulta_gp_grup .consulta-descricao')
					.removeData();
				
				$('._consulta_filtro[objcampo="FAMILIA"]')
					.val('')
				;
				
				$('.consulta_gp_grup .btn-apagar-filtro')
					.click()
				;
				
				$('#estab')
					.val('')
					.siblings('input')
					.val('')
				;
				
				$('#remessa')
					.val('')
					.removeClass('selecionado')
					.removeAttr('readonly')
					.focus()
					.next('._remessa-id')
					.val('')
					.next('._requisicao')
					.val('')
				;
				
				habilitarBtnAcao(false);
			
			})
		;
		
		//Data
		var data_input			= $('#data-prod'),
			limpar_data_input	= $('#limpar-data');
		
		//ações ao mudar data
		$(data_input)
			.change(function() {
		
				$(limpar_data_input).removeProp('disabled');
		
			})
		;
		
		//limpar data
		$(limpar_data_input)
			.click(function() {
				
				//confirmar antes de limpar
				if ( confirm('Se continuar, todas as alterações NÃO gravadas serão perdidas.') ) {

					$(data_input).val('');
					$(this).prop('disabled', true);
					
					//limpar tabelas
					$('.table-22040 tbody, .up-container')
						.empty()
					;

				}
				
			})
		;
		
	}
	
	/**
	 * Pesquisar remessa.
	 */
	function pesqFamiliaRemessa() {			
	
		$('#selec-remessa')
			.click(function() {
				
				if ( $('#remessa').val() === '' )
					return false;
				
				var url	= '/_22040/pesqRemessa';
		
				var dados = {
					remessa		: $('#remessa').val(),
					detalhe		: ['FAMILIA']
				};
				
				success = function(resposta) {
					if (!(resposta.FAMILIA.length > 0)) {
                        showAlert('Remessa/Pedido/Requisições inexistente ou sem consumo/família definida.');
                        return;
                    }
                    
					$('#remessa')
						.attr('readonly', true)
						.addClass('selecionado');
					
					var str = '';
					
					for(i = 0; i < resposta.FAMILIA.length; i++) {
						
						str = ( i == 0 )
								? resposta.FAMILIA[i].FAMILIA_ID 
								: str + ', ' + resposta.FAMILIA[i].FAMILIA_ID;
					}
					
					$('._consulta_filtro[objcampo="FAMILIA"]')
						.val(str);
					
					$('.consulta_gp_grup .btn-apagar-filtro')
						.click();
				
					$('.consulta_gp_grup .btn-filtro-consulta')
						.click();
				
					$('#remessa')
						.next('._remessa-id')
						.val(resposta.FAMILIA[0].REMESSA_ID)
					;
					
					//se a remessa digitada for igual a 'REQ', o campo que indica que a remessa é de requisição deve ser setado com 1
					if( $('#remessa').val() == 'REQ' ) {
						
						$('#remessa')
							.nextAll('._requisicao')
							.val('1')
						;
					}
					else {
						
						$('#remessa')
							.nextAll('._requisicao')
							.val(resposta.FAMILIA[0].REQUISICAO.trim())
						;
					}
					
					$('#estab')
						.val(resposta.FAMILIA[0].ESTABELECIMENTO_ID +' - '+resposta.FAMILIA[0].ESTABELECIMENTO_DESCRICAO)
						.siblings('._estab-id')
						.val(parseInt(resposta.FAMILIA[0].ESTABELECIMENTO_ID))
						.siblings('._estab-descricao')
						.val(resposta.FAMILIA[0].ESTABELECIMENTO_DESCRICAO)
					;
				
				};

				execAjax1(
					'POST', 
					url,
					dados,
					success
				);
				
			})
		;		
		
		$('#remessa')
			.on('keydown', function(e) {

				//Enter
				if ( (e.keyCode === 13) ) { 
					
					if ( $('#selec-remessa').is(':visible') )
						$('#selec-remessa').click();
				}
		
				//Deletar teclando 'Backspace' ou 'Delete'
				if ( (e.keyCode === 8) || (e.keyCode === 46) ) {
					
					if ( $('#limpar-remessa').is(':visible') )
						$('#limpar-remessa').click();
				
				}

			})
		;
	
	}
	
	/**
	 * Ao selecionar um GP, a família é setada em campos 'hidden'.
	 * Essa função passar a família para o campo 'text'.
	 */
	function familiaHiddenParaText() {
		
		var familia = $('#familia');
		
		$(familia)
			.siblings('._familia-descricao')
			.change(function() {
				
				$(familia)
					.val( 
						$(this).siblings('._familia-id').val() +
						' - '+ 
						$(this).val() 
					)
				;
				
			})
		;
		
	}
	
	/**
	 * Editar quantidade à programar na tabela de itens da remessa.
	 */
	function editarQtd() {
		
		/**
		 * Passar valor do input hidden para o td correspondente (tabela principal).
		 */
		$.fn.inputToTdGp = function() {		

			var classe	=	$(this)
								 .prop('class')
								 .replace('_', '')
							 ;

			var valor	=	$(this)
								 .val()
							 ;

			$(this)
				.siblings('.'+classe)
				.children('span')
				.text(formataPadraoBr(valor));

			//setar quantidade no 'input text'
			if ( classe === 'qtd-prog' ) {
				
				$(this)
					.siblings('.'+classe)
					.children('.editar-qtd')
					.val(valor)
				;
				
			}

			return $(this);

		};
		
		$(document)
			.on('click', '.btn-editar-qtd', function() {
				
				$(this)
					.hide()
					.siblings('.editar-qtd')
					.show()
					.select()
					.siblings('.btn-confirmar-editar-qtd')
					.removeAttr('disabled')
					.show()
					.siblings('.btn-cancelar-editar-qtd')
					.removeAttr('disabled')
					.show()
					.parent('td')
					.addClass('esconder-texto')
				;
				
			})
			.on('click', '.btn-cancelar-editar-qtd', function() {
				
				$(this)
					.attr('disabled', true)
					.hide()
					.siblings('.editar-qtd')
					.hide()
					.siblings('.btn-confirmar-editar-qtd')
					.attr('disabled', true)
					.data('valor-valido', true)
					.hide()
					.siblings('.btn-editar-qtd')
					.show()
					.parent('td')
					.removeClass('esconder-texto')
				;
				
			})
			.on('click', '.btn-confirmar-editar-qtd', function() {

				var valor_novo_str	=	$(this)
											.siblings('.editar-qtd')
											.val()
										;
										
				var valor_novo_num	=	parseFloat(valor_novo_str);
									
				var valor_ant_str	=	$(this)					
											.parent('td')
											.siblings('._qtd-prog')
											.val()
										;
							
				var valor_ant_num	=	parseFloat(valor_ant_str);				
				
				if ( (valor_novo_num <= 0) || (valor_novo_num > valor_ant_num) || (typeof valor_novo_num === 'undefined') ) {
					
					showAlert('A quantidade deve ser maior do que 0 e menor do que '+ formataPadraoBr(valor_ant_str) +'.');
					
					//indica se o valor é válido
					$(this)
						.data('valor-valido', false)
					;
					
					return false;
					
				}
				else {
					
					$(this)
						.data('valor-valido', true)	//indica se o valor é válido
						.attr('disabled', true)
						.hide()
						.siblings('.editar-qtd')
						.hide()
						.siblings('.btn-cancelar-editar-qtd')
						.attr('disabled', true)
						.hide()
						.siblings('.btn-editar-qtd')
						.show()
						.parent('td')
						.removeClass('esconder-texto')
						.siblings('._qtd-prog')
						.val( valor_novo_num.toFixed(decimal) )
						.inputToTdGp()
					;
					
				}
				
			})
			.on('keydown', '.editar-qtd', 'return', function() {
				
				$('.btn-confirmar-editar-qtd')
					.click();
				
			})
			.on('keydown', '.editar-qtd', 'esc', function() {
				
				$('.btn-cancelar-editar-qtd')
					.click();
				
			})
			.on('keydown', '.editar-qtd', function(e) {
		
				var keycode = e.keyCode || e.which;
		
				if ( !((keycode === 13) || (keycode === 8) || (keycode === 46) || (keycode === 27) || (keycode === 188 || keycode === 110) || (keycode >= 48 && keycode <= 57) || (keycode >= 96 && keycode <= 105)) ) {
					return false;
				}
				
			})
		;
				
	}
	
	/**
	 * Editar quantidade de talões na tabela de itens da remessa.
	 */
	function editarQtdTaloes() {
		
		/**
		 * Passar valor do input hidden para o td correspondente (tabela principal).
		 */
		$.fn.inputToTdQtdTaloes = function() {		

			var classe	=	$(this)
								 .prop('class')
								 .replace('_', '')
							 ;

			var valor	=	$(this)
								 .val()
							 ;

			$(this)
				.siblings('.'+classe)
				.children('span')
				.text(formataPadraoBr(valor));

			//setar quantidade no 'input text'
			if ( classe === 'qtd-taloes' ) {
				
				$(this)
					.siblings('.'+classe)
					.children('.editar-qtd-taloes')
					.val(valor)
				;
				
			}

			return $(this);

		};
		
		/**
		 * Passar valor do input hidden para o td correspondente (tabela principal).
		 */
		$.fn.inputToTdQtdProg = function() {		

			var classe	=	$(this)
								 .prop('class')
								 .replace('_', '')
							 ;

			var valor	=	$(this)
								 .val()
							 ;

			$(this)
				.siblings('.'+classe)
				.children('span')
				.text(number_format(valor, decimal, ',', '.'));

			//setar quantidade no 'input text'
			if ( classe === 'qtd-prog' ) {
				
				$(this)
					.siblings('.'+classe)
					.children('.editar-qtd')
					.val(valor)
				;
				
			}

			return $(this);

		};
		
		$(document)
			.on('click', '.btn-editar-qtd-taloes', function() {
				
                var qtd_taloes =
                    $(this)
                        .closest('tr')
                        .find('._qtd-taloes')
                        .val()
                    ;
                
				$(this)
					.hide()
					.siblings('.editar-qtd-taloes')
                    .val(parseInt(qtd_taloes))
					.show()
					.select()
					.siblings('.btn-confirmar-editar-qtd-taloes')
					.removeAttr('disabled')
					.show()
					.siblings('.btn-cancelar-editar-qtd-taloes')
					.removeAttr('disabled')
					.show()
					.parent('td')
					.addClass('esconder-texto')
				;
				
			})
			.on('click', '.btn-cancelar-editar-qtd-taloes', function() {
				
				$(this)
					.attr('disabled', true)
					.hide()
					.siblings('.editar-qtd-taloes')
					.hide()
					.siblings('.btn-confirmar-editar-qtd-taloes')
					.attr('disabled', true)
					.data('valor-valido', true)
					.hide()
					.siblings('.btn-editar-qtd-taloes')
					.show()
					.parent('td')
					.removeClass('esconder-texto')
				;
				
			})
			.on('click', '.btn-confirmar-editar-qtd-taloes', function() {

				var valor_novo_str				=	$(this)
														.siblings('.editar-qtd-taloes')
														.val()
													;
										
				var valor_novo_num				=	parseFloat(valor_novo_str);
										
				var valor_fator_divisao			=	$(this)					
														.parent('td')
														.siblings('._fator-divisao')
														.val()
													;
										
				var qtd_prog					=	$(this)					
														.parent('td')
														.siblings('._qtd-prog')
													;
				
				var valor_qtd_taloes_max =	$(this)					
                                                    .parent('td')
                                                    .siblings('._qtd-taloes-max')
                                                    .val()
                                                ;
													
				var valor_qtd_taloes_saldo_num	=	number_format(valor_qtd_taloes_max, 0, ',', '.');
				
				//verifica se o valor digitado é decimal
				if ( valor_novo_num % 1 != 0 && !isNaN(valor_novo_num % 1) ) {
					
					showAlert('O valor deve ser inteiro.');
					
					//indica se o valor é válido
					$(this)
						.data('valor-valido', false)
					;
					
					return false;
				}
				else if ( (valor_novo_num < 0) || (valor_novo_num > valor_qtd_taloes_max) || (typeof valor_novo_num === 'undefined') ) {
					
					showAlert('A quantidade informada deve estar entre 0 e '+ valor_qtd_taloes_saldo_num +'.');
					
					//indica se o valor é válido
					$(this)
						.data('valor-valido', false)
					;
					
					return false;
					
				}
				else {
					
					$(this)
						.data('valor-valido', true)	//indica se o valor é válido
						.attr('disabled', true)
						.hide()
						.siblings('.editar-qtd-taloes')
						.hide()
						.siblings('.btn-cancelar-editar-qtd-taloes')
						.attr('disabled', true)
						.hide()
						.siblings('.btn-editar-qtd-taloes')
						.show()
						.parent('td')
						.removeClass('esconder-texto')
						.siblings('._qtd-taloes')
						.val( valor_novo_num )
						.inputToTdQtdTaloes()
					;
					
					var res = valor_fator_divisao * valor_novo_num;
					res.toFixed(decimal);
					
					$(qtd_prog)
						.val( valor_fator_divisao * valor_novo_num )
						.inputToTdQtdProg()
					;
					
				}
				
			})
			.on('keydown', '.editar-qtd-taloes', 'return', function() {
				
				$('.btn-confirmar-editar-qtd-taloes')
					.click();
				
			})
			.on('keydown', '.editar-qtd-taloes', 'esc', function() {
				
				$('.btn-cancelar-editar-qtd-taloes')
					.click();
				
			})
			.on('keydown', '.editar-qtd-taloes', function(e) {
		
				var keycode = e.keyCode || e.which;
		
				if ( !((keycode === 13) || (keycode === 8) || (keycode === 46) || (keycode === 27) || (keycode === 188 || keycode === 110) || (keycode >= 48 && keycode <= 57) || (keycode >= 96 && keycode <= 105)) ) {
					return false;
				}
				
			})
		;
				
	}
	
	
	/**
	 * Editar quantidade de talões na tabela de itens da remessa.
	 */
	function editarQtdCota() {

        var tr;

        function alterar() {

            function clickAlterar() {
                $(document)
                    .on('click', '.table-22040 .btn-alterar',
                        function() {
                            tr = $(this).closest('tr');

                            var focus = true;
                            $(tr)
                                .find('td[field-js=alterar-input]')
                                .each(function(){
                                    
                                    var span = $(this).children('.span');
                                    var input = $(this).children('.input');
                                    
                                    span.hide();
                                    input.css('display', 'inline-block');
                                    
                                    if ( focus ) {
                                        input.select();
                                        focus = false;
                                    }
                                })
                            ;

                            $(tr).find('.btn-alterar').hide();
                            $(tr).find('.btn-confirm').css('display', 'inline-block');

                            $(tr).find('.btn-excluir').hide();
                            $(tr).find('.btn-cancel').css('display', 'inline-block');
                        }
                    )
                ;  
            }

            function clickCancelar() {
                $(document)
                    .on('click', '.table-22040 .btn-cancel',
                        function() {    
                            tr = $(this).closest('tr');

                            $(tr)
                                .find('td[field-js=alterar-input]')
                                .each(function(){
                                    
                                    var span = $(this).children('.span');
                                    var input = $(this).children('.input');
                                    
                                    input.val(span.text());
                                    span.css('display', 'inline-block');
                                    input.hide();
                                })
                            ;

                            $(tr).find('.btn-alterar').css('display', 'inline-block');
                            $(tr).find('.btn-confirm').hide();

                            $(tr).find('.btn-excluir').css('display', 'inline-block');
                            $(tr).find('.btn-cancel').hide();
                        }
                    )
                ;
            }

            function clickConfirmar() {
                var param = {};
                var input_estab;
                var input_mes;
                var input_ano;
                var input_valor;

                $(document)
                    .on('click', '.table-22040 .btn-confirm',
                        function() {    
                            var btn = $(this);
                            tr = $(this).closest('tr'); 

                            var param = {
                                modelo_id       : tr.find('._modelo-id' ).val(),
                                cliente_id      : tr.find('._cliente-id').val(),
                                quantidade_cota : tr.find('.editar-qtd-cota').val()
                            };


                            execAjax1('POST','/_22040/atualizarCotaCliente',param,function(data) {

                                $(btn)
                                    .closest('table')
                                    .find('tr')
                                    .each(function(){
                                        if ( 
                                            param.modelo_id  == $(this).find('._modelo-id'  ).val()  &&
                                            param.cliente_id == $(this).find('._cliente-id' ).val()
                                        ) {

                                            $(this)
                                                .find('td[field-js=alterar-input]')
                                                .each(function(){
                                                    
                                                    var span = $(this).children('.span');
                                                    var input = $(this).children('.input');

                                                    span.css('display', 'inline-block');
                                                    input.hide();

                                                    input.val(param.quantidade_cota);
                                                    span.text(input.val());
                                                    input.TrTo2Hidden(0);
                                                })
                                            ;
                                    
                                            $(this).find('.btn-alterar').css('display', 'inline-block');
                                            $(this).find('.btn-confirm').hide();

                                            $(this).find('.btn-excluir').css('display', 'inline-block');
                                            $(this).find('.btn-cancel').hide();    
                                        }
                                    })
                                ; 
                                showSuccess('Cota de talão do modelo por cliente definida com sucesso!');
                            });
                        }
                    )
                    .on('keydown', '.table-22040 .input', 'return', function() {

                        $(this)
                            .closest('tr')
                            .find('.btn-confirm')
                            .click();

                    })
                    .on('keydown', '.table-22040 .input', 'esc', function() {

                        $(this)
                            .closest('tr')
                            .find('.btn-cancel')
                            .click();

                    })
                ;
            }

            clickAlterar();
            clickConfirmar();
            clickCancelar();
        }

        alterar();    
    }
	
	/**
	 * Habilitar/desabilitar os botões de ações principais.
	 * 
	 * @param {boolean} habilitar
	 */
	function habilitarBtnAcao(habilitar) {

		if ( habilitar ) {

			$('#btn-processar')
				.removeAttr('disabled')
			;
		}
		else {

			$('#btn-processar')
				.attr('disabled', true)
			;
		}
	}
	
	/**
	 * Filtrar itens de consumo.
	 */
	function filtrar() {
		
		/**
		 * Verificações ao filtrar.
		 * 
		 * @returns {Boolean}
		 */
		function verificar() {
			
			var ret = true;
			
			//verificar se há registros na tabela
			if ( $('.table-22040 tbody tr').length > 1 ) {

				//confirmar antes de reiniciar o processo
				if( !confirm('Se continuar, todas as alterações NÃO gravadas serão perdidas.') ) {
					
					ret = false;
				}
			}
			
			//verificar se existe família definida no GP
			if ( $('#familia').siblings('._familia-id').val() === '' ) {
						
				showAlert('Grupo de Produção sem Família definida.');
				ret = false;
			}
			
			//verificar se existe perfil definido no GP
			else if ( $('._perfil-valor').val() === '' ) {

				showAlert('Grupo de Produção sem Perfil definido.');
				ret = false;
			}
			
			return ret;
		}
		
		/**
		 * Verifica se a remessa a ser gerada já existe.
		 * @returns {Promise}
		 */
		function verificarRemessaExiste() {
			
			return new Promise(function(resolve, reject) {
				
				var type	= 'POST',
					url		= '/_22040/verificarRemessaExiste',
					data	= {
						remessa	: $('#remessa').val(),
						gp_id	: $('._gp_id').val()
					},
					success	= function() {						
						resolve(true);						
					},
					error	= function() {
						reject(false);
					}
				;
				
				execAjax1(type, url, data, success, error);
				
			});
			
		}
		
        $('.form-filtrar').submit(function(e)
        {
            e.preventDefault();
            
            if ( !verificar() ) return false;
            
            $('.conteudo-filtro').empty();
            
            verificarRemessaExiste()
                .then(function() {
                    
                    if ( $('#remessa').val() == 'REP' ) {
                        $('._controle-talao').val('A'); // Acumula projeção
                    }

                    var url	= '/_22040/showNecessidade';

                    var dados = {
                        estabelecimento_id : $('._estab-id').val(),
                        remessa            : $('#remessa').val(),
                        remessa_id         : $('#remessa').next('._remessa-id').val(),
                        requisicao         : $('#remessa').nextAll('._requisicao').val(),
                        familia			   : $('#familia').siblings('._familia-id').val(),
                        estab			   : $('#estab').siblings('._estab-id').val(),
                        gp				   : $('._gp_id').val(),
                        data_producao	   : $('#data-prod').val(),
                        detalhe			   : ['NECESSIDADE', 'UP'],
                        CONTROLE_TALAO     : $('._controle-talao').val().trim(),
                        um_alternativa     : $('._familia-um-alternativa').val()
                    };

                    success = function(resposta) {

                        $('.conteudo-filtro')
                            .html(resposta);

                        $('#btn-distribuir-auto').prop('disabled', false);
                        //Verificar se há itens na projeção de consumo sem perfil
                        $('.table-22040 tr')
                            .each(function(){
                                var perfil_sku = $(this)
                                    .find('._perfil-sku')
                                    .val()
                                ;

                                if (perfil_sku == '') {
                                    $('#btn-distribuir-auto').prop('disabled', true);
                                    showAlert('Há itens da projeção de consumo sem PERFIL DE SKU definido.<br/><b>A programação automática não poderá ser utilizada</b>');
                                    return;
                                }
                            })
                        ;

                        ativarDatatable();
                        ativarDatatableMenor();
                        //dragAndDrop();
                        habilitarBtnAcao(true);
                        ativarSelecLinhaRadio();
                        manipularConsumo();

                        var controller   = function(){return $('#main').find('[ng-controller]');};

                        var scope = controller().scope();

                        var vm = scope.vm;

                        vm.gScope.DADOS = dados;

                        angular.element($('#main')).injector().invoke(['$compile','$timeout',function($compile,$timeout) {
                            
                            $timeout(function(){
                                var html = $('.conteudo-filtro');
                                $compile(html.contents())(scope);
                            });
                        }]);
//                        angular.element(controller).injector().invoke(function($compile){
//                        });
                    };

                    execAjax1(
                        'POST', 
                        url,
                        dados,
                        success
                    );
                })
                .catch(function() {

                })
            ;	
        });
        	
	}
	

	/**
	 * Passar valor do input hidden para o td correspondente.
	 */
	$.fn.TrTo2Hidden	= function(decimal) {	
        
        decimal = isNaN(decimal) ? 4 : decimal;

		var element_hidden	= $(this).data('input-hidden');
        
		var valor = $(this).val();							

		$(this)
            .closest('tr')
            .find(element_hidden)
			.val(number_format(valor, decimal, '.', ','));

		return $(this);

	};

	/**
	 * Passar valor do input hidden para o td correspondente.
	 */
	$.fn.inputToTd	= function(decimal) {	
        
        decimal = isNaN(decimal) ? 4 : decimal;

		var classe	= $(this)
						.prop('class')
						.replace('_', '');
		var valor	= $(this)
						.val();							

		$(this)
			.siblings('.'+classe)
			.children('span')
			.text(number_format(valor, decimal, ',', '.'));

		return $(this);

	};
	
	/**
	 * Adicionar 'tr' que identifica o bloco (talão acumulado).
	 * 
	 * @param {element} tr_item_estacao
	 * @param {element} tr_novo
	 */
	function addTrBloco(tr_item_estacao, tr_novo) {
		
		var bloco_modelo = false;
		
		//Para o primeiro da lista: 
		//verifica se já existe a classe 'bloco-modelo' em algum dos 'tr',
		//pois isso configura que já tem o identificador do bloco.
		if ( !$(tr_item_estacao).parent().find('tr').hasClass('bloco-modelo') ) {
			bloco_modelo = true;
		}
		//mudança de bloco: 'tr' atual com a classe 'bloco-novo'
		else if ( $(tr_item_estacao).hasClass('bloco-novo') && !$(tr_item_estacao).parent().find('tr').last().hasClass('bloco-novo') ) {
			bloco_modelo = true;
		}
		//mudança de bloco: 'tr' atual sem a classe 'bloco-novo'
		else if ( !$(tr_item_estacao).hasClass('bloco-novo') && $(tr_item_estacao).parent().find('tr').last().hasClass('bloco-novo') ) {
			bloco_modelo = true;
		}
		else {
			bloco_modelo = false;
		}
		
		//se o bloco for diferente
		if ( bloco_modelo ) {
			
			var tr_bloco_modelo = $(tr_novo).clone(true);
			
			$(tr_bloco_modelo)
				.addClass('bloco-modelo')
			;
			
			$(tr_bloco_modelo)
				.find('input')
				.remove()
			;
			
			$(tr_bloco_modelo)
				.find('.cor')
				.remove()
			;
			
			$(tr_bloco_modelo)
				.find('.tamanho')
				.remove()
			;
			
			$(tr_bloco_modelo)
				.find('.qtd-prog')
				.children('span')
				.text('')
			;
			
			$(tr_bloco_modelo)
				.find('.tempo')
				.text('-')
			;
			
			$(tr_bloco_modelo)
				.find('.modelo')
				.attr('colspan', '3')
			;
			
			$(tr_item_estacao)
				.parent()
				.append( tr_bloco_modelo )
			;
			
		}
		
	}
    
    function addTrDetalhe(tr_estacao,valor) {
        
        var saldo            = valor;
        var talao_quantidade = 0;
        var vinculos = [];
        
        function processarSaldo() {
            
            var cota_detalhe_val = $(tr_estacao).find('._cota-detalhe').val();
            
            var cota_detalhe = (parseFloat(cota_detalhe_val) > 0) ? parseFloat(cota_detalhe_val) : 99999999;
            
            switch(true) {

                case (saldo <= cota_detalhe) : 
                    talao_quantidade = saldo;
                    saldo = 0;
                break;

                case (saldo > cota_detalhe) : 
                    talao_quantidade = cota_detalhe; 
                    saldo = saldo - cota_detalhe;
                break;
            }
        }
        
        
        do {
            processarSaldo();
                        
            var tr_novo = $(tr_estacao).removeClass('selected').clone(true);
            
            $(tr_novo)
                .find('._qtd-prog')
                .val(talao_quantidade.toFixed(decimal))
                .inputToTd()
                .parent()
            ;

            //Adiciona o tr que identifica o bloco
            addTrBloco(tr_estacao, tr_novo);

            //Insere o novo tr no body da estação 
            $(tr_novo)		
                .appendTo( 
                    $(tr_estacao)
                        .parent()
                )
            ;
                                   
            var total    = 0;
            var qtd_prog = $(tr_novo).find('._qtd-prog').val();
            $(tr_novo)
                .find('._consumo-qtd')
                .each(function(){

                    var id_atual    = $(this).prevAll('._consumo-id').first().val();
                    var consumo_original = $(tr_estacao).find('[value='+id_atual+']');
                    var valor_atual = parseFloat($(this).val());

                    //Verifica se o total incrementado com o valor atual utrapassa a quantidade do talão
                    if ( ( total + valor_atual ) >= qtd_prog ) {

                        //Calcula o quanto será necessário para compor o restante da quantidade do talão
                        var restante = (qtd_prog - total);

                        //Aplica o valor restante
                        $(this).val( restante.toFixed(decimal) );

                        //Remove todos os itens de consumo a frente (já que a quantidade total já foi alcançada
                        $(this).nextAll('._consumo-id'   ).remove();
                        $(this).nextAll('._consumo-talao').remove();
                        $(this).nextAll('._consumo-ref'  ).remove();
                        $(this).nextAll('._consumo-qtd'  ).remove();

                        //Aplica o saldo restante para ser usado para o proximo laço
                        $(consumo_original).nextAll('._consumo-qtd').first().val((valor_atual - restante).toFixed(decimal));

                    } else {
                        //Remove o item já utilizado
                        $(consumo_original).nextAll('._consumo-talao').first().remove();
                        $(consumo_original).nextAll('._consumo-ref'  ).first().remove();
                        $(consumo_original).nextAll('._consumo-qtd'  ).first().remove();
                        $(consumo_original).remove();
                    }

                    //Incrementa o total com o valor atual para o próximo laço
                    total = total + valor_atual;
                })
            ;
        }
        while ( saldo > 0 );
    }
	
	
	/**
	 * Clonar 'tr' na Estação após o item ser dividido em blocos.
	 * 
	 * @param {object} tr_item_estacao
	 * @param {string} valor
	 */
	function addTrEstacao(tr_item_estacao, valor) {
		
        addTrDetalhe(tr_item_estacao, valor);
        
		var densidade = $(tr_item_estacao)
							.find('._densidade')
							.val()
						;
	
		var espessura = $(tr_item_estacao)
							.find('._espessura')
							.val()
						;	
                        
		var modelo = $(tr_item_estacao)
							.find('._modelo-id')
							.val();
                        
		var tamanho = $(tr_item_estacao)
							.find('._tamanho')
							.val();
						;
                        
		var cor = $(tr_item_estacao)
							.find('._cor-id')
							.val();
						;
                        
		var perfil = $(tr_item_estacao)
							.find('._perfil-sku')
							.val()
						;

		var bloco	  = $(tr_item_estacao)
							.hasClass('bloco-novo')
						;
						
		$(tr_item_estacao)
			.closest('.estacao-bloco')
			.find('._densidade-ultimo')
			.val(densidade);
	  						
						
		$(tr_item_estacao)
			.closest('.estacao-bloco')
			.find('._espessura-ultimo')
			.val(espessura);
						
		$(tr_item_estacao)
			.closest('.estacao-bloco')
			.find('._modelo-ultimo')
			.val(modelo);
						
		$(tr_item_estacao)
			.closest('.estacao-bloco')
			.find('._tamanho-ultimo')
			.val(tamanho);
						
		$(tr_item_estacao)
			.closest('.estacao-bloco')
			.find('._cor-ultimo')
			.val(cor);
    
		$(tr_item_estacao)
			.closest('.estacao-bloco')
			.find('._perfil-ultimo')
			.val(perfil);
	
		$(tr_item_estacao)
			.closest('.estacao-bloco')
			.find('._bloco-ultimo')
			.val(bloco);        

	}
	
	/**
	 * Regras para quebra de talões.
	 * @param {object} tr_item_estacao
	 * @param {int} restante
	 * @param {float} qtd_total
	 * @param {int} fator_divisao
	 */
	function dividirItemConsumo(tr_item_estacao, restante, qtd_total, fator_divisao) {

		var restante		= (restante > 0) ? restante : fator_divisao;
		var rest			= qtd_total;
		var bloco_novo		= false;

		var bloco_ant	    = $(tr_item_estacao).closest('.estacao-bloco').find('._bloco-ultimo').val();
		var densidade_ant	= $(tr_item_estacao).closest('.estacao-bloco').find('._densidade-ultimo').val();
		var espessura_ant	= $(tr_item_estacao).closest('.estacao-bloco').find('._espessura-ultimo').val();
		var modelo_ant	    = $(tr_item_estacao).closest('.estacao-bloco').find('._modelo-ultimo'   ).val();
		var tamanho_ant	    = $(tr_item_estacao).closest('.estacao-bloco').find('._tamanho-ultimo'  ).val();
		var cor_ant 	    = $(tr_item_estacao).closest('.estacao-bloco').find('._cor-ultimo'      ).val();
		var perfil_ant	    = $(tr_item_estacao).closest('.estacao-bloco').find('._perfil-ultimo'   ).val();

		var densidade		= $(tr_item_estacao).find('._densidade').val();         
		var espessura		= $(tr_item_estacao).find('._espessura').val();
		var modelo   		= $(tr_item_estacao).find('._modelo-id').val();
		var tamanho   		= $(tr_item_estacao).find('._tamanho').val();
		var cor        		= $(tr_item_estacao).find('._cor-id').val();
		var perfil   		= $(tr_item_estacao).find('._perfil-sku').val();
        
        var quebra_sku      = $('._quebra-talao-sku').val();
 
        if (bloco_ant == 'true') {
            $(tr_item_estacao).addClass('bloco-novo'); 
        } else {
            $(tr_item_estacao).removeClass('bloco-novo'); 
        }

        //Verifica se a densidade e espessura estão diferentes
		if (    (
                    (densidade_ant !== densidade) || 
                    (espessura_ant !== espessura) || 
                    (modelo_ant    !== modelo   ) ||
                    ((tamanho_ant   !== tamanho || cor_ant !== cor)  && (quebra_sku.trim() == '1')) ||
                    (perfil_ant    !== perfil   ) 
                ) 
                    && 
                parseFloat(densidade_ant) > 0 ) {  
            
			bloco_novo = true;          
		}
		//utilizar o resto de um bloco no novo bloco
		else if ( qtd_total > restante ) {
        
			bloco_novo = true;          
			addTrEstacao(tr_item_estacao, restante);
			rest -= restante;   			
		}         

		//partes inteiras do talão
		for (var i = 0; rest >= fator_divisao; i++) {
			
			$(tr_item_estacao).toggleClass('bloco-novo');            

			addTrEstacao(tr_item_estacao, fator_divisao);   
			rest -= fator_divisao;
		}
  
		//parte fracionada do talão
		if ( rest > 0 ) {
			if ((i > 0) || bloco_novo) {
                $(tr_item_estacao).toggleClass('bloco-novo');
            }
			addTrEstacao(tr_item_estacao, rest);
		}

		//Definir restante.
		//verifica se é um novo bloco
		if ( bloco_novo ) {
			
			rest = fator_divisao - rest; 
		}
		else {
			
			//se o restante da qtd total atual é menor do que o restante do bloco antigo
			if ( rest < restante ) {
				rest = restante - rest; // ex.: 100 - 30 = 70 (ainda restaria 70 do bloco antigo)
			}
			else {                
				rest = fator_divisao + restante - rest; // ex.: 150 + 30 - 100 = 80 (qtd restante para o novo bloco)
			}
		}
  
		//aplicar restante no input '._qtd-restante'
		$(tr_item_estacao)
			.closest('.estacao-bloco')
			.find('._qtd-restante')
			.val(rest)
			.inputToTd()
		;

		//remover tr já utilizado para os cálculos
		$(tr_item_estacao)
			.first()
			.remove()
		;

	}

/*
	function dividirItem(tr_item_estacao, restante, qtd_total, fator_divisao) {
		

		//pegar as qtds
		var qtd_prog = [];

		qtd_prog =	[
						44.3282 ,
						2.728   ,
						52.0593 ,
						89.1161 ,
						5.8031  ,
						39.3542 ,
						47.9778 ,
						14.1258 ,
						19.8962 ,
						69.0379 ,
						17.804  ,
						24.9805 ,
						27.356  ,
						93.291  ,
						0.6858  ,
						81.0621 ,
						34.0714 ,
						72.5391 ,
						19.4572 ,
						46.1838 ,
						50.795  ,
						54.1198 
					];
			
		qtd_prog.sort(function(a, b){return b-a;});
		var cota = 150;
		var bloco = null;
		var bloco_atual = null;

		//Laço formador de bloco
		for (var i = 0; i < qtd_prog.length; i++) {

			//Se é um bloco novo ou é o início do laço
			if ( bloco_atual === null || bloco === bloco_atual ) {

				//Reinicializa um bloco 
				bloco_atual	= qtd_prog[0];
				//Remove o talão utilizado
				qtd_prog.splice(0,1);

			}

			//Laço de varredura de talões restantes para composição do bloco
			for (var k = 0; k < qtd_prog.length; k++) {

				//Pega o proximo talão a compor o bloco
				var talao_prox	= qtd_prog[k];

				//Soma o proximo talão ao bloco atual
				var soma = bloco_atual + talao_prox;

				//Verifica se a soma excede a cota
				if ( soma > cota ) continue;

				//Aplica o valor da soma ao bloco atual
				bloco_atual = soma;

				//Remove do array o talão utlizado
				qtd_prog.splice(k,1);

				//Verifica se foi feito a varredura em todos os talões

				bloco = bloco_atual;


			}


		}
		
	}
*/

	function calcularQtd(id_item) {
			
        var qtd_taloes             = false;
		var qtd_taloes_prog        = 0;
		var qtd_taloes_saldo_final = 0;
		var qtd_taloes_total       = 0;
		var qtd_prog		= 0;
		var qtd_saldo_final = 0;
		var qtd_total       = 0;
        
        if ( $('._qtd-taloes').length > 0 ) {
            qtd_taloes = true;
        }

		$('.conteudo-filtro .up-container')
			.find('[data-item-consumo="'+id_item+'"]')
			.each(function() {

                if ( qtd_taloes ) {
                    //qtd programada
                    qtd_taloes_prog +=	parseFloat(
                                    $(this)
                                        .find('._qtd-taloes')
                                        .val()
                                );
                }
                    
				//qtd programada
				qtd_prog +=	parseFloat(
								$(this)
									.find('._qtd-prog')
									.val()
							);

			})
		;

		$('.table-22040')
			.find('[data-item-consumo="'+id_item+'"]')
			.each(function() {

                if ( qtd_taloes ) {
                    qtd_taloes_total = parseFloat(
                                    $(this)
                                        .find('._qtd-taloes-total')
                                        .val()
                                );

                    //qtd à programar restante
                    qtd_taloes_saldo_final = (qtd_taloes_total - qtd_taloes_prog).toFixed(decimal);

                    $(this)
                        .find('._qtd-taloes')
                        .val(qtd_taloes_saldo_final)
                        .inputToTd(0)				
                    ;                

                    $(this)
                        .find('._qtd-taloes-max')
                        .val(qtd_taloes_saldo_final)
                    ;      
                }

				qtd_total = parseFloat(
								$(this)
									.find('._qtd-total')
									.val()
							);

				//qtd à programar restante
				qtd_saldo_final = (qtd_total - qtd_prog).toFixed(decimal);

				$(this)
					.find('._qtd-prog')
					.val(qtd_saldo_final)
					.inputToTd()				
				;
				
				//desabilitar o 'tr' caso a qtd seja 0.
				if ( qtd_saldo_final == 0 ) {
					
					$(this)
						.attr('disabled', true)
						.prop('disabled', true)
						.find('button')
						.attr('disabled', true)
					;
					
				}
				else {
					
					$(this)
						.removeAttr('disabled')
						.prop('disabled', false)
						.find('button')
						.removeAttr('disabled')
					;
					
				}

			})
		;
			
	}
		

	function gravarRemessaProcessada() {
		
		/**
		 * Verificar se algum item foi escolhido.
		 */
		function verificarGravar() {
			
			var existe_item = false;

			$('.popup button.js-gravar')
				.click(function() {
					
					$('.popup .up-container table tbody')
						.each(function() {

							if ( $(this).find('tr').length > 1 ) {	//acima de 1 devido à msg de vazio

								existe_item = true;

							}

						})
					;

					if ( !existe_item ) {

						showAlert('Escolha algum item da remessa.');
						return false;

					}
					else {
						
						verif_atualizar = false;
						
					}
					
					//verifica se houve erro ao gravar
					setTimeout(function() {
						
						if ( $('.alert').is(':visible') ) {
							verif_atualizar = true;
						}
						
					}, 1000);

				})
			;
			
		}
		
		verificarGravar();
		
	}

	function gerarManual() {
		
        var self = this;
        this.events                     = events;
        this.processar                  = processar;
        this.processarRemessaAcumulada  = processarRemessaAcumulada;
        this.processarRemessaDetalhada  = processarRemessaDetalhada;
        this.addTrBlocoDetalhado        = addTrBlocoDetalhado;
        this.calcularFluxo              = calcularFluxo;
        this.valoresHidden              = valoresHidden;
        this.identifBlocos              = identifBlocos;
       
		/**
		 * Processar remessa acumulada
		 * 
		 * @param {object} rem_proc
		 */
        function processarRemessaAcumulada(rem_proc) {
        
            $(rem_proc)
                .find('.up-bloco')
                .each(function() {

                    $(this)
                        .find('.estacao-bloco')
                        .each(function() {

                            //LINHA POR LINHA DE CADA ESTAÇÃO						
                            $(this)
                                .find('tbody tr')
                                .each(function() {

                                    if ( $(this).children().hasClass('dataTables_empty') ) {
                                        return;
                                    }

                                    var tr_item_estacao = $(this);

                                    var restante		= parseFloat(
                                                            $(this)
                                                                .closest('.estacao-bloco')
                                                                .find('._qtd-restante')
                                                                .val()
                                                          );

                                    var qtd_total		= parseFloat(
                                                            $(this)
                                                                .find('._qtd-prog')
                                                                .val()
                                                          );

                                    var fator_divisao	= parseFloat(
                                                            $(this)
                                                                .find('._fator-divisao')
                                                                .val()
                                                          );

                                    if ( fator_divisao < 1 ) {
                                        var modelo	= $(this).find('.modelo').text();
                                        throw new Error('Ocorreu uma falha. O modelo ' + modelo + ' está sem cota de talão configurada.');
                                    }

                                    dividirItemConsumo( tr_item_estacao, restante, qtd_total, fator_divisao );								
                                })
                            ;

                        })
                    ;

                })
            ;
		}
        
        /**
         * Verifica se os itens da projeção de consumo possuem fluxo produtivo
         * @param {type} itens_dist Itens da projeção de consumo distribuidos
         * @param {function} callback Função a ser executada caso haja sucesso
         * @returns {bool}
         */
        function calcularFluxo(itens_dist,callback)
        {
            //var erros			= "Os seguintes produtos estão sem fluxo produtivo alimentado ou configurado:<br/>\n";
            //var exibe_erro		= false;
            var deferreds		= [];
			var identif_bloco	= 0;
			
			var bloco			= [];
			var modelo			= [];
			var modelo_desc		= [];
			var cor				= [];
			var tamanho			= [];
			var qtd				= [];
			var up_id			= [];
			var tamanho_desc	= [];
			var up_desc			= [];
						
			$(itens_dist)
                .find('tbody tr')
				.each(function() {
					
					//Será avaliado bloco por bloco, por isso essa verificação
					//de identificador do bloco
					if ( !$(this).hasClass('bloco-modelo') ) {
						return;
					}
					
					var possui_siblings = true;
					var tr_prox			= $(this);

					//loop dentro dos itens do bloco
					while( possui_siblings ) {
						
						tr_prox = $(tr_prox).next();

						//último 'tr'
						if ( $(tr_prox).is(':last-child') ) {
							
							possui_siblings = false;
							
						}
						
						//identificador do bloco
						if ( $(tr_prox).hasClass('bloco-modelo') ) {
							
							possui_siblings = false;
							
						}
						//soma as qtd dentro do bloco
						else {

							bloco		.push( identif_bloco );
							modelo		.push( $(tr_prox).find('._modelo-id'	).val()  );
							modelo_desc .push( $(tr_prox).find('.modelo'		).text()  );
							cor			.push( $(tr_prox).find('._cor-id'		).val()  );
							tamanho		.push( $(tr_prox).find('._tamanho'		).val()  );
							qtd			.push( $(tr_prox).find('._qtd-prog'		).val()  );
							up_id		.push( $(tr_prox).find('._up'			).val()  );
							tamanho_desc.push( $(tr_prox).find('.tamanho'		).text() );
							up_desc		.push( $(tr_prox).closest('.up-bloco').children('label').text()  );

						}

					}
					
					identif_bloco++;

				})
			;
			
			var dados = {
				bloco		: bloco,
				_modelo		: modelo,
				modelo_desc	: modelo_desc,
				_cor		: cor,
				_tamanho    : tamanho,
				_qtd_prog	: qtd,
				_up			: up_id,
				up_desc		: up_desc
			};

			var success = function(resposta) {

				var i = 0;
				$(itens_dist)
					.find('tbody tr.bloco-modelo')
					.each(function() {
				
						var tempo_total		= parseFloat(resposta[i].TOTAL);
						/*
						var modelo			= [];
						var up_desc			= [];

						//Verifica se o produto está com algum tempo alimentado
						if ( tempo_total <= 0 ) {
							
							modelo		.push( $(this).find('.modelo'	).text()  );
							up_desc		.push( $(this).closest('.up-bloco').children('label').text() );							

							exibe_erro = true;
							erros = erros + 
								"Modelo: "	+  modelo  + " - "
											+  up_desc + "<br/>";
							;

						}
						*/

						$(this)
							.append(
								$(input_clone)
									.clone()
									.attr('name','_tempo_total[]')
									.val( tempo_total )
							)
						;

						$(this)
							.find('.tempo')
							.text( formataPadraoBr(''+tempo_total) )
						;
						
						i++;
						
					})
				;

			};

			deferreds.push(
				execAjax1(
					'POST', 
					'/_22040/tempo',
					dados,
					success
				)
			);
			
			//Verifica se houve algum produto sem fluxo produtivo 
			$.when
				.apply(null, deferreds)
				.done(function() {
					callback();
					/*if ( exibe_erro ) {
						showAlert(erros);
					} 
					else {
						callback();
					}*/
				})
			;
        }
		
		
		/**
		 * Adicionar 'tr' que identifica o bloco (talão acumulado do detalhado).
		 * 
		 * @param {element} rem_proc
		 */
		function addTrBlocoDetalhado(rem_proc) {
			
			function addTalao(tr_atual) {

				var tr_bloco	=	$(tr_atual)
										.clone()
										.addClass('bloco-modelo')
									;

				$(tr_bloco)
					.find('input')
					.remove()
				;

				$(tr_bloco)
					.find('.cor')
					.remove()
				;

				$(tr_bloco)
					.find('.tamanho')
					.remove()
				;

				$(tr_bloco)
					.find('.modelo')
					.attr('colspan', 3)
				;

				$(tr_bloco)
					.insertBefore( $(tr_atual) )
				;
			}
 
			$(rem_proc)
				.find('.up-bloco')
				.each(function() {

					$(this)
						.find('.estacao-bloco')
						.each(function() {

							//LINHA POR LINHA DE CADA ESTAÇÃO						
							$(this)
								.find('tbody tr')
								.each(function() {

									//Guarda o id do talão atual
									var talao_atual		=	$(this)
																.find('._remessa-talao-id')
																.val()
															;
									//Guarda o id do talão anterior
									var talao_anterior	=	$(this)
																.prev()
																.find('._remessa-talao-id')
																.val()
															;

									
									if ( $(this).children().hasClass('dataTables_empty') ) {
										return;
									}
									
									
									
									if ( typeof talao_anterior === 'undefined') {
										
										addTalao($(this));
										
										return;
									}
									
									//Se o talão anterior for diferente do atual, deverá trocar a cor
									if ( talao_anterior !== talao_atual ) {

										addTalao($(this));
									} 					
	
									
								})
							;
						})
					;
				})
			;
			
		}
		
        /**
         * Processar remessa detalhada
         * @param {object} rem_proc
         * @returns {void}
         */
        function processarRemessaDetalhada(rem_proc)
        {

			$(rem_proc)
				.find('.up-bloco')
				.each(function() {

					$(this)
						.find('.estacao-bloco')
						.each(function() {

							//LINHA POR LINHA DE CADA ESTAÇÃO						
							$(this)
								.find('tbody tr')
								.each(function() {	
									
									if ( $(this).children().hasClass('dataTables_empty') ) {
										return;
									}
									
									//Guarda o id do talão atual
									var talao_atual		=	$(this)
																.find('._remessa-talao-id')
																.val()
															;
									//Guarda o id do talão anterior
									var talao_anterior	=	$(this)
																.prev()
																.find('._remessa-talao-id')
																.val()
															;
													
									//Se for indefinido, é porque o item atual é o primeiro da lista. Então deverá ser amarelo.
									if ( typeof talao_anterior === 'undefined') return;
									
									//Se o talão anterior for diferente do atual, deverá trocar a cor
									if ( talao_anterior !== talao_atual ) {

										//Verifica se o talão anterior possui a class de bloco novo
										if ( $(this).prev().hasClass('bloco-novo') ) {

											$(this)
												.removeClass('bloco-novo')
											;
										} else {
											$(this)
												.addClass('bloco-novo')
											;											
										}

									} else {

										if ( $(this).prev().hasClass('bloco-novo') ) {

											$(this)
												.addClass('bloco-novo')
											;
										} else {
											$(this)
												.removeClass('bloco-novo')
											;											
										}
									}
									
                                })
                            ;
						})
					;
				})
			;
        }
        
		/**
		 * Alterar a quantidade do 'tr' que identifica o bloco (talão acumulado).
		 * 
		 * @param {element} tr_corrente
		 */
		function definirQtdTrBloco(tr_corrente) {
			
			var soma_qtd_prog			= 0;
			var soma_qtd_alternativa	= 0;
			var possui_siblings			= true;
			var tr_prox					= $(tr_corrente);
			
			while( possui_siblings ) {
				
				tr_prox = $(tr_prox).next();
				
				//último 'tr'
				if ( $(tr_prox).is(':last-child') ) {

					possui_siblings = false;

				}
				
				//identificador do bloco
				if ( $(tr_prox).hasClass('bloco-modelo') ) {

					possui_siblings = false;

				}
				
				//soma as qtd dentro do bloco
				else {

					soma_qtd_prog			= soma_qtd_prog + parseFloat( $(tr_prox).find('._qtd-prog').val() );
					soma_qtd_alternativa	= soma_qtd_alternativa + parseFloat( $(tr_prox).find('._qtd-alternativa').val() );

				}
				
				if ( isNaN(soma_qtd_prog) ) {
					
					possui_siblings = false;
					
				}
				
			}
			
			$(tr_corrente)
				.find('.qtd-prog')
				.children('span')
				.text( formataPadraoBr(soma_qtd_prog.toFixed(decimal)) )
			;
			
			$(tr_corrente)
				.find('.qtd-alternativa')
				.text( formataPadraoBr(soma_qtd_alternativa.toFixed(decimal)) )
			;
			
		}
		
		/**
		 * Adicionar identificador para blocos.
		 * 
		 * @param {object} rem_proc
		 */
		function identifBlocos(rem_proc) {
			
			var i = 0;
            var y = 0;
			
			$(rem_proc)
				.find('.up-bloco')
				.each(function() {

					$(this)
						.find('.estacao-bloco')
						.each(function() {
							
							i++;

							//LINHA POR LINHA DE CADA ESTAÇÃO						
							$(this)
								.find('tbody tr')
								.each(function() {

									if ( $(this).children().hasClass('dataTables_empty') ) {
										return;
									}

									var classe		= $(this).hasClass('bloco-novo');
									var classe_ant	= $(this).prev().hasClass('bloco-novo');

									if ( classe !== classe_ant ) {

										i++;

									}
									
									if ( $(this).hasClass('bloco-modelo') ) {
										$(this)
											.append(
												input_clone.clone().attr('name','_tempo_talao[]').val(i)
											)
										;
									}

									$(this)
										.children('._bloco-divisor')
										.val(i)
									;
                                    
									$(this)
										.children('._consumo-ref')
										.val(i)
									;
                                    
                                    y++;
                                    
									$(this)
										.children('._talao-divisor')
										.val(y)
									;
                                    
									$(this)
										.children('._consumo-talao')
										.val(y)
									;
									
									
									
									if ( $(this).hasClass('bloco-modelo') ) {
										
										definirQtdTrBloco( $(this) );
										
									}

								})
							;
						})
					;
				})
			;

		}
		
		/**
		 * Adicionar valores aos input hidden no popup.
		 */
		function valoresHidden() {
			
			var remessa		=	$('#remessa')
									.val()
								;
							
			var requisicao	=	$('#remessa')
									.nextAll('._requisicao')
									.val()
								;

			var estab		=	$('#estab')
									.siblings('._estab-id')
									.val()
								;
	
			var familia		=	$('#familia')
									.siblings('._familia-id')
									.val()
								;
	
			var gp			=	$('.consulta_gp_grup')
									.siblings('._consulta_imputs')
									.children('._gp_id')
									.val()
								;
	
			var data_producao =	$('#data-prod')
									.val()
								;
				
			var perfil		=	$('._perfil-valor')
									.val()
								;

			$('.remessa-processada-container')
				.find('.up-container')
				.find('._remessa')
				.val( remessa )
				.siblings('._requisicao')
				.val( requisicao )
				.siblings('._estab')
				.val( estab )
				.siblings('._familia')
				.val( familia.replace(/^0+/, '') ) //remover 0 à esquerda
				.siblings('._gp')
				.val( gp.replace(/^0+/, '') ) //remover 0 à esquerda
				.siblings('._data-producao')
				.val( data_producao )
				.siblings('._perfil')
				.val( perfil.trim() )
			;			
			
		}
		
		/**
		 * Verifica se existe algum item que não tenha sido programado.
		 * @return {boolean}
		 */
		function verificarItensProg() {
			
			var ret = true;
            
            if ( $('#remessa').val() == 'REP' || $('#remessa').val() == 'REQ' ) {
                ret = true;
            } else
			if ( $('.table-22040 tbody tr').length === 0 ) {

				ret = false;
			}
			else {
				
				$('.table-22040 tbody tr')
					.each(function() {

						if ( parseFloat($(this).find('._qtd-prog').val()) > 0 ) {

							ret = false;
							return false;

						}

					})
				;
				
			}
			
			return ret;
		}
        
        function processar(rem_proc)
        {
            //verificar se existem itens não programados
            if ( !verificarItensProg() ) {

                throw new Error('Existem itens não programados.');
            }

            var itens_dist     = $('.conteudo-filtro .up-container');
            var controle_talao = $('._controle-talao').val().trim();


            //clonar e colar as estações no popup
            $(rem_proc)
                .html( itens_dist.clone() )
            ;

            if (controle_talao === 'A') {
                self.processarRemessaAcumulada(rem_proc);
            } 
            else {
                self.processarRemessaDetalhada(rem_proc);
                self.addTrBlocoDetalhado(rem_proc);
            }
        }
        
        function events()
        {
            $('#btn-processar')
                .click(function() {        
		
                    try {
                        
                        var rem_proc       = $('.remessa-processada-container');
                            self.processar(rem_proc);
                            self.calcularFluxo(rem_proc, function()
                            {
                                self.identifBlocos(rem_proc);				
                                self.valoresHidden();

                                //exibir popup
                                $(this).popUp();
                            });

                    } catch(err) {
                        showErro(err.message);
                    }
                })
            ;
        }

	}
	
	function gerarAuto() {
        
        var self = this;
        this.distribuir = distribuir;
        this.processar  = processar;
        
        function distribuir(table_tr) {
            
            return new Promise(function(resolve)
            {
                if (table_tr.length < 1) {
                    resolve(true);
                }
                var deferreds = [];
                

                function listing() {
                    var deferred = $.Deferred();
                    var promises = [];

                    progressBar(0,table_tr.length);
                    //Passa em todos os itens da projeção
                    table_tr
                        .each(function(index) {   
                            //Guardo o tr atual para ser utilizado no setTimeout
                            var _this    = $(this);
                            var qtd_prog = _this.find('._qtd-prog').val();
                            var deferred = $.Deferred();
                            
                            setTimeout(function() {
                                
                                if ( (! _this.is(":disabled")) && qtd_prog > 0 ) {
                                    _this.click(); 
                                    verificacao_perfil = false;
                                    $('.conteudo-filtro .btn-incluir-auto').click();
                                    verificacao_perfil = true;
                                }
                                progressBar(index+1);
                                
                                deferred.resolve();
                            }, 25 * index);
                            promises.push(deferred.promise());
                        })
                    ;

                    $.when.apply($, promises).then(function () {
                            deferred.resolve();
                    });

                    return deferred.promise();
                }

                $.when(listing().done(function () {
                    resolve(true);
                }));
                
            });
        }
        
        function processar() {

            var taloes      = $('.dist-auto .bloco-modelo');
            var estacoes    = $('.remessa-processada-container .estacao-bloco:not(.dist-auto)');
            var n_taloes    = taloes.length;
            var n_estacoes  = estacoes.length;            
            var arr_taloes  = [];
            
            
            /**
             * Laço que passará por todos os talões formados
             */
            $(taloes)
                .each(function(i,talao){
                    
                    /**
                     * Insere no talão o perfil e a posição inicial
                     */
                    var perfil_sku = $(this).next().find('._perfil-sku').val();
                    var perfil_sku_descricao = $(this).next().find('td.perfil span').text();
                    $(this)
                        .attr('index',i+1)
                        .attr('perfil', perfil_sku)
                        .find('td.modelo').text($(this).find('td.modelo').text() + ' (Perfil: '+perfil_sku_descricao+')');

                    /**
                     * Cria o array com os talões detalhados
                     */
                    var arr_detalhe = [];
                    var talao_detalhe = $(this)
                        .nextAll()
                        .each(function(){
                            if ($(this).hasClass('bloco-modelo')) {
                                return false;
                            } else {
                                arr_detalhe.push($(this));
                            }
                        })
                    ;
                
                    /**
                     * Insere no array de talões os talões acumulados e os detalhados
                     */
                    arr_taloes[i] = {
                        talao           : $(this),
                        talao_detalhe   : arr_detalhe
                    };
                    
                })
            ;
            
            /**
             * Ordena os talões por perfil
             */
            var taloes_ord = arr_taloes;
            taloes_ord.sort(function(a,b) {
                var A = a.talao.attr('perfil');
                var B = b.talao.attr('perfil');
                return (A < B) ? -1 : (A > B) ? 1 : 0;
            });
            
            /**
             * Obs.: Variável de escopo da lógica de distribuição paralela
             * Amazena a proxima estação a receber um talão
             */
            var proximo_receber = 0;
            
            /**
             * Início do looping da lógica de distribuição paralela
             */
            for ( var i = 0 ; i < n_taloes ; i++ ) {
                
                /* Armazena o talão atual            */ var that_talao = taloes_ord[i];
                /* Armazena o talão anterior         */ var talao_perfil_anterior   = (typeof taloes_ord[i-1] != 'undefined') ? taloes_ord[i-1].talao.attr('perfil') : '';
                /* Armazena o perfil to talão atual  */ var talao_perfil_atual      = that_talao.talao.attr('perfil');
                
                /**
                 * Verifica se o perfil do talão anterior é diferente do atual,
                 * para que seja resetado a próxima estação que receberá o talão
                 */
                if ( talao_perfil_anterior != talao_perfil_atual ) {
                    proximo_receber = 0;
                }
                
                /**
                 * Início do looping das estações
                 */
                $(estacoes)
                    .each(function(j,that_estacao){
                        
                        
                        /* Verifica se o talão já foi inserido em alguma estação */ var item_adicionado = false;
                    
                        /**
                         * Verifica se a proxima estçaão a receber o talão confere com a estação atual
                         */
                        if ( j == proximo_receber ) {

                            /**
                             * Verifica se a estação atual é compatível com o perfil do talão atual
                             */
                            if ( $(that_estacao).data('perfil-auto').indexOf(talao_perfil_atual) != -1 ) {
                                
                                /**
                                 * Armazena o body da estação
                                 */
                                var tbody = $(that_estacao).find('tbody');

                                /**
                                 * Insere o talão acumulado no body da estação
                                 */
                                tbody.append(that_talao.talao); 

                                /**
                                 * Insere os talões detalhado no body da estação
                                 */
                                $.each(that_talao.talao_detalhe,function(id_detalhe,elemento_detalhe){
                                    tbody.append(elemento_detalhe); 
                                });
                                
                                /**
                                 * Marca como inserido o talão corrente
                                 */
                                item_adicionado = true;
                                
                            } else {
                                
                                /**
                                 * Verifica se está na ultima estação e o talão não foi atribuido a nenhuma estação,
                                 * reinicializa a busca do talão atual
                                 */
                                if ( proximo_receber == n_estacoes-1 ) {
                                  
                                  i--;
                                }
                            }
                            
                            /**
                             * Incrementa a próxima estação 
                             */
                            proximo_receber++;
                        }
                        
                        /**
                         * Verifica se a proxima estação a receber o talão é maior que o número de estações
                         * e reinicializa o proximo a receber
                         */
                        if ( proximo_receber > n_estacoes-1 ) {
                            proximo_receber = 0;
                        }

                        /**
                         * Se o talão já foi inserido em alguma estação, cancela o loop atual
                         */
                        if ( item_adicionado ) {
                            return false;
                        }
                    })
                ;    
                
            };
            
            
            $(estacoes)
                .each(function(i,estacao){

                    var arr_taloes  = [];
                    var tr_estacao = $(this).find('tbody .bloco-modelo');
            
                    $(tr_estacao)
                        .each(function(j){
                            
                            var arr_detalhe = [];
                            $(this)
                                .nextAll()
                                .each(function(){
                                    if ($(this).hasClass('bloco-modelo')) {
                                        return false;
                                    } else {
                                        arr_detalhe.push($(this));
                                    }
                                })
                            ;
                            
                            arr_taloes[j] = {
                                talao           : $(this),
                                talao_detalhe   : arr_detalhe
                            };
                        })
                    ;

                    var taloes_ordx = arr_taloes;
                    taloes_ordx.sort(function(a,b) {
                        var A = parseInt(a.talao.attr('index'));
                        var B = parseInt(b.talao.attr('index'));
                        return (A < B) ? -1 : (A > B) ? 1 : 0;
                    });
                    
                    var add_class   = true;
                        
                    $.each(taloes_ordx, function(id, elemento) {
                        
                        var tbody = tr_estacao.closest('tbody');
                        
                        if ( add_class == true ) {
                            elemento.talao.addClass('bloco-novo');
                        } else {
                            elemento.talao.removeClass('bloco-novo');
                        }
                        
                        tbody.append(elemento.talao); 

                        $.each(elemento.talao_detalhe,function(id_detalhe,elemento_detalhe){
                            
                            elemento_detalhe.find('._up'     ).val(elemento_detalhe.closest('.up-bloco'     ).data('up'     ));
                            elemento_detalhe.find('._estacao').val(elemento_detalhe.closest('.estacao-bloco').data('estacao'));
                            
                            if ( add_class == true ) {
                                elemento_detalhe.addClass('bloco-novo');
                            } else {
                                elemento_detalhe.removeClass('bloco-novo');
                            }
                            tbody.append(elemento_detalhe); 
                        });
                        
                        add_class = !add_class;
                    });
                    
                })
            ;
                    
            
            
        }
        
		$('#btn-distribuir-auto')
            .off('click')
			.click(function() {
                
                var perfis = '';
                $('.estacao-bloco:not(.dist-auto)')
                    .each(function(){
                        perfis = perfis + ' ' + $(this).data('perfil');
                    });
                
                $('.estacao-bloco.dist-auto')
                    .data('perfil',perfis)
                    .find('.estacao-perfil').text(perfis);
                $('.btn-incluir-auto')
                    .data('perfil',perfis);
                
                var table_tr = $('.table-22040 tbody tr:not([disabled])');
                    self.distribuir(table_tr).then(function() {
                        try {
                            var rem_proc       = $('.remessa-processada-container');
                                objGerarManual.processar(rem_proc);

                            self.processar();
                                
                            objGerarManual.calcularFluxo(rem_proc, function()
                            {
                                objGerarManual.identifBlocos(rem_proc);				
                                objGerarManual.valoresHidden();
                                
                                //exibir popup
                                $(this).popUp();
                                
                                //Passa em todos os itens da projeção
                                $('.taloes-automatico tr')
                                    .each(function(index,teste) {   
                                        var _this = $(this);
                                            _this.click(); 
                                            $('.btn-excluir-auto').click();
                                    })
                                ;
                            }); 
                        } catch(err) {

                            //Passa em todos os itens da projeção
                            $('.taloes-automatico tr')
                                .each(function(index,teste) {   
                                    var _this = $(this);
                                        _this.click(); 
                                        $('.btn-excluir-auto').click();
                                })
                            ;
                            
                            showErro(err.message);
                        }
                        
                    });
                
                
			})
        ;
	}
	
	function dragAndDrop() {
		
		var tr_22040 = $('.table-22040 tbody tr');
		//var ja_up = false;
				
		function gpToUp() {
			
			$(tr_22040)
				.draggable({
					appendTo			: "body",
					helper				: "clone",
					cursor				: "-webkit-grabbing",
					cursorAt			: { top: 50, left: 110 },
					scroll				: true, 
					scrollSensitivity	: 100,
					drag				: function( event, ui ) {
						
						//desabilitar overflow da tabela principal
						$(this)
							.closest('.dataTables_scrollBody')
							.addClass('dragging');

						$(this)
							.closest('table')
							.addClass('dragging');

					},
					stop				: function( event, ui ) {

						//habilitar overflow da tabela principal
						$(tr_22040)
							.closest('.dataTables_scrollBody')
							.removeClass('dragging');

						$(tr_22040)
							.closest('table')
							.removeClass('dragging');

					}
				});

			$(".estacao-bloco")
				.droppable({
	//				accept		: ".estacao-bloco",
					tolerance	: "pointer",
					drop		: function( event, ui ) {

//						console.log( 'estdrop' );
//						console.log( event.target );
//						console.log( $(event.target).hasClass('estacao-bloco') );
						
						var clone = $(ui.helper).clone(true);
						$(clone).removeClass('ui-draggable-dragging');
						
						$(this)
							.find('tbody')
							.append( clone )	//add tr (drag) à estação
							.find('tr')
							.first()
							.hide(); //esconder mensagem 'Arraste'
					
						$(ui.draggable)
							.children('td')
							.last()
							.prev()
							.text('0,0000 KG');
					
//						//se for de estação para estação
//						if( $(event.target).parents('.estacao-bloco').length > 0 ) {
//							return false;
//						}
//
						//if( !ja_up )							
							upToGp();

					}
				});
			
		}
		
		function upToGp() {
			
//			console.log('uptogp');
			
			$(".estacao-bloco tbody tr")
				.draggable({
					cancel		: ".dataTables_empty",
					appendTo	: "body",
					helper		: "clone",
					cursor		: "-webkit-grabbing",
					cursorAt	: { top: 50, left: 110 },
//					start		: function( event, ui ) {
//						
//						//se for de estação para estação
//						if( $(event.target).parents('.estacao-bloco').length > 0 ) {
//							ja_up = true;
//						}
//						
////						upToGp();
//						
//					},
					drag		: function( event, ui ) {

//						console.log('dragestacaotr');
//						console.log( $(event.target).parents('.estacao') );
						
						if ( $(this).siblings().length === 1 ) {

							//exibir mensagem 'Arraste'
							$(this)
								.siblings()
								.find('.dataTables_empty')
								.parent()
								.show();

						}

					}
				});

			$(".table-22040")
				.droppable({
					//accept		: ".estacao-bloco",
					tolerance	: "pointer",
					drop		: function( event, ui ) {
								
//						//retornar dados da estação para a tabela principal
//						$(this)
//							.find('tbody')
//							.append( ui.draggable );
					
						var tr_gp = '';
						var tr_up = '';
						
						//valores da 'tr' de GP
						$(this)
							.find('tr')
							.each(function() {
								
								$(this)
									.children('td')
									.each(function() {
										
										tr_gp += $(this).text();
								
									});
						
							});
						
						//valores da 'tr' de UP
						$(ui.draggable)
							.find('tr')
							.each(function() {
								
								$(this)
									.children('td')
									.each(function() {
										
										tr_up += $(this).text();
								
									});
						
							});
							
						
						if ( tr_gp === tr_up ) {
							
							//retornar dados da estação para a tabela principal
							$(this)
								.find('tbody')
								.append( ui.draggable );
							
						}

					}
				});
			
		}
		
		gpToUp();

	}
	
	function manipularConsumo() {
		
		function inserir() {
			
			function valoresHidden(btn) {
				
				var up_bloco		=	$(btn)
											.closest('.up-bloco')
										;
										
				var estacao_bloco	=	$(btn)
											.closest('.estacao-bloco')
										;

				$(up_bloco)
					.find('._up')
					.val( $(up_bloco).data('up') )
					.siblings('._estacao')
					.val( $(estacao_bloco).data('estacao') )
				;
				
			}
			
			/**
			 * Verificar se o perfil do item é compatível com o da estação.
			 * 
			 * @param {element} tr_consumo_selec
			 * @param {element} perfil_estacao
			 * @returns {Boolean}
			 */
			function verificarPerfil(tr_consumo_selec, estacao) {
				
				var verif_perfil = true;
					
                var perfil_estacao      = estacao.data('perfil');                    
                    
				$(tr_consumo_selec)
					.each(function() {

						var perfil_consumo	=	$(this)
													.find('._perfil-sku')
													.val()
												;
                        
                            if ( perfil_estacao.indexOf(perfil_consumo) == -1 && verificacao_perfil ) {
                                showAlert('Perfil do Item de Consumo é diferente do Perfil da Estação.');
                                verif_perfil = false;
                                return false;
                            }
					})
				;
				
				return verif_perfil;
					
			}
			
			$(document)
				.on('click', '.conteudo-filtro .btn-incluir-consumo', function() {

					var tr_consumo_selec	=	$('.table-22040')
													.find('tr.selected')
												;				
						
					var tbody_estacao		=	$(this)
													.closest('.estacao-bloco')
													.find('tbody')
												;
					
					
					if ( !verificarPerfil(tr_consumo_selec, $(this)) ) {
						return false;
					}
                    
					//clicar no botão de confirmar a edição de quantidade
//					$(btn_conf_qtd)
//						.click()
//					;
					
					//caso o valor de qtd inserido seja inválido, pára a execução
//					if ( $(btn_conf_qtd).data('valor-valido') === false ) {
//						return false;
//					}

					//Copiar tr para a estação
					$(tr_consumo_selec)
						.each(function() {
							
							$(this)
								.removeClass('selected')
								.find('.selec-item-consumo')
								.removeProp('checked')
								.change()
							;
							
							$(this)
								.clone(true)
								.appendTo( tbody_estacao )
							;
							
							var id_item		=	parseFloat( 
													$(this)
														.data('item-consumo')
												);
							
							calcularQtd( id_item );
							
						})
					;
								
					valoresHidden( $(this) );				
				   
				    //esconder mensagem 'arraste...'
					$(tbody_estacao)
						.find('.dataTables_empty')
						.parent()
						.hide()
					;
				})
            ;
		}
		
		function excluir() {
			
			$('.btn-excluir-consumo')
				.click(function() {

					var tbody_estacao		=	$(this)
													.closest('.estacao-bloco')
													.find('table.estacao tbody')
												;
												
					var tr_estacao_selec	=	$(tbody_estacao)
													.find('tr.selected')
												;
					
					$(tr_estacao_selec)
						.each(function() {
							
							//remover linha selecionada
							$(this)
								.remove()
							;
							
							var item_consumo	=	$(this)
														.data('item-consumo')
													;
							
							calcularQtd( item_consumo );
							
						})
					;
					
					//mostrar mensagem 'Arraste...'
					if ( $(tbody_estacao).find('tr').length === 1 ) {
					
						$(tbody_estacao)
							.find('.dataTables_empty')
							.parent()
							.show()
						;
					
					}
					
					//desabilitar botão excluir
					$(this)
						.prop('disabled', true)
					;
					
					//desabilitar botões de ordenação
					$(this)
						.parent()
						.siblings('.acoes-ordenar-estacao')
						.children('button')
						.prop('disabled', true)
					;

				})
			;
			
		}
		
		function ordenar() {
			
			$(document)
				.on('click', '.btn-subir', function() {
					
					var tr_estacao_selec	=	$(this)
													.closest('.estacao-bloco')
													.find('.selec-item-consumo:checked')
													.closest('tr')
												;
					
					$(tr_estacao_selec)
						.insertBefore( tr_estacao_selec.prev() );
					;
					
				})
				.on('click', '.btn-descer', function() {
					
					var tr_estacao_selec	=	$(this)
													.closest('.estacao-bloco')
													.find('.selec-item-consumo:checked')
													.closest('tr')
												;
					
					$(tr_estacao_selec)
						.insertAfter( tr_estacao_selec.next() );
					;
					
				})
			;
			
		}
		
		function verificar() {
			
			function verificarIncluir() {
				
				$('.table-22040')
					.on('change', '.selec-item-consumo', function() {

						$('.btn-incluir-consumo')
							.prop('disabled', !$(this).is(':checked'))
						;
						
					})
				;
				
			}
			
			function verificarExcluirOrdenar() {
				
				$('table.estacao')
					.on('change', 'input[type="radio"]', function() {

						var up_container	=	$(this)
													.closest('.up-container')
												;
												
						var estacao_bloco	=	$(this)
													.closest('.estacao-bloco')
												;
							
						//Desabilitar todos os botões das estações
						$(up_container)
							.find('.btn-excluir-consumo, .btn-subir, .btn-descer')
							.prop('disabled', true)
						;
						
						//Desmarcar todos os 'tr' das estações
						$(up_container)
							.find('tbody tr')
							.removeClass('selected')
						;
						
						//Desmarcar todos os 'tr' da remessa
						$('.table-22040')
							.find('tbody tr')
							.removeClass('selected')
							.find('input[type="radio"]')
							.trigger('change')
						;

						//Marcar 'tr' e habilitar botões de excluir/ordenar quando o 'radio' estiver marcado
						if ( $(this).is(':checked') ) {
							
							$(this)
								.closest('tr')
								.addClass('selected')
							;
							
							$(estacao_bloco)
								.find('.btn-excluir-consumo, .btn-subir, .btn-descer')
								.prop('disabled', false)
							;
							
						}

					})
				;
				
			}
			
			verificarIncluir();
			verificarExcluirOrdenar();
			
		}
		
		inserir();
		excluir();
		ordenar();
		verificar();
		
	}
	
	function marcarTr() {
		
		/**
		 * Verifica e marca os consumos com talão igual ao consumo selecionado.
		 * 
		 * @param {string} talao_id
		 * @param {element} tr_siblings
		 */
		function verificarTalaoIgual(talao_id, tr_siblings) {
									
			$(tr_siblings)
				.each(function() {

					var talao_id_ant	=	$(this)
												.find('._remessa-talao-id')
												.val()
											;
					
					if (talao_id === talao_id_ant) {
						
						$(this)
							.addClass('selected')
						;
						
					}
					
				})
			;
			
		}
		
		$(document)
			.on('click', '.table-22040 tbody tr, table.estacao tbody tr', function() {
				
				var controle_talao	=	$('._controle-talao')
											.val()
											.trim()
										;
										
				var talao_id		=	$(this)
											.find('._remessa-talao-id')
											.val()
										;
										
				//Só prossegue se for talão detalhado
				if (controle_talao === 'A' || talao_id === '') {
					return false;
				}
										
				verificarTalaoIgual( talao_id, $(this).siblings() );

			})
		;
	}
	
	/**
	 * Pedir confirmação ao atualizar a página.
	 */
	function verificarAtualizar() {
			
//		window.onbeforeunload = function() { 
//
//			if ( verif_atualizar == true ) {
//				
//				return 'Se continuar, todas as alterações NÃO gravadas serão perdidas.';
//				
//			}
//
//		};
		
	}
    
    function getRemessa() {
        if ( parseInt($('#remessa').val()) > 0 ) {
            $('#selec-remessa').click();
        }
    }
	
	$(function() {
		
		destruirDataTable();
		ativarDatatable();
		ativarDatatableMenor();
		limpar();
		pesqFamiliaRemessa();
		familiaHiddenParaText();
		editarQtd();
		editarQtdTaloes();
		editarQtdCota();
		filtrar();
		gerarAuto();
		objGerarManual.events();
		gravarRemessaProcessada();
		marcarTr();
		verificarAtualizar();
        getRemessa();
		
	});
	
})(jQuery);

//# sourceMappingURL=_22040-create.js.map
