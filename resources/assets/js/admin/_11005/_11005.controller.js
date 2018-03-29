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
        'Parametro',
        'ParametroDetalhe'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        gScope, 
        $consulta,
        Historico,
        Parametro,
        ParametroDetalhe
    ) {

		var vm          = this;
        gScope.Ctrl     = this;
        
        vm.Parametro        = new Parametro();
        vm.ParametroDetalhe = new ParametroDetalhe();
        
        vm.Historico    = new Historico();
        vm.Consulta     = new $consulta();
      
        
        
        vm.ConsultaParametroTabela                        = vm.Consulta.getNew(true);
        vm.ConsultaParametroTabela.componente             = '.consulta-parametro-tabela';
        vm.ConsultaParametroTabela.model                  = 'vm.ConsultaParametroTabela';
        vm.ConsultaParametroTabela.option.label_descricao = 'Tabela:';
        vm.ConsultaParametroTabela.option.obj_consulta    = '/_11005/api/parametro/tabela';
        vm.ConsultaParametroTabela.option.campos_tabela   = [['TABELA', 'Tabela']];
        vm.ConsultaParametroTabela.option.obj_ret         = ['TABELA'];
        vm.ConsultaParametroTabela.compile();
        
        vm.Parametro.TABELA = 'SISTEMA';
        vm.Parametro.consultar(vm.Parametro.TABELA);
        vm.ConsultaParametroTabela.Input.value = vm.Parametro.TABELA;
        vm.ConsultaParametroTabela.Input.readonly             = true;
        vm.ConsultaParametroTabela.btn_apagar_filtro.visivel  = true;
        vm.ConsultaParametroTabela.btn_apagar_filtro.disabled = false;
        vm.ConsultaParametroTabela.btn_filtro.visivel         = false;            
        vm.ConsultaParametroTabela.item.selected = true;           
        
        
        vm.ConsultaParametroTabela.onSelect = function() {
            
            vm.Parametro.DADOS = [];
            vm.ParametroDetalhe.DADOS = [];
            
            vm.Parametro.SELECTED = {};
            vm.ParametroDetalhe.SELECTED = {};
            
            vm.Parametro.TABELA = vm.ConsultaParametroTabela.TABELA;
            
            if ( vm.Parametro.VISUALIZACAO == 2 && vm.Parametro.TABELA != 'SISTEMA' ) {
                vm.ParametroDetalhe.consultar();
            } else {
                vm.Parametro.consultar();
            }
        };
        
        vm.ConsultaParametroTabela.onClear = function() {
            vm.Parametro.TABELA = '';

            vm.Parametro.DADOS = [];
            vm.ParametroDetalhe.DADOS = [];
            
            vm.Parametro.SELECTED = {};
            vm.ParametroDetalhe.SELECTED = {};
        };      

	}   
  