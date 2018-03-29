angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$ajax',
        '$filter',
        '$timeout',
        '$sce',
        '$consulta',
        'gcCollection', 
        'Historico',
        'gScope',
        'RemessaIntermediaria',
        'RemessaComponente'
    ];

	function Ctrl( 
        $scope, 
        $ajax,
        $filter,
        $timeout, 
        $sce,
        $consulta,
        gcCollection, 
        Historico,
        gScope,
        RemessaIntermediaria,
        RemessaComponente
    ) {

		var vm = this;
        
		vm.RemessaIntermediaria = new RemessaIntermediaria();
		vm.RemessaComponente    = new RemessaComponente();
        vm.Consulta             = new $consulta();
        
        
        vm.ConsultaRemessaVinculo                             = vm.Consulta.getNew(true);
        vm.ConsultaRemessaVinculo.componente                  = '.consulta-remessa-vinculo';
        vm.ConsultaRemessaVinculo.model                       = 'vm.ConsultaRemessaVinculo';
        vm.ConsultaRemessaVinculo.option.label_descricao      = 'Remessa Vinculada:';
        vm.ConsultaRemessaVinculo.option.obj_consulta         = '/_22120/api/remessas-vinculo';
//        vm.ConsultaRemessaVinculo.option.tamanho_input        = '';
//        vm.ConsultaRemessaVinculo.option.tamanho_tabela       = 427;
        vm.ConsultaRemessaVinculo.option.campos_tabela        = [['REMESSA', 'REMESSA']];
        vm.ConsultaRemessaVinculo.option.obj_ret              = ['REMESSA'];
//        vm.ConsultaRemessaVinculo.require                     = vm.Remessa;
//        vm.ConsultaRemessaVinculo.vincular();
        vm.ConsultaRemessaVinculo.setDataRequest({REMESSA: [vm.RemessaIntermediaria.FILTRO, 'REMESSA']});
        vm.ConsultaRemessaVinculo.compile();
        gScope.ConsultaRemessaVinculo = vm.ConsultaRemessaVinculo;


        vm.ConsultaGp                             = vm.Consulta.getNew(true);
        vm.ConsultaGp.componente                  = '.consulta-gp';
        vm.ConsultaGp.model                       = 'vm.ConsultaGp';
        vm.ConsultaGp.option.label_descricao      = 'GP:';
        vm.ConsultaGp.option.obj_consulta         = '/_22030/api/gp';
        vm.ConsultaGp.option.tamanho_input        = 'input-maior';
        vm.ConsultaGp.option.tamanho_tabela       = 427;
        vm.ConsultaGp.option.campos_tabela        = [['GP_ID', 'ID'],['GP_DESCRICAO','GRUPO DE PRODUÇÃO']];
        vm.ConsultaGp.option.obj_ret              = ['GP_ID', 'GP_DESCRICAO'];
        vm.ConsultaGp.require                     = vm.ConsultaRemessaVinculo;
        vm.ConsultaGp.vincular();
        vm.ConsultaGp.compile();
        gScope.ConsultaGp = vm.ConsultaGp;
        

        vm.RcConsultaGp                             = vm.Consulta.getNew(true);
        vm.RcConsultaGp.componente                  = '.rc-consulta-gp';
        vm.RcConsultaGp.model                       = 'vm.RcConsultaGp';
        vm.RcConsultaGp.option.label_descricao      = 'GP:';
        vm.RcConsultaGp.option.obj_consulta         = '/_22030/api/gp';
        vm.RcConsultaGp.option.tamanho_input        = 'input-maior';
        vm.RcConsultaGp.option.tamanho_tabela       = 427;
        vm.RcConsultaGp.option.campos_tabela        = [['GP_ID', 'ID'],['GP_DESCRICAO','GRUPO DE PRODUÇÃO']];
        vm.RcConsultaGp.option.obj_ret              = ['GP_ID', 'GP_DESCRICAO'];
        vm.RcConsultaGp.setDataRequest({GP_FAMILIAS_ID: [vm.RemessaComponente.FILTRO, 'FAMILIAS_ID']});
        vm.RcConsultaGp.compile();
        vm.RcConsultaGp.disable(true);
        gScope.RcConsultaGp = vm.RcConsultaGp;        

        vm.ConsConsultaProduto                             = vm.Consulta.getNew(true);
        vm.ConsConsultaProduto.componente                  = '.cons-consulta-produto';
        vm.ConsConsultaProduto.model                       = 'vm.ConsConsultaProduto';
        vm.ConsConsultaProduto.option.label_descricao      = 'Produto:';
        vm.ConsConsultaProduto.option.obj_consulta         = '/_27050/api/produto';
        vm.ConsConsultaProduto.option.tamanho_input        = 'input-maior';
        vm.ConsConsultaProduto.option.tamanho_tabela       = 427;
        vm.ConsConsultaProduto.option.campos_tabela        = [['PRODUTO_ID', 'ID'],['PRODUTO_DESCRICAO','PRODUTO']];
        vm.ConsConsultaProduto.option.obj_ret              = ['PRODUTO_ID', 'PRODUTO_DESCRICAO'];
        vm.ConsConsultaProduto.setDataRequest({STATUS: 1,MODELO_ID: '> 0'});
        vm.ConsConsultaProduto.compile();
        gScope.ConsConsultaProduto = vm.ConsConsultaProduto;        
       

        vm.ConsConsultaModeloTamanho                             = vm.Consulta.getNew(true);
        vm.ConsConsultaModeloTamanho.componente                  = '.cons-consulta-modelo-tamanho';
        vm.ConsConsultaModeloTamanho.model                       = 'vm.ConsConsultaModeloTamanho';
        vm.ConsConsultaModeloTamanho.option.label_descricao      = 'Tamanho:';
        vm.ConsConsultaModeloTamanho.option.obj_consulta         = '/_27020/api/modelo/tamanho';
        vm.ConsConsultaModeloTamanho.option.tamanho_input        = 'input-menor';
        vm.ConsConsultaModeloTamanho.option.tamanho_tabela       = 100;
        vm.ConsConsultaModeloTamanho.option.campos_tabela        = [['TAMANHO_DESCRICAO', 'Tam.']];
        vm.ConsConsultaModeloTamanho.option.obj_ret              = ['TAMANHO_DESCRICAO'];
        vm.ConsConsultaModeloTamanho.require                     = vm.ConsConsultaProduto;
        vm.ConsConsultaModeloTamanho.vincular();
        vm.ConsConsultaModeloTamanho.setDataRequest({MODELO_ID: [vm.ConsConsultaProduto, 'MODELO_ID']});
        vm.ConsConsultaModeloTamanho.compile();
        gScope.ConsConsultaModeloTamanho = vm.ConsConsultaModeloTamanho;        
       
        

        $scope.$watch('vm.RemessaComponente.REMESSA_TIPO_AUTO', function (newValue, oldValue, scope) {

            if ( newValue != undefined && newValue.trim() != '' ) {
                $timeout(function(){
                    vm.RemessaComponente.modalOpen();
                    vm.RemessaComponente.FILTRO.REMESSA_TIPO = newValue;
                });
            }
        }, true);

        $scope.$watch('vm.RemessaComponente.REMESSA_ORIGEM_AUTO', function (newValue, oldValue, scope) {

            if ( newValue != undefined && newValue.trim() != '' ) {
                $timeout(function(){
                    vm.RemessaComponente.FILTRO.ORIGEM       = newValue;
                    vm.RemessaComponente.FILTRO.AUTO_FILTER = true;
                    vm.RemessaComponente.consultarOrigemDados();
                });
            }
        }, true);

        $scope.$watch('vm.RemessaComponente.FILTRO.REMESSA_TIPO', function (newValue, oldValue, scope) {

            vm.RemessaComponente.FILTRO.ORIGEM          = '';
            vm.RemessaComponente.FILTRO.ORIGEM_SELECTED = false;

            if ( newValue == 3 || newValue == 4 ) {
                
                vm.RemessaComponente.consultarOrigemDados();
            }
        }, true);

        $scope.$watch('vm.RemessaComponente.FILTRO.ORIGEM_SELECTED', function (newValue, oldValue, scope) {

            vm.RcConsultaGp.apagar();
            vm.RcConsultaGp.disable(!newValue);
        }, true);        
            

        $scope.$watch('vm.RemessaIntermediaria.FILTRO.REMESSA_SELECTED', function (newValue, oldValue, scope) {
            if ( newValue ) {
                vm.ConsultaRemessaVinculo.filtrar();
            } else 
            if ( !newValue ) {
                vm.ConsultaRemessaVinculo.apagar();
            } 
        }, true);

        vm.trustedHtml = function (plainText) {
            return $sce.trustAsHtml(plainText);
        };
        
        
        
        
        var data_table = $.extend({}, table_default);
            data_table.scrollY = 'calc(100% - 35px)';
        
        vm.Historico           = new Historico();
        vm.Math = window.Math;
        vm.DADOS    = [];
        vm.dtOptions           = data_table;
        vm.remessa             = "";
        vm.TALAO_ORDER_BY      = '';
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
        
        
        
        vm.RemessaKeypress = function(item,tipo,$event) {

            if ( $event.key == ' ' ) {

                $event.preventDefault();

                vm.selectItemAcao(item,tipo,'ID');
            }
        };

        vm.remessaAction = {
            Filtrar: function() {
                
                loading($('.table-remessas'));
                var dados = {
                    data_1 : moment(vm.filtro.data_1).format('DD.MM.YYYY'),
                    data_2 : moment(vm.filtro.data_2).format('DD.MM.YYYY')
                };
                
                var remessa = String($filter('uppercase')(vm.filtrar_remessa));
                
                if ( remessa != "undefined" && String(vm.filtrar_remessa+'').trim() != '' ) {
                    dados = { remessa : remessa };
                }
                
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
            },
            alterar : function() {
                
                
    
                var data = {
                    DADOS : {
                        CONSUMOS: vm.selected_itens_acao['CONSUMO'],
                        PRODUTO_ID : vm.ConsConsultaProduto.PRODUTO_ID,
                        TAMANHO    : vm.ConsConsultaModeloTamanho.TAMANHO
                    },
                    FILTRO : { remessa : String($filter('uppercase')(vm.remessa.REMESSA)) }
                };                
  
                $ajax.post('/_22120/api/consumo/alterar',data)
                    .then(function(response) {  
                        vm.ConsConsultaProduto.apagar();
                        vm.estruturaAction.loadData(response.DATA_RETURN.DADOS);
                        $('#modal-alterar-consumo').modal('hide');                      
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
            },
            liberacaoCancelar : function () {
                
                addConfirme('<h4>Confirmação</h4>',
                    'Confirma o cancelamento da liberação dos talões selecionados?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){
                        $scope.$apply(function(){

                            var data = {
                                DADOS : {
                                    TALOES: vm.selected_itens_acao['TALAO']
                                },
                                FILTRO : { remessa : String($filter('uppercase')(vm.remessa.REMESSA)) }
                            };                

                            $ajax.post('/_22120/api/talao/liberacao/cancelar',data)
                                .then(function(response) {  
                                    vm.estruturaAction.loadData(response.DATA_RETURN.DADOS);            
                                })
                            ; 
                        });
                    }}]     
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
        
        
        
        gScope.Estrutura = vm.estruturaAction;
	}   
  