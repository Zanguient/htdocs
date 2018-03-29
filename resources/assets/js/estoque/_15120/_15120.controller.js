angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        'Estoque',
        '$consulta',
        'gScope'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        Estoque,
        $consulta,
        gScope
    ) {

		var vm = this;

		vm.Estoque = new Estoque();
        vm.SALDO = {STATUS : true};

        vm.Consulta = new $consulta();
        vm.Consulta_Familia  = vm.Consulta.getNew();

        vm.Consulta_Familia.componente              = '.famila-estoque',
        vm.Consulta_Familia.model                   = 'vm.Consulta_Familia',
        vm.Consulta_Familia.option.label_descricao  = 'Família:',
        vm.Consulta_Familia.option.obj_consulta     = '/_15120/api/familia',
        vm.Consulta_Familia.option.tamanho_input    = 'input-medio';
        vm.Consulta_Familia.option.class            = 'Consulta_Familia';
        vm.Consulta_Familia.option.tamanho_tabela   = 200;
        vm.Consulta_Familia.option.required         = false;

        vm.Consulta_Familia.compile();

        gScope.Consulta_Familia = vm.Consulta_Familia; 
        gScope.SALDO = vm.SALDO;


	}   
  