/**
 * _11110 - Gerenciar Qlik Sense
 */

;(function(angular) {

    var Ctrl = function($scope,$ajax,$timeout) {

        var vm = this;
		vm.DADOS = [];

        vm.DADOS.USUARIOS = [];

        function init(){

            console.log('A');

            $ajax.post('_11110/listUser',[],{contentType: 'application/json'})
                .then(function(response) {

                    vm.DADOS.USUARIOS = response;
                    console.log(vm.DADOS.USUARIOS);
                    
                }
            );

        }
		
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