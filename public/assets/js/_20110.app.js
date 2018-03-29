/**
 * _11140 - Cadastro de paineis de Casos
 */
'use strict';

angular
	.module('app', [
		'vs-repeat', 
		'gc-ajax',
		'gc-transform',
		'gc-form',
		'gc-utils',
		'gc-find'
	])
;
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
        '$consulta',
        '$httpParamSerializer',
        'gcCollection'
    ];

	function Ctrl($ajax, $scope, $window, $timeout, gScope, $consulta, $httpParamSerializer, gcCollection) {

		var vm = this;
		vm.DADOS = [];
        vm.DADOS.Bancos       = {};
        vm.DADOS.Negociados   = {};
        vm.DADOS.Provisoes    = {};
        vm.DADOS.ContaPagar   = {};
        vm.DADOS.ContaReceber = {};
        vm.DADOS.OrdensCompra = {};
        vm.DADOS.FLUXO        = [];

        vm.DADOS.FLUXO_TOTAL = {
            PAGAMENTO:0,
            NEGOCIADO:0,
            COMPRA:0,
            RECEBIMENTO:0,
            PAGAR:0,
            RECEBER:0,
            SALDODIA:0,
            SALDO: 0,
            SALDO_BANCO: 0
        };     

        vm.DETALHAR = false;
        vm.TOTAL = {DEBITO : 0, CREDITO : 0, SALDO : 0};
        vm.SALDO_ANTERIOR = 0;
        vm.PERFIL = '';

        vm.dataTodas = true;

        vm.detalhar = {
            compra      : false,
            receber     : false,
            bancos      : false,
            pagar       : false,
            provisoes   : false,
            negociados  : false,
            compra_d    : true,
            receber_d   : true,
            bancos_d    : true,
            pagar_d     : true,
            provisoes_d : true,
            negociados_d: true
        }

		function dataInicio(){
            var data = new Date();
            var dia = data.getDate();
            if (dia.toString().length == 1)
              dia = "0"+dia;
            var mes = data.getMonth()+1;
            if (mes.toString().length == 1)
              mes = "0"+mes;
            var ano = data.getFullYear();  
            return mes+"/01/"+ano;
        }

        function dataFim(){
            var data = new Date();
            var dia = data.getDate();
            if (dia.toString().length == 1)
              dia = "0"+dia;
            var mes = data.getMonth()+1;
            if (mes.toString().length == 1)
              mes = "0"+mes;
            var ano = data.getFullYear();  
            return mes+"/"+dia+"/"+ano;
        }

        vm.TratarOrdem = function(filtro){
            if(vm.ordem == filtro){
                vm.ordem = '-'+filtro;
            }else{
                vm.ordem = filtro;
            }
        };

        vm.GerarDatas = function(dados, tipo){

            var dataold = '';
            var cont = 0;
            var hoje = new Date();
            var data = new Date(); 

            angular.forEach(dados, function(value, key) {
                var notIn = true;

                if(dataold != value.DATA_FLUXO2){

                    angular.forEach(vm.DADOS.FLUXO , function(v, k) {
                        if(v.DATA_FLUXO2 == value.DATA_FLUXO2){
                            notIn = false;
                        }
                    });

                    if(notIn == true){

                        data = new Date(value.DATA_FLUXO); 
                        var FLAG = hoje > data;

                        if(hoje == data){
                            FLAG = false;
                        }

                        vm.DADOS.FLUXO.push({FLAG: FLAG, DIA_SEMANA: value.DIA_SEMANA, DATA_FLUXO2 : value.DATA_FLUXO2, DATA_FLUXO : value.DATA_FLUXO ,PAGAMENTO:0, NEGOCIADO: 0, COMPRA: 0, RECEBIMENTO: 0, PAGAR:0, RECEBER:0, SALDODIA:0, SALDO: 0});
                    }
                }

                dataold = value.DATA_FLUXO2;

                //vm.GerarDatas(vm.DADOS.Negociados   , 1);
                //vm.GerarDatas(vm.DADOS.Provisoes    , 2);
                //vm.GerarDatas(vm.DADOS.ContaPagar   , 3);
                //vm.GerarDatas(vm.DADOS.ContaReceber , 4);
                //vm.GerarDatas(vm.DADOS.OrdensCompra , 5);

                angular.forEach(vm.DADOS.FLUXO , function(v, k) {
                    
                    if(value.DATA_FLUXO2 == v.DATA_FLUXO2){

                        if(tipo == 1){
                            v.NEGOCIADO = Number(v.NEGOCIADO) + Number(value.VALOR_SALDO);
                        }
                        if(tipo == 2){

                            if(value.TIPO == 'C'){
                                v.RECEBIMENTO = Number(v.RECEBIMENTO) + Number(value.VALOR_TOTAL);
                                v.SALDODIA    = Number(v.SALDODIA)    - Number(value.VALOR_TOTAL);
                                vm.DADOS.FLUXO_TOTAL.RECEBIMENTO      = Number(vm.DADOS.FLUXO_TOTAL.RECEBIMENTO) + Number(value.VALOR_TOTAL);
                                vm.DADOS.FLUXO_TOTAL.RECEBIMENTO      = Number(vm.DADOS.FLUXO_TOTAL.RECEBIMENTO) - Number(value.VALOR_TOTAL);
                            }else{
                                v.PAGAMENTO = Number(v.PAGAMENTO) + Number(value.VALOR_TOTAL);
                                v.SALDODIA  = Number(v.SALDODIA)  - Number(value.VALOR_TOTAL);
                                vm.DADOS.FLUXO_TOTAL.PAGAMENTO    = Number(vm.DADOS.FLUXO_TOTAL.PAGAMENTO) + Number(value.VALOR_TOTAL);
                                vm.DADOS.FLUXO_TOTAL.SALDODIA     = Number(vm.DADOS.FLUXO_TOTAL.SALDODIA)  - Number(value.VALOR_TOTAL);
                            }

                        }
                        if(tipo == 3){
                            v.PAGAR    = Number(v.PAGAR)    + Number(value.VALOR_SALDO);
                            v.SALDODIA = Number(v.SALDODIA) - Number(value.VALOR_SALDO);
                            vm.DADOS.FLUXO_TOTAL.PAGAR      = Number(vm.DADOS.FLUXO_TOTAL.PAGAR)    + Number(value.VALOR_SALDO);
                            vm.DADOS.FLUXO_TOTAL.SALDODIA   = Number(vm.DADOS.FLUXO_TOTAL.SALDODIA) - Number(value.VALOR_SALDO);
                        }
                        if(tipo == 4){
                            v.RECEBER  = Number(v.RECEBER)  + Number(value.VALOR_SALDO);
                            v.SALDODIA = Number(v.SALDODIA) + Number(value.VALOR_SALDO);
                            vm.DADOS.FLUXO_TOTAL.RECEBER    = Number(vm.DADOS.FLUXO_TOTAL.RECEBER)  + Number(value.VALOR_SALDO);
                            vm.DADOS.FLUXO_TOTAL.SALDODIA   = Number(vm.DADOS.FLUXO_TOTAL.SALDODIA) + Number(value.VALOR_SALDO);
                        }
                        if(tipo == 5){
                            v.COMPRA   = Number(v.COMPRA)   + Number(value.VALOR);
                            v.SALDODIA = Number(v.SALDODIA) - Number(value.VALOR);
                            vm.DADOS.FLUXO_TOTAL.COMPRA     = Number(vm.DADOS.FLUXO_TOTAL.COMPRA)   + Number(value.VALOR);
                            vm.DADOS.FLUXO_TOTAL.SALDODIA   = Number(vm.DADOS.FLUXO_TOTAL.SALDODIA) - Number(value.VALOR);
                        }

                    }

                });
                
            });

        }

        vm.DATA1 = moment(dataInicio()).toDate();
        vm.DATA2 = moment(dataFim()).toDate();

        /*
        vm.Consulta     = new $consulta();
        gScope.Consulta = vm.Consulta;

        vm.Consulta_Banco = vm.Consulta.getNew();

        vm.Consulta_Banco.componente             = '.consulta_banco',
        vm.Consulta_Banco.model                  = 'vm.Consulta_Banco',
        vm.Consulta_Banco.option.label_descricao = 'Banco:',
        vm.Consulta_Banco.option.obj_consulta    = '/_20110/ConsultarBanco',
        vm.Consulta_Banco.option.tamanho_Input   = 'input-maior';
        vm.Consulta_Banco.option.class           = 'consulta_banco_grup';
        vm.Consulta_Banco.option.tamanho_tabela  = 350;

        vm.Consulta_Banco.compile();
        */

         vm.Consulta_Banco = {
            item : {
                selected : false,
                dados    : {DESCRICAO: '', ID: 0}
            }
         };

        vm.Acoes = {
            imprimir : function(){
                var valido = true;

                if(valido == true){
                    var user = $('#usuario-descricao').val();

                    var banco = '';
                    if(vm.Consulta_Banco.item.selected == true){
                        banco = 'Banco:' + vm.Consulta_Banco.item.dados.DESCRICAO;
                    }else{
                        banco = 'Banco: Todos'    
                    }

                    var periodo = '';
                    if( vm.dataTodas == true){
                        periodo = 'Período: Todos';
                    }else{
                        periodo = 'Período:' + moment(vm.DATA1).format('L') +' a '+ moment(vm.DATA2).format('L');   
                    }

                    var filtro = banco + ' / ' + periodo;
                    printHtml('container-registros', 'Relatório de Fluxo de Caixa', filtro, user, '1.0.0',1,'');
                }

            },
            export1 : function(){
                exportTableToCsv('extrato.csv', 'tabela-registros');
            },
            export2 : function(){
                exportTableToXls('extrato.xls', 'tabela-registros');
            },
            filtrar : function(){
                var valido = true;

                if(vm.Consulta_Banco.item.selected == false){
                    //showErro('Selecione um banco!');
                    //valido = false;

                    vm.Consulta_Banco.item.dados.ID = 0;
                }

                if(vm.DATA2 < vm.DATA1){
                    showErro('Período invalido');
                    valido = false;
                }

                if(valido == true){
                    this.exec();   
                }

            },
            exec : function(){

                var that  = this;

                vm.DADOS.EXTRATO = [];
                vm.DADOS.DETALHE = [];
                vm.DADOS.RELATORIO = [];

                var dados = {
                    DATA1    : vm.DATA1,
                    DATA2    : vm.DATA2,
                    DETALHAR : vm.DETALHAR,
                    BANCO    : vm.Consulta_Banco.item.dados,
                    DETALHES : vm.detalhar,
                    TODOS    : vm.dataTodas
                };

                $ajax.post('/_20110/ConsultarFluxo',dados)
                    .then(function(response) {

                        //vm.DADOS = response;

                        var dataold = '';

                        vm.DADOS.Bancos = {};

                        
                        
                        vm.DADOS.Negociados   = response.Negociados   ;
                        vm.DADOS.Provisoes    = response.Provisoes    ;
                        vm.DADOS.ContaPagar   = response.ContaPagar   ;
                        vm.DADOS.ContaReceber = response.ContaReceber ;
                        vm.DADOS.OrdensCompra = response.OrdensCompra ;
                        vm.DADOS.FLUXO = [];

                        vm.DADOS.FLUXO_TOTAL = {
                            PAGAMENTO:0,
                            NEGOCIADO:0,
                            COMPRA:0,
                            RECEBIMENTO:0,
                            PAGAR:0,
                            RECEBER:0,
                            SALDODIA:0,
                            SALDO: 0,
                            SALDO_BANCO: 0
                        }; 


                        if(vm.detalhar.negociados_d == true){vm.GerarDatas(vm.DADOS.Negociados   , 1);}
                        if(vm.detalhar.provisoes_d  == true){vm.GerarDatas(vm.DADOS.Provisoes    , 2);}
                        if(vm.detalhar.pagar_d      == true){vm.GerarDatas(vm.DADOS.ContaPagar   , 3);}
                        if(vm.detalhar.receber_d    == true){vm.GerarDatas(vm.DADOS.ContaReceber , 4);}
                        if(vm.detalhar.compra_d     == true){vm.GerarDatas(vm.DADOS.OrdensCompra , 5);}

                        if(vm.detalhar.negociados == true){gcCollection.bind(vm.DADOS.FLUXO  , vm.DADOS.Negociados   , 'DATA_FLUXO2', 'Negociados'  );}
                        if(vm.detalhar.provisoes  == true){gcCollection.bind(vm.DADOS.FLUXO  , vm.DADOS.Provisoes    , 'DATA_FLUXO2', 'Provisoes'   );}
                        if(vm.detalhar.pagar      == true){gcCollection.bind(vm.DADOS.FLUXO  , vm.DADOS.ContaPagar   , 'DATA_FLUXO2', 'ContaPagar'  );}
                        if(vm.detalhar.receber    == true){gcCollection.bind(vm.DADOS.FLUXO  , vm.DADOS.ContaReceber , 'DATA_FLUXO2', 'ContaReceber');}
                        if(vm.detalhar.compra     == true){gcCollection.bind(vm.DADOS.FLUXO  , vm.DADOS.OrdensCompra , 'DATA_FLUXO2', 'OrdensCompra');}


                        function compare(a,b) {
                          return a.DATA_FLUXO < b.DATA_FLUXO ? -1 : a.DATA_FLUXO > b.DATA_FLUXO ? 1 : 0;
                        }

                        vm.DADOS.FLUXO.sort(compare);

                        var saldo = 0;

                        vm.DADOS.Bancos = [];

                        if(vm.detalhar.bancos == true){
                            vm.DADOS.Bancos = response.Bancos ;
                        }else{
                            if(vm.detalhar.bancos == false){
                                vm.DADOS.Bancos.push({
                                    NOME  : response.Bancos[response.Bancos.length -1].NOME,
                                    SALDO : response.Bancos[response.Bancos.length -1].SALDO
                                });
                            }
                        }

                        angular.forEach(response.Bancos , function(v, k) {
                            saldo = Number(v.SALDO);

                            
                        });

                        vm.DADOS.FLUXO_TOTAL.SALDO_BANCO = saldo;

                        angular.forEach(vm.DADOS.FLUXO , function(v, k) {
                            saldo = Number(saldo) + Number(v.SALDODIA);
                            v.SALDO = Number(saldo);
                        });

                        vm.DADOS.FLUXO_TOTAL.SALDO = saldo;
                        

                        console.log(vm.DADOS);

                        /*
                        var dataold = '';

                        gcCollection.merge(vm.DADOS.EXTRATO   , response.EXTRATO   , 'CONTROLE');
                        gcCollection.merge(vm.DADOS.DETALHE   , response.DETALHE   , 'CONTROLE');

                        if(vm.DETALHAR == true){
                            gcCollection.bind(vm.DADOS.EXTRATO    , vm.DADOS.DETALHE   , 'CONTROLE|CAIXABANCO_CONTROLE', 'DETALHES');
                        }

                        var cont = 0;
                        angular.forEach(vm.DADOS.EXTRATO, function(value, key) {
                            var notIn = true;

                            if(cont == 0){
                                vm.SALDO_ANTERIOR = value.SALDO_ANTERIOR;
                            }

                            if(dataold != value.DATA){

                                angular.forEach(vm.DADOS.RELATORIO, function(v, k) {
                                    if(v.DATA == value.DATA){
                                        notIn = false;
                                    }
                                });

                                if(notIn == true){
                                    vm.DADOS.RELATORIO.push({DATA : value.DATA, DEBITO: 0, CREDITO: 0, SALDO: 0});
                                }
                            }

                            dataold = value.DATA;

                            angular.forEach(vm.DADOS.RELATORIO, function(v, k) {
                                if(v.DATA == dataold){
                                    v.DEBITO  = value.ACUMULADO_DEBITO_DIA; 
                                    v.CREDITO = value.ACUMULADO_CREDITO_DIA; 
                                    v.SALDO   = value.SALDO; 

                                    vm.TOTAL.DEBITO  = value.ACUMULADO_DEBITO; 
                                    vm.TOTAL.CREDITO = value.ACUMULADO_CREDITO; 
                                    vm.TOTAL.SALDO   = value.SALDO; 
                                }
                            });

                            
                        });
                        
                        gcCollection.bind(vm.DADOS.RELATORIO  , vm.DADOS.EXTRATO   , 'DATA', 'EXTRATO');

                        console.log(vm.DADOS);

                        cont = cont + 1;
                        */

                    }
                );
            }
        }
	}   
    
//# sourceMappingURL=_20110.app.js.map
