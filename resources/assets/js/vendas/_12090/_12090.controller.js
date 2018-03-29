angular
    .module('app')
    .value('gScope', {
        REPRESENTANTE_ID : $('#usuario-representante-id').first().val()
    })
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        '$consulta',
        'gScope',
        'Filtro',
        'Empresa',
        'Empresas',
        'Historico'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        $consulta,
        gScope, 
        Filtro, 
        Empresa,
        Empresas,
        Historico
    ) {

		var vm = this;

		vm.Filtro     = new Filtro();
		vm.Empresa    = new Empresa();
		vm.Empresas   = new Empresas();
		vm.Historico  = new Historico();
        vm.Consulta   = new $consulta();
        
        
        loading('.main-ctrl');    
        
        var usuario_representante_id = gScope.REPRESENTANTE_ID;
        
        var representante_args = {
            HABILITA_REPRESENTANTE: '1'
        };
        

        if ( usuario_representante_id > 0 ) {
            representante_args.EMPRESA_ID = usuario_representante_id;
        }
        
        vm.ConsultaRepresentante                        = vm.Consulta.getNew(true);
        vm.ConsultaRepresentante.componente             = '.consulta-representante';
        vm.ConsultaRepresentante.model                  = 'vm.ConsultaRepresentante';
        vm.ConsultaRepresentante.option.label_descricao = 'Representante:';
        vm.ConsultaRepresentante.option.obj_consulta    = '/_12090/api/empresas';
        vm.ConsultaRepresentante.option.tamanho_input   = 'input-maior';
        vm.ConsultaRepresentante.option.campos_tabela   = [['EMPRESA_ID','ID'],['EMPRESA_CNPJ_MASK', 'CNPJ/CPF'],['EMPRESA_NOMEFANTASIA','Nome Fantasia'],['EMPRESA_RAZAO_SOCIAL','Razão Social'],['EMPRESA_UF','UF'],['EMPRESA_CIDADE','Cidade']];
        vm.ConsultaRepresentante.option.obj_ret         = ['EMPRESA_CNPJ_MASK', 'EMPRESA_NOMEFANTASIA'];
        vm.ConsultaRepresentante.option.required        = false;        
        vm.ConsultaRepresentante.compile();
        vm.ConsultaRepresentante.setDataRequest(representante_args);
        gScope.ConsultaRepresentante = vm.ConsultaRepresentante;
        
        
        if ( usuario_representante_id > 0 ) {
            vm.ConsultaRepresentante.Input.disabled             = true;
            vm.ConsultaRepresentante.btn_apagar_filtro.disabled = true;
            vm.ConsultaRepresentante.filtrar();

        } else {
            vm.Empresas.consultar(true).then(function(){
                loading('hide');
            });        
        }
        

        vm.ConsultaRepresentante.onSelect = function(){

            vm.Empresas.emptyData();                
            
            vm.Empresas.FILTRO.REPRESENTANTE_ID = vm.ConsultaRepresentante.EMPRESA_ID;

            if ( usuario_representante_id > 0 ) {
                vm.ConsultaRepresentante.btn_apagar_filtro.disabled = true;
                vm.Empresas.consultar(true).then(function(){
                    loading('hide');
                });                
            } 
        };

        vm.ConsultaRepresentante.onClear = function (){
            
            delete vm.Empresas.FILTRO.REPRESENTANTE_ID;
            
            vm.Empresas.emptyData();    
        };

        vm.export1 = function(tabela,nome){
            exportTableToCsv(nome, tabela);
        };

        vm.export2 = function(tabela,nome){
            exportTableToXls(nome, tabela);
        };

        vm.export3 = function(div,descricao){
            var user = $('#usuario-descricao').val();
            var filtro = '' + vm.Empresa.MODELO_PRECO_FILTRO;
            printPDF('preco_modelos',div, 'Preço por Modelo - ' + descricao, filtro, user, '1.0.0',1,'');
        };

        vm.Imprimir = function(div,descricao){
            var user = $('#usuario-descricao').val();
            var filtro = '' + vm.Empresa.MODELO_PRECO_FILTRO;
            printHtml(div, 'Preço por Modelo - ' + descricao, filtro, user, '1.0.0',1,'');
        }           
        
        
        $timeout(function(){
            if ( vm.Empresa.SELECTED.EMPRESA_ID > 0 ) {
                vm.Empresa.open();
            }
        });

//        $timeout(function(){
//            vm.Filtro.consultar().then(function(){
//
//                loading('hide');
//                $timeout(function(){
//                    if ( vm.Filtro.COTA_ID > 0 ) {
//                        var cota = $('[data-cota-id="' + vm.Filtro.COTA_ID + '"]:focusable');
//
//                        cota.focus();
//
//                        $timeout(function(){
//                            if ( vm.Filtro.COTA_OPEN == 1 && gScope.Cota.SELECTED.ID != undefined ) {
//                                vm.Cota.dblPick(vm.Cota.SELECTED);
//                            }
//                        },100);
//                    }
//                },50);
//
//            });
//
//        },50);
//        
	}   
  