angular
    .module('app')
    .factory('Estoque', Estoque);
    

	Estoque.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Estoque($ajax, $httpParamSerializer, $rootScope, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Estoque(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Estoque = this; 
        
        this.DADOS     = [];
        this.SELECTED  = {};
        this.ITENS     = [];
        this.PENDENTES = [];
        this.GRUPOS    = [];
        this.FILTRO    = {};

        this.OPERADOR_BARRAS = '';
    }
    

    Estoque.prototype.pick = function(item,action) {
        
        var that = this;

        if ( item != undefined ) {
        
            this.SELECTED = item;

        }

    };     
       

    Estoque.prototype.consultar = function() {
        
        var that = this;

        var data = {};
        
        angular.copy(this.FILTRO,data);        
        
        return $q(function(resolve,reject){

            $ajax.post('/_15110/api/estoque',data)
                .then(function(response) {

                    that.merge(response);                
            
                    resolve(response);
                })
                .catch(function(e) {
                    reject(e);
                })
            ;
 
        });
    };     
       

    Estoque.prototype.merge = function(response) {
        
        sanitizeJson(response);
        
        gcCollection.merge(this.DADOS, response, [
            'ESTABELECIMENTO_ID',
            'LOCALIZACAO_ID',
            'FAMILIA_ID',
            'PRODUTO_ID',
            'TAMANHO'
        ]);
    };     

    Estoque.prototype.checkAll = function() {
                
        for ( var i in this.DADOS ) {
            var item = this.DADOS[i];
            
            this.toggleCheck(item,true);
        }
    };

    Estoque.prototype.checkVisibility = function(item) {
        
        var that = this;
        var ret  = true;
        
        for ( var i in that.GRUPOS ) {
            var grupo = that.GRUPOS[i];
            
            if ( grupo.VALOR == (item.TIPO + ' ').trim() ) {
                if ( !grupo.CHECKED ) {
                    ret = false;
                }
                break;
            }
        }
        
        return ret;

    };

    Estoque.prototype.pendencias = function() {
        var that = this;

        $ajax.post('/_15110/api/conferencia/pendentes',that).then(function(response){

            that.PENDENTES = response;

            angular.forEach(that.PENDENTES, function(item, key) {
                var adicionar = true;
                angular.forEach(that.GRUPOS, function(iten, key) {
                    if((item.TIPO + ' ').trim() == iten.VALOR){
                        adicionar = false;
                    }
                });

                if(adicionar == true){
                    that.GRUPOS.push({VALOR: (item.TIPO + ' ').trim(), CHECKED: true});
                }
            });

        });

    };

         
    
    
    Estoque.prototype.clearData = function() {
        gScope.Filtro.CODIGO_BARRAS = ''; 
        this.DADOS = []; 
        this.ITENS = [];
        
        $('.input-codigo-barras:focusable').first().focus();
    };     
    
    

    Estoque.prototype.keypress = function(item,$event) {
        
        if ( $event.key == ' ' ) {
            
            $event.preventDefault();
            
            this.toggleCheck(item);
        } else        
        if ( $event.key == 'Enter' ) {
            
            $event.preventDefault();
            
            
            if ( this.ITENS.length > 0) {
                this.modalOperador.show();
            }
        }
    };     

    Estoque.prototype.modalOperador = {
        _modal : function () {
            return $('#modal-autenticar-operador');
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
                        gScope.Estoque.OPERADOR_BARRAS = '';
                
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
    return Estoque;
};