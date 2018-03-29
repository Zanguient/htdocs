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
        
//        vm.RateioTipo.consultarDetalhe();
        vm.RateioTipo.consultar();
        
        
        vm.RateioTipo.consultar();
        
        vm.ConsultaUnidadeMedida                        = vm.Consulta.getNew(true);
        vm.ConsultaUnidadeMedida.componente             = '.consulta-unidade-medida';
        vm.ConsultaUnidadeMedida.model                  = 'vm.ConsultaUnidadeMedida';
        vm.ConsultaUnidadeMedida.option.label_descricao = 'Unidade de Medida:';
        vm.ConsultaUnidadeMedida.option.obj_consulta    = '/_20120/api/unidade-medida';
        vm.ConsultaUnidadeMedida.option.tamanho_input   = 'input-maior';
        vm.ConsultaUnidadeMedida.option.campos_tabela   = [['ID', 'Id'],['DESCRICAO','Descrição'],['SIGLA','Sigla'],['PODE_FRACIONAR_DESCRICAO','Fraciona']];
        vm.ConsultaUnidadeMedida.option.obj_ret         = ['SIGLA','DESCRICAO'];
        vm.ConsultaUnidadeMedida.compile();
        
        vm.ConsultaUnidadeMedida.onSelect = function() {
            vm.RateioTipo.SELECTED.UM           = vm.ConsultaUnidadeMedida.SIGLA;
            vm.RateioTipo.SELECTED.UM_DESCRICAO = vm.ConsultaUnidadeMedida.DESCRICAO;
            vm.RateioTipo.SELECTED.UM_ID        = vm.ConsultaUnidadeMedida.ID;
        };
        
        vm.ConsultaUnidadeMedida.onClear = function() {
            vm.RateioTipo.SELECTED.UM           = '';
            vm.RateioTipo.SELECTED.UM_DESCRICAO = '';
            vm.RateioTipo.SELECTED.UM_ID = '';
        };        
        

	}   
  