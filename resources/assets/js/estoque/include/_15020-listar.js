(function($) {
	
	/**
	 * Listar Localização.
	 */
	function listarLoc() {
		
		execAjax1(
			'POST', 
			'/_15020/listarSelect',
			null,
			function(data) {
				
				for (var i = 0; data.length > i; i++) {

					//se a localização for igual a cadastrada ou
					//a quantidade de localizações for igual a 1
					if ( ($('._loc_cadastrado').val() === data[i]['ID']) || (data.length === 1) ) {

						$('.loc')
							.append(
								'<option value="'+ data[i]['ID'] +'" selected>'+ 
								data[i]['ID'] +' - '+ data[i]['DESCRICAO'] +
								'</option>'
							);
					}
					else {

						$('.loc')
							.append(
								'<option value="'+ data[i]['ID'] +'">'+ 
								data[i]['ID'] +' - '+ data[i]['DESCRICAO'] +
								'</option>'
							);
					}
				}
			}
		);

	}
	
	$(function() {
		
		listarLoc();
		
	});
	
})(jQuery);