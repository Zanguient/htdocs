/**
 * _22130 - Conformacao
 */
(function($) {
    
	$(function() {

		function getUP(){

		var type	= 'POST',
			url		= '/_22130/getUp',
			data	= {'filtro': ''},
			success	= function(data) {};				
			execAjax2(type, url, data, success);
		}

		function getSubUp(){
			var type	= 'POST',
			url		= '/_22130/getSubUp',
			data	= {'filtro': ''},
			success	= function(data) {};				
			execAjax2(type, url, data, success);
		}

		getSubUp();
		getUP();
		
	});

		function teste(){
			var type	= 'POST',
			url		= '/_22130/teste',
			data	= {'_socket_token': $('._socket_token').val()},
			success	= function(data) {};				
			execAjax2(type, url, data, success);
		}

		$(document).on('click','.tg-1sci', function(e) {
			console.log($('._socket_token').val());
			teste();
		}

		createSockte();

	
})(jQuery);

