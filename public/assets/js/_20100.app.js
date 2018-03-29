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
        vm.DADOS.EXTRATO = [];
        vm.DADOS.DETALHE = [];
        vm.DADOS.RELATORIO = [];
        vm.DETALHAR = false;
        vm.TOTAL = {DEBITO : 0, CREDITO : 0, SALDO : 0};
        vm.SALDO_ANTERIOR = 0;

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

        vm.DATA1 = moment(dataInicio()).toDate();
        vm.DATA2 = moment(dataFim()).toDate();

        vm.Consulta     = new $consulta();
        gScope.Consulta = vm.Consulta;

        vm.Consulta_Banco = vm.Consulta.getNew();

        vm.Consulta_Banco.componente             = '.consulta_banco',
        vm.Consulta_Banco.model                  = 'vm.Consulta_Banco',
        vm.Consulta_Banco.option.label_descricao = 'Banco:',
        vm.Consulta_Banco.option.obj_consulta    = '/_20100/ConsultarBanco',
        vm.Consulta_Banco.option.tamanho_Input   = 'input-maior';
        vm.Consulta_Banco.option.class           = 'consulta_banco_grup';
        vm.Consulta_Banco.option.tamanho_tabela  = 350;

        vm.Consulta_Banco.compile();

        vm.Acoes = {
            imprimir : function(){
                var valido = true;

                if(vm.Consulta_Banco.item.selected == false){
                    showErro('Selecione um banco!');
                    valido = false;
                }

                if(vm.DATA2 < vm.DATA1){
                    showErro('Período invalido');
                    valido = false;
                }

                if(valido == true){
                    var user = $('#usuario-descricao').val();
                    var filtro = 'Banco: ' + vm.Consulta_Banco.item.dados.DESCRICAO + ' Data:' + moment(vm.DATA1).format('L') +' a '+ moment(vm.DATA2).format('L');
                    printHtml('container-registros', 'Relatório de Extrato de Caixa / Bancos', filtro, user, '1.0.0',1,'');
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
                    showErro('Selecione um banco!');
                    valido = false;
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
                    BANCO    : vm.Consulta_Banco.item.dados
                };

                $ajax.post('/_20100/ConsultarExtrato',dados)
                    .then(function(response) {

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

                    }
                );
            }
        }
	}   
    
//# sourceMappingURL=_20100.app.js.map
