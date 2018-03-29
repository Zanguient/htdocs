	
(function ($) {
    
    var des_popup_old = '';
    var des_popup_nov = '';
    des_popup_old = $('.popup-title').html();
    des_popup_nov = 'Cadastro de Plano de Ação';
    
    /**
    * Carega uma tela no PopUp
    * @param {} param Array com Rota,Dados e Seletor
    */
    function getJanelasPopUp(param) {
        var param = param || {};
        
        $('.popup-title').html(des_popup_nov);

        /**
         * Realiza a chamada ajax
         */
        function tratarDados() {           
            
            function success(data) {						
                if(data) {
                    $('div.modal-body').html(data);
                    param.selector.popUp();
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
        
        $('.popup-title').html(des_popup_nov);
        escondevoltar();

        /**
         * Realiza a chamada ajax
         */
        function tratarDados() {           

            function success(data) {						
                if(data) {
                    $('div.modal-body').html(data);
                    param.selector.popUp();
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
                $('div.modal-body').html(data);
                    bootstrapInit();
                    escondevoltar();
                    
                showSuccess('Gravado com sucesso!');
            } 
        }

        function erro(xhr){
            $('.popup-title').html(des_popup_old);
            mostravoltar();
            showErro(xhr);  
        }
        
            var class_p_a_ccusto    = $('.class-p-a-ccusto').val();  
            var class_p_a_vinculo   = $('.class-p-a-vinculo').val();
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
            
        var exec = 0;
        
        console.log(class_p_a_ccusto);
        
        
   /*validar*/if((class_p_a_ccusto.length   < 1 ) || (class_p_a_ccusto    === undefined)){showAlert('Centro de custo obrigatório'); exec = 1;   $('.class-c-a-ccusto').focus(); }
        else{ if((class_p_a_indicador.length< 1 ) || (class_p_a_indicador === undefined)){showAlert('Indicador obrigatório');       exec = 1;   $('.class-c-a-indicador').focus(); }
        else{ if((class_p_a_vinculo.length  < 1 ) || (class_p_a_vinculo   === undefined)){showAlert('Vínculo obrigatório');         exec = 1;   $('.class-p-a-vinculo').focus();}
        else{ if((class_p_a_turno.length    < 1 ) || (class_p_a_turno     === undefined)){showAlert('Turno obrigatório');           exec = 1;   $('.class-p-a-turno').focus();  }
        else{ if((class_p_a_oque.length     < 11) || (class_p_a_oque      === undefined)){showAlert('"O que?" obrigatório, min:10 caracteres'); exec = 1;  $('.class-p-a-oque').focus();   }
        else{ if((class_p_a_quem.length     < 2 ) || (class_p_a_quem      === undefined)){showAlert('"Quem?" obrigatório');         exec = 1;   $('.class-p-a-quem').focus();   }
        else{ if((class_p_a_quandod.length  < 1 ) || (class_p_a_quandod   === undefined)){showAlert('"Quando?" obrigatório');       exec = 1;   $('.class-p-a-quandod').focus();}
        else{ if((class_p_a_quandot.length  < 1 ) || (class_p_a_quandot   === undefined)){showAlert('"Quando?" obrigatório');       exec = 1;   $('.class-p-a-quandot').focus();}
        else{ if((class_p_a_como.length     < 11) || (class_p_a_como      === undefined)){showAlert('"Como?" obrigatório, min:10 caracteres');  exec = 1;   $('.class-p-a-como').focus();   }
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
            'vinculo'            : class_p_a_vinculo,
            'descpa'             : class_p_a_descpa,
            'controlen'          : class_p_a_controlen,
            'oque'               : class_p_a_oqued
        };
        
        if(exec == 0){
            execAjax1('POST','/_25700/store',dados,success,erro);
        }
        
    }
    
    function excluir(param){
        var param = param || {};
        
        function success(data) {						
            if(data) {
                $('div.modal-body').html(data);
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
                $('div.modal-body').html(data);
                    bootstrapInit();
                    
                showSuccess('Alterado com sucesso!');
            } 
        }

        function erro(xhr){
            $('.popup-title').html(des_popup_old);
            mostravoltar();
            showErro(xhr);  
        }
            var class_p_a_id        = $('.plano-acao-id').val(); 
            var class_p_a_ccusto    = $('.class-p-a-ccusto').val();
            var class_p_a_indicador = $('.class-p-a-indicador').val();
            var class_p_a_vinculo   = $('.class-p-a-vinculo').val();
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
            
        var exec = 0;
        
   /*validar*/if((class_p_a_ccusto.length   < 1 ) || (class_p_a_ccusto  === undefined)){showAlert('Centro de custo obrigatório'); exec = 1;   $('.class-c-a-ccusto').focus(); }
        else{ if((class_p_a_indicador.length   < 1 ) || (class_p_a_indicador  === undefined)){showAlert('Indicador obrigatório');          exec = 1;   $('.class-c-a-indicador').focus(); }
        else{ if((class_p_a_vinculo.length  < 1 ) || (class_p_a_vinculo === undefined)){showAlert('Vínculo obrigatório');         exec = 1;   $('.class-p-a-vinculo').focus();}
        else{ if((class_p_a_turno.length    < 1 ) || (class_p_a_turno   === undefined)){showAlert('Turno obrigatório');           exec = 1;   $('.class-p-a-turno').focus();  }
        else{ if((class_p_a_oque.length     < 11) || (class_p_a_oque    === undefined)){showAlert('"O que?" obrigatório, min:10 caracteres'); exec = 1;  $('.class-p-a-oque').focus();   }
        else{ if((class_p_a_quem.length     < 2 ) || (class_p_a_quem    === undefined)){showAlert('"Quem?" obrigatório');         exec = 1;   $('.class-p-a-quem').focus();   }
        else{ if((class_p_a_quandod.length  < 1 ) || (class_p_a_quandod === undefined)){showAlert('"Quando?" obrigatório');       exec = 1;   $('.class-p-a-quandod').focus();}
        else{ if((class_p_a_quandot.length  < 1 ) || (class_p_a_quandot === undefined)){showAlert('"Quando?" obrigatório');       exec = 1;   $('.class-p-a-quandot').focus();}
        else{ if((class_p_a_como.length     < 11) || (class_p_a_como    === undefined)){showAlert('"Como?" obrigatório, min:10 caracteres');  exec = 1;   $('.class-p-a-como').focus();   }
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
            'vinculo'           : class_p_a_vinculo,
            'id'                : class_p_a_id,
            'descpa'            : class_p_a_descpa,
            'controlen'         : class_p_a_controlen,
            'oque'              : class_p_a_oqued
        };
        
        if(exec == 0){
            execAjax1('POST','/_25700/alterar',dados,success,erro);
        }
        
    }
    
    $(document).on('click','.popup-close', function(e) {

        tempo_espera = setTimeout(function() { 
            $('.popup-title').html(des_popup_old);
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
        showPAcao({
            selector : $(this),
            dados    : {
                'ccusto'    : $(this).attr('ccusto'),
                'vinculo'   : $(this).attr('vinculo'),
                'descpa'    : $(this).attr('descpa'),
                'controlen' : $(this).attr('controlen'),
                'oque'      : $(this).attr('oque')
        }});                            
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
                'oque'      : $('.plano-acao-oque').val()}
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
                'oque'      : $('.plano-acao-oque').val()}
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
               'oque': $('.plano-acao-oque').val()}
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
                'descpa'    : $('.plano-acao-descpa').val(),
                'controlen' : $('.plano-acao-controlen').val(),
                'oque'      : $('.plano-acao-oque').val()}
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
              'oque': $('.plano-acao-oque').val()}
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
              'oque': $('.plano-acao-oque').val()}
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
    
    

})(jQuery);