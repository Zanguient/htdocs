(function($) {
	
	/**
	 * Listar Turnos.
	 */
	function listarTurno() {
		
		execAjax1(
			'POST', 
			'/_23010/listarSelect',
			null,
			function(data) {
				
				var opcao_todos_selecionada = $('#turno').find('.turno-opcao-todos').is(':selected');
				
				for (var i = 0; data.length > i; i++) {
					
					//se o turno for igual ao cadastrado ou
					//a quantidade de turnos for igual a 1
					if ( ($('#_turno_cadastrado').val() === data[i]['ID']) || (data.length === 1) ) {

                        $('#turno')
                            .append(
                                '<option value="'+ data[i]['ID'] +'" data-descricao="' + data[i]['DESCRICAO'] + '" data-hora-ini="'+ data[i]['HORA_INICIO'] +'" data-hora-fim="'+ data[i]['HORA_FIM'] +'" selected>'+ 
                                data[i]['ID'] +' - '+ data[i]['DESCRICAO'] +
                                '</option>'
                            );
                    

                            var valor = data[i]['ID'];
                            $('#_turno_valor').val(valor).trigger('change');

                            var descricao = data[i]['DESCRICAO'];
                            $('#_turno_valor_descricao').val(descricao).trigger('change');

					}
					//se a opção todos estiver pré-selecionada
					else if (opcao_todos_selecionada) {
						
						$('#turno')
							.append(
								'<option value="'+ data[i]['ID'] +'" data-descricao="' + data[i]['DESCRICAO'] + '" data-hora-ini="'+ data[i]['HORA_INICIO'] +'" data-hora-fim="'+ data[i]['HORA_FIM'] +'">'+ 
								data[i]['ID'] +' - '+ data[i]['DESCRICAO'] +
								'</option>'
							);
                    
					}
					else {
                        
                        if ( data[i]['FLAG'] != 1 ){

                            $('#turno')
                                .append(
                                    '<option value="'+ data[i]['ID'] +'" data-descricao="' + data[i]['DESCRICAO'] + '" data-hora-ini="'+ data[i]['HORA_INICIO'] +'" data-hora-fim="'+ data[i]['HORA_FIM'] +'">'+ 
                                    data[i]['ID'] +' - '+ data[i]['DESCRICAO'] +
                                    '</option>'
                                );
                        
                        }
                        else {
							
                            $('#turno')
                                .append(
                                    '<option value="'+ data[i]['ID'] +'" data-descricao="' + data[i]['DESCRICAO'] + '" data-hora-ini="'+ data[i]['HORA_INICIO'] +'" data-hora-fim="'+ data[i]['HORA_FIM'] +'" selected>'+ 
                                    data[i]['ID'] +' - '+ data[i]['DESCRICAO'] +
                                    '</option>'
                                );
                        
                        
                        }
					}
					
				}
			}
		);

	}
	
	$(function() {
		
		listarTurno();

		$(document).on('change','#turno',function(){
			var turno = $(this).val();
            var descricao = $(this).find(':selected').data('descricao');
            
			$('#_turno_valor'          ).val(turno).trigger('change');
			$('#_turno_valor_descricao').val(descricao).trigger('change');
		});
		
	});
	
})(jQuery);