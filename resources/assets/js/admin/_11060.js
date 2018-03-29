
function listarImpressoras(){
        
        var estabelecimento = $('.estab').val();
        
        if((typeof estabelecimento == "undefined") || (estabelecimento == "") || (estabelecimento == null)){
           showErro('Selecione um estabelecimento!'); 
        }else{

            var dados = {
                estab:estabelecimento  
            };    

            function success(data) {						
                if(data) {

                    //$('.lista-obj-11060').html('');
                    $('.lista-obj-11060').html(data);

                }
            }

            function erro(xhr){
                showErro(xhr);  
            }

            execAjax1('POST','/_11060/listar',dados,success,erro);
        }
}

function excluir(e){
    addConfirme('Impressão','Deseja realmente excluir este item?',
            [
                obtn_sim,
                obtn_cancelar
            ],
            [
                {ret:1,func:function(){
                    excluirImpressoras(e);
                }},
                {ret:2,func:function(){
                        
                }}
            ]   
        );
}

function excluirImpressoras(e){
        
        var id = $(e).attr('impressora-id');
        
        if((typeof id == "undefined") || (id == "") || (id == null)){
           showErro('Impressora não definida, volte e selecione uma impressora!'); 
        }else{

            var dados = {
                id:id  
            };    

            function success(data) {						
                showSuccess('Item excluido!');
                
                setTimeout(function(){
                    window.location= urlhost + '/_11060';
                },1500);
            }

            function erro(xhr){
                showErro(xhr);  
            }

            execAjax1('POST','/_11060/excluir',dados,success,erro);
        }
}

(function($) {
    
    $(document).on('click','.btn-filtrar', function(e) {
       listarImpressoras();                          
    });
    
    $(document).on('click','.excluir-impressora', function(e) {
       excluir(this);                          
    });
    
})(jQuery);

