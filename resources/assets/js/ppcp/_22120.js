/**
 * _22120 - Estrutura Analítica de Remessas
 */
;(function(angular) {
   
   
        
    var MyCtrl = function($scope,$ajax,$filter,$timeout,$document,$rootScope, gcCollection, Historico) {     
        var vm = this;
        var data_table = $.extend({}, table_default);
            data_table.scrollY = 'calc(100% - 35px)';
        
        vm.Historico           = new Historico();
        vm.Math = window.Math;
        vm.DADOS    = [];
        vm.dtOptions           = data_table;
        vm.remessa             = "";
        vm.remessas            = [];
        vm.itens               = [];
        vm.selected_itens_acao = [];
        vm.class               = [];
        vm.filtrar_arvore      = false;
        vm.familias_consumo    = [];
        vm.consumo_dados       = [];
        vm.gerar_consumo_familias = [];
        vm.gerar_consumo_familia = [];
        vm.filtro              = {
            data_1 : moment().subtract(2, "month").toDate(),
            data_2 : moment().add(2, "month").toDate()
        };

        vm.remessaAction = {
            Filtrar: function() {
                
                loading($('.table-remessas'));
                var dados = {
                    data_1 : moment(vm.filtro.data_1).format('DD.MM.YYYY'),
                    data_2 : moment(vm.filtro.data_2).format('DD.MM.YYYY'),
                    remessa: String($filter('uppercase')(vm.filtro.remessa))
                };
                
                $ajax.post('/_22120/remessas',dados)
                    .then(function(response) {
                        vm.remessas = response;
                
                        $timeout(function(){

                            if ( vm.filtrar_remessa != '' && vm.remessas_filtered.length == 1) {
                                vm.remessaAction.VisualizarItem(vm.remessas_filtered[0]);
                            }
                        },100);
                        
                        $('.pesquisa.filtro-obj').select();
                        loading('hide');
                    }
                );
            },
            VisualizarItem: function(r) {
                vm.remessa = r;
                
                var link = encodeURI(urlhost + '/_22120?remessa='+vm.remessa.REMESSA);
                window.history.pushState('Delfa - GC', 'Title', link);
                vm.estruturaAction.ConsultarRemessa(function(){
                    vm.filtrar_arvore      = false;
                    vm.selected_itens_acao = [];
                    vm.class               = [];
                });
            },
            RepeatFilter: function(row) {
                
                var res = false;
                
                if ( vm.filtro.status == '' || row.STATUS_PRODUCAO == vm.filtro.status ) {
                    res = true;
                }
                
                if ( res ) {
                    if ( vm.filtro.familia == '' || row.FAMILIA_ID == vm.filtro.familia ) {
                        res = true;
                    } else {
                        res = false;
                    }
                }
                
                return res;
            }
        };

        vm.estruturaAction = {
            ConsultarRemessa: function(callback) {
                var dados = {
                    remessa : vm.remessa.REMESSA
                };

                $ajax.post('/_22120/find',dados)
                    .then(function(response) {
                        
                        callback ? callback() : null;
                        
//                        vm.itens = response;
//                        resizable();

                        vm.estruturaAction.loadData(response);
                        
                        if ( $('#modal-remessa').is(":visible") ) {
                            showSuccess('Os dados foram atualizados!');
                        }

                        $('#modal-remessa').modal();
                    }
                );
            },
            loadData : function (response) {
                
                    gcCollection.merge(vm.itens, response);
                
                for ( var i in  vm.itens ) {
                    var remessa = vm.itens[i];
                    
                    gcCollection.bind(remessa.CONSUMOS, remessa.CONSUMO_ALOCACOES, 'ID|CONSUMO_ID', 'ALOCACOES');
                }
            
            }
        };
                
        vm.Acao = function (action,msg,remessa,key,name) {
            addConfirme('<h4>Confirmação</h4>',
                msg,
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $scope.$apply(function(){

                        var itens = vm.selected_itens_acao[name];

                        var array = [];

                        for ( var i in itens ) {
                            var item = itens[i];

                            if ( item[key] == remessa[key] ) {
                                array.push(item);
                            }
                        }

                        var dados = {
                            dados: array,
                            retorno: true,
                            param: {
                                remessa: String($filter('uppercase')(vm.remessa.REMESSA))
                            }
                        };

                        $ajax.post('/_22120/'+action+'/'+name,JSON.stringify(dados),{contentType: 'application/json'})
                            .then(function(response) {
                                showSuccess(response.success);
//                                vm.itens = response.dados;
//                                resizable();
                                vm.estruturaAction.loadData(response.dados);
                                
                                if ( vm.itens.length <= 0 ) {
                                    
                                    var idx = vm.remessas.indexOf(vm.remessa);

                                    vm.remessas.splice(idx, 1);
                                    
                                    $('#modal-remessa').modal('hide');
                                }
                            })
                        ;    
                    });
                }}]     
            );
        };
        
        vm.winPopUp = function (url,id,params) {
            var modal = winPopUp(url,id,params);
            
            $(modal).unload( function() {
                vm.estruturaAction.ConsultarRemessa();
            });
        };
        
        vm.setRemessaHistorico = function (remessa) {
            $('.historico-corpo').data('id',remessa.REMESSA_ID);
        };
        
        vm.getConsumo = function ()
        {            
            var dados = {
                remessa_id : vm.consumo_dados.remessa_id, 
                familia_id_consumo: vm.consumo_dados.familia_id_consumo
            };
            
            $ajax.post('/_22040/getPdfConsumo',dados)
                .then(function(response) {  
                    if (response) {
                        printPdf(response);
                    }
                })
            ; 
        };
        
        vm.Remessa = {
            SELECTED : null
        };
        
        vm.Consumo = {
            FAMILIAS : [],
            FAMILIA_SELECTED : null,
            Gerar : function () {   
                
                addConfirme('<h4>Confirmação</h4>',
                    'Confirma a geração de consumo para esta remessa?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){
                        $scope.$apply(function(){

                            var dados = {
                                dados: {
                                    REMESSA_ID : vm.Remessa.SELECTED.REMESSA_ID,
                                    MP_FAMILIA_ID: vm.Consumo.FAMILIA_SELECTED
                                },
                                retorno: true,
                                param: {
                                    remessa: String($filter('uppercase')(vm.remessa.REMESSA))
                                }
                            };

                            $ajax.post('/_22120/gerar-consumo',JSON.stringify(dados),{contentType: 'application/json'})
                                .then(function(response) {  
                                    showSuccess(response.success);
//                                    vm.itens = response.dados;
//                                    resizable();
                                    vm.estruturaAction.loadData(response.dados);
                                    
                                    $('#modal-gerar-consumo').modal('hide');
                                })
                            ;    
                        });
                    }}]     
                );
                
            },
            ListarFamilias : function(dados) {
                
                $ajax.post('/_27010/familia-modelo-alocacao',dados)
                    .then(function(response) {  
                        if (response) {
                            vm.Consumo.FAMILIAS = response;
                    
                            if ( vm.Consumo.FAMILIAS.length == 1 ) {
                                vm.Consumo.FAMILIA_SELECTED = vm.Consumo.FAMILIAS[0].FAMILIA_ID;
                            } else {
                                vm.Consumo.FAMILIA_SELECTED = '';
                            }
                            
                            $('#modal-gerar-consumo').modal('show');
                        }
                    })
                ; 
            }
        };
                
                

        
        vm.IndexOfAttr = function(array,attr, value) {
            for(var i in array) {
                if(array[i][attr] === value) {
                    return i;
                }
            }
            return -1;
        };
        
        vm.selectItemAcao = function (item,name,key)
        {    
            if ( vm.selected_itens_acao[name] == undefined ) vm.selected_itens_acao[name] = [];
            var colletion = vm.selected_itens_acao[name];
            
            var idx = vm.IndexOfAttr(colletion,key,item[key]);
            
            if (idx > -1) colletion.splice(idx, 1);
            else          colletion.push(item);
        };
        
        vm.selectedItemAcao = function (item,name,key)
        {    
            if ( vm.selected_itens_acao[name] == undefined ) vm.selected_itens_acao[name] = [];
            var colletion = vm.selected_itens_acao[name];
            
            return ( vm.IndexOfAttr(colletion,key,item[key]) > -1 ) ? true : false;
        };        
        
        vm.selectTalao = function (item) {
            vm.class = []; 
            vm.changeClass(item);
            vm.marcarFilhos(item);
            vm.marcarPais(item);
        };
        
        vm.changeClass = function(item){

            if (vm.IndexOfAttr(vm.class,item,item.ID) == -1) {
                vm.class.push(item);
            }    
        };
        
        vm.marcarMaes = function (item) {

            var item_filtro = '[' + item.GP_PERFIL + '/' + item.REMESSA_TALAO_ID + ']';
            //var filtro = '[' + talao.REMESSA_ID + '/' + talao.REMESSA_TALAO_ID +']';

            for(var i in vm.itens)
            {
                var remessa = vm.itens[i]; 
                for(var j in remessa.TALOES) {
                    var talao = remessa.TALOES[j];
                    var talao_filtro = talao.VINCULOS;

                    if ( talao_filtro != undefined && talao_filtro.indexOf(item_filtro) >= 0 ) {
                        vm.changeClass(talao);
                        vm.marcarMaes(talao);

                    }
                }
            }
        };
        
        vm.marcarPais = function (item) {
            
            var item_filtro = '[' + item.GP_PERFIL + '/' + item.REMESSA_TALAO_ID + ']';
            //var filtro = '[' + talao.REMESSA_ID + '/' + talao.REMESSA_TALAO_ID +']';
            
            for(var i in vm.itens)
            {
                var remessa = vm.itens[i]; 
                for(var j in remessa.TALOES) {
                    var talao = remessa.TALOES[j];
                    var talao_filtro = talao.VINCULOS;
                    
                    if ( talao_filtro != undefined && talao_filtro.indexOf(item_filtro) >= 0 ) {
                        vm.changeClass(talao);
                        vm.marcarPais(talao);
                        
                        if ( vm.marcar_irmaos ) {
                            vm.marcarIrmaos(talao);
                        }
                    }
                }
            }
        };
       
        vm.marcarFilhos = function (item) {
            
            var item_filtro = item.VINCULOS;
            
            for(var i in vm.itens)
            {
                var remessa = vm.itens[i]; 
                for(var j in remessa.TALOES)
                {
                    var talao = remessa.TALOES[j];
                    var vinculo = '[' + talao.GP_PERFIL + '/' + talao.REMESSA_TALAO_ID +']';
                    
                    if ( item_filtro != undefined && item_filtro.indexOf(vinculo) >= 0 ) {
                        vm.changeClass(talao);
                        vm.marcarFilhos(talao);
                    }
                }
            }
        };
        
        vm.marcarIrmaos = function (item) {
            
            var item_filtro = item.VINCULOS;// '[' + item.REMESSA_ID + '/' + item.REMESSA_TALAO_ID +']';
            
            for(var i in vm.itens)
            {
                var remessa = vm.itens[i]; 
                for(var j in remessa.TALOES) {
                    var talao = remessa.TALOES[j];
                    var vinculo = '[' + talao.GP_PERFIL + '/' + talao.REMESSA_TALAO_ID +']';
                    
                    if ( item_filtro != undefined && item_filtro.indexOf(vinculo) >= 0 ) {
                        vm.changeClass(talao);
                       
                        vm.marcarFilhos(talao);
   
                        if ( vm.marcar_irmaos ) {
                            vm.marcarMaes(talao);
                        }
                    }
                }
            }
        };
          
        vm.FiltrarArvore = function (row) {
            var result = true;
            if ( vm.filtrar_arvore == true ) {
                vm.filtrar_talao = '';
                result = false;
                
                if ( vm.IndexOfAttr(vm.class,'ID',row.ID) >= 0 ) {
                    result = true;
                }
            }
            return result;
        };
                
        vm.FiltrarTalaoDetalhe = function(talao) {
            
            var itens_selecionados = vm.class;
            var result = false;

            if ( itens_selecionados.length > 0 ) {
                
                for(var i in itens_selecionados) {
                    if (itens_selecionados[i].REMESSA_ID == talao.REMESSA_ID && itens_selecionados[i].REMESSA_TALAO_ID == talao.REMESSA_TALAO_ID) {
                        result = true;
                        break;
                    }
                }                
            }
            
            return result;
        };
        
        vm.somaTaloes = function(remessa){
            var itens = vm.class;
            var summ     = 0;
            var summ_alt = 0;
            
            for(var i in itens)
            {
                if (remessa.REMESSA_ID == itens[i].REMESSA_ID) {
                    var qtd = (itens[i].QUANTIDADE == undefined) ? 0 : parseFloat(itens[i].QUANTIDADE);
                    summ += qtd;
                    
                    var qtd_alt = (itens[i].QUANTIDADE_ALTERNATIVA == undefined) ? 0 : parseFloat(itens[i].QUANTIDADE_ALTERNATIVA);
                    summ_alt += qtd_alt;
                }
            }
            
            remessa.QUANTIDADE_SOMA = summ;
            remessa.QUANTIDADE_ALTERNATIVA_SOMA = summ_alt;
        };
                
		vm.limparFiltro = function() {
            if ( vm.filtrar_arvore ) {
                vm.filtrar_arvore = false;
            }
        };
        
        vm.FiltrarChange = function() {
            $timeout(function(){
                $('.talao .scroll-table').scrollTop(0);
            }, 10);
        };
        
        vm.Talao = {
            verificarSobras : function (remessa_id) {

                var dados = {
                    dados: {
                        remessa_id : remessa_id
                    },
                    retorno: true,
                    param: {
                        remessa: String($filter('uppercase')(vm.remessa.REMESSA))
                    }
                };
                $ajax.post('/_22120/post-aproveitamento-sobra',dados)
                    .then(function(response) {  
                        vm.estruturaAction.loadData(response.dados);
                    }
                );
            }
        };
        
        vm.TaloesExtra = {
            DADOS : {
                SKUS : [],
                TALOES_EXTRA : [],
                SELECTED : {}
            },
            Consultar : function(remessa_id) {
                var that = this;
                
                var dados = {
                    remessa_id : remessa_id
                };
                
                $ajax.post('/_22120/get-taloes-extras',dados)
                    .then(function(response) {  
                        if (response) {

                            
                            gcCollection.merge(that.DADOS.SKUS        , response.SKUS        );
                            gcCollection.merge(that.DADOS.TALOES_EXTRA, response.TALOES_EXTRA);
                            
                            var taloes = that.DADOS.TALOES_EXTRA;
                            for ( var i in taloes ) {
                                var talao_extra = taloes[i];
                                
                                var skus = that.DADOS.SKUS;
                                for ( var y in skus ) {
                                    var sku = skus[y];
                                    if ( 
                                        talao_extra.MODELO_ID == sku.MODELO_ID && 
                                        talao_extra.COR_ID == sku.COR_ID && 
                                        talao_extra.TAMANHO == sku.TAMANHO
                                    ) {
                                        talao_extra.EXTEND = sku;
                                    }
                                }
                            }

                            $('#modal-taloes-extra').modal('show');
                        }
                    })
                ; 
            },
            Gravar : function() {
                var that = this;

                addConfirme('<h4>Confirmação</h4>',
                    'Confirma a geração dos Talões Extras?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){
                        $scope.$apply(function(){

                            var dados = {
                                dados: that.DADOS.TALOES_EXTRA,
                                retorno: true,
                                param: {
                                    remessa: String($filter('uppercase')(vm.remessa.REMESSA))
                                }
                            };

                            $ajax.post('/_22120/post-taloes-extras',JSON.stringify(dados),{contentType: 'application/json'})
                                .then(function(response) {  
                                    showSuccess(response.success);

                                    vm.estruturaAction.loadData(response.dados);
                                    
                                    $('#modal-taloes-extra').modal('hide');
                                })
                            ;    
                        });
                    }}]     
                );                
                
                
            }
        };
        
        vm.fixVsRepeatRemessa = function() {
            $timeout(function(){
                $('.table-remessas .scroll-table').scrollTop(0);
            }, 10);
        };
        
        function resizable() {
            $timeout(function () {
                $('.recebe-puxador-talao, .recebe-puxador-comum')
                    .resizable({
                        resize  : function( event, ui ) {
                            $scope.$apply(function(){
                                $(document).resize();
                            });

                        },
                        handles  : 's',
                        minHeight : 48
                    })
                ;
            }, 500);
        }
        
        $scope.$on('bs-init', function(ngRepeatFinishedEvent) {
            bootstrapInit();
        });        
        
        $('#modal-remessa')
            .on('show.bs.modal', function(){
                resizable();
            })
            .on('hide.bs.modal', function () {
                window.history.pushState('Delfa - GC', 'Title', encodeURI(urlhost + '/_22120'));
            })
            .on('hidden.bs.modal', function(){
                $('.pesquisa.filtro-obj').select();
            })
        ;
        
        $('#modal-taloes-extra')
            .on('shown.bs.modal', function(){
                $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
//                $(document).resize();
            })
        ;
                
    };
    
    MyCtrl.$inject = [
        '$scope',
        '$ajax',
        '$filter',
        '$timeout',
        '$document',
        '$rootScope',
        'gcCollection',
        'Historico'
    ];
    
    angular.module('app', [
        'angular.filter',
        'vs-repeat',
        'gc-ajax',
        'gc-find',
        'gc-utils',
        'gc-transform',
        'datatables',
//        'ui.grid'
    ]);
    
    angular.module('app').directive('bsInit', function() {
        return function(scope, element, attrs) {         
            bootstrapInit();
        };
    });
    
    angular.module('app').directive('stringToNumber', function() {
        return {
            require: 'ngModel',
            link: function(scope, element, attrs, ngModel) {
                ngModel.$parsers.push(function(value) {
                    return '' + value;
                });
                ngModel.$formatters.push(function(value) {
                    return parseFloat(value);
                });
            }
        };
    });
                
    angular.module('app').directive('ngUpdateHidden', function () {
        return {
            restrict: 'AE', //attribute or element
            scope: {},
            replace: true,
            require: 'ngModel',
            link: function (vm, elem, attr, ngModel) {
                vm.$watch(ngModel, function (nv) {
                    elem.val(nv);
                });
                elem.change(function () { //bind the change event to hidden input
                    vm.$apply(function () {
                        ngModel.$setViewValue(  elem.val());
                    });
                });
            }
        };
    });
    
    angular.module('app').directive('ngRightClick', ['$parse',function($parse) {
        return function(scope, element, attrs) {
            var fn = $parse(attrs.ngRightClick);
            element.bind('contextmenu', function(event) {
                scope.$apply(function() {
                    event.preventDefault();
                    fn(scope, {$event:event});
                });
            });
        };
    }]);
   
    angular.module('app').filter('lpad', function() {
        return function(input, n) {
            
            var tamanho   = ( n[0] == undefined ) ? n   : n[0];
            var caractere = ( n[1] == undefined ) ? " " : n[1];
            
            if(input === undefined)
                input = "";
            if(input.length >= tamanho)
                return input;
            var zeros = caractere.repeat(tamanho);
            return (zeros + input).slice(-1 * tamanho);
        };
    });
  
    
    angular.module('app').filter('parseDate', function() {
        return function(input) {
            if ( input ) return new Date(input);
        };
    });

    angular.module('app').controller('MyCtrl', MyCtrl);
    
})(angular);


(function($) {
    
	$(function() {
        
        
        
        
	});
	
})(jQuery);