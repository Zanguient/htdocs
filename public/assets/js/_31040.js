'use strict';

angular
	.module('app', [
		'vs-repeat', 
        'gc-find',
		'gc-ajax',
		'gc-transform',
		'gc-form',
		'gc-utils'
	])
;
     
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
            this.consultarDetalhe = consultarDetalhe;
            this.merge            = merge;
            this.totalizador      = totalizador;
            
            this.incluir        = incluir; 
            this.confirmar      = confirmar; 
            this.alterar        = alterar; 
            this.cancelar       = cancelar; 
            this.excluir        = excluir; 
          
            this.tipoChange = tipoChange;
            
            this.Modal         = Modal; 
            
            this.ORDER_BY  = '';
            this.INCLUINDO = false;
            this.ALTERANDO = false;
            this.DADOS = [];
            this.DADOS_RENDER = [];

            this.TIPOS          = [];
            this.TIPOS_DETALHES = [];
            
            this.TOTAL_GERAL = 0;
            this.SELECTEDS = [];
            this.SELECTED = [];
            this.SELECTED_BACKUP = [];
            
	    }
        
        
        

        function consultar() {
            
            return $q(function(resolve, reject){
                $ajax.get('/_31040/api/rateio/tipo').then(function(response){
                    
                    obj.TIPOS = response;
                    
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }                

        function consultarDetalhe() {
            
            return $q(function(resolve, reject){
                $ajax.get('/_31040/api/rateio/tipo/detalhe').then(function(response){
                    
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
            
            var tipos = gcCollection.groupBy(obj.DADOS,[
                'TIPO_ID',
                'TIPO_DESCRICAO',
                'UM',
                'UM_DESCRICAO'
            ],'CCUSTOS',
            function(group, item){
                
                if ( group.VALOR_TOTAL == undefined ) {
                    group.VALOR_TOTAL = 0;
                }
                
                group.VALOR_TOTAL += item.VALOR;
            });
            
            
            gcCollection.merge(obj.TIPOS_DETALHES, tipos, ['TIPO_ID']);
            
            if ( obj.TIPO == undefined && obj.TIPOS_DETALHES.length > 0 ) {
                obj.TIPO = obj.TIPOS_DETALHES[0];
            }            
            
            var item = null;
            for ( var i in obj.DADOS ) {
                item = obj.DADOS[i];
                
                item.EXCLUIDO = false;
            }
            
        }
        
        /**
         * Retorna a view da factory
         */
        function totalizador() {
            
            var ret = 0;
            
            if ( obj.TIPO != undefined ) {

                obj.TIPO.VALOR_TOTAL = 0;

                var ccustos = obj.TIPO.CCUSTOS;
                var ccusto  = null;

                for ( var i in ccustos ) {
                    ccusto = ccustos[i];

                    if ( parseFloat(ccusto.VALOR) > 0 ) {
                        obj.TIPO.VALOR_TOTAL += parseFloat(ccusto.VALOR);
                    }
                }                        
                
                ret = obj.TIPO.VALOR_TOTAL;
            }
            
            return ret;
        }
        
        function incluir() {
            
            obj.SELECTED = {
                CCUSTO           : '',
                CCUSTOA          : '',
                CCUSTO_DESCRICAO : '',
                CCUSTO_MASK      : '',
                TIPO_DESCRICAO   : '',
                TIPO_ID          : 1,
                EXCLUIDO         : false
            };
            
            obj.tipoChange(obj.TIPO.TIPO_ID);
            
            gScope.Ctrl.ConsultaCCusto.apagar(true);
            
            obj.DADOS.push(obj.SELECTED);
            obj.TIPO.CCUSTOS.push(obj.SELECTED);
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
            $ajax.post('/_31040/api/rateio/tipo/post',dados).then(function(response){

                obj.merge(response.DATA_RETURN);
                
            });
        }

        function alterar() {
            
//            gScope.Ctrl.ImobilizadoItem.SELECTEDS = [];
            
//            gScope.Ctrl.ConsultaCCusto.setDataRequest({});
            
            gScope.Ctrl.ConsultaCCusto.Input.value = obj.SELECTED.CCUSTO_MASK + ' - ' + obj.SELECTED.CCUSTO_DESCRICAO;
            
            gScope.Ctrl.ConsultaCCusto.Input.readonly             = true;
            gScope.Ctrl.ConsultaCCusto.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaCCusto.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaCCusto.btn_filtro.visivel         = false;            
            
            gScope.Ctrl.ConsultaCCusto.item.selected = true;            
            
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
                            
                            
                            var idx1 = obj.TIPO.CCUSTOS.indexOf(obj.SELECTED);
                            
                            obj.TIPO.CCUSTOS.splice(idx1,1);                            
                            
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
        
        function tipoChange(id) {
            var idx = indexOfAttr(obj.TIPOS,'TIPO_ID',id);
            
            if ( idx == -1 ) {
                idx = 0;
            }
            obj.SELECTED.TIPO_ID        = obj.TIPOS[idx].TIPO_ID;
            obj.SELECTED.TIPO_DESCRICAO = obj.TIPOS[idx].TIPO_DESCRICAO;
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
angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        'gScope',
        '$consulta',
        'Historico',
        'RateioTipo'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        gScope, 
        $consulta,
        Historico,
        RateioTipo
    ) {

		var vm          = this;
        gScope.Ctrl     = this;
        
        vm.RateioTipo   = new RateioTipo();
        vm.Historico    = new Historico();
        vm.Consulta     = new $consulta();
        
        vm.RateioTipo.consultarDetalhe();
        vm.RateioTipo.consultar();
        
        vm.ConsultaCCusto                        = vm.Consulta.getNew(true);
        vm.ConsultaCCusto.componente             = '.consulta-ccusto';
        vm.ConsultaCCusto.model                  = 'vm.ConsultaCCusto';
        vm.ConsultaCCusto.option.label_descricao = 'Centro de Custo:';
        vm.ConsultaCCusto.option.obj_consulta    = '/_20030/api/ccusto';
        vm.ConsultaCCusto.option.tamanho_input   = 'input-maior';
        vm.ConsultaCCusto.option.campos_tabela   = [['MASK', 'C. Custo'],['DESCRICAO','Descrição']];
        vm.ConsultaCCusto.option.obj_ret         = ['MASK', 'DESCRICAO'];
        vm.ConsultaCCusto.compile();
        
        vm.ConsultaCCusto.onSelect = function() {
            vm.RateioTipo.SELECTED.CCUSTO           = vm.ConsultaCCusto.ID;
            vm.RateioTipo.SELECTED.CCUSTOA          = 'A'+vm.ConsultaCCusto.ID;
            vm.RateioTipo.SELECTED.CCUSTO_MASK      = vm.ConsultaCCusto.MASK;
            vm.RateioTipo.SELECTED.CCUSTO_DESCRICAO = vm.ConsultaCCusto.DESCRICAO;
        };
        
        vm.ConsultaCCusto.onClear = function() {
            vm.RateioTipo.SELECTED.CCUSTO           = '';
            vm.RateioTipo.SELECTED.CCUSTOA          = '';
            vm.RateioTipo.SELECTED.CCUSTO_MASK      = '';
            vm.RateioTipo.SELECTED.CCUSTO_DESCRICAO = '';
        };        
        

	}   
  
//# sourceMappingURL=_31040.js.map
