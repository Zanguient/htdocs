angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        '$ajax',
        'gScope',
        '$consulta',
        'Historico',
        'RateioCCusto',
        'CCustoAbsorcao'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        $ajax,
        gScope, 
        $consulta,
        Historico,
        RateioCCusto,
        CCustoAbsorcao
    ) {

		var vm     = this;
        
        gScope.Ctrl = this;
        
     
        vm.Historico       = new Historico();
        vm.RateioCCusto    = new RateioCCusto();
        vm.CCustoAbsorcao = new CCustoAbsorcao();
        vm.Consulta        = new $consulta();
        
        vm.RateioCCusto.consultar();
        

        vm.ConsultaCcusto                        = vm.Consulta.getNew(true);
        vm.ConsultaCcusto.componente             = '.consulta-ccusto';
        vm.ConsultaCcusto.model                  = 'vm.ConsultaCcusto';
        vm.ConsultaCcusto.option.label_descricao = 'Centro de Custo:';
        vm.ConsultaCcusto.option.obj_consulta    = '/_20030/api/ccusto';
        vm.ConsultaCcusto.option.tamanho_input   = 'input-maior';
        vm.ConsultaCcusto.option.campos_tabela   = [['MASK', 'C. Custo'],['DESCRICAO','Descrição']];
        vm.ConsultaCcusto.option.obj_ret         = ['MASK', 'DESCRICAO'];
        vm.ConsultaCcusto.compile();
        
        vm.ConsultaCcusto.onSelect = function() {
            vm.RateioCCusto.SELECTED.CCUSTO           = vm.ConsultaCcusto.ID;
            vm.RateioCCusto.SELECTED.CCUSTO_MASK      = vm.ConsultaCcusto.MASK;
            vm.RateioCCusto.SELECTED.CCUSTO_DESCRICAO = vm.ConsultaCcusto.DESCRICAO;
        };
        
        vm.ConsultaCcusto.onClear = function() {
            vm.RateioCCusto.SELECTED.CCUSTO           = '';
            vm.RateioCCusto.SELECTED.CCUSTO_MASK      = '';
            vm.RateioCCusto.SELECTED.CCUSTO_DESCRICAO = '';
        };

        vm.caConsultaCCusto                        = vm.Consulta.getNew(true);
        vm.caConsultaCCusto.componente             = '.ca-consulta-ccusto';
        vm.caConsultaCCusto.model                  = 'vm.caConsultaCCusto';
        vm.caConsultaCCusto.option.label_descricao = 'Centro de Custo:';
        vm.caConsultaCCusto.option.obj_consulta    = '/_20030/api/ccusto';
        vm.caConsultaCCusto.option.tamanho_input   = 'input-maior';
        vm.caConsultaCCusto.option.campos_tabela   = [['MASK', 'C. Custo'],['DESCRICAO','Descrição']];
        vm.caConsultaCCusto.option.obj_ret         = ['MASK', 'DESCRICAO'];
        vm.caConsultaCCusto.compile();
        
        vm.caConsultaCCusto.onSelect = function() {
            vm.CCustoAbsorcao.SELECTED.CCUSTO           = vm.caConsultaCCusto.ID;
            vm.CCustoAbsorcao.SELECTED.CCUSTO_MASK      = vm.caConsultaCCusto.MASK;
            vm.CCustoAbsorcao.SELECTED.CCUSTO_MASKA     = 'A'+vm.caConsultaCCusto.MASK;
            vm.CCustoAbsorcao.SELECTED.CCUSTO_DESCRICAO = vm.caConsultaCCusto.DESCRICAO;
        };
        
        vm.caConsultaCCusto.onClear = function() {
            vm.CCustoAbsorcao.SELECTED.CCUSTO           = '';
            vm.CCustoAbsorcao.SELECTED.CCUSTO_MASK      = '';
            vm.CCustoAbsorcao.SELECTED.CCUSTO_MASKA     = '';
            vm.CCustoAbsorcao.SELECTED.CCUSTO_DESCRICAO = '';
        };



         
            
        $ajax.get('/_31050/api/rateio/tipo').then(function(response){
            sanitizeJson(response);
            vm.rateioTipos = response;
        });

	}   
  