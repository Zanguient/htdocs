/**
 * Script para input's.
 */

/**
 * Chama uma função no campo de pesquisa
 * @param function funcaoExecute
 * @returns funcaoExecute
 */
$.fn.keyEnter = function(funcaoExecute) {

   $(this)
	   .nextAll('button[type="button"]')
	   .click(function() {
		   funcaoExecute();
	   })
   ;

   $(this)
	   .bind('keydown', 'return', function() {
		   funcaoExecute();
	   })
   ;
   
};

/**
 * Função para serializar filhos de um elemento<br/>
 * Exemplo de uso: $(this).srlz();<br/>
 * Elementos no html deverão ter o seguinte formato:<br/>
 * &lt;input srlz-name="nome_input" srlz-value="1" /&gt;
 * @returns serialize()
 */
$.fn.srlz = function() {
	var input_group = $(this);    
	var str_srlz = '';
	var x = 0;

	input_group.find('input[srlz-name]').each(function(){

		if  (x) str_srlz += '&';
		else x = 1;

		str_srlz += $(this).attr('srlz-name') + '=' + $(this).attr('srlz-value');
	});

	return str_srlz;
};

/**
 * Transformar valores digitados nos campos em UpperCase.
 */
function inputUppercase() {

	$(document)
		.on('input', 
			'input[type="text"]:not(.normal-case), input[type="search"]:not(.normal-case), textarea:not(.normal-case)',
			function(e) {
                var ss = e.target.selectionStart;
                var se = e.target.selectionEnd;
                e.target.value = e.target.value.toUpperCase();
                e.target.selectionStart = ss;
                e.target.selectionEnd = se;
            }
		);
}

function tabTextarea() {

    $(document).on('keydown','textarea',function(e) {

    	if ($(this).data('tabSpace')) {

	        if(e.keyCode === 9) { // tab was pressed
	            // get caret position/selection
	            var start = this.selectionStart;
	            var end = this.selectionEnd;

	            var $this = $(this);
	            var value = $this.val();

	            // set textarea value to: text before caret + tab + text after caret
	            $this.val(value.substring(0, start)
	                        + "\t"
	                        + value.substring(end));

	            // put caret at right position again (add one for the tab)
	            this.selectionStart = this.selectionEnd = start + 1;

	            // prevent the focus lose
	            e.preventDefault();
	        }
	    }
    });
}
tabTextarea();

/**
 * Limitar caracteres digitados em textarea.
 * 
 * @param {textarea} textarea
 * @param {int} limite
 * @param {int} contador
 */
function limiteTextarea(textarea, limite, contador) {

	/**
	 * Verifica se a quantidade de caracteres digitados ultrapassou o limite.
	 * 
	 * @param {textarea} textarea
	 * @param {int} limite
	 * @param {int} contador
	 */
	function verificaLimite(textarea, limite, contador) {

		var tamanho = $(textarea).val().length;
		if(tamanho > limite)
			tamanho -= 1;

		$(contador).text(limite - tamanho);

		if(tamanho >= limite) {
			var txt = $(textarea).val().substring(0, limite);
			$(textarea).val(txt);
		}
	}

	/**
	 * Ativar eventos para o limite de textarea.
	 */
	function eventoLimiteTextarea() {

		if( $(textarea).length === 0 )
			return false;

		$(contador).text(limite);

		verificaLimite( textarea, limite, contador );

		$(textarea).on('input propertychange', function() {
			verificaLimite( textarea, limite, contador );
		});

	}

	eventoLimiteTextarea();

}


/**
 * Pesquisa somente se for pressionado caractere, espaço, delete ou backspace.
 * 
 * @param {int} keyCode
 * @returns {Boolean}
 */
function verifTeclaFiltro(keyCode) {

   if( (keyCode >= 48 && keyCode <= 57) || 
	   (keyCode >= 65 && keyCode <= 122) || 
	   (keyCode >= 193 && keyCode <= 255) || 
	   (keyCode === 8) || 
	   (keyCode === 32) || 
	   (keyCode === 46) 
   ) {
	   return true;
   }
   else {
	   return false;
   }
}

/**
 * Pegar a posição da digitação dentro do input.
 * 
 * @param {input} ctrl
 * @returns {Number|document.selection@call;createRange.text.length|ctrl.selectionStart|Sel.text.length}
 */
function doGetCaretPosition (ctrl) {
	
	var CaretPos = 0;   // IE Support
		if (document.selection) {
		ctrl.focus ();
		var Sel = document.selection.createRange ();
		Sel.moveStart ('character', -ctrl.value.length);
		CaretPos = Sel.text.length;
	}
	// Firefox support
	else if (ctrl.selectionStart || ctrl.selectionStart === '0')
	CaretPos = ctrl.selectionStart;
	return (CaretPos);
	
}

/**
 * Definir a posição da digitação dentro do input.
 * 
 * @param {input} ctrl
 * @param {int} pos
 */
function setCaretPosition(ctrl, pos){
	
	if(ctrl.setSelectionRange)
	{
		ctrl.focus();
		ctrl.setSelectionRange(pos,pos);
	}else if (ctrl.createTextRange)
	{
		var range = ctrl.createTextRange();
		range.collapse(true);
		range.moveEnd('character', pos);
		range.moveStart('character', pos);
		range.select();
	}
	
}

function tabaFilter(e){
    var flag = $('.btn-filtro-add').attr('flag');
    
    if(flag == '0'){
        $('.more-filter').show();
        $('.btn-filtro-add').attr('flag',1);
        $('.btn-filtro-add').find('.glyphicon').removeClass('glyphicon-triangle-bottom');
        $('.btn-filtro-add').find('.glyphicon').addClass('glyphicon-triangle-top');
        
    }else{
        $('.more-filter').hide();
        $('.btn-filtro-add').attr('flag',0);
        $('.btn-filtro-add').find('.glyphicon').removeClass('glyphicon-triangle-top');
        $('.btn-filtro-add').find('.glyphicon').addClass('glyphicon-triangle-bottom');
    }
    
}

function checitem(e){
   var status = $(e).attr('status');
   
   if(status == 1){
        $(e).find('.mark-chec').css('color','white');
        $(e).attr('status',0);
   }else{
        $(e).find('.mark-chec').css('color','black');
        $(e).attr('status',1);
   }
}

function expert(e){
    $('.item-chec').addClass('expert-chec'); 
}

/**
 * Permitir somente números no input, com exceção de:
 * backspace, delete, tab, escape, enter, ',', Ctrl+A, Command+A, home, end, left, right, down, up
 */
function onlyNumber() {
	
	$(document)
		.on('keydown', 'input[type="number"]', function(e) {
		
			// Allow: backspace, delete, tab, escape, enter and ','
			if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 188]) !== -1 ||
				 // Allow: Ctrl+A, Command+A
				(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
				 // Allow: home, end, left, right, down, up
				(e.keyCode >= 35 && e.keyCode <= 40)) {
					 // let it happen, don't do anything
					 return;
			}
			// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}

		})
	;
	
}

(function($) {
    
	$(function() {
		inputUppercase();
		onlyNumber();
	});
    
    $(document).on('click','.btn-filtro-add', function(e) {
        tabaFilter(this);
    });
    
    $(document).on('click','.item-chec', function(e) {
        checitem(this);
    });
    
    $(document).on('click','.filter-expert', function(e) {
        expert(this);
    }); 
    
})(jQuery);