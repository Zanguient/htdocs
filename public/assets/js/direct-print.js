
    function addProgresPrint(){
        addConfirme('Impressão',''
            +'<div id="printer_data_loading"><span id="loading_message">Carregando Detalhes da impressora...</span><br/>'
            +'  <div class="progress" style="width:100%">'
            +'    <div class="progress-bar progress-bar-striped active"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">'
            +'    </div>'
            +'  </div>'
            +'</div>'
            +'',
            [obtn_cancelar],function(){}    
            );
    }

   function postprint(codigo){
       
        addProgresPrint();
        
        if(true){
            
            function success(data) {
            
                if(data) {

                    $('.retcod').val(data);

                    var init_app = $('.iniciado-pelo-app').val();
                    if(init_app == 0){
                        document.location.href = "clientprint:"+urlhost+"/assets/temp/print/"+data+".txt";
                    }else{
                        $('.ativar-post-print').attr('cod',data);
                        $('.ativar-post-print').attr('url',urlhost).trigger('click');
                    }

                    setTimeout(function(){
                        $('.btn-voltar.btn-confirm-action').trigger( "click" );
                    },2000);
                } 
            }

            function erro(xhr){
                showErro(xhr);  
            }

            var dados = {
                'codigo'  : codigo
            };

            execAjax1('POST','/print/postprint',dados,success,erro);
        }else{
            
            $('.retcod').val(data);

            var init_app = $('.iniciado-pelo-app').val();

            $('.ativar-post-print').attr('cod',codigo);
            $('.ativar-post-print').attr('url',urlhost).trigger('click');

            setTimeout(function(){
                $('.btn-voltar.btn-confirm-action').trigger( "click" );
            },2000);
 
        }
        
    }
    
    function prepareprint(){
        var code = '';
            code = code + '<div style="display: none">';
            code = code + '        <button class="gc-print-open-app" >Open APP</button></p>';
            code = code + '        <button class="gc-print-abrir-log" >Console Log</button></p>';
            code = code + '        <button class="gc-print-open-com" >Conectar</button> <button class="gc-print-close-com" >Desconectar</button></p>';
            code = code + '       <button class="gc-print-set-config" porta="COM3" drive="Alfa1" parit="2">SetConfig</button></p>';
            code = code + '        <div class="gc-print-log-tela">';
            code = code + '           <div class="gc-print-log-topo">';
            code = code + '              <div class="gc-print-fechar-log">x</div>';
            code = code + '               <div class="gc-print-clear-log">Clear</div>';
            code = code + '            </div>';
            code = code + '            <div class="gc-print-log"></div>';
            code = code + '         </div>';
            code = code + '    </div>';
            
            $('body').append(code);
            
            $( ".gc-print-close-com" ).trigger( "click" );
    }
    
(function ($) {
    
    var time;
    var iniciado = 0;
    
    $( ".postprint" ).on( "click", function(e) {
    
        var codigo = $('.codigo').val();
        postprint(codigo);
        
    });
 
    $( ".gc-print-get-peso" ).on( "click", function(e) {
        
        if(iniciado === 0){
            iniciado = 1;
            
            $( ".gc-print-close-com" ).trigger( "click" );
            $( ".gc-print-open-com" ).trigger( "click" );
            
            time = setInterval(function(){ 
                $( ".gc-print-set-config" ).trigger( "click" );
            },3000);
              
        }else{
            $( ".gc-print-close-com" ).trigger( "click" );
            iniciado = 0;
            clearInterval(time);
        }
        
    });
    
    $(document).ready(function(){
        prepareprint();
    });
    
})(jQuery);
//# sourceMappingURL=direct-print.js.map
