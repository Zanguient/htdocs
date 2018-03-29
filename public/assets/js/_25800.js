   var cor = 1;
   var horas_cepo;
   var time_cepo;
   var troca;
   var tempo_troca_tela = 60;       //inicial
   
   // alterei - By Emerson 
   var tempo_troca_tela1 = 120;     //producao
   var tempo_troca_tela2 = 90;      //bsc
   var tempo_troca_tela3 = 60;      //comparativo
   var tempo_troca_tela4 = 60;      //ranking
   var tempo_troca_tela5 = 60;      //anuncio
   
   var auto_inicia = 0;
   var trofeu = 0;
   var cepoativo = 0;
   
   var HorariosRanking = [];
   
   function getStart(){
       auto_inicia = $('.auto-inicia').val();
   }
   
   function setImgRodape(img){
        $('.rodape-term').removeClass('img-bsc-1');
        $('.rodape-term').removeClass('img-bsc-2');
        $('.rodape-term').removeClass('img-bsc-3');
        
        $('.rodape-term').addClass(img);
    }
    
    
    {     
        var telaAtiva = 1;
        var GP;
        var Estabelecimento;
        var data1;
        var data2;
        
        function calcTela(e,menu){
            
            if(menu){
                abreFechaMenu($('.icon-fechar'));
            }
            
            redimensionaMenu();
            //$('.tempo').addClock();
            sizeOfThings();
            escondeZerado();
            escondeZeradoP();
            removeNegativo();
            limitaDesc();
            
        }
        
        function limitaDesc(){
            var obj = $('.desc-fab');
            var valor = $(obj).html();
            var ret = '';
            
            if (typeof valor != "undefined"){
                if (valor.length > 9){
                  ret =  valor[0]+valor[1]+valor[2]+valor[3]+valor[4]+valor[5]+valor[6]+valor[7]+valor[8]+'...';
                  $(obj).html(ret);
                }
            }
            
        }
        
        function addEsconde(valor, clas){
           if(valor < 1){
                $(clas).find('.font-ajustavel').addClass('econde-zero');  
            }else{
                $(clas).find('.font-ajustavel').removeClass('econde-zero');
            } 
        }
        
        function escondeZerado(){
            var coluna1 = parseInt($('.eficiencia-turno1-hora-producao').find('.Efic').val());
            var coluna2 = parseInt($('.eficiencia-turno1-geral-producao').find('.Efic').val());
            var coluna3 = parseInt($('.eficiencia-turno2-hora-producao').find('.Efic').val());
            var coluna4 = parseInt($('.eficiencia-turno2-geral-producao').find('.Efic').val());
            
            addEsconde(coluna1,'.coluna1');
            addEsconde(coluna2,'.coluna2');
            addEsconde(coluna3,'.coluna3');
            addEsconde(coluna4,'.coluna4');
            
        }
        
        function addEscondeP(valor,Perda, clas){
           if((valor < 1) && (Perda < 1)){
                $(clas).find('.font-ajustavel').addClass('econde-zero');  
            }else{
                $(clas).find('.font-ajustavel').removeClass('econde-zero');
            } 
        }
        
        function escondeZeradoP(){
            var coluna1p = parseInt($('.perdasL1-turno1-hora-producao').find('.Efic').val());
            var coluna2p = parseInt($('.perdasL1-turno1-geral-producao').find('.Efic').val());
            var coluna3p = parseInt($('.perdasL1-turno2-hora-producao').find('.Efic').val());
            var coluna4p = parseInt($('.perdasL1-turno2-geral-producao').find('.Efic').val());
            
            var coluna1 = parseInt($('.eficiencia-turno1-hora-producao').find('.Efic').val());
            var coluna2 = parseInt($('.eficiencia-turno1-geral-producao').find('.Efic').val());
            var coluna3 = parseInt($('.eficiencia-turno2-hora-producao').find('.Efic').val());
            var coluna4 = parseInt($('.eficiencia-turno2-geral-producao').find('.Efic').val());
            
            addEscondeP(coluna1,coluna1p,'.colunaP1');
            addEscondeP(coluna2,coluna2p,'.colunaP2');
            addEscondeP(coluna3,coluna3p,'.colunaP3');
            addEscondeP(coluna4,coluna4p,'.colunaP4');
        }
        
        function removeNegativo(){
          var obj = $('.no-negative');
          var cont = $(obj).length;
          
          for (i = 0; i < cont; i++){
             var item  = $(obj)[i];
             
             var valor = $(item).html();
             var res = valor.replace("-", "");
             $(item).html(res);
          }
          
        }
        
        function execMenuAtualiza(e){
            alert("Tela em desenvolvimento");   
        }
        
        function  execMenuProducao(e){
            var url = urlhost+"/_25800/include/producao";

            $( ".tela" ).load( url, function( response, status, xhr ) {
                if ( status == "error" ) {
                  var msg = "Sorry but there was an error: ";
                  $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
                }else{
                  calcTela(this);  
                }
            });
            
            telaAtiva = 1; 
        };
        
        function execMenuBSC(e){
            telaAtiva = 2; 
            alert("Tela em desenvolvimento"); 
        };
        
        function  execMenuComparativo(e){
            telaAtiva = 3; 
            alert("Tela em desenvolvimento"); 
        };
        
        function  execMenuRanking(e){
            telaAtiva = 4; 
            alert("Tela em desenvolvimento"); 
        };
        
        function  execMenuAnuncio(e){
            telaAtiva = 5; 
            alert("Tela em desenvolvimento"); 
        };
         
    }
        
    function abreFechaMenu(e){

      var menu = $(e).parent().parent();

      $(menu).toggleClass('esconder');

      if ($(e).hasClass('fa-close')){

        $(e).removeClass('fa-close').addClass('fa-ellipsis-v');    

      }else{ 
        $(e).removeClass('fa-ellipsis-v ').addClass('fa-close');
      }

    }

    function sizeOfThings(){
        
        var windowWidth = window.innerWidth;
        var windowHeight = window.innerHeight;
        
        if((windowHeight*2) > (windowWidth)){
            $('.font-ajustavel' ).css('font-size','2.5vw');
            $('.font-ajustavel2').css('font-size','1.8vw');
            $('.font-ajustavel3').css('font-size','1.4vw');
            $('.font-ajustavel4').css('font-size','2.5vw');
            $('.font-ajustavel5').css('font-size','2.9vw');
            $('.font-ajustavel6').css('font-size','0.7vw');

            $('.font-corte1').css('font-size','2vw');
            $('.font-corte2').css('font-size','1.4vw');
            $('.font-corte3').css('font-size','0.7vw');

        }else{
            $('.font-ajustavel' ).css('font-size','2.4vw');
            $('.font-ajustavel2').css('font-size','1.2vw');
            $('.font-ajustavel3').css('font-size','1.4vw');
            $('.font-ajustavel4').css('font-size','1.8vw');
            $('.font-ajustavel5').css('font-size','2.9vw');
            $('.font-ajustavel6').css('font-size','0.9vw');

            $('.font-corte1').css('font-size','2vw');
            $('.font-corte2').css('font-size','1.4vw');
            $('.font-corte3').css('font-size','0.7vw');
        }
        
        $('.clock').css('font-size',(windowHeight/100)*9.5);
        $('.clock').css('height',(windowHeight/100)*9.5);
        $('.clock').css('width',(windowHeight/100)*9.5);
       
    };
    
    function redimensionaMenu() {
		$('.menu-toggle').height( $(window).height() - 80 );
	}
    
    function addItem(clas) {
        var item = '';
		var obj = $(clas).closest('.form-group').find('._consulta_imputs');
        var imputs = $(obj).html();
        var ID = $(obj).find('._CD').val();
        var DC = $(obj).find('._desc').val();
        
        var cont = $('.'+ID+ID+ID+ID+ID+ID).length;
        
        if(cont < 1){
        
            item = item+'<div class="label label-default tabelas-gp '+ID+ID+ID+ID+ID+ID+'">';
            item = item+'   <span class="lista_sel_id">'+ID+'</span>';
            item = item+'   <span class="lista_sel_id">'+DC+'</span>';
            item = item+'   <button type="button" class="btn btn-danger excluir" title="Excluir" style="display: block;"><i class="glyphicon glyphicon-trash"></i></button>';
            item = item+imputs+'</div>';

            $('.panel-body').append(item);
        }else{
            showAlert('O item da '+DC+' já foi selecionado');
        }
        
        $(clas).find('.btn-apagar-filtro-consulta').trigger('click');
	}
    
    function excluirItem(e){
        $(e).parent().remove();
        
        var cont  = $('.excluir').length;
        
        if (cont == 0){
           $('.consulta_gp_grup').find('.objConsulta').prop('readonly', false);
           $('.consulta_gp_grup').find('.btn-filtro-consulta').prop('disabled', false);
           $('.consulta_gp_grup2').find('.objConsulta').prop('readonly', false);
           $('.consulta_gp_grup2').find('.btn-filtro-consulta').prop('disabled', false);
           $('.consulta_gp_grup').find('.btn-filtro-consulta').css('cursor', 'pointer');
           $('.consulta_gp_grup2').find('.btn-filtro-consulta').css('cursor', 'pointer');
        }
    }
    
    var ret = 2;
    
    function getPerildo(){
        
        var dia         = $('.checs')[1];
        var semana      = $('.checs')[2];
        var mes         = $('.checs')[3];
        var semestre    = $('.checs')[4];
        
        if ($(dia).val() == 1){
            ret = 0;
        }else{
            if ($(semana).val() == 1){
                ret = 1;
            }else{
                if ($(mes).val() == 1){
                    ret = 2;
                }else{
                    if ($(semestre).val() == 1){
                        ret = 3;
                    }
                }
            }
        }
        
        return ret;  
    }
    
    var total = 1;
        
    function getTotal(){

        var res = 0;

        if( $.type($('.tot-comp').val()) == 'undefined' ){
            res = total;
        }else{
            res = $($('.tot-comp')).val();
            total = res;
        }

        return res;
    }
    
    function compararHora(hora1, hora2)
    {
        hora1 = hora1.split(":");
        hora2 = hora2.split(":");

        var d = new Date();
        var data1 = new Date(d.getFullYear(), d.getMonth(), d.getDate(), hora1[0], hora1[1]);
        var data2 = new Date(d.getFullYear(), d.getMonth(), d.getDate(), hora2[0], hora2[1]);

        return data1 > data2;
    };
    
    function compararIntervalo(hora1, hora2)
    {
        hora1 = hora1.split(":");
        hora2 = hora2.split(":");

        var d = new Date();
        var data0 = new Date(d.getFullYear(), d.getMonth(), d.getDate(), d.getHours(), d.getMinutes());
        var data1 = new Date(d.getFullYear(), d.getMonth(), d.getDate(), hora1[0], hora1[1]);
        var data2 = new Date(d.getFullYear(), d.getMonth(), d.getDate(), hora2[0], hora2[1]);
        
        var v1 = data0 >= data1;
        var v2 = data0 <= data2;
        
        return v1 && v2;
        
    }
        
    function filtrarTela(e,tela,progress){
        
       if(tela > 0){telaAtiva = tela;}
           
        var obj   =  $('.excluir').parent();
        var cont  = $(obj).length;

        var id = '';
        var grupo = '';
        var eficiencia = '';
        var desc = '';
        var ccusto = '';
        var estab = 0;
        var data = '';
        var perildo = 1;
        var auto = 0;
        var familia = 0;
        
        if (cont > 0){
            if (telaAtiva > 0){
                
            for (var i = 0; i < cont ; i++) {
                
                 var imput =  $(obj)[i];

                 if (i < 1){
                     id = $(imput).find('._id_gp').val();
                     grupo = $(imput).find('._bsc_grupo_gp').val();
                     eficiencia = $(imput).find('._efic_gp').val();
                     desc = $(imput).find('._desc').val();
                     ccusto = $(imput).find('._ccusto_gp').val();
                 }else{ 
                     id = id + ',' +$(imput).find('._id_gp').val();
                     grupo = grupo + ',' +$(imput).find('._bsc_grupo_gp').val();
                     eficiencia = eficiencia + ',' +$(imput).find('._efic_gp').val();
                     desc = desc + ',' +$(imput).find('._desc').val();
                     ccusto = ccusto + ',' +$(imput).find('._ccusto_gp').val();
                }

            }


            estab   = $('.estab').val();
            data    = $('.data-indicador').val();
            familia = $('.familia').val();
                    
            if(telaAtiva == 3){
               total   = getTotal();
            }
            
            auto_inicia = $('.auto-inicia').val();
            perildo = getPerildo();
             
            var caminho_consulta = '';
             
             if(telaAtiva == 1 ){caminho_consulta = 'consultaprod'; gettrofeuall();}
             if(telaAtiva == 2 ){caminho_consulta = 'consultabsc';}
             if(telaAtiva == 3 ){caminho_consulta = 'consultacomparativo';}
             if(telaAtiva == 4 ){
                 
                caminho_consulta = 'consultaranking';
                
                //se esta no modo altomatico
                if(auto_inicia == 1){
                    var Mudar = false;

                    if(HorariosRanking.length > 0){
                        
                        $(HorariosRanking).each(function(i){
                            var horainicio = $(this).attr('horainicio');
                            var horafim    = $(this).attr('horafim');

                            Mudar = Mudar || compararIntervalo(horainicio,horafim);
                        });
                        
                        if(Mudar){

                        }else{
                            //se não esta na hora de mudar para o ranking vai pra a procima tela
                            telaAtiva++;
                        }
                            
                    }else{
                       //se não esta na hora de mudar para o ranking vai pra a procima tela 
                       telaAtiva++;
                    }
                } 
             }
             if(telaAtiva == 5 ){caminho_consulta = 'consultaanuncio'; anuncioitem = 0;}
             if(telaAtiva == 6 ){caminho_consulta = 'consultacomparativoG1';}
             
             var url_action = urlhost + "/_25800/"+caminho_consulta;
             var dados = {'id':id, 'grupo':grupo ,'eficiencia':eficiencia ,'desc':desc ,'ccusto':ccusto,'estab':estab, 'data':data, 'perildo':perildo, 'total':total, 'autoinicia':auto_inicia,'familia':familia};
             var type = "POST";

             function success(data){
                 
                if(telaAtiva == 1){
                    if(data == '0'){
                        if(auto_inicia == 1){
                            clearInterval(troca);
                            troca = setInterval(trocaTela, 1 * 1000);
                            tempo_troca_tela = tempo_troca_tela5;
                            console.log(tempo_troca_tela5);

                        }else{
                            $(".tela" ).html(data);
                            calcTela($(".tela" ),false);
                        }
                    }else{
                        $(".tela" ).html(data);
                        calcTela($(".tela" ),false);

                        if(auto_inicia == 1){
                           clearInterval(troca);
                           troca = setInterval(trocaTela, tempo_troca_tela5 * 1000);
                           tempo_troca_tela = tempo_troca_tela5;
                           console.log(tempo_troca_tela5);
                        }
                    }
                    
                    var itens = $('.anuncio-conteiner');
                    var obj2  = $(itens)[0];
                    var cont  =  parseInt($(obj2).attr('max'));

                    if(proximo > 0){
                        for(i = 0; i < proximo; i++){
                            procimoanuncio();
                        }
                    }

                    proximo++;
                    if(proximo > cont){proximo = 0;}
                    
                }else{

                   $(".tela" ).html(data);
                   calcTela($(".tela" ),false);

                   if(caminho_consulta == 'consultaprod'){
                       HorariosRanking = $('.hora-ranking');
                   }

                   //
                   //if(auto_inicia == 1){
                   //    clearInterval(troca);
                   //   troca = setInterval(trocaTela, tempo_troca_tela * 1000);
                   //    
                   //}

                   //ranking
                   if(telaAtiva == 6){
                       if(auto_inicia == 1){
                           clearInterval(troca);
                           troca = setInterval(trocaTela, tempo_troca_tela4 * 1000);
                           tempo_troca_tela = tempo_troca_tela4;
                           console.log(tempo_troca_tela4);
                       }

                   }

                   //ranking
                   if(telaAtiva == 5){
                       if(auto_inicia == 1){
                           clearInterval(troca);
                           troca = setInterval(trocaTela, tempo_troca_tela4 * 1000);
                           tempo_troca_tela = tempo_troca_tela4;
                           console.log(tempo_troca_tela4);
                       }

                   }

                   //bsc
                   if(telaAtiva == 4){
                       if(auto_inicia == 1){
                           clearInterval(troca);
                           troca = setInterval(trocaTela, tempo_troca_tela2 * 1000);
                           tempo_troca_tela = tempo_troca_tela2;
                           console.log(tempo_troca_tela2);
                       }   

                   }

                   //comparativo
                   if(telaAtiva == 3){
                       if(auto_inicia == 1){
                           clearInterval(troca);
                           troca = setInterval(trocaTela, tempo_troca_tela3 * 1000);
                           tempo_troca_tela = tempo_troca_tela3;
                           console.log(tempo_troca_tela3);
                       }

                   }

                   //producao
                   if(telaAtiva == 2){
                       if(auto_inicia == 1){
                           clearInterval(troca);
                           troca = setInterval(trocaTela, tempo_troca_tela1 * 1000);
                           tempo_troca_tela = tempo_troca_tela1;
                           console.log(tempo_troca_tela1);
                       }

                   }
                   
               }
                 
             }

             function erro(data){
                 showErro(data);
                
                if(auto_inicia == 1){
                     troca = setInterval(trocaTela, tempo_troca_tela * 1000);
                }
             }

             execAjax1(type,url_action,dados,success,erro,false,progress);

             $('.fechar-modal').trigger('click');
             
        }else{
            showAlert('Selecione uma tela');  
        }
        }else{
            showAlert('Selecione um Grupo de Produção');
        }
    }


    function execmoreinfo(e){
        
        if( $(e).parent().find('.desc-nota').hasClass('info-inativo') ){
            $(e).parent().find('.desc-nota').addClass('info-ativo').removeClass('info-inativo');
            $(e).addClass('bol-inativo').removeClass('bol-ativo');
            $(e).find('.bol').html('x');
        }else{
            $(e).parent().find('.desc-nota').addClass('info-inativo').removeClass('info-ativo');
            $(e).addClass('bol-ativo').removeClass('bol-inativo');
            $(e).find('.bol').html('i');
        }
    }
    
    function execMenuParanConnsulta(e){
        
    }
    
    var anuncioitem = 0;
    var max = 0;
    var proximo = 0;
    
    function procimoanuncio(e){
       
       var itens = $('.anuncio-conteiner');
       var obj2  = $(itens)[0];
       
       var item =  anuncioitem;
       var max  =  $(obj2).attr('max');
       
       if(parseInt(item) < parseInt(max)){
           var obj1 = $(itens)[parseInt(item)+1];
           var obj2 = $(itens)[parseInt(item)];
           
           $(obj1).removeClass('imgnoativa');
           $(obj1).addClass('imgativa');
           $(obj2).addClass('imgnoativa');
           $(obj2).removeClass('imgativa');
           
           anuncioitem = item+1;
           
           $('.imginativo').removeClass('imginativo');
            
            if(anuncioitem == max){
                $(e).addClass('imginativo');
            }
            
            $('.prev-anuncio').html((anuncioitem+1)+' de '+(parseInt(max)+1));
            
       }
       
    }
    
    function anuncioanterior(e){
       var itens = $('.anuncio-conteiner');
       var obj2  = $(itens)[0];
       
       var item =  anuncioitem;
       var max  =  $(obj2).attr('max');
       
       if(parseInt(item) > 0){
           var obj1 = $(itens)[parseInt(item)-1];
           var obj2 = $(itens)[parseInt(item)];
           
           $(obj1).removeClass('imgnoativa');
           $(obj1).addClass('imgativa');
           $(obj2).addClass('imgnoativa');
           $(obj2).removeClass('imgativa');
           
           anuncioitem = item-1;
            
            $('.imginativo').removeClass('imginativo');
            
            if(anuncioitem == 0){
                $(e).addClass('imginativo');
            }
            
            $('.prev-anuncio').html((anuncioitem+1)+' de '+(parseInt(max)+1));
            
            $('.prev-anuncio').html(desc);
       }
    }
    
    
    function sowcepo(cor,hora){
     
        $('.tela-cepo').popUp();
        
        $('.modal-body').addClass('cepo-'+cor);
        $('.cor-cepo').html(cor).addClass('cepof-'+cor);
        $('.hora-cepo').html(hora);
        
        var exec = setInterval(function(){
                
            $('.popup-close').trigger('click');
            clearInterval(exec);
            
        }, 60000);
    }
    
    function removecorcepo(){
        $('.modal-body').removeClass('cepo-verde');
        $('.modal-body').removeClass('cepo-vermelho');
        $('.modal-body').removeClass('cepo-amarelo');
        $('.modal-body').removeClass('cepo-azul');
        
        $('.cor-cepo').removeClass('cepof-verde');
        $('.cor-cepo').removeClass('cepof-vermelho');
        $('.cor-cepo').removeClass('cepof-amarelo');
        $('.cor-cepo').removeClass('cepof-azul');
        
        //time_cepo = setInterval(exec_cepo, 1000);
    }
    
    function addLetreuiro(srt){
        var res = srt.toUpperCase();
        $('.desc-msg').html(res);
    }
    
    function getLetreiro(){
        
        var obj   =  $('.excluir').parent();
        var cont  = $(obj).length;

        var id = '';
        var ccusto = '';
        var estab = 0;

        if (cont > 0){
                
            for (var i = 0; i < cont ; i++) {
                
                 var imput =  $(obj)[i];

                 if (i < 1){
                     id = $(imput).find('._id_gp').val();
                     ccusto = $(imput).find('._ccusto_gp').val();
                 }else{ 
                     id = id + ',' +$(imput).find('._id_gp').val();
                     ccusto = ccusto + ',' +$(imput).find('._ccusto_gp').val();
                }

            }

            estab = $('.estab').val();

            var url_action = urlhost + "/_25800/letreiro";
            var dados = {'id':id,'ccusto':ccusto,'estab':estab};
            var type = "POST";

            function success(data){
                addLetreuiro(data);
            }

            function erro(data){
                showErro(data);
            }

            execAjax1(type,url_action,dados,success,erro,false,false);
        }else{
            console.log('Erro no letreiro(Selecione um Grupo de Produção)');
        } 
    }
    
    function getFrase(){
        var f1  = 'Pessoas normais produzem resultados normais. Pessoas diferentes produzem Resultados Extraordinários. :José Roberto Marques';
        var f2  = 'Eu sou parte de uma equipe. Então, quando venço, não sou eu apenas quem vence. De certa forma, termino o trabalho de um grupo enorme de pessoas. :Ayrton Senna';
        var f3  = 'O talento vence jogos, mas só o trabalho em equipe ganha campeonatos. :Michael Jordan';
        var f4  = 'O segredo de um grande sucesso esta no trabalho de uma grande equipe. :Murillo Cintra';
        var f5  = 'Trabalhar em equipe não significa que todos tenham que fazer tudo, mas sim ter a consciência do todo e do papel de cada um neste todo. :Daniel Godri Junior';
        var f6  = 'Todos são peças importantes no trabalho em equipe, cada um representa uma pequena parcela do resultado final, quando um falha, todos devem se unir, para sua reconstrução. :Salvador Faria';
        var f7  = 'Quando trabalhamos coletivamente em prol de um objetivo, conquistamos o impossível. :Jadson Barbosa';
        var f8  = 'Compromisso, trabalho em equipe e melhoria contínua, são chaves para conquistar excelência em qualidade e satisfação dos clientes. :Marcelo Sousa da Silva';
        var f9  = 'Devemos nos unir. Aprendermos uns com os outros pra ficarmos mais fortes. Isso é trabalho de equipe, isso é amizade. :André Bianco';
        var f10 = 'A prática do trabalho em equipe com respeito, lealdade, generosidade, empatia, transparência, são fatores essenciais para uma conduta Ética e vencedora. :Leao. J.F.';
        var f11 = 'Por vezes sentimos que aquilo que fazemos não é senão uma gota de água no mar. Mas o mar seria menor se lhe faltasse uma gota. :Madre Teresa de Calcutá';
        var f12 = 'Com dedicação, motivação, visão, talento, diálogo e trabalho em equipe podemos transformar o trabalho, a empresa, a comunidade e contribuirmos para uma sociedade melhor e mais justa. :Otto Cembranelli';
        var f13 = 'vencer exige trabalho de equipe. :Israel Rodrigues';
        var f14 = 'Trabalhar em equipe, nem sempre é acertar o alvo, mas sim se dispor a qualquer momento, ir além de suas expectativas, é ajudar o próximo. :Gilberto Blayt';
        var f15 = 'Para que o trabalho em equipe funcione, cada membro precisa usar a seguinte fórmula: um punhado de bom senso, duas xícaras de coleguismo, uma jarra cheia dos objetivos do grupo, e uma pitada de sincronismo. :Josianne Corrêa Cardoso';
        var f16 = 'O trabalho em equipe reúne forças e experiência. :Johnny De Carli';
        var f17 = 'Uma equipe de trabalho sem harmonia, é igual uma orquestra desafinada. :Salvador Costa';
        var f18 = 'Tenta se apegar na arte do trabalho de equipe. Com isso, você e seu time ganhará muito mais do que um jogo. :Gaa Caiires';
        var f19 = 'Quando uma equipe pensa, reflete e aceita o novo como algo construtivo, é mais fácil assumir novos conhecimentos :Helyane Dianno';
        var f20 = 'Motivação, parceria, trabalho em equipe :Bernardinho';
        
        $i = Math.floor(Math.random() * 19) + 1;
        
        switch ($i) {
            case 1 : $ret = f1;  break;
            case 2 : $ret = f2;  break;
            case 3 : $ret = f3;  break;
            case 4 : $ret = f4;  break;
            case 5 : $ret = f5;  break;
            case 6 : $ret = f6;  break;
            case 7 : $ret = f7;  break;
            case 8 : $ret = f8;  break;
            case 9 : $ret = f9;  break;
            case 10: $ret = f10; break;
            case 11: $ret = f11; break;
            case 12: $ret = f12; break;
            case 13: $ret = f13; break;
            case 14: $ret = f14; break;
            case 15: $ret = f15; break;
            case 16: $ret = f16; break;
            case 17: $ret = f17; break;
            case 18: $ret = f18; break;
            case 19: $ret = f19; break;
            case 20: $ret = f20; break;
        }
        
        return $ret;
       
    }
    
    function pad(n, width, z) {
        z = z || '0';
        n = n + '';
        return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
    }
    
    
    var contcepo = 0;
    
    function exec_cepo(){
        var data = new Date();
        var h = pad(data.getHours(),2);
        var m = pad(data.getMinutes(),2);
        
        var atual = h+':'+m;
        
        contcepo++;
        
        horas_cepo.forEach(function(obj){
            var hora = obj.HORA; 
            var cor  = obj.COR;
            
            if(hora == atual){
                if (cor == 1){sowcepo('vermelho',hora);}
                if (cor == 2){sowcepo('azul',hora);}
                if (cor == 3){sowcepo('verde',hora);}
                if (cor == 4){sowcepo('amarelo',hora);} 
            }
        
        });
        
    }
    
    function getHoraCepo(){

        var estab = 0;

        estab = $('.estab').val();

        var url_action = urlhost + "/_25800/horacepo";
        var dados = {'estab':estab};
        var type = "POST";

        function success(data){
            horas_cepo = data;
                if(cepoativo < 1){
                    time_cepo = setInterval(exec_cepo, 3000);
                }
            cepoativo++;
        }

        function erro(data){
            showErro(data);
        }
        
        if(cepoativo < 1){
            execAjax1(type,url_action,dados,success,erro,false,false);
        }
    }
    
    function gettrofeu(){

        var estab = 0;
        
        var obj   =  $('.excluir').parent();
        var cont  = $(obj).length;

        var id = '';
        var mes = 0;
        var estab = 0;

        if (cont == 1){
                
            for (var i = 0; i < cont ; i++) {
                
                 var imput =  $(obj)[i];

                 if (i < 1){
                     id = $(imput).find('._id_gp').val();
                 }else{ 
                     id = id + ',' +$(imput).find('._id_gp').val();
                }

            }
            
            mes = $('.data-indicador').val();
            
            estab = $('.estab').val();

            var url_action = urlhost + "/_25800/trofeu";
            var dados = {'gp':id,'mes':mes,'estab':estab};
            var type = "POST";

            function success(data){
                trofeu = data;
            }

            function erro(data){
                showErro(data);
            }

            execAjax1(type,url_action,dados,success,erro,false,false);
        }else{
            console.log('Erro no trofel');
        }
    }
    
    function gettrofeuall(){

        var estab = 0;
        
        var obj   =  $('.excluir').parent();
        var cont  = $(obj).length;

        var id = '';
        var mes = 0;
        var estab = 0;

        if (cont == 1){
                
            for (var i = 0; i < cont ; i++) {
                
                 var imput =  $(obj)[i];

                 if (i < 1){
                     id = $(imput).find('._id_gp').val();
                 }else{ 
                     id = id + ',' +$(imput).find('._id_gp').val();
                }

            }
            
            mes = $('.data-indicador').val();
            
            estab = $('.estab').val();

            var url_action = urlhost + "/_25800/trofeuall";
            var dados = {'id':id,'data':mes,'estab':estab};
            var type = "POST";

            function success(data){
                $('.desc-tro').html(data);
            }

            function erro(data){
                showErro(data);
            }

            execAjax1(type,url_action,dados,success,erro,false,false);
        }else{
            console.log('Erro no trofel-all');
        }
    }
    
    var cont = 0;
    
    function trocaTela(){
        cont++;
        now = new Date;

        console.log('Troca tela: '+cont+' as '+now.getHours()+ ":" + now.getMinutes() + ":" + now.getSeconds()+" -> "+tempo_troca_tela+"s");     
        
        clearInterval(troca);
        
        filtrarTela(this,telaAtiva,false);
        
        if(telaAtiva == 1){
            gettrofeu();

            if(trofeu > 0){
                $('.div-trofeu').removeClass('trofeu1-fogos');
                $('.div-trofeu').removeClass('trofeu2-fogos');
                $('.div-trofeu').removeClass('trofeu3-fogos');

                $('.div-trofeu').addClass('trofeu'+trofeu+'-fogos');

                //$('.navbar-left').trigger('click');
            }
            telaAtiva = 2;
        }else{
            if(telaAtiva == 2){
                telaAtiva = 4;
            }else{
                if(telaAtiva == 4){
                    telaAtiva = 5;
                }else{
                    telaAtiva = 1;
                }
            }
        }
    }
    
    function addGP(gp_id,desc,ccusto,efic,grupo){
        var code = '';
        
        code = code+'<div class="label label-default tabelas-gp '+gp_id+gp_id+gp_id+gp_id+gp_id+gp_id+'">';
        code = code+'<span class="lista_sel_id">'+gp_id+'</span>';
        code = code+'<span class="lista_sel_id">'+desc+'</span>';
        code = code+'<button type="button" class="btn btn-danger excluir" title="Excluir" style="display: block;"><i class="glyphicon glyphicon-trash"></i></button>';
        code = code+'<input type="hidden" name="_id_gp" class="_consulta_imputs _id_gp" objcampo="CODE" value="'+gp_id+'">';	
        code = code+'<input type="hidden" name="_ccusto_gp" class="_consulta_imputs _ccusto_gp" objcampo="CCUSTO" value="'+ccusto+'">';	
        code = code+'<input type="hidden" name="_bsc_grupo_gp" class="_consulta_imputs _bsc_grupo_gp" objcampo="BSC_GRUPO" value="'+grupo+'">';	
        code = code+'<input type="hidden" name="_efic_gp" class="_consulta_imputs _efic_gp" objcampo="EFIC_MINIMA" value="'+efic+'">';	
        code = code+'<input type="hidden" name="_desc" class="_consulta_imputs _desc" objcampo="DESC" value="'+desc+'">';
        code = code+'<input type="hidden" name="_CD" class="_consulta_imputs _CD" objcampo="CODE" value="'+gp_id+'">';
        code = code+'</div>';
        
        $('.panel-body').append(code);
    }
    
    
    
    function ativarTrocaDeTelas(){
        
        execem = setInterval(function(){
            clearInterval(execem);
            
            getHoraCepo();

            if(auto_inicia == 1){
                trocaTela();
            }
                 
   
        }, 3000);
        
        
    }
    
    function caregar(){
        location.reload();    
    }
	
(function ($) {
    getStart();
    myVar = setInterval(getLetreiro, 600000);
    
    addLetreuiro(getFrase());
    
    if(auto_inicia == 1){
        var id = $('.auto-id').val();
        var desc = $('.auto-desc').val();
        
        if(parseInt(id) > 0){
            addGP(id,desc,'',99.9,'');
            ativarTrocaDeTelas();
            //recaregar = setInterval(caregar, 7200000);
            $('.go-fullscreen').trigger('click');
        }
    }
    
    if(auto_inicia == 2){
        var id = $('.auto-id').val();
        var desc = $('.auto-desc').val();
        
        if(parseInt(id) > 0){
            addGP(id,desc,'',99.9,'');
        }
    }
    
    $(document).ready(function(){
        //
        sizeOfThings();
        redimensionaMenu();
        abreFechaMenu($('.icon-fechar'));
    });

    $(document).on('click','.icon-fechar', function(e) {
        abreFechaMenu(this);
    });
    
    $(document).on('click','.fechar-info-prod', function(e) {
        $('.dados-prod').css('display','none');
    });
    
    $(document).on('click','.perdas-mez', function(e) {
        $('.dados-prod').css('display','block');
    });

    $(document).on('click','.excluir', function(e) {
        excluirItem(this);
    });
    
    $(document).on('click','.span-consulta', function(e) {
        
        if ($(this).hasClass('span-DESC') || $(this).hasClass('span-CODE')){ 
            addItem('.consulta_gp_grup');
            
            $('.consulta_gp_grup2').find('.objConsulta').prop('readonly', true);
            $('.consulta_gp_grup2').find('.btn-filtro-consulta').prop('disabled', true);
            $('.consulta_gp_grup2').find('.btn-filtro-consulta').css('cursor', 'not-allowed !important');
        }else{
            if ($(this).hasClass('span-DESCRICAO') || $(this).hasClass('span-ID')){ 
                addItem('.consulta_gp_grup2');
                $('.consulta_gp_grup').find('.objConsulta').prop('readonly', true);
                $('.consulta_gp_grup').find('.btn-filtro-consulta').prop('disabled', true);
                $('.consulta_gp_grup2').find('.objConsulta').prop('readonly', true);
                $('.consulta_gp_grup2').find('.btn-filtro-consulta').prop('disabled', true);
                $('.consulta_gp_grup').find('.btn-filtro-consulta').css('cursor', 'not-allowed !important');
                $('.consulta_gp_grup2').find('.btn-filtro-consulta').css('cursor', 'not-allowed !important');
            }
        }
        
    });
    
    $(document).on('click','.menu-atualiza-consulta', function(e) {
        gettrofeuall();
        filtrarTela(this,0);
    });
    
    $(document).on('click','.filtrar-indicador', function(e) {
        filtrarTela(this,0);
    });
    
    $(document).on('click','.menu-paran-consulta', function(e) {
        execMenuParanConnsulta(this);
    });
    
    $(document).on('click','.menu-producao', function(e) {
        //execMenuProducao(this);
        filtrarTela(this,1);
    });
    
    $(document).on('click','.menu-bsc', function(e) {
        filtrarTela(this,2);
    });
    
    $(document).on('click','.menu-comparativo', function(e) {
        filtrarTela(this,3);
    });
    
    $(document).on('click','.menu-comparativoG1', function(e) {
        filtrarTela(this,6);
    });
    
    $(document).on('click','.menu-ranking', function(e) {
        filtrarTela(this,4);
    });
    
    $(document).on('click','.menu-anuncio', function(e) {
        filtrarTela(this,5);
    });
    
    $(document).on('click','.anuncion', function(e) {
        procimoanuncio(this);
    });
    
    $(document).on('click','.anunciop', function(e) {
        anuncioanterior(this);
    });
    
    window.addEventListener('resize', function(){
        sizeOfThings();
    });
	
	$(window).resize(function() {
		redimensionaMenu();
	});
    
    $(document).on('click','.more-info', function(e) {
        execmoreinfo(this);
    });
    
    var tempo;
    var tempo2;
    
    $(document).on('click','.navbar-left', function(e) {
        
        gettrofeuall();
        
        //$('.canvas-container').fadeIn();
        //$('.trofeu-fogos').fadeIn();
        
        //tempo = setInterval(function (){$('canvas').trigger('click'); clearInterval(tempo);}, 1000);
        //tempo2 = setInterval(function (){$('canvas').trigger('click'); clearInterval(tempo2);}, 2500);
        	
			//$('.canvas-container').delay(6000).fadeOut();
			//$('.trofeu-fogos').delay(6000).fadeOut();  
    });
    
    $(document).on('click','.usuario-logado', function(e) {
       
        getHoraCepo();
        var hora = '00:00';
        
        if (cor == 2){sowcepo('verde',hora);}
        if (cor == 1){sowcepo('vermelho',hora);}
        if (cor == 4){sowcepo('amarelo',hora);}
        if (cor == 3){sowcepo('azul',hora);}
        
        cor++;
        
        if(cor > 4){
             cor = 1;
        }
    });
    
    $(document).on('click','.popup-close', function(e) {
        removecorcepo();   
    });
    
    $(document).on('change','.checs', function(e) {
        
        $('.checs').prop('checked',false);
        $('.checs').val(0);
        $(this).prop('checked',true);
        $(this).val(1);
        
    });
    
    $(document).on('change','.tot-comp', function(e) {
        
        if($(this).val() == 1){
            $(this).prop('checked',false);
            $(this).val(0);
        }else{
            $(this).prop('checked',true);
            $(this).val(1);
        }
        
        
    });
    
    $(document).on('click','.btn-pause', function(e) {
        trofeu();
        
    });

})(jQuery);
//# sourceMappingURL=_25800.js.map
