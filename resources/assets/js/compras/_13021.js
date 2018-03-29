/**
 * Script com funções de:
 * - Limitar caracteres do textarea
 * */
(function($) {
	
	{ /** Limitar caracteres do textarea */
		
		limiteTextarea( $('textarea'), 200, $('span.contador span') ); //função em master.js

	}

	{ /** Tratamento de produtos repetidos */

		//Verificar produtos repetidos
		function verificarProdRepet(prod) {

			var ret = false;

			if ($(prod).siblings().hasClass($(prod).attr('class').split(' ').pop()))
				ret = true;

			return ret;
		}

		//Agrupar valores de itens repetidos
		$('.orc-item').each(function () {

			if (verificarProdRepet($(this))) {

				var qtd = $(this).find('.prod-qtd').val();
				if (qtd === "") qtd = 0;
				var qtd_tot = parseFloat(qtd) + parseFloat($(this).find('.prod-qtd').val());
				qtd_tot = qtd_tot.toFixed(4);	//4 dígitos
				qtd_tot = formataReal(qtd_tot);	//função em master.js

				$(this)
					.find('.prod-qtd')
					.val(qtd_tot);
			}
		});


		//Remover itens repetidos
		$('.orc-item').each(function () {

			if (verificarProdRepet($(this))) {
				$(this).remove();
			}
		});

	}

	{ /** Campos calculados */

        //Calcular ao carregar a página
		$('.orc-item').each(function() {

            calculaCampos(
                $(this).find('.prod-vlr').val(),
                $(this).find('.prod-qtd').val(),
                $(this).find('.subtotal'),
                $(this).find('.perc-ipi').val(),
                $(this).find('.vlr-ipi'),
                $(this).find('.total')
            );

		});

        var timer = null;

        //Calcular ao digitar o valor
        $('.prod-vlr, .perc-ipi').keyup(function() {

            var input = $(this);
            var row = input.parent().parent().parent();

            if(timer) clearTimeout(timer);

            setTimeout(function() {

                calculaCampos(
                    $(input).hasClass('prod-vlr') ? $(input).val() : row.find('.prod-vlr').val(),
                    row.find('.prod-qtd').val(),
                    row.find('.subtotal'),
                    $(input).hasClass('perc-ipi') ? $(input).val() : row.find('.perc-ipi').val(),
                    row.find('.vlr-ipi'),
                    row.find('.total')
                );

            }, 500);
        });

        //Efetua o cálculo dos campos
        function calculaCampos(valor, qtd, input_subtotal, perc_ipi, input_vlr_ipi, input_total) {

            //cálculos
			valor 	 = formataPadrao(valor);
            valor    = valor ? valor : 0;
            qtd      = formataPadrao(qtd);
            qtd      = qtd ? qtd : 0;
            perc_ipi = formataPadrao(perc_ipi) / 100;
            perc_ipi = perc_ipi ? perc_ipi : 0;

            var subtotal = valor * qtd;
            var vlr_ipi  = subtotal * perc_ipi;
            var total    = subtotal + vlr_ipi;

            //formatações nos campos
            subtotal = formataReal( subtotal.toFixed(4) );
            input_subtotal.val( subtotal );

			vlr_ipi = formataReal( vlr_ipi.toFixed(4) );
            input_vlr_ipi.val( vlr_ipi );

            total = formataReal( total.toFixed(4) );
            input_total.val( total );

        }

	}
	
	{ /** Enviar arquivos */
		
		$(':file').change(function() {
			enviarArquivo($(this), 'ORCAMENTO');	//função em 'arquivo.js'
		});
		
	}
	
	{/** Excluir arquivo */
		
		$('.excluir-arquivo').click(function(){

			if( !confirm('Confirma exclusão?') ) return false;

			prod    = $(this);
			prod_id = $(prod).parent('.form-group').parent('.item-dinamico').children('.form-group').first().children('input[name="_vinculo_arquivo_id[]"]').val();

            if (prod_id > 0) {
			    $(prod).parent('.form-group').parent('.item-dinamico').children('.form-group').first().children('.marcaexcluiritem').val('1');
            }

            if( $(prod).parents('.anexo-container').children('.item-dinamico').length > 1 ){

                $(prod).parent().parent('.item-dinamico').hide();

            } else {

				var item_din = $(prod).parent().parent('.item-dinamico');
				
				item_din.clone(true).appendTo( item_din.parent('.item-dinamico-container') );
				item_din.find('input').removeAttr('disabled').removeAttr('readonly').val('');
				item_din.find('input').first().focus();
				item_din.parent('.item-dinamico-container').children('.item-dinamico').last().find('.CLArquivo').remove();
				item_din.parent('.item-dinamico-container').children('.item-dinamico').last().hide();
				
            }

		});
		
	}
	
	{
		
		/**
		 * Verificar o tipo de frete para definir se ele pode ou não receber valor.
		 * 
		 * @param {string} tipo
		 * @returns {undefined}
		 */
		function verificarFrete(tipo) {
			
			//CIF
			if ( tipo === '1' ) {
				
				$('.frete-valor')
					.removeAttr('readonly')
					.parent('.input-group')
					.removeClass('readonly');
			}
			//FOB
			else {
				
				$('.frete-valor')
					.attr('readonly', true)
					.val('0,0000')
					.parent('.input-group')
					.addClass('readonly');;
			}
		}
		
		verificarFrete( $('.frete').val() );
		
		$('.frete').change(function() {
			verificarFrete( $(this).val() );			
		});
		
	}
	
})(jQuery);