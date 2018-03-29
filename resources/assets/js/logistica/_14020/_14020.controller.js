if(window.name.length > 0) {

    $('.navbar-toggle').remove();
//    $('.navbar-left').text('Geração de Remessa de Componente');
    $('.navbar-right').remove();
    $('.navbar-brand' ).attr('href','javascript:void(0);');
    $('.duplicar-tela-mobile').remove();
    $('.go-fullscreen-mobile').remove();
        
}

angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        '$sce',
        'gScope',
        '$consulta',
        'Historico',
        'Cte',
        'Comparar',
        'Simulador',
        'Frete'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        $sce,
        gScope, 
        $consulta,
        Historico,
        Cte,
        Comparar,
        Simulador,
        Frete
    ) {

		var vm          = this;
        gScope.Ctrl     = this;
        
        vm.Consulta     = new $consulta();
        vm.Cte          = new Cte();
        vm.Comparar     = new Comparar();
        vm.Simulador    = new Simulador();
        vm.Frete        = new Frete();
        vm.Historico    = new Historico();
        
        vm.trustedHtml = function (plainText) {
            return $sce.trustAsHtml(plainText);
        };        
        
        
        var ConsultaFreteTransportadora = function(componente,model) {
            if ( $(".consulta-frete-transportadora" + (componente != undefined ? '-'+componente : '')).length ) {
                var trans_model = 'ConsultaFreteTransportadora'+ (model != undefined ? model : '');
                
                vm[trans_model]                        = vm.Consulta.getNew(true);
                vm[trans_model].componente             = '.consulta-frete-transportadora'+ (componente != undefined ? '-'+componente : '');
                vm[trans_model].model                  = 'vm.'+trans_model;
                vm[trans_model].option.label_descricao = 'Transportadora:';
                vm[trans_model].option.obj_consulta    = '/_14020/api/transportadora';
                vm[trans_model].option.tamanho_input   = 'input-maior';
                vm[trans_model].option.tamanho_tabela  = 650;
                vm[trans_model].option.campos_tabela   = [['TRANSPORTADORA_ID', 'Id'],['RAZAOSOCIAL','Razão Social'],['NOMEFANTASIA', 'Nome Fantasia'],['CLASSIFICACAO','Classificação']];
                vm[trans_model].option.obj_ret         = ['TRANSPORTADORA_ID','RAZAOSOCIAL'];
                vm[trans_model].option.required        = false;
                vm[trans_model].compile();
                
                return vm[trans_model];
            }
        };
        
        ConsultaFreteTransportadora();
        
        var ConsultaFreteTransportadoraSimuladorItens = ConsultaFreteTransportadora('simulador-itens','SimuladorItens');
            
            if ( $(".consulta-frete-transportadora-simulador-itens").length > 0) {
                ConsultaFreteTransportadoraSimuladorItens.option.required = true;
            }
        
        var ConsultaFreteTransportadoraSimulador = ConsultaFreteTransportadora('simulador','Simulador');
        if ( $(".consulta-frete-transportadora-simulador").length > 0) {
            ConsultaFreteTransportadoraSimulador.onSelect = function() {
                vm.Frete.DADOS.TRANSPORTADORA_ID          = ConsultaFreteTransportadoraSimulador.TRANSPORTADORA_ID;
                vm.Frete.DADOS.TRANSPORTADORA_RAZAOSOCIAL = ConsultaFreteTransportadoraSimulador.RAZAOSOCIAL;
                vm.Frete.calcular(vm.Frete.ORIGEM,vm.Frete.ORIGEM_ID,vm.Frete.DADOS.TRANSPORTADORA_ID,null,true,true);
            };
            ConsultaFreteTransportadoraSimulador.onClear = function() {
                vm.Frete.DADOS = {};
                vm.Frete.DADOS.TRANSPORTADORA_ID          = '';
                vm.Frete.DADOS.TRANSPORTADORA_RAZAOSOCIAL = '';
            };               
        }
        
        
        var componente = '.consulta-cliente-simulador-itens';
        if ( $(componente).length ) {
            var trans_model = 'ConsultaClienteSimuladorItens';

            vm[trans_model]                        = vm.Consulta.getNew(true);
            vm[trans_model].componente             = componente;
            vm[trans_model].model                  = 'vm.'+trans_model;
            vm[trans_model].option.label_descricao = 'Cliente:';
            vm[trans_model].option.obj_consulta    = '/_14020/api/cliente';
            vm[trans_model].option.tamanho_input   = 'input-maior';
            vm[trans_model].option.tamanho_tabela  = 780;
            vm[trans_model].option.campos_tabela   = [['ID', 'Id'],['RAZAOSOCIAL','Razão Social'],['NOMEFANTASIA', 'Nome Fantasia'],['UF','UF'],['CIDADE','Cidade']];
            vm[trans_model].option.obj_ret         = ['ID','RAZAOSOCIAL'];
            vm[trans_model].option.required        = false;
            vm[trans_model].compile();

            vm[trans_model].onSelect = function() {


                vm.ConsultaCidadeSimuladorItens.Input.value = vm.ConsultaClienteSimuladorItens.UF + ' - ' + vm.ConsultaClienteSimuladorItens.CIDADE;
                vm.ConsultaCidadeSimuladorItens.Input.readonly             = true;
                vm.ConsultaCidadeSimuladorItens.btn_apagar_filtro.visivel  = true;
                vm.ConsultaCidadeSimuladorItens.btn_apagar_filtro.disabled = false;
                vm.ConsultaCidadeSimuladorItens.btn_filtro.visivel         = false;            
                vm.ConsultaCidadeSimuladorItens.item.selected              = true;  

            };

            vm[trans_model].onClear = function() {
                vm.ConsultaCidadeSimuladorItens.apagar(true);
            };  

        }
        
        var componente = '.consulta-cidade-simulador-itens';
        if ( $(componente).length ) {
            var trans_model = 'ConsultaCidadeSimuladorItens';

            vm[trans_model]                        = vm.Consulta.getNew(true);
            vm[trans_model].componente             = componente;
            vm[trans_model].model                  = 'vm.'+trans_model;
            vm[trans_model].option.label_descricao = 'Cidade:';
            vm[trans_model].option.obj_consulta    = '/_14020/api/cidade';
//            vm[trans_model].option.tamanho_input   = 'input-maior';
//            vm[trans_model].option.tamanho_tabela  = 780;
            vm[trans_model].option.campos_tabela   = [['UF', 'UF'],['DESCRICAO','Cidade']];
            vm[trans_model].option.obj_ret         = ['UF','DESCRICAO'];
            vm[trans_model].option.required        = true;
            vm[trans_model].compile();

            vm[trans_model].onSelect = function() {

            };

            vm[trans_model].onClear = function() {

                vm.ConsultaClienteSimuladorItens.Input.value = '';
                vm.ConsultaClienteSimuladorItens.Input.readonly             = false;
                vm.ConsultaClienteSimuladorItens.btn_apagar_filtro.visivel  = false;
                vm.ConsultaClienteSimuladorItens.btn_apagar_filtro.disabled = true;
                vm.ConsultaClienteSimuladorItens.btn_filtro.visivel         = true;   
                vm.ConsultaClienteSimuladorItens.btn_filtro.disabled        = false;         
                vm.ConsultaClienteSimuladorItens.item.selected              = false;              
            }; 
        }        

        

         

        
        $scope.$watch('vm.FRETE_ID', function (newValue, oldValue, scope) {
            
            if ( newValue > 0 ) {
                    vm.Frete.consultar(newValue);
            } 
        });
        
        $scope.$watch('vm.Frete.CALCULAR', function (newValue, oldValue, scope) {
            
            if ( newValue == 1 ) {
                vm.Frete.calcular(vm.Frete.ORIGEM,vm.Frete.ORIGEM_ID,null,null,true,true);
            } 
        });
	}   
  