
(function ($) {
    
    function getModelosValor(modelos,valorres){
            
            var vetor = new Array ();
            var cont = $(modelos).length;
            
            for (i = 0; i < cont; i++) {
               var obj1 = $(modelos)[i];
               var obj2 = $(valorres)[i];
               var campo1 = $(obj1).val();
               var campo2 = $(obj2).val();
               vetor[i] = [campo1,campo2];           
            }
         
            return vetor;
    }
    
    function excluir(e){
        var ID = $('.itemexcluir').attr('id');
        
        var dadosEnvio = {
            ID : ID
        };
        
        var url_action = "delete";
        var dados = dadosEnvio;
        var type = "POST";
                    
        function success(data){
            showSuccess('Excluido com sucesso');
            window.location.href = urlhost+'/_12030';
        }

        function erro(xhr){
            showErro(xhr);
        }
        console.log(dadosEnvio);
        execAjax1(type,url_action,dados,success,erro,false);
    }
    
    function gravar(e){
        var modelos = $('.vmodelo');
        var valores = $('.vvalor');
        var cliente = $('.icliente').val();
        var observa = $('.iobservacao').val();
        var email   = $('.email');
        var chec = 0;

        if(cliente.length > 0){
            if(modelos.length > 0){
                
             var ModelosValor = getModelosValor(modelos,valores);
             var obj1 = $(email)[0];
             var obj2 = $(email)[1];
             var obj3 = $(email)[2];
             
             if($(obj1).is(':checked')){chec = $(obj1).val();}
             if($(obj2).is(':checked')){chec = $(obj2).val();}
             if($(obj3).is(':checked')){chec = $(obj3).val();}
             
                var dadosEnvio = {
                    modelos : ModelosValor, 
                    cliente : cliente, 
                    observa : observa,
                    email   : chec
                };
                
                if(chec > 0){
                    var url_action = "store";
                    var dados = dadosEnvio;
                    var type = "POST";
                    
                    function success(data){
                        showSuccess('Gravado com sucesso');
                        window.location.href = urlhost+'/_12030';
                    }
                    
                    function erro(xhr){
                        showErro(xhr);
                    }
                    
                    execAjax1(type,url_action,dados,success,erro,false);

                }else{
                   showAlert('Selecione um destinatário de email!');
                }
            
            }else{
                showAlert('Não foram definidos Modelos!');
                $('.imodelo').focus();
            }
        }else{
            showAlert('Cliente obrigatório!');
            $('.icliente').focus();
        }
        
    }
    
    
    function additem(){
        var modelo = $('.imodelo').val();
        var valor  = $('.ivalor').val();
        
        if(modelo.length > 0){
            if(valor.length > 0){
                var btn = '<button type="button" class="btn btn-danger item-excluir" title="Excluir" style="display: block;"><i class="glyphicon glyphicon-trash"></i></button>';
                var imput1 = '<input type="hidden" id="ivalor" class="vvalor" value="'+valor+'">';
                var imput2 = '<input type="hidden" id="imodelo" class="vmodelo" value="'+modelo+'">';
                var item = '<div class="item"><div class="valor">'+valor+'</div>|<div class="modelo">'+modelo+'</div>'+btn+imput1+imput2+'</div>';
                
                $('.area-item').append(item);

                $('.imodelo').val('');
                $('.ivalor').val('');
            }else{
                showAlert('Valor obrigatório!');
                $('.ivalor').focus();
            }
            
        }else{
            showAlert('Modelo obrigatório!');
            $('.imodelo').focus();
        }
    }
    
    function removeitem(e){
        $(e).parent().remove();
    }
    
    $(document).on('click','.add-item-dinamico', function(e) {
        additem();
    });
    
    $(document).on('click','.item-excluir', function(e) {
        removeitem(this);
    });
    
    $(document).on('click','.gravar', function(e) {
        gravar(this);
    });
    
    $(document).on('click','.itemexcluir', function(e) {

        addConfirme('Excluir registro','Deseja realmente excluir este item?',[obtn_sim,obtn_nao],
            function(){excluir(this);}
        ); 
    });
    
    $(document).on('blur','.mask-numero', function(e) {
        var valor1 = parseFloat($(this).val());
        var valor2 = $(this).val();
        var n = valor2.indexOf(",");
        
        if(n < 0){
            
            var v = String(valor1.toFixed(2)).replace(".", ",");


            $(this).val(v);
        }else{
            var virgula = valor2.indexOf(",");
            var tamanho = valor2.length;
            
            if((tamanho - virgula) === 2){
                var v = valor2+'0';
                $(this).val(v);
            }else{
                if((tamanho - virgula) === 1){
                    var v = valor2+'00';
                    $(this).val(v);
                }else{
                    if((tamanho - virgula) === 0){
                        var v = valor2+'00';
                        $(this).val(v);
                    }else{
                        
                    }
                }
            }
        }
    });
    
    
    
    

})(jQuery);
//# sourceMappingURL=_12030.js.map
