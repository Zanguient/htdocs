/**
 * Script para formatações de variáveis.
 */

/**
 * Formata número para o padrão brasileiro.
 * Ex.: 1000.0000 -> 1.000,0000
 * 
 * @param {string} int
 * @returns {string}
 */
function formataReal(int) {
	
	var tmp = int + '';
	tmp = tmp.replace('.', '');
	tmp = tmp.replace(/([0-9]{4})$/g, ",$1");

	if (tmp.length > 8)
		tmp = tmp.replace(/([0-9]{3}),([0-9]{4}$)/g, ".$1,$2");

	return tmp;
	
}

/**
 * Formata número para o formato padrão do javascript.
 * Ex.: 1.000,00 -> 1000.00
 * 
 * @param {string} num
 * @returns {float}
 */
function formataPadrao(num) {
	
	return parseFloat(num.replace('.', '').replace(',', '.'));
	
}

/**
 * Formata número para o formato padrão brasileiro.
 * Ex.: 10.5 -> 10,5
 * 
 * @param {string} num
 * @returns {string}
 */
function formataPadraoBr(num) {
    if(num != ''){
        num = String(num);
        return num.replace('.', ',');
    }else{
        return '0';
    }
}

function SomenteNumeroePonto(obj,e,decimal){
	
	var tecla=(window.event)?event.keyCode:e.which;

	var texto = $(obj).val();
	var tecla = ( window.event ) ? e.keyCode : e.which;
	var quant = decimal-1;

	PositionC = doGetCaretPosition(obj);
	PositionV = texto.indexOf(",")+1;

	var res = texto.substring(PositionV);

	if ( (tecla === 44) && (quant === -1))
	   return false;

	if ( tecla === 44)
		if (texto.indexOf(",") !== -1)
			return false;

	if ( tecla === 8 || tecla === 0 )
		return true;
	if ( tecla !== 44 && tecla < 48 || tecla > 57 )
		return false;

	if (PositionV > 0)
		if (PositionC >= PositionV)
			if ( tecla !== 44)
				if (res.length > quant)
					return false;

}

/**
 * Separa uma string em parametros
 * @param {string} string
 * @param {string} separador
 * @param {string} divisor
 * @returns {object array}
 */
function paramSplit(string,separador,divisor) {
    separador = separador || '=';
    divisor   = divisor   || '&';

    var list = string.split(divisor);
    var hash = {};

    for(var i = 0; i < list.length; i++){
        var parametro = list[i].split(separador);
        var chave = parametro[0];
        var valor = parametro[1];
        hash[chave] = valor;
    }   
    return hash;
}  

//function number_format( numero, decimal, decimal_separador, milhar_separador ){	
//    numero = parseFloat(numero.replace('.', '').replace(',', '.'));
//    numero = (numero + '').replace(/[^0-9+\-Ee.]/g, '');
//    var n = !isFinite(+numero) ? 0 : +numero,
//        prec = !isFinite(+decimal) ? 0 : Math.abs(decimal),
//        sep = (typeof milhar_separador === 'undefined') ? ',' : milhar_separador,
//        dec = (typeof decimal_separador === 'undefined') ? '.' : decimal_separador,
//        s = '',
//        toFixedFix = function (n, prec) {
//            var k = Math.pow(10, prec);
//            return '' + Math.round(n * k) / k;
//        };
//    // Fix para IE: parseFloat(0.55).toFixed(0) = 0;
//    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
//    if (s[0].length > 3) {
//        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
//    }
//    if ((s[1] || '').length < prec) {
//        s[1] = s[1] || '';
//        s[1] += new Array(prec - s[1].length + 1).join('0');
//    }
//
//    return s.join(dec);
//}

function number_format (number, decimals, decPoint, thousandsSep) { // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/number_format/
  // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: davook
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Theriault (https://github.com/Theriault)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // bugfixed by: Michael White (http://getsprink.com)
  // bugfixed by: Benjamin Lupton
  // bugfixed by: Allan Jensen (http://www.winternet.no)
  // bugfixed by: Howard Yeend
  // bugfixed by: Diogo Resende
  // bugfixed by: Rival
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  //  revised by: Luke Smith (http://lucassmith.name)
  //    input by: Kheang Hok Chin (http://www.distantia.ca/)
  //    input by: Jay Klehr
  //    input by: Amir Habibi (http://www.residence-mixte.com/)
  //    input by: Amirouche
  //   example 1: number_format(1234.56)
  //   returns 1: '1,235'
  //   example 2: number_format(1234.56, 2, ',', ' ')
  //   returns 2: '1 234,56'
  //   example 3: number_format(1234.5678, 2, '.', '')
  //   returns 3: '1234.57'
  //   example 4: number_format(67, 2, ',', '.')
  //   returns 4: '67,00'
  //   example 5: number_format(1000)
  //   returns 5: '1,000'
  //   example 6: number_format(67.311, 2)
  //   returns 6: '67.31'
  //   example 7: number_format(1000.55, 1)
  //   returns 7: '1,000.6'
  //   example 8: number_format(67000, 5, ',', '.')
  //   returns 8: '67.000,00000'
  //   example 9: number_format(0.9, 0)
  //   returns 9: '1'
  //  example 10: number_format('1.20', 2)
  //  returns 10: '1.20'
  //  example 11: number_format('1.20', 4)
  //  returns 11: '1.2000'
  //  example 12: number_format('1.2000', 3)
  //  returns 12: '1.200'
  //  example 13: number_format('1 000,50', 2, '.', ' ')
  //  returns 13: '100 050.00'
  //  example 14: number_format(1e-8, 8, '.', '')
  //  returns 14: '0.00000001'

  number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
  var n = !isFinite(+number) ? 0 : +number
  var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
  var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
  var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
  var s = ''

  var toFixedFix = function (n, prec) {
    var k = Math.pow(10, prec)
    return '' + (Math.round(n * k) / k)
      .toFixed(prec)
  }

  // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || ''
    s[1] += new Array(prec - s[1].length + 1).join('0')
  }

  return s.join(dec)
}

/**
 * Retorna as palavras contidas no intervalo.
 * Ex.: 
 *		palavra = Lorem ipsum dolor sit amet consectetur adipiscing elit.
 *		inicio = 0
 *		fim = 2
 *		retorno = Lorem ipsum
 * 
 * @param {string} palavra
 * @param {integer} inicio
 * @param {integer} fim
 * @returns {string}
 */
function pegarPalavra(palavra, inicio, fim) {
	
	return	palavra
				.split(/\s+/)
				.slice(inicio, fim)
				.join(' ')
			;
	
}