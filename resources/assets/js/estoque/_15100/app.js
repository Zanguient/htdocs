/**
 * _15100 - Abastecer estoque
 */

;(function(angular) {

    var Ctrl = function($scope,$ajax,$timeout) {

        var vm   = this;
		vm.DADOS = [];


        function init(){
        	/*
			var ds = {
					ID : 0
				};

			$ajax.post('/_15100/Consultar',ds,{contentType: 'application/json'})
				.then(function(response) {
					vm.DADOS.CONSULTA = response[0];                   
				}
			);
			*/
        }

        vm.gp = {
        	ID        : 0,
        	PERFIL    : '',
        	DESCRICAO : '',
        	CODBARRAS : ''
        };

        vm.operador = {
        	ID        : 0,
        	DESCRICAO : '',
        	CODBARRAS : ''
        };

        vm.peca = {
        	CODBARRAS : '',
        	ABASTECER : 0
        };

        vm.logado = false;

        vm.regra = {};

        vm.Acoes = {
        	login: function(){
        		$('#modal-up').modal();
        		setTimeout(function(){
        			$('#modal-up').find('input').focus();
        		},300);
        	},
        	UpKeydown: function($event){
        		if($event.key == 'Enter'){

        			var ds = {
						CODBARRAS : vm.gp.CODBARRAS
					};

	            	$ajax.post('/_15100/ConsultarUP',ds)
						.then(function(response) {
							if(response.ID == 'ERRO'){
								showErro('UP não foi encontrada.<br>COD. BARRAS:'+vm.gp.CODBARRAS);
								vm.gp.CODBARRAS = '';
							}else{
								$('#modal-up').modal('hide');
        						$('#modal-operador').modal();

        						setTimeout(function(){
				        			$('#modal-operador').find('input').focus();
				        		},300);

						        vm.gp.ID        = response.ID;
						        vm.gp.PERFIL    = response.PERFIL;
						        vm.gp.DESCRICAO = response.DESCRICAO;
							}                 
						}
					);     
	            }
        	},
        	OperadorKeydown: function($event){
        		if($event.key == 'Enter'){

        			var ds = {
						CODBARRAS : vm.operador.CODBARRAS
					};

	            	$ajax.post('/_15100/ConsultarOperador',ds)
						.then(function(response) {
							if(response.ID == 'ERRO' || response.VALOR != 1){
								showErro('Operador não foi encontrado ou não tem permissão.<br>26-PERMITE ABASTECER WIP.<br>COD. BARRAS:'+vm.gp.CODBARRAS);
								vm.operador.CODBARRAS = '';
							}else{

    							$('#modal-operador').modal('hide');

        						setTimeout(function(){
				        			$('.input-peca').focus();
				        		},300);
						        vm.operador.ID        = response.ID;
						        vm.operador.DESCRICAO = response.DESCRICAO;
						    	
							}                 
						}
					);     
	            }
        	},
        	PecaKeydown: function($event){

        		vm.peca.ABASTECER = 0;

        		if($event.key == 'Enter'){
        			
        			var ds = {
						COD_BARRAS   :vm.peca.CODBARRAS,
            			OPERADOR_ID  :vm.operador.ID,
            			COD_UP       :vm.gp.CODBARRAS
					};

	            	$ajax.post('/_15100/ConsultarPeca',ds)
						.then(function(response) {

    						vm.regra = response;
						    vm.peca.ABASTECER = 1;	                 
						}
					);     
	            }
        	},
        	Abastercer: function($event){
    			var ds = {
					COD_BARRAS   :vm.peca.CODBARRAS,
        			OPERADOR_ID  :vm.operador.ID,
        			COD_UP       :vm.gp.CODBARRAS
				};

            	$ajax.post('/_15100/Abastercer',ds)
					.then(function(response) {
						if(vm.regra.DESFAZER == 0){
							showSuccess('Abastecido.');
						}else{
							showSuccess('Desabastecido.');	
						}
						vm.peca.CODBARRAS = '';
						vm.peca.ABASTECER = 0;				    	                 
					}
				);     
        	},
        	logOff: function(){

        		$('#modal-up').modal('hide');
        		$('#modal-operador').modal('hide');
        		vm.peca.CODBARRAS = '';
        		vm.peca.ABASTECER = 0;

        		vm.gp = {
		        	ID        : 0,
		        	PERFIL    : '',
		        	DESCRICAO : '',
		        	CODBARRAS : ''
		        };

		        vm.operador = {
		        	ID        : 0,
		        	DESCRICAO : '',
		        	CODBARRAS : ''
		        };
        	}
        }
        
        $scope.$watch('vm.DADOS', function (newValue, oldValue, scope) {
            if(newValue != oldValue){
				
            }
        }, true);

		
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