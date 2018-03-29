/**
 * _22021 - Relatório de peças disponíveis para consumo
 */
(function($) {
    
	function imprimirPecaDisponivel() {
		
		function verificarCampos() {
			
			var ret = true;
			
			if( $('._gp_id').val() == '' ) {
				showErro('Selecione o GP.');
				ret = false;
			}
			
			return ret;
			
		}
		
        function dados()
        {				
			var dados = {
				estabelecimento_id	: $('.estab').val(),
				gp_id				: $('._gp_id').val(),
				gp_descricao		: $('._gp_descricao').val(),
				data_ini			: $('#data-ini').val(),
				data_fim			: $('#data-fim').val(),
				somente_sobra		: $('#somente-sobra').is(':checked'),
				status				: 1
			};

			execAjax1(
				'POST',
				'/_22021/relatorioPecaDisponivel',
				dados,
				function(resposta) {

					if(resposta) {
						
						printPdf(resposta); 
						
					}

				},
				null,
				null,
				true
			);
        }
		
		$('#btn-imprimir-peca-disponivel')
			.click(function() {
				
				if( !verificarCampos() )
					return false;
				
				dados();
				
			})
		;
		
//		$('#btn-imprimir-pecas-disponiveis')
//			.click(function() {
//				
//				var type	= 'GET',
//					url		= 'report/Ppcp/22020/peca_disponivel.php',
//					data	= {
//						produto_id: 60358,
//						quantidade: 20
//					},
//					success	= function(resposta) {
//						
////						$('#peca-disponivel')
////							.html(resposta)
////						;
//						$('#peca-disponivel')
//							.attr('data', resposta)
//						;
//						
//					}
//				;
//				
//				execAjax1(type, url, data, success);
////////////////////////				
//				var type	= 'GET',
//					url		= '/report',
//					data	= {
//						report	: 'Ppcp/22020/peca_disponivel',
//						param	: {
//							produto_id : 60358, 
//							quantidade : 20
//						}
//					},
//					success	= function(resposta) {
//						
//						$('#peca-disponivel')
//							.html(resposta)
//						;
////						$('#peca-disponivel')
////							.attr('data', resposta)
////						;
//						
//					}
//				;
//				
//				execAjax1(type, url, data, success);
				
//			})
//		;
		
	}
	
	$(function() {
		imprimirPecaDisponivel();
	});
	
})(jQuery);

