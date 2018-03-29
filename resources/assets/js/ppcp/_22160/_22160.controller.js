angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        'gScope',
        'ConsumoBaixarFiltro',
        'ConsumoBaixarProduto',
        'ConsumoBaixarTalao',
        'ConsumoBaixarBalanca',
        'ConsumoBaixadoFiltro',
        'ConsumoBaixadoProduto',
        'ConsumoBaixadoTalao',
        'ConsumoBaixadoTransacao'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        gScope, 
        ConsumoBaixarFiltro, 
        ConsumoBaixarProduto,
        ConsumoBaixarTalao, 
        ConsumoBaixarBalanca,
        ConsumoBaixadoFiltro, 
        ConsumoBaixadoProduto,
        ConsumoBaixadoTalao,
        ConsumoBaixadoTransacao
    ) {

		var vm = this;

		vm.ConsumoBaixarFiltro  = new ConsumoBaixarFiltro();
		vm.ConsumoBaixarTalao   = new ConsumoBaixarTalao();
		vm.ConsumoBaixarProduto = new ConsumoBaixarProduto();
		vm.ConsumoBaixarBalanca = new ConsumoBaixarBalanca();

		vm.ConsumoBaixadoFiltro    = new ConsumoBaixadoFiltro();
		vm.ConsumoBaixadoTalao     = new ConsumoBaixadoTalao();
		vm.ConsumoBaixadoProduto   = new ConsumoBaixadoProduto();
		vm.ConsumoBaixadoTransacao = new ConsumoBaixadoTransacao();


        loading('.main-ctrl');    
        vm.ConsumoBaixarFiltro.consultar().then(function(){
            loading('hide');
        });

	}   
  