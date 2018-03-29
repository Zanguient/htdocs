angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        'Estoque'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        Estoque
    ) {

		var vm = this;

		vm.Estoque = new Estoque();

	}   
  