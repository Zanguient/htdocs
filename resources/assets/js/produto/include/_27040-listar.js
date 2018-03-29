(function($) {
	
	$(function() {		
		
		$(document).on('click', '.tamanho-produto', function(){
			$('.div-tamanho-produto').show();
		});

		$(document).on('click', '.settamanho', function(){

			var valor = $(this).attr('tamanho');
			var posicao = $(this).attr('posicao');

			$('.setar-tamanho')
				.val(valor)
				.next('.tamanho-posicao')
				.val(posicao);

			$('.setar-tamanho')
				.removeClass('setar-tamanho');

		});

		$(document).on('click', '.sett', function() {
			
			var btn = $(this);
			var but = $(this).parent().parent();
			but.children('.tamanho-produto').addClass('setar-tamanho');
			
			var ProdID;
			var fdata;
			var p = but.parent().parent().find('input[name="_produto_id[]"]');
			
			if (p.length > 0) {
				ProdID = p.val();
				fdata = new FormData();
				fdata.append('ProdID', ProdID);
			}
			else {
				ProdID = $('._produto_id').val();
				fdata = ProdID;
			}
			
			if (ProdID == '') {
				showAlert('Selecione um produto');
				return false;
			}
			
			//ajax
			var type	= 'POST',
				url		= "/_27040/listarTamanho",
				data	= {'id_prod': ProdID},
				success	= function(data) {

					if (data){
						
						//exibir modal
						var modal = btn.attr('data-target');
						$(modal).modal('show');
						//
						
						var desc = data[0][0][0];
						var total_tamanhos = parseInt(data[0][0][1]);
						var abilitado;
						var posicao;
						var Tan;

						for (i = 1; i <= 20; i++) {

							tamanho   = data[0][i][0];
							abilitado = data[0][i][1];
							posicao   = data[0][i][2];

							if (i < 10){
								Tan = '.T0'+i;
							}else{
								Tan = '.T'+i;
							}

							if ((parseInt(data[0][i][1]) === 0) || (total_tamanhos < i)){
								$(Tan).prop( "disabled", true );
							}else{
								$(Tan).prop( "disabled", false );
							}

							if(abilitado == 0){
								$(Tan).prop( "disabled", true );
							}


							$(Tan).attr("tamanho",tamanho);
							$(Tan).attr("abilitado",abilitado);
							$(Tan).attr("posicao",posicao);
							$(Tan).find(Tan).html(tamanho);
							//$('.TGRADE').html('GRADE - ('+desc+')');


						}

						for (i = total_tamanhos+1; i <= 20; i++) {

							if (i < 10){
								Tan = '.T0'+i;
							}else{
								Tan = '.T'+i;
							}

							$(Tan).prop( "disabled", true );

							tamanho = '00';
							posicao = '00';

							$(Tan).attr("tamanho",tamanho);
							$(Tan).attr("posicao",posicao);
							$(Tan).find(Tan).html(tamanho);

						}

					}else{

						for (i = 1; i <= 20; i++) {

								if (i < 10){
									Tan = '.T0'+i;
								}else{
									Tan = '.T'+i;
								}

								$(Tan).prop( "disabled", true );

								tamanho = '00';

								$(Tan).attr("tamanho",tamanho);
								$(Tan).find(Tan).html(tamanho);

						}
					}

				},
				error	= function(xhr) {
					
					for (i = 1; i <= 20; i++) {

							if (i < 10){
								Tan = '.T0'+i;
							}else{
								Tan = '.T'+i;
							}

							$(Tan).prop( "disabled", true );

							tamanho = '00';

							$(Tan).attr("tamanho",tamanho);
							$(Tan).find(Tan).html(tamanho);

					}
				}
			;

			execAjax1(type, url, data, success, error, null, false);
			
		});
		
		
		
	});
	
})(jQuery);