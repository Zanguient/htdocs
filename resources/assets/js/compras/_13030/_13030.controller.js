angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        '$consulta',
        'gScope',
        'Filtro',
        'Cota',
        'CotaExtra',
        'CotaReducao',
        'CotaGgf',
        'CotaIncluir',
        'CotaCcusto',
        'CotaPeriodo',
        'CotaCcontabil',
        'CotaDetalhe',
        'Historico'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        $consulta,
        gScope, 
        Filtro, 
        Cota,
        CotaExtra,
        CotaReducao,
        CotaGgf,
        CotaIncluir,
        CotaCcusto,
        CotaPeriodo,
        CotaCcontabil,
        CotaDetalhe, 
        Historico
    ) {

		var vm = this;

		vm.Filtro        = new Filtro();
		vm.Cota          = new Cota();
		vm.CotaExtra     = new CotaExtra();
		vm.CotaReducao   = new CotaReducao();
		vm.CotaGgf       = new CotaGgf();
		vm.CotaIncluir   = new CotaIncluir();
		vm.CotaCcusto    = new CotaCcusto();
		vm.CotaPeriodo   = new CotaPeriodo();
		vm.CotaCcontabil = new CotaCcontabil();
		vm.CotaDetalhe   = new CotaDetalhe();
		vm.Historico     = new Historico('vm.Historico');


        vm.Consulta   = new $consulta();
        
        vm.ConsultaCcusto                        = vm.Consulta.getNew(true);
        vm.ConsultaCcusto.componente             = '.consulta-ccusto';
        vm.ConsultaCcusto.model                  = 'vm.ConsultaCcusto';
        vm.ConsultaCcusto.option.label_descricao = 'C. Custo:';
        vm.ConsultaCcusto.option.obj_consulta    = '/_20030/api/ccusto';
        vm.ConsultaCcusto.option.tamanho_input   = 'input-maior';
        vm.ConsultaCcusto.option.campos_tabela   = [['MASK', 'C. Custo'],['DESCRICAO','Descrição']];
        vm.ConsultaCcusto.option.obj_ret         = ['MASK', 'DESCRICAO'];
        vm.ConsultaCcusto.compile();
        gScope.ConsultaCcusto = vm.ConsultaCcusto;
        
        vm.ConsultaCcontabil                        = vm.Consulta.getNew(true);
        vm.ConsultaCcontabil.componente             = '.consulta-ccontabil';
        vm.ConsultaCcontabil.model                  = 'vm.ConsultaCcontabil';
        vm.ConsultaCcontabil.option.label_descricao = 'C. Contábil:';
        vm.ConsultaCcontabil.option.obj_consulta    = '/_17010/api/ccontabil';
        vm.ConsultaCcontabil.option.tamanho_input   = 'input-maior';
        vm.ConsultaCcontabil.option.campos_tabela   = [['MASK', 'C. Contábil'],['DESCRICAO','Descrição']];
        vm.ConsultaCcontabil.option.obj_ret         = ['MASK', 'DESCRICAO'];
        vm.ConsultaCcontabil.compile();
        vm.ConsultaCcontabil.setDataRequest({CCONTABIL_TIPO: 'analitica'});
        gScope.ConsultaCcontabil = vm.ConsultaCcontabil;
        

        loading('.main-ctrl');    
        $timeout(function(){
            vm.Filtro.consultar().then(function(){

                if ( vm.Filtro.COTA_ID > 0 || gScope.CotaCcusto.DADOS.length <= 3 ) {
                    vm.CotaCcusto.toggleExpand(true);
                }
                
                loading('hide');
                $timeout(function(){
                    if ( vm.Filtro.COTA_ID > 0 ) {
                        var cota = $('[data-cota-id="' + vm.Filtro.COTA_ID + '"]:focusable');

                        cota.focus();
                                       
                        var item = vm.Cota.SELECTED;

                        for ( var i in gScope.CotaCcusto.DADOS ) {
                            var cota = gScope.CotaCcusto.DADOS[i];

                            if ( cota.CCUSTO != item.CCUSTO ) {
                                cota.OPENED = false;
                                
                                for ( var j in cota.PERIODOS ) {
                                    var periodo = cota.PERIODOS[j];

                                    periodo.OPENED = false;
                                }
                            } else {
                                
                                for ( var j in cota.PERIODOS ) {
                                    var periodo = cota.PERIODOS[j];
                                    
                                    if ( periodo.PERIODO_DESCRICAO != item.PERIODO_DESCRICAO ) {
                                        periodo.OPENED = false;
                                    }
                                }                                
                            }
                        }

                        $timeout(function(){                 
                                    
                            if ( vm.Filtro.COTA_OPEN == 1 && gScope.Cota.SELECTED.ID != undefined ) {
                                vm.Cota.dblPick(vm.Cota.SELECTED);
                            }
                        },100);
                    }
                },50);

            });

        },50);
	}   
  