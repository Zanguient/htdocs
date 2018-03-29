(function(window, angular) {
    'use strict';

    angular
        .module('app')
        .factory('RateioTipo', RateioTipo);

	RateioTipo.$inject = [
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

	function RateioTipo($ajax, $q, $rootScope, $compile,$timeout, $consulta, gScope, gcCollection, gcObject) {

        // Private variables.
        var obj = null;
        var selected = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function RateioTipo() {
            
            obj = this; 

            // Public methods         
            this.consultar        = consultar;
//            this.consultarDetalhe = consultarDetalhe;
            this.merge            = merge;
            
            
            this.incluir        = incluir; 
            this.confirmar      = confirmar; 
            this.alterar        = alterar; 
            this.cancelar       = cancelar; 
            this.excluir        = excluir; 
          
            this.changeDataInicial  = changeDataInicial;
            this.changeDataFinal    = changeDataFinal;
            this.changeDataCorrente = changeDataCorrente;
            
            this.Modal         = Modal; 
            
            this.ORDER_BY  = 'ID*1';
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
            
            return $q(function(resolve, reject){
                $ajax.get('/_31050/api/rateio/tipo').then(function(response){
                    
                    obj.merge(response);
                    
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }                

//        function consultarDetalhe() {
//            
//            return $q(function(resolve, reject){
//                $ajax.get('/_31050/api/rateio/tipo/detalhe').then(function(response){
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
        function merge(response) {
            
            sanitizeJson(response);
            
            
            gcCollection.merge(obj.DADOS, response, ['ID']);
            
            var item = null;
            for ( var i in obj.DADOS ) {
                item = obj.DADOS[i];
                
                item.DATA_INICIAL_MODEL = item.DATA_INICIAL == null ? undefined : moment(item.DATA_INICIAL).toDate();
                item.DATA_FINAL_MODEL   = item.DATA_FINAL == null   ? undefined : moment(item.DATA_FINAL  ).toDate();
                
                item.DATA_INICIAL = item.DATA_INICIAL != null ? moment(item.DATA_INICIAL).format('DD.MM.YYYY') : null;
                item.DATA_FINAL   = item.DATA_FINAL   != null ? moment(item.DATA_FINAL  ).format('DD.MM.YYYY') : null;
                
                item.DATA_CORRENTE =  item.DATA_FINAL == null ? true : false;
                
                item.EXCLUIDO = false;
            }
            
        }
        
        
        function incluir() {
            
            obj.SELECTED = {
                DESCRICAO        : '',
                DATA_INICIAL     : '',
                DATA_FINAL       : '',
                UNIDADEMEDIDA_ID : null,
                EXCLUIDO         : false
            };
            
//            obj.tipoChange(obj.TIPO.TIPO_ID);
            
//            gScope.Ctrl.ConsultaUnidadeMedida.apagar(true);
            
            obj.DADOS.push(obj.SELECTED);
//            obj.TIPO.CCUSTOS.push(obj.SELECTED);
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
            $ajax.post('/_31050/api/rateio/tipo/post',dados).then(function(response){

                obj.merge(response.DATA_RETURN);
                
            });
        }

        function alterar() {
            
//            gScope.Ctrl.ImobilizadoItem.SELECTEDS = [];
            
            gScope.Ctrl.ConsultaUnidadeMedida.setDataRequest({});
            
            gScope.Ctrl.ConsultaUnidadeMedida.Input.value = obj.SELECTED.UM + ' - ' + obj.SELECTED.UM_DESCRICAO;
//            
            gScope.Ctrl.ConsultaUnidadeMedida.Input.readonly             = true;
            gScope.Ctrl.ConsultaUnidadeMedida.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaUnidadeMedida.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaUnidadeMedida.btn_filtro.visivel         = false;            
//            
            gScope.Ctrl.ConsultaUnidadeMedida.item.selected = true;            
            
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
                            f:item.CCUSTO_DESCRICAO,
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
        
        function changeDataInicial() {
            
            obj.SELECTED.DATA_INICIAL = moment(obj.SELECTED.DATA_INICIAL_MODEL).format('DD.MM.YYYY');
        }  
        
        function changeDataFinal() {
            
            obj.SELECTED.DATA_FINAL = moment(obj.SELECTED.DATA_FINAL_MODEL).format('DD.MM.YYYY');
        }  
        
        function changeDataCorrente() {
            
            if ( obj.SELECTED.DATA_CORRENTE == true ) {
                obj.SELECTED.DATA_FINAL       = null;
                obj.SELECTED.DATA_FINAL_MODEL = undefined;
            }
        }  
        
        var Modal = {
            
            _modal : function(){
                return $('#modal-rateio-tipo');
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
	    return RateioTipo;
	};
   
})(window, window.angular);