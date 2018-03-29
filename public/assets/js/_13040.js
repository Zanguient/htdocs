/**
 * Script com funções da tela 13040
 * Produtos para Geração de Ordem de Compra 
 * */


(function($) {

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

    {/** Calcula campos em tempo de execução */
        function clickRadioItem() {
            //Calcula o total para os itens selecionados
            $('input[type=radio],input[type=checkbox]').click(function(){
                var total 			= 0;
                var previousValue	= $(this).attr('previousValue');
                var name 			= $(this).attr('name');
                var coluna;

                coluna = 
                $(this) 			//<- botão do radio
                    .parent()		//<- label do radio
                    .parent()		//<- div radio
                    .parent()		//<- td
                    .attr('coluna')	//codigo coluna
                ;								

                {// Responsavel por realizar o 'uncheck' do radio button
                    if (previousValue === 'checked'){
                        $(this).removeAttr('checked');
                        $(this).attr('previousValue', false);

                    } else {
                        $("input[name="+name+"]:radio").attr('previousValue', false);
                        $(this).attr('previousValue', 'checked');	

                    }	
                }

                {//Desmarca todos os campos required e input head
                    $('input:radio, input:checkbox').each(function () {
                        //código da coluna
                        coluna = 
                        $(this) 			//<- botão do radio
                            .parent()		//<- label do radio
                            .parent()		//<- div radio
                            .parent()		//<- td
                            .attr('coluna')	//codigo coluna
                        ;	

                        //remove required da coluna
                        $('td[coluna=' + coluna + ']')
                            .children('.form-group')
                            .find('input, select')
                            .removeAttr('required')
                        ;	

                        //remove o name para que não seja enviado no submit
                        $('div[coluna=' + coluna + ']')		//<- coluna head
                            .children()						//<- filho da coluna
                            .siblings('input')				//<- irmãos input
                            .each(function () {				//<- todos os inputs
                                $(this)						//<- input
                                    .removeAttr('name')		//<- remove o atritubo name
                                ;
                            })
                        ;
                        
                        //remove o required da operacao
                        $(this)                             //<- botão do radio
                            .parents('tr')                  //<- linha
                            .first()                        //<- linha corrente
                            .find('.operacao-descricao')    //<- input operacao
                            .removeAttr('required')         //<= remove o atritubo required
                        ;
                        
                        //remove o required do ipi
                        $(this)                             //<- botão do radio
                            .parents('tr')                  //<- linha
                            .first()                        //<- linha corrente
                            .find('.perc-ipi')              //<- input ipi
                            .removeAttr('required')         //<= remove o atritubo required
                        ;
                        
                        //remove o required da data entrega
                        $(this)                             //<- botão do radio
                            .parents('tr')                  //<- linha
                            .first()                        //<- linha corrente
                            .find('.data-entrega')          //<- input data entrega
                            .removeAttr('required')         //<= remove o atritubo required
                        ;
                        
                        
                    });		
                }

                $('input:radio, input:checkbox').each(function () {		

                    coluna = 
                    $(this) 			//<- botão do radio
                        .parent()		//<- label do radio
                        .parent()		//<- div radio
                        .parent()		//<- td
                        .attr('coluna')	//codigo coluna
                    ;	

                    if ($(this).prop('checked')) {

                        {//Calcula o total
                            total = 
                            total +
                            formataPadrao(
                                $(this) 			  //<- botão do radio
                                    .parent() 		  //<- label do radio
                                    .children('span') //<- campo do valor
                                    .text() 		  //<- valor
                            );		
                        }

                        $(this) 			 				//<- botão do radio
                            .parent()						//<- label do radio
                            .siblings('input')				//<- irmãos de label
                            .each(function () {				//<- todos os inputs
                                $(this)						//<- input
                                    .attr('name',			//<- atributo name
                                        $(this)				//<- input
                                            .attr('field')	//<- atributo field
                                    );
                            })
                        ;

                        {//Marca os campos required e campos input head
                            
                            //add required na coluna
                            $('td[coluna=' + coluna + ']')
                                .children('.form-group')
                                .find('input, select')
                                .attr('required',true)
                            ;

                            //add o atritubo name para ser enviado no submit
                            $('div[coluna=' + coluna + ']')
                                .children()
                                .siblings('input')
                                .each(function () {				//<- todos os inputs
                                    $(this)						//<- input
                                        .attr('name',			//<- atributo name
                                            $(this)				//<- input
                                                .attr('field')	//<- atributo field
                                        );
                                })
                            ;
                            
                            //add o required da operacao
                            $(this)                             //<- botão do radio
                                .parents('tr')                  //<- linha
                                .first()                        //<- linha corrente
                                .find('.operacao-descricao')    //<- input operacao
                                .attr('required',true)          //<= add o atritubo required
                            ;     
                            
                            //add o required do ipi
                            $(this)                             //<- botão do radio
                                .parents('tr')                  //<- linha
                                .first()                        //<- linha corrente
                                .find('.perc-ipi')              //<- input ipi
                                .attr('required',true)          //<= add o atritubo required
                            ;    
                            
                            //add o required da data entrega
                            $(this)                             //<- botão do radio
                                .parents('tr')                  //<- linha
                                .first()                        //<- linha corrente
                                .find('.data-entrega')          //<- input data entrega
                                .attr('required',true)          //<= add o atritubo required
                            ;                           
                        }

                    } else {					

                        $(this) 		 		//<- botão do radio
                            .parent()			//<- label do radio
                            .siblings('input')	//<- irmãos de label
                            .each(function () {	//<- todos os inputs
                                $(this).removeAttr('name');
                            })
                        ;
                    }

                });

                ( total > 0 ) ? $('button.js-gravar').removeAttr('disabled') : $('button.js-gravar').attr('disabled',true); 

                total = formataReal( total.toFixed(4) );
                $('.total-calculado').text(total);

            });
        }
    }

    { /** Pesquisar Operações Fiscais */

        var filtro;
        var operacao_campo;
        var operacao_btn_filtro;
        var operacao_btn_filtro_apagar;
        var input_group;
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
            input_linha     = input_group.parents('tr').first();
            _operacao_id    = input_linha.find('input[field="item[operacao][]"]');
            _produto_id     = input_linha.find('input[field="_produto_id"]');
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
    }

    /**
     * Filtrar Empresa
     * @returns void
     */
    function filtroEmpresa() { 
        var tempo_focus;
        var url;

        function filtrarEmpresa( input_group, input, campo_id ) {
            var result_itens;
            var srlz        = $(input_group).find('select, input, textarea').serialize();
            var btn_filtro  = input_group.find('.btn-filtro');           
            
			//ajax
			var type	= "POST",
				url_act	= url,
				data	= srlz,
				success	= function(data) {

                    abreListaPesquisa( input_group );

                    $('.lista-empresa')
                        .html(data)
                    ;

                    //existem dados cadastrados
                    if( data.indexOf('nao-cadastrado') === -1 ) {

                        result_itens = $('ul.empresa li a');

                        selecItemListaEmpresa( result_itens, input_group, input, campo_id);

                        $(result_itens)
                            .focusout(function() {

                                if(tempo_focus) 
                                    clearTimeout(tempo_focus);

                                tempo_focus = setTimeout(function() {

                                    if( !$(result_itens).is(':focus') && 
                                        !$(input).is(':focus') && 
                                        !$(btn_filtro).is(':focus') 
                                    ) {
                                        $(input).val('');
                                        fechaListaPesquisa( input_group );
                                    }

                                }, 200);

                            });

                        $(input)
                            .focusout(function() {

                                if(tempo_focus) 
                                    clearTimeout(tempo_focus);

                                tempo_focus = setTimeout(function() {

                                    if( !$(result_itens).is(':focus') &&
                                        !$(campo_id).val() && 
                                        !$(btn_filtro).is(':focus') 
                                    ) {
                                        $(input).val('');
                                        fechaListaPesquisa( input_group );
                                    }

                                }, 200);

                            });
                    }
                    else {
                        $(campo_id).val('');

                        $(input)
                            .focusout(function() {
                                if( $('.pesquisa-res').children().children().hasClass('nao-cadastrado') ) {
                                    $(input).val('');
                                    fechaListaPesquisa( input_group );
                                }
                            });
                    }
                    bootstrapInit();
                }
			;
				
			execAjax2(type, url_act, data, success, null, btn_filtro);

        }

        function selecItemListaEmpresa(itens, input_group, input, campo_id) { 
			
			$(itens)
				.click(function(e) {
					e.preventDefault();
            
					$(input)
						.val( $(this).nextAll('.descricao').val() )
						.focus()
                    ;
					
					$(campo_id)
						.val( $(this).nextAll('.codigo').val() )
						.trigger('change')
                    ;
                    
					selecionadoEmpresa(input,campo_id);
					fechaListaPesquisa( input_group );
            });

        }
        
		function selecionadoEmpresa(input, campo_id) {
            var btn_filtro         = input.nextAll('.btn-filtro');     
			var btn_filtro_apagar  = input.nextAll('.btn-filtro-apagar'); 
			
			$(input)
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
						.show()
                    ;
                    
					$(this)
						.siblings('input')
						.removeAttr('readonly')
                        .val('')
                    ;                    
                    
					campo_id.val('');             
				})
            ;
		}      

        /**
         * Eventos para o filtro de CCusto.
         */
        function iniciarFiltroEmpresa() {
            var linha_item;
            var click_item;     
                        
			//Se o item já estiver selecionado (tela de update), efetua as devidas ações.
			$('input[field="oc[empresa][]"]').each(function() {
                var input_ref;
                var input_group;
                var input;
                
                input_ref   = $(this).parent('div').attr('coluna');
                input_group = $(this).parents('fieldset').first().find('td[coluna="'+input_ref+'"]');
                input       = input_group.find('input.empresa-descricao');

				if ( ( $(this).val() !== '' ) && ( $(this).val() > 0 ) ) {
			
					selecionadoEmpresa(
						input,
                        $(this)
					);
				}
			});            
            
            //Botão de filtrar
            $('.btn-filtro-empresa').on({

                click: function() {
                    var input_group = $(this).parent('.input-group');
                    var input_ref;
                    var input_linha;
                    var input;
                    var campo_id;

                    input_ref   = input_group.parents('td').first().attr('coluna');
                    input_linha = input_group.parents('fieldset').first().find('div[coluna="'+input_ref+'"]');
                    input       = input_group.find('input.empresa-descricao');
                    campo_id    = input_linha.find('input[field="oc[empresa][]"]');      
                                        
                    if ( !campo_id.val() || !(campo_id.val() > 0) ) {
                        url = "/_13060/pesquisa";
                        filtrarEmpresa( input_group, input, campo_id );
                    }
                },
                
				focusout: function() {
                    var input_group = $(this).parent('.input-group');
                    var input_ref;
                    var input_linha;
                    var campo_id;

                    input_ref   = input_group.parents('td').first().attr('coluna');
                    input_linha = input_group.parents('fieldset').first().find('div[coluna="'+input_ref+'"]');
                    campo_id    = input_linha.find('input[field="oc[empresa][]"]');   
                    
					if(tempo_focus) 
						clearTimeout(tempo_focus)
                    ;

					tempo_focus = setTimeout(function() {

						if (
                           (
                           !(campo_id.val() > 0) ||
                           !campo_id.val() 
                           )
                            &&
                            input_group
                                .parent()
                                .find('.pesquisa-res')
                                .is(':empty')
                        ) {
                            input_group
                                .find('input[type="search"]')
                                .val('')
                            ;
						}

						if ( 
                           ( !(campo_id.val() > 0) || !campo_id.val() )
                            && 
                           !input_group
                                .parent()
                                .find('.pesquisa-res ul li a')
                                .is(':focus')
                            && 
							input_group
                                .find('input[type="search"]')
                                .val() 
						) {
							input_group
                                .find('input[type="search"]')
                                .val('')
                            ;
							fechaListaPesquisa(input_group);
						}
						
						if ( 
                            ( !(campo_id.val() > 0) || !campo_id.val() )
                            && 
                           !input_group
                                .parent()
                                .find('.pesquisa-res ul li a')
                                .is(':focus')
                            && 
                           !input_group
                                .find('input[type="search"]')
                                .val() 
						) {
							fechaListaPesquisa(input_group);
						}

					}, 200);
				}
            });

            //Campo de filtro
            $('.empresa-descricao').on({

                keydown: function(e) {

                    var input_group = $(this).parent('.input-group');
                    var input_ref;
                    var input_linha;
                    var campo_id;

                    input_ref       = input_group.parents('td').first().attr('coluna');
                    input_linha     = input_group.parents('fieldset').first().find('div[coluna="'+input_ref+'"]');;
                    campo_id        = input_linha.find('input[field="oc[empresa][]"]');


                        //Eventos após a escolha de um item
                        if ( $(this).is('[readonly]') ) {

                            //Deletar teclando 'Backspace' ou 'Delete'
                            if ( (e.keyCode === 8) || (e.keyCode === 46) ) {
                                $(this)
                                    .nextAll('.btn-filtro-apagar')
                                    .click();
                            }
                        }
                        else {

                            //Pesquisar com 'Enter'
                            if (e.keyCode === 13) {
                                url = "/_13060/pesquisa";
                                filtrarEmpresa( input_group, $(this), campo_id );
                            }
                        }
                },  
                
				focusout: function() {
                    var input_group = $(this).parent('.input-group');
                    var input_ref;
                    var input_linha;
                    var campo_id;

                    input_ref   = input_group.parents('td').first().attr('coluna');
                    input_linha = input_group.parents('fieldset').first().find('div[coluna="'+input_ref+'"]');
                    campo_id    = input_linha.find('input[field="oc[empresa][]"]');                       
                    
                    
                    
                    linha_item = $(this).parents('tr').first();
                    click_item = $(this);
                    
					//verificar quando o campo deve ser zerado
					if(tempo_focus) clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if (
                           !campo_id
                                .val('') 
                            && 
							$(this)
                                .parent()
                                .nextAll('.pesquisa-res-container')
                                .children('.pesquisa-res')
                                .is(':empty')
                            &&
                           !$(this)
                                .nextAll('.btn-filtro')
                                .is(':focus') 
						) {
							$(this)
                                .val('')
                            ;
						}
					}, 200);
				}
            });
        }
        

        /**
         * Eventos para o filtro de CCusto.
         */
        function iniciarFiltroTransp() {
            var linha_item;
            var click_item;     
            
			//Se o item já estiver selecionado (tela de update), efetua as devidas ações.
			$('input[field="oc[transp][]"]').each(function() {
                var input_ref;
                var input_group;
                var input;
                
                input_ref   = $(this).parent('div').attr('coluna');
                input_group = $(this).parents('fieldset').first().find('td[coluna="'+input_ref+'"]');
                input       = input_group.find('input.transp-descricao');

				if ( $(this).val() !== '' ) {
			
					selecionadoEmpresa(
						input,
                        $(this)
					);
				}
			});            
            
            //Botão de filtrar
            $('.btn-filtro-transp').on({

                click: function() {
                    var input_group = $(this).parent('.input-group');
                    var input_ref;
                    var input_linha;
                    var input;
                    var campo_id;

                    input_ref   = input_group.parents('td').first().attr('coluna');
                    input_linha = input_group.parents('fieldset').first().find('div[coluna="'+input_ref+'"]');
                    input       = input_group.find('input.transp-descricao');
                    campo_id    = input_linha.find('input[field="oc[transp][]"]');      
                    
                    if ( !(campo_id.val() > 0) || !campo_id.val() ) {
                        url = "/_14010/pesquisa";
                        filtrarEmpresa( input_group, input, campo_id );
                    }
                },
                
				focusout: function() {
                    var input_group = $(this).parent('.input-group');
                    var input_ref;
                    var input_linha;
                    var campo_id;

                    input_ref   = input_group.parents('td').first().attr('coluna');
                    input_linha = input_group.parents('fieldset').first().find('div[coluna="'+input_ref+'"]');
                    campo_id    = input_linha.find('input[field="oc[transp][]"]');   
                    
					if(tempo_focus) 
						clearTimeout(tempo_focus)
                    ;

					tempo_focus = setTimeout(function() {

						if (
                           ( !(campo_id.val() > 0) || !campo_id.val() )
                            &&
                            input_group
                                .parent()
                                .find('.pesquisa-res')
                                .is(':empty')
                        ) {
                            input_group
                                .find('input[type="search"]')
                                .val('')
                            ;
						}

						if ( 
                           ( !(campo_id.val() > 0) || !campo_id.val() ) 
                            && 
                           !input_group
                                .parent()
                                .find('.pesquisa-res ul li a')
                                .is(':focus')
                            && 
							input_group
                                .find('input[type="search"]')
                                .val() 
						) {
							input_group
                                .find('input[type="search"]')
                                .val('')
                            ;
							fechaListaPesquisa(input_group);
						}
						
						if ( 
                           ( !(campo_id.val() > 0) || !campo_id.val() )
                            && 
                           !input_group
                                .parent()
                                .find('.pesquisa-res ul li a')
                                .is(':focus')
                            && 
                           !input_group
                                .find('input[type="search"]')
                                .val() 
						) {
							fechaListaPesquisa(input_group);
						}

					}, 200);
				}
            });

            //Campo de filtro
            $('.transp-descricao').on({

                keydown: function(e) {

                    var input_group = $(this).parent('.input-group');
                    var input_ref;
                    var input_linha;
                    var campo_id;

                    input_ref       = input_group.parents('td').first().attr('coluna');
                    input_linha     = input_group.parents('fieldset').first().find('div[coluna="'+input_ref+'"]');;
                    campo_id        = input_linha.find('input[field="oc[transp][]"]');


                        //Eventos após a escolha de um item
                        if ( $(this).is('[readonly]') ) {

                            //Deletar teclando 'Backspace' ou 'Delete'
                            if ( (e.keyCode === 8) || (e.keyCode === 46) ) {
                                $(this)
                                    .nextAll('.btn-filtro-apagar')
                                    .click();
                            }
                        }
                        else {

                            //Pesquisar com 'Enter'
                            if (e.keyCode === 13) {
                                url = "/_14010/pesquisa";
                                filtrarEmpresa( input_group, $(this), campo_id );
                            }
                        }
                },  
                
				focusout: function() {
                    var input_group = $(this).parent('.input-group');
                    var input_ref;
                    var input_linha;
                    var campo_id;

                    input_ref   = input_group.parents('td').first().attr('coluna');
                    input_linha = input_group.parents('fieldset').first().find('div[coluna="'+input_ref+'"]');
                    campo_id    = input_linha.find('input[field="oc[transp][]"]');                       
                    
                    
                    
                    linha_item = $(this).parents('tr').first();
                    click_item = $(this);
                    
					//verificar quando o campo deve ser zerado
					if(tempo_focus) clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if (
                           !campo_id
                                .val('') 
                            && 
							$(this)
                                .parent()
                                .nextAll('.pesquisa-res-container')
                                .children('.pesquisa-res')
                                .is(':empty')
                            &&
                           !$(this)
                                .nextAll('.btn-filtro')
                                .is(':focus') 
						) {
							$(this)
                                .val('')
                            ;
						}
					}, 200);
				}
            });
        }   
        
        iniciarFiltroEmpresa();
        iniciarFiltroTransp();
    }
      
    

    { /** Setar o select para o input*/
        function setSelectToInput() {
            
//			$('.select-to-input').val().trigger('change');
			
            $('.select-to-input').on('change', function() {
//                var input_group = $(this).parent();

                var input_name;
                var input_id;

                input_name      = $(this).attr('input-name');
                input_id        = $('input[field="' + input_name + '"]');  
                input_id.val($(this).val());
            });
        }
    }
    
    
	/** 
	 * Visualizar arquivo 
	 * */
	function visualizarArquivo() {
		
        $('.view-arquivo').click(function() {
			
            var arq     = $(this);
            var arq_id  = $(arq).parent('.form-group').parent('.item-dinamico').children('.form-group').first().children('input[name="_vinculo_id[]"]').val();

			//ajax
			var type	= 'POST',
				url		= '/_13010/DownloadArquivo',
		        data	= {'item': arq_id},
				success	= function(data) {

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
			
		});
	} 
    
    
    function calcTotal() {
        var total = 0;
        var coluna;
                {//Desmarca todos os campos required e input head
                    $('input:radio, input:checkbox').each(function () {
                        //código da coluna
                        coluna = 
                        $(this) 			//<- botão do radio
                            .parent()		//<- label do radio
                            .parent()		//<- div radio
                            .parent()		//<- td
                            .attr('coluna')	//codigo coluna
                        ;	

                        //remove required da coluna
                        $('td[coluna=' + coluna + ']')
                            .children('.form-group')
                            .find('input, select')
                            .removeAttr('required')
                        ;	

                        //remove o name para que não seja enviado no submit
                        $('div[coluna=' + coluna + ']')		//<- coluna head
                            .children()						//<- filho da coluna
                            .siblings('input')				//<- irmãos input
                            .each(function () {				//<- todos os inputs
                                $(this)						//<- input
                                    .removeAttr('name')		//<- remove o atritubo name
                                ;
                            })
                        ;
                        
                        //remove o required da operacao
                        $(this)                             //<- botão do radio
                            .parents('tr')                  //<- linha
                            .first()                        //<- linha corrente
                            .find('.operacao-descricao')    //<- input operacao
                            .removeAttr('required')         //<= remove o atritubo required
                        ;
                        
                        //remove o required do ipi
                        $(this)                             //<- botão do radio
                            .parents('tr')                  //<- linha
                            .first()                        //<- linha corrente
                            .find('.perc-ipi')              //<- input ipi
                            .removeAttr('required')         //<= remove o atritubo required
                        ;
                        
                        //remove o required da data entrega
                        $(this)                             //<- botão do radio
                            .parents('tr')                  //<- linha
                            .first()                        //<- linha corrente
                            .find('.data-entrega')          //<- input data entrega
                            .removeAttr('required')         //<= remove o atritubo required
                        ;
                        
                        
                    });		
                }

                $('input:radio, input:checkbox').each(function () {		

                    coluna = 
                    $(this) 			//<- botão do radio
                        .parent()		//<- label do radio
                        .parent()		//<- div radio
                        .parent()		//<- td
                        .attr('coluna')	//codigo coluna
                    ;	

                    if ($(this).prop('checked')) {

                        {//Calcula o total
                            total = 
                            total +
                            formataPadrao(
                                $(this) 			  //<- botão do radio
                                    .parent() 		  //<- label do radio
                                    .children('span') //<- campo do valor
                                    .text() 		  //<- valor
                            );		
                        }

                        $(this) 			 				//<- botão do radio
                            .parent()						//<- label do radio
                            .siblings('input')				//<- irmãos de label
                            .each(function () {				//<- todos os inputs
                                $(this)						//<- input
                                    .attr('name',			//<- atributo name
                                        $(this)				//<- input
                                            .attr('field')	//<- atributo field
                                    );
                            })
                        ;

                        {//Marca os campos required e campos input head
                            
                            //add required na coluna
                            $('td[coluna=' + coluna + ']')
                                .children('.form-group')
                                .find('input, select')
                                .attr('required',true)
                            ;

                            //add o atritubo name para ser enviado no submit
                            $('div[coluna=' + coluna + ']')
                                .children()
                                .siblings('input')
                                .each(function () {				//<- todos os inputs
                                    $(this)						//<- input
                                        .attr('name',			//<- atributo name
                                            $(this)				//<- input
                                                .attr('field')	//<- atributo field
                                        );
                                })
                            ;
                            
                            //add o required da operacao
                            $(this)                             //<- botão do radio
                                .parents('tr')                  //<- linha
                                .first()                        //<- linha corrente
                                .find('.operacao-descricao')    //<- input operacao
                                .attr('required',true)          //<= add o atritubo required
                            ;     
                            
                            //add o required do ipi
                            $(this)                             //<- botão do radio
                                .parents('tr')                  //<- linha
                                .first()                        //<- linha corrente
                                .find('.perc-ipi')              //<- input ipi
                                .attr('required',true)          //<= add o atritubo required
                            ;    
                            
                            //add o required da data entrega
                            $(this)                             //<- botão do radio
                                .parents('tr')                  //<- linha
                                .first()                        //<- linha corrente
                                .find('.data-entrega')          //<- input data entrega
                                .attr('required',true)          //<= add o atritubo required
                            ;                           
                        }

                    } else {					

                        $(this) 		 		//<- botão do radio
                            .parent()			//<- label do radio
                            .siblings('input')	//<- irmãos de label
                            .each(function () {	//<- todos os inputs
                                $(this).removeAttr('name');
                            })
                        ;
                    }

                });

                ( total > 0 ) ? $('button.js-gravar').removeAttr('disabled') : $('button.js-gravar').attr('disabled',true); 

                total = formataReal( total.toFixed(4) );
                $('.total-calculado').text(total);
    }
    
    function frete() {
		function verificarFrete(tipo) {
			//CIF
			if ( tipo === '1' ) {
				$('.frete-valor')
					.removeAttr('readonly')
                ;
            
                $('input[field="oc[frete][]"]').val('CIF');
			}
			//FOB
			else {
				
				$('.frete-valor')
					.attr('readonly', true)
					.val('0,00')
                ;
            
                $('input[field="oc[frete][]"]').val('FOB');
			}
		}
		
		verificarFrete( $('.frete').val() );
        
		$('.frete').change(function() {
			verificarFrete( $(this).val() );			
		});   
        
        $('.frete-valor').change(function() {
            $('input[field="oc[frete][]"]').val( $(this).val() );
        });
    }
    
	/**
	 * Ativar Datatable.
	 */
	function ativarDatatable() {
		
		var data_table = $.extend({}, table_default);
			data_table.scrollY = 'auto';             

		$('.table').DataTable(data_table);   
		
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
			.on('keydown', '.operacao-descricao, .transp-descricao', 'down', function() {
				
				$.tabNext();
				return false;
				
			});
			
	}
	
	function replicarData() {
		
		$('.replicar-data-saida').click(function() {
			
			var data_saida =	$(this)
									.prev('.data-saida')
									.val()
								;
			
			$('.data-saida')
				.val(data_saida)
			;
			
		});
		
		$('.replicar-data-entrega').click(function() {
			
			var data_entrega =	$(this)
									.prev('.data-entrega')
									.val()
								;
			
			$('.data-entrega')
				.val(data_entrega)
			;
			
		});
		
	}
	
    frete();
    calcTotal();
    clickRadioItem();
    iniciarFiltroOperacao();
    filtroEmpresa();
    setSelectToInput();
	visualizarArquivo();
	//ativarDatatable();
	
	$(function() {
		
		ativarTabSeta();
		replicarData();
		
	});

})(jQuery);
//# sourceMappingURL=_13040.js.map
