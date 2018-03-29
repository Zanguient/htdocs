/**
 * _12080 - REGISTRO DE CASOS
 */

;(function(angular) {

    var Ctrl = function($scope,$ajax,$timeout) {

        var vm = this;
		vm.DADOS = [];
		vm.DADOS.CONSULTA = '';


        function init(){
			var ds = {
					ID : 0
				};

			$ajax.post('/_12080/Consultar',ds,{contentType: 'application/json'})
				.then(function(response) {
					vm.DADOS.CONSULTA = response[0];                   
				}
			);
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
//# sourceMappingURL=_12080.js.map
