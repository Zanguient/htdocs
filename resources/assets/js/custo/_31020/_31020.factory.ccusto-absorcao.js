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