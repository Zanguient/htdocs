(function(window, angular) {
    'use strict';

    angular
        .module('app')
        .factory('Imobilizado', Imobilizado);

	Imobilizado.$inject = [
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

	function Imobilizado($ajax, $q, $rootScope, $compile,$timeout, $consulta,gScope, gcCollection, gcObject) {

        // Private variables.
        var obj = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function Imobilizado() {
            
            obj = this; 

            // Public methods         
            this.merge = merge;
            this.consultarTodos = consultarTodos; 
            this.consultar      = consultar; 
            this.consultarParcelas = consultarParcelas;
            this.visualizar     = visualizar; 
            this.alterar        = alterar; 
            this.incluir        = incluir; 
            this.confirmar      = confirmar; 
            this.cancelar       = cancelar; 
            this.excluir        = excluir; 
            this.depreciar      = depreciar; 
            this.selectedReset  = selectedReset;
            this.Modal          = Modal;
            this.copiar         = copiar;

            this.Flag  = 0;    
            this.RESPONSE = [];
            
            
            this.ARR_STATUS_ANO_MES_TIPO_NF = [];
            this.ARR_STATUS_ANO_MES_TIPO = [];
            this.ARR_STATUS_ANO_MES = [];
            this.ARR_STATUS_ANO = [];
            this.ARR_STATUS = [];
            
            
            this.DADOS = [];
            this.SELECTED = {};
            this.SELECTED_BACKUP = {};
            this.TIPOS = [];
            this.INCLUINDO = false;
            this.ALTERANDO = false;
            
            obj.selectedReset();
	    }
        
        function merge(response) {
            
            
            sanitizeJson(response);
                        
            gcCollection.merge(obj.RESPONSE, response, ['NFE_ID','ID']);
            
            obj.VALOR_GERAL = 0;
            obj.SALDO_MES_GERAL = 0;
            obj.SALDO_GERAL = 0;

            // AGRUPAMENTO NF
            
            var arr_status_ano_mes_tipo_nf = gcCollection.groupBy(obj.RESPONSE,[
                'STATUS',
                'STATUS_DESCRICAO',
                'ANO',
                'MES',
                'MES_DESCRICAO',
                'TIPO_ID',
                'TIPO_DESCRICAO',
                'TAXA',
                'NFE_ID',
                'NFE',
                'SERIE',
                'DOC_FISCAL',
                'EMPRESA_RAZAOSOCIAL',
                'NFE_DATA_ENTRADA',
                'NFE_DATA_ENTRADA_TEXT'
            ],'IMOBILIZADOS',function(group,item){
                
                if ( group.VALOR      == undefined ) { group.VALOR      = 0; }
                if ( group.SALDO_MES  == undefined ) { group.SALDO_MES  = 0; }
                if ( group.SALDO      == undefined ) { group.SALDO      = 0; }
                
                group.VALOR     += item.VALOR;
                group.SALDO_MES += item.SALDO_MES;
                group.SALDO     += item.SALDO;
                
                obj.VALOR_GERAL     += item.VALOR    ;
                obj.SALDO_MES_GERAL += item.SALDO_MES;
                obj.SALDO_GERAL     += item.SALDO    ;
            });

            gcCollection.merge(obj.ARR_STATUS_ANO_MES_TIPO_NF, arr_status_ano_mes_tipo_nf, [
                'STATUS',
                'ANO',
                'MES',
                'TIPO_ID',
                'NFE_ID'
            ]);
            
            //

            // AGRUPAMENTO TIPO
            
            var arr_status_ano_mes_tipo = gcCollection.groupBy(obj.ARR_STATUS_ANO_MES_TIPO_NF,[
                'STATUS',
                'STATUS_DESCRICAO',
                'ANO',
                'MES',
                'MES_DESCRICAO',
                'TIPO_ID',
                'TIPO_DESCRICAO',
                'TAXA'
            ],'NFS',function(group,item){
                if ( group.VALOR      == undefined ) { group.VALOR      = 0; }
                if ( group.SALDO_MES  == undefined ) { group.SALDO_MES  = 0; }
                if ( group.SALDO      == undefined ) { group.SALDO      = 0; }
                
                group.VALOR     += item.VALOR;
                group.SALDO_MES += item.SALDO_MES;
                group.SALDO     += item.SALDO;
            });

            gcCollection.merge(obj.ARR_STATUS_ANO_MES_TIPO, arr_status_ano_mes_tipo, [
                'STATUS',
                'ANO',
                'MES',
                'TIPO_ID'
            ]);
            
            //

            // AGRUPAMENTO MES
            
            var arr_status_ano_mes = gcCollection.groupBy(obj.ARR_STATUS_ANO_MES_TIPO,[
                'STATUS',
                'STATUS_DESCRICAO',
                'ANO',
                'MES',
                'MES_DESCRICAO'
            ],'TIPOS',function(group,item){
                if ( group.VALOR      == undefined ) { group.VALOR      = 0; }
                if ( group.SALDO_MES  == undefined ) { group.SALDO_MES  = 0; }
                if ( group.SALDO      == undefined ) { group.SALDO      = 0; }
                
                group.VALOR     += item.VALOR;
                group.SALDO_MES += item.SALDO_MES;
                group.SALDO     += item.SALDO;
            });

            gcCollection.merge(obj.ARR_STATUS_ANO_MES, arr_status_ano_mes, [
                'STATUS',
                'ANO',
                'MES'
            ]);
            
            //

            // AGRUPAMENTO ANO
            
            var arr_status_ano = gcCollection.groupBy(obj.ARR_STATUS_ANO_MES,[
                'STATUS',
                'STATUS_DESCRICAO',
                'ANO'
            ],'MESES',function(group,item){
                if ( group.VALOR      == undefined ) { group.VALOR      = 0; }
                if ( group.SALDO_MES  == undefined ) { group.SALDO_MES  = 0; }
                if ( group.SALDO      == undefined ) { group.SALDO      = 0; }
                
                group.VALOR     += item.VALOR;
                group.SALDO_MES += item.SALDO_MES;
                group.SALDO     += item.SALDO;
            });

            gcCollection.merge(obj.ARR_STATUS_ANO, arr_status_ano, [
                'STATUS',
                'ANO'
            ]);
            
            //

            // AGRUPAMENTO STATUS
            
            var arr_status = gcCollection.groupBy(obj.ARR_STATUS_ANO,[
                'STATUS',
                'STATUS_DESCRICAO'
            ],'ANOS',function(group,item){
                if ( group.VALOR      == undefined ) { group.VALOR      = 0; }
                if ( group.SALDO_MES  == undefined ) { group.SALDO_MES  = 0; }
                if ( group.SALDO      == undefined ) { group.SALDO      = 0; }
                
                group.VALOR     += item.VALOR;
                group.SALDO_MES += item.SALDO_MES;
                group.SALDO     += item.SALDO;
            });

            gcCollection.merge(obj.ARR_STATUS, arr_status, [
                'STATUS'
            ]);
            
            //
            
            
            console.log(obj.ARR_STATUS);
            
            
        }

        function consultarTodos() {
            
            $ajax.post('/_16010/api/imobilizados',{progress : false}).then(function(response){

                obj.merge(response);
//                gcCollection.merge(obj.DADOS, response,'ID');

                var itens = $('.tabela-itens-imobilizado').find('.itens-imobilizado');

                if(itens.length > 0){
                    var iten = itens[0];
                    $(iten).trigger('click');   
                }
                
            });
        }        

        function consultar(id) {
            
            var id = id != undefined ? id : obj.SELECTED.ID;
                
            return $q(function(resolve,reject){
                
                $ajax.post('/_16010/api/imobilizado',{ID:id}).then(function(response){

                    sanitizeJson(response.ITENS);
                    sanitizeJson(response.FRETES);
                    sanitizeJson(response.IMOBILIZADO);
                    
                    response.DATA_DEPRECIACAO =  moment(response.DATA_DEPRECIACAO).toDate();

                    angular.extend(obj.SELECTED,response.IMOBILIZADO);
                    gcObject.bind(obj.SELECTED,response.ITENS,'ID|IMOBILIZADO_ID','ITENS');
                    gcObject.bind(obj.SELECTED,response.FRETES,'ID|IMOBILIZADO_ID','FRETES');

                    obj.SELECTED.DATA_DEPRECIACAO =  moment(obj.SELECTED.DATA_DEPRECIACAO).toDate();

                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }        
        
        function visualizar(id) {
            
            var prepare = function() {
                gScope.Ctrl.ConsultaImobilizadoTipo.Input.value = obj.SELECTED.TIPO_ID + ' - ' + obj.SELECTED.TIPO_DESCRICAO;
                gScope.Ctrl.ConsultaImobilizadoTipo.disable(true);   
                gScope.Ctrl.ConsultaImobilizadoTipo.item.selected = true;

                gScope.Ctrl.IConsultaCcusto.Input.value = obj.SELECTED.CCUSTO + ' - ' + obj.SELECTED.CCUSTO_DESCRICAO;
                gScope.Ctrl.IConsultaCcusto.disable(true);
                gScope.Ctrl.IConsultaCcusto.item.selected = true;

                gScope.Ctrl.ConsultaImobilizadoTipo.ID               = obj.SELECTED.TIPO_ID 
                gScope.Ctrl.ConsultaImobilizadoTipo.DESCRICAO        = obj.SELECTED.TIPO_DESCRICAO;
                gScope.Ctrl.ConsultaImobilizadoTipo.TAXA_DEPRECIACAO = obj.SELECTED.TAXA;
                gScope.Ctrl.ConsultaImobilizadoTipo.VIDA_UTIL        = obj.SELECTED.VIDA_UTIL + 0;
                gScope.Ctrl.IConsultaCcusto.ID                       = obj.SELECTED.CCUSTO;
                gScope.Ctrl.IConsultaCcusto.DESCRICAO                = obj.SELECTED.CCUSTO_DESCRICAO;

                obj.SELECTED.REPLICAR = 1;
                obj.REPLICAR_READONLY = true;
                obj.SELECTED.TOTAL_ITENS = 0;

                obj.consultar(id).then(function(){
                    obj.Modal.show();                
                });   
            };
            
            if ( id > 0 ) {
                obj.consultar(id).then(function(){
                    prepare();
                });
            } else {
                prepare();
            }
              
        
        }
        
        /**
         * Retorna a view da factory
         */
        function incluir() {
            obj.INCLUINDO = true;
            obj.ALTERANDO = true;
            obj.Modal.show();

            obj.selectedReset();
        }
        
        function confirmar() {
            
            if ( !(obj.SELECTED.ITENS.length > 0) ) {
                showErro('Não há componentes para esse imobilizado.');
                return false;
            }
            
            obj.SELECTED.TIPO_ID          = gScope.Ctrl.ConsultaImobilizadoTipo.ID;
            obj.SELECTED.TIPO_DESCRICAO   = gScope.Ctrl.ConsultaImobilizadoTipo.DESCRICAO;
            obj.SELECTED.TIPO_TAXA        = gScope.Ctrl.ConsultaImobilizadoTipo.TAXA_DEPRECIACAO;
            obj.SELECTED.TIPO_VIDA_UTIL   = gScope.Ctrl.ConsultaImobilizadoTipo.VIDA_UTIL;
            obj.SELECTED.CCUSTO           = gScope.Ctrl.IConsultaCcusto.ID;
            obj.SELECTED.CCUSTO_DESCRICAO = gScope.Ctrl.IConsultaCcusto.DESCRICAO;
            
            var dados = {
                DADOS : obj.SELECTED,
                FLAG  : gScope.Ctrl.Imobilizado.Flag
            };
          
            $ajax.post('/_16010/api/imobilizado/gravar',dados).then(function(response){

                obj.ALTERANDO = false;

                if ( obj.INCLUINDO ) {
                    obj.consultarTodos();
                    obj.INCLUINDO = false;
                    obj.selectedReset();
                    obj.Modal.hide();
                }

                if(gScope.Ctrl.Imobilizado.Flag == 2){
                    gScope.Ctrl.ImobilizadoItem.consultar();
                }
                
            });

        }

        function alterar() {
            
            gScope.Ctrl.ImobilizadoItem.SELECTEDS = [];
            
            gScope.Ctrl.ConsultaImobilizadoTipo.setDataRequest({});
            gScope.Ctrl.IConsultaCcusto.setDataRequest({});
            
            this.ALTERANDO = true;
            angular.copy(this.SELECTED, this.SELECTED_BACKUP);
        };        
        
        function cancelar() {
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente cancelar esta operação?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){

                        obj.ALTERANDO = false;
                        angular.extend(obj.SELECTED, obj.SELECTED_BACKUP);       
                        
                        if ( obj.INCLUINDO ) {
                            obj.INCLUINDO = false;
                            obj.selectedReset();
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
                        
                        $ajax.post('/_16010/api/imobilizado/excluir',{DADOS: obj.SELECTED}).then(function(response){

                            obj.consultarTodos();
                            obj.selectedReset();
                            obj.Modal.hide();

                        });
                    });
                }}]     
            );
        }
        
        function depreciar() {

            $ajax.post('/_16010/api/imobilizado/depreciar',{DADOS: obj.SELECTED}).then(function(response){
                gScope.Ctrl.ImobilizadoItem.consultar();
            });
        }
        
        function copiar(){
            var copia = angular.copy(this.SELECTED);

            copia.ID = undefined;
            copia.ITENS = [];

            this.SELECTED = copia;

            obj.INCLUINDO = true;
            obj.ALTERANDO = true;

            showSuccess('Copiado.');   
        }      
        

        function consultarParcelas(item) {
            
            return $q(function(resolve, reject){
                $ajax.post('/_16010/api/imobilizado/parcelas',{IMOBILIZADO_ID:obj.SELECTED.ID}).then(function(response){

                    obj.PARCELAS = response;
                    
                    
                    $('#modal-imobilizado-parcela')
                        .modal('show')
                        .one('shown.bs.modal', function(){
                            $(this).find('.table-ec').scrollTop(0);
                        })
                    ;                      
                    
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }        
        
        function selectedReset() {

            obj.SELECTED = {
                DATA_DEPRECIACAO : moment().toDate(),
                TIPO_ID: null,
                TIPO_TAXA: null,
                TIPO_VIDA_UTIL: null,
                CCUSTO: '',
                DESCRICAO : '',
                OBSERVACAO : '',
                ITENS : [],
                REPLICAR : 1,
                REPLICAR_READONLY: false
            };

            gScope.Ctrl.ImobilizadoItem.SELECTEDS = [];
            
            gScope.Ctrl.ConsultaImobilizadoTipo.setDataRequest({});
            gScope.Ctrl.ConsultaImobilizadoTipo.apagar();
            gScope.Ctrl.IConsultaCcusto.setDataRequest({});
            gScope.Ctrl.IConsultaCcusto.apagar();
        }  
        
        var Modal = {
            
            _modal : function(){
                return $('#modal-imobilizado');
            },
            show : function(shown,hidden) {

                this._modal()
                    .modal('show')
                ;                         


                this._modal()
                    .one('shown.bs.modal', function(){

                        $(this).find('input:focusable').first().focus();

                        if ( shown ) {
                            $rootScope.$apply(function(){
                                shown(); 
                            });
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
	    return Imobilizado;
	};
   
})(window, window.angular);