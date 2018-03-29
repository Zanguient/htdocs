/**
 * _20100 - Relatorio de Extrato de Caixa/Bancos
 */

;(function(angular) {

    var Ctrl = function($scope, $ajax, $timeout, gScope, $consulta) {

        var vm = this;
		vm.DADOS = [];
		vm.DADOS.CONSULTA = '';

		vm.Consulta     = new $consulta();
        gScope.Consulta = vm.Consulta;

        vm.Consulta_Banco = vm.Consulta.getNew();

        vm.Consulta_Banco.componente             = '.consulta_banco',
        vm.Consulta_Banco.model                  = 'vm.Consulta_Banco',
        vm.Consulta_Banco.option.label_descricao = 'GP1:',
        vm.Consulta_Banco.option.obj_consulta    = '/_11140/Consultar',
        vm.Consulta_Banco.option.tamanho_Input   = 'input-medio';
        vm.Consulta_Banco.option.class           = 'consulta_gp_grup';
        vm.Consulta_Banco.option.tamanho_tabela  = 250;
        vm.Consulta_Banco.compile();

        function init(){
			
        }

		
        init();        
    };

    Ctrl.$inject = [
		'$scope',
		'$ajax',
		'$timeout',
		'gScope',
		'$consulta'
	];
 
    angular
    .module    ('app' , ['angular.filter','vs-repeat','gc-ajax','gc-form','gc-find','gc-transform', 'gc-utils'])
    .controller('Ctrl', Ctrl);
        
})(angular);

 ;(function($){


})(jQuery);