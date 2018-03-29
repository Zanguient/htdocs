'use strict';

angular
	.module('app', [
		'vs-repeat', 
        'gc-find',
		'gc-ajax',
		'gc-transform',
		'gc-form',
		'gc-utils',
        'infinite-scroll',
        'ngSanitize'
	])
;
     
(function(window, angular) {
    'use strict';

    angular
        .module('app')
        .factory('Comparar', Comparar);

	Comparar.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$compile',
        '$timeout',
        '$consulta',
        'gScope',
        'gcCollection',
        'gcObject'
    ];

	function Comparar($ajax, $q, $rootScope, $compile,$timeout, $consulta, gScope, gcCollection, gcObject) {

        // Private variables.
        var obj = null;
        var selected = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function Comparar() {
            
            obj = this; 

            // Public methods         
  
            this.getTransportadoraCidade = getTransportadoraCidade;
            
            this.autoComparar = autoComparar;
            
            this.calcular       = calcular;
            this.inserirItem    = inserirItem;
            this.alterar        = alterar; 
            this.cancelar       = cancelar; 
            this.excluir        = excluir; 
          
            this.changeRebobinamento = changeRebobinamento;
            this.changeConformacao   = changeConformacao;
            
            this.Modal         = Modal; 
            
            this.ORDER_BY = '-DATA_ENTRADA_JS';
            
            this.FILTRO    = {
                DATA_1 : moment().subtract(1, "month").toDate(),
                DATA_2 : moment().toDate()                
            };
        
            
            this.INCLUINDO = false;
            this.ALTERANDO = false;
            this.DADOS = [];
            this.DADOS_RENDER = [];

            this.ITENS          = [];
            this.DADOS          = [];
            this.DADOS_DETALHES = [];
            this.TRANSPORTADORAS     = [];
            
            this.TOTAL_GERAL = 0;
            this.SELECTEDS = [];
            this.SELECTED = [];
            this.SELECTED_BACKUP = [];
            this.DOM_ID = 0;
            
	    }
        
        function getTransportadoraCidade(cidade_id) {
            return $q(function(resolve, reject){
                $ajax.post('/_14020/api/transportadora/cidade',{CIDADE_ID : cidade_id}).then(function(response){
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }
        
        function autoComparar(cidade_id) {
            
            getTransportadoraCidade(cidade_id).then(function(response){
                
                var transportadoras = response;
                
                for ( var i in transportadoras ) {
                    var transportadora = transportadoras[i];
                    
                    inserirItem({
                        TRANSPORTADORA_ID : transportadora.TRANSPORTADORA_ID,
                        TRANSPORTADORA_RAZAOSOCIAL : transportadora.RAZAOSOCIAL
                    },true);
                }
                
                inserirItem();
            });
        }
        
        function calcular(transportadora_id) {
            
            var filtro = {
                ORIGEM : obj.DADOS.ORIGEM,
                ORIGEM_ID : obj.DADOS.ORIGEM_ID,
                TRANSPORTADORA_ID : transportadora_id,
                ITENS : obj.DADOS.ITENS
            };
            
            return $q(function(resolve, reject){
                $ajax.post('/_14020/api/frete/calcular',filtro).then(function(response){
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }

        function inserirItem(transportadora,no_auto_insert) {
   
            obj.DOM_ID += 1;
            
            var item = {DOM_ID:obj.DOM_ID}; 
            
            var id = item.DOM_ID;
    
            item.REQUIRED = obj.TRANSPORTADORAS.length == 0 ? true : false;
            obj.TRANSPORTADORAS.push(item);
    

            
            item.ConsultaTransportadora                        = gScope.Ctrl.Consulta.getNew(true);
            item.ConsultaTransportadora.componente             = '.consulta-transportadora-'+id;
            item.ConsultaTransportadora.model                  = 'item.ConsultaTransportadora';
            item.ConsultaTransportadora.option.label_descricao = 'Transportadora:';
            item.ConsultaTransportadora.option.obj_consulta    = '/_14020/api/transportadora';
            item.ConsultaTransportadora.option.tamanho_input   = 'input-maior';
            item.ConsultaTransportadora.option.tamanho_tabela  = 650;
            item.ConsultaTransportadora.option.campos_tabela   = [['TRANSPORTADORA_ID', 'Id'],['RAZAOSOCIAL','Razão Social'],['NOMEFANTASIA', 'Nome Fantasia'],['CLASSIFICACAO','Classificação']];
            item.ConsultaTransportadora.option.obj_ret         = ['TRANSPORTADORA_ID','RAZAOSOCIAL'];
            item.ConsultaTransportadora.option.required        = true;
            item.ConsultaTransportadora.autoload               = false;

            item.ConsultaTransportadora.onSelect = function(){
                
                
                obj.calcular(item.ConsultaTransportadora.TRANSPORTADORA_ID).then(function(response){
                    
                    angular.extend(item,response);
                    
                    item.COMPOSICOES = [
                        {
                            DESCRICAO : 'Composição dos Valores',
                            DADOS : response.DADOS_COMPOSICAO
                        },
                        {
                            DESCRICAO : 'Dados da Carga',
                            DADOS : response.DADOS_CARGA
                        }                         
                    ];                

                    if ( !(no_auto_insert == true) )  {
                        obj.inserirItem();
                    }
                });
                
            };
            
            item.ConsultaTransportadora.onClear = function() {
                
//                item.MODELO_ID = undefined;
                
                var idx = obj.TRANSPORTADORAS.indexOf(item);
                if ( obj.TRANSPORTADORAS.length-1 != idx ) {
                    obj.TRANSPORTADORAS.splice(idx,1);
                    
                    if ( obj.TRANSPORTADORAS.length == 1 ) {
                        obj.TRANSPORTADORAS[0].REQUIRED = true;
                        item.ConsultaTransportadora.option.required  = item.REQUIRED;
                    }
                }
                
            };



            $timeout(function(){
                item.ConsultaTransportadora.compile();

                if ( transportadora != undefined ) {
                    item.ConsultaTransportadora.TRANSPORTADORA_ID          = transportadora.TRANSPORTADORA_ID;
                    item.ConsultaTransportadora.TRANSPORTADORA_RAZAOSOCIAL = obj.DADOS.TRANSPORTADORA_RAZAOSOCIAL;
                    item.ConsultaTransportadora.Input.value = transportadora.TRANSPORTADORA_ID + ' - ' + transportadora.TRANSPORTADORA_RAZAOSOCIAL;
                    item.ConsultaTransportadora.Input.readonly             = true;
                    item.ConsultaTransportadora.btn_apagar_filtro.visivel  = true;
                    item.ConsultaTransportadora.btn_apagar_filtro.disabled = false;
                    item.ConsultaTransportadora.btn_filtro.visivel         = false;            
                    item.ConsultaTransportadora.item.selected              = true;  
                    item.ConsultaTransportadora.onSelect();
                }

            });
            
            return item;
        }

        function alterar() {
            
//            gScope.Ctrl.ImobilizadoItem.SELECTEDS = [];
            
            
            gScope.Ctrl.ConsultaFamiliaAgrup.Input.value = obj.SELECTED.FAMILIA_PRODUCAO + ' - ' + obj.SELECTED.FAMILIA_PRODUCAO_DESCRICAO;  
            gScope.Ctrl.ConsultaFamiliaAgrup.Input.readonly             = true;
            gScope.Ctrl.ConsultaFamiliaAgrup.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaFamiliaAgrup.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaFamiliaAgrup.btn_filtro.visivel         = false;              
            gScope.Ctrl.ConsultaFamiliaAgrup.item.selected = true;            
            
            
            gScope.Ctrl.ConsultaFamilia.Input.value = obj.SELECTED.FAMILIA_ID + ' - ' + obj.SELECTED.FAMILIA_DESCRICAO;
            gScope.Ctrl.ConsultaFamilia.Input.readonly             = true;
            gScope.Ctrl.ConsultaFamilia.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaFamilia.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaFamilia.btn_filtro.visivel         = false;            
            gScope.Ctrl.ConsultaFamilia.item.selected = true;            
            
            
            if ( obj.SELECTED.GP_ID > 0 ) {
                gScope.Ctrl.ConsultaGp.Input.value = obj.SELECTED.GP_ID + ' - ' + obj.SELECTED.GP_DESCRICAO;
                gScope.Ctrl.ConsultaGp.Input.readonly             = true;
                gScope.Ctrl.ConsultaGp.btn_apagar_filtro.visivel  = true;
                gScope.Ctrl.ConsultaGp.btn_apagar_filtro.disabled = false;
                gScope.Ctrl.ConsultaGp.btn_filtro.visivel         = false;            
                gScope.Ctrl.ConsultaGp.item.selected = true;            
            } else {
                gScope.Ctrl.ConsultaGp.apagar();
                gScope.Ctrl.ConsultaGp.disable(true);
            }
            
            
            if ( obj.SELECTED.PERFIL_UP != null && obj.SELECTED.PERFIL_UP != '' ) {
                gScope.Ctrl.ConsultaPerfil.Input.value = obj.SELECTED.PERFIL_UP + ' - ' + obj.SELECTED.PERFIL_UP_DESCRICAO;
                gScope.Ctrl.ConsultaPerfil.Input.readonly             = true;
                gScope.Ctrl.ConsultaPerfil.btn_apagar_filtro.visivel  = true;
                gScope.Ctrl.ConsultaPerfil.btn_apagar_filtro.disabled = false;
                gScope.Ctrl.ConsultaPerfil.btn_filtro.visivel         = false;            
                gScope.Ctrl.ConsultaPerfil.item.selected = true;            
            } else {
                gScope.Ctrl.ConsultaPerfil.apagar();
                gScope.Ctrl.ConsultaPerfil.disable(true);
            }
            
            
            gScope.Ctrl.ConsultaUp1.Input.value = obj.SELECTED.UP_PADRAO1 + ' - ' + obj.SELECTED.UP_PADRAO1_DESCRICAO;
            gScope.Ctrl.ConsultaUp1.Input.readonly             = true;
            gScope.Ctrl.ConsultaUp1.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaUp1.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaUp1.btn_filtro.visivel         = false;
            gScope.Ctrl.ConsultaUp1.item.selected = true;
            
            
            if ( obj.SELECTED.UP_PADRAO2 > 0 ) {
                gScope.Ctrl.ConsultaUp2.Input.value = obj.SELECTED.UP_PADRAO2 + ' - ' + obj.SELECTED.UP_PADRAO2_DESCRICAO;
                gScope.Ctrl.ConsultaUp2.Input.readonly             = true;
                gScope.Ctrl.ConsultaUp2.btn_apagar_filtro.visivel  = true;
                gScope.Ctrl.ConsultaUp2.btn_apagar_filtro.disabled = false;
                gScope.Ctrl.ConsultaUp2.btn_filtro.visivel         = false;
                gScope.Ctrl.ConsultaUp2.item.selected              = true; 
            } else {
                gScope.Ctrl.ConsultaUp2.apagar();
            }
            
            
            gScope.Ctrl.ConsultaCLogistica.Input.value = obj.SELECTED.CLOGISTICA_MASK + ' - ' + obj.SELECTED.CLOGISTICA_DESCRICAO;
            gScope.Ctrl.ConsultaCLogistica.Input.readonly             = true;
            gScope.Ctrl.ConsultaCLogistica.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaCLogistica.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaCLogistica.btn_filtro.visivel         = false;
            gScope.Ctrl.ConsultaCLogistica.item.selected = true;            
            
            
            this.ALTERANDO = true;
            this.INCLUINDO = false;
            angular.copy(this.SELECTED, this.SELECTED_BACKUP);
            
            obj.Modal.show();
        };        
        
        function cancelar() {
            
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente cancelar esta operação?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){
                        
                             
                        
                        if ( obj.ALTERANDO && !obj.INCLUINDO ) {
                            angular.extend(obj.SELECTED, obj.SELECTED_BACKUP);  
                            
                            obj.Modal.hide();
                        }
                        
                        obj.ALTERANDO = false;
                        
                        if ( obj.INCLUINDO ) {
                            obj.INCLUINDO = false;
                            
                            var idx = obj.DADOS.indexOf(obj.SELECTED);
                            
                            obj.DADOS.splice(idx,1);                            
                                                    
                            
//                            obj.selectedReset();
                            obj.Modal.hide();
                        }
                    });
                }}]     
            );
        }
        
        function excluir() {
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente excluir este item?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){
                        
                        obj.SELECTED.EXCLUIDO = true;
                    });
                }}]     
            );
        }
        
        
        /**
         * Retorna a view da factory
         */
        function renderChart(response) {


            var obj = $('#chart_div');
            var pai = $(obj).closest('#chart-google');

            google.charts.load('current', {packages:["orgchart"]});
            google.charts.setOnLoadCallback(function() {
                
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Name');
                data.addColumn('string', 'Manager');
                data.addColumn('string', 'ToolTip');

                var dados = response;
                var item = null;
                
                var chart_container = [];
                var chart_item = [];
                
                for ( var i in dados ) {
                    item = dados[i];
                    
                    chart_item = [
                        {
                            v:''+item.ID,
                            f:item.CLOGISTICA_DESCRICAO,
                            id: ''+item.ORDEM
                        },
                        ''+( (item.ORDEM - 1) > 0 ? item.ORDEM - 1 : ''),
                        ''
                    ];
                    
                    chart_container.push(chart_item);
                }
                
                // For each orgchart box, provide the name, manager, and tooltip to show.
                data.addRows(chart_container);

                var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
                chart.draw(data, {allowHtml:true});

                setTimeout(function(){
//
//                    $('.img-loading').css('display','none');
                    $(obj).replaceWith( $compile($(obj).html())($rootScope) );
                    $(pai).append('<div style="width: 99%; height: 100%;" id="chart_div"></div>');

                    $rootScope.$apply(function () {
                        $rootScope.message = "Timeout called!";
                    });

                },400);
            });
        }
        
        function changeConformacao() {
            if ( obj.SELECTED.CALCULO_CONFORMACAO_MODEL ) {
                obj.SELECTED.CALCULO_CONFORMACAO           = 1;
                obj.SELECTED.CALCULO_CONFORMACAO_DESCRICAO = 'SIM';
            } else {
                obj.SELECTED.CALCULO_CONFORMACAO           = 0;
                obj.SELECTED.CALCULO_CONFORMACAO_DESCRICAO = 'NÃO';
            }
        }  
        
        function changeRebobinamento(key) {
            if ( obj.SELECTED.CALCULO_REBOBINAMENTO_MODEL ) {
                obj.SELECTED.CALCULO_REBOBINAMENTO           = 1;
                obj.SELECTED.CALCULO_REBOBINAMENTO_DESCRICAO = 'SIM';
            } else {
                obj.SELECTED.CALCULO_REBOBINAMENTO           = 0;
                obj.SELECTED.CALCULO_REBOBINAMENTO_DESCRICAO = 'NÃO';
            }
        }  
        
        var Modal = {
            
            _modal : function(){
                return $('#modal-regra');
            },
            show : function(shown,hidden) {

                this._modal()
                    .modal('show')
                ;                         


                this._modal()
                    .one('shown.bs.modal', function(){

                        $(this).find('input:focusable').first().select();

                        if ( shown ) {
                            shown(); 
                        }
                    })
                ;    

                    this._modal()
                        .one('hidden.bs.modal', function(){
                            
                            if ( hidden ) {
                                hidden();      
                            }
                        })
                    ;        
            },
            hide : function(hidden) {

                this._modal()
                    .modal('hide')
                ;

                if ( hidden ) {
                    this._modal()
                        .one('hidden.bs.modal', function(){
                            hidden ? hidden() : '';
                        })
                    ;                      
                }
            }
        };     

            
	    /**
	     * Return the constructor function
	     */
	    return Comparar;
	};
   
})(window, window.angular);
(function(window, angular) {
    'use strict';

    angular
        .module('app')
        .factory('Simulador', Simulador);

	Simulador.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$compile',
        '$timeout',
        '$consulta',
        'gScope',
        'gcCollection',
        'gcObject'
    ];

	function Simulador($ajax, $q, $rootScope, $compile,$timeout, $consulta, gScope, gcCollection, gcObject) {

        // Private variables.
        var obj = null;
        var selected = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function Simulador() {
            
            obj = this; 

            // Public methods         
            this.consultar        = consultar;
            this.calcular         = calcular;
            this.merge            = merge;
            
            
            this.incluir        = incluir; 
            this.inserirItem    = inserirItem;
            this.alterar        = alterar; 
            this.cancelar       = cancelar; 
            this.excluir        = excluir; 
          
            this.changeRebobinamento = changeRebobinamento;
            this.changeConformacao   = changeConformacao;
            
            this.Modal         = Modal; 
            
            this.ORDER_BY = '-DATA_ENTRADA_JS';
            
            this.FILTRO    = {
                DATA_1 : moment().subtract(1, "month").toDate(),
                DATA_2 : moment().toDate()                
            };
        
            
            this.INCLUINDO = false;
            this.ALTERANDO = false;
            this.DADOS = [];
            this.DADOS_RENDER = [];

            this.ITENS          = [];
            this.DADOS          = [];
            this.DADOS_DETALHES = [];
            
            this.TOTAL_GERAL = 0;
            this.SELECTEDS = [];
            this.SELECTED = [];
            this.SELECTED_BACKUP = [];
            this.DOM_ID = 0;
            
            obj.inserirItem();
	    }
        
        
        

        function consultar() {
            
            
            var filtro = {};
            angular.copy(obj.FILTRO,filtro);
            
            if ( gScope.Ctrl.ConsultaFreteTransportadora.ID > 0 ) {
                filtro.FRETE_TRANSPORTADORA_ID = "="+gScope.Ctrl.ConsultaFreteTransportadora.ID;
            } else {
                delete filtro.FRETE_TRANSPORTADORA_ID;
            }
            
            if ( filtro.DOCUMENTO_TODOS == true ) {
                delete filtro.DOCUMENTO;
            } else {
                filtro.DOCUMENTO = "LIKE '%' || UPPER('" + filtro.DOCUMENTO.replace(" ", "%") + "') || '%'";
            }
            
            if ( filtro.DATA_TODOS == undefined || filtro.DATA_TODOS == false ) {
                filtro.DATA_ENTRADA = "BETWEEN '" + moment(filtro.DATA_1).format('DD.MM.YYYY') + "' AND '" + moment(filtro.DATA_2).format('DD.MM.YYYY') + "'";                
            }
            
            delete filtro.DOCUMENTO_TODOS;
            delete filtro.DATA_TODOS;
            delete filtro.DATA_1;
            delete filtro.DATA_2;
            
            return $q(function(resolve, reject){
                $ajax.post('/_14020/api/ctrc',filtro).then(function(response){
                    
                    obj.merge(response);
                    
                    if ( obj.DADOS.length == 0 ) {
                        obj.SELECTED = obj.DADOS[0];
                        $('#tab-cte tbody tr').first().click();
                    }
                    
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }      
        
        

        function calcular() {
            try {


                var objTransportadora = gScope.Ctrl.ConsultaFreteTransportadoraSimuladorItens;
                var objCliente = gScope.Ctrl.ConsultaClienteSimuladorItens;
                var objCidade  = gScope.Ctrl.ConsultaCidadeSimuladorItens;

                var origem = '';
                var origem_id = '';
                var itens = [];

                if( objCliente.item.selected == true ) {
                    origem = 'SIMULADOR';
                    origem_id = objCliente.ID;
                } else {
                    origem    = 'SIMULADOR_CIDADE';
                    origem_id = objCidade.ID;
                }

                for ( var i in obj.ITENS ) {
                    var item = obj.ITENS[i];

                    if ( item.MODELO_ID != undefined ) {
                        
                        if ( item.COR_ID == undefined ) {
                            throw 'Todos os itens deverão ter "Cor"';
                        }
                        else
                        if ( item.TAMANHO == undefined ) {
                            throw 'Todos os itens deverão ter "Tamanho"';
                        }
                        else
                        if ( item.TAMANHO == undefined ) {
                            throw 'Todos os itens deverão ter "Tamanho"';
                        }
                        else
                        if ( item.QUANTIDADE == undefined ) {
                            throw 'Todos os itens deverão ter "Quantidade"';
                        }
                        else
                        if ( item.VALOR_UNITARIO == undefined ) {
                            throw 'Todos os itens deverão ter "Valor Unitário"';
                        }
                    
                        itens.push(item);
                    }
                }

                if ( itens.length == 0 ) {
                    throw  'Selecione algum item para realizar a simulação.';
                }
                
                gScope.Ctrl.Frete.calcular(origem,origem_id,objTransportadora.TRANSPORTADORA_ID,null,true,true,itens).then(function(){
                    gScope.Ctrl.ConsultaFreteTransportadoraSimulador.disable(true);
                    gScope.Ctrl.Frete.Modal.show();

                });                
                
            } catch(err) {
                showErro(err);
            }
        }      
        
        
        

//        function consultarDetalhe() {
//            
//            return $q(function(resolve, reject){
//                $ajax.get('/_14020/api/rateio/tipo/detalhe').then(function(response){
//                    
//                    obj.merge(response);
//                    
//                    resolve(response);
//                },function(e){
//                    reject(e);
//                });
//            });
//        }                
        
        /**
         * Retorna a view da factory
         */
        function merge(response, preserve_main) {
            
            sanitizeJson(response);
            
            gcCollection.merge(obj.DADOS, response,'ID');
            
            var item = null;
            for ( var i in obj.DADOS ) {
                item = obj.DADOS[i];
                
                item.DATA_ENTRADA_JS = moment(item.DATA_ENTRADA).toDate();
                item.DATA_EMISSAO_JS = moment(item.DATA_EMISSAO).toDate();
                
            }
            
        }
        
        
        function incluir() {
            
            
            obj.SELECTED = {
                CALCULO_CONFORMACAO_MODEL       :false ,
                CALCULO_CONFORMACAO             :0     ,
                CALCULO_CONFORMACAO_DESCRICAO   :'NÃO' ,
                CALCULO_REBOBINAMENTO_MODEL     :false ,
                CALCULO_REBOBINAMENTO           :0     ,
                CALCULO_REBOBINAMENTO_DESCRICAO :'NÃO' ,
                CLOGISTICA                          :''    ,
                CLOGISTICA_DESCRICAO                :''     ,
                CLOGISTICA_HIERARQUIA               :1      ,
                CLOGISTICA_MASK                     :''     ,
                FAMILIA_DESCRICAO               :''     ,
                FAMILIA_ID                      :''     ,
                FAMILIA_PRODUCAO                :''     ,
                FAMILIA_PRODUCAO_DESCRICAO      :''     ,
                FATOR                           :1      ,
                GP_DESCRICAO                    :''     ,
                GP_ID                           :''     ,
                PERFIL_UP                       :''     ,
                PERFIL_UP_DESCRICAO             :''     ,
                REMESSAS_DEFEITO                :0      ,
                SEQUENCIA                       :1      ,
                UP_PADRAO1                      :''     ,
                UP_PADRAO1_DESCRICAO            :''     ,
                UP_PADRAO2                      :''     ,
                UP_PADRAO2_DESCRICAO            :''     ,
                STATUS                          :1      ,
                EXCLUIDO                        :false
            };
            
            gScope.Ctrl.ConsultaFamiliaAgrup.apagar(true);
            gScope.Ctrl.ConsultaFamilia.apagar(true);
            gScope.Ctrl.ConsultaGp.apagar(true);
            gScope.Ctrl.ConsultaPerfil.apagar(true);
            gScope.Ctrl.ConsultaUp1.apagar(true);
            gScope.Ctrl.ConsultaUp2.apagar(true);
            gScope.Ctrl.ConsultaCLogistica.apagar(true);
            
            
            obj.DADOS.push(obj.SELECTED);
//            obj.TIPO.CLOGISTICAS.push(obj.SELECTED);
//            mergeGroup();
            
            obj.INCLUINDO = true;
            obj.ALTERANDO = true;
            obj.Modal.show();

//            obj.selectedReset();
        }
        
        
        function inserirItem() {
   
            obj.DOM_ID += 1;
            
            var item = {DOM_ID:obj.DOM_ID}; 
            
            var id = item.DOM_ID;
    
            item.REQUIRED = obj.ITENS.length == 0 ? true : false;
            obj.ITENS.push(item);
    

            item.ConsultaModelo = gScope.Ctrl.Consulta.getNew(true);
            item.ConsultaModelo.componente                  = '.consulta-modelo-'+id;
            item.ConsultaModelo.model                       = 'item.ConsultaModelo';
            item.ConsultaModelo.option.label_descricao      = 'Modelo:';
            item.ConsultaModelo.option.obj_consulta         = '/_31010/Consultar';
            item.ConsultaModelo.option.tamanho_input        = 'input-medio';
            item.ConsultaModelo.option.tamanho_tabela       = 240;
            item.ConsultaModelo.autoload                    = false;
            item.ConsultaModelo.option.required                    = item.REQUIRED;
            item.ConsultaModelo.option.paran                 = {MERCADO: {FAMILIA_ID: 3}}; 

            item.ConsultaCor = gScope.Ctrl.Consulta.getNew(true);
            item.ConsultaCor.componente                      = '.consulta-cor-'+id;
            item.ConsultaCor.model                           = 'item.ConsultaCor';
            item.ConsultaCor.option.label_descricao          = 'Cor:';
            item.ConsultaCor.option.obj_consulta             = '/_31010/ConsultarCor';
            item.ConsultaCor.option.tamanho_input            = 'input-medio';
            item.ConsultaCor.option.tamanho_tabela           = 240;
            item.ConsultaCor.autoload                        = false;
            item.ConsultaCor.option.required                    = item.REQUIRED;
            item.ConsultaCor.option.paran = {'PADRAO': 0};

            item.ConsultaTamanho = gScope.Ctrl.Consulta.getNew(true);
            item.ConsultaTamanho.componente                  = '.consulta-tamanho-'+id;
            item.ConsultaTamanho.model                       = 'item.ConsultaTamanho';
            item.ConsultaTamanho.option.label_descricao      = 'Tamanho:';
            item.ConsultaTamanho.option.obj_consulta         = '/_31010/ConsultarTamanho';
            item.ConsultaTamanho.option.tamanho_input        = 'input-menor';
            item.ConsultaTamanho.option.obj_ret              = ['DESCRICAO'];
            item.ConsultaTamanho.option.tamanho_tabela       = 150;
            item.ConsultaTamanho.autoload                    = false;
            item.ConsultaTamanho.option.required                    = item.REQUIRED;
            item.ConsultaTamanho.option.paran = {'PADRAO': 0};


            item.ConsultaModelo.onSelect = function(){
                
                item.REQUIRED = true;
                item.ConsultaModelo.option.required  = item.REQUIRED;
                item.ConsultaCor.option.required     = item.REQUIRED;
                item.ConsultaTamanho.option.required = item.REQUIRED;

                item.MODELO_ID = item.ConsultaModelo.ID;
                
                obj.inserirItem();

                item.ConsultaCor.option.paran = [];
                item.ConsultaCor.option.paran = {'PADRAO': 1};
                item.ConsultaCor.filtrar();   

                item.ConsultaTamanho.option.paran = {'PADRAO': 1};
                item.ConsultaTamanho.filtrar();
            };
            
            item.ConsultaModelo.onClear = function() {
                
                item.MODELO_ID = undefined;
                
                var idx = obj.ITENS.indexOf(item);
                if ( obj.ITENS.length-1 != idx ) {
                    obj.ITENS.splice(idx,1);
                    
                    if ( obj.ITENS.length == 1 ) {
                        obj.ITENS[0].REQUIRED = true;
                        item.ConsultaModelo.option.required  = item.REQUIRED;
                        item.ConsultaCor.option.required     = item.REQUIRED;
                        item.ConsultaTamanho.option.required = item.REQUIRED;
                    }
                }
                
            };

            item.ConsultaCor.onSelect = function(){

                item.COR_ID = item.ConsultaCor.ID;
                
                item.ConsultaCor.option.paran = {'PADRAO': 0};

            };
            
            item.ConsultaCor.onClear = function(){
                item.COR_ID = undefined;
            };

            item.ConsultaTamanho.onSelect = function(){

                item.TAMANHO = item.ConsultaTamanho.ID;
                
                item.ConsultaTamanho.option.paran = {'PADRAO': 0};
            };

            item.ConsultaTamanho.onClear = function(){
                item.TAMANHO = undefined;
            };

            $timeout(function(){
                item.ConsultaModelo.compile();
                item.ConsultaCor.compile();
                item.ConsultaTamanho.compile();

                item.ConsultaCor.require = item.ConsultaModelo;
                item.ConsultaCor.vincular();

                item.ConsultaTamanho.require = item.ConsultaModelo;
                item.ConsultaTamanho.vincular();

            });
            
            return item;
        }

        function alterar() {
            
//            gScope.Ctrl.ImobilizadoItem.SELECTEDS = [];
            
            
            gScope.Ctrl.ConsultaFamiliaAgrup.Input.value = obj.SELECTED.FAMILIA_PRODUCAO + ' - ' + obj.SELECTED.FAMILIA_PRODUCAO_DESCRICAO;  
            gScope.Ctrl.ConsultaFamiliaAgrup.Input.readonly             = true;
            gScope.Ctrl.ConsultaFamiliaAgrup.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaFamiliaAgrup.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaFamiliaAgrup.btn_filtro.visivel         = false;              
            gScope.Ctrl.ConsultaFamiliaAgrup.item.selected = true;            
            
            
            gScope.Ctrl.ConsultaFamilia.Input.value = obj.SELECTED.FAMILIA_ID + ' - ' + obj.SELECTED.FAMILIA_DESCRICAO;
            gScope.Ctrl.ConsultaFamilia.Input.readonly             = true;
            gScope.Ctrl.ConsultaFamilia.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaFamilia.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaFamilia.btn_filtro.visivel         = false;            
            gScope.Ctrl.ConsultaFamilia.item.selected = true;            
            
            
            if ( obj.SELECTED.GP_ID > 0 ) {
                gScope.Ctrl.ConsultaGp.Input.value = obj.SELECTED.GP_ID + ' - ' + obj.SELECTED.GP_DESCRICAO;
                gScope.Ctrl.ConsultaGp.Input.readonly             = true;
                gScope.Ctrl.ConsultaGp.btn_apagar_filtro.visivel  = true;
                gScope.Ctrl.ConsultaGp.btn_apagar_filtro.disabled = false;
                gScope.Ctrl.ConsultaGp.btn_filtro.visivel         = false;            
                gScope.Ctrl.ConsultaGp.item.selected = true;            
            } else {
                gScope.Ctrl.ConsultaGp.apagar();
                gScope.Ctrl.ConsultaGp.disable(true);
            }
            
            
            if ( obj.SELECTED.PERFIL_UP != null && obj.SELECTED.PERFIL_UP != '' ) {
                gScope.Ctrl.ConsultaPerfil.Input.value = obj.SELECTED.PERFIL_UP + ' - ' + obj.SELECTED.PERFIL_UP_DESCRICAO;
                gScope.Ctrl.ConsultaPerfil.Input.readonly             = true;
                gScope.Ctrl.ConsultaPerfil.btn_apagar_filtro.visivel  = true;
                gScope.Ctrl.ConsultaPerfil.btn_apagar_filtro.disabled = false;
                gScope.Ctrl.ConsultaPerfil.btn_filtro.visivel         = false;            
                gScope.Ctrl.ConsultaPerfil.item.selected = true;            
            } else {
                gScope.Ctrl.ConsultaPerfil.apagar();
                gScope.Ctrl.ConsultaPerfil.disable(true);
            }
            
            
            gScope.Ctrl.ConsultaUp1.Input.value = obj.SELECTED.UP_PADRAO1 + ' - ' + obj.SELECTED.UP_PADRAO1_DESCRICAO;
            gScope.Ctrl.ConsultaUp1.Input.readonly             = true;
            gScope.Ctrl.ConsultaUp1.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaUp1.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaUp1.btn_filtro.visivel         = false;
            gScope.Ctrl.ConsultaUp1.item.selected = true;
            
            
            if ( obj.SELECTED.UP_PADRAO2 > 0 ) {
                gScope.Ctrl.ConsultaUp2.Input.value = obj.SELECTED.UP_PADRAO2 + ' - ' + obj.SELECTED.UP_PADRAO2_DESCRICAO;
                gScope.Ctrl.ConsultaUp2.Input.readonly             = true;
                gScope.Ctrl.ConsultaUp2.btn_apagar_filtro.visivel  = true;
                gScope.Ctrl.ConsultaUp2.btn_apagar_filtro.disabled = false;
                gScope.Ctrl.ConsultaUp2.btn_filtro.visivel         = false;
                gScope.Ctrl.ConsultaUp2.item.selected              = true; 
            } else {
                gScope.Ctrl.ConsultaUp2.apagar();
            }
            
            
            gScope.Ctrl.ConsultaCLogistica.Input.value = obj.SELECTED.CLOGISTICA_MASK + ' - ' + obj.SELECTED.CLOGISTICA_DESCRICAO;
            gScope.Ctrl.ConsultaCLogistica.Input.readonly             = true;
            gScope.Ctrl.ConsultaCLogistica.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaCLogistica.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaCLogistica.btn_filtro.visivel         = false;
            gScope.Ctrl.ConsultaCLogistica.item.selected = true;            
            
            
            this.ALTERANDO = true;
            this.INCLUINDO = false;
            angular.copy(this.SELECTED, this.SELECTED_BACKUP);
            
            obj.Modal.show();
        };        
        
        function cancelar() {
            
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente cancelar esta operação?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){
                        
                             
                        
                        if ( obj.ALTERANDO && !obj.INCLUINDO ) {
                            angular.extend(obj.SELECTED, obj.SELECTED_BACKUP);  
                            
                            obj.Modal.hide();
                        }
                        
                        obj.ALTERANDO = false;
                        
                        if ( obj.INCLUINDO ) {
                            obj.INCLUINDO = false;
                            
                            var idx = obj.DADOS.indexOf(obj.SELECTED);
                            
                            obj.DADOS.splice(idx,1);                            
                                                    
                            
//                            obj.selectedReset();
                            obj.Modal.hide();
                        }
                    });
                }}]     
            );
        }
        
        function excluir() {
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente excluir este item?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){
                        
                        obj.SELECTED.EXCLUIDO = true;
                    });
                }}]     
            );
        }
        
        
        /**
         * Retorna a view da factory
         */
        function renderChart(response) {


            var obj = $('#chart_div');
            var pai = $(obj).closest('#chart-google');

            google.charts.load('current', {packages:["orgchart"]});
            google.charts.setOnLoadCallback(function() {
                
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Name');
                data.addColumn('string', 'Manager');
                data.addColumn('string', 'ToolTip');

                var dados = response;
                var item = null;
                
                var chart_container = [];
                var chart_item = [];
                
                for ( var i in dados ) {
                    item = dados[i];
                    
                    chart_item = [
                        {
                            v:''+item.ID,
                            f:item.CLOGISTICA_DESCRICAO,
                            id: ''+item.ORDEM
                        },
                        ''+( (item.ORDEM - 1) > 0 ? item.ORDEM - 1 : ''),
                        ''
                    ];
                    
                    chart_container.push(chart_item);
                }
                
                // For each orgchart box, provide the name, manager, and tooltip to show.
                data.addRows(chart_container);

                var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
                chart.draw(data, {allowHtml:true});

                setTimeout(function(){
//
//                    $('.img-loading').css('display','none');
                    $(obj).replaceWith( $compile($(obj).html())($rootScope) );
                    $(pai).append('<div style="width: 99%; height: 100%;" id="chart_div"></div>');

                    $rootScope.$apply(function () {
                        $rootScope.message = "Timeout called!";
                    });

                },400);
            });
        }
        
        function changeConformacao() {
            if ( obj.SELECTED.CALCULO_CONFORMACAO_MODEL ) {
                obj.SELECTED.CALCULO_CONFORMACAO           = 1;
                obj.SELECTED.CALCULO_CONFORMACAO_DESCRICAO = 'SIM';
            } else {
                obj.SELECTED.CALCULO_CONFORMACAO           = 0;
                obj.SELECTED.CALCULO_CONFORMACAO_DESCRICAO = 'NÃO';
            }
        }  
        
        function changeRebobinamento(key) {
            if ( obj.SELECTED.CALCULO_REBOBINAMENTO_MODEL ) {
                obj.SELECTED.CALCULO_REBOBINAMENTO           = 1;
                obj.SELECTED.CALCULO_REBOBINAMENTO_DESCRICAO = 'SIM';
            } else {
                obj.SELECTED.CALCULO_REBOBINAMENTO           = 0;
                obj.SELECTED.CALCULO_REBOBINAMENTO_DESCRICAO = 'NÃO';
            }
        }  
        
        var Modal = {
            
            _modal : function(){
                return $('#modal-regra');
            },
            show : function(shown,hidden) {

                this._modal()
                    .modal('show')
                ;                         


                this._modal()
                    .one('shown.bs.modal', function(){

                        $(this).find('input:focusable').first().select();

                        if ( shown ) {
                            shown(); 
                        }
                    })
                ;    

                    this._modal()
                        .one('hidden.bs.modal', function(){
                            
                            if ( hidden ) {
                                hidden();      
                            }
                        })
                    ;        
            },
            hide : function(hidden) {

                this._modal()
                    .modal('hide')
                ;

                if ( hidden ) {
                    this._modal()
                        .one('hidden.bs.modal', function(){
                            hidden ? hidden() : '';
                        })
                    ;                      
                }
            }
        };     

            
	    /**
	     * Return the constructor function
	     */
	    return Simulador;
	};
   
})(window, window.angular);
(function(window, angular) {
    'use strict';

    angular
        .module('app')
        .factory('Cte', Cte);

	Cte.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$compile',
        '$timeout',
        '$consulta',
        'gScope',
        'gcCollection',
        'gcObject'
    ];

	function Cte($ajax, $q, $rootScope, $compile,$timeout, $consulta, gScope, gcCollection, gcObject) {

        // Private variables.
        var obj = null;
        var selected = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function Cte() {
            
            obj = this; 

            // Public methods         
            this.consultar        = consultar;
            this.calcular         = calcular;
            this.merge            = merge;
            
            
            this.incluir        = incluir; 
            this.confirmar      = confirmar; 
            this.alterar        = alterar; 
            this.cancelar       = cancelar; 
            this.excluir        = excluir; 
          
            this.changeRebobinamento = changeRebobinamento;
            this.changeConformacao   = changeConformacao;
            
            this.Modal         = Modal; 
            
            this.ORDER_BY = '-DATA_ENTRADA_JS';
            
            this.FILTRO    = {
                DATA_1 : moment().subtract(1, "month").toDate(),
                DATA_2 : moment().toDate()                
            };
        
            
            this.INCLUINDO = false;
            this.ALTERANDO = false;
            this.DADOS = [];
            this.DADOS_RENDER = [];

            this.DADOS          = [];
            this.DADOS_DETALHES = [];
            
            this.TOTAL_GERAL = 0;
            this.SELECTEDS = [];
            this.SELECTED = [];
            this.SELECTED_BACKUP = [];
            
	    }
        
        
        

        function consultar() {
            
            
            var filtro = {};
            angular.copy(obj.FILTRO,filtro);
            
            if ( gScope.Ctrl.ConsultaFreteTransportadora.ID > 0 ) {
                filtro.FRETE_TRANSPORTADORA_ID = "="+gScope.Ctrl.ConsultaFreteTransportadora.ID;
            } else {
                delete filtro.FRETE_TRANSPORTADORA_ID;
            }
            
            if ( filtro.DOCUMENTO_TODOS == true ) {
                delete filtro.DOCUMENTO;
            } else {
                filtro.DOCUMENTO = "LIKE '%' || UPPER('" + filtro.DOCUMENTO.replace(" ", "%") + "') || '%'";
            }
            
            if ( filtro.DATA_TODOS == undefined || filtro.DATA_TODOS == false ) {
                filtro.DATA_ENTRADA = "BETWEEN '" + moment(filtro.DATA_1).format('DD.MM.YYYY') + "' AND '" + moment(filtro.DATA_2).format('DD.MM.YYYY') + "'";                
            }
            
            delete filtro.DOCUMENTO_TODOS;
            delete filtro.DATA_TODOS;
            delete filtro.DATA_1;
            delete filtro.DATA_2;
            
            return $q(function(resolve, reject){
                $ajax.post('/_14020/api/ctrc',filtro).then(function(response){
                    
                    obj.merge(response);
                    
                    if ( obj.DADOS.length == 0 ) {
                        obj.SELECTED = obj.DADOS[0];
                        $('#tab-cte tbody tr').first().click();
                    }
                    
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }      
        
        

        function calcular(origem,origem_id,transportadora_id,estabelecimento_id) {
            
            gScope.Ctrl.Frete.calcular(origem,origem_id,transportadora_id,estabelecimento_id).then(function(){
                obj.consultar();
            });
        }      
        
        
        

//        function consultarDetalhe() {
//            
//            return $q(function(resolve, reject){
//                $ajax.get('/_14020/api/rateio/tipo/detalhe').then(function(response){
//                    
//                    obj.merge(response);
//                    
//                    resolve(response);
//                },function(e){
//                    reject(e);
//                });
//            });
//        }                
        
        /**
         * Retorna a view da factory
         */
        function merge(response, preserve_main) {
            
            sanitizeJson(response);
            
            gcCollection.merge(obj.DADOS, response,'ID');
            
            var item = null;
            for ( var i in obj.DADOS ) {
                item = obj.DADOS[i];
                
                item.DATA_ENTRADA_JS = moment(item.DATA_ENTRADA).toDate();
                item.DATA_EMISSAO_JS = moment(item.DATA_EMISSAO).toDate();
                
            }
            
        }
        
        
        function incluir() {
            
            
            obj.SELECTED = {
                CALCULO_CONFORMACAO_MODEL       :false ,
                CALCULO_CONFORMACAO             :0     ,
                CALCULO_CONFORMACAO_DESCRICAO   :'NÃO' ,
                CALCULO_REBOBINAMENTO_MODEL     :false ,
                CALCULO_REBOBINAMENTO           :0     ,
                CALCULO_REBOBINAMENTO_DESCRICAO :'NÃO' ,
                CLOGISTICA                          :''    ,
                CLOGISTICA_DESCRICAO                :''     ,
                CLOGISTICA_HIERARQUIA               :1      ,
                CLOGISTICA_MASK                     :''     ,
                FAMILIA_DESCRICAO               :''     ,
                FAMILIA_ID                      :''     ,
                FAMILIA_PRODUCAO                :''     ,
                FAMILIA_PRODUCAO_DESCRICAO      :''     ,
                FATOR                           :1      ,
                GP_DESCRICAO                    :''     ,
                GP_ID                           :''     ,
                PERFIL_UP                       :''     ,
                PERFIL_UP_DESCRICAO             :''     ,
                REMESSAS_DEFEITO                :0      ,
                SEQUENCIA                       :1      ,
                UP_PADRAO1                      :''     ,
                UP_PADRAO1_DESCRICAO            :''     ,
                UP_PADRAO2                      :''     ,
                UP_PADRAO2_DESCRICAO            :''     ,
                STATUS                          :1      ,
                EXCLUIDO                        :false
            };
            
            gScope.Ctrl.ConsultaFamiliaAgrup.apagar(true);
            gScope.Ctrl.ConsultaFamilia.apagar(true);
            gScope.Ctrl.ConsultaGp.apagar(true);
            gScope.Ctrl.ConsultaPerfil.apagar(true);
            gScope.Ctrl.ConsultaUp1.apagar(true);
            gScope.Ctrl.ConsultaUp2.apagar(true);
            gScope.Ctrl.ConsultaCLogistica.apagar(true);
            
            
            obj.DADOS.push(obj.SELECTED);
//            obj.TIPO.CLOGISTICAS.push(obj.SELECTED);
//            mergeGroup();
            
            obj.INCLUINDO = true;
            obj.ALTERANDO = true;
            obj.Modal.show();

//            obj.selectedReset();
        }
        
        function confirmar() {
       
            
            var dados = {
                FILTRO: {},
                DADOS : obj.DADOS
            };
//    
            $ajax.post('/_14020/api/regra/post',dados).then(function(response){

                obj.merge(response.DATA_RETURN);
                
            });
        }

        function alterar() {
            
//            gScope.Ctrl.ImobilizadoItem.SELECTEDS = [];
            
            
            gScope.Ctrl.ConsultaFamiliaAgrup.Input.value = obj.SELECTED.FAMILIA_PRODUCAO + ' - ' + obj.SELECTED.FAMILIA_PRODUCAO_DESCRICAO;  
            gScope.Ctrl.ConsultaFamiliaAgrup.Input.readonly             = true;
            gScope.Ctrl.ConsultaFamiliaAgrup.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaFamiliaAgrup.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaFamiliaAgrup.btn_filtro.visivel         = false;              
            gScope.Ctrl.ConsultaFamiliaAgrup.item.selected = true;            
            
            
            gScope.Ctrl.ConsultaFamilia.Input.value = obj.SELECTED.FAMILIA_ID + ' - ' + obj.SELECTED.FAMILIA_DESCRICAO;
            gScope.Ctrl.ConsultaFamilia.Input.readonly             = true;
            gScope.Ctrl.ConsultaFamilia.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaFamilia.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaFamilia.btn_filtro.visivel         = false;            
            gScope.Ctrl.ConsultaFamilia.item.selected = true;            
            
            
            if ( obj.SELECTED.GP_ID > 0 ) {
                gScope.Ctrl.ConsultaGp.Input.value = obj.SELECTED.GP_ID + ' - ' + obj.SELECTED.GP_DESCRICAO;
                gScope.Ctrl.ConsultaGp.Input.readonly             = true;
                gScope.Ctrl.ConsultaGp.btn_apagar_filtro.visivel  = true;
                gScope.Ctrl.ConsultaGp.btn_apagar_filtro.disabled = false;
                gScope.Ctrl.ConsultaGp.btn_filtro.visivel         = false;            
                gScope.Ctrl.ConsultaGp.item.selected = true;            
            } else {
                gScope.Ctrl.ConsultaGp.apagar();
                gScope.Ctrl.ConsultaGp.disable(true);
            }
            
            
            if ( obj.SELECTED.PERFIL_UP != null && obj.SELECTED.PERFIL_UP != '' ) {
                gScope.Ctrl.ConsultaPerfil.Input.value = obj.SELECTED.PERFIL_UP + ' - ' + obj.SELECTED.PERFIL_UP_DESCRICAO;
                gScope.Ctrl.ConsultaPerfil.Input.readonly             = true;
                gScope.Ctrl.ConsultaPerfil.btn_apagar_filtro.visivel  = true;
                gScope.Ctrl.ConsultaPerfil.btn_apagar_filtro.disabled = false;
                gScope.Ctrl.ConsultaPerfil.btn_filtro.visivel         = false;            
                gScope.Ctrl.ConsultaPerfil.item.selected = true;            
            } else {
                gScope.Ctrl.ConsultaPerfil.apagar();
                gScope.Ctrl.ConsultaPerfil.disable(true);
            }
            
            
            gScope.Ctrl.ConsultaUp1.Input.value = obj.SELECTED.UP_PADRAO1 + ' - ' + obj.SELECTED.UP_PADRAO1_DESCRICAO;
            gScope.Ctrl.ConsultaUp1.Input.readonly             = true;
            gScope.Ctrl.ConsultaUp1.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaUp1.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaUp1.btn_filtro.visivel         = false;
            gScope.Ctrl.ConsultaUp1.item.selected = true;
            
            
            if ( obj.SELECTED.UP_PADRAO2 > 0 ) {
                gScope.Ctrl.ConsultaUp2.Input.value = obj.SELECTED.UP_PADRAO2 + ' - ' + obj.SELECTED.UP_PADRAO2_DESCRICAO;
                gScope.Ctrl.ConsultaUp2.Input.readonly             = true;
                gScope.Ctrl.ConsultaUp2.btn_apagar_filtro.visivel  = true;
                gScope.Ctrl.ConsultaUp2.btn_apagar_filtro.disabled = false;
                gScope.Ctrl.ConsultaUp2.btn_filtro.visivel         = false;
                gScope.Ctrl.ConsultaUp2.item.selected              = true; 
            } else {
                gScope.Ctrl.ConsultaUp2.apagar();
            }
            
            
            gScope.Ctrl.ConsultaCLogistica.Input.value = obj.SELECTED.CLOGISTICA_MASK + ' - ' + obj.SELECTED.CLOGISTICA_DESCRICAO;
            gScope.Ctrl.ConsultaCLogistica.Input.readonly             = true;
            gScope.Ctrl.ConsultaCLogistica.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaCLogistica.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaCLogistica.btn_filtro.visivel         = false;
            gScope.Ctrl.ConsultaCLogistica.item.selected = true;            
            
            
            this.ALTERANDO = true;
            this.INCLUINDO = false;
            angular.copy(this.SELECTED, this.SELECTED_BACKUP);
            
            obj.Modal.show();
        };        
        
        function cancelar() {
            
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente cancelar esta operação?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){
                        
                             
                        
                        if ( obj.ALTERANDO && !obj.INCLUINDO ) {
                            angular.extend(obj.SELECTED, obj.SELECTED_BACKUP);  
                            
                            obj.Modal.hide();
                        }
                        
                        obj.ALTERANDO = false;
                        
                        if ( obj.INCLUINDO ) {
                            obj.INCLUINDO = false;
                            
                            var idx = obj.DADOS.indexOf(obj.SELECTED);
                            
                            obj.DADOS.splice(idx,1);                            
                                                    
                            
//                            obj.selectedReset();
                            obj.Modal.hide();
                        }
                    });
                }}]     
            );
        }
        
        function excluir() {
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente excluir este item?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){
                        
                        obj.SELECTED.EXCLUIDO = true;
                    });
                }}]     
            );
        }
        
        
        /**
         * Retorna a view da factory
         */
        function renderChart(response) {


            var obj = $('#chart_div');
            var pai = $(obj).closest('#chart-google');

            google.charts.load('current', {packages:["orgchart"]});
            google.charts.setOnLoadCallback(function() {
                
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Name');
                data.addColumn('string', 'Manager');
                data.addColumn('string', 'ToolTip');

                var dados = response;
                var item = null;
                
                var chart_container = [];
                var chart_item = [];
                
                for ( var i in dados ) {
                    item = dados[i];
                    
                    chart_item = [
                        {
                            v:''+item.ID,
                            f:item.CLOGISTICA_DESCRICAO,
                            id: ''+item.ORDEM
                        },
                        ''+( (item.ORDEM - 1) > 0 ? item.ORDEM - 1 : ''),
                        ''
                    ];
                    
                    chart_container.push(chart_item);
                }
                
                // For each orgchart box, provide the name, manager, and tooltip to show.
                data.addRows(chart_container);

                var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
                chart.draw(data, {allowHtml:true});

                setTimeout(function(){
//
//                    $('.img-loading').css('display','none');
                    $(obj).replaceWith( $compile($(obj).html())($rootScope) );
                    $(pai).append('<div style="width: 99%; height: 100%;" id="chart_div"></div>');

                    $rootScope.$apply(function () {
                        $rootScope.message = "Timeout called!";
                    });

                },400);
            });
        }
        
        function changeConformacao() {
            if ( obj.SELECTED.CALCULO_CONFORMACAO_MODEL ) {
                obj.SELECTED.CALCULO_CONFORMACAO           = 1;
                obj.SELECTED.CALCULO_CONFORMACAO_DESCRICAO = 'SIM';
            } else {
                obj.SELECTED.CALCULO_CONFORMACAO           = 0;
                obj.SELECTED.CALCULO_CONFORMACAO_DESCRICAO = 'NÃO';
            }
        }  
        
        function changeRebobinamento(key) {
            if ( obj.SELECTED.CALCULO_REBOBINAMENTO_MODEL ) {
                obj.SELECTED.CALCULO_REBOBINAMENTO           = 1;
                obj.SELECTED.CALCULO_REBOBINAMENTO_DESCRICAO = 'SIM';
            } else {
                obj.SELECTED.CALCULO_REBOBINAMENTO           = 0;
                obj.SELECTED.CALCULO_REBOBINAMENTO_DESCRICAO = 'NÃO';
            }
        }  
        
        var Modal = {
            
            _modal : function(){
                return $('#modal-regra');
            },
            show : function(shown,hidden) {

                this._modal()
                    .modal('show')
                ;                         


                this._modal()
                    .one('shown.bs.modal', function(){

                        $(this).find('input:focusable').first().select();

                        if ( shown ) {
                            shown(); 
                        }
                    })
                ;    

                    this._modal()
                        .one('hidden.bs.modal', function(){
                            
                            if ( hidden ) {
                                hidden();      
                            }
                        })
                    ;        
            },
            hide : function(hidden) {

                this._modal()
                    .modal('hide')
                ;

                if ( hidden ) {
                    this._modal()
                        .one('hidden.bs.modal', function(){
                            hidden ? hidden() : '';
                        })
                    ;                      
                }
            }
        };     

            
	    /**
	     * Return the constructor function
	     */
	    return Cte;
	};
   
})(window, window.angular);
(function(window, angular) {
    'use strict';

    angular
        .module('app')
        .factory('Frete', Frete);

	Frete.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$compile',
        '$timeout',
        '$consulta',
        'gScope',
        'gcCollection',
        'gcObject'
    ];

	function Frete($ajax, $q, $rootScope, $compile,$timeout, $consulta, gScope, gcCollection, gcObject) {

        // Private variables.
        var obj = null;
        var selected = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function Frete() {
            
            obj = this; 

            // Public methods         
            this.consultar = consultar;
            this.calcular        = calcular;
            this.merge            = merge;
            
            this.keydown          = keydown;
       
            
            this.incluir        = incluir; 
            this.confirmar      = confirmar; 
            this.alterar        = alterar; 
            this.cancelar       = cancelar; 
            this.excluir        = excluir; 
          
            this.changeRebobinamento = changeRebobinamento;
            this.changeConformacao   = changeConformacao;
            
            this.Modal         = Modal; 
            
            this.ORDER_BY = '-DATA_ENTRADA_JS';
            
            this.FILTRO    = {
                DATA_1 : moment().subtract(1, "year").toDate(),
                DATA_2 : moment().toDate()                
            };
        
            
            this.INCLUINDO = false;
            this.ALTERANDO = false;
            this.DADOS = [];
            this.DADOS_RENDER = [];

            this.DADOS          = {};
            this.DADOS_DETALHES = [];
            
            this.TOTAL_GERAL = 0;
            this.SELECTEDS = [];
            this.SELECTED = [];
            this.SELECTED_BACKUP = [];
            
	    }
        
        
        

        function consultar(frete_id) {
        
            return $q(function(resolve, reject){
                $ajax.get('/_14020/api/frete/'+frete_id).then(function(response){
                    
                    obj.merge(response);
                    
                    obj.Modal.show();
                    
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }                

        function calcular(origem,origem_id,transportadora_id,estabelecimento_id,retorno,rollback,itens) {
            
            var filtro = {
                ORIGEM : origem,
                ORIGEM_ID : origem_id,
                TRANSPORTADORA_ID : transportadora_id,
                ESTABELECIMENTO_ID : estabelecimento_id,
                FRETE_ID : obj.SELECTED.FRETE_ID,
                ITENS : itens
            };
            
            if ( retorno != undefined ) {
                filtro.RETURN = true;
            }
            
            if ( rollback != undefined ) {
                filtro.ROLLBACK = true;
            }
            
            return $q(function(resolve, reject){
                $ajax.post('/_14020/api/frete/calcular',filtro).then(function(response){
                    
                    if ( retorno != undefined ) {
                        obj.merge(response);
                    }
                    
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }                
        

//        function consultarDetalhe() {
//            
//            return $q(function(resolve, reject){
//                $ajax.get('/_14020/api/rateio/tipo/detalhe').then(function(response){
//                    
//                    obj.merge(response);
//                    
//                    resolve(response);
//                },function(e){
//                    reject(e);
//                });
//            });
//        }                
        
        /**
         * Retorna a view da factory
         */
        function merge(response, preserve_main) {

            sanitizeJson(response);

            obj.DADOS = response;

            obj.ORIGEM    = obj.DADOS.ORIGEM;
            obj.ORIGEM_ID = obj.DADOS.ORIGEM_ID;

            obj.DADOS.COMPOSICOES = [
                {
                    DESCRICAO : 'Dados da Carga',
                    DADOS : response.DADOS_CARGA
                },
                {
                    DESCRICAO : 'Composição dos Valores',
                    DADOS : response.DADOS_COMPOSICAO
                }                            
            ];

            gScope.Ctrl.ConsultaFreteTransportadoraSimulador.Input.value = obj.DADOS.TRANSPORTADORA_ID + ' - ' + obj.DADOS.TRANSPORTADORA_RAZAOSOCIAL;
            gScope.Ctrl.ConsultaFreteTransportadoraSimulador.Input.readonly             = true;
            gScope.Ctrl.ConsultaFreteTransportadoraSimulador.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaFreteTransportadoraSimulador.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaFreteTransportadoraSimulador.btn_filtro.visivel         = false;            
            gScope.Ctrl.ConsultaFreteTransportadoraSimulador.item.selected              = true;  
            
        }

        function keydown(item,$event) {
           var that = this;
               /* Verifica se existe um evento */
               if ( !($event === undefined) ) {

                   if ( $event.key == 'Enter' ) {
                       
                       if ( item.FRETE_ID > 0 ) {
                           obj.consultar(item.FRETE_ID);
                       }
                   }
                   if ( $event.key == 'Escape' ) {
//                       that.cancelarQuantidade(item);
                   }
               }            
        }
        
        function incluir() {
            
            
            obj.SELECTED = {
                CALCULO_CONFORMACAO_MODEL       :false ,
                CALCULO_CONFORMACAO             :0     ,
                CALCULO_CONFORMACAO_DESCRICAO   :'NÃO' ,
                CALCULO_REBOBINAMENTO_MODEL     :false ,
                CALCULO_REBOBINAMENTO           :0     ,
                CALCULO_REBOBINAMENTO_DESCRICAO :'NÃO' ,
                CLOGISTICA                          :''    ,
                CLOGISTICA_DESCRICAO                :''     ,
                CLOGISTICA_HIERARQUIA               :1      ,
                CLOGISTICA_MASK                     :''     ,
                FAMILIA_DESCRICAO               :''     ,
                FAMILIA_ID                      :''     ,
                FAMILIA_PRODUCAO                :''     ,
                FAMILIA_PRODUCAO_DESCRICAO      :''     ,
                FATOR                           :1      ,
                GP_DESCRICAO                    :''     ,
                GP_ID                           :''     ,
                PERFIL_UP                       :''     ,
                PERFIL_UP_DESCRICAO             :''     ,
                REMESSAS_DEFEITO                :0      ,
                SEQUENCIA                       :1      ,
                UP_PADRAO1                      :''     ,
                UP_PADRAO1_DESCRICAO            :''     ,
                UP_PADRAO2                      :''     ,
                UP_PADRAO2_DESCRICAO            :''     ,
                STATUS                          :1      ,
                EXCLUIDO                        :false
            };
            
            gScope.Ctrl.ConsultaFamiliaAgrup.apagar(true);
            gScope.Ctrl.ConsultaFamilia.apagar(true);
            gScope.Ctrl.ConsultaGp.apagar(true);
            gScope.Ctrl.ConsultaPerfil.apagar(true);
            gScope.Ctrl.ConsultaUp1.apagar(true);
            gScope.Ctrl.ConsultaUp2.apagar(true);
            gScope.Ctrl.ConsultaCLogistica.apagar(true);
            
            
            obj.DADOS.push(obj.SELECTED);
//            obj.TIPO.CLOGISTICAS.push(obj.SELECTED);
//            mergeGroup();
            
            obj.INCLUINDO = true;
            obj.ALTERANDO = true;
            obj.Modal.show();

//            obj.selectedReset();
        }
        
        function confirmar() {
       
            
            var dados = {
                FILTRO: {},
                DADOS : obj.DADOS
            };
//    
            $ajax.post('/_14020/api/frete/post',dados).then(function(response){

                obj.merge(response.DATA_RETURN);
                
            });
        }

        function alterar() {
            
//            gScope.Ctrl.ImobilizadoItem.SELECTEDS = [];
            
            
            gScope.Ctrl.ConsultaFamiliaAgrup.Input.value = obj.SELECTED.FAMILIA_PRODUCAO + ' - ' + obj.SELECTED.FAMILIA_PRODUCAO_DESCRICAO;  
            gScope.Ctrl.ConsultaFamiliaAgrup.Input.readonly             = true;
            gScope.Ctrl.ConsultaFamiliaAgrup.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaFamiliaAgrup.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaFamiliaAgrup.btn_filtro.visivel         = false;              
            gScope.Ctrl.ConsultaFamiliaAgrup.item.selected = true;            
            
            
            gScope.Ctrl.ConsultaFamilia.Input.value = obj.SELECTED.FAMILIA_ID + ' - ' + obj.SELECTED.FAMILIA_DESCRICAO;
            gScope.Ctrl.ConsultaFamilia.Input.readonly             = true;
            gScope.Ctrl.ConsultaFamilia.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaFamilia.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaFamilia.btn_filtro.visivel         = false;            
            gScope.Ctrl.ConsultaFamilia.item.selected = true;            
            
            
            if ( obj.SELECTED.GP_ID > 0 ) {
                gScope.Ctrl.ConsultaGp.Input.value = obj.SELECTED.GP_ID + ' - ' + obj.SELECTED.GP_DESCRICAO;
                gScope.Ctrl.ConsultaGp.Input.readonly             = true;
                gScope.Ctrl.ConsultaGp.btn_apagar_filtro.visivel  = true;
                gScope.Ctrl.ConsultaGp.btn_apagar_filtro.disabled = false;
                gScope.Ctrl.ConsultaGp.btn_filtro.visivel         = false;            
                gScope.Ctrl.ConsultaGp.item.selected = true;            
            } else {
                gScope.Ctrl.ConsultaGp.apagar();
                gScope.Ctrl.ConsultaGp.disable(true);
            }
            
            
            if ( obj.SELECTED.PERFIL_UP != null && obj.SELECTED.PERFIL_UP != '' ) {
                gScope.Ctrl.ConsultaPerfil.Input.value = obj.SELECTED.PERFIL_UP + ' - ' + obj.SELECTED.PERFIL_UP_DESCRICAO;
                gScope.Ctrl.ConsultaPerfil.Input.readonly             = true;
                gScope.Ctrl.ConsultaPerfil.btn_apagar_filtro.visivel  = true;
                gScope.Ctrl.ConsultaPerfil.btn_apagar_filtro.disabled = false;
                gScope.Ctrl.ConsultaPerfil.btn_filtro.visivel         = false;            
                gScope.Ctrl.ConsultaPerfil.item.selected = true;            
            } else {
                gScope.Ctrl.ConsultaPerfil.apagar();
                gScope.Ctrl.ConsultaPerfil.disable(true);
            }
            
            
            gScope.Ctrl.ConsultaUp1.Input.value = obj.SELECTED.UP_PADRAO1 + ' - ' + obj.SELECTED.UP_PADRAO1_DESCRICAO;
            gScope.Ctrl.ConsultaUp1.Input.readonly             = true;
            gScope.Ctrl.ConsultaUp1.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaUp1.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaUp1.btn_filtro.visivel         = false;
            gScope.Ctrl.ConsultaUp1.item.selected = true;
            
            
            if ( obj.SELECTED.UP_PADRAO2 > 0 ) {
                gScope.Ctrl.ConsultaUp2.Input.value = obj.SELECTED.UP_PADRAO2 + ' - ' + obj.SELECTED.UP_PADRAO2_DESCRICAO;
                gScope.Ctrl.ConsultaUp2.Input.readonly             = true;
                gScope.Ctrl.ConsultaUp2.btn_apagar_filtro.visivel  = true;
                gScope.Ctrl.ConsultaUp2.btn_apagar_filtro.disabled = false;
                gScope.Ctrl.ConsultaUp2.btn_filtro.visivel         = false;
                gScope.Ctrl.ConsultaUp2.item.selected              = true; 
            } else {
                gScope.Ctrl.ConsultaUp2.apagar();
            }
            
            
            gScope.Ctrl.ConsultaCLogistica.Input.value = obj.SELECTED.CLOGISTICA_MASK + ' - ' + obj.SELECTED.CLOGISTICA_DESCRICAO;
            gScope.Ctrl.ConsultaCLogistica.Input.readonly             = true;
            gScope.Ctrl.ConsultaCLogistica.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaCLogistica.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaCLogistica.btn_filtro.visivel         = false;
            gScope.Ctrl.ConsultaCLogistica.item.selected = true;            
            
            
            this.ALTERANDO = true;
            this.INCLUINDO = false;
            angular.copy(this.SELECTED, this.SELECTED_BACKUP);
            
            obj.Modal.show();
        };        
        
        function cancelar() {
            
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente cancelar esta operação?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){
                        
                             
                        
                        if ( obj.ALTERANDO && !obj.INCLUINDO ) {
                            angular.extend(obj.SELECTED, obj.SELECTED_BACKUP);  
                            
                            obj.Modal.hide();
                        }
                        
                        obj.ALTERANDO = false;
                        
                        if ( obj.INCLUINDO ) {
                            obj.INCLUINDO = false;
                            
                            var idx = obj.DADOS.indexOf(obj.SELECTED);
                            
                            obj.DADOS.splice(idx,1);                            
                                                    
                            
//                            obj.selectedReset();
                            obj.Modal.hide();
                        }
                    });
                }}]     
            );
        }
        
        function excluir() {
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente excluir este item?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){
                        
                        obj.SELECTED.EXCLUIDO = true;
                    });
                }}]     
            );
        }
        
        
        /**
         * Retorna a view da factory
         */
        function renderChart(response) {


            var obj = $('#chart_div');
            var pai = $(obj).closest('#chart-google');

            google.charts.load('current', {packages:["orgchart"]});
            google.charts.setOnLoadCallback(function() {
                
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Name');
                data.addColumn('string', 'Manager');
                data.addColumn('string', 'ToolTip');

                var dados = response;
                var item = null;
                
                var chart_container = [];
                var chart_item = [];
                
                for ( var i in dados ) {
                    item = dados[i];
                    
                    chart_item = [
                        {
                            v:''+item.ID,
                            f:item.CLOGISTICA_DESCRICAO,
                            id: ''+item.ORDEM
                        },
                        ''+( (item.ORDEM - 1) > 0 ? item.ORDEM - 1 : ''),
                        ''
                    ];
                    
                    chart_container.push(chart_item);
                }
                
                // For each orgchart box, provide the name, manager, and tooltip to show.
                data.addRows(chart_container);

                var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
                chart.draw(data, {allowHtml:true});

                setTimeout(function(){
//
//                    $('.img-loading').css('display','none');
                    $(obj).replaceWith( $compile($(obj).html())($rootScope) );
                    $(pai).append('<div style="width: 99%; height: 100%;" id="chart_div"></div>');

                    $rootScope.$apply(function () {
                        $rootScope.message = "Timeout called!";
                    });

                },400);
            });
        }
        
        function changeConformacao() {
            if ( obj.SELECTED.CALCULO_CONFORMACAO_MODEL ) {
                obj.SELECTED.CALCULO_CONFORMACAO           = 1;
                obj.SELECTED.CALCULO_CONFORMACAO_DESCRICAO = 'SIM';
            } else {
                obj.SELECTED.CALCULO_CONFORMACAO           = 0;
                obj.SELECTED.CALCULO_CONFORMACAO_DESCRICAO = 'NÃO';
            }
        }  
        
        function changeRebobinamento(key) {
            if ( obj.SELECTED.CALCULO_REBOBINAMENTO_MODEL ) {
                obj.SELECTED.CALCULO_REBOBINAMENTO           = 1;
                obj.SELECTED.CALCULO_REBOBINAMENTO_DESCRICAO = 'SIM';
            } else {
                obj.SELECTED.CALCULO_REBOBINAMENTO           = 0;
                obj.SELECTED.CALCULO_REBOBINAMENTO_DESCRICAO = 'NÃO';
            }
        }  
        
        var Modal = {
            
            _modal : function(){
                return $('#modal-frete');
            },
            show : function(shown,hidden) {

                this._modal()
                    .modal('show')
                ;                         


                this._modal()
                    .one('shown.bs.modal', function(){

//                        $(this).find('input:focusable').first().select();

                        if ( shown ) {
                            shown(); 
                        }
                    })
                ;    

                    this._modal()
                        .one('hidden.bs.modal', function(){
                            
                            if ( hidden ) {
                                hidden();      
                            }
                        })
                    ;        
            },
            hide : function(hidden) {

                this._modal()
                    .modal('hide')
                ;

                if ( hidden ) {
                    this._modal()
                        .one('hidden.bs.modal', function(){
                            gScope.Ctrl.ConsultaFreteTransportadoraSimulador.disable(true);
                            hidden ? hidden() : '';
                        })
                    ;                      
                }
            }
        };     

            
	    /**
	     * Return the constructor function
	     */
	    return Frete;
	};
   
})(window, window.angular);
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
  
//# sourceMappingURL=_14020.js.map
