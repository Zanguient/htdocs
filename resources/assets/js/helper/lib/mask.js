/**
 * Script com funções para definir máscaras.
 */

/**
 * Aplica máscara de telefone.
 * @param {input} campo
 */
function mascaraFone(campo) {

    var MascaraFone = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        foneOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(MascaraFone.apply({}, arguments), options);
            }
        };

    jQuery(campo).mask(MascaraFone, foneOptions);

}


/**
 * Aplica a mascara do tipo R$
 * <br />
 * Obs 1: Caso o simbolo da moeda não seja atribudo, por padrão, o simbolo da moeda será igual a R$
 * Ex.: 'U$' => U$ 1000,00
 * <br />
 * Obs 2: Caso o atributo "simbolo" não seja "true", por padrão, o simbolo da moeda não será exibido
 * Ex.: true => R$ 1000,00 ; false => 1000,00
 * <br />
 * Obs 3: Caso o atributo "precisao" não seja atribuido, por padrão, as casas decimais serão igual 2
 * Ex.: 2 => 1000,00 ; 3 => 1000,000
 * 
 * @param {input} campo
 */
function mascaraDinheiro(campo) {

	jQuery(campo).each(function() {
		
		var currency_symbol		= (typeof jQuery(this).attr('simbolo')			!== typeof undefined && jQuery(this).attr('simbolo') 		    !== false) ? jQuery(this).attr('simbolo') : 'R$';
		var currency_showSymbol	= (typeof jQuery(this).attr('exibir-simbolo')	!== typeof undefined && jQuery(this).attr('exibir-simbolo')     !== false) ? true : false;
		var currency_precision	= (typeof jQuery(this).attr('precisao')			!== typeof undefined && jQuery(this).attr('precisao')		    !== false) ? parseInt(jQuery(this).attr('precisao')) : 2;

		jQuery(this).maskMoney({
			symbol: 	currency_symbol+ ' ',
			showSymbol: currency_showSymbol,
			precision: 	currency_precision,
			thousands:	'.',
			decimal: 	',',
			symbolStay: true
		});
		
	});

}


/**
 * Máscara para números.
 */
function mascNum() {
	
	$(document).on('keypress','.mask-numero', function() {

		var decimal = $(this).attr('decimal');

		if (decimal < 1){
		   decimal = 0;
		}

		return SomenteNumeroePonto(this,event,decimal);

	});
	
}

(function($) {
	$(function() {

		//mascaraDinheiro('.currency');
		//mascaraDinheiro('.mask-dinheiro');
		//mascaraDinheiro('.mask-qtd');
		mascaraFone('.fone');
		mascNum();

	});	
})(jQuery);