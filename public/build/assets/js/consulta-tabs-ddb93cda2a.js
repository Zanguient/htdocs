	{ 
        /** Consulta generica (Tabulado)
         * @deprecated 
         * */
		
        
		var filtro;
		var campo_consulta;
		var btn_filtro_consulta;
		var input_group;
		var itens_consulta;
		var _consulta_id;
		var tempo_focus;
		var consulta_selecionado = false;
        var obj;
        
       var campos_imputs   = [];
       var recebe_valor    = [];
       var campos_sql      = [];
       var filtro_sql      = [];
       var campos_tabela   = [];
       var campos_titulo   = [];
       var tamanho_tabela  = [];
       var campos_titulo   = [];
       var tabs            = [];
       var tabs_consulta   = [];
       var campo_ret       = [];
       var limitar         = 0; 
       var total_width     = 0;
       var fechar = 0;
       
       function setSelectTab(tab){
            var cont  = $(tabs).length;

            for (i = 0; i < cont; i++) {
                
                if(tab != i){
                   $('.tabe-gered-'+i).addClass('desabilitado-lista');
                }else{
                   $('.tabe-gered-'+i).removeClass('desabilitado-lista');
                }
 
            }
                
        }
        
       function getTab(e){
          $('.tabs-consulta').removeClass('tab-selected'); 
          $(e).addClass('tab-selected');
          
          var tab = $(e).attr('tabcont');
          setSelectTab(tab);
       }
       
       /** Retorna as classes dos campos que irão receber os valores que foram passados como parametro
        * 
        *@param {json} x imputs com as classes   
        *@returns {array}
        **/
       function getRecebeValor(x){
         var obj = $(recebe_valor)[x];
         
         var vclass = '.'+$(obj).attr('objClass');
         var vcampo = ''+$(obj).attr('objCampo');
         
         var ret = $(vclass);
         
         $(ret).attr('objCampo',vcampo);
         
         return ret;
       }
       
       /** Retorna os campos da consulta que foram passados como parametro
        *  
        *@returns {array}
        **/
       function getCampos(){
            
            var vetor = new Array ();
            var cont = $(campos_sql).length;
            
            for (i = 0; i < cont; i++) {
               var obj = $(campos_sql)[i];
               var campo = $(obj).val();
               vetor[i] = campo;           

             }
         
            return vetor;
       }
       
       /** Retorna os as condições que foram passados como parametro
        *  
        *@returns {array}
        **/
       function getCondicao(){
            
            var vetor = new Array ();
            var cont = $(filtro_sql).length;
            
            for (i = 0; i < cont; i++) {
               var obj = $(filtro_sql)[i];
               var campo = $(obj).val();
               vetor[i] = campo;           

             }
         
            return vetor;
       }
       
       /** Retorna os campos da tabela que sera montada com os parametros
        *  
        *@returns {array}
        **/
       function getTabela(){
            
            var vetor = new Array ();
            var cont = $(campos_tabela).length;
            
            for (i = 0; i < cont; i++) {
               var obj = $(campos_tabela)[i];
               var campo = $(obj).val();
               vetor[i] = campo;           

             }
         
            return vetor;
       }
       
       /** Retorna os tamanhos dos campos da tabela que sera montada com os parametros
        *  
        *@returns {array}
        **/
       function getTamanhos(){
            
            var vetor = new Array ();
            var cont = $(tamanho_tabela).length;
            total_width = 0;
            
            for (i = 0; i < cont; i++) {
               var obj = $(tamanho_tabela)[i];
               var campo = $(obj).attr('objTamanho');
               vetor[i] = campo;
               total_width = parseInt(total_width) + parseInt(campo);

            }
            return vetor;
        }
       
       /** Retorna os titulos dos campos da tabela que sera montada com os parametros
        *  
        *@returns {array}
        **/
       function getTitulos(){
            
            var vetor = new Array ();
            var cont = $(campos_titulo).length;
            
            for (i = 0; i < cont; i++) {
               var obj = $(campos_titulo)[i];
               var campo = $(obj).val();
               vetor[i] = campo;           

             }
         
            return vetor;
       }
       
       /** Retorna as tabs
        *  
        *@returns {array}
        **/
       function getTabs(){
            
            var vetor = new Array ();
            var cont = $(tabs).length;
            
            for (i = 0; i < cont; i++) {
               var obj = $(tabs)[i];
               var campo = $(obj).val();
               vetor[i] = campo;           

             }
         
            return vetor;
       }
       
       /** Retorna as arquivos de consulta das tabs
        *  
        *@returns {array}
        **/
       function getTabsConsulta(){
            
            var vetor = new Array ();
            var cont = $(tabs_consulta).length;
            
            for (i = 0; i < cont; i++) {
               var obj = $(tabs_consulta)[i];
               var campo = $(obj).val();
               vetor[i] = campo;           

             }
         
            return vetor;
       }
       
       /** Seta os valores dos campos Ocultos, e Recebe valor como vazio
        **/
       function empytValores(){
           $(campos_imputs).val('');
           
           var cont = $(recebe_valor).length;
           
           for (i = 0; i < cont; i++) {
             var objrv = getRecebeValor(i);
             
             $(objrv).val('');
             
           }
           
       }
       
       /** adiciona os eventos dos items da lista que forão adicionados
        * 
        *@param {json} e objeto jquey do form-group  
        **/
       function trataItens(e){
            
            itens_consulta = $(e).find('ul.consulta-lista li a');
                        
            selecItemListaConsulta( $(itens_consulta), campo_consulta );

            $(itens_consulta)
                .focusout(function() {

                    if(tempo_focus) 
                        clearTimeout(tempo_focus);

                    tempo_focus = setTimeout(function() {

                        if( !$(e).find(itens_consulta).is(':focus') && 
                            !$(e).find(campo_consulta).is(':focus') && 
                            !$(e).find(btn_filtro_consulta).is(':focus') 
                        ) {
                            $(campo_consulta).val('');
                            fechaListaConsulta( input_group ,e);
                        }

                    }, 200);

                });

            $(campo_consulta)
                .focusout(function() {

                    if(tempo_focus) 
                        clearTimeout(tempo_focus);

                    tempo_focus = setTimeout(function() {

                        if( !$(itens_consulta).is(':focus') &&
                            !$(_consulta_id).val() && 
                            !$(btn_filtro_consulta).is(':focus') 
                        ) {
                            $(campo_consulta).val('');
                            fechaListaConsulta( input_group ,e);
                        }

                    }, 200);

                });
        }
        
       
		/** Chama a consulta com os parametros passados como parametros
        * 
        *@param {json} e objeto jquey que foi clicado  
        **/
		function filtrarConsulta(e) {
            
            var base  = $(e).parent().parent().parent();
            
            var obj_parametros = $(base).find('._consulta_parametros');
            var obj_imputs = $(base).find('._consulta_imputs');
            
            campos_imputs   = $(obj_imputs).find('._consulta_imputs');
            
            obj             = $(obj_parametros).find('._consulta_obj').val();
            recebe_valor    = $(obj_parametros).find('._consulta_recebevalor');
            campos_sql      = $(obj_parametros).find('._consulta_campos');
            filtro_sql      = $(obj_parametros).find('._consulta_filtro');
            campos_tabela   = $(obj_parametros).find('._consulta_tabela');
            tamanho_tabela  = $(obj_parametros).find('._consulta_tabela');
            campos_titulo   = $(obj_parametros).find('._consulta_titulo');
            tabs            = $(obj_parametros).find('._consulta_tab');
            tabs_consulta   = $(obj_parametros).find('._consulta_tab_consulta');
            campo_ret       = $(obj_parametros).find('._consulta_ret');
            limitar         = $(obj_parametros).find('._consulta_limitar').val(); 

			campo_consulta		= $(base).find('#consulta-descricao');	//campo
			btn_filtro_consulta	= $(base).find('.btn-filtro-consulta');

			input_group			= $(campo_consulta).parent('.input-group');	//input-group
			_consulta_id		= $(base).find('._valor_selecionado_consulta');

			filtro = campo_consulta.val(); 
            
			//esvazia campo hidden caso algum item já tenha sido escolhido antes
			if(consulta_selecionado){ 
				$(_consulta_id).val('');
                empytValores();
            }

			if( !filtro ) {
				fechaListaConsulta( input_group ,e);
				$(_consulta_id).val('');
				empytValores();
            }
            
            var campos      = getCampos();
            var condicao    = getCondicao();
            var tabela      = getTabela();
            var titulos     = getTitulos();
            var tamanhos    = getTamanhos();
            var tab         = getTabs();
            var tab_con     = getTabsConsulta();
            var limit       = limitar;
            
            var dadosEnvio = {'filtro': filtro, 'campos': campos, 'obj': obj, 'condicao': condicao, 'tabela':tabela, 'titulos':titulos, 'tamanhos':tamanhos, 'tabs':tab, 'tabs_consulta':tab_con, 'limit':limit};
            
            $(base).find('.lista-consulta-container').css('width',total_width);
            
            var url_action = "/consultaAll";
            var dados = dadosEnvio;
            var type = "POST";
            
            function success(data){
              setSelectTab(0);  
              abreListaConsulta( input_group );
			 		
					$(base).find('.lista-consulta')
						.html(data);

					//existem dados cadastrados
					if( data.indexOf('nao-cadastrado') === -1 ) {
                        trataItens(base);
					}
					else {
						$(_consulta_id).val('');

						$(campo_consulta)
							.focusout(function() {
								if( $(base).find('.lista-consulta').children().children().hasClass('nao-cadastrado') ) {
									$(campo_consulta).val('');
									fechaListaConsulta( input_group ,base);
								}
							});
					}  
            }
            

            execAjax2(type,url_action,dados,success,false,btn_filtro_consulta,false);
		}
        
        
        
        /** Chama a consulta da procima pagina a ser caregada
        * 
        *@param {json} e objeto jquey que foi clicado  
        **/
		function getMais(e) {
            
            var obj = $(e).closest('.form-group');
            
            $(obj).find('._valida_fechar_lista').val(1);
            
            btn_filtro_consulta	= $(obj).find('.btn-filtro-consulta');
            
            var tag = $(e).attr('tag');
            var pag = $(e).attr('pag');
            var tab = $('.tab-selected').attr('tabcont');
            
            var mais_all = $(e).parent().find('.btn-caregar-mais-all');
            
            var url_action = "/consultaMais";
            var dados = {'tag':tag, 'pag':pag, 'tab':tab};
            var type = "POST";
            
            function success(data){
              $(e).parent().append(data);
              $(e).remove();
              $(mais_all).remove();
              
              trataItens(obj);
              
              $(obj).find('._valida_fechar_lista').val(0);
            }

            execAjax2(type,url_action,dados,success,false,btn_filtro_consulta,false);
            
            
		}
        
        /** Chama a consulta com todas as paginas que estão faltando serem caregadas
        * 
        *@param {json} e objeto jquey que foi clicado  
        **/
		function getAll(e) {
            
            var obj = $(e).closest('.form-group');
            
            $(obj).find('._valida_fechar_lista').val(1);
              
            btn_filtro_consulta	= $(obj).find('.btn-filtro-consulta');
            
            var tag = $(e).attr('tag');
            var pag = $(e).attr('pag');
            var tab = $('.tab-selected').attr('tabcont');
            
            var mais = $(e).parent().find('.btn-caregar-mais');
            
            var url_action = "/consultaMaisAll";
            var dados = {'tag':tag, 'pag':pag, 'tab':tab};
            var type = "POST";
            
            function success(data){
              $(e).parent().append(data);
              $(e).remove();
              $(mais).remove();
              
              trataItens(obj);
              
              $(obj).find('._valida_fechar_lista').val(0);
            }

            execAjax2(type,url_action,dados,success,false,btn_filtro_consulta,false);
		}

		/** Mostra lista com o resultado da consulta na tela
        * 
        *@param {json} consulta objeto jquey que contem a lista  
        **/
		function abreListaConsulta(consulta) {
			
			$(consulta)
				.next('.lista-consulta-container')
				.addClass('ativo');
		
			$(btn_filtro_consulta)
				.attr('tabindex', '-1');
		}

		/** Fecha lista com o resultado da consulta na tela
        * 
        *@param {json} consulta objeto jquey que contem a lista 
        *@param {json} e objeto jquey do form-group  
        **/
		function fechaListaConsulta(consulta,e) {
         /*   
            fechar = $(e).find('._valida_fechar_lista').val();
            
            if (fechar == 0){
			$(consulta)
				.next('.lista-consulta-container')
				.removeClass('ativo')
				.children('.lista-consulta')
				.empty();
		
			$(btn_filtro_consulta)
				.removeAttr('tabindex');
            }else{
              $(itens_consulta).focus();  
            }   
           
           //*/
		}

		/**
		 * Preencher campos de acordo com o item selecionado.
		 * 
		 * @param {json} itens Item que foi clicado
		 * @param {json} campo Campos que recebe valor
		 */
		function selecItemListaConsulta(itens, campo) {
            
                $(itens).click(function(e) {

                    var obj = $(campos_imputs).closest('.form-group');

                    e.preventDefault();

                    var cont  = $(recebe_valor).length;
                    var cont2 = $(campo_ret).length;
                    var cont3 = $(campos_imputs).length;

                        for (i = 0; i < cont; i++) {
                          var objrv = getRecebeValor(i);
						  
						  if ($(objrv).attr('objcampo') == 'clear') {
							var objValor = ''; 
						  }
						  else {
							var clas = '._consulta_'+$(objrv).attr('objcampo');
							var objValor = $(this).find(clas).val();
						}

                          $(objrv).val(objValor);

                        }

                        for (i = 0; i < cont3; i++) {
                          var objrv = $(campos_imputs)[i];
                          var clas = '._consulta_'+$(objrv).attr('objcampo');
                          var objValor = $(this).find(clas).val();

                          $(objrv).val(objValor);

                        }

                        var ret ='';
                        for (i = 0; i < cont2; i++) {
                            var objrv = $(campo_ret)[i];
                            var clas = '._consulta_'+$(objrv).attr('objcampo');
                            var objValor = $(this).find(clas).val();

                            if (i == 0){
                                ret = objValor;
                            }else{
                                ret = ret + ' - ' + objValor;
                            } 

                        }

                    $(campo)
                        .val(ret)
                        .focus();

                    selecionadoConsulta(obj);

                    $(_consulta_id)
                        .val(1)
                        .trigger('change');

                    $(obj).find(".consulta-descricao").trigger( "change" );

                    fechaListaConsulta( input_group, obj);
                    consulta_selecionado = true;
                });

		}
		
		/**
		 * Se um item foi selecionado modifica os campos.
		 * 
		 * @param {json} e Objeto jquey do form-group
		 */
		function selecionadoConsulta(e) {
            
            var obj = e;
            
			$(obj).find('#consulta-descricao')
				.attr('readonly', true);

			$(obj).find('.btn-filtro-consulta')
				.hide();
            
			$(obj).find('.btn-apagar-filtro-consulta')
				.show()
				.click(function() {
                
                    var obj_parametros = $(this).parent().parent().parent().find('._consulta_parametros');
                    var obj_imputs = $(this).parent().parent().parent().find('._consulta_imputs');

                    campos_imputs   = $(obj_imputs).find('._consulta_imputs');
                    recebe_valor    = $(obj_parametros).find('._consulta_recebevalor');
                    
					$(this)
						.siblings('input')
						.removeAttr('readonly');

					$(this)
						.hide()
						.prev('button')
						.show();
                
                    empytValores();    

					$(obj).find('#consulta-descricao')
						.val('')
						.focus();

					$(obj).find('._valor_selecionado_consulta')
						.val('');
				});
		}

		/**
		 * Inicializa os eventos que irão disparaar as chamadas dos metodos de consulta
		 */
		function iniciarFiltroConsulta() {

            $(document).on('click','.btn-caregar-mais', function(e) {
                getMais(this);
            });
            
            
            $(document).on('click','.tabs-consulta', function(e) {
                getTab(this);
            });
            
            $(document).on('click','.btn-caregar-mais-all', function(e) {
                getAll(this);
            });
			
            $('._valor_selecionado_consulta').each(function() {
                
                //Se o item já estiver selecionado (tela de update), efetua as devidas ações.
                if ( $(this).val() !== '' ){
                    var obj = $(this).closest('.form-group');
                    selecionadoConsulta(obj);
                }
                
            });
            

			//Botão de filtrar
			$('.btn-filtro-consulta').on({

				click: function() {
                    var obj = $(this).closest('.form-group');
                    _consulta_id = $(obj).find('._valor_selecionado_consulta');
                    
					if ( !$(_consulta_id).val() )
						filtrarConsulta(this);
				},

				focusout: function() {
                    
                    var obj = $(this).closest('.form-group');
                    
					if(tempo_focus) clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {

						if ( !$(obj).find('input[name="_consulta_id"]').val() && 
							 $(obj).find('.lista-consulta').is(':empty') 
						) {
						   $(obj).find('#consulta-descricao').val('');
						}

						if ( !$(obj).find('input[name="_consulta_id"]').val() && 
							 !$(obj).find('.lista-consulta ul li a').is(':focus') && 
							  $(obj).find('#consulta-descricao').val() 
						) {
							$(obj).find('#consulta-descricao').val('');
							fechaListaConsulta(input_group,obj);
						}
						
						if ( !$(obj).find('input[name="_consulta_id"]').val() && 
							 !$(obj).find('.lista-consulta ul li a').is(':focus') && 
							 !$(obj).find('#consulta-descricao').val() 
						) {
							fechaListaConsulta(input_group,obj);
						}

					}, 200);
				}

			});

			//Campo de filtro
			$('.consulta-descricao').on({

				keydown: function(e) {
                    var obj = $(this).closest('.form-group');
                    
					//Eventos ap?s a escolha de um item
					if ( $(this).is('[readonly]') ) {

						//Deletar teclando 'Backspace' ou 'Delete'
						if ( (e.keyCode === 8) || (e.keyCode === 46) ) {
							$(obj).find('.btn-apagar-filtro-consulta').click();
						}
					}
					else {

						//Pesquisar com 'Enter'
						if (e.keyCode === 13) {
							var obj = $(this).closest('.form-group');
							_consulta_id = $(obj).find('._valor_selecionado_consulta');

							if ( !$(_consulta_id).val() )
								filtrarConsulta(this);
						}
					}
				},

				focusout: function() {
                    var obj = $(this).closest('.form-group');
                    
					//verificar quando o campo deve ser zerado
					//*
                    if(tempo_focus)
						clearTimeout(tempo_focus);

					tempo_focus = setTimeout(function() {
                        
						if ( !$(obj).find('._valor_selecionado_consulta').val() && 
							 $(obj).find('.lista-consulta').is(':empty') &&
							 !$(obj).find('.btn-filtro-consulta').is(':focus') 
						) {
							$(obj).find('#consulta-descricao').val('');
						}

					}, 200);
                    //*/
				}

			});
		
		}
		
		iniciarFiltroConsulta();
	}
    
//# sourceMappingURL=consulta-tabs.js.map
