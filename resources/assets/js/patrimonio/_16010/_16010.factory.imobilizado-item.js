(function(window, angular) {
    'use strict';

    angular
        .module('app')
        .factory('ImobilizadoItem', ImobilizadoItem);

	ImobilizadoItem.$inject = [
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

	function ImobilizadoItem($ajax, $q, $rootScope, $compile,$timeout, $consulta, gScope, gcCollection, gcObject) {

        // Private variables.
        var obj = null;
        var selected = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function ImobilizadoItem() {
            
            obj = this; 

            // Public methods         
            this.consultar                      = consultar;
            this.consultarParcelas              = consultarParcelas;
            this.importarDocumentoFiscal        = importarDocumentoFiscal; 
            this.importarDocumentoFiscalSaida   = importarDocumentoFiscalSaida; 
            this.incluir                        = incluir; 
            this.alterar                        = alterar; 
            this.confirmar                      = confirmar; 
            this.cancelar                       = cancelar; 
            this.remover                        = remover; 
            this.keypress                       = keypress; 
            this.pick                           = pick; 
            this.unpick                         = unpick; 
            this.pickToggle                     = pickToggle; 
            this.picked                         = picked; 
            this.pickReverse                    = pickReverse; 
            this.pickAll                        = pickAll; 
            this.unpickAll                      = unpickAll; 
            this.dblClick                       = dblClick;
            this.encerrar                       = encerrar; 
            this.encerrarPickToggle             = encerrarPickToggle; 
            this.encerrarPicked                 = encerrarPicked; 
            this.Modal                          = Modal; 
            
                      
            this.SELECTEDS = [];
            this.SELECTED_OF_LIST = {};
            this.SELECTED = {};
            this.SELECTED_COPY = {};
            this.PARCELAS = [];
            this.TIPOS = [];
            this.NFE = '';
            this.PRODUTO_READONLY = false;
                        
	    }

        function consultar() {
            
            return $q(function(resolve, reject){
                $ajax.post('/_16010/api/imobilizado/item',{IMOBILIZADO_ID:gScope.Ctrl.Imobilizado.SELECTED.ID}).then(function(response){

                    sanitizeJson(response);
                    gcObject.bind(gScope.Ctrl.Imobilizado.SELECTED,response,'ID|IMOBILIZADO_ID','ITENS');
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }                

        function consultarParcelas(item) {
            
            return $q(function(resolve, reject){
                $ajax.post('/_16010/api/imobilizado/item/parcelas',{IMOBILIZADO_ITEM_ID:item.ID}).then(function(response){

                    gcObject.bind(item,response,'ID|IMOBILIZADO_ITEM_ID','PARCELAMENTOS');
                    obj.PARCELAS = response;
                    
                    $('#modal-imobilizado-item-parcela').modal('show');
                    $('#modal-imobilizado-item-parcela .table-ec').scrollTop(0);
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        } 

                       
        
        /**
         * Retorna a view da factory
         */
        function importarDocumentoFiscal() {
            
            selected = gScope.Ctrl.Imobilizado.SELECTED;
            gScope.Ctrl.Empresas = [];
            
            $ajax.post('/_16010/api/nf/item',{NFE: obj.NFE}).then(function(response){
                
                sanitizeJson(response);


                var empresas = [];
                var old_string = '';
                angular.forEach(response, function(item, key) {
                    if(old_string != item.FLAG){
                        old_string = item.FLAG;

                        empresas.push({
                            FLAG: item.FLAG,
                            EMPRESA: item.EMPRESA,
                            NOTA: item.NFE,
                            SERIE: item.SERIE,
                            DESC_DATA_ENTRADA: item.DESC_DATA_ENTRADA,
                            DATA_ENTRADA:  item.DATA_ENTRADA});
                    }
                });

                angular.forEach(empresas, function(item, key) {
                    gcObject.bind(item,response,'FLAG','ITENS');
                });

                angular.forEach(empresas, function(item, key) {
                    gcObject.bind(item,response,'FLAG','ITENS');
                });

                obj.NFE = '';

                if(empresas.length == 1){
                    gcCollection.merge(selected.ITENS,empresas[0].ITENS, ['PRODUTO_ID','NFE'], true);
                    showSuccess('Documento fiscal importado com sucesso.');

                    addConfirme('<h4>Confirmação</h4>',
                        'Deseja utilizar a data de entrada da nota ('+empresas[0].DESC_DATA_ENTRADA+') como data de início da depreciação?',
                        [obtn_sim,obtn_nao],
                        [{ret:1,func:function(){
                            $rootScope.$apply(function(){
                               gScope.Ctrl.Imobilizado.SELECTED.DATA_DEPRECIACAO =  moment(empresas[0].DATA_ENTRADA).toDate();
                            });
                        }}]     
                    );

                }else{
                    if(empresas.length > 0){
                        gScope.Ctrl.Empresas = empresas;

                        gScope.Ctrl.AddItensEmpresa = function(itens){
                            gcCollection.merge(selected.ITENS, itens, ['PRODUTO_ID','NFE'], true);
                            gScope.Ctrl.Empresas = [];
                            showSuccess('Documento fiscal importado com sucesso.');

                            addConfirme('<h4>Confirmação</h4>',
                                'Deseja utilizar a data de entrada da nota ('+itens[0].DESC_DATA_ENTRADA+') como data de início da depreciação?',
                                [obtn_sim,obtn_nao],
                                [{ret:1,func:function(){
                                    $rootScope.$apply(function(){
                                       gScope.Ctrl.Imobilizado.SELECTED.DATA_DEPRECIACAO =  moment(itens[0].DATA_ENTRADA).toDate();
                                    });
                                }}]     
                            );
                        }

                    }else{
                        showErro('Documento fiscal não encontrado ou todos os itens já importados.');    
                    }
                }

            });
        }
        
        
        /**
         * Retorna a view da factory
         */
        function importarDocumentoFiscalSaida() {
                        
            $ajax.post('/_16010/api/nfs',{NFS: obj.NFS}).then(function(response){
                
                sanitizeJson(response);

                gScope.Ctrl.NFSS = response;
                
                if ( gScope.Ctrl.NFSS.length == 1 ) {
                    gScope.Ctrl.NFS_SELECTED = gScope.Ctrl.NFSS[0];
                    gScope.Ctrl.ImobilizadoItem.NFS = ''; 
                    gScope.Ctrl.NFSS = [];
                }

            });
        }
        

        
        /**
         * Retorna a view da factory
         */
        function incluir() {
            obj.PRODUTO_READONLY = false;
            gScope.Ctrl.IIConsultaProduto.option.required = true;
            obj.SELECTED = {
                DATA_ENTRADA : moment().toDate(),
                FRETE_UNITARIO : 0
            };
            
//            $ajax.get('/_16010/api/imobilizado/tipo').then(function(response){
//                obj.TIPOS = response;
                obj.Modal.show();
//
//                obj.SELECTED = {
//                    TIPO: null,
//                    DESCRICAO : '',
//                    OBSERVACAO : '',
//                    ITENS : []
//                };
//            });
        }
        
        /**
         * Retorna a view da factory
         */
        function alterar() {
            obj.PRODUTO_READONLY = true;
            
            gScope.Ctrl.IIConsultaProduto.option.required = false;
            
            obj.SELECTED = obj.SELECTEDS[0];

            angular.copy(obj.SELECTED,obj.SELECTED_COPY);
            obj.SELECTED.DATA_ENTRADA = moment(obj.SELECTED.DATA_ENTRADA).toDate();
            
            
//            $ajax.get('/_16010/api/imobilizado/tipo').then(function(response){
//                obj.TIPOS = response;
                obj.Modal.show();
//
//                obj.SELECTED = {
//                    TIPO: null,
//                    DESCRICAO : '',
//                    OBSERVACAO : '',
//                    ITENS : []
//                };
//            });
        }
        
        function confirmar() {

            if ( !obj.PRODUTO_READONLY ) {
                obj.SELECTED.PRODUTO_ID        = gScope.Ctrl.IIConsultaProduto.PRODUTO_ID;
                obj.SELECTED.PRODUTO_DESCRICAO = gScope.Ctrl.IIConsultaProduto.PRODUTO_DESCRICAO;
                
                gScope.Ctrl.Imobilizado.SELECTED.ITENS.push(obj.SELECTED);
                
                gScope.Ctrl.IIConsultaProduto.apagar();
            }
            obj.Modal.hide();
        }
        
        function cancelar() {
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente cancelar esta operação?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){
                        angular.extend(obj.SELECTED,obj.SELECTED_COPY);
                        gScope.Ctrl.IIConsultaProduto.apagar();
                        obj.Modal.hide();
                    });
                }}]     
            );
        }
        

        function remover(item,$event ){

            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente excluir os itens selecionados?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){
                        

                        var itens = gScope.Ctrl.Imobilizado.SELECTED.ITENS;
                        var selecteds = obj.SELECTEDS;
                        
                        for ( var i  in selecteds ) {
                            var selected = selecteds[i];
                            
                            if ( selected.ID != undefined ) {
                                selected.EXCLUIDO = 1;
                            } else {

                                var idx = itens.indexOf(selected);

                                itens.splice(idx,1);
                            }
                        }
                        
                        obj.SELECTEDS = [];
                        
                        showSuccess('Itens removidos.');
                    });
                }}]     
            );
        };            
        


        function keypress(item,$event) {

            if ( $event.key == ' ' ) {

                $event.preventDefault();

                obj.pickToggle(item);
            }
//            else        
//            if ( $event.key == 'Enter' ) {
//
//            }
        };            
        
        
        /**
         * Retorna a view da factory
         */
        function pick(item) {
            
            if ( !obj.picked(item) ) {
                obj.SELECTEDS.push(item);
            }
        }
        
        /**
         * Retorna a view da factory
         */
        function unpick(item) {
            
            if ( obj.picked(item) ) {
                obj.SELECTEDS.splice(obj.SELECTEDS.indexOf(item),1);
            }
        }
        
        /**
         * Retorna a view da factory
         */
        function pickToggle(item) {
            
            if ( !obj.picked(item) ) {
                obj.SELECTEDS.push(item);
            } else {
                obj.SELECTEDS.splice(obj.SELECTEDS.indexOf(item),1);
            }
        }
        
        /**
         * Retorna a view da factory
         */
        function pickReverse() {
            var itens = gScope.Ctrl.Imobilizado.SELECTED.ITENS;
            
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
        
        /**
         * Retorna a view da factory
         */
        function pickAll() {
            var itens = gScope.Ctrl.Imobilizado.SELECTED.ITENS;
            
            for ( var i in itens ) {
                obj.pick(itens[i]);
            }
        }
        
        /**
         * Retorna a view da factory
         */
        function unpickAll() {
            var itens = gScope.Ctrl.Imobilizado.SELECTED.ITENS;
            
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
        
        
        function dblClick(item) {
            obj.unpickAll();
            obj.pick(item);
            obj.alterar();
        }
        

        function encerrar() {
            
            var dados = {
                NFS_ID : gScope.Ctrl.NFS_SELECTED.NFS_ID,
                TIPO : obj.ENCERRAR_TIPO,
                IMOBILIZADO_ID : gScope.Ctrl.Imobilizado.SELECTED.ID,
                ITENS : obj.ENCERRAR_SELECTEDS
            };

            $ajax.post('/_16010/api/imobilizado/item/encerrar',dados).then(function(response){
                gScope.Ctrl.ImobilizadoItem.consultar();
                
                $('#modal-imobilizado-encerrar').modal('hide');
            });
        }        
        
        function encerrarPickToggle(item) {
            
            if ( obj.ENCERRAR_SELECTEDS == undefined ) {
                obj.ENCERRAR_SELECTEDS = [];
            }
            
            if ( !obj.encerrarPicked(item) ) {
                obj.ENCERRAR_SELECTEDS.push(item);
            } else {
                obj.ENCERRAR_SELECTEDS.splice(obj.ENCERRAR_SELECTEDS.indexOf(item),1);
            }
        }        
        

        function encerrarPicked(item) {
            
            var ret = true;
            
            if ( obj.ENCERRAR_SELECTEDS == undefined || obj.ENCERRAR_SELECTEDS.indexOf(item) == -1 ) {
                ret = false;
            }
            
            return ret;
        }        
        
                
        
        var Modal = {
            
            _modal : function(){
                return $('#modal-imobilizado-item');
            },
            show : function(shown,hidden) {

                this._modal()
                    .modal('show')
                ;                         


                this._modal()
                    .one('shown.bs.modal', function(){

                        $(this).find('input:focusable').first().focus();

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
	    return ImobilizadoItem;
	};
   
})(window, window.angular);