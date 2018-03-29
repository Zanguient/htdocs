/**
 * Função para repetir a função (promise) passada no parâmetro enquanto ela lançar uma exception.
 * @param {promise} operation
 * @returns {promise}
 */
function promiseRetry(operation) {
										
	return operation
			.catch(function() {
				return promiseRetry.bind(null, operation);
			})
	;

}

function isMobile()
{
	var userAgent = navigator.userAgent.toLowerCase();
	if( userAgent.search(/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i)!= -1 )
		return true;
}

/**
 * Pegar parâmetros da URL.
 * Função idêntica ao $_GET do PHP.
 * @param {string} sParam Nome do parâmetro
 */
function getURLParameter(sParam) {

    var sPageURL 	  = window.location.search.substring(1),
    	sURLVariables = sPageURL.split('&');

    for (var i = 0; i < sURLVariables.length; i++) {

        var sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] == sParam)
            return sParameterName[1];
    }
}

(function($) {
	
	/**
	 * Ativar clipboard.
	 */
	function clipboardAtivar() {
		
		var cb		 = new Clipboard('.btn-clipboard');
		var resposta = '';
		
		$('.btn-clipboard').click(function() {
			resposta = $(this).data('clipboard-response');
		});
			
		cb.on('success', function(event) {
			event.trigger.title = resposta;
		});
		
	}
	
	$(function() {
		
		clipboardAtivar();
		
	});
	
})(jQuery);


/*
 * object.watch polyfill
 *
 * 2012-04-03
 *
 * By Eli Grey, http://eligrey.com
 * Public Domain.
 * NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
 */

// object.watch
if (!Object.prototype.watch) {
	Object.defineProperty(Object.prototype, "watch", {
		  enumerable: false
		, configurable: true
		, writable: false
		, value: function (prop, handler) {
			var
			  oldval = this[prop]
			, newval = oldval
			, getter = function () {
				return newval;
			}
			, setter = function (val) {
				oldval = newval;
				return newval = handler.call(this, prop, oldval, val);
			}
			;
			
			if (delete this[prop]) { // can't watch constants
				Object.defineProperty(this, prop, {
					  get: getter
					, set: setter
					, enumerable: true
					, configurable: true
				});
			}
		}
	});
}

// object.unwatch
if (!Object.prototype.unwatch) {
	Object.defineProperty(Object.prototype, "unwatch", {
		  enumerable: false
		, configurable: true
		, writable: false
		, value: function (prop) {
			var val = this[prop];
			delete this[prop]; // remove accessors
			this[prop] = val;
		}
	});
}

function isEmpty(obj) {
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            return false;
    }

    return JSON.stringify(obj) === JSON.stringify({});
}

/**
 * Busca em um array uma propriedade com um valor
 * Se a propriedade não existir, o retorno será -1
 * Se a propriedade existe, o retorno será o item
 */
indexOfAttr = function(array,attr, value) {
    for(var i in array) {
        if(array[i][attr] === value) {
            return i;
        }
    }
    return -1;
};

/**
 * Converter um array de objecto em uma lista
 * @param {Array} array Array a ser convertido
 * @param {String} field Campo a ser convertido
 * @param {String} separator Separador; Default: ' ,'
 * @returns {String}
 */
function arrayToList(array,field,separator) {
    try {
        
        if ( !Array.isArray(array) ) {
            throw 'Informe um array no primeiro argumento para transforma-lo em uma lista.';
        }
        
        var separator = separator || ', ';
    
        var ret = '';

        for(var i in array) {
            
            var item     = array[i];
            var property = item[field];
            
            if ( property != undefined ) {
                if ( ret != '' ) {
                    ret += separator + property;
                } else {
                    ret = property;
                }
            }
        } 

        return ret;          
    } catch (e) {
        showErro(e);
    }
    
}

/**
 * Compara objetos pelo campo
 * @param {type} item_main
 * @param {type} item_bind
 * @param {type} field_identifier
 * @returns {Boolean}
 */
function equalsByField(item_main, item_bind, field_identifier) {
    var condicao_true = true;
    if ( typeof field_identifier == 'string' ) {

        if ( field_identifier.indexOf('|') > 0 ) {

            if ( item_main[field_identifier.split('|')[0]] != item_bind[field_identifier.split('|')[1]] ) {
                condicao_true = false;
            }			
        } else {

            if ( item_main[field_identifier] != item_bind[field_identifier] ) {
                condicao_true = false;
            }
        }
    } else {

        if ( Array.isArray(field_identifier) && Array.isArray(field_identifier[0]) ) {

            for ( var k in field_identifier[0] ) {
                var condicao1 = field_identifier[0][k];
                var condicao2 = field_identifier[1][k];

                if ( item_main[condicao1] != item_bind[condicao2] ) {
                    condicao_true = false;
                    break;
                }
            }		

        } else {
            for ( var k in field_identifier ) {
                var condicao = field_identifier[k];

                if ( item_main[condicao] != item_bind[condicao] ) {
                    condicao_true = false;
                    break;
                }
            }
        }
    }	
    return condicao_true;
}

//
$(document).on('click','.btn-cancelar[data-confirm="yes"]', function(e) {
     
    e.preventDefault();
    
    var that = $(this);

    addConfirme(
        '<h4>Confirmação</h4>',
        'Deseja realmente cancelar?',
        [obtn_sim, obtn_nao],
        [{
            ret: 1,
            func: function() {


                if ( $(that).attr('data-modal-close') != undefined ) {
                    $(that).closest('.modal').modal('hide');
                }
                
                $(that).removeAttr('data-confirm');
                if ( $(that).attr('href') ) {
                    window.location = $(that).attr('href');
                } else {
                    $(that).trigger(e);   
                    $(that).attr('data-confirm','yes');  
                }
            }
        },
        {
            ret: 2,
            func: function() {

            }
        }]
    );
});

//var cancel_bf_unload = false;
////
//$(document).on('click','[type="submit"]',function(e) {
//    var form = $(this).closest('form');
//    var action = $(form).attr('action') == undefined ? '' : $(form).attr('action');
//    
//    if ( action != '' ) {
//        cancel_bf_unload = true;
//    }
//});
//
//var bf_load_timeout;
//
//function warning() {
//
//    var btn_cancelar = $(document).find('.btn-cancelar[data-confirm="yes"]:visible');
//    if ( btn_cancelar.length > 0 && cancel_bf_unload == false ) {
//        return 'oi';
//    }
//}
//
//function noTimeout() {
//    clearTimeout(bf_load_timeout);
//}
//
//window.onbeforeunload = warning;
//window.unload = noTimeout;

    
    
function inThePeriod( my_timestamp_start, my_timestamp_end, period_start, period_end, bool ) {

    var my_timestamp_start  = typeof my_timestamp_start == 'string' ? new Date(my_timestamp_start)  : my_timestamp_start;
    var my_timestamp_end    = typeof my_timestamp_end   == 'string' ? new Date(my_timestamp_end)    : my_timestamp_end  ;
    var period_start        = typeof period_start       == 'string' ? new Date(period_start)        : period_start      ;
    var period_end          = typeof period_end         == 'string' ? new Date(period_end)          : period_end        ;
    

    var ret = false;

    if ( my_timestamp_end >= period_start && my_timestamp_start <= period_start && period_end >= my_timestamp_end ) {
    
        ret = bool ? true : 'left';
    }
    else
    if ( my_timestamp_start <= period_end && my_timestamp_start >= period_start && period_end <= my_timestamp_end ) {
    
        ret = bool ? true : 'right';
    }
    else
    if ( my_timestamp_start <= period_start && period_end <= my_timestamp_end ) {
    
        ret = bool ? true : 'outer';
    }
    else
    if ( my_timestamp_start >= period_start && period_end >= my_timestamp_end ) {
    
        ret = bool ? true : 'inner';
    }

    return ret;    
}

function sanitizeJson(response) {
    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    for ( var i in response ) {
        var item = response[i];

        for (var k in item){
            if (item.hasOwnProperty(k)) {

                if ( item[k] === '0' ) {
                    item[k] = 0;
                } else
                if ( isNumber(item[k]) && (String(item[k]).substr(0, 1) !== '0' || String(item[k]).indexOf('.') !== -1) ) {               
                    item[k] = parseFloat(item[k]);
                }
            }
        }            
    }
    
    return response;
}

/**
 * Seleciona um objeto em um array de objetos pelo id
 * @param {arrayObject} array
 * @param {mixed} id
 * @param {mixed} field_id
 * @returns {object}
 */
function selectById(array,id,field_id) {
    return array.filter(function (el) {

        if ( field_id == undefined ) {

            if ( el.ID != undefined ) {
                field_id = 'ID';
            } else 
            if ( el.CODIGO != undefined ) {
                field_id = 'CODIGO';
            } 
        }

        return el[field_id] == id;
    })[0];
} 