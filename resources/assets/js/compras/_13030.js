/**
 * Script com funções de:
 * - Escolher produtos para montar orçamento
 * */
(function($) {
    
    function showCotaItem(param) {
        var param = param || {};
        
        /**
         * 
         * @returns {undefined}Controla a exibição da tabela de cota extra
         */
        function showHicota_totalizadeTable() {
            $('tbody.t-body').each(function () {
                var panel = $(this).parents('div.cota-extra');
                var itens = $(this).find('td');

                (itens.length > 0) ? panel.fadeIn('low') : panel.fadeOut('low');
            });
        }        

        /**
         * Controla a exibição da tabela de redução de cota
         */
        function showHideTableOutros() {
            $('tbody.outros').each(function () {
                var panel = $(this).parents('div.cota-outros');
                var itens = $(this).find('td');

                (itens.length > 0) ? panel.fadeIn('slow') : panel.fadeOut('slow');
            });
        } 

        /**
         * Controla a exibição da tabela de itens
         */
        function showHideTableItens() {
            $('tbody.itens').each(function () {
                var panel = $(this).parents('div.cota-itens');
                var itens = $(this).find('td');

                (itens.length > 0) ? panel.fadeIn('slow') : panel.fadeOut('slow');
            });
        }         

        /**
         * 
         * @returns {undefined}Ativa a opção para copiar o atritubuto copy para o clipboard
         */
        function copyLink() {

            $('.btn-copy')
                .click(
                    function() {
                        //TODO
                    }
                )
            ;
        }

        /**
         * Realiza a chamada ajax
         */
        function tratarDados() {        
            
            function showHideTable() {
                $('tbody.t-body').each(function () {
                    var panel = $(this).parents('div.cota-extra');
                    var itens = $(this).find('td');

                    (itens.length > 0) ? panel.fadeIn('low') : panel.fadeOut('low');
                });
            }       
            
            function showHideTableOutros() {
                $('tbody.outros').each(function () {
                    var panel = $(this).parents('div.cota-outros');
                    var itens = $(this).find('td');

                    (itens.length > 0) ? panel.fadeIn('slow') : panel.fadeOut('slow');
                });
            }             

            function showHideTableItens() {
                $('tbody.itens').each(function () {
                    var panel = $(this).parents('div.cota-itens');
                    var itens = $(this).find('td');

                    (itens.length > 0) ? panel.fadeIn('slow') : panel.fadeOut('slow');
                });
            } 

            function success(data) {						
                if(data) {
                    
                    $('#dre-historico').attr('data-tabela-id',param.dados.id);
                    
                    $('#cota-modal .modal-body').html(data);
                    $('#cota-modal').modal('show');
                    
//                    $('div.modal-body').html(data);
//                    param.selector.popUp();
                    bootstrapInit();
//                    copyLink();
                    showHideTable();
                    showHideTableOutros();
                    showHideTableItens();
//                    escondevoltar();
                    $('#cota-modal').trigger('resize');
                }
            }

            execAjax1('POST','/_13030/show',param.dados,success);
        } 
        
        tratarDados();
    }    
    
    function index(){

        function dre() {

            var param = {};
            var selector;
            var table;

            /**
             * Carrega e configura tabela
             * @returns {void}
             */
            function dataTable1() {

                table = $('div.dre table.table');
//                $(table)
//                    .DataTable( 
//                        {
//                            "scrollY"  : '60vh', // Altura da tabela 
//                            "scrollX"  : true  , // Habitila a rolagem horizontal
//                            "bSort"    : false , // Desativa a ordenação
//                            "bFilter"  : false , // Desativa o filtro
//                            "bInfo"    : false , // Desativa as informações de registro
//                            "bPaginate": false   // Desativa a paginação
//                        } 
//                    )
//                ;

                popUpOpen();  
            }

            /**
             * Exibe popUp
             * @returns {void}
             */
            function popUpOpen() {

                $('.popup-show')
                    .click(
                        function(e){
                            e.stopPropagation();
                            e.preventDefault();


                            var args = {
                                ajuste_inventario : $(this).data('ajuste-inventario'),
                                ccusto            : $(this).data('ccusto'     ),
                                mes               : $(this).data('mes'        ),
                                ano               : $(this).data('ano'        )
                            };

                            if ( args.ccusto ) {

                                execAjax1('POST','/_13030/ggf',args,
                                    function(data) {

                                        $('#ggf-modal .modal-body').html(data);
                                        $('#ggf-modal').modal('show');
                                        
                                        $('#ggf-modal').trigger('resize');
                                        bootstrapInit();


                                        $('button[data-item="ggf"]').click(function(){

                                            var param = {
                                                ajuste_inventario : args.ajuste_inventario,
                                                ccusto      : $(this).data('ccusto'     ),
                                                mes         : $(this).data('mes'        ),
                                                ano         : $(this).data('ano'        ),
                                                familia_id  : $(this).data('familia_id' )
                                            };

                                            execAjax1('POST','/_13030/ggfDetalhe',param,
                                                function(data) {

                                                    $('#ggf-modal-detalhe .modal-body').html(data);
                                                    $('#ggf-modal-detalhe').modal('show');
//                                                    $('#ggf-modal-detalhe').find('.lista-obj').DataTable(table_default);  
                                                    $('#ggf-modal-detalhe').trigger('resize');
                                                    bootstrapInit();

                                                }
                                            );                
                                        });

                                    }
                                );  
                            } else {

                                var that = $(this);

                                param['show'] = $(this).attr('id');    
                                selector = $(this);
                                showCotaItem({
                                    selector : selector,
                                    dados    : {
                                        id      : param.show,
                                        ref     : 'dre'
                                    }
                                });
                            }
                        }   
                    )
                ;

            }

            /**
             * Exibe o detalhamento do item se a url requisitar
             * @returns {void}
             */
            function showItem() {

                param  = paramSplit(urlhash,'-');

                if ( param.show ) {
                    selector = $('#'+param.show);
                    tratarDados();
                }

                if ( param.id ) {
                    $('#'+param.id).focus();
                }
            }        

            function printTable() {
                $('.btn-print')
                    .click( 
                        function(){
                            $('.btn-print').button('loading');
//                            $(table).dataTable().fnDestroy();
                            var dados = {
                                'table': $('div.dre').html()
                            };    

                            function success(data) {						
                                if(data) {
                                    printPdf(data);
                                }
                            }

                            function complete() {
                                dataTable1();
                                $('.btn-print').button('reset');
                            }

                            execAjax1('POST','/_13030/dre/pdf',dados,success,null,complete);
                        }
                    )
                ;
            }

            function mesesToggle() {

                $('#meses_toggle').change(function() {
                    if($(this).is(":checked")) {
                        $('.meses-transition').removeClass('meses-ocultar');
                    } else {
                        $('.meses-transition').addClass('meses-ocultar');

                    }   
//                    $('.dre').trigger('resize');
                });
            }

            function filter() {

                var dados = {};  

                function ajax() {

                    $('.btn-dre-filtrar').button('loading');

                    function success(data) {
                        if(data) {
                            $('div.dre').html(data);
                            //$('div.print').html(data);
                            dataTable1();

                            if($('#meses_toggle').is(":checked")) {
                                $('.meses-transition').removeClass('meses-ocultar');
                            } else {
                                $('.meses-transition').addClass('meses-ocultar');
                            }   
//                            $('.dre').trigger('resize');  
                        }
                    }

                    function complete() {
                        $('.btn-dre-filtrar').button('reset');                        
                    }

                    execAjax1('POST','/_13030/dre/filter',dados,success,null,complete);             
                }

                function filedsLoad() {
                    dados = {
                        'filtro'        : $('input[name=filtro]').val() === '' ? null : $('input[name=filtro]').val(),
                        'mes_1'         : $('select[name=mes_1]').val(),
                        'mes_2'         : $('select[name=mes_2]').val(), 
                        'ano_1'         : $('select[name=ano_1]').val(),
                        'cota_zerada'   : $('input[name=cota_zerada]').is(":checked"),
                        'cota_valida'   : $('input[name=cota_valida]').is(":checked"),
                        'cota_totaliza' : $('input[name=cota_totaliza]').is(":checked"),
                        cota_faturamento : $('#dre-modal .filtro-faturamento').is(":checked"), 
                        cota_ggf         : $('#dre-modal .filtro-ggf').is(":checked"),
                        cota_ajuste_inventario : $('#dre-modal .filtro-ajuste_inventario').is(":checked")                        
                    };  

                    ajax();
                }

                function filterStart() {
                    $('.btn-dre-filtrar').click(   function(){ filedsLoad();});
                    $('.btn-dre-filtro' ).keyEnter(function(){ filedsLoad();});        
                }
                filterStart();
            }

            function modalShow() {
                
                $('button[data-action="show-dre"]').click(function(){
    //
    //            execAjax1('POST','/_13030/ggf',args,
    //                function(data) {
    //
    //                    $('#ggf-modal .modal-body').html(data);
                        $('#dre-modal').modal('show');
    //                    $('#ggf-modal ').find('.lista-obj').DataTable(table_default);  
    //                    $('#ggf-modal').trigger('resize');
    //                    bootstrapInit();
    //
    //
    //                    $('button[data-item="ggf"]').click(function(){
    //
    //                        var param = {
    //                            ccusto      : $(this).data('ccusto'     ),
    //                            mes         : $(this).data('mes'        ),
    //                            ano         : $(this).data('ano'        ),
    //                            familia_id  : $(this).data('familia_id' )
    //                        };
    //
    //                        execAjax1('POST','/_13030/ggfDetalhe',param,
    //                            function(data) {
    //
    //                                $('#ggf-modal-detalhe .modal-body').html(data);
    //                                $('#ggf-modal-detalhe').modal('show');
    //                                $('#ggf-modal-detalhe').find('.lista-obj').DataTable(table_default);  
    //                                $('#ggf-modal-detalhe').trigger('resize');
    //                                bootstrapInit();
    //
    //                            }
    //                        );                
    //                    });
    //
    //                }
    //            ); 
                });
            }
            
            $(function() {
                filter();
                dataTable1();
                printTable();
                mesesToggle();
                showItem();
                modalShow();
            });
        }
        dre();

        function ggf() {
            
            function dataTableGgf() {
//                $('#ggf-modal-detalhe').find('.lista-obj').DataTable(table_default);  
            }    

            $('button[data-item="ggf"]').click(function(){
                
                var param = {
                    ccusto      : $(this).data('ccusto'     ),
                    mes         : $(this).data('mes'        ),
                    ano         : $(this).data('ano'        ),
                    familia_id  : $(this).data('familia_id' )
                };
                
                execAjax1('POST','/_13030/ggfDetalhe',param,
                    function(data) {
                        
                        $('#ggf-modal-detalhe .modal-body').html(data);
                        bootstrapInit();
                        
                        $('#ggf-modal-detalhe').modal('show');
                        
                        dataTableGgf();
                        $('#ggf-modal-detalhe').trigger('resize');
                    }
                );                
            });
        }
        ggf();  
            
        function popUp() {
            /**
             * Abre o popup
             */
            function popUpOpen() {

                $('.popup-show')
                    .click(
                        function(){
                            showCotaItem({
                                selector : $(this),
                                dados    : {
                                    'id': $(this).attr('id'),
                                    'ref': 'index'
                                }
                            });                            
                        }   
                    )
                ;
                
            }
            popUpOpen();
        }
        
        /**
         * Filtro Accordion 
         */
        function filtrar() {

            //Efetua o filtro via ajax
            function filtroAccordion(dados) {

                $('.btn-cotas-filtrar').button('loading');
                
                function success(data) {

                    if(data) {
                        $('.Area-Acordion').html(data);
                        popUp();
                        ggf();
                        bootstrapInit();
                    }else{
                       $('.Area-Acordion').empty();
                    }
                }           
                
                function complete() {
                    $('.btn-cotas-filtrar').button('reset');                      
                }
                execAjax1('POST',pathname+'/listar',dados,success,null,complete);
            } 
            
            function filedsLoad() {
            
                var filtro          = $('.btn-cotas-filtro').val();
                var mes_inicial		= $('.filtro-mes-inicial').val();
                var ano_inicial		= $('.filtro-ano-inicial').val();
                var mes_final		= $('.filtro-mes-final').val();
                var ano_final		= $('.filtro-ano-final').val();
                var data_inicial  	= new Date(Date.parse('00:00:00 '+ano_inicial+'-'+mes_inicial+'-01'));
                var data_final   	= new Date(Date.parse('00:00:00 '+ano_final+'-'+mes_final+'-01'));
                var cota_zerada 	= $('.filtro-cota-zerada').is(":checked");
                var cota_valida  	= $('.filtro-cota-valida').is(":checked"); 
                var cota_totaliza  	= $('.filtro-totaliza').is(":checked"); 
                var cota_faturamento  	= $('#programacao-filtro .filtro-faturamento').is(":checked"); 
                var cota_ggf          	= $('#programacao-filtro .filtro-ggf').is(":checked"); 
                var cota_ajuste_inventario = $('#programacao-filtro .filtro-ajuste_inventario').is(":checked"); 
                
                var dados = {
                    'filtro'		: filtro,
                    'mes_inicial'	: mes_inicial,
                    'ano_inicial'	: ano_inicial,
                    'mes_final'		: mes_final,
                    'ano_final'		: ano_final,
                    'cota_zerada'	: cota_zerada,
                    'cota_valida'	: cota_valida,
                    'cota_totaliza'	: cota_totaliza,
                    cota_faturamento : cota_faturamento,
                    cota_ggf         : cota_ggf,
                    cota_ajuste_inventario : cota_ajuste_inventario
                };

                if (data_inicial > data_final) { 
                    showAlert('Data inválida. Data inicial maior que data final.');
                    return false;
                }
                filtroAccordion(dados);
            }
            
            function filterStart() {
                if ( !(pathname == '/_13030') ) return false;
                
                $('.btn-cotas-filtrar').click   (function(){ filedsLoad();});
                $('.btn-cotas-filtro' ).keyEnter(function(){ filedsLoad();});     
//                $(window              ).load    (function(){ filedsLoad();});                
            }
            filterStart();
        }
        filtrar();

        {
           function removeItemAccordion(classdelete,classpai,classavo,tempo) {
//                popUpSelector.popUpClose({focus: false});
                
                if ($('.'+classdelete).siblings().length == 0){

                   console.log($('.'+classpai).children().length);
                   if ($('.'+classpai).siblings().length == 0){

                        $('.'+classdelete).fadeOut(tempo);
                        setTimeout(function () { $('.'+classdelete).remove();

                            $('.'+classpai).fadeOut(tempo);
                            setTimeout(function () { $('.'+classpai).remove();

                                $('.'+classavo).fadeOut(tempo);
                                setTimeout(function () { $('.'+classavo).remove();}, tempo);

                            } , tempo);

                        }, tempo);

                    }else{

                        $('.'+classdelete).fadeOut(tempo);
                        setTimeout(function () { $('.'+classdelete).remove();

                            $('.'+classpai).fadeOut(tempo);
                            setTimeout(function () { $('.'+classpai).remove();} , tempo);

                        }, tempo);


                    }

                }else{

                    $('.'+classdelete).fadeOut(tempo);
                    setTimeout(function () { $('.'+classdelete).remove();}, tempo);

                }
            }

            $(document).on('click', '.deletar', function(){

				var contaid = $(this).attr('contaid');

				if( !confirm('Confirma exclusão desta cota?') ) return false;


				var classdelete = $(this).attr('classdelete');
				var classpai = $(this).attr('classpai');
				var classavo = $(this).attr('classavo');
				var tempo = 250;
			  
				//ajax
				var type	= "POST",
					url		= pathname+"/DeletaItemAccordion",
					data	= {'ID': contaid},
					success	= function(data) {
                        $('#cota-modal')
                            .modal('hide')
                            .one('hidden.bs.modal', function(){
                                showSuccess('Conta excluída com sucesso.<br/><b>Para atualizar os totalizadores, filtre novamente.</b>');
                                removeItemAccordion(classdelete,classpai,classavo,tempo);
                            })
                        ;
					}
				;
				
				execAjax1(type, url, data, success, null, null);
              //removeItemAccordion(classdelete,classpai,classavo,tempo);

           });

        }

        { /** Pesquisar Centro de Custo */

            var filtro;
            var campo_ccusto;
            var btn_filtro_ccusto;
            var input_group;
            var itens_ccusto;
            var _ccusto;
            var tempo_focus;
            var ccusto_selecionado = false;

            /**
             * Filtrar objeto.
             */
            function filtrarCCusto() {

                campo_ccusto		= $('#ccusto-descricao');	//campo
                btn_filtro_ccusto	= $('.btn-filtro-ccusto');

                input_group			= $(campo_ccusto).parent('.input-group');	//input-group
                _ccusto				= $('input[name="_ccusto"]');

                filtro				= campo_ccusto.val();

                //esvazia campo hidden caso algum item já tenha sido escolhido antes
                if(ccusto_selecionado) 
                    $(_ccusto).val('');

                if( !filtro ) {
                    fechaListaccusto( input_group );
                    $(_ccusto).val('');
    //				return false;
                }
    //				$('.container-fluid').shCircleLoader();
	
				//ajax
				var type	= "POST",
                    url		= "/_20030/pesquisaCCusto",
                    data	= {'filtro': filtro},
					success	= function(data) {

                        abreListaccusto( input_group );
                        $('.lista-ccusto').html(data);

                        //se existem dados cadastrados
                        if( data.indexOf('nao-cadastrado') === -1 ) {

                            itens_ccusto = $('ul.ccusto li a');

                            selecItemListaccusto( $(itens_ccusto), campo_ccusto );

                            $(itens_ccusto)
                                .focusout(function() {

                                    if(tempo_focus) clearTimeout(tempo_focus);

                                    tempo_focus = setTimeout(function() {

                                        if( !$(itens_ccusto).is(':focus') && 
                                            !$(campo_ccusto).is(':focus') && 
                                            !$(btn_filtro_ccusto).is(':focus') 
                                        ) {
                                            $(campo_ccusto).val('');
                                            fechaListaccusto( input_group );
                                        }

                                    }, 200);

                                });

                            $(campo_ccusto)
                                .focusout(function() {

                                    if(tempo_focus) clearTimeout(tempo_focus);

                                    tempo_focus = setTimeout(function() {

                                        if( !$(itens_ccusto).is(':focus') && 
                                            !$(_ccusto).val() && 
                                            !$(btn_filtro_ccusto).is(':focus') 
                                        ) {
                                            $(campo_ccusto).val('');
                                            fechaListaccusto( input_group );
                                        }

                                    }, 200);							

                                });
                        }
                        else {
                            $(_ccusto).val('');

                            $(campo_ccusto)
                                .focusout(function() {
                                    if( $('.lista-ccusto').children().children().hasClass('nao-cadastrado') ) {
                                        $(campo_ccusto).val('');
                                        fechaListaccusto( input_group );
                                    }
                                });
                        }

                    }
				;
		
				execAjax2(type, url, data, success, null, btn_filtro_ccusto);
				
            }


            //Abre resultado da filtragem
            function abreListaccusto(ccusto) {

                $(ccusto)
                    .next('.lista-ccusto-container')
                    .addClass('ativo');

                $(btn_filtro_ccusto)
                    .attr('tabindex', '-1');

            }

            //Fecha resultado da filtragem
            function fechaListaccusto(ccusto) {

                $(ccusto)
                    .next('.lista-ccusto-container')
                    .removeClass('ativo')
                    .children('.lista-ccusto')
                    .empty();

                $(btn_filtro_ccusto)
                    .removeAttr('tabindex');

            }

            //Preencher campos de acordo com o item selecionado
            function selecItemListaccusto(itens, campo) {

                $(itens).click(function(e) {

                    e.preventDefault();

                    $(campo)
                        .val( $(this).text() )
                        .focus();

                    selecionadoCCusto();

                    $(_ccusto)
                        .val( $(this).nextAll('.ccusto-id').val() )
                        .trigger('change');

                    fechaListaccusto( input_group );
                    ccusto_selecionado = true;
                });

            }

            /**
             * Ações que devem acontecer após o item ser selecionado.
             * 
             * @returns {undefined}
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

                        $('input[name="_ccusto"]')
                            .val('');

                    });
            }

            /**
             * Eventos para o filtro de CCusto.
             */
            function iniciarFiltroCCusto() {

                //Se o item já estiver selecionado (tela de update), efetua as devidas ações.
                if ( $('input[name="_ccusto"]').val() !== '' )
                    selecionadoCCusto();

                //Botão de filtrar
                $('.btn-filtro-ccusto').on({

                    click: function() {
                        if ( !$(_ccusto).val() )
                            filtrarCCusto();
                    },

                    focusout: function() {

                        if(tempo_focus) clearTimeout(tempo_focus);

                        tempo_focus = setTimeout(function() {

                            if ( !$('input[name="_ccusto"]').val() && $('.lista-ccusto').is(':empty') ) {
                               $('#ccusto-descricao').val('');
                            }

                            if ( !$('input[name="_ccusto"]').val() && 
                                 !$('.lista-ccusto ul li a').is(':focus') && 
                                  $('#ccusto-descricao').val() 
                            ) {
                                $('#ccusto-descricao').val('');
                                fechaListaccusto(input_group);
                            }

                            if ( !$('input[name="_ccusto"]').val() && 
                                 !$('.lista-ccusto ul li a').is(':focus') && 
                                 !$('#ccusto-descricao').val() 
                            ) {
                                fechaListaccusto(input_group);
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
                        if(tempo_focus) clearTimeout(tempo_focus);

                        tempo_focus = setTimeout(function() {

                            if ( !$('input[name="_ccusto"]').val() && 
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

        { /** Pesquisar Conta Contábil */

            var filtro;
            var campo_ccontabil;
            var btn_filtro_ccontabil;
            var input_group;
            var itens_ccontabil;
            var _ccontabil_id;
            var tempo_focus;
            var ccontabil_selecionado = false;

            /**
             * Filtrar objeto.
             */
            function filtrarCContabil() {

                var ccontabil_tipo;

                var campo_ccontabil_tipo	= $('#ccontabil-tipo');

                campo_ccontabil			= $('#ccontabil-descricao');	//campo
                btn_filtro_ccontabil	= $('.btn-filtro-ccontabil');

                input_group				= $(campo_ccontabil).parent('.input-group');	//input-group
                _ccontabil_id			= $('input[name="_ccontabil"]');

                filtro 			= campo_ccontabil.val();
                ccontabil_tipo	= campo_ccontabil_tipo.val();

                //esvazia campo hidden caso algum item já tenha sido escolhido antes
                if(ccontabil_selecionado) 
                    $(_ccontabil_id).val('');

                if( !filtro ) {
                    fechaListaccontabil( input_group );
                    $(_ccontabil_id).val('');
                    //return false;
                }
				
				//ajax
				var type	= "POST",
                    url		= "/_17010/pesquisa",
                    data	= {
                        'filtro'		:	filtro,
                        'ccontabil_tipo':	ccontabil_tipo
					},
					success	= function(data) {

                        abreListaccontabil( input_group );
                        $('.lista-ccontabil').html(data);

                        //existem dados cadastrados
                        if( data.indexOf('nao-cadastrado') === -1 ) {

                            itens_ccontabil = $('ul.ccontabil li a');

                            selecItemListaccontabil( $(itens_ccontabil), campo_ccontabil );

                            $(itens_ccontabil).focusout(function() {

                                if(tempo_focus) clearTimeout(tempo_focus);

                                tempo_focus = setTimeout(function() {
                                    if( !$(itens_ccontabil).is(':focus') && !$(campo_ccontabil).is(':focus') && !$(btn_filtro_ccontabil).is(':focus') ) {
                                        $(campo_ccontabil).val('');
                                        fechaListaccontabil( input_group );
                                    }
                                }, 200);

                            });

                            $(campo_ccontabil).focusout(function() {

                                if(tempo_focus) clearTimeout(tempo_focus);

                                tempo_focus = setTimeout(function() {

                                    if( !$(itens_ccontabil).is(':focus') && !$(_ccontabil_id).val() && !$(btn_filtro_ccontabil).is(':focus') ) {
                                        $(campo_ccontabil).val('');
                                        fechaListaccontabil( input_group );
                                    }
                                }, 200);

                            });
                        }
                        else {
                            $(_ccontabil_id).val('');

                            $(campo_ccontabil).focusout(function() {
                                if( $('.lista-ccontabil').children().children().hasClass('nao-cadastrado') ) {
                                    $(campo_ccontabil).val('');
                                    fechaListaccontabil( input_group );
                                }
                            });
                        }

                    }
				;
		
				execAjax2(type, url, data, success, null, btn_filtro_ccontabil);

            }


            //Abre resultado da filtragem
            function abreListaccontabil(ccontabil) {
                $(ccontabil).next('.lista-ccontabil-container').addClass('ativo');
                $(btn_filtro_ccontabil).attr('tabindex', '-1');
            }

            //Fecha resultado da filtragem
            function fechaListaccontabil(ccontabil) {
                $(ccontabil).next('.lista-ccontabil-container').removeClass('ativo').children('.lista-ccontabil').empty();
                $(btn_filtro_ccontabil).removeAttr('tabindex');
            }

            //Preencher campos de acordo com o item selecionado
            function selecItemListaccontabil(itens, campo) {

                $(itens).click(function(e) {

                    e.preventDefault();

                    $(campo)
                        .val( $(this).text() )
                        .focus();

                    selecionadoCcontabil();

                    $(_ccontabil_id)
                        .val( $(this).nextAll('.ccontabil-conta').val() )
                        .trigger('change');

                    fechaListaccontabil( input_group );
                    ccontabil_selecionado = true;

                });

            }

            /**
             * Ações que devem acontecer após o item ser selecionado.
             * 
             * @returns {undefined}
             */
            function selecionadoCcontabil() {

                $('#ccontabil-descricao')
                    .attr('readonly', true);

                $('.btn-filtro-ccontabil')
                    .hide();

                $('.btn-apagar-filtro-ccontabil')
                    .show()
                    .click(function() {

                        $(this)
                            .siblings('input')
                            .removeAttr('readonly');

                        $(this)
                            .hide()
                            .prev('button')
                            .show();

                        $('#ccontabil-descricao')
                            .val('')
                            .focus();

                        $('input[name="_ccontabil"]')
                            .val('');

                    });
            }

            //Se o item já estiver selecionado (tela de update), efetua as devidas ações.
            if ( $('input[name="_ccontabil"]').val() !== '' )
                selecionadoCcontabil();

            //Botão de filtrar
            $('.btn-filtro-ccontabil').on({

                click: function() {
                    if ( !$(_ccontabil_id).val() )
                        filtrarCContabil();
                },

                focusout: function() {

                    if(tempo_focus) clearTimeout(tempo_focus);

                    tempo_focus = setTimeout(function() {

                        if ( !$('input[name="_ccontabil_id"]').val() && $('.lista-ccontabil').is(':empty') ) {
                           $('#ccontabil-descricao').val('');
                        }

                        if ( !$('input[name="_ccontabil_id"]').val() && 
                             !$('.lista-ccontabil ul li a').is(':focus') && 
                              $('#ccontabil-descricao').val() 
                        ) {
                            $('#ccontabil-descricao').val('');
                            fechaListaccontabil(input_group);
                        }

                        if ( !$('input[name="_ccontabil_id"]').val() && 
                             !$('.lista-ccontabil ul li a').is(':focus') && 
                             !$('#ccontabil-descricao').val() 
                        ) {
                            fechaListaccontabil(input_group);
                        }

                    }, 200);
                }

            });


            //Campo de filtro
            $('#ccontabil-descricao').on({

                keydown: function(e) {

                    //Eventos após a escolha de um item
                    if ( $(this).is('[readonly]') ) {

                        //Deletar teclando 'Backspace' ou 'Delete'
                        if ( (e.keyCode === 8) || (e.keyCode === 46) ) {
                            $('.btn-apagar-filtro-ccontabil').click();
                        }
                    }
                    else {

                        //Pesquisar com 'Enter'
                        if (e.keyCode === 13) {
                            filtrarCContabil();
                        }
                    }
                },

                focusout: function() {

                    //verificar quando o campo deve ser zerado
                    if(tempo_focus) clearTimeout(tempo_focus);

                    tempo_focus = setTimeout(function() {

                        if ( !$('input[name="_ccontabil_id"]').val() && 
                             $('.lista-ccontabil').is(':empty') &&
                             !$('.btn-filtro-ccontabil').is(':focus') 
                        ) {
                            $('#ccontabil-descricao').val('');
                        }

                    }, 200);
                }

            });

        }

        //var mes;
        //var ano;
        //var ccusto_id;
        //var data_val;
        //var data;
        //
        //$('.incluir-ccusto').click(function(e){
        //
        //	data_val = Date.parse('00:00:00 '+$('input[name="data_cota"]').val()+'-01');
        //	data = new Date(data_val);
        //
        //	ccusto_id  = $(_ccusto).val();
        //	mes = data.getMonth()+1;
        //	ano = data.getFullYear();
        //	console.log('passo 1');
        //	if(mes!='' && ano!='' && ccusto_id!=''){
        //		$.ajax({
        //			type: "POST",
        //			url: pathname+"/consultaSaldo",
        //			data: {
        //				'ccusto_id': ccusto_id,
        //				'mes': mes,
        //				'ano': ano},
        //			success:
        //				function(data) {
        //					if(data.indexOf('falha') == -1){
        //						console.log('passo 2');
        //					}
        //					else{
        //						console.log('Valores não localizados');
        //					}
        //
        //
        //
        //				},
        //			error: function(e) {
        //				console.log('Erro ao localizar a cota do centro de custo (Ajax): '+e);
        //			}
        //		});
        //	}
        //	e.preventDefault();
        //});


    //	/*
    //	 * Alteração de Cotas
    //	 */
    //	{
    //		//Abre o modal de alteração de cota
    //		var ccustoId = "";
    //		var ccustoDescricao = "";
    //		var conta = "";
    //		var contaDescricao = "";
    //		var mes = "";
    //		var mesDescricao = "";
    //		var ano = "";
    //		var valor = "";
    //		var expandido = false;
    //		$('.edit-cota-conta').click(function () {
    //
    //			ccustoId = $(this).attr('ccusto_id');
    //			ccustoDescricao = $(this).attr('ccusto_descricao');
    //			conta = $(this).attr('conta');
    //			contaDescricao = $(this).attr('conta_descricao');
    //			mes = $(this).attr('mes');
    //			mesDescricao = $(this).attr('mes_descricao');
    //			ano = $(this).attr('ano');
    //			valor = $(this).attr('valor');
    //
    //			expandido = $(this).parent().prev('a').attr('aria-expanded');
    //
    //			console.log(expandido);
    //			$('#modal-edit .ccusto-descricao').children('span').text(ccustoDescricao);
    //			$('#modal-edit .conta-descricao').children('span').text(contaDescricao);
    //			$('#modal-edit .periodo').children('span').text(mesDescricao + ' de ' + ano);
    //			$('#modal-edit .valor').children('span').text(mesDescricao + ' de ' + ano);
    //			$('#modal-edit .cota').val(valor);
    //		});
    //
    //		$('.salvar-alt').click(function () {
    //			if (ccustoId != '' && conta != '' && mes != '' && ano != '' && valor != '') {
    //
    //				$.ajax({
    //					type: "PUT",
    //					url: pathname+"/update",
    //					data: {
    //						'ccusto_id': ccustoId,
    //						'conta': conta,
    //						'mes': mes,
    //						'ano': ano,
    //						'valor': $('#modal-edit .cota').val()
    //					},
    //					success: function (data) {
    //						if (data.indexOf('sucesso') != -1) {
    //
    //							$('#modal-edit').modal('toggle');
    //							//$('.alert-success').children('.texto').text('Alteração realizada com sucesso!');
    //							setTimeout(function () {
    //								//$('.alert-success').fadeIn('normal', function () {
    //								//	$(this).removeClass('alerta');
    //								//});
    //							}, 500);
    //
    //
    //							listarCotas(ccustoId, conta, mes, ano, expandido);
    //
    //
    //						}
    //						else {
    //							//$('.alert-danger').children('.texto').text(data);
    //							setTimeout(function () {
    //								//$('.alert-danger').fadeIn('normal', function () {
    //								//	$(this).removeClass('alerta');
    //								//});
    //							}, 500);
    //						}
    //
    //
    //					},
    //					error: function (xhr, ajaxOptions, thrownError) {
    //						var mensagemErro = retornaMensagemErro(xhr);
    //						//$('.alert-danger').children('.texto').text(mensagemErro + 'Ocorreu uma falha. Requisição não enviada ao servidor.');
    //						setTimeout(function () {
    //							//$('.alert-danger').fadeIn('normal', function () {
    //							//	$(this).removeClass('alerta');
    //							//});
    //						}, 500);
    //					}
    //				});
    //			}
    //
    //		});
    //
    //
    //		/*
    //		 * Controles iniciais do modal de alteração de cota
    //		 */
    //		$('#modal-edit').on('shown.bs.modal', function () {
    //			$('.cota').focus();
    //		});
    //
    //		$('#modal-edit').on('hidden.bs.modal', function () {
    //			$('.toggle-btn').attr('checked', false);
    //			$('.toggle-item').fadeOut('normal');
    //		});
    //
    //		$('.toggle-btn').click(function () {
    //			$(".toggle-item").fadeToggle('normal');
    //
    //		});
    //
    //		function listarCotas(ccusto_id, conta, mes, ano, expandido) {
    //			$.ajax({
    //				type: "POST",
    //				url: pathname+"/listarCotas",
    //				data: {
    //					'ccusto_id': ccusto_id,
    //					'conta': conta,
    //					'mes': mes,
    //					'ano': ano,
    //					'expandido': expandido
    //				},
    //				success: function (data) {
    //					$('.cotas').children('.panel-body').empty();
    //					$('.cotas').children('.panel-body').append(data);
    //				},
    //				error: function (e) {
    //					console.log('erro');
    //				}
    //			});
    //			expandido = '';
    //		}
    //	}


        { /** Verifica cota existente de acordo com o ano 13030.create */

            /** Realiza a consulta de verificação */
            $('.ccusto, .ccontabil, .mes-inicial, .ano-inicial, .mes-final, .ano-final').change(function () {

                setTimeout(function() {
                    var ccusto		= $('.ccusto').val();
                    var ccontabil	= $('.ccontabil').val();
                    var valor		= $('.valor').val();
                    var mesInicial	= $('.mes-inicial').val();
                    var anoInicial	= $('.ano-inicial').val();
                    var mesFinal	= $('.mes-final').val();
                    var anoFinal	= $('.ano-final').val();
                    var dataInicial  = new Date(Date.parse('00:00:00 '+anoInicial+'-'+mesInicial+'-01'));
                    var dataFinal   = new Date(Date.parse('00:00:00 '+anoFinal+'-'+mesFinal+'-01'));
                    var validaData  = !(dataInicial > dataFinal);

                    //Limpa as informações sobre cotas existentes
                    $('.cotas-existentes').empty();
                    //Aplica os valores originais no botão GRAVAR (para não chamar o modal ao clicar em gravar)
                    $('button.btn-modal').removeAttr('data-toggle').removeAttr('data-target').addClass('js-gravar').attr('type', 'submit');
                    //Aplica os valores originais do botão GRAVAR do MODAL (para não chamar o submit no modal)
                    $('button.btn-confirma').removeClass('js-gravar').attr('type', 'button');

                    if (ccusto && ccontabil && dataInicial && dataFinal) {

                        if ( validaData ) {
							
							//ajax
							var type	= "POST",
                                url		= "/_13030/consultaCota",
                                data	= {
                                    'ccusto'	: ccusto,
                                    'ccontabil'	: ccontabil,
                                    'mesInicial'	: mesInicial,
                                    'anoInicial': anoInicial,
                                    'mesFinal'	: mesFinal,
                                    'anoFinal'	: anoFinal
                                },
								success	= function (data) {
                                    if (data.indexOf('sucesso') !== -1) {
                                        $('.cotas-existentes').append(data);
                                        $('[data-toggle="tooltip"]').tooltip();

                                        //Remove o js-gravar do GRAVAR da página e coloca para o modal
                                        $('button.btn-modal').attr('data-toggle','modal').attr('data-target','#modal-cotas-existentes').removeClass('js-gravar').attr('type', 'button');
                                        $('button.btn-confirma').addClass('js-gravar').attr('type', 'submit');

                                    } else {
                                        if (data) {
                                            $('.alert').children('.texto').text('Ocorreu uma falha: ' + data);
                                            $('.alert').addClass('alert-danger').fadeIn();
                                        }
                                    }
                                }
							;
							
							execAjax1(type, url, data, success, null, null, false);

                        }else {
                            $('.alert').children('.texto').text('Data inválida! Data inicial maior que a final.');
                            $('.alert').addClass('alert-danger').fadeIn();
                        }

                    }

                }, 1);

            });

            /** Verifica onde será realizado o submit */
            $('button.btn-modal, button.btn-confirma').click( function(e) {
                var ccusto		= $('.ccusto').val();
                var ccontabil	= $('.ccontabil').val();
                var valor		= $('.valor').val();
                var mesInicial	= $('.mes-inicial').val();
                var anoInicial	= $('.ano-inicial').val();
                var mesFinal	= $('.mes-final').val();
                var anoFinal	= $('.ano-final').val();
                var dataInicial  = new Date(Date.parse('00:00:00 '+anoInicial+'-'+mesInicial+'-01'));
                var dataFinal   = new Date(Date.parse('00:00:00 '+anoFinal+'-'+mesFinal+'-01'));
                var validaData  = !(dataInicial > dataFinal);
                var btnConfirma = $('button.btn-confirma');
                var modalCota	= $('#modal-cotas-existentes');

                //Clicou no gravar padrão (cancela 
                if ( $(this).hasClass('btn-modal') && btnConfirma.attr('type')==='submit' ) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('button.btn-confirma').trigger('click');
                }
                if (btnConfirma.attr('type')==='submit' && modalCota.is(':visible') === false && ccusto && ccontabil && valor && validaData) {
                    modalCota.modal('show');
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
    //
    //		$(document).on('click', '.btn-sim-todos', function(){
    //			$('.btn-sim-item').addClass('active').children('input').attr('checked', true);
    //			$('.btn-nao-item').removeClass('active').children('input').removeAttr('checked', false);
    //		});
    //		$(document).on('click', '.btn-nao-todos', function(){
    //			$('.btn-nao-item').addClass('active').children('input').attr('checked', true);
    //			$('.btn-sim-item').removeClass('active').children('input').removeAttr('checked', false);
    //		});
    //		//$(document).on('click', '.btn-sim-item', function(){
    //		//	alert('clicou Sim ITEM');
    //		//});
    //		//$(document).on('click', '.btn-nao-item', function(){
    //		//	alert('clicou Nao ITEM');
    //		//});

        }

        {/** Alteração de Cota 13030.edit*/



            {/** Cota extra */

                {/** Add cota extra */
                    $('.btn-add-cota').click( function() {

                        var datahora	= dataFormatada();
                        var usuario		= $('input[name=usuario_nome]').val();
                        var field_valor = $('input[name=cota_extra]');
                        var field_obs   = $('textarea[name=observacao]');
                        var valor		= field_valor.val();
                        var obs         = field_obs.val();
                        var lineTable   =
                            '<tr class="item-dinamico" style="display: none">\
                                    <td class="t-text t-medium">' + usuario + '</td>\
                                    <td class="t-numb t-low">R$ ' + valor + '</td>\
                                    <td class="t-center t-medium">' + datahora + '</td>\
                                    <td class="t-text t-extra-large limit-width">' + obs + '</td>\
                                    <td class="t-center t-low t-btn"><button type="button" class="btn btn-danger btn-sm btn-remove-cota"><span class="glyphicon glyphicon-trash remove"></span></button></td>\
                                    <input type="hidden" name="cota_extra_add[valor][]" value="' + formataPadrao(valor) + '">\
                                    <input type="hidden" name="cota_extra_add[obs][]" value="' + obs + '">\
                             </tr>';

                        if ( valor && obs ) {                                 

                            $('tbody.t-body').append(lineTable);
                            $('tr.item-dinamico').fadeIn('slow').removeClass('item-dinamico');
                            bootstrapInit();
                            showHideTable();

                            field_valor.val('');
                            field_obs.val('');
                        } else if ( !valor ) {
                            field_valor.focus();                               
                        } else if ( !obs ) {
                            field_obs.focus();                               
                        }


                    });
                }

                {/** Remove cota extra */
                    $(document).on('click', '.btn-remove-cota', function(){
                        var linhaCota  = $(this).parent().parent('tr');
                        var itemId	   = $(this).parents('tr').attr('data-id');
                        var lineRemove = '<input type="hidden" name="cota_extra_del[]" value="' + itemId + '">'; 

                        linhaCota.fadeOut('low');
                        setTimeout(function() {
                            linhaCota.remove();
                            itemId === undefined ? '' : $('.input-hiddens').append(lineRemove);
                            showHideTable();
                        }, 200);
                    });
                }

                {/** Oculta/Mostra tabela se hover registros */

                    function showHideTable() {
                        $('tbody.t-body').each(function () {
                            var panel = $(this).parents('div.cota-extra');
                            var itens = $(this).find('td');

                            (itens.length > 0) ? panel.fadeIn('low') : panel.fadeOut('low');
                        });
                    }

                    showHideTable();

                }

                {/** Limite do textarea */
                    limiteTextarea( $('textarea.obs'), 200, $('span.contador span') ); //função em master.js
                }

            }

            {/** Cota Outros */

                {/** Add cota outros */
                    $('.btn-add-outros').click( function() {
                        var datahora	= dataFormatada();
                        var usuario		= $('input[name=usuario_nome]').val();
                        var field_valor	= $('input[name=outros_valor]');
                        var field_obs   = $('textarea[name=outros_observacao]');
                        var valor		= field_valor.val();
                        var obs		= field_obs.val();
                        var lineTable =
                            '<tr class="outros-item-dinamico" style="display: none">\
                                    <td class="t-text t-medium">' + usuario + '</td>\
                                    <td class="t-numb t-low">R$ ' + valor + '</td>\
                                    <td class="t-center t-medium">' + datahora + '</td>\
                                    <td class="t-text t-extra-large limit-width">' + obs + '</td>\
                                    <td class="t-center t-low t-btn"><button type="button" class="btn btn-danger btn-sm btn-remove-outros"><span class="glyphicon glyphicon-trash remove"></span></button></td>\
                                    <input type="hidden" name="cota_outros_add[valor][]" value="' + formataPadrao(valor) + '">\
                                    <input type="hidden" name="cota_outros_add[obs][]" value="' + obs + '">\
                             </tr>';

                        if ( valor && obs ) {                                 

                            $('tbody.outros').append(lineTable);
                            $('tr.outros-item-dinamico').fadeIn('slow').removeClass('outros-item-dinamico');
                            bootstrapInit();
                            showHideTableOutros();

                            field_valor.val('');
                            field_obs.val('');
                        } else if ( !valor ) {
                            field_valor.focus();                               
                        } else if ( !obs ) {
                            field_obs.focus();                               
                        }


                    });
                }

                {/** Remove cota outros */
                    $(document).on('click', '.btn-remove-outros', function(){
                        var linhaCota  = $(this).parent().parent('tr');
                        var itemId	   = $(this).parents('tr').attr('data-id');
                        var lineRemove = '<input type="hidden" name="cota_outros_del[]" value="' + itemId + '">'; 

                        linhaCota.fadeOut('low');
                        setTimeout(function() {
                            linhaCota.remove();
                            itemId === undefined ? '' : $('.input-hiddens').append(lineRemove);
                            showHideTableOutros();
                        }, 200);
                    });
                }

                {/** Oculta/Mostra tabela se hover registros */

                    function showHideTableOutros() {
                        $('tbody.outros').each(function () {
                            console.log('ui');
                            var panel = $(this).parents('div.cota-outros');
                            var itens = $(this).find('td');

                            (itens.length > 0) ? panel.fadeIn('slow') : panel.fadeOut('slow');
                        });
                    }				
                    showHideTableOutros();	
                }

                {/** Limite do textarea */
                    limiteTextarea( $('textarea.obs-outros'), 200, $('span.contador-outros span') ); //função em master.js
                }

            }
        }

        {/** Oculta o flash message */
            $('.alert .close').click(function () {$(this).parent().removeClass('alert-success').removeClass('alert-info').removeClass('alert-warning').removeClass('alert-danger');});
        }

        { /** Redimensiona lista de acordo com o tamanho da tela */
           function redimensionaTabela() {

               if( $('fieldset .cotas > .panel-body').length > 0 ) {
                   $('fieldset .cotas > .panel-body').height(document.documentElement.clientHeight - 380);
               }
           }

           redimensionaTabela();

           $(window).resize(function () {
               redimensionaTabela();
           });
        }

        function dataFormatada() {
            var data	 = new Date(),
                dia		 = strPad(data.getDate(), 2, '0'),
                mes		 = strPad(data.getMonth() + 1, 2, '0'),
                ano		 = strPad(data.getFullYear(), 2, '0'),
                hora	 = strPad(data.getHours(), 2, '0'),
                minutos	 = strPad(data.getMinutes(), 2, '0'),
                segundos = strPad(data.getSeconds(), 2, '0');

            return [dia, mes, ano].join('/') + ' ' + [hora, minutos, segundos].join(':');
        }

        function strPad(i,l,s) {
            var o = i.toString();
            if (!s) { s = '0'; }
            while (o.length < l) {
                o = s + o;
            }
            return o;
        };
    }

    function dre(){
        //Verifica o caminho acessado
        if ( !(pathname === '/_13030/dre') ) return false;
               
        var param = {};
        var selector;
        var table;
                
        /**
         * Carrega e configura tabela
         * @returns {void}
         */
        function dataTable1() {
            
            table = $('div.dre table.table');
//            $(table)
//                .DataTable( 
//                    {
//                        "scrollY"  : '60vh', // Altura da tabela 
//                        "scrollX"  : true  , // Habitila a rolagem horizontal
//                        "bSort"    : false , // Desativa a ordenação
//                        "bFilter"  : false , // Desativa o filtro
//                        "bInfo"    : false , // Desativa as informações de registro
//                        "bPaginate": false   // Desativa a paginação
//                    } 
//                )
//            ;

            popUpOpen();  
        }
                        
        /**
         * Exibe popUp
         * @returns {void}
         */
        function popUpOpen() {
            
            $('.popup-show')
                .click(
                    function(e){
                        e.stopPropagation();
                        e.preventDefault();
                        
                        
                        var args = {
                            ccusto      : $(this).data('ccusto'     ),
                            mes         : $(this).data('mes'        ),
                            ano         : $(this).data('ano'        )
                        };

                        if ( args.ccusto ) {

                            execAjax1('POST','/_13030/ggf',args,
                                function(data) {

                                    $('#ggf-modal .modal-body').html(data);
                                    $('#ggf-modal').modal('show');
//                                    $('#ggf-modal ').find('.lista-obj').DataTable(table_default);  
                                    $('#ggf-modal').trigger('resize');
                                    bootstrapInit();

                                    
                                    $('button[data-item="ggf"]').click(function(){

                                        var param = {
                                            ccusto      : $(this).data('ccusto'     ),
                                            mes         : $(this).data('mes'        ),
                                            ano         : $(this).data('ano'        ),
                                            familia_id  : $(this).data('familia_id' )
                                        };

                                        execAjax1('POST','/_13030/ggfDetalhe',param,
                                            function(data) {

                                                $('#ggf-modal-detalhe .modal-body').html(data);
                                                $('#ggf-modal-detalhe').modal('show');
//                                                $('#ggf-modal-detalhe').find('.lista-obj').DataTable(table_default);  
                                                $('#ggf-modal-detalhe').trigger('resize');
                                                bootstrapInit();

                                            }
                                        );                
                                    });
                                    
                                }
                            );  
                        } else {

                            var that = $(this);

                            param['show'] = $(this).attr('id');    
                            selector = $(this);
                            showCotaItem({
                                selector : selector,
                                dados    : {
                                    id      : param.show,
                                    ref     : 'dre'
                                }
                            });
                        }
                    }   
                )
            ;
            
        }

        /**
         * Exibe o detalhamento do item se a url requisitar
         * @returns {void}
         */
        function showItem() {
            
            param  = paramSplit(urlhash,'-');
            
            if ( param.show ) {
                selector = $('#'+param.show);
                tratarDados();
            }
            
            if ( param.id ) {
                $('#'+param.id).focus();
            }
        }        
        
        function printTable() {
            $('.btn-print')
                .click( 
                    function(){
                        $('.btn-print').button('loading');
//                        $(table).dataTable().fnDestroy();
                        var dados = {
                            'table': $('div.dre').html()
                        };    
                        
                        function success(data) {						
                            if(data) {
                                printPdf(data);
                            }
                        }
                        
                        function complete() {
                            dataTable1();
                            $('.btn-print').button('reset');
                        }
                        
                        execAjax1('POST','/_13030/dre/pdf',dados,success,null,complete);
                    }
                )
            ;
        }
        
        function mesesToggle() {
            
            $('#meses_toggle').change(function() {
                if($(this).is(":checked")) {
                    $('.meses-transition').removeClass('meses-ocultar');
                } else {
                    $('.meses-transition').addClass('meses-ocultar');
                    
                }   
                $('.dre').trigger('resize');
            });
        }
        
        function filter() {
            
            var dados = {};  
            
            function ajax() {

                $('.btn-dre-filtrar').button('loading');

                function success(data) {
                    if(data) {
                        $('div.dre').html(data);
                        //$('div.print').html(data);
                        dataTable1();
                
                        if($('#meses_toggle').is(":checked")) {
                            $('.meses-transition').removeClass('meses-ocultar');
                        } else {
                            $('.meses-transition').addClass('meses-ocultar');
                        }   
                        $('.dre').trigger('resize');  
                    }
                }
                
                function complete() {
                    $('.btn-dre-filtrar').button('reset');                        
                }

                execAjax1('POST',pathname + '/filter',dados,success,null,complete);             
            }
            
            function filedsLoad() {
                dados = {
                    'filtro'         : $('input[name=filtro]').val() === '' ? null : $('input[name=filtro]').val(),
                    'mes_1'          : $('select[name=mes_1]').val(),
                    'mes_2'          : $('select[name=mes_2]').val(), 
                    'ano_1'          : $('select[name=ano_1]').val(),
                    'cota_zerada'    : $('input[name=cota_zerada]').is(":checked"),
                    'cota_valida'    : $('input[name=cota_valida]').is(":checked"),
                    'cota_totaliza'  : $('input[name=cota_totaliza]').is(":checked"),
                    cota_faturamento : $('#dre-modal .filtro-faturamento').is(":checked"), 
                    cota_ggf         : $('#dre-modal .filtro-ggf').is(":checked"),
                    cota_ajuste_inventario : $('#dre-modal .filtro-ajuste_inventario').is(":checked")
                };  
                
                ajax();
            }
            
            function filterStart() {
                $('.btn-dre-filtrar').click(   function(){ filedsLoad();});
                $('.btn-dre-filtro' ).keyEnter(function(){ filedsLoad();});        
            }
            filterStart();
        }
        
        $(function() {
            filter();
            dataTable1();
            printTable();
            mesesToggle();
            showItem();
        });
    }
    
    function faturamento() {
        //Verifica o caminho acessado
        if ( !(pathname === '/_13030/faturamento') ) return false;
        
        var table = $('.table-faturamento');     

        function dataTable() {     
            var data_table = $.extend({}, table_default);
                data_table.scrollY = 'auto';             
            
//            table.DataTable(data_table);   
            
            function acoes() {
                var tr;
                
                function alterar() {

                    function clickAlterar() {
                        $(table)
                            .find('.btn-alterar')
                            .click(
                                function() {
                                    tr = $(this).closest('tr');

                                    $(tr)
                                        .find('td[field-js=alterar-input]')
                                        .each(function(){
                                            $(this).children('.span').hide();
                                            $(this).children('.input').css('display', 'inline-block');
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
                        $(table)
                            .find('.btn-cancel')
                            .click(
                                function() {    
                                    tr = $(this).closest('tr');
                                    
                                    $(tr)
                                        .find('td[field-js=alterar-input]')
                                        .each(function(){
                                            $(this).children('.span').css('display', 'inline-block');
                                            $(this).children('.input').hide();
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
                        
                        $(table)
                            .find('.btn-confirm')
                            .click(
                                function() {    
                                    tr = $(this).closest('tr'); 
                                    
                                    input_estab = tr.find('select[name="estab"]');
                                    input_mes   = tr.find('select[name="mes"]'  );
                                    input_ano   = tr.find('select[name="ano"]'  );
                                    input_valor = tr.find('input[name="valor"]');
                                
                                    param.ID    = $(tr).attr('data-id');
                                    param.ESTABELECIMENTO_ID = parseFloat(input_estab.val());
                                    param.MES   = parseFloat(input_mes.val());
                                    param.ANO   = parseFloat(input_ano.val());
                                    param.VALOR = formataPadrao(input_valor.val());
                                    
//                                    input_valor.val(number_format(input_valor.val(), 2, ',', '.'));
                                                                        
                                    execAjax1('PUT',pathname,param,function(data) {
                                    
                                        tr.find('span[name="estab"]').text(input_estab.find(':selected').text());
                                        tr.find('span[name="data"]' ).text(input_mes.find(':selected').text() + ' DE ' + input_ano.find(':selected').text());
                                        tr.find('span[name="valor"]').text('R$ ' + input_valor.val());

                                        $(tr)
                                            .find('td[field-js=alterar-input]')
                                            .each(function(){
                                                $(this).children('.span').css('display', 'inline-block');
                                                $(this).children('.input').hide();
                                            })
                                        ;

                                        $(tr).find('.btn-alterar').css('display', 'inline-block');
                                        $(tr).find('.btn-confirm').hide();

                                        $(tr).find('.btn-excluir').css('display', 'inline-block');
                                        $(tr).find('.btn-cancel').hide();     
                                        
                                        
                                        showSuccess('Faturamento atualizado com sucesso!');
                                    });
                                }
                            )
                        ;
                    }
                    
                    clickAlterar();
                    clickConfirmar();
                    clickCancelar();
                }
                
                function excluir() {
                    $(table)
                        .find('.btn-excluir')                    
                        .click( function(){
                            tr = $(this).closest('tr'); 
                            var param = {};
                            param.ID = $(tr).attr('data-id');

                            execAjax1('DELETE',pathname,param,function(data) {
                                tr.fadeOut('slow');
                                setTimeout(function() {
                                    tr.remove();
                                    
                                    showSuccess('Faturamento excluído com sucesso!');
                                }, 200);
                            });
                        })
                    ;                    
                }
                
                alterar();
                excluir();
            }
            acoes();
        }     
        
        function cadastrar() {
            var param = {};
            var div_cad = $('div.faturamento-cadastrar');
            $(div_cad)
                .find('.btn-gravar')
                .click(function(){
                    
                    var input_estab = div_cad.find('select[name="estab"]');
                    var input_mes   = div_cad.find('select[name="mes"]'  );
                    var input_ano   = div_cad.find('select[name="ano"]'  );
                    var input_valor = div_cad.find('input[name="valor"]');
//                    input_valor.val(number_format(input_valor.val(), 2, ',', '.'));

                    param.ESTABELECIMENTO_ID = parseFloat(input_estab.val());
                    param.MES   = parseFloat(input_mes.val());
                    param.ANO   = parseFloat(input_ano.val());
                    param.VALOR = formataPadrao(input_valor.val());
                    
                    execAjax1('POST',pathname,param,function(data) {
                        execAjax1('GET',pathname+'/table',null,function(data) {
                            if(data) {
//                                table.dataTable().fnDestroy();
                                $('.faturamento-alterar').html(data);
                                table = $('.table-faturamento');       
                                dataTable();
                                listarEstab();
                                
                                showSuccess('Faturamento incluído com sucesso!');
                            }
                        });  
                    });
                                     
                })
            ;
        }
        
        $(function() {
            cadastrar();
            dataTable();
            
            $(".modal-dialog").on('hidden.bs.modal, shown.bs.modal', function () {
                $(window).trigger('resize');
            });
        });        
    }
    
    index();
    dre();
    faturamento();

})(jQuery);