/**
 * _22400 - Geracao de Remessas de Bojo
 */

;(function(angular) {

    var Ctrl = function($scope,$ajax,$timeout,$filter,$window,$interval) {

        var vm = this;

        vm.ONLINE_CHAT  = [];
        vm.MENSAGE 		= [];

        vm.Acoes = {
            filtrar:function(){

            },
            SEND_CHAT:function(cliente){
            	SocketWeb.sendMensage(
					SocketWeb.CONNECTION_ID,
					cliente,
					{
						MSG 	:'OI',
				        DATA    : moment().toDate(),
				        DE 	    : SocketWeb.CONNECTION_ID
					},
					'SEND_CHAT',
					'SEND_CHAT',
					[]
				);	
            }
        };

       var metodos = [
			{
				METHOD  :'ON_LOGIN_USER',
				FUNCTION:function(ret){
					$scope.$apply(function(){
						console.log('Entrou no chat:');
						console.log(ret.MENSAGE.DADOS.NEW);
						vm.ONLINE_CHAT = ret.MENSAGE.DADOS.LISTA;
	    			});
				}
			},
			{
				METHOD  :'ON_LOGOF_USER',
				FUNCTION:function(ret){
					$scope.$apply(function(){
						console.log('Saiu do chat:');
						console.log(ret.MENSAGE.DADOS.OLD);
						vm.ONLINE_CHAT = ret.MENSAGE.DADOS.LISTA;
	    			});	
				}
			},
			{
				METHOD  :'ON_MENSAGE',
				FUNCTION:function(ret){
					$scope.$apply(function(){
						vm.MENSAGE.push(ret.MENSAGE.DADOS);
	    			});

	    			console.log(ret.MENSAGE.DADOS);
				}
			}

		];

		SocketWeb.create(metodos);

		setTimeout(function(){
			SocketWeb.sendMensage(
				SocketWeb.CONNECTION_ID,
				SocketWeb.CONNECTION_ID,
				{
					NOME 	: $('.user_nome').val(),
			        ID 		:SocketWeb.CONNECTION_ID,
			        TYPO 	:'CLIENTE',
			        STATUS  :1
				},
				'LOGIN_CHAT',
				'LOGIN_CHAT',
				[]
			);
		},1000);
        
    };

    Ctrl.$inject = ['$scope','$ajax','$timeout','$filter','$window','$interval'];

    var bsInit = function() {
        return function(scope, element, attrs) {         
            bootstrapInit();
        };
    };
    
    var parseData = function() {
        return function(input) {
            if ( input ) return new Date(input);
        };
    };
        
    angular
    .module    ('app'           , ['angular.filter','vs-repeat','gc-ajax','gc-form','gc-find','gc-transform'])
    .filter    ('parseDate'     , parseData)
    .controller('Ctrl'          , Ctrl     );
        
})(angular);


;(function($){
    

})(jQuery);


//# sourceMappingURL=_11090.js.map
