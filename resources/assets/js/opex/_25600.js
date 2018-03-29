    {
		
      var validar = 0;  
      var keyb;
      
        {
            var filtrado = 0;

            function limpaListaIndicador(e){
                if ($(e).hasClass('btn-filtro')){
                    filtrado = 0;
                    $('.corpo-tabela-3').html('<tr><td cellspacing="4" colspan="5"> <div class="tabela-vazia"> Sem registros </div> </td></tr>');
                }
            }

            function consultaListaIndicador(e){

                var _ccusto_id = $('._ccustoindicador_id').val();
                var _indicadores_id = $('._indicadores_id').val();
                var _turno_id = $('._turno_id').val();

                if ((_ccusto_id > 0) & (_indicadores_id > 0) & (_turno_id > 0)) {
                    filtrado = 1;
                    var id = _indicadores_id;  
                    var url_action = '/_25600/listaFaixas';
                    var dados = {'id': id};
                    var type = "POST";

                    if (id > 0){

                        function success(retorno){
                          $('.corpo-tabela-3').html(retorno);
                          abilitarTeclado();
                        }

                        execAjax1(type,url_action,dados,success);
                    }

                    $('.corpo-tabela-3').html('<tr><td cellspacing="4" colspan="5"> <div class="tabela-vazia"> Lista de indicadores </div> </td></tr>');
                }
            }
        }

        {
            var itemEmValidacao;
            var proximoEmValidacao;

            function mediaValores(e){

               var tabela = $.find('.media-valor');
               var cont = 0;
               var soma = 0;
               var item;

               for (i = 0; i < $(tabela).length; i++) {
                   item = $(tabela)[i];
                   cont++;
                   soma = (Number(soma) + Number($(item).val()));
               }
               
               var media = soma/cont;
               $('.edit-media').val(media.toPrecision(2));

            }

            function cancelarValidacao(e){
               validar = 0; 
               $(e).parent().parent().find('.Square-Color').removeClass('verde');
               $(e).parent().parent().find('.Square-Color').removeClass('vermelho');

               $(itemEmValidacao).attr('addplano','0');
               $(itemEmValidacao).attr('plano','');

               $(itemEmValidacao).val('').focus();
               $( ".close" ).trigger( "click" );
               $(e).focus();
            }

            function adicionarPlano(e){
              
             $(itemEmValidacao).attr('addplano','1');
              
              if ($('.adicionar-desc-plano').hasClass('valor-na-tabela')){
                validarValor(itemEmValidacao);
              }
              
              validar = 0;
                var plano = $('.area-ob-indicador').val();  
                
                $(itemEmValidacao).attr('plano',plano);
                $( ".close" ).trigger( "click" );
                proximoEmValidacao.focus();
                
                $(e).removeClass('valor-na-tabela');
            }

            function gravarNotaIndicador(){
                var Tabela = $('.corpo-tabela-3');
                var campos = $(Tabela).find('tr');
                var item = $(campos)[0];

                var id          = '';
                var valor       = '';
                var indicadorid = '';
                var addPlano    = '';
                var plano       = '';
                var detalhe     = '';
                var data        = '';
                var ccusto      = '';
                var turno       = '';
                var peso        = '';
                var items = {};
                var notas =[];

                data        = $('.data-indicador').val();
                ccusto      = $('._ccustoindicador_id').val();
                turno       = $('._turno_id').val();
                indicadorid = $('._indicadores_id').val();

                for (i = 0; i < $(campos).length; i++) {

                    item = $(campos)[i];

                    if ($(item).hasClass('valor')){

                        items = {};

                        id          = $(item).find('._id_item').val();
                        valor       = $(item).find('.validar-valor').val();
                        peso        = $(item).find('._peso_indicador').val();
                        addPlano    = $(item).find('.validar-valor').attr('addplano');
                        plano       = $(item).find('.validar-valor').attr('plano');
                        detalhe     = $(item).find('._id_detalhe_item').val();

                        items['valor'] = valor;
                        items['addPlano'] = addPlano;
                        items['plano'] = plano;
                        items['detalhe'] = detalhe;
                        items['turno'] = turno;
                        items['id'] = id;
                        items['peso'] = peso;
                        
                        notas.push(items);
                    }
                }

                var url_action = '/_25600/store';
                var dados = {'DADOS':notas,'BSC':indicadorid,'DATA':data,'CCUSTO':ccusto};
                var type = "POST";

                function success(dado){
                    console.log(dado);
                    window.location = "/sucessoGravar/_25600";
                }

                execAjax1(type,url_action,dados,success);

            }

            function consultaDescFaixas(e){

                var indicador = $('.modal-corpo').find('._id_indicador_edit').val();
                var detalhe = $('.modal-corpo').find('._id_detalhe_item').val();

                var url_action = '/_25600/descfaixas';
                var dados = { 'INDICADOR':indicador,'IDDETALHE':detalhe, 'CLASS':'adiciona-valor'};
                var type = "POST";

                function success(dado){
                    $('.tabela-4').remove();
                    $('.modal-corpo').append(dado);

                }

                execAjax1(type,url_action,dados,success);
            }

            function consultaDescFaixas2(e){
                    
                var indicador = $(e).parent().parent().find('._id_indicador').val();
                var detalhe = $(e).parent().parent().find('._id_detalhe_item').val();

                var url_action = '/_25600/descfaixas';
                var dados = { 'INDICADOR':indicador,'IDDETALHE':detalhe, 'CLASS':'selecionar-valor'};
                var type = "POST";

                function success(dado){
                    $('.ui-keyboard.ui-widget-content.ui-widget.ui-corner-all.ui-helper-clearfix.ui-keyboard-has-focus').css('display','none');
                    
                    $('.tabela-4').remove();
                    $('.area-texto-plano').remove();
                    $('.textArea-plano-acao').empty();
                    $('.textArea-plano-acao').html('<div class="panel-heading area-texto-plano"><h3 class="panel-title">O QUE?</h3></div><textarea rows="7" class="area-ob-indicador"></textarea>');
                    $('.textArea-plano-acao').append(dado);
                    
                    $('#modal-editar').on('shown.bs.modal', function () {
                        $('.area-ob-indicador').focus();
                    });
                    
                    $( ".ativar-modal" ).trigger( "click" );
                }

                execAjax1(type,url_action,dados,success);
            }

            function consultaDescFaixa(e){
                var valor = $(e).find('.imput-editar-valor').val();
                var indicador = $(e).find('._id_indicador').val();
                var detalhe = $(e).find('._id_detalhe_item').val();

                var url_action = '/_25600/descfaixa';
                var dados = {'VALOR':valor,'INDICADOR':indicador,'IDDETALHE':detalhe};
                var type = "POST";

                function success(dado){

                    $('.tabela-4').remove();

                     $('#modal-editar').on('shown.bs.modal', function () {
                         if(validar === 0){

                            if ( dado.length > 5){
                                $('.textArea-plano-acao').empty();
                                $('.textArea-plano-acao').html('<div class="panel-heading area-texto-plano"><h3 class="panel-title">O QUE?</h3></div><textarea rows="7" class="area-ob-indicador  area-texto-plano"></textarea>');
                                $('.area-ob-indicador').focus();
                                $('.area-ob-indicador').val(dado);

                            }else{
                                $('.textArea-plano-acao').html('<div class="panel-heading area-texto-plano"><h3 class="panel-title">O QUE?</h3></div><textarea rows="7" class="area-ob-indicador"></textarea>');
                                $('.area-ob-indicador').focus();
                            }
                        }
                    });

                }

                execAjax1(type,url_action,dados,success);
            }
            

            function validarValor(e){
                itemEmValidacao = e;
                proximoEmValidacao = $(e).parent().parent().next().find('.validar-valor');
                if (itemEmValidacao === proximoEmValidacao){proximoEmValidacao = $('.js-gravar');};

                var valor = Number($(e).val());
                var max = Number($(e).attr('max'));
                var min = Number($(e).attr('min'));

                var Faixa1_a = Number($(e).attr('A1'));
                var Faixa1_b = Number($(e).attr('A2'));

                var statusErro = 0;

                if (valor > max){
                    if(validar === 0){showAlert('O VALOR MÁXIMO PERMITIDO É '+max);}
                    $(itemEmValidacao).val('');
                    $(itemEmValidacao).focus();
                    statusErro=1;
                };

                if (valor < min){
                    if(validar === 0){showAlert('O VALOR MÍNIMO PERMITIDO É '+min);}
                    $(itemEmValidacao).val('');
                    $(itemEmValidacao).focus();
                    statusErro=1;
                };

                if(statusErro === 0){

                    if ((valor <= Faixa1_a) & (valor >= Faixa1_b)){

                        $(itemEmValidacao).attr('addplano','0');
                        $(itemEmValidacao).attr('plano','');

                        $(e).parent().parent().find('.Square-Color').addClass('verde').removeClass('vermelho');
                        $( ".close" ).trigger( "click" );

                        proximoEmValidacao.focus();

                    }else{
                        
                        if(validar === 0){
                            $(e).parent().parent().find('.Square-Color').addClass('vermelho').removeClass('verde'); 
                            if(validar === 0){showAlert('Este item recebeu uma pontuação baixa e deverá ter uma descrição no campo "O QUE?".');}
                            $( ".ativar-modal" ).trigger( "click" );

                            $('.textArea-plano-acao').html('');
                            var obj = $(e).parent().parent();
                            consultaDescFaixa(obj);
                        }
                    }

                }else{
                    $(itemEmValidacao).val('').focus();
                }
            }
        }

        function AtualizarTela(e) {
                $('.corpo-tabela-1').html('<tr><td cellspacing="4" colspan="4"> <div class="tabela-vazia"> Sem registros </div> </td></tr>');
                $('.corpo-tabela-2').html('<tr><td cellspacing="4" colspan="5"> <div class="tabela-vazia"> Sem registros </div> </td></tr>');
        }

        function showNotaModal(e) {

          var id = $(e).attr('indid');
          var item = $(e).parent().parent().find('td');
          var id = $(e).parent().parent().find('._id_item').val();
          var detalhe = $(e).parent().parent().find('._id_detalhe').val();
          var itemText = '';
          var IDINDICADOR = $(e).parent().parent().find('._id_indicador_').val();

          $('.item-em-edicao').removeClass('item-em-edicao');
          $('.item-em-edicao-cor').removeClass('item-em-edicao-cor');

          $(item[3]).addClass('item-em-edicao');

          $(item[0]).find('.Square-Color').addClass('item-em-edicao-cor');  
          var va = $(item[3]).find('input').val(); 

          itemText = itemText+'<td>'+$(item[0]).html()+'</td>';
          itemText = itemText+'<td>'+$(item[1]).html()+'</td>';
          itemText = itemText+'<td class="limit-width linha-pontilhada">'+$(item[2]).html()+'</td>';
          itemText = itemText+'<td>'+va+'</td>';
          itemText = itemText+'<td class="linha-editar"><input type="text" size="7" class="form-control imput-editar qtd mask-numero" type="number" min="1" decimal="4" autofocus> </td> ';
          itemText = itemText+' <input type="hidden" class="_id_item_indicador" name="_id_item_indicador" value='+id+'>';
          itemText = itemText+' <input type="hidden" class="_exec_plano" name="_exec_plano" value=0 >';
          itemText = itemText+' <input type="hidden" class="_valor_anteior" name="_valor_anteior" value='+va+'>';
          itemText = itemText+' <input type="hidden" class="_id_detalhe_item" name="_id_detalhe_item" value='+detalhe+'>';
          itemText = itemText+' <input type="hidden" class="_id_indicador_edit" name="_id_indicador_edit" value='+IDINDICADOR+'>';

          var html1 = '';
          var html2 = '';
            html1 = html1+'<table class="table table-bordered table-striped table-hover tabulado1 table-selectable">';
            html1 = html1+'    <thead>';
            html1 = html1+'        <tr>';
            html1 = html1+'           <th class="coll-flag"></th>';
            html1 = html1+'           <th class="coll-sequncia">Seq.</th>';
            html1 = html1+'           <th class="coll-descricao">Descrição</th>';
            html1 = html1+'           <th class="coll-valor">Valor</th>';
            html1 = html1+'           <th class="coll-editar"></th>';
            html1 = html1+'        </tr>';
            html1 = html1+'    </thead>';
            html1 = html1+'    <tbody>';
            html2 = '</tbody></table>';

          $('.modal-corpo').html(html1+itemText+html2);

          $('#modal-editar').on('shown.bs.modal', function () {
            abilitarTeclado();
            $('.imput-editar').focus();
          });

        }

        function consultarNotas(obj){

            var id = $(obj).attr('indid');  
            var url_action = '/_25600/consultarRegistro';
            var dados = {'id': id};
            var type = "POST";

            if (id > 0){

                function success(retorno){
                  $('.corpo-tabela-2').html(retorno);
                }

                execAjax1(type,url_action,dados,success);
            } 

        }

        function filtrarIndicador() {

            var CCUSTO = $('._ccustoindicador_id').val();
            var DATAS = $('.data-filtro').val();
            var url_action = '/_25600/filtrar';
            var dados = {'CCUSTO': CCUSTO, 'DATA': DATAS};
            var type = "POST";

            function success(retorno){
              $('.corpo-tabela-1').html(retorno);
              $('.corpo-tabela-2').html('<tr><td cellspacing="4" colspan="5"> <div class="tabela-vazia"> Sem registros </div> </td></tr>');
            }

            execAjax1(type,url_action,dados,success);

        }

        function alterarNota(e) {
            var id = $('._id_item_indicador').val();
            var valor = $('.imput-editar').val();
            var indicador = $('._id_indicador').val();
            var plano = $('._exec_plano').val();
            var valorAnterior = $('._valor_anteior').val();
            var detalhe = $('._id_detalhe_item').val();
            var descPlano = $('.textArea-plano-acao').val();
            var idIndicador = $('._id_indicador_').val();

            if ( valorAnterior != valor ){

                var url_action = '/_25600/alterarNota';
                var dados = {'ID': id, 'VALOR':valor, 'INDICADOR':indicador, 'PLANO':plano,'IDDETALHE':detalhe,'DESCPLANO':descPlano, 'IDINDICADOR':idIndicador};
                var type = "POST";

                function success(retorno){

                  if(retorno['PLANO'] == 0){

                    $( ".fechar-modal" ).trigger( "click" );  
                    showSuccess('GRAVADO COM SUCESSO!');  
                    $('.item-em-edicao').find('input').val(valor);
                    $('.item-em-edicao').removeClass('item-em-edicao');

                    $('.item-em-edicao-cor').removeClass('verde');
                    $('.item-em-edicao-cor').removeClass('vermelho');
                    $('.item-em-edicao-cor').addClass(retorno['DESC']);
                    $('.item-em-edicao-cor').removeClass('item-em-edicao-cor');

                    mediaValores(e);
                  }else{
                    $('.tabela-4').remove();
                    $('.area-plano-acao').remove();

                    //$('.imput-editar').attr('readonly', true);
                    $('.imput-editar').attr('disabled', true);

                    if (retorno['PLANO'] == 1){  
                       showAlert('Este item recebeu uma pontuação baixa e deverá ter uma descrição no campo "O QUE?".');
                    }else{
                       showAlert('Este item está na faixa "NÃO APLICÁVEL", e recebeu uma descrição no campo "O QUE?".'); 
                    }
                    var textoPlanoDeAcao = '';

                    textoPlanoDeAcao = textoPlanoDeAcao+'<div class="panel panel-primary orcamento area-plano-acao">';
                    textoPlanoDeAcao = textoPlanoDeAcao+'  <div class="panel-heading">';
                    textoPlanoDeAcao = textoPlanoDeAcao+'      <h3 class="panel-title">Registrar plano de ação</h3>';
                    textoPlanoDeAcao = textoPlanoDeAcao+('  </div>');
                    textoPlanoDeAcao = textoPlanoDeAcao+'  <textarea class="textArea-plano-acao" rows="4">';
                    textoPlanoDeAcao = textoPlanoDeAcao+'</textarea>';
                    textoPlanoDeAcao = textoPlanoDeAcao+'</div>';

                    $('.modal-corpo').append(textoPlanoDeAcao);


                    $('._exec_plano').val(1);
                    $('.textArea-plano-acao').focus();
                    $('.textArea-plano-acao').html(retorno['DESC']);

                  }

                }

                function error(xhr){
                  $('.item-em-edicao').removeClass('item-em-edicao');
                  $('.item-em-edicao-cor').removeClass('item-em-edicao-cor');
                }

                execAjax1(type,url_action,dados,success,error);
            }else{ 
               showAlert('Valor igual ao anterior');
               $('.imput-editar').focus();
            }

        }

        {
            var editAtivo;

            function isMobile()
            {
                var userAgent = navigator.userAgent.toLowerCase();
                if( userAgent.search(/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i)!= -1 )
                    return true;
            }

            $.keyboard.keyaction.panic = function(base) {
               validar = 1;     
              
                base.reveal();

               consultaDescFaixas2(editAtivo);
               
            };
            
            $.keyboard.keyaction.pani = function(base) {
               $( ".btn-more-info" ).trigger( "click" );
               $('.ui-keyboard.ui-widget-content.ui-widget.ui-corner-all.ui-helper-clearfix.ui-keyboard-has-focus').css('display','none');
                    
            };


            function abilitarTeclado(){

                if(isMobile()){
                //if(true){    

                    jQuery('.keyboard-numeric').keyboard({
                        lockInput: true,
                        layout: 'num',
                        restrictInput : true,
                        preventPaste : true,
                        autoAccept : true
                    });

                    jQuery('.keyboard-numeric2').keyboard({
                        lockInput: true,
                        layout: 'custom',
                        customLayout: {
                            'default' : [
                                '9 . -',
                                '6 7 8',
                                '3 4 5',
                                '0 1 2',
                                '{b} {a} {clear}',
                                '{panic!!}'
                            ]
                        },
                        restrictInput : true,
                        preventPaste : true,
                        autoAccept : true,
                        display: {
                            'panic' : 'Descrição'
                        },
                        beforeVisible : function(event, keyboard, el){
                            editAtivo = this;
                        }
                    });
                    
                    jQuery('.keyboard-numeric3').keyboard({
                        lockInput: true,
                        layout: 'custom',
                        customLayout: {
                            'default' : [
                                '9 . -',
                                '6 7 8',
                                '3 4 5',
                                '0 1 2',
                                '{b} {a} {clear}',
                                '{pani!!}'
                            ]
                        },
                        restrictInput : true,
                        preventPaste : true,
                        autoAccept : true,
                        display: {
                            'pani' : 'Descrição'
                        },
                        beforeVisible : function(event, keyboard, el){
                            editAtivo = this;
                        }
                    });
                }
            }
        }

        function adicionaValor(e){
           var nota = $(e).attr('valor');
           $('.imput-editar').val(nota);
           $( ".imput-editar" ).trigger( "change" );
        }

        function revalidarNota(){
          $('._exec_plano').val(0);
          $('.area-plano-acao').remove();
        }
        
        function selecionarValor(e){
            var desc = $(e).attr('desc');
            var nota = $(e).attr('valor');
            
            $('.area-ob-indicador').val('');
            $('.area-ob-indicador').focus();
            $('.area-ob-indicador').val(desc);
            
            $(editAtivo).val(nota);
            
           $('.adicionar-desc-plano').addClass('valor-na-tabela');
        }
    }
    
(function ($) {

    $('.filtrar-indicador').click(function () {
        filtrarIndicador();
    });
    
    $('.Atualizar-Tela').click(function () {
        AtualizarTela();
    });
    
    $('.Atualizar-Tela').change(function () {
        AtualizarTela();
    });
    
    $(document).on('click','.editar-nota', function(e) {
       showNotaModal(this);
    });
    
    $(document).on('click','.corpo-tabela-1 tr', function(e) {
       consultarNotas(this);   
    });
    
    $(document).on('click','.alterar-nota', function(e) {
       alterarNota(e);   
    });
    
    $('.atualiza-lista').click(function () {
        limpaListaIndicador(this);
    });
    
    $('.indicadores-descricao').change(function () {
        consultaListaIndicador(this);
    });
    
    $(document).on('change','input', function(e) {
        if ($(this).hasClass('no-atualiza-lista')){}else{
           consultaListaIndicador(this); 
        }
    });
    
    $(document).on('change','.validar-valor', function(e) {
        validarValor(this);
    });
    
    $(document).on('change','.media-valor', function(e) {
        mediaValores(this);
    });
   
    $(document).on('click','.cancelar-validacao', function(e) {
        cancelarValidacao(this);
    });
    
    $(document).on('click','.adicionar-desc-plano', function(e) {
       adicionarPlano(this);
    });
    
    $(document).on('click','.gravar-notas-indicador', function(e) {
       gravarNotaIndicador();
    });
    
    $(document).on('click','.btn-more-info', function(e) {
       consultaDescFaixas(this);
    });
    
    $(document).on('focus','input', function(e) {
        var obj = $(this).parent().parent();
                
        if($(obj).hasClass('linha-selecionavel')){
            SelecionarLinha(obj);
        }
    });
    
    $(document).on('click','.adiciona-valor', function(e) {
       adicionaValor(this);
    });
    
    $(document).on('click','.selecionar-valor', function(e) {
       selecionarValor(this);
    });

    $(document).on('change','.imput-editar', function(e) {
       revalidarNota(this);
    });
	
	
	/**
	 * Passar entre itens com as teclas up/down.
	 */
	function ativarTabSeta() {

		$(document)
			.on('keydown', '.pesquisa-res ul li a', 'down', function() {
				
				$.tabNext();
				return false;
				
			})
			.on('keydown', '.pesquisa-res ul li a', 'up', function() {
								
				$.tabPrev();
				return false;
			
			})
			.on('keydown', '.ccustoindicador input, .indicadores input, .turno input', 'down', function() {
				
				$.tabNext();
				return false;
				
			});
	}
	
	$(function() {
		
		ativarTabSeta();
		
	});    
    
            
})(jQuery);    