angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        '$consulta',
        'gScope',
        'Filtro',
        'Modelo',
        'Historico'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        $consulta,
        gScope, 
        Filtro, 
        Modelo,
        Historico
    ) {

		var vm = this;

		vm.Filtro    = new Filtro();
		vm.Modelo    = new Modelo();
		vm.Historico = new Historico();
        
        vm.Consulta  = new $consulta();
        
        vm.Modelo.consultar(true);

//        $timeout(function(){
//            vm.Filtro.consultar().then(function(){
//
//                loading('hide');
//                $timeout(function(){
//                    if ( vm.Filtro.COTA_ID > 0 ) {
//                        var cota = $('[data-cota-id="' + vm.Filtro.COTA_ID + '"]:focusable');
//
//                        cota.focus();
//
//                        $timeout(function(){
//                            if ( vm.Filtro.COTA_OPEN == 1 && gScope.Cota.SELECTED.ID != undefined ) {
//                                vm.Cota.dblPick(vm.Cota.SELECTED);
//                            }
//                        },100);
//                    }
//                },50);
//
//            });
//
//        },50);
//        
	}   
  