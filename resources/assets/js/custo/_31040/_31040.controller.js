angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        'gScope',
        '$consulta',
        'Historico',
        'RateioTipo'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        gScope, 
        $consulta,
        Historico,
        RateioTipo
    ) {

		var vm          = this;
        gScope.Ctrl     = this;
        
        vm.RateioTipo   = new RateioTipo();
        vm.Historico    = new Historico();
        vm.Consulta     = new $consulta();
        
        vm.RateioTipo.consultarDetalhe();
        vm.RateioTipo.consultar();
        
        vm.ConsultaCCusto                        = vm.Consulta.getNew(true);
        vm.ConsultaCCusto.componente             = '.consulta-ccusto';
        vm.ConsultaCCusto.model                  = 'vm.ConsultaCCusto';
        vm.ConsultaCCusto.option.label_descricao = 'Centro de Custo:';
        vm.ConsultaCCusto.option.obj_consulta    = '/_20030/api/ccusto';
        vm.ConsultaCCusto.option.tamanho_input   = 'input-maior';
        vm.ConsultaCCusto.option.campos_tabela   = [['MASK', 'C. Custo'],['DESCRICAO','Descrição']];
        vm.ConsultaCCusto.option.obj_ret         = ['MASK', 'DESCRICAO'];
        vm.ConsultaCCusto.compile();
        
        vm.ConsultaCCusto.onSelect = function() {
            vm.RateioTipo.SELECTED.CCUSTO           = vm.ConsultaCCusto.ID;
            vm.RateioTipo.SELECTED.CCUSTOA          = 'A'+vm.ConsultaCCusto.ID;
            vm.RateioTipo.SELECTED.CCUSTO_MASK      = vm.ConsultaCCusto.MASK;
            vm.RateioTipo.SELECTED.CCUSTO_DESCRICAO = vm.ConsultaCCusto.DESCRICAO;
        };
        
        vm.ConsultaCCusto.onClear = function() {
            vm.RateioTipo.SELECTED.CCUSTO           = '';
            vm.RateioTipo.SELECTED.CCUSTOA          = '';
            vm.RateioTipo.SELECTED.CCUSTO_MASK      = '';
            vm.RateioTipo.SELECTED.CCUSTO_DESCRICAO = '';
        };        
        

	}   
  