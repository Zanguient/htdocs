/**
 * _11180 - Blok
 */

;(function(angular) {

    var Ctrl = function($scope,$ajax,$timeout) {

        var vm     = this;
		vm.DADOS   = [];
		vm.FILTRO  = '';
		vm.ordem   = 'NOME';
		vm.filtro  = '';
		vm.DADOS   = [];
		vm.modal   = {};
		vm.TEMP    = [];

		vm.modal.URL    = {};
		vm.modal.JANELA = {};

		vm.iten = {
			CDDVD		:"0",
			FLAG		:"0",
			GRUPO 		:"0",
			HORA1 		:"00:00",
			HORA2 		:"00:00",
			ID 			:"0",
			INVERT_TELA :"0",
			INVERT_URL 	:"0",
			LIBERAR1 	:"0",
			LIBERAR2 	:"0",
			LIBERAR_C1 	:"0",
			LIBERAR_C2 	:"0",
			LIBERAR_H1 	:"0",
			LIBERAR_H2 	:"0",
			LIBERAR_N 	:"0",
			LIBERAR_T 	:"0",
			NOME 		:"",
			USB 		:"0",
		};

        function init(){
			var ds = {
					FILTRO : ''
				};

			$ajax.post('/_11180/Consultar',ds,{contentType: 'application/json'})
				.then(function(response) {
					vm.DADOS = response;                   
				}
			);
        }

        vm.Acoes = {
        	atualizar: function(){
        		init();
        	},
        	atualizarURL: function(){
        		var ds = {
					ID : vm.modal.iten.ID
				};

				$ajax.post('/_11180/url',ds)
					.then(function(response) {
						vm.modal.URL = response;                  
					}
				);
        		
        	},
        	atualizarJANELA: function(){
        		var ds = {
					ID : vm.modal.iten.ID
				};

				$ajax.post('/_11180/janela',ds)
					.then(function(response) {
						vm.modal.JANELA = response;                  
					}
				);	
        	},
        	Alterar: function(){
        		vm.modal.acao = 3;
        		$('#tab-user').trigger('click');
        	},
        	AlterarNO: function(){
        		vm.modal.iten = angular.copy(vm.TEMP.iten);
        		vm.modal.acao = 2;	
        	},
        	excluir: function(){
        		addConfirme('Excluir Usuário',
                        'Deseja realmente Excluir Usuário:'+vm.modal.iten.NOME 
                        ,[obtn_ok,obtn_cancelar],
                    [
                    {ret:1,func:function(e){

                    	var ds = {
							ID : vm.modal.iten.ID
						};

                        $ajax.post('/_11180/excluir', ds)
                            .then(function(response) {
                            	$('#modal-blok').modal('hide');
                            	init();                                
                            }
                        );

                    }},
                    {ret:2,func:function(e){


                    }},
                    ]  
                );	
        	},
        	gravar: function(){
        		var iten = vm.modal.iten;

				$ajax.post('/_11180/gravar',iten)
					.then(function(id) {
						iten.ID = id; 
						vm.modal.iten = angular.copy(iten);
        				vm.TEMP.iten  = angular.copy(iten);
        				vm.modal.acao = 2;	
        				init();                
					}
				);

        	},
        	add: function(){
        		vm.modal.acao = 1;
        		vm.modal.iten = angular.copy(vm.iten);
        		$('#modal-blok').modal();
        	},
        	TratarOrdem : function(filtro){
        		if(vm.ordem == filtro){
	                vm.ordem = '-'+filtro;
	            }else{
	                vm.ordem = filtro;
	            }
        	},
        	open : function(iten){
        		vm.modal.acao = 2;
        		vm.modal.iten = angular.copy(iten);
        		vm.TEMP.iten = angular.copy(iten);
        		$('#modal-blok').modal();
        		$('#tab-user').trigger('click');
        	}
        };
		
        init();        
    };

    Ctrl.$inject = [
		'$scope',
		'$ajax',
		'$timeout'
	];
 
    angular
    .module    ('app' , ['angular.filter','vs-repeat','gc-ajax','gc-form','gc-find','gc-transform'])
    .controller('Ctrl', Ctrl);
        
})(angular);

 ;(function($){


})(jQuery);