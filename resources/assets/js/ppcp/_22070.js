
(function($) {
    
    /**
     * Xml Htpp Request Scope
     * @param {type} param
     * @returns {Promise}
     */
    var xhr = function(param)
    {
        param        = param || {};
        param.method = param.method || 'POST';
        
        return new Promise(function(resolve, reject) {
            execAjax1(param.method,param.url,param.dados,
            function(data) {
                resolve(data);
            },
            function() {
                reject(false);
               
            });
        });  
    };
    
    
    function objIndex()
    {
        this.paramByInput           = paramByInput;       
        this.filtrar                = filtrar;       
        this.inicializarDataTable   = inicializarDataTable;  
        this.dragDrop               = dragDrop;  
        this.menuLateral            = menuLateral;
        this.menuLateralContainer   = menuLateralContainer;
        this.reprogramarPara        = reprogramarPara;
        
        /**
         * Definir os parâmetros de um campo a partir de outro.
         */
        function paramByInput()
        {

            /**
             * Define o GP selecionado como parâmetro para a UP.
             */
            function gpParamUp() {

                $('._gp_id')
                    .change(function() {
                        var _this = $(this);

                        //Seta GP na UP
                        $('.consulta_up_group')
                            .siblings('._consulta_parametros')
                            .children('._consulta_filtro[objcampo="GP"]')
                            .val( $(this).val() )
                        ;
                        
                    })
                ;
            }

            /**
             * Define o UP selecionado como parâmetro para a Estação.
             */
            function upParamEstacao() {

                $('._up_id')
                    .change(function() {

                        //Seta UP na Estacao
                        $('.consulta_estacao_group')
                            .siblings('._consulta_parametros')
                            .children('._consulta_filtro[objcampo="UP"]')
                            .val( $(this).val() )
                        ;

                    })
                ;
            }

            gpParamUp();
            upParamEstacao();
        }
        
        function filtrar()
        {            
            $('.form-filtrar').submit(function(e)
            {
                return new Promise(function(resolve, reject) {
                    e.preventDefault();
                    
                    var scroll_left_before_filter = $('.up-container').scrollLeft();
                    var scroll_top_before_filter  = $('.up-container').scrollTop();

                    xhr({
                        url   : '/_22070/filtrar',
                        dados : {
                            estabelecimento_id : $('.estab'      ).val(),
                            gp_id              : $('._gp_id'     ).val(),
                            up_id              : $('._up_id'     ).val(),
                            estacao            : $('._estacao_id').val(),
                            datahora_inicio    : $('#data-ini'   ).val(),
                            datahora_fim       : $('#data-fim'   ).val()
                        }
                    })
                    .then(function(data){
                        $('.taloes-container').html(data);

                        var index = new objIndex();
                            index.inicializarDataTable();
                            index.dragDrop();
                            index.reprogramarPara();
                            index.menuLateralContainer();
                            
                        $('.up-container').scrollLeft(scroll_left_before_filter);
                        $('.up-container').scrollTop(scroll_top_before_filter);
                        
                        //$('.menu-lateral').trigger('hide').fadeIn();
                        resolve(true);
                    })
                    .catch(function(){
                        reject(false);
                    });
                });
            });
        }
        
        function inicializarDataTable(param)
        {
            var param                   = param || {};
			var data_table_def			= $.extend({}, table_default);
                data_table_def.sScrollY = '100%'; 
				
            $('table.estacao').DataTable(data_table_def);
            
            ativarSelecLinhaCheckbox();     

            //Só permite que uma tabela tenha itens selecionados por vez
            $('tr')
                .click(function(){
                    $(this)
                        .closest('.up-bloco')
                        .siblings()
                        .find('.selected')
                        .removeClass('selected')
                    ;
                })
            ;            
        }
        
        function dragDrop()
        {
            var dados = {};
            
            function TaloesOrigem(itens_selecionado_origem) {
                
                var item_selecionado_first = $(itens_selecionado_origem).first();
                
                dados.origem_datahora_inicio = item_selecionado_first.find('._data-inicio'     ).val();
                dados.origem_up_id           = item_selecionado_first.find('._up-id'           ).val();
                dados.origem_estacao         = item_selecionado_first.find('._estacao'         ).val();
                dados.origem_data            = item_selecionado_first.find('._data-programacao').val();
            }
            
            /**
             * Realiza todos os tratamentos do servidor<br/>
             * Atualiza as informações dos itens
             */
            function TaloesDestino() {
                
                var itens_selecionados = $('.selected');
                
                var capturaTaloes = function() {
                    dados.estabelecimento_id = $('.estab'        ).val();
                    dados.gp_descricao       = $('._gp_descricao').val();
                    dados.gp_id              = $('._gp_id'       ).val();
                    dados.up_id              = $('._up_id'       ).val();
                    dados.estacao            = $('._estacao_id'  ).val();
                    dados.datahora_inicio    = $('#data-ini'     ).val();
                    dados.datahora_fim       = $('#data-fim'     ).val();
                    dados.destino_up_id      = itens_selecionados.first().closest('.up-bloco'     ).data('up'     );
                    dados.destino_estacao    = itens_selecionados.first().closest('.estacao-bloco').data('estacao');
                    dados.destino_taloes     = [];
                    
                    itens_selecionados
                        .each(function(){
                            dados.destino_taloes.push({
                                talao_id       : $(this).find('._talao-id'      ).val(),
                                programacao_id : $(this).find('._programacao-id').val()
                            });
                        })
                    ;
                    
                    var scroll_left_before_filter = $('.up-container').scrollLeft();
                    var scroll_top_before_filter  = $('.up-container').scrollTop();
                    
                    xhr({
                        url   : '/_22070/reprogramar',
                        dados : dados
                    })
                    .then(function(data){
                        $('.taloes-container').html(data);

                        var index = new objIndex();
                            index.inicializarDataTable();
                            index.dragDrop();
                            index.reprogramarPara();
                            
                        $('.up-container').scrollLeft(scroll_left_before_filter);
                        $('.up-container').scrollTop(scroll_top_before_filter);
                        
                    })
                    .catch(function(){
                        $('.form-filtrar').submit();
                    });
                };
                
                var capturaDataInicio = function() {
                    
                    var data_inicio_anterior       = itens_selecionados.first().prev().find('._data-inicio').data('data-inicial');
                    var data_programacao_anterior  = itens_selecionados.first().prev().find('._data-programacao').val();
                    var data_inicio_posterior      = itens_selecionados.last().next().find('._data-inicio').val();
                    var data_programacao_posterior = itens_selecionados.last().next().find('._data-programacao').val();
                    
                    dados.destino_datahora_inicio = ( data_inicio_anterior      === undefined ) ? data_inicio_posterior      : data_inicio_anterior; 
                    dados.destino_data            = ( data_programacao_anterior === undefined ) ? data_programacao_posterior : data_programacao_anterior; 

                    if ( dados.destino_datahora_inicio === undefined ) {

                        var now          = new Date();
                        var now1         = new Date(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(),  now.getUTCHours(), now.getUTCMinutes(), 0);
                        var datetime_now = new Date(now1.getTime()-now1.getTimezoneOffset()*60000).toISOString().substring(0,19);

                        addConfirme(
                            '<h4>Reprogramar Talões</h4>',
                            ' A Unidade Produtiva/Estação selecionada ainda não possui talões programados.<br />'+
                            ' Informe uma data para início do primeiro talão:<br/>'+
                            '<input type="datetime-local" class="form-control input-datetime" value="' + datetime_now + '">',
                            [obtn_ok,obtn_cancelar],
                            [
                                {ret:1,func:function(){
                                    
                                    if ( $('.input-datetime').val() == '' ) {
                                        throw new Error('Oooops! ocorreu um falha');
                                    }

                                    dados.destino_datahora_inicio = $('.input-datetime').val();
                                    dados.destino_data            = $('.input-datetime').val();
                                    capturaTaloes();
                                }},
                                {ret:2,func:function(){
                                    $('.form-filtrar').submit();     
                                }}
                            ] 
                        );
                    } else {
                        capturaTaloes();
                    }
                };
                    
                capturaDataInicio();
            }
            
            /**
             * Realiza todos os tratamentos visuais<br/>
             * Realiza a chamada dos eventos de backend
             */
            function frontEnd() {
                $('tbody')
                    .sortable({
//                        cancel      : 'tr:not(.selected)',  //Quais itens não podem ser selecionados
                        items       : 'tr',                 //Quais os itens que podem ser selecionados
                        appendTo    : ".up-container",      //Por onde os itens selecionados podem percorrer
                        connectWith : 'tbody',              //Onde poderá ser soltado os itens selecionados
                        cursor      : 'move',               //Cursor enquanto arrasta itens
                        tolerance   : 'pointer',            //Em que ponto poderá ser soltados os itens
                        delay       : 150,                  //Tempo em milisegundos que demora para começar a arrastar os itens
                        distance    : 5,    
                        revert      : true,                 //Ativa o efeito ao soltar suavisado
                        cursorAt    : { left: 5 },          //Posição do cursor em relação ao item que está sendo arrastado
                        //Item flutuante
                        helper: function(e, item){
                            if(!item.hasClass('selected')){
                               item.addClass('selected').siblings().removeClass('selected');
                            }

                            var elements = item.parent().children('.selected').clone();
                            item.data('multidrag', elements).siblings('.selected').remove();
                            
                            TaloesOrigem(elements);

                            var helper = $('<tr/>');
                            return helper.append(elements);
                        },
                        beforeStop: function(e, ui){
                            $('tbody')
                                .each(function(){
                                    var tr    = $(this).find('tr.ui-sortable-handle');
                                    var empty = $(this).find('.dataTables_empty');
                                    
                                    if( tr.length >= 1 && empty.length == 1 ) {
                                        empty.remove();
                                    }
                                    
                                    if ( tr.length < 1 && empty.length == 0 ) {
                                        $(this).append('<tr><td colspan="10" class="dataTables_empty">Não há registros para listar</td></tr>');
                                    }
                                })
                            ;  
                        },
                        //Ação ao soltar itens
                        stop: function(e, ui){

                            var elements = ui.item.data('multidrag');
                            ui.item.after(elements).remove();
                                                        
                            addConfirme(
                                '<h4>Reprogramar Talões</h4>',
                                ' Confirma a reprogramação dos itens selecionados?<br />',
                                [obtn_sim,obtn_nao],
                                [
                                    {ret:1,func:function(){
                                        TaloesDestino();
                                    }},
                                    {ret:2,func:function(){
                                        $('.form-filtrar').submit();     
                                    }}
                                ]     
                            );
                        },
                        //Abertura onde será soltado os itens
                        placeholder: {
                            element: function(currentItem) {
                                return $('<tr><td colspan="10" class="reprogramar-aqui">Reprogramar aqui</td></tr>')[0];
                            },
                            update: function(container, p) {
                                return;
                            }
                        }
                    })
                ;
            }
                        
            frontEnd();
        }
        
        function menuLateral()
        {

            // Redimensiona o menu lateral
            var menu = $('.menu-lateral');
            var button = menu.find('.btn-toggle');
            
            menu
                .on('hide',function(){
                    $(this).css({left : -($(this).width()+4)});
                    $(this).addClass('ocultar');
                    button.addClass('glyphicon-option-vertical');
                    button.removeClass('glyphicon-remove');
                })
                .on('show',function(){
                    $(this).css({left : 0});
                    $(this).removeClass('ocultar');
                    button.removeClass('glyphicon-option-vertical');
                    button.addClass('glyphicon-remove');
                })
                .on('toggle',function(){
                    
                    if ( $(this).hasClass('ocultar') ) {
                        $(this).css({left : 0});
                    } else {
                        $(this).css({left : -($(this).width()+4)});
                    }
            
                    $(this).toggleClass('ocultar');
                    button.toggleClass('glyphicon-option-vertical');
                    button.toggleClass('glyphicon-remove');
                })
            ;
            
            button
                .click(function(){
                    menu.trigger('toggle');
                })
            ;
            button.click();
        }
        
        function menuLateralContainer()
        {
            
            function carregaItens()
            {
                var menu_center = $('.menu-lateral .treeview-container');

                menu_center.empty();

                var insertElement = function(param) {
                        var param = param || {};
                        var id = param.class_element +'-dinamic-'+parseInt(Math.random() * 10000);

                    return $(
                        '<div class="treeview-element ' + param.class_element + '" data-' + param.class_element + '="' + param.data_id +'">\n\
                            <div class="form-group">\n\
                                <input type="checkbox" id="' + id + '" class="form-control treeview-checkbox ' + param.class_element + '-checkbox" checked />\n\
                                <label class="treeview-label" for="' + id + '">\n\
                                    ' + param.label_text + '\n\
                                </label>\n\
                            </div>\n\
                        </div>'
                    ).appendTo(param.append_to);
                };

                $('.up-bloco')
                    .each(function(){
                        var div_up = insertElement({
                            data_id       : $(this).data('up'),
                            class_element : 'up',
                            append_to     : menu_center,
                            label_text    : $(this).data('up') + ' - ' + $(this).data('up-descricao')
                        });

                        $(this)
                            .find('.estacao-bloco')
                            .each(function(){
                                insertElement({
                                    data_id       : $(this).data('estacao'),
                                    class_element : 'estacao',
                                    append_to     : div_up,
                                    label_text    : $(this).data('estacao') + ' - ' + $(this).data('estacao-descricao')
                                });
                            })
                        ;

                    })
                ;
            }
            
            function bloqueios()
            {     
                $('.treeview-element')
                    .each(function(){
                        var _this_checked = $(this).find('.form-group .treeview-checkbox').prop('checked');
                        var count_children = $(this).children('.treeview-element').length;
                        if ( _this_checked && count_children < 2 ) {
                            $(this)
                                .children('.treeview-element').find('.treeview-checkbox, .treeview-label')
                                .prop('disabled', true )
                            ;
                        }
                    })
                ;
            }

            function eventos()
            {
                $('.up-marcar-todos')
                    .change(function(){
                        var all_container = $(this).closest('.center');
                        if($(this).prop( 'checked' )) {
                            all_container.find('.up-checkbox').prop('checked',true).trigger('change');
                        } else {
                            all_container.find('.up-checkbox').prop('checked',false).trigger('change');
                        }
                    })
                ;
                
                $('.up-checkbox')
                    .change(function() {
                        
                        var up = $(this).closest('.up');
                        var estacoes = up.find('.estacao input');
                        
                        if($(this).prop( 'checked' )) {
                            estacoes
                                .prop('checked', true )
                                .trigger('change')
                            ;
                            
                            bloqueios();
                            
                            $('.taloes-container [data-up="' + up.data('up') + '"]').show();
                        } else {
                            estacoes
                                .prop('checked', false )
                                .prop('disabled', false )
                                .trigger('change')
                            ;
                            
                            $('.taloes-container [data-up="' + up.data('up') + '"]').hide();
                        }
                    }
                );
        
                $('.estacao-checkbox')
                    .change(function() {
                        
                        var up = $(this)
                            .closest('.up')
                        ;
                        var estacao = $(this)
                            .closest('.estacao')
                        ;
                        
                        if($(this).prop( 'checked' )) {
                            
                            var up_checkbox = up.find('.up-checkbox');
                            if ( !up_checkbox.prop('checked') ) {
                                up_checkbox
                                    .prop('checked', true )
                                    .trigger('change')
                                ;
                            }
                    
                            $('.taloes-container [data-up="' + up.data('up') + '"] [data-estacao="' + estacao.data('estacao') + '"]').show();
                        } else {
                            
                            $('.taloes-container [data-up="' + up.data('up') + '"] [data-estacao="' + estacao.data('estacao') + '"]').hide();
                        }
                    }
                );
            }
            
            carregaItens();
            bloqueios();
            eventos();
        }
    
        function reprogramarPara()
        {
            $('tr').on('trSelectedChanged',function(){
                
                $('.btn-reprogramar-para').prop('disabled', true );
                
                var btn_reprogramar = $(this).closest('.estacao-bloco').find('.btn-reprogramar-para');

                if ( $(this).closest('tbody').find('.selected').length > 0 ) {
                    btn_reprogramar.prop('disabled', false );
                } else {
                    btn_reprogramar.prop('disabled', true );
                }
            });
            
            $('.btn-reprogramar-para').click(function(){
                
                var dados = {};

                function TaloesOrigem(itens_selecionado_origem) {

                    var item_selecionado_first = $(itens_selecionado_origem).first();

                    dados.origem_datahora_inicio = item_selecionado_first.find('._data-inicio'     ).val();
                    dados.origem_up_id           = item_selecionado_first.find('._up-id'           ).val();
                    dados.origem_estacao         = item_selecionado_first.find('._estacao'         ).val();
                    dados.origem_data            = item_selecionado_first.find('._data-programacao').val();
                }

                /**
                 * Realiza todos os tratamentos do servidor<br/>
                 * Atualiza as informações dos itens
                 */
                function TaloesDestino() {

                    var itens_selecionados = $('.selected');

                    var capturaTaloes = function() {
                        dados.estabelecimento_id = $('.estab'        ).val();
                        dados.gp_descricao       = $('._gp_descricao').val();
                        dados.gp_id              = $('._gp_id'       ).val();
                        dados.up_id              = $('._up_id'       ).val();
                        dados.estacao            = $('._estacao_id'  ).val();
                        dados.datahora_inicio    = $('#data-ini'     ).val();
                        dados.datahora_fim       = $('#data-fim'     ).val();
                        dados.destino_up_id      = itens_selecionados.first().closest('.up-bloco'     ).data('up'     );
                        dados.destino_estacao    = itens_selecionados.first().closest('.estacao-bloco').data('estacao');
                        dados.destino_taloes     = [];

                        itens_selecionados
                            .each(function(){
                                dados.destino_taloes.push({
                                    talao_id       : $(this).find('._talao-id'      ).val(),
                                    programacao_id : $(this).find('._programacao-id').val()
                                });
                            })
                        ;

                        var scroll_left_before_filter = $('.up-container').scrollLeft();
                        var scroll_top_before_filter  = $('.up-container').scrollTop();

                        xhr({
                            url   : '/_22070/reprogramar',
                            dados : dados
                        })
                        .then(function(data){
                            $('.taloes-container').html(data);

                            var index = new objIndex();
                                index.inicializarDataTable();
                                index.dragDrop();
                                index.reprogramarPara();

                            $('.up-container').scrollLeft(scroll_left_before_filter);
                            $('.up-container').scrollTop(scroll_top_before_filter);

                        })
                        .catch(function(){
                            $('.form-filtrar').submit();
                        });
                    };

                    var capturaDataInicio = function() {

                        var data_inicio_anterior       = itens_selecionados.first().prev().find('._data-inicio').data('data-inicial');
                        var data_programacao_anterior  = itens_selecionados.first().prev().find('._data-programacao').val();
                        var data_inicio_posterior      = itens_selecionados.last().next().find('._data-inicio').val();
                        var data_programacao_posterior = itens_selecionados.last().next().find('._data-programacao').val();



                        var now          = new Date();
                        var now1         = new Date(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(),  now.getUTCHours(), now.getUTCMinutes(), 0);
                        var datetime_now = new Date(now1.getTime()-now1.getTimezoneOffset()*60000).toISOString().substring(0,19);

                        addConfirme(
                            '<h4>Reprogramar Talões</h4>',
                            ' Informe a data/hora para o primeiro talão selecionado:<br/>'+
                            '<input type="datetime-local" class="form-control input-datetime" value="' + datetime_now + '">',
                            [obtn_ok,obtn_cancelar],
                            [
                                {ret:1,func:function(){
                                    
                                    if ( $('.input-datetime').val() == '' ) {
                                        throw new Error('Oooops! ocorreu um falha');
                                    }

                                    dados.destino_datahora_inicio = $('.input-datetime').val();
                                    dados.destino_data            = $('.input-datetime').val();
                                    capturaTaloes();
                                }}
                            ] 
                        );
                    };

                    capturaDataInicio();
                }
                
                TaloesOrigem($('.selected'));
                TaloesDestino();
            });
        }
    }
    
    function loadIndex()
    {
        function clearData()
        {
            $('.estab, ._gp_id, ._up_id, ._estacao_id, #data-ini, #data-fim')
                .change(function(){
                    $('.taloes-container').empty();
                    $('.menu-lateral .treeview-container').empty();
                    //$('.menu-lateral').trigger('hide');
                })
            ;
        }
        
        clearData();
        var index = new objIndex();
            index.paramByInput();
            index.filtrar();
            //index.menuLateral();
    }

    /**
     * Document Ready
     */
	$(function() {
        loadIndex();
	});
    
})(jQuery);

