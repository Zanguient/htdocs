(function(window, angular) {
    'use strict';

    angular
        .module('app')
        .factory('DemonstrativoDepreciacaoAnual', DemonstrativoDepreciacaoAnual);

	DemonstrativoDepreciacaoAnual.$inject = [
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

	function DemonstrativoDepreciacaoAnual($ajax, $q, $rootScope, $compile,$timeout, $consulta, gScope, gcCollection, gcObject) {

        // Private variables.
        var obj = null;
        var selected = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function DemonstrativoDepreciacaoAnual() {
            
            obj = this; 

            // Public methods         
            this.consultar     = consultar;
            this.merge         = merge;
          
            this.Modal         = Modal; 
            
                   
            this.DADOS = [];
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
            
	    }
        
        
        

        function consultar() {
            
            return $q(function(resolve, reject){
                $ajax.post('/_16010/api/demonstratitvo-depreciacao',{
                    MES_1: obj.MES_1,
                    MES_2: obj.MES_2,
                    ANO_1: obj.ANO_1,
                    ANO_2: obj.ANO_2
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
            
            
            gcCollection.merge(obj.DADOS, response, ['CCUSTO','IMOBILIZADO_ID','TIPO_ID','MES','ANO']);
            
            obj.TOTAL_GERAL = 0;
            
            var meses = gcCollection.groupBy(obj.DADOS,['MES','MES_DESCRICAO','ANO'],'IMOBILIZADOS',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
                
                obj.TOTAL_GERAL += item.VALOR;
            });

            gcCollection.merge(obj.MESES, meses, ['MES','ANO']);
            
            
            
            
            // VISÃO POR CENTRO DE CUSTO/TIPO
            
            var ccustos_tipos_meses = gcCollection.groupBy(obj.DADOS,['CCUSTO','CCUSTO_MASK','CCUSTO_DESCRICAO', 'TIPO_ID','TIPO_DESCRICAO','MES','MES_DESCRICAO','ANO'],'IMOBILIZADOS',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
                
            });

            gcCollection.merge(obj.CCUSTOS_TIPOS_MESES, ccustos_tipos_meses, ['CCUSTO','TIPO_ID','MES','ANO']);
            
            //
            
            var ccustos_meses = gcCollection.groupBy(obj.DADOS,['CCUSTO','MES_DESCRICAO','MES','ANO'],'IMOBILIZADOS',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
                
            });

            gcCollection.merge(obj.CCUSTOS_MESES, ccustos_meses, ['CCUSTO','MES','ANO']);
            
            //
            
            
            var ccustos_tipos = gcCollection.groupBy(obj.CCUSTOS_TIPOS_MESES,['CCUSTO','CCUSTO_MASK','CCUSTO_DESCRICAO', 'TIPO_ID','TIPO_DESCRICAO'],'MESES',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
            });

            gcCollection.merge(obj.CCUSTOS_TIPOS, ccustos_tipos, ['CCUSTO','TIPO_ID']);
            
            //
            
            var ccustos = gcCollection.groupBy(obj.CCUSTOS_TIPOS,['CCUSTO','CCUSTO_MASK','CCUSTO_DESCRICAO'],'TIPOS',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
            });

            gcCollection.merge(obj.CCUSTOS, ccustos, 'CCUSTO');
            
            gcCollection.bind(obj.CCUSTOS, obj.CCUSTOS_MESES, 'CCUSTO','MESES');
            
            
            
            
            
            
            
            // VISÃO POR TIPO/CCUSTO
            
            var tipos_ccustos_meses = gcCollection.groupBy(obj.DADOS,['CCUSTO','CCUSTO_MASK','CCUSTO_DESCRICAO', 'TIPO_ID','TIPO_DESCRICAO','MES','MES_DESCRICAO','ANO'],'IMOBILIZADOS',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
                
            });

            gcCollection.merge(obj.TIPOS_CCUSTOS_MESES, tipos_ccustos_meses, ['CCUSTO','TIPO_ID','MES','ANO']);
            
            //
            
            var tipos_meses = gcCollection.groupBy(obj.DADOS,['TIPO_ID','TIPO_DESCRICAO','MES','ANO'],'IMOBILIZADOS',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
                
            });

            gcCollection.merge(obj.TIPOS_MESES, tipos_meses, ['TIPO_ID','MES','ANO']);
            
            //
            
            
            var tipos_ccustos = gcCollection.groupBy(obj.CCUSTOS_TIPOS_MESES,['CCUSTO','CCUSTO_MASK','CCUSTO_DESCRICAO', 'TIPO_ID','TIPO_DESCRICAO'],'MESES',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
            });

            gcCollection.merge(obj.TIPOS_CCUSTOS, tipos_ccustos, ['CCUSTO','TIPO_ID']);
            
            //
            
            var tipos = gcCollection.groupBy(obj.TIPOS_CCUSTOS,['TIPO_ID','TIPO_DESCRICAO'],'CCUSTOS',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
            });

            gcCollection.merge(obj.TIPOS, tipos, ['TIPO_ID']);
            
            gcCollection.bind(obj.TIPOS, obj.TIPOS_MESES, 'TIPO_ID','MESES');
            
            
            
            
            
            
            
            // VISAÕ GERAL
            
            
            var tipos_meses = gcCollection.groupBy(obj.DADOS,[ 'TIPO_ID','TIPO_DESCRICAO','MES','MES_DESCRICAO','ANO'],'IMOBILIZADOS',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
                
            });

            gcCollection.merge(obj.GERAL_MESES, tipos_meses, ['TIPO_ID','MES','ANO']);
            
            //
            
            
            var tipos = gcCollection.groupBy(obj.GERAL_MESES,['TIPO_ID','TIPO_DESCRICAO'],'MESES',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
            });

            gcCollection.merge(obj.GERAL, tipos, ['TIPO_ID']);
            
            //
            
            
        }
        

                
        
        var Modal = {
            
            _modal : function(){
                return $('#modal-demonstratitvo-depreciacao-anual');
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
	    return DemonstrativoDepreciacaoAnual;
	};
   
})(window, window.angular);