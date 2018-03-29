/** 
 * Script para campos dinâmicos.
 */
	
/**
 * Adicionar campos dinamicamente.
 */
function itemDinamicoAdd() {
	
	$('.add-item-dinamico').click(function() {

		$(this).prev('.item-dinamico-container').children('.item-dinamico').first().clone(true).appendTo( $(this).prev('.item-dinamico-container') );
		
		$(this).prev('.item-dinamico-container').children('.item-dinamico').last().find('input, textarea')
			.each(function() {
				
				//Zerar apenas se não for input dos parâmetros do ConsultaAll
				if( $(this).parent('._consulta_parametros').length === 0 )
					$(this).val('').first().focus();
				
			})
		;

		$(this).prev('.item-dinamico-container').children('.item-dinamico').last().find('input, textarea').removeAttr('readonly');
		$(this).prev('.item-dinamico-container').children('.item-dinamico').last().find('button').removeAttr('disabled');

		$(this).prev('.item-dinamico-container').children('.item-dinamico').last().find('.CLArquivo').prop( "disabled", false );

		$(this).prev('.item-dinamico-container').children('.item-dinamico').find('.NoEnableR').prop( "readonly", true );
		$(this).prev('.item-dinamico-container').children('.item-dinamico').find('.NoEnableD').prop( "disabled", true );

		$(this).prev('.item-dinamico-container').children('.item-dinamico').last().find('.glyphicon-pencil').parents('.form-group').hide();
		$(this).prev('.item-dinamico-container').children('.item-dinamico').last().find('.glyphicon-trash').parents('.form-group').hide();
		$(this).prev('.item-dinamico-container').children('.item-dinamico').last().find('.remove').parents('.form-group-removido').addClass('form-group').removeClass('form-group-removido').css('display', 'inline-block');

		$(this).prev('.item-dinamico-container').children('.item-dinamico').last().find('.marc').val(3);



		//verificação necessária pois ao atualizar existe um botão semelhante mas que serve para apagar o item via Ajax.
		if( $(this).prev('.item-dinamico-container').children('.item-dinamico').last().children('.form-group').last().children('button').children('span').hasClass('glyphicon-trash') ) {
			$(this).prev('.item-dinamico-container').children('.item-dinamico').last().children('.form-group').last().hide();
			$(this).prev('.item-dinamico-container').children('.item-dinamico').last().find('.glyphicon-remove').parents('.form-group-removido').addClass('form-group').removeClass('form-group-removido').css('display', 'inline-block');
			$(this).prev('.item-dinamico-container').children('.item-dinamico').last().find('.glyphicon-pencil').parents('.form-group').hide();

		}

		$(this).prev('.item-dinamico-container').children('.item-dinamico').last().find('.pencil').hide();
		$(this).prev('.item-dinamico-container').children('.item-dinamico').last().find('.trash').hide();
		$(this).prev('.item-dinamico-container').children('.item-dinamico').last().find('.remove').show();

	});
	
}

/**
 * Excluir campos dinamicamente.
 */
function itemDinamicoExcluir() {
			
	$('.excluir-item-dinamico').each(function() {
		//verificação necessária pois ao atualizar existe um botão semelhante mas que serve para apagar o item via Ajax.
		if( $(this).hasClass('remove') ) {

			$(this).click(function() {
				$(this).closest('.item-dinamico').remove();
			});

		}

		if( $(this).children('.glyphicon').hasClass('glyphicon-trash') ) {
			//$(this).parent().prev().find('.remove').hide();
			$(this).parent().next().find('.pencil').parent().show();
			//$(this).parent().prev().find('.remove').parent().parent('.form-group').hide();
			$(this).parent().prev().children('.remove').parent().removeClass('form-group').addClass('form-group-removido').hide();
		}

//			if( $(this).parents('.item-dinamico-container').children('.item-dinamico').length == 1 ) {
//			   	$(this).parent('.form-group').parent('.form-group').hide();
//               //$(this).prev().hide();
//			}
	});
	
}

/**
 * Editar campos dinamicamente
 */
function itemDinamicoEditar() {
	
	$('.editar-item-dinamico').each(function() {

		$(this).click(function() {

			$(this).closest('.item-dinamico').last().find('input').not('[type="search"]').prop( "readonly", false );
			$(this).closest('.item-dinamico').find('.marceditavel').prop( "readonly", false );
			$(this).closest('.item-dinamico').find('.marceditavel').prop( "disabled", false );

			$(this).closest('.item-dinamico').find('.NoEnableR').prop( "readonly", true );
			$(this).closest('.item-dinamico').find('.NoEnableD').prop( "disabled", true );

			$(this).hide();

			$(this).closest('.item-dinamico').find('.form-group').first().children('.marcaeditaritem').val(1);

			$(this).closest('.item-dinamico').find('.form-group').first().find('.btn-filtro').removeAttr('disabled');

		});
		
	});
	
}

(function($) {
	$(function() {
		
		itemDinamicoAdd();
		itemDinamicoEditar();
		itemDinamicoExcluir();
		
	});
})(jQuery);