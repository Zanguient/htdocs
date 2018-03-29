angular
    .module('app')
    .value('gScope', {
        indexOfAttr : function(array,attr, value) {
            for(var i in array) {
                if(array[i][attr] === value) {
                    return i;
                }
            }
            return -1;
        }
    })
    .controller('Ctrl', Ctrl);

	Ctrl.$inject = [
        '$ajax',
        '$scope',
        '$window',
        '$timeout',
        'gScope',
        'Create', 
        'Index',
        '$consulta',
        '$httpParamSerializer'
    ];

	function Ctrl($ajax, $scope, $window, $timeout, gScope, Create, Index, $consulta,$httpParamSerializer) {

		var vm = this;
		vm.DADOS = [];
		
        var pagina = window.location.pathname;
        if(pagina == '/_11140'){
            
            vm.Index         = new Index();
            gScope.Index     = vm.Index;
            vm.DADOS.PAINEIS = [];
            vm.Index.init();

        }else{

            vm.Create        = new Create();
            gScope.Create    = vm.Create;

            $scope.$watch('vm.Create.Input.TIPO', function (newValue, oldValue, scope) {
                if ( newValue != oldValue) {
                    vm.Create.validarInfo(newValue);
                } 
            });

            /*
            vm.Consulta     = new $consulta();
            gScope.Consulta = vm.Consulta;

            vm.Consulta_GP1 = vm.Consulta.getNew();
            vm.Consulta_GP2 = vm.Consulta.getNew();
            vm.Consulta_GP3 = vm.Consulta.getNew();

            vm.Consulta_GP1.componente             = '.consulta_angularjs1',
            vm.Consulta_GP1.model                  = 'vm.Consulta_GP1',
            vm.Consulta_GP1.option.label_descricao = 'GP1:',
            vm.Consulta_GP1.option.obj_consulta    = '/_11140/Consultar',
            vm.Consulta_GP1.option.tamanho_Input   = 'input-medio';
            vm.Consulta_GP1.option.class           = 'consulta_gp_grup';
            vm.Consulta_GP3.option.tamanho_tabela  = 250;

            vm.Consulta_GP2.componente             = '.consulta_angularjs2',
            vm.Consulta_GP2.model                  = 'vm.Consulta_GP2',
            vm.Consulta_GP2.option.label_descricao = 'GP2:',
            vm.Consulta_GP2.option.obj_consulta    = '/_11140/Consultar',
            vm.Consulta_GP2.option.tamanho_Input   = 'input-medio';
            vm.Consulta_GP2.option.class           = 'consulta_gp_grup2';
            vm.Consulta_GP3.option.tamanho_tabela  = 250;

            vm.Consulta_GP3.componente             = '.consulta_angularjs3',
            vm.Consulta_GP3.model                  = 'vm.Consulta_GP3',
            vm.Consulta_GP3.option.label_descricao = 'GP3:',
            vm.Consulta_GP3.option.obj_consulta    = '/_11140/getClientes',
            vm.Consulta_GP3.option.tamanho_Input   = 'input-medio';
            vm.Consulta_GP3.option.class           = 'consulta_gp_grup3';
            vm.Consulta_GP3.option.tamanho_tabela  = 480;
            vm.Consulta_GP3.option.campos_tabela   = [['ID','ID'],['DESCRICAO','DESCRIÇÃO'],['STATUS','STATUS']],

            vm.Consulta_GP1.compile();
            vm.Consulta_GP2.compile();
            vm.Consulta_GP3.compile();

            vm.Consulta_GP2.require  = vm.Consulta_GP1;
            vm.Consulta_GP3.require  = [vm.Consulta_GP1,vm.Consulta_GP2];
            vm.Consulta_GP2.vincular();
            vm.Consulta_GP3.vincular();

            var arr = vm.Consulta.getHistory();

            //vm.Consulta_GP3.option.filtro_sql = {GP1: vm.Consulta_GP2.item};

            vm.Consulta_GP3.onSelect = function(){
                var put = {TESTE:'teste'};
                vm.Consulta.postHistory(put,'/_11140/create');
            };

            vm.Consulta_GP3.onClear = function(){
                vm.Consulta.clearHistory('/_11140/create');
            };

            vm.Consulta_GP3.validarInput = function(){
                var ret = true;
                if(vm.Consulta_GP1.selected == null){
                    //showSuccess('Selecione o GP1 e GP2');
                    ret = false;    
                }
                return ret;
            }
            */


        }
	}   
    