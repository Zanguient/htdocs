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