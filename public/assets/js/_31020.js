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
        .factory('RateioCCusto', RateioCCusto);

	RateioCCusto.$inject = [
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

	function RateioCCusto($ajax, $q, $rootScope, $compile,$timeout, $consulta, gScope, gcCollection, gcObject) {

        // Private variables.
        var obj = null;
        var selected = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function RateioCCusto() {
            
            obj = this; 

            // Public methods         
            this.consultar     = consultar;
            this.merge         = merge;
            
            this.incluir        = incluir; 
            this.confirmar      = confirmar; 
            this.alterar        = alterar; 
            this.cancelar       = cancelar; 
            this.excluir        = excluir; 
          
            this.processarOrdem = processarOrdem;
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
                    
            this.CCUSTOS_MESES = [];
            this.CCUSTOS_TIPOS_MESES = [];
            this.CCUSTOS_TIPOS = [];
            this.CCUSTOS = [];
            
            this.TOTAL_GERAL = 0;
            this.SELECTEDS = [];
            this.SELECTED = [];
            this.SELECTED_BACKUP = [];
            
	    }
        
        
        

        function consultar() {
            
            return $q(function(resolve, reject){
                $ajax.get('/_31020/api/rateio/ccusto').then(function(response){
                    
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
            ],'CCUSTOS');
            
            
            gcCollection.merge(obj.RATEAMENTO_GRUPOS, rateamento_grupos, ['RATEAMENTO_GRUPO']);
            
            if ( obj.RATEAMENTO_GRUPO == undefined && obj.RATEAMENTO_GRUPOS.length > 0 ) {
                obj.RATEAMENTO_GRUPO = obj.RATEAMENTO_GRUPOS[0];
            }
            
            
            var item = null;
            for ( var i in obj.DADOS ) {
                item = obj.DADOS[i];
                
                item.CCUSTOS = undefined;
                item.EXCLUIDO = false;
            }
            
        }
        
        function incluir() {
            
            var abrangencia = 0;
            var ordem       = 1;
            
            if ( obj.SELECTED != undefined && obj.SELECTED.ABRANGENCIA > 0 ) {
                abrangencia = obj.SELECTED.ABRANGENCIA;
                ordem       = obj.SELECTED.ORDEM -1;
            }
            
            obj.SELECTED = {
                ABRANGENCIA      : abrangencia,
                ORDEM            : ordem,
                REGRA_RATEAMENTO : 1,
                VALOR_ORIGEM     : 1,
                RATEAMENTO_GRUPO : 1,
                EXCLUIDO         : false
            };
            
            obj.regraChange(1);
            obj.origemChange(1);
            obj.grupoChange(1);
            
            gScope.Ctrl.ConsultaCcusto.apagar(true);
            
            obj.DADOS.push(obj.SELECTED);
            
            obj.INCLUINDO = true;
            obj.ALTERANDO = true;
            obj.Modal.show();

//            obj.selectedReset();
        }
        
        function confirmar() {
       
            obj.processarOrdem();
            
            var dados = {
                FILTRO: {},
                DADOS : obj.DADOS
            };
//    
            $ajax.post('/_31020/api/rateio/ccusto/post',dados).then(function(response){

                obj.merge(response.DATA_RETURN);
                
            });
        }

        function alterar() {
            
//            gScope.Ctrl.ImobilizadoItem.SELECTEDS = [];
            
            gScope.Ctrl.ConsultaCcusto.setDataRequest({});
            
            gScope.Ctrl.ConsultaCcusto.Input.value = obj.SELECTED.CCUSTO_MASK + ' - ' + obj.SELECTED.CCUSTO_DESCRICAO;
            
            gScope.Ctrl.ConsultaCcusto.Input.readonly             = true;
            gScope.Ctrl.ConsultaCcusto.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaCcusto.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaCcusto.btn_filtro.visivel         = false;            
            
            gScope.Ctrl.ConsultaCcusto.item.selected = true;            
            
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
        
        
        function processarOrdem () {
            
            var item        = null; 
            var item_prev   = null;
            var abrangencia = 0;
            var ordem       = 0;
            
            for ( var i in obj.DADOS_RENDER ) {
                item = obj.DADOS_RENDER[i];
                item_prev = obj.DADOS_RENDER[parseInt(i)-1];
                
                
                if ( item_prev == undefined || item_prev.ABRANGENCIA != item.ABRANGENCIA ) {
                    item.ABRANGENCIA_CHANGE = true;
                } else {
                    item.ABRANGENCIA_CHANGE = false;
                }
            }
            
            for ( var i in obj.DADOS_RENDER ) {
                item = obj.DADOS_RENDER[i];
                
                if ( item.ABRANGENCIA_CHANGE ) {
                    abrangencia += 10;
                    ordem        = 1;
                }
                
                item.ABRANGENCIA = abrangencia;
                item.ORDEM       = ordem;
                
                ordem++;
            }
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
                case 1:
                obj.SELECTED.REGRA_RATEAMENTO_DESCRICAO = '01 - COLABORADORES';
                break;
                case 2:
                obj.SELECTED.REGRA_RATEAMENTO_DESCRICAO = '02 - AREA';
                break;
                case 3:
                obj.SELECTED.REGRA_RATEAMENTO_DESCRICAO = '03 - MÁQUINAS';
                break;
                case 4:
                obj.SELECTED.REGRA_RATEAMENTO_DESCRICAO = '04 - FIXO';
                break;
            }
        }
        
        function origemChange(id) {
            
            switch (id) {
                case 1:
                obj.SELECTED.VALOR_ORIGEM_DESCRICAO = '01 - SALÁRIOS';
                break;
                case 2:
                obj.SELECTED.VALOR_ORIGEM_DESCRICAO = '02 - OUTROS';
                break;
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
                return $('#modal-rateio-ccusto');
            },
            show : function(shown,hidden) {
                
                
                gScope.Ctrl.CCustoAbsorcao.SELECTEDS = [];
                if ( obj.SELECTED.CCUSTOS == undefined ) {
                    gScope.Ctrl.CCustoAbsorcao.consultar();
                }
                                
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
	    return RateioCCusto;
	};
   
})(window, window.angular);
(function(window, angular) {
    'use strict';

    angular
        .module('app')
        .factory('CCustoAbsorcao', CCustoAbsorcao);

	CCustoAbsorcao.$inject = [
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

	function CCustoAbsorcao($ajax, $q, $rootScope, $compile,$timeout, $consulta, gScope, gcCollection, gcObject) {

        // Private variables.
        var obj = null;
        var selected = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function CCustoAbsorcao() {
            
            obj = this; 

            // Public methods         
            this.consultar     = consultar;
            this.merge         = merge;
            
            this.incluir        = incluir; 
            this.confirmar      = confirmar; 
            this.alterar        = alterar; 
            this.cancelar       = cancelar; 
            this.excluir        = excluir; 
          
            this.pick                    = pick; 
            this.unpick                  = unpick; 
            this.pickToggle              = pickToggle; 
            this.picked                  = picked; 
            this.pickReverse             = pickReverse; 
            this.pickAll                 = pickAll; 
            this.unpickAll               = unpickAll;           
          
            this.Modal         = Modal; 
            
            
            this.INCLUINDO = false;
            this.ALTERANDO = false;
            this.DADOS = [];
            this.DADOS_RENDER = [];
            this.MESES = [];
            
            this.GERAL_MESES = [];
            this.GERAL = [];
            
            this.TIPOS_CCUSTOS = [];
            
            
            this.TIPOS_MESES = [];
            this.TIPOS = [];
            this.TIPOS_CCUSTOS = [];
            this.TIPOS_CCUSTOS_MESES = [];
            
            this.CCUSTOS_MESES = [];
            this.CCUSTOS_TIPOS_MESES = [];
            this.CCUSTOS_TIPOS = [];
            this.CCUSTOS = [];
            
            this.TOTAL_GERAL = 0;
            this.SELECTEDS = [];
            this.SELECTED = [];
            this.SELECTED_BACKUP = [];
            
	    }
        
        
        

        function consultar() {
            
            return $q(function(resolve, reject){
                $ajax.post('/_31020/api/ccusto/absorcao',{
                    CCUSTO: gScope.Ctrl.RateioCCusto.SELECTED.CCUSTO + (gScope.Ctrl.RateioCCusto.SELECTED.HIERARQUIA == 1 ? '*' : '')
                }).then(function(response){
                    
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
            
            
            if ( gScope.Ctrl.RateioCCusto.SELECTED.CCUSTOS == undefined ) {
                gScope.Ctrl.RateioCCusto.SELECTED.CCUSTOS = [];
            }
            
            var dados = gScope.Ctrl.RateioCCusto.SELECTED.CCUSTOS;
            
            gcCollection.merge(dados, response, ['ID']);
            
            var item = null;
            for ( var i in dados ) {
                item = dados[i];
                
                item.EXCLUIDO = false;
            }
            
            
        }
        
        function incluir() {
            
            obj.SELECTED = {
                CCUSTO_ABSORCAO  : gScope.Ctrl.RateioCCusto.SELECTED.CCUSTO + (gScope.Ctrl.RateioCCusto.SELECTED.HIERARQUIA == 1 ? '*' : ''),
                PERC_ABSORCAO    : 0,
                RATEAMENTO_GRUPO : 1,
                EXCLUIDO         : false
            };
            
            gScope.Ctrl.caConsultaCCusto.apagar(true);
            
            gScope.Ctrl.RateioCCusto.SELECTED.CCUSTOS.push(obj.SELECTED);
            
            obj.INCLUINDO = true;
            obj.ALTERANDO = true;
            obj.Modal.show();

//            obj.selectedReset();
        }
        
        function confirmar() {
       
            obj.processarOrdem();
            
            var dados = {
                FILTRO: {},
                DADOS : gScope.Ctrl.RateioCCusto.SELECTED.CCUSTOS
            };
//    
            $ajax.post('/_31020/api/rateio/ccusto/post',dados).then(function(response){

                obj.merge(response.DATA_RETURN);
                
            });
        }

        function alterar() {
            
//            gScope.Ctrl.ImobilizadoItem.SELECTEDS = [];
            
            gScope.Ctrl.ConsultaCcusto.setDataRequest({});
            
            gScope.Ctrl.ConsultaCcusto.Input.value = obj.SELECTED.CCUSTO_MASK + ' - ' + obj.SELECTED.CCUSTO_DESCRICAO;
            
            gScope.Ctrl.ConsultaCcusto.Input.readonly             = true;
            gScope.Ctrl.ConsultaCcusto.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaCcusto.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaCcusto.btn_filtro.visivel         = false;            
            
            gScope.Ctrl.ConsultaCcusto.item.selected = true;            
            
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
                            
                            var idx = gScope.Ctrl.RateioCCusto.SELECTED.CCUSTOS.indexOf(obj.SELECTED);
                            
                            gScope.Ctrl.RateioCCusto.SELECTED.CCUSTOS.splice(idx,1);                            
                            
//                            obj.selectedReset();
                            obj.Modal.hide();
                        }
                    });
                }}]     
            );
        }
        
        function excluir() {
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente excluir os itens selecionados?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){
                        
                        var item = null;
                        for ( var i in obj.SELECTEDS ) {
                            item = obj.SELECTEDS[i];
                            
                            item.EXCLUIDO = true;
                        }
                    });
                }}]     
            );
        }


        function pick(item) {
            
            if ( !obj.picked(item) ) {
                obj.SELECTEDS.push(item);
            }
        }
        
        function unpick(item) {
            
            if ( obj.picked(item) ) {
                obj.SELECTEDS.splice(obj.SELECTEDS.indexOf(item),1);
            }
        }
        
        function pickToggle(item) {
            
            if ( !obj.picked(item) ) {
                obj.SELECTEDS.push(item);
            } else {
                obj.SELECTEDS.splice(obj.SELECTEDS.indexOf(item),1);
            }
        }
        
        function pickReverse() {
            var itens = gScope.Ctrl.RateioCCusto.SELECTED.CCUSTOS;
            
            for ( var i in itens ) {
                obj.pickToggle(itens[i]);
            }
        }
        
        function picked(item) {
            
            var ret = true;
            
            if ( obj.SELECTEDS.indexOf(item) == -1 ) {
                ret = false;
            }
            
            return ret;
        }
        
        function pickAll() {
            var itens = gScope.Ctrl.RateioCCusto.SELECTED.CCUSTOS;
            
            for ( var i in itens ) {
                obj.pick(itens[i]);
            }
        }
        
        function unpickAll() {
            var itens = gScope.Ctrl.RateioCCusto.SELECTED.CCUSTOS;
            
            for ( var i in itens ) {
                obj.unpick(itens[i]);
            }
        }
        
        function picked(item) {
            
            var ret = true;
            
            if ( obj.SELECTEDS.indexOf(item) == -1 ) {
                ret = false;
            }
            
            return ret;
        }
        
        
        var Modal = {
            
            _modal : function(){
                return $('#modal-ccusto-absorcao');
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
	    return CCustoAbsorcao;
	};
   
})(window, window.angular);
angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        '$ajax',
        'gScope',
        '$consulta',
        'Historico',
        'RateioCCusto',
        'CCustoAbsorcao'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        $ajax,
        gScope, 
        $consulta,
        Historico,
        RateioCCusto,
        CCustoAbsorcao
    ) {

		var vm     = this;
        
        gScope.Ctrl = this;
        
     
        vm.Historico       = new Historico();
        vm.RateioCCusto    = new RateioCCusto();
        vm.CCustoAbsorcao = new CCustoAbsorcao();
        vm.Consulta        = new $consulta();
        
        vm.RateioCCusto.consultar();
        

        vm.ConsultaCcusto                        = vm.Consulta.getNew(true);
        vm.ConsultaCcusto.componente             = '.consulta-ccusto';
        vm.ConsultaCcusto.model                  = 'vm.ConsultaCcusto';
        vm.ConsultaCcusto.option.label_descricao = 'Centro de Custo:';
        vm.ConsultaCcusto.option.obj_consulta    = '/_20030/api/ccusto';
        vm.ConsultaCcusto.option.tamanho_input   = 'input-maior';
        vm.ConsultaCcusto.option.campos_tabela   = [['MASK', 'C. Custo'],['DESCRICAO','Descrição']];
        vm.ConsultaCcusto.option.obj_ret         = ['MASK', 'DESCRICAO'];
        vm.ConsultaCcusto.compile();
        
        vm.ConsultaCcusto.onSelect = function() {
            vm.RateioCCusto.SELECTED.CCUSTO           = vm.ConsultaCcusto.ID;
            vm.RateioCCusto.SELECTED.CCUSTO_MASK      = vm.ConsultaCcusto.MASK;
            vm.RateioCCusto.SELECTED.CCUSTO_DESCRICAO = vm.ConsultaCcusto.DESCRICAO;
        };
        
        vm.ConsultaCcusto.onClear = function() {
            vm.RateioCCusto.SELECTED.CCUSTO           = '';
            vm.RateioCCusto.SELECTED.CCUSTO_MASK      = '';
            vm.RateioCCusto.SELECTED.CCUSTO_DESCRICAO = '';
        };

        vm.caConsultaCCusto                        = vm.Consulta.getNew(true);
        vm.caConsultaCCusto.componente             = '.ca-consulta-ccusto';
        vm.caConsultaCCusto.model                  = 'vm.caConsultaCCusto';
        vm.caConsultaCCusto.option.label_descricao = 'Centro de Custo:';
        vm.caConsultaCCusto.option.obj_consulta    = '/_20030/api/ccusto';
        vm.caConsultaCCusto.option.tamanho_input   = 'input-maior';
        vm.caConsultaCCusto.option.campos_tabela   = [['MASK', 'C. Custo'],['DESCRICAO','Descrição']];
        vm.caConsultaCCusto.option.obj_ret         = ['MASK', 'DESCRICAO'];
        vm.caConsultaCCusto.compile();
        
        vm.caConsultaCCusto.onSelect = function() {
            vm.CCustoAbsorcao.SELECTED.CCUSTO           = vm.caConsultaCCusto.ID;
            vm.CCustoAbsorcao.SELECTED.CCUSTO_MASK      = vm.caConsultaCCusto.MASK;
            vm.CCustoAbsorcao.SELECTED.CCUSTO_MASKA     = 'A'+vm.caConsultaCCusto.MASK;
            vm.CCustoAbsorcao.SELECTED.CCUSTO_DESCRICAO = vm.caConsultaCCusto.DESCRICAO;
        };
        
        vm.caConsultaCCusto.onClear = function() {
            vm.CCustoAbsorcao.SELECTED.CCUSTO           = '';
            vm.CCustoAbsorcao.SELECTED.CCUSTO_MASK      = '';
            vm.CCustoAbsorcao.SELECTED.CCUSTO_MASKA     = '';
            vm.CCustoAbsorcao.SELECTED.CCUSTO_DESCRICAO = '';
        };



         
            
        $ajax.get('/_31050/api/rateio/tipo').then(function(response){
            sanitizeJson(response);
            vm.rateioTipos = response;
        });

	}   
  
//# sourceMappingURL=_31020.js.map
