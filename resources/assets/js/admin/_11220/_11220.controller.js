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
        'Filtro'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        $ajax,
        gScope, 
        $consulta,
        Historico,
        Filtro
    ) {

		var vm          = this;
        gScope.Ctrl     = this;
        
        

        vm.Estabelecimento = {};
        vm.Modulo          = {
            DESCRICAO : '',
            INSERINDO : false,
            gravar : function(){
                
                $ajax.post('/_11220/api/modulo/post',{DATA_RETURN:true,DADOS:{DESCRICAO:vm.Modulo.DESCRICAO}}).then(function(response){

                    vm.Modulo.DESCRICAO = '';
                    vm.Modulo.INSERINDO = false;
                    
                    vm.Filtro.merge(response.DATA_RETURN);

                });

            }
        };
        vm.Periodo         = {};
        
        vm.RESPONSE         = [];
        vm.ESTABELECIMENTOS = [];
        vm.MODULOS          = [];
        vm.PERIODOS         = [];
        
        
        /**
         * Importa a função helper selectById para ser utlizado na view
         */
        vm.selectById = selectById;        
        
        /**
         * Inicializa as factorys
         */
        vm.Filtro      = new Filtro();
        vm.Historico = new Historico();
        vm.Consulta  = new $consulta();
        
        /**
         * Realiza a consulta inicial
         */
        vm.Filtro.consultar().then(function(response){
            vm.Filtro.merge(response);
        });
        

        vm.PeriodoSumbit = function(){
        
            var periodos = [];
            angular.copy(vm.PERIODO_FILTERED,periodos);
            
            for ( var i in periodos ) {
                var periodo = periodos[i];
                
                
                periodo.DATAINICIAL = moment(vm.Filtro.DATA_1).format('YYYY.MM.DD');
                periodo.DATAFINAL = moment(vm.Filtro.DATA_2).format('YYYY.MM.DD');
        
            }
            
            $ajax.post('/_11220/api/periodo/post',{DATA_RETURN:true,DADOS:periodos}).then(function(response){
                
                vm.Filtro.merge(response.DATA_RETURN);
    
                resolve(response);

            });
            
            
        };

        vm.PeriodoFilter = function (item) {
            
            var ret = true;
            
            var filter_estabelecimento = false;
            
            if ( vm.Estabelecimento.SELECTED != undefined && !isEmpty(vm.Estabelecimento.SELECTED) ) {
                filter_estabelecimento = true;
            }
            
            
            if ( filter_estabelecimento && vm.Estabelecimento.SELECTED.ID != item.ESTABELECIMENTO_ID ) {
                ret = false;
            }
            
            
            var filter_modulo = false;
            
            if ( vm.Modulo.SELECTED != undefined && !isEmpty(vm.Modulo.SELECTED) ) {
                filter_modulo = true;
            }
            
            
            if ( filter_modulo && vm.Modulo.SELECTED.ID != item.MODULO_ID ) {
                ret = false;
            }
            
            return ret;
        };
	}   
  