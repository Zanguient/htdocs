(function($) {

if(window.name === 'remessa-componente') {
	window.close();
} 
    
    /**
     * Funções do index
     * @returns {void}
     */
    function index()
    {   
        
        /**
        * Realiza a chamada ajax
        */
        function reabrirTalao(id,talao_id,remessa_id,programacao_id) {           
            var dados = {
                id              : id,
                talao_id        : talao_id,
                remessa_id      : remessa_id,
                programacao_id  : programacao_id
			};
                    
           function success(data) {						
               if(data) {
                   var id;
                    var selector;
            
                   $('div.modal-body').html(data);
                        //selector.popUp();
                        dataTableItens();
                        dataTableEstacao();
                        dataTableResize();
                        bootstrapInit();
						ativarBtnSwitch();
						//switchStatus();
						//selecAbaUp();
                        //edit();
                    showSuccess('Finalização do talão desfeita');
               }
           }
           
           function erro(data) {
           }
           
            execAjax1('POST','/_22040/reabrirTalao',dados,success,erro);  
           
       }
        
        /**
         * Carrega e configura tabela
         * @returns {void}
         */
        function dataTable() {

			var data_table			= $.extend({}, table_default);
                data_table.sScrollY = '65vh'; 
				
            $('.lista-obj')
                .DataTable(data_table)
            ;
        }

        /**
         * Carrega e configura tabela de itens (show)
         * @returns {void}
         */
        function dataTableItens() {

            var data_table			= $.extend({}, table_default);
                data_table.sScrollY = 'auto'; 
                
            $('.table-itens')
                .DataTable(data_table)
            ;
        }
        
        /**
         * Ativar Datatable nas tabelas menores.
         */
        function dataTableEstacao() {

            var data_table			= $.extend({}, table_default);
                data_table.sScrollY = '197px';
                
            $('table.estacao').DataTable(data_table);
        }        
        
        function dataTableResize()
        {
            $('#accordion').on('shown.bs.collapse', function () {
				
                //setTimeout(function() {
                $(this).trigger('resize');
                //}, 300);
            });            
        }

        /**
         * Exibir (popup).
         */
        function show() {

            var id;
            var selector;
			
            function imprimirConsumo() {
                $('#imprimir-consumo').click(function(){
                    $(this).button('loading');
                    execAjax1('POST','/_22040/getPdfConsumo',{remessa_id : id, familia_id_consumo: $('#filtro-familia-id-consumo').val()},
                        function success(data) {						
                            if(data) {
                                printPdf(data);   
                            }
                        },null,
                        function complete() {
                            $('#imprimir-consumo').button('reset');
                        }
                    );
                });
            }
            
			/**
			 * Evento ao mudar o status.
			 */
			function switchStatus() {
				
				$('.chk-switch')
					.on('switchChange.bootstrapSwitch', function(event, state) {
						
						if ( state ) {
							
						}
						
					})
				;
				
			}
			
			/**
			 * Evento ao selecionar a aba de UP
			 */
			function selecAbaUp() {
				
				$('#ups-tab')
					.click(function() {
						
						setTimeout(function() {
							
							$('#ups')
								.trigger('resize')
							;
							
						}, 300);
						
					})
				;
				
			}

            /**
             * Realiza a chamada ajax
             */
            function carregarDados() {           

                function success(data) {						
                    if(data) {
                        $('.popup .modal-body').html(data);
                        selector.popUp();
                        dataTableItens();
                        dataTableEstacao();
                        dataTableResize();
                        bootstrapInit();
                        imprimirConsumo();
						ativarBtnSwitch();
						switchStatus();
						selecAbaUp();
                        edit();
                    }
                }

                execAjax1('GET','/_22040/show/'+id,null,success);
            }

            function clickItem() {

                $('.btn-componente')
                    .on('click',function(e){
                        e.stopPropagation();
                    })
                ;

                $('tr[data-remessa]')
                    .click(function(){
                        selector = $(this);
                        id = selector.data('remessa');
						$('#modal-historico .historico-corpo').data('id',id);
                        carregarDados();
                    })
                ;
            }

            clickItem();
        }

        /**
         * Realiza filtros na OC
         */
        function filtrar() {

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

                var estabelecimento_id = $('.estab'       ).val();
                var status             = $('#filter-status').val();
                var perfil             = $('#filter-perfil').val();
                var data_1             = $('.data-inicial').val();
                var data_2             = $('.data-final'  ).val();
                var pesquisa           = $('.pesquisa'    ).val();

                var dados = {
                    retorno            : 'view',
                    qtd_por_pagina     : qtd_por_pag,
                    pagina             : pagina_inc,
                    estabelecimento_id : estabelecimento_id,
                    data               : [data_1,data_2],
                    status             : status,
                    perfil			   : perfil,
                    filtro             : pesquisa
    //                familia_id         : 
    //                remessa_id         : 
    //                remessa            : 
                };

                //Ativa o botão de loading
                $('.btn-oc-filtrar').button('loading');

                execAjax1('POST','/_22040/filtrar',dados,
                    function(data) { //Função success
                        if(data) {
                            $('.lista-obj tbody').append(data);
                            show();
                            bootstrapInit();
                        } else {
                            final_pag = true;
                        }
                    },     
                    null, //Função error
                    function() { //Função complete
                        $('.btn-oc-filtrar').button('reset'); 
                    }
                );
            }

            /**
             * Realiza a parametrização inicial<br/>
             * Realiza o acionamento da consulta
             */
            function acionarFiltro() {

                var campo_pesquisa = $('.filtro-obj');
                var btn_filtrar    = $('.btn-filtrar');
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
                $('.dataTables_scrollBody').scroll(function() {

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
            $('.btn-filtrar').trigger('click');
        }
        
        $(document)
            .on('click', '.btn-reabrir-talao', function() {
                
                id = parseInt($(this).data('id'));
                talao_id = parseInt($(this).data('talao_id'));
                remessa_id = parseInt($(this).data('remessa_id'));
                programacao_id = parseInt($(this).data('programacao_id'));
                
                msg = $(this).data('msg');

                addConfirme('Remessa','Deseja reabrir o talão '+talao_id+'?',[obtn_sim,obtn_cancelar],
                    [
                        {ret:1,func:function(){ reabrirTalao(id,talao_id,remessa_id,programacao_id);}}
                    ]     
                );
                
                
            });
        ;
        
        dataTable();
        filtrar();
    }

    /**
     * Funções do edit
     * @returns {void}
     */
    function edit()
    {
        function ups()
        {
            function contextMenu()
            {

            }
            
            function checkBoxTr()
            {
                var table =  $('.up-container table');
                
                $(table)
                    .on('change', 'tbody tr td input[type="checkbox"]', function() {
                        var tr = $(this).closest('tr');
                    
                        if ( $(this).is(':checked') ) {
                            $(tr)
                                .addClass('selected');
                            ;
                        } else {
                            $(tr)
                                .removeClass('selected');
                            ;
                        }
                    })
                ;

                $(table)
                    .on('click', 'tbody tr', function() {
                        $(this)
                            .find('input[type="checkbox"]')
                            .trigger('click')
                        ;
                    });
                ;	
            }
            
            contextMenu();
            checkBoxTr();
        }
        ups();
    }
    
    
	$(function() {
		index();
	});
	
})(jQuery);