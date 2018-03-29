
/**
 * Variável que identifica se o popUp está visível
 * @type Boolean
 */
var popUpShowing = false;
var popUpSelector;

/**
 * Função para exibir PopUp em tela cheia<br/>
 * @param {array} json <br/>
 * • Campo <b>focus</b>:
 *      <ul>
 *          <li>
 *              <u><i>true</i></u> = focar no elemento emitente ao fechar
 *          </li>
 *          <li>
 *              <u><i>false</i></u> = não focar no elemento emitente ao fechar
 *          </li>
  *          <li>
 *              Default: <u><i>true</i></u>
 *          </li>
 *      </ul>
 * • Campo <b>function</b>:
 *      <ul>
 *          <li>
 *              <u><i>function</i></u> = função a ser executada ao abrir
 *          </li>
  *          <li>
 *              Default: <u><i>null</i></u>
 *          </li>
 *      </ul>
 * @returns void
 */
$.fn.popUp = function(json) {
//    var _this_click 
    var _this = $(this);
    
    json = json || {};
    popUpSelector = _this;
                
    function popUpClose() {

        function close() {
            
            if (json.function_close !== undefined) json.function_close();
        
            $('body').css("overflow","auto");
            $('div.popup')
                .slideUp(
                    600, 
                    function() {
                        if ( json.focus === false ) return false;
                        _this.focus();
                    }
                )                        
            ;
			$('.popup-acoes')
				.removeClass('abrir')
			;
            popUpShowing = false;
        }

        $('.popup-close')
            .click(
                close
            )
        ;
        /*
		$('body')
			.bind(
				'keydown', 
				'esc', 
				function() {
					if ( !$('.alert-principal').is(':visible') ) {
						close();
					}
				}
			)
		;   
		*/
       $('.popup-voltar2')
            .click(
                close
            )
        ;
    }

    function popUpOpen() {
        mostravoltar();
        if (json.function !== undefined) json.function();
        
        $('body').css("overflow","hidden");
        $('div.popup')
            .slideDown(800)
            .find('.modal-body')
            .scrollTop(0)
        ;
        
        popUpClose();
        
        popUpShowing = true;
        
        mostravoltar();
        
    }

    popUpOpen();
};


    function escondevoltar(){
        $('.popup-close').css('visibility','hidden');
        $('.popup-close').attr('oculto','1');
    }

    function mostravoltar(){
        $('.popup-close').css('visibility','visible');
        $('.popup-close').attr('oculto','0');
    }

    function Modalescondevoltar(class_modal){
        $(class_modal).find('.popup-close').css('visibility','hidden');
        $(class_modal).find('.popup-close').attr('oculto','1');
    }

    function Modalmostravoltar(class_modal){
        $(class_modal).find('.popup-close').css('visibility','visible');
        $(class_modal).find('.popup-close').attr('oculto','0');
    }


$.fn.popUpClose = function(json) {
//    var _this_click 
    var _this = $(this);
    
    json = json || {};
    
    function close() {

        if (json.function !== undefined) json.function();

        $('body').css("overflow","auto");
        $('div.popup')
            .slideUp(
                600, 
                function() {
                    if ( json.focus === false ) return false;
                    _this.focus();
                }
            )                         
        ;
        popUpShowing  = false;
        popUpSelector = null;
    }

    close();
};


//function addConfirme(dados) {
//	
//    var popup = '';
////    popup = popup + '<div class="pop-confirm">';
////    popup = popup + '<div class="topo">'; 
////    popup = popup + dados.inter;
////    popup = popup + '</div>';    
////    popup = popup + '<div class="rodape">';
////    popup = popup + dados.botao;
////    popup = popup + '</div>';
////    popup = popup + '</div>';
////    
////    var obj = $('.pop-confirm');
//
//	popup += '<div class="modal fade" tabindex="-1" role="dialog">';
//	popup += '<div class="modal-dialog" role="document">';
//	popup += '<div class="modal-content">';
//	popup += '<div class="modal-header">';
//	popup += dados.inter;
//	popup += '</div>';
//	popup += '<div class="modal-body">';
//	popup += '</div>';
//	popup += '<div class="modal-footer">';
//	popup += dados.botao;
//	popup += '</div>';
//	popup += '</div>';
//	popup += '</div>';
//	popup += '</div>';
//    
//    //if(typeof obj === undefined){
//        $(document).append(popup);
//    //} 
//}

var btn_ok = 1;
var btn_no = 2;
var btn_ca = 2;

function getBtn(btn,action,clases){
  var ret = '';
  
  switch(btn) {
    case 1: ret = '<button type="button" class="btn btn-primary btn-confirm-action" ret="1" action="'+action+'" clases="'+clases+'" data-hotkey="f1"><span class="glyphicon glyphicon-ok"></span> Sim</button>'; break;
    case 2: ret = '<button type="button" class="btn btn-danger  btn-confirm-action" ret="2" action="'+action+'" clases="'+clases+'" data-hotkey="f2"><span class="glyphicon glyphicon-ban-circle"></span> Não</button>'; break;
    case 3: ret = '<button type="button" class="btn btn-danger  btn-confirm-action" ret="3" action="'+action+'" clases="'+clases+'" data-hotkey="f3"><span class="glyphicon glyphicon-ban-circle"></span> Cancelar</button>'; break;
    default:
        ret = '';
  }
  
  return ret;
  
}

function comfirme(pergunta,botao,action,clases){
    var botoes = '';
    
    botao.forEach(function(btn){
        botoes = getBtn(btn,action,clases);
    });
    
    addConfirme({
		inter : pergunta,
		botao : botoes
    });
}



/**
 * Confirm
 */
var function_agendada_confirme;
var alto_fechar = false;
    
var obtn_sim         = {desc:'Sim'       ,class:'btn-primary btn-confirm-sim' ,ret:'1' ,hotkey:'enter',glyphicon:'glyphicon-ok'};
var obtn_ok          = {desc:'Confirmar' ,class:'btn-primary btn-confirm-ok ' ,ret:'1' ,hotkey:'enter',glyphicon:'glyphicon-ok'};
var obtn_nao         = {desc:'Não'       ,class:'btn-danger  btn-confirm-nao btn-voltar'  ,ret:'2' ,hotkey:'esc',glyphicon:'glyphicon-ban-circle'};
var obtn_cancelar    = {desc:'Cancelar'  ,class:'btn-danger  btn-confirm-can btn-voltar'  ,ret:'2' ,hotkey:'esc',glyphicon:'glyphicon-ban-circle'};
var obtn_voltar      = {desc:'Voltar'    ,class:'btn-default btn-confirm-vol btn-voltar' ,ret:'2' ,hotkey:'esc',glyphicon:'glyphicon-chevron-left'};

function Confirme(dados) {
	var popup = '';
	
	popup += '<div class="modal-backdrop confirm fade in" style="z-index: 9998;"></div>';
	popup += '<div class="modal fade in confirm" tabindex="-1" role="dialog" style="display: block;z-index: 9999;">';
	popup += '<div class="modal-dialog" role="document">';
	popup += '<div class="modal-content">';
	popup += '<div class="modal-header">';
	popup += dados.titulo;
	popup += '</div>';
	popup += '<div class="modal-body">';
	popup += dados.inter;
	popup += '</div>';
	popup += '<div class="modal-footer">';
	popup += dados.botao;
	popup += '</div>';
	popup += '</div>';
	popup += '</div>';
	popup += '</div>';

	$('body').append(popup);
    $('.modal.fade.in.confirm').focus();
    
    setTimeout(function(){
        $('.modal.fade.in.confirm').addClass('carregado');
    },500);
}   

function getbutom(btn){
	var ret = '';

	ret = ret + '<button type="button" class="btn '+btn.class+' btn-confirm-action" ret="'+btn.ret+'"';
	ret = ret + 'data-hotkey="'+btn.hotkey+'"><span class="glyphicon '+btn.glyphicon+'"></span> '+btn.desc;
	ret = ret + '</button>';

	return ret;
}

/**
 * Este metodo serve para receber uam confirmação antes da execução de um metodo<br/>
 * <br/>
 * Como Usar:<br/>
 *           pergunta:<br/>
 *              A pergunta deve ser uma string pre formatada;<br/>
 * <br/>
 *           botao:<br/>
 *              Deve ser um array de objetos, cada objeto deve ser composto por<br/>
 *              {desc:'',class:'' ,ret:'' ,hotkey:'',glyphicon:''};<br/>
 *              Obs.: há um grupo de botoes pre definidos (obtn_sim,obtn_ok,obtn_nao,obtn_cancelar,obtn_voltar)<br/>
 * <br/>          
 *          func:<br/>
 *              Pode ser uma unica função que sera executada se o retorno do botao (ret) for 1 ou<br/>
 *              Um array com a objetos contendo a sequinte estrutura [{ret:retorno,func:função},...]<br/>
 *              Se func for um objeto ret: é o retorno esperado para a execução de func<br/>
 * <br/>              
 * <br/>                
 *           Ex:<br/>
 *             addConfirme('Titulo','Deseja realmente excluir este item?',[obtn_sim,obtn_ok,obtn_nao,obtn_cancelar,obtn_voltar,{desc:'TESTE',class:'btn-primary',ret:'8',hotkey:'f1',glyphicon:'glyphicon-ok'}],<br/>
 *                  [<br/>
 *                      {ret:1,func:function(){ alert("Voce afirmou que sim");}},<br/>
 *                      {ret:2,func:function(){ alert("Voce afirmou que não");}},<br/>
 *                      {ret:8,func:function(){ alert("Voce afirmou que teste");}}<br/>
 *                  ]<br/>     
 *             );<br/>  
 * <br/> 
 * <br/> 
 * @param {string} titulo Titulo da mensagem
 * @param {string} pergunta Frase que sera esibida
 * @param {array de objetos} botao Botões que serão exibidos
 * @param {função ou array de função} func função que sera executada
 * @param {string} fechar fechar
 * @returns {undefined}
 */
function addConfirme(titulo,pergunta,botao,func,fechar){
	var botoes = '';
    
	function_agendada_confirme = func;
    
    fechar = fechar ? 'N' : 'S';
    
    if(fechar == 'N'){
        alto_fechar = false;
    }else{
        alto_fechar = true;
    }
    
	botao.reverse();

	for (i = 0; i < botao.length; i++) { 
		var num = botao[i];
		var cod = getbutom(num);
		botoes = botoes + cod;
	}

	Confirme({
		titulo: titulo,
		inter : pergunta,
		botao : botoes
	});
}


/**
 * Abre popup no navegador
 * @param {string} pagina link da página
 * @param {string} idenficador identificador da pagina
 * @param {json} param parametros do popup
 * @returns {void}
 */
function winPopUp(pagina,idenficador,param){
    param             = param             || {}   ;
    param.status      = param.status      || 'no' ;
    param.toolbar     = param.toolbar     || 'no' ;
    param.location    = param.location    || 'no' ;
    param.directories = param.directories || 'no' ;
    param.resisable   = param.resisable   || 'no' ;
    param.scrollbars  = param.scrollbars  || 'no' ;
    param.width       = param.width       || '500';
    param.height      = param.height      || '500';
    param.top         = param.top         || ((screen.height) ? (screen.height - param.height)/2 : 0); 
    param.left        = param.left        || ((screen.width)  ? (screen.width  - param.width )/2 : 0);

    var settings =
        'status      ='+ param.status     +',  '+
        'toolbar     ='+ param.toolbar    +',  '+
        'location    ='+ param.location   +',  '+
        'directories ='+ param.directories+',  '+
        'resisable   ='+ param.resisable  +',  '+
        'scrollbars  ='+ param.scrollbars +',  '+
        'width       ='+ param.width      +',  '+
        'height      ='+ param.height     +',  '+
        'top         ='+ param.top        +',  '+
        'left        ='+ param.left       +'   ';

    return window.open(pagina,idenficador,settings);  
}  

(function($) {
	
	$(function() {
       
		$(document)
			.on('click','.btn-confirm-action', function(e) {
                try {
                    var ret = parseInt($(this).attr('ret'));

                    if(function_agendada_confirme instanceof Array){
                        for (i = 0; i < function_agendada_confirme.length; i++) { 
                            var num = function_agendada_confirme[i];

                            if(ret === num.ret){
                                ret = num.func(e);
                            }
                        }
                    }else{
                        if(ret === 1){
                            ret = function_agendada_confirme(e);
                        }
                    }
                    
                    if(alto_fechar){
                        $(this).closest('.modal.confirm').prev('.modal-backdrop.confirm').remove();
                        $(this).closest('.modal.confirm').remove();
                    }

                    return ret;
                } catch(err) {
                    showErro(err.message);
                }

			})
			.on('click','.modal-backdrop.confirm', function(e) {
           
                var modal_content = $('.modal-content');
				var piscadas = 0;

				var pisca =  setInterval(function() {

						   if($(modal_content).css("opacity") < 1){
							   $(modal_content).css("opacity","1");
						   }else{
							   $(modal_content).css("opacity","0.5");
						   }

						   if(piscadas > 5){
							   clearInterval(pisca);
							   $(modal_content).css("opacity","1");
						   }

						   piscadas++;
				}, 100);
			})
		;
		
	});
	
})(jQuery);