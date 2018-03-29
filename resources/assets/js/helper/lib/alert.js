/**
 * Script para mensagens de alerta.
 */

/**
 * Alerta para mensagem de erro.
 * @param {string} xhr Mensagem de erro. XHR para erros emitidos pelo Ajax e string para mensagem personalizada.
 */

String.prototype.stripHTML = function() {return this.replace(/<.*?>/g, '');}

function showErro(xhr){

	var msg = xhr.responseText;
	
	if ( typeof msg !== 'undefined' ) {
		
		msg = msg.substring(msg.indexOf('<div class="msg-erro">') );
		msg = msg.substring(0,msg.indexOf('</div>'));

		//tratamento para para exception gerada a partir de trigger
		var excecao = msg.match(/exception 1 ...(.*) At (.*)/i);

		if ( excecao ) {
			msg = excecao['1'];
		}
		
	}
	else {
		
		msg = xhr;
		
	}
    
    
    addBalao(msg,'danger');
	
//	$('.alert-principal')
//		.removeClass('alert-success')
//		.removeClass('alert-warning')
//		.addClass('alert-danger');
//
//	$('.alert-principal .texto')
//		.html(msg)
//		.parent()
//		.slideDown();
//
//	ativarFecharAlert();
	
}

/**
 * Alerta para mensagem de sucesso.
 * @param {string} msg
 */
function showSuccess(msg){
    addBalao(msg,'success');
//	$('.alert-principal')
//		.addClass('alert-success')
//		.removeClass('alert-danger')
//		.removeClass('alert-warning');
//
//	$('.alert-principal .texto')
//		.html(msg)
//		.parent()
//		.slideDown();
//
//	ativarFecharAlert();

}

$(document).on('click', '.balao-fechar',function(){
    $(this).closest('.balao').slideUp(100, function() {$(this).remove();});
});

/**
 * Alerta para mensagem de sucesso.
 * @param {string} msg
 */
function addBalao(msg,tipo){
    
    var icon = 'default';
    switch(tipo) {
        case 'success':
            icon = 'check';
            break;
        case 'danger':
            icon = 'times';
            break;
        case 'warning':
            icon = 'exclamation';
            break;
    }
    
    var tipo = 'alert-' + (tipo || 'default');


    var class_hash = 'class-' + moment().format('YYYYMMDDHHmmssms') + Math.floor(Math.random() * 500) + 1;;
    
    var balao = 
        '<div class="balao ' + class_hash + ' ' + tipo + '" style="display: none">'+
            '<div class="marca-fixo" title="Este balão est fixo, e só ira sumir se for fechado"></div>'+
            '<div class="balao-fechar"><span title="Fechar" class="fa fa-close"></span></div>'+
            '<div class="balao-fixar" ><span title="Fixar" class="glyphicon glyphicon-pushpin"></span></div>'+
            '<div class="body">'+
                msg +
            '</div>'+
        '</div>';

    $('.baloes').prepend(balao);
    setTimeout(function(){
        $('.'+class_hash).slideDown();
    },100);
    
    setTimeout(function(){
        if(!$('.'+class_hash).hasClass('balao-fixo')){ 
            $('.'+class_hash).slideUp(100, function() {$(this).remove();});
        }
    },7000);

}

/**
 * Alerta para mensagem de sucesso.
 * @param {string} msg
 */
function addNotificacao(msg,titulo,id, flag, agd_id){
    
    var icon = 'default';
    switch(tipo) {
        case 'success':
            icon = 'check';
            break;
        case 'danger':
            icon = 'times';
            break;
        case 'warning':
            icon = 'exclamation';
            break;
    }
    
    var tipo = 'alert-' + (tipo || 'default');
    var class_hash = '';

    class_hash = id;

    var agd = '';

    if(agd_id > 0){
        agd += '<br><br>';
        agd += '<form>';
        agd += '  Adiar esta notificação:<br>';
        agd += '  <select class="agd-id-'+agd_id+'">';
        agd += '    <option value="5"    >5 Minutos</option>';
        agd += '    <option value="10"   >10 Minutos</option>';
        agd += '    <option value="30"   >30 Minutos</option>';
        agd += '    <option value="60"   >1 hora</option>';
        agd += '    <option value="1440" >1 dia</option>';
        agd += '    <option value="10080">1 semana</option>';
        agd += '    <option value="43200">1 mês</option>';
        agd += '  </select>';
        agd += '  <input type="button" class="btn-agendar-notificacao" data-id="'+agd_id+'" value="Adiar">';
        agd += '</form>';
    }
    
    var balao = 
        '<div class="balao ' + class_hash + ' pop-notificacao" data-id="'+class_hash+'" style="display: none">'+
            '<div class="marca-fixo" title="Este balão est fixo, e só ira sumir se for fechado"></div>'+
            '<div data-id="'+class_hash+'" class="balao-fechar '+class_hash+'-fechar"><span title="Fechar" class="fa fa-close"></span></div>'+
            '<div class="body">'+
            '<label>'+titulo+'</label>'+
                msg + agd +
            '</div>'
        '</div>';

    $('.baloes').prepend(balao);


    var msgs = window.localStorage.getItem('MSG');

    if(msgs == null){
        msgs = [];
    }else{
        msgs = JSON.parse(msgs);    
    }

    var validar = 0;
    msgs.forEach(function(iten, index){
        if(iten.ID == id){
            validar = 1;   
        }
    });

    if(validar == 0){
        msgs.push({MSG: msg, TITULO: titulo, ID: class_hash, AGENDAMENTO_ID: agd_id});
        window.localStorage.setItem('MSG', JSON.stringify(msgs));

        Notification.requestPermission(function() {

            var notification = new Notification(
                titulo, 
                {
                    icon: '../../assets/images/logo2.png',
                    body: msg.stripHTML(),
                    tag : class_hash
                }
            );

            notification.onclick = function() {
                window.focus();
                notification.close();
            };

        });
    }

    setTimeout(function(){
        $('.'+class_hash).slideDown();
        $('.'+class_hash+'-fechar').on('click',function(e){
            var id = $(this).data('id');
            
                var msgs = window.localStorage.getItem('MSG');

                if(msgs == null){
                    msgs = [];
                }else{
                    msgs = JSON.parse(msgs);    
                }

                msgs.forEach(function(iten, index){

                    if(iten.ID == id){
                         msgs.splice(index,1);    
                    }
                });

                window.localStorage.setItem('MSG', JSON.stringify(msgs));

        });
    },100);


    
    /*
    setTimeout(function(){
        if(!$('.'+class_hash).hasClass('balao-fixo')){ 
            $('.'+class_hash).slideUp(100, function() {$(this).remove();});
        }
    },7000);
    */
}

/**
 * Alerta para mensagem de atenção.
 * @param {string} msg
 */
function showAlert(msg){

    addBalao(msg,'warning');
    
//	$('.alert-principal')
//		.addClass('alert-warning')
//		.removeClass('alert-danger')
//		.removeClass('alert-success');
//
//	$('.alert-principal .texto')
//		.html(msg)
//		.parent()
//		.slideDown();
//
//	ativarFecharAlert();

}

/**
 * Fechar o alerta após determinado tempo 
 * e se não estiver com o mouse por cima.
 */
function ativarFecharAlert() {

   var time = 0;

   /**
	* Fechar o alerta.
	* @param {object} alert
	*/
   function fechar(alert) {

	   $(alert)
		   .slideUp('medium', function() {

			   $(this)
				   .children('.texto')
				   .empty()
				;
				
				//remover title do botão copiar
				$(this)
					.find('.btn-clipboard')
					.removeAttr('title')
				;
				
		   });
   }

   /**
	* Ativar evento para fechar após determinado tempo
	* ou ao clicar.
	*/
   function eventoFechar() {

	   time = 0;

	   //fechar após determinado tempo
	   time = setTimeout(function() {

		   fechar( $('.alert-principal') );

	   }, 5000);

   }

   /**
	* Iniciar ações para fechar o alerta.
	*/
   function iniciarFechar() {

	   //não permite fechar quando estiver com mouse por cima
	   $('.alert-principal:visible')
		   .hover(function() {

			   clearTimeout(time);

		   }, function() {

			   eventoFechar();

		   });

	   //ativar contagem quando o alert estiver visível
	   if ( $('.alert-principal').is(':visible') ) {

		   eventoFechar();

	   }
	   
	   //fechar com clique
	   $('.alert .fechar')
		   .click(function() {

			   fechar( $(this).closest('.alert') );

		   });

   }

   iniciarFechar();

}

/**
 * Barra de progresso
 * @param {type} valor_atual
 * @param {type} valor_maximo
 */
function progressBar(valor_atual,valor_maximo) {
    
    var div_block = $('.carregando-pagina');
    var barra     = $('.progress-bar');
    var arialmax  = barra.attr('aria-valuemax');
    
    var show = function() {
        barra.attr('aria-valuemax',valor_maximo);
        arialmax = barra.attr('aria-valuemax');
        div_block.show();
    };
    var hide = function() {
        div_block.hide();
        barra.width(0);
        barra.attr('aria-valuemax',0);
    };
    
    if ( valor_maximo > 0 ) {
        show();
    }
    
    if ( (valor_atual == 0 && !valor_maximo) ){ 
        hide();
    }
    
    if ( valor_atual > 0 && arialmax > 0 ) {
        var percentual = ((valor_atual / arialmax)*100).toFixed(2) + '%';
               
        barra
            .width(percentual)
            .on('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd', function() {
                if ( percentual == '100.00%' ) {
                    hide();
                }
            })
        ;
    }
}

(function($) {

    $(document).on('click', '.balao-fixar', function(event) {
        //$(this).parent().find('.marca-fixo').css('display','block');
        $(this).parent().addClass('balao-fixo');
        //$(this).find('span').removeClass('glyphicon-pushpin');
        //$(this).find('span').addClass('glyphicon-info-sign');
        $(this).find('span').attr({'title': 'Este balão está fixo, e só ira sumir se for fechado'});('glyphicon-info-sign');
        $(this).css('opacity','0.4');
    });

	$(function() {
		
		ativarFecharAlert();

        var msgs = window.localStorage.getItem('MSG');

        if(msgs == null){
            msgs = [];
        }else{
            msgs = JSON.parse(msgs);    
        }

        msgs.forEach(function(iten, index){
            addNotificacao(iten.MSG, iten.TITULO, iten.ID, 0, iten.AGENDAMENTO_ID); 
        });

        //function storage_event(e){
           // console.log(e);
        //}

        //window.addEventListener('storage', storage_event, false);

        $(window).bind('storage', function (e) {

            var itens = $('.pop-notificacao');
            var msgs = window.localStorage.getItem('MSG');

            if(msgs == null){
                msgs = [];
            }else{
                msgs = JSON.parse(msgs);    
            }

            $(itens).each(function(index,iten){

                var validar = 0;
                var id = $(iten).data('id');

                msgs.forEach(function(a, b){
                    if(a.ID == id){
                        validar = 1;   
                    }
                });

                if(validar == 0){
                    console.log($('.'+id+'-fechar'));
                    $('.'+id+'-fechar').trigger('click');
                }

            });
        });

	});
	
})(jQuery);