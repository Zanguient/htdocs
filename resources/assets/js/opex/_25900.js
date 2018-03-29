
var cor = 1;

    function filtarIndicador(){
        
        var estb = $('._input_estab').val();
        var area = $('._area_id').val();
        var pers = $('._pespectiva_id').val();
        var seto = $('._setor_id').val();
        
        var valoresSetor = [];
        var valoresGrupo = [];
        
        var obj   =  $('.excluir').parent();
        var cont  = $(obj).length;
        
        if (cont > 0){
                
            for (var i = 0; i < cont ; i++) {
                
                 var imput =  $(obj)[i];
                 valoresSetor[i] = $(imput).find('._setor_id').val();
                 valoresGrupo[i] = $(imput).find('._grupo_id').val(); 
                 
            }
        }
            
        var data = $('.data-indicador').val();
        var datb = $('.data-indicador').val();
        
        console.log();
        
        var url_action = "/_25900/filtarIndicador";
        var dados = {
            'estb':estb,
            'area':area,
            'pers':pers,
            'seto':valoresSetor,
            'grup':valoresGrupo,
            'data':data,
            'datb':datb
        };
        
        var type = "POST";

        function success(data){
            $(".tela" ).html(data);
            
            $('.icon-fechar').trigger('click');
            $('.fechar-modal').trigger('click');
            
            sizeOfThings();
        }

        function erro(data){
            showErro(data);
        }

        execAjax1(type,url_action,dados,success,erro);
    }
    
        
    function sizeOfThings(){

        var windowWidth = window.innerWidth;
        var windowHeight = window.innerHeight;

        if((windowHeight*2) > (windowWidth)){
            $('.font-ajustavel').css('font-size','3.2vw' );
            $('.font-ajustavel2').css('font-size','1.9vw');
            $('.font-ajustavel3').css('font-size','1.5vw');
            $('.font-ajustavel4').css('font-size','2.6vw');
            $('.font-ajustavel5').css('font-size','2.5vw');
            $('.font-ajustavel6').css('font-size','0.8vw');
        }else{
            $('.font-ajustavel').css('font-size','2.5vw' );
            $('.font-ajustavel2').css('font-size','1.3vw');
            $('.font-ajustavel3').css('font-size','1.5vw');
            $('.font-ajustavel4').css('font-size','2vw'  );
            $('.font-ajustavel5').css('font-size','2.5vw');
            $('.font-ajustavel6').css('font-size','0.7vw');
        }

    };
 
   
   
   function liparLista(){
   
        $('.panel-body').html('<div class="titulo-lista"><span class="lista_sel_id">ID</span><span class="lista_sel_id">DESCRICAO</span></div>');
        $('.consulta_grupo_grup').find('.objConsulta').prop('readonly', false);
        $('.consulta_grupo_grup').find('.btn-filtro-consulta').prop('disabled', false);

        $('.consulta_setor_grup').find('.objConsulta').prop('readonly', false);
        $('.consulta_setor_grup').find('.btn-filtro-consulta').prop('disabled', false);

        $('.consulta_setor_grup').find('.btn-filtro-consulta').css('cursor', 'pointer');
        $('.consulta_setor_grup').find('.btn-filtro-consulta').css('cursor', 'pointer');
   }
   
   /**
	 * Habilitar para que as consultas para o filtro sejam 
	 * abertas uma após a outra selecionada.
	 */
	function abrirConsultaAutom() {
		
        
		$('select.estab')
			.change(function() {

				var elem = $(this);
		
				setTimeout(function() {
                    
					if ( $(elem).val() != '' ) {

                        var valor = $('.consulta_area_grup').closest('.form-group').find('._consulta_imputs').find('._area_id').val();
                        
                        if((valor != '') && (typeof(valor) != 'undefined') ){
                            console.log('Teste');
                        
                            $('.consulta_area_grup')
                                .find('.btn-apagar-filtro-consulta')
                                .click()
                                .siblings('.consulta-descricao')
                                .focus()
                            ;
                            
                            liparLista();
                            
                        }else{
                            $('.consulta_area_grup')
                                .find('.btn-filtro-consulta')
                                .click()
                                .siblings('.consulta-descricao')
                                .focus()
                            ;
                        }

					}
					
				}, 200);
				
			})
		;
		
		$('._area_id')
			.change(function() {
				
				var elem = $(this);
		
				setTimeout(function() {
					
					if ( $(elem).val() !== '' ) {
                        
                        var valor = $('.consulta_perspectiva_grup').closest('.form-group').find('._consulta_imputs').find('._pespectiva_id').val();
                        
                        if((valor != '') && (typeof(valor) != 'undefined') ){
                        
                            $('.consulta_perspectiva_grup')
                                .find('.btn-apagar-filtro-consulta')
                                .click()
                                .siblings('.consulta-descricao')
                                .focus()
                            ;
                            
                            liparLista();
                            
                        }else{
                            $('.consulta_perspectiva_grup')
                                .find('.btn-filtro-consulta')
                                .click()
                                .siblings('.consulta-descricao')
                                .focus()
                            ;
                        }
					}
					
				}, 200);
				
			})
		;
        
        $('._pespectiva_id')
			.change(function() {
				
				var elem = $(this);
		
				setTimeout(function() {
					
					if ( $(elem).val() !== '' ) {
                        liparLista();       
                    }
				}, 200);
				
			})
		;
        
    }
   
   function setImgRodape(img){
        $('.rodape-term').removeClass('img-bsc-1');
        $('.rodape-term').removeClass('img-bsc-2');
        $('.rodape-term').removeClass('img-bsc-3');
        
        $('.rodape-term').addClass(img);
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
    
    function redimensionaMenu() {
		$('.menu-toggle').height( $(window).height() - 80 );
	}
    
    function addItem(clas) {
        var item    = '';
		var obj     = $(clas).closest('.form-group').find('._consulta_imputs');
        var imputs  = $(obj).html();
        var ID      = $(obj).find('._setor_id').val();
        var DC      = $(obj).find('._setor_descricao').val();
        
        var cont = $('.item_id_'+ID).length;
        
        if(cont < 1){
        
            item = item+'<div class="label label-default tabelas-gp item_id_'+ID+'">';
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
           $('.consulta_grupo_grup').find('.objConsulta').prop('readonly', false);
           $('.consulta_grupo_grup').find('.btn-filtro-consulta').prop('disabled', false);
           
           $('.consulta_setor_grup').find('.objConsulta').prop('readonly', false);
           $('.consulta_setor_grup').find('.btn-filtro-consulta').prop('disabled', false);
           
           $('.consulta_setor_grup').find('.btn-filtro-consulta').css('cursor', 'pointer');
           $('.consulta_setor_grup').find('.btn-filtro-consulta').css('cursor', 'pointer');
        }
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
	
(function ($) {
    //myVar = setInterval(getLetreiro, 600000);
    
    addLetreuiro(getFrase());
    
    $(document).ready(function(){
        redimensionaMenu();
        abreFechaMenu($('.icon-fechar'));
    });

    $(document).on('click','.icon-fechar', function(e) {
        abreFechaMenu(this);
    });

    $(document).on('click','.excluir', function(e) {
        excluirItem(this);
    });
    
    $(document).on('change','._setor_id', function(e) {
        
        var valor = $(this).val();
        
        if (typeof(valor) != 'undefined' && valor > 0 ){
            
            var clase = $(this).closest('.form-group').find('.consulta_setor_grup');
            console.log(clase);
            
            if (typeof(clase) != 'undefined' && clase.length > 0){

                setTimeout(function(){
                    addItem('.consulta_setor_grup');

                    $('.consulta_grupo_grup').find('.objConsulta').prop('readonly', true);
                    $('.consulta_grupo_grup').find('.btn-filtro-consulta').prop('disabled', true);
                    $('.consulta_grupo_grup').find('.btn-filtro-consulta').css('cursor', 'not-allowed !important');
                },200);

            }else{
                setTimeout(function(){
                    addItem('.consulta_grupo_grup');

                    $('.consulta_setor_grup').find('.objConsulta').prop('readonly', true);
                    $('.consulta_setor_grup').find('.btn-filtro-consulta').prop('disabled', true);
                    $('.consulta_setor_grup').find('.btn-filtro-consulta').css('cursor', 'not-allowed !important');
                    
                    $('.consulta_grupo_grup').find('.objConsulta').prop('readonly', true);
                    $('.consulta_grupo_grup').find('.btn-filtro-consulta').prop('disabled', true);
                    $('.consulta_grupo_grup').find('.btn-filtro-consulta').css('cursor', 'not-allowed !important');
                },200);
            }  
        }  
    });
    
    $(function() {
        abrirConsultaAutom();
    });
    
    $(document).on('click','.filtrar-indicador', function(e) {
        filtarIndicador(e);
    });

})(jQuery);