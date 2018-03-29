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
        'RateioCContabil'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        $ajax,
        gScope, 
        $consulta,
        Historico,
        RateioCContabil
    ) {

		var vm             = this;
        gScope.Ctrl        = this;
        
        vm.RateioCContabil = new RateioCContabil();
        vm.Historico       = new Historico();
        vm.Consulta        = new $consulta();
        
        vm.RateioCContabil.consultar();
        
        vm.ConsultaCContabil                        = vm.Consulta.getNew(true);
        vm.ConsultaCContabil.componente             = '.consulta-ccontabil';
        vm.ConsultaCContabil.model                  = 'vm.ConsultaCContabil';
        vm.ConsultaCContabil.option.label_descricao = 'C. Contábil:';
        vm.ConsultaCContabil.option.obj_consulta    = '/_17010/api/ccontabil';
        vm.ConsultaCContabil.option.tamanho_input   = 'input-maior';
        vm.ConsultaCContabil.option.campos_tabela   = [['MASK', 'C. Contábil'],['DESCRICAO','Descrição']];
        vm.ConsultaCContabil.option.obj_ret         = ['MASK', 'DESCRICAO'];
        vm.ConsultaCContabil.setDataRequest({CCONTABIL_TIPO: 'analitica'});
        vm.ConsultaCContabil.compile();
        
        vm.ConsultaCContabil.onSelect = function() {
            vm.RateioCContabil.SELECTED.CCONTABIL           = vm.ConsultaCContabil.CONTA;
            vm.RateioCContabil.SELECTED.CCONTABIL_MASK      = vm.ConsultaCContabil.MASK;
            vm.RateioCContabil.SELECTED.CCONTABIL_DESCRICAO = vm.ConsultaCContabil.DESCRICAO;
        };
        
        vm.ConsultaCContabil.onClear = function() {
            vm.RateioCContabil.SELECTED.CCONTABIL           = '';
            vm.RateioCContabil.SELECTED.CCONTABIL_MASK      = '';
            vm.RateioCContabil.SELECTED.CCONTABIL_DESCRICAO = '';
        };
              
            
        $ajax.get('/_31050/api/rateio/tipo').then(function(response){
            sanitizeJson(response);
            vm.rateioTipos = response;
        });
	}   
  