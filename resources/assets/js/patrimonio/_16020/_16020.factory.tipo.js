(function(window, angular) {
    'use strict';

    angular
        .module('app')
        .factory('Tipo', Tipo);

	Tipo.$inject = [
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

	function Tipo($ajax, $q, $rootScope, $compile,$timeout, $consulta, gScope, gcCollection, gcObject) {

        // Private variables.
        var obj = null;
        var selected = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function Tipo() {
            
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
            this.SELECTED = {};
            this.SELECTED_BACKUP = {};
            
	    }

        function consultar() {
            
            return $q(function(resolve, reject){
                $ajax.get('/_16020/api/tipos').then(function(response){
                    
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
//                $ajax.get('/_16020/api/tipo/detalhe').then(function(response){
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
                
                item.EXCLUIDO = false;
                item.TIPO_GASTO_SELECT = selectById(gScope.Ctrl.TIPOS_GASTO,item.TIPO_GASTO);
            }
            
        }
        

        
        function incluir() {
            
            obj.SELECTED = {
                DESCRICAO         : '',
                TIPO_GASTO        : 0,
                TIPO_GASTO_SELECT : selectById(gScope.Ctrl.TIPOS_GASTO,0),
                EXCLUIDO          : false
            };
                        
            gScope.Ctrl.ConsultaCContabil.apagar(true);
            gScope.Ctrl.ConsultaCContabilDebito.apagar(true);
            
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
            $ajax.post('/_16020/api/tipo/post',dados).then(function(response){

                obj.merge(response.DATA_RETURN);
                
            });
        }

        function alterar() {
            
//            gScope.Ctrl.ImobilizadoItem.SELECTEDS = [];

            gScope.Ctrl.ConsultaCContabil.Input.value = obj.SELECTED.CCONTABIL_MASK + ' - ' + obj.SELECTED.CCONTABIL_DESCRICAO;
            gScope.Ctrl.ConsultaCContabil.Input.readonly             = true;
            gScope.Ctrl.ConsultaCContabil.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaCContabil.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaCContabil.btn_filtro.visivel         = false;            
            gScope.Ctrl.ConsultaCContabil.item.selected = true;            

            gScope.Ctrl.ConsultaCContabilDebito.Input.value = obj.SELECTED.CCONTABIL_DEBITO_MASK + ' - ' + obj.SELECTED.CCONTABIL_DEBITO_DESCRICAO;
            gScope.Ctrl.ConsultaCContabilDebito.Input.readonly             = true;
            gScope.Ctrl.ConsultaCContabilDebito.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaCContabilDebito.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaCContabilDebito.btn_filtro.visivel         = false;            
            gScope.Ctrl.ConsultaCContabilDebito.item.selected = true;            
            
            this.ALTERANDO = true;
            this.INCLUINDO = false;
            angular.copy(this.SELECTED, this.SELECTED_BACKUP);
            
            obj.Modal.show();
        };        
        
        function cancelar() {
            
            var selected = angular.toJson(obj.SELECTED);
            var backup   = angular.toJson(obj.SELECTED_BACKUP );
            
            function close() {
                if ( obj.ALTERANDO && !obj.INCLUINDO ) {
                    angular.extend(obj.SELECTED, obj.SELECTED_BACKUP);  

                    obj.Modal.hide();
                }

                if ( obj.INCLUINDO ) {

                    var idx = obj.DADOS.indexOf(obj.SELECTED);

                    obj.DADOS.splice(idx,1);                            


//                            obj.selectedReset();
                    obj.Modal.hide();
                }
            }
            
            if ( selected != backup ) {
                addConfirme('<h4>Confirmação</h4>',
                    'Deseja realmente cancelar esta operação?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){
                        $rootScope.$apply(function(){

                            close();
                        });
                    }}]     
                );
            } else {
                close();
            }
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
                return $('#modal-tipo');
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
                            
                            obj.ALTERANDO = false;
                            obj.INCLUINDO = false;
                            $('.selected:focusable').first().focus();
                    
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
                            
                            obj.ALTERANDO = false;
                            obj.INCLUINDO = false;
                            $('.selected:focusable').first().focus();
                    
                            hidden ? hidden() : '';
                        })
                    ;                      
                }
            }
        };     

            
	    /**
	     * Return the constructor function
	     */
	    return Tipo;
	};
   
})(window, window.angular);