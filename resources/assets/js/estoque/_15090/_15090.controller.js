angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        'Filtro',
        'Conferencia'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        Filtro,
        Conferencia
    ) {

		var vm = this;

		vm.Filtro      = new Filtro();
		vm.Conferencia = new Conferencia();


	}   
  