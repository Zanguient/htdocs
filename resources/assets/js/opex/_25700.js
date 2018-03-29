	
(function ($) {
    
    var des_popup_old = '';
    var des_popup_nov = '';
    
    des_popup_nov = 'Cadastro de Plano de Ação';

    var modal_plan_acao = $('#planacao-modal');
    var class_corpo     = '.modal-body';
    var class_title     = '.modal-title';

    $(modal_plan_acao).find(class_corpo).css({
        'min-height' : ' 83vh'
    });

    des_popup_old = $(modal_plan_acao).find(class_title).html();
    
    function escondevoltar(){
        Modalescondevoltar('#planacao-modal');
    }

    function mostravoltar(){
        Modalmostravoltar('#planacao-modal');
    }

    /**
    * Carega uma tela no PopUp
    * @param {} param Array com Rota,Dados e Seletor
    */
    function getJanelasPopUp(param) {
        var param = param || {};
        
        $(class_title).html(des_popup_nov);

        /**
         * Realiza a chamada ajax
         */
        function tratarDados() {           
            
            function success(data) {						
                if(data) {
                    //$('div.popup-body').html(data);
                    $(modal_plan_acao).find(class_corpo).html(data);
                    //param.selector.popUp();
                    bootstrapInit();
                    escondevoltar();
                }
            }
            
            function erro(xhr){
                showErro(xhr);  
            }

            execAjax1('POST',param.url,param.dado,success,erro);
        } 
        
        tratarDados();
    }
    

    /**
    * Plano de Ação
    * @param {} param Parametros incluidos no array
    */
    function showPAcao(param) {
        var param = param || {};
        
        //$(class_title).html(des_popup_nov);
        $(modal_plan_acao).find(class_title).html(des_popup_nov);
        escondevoltar();

        /**
         * Realiza a chamada ajax
         */
        function tratarDados() {           

            function success(data) {						
                if(data) {

                    //$('div.popup-body').html(data);

                    $(modal_plan_acao).find(class_corpo).html(data);
                    //param.selector.popUp();
                    bootstrapInit();
                    escondevoltar();
                }
            }
            
            function erro(xhr){
                showErro(xhr);  
            }

            execAjax1('POST','/_25700/planoacao',param.dados,success,erro);
        } 
        
        tratarDados();
    }
    
    /**
    * Gravar Plano de Ação
    * @param {} param Parametros incluidos no array
    */
   
    
    function gravarPlaboAcao(param) {
        var param = param || {};
        
        function success(data) {
            if(data) {
                //$('div.popup-body').html(data);
                $(modal_plan_acao).find(class_corpo).html(data);
                    bootstrapInit();
                    escondevoltar();
                    
                showSuccess('Gravado com sucesso!');
            } 
        }

        function erro(xhr){
            //$(class_title).html(des_popup_old);
            $(modal_plan_acao).find(class_title).html(des_popup_old);
            mostravoltar();
            showErro(xhr);  
        }
        
            var class_p_a_ccusto    = $('.class-p-a-ccusto').val();  
            var class_p_a_vinculo   = 0; //vinculo antigo
            var class_p_a_turno     = $('.class-p-a-turno').val();
            var class_p_a_oque      = $('.class-p-a-oque').val();
            var class_p_a_quem      = $('.class-p-a-quem').val();
            var class_p_a_quandod   = $('.class-p-a-quandod').val();
            var class_p_a_quandot   = $('.class-p-a-quandot').val();
            var class_p_a_como      = $('.class-p-a-como').val();
            var class_p_a_descpa    = $('.plano-acao-descpa').val();
            var class_p_a_controlen = $('.plano-acao-controlen').val();
            var class_p_a_oqued     = $('.plano-acao-oque').val();
            var class_p_a_indicador = $('.class-p-a-indicador').val();
            var class_p_a_tela      = $('.plano-acao-tela').val();
            var class_p_a_vinculo2  = $('.class-p-a-vinculo').val();
            var class_p_a_subvinculo= $('.plano-acao-subvinculo').val();
            
        var exec = 0;
        
   /*validar*/if((class_p_a_ccusto.length    < 1 )  || (class_p_a_ccusto    === undefined)){showAlert('Centro de custo obrigatório');               exec = 1;   $('.class-c-a-ccusto').focus();     }
        else{ if((class_p_a_indicador.length < 1 )  || (class_p_a_indicador === undefined)){showAlert('Indicador obrigatório');                     exec = 1;   $('.class-c-a-indicador').focus();  }
        else{ if((class_p_a_vinculo2.length  < 1 )  || (class_p_a_vinculo2  === undefined)){showAlert('Vínculo obrigatório');                       exec = 1;   $('.class-p-a-vinculo').focus();    }
        else{ if((class_p_a_turno.length     < 1 )  || (class_p_a_turno     === undefined)){showAlert('Turno obrigatório');                         exec = 1;   $('.class-p-a-turno').focus();      }
        else{ if((class_p_a_oque.length      < 11)  || (class_p_a_oque      === undefined)){showAlert('"O que?" obrigatório, min:10 caracteres');   exec = 1;   $('.class-p-a-oque').focus();       }
        else{ if((class_p_a_quem.length      < 2 )  || (class_p_a_quem      === undefined)){showAlert('"Quem?" obrigatório');                       exec = 1;   $('.class-p-a-quem').focus();       }
        else{ if((class_p_a_quandod.length   < 1 )  || (class_p_a_quandod   === undefined)){showAlert('"Quando Inicio?" obrigatório');              exec = 1;   $('.class-p-a-quandod').focus();    }
        else{ if((class_p_a_quandot.length   < 1 )  || (class_p_a_quandot   === undefined)){showAlert('"Quando Fim?" obrigatório');                 exec = 1;   $('.class-p-a-quandot').focus();    }
        else{ if((class_p_a_como.length      < 11)  || (class_p_a_como      === undefined)){showAlert('"Como?" obrigatório, min:10 caracteres');    exec = 1;   $('.class-p-a-como').focus();       }
        }}}}}}}}
        
        var dados = {
            'class-p-a-ccusto'   : class_p_a_ccusto,
            'class-p-a-vinculo'  : class_p_a_vinculo,
            'class-p-a-indicador': class_p_a_indicador,
            'class-p-a-turno'    : class_p_a_turno,
            'class-p-a-oque'     : class_p_a_oque,
            'class-p-a-quem'     : class_p_a_quem,
            'class-p-a-quandod'  : class_p_a_quandod,
            'class-p-a-quandot'  : class_p_a_quandot,
            'class-p-a-como'     : class_p_a_como,
            'ccusto'             : class_p_a_ccusto,
            'vinculo'            : class_p_a_vinculo2,
            'descpa'             : class_p_a_descpa,
            'controlen'          : class_p_a_controlen,
            'oque'               : class_p_a_oqued,
            'tela'               : class_p_a_tela,
            'sub_vinc'           : class_p_a_subvinculo,
            'mes_i'              : $('.plano-mes-inicial').val(),
            'mes_f'              : $('.plano-mes-final').val(),
            'ano_i'              : $('.plano-ano-inicial').val(),
            'ano_f'              : $('.plano-ano-final').val()
        };
        
        if(exec == 0){
            execAjax1('POST','/_25700/store',dados,success,erro);
        }
        
    }
    
    function excluir(param){
        var param = param || {};
        
        function success(data) {						
            if(data) {
                //$('div.popup-body').html(data);
                $(modal_plan_acao).find(class_corpo).html(data);
                    bootstrapInit();
                    
                showSuccess('Item:'+param.dado.id+' excluido!');
            }
        }

        function erro(xhr){
            showErro(xhr);  
        }

        execAjax1('POST','/_25700/excluir',param.dado,success,erro);
    }
    
    function AlterarPlaboAcao(param) {
        var param = param || {};
        
        function success(data) {
            if(data) {
                //$('div.popup-body').html(data);
                $(modal_plan_acao).find(class_corpo).html(data);
                    bootstrapInit();
                    
                showSuccess('Alterado com sucesso!');
            } 
        }

        function erro(xhr){
            //$(class_title).html(des_popup_old);
            $(modal_plan_acao).find(class_title).html(des_popup_old);
            mostravoltar();
            showErro(xhr);  
        }
            var class_p_a_id        = $('.plano-acao-id').val(); 
            var class_p_a_ccusto    = $('.class-p-a-ccusto').val();
            var class_p_a_indicador = $('.class-p-a-indicador').val();
            var class_p_a_vinculo   = 0; //vunculo antigo, serve apanas para não deicar o camo nulo
            var class_p_a_turno     = $('.class-p-a-turno').val();
            var class_p_a_oque      = $('.class-p-a-oque').val();
            var class_p_a_quem      = $('.class-p-a-quem').val();
            var class_p_a_quandod   = $('.class-p-a-quandod').val();
            var class_p_a_quandot   = $('.class-p-a-quandot').val();
            var class_p_a_como      = $('.class-p-a-como').val();
            var class_p_a_descpa    = $('.plano-acao-descpa').val();
            var class_p_a_controlen = $('.plano-acao-controlen').val();
            var class_p_a_oqued     = $('.plano-acao-oque').val();
            var class_p_a_indicador = $('.class-p-a-indicador').val();
            var class_p_a_tela      = $('.plano-acao-tela').val();
            var class_p_a_vinculo2  = $('.plano-acao-vinculo').val();
            var class_p_a_subvinculo= $('.plano-acao-subvinculo').val();
            
        var exec = 0;
        
   /*validar*/if((class_p_a_ccusto.length    < 1 ) || (class_p_a_ccusto    === undefined)){showAlert('Centro de custo obrigatório');             exec = 1;   $('.class-c-a-ccusto').focus();    }
        else{ if((class_p_a_indicador.length < 1 ) || (class_p_a_indicador === undefined)){showAlert('Indicador obrigatório');                   exec = 1;   $('.class-c-a-indicador').focus(); }
        else{ if((class_p_a_vinculo2.length  < 1 ) || (class_p_a_vinculo2  === undefined)){showAlert('Vínculo obrigatório');                     exec = 1;   $('.class-p-a-vinculo').focus();   }
        else{ if((class_p_a_turno.length     < 1 ) || (class_p_a_turno     === undefined)){showAlert('Turno obrigatório');                       exec = 1;   $('.class-p-a-turno').focus();     }
        else{ if((class_p_a_oque.length      < 11) || (class_p_a_oque      === undefined)){showAlert('"O que?" obrigatório, min:10 caracteres'); exec = 1;  $('.class-p-a-oque').focus();       }
        else{ if((class_p_a_quem.length      < 2 ) || (class_p_a_quem      === undefined)){showAlert('"Quem?" obrigatório');                     exec = 1;   $('.class-p-a-quem').focus();      }
        else{ if((class_p_a_quandod.length   < 1 ) || (class_p_a_quandod   === undefined)){showAlert('"Quando?" obrigatório');                   exec = 1;   $('.class-p-a-quandod').focus();   }
        else{ if((class_p_a_quandot.length   < 1 ) || (class_p_a_quandot   === undefined)){showAlert('"Quando?" obrigatório');                   exec = 1;   $('.class-p-a-quandot').focus();   }
        else{ if((class_p_a_como.length      < 11) || (class_p_a_como      === undefined)){showAlert('"Como?" obrigatório, min:10 caracteres');  exec = 1;   $('.class-p-a-como').focus();      }
        }}}}}}}}
        
        var dados = {
            'class-p-a-ccusto'  : class_p_a_ccusto,
            'class-p-a-indicador'  : class_p_a_indicador,
            'class-p-a-vinculo' : class_p_a_vinculo,
            'class-p-a-turno'   : class_p_a_turno,
            'class-p-a-oque'    : class_p_a_oque,
            'class-p-a-quem'    : class_p_a_quem,
            'class-p-a-quandod' : class_p_a_quandod,
            'class-p-a-quandot' : class_p_a_quandot,
            'class-p-a-como'    : class_p_a_como,
            'indicador'         : class_p_a_indicador,
            'ccusto'            : class_p_a_ccusto,
            'vinculo'           : class_p_a_vinculo2,
            'id'                : class_p_a_id,
            'descpa'            : class_p_a_descpa,
            'controlen'         : class_p_a_controlen,
            'oque'              : class_p_a_oqued,
            'tela'              : class_p_a_tela,
            'sub_vinc'          : class_p_a_subvinculo,
            'mes_i'              : $('.plano-mes-inicial').val(),
            'mes_f'              : $('.plano-mes-final').val(),
            'ano_i'              : $('.plano-ano-inicial').val(),
            'ano_f'              : $('.plano-ano-final').val()
        };
        
        if(exec == 0){
            execAjax1('POST','/_25700/alterar',dados,success,erro);
        }
        
    }
    
    $(document).on('click','.popup-close', function(e) {

        tempo_espera = setTimeout(function() { 
            //$(class_title).html(des_popup_old);
            $(modal_plan_acao).find(class_title).html(des_popup_old);
            mostravoltar();
            clearTimeout(tempo_espera);
        }, 100);
         
    });
    
    $(document).on('click','.popup-show-plano-acao-gravar', function(e) {
        gravarPlaboAcao({
            selector : $(this),
            dados    : {}
        });    
    });

    $(document).on('click','.popup-show-plano-acao', function(e) {
        
        var mes_i = $('.filtro-mes-inicial').val();
        var mes_f = $('.filtro-mes-final').val();
        
        var ano_i = $('.filtro-ano-inicial').val();
        var ano_f = $('.filtro-ano-final').val();
        
        if(typeof  mes_i == 'undefined'){
            var mes_i = $('select[name=mes_1]').val();
            var mes_f = $('select[name=mes_2]').val();

            var ano_i = $('select[name=ano_1]').val();
            var ano_f = $('select[name=ano_1]').val();
            
            if(typeof  mes_i == 'undefined'){
                var mes_i = '1';
                var mes_f = '12';
                
                var data = new Date();
                var ano = data.getFullYear();  

                var ano_i = ano;
                var ano_f = ano;
                
                delete(data);
            }
        }
        
        showPAcao({
            selector : $(this),
            dados    : {
                'ccusto'    : $(this).attr('ccusto'),
                'vinculo'   : $(this).attr('vinculo'),
                'descpa'    : $(this).attr('descpa'),
                'controlen' : $(this).attr('controlen'),
                'oque'      : $(this).attr('oque'),
                'tela'      : $(this).attr('tela'),
                'sub_vinc'  : $(this).attr('subvinculo'),
                'mes_i'     : mes_i,
                'mes_f'     : mes_f,
                'ano_i'     : ano_i,
                'ano_f'     : ano_f
        }});
    
        console.log(mes_i+' - '+ ano_f);
    });

    $(document).on('click','.popup-show-plano-acao2', function(e) {
        
        var mes_i = $('select[name=mes_1]').val();
        var mes_f = $('select[name=mes_2]').val();

        var ano_i = $('select[name=ano_1]').val();
        var ano_f = $('select[name=ano_1]').val();
        
        if(typeof  mes_i == 'undefined'){
            var mes_i = '1';
            var mes_f = '12';
            
            var data = new Date();
            var ano = data.getFullYear();  

            var ano_i = ano;
            var ano_f = ano;
            
            delete(data);
        }

        
        showPAcao({
            selector : $(this),
            dados    : {
                'ccusto'    : $(this).attr('ccusto'),
                'vinculo'   : $(this).attr('vinculo'),
                'descpa'    : $(this).attr('descpa'),
                'controlen' : $(this).attr('controlen'),
                'oque'      : $(this).attr('oque'),
                'tela'      : $(this).attr('tela'),
                'sub_vinc'  : $(this).attr('subvinculo'),
                'mes_i'     : mes_i,
                'mes_f'     : mes_f,
                'ano_i'     : ano_i,
                'ano_f'     : ano_f
        }});
    
        console.log(mes_i+' - '+ ano_f);
    });
    
    $(document).on('click','.popup-show-plano-acao-cancelar', function(e) {
        getJanelasPopUp({
            selector : $(this),
            url      : '/_25700/planoacao',
            dado     : {
                'ccusto'    : $('.plano-acao-ccsuto').val(),
                'vinculo'   : $('.plano-acao-vinculo').val(),
                'descpa'    : $('.plano-acao-descpa').val(),
                'controlen' : $('.plano-acao-controlen').val(),
                'oque'      : $('.plano-acao-oque').val(),
                'tela'      : $('.plano-acao-tela').val(),
                'sub_vinc'  : $('.plano-acao-subvinculo').val(),
                'mes_i'     : $('.plano-mes-inicial').val(),
                'mes_f'     : $('.plano-mes-final').val(),
                'ano_i'     : $('.plano-ano-inicial').val(),
                'ano_f'     : $('.plano-ano-final').val()}
        });                            
    });
    
    $(document).on('click','.popup-show-plano-acao-incluir', function(e) {
        getJanelasPopUp({
            selector : $(this),
            url      : '/_25700/incluir',
            dado     : {
                'ccusto'    : $('.plano-acao-ccsuto').val(),
                'vinculo'   : $('.plano-acao-vinculo').val(),
                'descpa'    : $('.plano-acao-descpa').val(),
                'controlen' : $('.plano-acao-controlen').val(),
                'oque'      : $('.plano-acao-oque').val(),
                'tela'      : $('.plano-acao-tela').val(),
                'sub_vinc'  : $('.plano-acao-subvinculo').val(),
                'mes_i'     : $('.plano-mes-inicial').val(),
                'mes_f'     : $('.plano-mes-final').val(),
                'ano_i'     : $('.plano-ano-inicial').val(),
                'ano_f'     : $('.plano-ano-final').val()}
        });                            
    });
    
    $(document).on('click','.popup-show-plano-acao-item', function(e) {
        getJanelasPopUp({
            selector : $(this),
            url      : '/_25700/showitem',
            dado     : {
                 'id': $(this).attr('id'),
             'ccusto': $('.plano-acao-ccsuto').val(),
            'vinculo': $('.plano-acao-vinculo').val(),
             'descpa': $('.plano-acao-descpa').val(),
          'controlen': $('.plano-acao-controlen').val(),
               'oque': $('.plano-acao-oque').val(),
               'tela': $('.plano-acao-tela').val(),
           'sub_vinc': $('.plano-acao-subvinculo').val(),
              'mes_i': $('.plano-mes-inicial').val(),
              'mes_f': $('.plano-mes-final').val(),
              'ano_i': $('.plano-ano-inicial').val(),
              'ano_f': $('.plano-ano-final').val()}
        });                            
    });
    
    $(document).on('click','.popup-show-plano-acao-excluir', function(e) {

        addConfirme('Excluir registro','Deseja realmente excluir este item?',[obtn_sim,obtn_nao],
            function(){
                excluir({
                    selector: $(this),
                    dado:{
                        'id': $('.plano-acao-id').val(),
                    'ccusto': $('.plano-acao-ccsuto').val(),
                   'vinculo': $('.plano-acao-vinculo').val(),
                    'descpa': $('.plano-acao-descpa').val(),
                 'controlen': $('.plano-acao-controlen').val(),
                      'oque': $('.plano-acao-oque').val(),
                      'tela': $('.plano-acao-tela').val(),
                  'sub_vinc': $('.plano-acao-subvinculo').val(),
                     'mes_i': $('.plano-mes-inicial').val(),
                     'mes_f': $('.plano-mes-final').val(),
                     'ano_i': $('.plano-ano-inicial').val(),
                     'ano_f': $('.plano-ano-final').val()}
                });
            }
        ); 
        
    });
    
    $(document).on('click','.popup-show-plano-acao-aleraritem', function(e) {
        getJanelasPopUp({
            selector : $(this),
            url      : '/_25700/alteritem',
            dado     : {
                'id': $('.plano-acao-id').val(),
            'ccusto': $('.plano-acao-ccsuto').val(),
           'vinculo': $('.plano-acao-vinculo').val(),
            'descpa': $('.plano-acao-descpa').val(),
         'controlen': $('.plano-acao-controlen').val(),
              'oque': $('.plano-acao-oque').val(),
              'tela': $('.plano-acao-tela').val(),
          'sub_vinc': $('.plano-acao-subvinculo').val(),
             'mes_i': $('.plano-mes-inicial').val(),
             'mes_f': $('.plano-mes-final').val(),
             'ano_i': $('.plano-ano-inicial').val(),
             'ano_f': $('.plano-ano-final').val()}
        });                            
    });
    
    $(document).on('click','.popup-show-plano-acao-alterar', function(e) {
        AlterarPlaboAcao({
            selector : $(this),
            url      : '/_25700/alteritem',
            dado     : {
                'id': $('.plano-acao-id').val(),
            'ccusto': $('.plano-acao-ccsuto').val(),
           'vinculo': $('.plano-acao-vinculo').val(),
            'descpa': $('.plano-acao-descpa').val(),
         'controlen': $('.plano-acao-controlen').val(),
              'oque': $('.plano-acao-oque').val(),
              'tela': $('.plano-acao-tela').val(),
          'sub_vinc': $('.plano-acao-subvinculo').val(),
             'mes_i': $('.plano-mes-inicial').val(),
             'mes_f': $('.plano-mes-final').val(),
             'ano_i': $('.plano-ano-inicial').val(),
             'ano_f': $('.plano-ano-final').val()}
        });                            
    });
    
    $(document).on('click','.fim-plano-acao', function(e) {
       $('.popup-close').trigger( "click" );                          
    });
    
    function postprint(){
        console.log('Inicio');
        
        var codigo = $('.codigo').val();
        
        function success(data) {
            if(data) {
                $('.retcod').val(data);
                document.location.href = "clientprint:"+urlhost+"/assets/temp/print/"+data+".txt"; 
            } 
        }

        function erro(xhr){
            showErro(xhr);  
        }
        
        var dados = {
            'codigo'  : codigo
        };
        
        execAjax1('POST','/print/postprint',dados,success,erro);
        
    }
    
    function getprint(){
      
        var codigo = $('.retcod').val();
        
        function success(data) {
            if(data) {
                $('.codigo').val(data);
            } 
        }

        function erro(xhr){
            showErro(xhr);  
        }
        
        var dados = {
            'tag'  : codigo
        };
        
        execAjax1('POST','/print/get/'+codigo,dados,success,erro);
        
    }
    
    $(document).on('click','.postprint', function(e) {
       postprint();                          
    });
    
    $(document).on('click','.getprint', function(e) {
       getprint();                          
    });

    $(document).on('click','.fim-plano-acao', function(e) {
        $('.fechar-modal2').trigger('click');                    
    });

    
    
    
})(jQuery);