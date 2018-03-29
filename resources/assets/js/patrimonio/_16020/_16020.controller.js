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
        'Tipo'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        gScope, 
        $consulta,
        Historico,
        Tipo
    ) {

		var vm          = this;
        gScope.Ctrl     = this;
        
        
        /**
         * Importa a função helper selectById para ser utlizado na view
         */
        vm.selectById = selectById;        
        
        /**
         * Inicializa as factorys
         */
        vm.Tipo      = new Tipo();
        vm.Historico = new Historico();
        vm.Consulta  = new $consulta();
        
        /**
         * Realiza a consulta inicial
         */
        vm.Tipo.consultar();
        
        
        /**
         * Inicializa e configura a consulta C. Contábil
         */
        vm.ConsultaCContabil                        = vm.Consulta.getNew(true);
        vm.ConsultaCContabil.componente             = '.consulta-ccontabil';
        vm.ConsultaCContabil.model                  = 'vm.ConsultaCContabil';
        vm.ConsultaCContabil.option.label_descricao = 'C. Contábil Crédito:';
        vm.ConsultaCContabil.option.obj_consulta    = '/_17010/api/ccontabil';
        vm.ConsultaCContabil.option.tamanho_input   = 'input-maior';
        vm.ConsultaCContabil.option.campos_tabela   = [['MASK', 'C. Contábil'],['DESCRICAO','Descrição']];
        vm.ConsultaCContabil.option.obj_ret         = ['MASK', 'DESCRICAO'];
        vm.ConsultaCContabil.compile();
        vm.ConsultaCContabil.setDataRequest({CCONTABIL_TIPO: 'analitica'});
        gScope.ConsultaCContabil = vm.ConsultaCContabil;
        
        vm.ConsultaCContabil.onSelect = function() {
            vm.Tipo.SELECTED.CCONTABIL           = vm.ConsultaCContabil.CONTA;
            vm.Tipo.SELECTED.CCONTABIL_MASK      = vm.ConsultaCContabil.MASK;
            vm.Tipo.SELECTED.CCONTABIL_DESCRICAO = vm.ConsultaCContabil.DESCRICAO;
        };
        
        vm.ConsultaCContabil.onClear = function() {
            vm.Tipo.SELECTED.CCONTABIL           = '';
            vm.Tipo.SELECTED.CCONTABIL_MASK      = '';
            vm.Tipo.SELECTED.CCONTABIL_DESCRICAO = '';
        };        
       
        
        /**
         * Inicializa e configura a consulta C. Contábil Debito
         */
        vm.ConsultaCContabilDebito                        = vm.Consulta.getNew(true);
        vm.ConsultaCContabilDebito.componente             = '.consulta-ccontabil-debito';
        vm.ConsultaCContabilDebito.model                  = 'vm.ConsultaCContabilDebito';
        vm.ConsultaCContabilDebito.option.label_descricao = 'C. Contábil Débito:';
        vm.ConsultaCContabilDebito.option.obj_consulta    = '/_17010/api/ccontabil';
        vm.ConsultaCContabilDebito.option.tamanho_input   = 'input-maior';
        vm.ConsultaCContabilDebito.option.campos_tabela   = [['MASK', 'C. Contábil'],['DESCRICAO','Descrição']];
        vm.ConsultaCContabilDebito.option.obj_ret         = ['MASK', 'DESCRICAO'];
        vm.ConsultaCContabilDebito.compile();
        vm.ConsultaCContabilDebito.setDataRequest({CCONTABIL_TIPO: 'analitica'});
        gScope.ConsultaCContabilDebito = vm.ConsultaCContabilDebito;
        
        vm.ConsultaCContabilDebito.onSelect = function() {
            vm.Tipo.SELECTED.CCONTABIL_DEBITO           = vm.ConsultaCContabilDebito.CONTA;
            vm.Tipo.SELECTED.CCONTABIL_DEBITO_MASK      = vm.ConsultaCContabilDebito.MASK;
            vm.Tipo.SELECTED.CCONTABIL_DEBITO_DESCRICAO = vm.ConsultaCContabilDebito.DESCRICAO;
        };
        
        vm.ConsultaCContabilDebito.onClear = function() {
            vm.Tipo.SELECTED.CCONTABIL_DEBITO           = '';
            vm.Tipo.SELECTED.CCONTABIL_DEBITO_MASK      = '';
            vm.Tipo.SELECTED.CCONTABIL_DEBITO_DESCRICAO = '';
        };        


        /**
         * Cria o array de objetos com os tipos de gasto
         */
        vm.TIPOS_GASTO = [
            {   
                ID : 0,
                DESCRICAO : 'INDEFINIDO'
            },
            {   
                ID : 1,
                DESCRICAO : 'CUSTO/DFESPESA'
            }
        ];

	}   
  