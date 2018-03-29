(function(window, angular) {
    'use strict';

    angular
        .module('app')
        .factory('RateioCContabil', RateioCContabil);

	RateioCContabil.$inject = [
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

	function RateioCContabil($ajax, $q, $rootScope, $compile,$timeout, $consulta, gScope, gcCollection, gcObject) {

        // Private variables.
        var obj = null;
        var selected = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function RateioCContabil() {
            
            obj = this; 

            // Public methods         
            this.consultar     = consultar;
            this.merge         = merge;
            
            this.incluir        = incluir; 
            this.confirmar      = confirmar; 
            this.alterar        = alterar; 
            this.cancelar       = cancelar; 
            this.excluir        = excluir; 
          
            this.tipoChange = tipoChange;          
            this.regraChange = regraChange;          
            this.origemChange = origemChange;
            this.grupoChange = grupoChange;
          
            this.Modal         = Modal; 
            
            
            this.INCLUINDO = false;
            this.ALTERANDO = false;
            this.DADOS = [];
            this.DADOS_RENDER = [];

            this.RATEAMENTO_GRUPOS = [];
            
            this.TOTAL_GERAL = 0;
            this.SELECTEDS = [];
            this.SELECTED = [];
            this.SELECTED_BACKUP = [];
            
	    }
        
        
        

        function consultar() {
            
            return $q(function(resolve, reject){
                $ajax.get('/_31030/api/rateio/ccontabil').then(function(response){
                    
                    obj.merge(response);
                    
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }                
        
        /**
         * Retorna a view da factory
         */
        function merge(response) {
            
            sanitizeJson(response);
            
            
            gcCollection.merge(obj.DADOS, response, ['ID']);
            
            var rateamento_grupos = gcCollection.groupBy(obj.DADOS,[
                'RATEAMENTO_GRUPO',
                'RATEAMENTO_GRUPO_DESCRICAO'
            ],'CCONTABILS');
            
            
            gcCollection.merge(obj.RATEAMENTO_GRUPOS, rateamento_grupos, ['RATEAMENTO_GRUPO']);
            
            if ( obj.RATEAMENTO_GRUPO == undefined && obj.RATEAMENTO_GRUPOS.length > 0 ) {
                obj.RATEAMENTO_GRUPO = obj.RATEAMENTO_GRUPOS[0];
            }            
            
            var item = null;
            for ( var i in obj.DADOS ) {
                item = obj.DADOS[i];
                
                item.EXCLUIDO = false;
            }
            
        }
        
        function incluir() {
            
            obj.SELECTED = {
                REGRA_RATEAMENTO : 1,
                VALOR_ORIGEM     : 1,
                RATEAMENTO_GRUPO : 1,
                EXCLUIDO         : false
            };
            
            obj.regraChange(1);
            obj.origemChange(1);
            obj.grupoChange(1);
            
            gScope.Ctrl.ConsultaCContabil.apagar(true);
            
            obj.DADOS.push(obj.SELECTED);
            
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
            $ajax.post('/_31030/api/rateio/ccontabil/post',dados).then(function(response){

                obj.merge(response.DATA_RETURN);
                
            });
        }

        function alterar() {
            
//            gScope.Ctrl.ImobilizadoItem.SELECTEDS = [];
            
//            gScope.Ctrl.ConsultaCContabil.setDataRequest({});
            
            gScope.Ctrl.ConsultaCContabil.Input.value = obj.SELECTED.CCONTABIL_MASK + ' - ' + obj.SELECTED.CCONTABIL_DESCRICAO;
            
            gScope.Ctrl.ConsultaCContabil.Input.readonly             = true;
            gScope.Ctrl.ConsultaCContabil.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaCContabil.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaCContabil.btn_filtro.visivel         = false;            
            
            gScope.Ctrl.ConsultaCContabil.item.selected = true;            
            
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
                            f:item.CCONTABIL_DESCRICAO,
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
        
        function tipoChange(id) {
            var idx = indexOfAttr(gScope.Ctrl.rateioTipos,'ID',id);
            
            if ( idx == -1 ) {
                idx = 0;
            }
            obj.SELECTED.TIPO_ID        = gScope.Ctrl.rateioTipos[idx].ID;
            obj.SELECTED.TIPO_DESCRICAO = gScope.Ctrl.rateioTipos[idx].DESCRICAO;
        }           
        
        function regraChange(id) {
            
            switch (id) {
                case 1: obj.SELECTED.REGRA_RATEAMENTO_DESCRICAO = '01 - DEFINIDO PELA CONSULTA(ORIGEM)'         ; break;
                case 2: obj.SELECTED.REGRA_RATEAMENTO_DESCRICAO = '02 - FIXO (TBRATEAMENTO_CONTABIL_CCONTABIL)' ; break;
                case 3: obj.SELECTED.REGRA_RATEAMENTO_DESCRICAO = '03 - COLABORADOR'                            ; break;
                case 4: obj.SELECTED.REGRA_RATEAMENTO_DESCRICAO = '04 - COLABORADOR/TRANSPORTE'                 ; break;
                case 5: obj.SELECTED.REGRA_RATEAMENTO_DESCRICAO = '05 - COLABORADOR/REFEICAO'                   ; break;
                case 6: obj.SELECTED.REGRA_RATEAMENTO_DESCRICAO = '06 - AREA'                                   ; break;
                case 7: obj.SELECTED.REGRA_RATEAMENTO_DESCRICAO = '07 - SETORES BALANCIM HIDRAULICO'            ; break;
            }
        }
        
        function origemChange(id) {
            
            switch (id) {
                case 1: obj.SELECTED.VALOR_ORIGEM_DESCRICAO = '01 - LANCAMENTO DE ESTOQUE'                               ; break;
                case 2: obj.SELECTED.VALOR_ORIGEM_DESCRICAO = '02 - LANCAMENTO CONTABIL'                                 ; break;
                case 3: obj.SELECTED.VALOR_ORIGEM_DESCRICAO = '03 - INDEFINIDO'                                          ; break;
                case 4: obj.SELECTED.VALOR_ORIGEM_DESCRICAO = '04 - LANCAMENTO CONTABIL SEM CONSIDERAR O CENTRO DE CUSTO'; break;
                case 5: obj.SELECTED.VALOR_ORIGEM_DESCRICAO = '05 - DEPRECIACAO'                                         ; break;
            }
        }
        
        function grupoChange(id) {
            
            switch (id) {
                case 1:
                obj.SELECTED.RATEAMENTO_GRUPO_DESCRICAO = '01 - CUSTO DE MÃO DE OBRA INDIRETA';
                break;
            }
        }
                
        
        var Modal = {
            
            _modal : function(){
                return $('#modal-rateio-ccontabil');
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
	    return RateioCContabil;
	};
   
})(window, window.angular);