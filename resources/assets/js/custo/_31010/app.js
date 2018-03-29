/**
 * _31010 - Custos Gerenciais
 */

;(function(angular) {

    var Ctrl = function($scope,$ajax,$timeout, gcCollection, gScope) {

        var vm = this;
		vm.DADOS = [];
		vm.DADOS.CONSULTA = '';
		vm.MODELO = [];

        function init(){
			var ds = {
					ID : 0
				};

			$ajax.post('/_31010/Consultar',ds,{contentType: 'application/json'})
				.then(function(response) {
					vm.MODELO = response;

					var grupos = gcCollection.groupBy(response, [
		                'COR_CODIGO'
		            ], 'CORES'); 
		            
		            gcCollection.merge(vm.MODELO, grupos, ['CORES']);

		            console.log(vm.MODELO);                  
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
		'$timeout',
        'gcCollection',
        'gScope'
	];
 
    angular
    .module    ('app' , ['angular.filter','vs-repeat','gc-ajax','gc-form','gc-find','gc-transform', 'gc-utils'])
    .controller('Ctrl', Ctrl);

        
})(angular);

 ;(function($){


})(jQuery);