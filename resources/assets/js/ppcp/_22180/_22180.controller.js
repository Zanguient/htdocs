angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        'gScope',
        '$consulta',
        'Filtro',
        'Talao',
        'TalaoConsumo',
        'TalaoHistorico',
        'TalaoDetalhe',
        'TalaoProduzir',
        'Operador',
        'ServerEvent', 
        'TalaoProduzido',
        'ColaboradorCentroDeTrabalho' 
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        gScope, 
        $consulta,
        Filtro,
        Talao,
        TalaoConsumo,
        TalaoHistorico,
        TalaoDetalhe,
        TalaoProduzir,
        Operador,
        ServerEvent, 
        TalaoProduzido,
        ColaboradorCentroDeTrabalho 
    ) {

		var vm = this;

        vm.Clock      = Clock;
        vm.Consulta   = new $consulta();
        
        vm.ConsultaEstabelecimento                             = vm.Consulta.getNew(true);
        vm.ConsultaEstabelecimento.componente                  = '.consulta-estabelecimento';
        vm.ConsultaEstabelecimento.model                       = 'vm.ConsultaEstabelecimento';
        vm.ConsultaEstabelecimento.option.label_descricao      = 'Estabelecimento:';
        vm.ConsultaEstabelecimento.option.obj_consulta         = '/_11020/api/estabelecimento';
        vm.ConsultaEstabelecimento.option.tamanho_input        = 'input-maior';
        vm.ConsultaEstabelecimento.option.tamanho_tabela       = 427;
        vm.ConsultaEstabelecimento.option.campos_tabela        = [['ESTABELECIMENTO_ID', 'ID'],['ESTABELECIMENTO_NOMEFANTASIA','NOME FANTASIA']];
        vm.ConsultaEstabelecimento.option.obj_ret              = ['ESTABELECIMENTO_ID', 'ESTABELECIMENTO_NOMEFANTASIA'];
        vm.ConsultaEstabelecimento.compile();
        gScope.ConsultaEstabelecimento = vm.ConsultaEstabelecimento;
        
        vm.ConsultaGp                             = vm.Consulta.getNew(true);
        vm.ConsultaGp.componente                  = '.consulta-gp';
        vm.ConsultaGp.model                       = 'vm.ConsultaGp';
        vm.ConsultaGp.option.label_descricao      = 'GP:';
        vm.ConsultaGp.option.obj_consulta         = '/_22030/api/gp';
        vm.ConsultaGp.option.tamanho_input        = 'input-maior';
        vm.ConsultaGp.option.tamanho_tabela       = 427;
        vm.ConsultaGp.option.campos_tabela        = [['GP_ID', 'ID'],['GP_DESCRICAO','GRUPO DE PRODUÇÃO']];
        vm.ConsultaGp.option.obj_ret              = ['GP_ID', 'GP_DESCRICAO'];
        vm.ConsultaGp.require                     = vm.ConsultaEstabelecimento;
        vm.ConsultaGp.vincular();
        vm.ConsultaGp.compile();
        gScope.ConsultaGp = vm.ConsultaGp;
        
        vm.ConsultaUp                             = vm.Consulta.getNew(true);
        vm.ConsultaUp.componente                  = '.consulta-up';
        vm.ConsultaUp.model                       = 'vm.ConsultaUp';
        vm.ConsultaUp.option.label_descricao      = 'UP:';
        vm.ConsultaUp.option.obj_consulta         = '/_22030/api/up';
        vm.ConsultaUp.option.tamanho_input        = 'input-maior';
        vm.ConsultaUp.option.tamanho_tabela       = 427;
        vm.ConsultaUp.option.campos_tabela        = [['UP_ID', 'ID'],['UP_DESCRICAO','GRUPO DE PRODUÇÃO']];
        vm.ConsultaUp.option.obj_ret              = ['UP_ID', 'UP_DESCRICAO'];
        vm.ConsultaUp.require                     = vm.ConsultaGp;
        vm.ConsultaUp.vincular();
        vm.ConsultaUp.setRequireRequest({GP_ID: [vm.ConsultaGp, 'GP_ID']});
        vm.ConsultaUp.compile();
        gScope.ConsultaUp = vm.ConsultaUp;
        
        vm.ConsultaEstacao                             = vm.Consulta.getNew(true);
        vm.ConsultaEstacao.componente                  = '.consulta-estacao';
        vm.ConsultaEstacao.model                       = 'vm.ConsultaEstacao';
        vm.ConsultaEstacao.option.label_descricao      = 'Estação:';
        vm.ConsultaEstacao.option.obj_consulta         = '/_22030/api/estacao';
        vm.ConsultaEstacao.option.tamanho_input        = 'input-maior';
        vm.ConsultaEstacao.option.tamanho_tabela       = 427;
        vm.ConsultaEstacao.option.campos_tabela        = [['ESTACAO', 'ID'],['ESTACAO_DESCRICAO','GRUPO DE PRODUÇÃO']];
        vm.ConsultaEstacao.option.obj_ret              = ['ESTACAO', 'ESTACAO_DESCRICAO'];
        vm.ConsultaEstacao.require                     = vm.ConsultaUp;
        vm.ConsultaEstacao.vincular();
        vm.ConsultaEstacao.setRequireRequest({UP_ID: [vm.ConsultaUp, 'UP_ID']});
        vm.ConsultaEstacao.compile();
        gScope.ConsultaEstacao = vm.ConsultaEstacao;
        
		vm.Filtro         = new Filtro();
		vm.Talao          = new Talao();
		vm.TalaoConsumo   = new TalaoConsumo();
		vm.TalaoHistorico = new TalaoHistorico();
		vm.TalaoProduzir  = new TalaoProduzir();
		vm.TalaoDetalhe   = new TalaoDetalhe();
		vm.Operador       = new Operador();
		vm.ServerEvent    = new ServerEvent();
		vm.TalaoProduzido = new TalaoProduzido();
        
        vm.ColaboradorCentroDeTrabalho = new ColaboradorCentroDeTrabalho();


        $scope.$watch('vm.ConsultaEstacao.ESTACAO', function (newValue, oldValue, scope) {
            if ( newValue == undefined || newValue <= 0 ) {
                vm.ServerEvent.close();
                
                vm.Filtro.merge([]);
            }
        }, true);

        $scope.$watch('vm.Filtro.TAB_ACTIVE', function (newValue, oldValue, scope) {
            
            if ( newValue == 'PRODUZIR' ) {          
                vm.Filtro.DATA_TODOS_DISABLED = false;  
                vm.Filtro.DATA_TODOS = true;
                vm.Filtro.consultar();
            }
            else
            if ( newValue == 'PRODUZIDO' ) {
                vm.Filtro.DATA_TODOS_DISABLED = true;
                vm.Filtro.DATA_TODOS = false;
                vm.TalaoProduzido.consultar();
            }
        }, true);

//        loading('.main-ctrl');    
//        vm.ConsumoBaixarFiltro.consultar().then(function(){
//            loading('hide');
//        });

	}   
  