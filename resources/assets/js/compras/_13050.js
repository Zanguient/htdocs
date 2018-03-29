/**
 * Script com funções de:
 * - Gerar pdf
 * */

(function($) {

    function addEventBtn(){
        /**
         * Autoriza ordem de compra
         */
        $('.autorizar-oc2')
            .click(function(e) {

                e.stopPropagation();

                var id    = $(this).data('oc');
                var index = $(this).data('index');
                var that  = this;

                var dados = {ID : id};

                function success(retorno) {

                    if(retorno.Q > 0){
                        $(that).parent().parent().trigger('click');
                        showAlert('ITENS COM PENDÊNCIAS DE APROVAÇÃO');
                    }else{
                        addConfirme('OC','Deseja realmente autorizar OC:'+id+'?',[obtn_ok,obtn_cancelar],
                           [
                               {ret:1,func:function(){

                                    var dados = 
                                    {   id    : id,
                                        tipo  : '1',
                                        obs   : '',
                                        itens : []
                                    };
                                    
                                    $(this).button('loading');

                                    function success() { 

                                        showSuccess('OC:'+id+' autorizada.');

                                        $('tr[tabindex=' + index + ']').remove();
                                        
                                    }

                                    execAjax1('POST','/_13050/autorizacao',dados,success);
                               }},
                               {ret:2,func:function(){
                                
                               }}
                           ]    
                        ); 
                    } 

                }

                execAjax1('POST','/_13050/pendencias',dados,success);
                
            })
        ;
    } 

    /**
     * Realiza filtros na OC
     */
    function filtroOc() {
        
        var qtd_por_pag   = 30;
        var pagina_atual  = 1;
        var pagina_inc    = 0;
        var final_pag	  = false;

        /**
         * Realiza a coleta dos dados<br/>
         * Realiza a chamada ajax
         */
        function tratarDados() {
			
			if( final_pag ) {
                pagina_atual = 1;
                pagina_inc = 0;
                return false;
            }

            var campo_pesquisa      = $('.btn-oc-filtro');
            var campo_pendencia     = $('.filtro-pendencia');
            var campo_pendentes     = $('.filtro-pendentes');
            var campo_autorizadas   = $('.filtro-autorizadas');
            var campo_reprovadas    = $('.filtro-reprovadas');
            var campo_mes_inicial   = $('.filtro-mes-inicial');
            var campo_ano_inicial   = $('.filtro-ano-inicial');
            var campo_mes_final     = $('.filtro-mes-final');
            var campo_ano_final     = $('.filtro-ano-final');

            var pesquisa      = campo_pesquisa.val();
            var pendencia     = campo_pendencia.is(":checked");
            var pendentes     = campo_pendentes.is(":checked");
            var autorizadas   = campo_autorizadas.is(":checked");
            var reprovadas    = campo_reprovadas.is(":checked");
            var mes_inicial   = campo_mes_inicial.val();
            var ano_inicial   = campo_ano_inicial.val();
            var mes_final     = campo_mes_final.val();
            var ano_final     = campo_ano_final.val();
            var data_inicial  = new Date(ano_inicial, mes_inicial-1,01);
            var data_final    = lastDate(new Date(ano_final, mes_final-1,01));
            var data_1        = data_inicial.getFullYear()+'.'+(data_inicial.getMonth()+1)+'.'+data_inicial.getDate();
            var data_2        = data_final  .getFullYear()+'.'+(data_final  .getMonth()+1)+'.'+data_final  .getDate();
            var dados;
            
            dados = {
                'qtd_por_pagina': qtd_por_pag,
                'pagina'        : pagina_inc,              
                'filtro'        : pesquisa,
                'pendencia'     : pendencia,
                'pendentes'     : pendentes,
                'autorizadas'   : autorizadas,
                'reprovadas'    : reprovadas,
                'data_1'        : data_1,
                'data_2'        : data_2          
            };   

            function success(data) {						
                if(data) {
                    var html = $('table.lista-obj tbody').html();
                    $('table.lista-obj tbody').html(html + data);
                    show();
                    bootstrapInit();
                    addEventBtn();

                } else {
                    final_pag = true;
                }
            }
            function complete() {
                $('.btn-oc-filtrar').button('reset'); 
            }
            $('.btn-oc-filtrar').button('loading');
            execAjax1('POST','/_13050/paginacaoScroll',dados,success,null,complete);
        }

        /**
         * Realiza a parametrização inicial<br/>
         * Realiza o acionamento da consulta
         */
        function acionarFiltro() {
            
            var campo_pesquisa = $('.btn-oc-filtro');
            var btn_filtrar    = $('.btn-oc-filtrar');
            var scroll_timer   = 0;
			
            btn_filtrar.click(function(){
				final_pag	  = false;
				pagina_atual  = 1;
				pagina_inc    = 0;
                $('table.lista-obj tbody').empty();
                tratarDados();
            });
            
            campo_pesquisa.keyEnter(function(){
				final_pag	  = false;
				pagina_atual  = 1;
				pagina_inc    = 0;
                $('table.lista-obj tbody').empty();
                tratarDados();
            });
            
            //carregar página com scroll
            $('.table-ec').scroll(function() {
				
                if (popUpShowing) return false; 
                
                var div = $(this);

                clearTimeout(scroll_timer);

                scroll_timer = setTimeout(function() {
					
					//final do scroll da tabela
                    if( ( div.scrollTop() + div.height() ) >= div.children('table').height() ) {

                        pagina_atual += 1;
                        pagina_inc = pagina_atual * qtd_por_pag - qtd_por_pag;                        
                        tratarDados();
					}
                }, 200);
            });

            //carregar página com clique
            $('.carregar-pagina').click(function() {

                pagina_atual += 1;
                pagina_inc = pagina_atual * qtd_por_pag - qtd_por_pag;                                        
                tratarDados();
            });            
        }
        
        acionarFiltro();
    }
        
    /**
     * Carrega e configura tabela
     * @returns {void}
     */
    function dataTable() {
        $('.lista-obj')
            .DataTable(table_default);
    }  
    
    function show() {
        
        var param = {};
        var selector;
        
        function acoes() {

            /**
             * Gerar PDF de Oc.
             * @param {int} opcao 1 - Enviar | 2 - Imprimir
             */
            function gerarPdfOc(opcao) {

                var status_res = 0;
                var status_msg = '';
                var url_ajx = '';

                $('.alert-principal .texto')
                    .empty()
                    .parent()
                    .hide();

                if (opcao === 1) {
                    $('.enviar-oc')
                        .button('loading');

                    url_ajx = '/_13050/enviarPdfOc';
                }
                else {
                    $('.imprimir-oc')
                        .button('loading');

                    url_ajx = '/_13050/imprimirPdfOc';
                }
				
				//ajax
				var type		= 'POST',
                    url			= url_ajx,
                    data		= $('form').serialize(),
					success		= function(resposta) {

                        status_res = 0;
                        status_msg = resposta;

                    },
					error		= function (xhr) {

                        var msg = $(xhr.responseText)
                                        .find('.msg-erro')
                                        .html();

                        if ( xhr.status === 500 ) {
                            status_msg = msg;
                        }

                        status_res = 1;

                    },
                    complete	= function() {

                        //sucesso
                        if (status_res === 0) {

                            if (opcao === 1) {

                                $('.alert-principal .texto')
                                    .text('OC enviada com sucesso.')
                                    .parent()
                                    .removeClass('alert-danger')
                                    .addClass('alert-success')
                                    .slideDown();
                            }
                            else {
                                
                                printPdf(status_msg);

                            }

                        }
                        //erro
                        else if (status_res === 1) {

                            $('.alert-principal')
                                .removeClass('alert-success')
                                .addClass('alert-danger');

                            var excecao;

                            if ( status_msg ) {
                                excecao = status_msg.match(/exception 1 ...(.*) At trigger (.*)/i);
                            }

                            //se for exceção de trigger
                            if ( excecao ) {

                                $('.alert-principal .texto')
                                    .html(excecao['1'])
                                    .parent()
                                    .slideDown();

                            }
                            else {

                                $('.alert-principal .texto')
                                    .html(status_msg)
                                    .parent()
                                    .slideDown();

                            }
                        }

                        if (opcao === 1) {
                            $('.enviar-oc')
                                .button('reset');
                        }
                        else {
                            $('.imprimir-oc')
                                .button('reset');
                        }

                    }
				;
				
				execAjax1(type, url, data, success, error, complete, false);
                
            }

            /**
             * Excluir Pdf de Oc.
             */
            function excluirPdfOc() {

                $('.pdf-fechar')
                    .click(function() {				
                        execAjax1(
                            'POST', 
                            '/_13050/excluirPdfOc', 
                            { 'url_temp': getUrlPDf() },
                            function() {
                                $('.pdf-ver').fadeOut();
                            }
                        );
                    });
            }



            /**
             * Autorizar Oc.
             */
            function autorizarOc() {

                /**
                 * Coleta todos os itens da ordem de compra que não deverão ser referência para as proximas compras de mesmo produto
                 */
                $('.check-item').click(function(){
                    var checked = $(this).is(':checked');
                    
                    if ( checked ) {
                        $(this).next('input').attr('disabled',true);
                    } else {
                        $(this).next('input').removeAttr('disabled');
                    }
                });
                
                /**
                 * Autoriza ordem de compra
                 */
                $('.autorizar-oc')
                    .click(function() {
                        var obs = prompt('Confirma a AUTORIZAÇÃO desta Ordem de Compra?\n\nDigite uma observação (não obrigatório):');
                        if (obs === null) return false;

                        var id = $('#oc').val();
                        var dados = 
                        {   id    : id,
                            tipo  : '1',
                            obs   : obs,
                            itens : $('input[name="itens[]"]').serializeArray()
                        };
                        
                        $(this)
                            .button('loading');

                        function success() {    
                            var tabindex = selector.attr('tabindex');
                            tabindex++;
                            $('tr[tabindex=' + tabindex + ']').focus();
                            
                            selector.popUpClose();   
                            selector.remove();
//                            window.location = $('form').attr('url-redirect');
                        }

                        function complete() {

                            $('.autorizar-oc')
                                .button('reset');
                        }

                        execAjax1('POST','/_13050/autorizacao',dados,success,null,complete);
                    })
                ;

                
                
                /**
                 * Reprova ordem de compra
                 */
                $('.negar-oc')
                    .click(function() {
                        var obs = prompt('Confirma a REPROVAÇÃO desta Ordem de Compra?\n\nDigite uma observação (não obrigatório):');
                        if (obs === null) return false;

                        var id = $('#oc').val();
                        var dados = 
                        {   'id'    : id,
                            'tipo'  : '2',
                            'obs'   : obs
                        };

                        function success() {

                            var tabindex = selector.attr('tabindex');
                            tabindex++;
                            $('tr[tabindex=' + tabindex + ']').focus();
                            
                            selector.popUpClose();   
                            selector.remove();                             
//                            window.location.reload();
                        }

                        execAjax1('POST','/_13050/autorizacao',dados,success);
                    })
                ;		            
            }



            /**
             * Ação para enviar e imprimir OC.
             */
            function iniciarGerarPdfOc() {

                $('.enviar-oc')
                    .click(function() {
                        gerarPdfOc(1);
                    });

                $('.imprimir-oc')
                    .click(function() {
                        gerarPdfOc(2);
                    });
            }
            
            autorizarOc();        
            iniciarGerarPdfOc();
            excluirPdfOc();  
        }
                
        /**
         * Carrega e configura tabela de itens
         * @returns {void}
         */
        function dataTableItens() {
            var data_table = $.extend({}, table_default);
                data_table.sScrollY = 'auto';           
            $('.table-itens').DataTable(data_table);  
        }           
        
        /**
         * Carrega e configura tabela de itens
         * @returns {void}
         */
        function dataTableAutorizacoes() {
            var data_table = $.extend({}, table_default);
                data_table.sScrollY = 'auto'; 
            $('.table-autorizacoes').DataTable(data_table);  
        }           
        
        /**
         * Realiza a chamada ajax
         */
        function carregarDados() {           
            
            function success(data) {						
                if(data) {
                    $('div.modal-body').html(data);
                    selector.popUp();
                    bootstrapInit();
                    dataTableItens();
                    dataTableAutorizacoes();
                    acoes();
                }
            }

            execAjax1('GET','/_13050/show/'+param.id,null,success);
        }
        
        function clickItem() {
            $('tr[id]')
                .click(function(){
                    selector = $(this);
                    param.id = selector.attr('id');
                    carregarDados();
                })
            ;
        }
        clickItem();
		acoes();
    }

	
	$(function() {
		
        //dataTable();
	    limiteTextarea($('textarea.obs'), 200, $('span.contador span'));
		filtroOc();
        show();
        addEventBtn();
	
	});
})(jQuery);