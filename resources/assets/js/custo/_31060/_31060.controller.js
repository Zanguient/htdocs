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
        'Regra'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        gScope, 
        $consulta,
        Historico,
        Regra
    ) {

		var vm          = this;
        gScope.Ctrl     = this;
        
        vm.Regra   = new Regra();
        vm.Historico    = new Historico();
        vm.Consulta     = new $consulta();
        
//        vm.Regra.consultarDetalhe();
        vm.Regra.consultar();
        
        
        vm.ConsultaFamiliaAgrup                        = vm.Consulta.getNew(true);
        vm.ConsultaFamiliaAgrup.componente             = '.consulta-familia-agrup';
        vm.ConsultaFamiliaAgrup.model                  = 'vm.ConsultaFamiliaAgrup';
        vm.ConsultaFamiliaAgrup.option.label_descricao = 'Agrup. Família:';
        vm.ConsultaFamiliaAgrup.option.obj_consulta    = '/_27010/api/familia';
        vm.ConsultaFamiliaAgrup.option.tamanho_input   = 'input-maior';
        vm.ConsultaFamiliaAgrup.option.campos_tabela   = [['ID', 'Id'],['DESCRICAO','Descrição']];
        vm.ConsultaFamiliaAgrup.option.obj_ret         = ['ID','DESCRICAO'];
        vm.ConsultaFamiliaAgrup.setDataRequest({STATUS: 1});
        vm.ConsultaFamiliaAgrup.compile();
        
        vm.ConsultaFamiliaAgrup.onSelect = function() {
            vm.Regra.SELECTED.FAMILIA_PRODUCAO           = vm.ConsultaFamiliaAgrup.ID;
            vm.Regra.SELECTED.FAMILIA_PRODUCAO_DESCRICAO = vm.ConsultaFamiliaAgrup.DESCRICAO;
        };
        
        vm.ConsultaFamiliaAgrup.onClear = function() {
            vm.Regra.SELECTED.FAMILIA_PRODUCAO           = '';
            vm.Regra.SELECTED.FAMILIA_PRODUCAO_DESCRICAO = '';
        };        
        
        
        
        vm.ConsultaFamilia                        = vm.Consulta.getNew(true);
        vm.ConsultaFamilia.componente             = '.consulta-familia';
        vm.ConsultaFamilia.model                  = 'vm.ConsultaFamilia';
        vm.ConsultaFamilia.option.label_descricao = 'Família:';
        vm.ConsultaFamilia.option.obj_consulta    = '/_27010/api/familia';
        vm.ConsultaFamilia.option.tamanho_input   = 'input-maior';
        vm.ConsultaFamilia.option.campos_tabela   = [['ID', 'Id'],['DESCRICAO','Descrição']];
        vm.ConsultaFamilia.option.obj_ret         = ['ID','DESCRICAO'];
        vm.ConsultaFamilia.setDataRequest({STATUS: 1});
        vm.ConsultaFamilia.compile();
        
        vm.ConsultaFamilia.onSelect = function() {
            vm.Regra.SELECTED.FAMILIA_ID        = vm.ConsultaFamilia.ID;
            vm.Regra.SELECTED.FAMILIA_DESCRICAO = vm.ConsultaFamilia.DESCRICAO;
        };
        
        vm.ConsultaFamilia.onClear = function() {
            vm.Regra.SELECTED.FAMILIA_ID        = '';
            vm.Regra.SELECTED.FAMILIA_DESCRICAO = '';
        };        
        
        
        
        vm.ConsultaGp                        = vm.Consulta.getNew(true);
        vm.ConsultaGp.componente             = '.consulta-gp';
        vm.ConsultaGp.model                  = 'vm.ConsultaGp';
        vm.ConsultaGp.option.label_descricao = 'Gp:';
        vm.ConsultaGp.option.obj_consulta    = '/_22030/api/gp';
        vm.ConsultaGp.option.tamanho_input   = 'input-maior';
        vm.ConsultaGp.option.campos_tabela   = [['GP_ID', 'ID'],['GP_DESCRICAO','GRUPO DE PRODUÇÃO']];
        vm.ConsultaGp.option.obj_ret         = ['GP_ID', 'GP_DESCRICAO'];
        vm.ConsultaGp.setDataRequest({STATUS: 1});
        vm.ConsultaGp.compile();
        
        vm.ConsultaGp.onSelect = function() {
            vm.Regra.SELECTED.GP_ID        = vm.ConsultaGp.GP_ID;
            vm.Regra.SELECTED.GP_DESCRICAO = vm.ConsultaGp.GP_DESCRICAO;
        };
        
        vm.ConsultaGp.onClear = function() {
            vm.Regra.SELECTED.GP_ID        = '';
            vm.Regra.SELECTED.GP_DESCRICAO = '';
        };        
        
        
        
        vm.ConsultaPerfil                        = vm.Consulta.getNew(true);
        vm.ConsultaPerfil.componente             = '.consulta-perfil';
        vm.ConsultaPerfil.model                  = 'vm.ConsultaPerfil';
        vm.ConsultaPerfil.option.label_descricao = 'Perfil UP:';
        vm.ConsultaPerfil.option.obj_consulta    = '/_11200/api/perfil';
        vm.ConsultaPerfil.option.tamanho_input   = 'input-maior';
        vm.ConsultaPerfil.option.campos_tabela   = [['PERFIL_TABELA_ID', 'Id'],['PERFIL_DESCRICAO','Unidade Produtiva']];
        vm.ConsultaPerfil.option.obj_ret         = ['PERFIL_TABELA_ID', 'PERFIL_DESCRICAO'];
        vm.ConsultaPerfil.setDataRequest({STATUS: 1,TABELA:'UP'});
        vm.ConsultaPerfil.compile();
        
        vm.ConsultaPerfil.onSelect = function() {
            vm.Regra.SELECTED.PERFIL_UP           = vm.ConsultaPerfil.PERFIL_TABELA_ID;
            vm.Regra.SELECTED.PERFIL_UP_DESCRICAO = vm.ConsultaPerfil.PERFIL_DESCRICAO;
        };
        
        vm.ConsultaPerfil.onClear = function() {
            vm.Regra.SELECTED.PERFIL_UP           = '';
            vm.Regra.SELECTED.PERFIL_UP_DESCRICAO = '';
        };        
        
        
        
        vm.ConsultaUp1                        = vm.Consulta.getNew(true);
        vm.ConsultaUp1.componente             = '.consulta-up-1';
        vm.ConsultaUp1.model                  = 'vm.ConsultaUp1';
        vm.ConsultaUp1.option.label_descricao = '1ª UP:';
        vm.ConsultaUp1.option.obj_consulta    = '/_22030/api/up';
        vm.ConsultaUp1.option.tamanho_input   = 'input-maior';
        vm.ConsultaUp1.option.campos_tabela   = [['UP_ID', 'Id'],['UP_DESCRICAO','Unidade Produtiva']];
        vm.ConsultaUp1.option.obj_ret         = ['UP_ID', 'UP_DESCRICAO'];
        vm.ConsultaUp1.setDataRequest({STATUS: 1});
        vm.ConsultaUp1.compile();
        
        vm.ConsultaUp1.onSelect = function() {
            vm.Regra.SELECTED.UP_PADRAO1           = vm.ConsultaUp1.UP_ID;
            vm.Regra.SELECTED.UP_PADRAO1_DESCRICAO = vm.ConsultaUp1.UP_DESCRICAO;
        };
        
        vm.ConsultaUp1.onClear = function() {
            vm.Regra.SELECTED.UP_PADRAO1           = '';
            vm.Regra.SELECTED.UP_PADRAO1_DESCRICAO = '';
        };        
        
        
        
        
        vm.ConsultaUp2                        = vm.Consulta.getNew(true);
        vm.ConsultaUp2.componente             = '.consulta-up-2';
        vm.ConsultaUp2.model                  = 'vm.ConsultaUp2';
        vm.ConsultaUp2.option.label_descricao = '2ª UP:';
        vm.ConsultaUp2.option.obj_consulta    = '/_22030/api/up';
        vm.ConsultaUp2.option.tamanho_input   = 'input-maior';
        vm.ConsultaUp2.option.campos_tabela   = [['UP_ID', 'Id'],['UP_DESCRICAO','Unidade Produtiva']];
        vm.ConsultaUp2.option.obj_ret         = ['UP_ID', 'UP_DESCRICAO'];
        vm.ConsultaUp2.option.required        = false;
        vm.ConsultaUp2.setDataRequest({STATUS: 1});
        vm.ConsultaUp2.compile();
        
        vm.ConsultaUp2.onSelect = function() {
            vm.Regra.SELECTED.UP_PADRAO2           = vm.ConsultaUp2.UP_ID;
            vm.Regra.SELECTED.UP_PADRAO2_DESCRICAO = vm.ConsultaUp2.UP_DESCRICAO;
        };
        
        vm.ConsultaUp2.onClear = function() {
            vm.Regra.SELECTED.UP_PADRAO2           = '';
            vm.Regra.SELECTED.UP_PADRAO2_DESCRICAO = '';
        };        
        
        
        
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
            vm.Regra.SELECTED.CCUSTO           = vm.ConsultaCCusto.ID;
            vm.Regra.SELECTED.CCUSTO_MASK      = vm.ConsultaCCusto.MASK;
            vm.Regra.SELECTED.CCUSTO_DESCRICAO = vm.ConsultaCCusto.DESCRICAO;
        };
        
        vm.ConsultaCCusto.onClear = function() {
            vm.Regra.SELECTED.CCUSTO           = '';
            vm.Regra.SELECTED.CCUSTO_MASK      = '';
            vm.Regra.SELECTED.CCUSTO_DESCRICAO = '';
        };        
	}   
  