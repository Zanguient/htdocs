(function($) {	
	
	/**
	 * Listar Estabelecimentos.
	 */
	function listarEstab() {

		function ativarConsulta() {

			execAjax1(
				'POST', 
				'/_11020/listarSelect',
				null,
				function(data) {

					$('._estab_cadastrado').each(function() {

						for (var i = 0; data.length > i; i++) {

							//se o estabelecimento for igual ao cadastrado ou
							//a quantidade de estabelecimentos for igual a 1 (ou seja, usuário só possui permissão para 1 estabelecimento)
							if ( ($(this).val() === data[i]['ID']) || data.length === 1 ) {

								$(this)
									.siblings('.estab')
									.append(
										'<option value="'+ data[i]['ID'] +'" data-descricao="' + data[i]['NOMEFANTASIA'] + '" selected>'+ 
										data[i]['ID'] +' - '+ data[i]['NOMEFANTASIA'] +
										'</option>'
									)
								;
                                
                                var valor = data[i]['ID'];
                                $(this).parent().find('._input_estab').val(valor).trigger('change');
                                
                                var descricao = data[i]['NOMEFANTASIA'];
                                $(this).parent().find('._input_estab_descricao').val(descricao).trigger('change');
                                
							}
							else {

								$(this)
									.siblings('.estab')
									.append(
										'<option value="'+ data[i]['ID'] +'" data-descricao="' + data[i]['NOMEFANTASIA'] + '">'+ 
										data[i]['ID'] +' - '+ data[i]['NOMEFANTASIA'] +
										'</option>'
									)
								;
							}
						}

					});
				}
			);

		}


		var existe = false;

		$('.estab option').each(function() {

		   //verifica se não está preenchido e selecionado.
	//				if( $(this).val() !== '' && $(this).is(':selected') ) {
	//					existe = false;
	//					return false;
	//				}

			if( $(this).val() !== '' ) {
				$(this).remove();
			}

		});

		if(!existe) {
			ativarConsulta();
		}
	}
    
    $(document).on('change','.estab', function(e) {
        var valor     = $(this).val();
        var descricao = $(this).find(':selected').data('descricao');
        $(this).parent().find('._input_estab'          ).val(valor).trigger('change');
        $(this).parent().find('._input_estab_descricao').val(descricao).trigger('change');
    });
    
	
	$(function() {
			
		listarEstab();
		
	});
	
})(jQuery);

//# sourceMappingURL=_11020-listar.js.map
