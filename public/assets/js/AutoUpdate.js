(function($) {
    
    var abilite = false;
    var tempo_atualizar = 60;
    var time_fechar;
    var fechar_ativo = 0;
    var tempo_fechar_tela = tempo_atualizar * 1000;
    var tempo_atualizar_tela = tempo_atualizar * tempo_atualizar * 1000;
    
    function getAtributosTelaProd(x){

        function getStabelecimento(){
           return $("._input_estab").val();
        }
        
        function setStabelecimento(valor){
           $("._input_estab").val(valor);
        }

        function getGp(){
           return $("._gp_descricao").val(); 
        }
        
        function setGp(valor){
            if(valor != ''){ 
                $('.consulta_gp_grup').find(".consulta-descricao").val(valor);
                $('.consulta_gp_grup').find(".btn-filtro-consulta").trigger( "click" );
            }
        }

        function getUp(){
           return $("._up_descricao").val(); 
        }

        function setUp(valor){
            if(valor != ''){ 
                $('.consulta_up_group').find(".consulta-descricao").val(valor);
                $('.consulta_up_group').find(".btn-filtro-consulta").trigger( "click" );
            }
        }

        function getUpo(){
           return $("._up_origem_descricao").val(); 
        }
        
        function setUpo(valor){
            if(valor != ''){ 
                $('.consulta_up_origem_group').find(".consulta-descricao").val(valor);
                $('.consulta_up_origem_group').find(".btn-filtro-consulta").trigger( "click" );
            }
        }

        function filtrar(){
           return $(".btn-filtrar").trigger( "click" );
        }

        function coletar(){
            var estabelecimento = getStabelecimento();
            var descricao_gp    = getGp();
            var descricao_up    = getUp();
            var descricao_upo   = getUpo();
            var atualizar       = 0; 

            sessionStorage.setItem("estabelecimento"  , estabelecimento   );
            sessionStorage.setItem("descricao_gp"     , descricao_gp      );
            sessionStorage.setItem("descricao_up"     , descricao_up      );
            sessionStorage.setItem("descricao_upo"    , descricao_upo     );
            sessionStorage.setItem("atualizar"        , atualizar         );

        }
        
        function setValores(){
            
            var tempo = 2000;
            
            var estabelecimento = sessionStorage.getItem("estabelecimento"  );
            var descricao_gp    = sessionStorage.getItem("descricao_gp"     );
            var descricao_up    = sessionStorage.getItem("descricao_up"     );
            var descricao_upo   = sessionStorage.getItem("descricao_upo"    );
            
            sessionStorage.setItem("atualizar",0);
            
            setStabelecimento(estabelecimento);
            setTimeout(function(){
                setGp(descricao_gp);
                setTimeout(function(){
                    setUp(descricao_up);
                    setTimeout(function(){
                        setUpo(descricao_upo);
                        setTimeout(function(){
                            filtrar();
                            fechar_ativo = 1;
                            
                            time_load = setTimeout(function(){
                                $(document).on('mousemove', function() {
                                    liparTimes();
                                });

                                clearTimeout(time_load);
                                time_load = null;
                            },5000);
                            
                            time_fechar = setTimeout(function(){
                                
                                var url = document.URL;
                                var url = (url).replace("/", "@");
                                var url = (url).replace("/", "@");
                                var url = (url).replace("/", "@");
                                var url = (url).replace("/", "@");
                                var url = (url).replace("/", "@");
                                
                                window.location.href = urlhost+'/abainativa/'+url;
                                
                            },tempo_fechar_tela);
                            
                        },tempo);
                    },tempo);
                },tempo);
            },tempo);
        }
        
        if(x == 1){
            coletar();
        }else{
          setValores();  
        }
        
    }
    
    var timer;
    var Time_cont;
    var cont = 0;
    var contando = 0;
    var timer_ativar;
    
    function liparTimes(){
        clearTimeout(timer_ativar);
        setAlertaOculto();
        
        if(fechar_ativo == 1){
            clearTimeout(time_fechar);
            time_fechar = null;
        }
        
        if(contando == 1){
            cont=0;
            clearInterval(Time_cont);
            Time_cont = null;
            clearTimeout(timer);
            timer = null;
            contando = 0;
            setContadorOculto();
            sessionStorage.setItem("atualizar",0);
        }
        
        timer_ativar = setTimeout(function(){
            if(abilite){
                var flag = $('#finalizar').attr('disabled');

                if( flag == 'disabled'){
                    initContador();
                }
            }
        },5000);
    }
    
    function initContador(){
                
        contando =1;

        setContadorVisivel();

        timer = setTimeout(function(){
            cont=0;
            clearInterval(Time_cont);
            Time_cont = null;
            clearTimeout(timer);
            timer = null;
            sessionStorage.setItem("atualizar",1);
            setContadorOculto();
            location.reload();
        },tempo_atualizar_tela);

        Time_cont = setInterval(function(){
            cont++;
            setContadorValor((tempo_atualizar - cont) + 's para actualizar a pagina e melhorar o desempenho do aplicativo.');
            
            if((tempo_atualizar - cont) <= 5){
                setAlertaVisivel();
            }
            
        },1000);
    }
    
    function setContadorVisivel(){
        $('.contador-Atualizar').show();
    }
    
    function setContadorOculto(){
        $('.contador-Atualizar').hide();
    }
    
    function setAlertaVisivel(){
        $('.info-Atualizar').show();
    }
    
    function setAlertaOculto(){
        $('.info-Atualizar').hide();
    }
    
    function setContadorValor(valor){
        $('.contador-Atualizar').html(valor);
        $('.info-Atualizar-fraze').html(valor);
    }
    
    var time_load;
    
    window.onload = function(){
        if(abilite){
            var flag = sessionStorage.getItem("atualizar");

            setContadorOculto();

            if(flag == 1){
                getAtributosTelaProd(2);  
            }else{
                time_load = setTimeout(function(){
                    $(document).on('mousemove', function() {
                        liparTimes();
                    });

                    clearTimeout(time_load);
                    time_load = null;
                },4000);
            }    
        }
        setAlertaOculto();
    };
    
    $(document).on('click','.btn-filtrar', function(e) {
      getAtributosTelaProd(1);  
    });
    
})(jQuery);
//# sourceMappingURL=AutoUpdate.js.map
