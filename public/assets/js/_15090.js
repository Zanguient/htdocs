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
     
angular
    .module('app')
    .factory('Conferencia', Conferencia);
    

	Conferencia.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Conferencia($ajax, $httpParamSerializer, $rootScope, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Conferencia(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Conferencia = this; 
        
        this.DADOS     = [];
        this.SELECTED  = {};
        this.ITENS     = [];
        this.PENDENTES = [];
        this.PENDENTESLOTE = [];
        this.GRUPOS    = [];
        this.GRUPOS2   = [];
        this.FILTRO    = '';

        this.DATA1     = '01.10.2017';
        this.DATA2     = '26.10.2017';

        this.OPERADOR_BARRAS = '';
    }
    

    Conferencia.prototype.pick = function(item,action) {
        
        var that = this;

        if ( item != undefined ) {
        
            this.SELECTED = item;

        }

    };     
       

    Conferencia.prototype.confirmar = function() {
        
        var that = this;

        var data = {
            ITENS            : this.ITENS,
            OPERADOR_BARRAS  : this.OPERADOR_BARRAS,
            CODIGO_BARRAS    : gScope.Filtro.CODIGO_BARRAS,
            CONFERENCIA_TIPO : gScope.Filtro.CONFERENCIA_TIPO
        };
        
        $ajax.post('/_15090/api/conferencia/confirmar',data)
            .then(function(response) {
        
                that.ITENS = [];
                gScope.Filtro.merge(response.DATA_RETURN);
                that.modalOperador.hide(function(){
//                    that.clearData();
                });
                
            })
            .catch(function() {
                that.OPERADOR_BARRAS = '';
                $('#modal-autenticar-operador').find('input:focusable').first().focus();
            })
        ;

    };     
       

    Conferencia.prototype.toggleCheck = function(item,type) {
              
        if ( item.CONFERENCIA > 0 ) {  
            item.CONFERIR = (item.CONFERIR == '2') ? '1' : '2';

            if ( type != undefined ) {
                item.CONFERIR = type == true ? '2' : '1';
            }
            
            var index = this.ITENS.indexOf(item);
                
            if ( item.CONFERIR != item.CONFERENCIA ) {
                if ( index == -1 ) {
                    this.ITENS.push(item);   
                }                 
            } else {
                if ( index > -1 ) {
                    this.ITENS.splice(index, 1);                      
                }
            }
            
        }
    };     

    Conferencia.prototype.checkAll = function() {
                
        for ( var i in this.DADOS ) {
            var item = this.DADOS[i];
            
            this.toggleCheck(item,true);
        }
    };

    Conferencia.prototype.Conferir = function(COD_BARRAS,TIPO) {

        $('#tab1-tab').trigger('click');

            gScope.Filtro.CONFERENCIA_TIPO = TIPO;
            gScope.Filtro.CODIGO_BARRAS    = COD_BARRAS;
            gScope.Filtro.consultar();

    };

    Conferencia.prototype.checkVisibility = function(item) {
        
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

    Conferencia.prototype.checkVisibility2 = function(item) {
        
        var that = this;
        var ret  = true;
        
        for ( var i in that.GRUPOS2 ) {
            var grupo = that.GRUPOS2[i];
            
            if ( grupo.VALOR == (item.FAMILIA + ' ').trim() ) {
                if ( !grupo.CHECKED ) {
                    ret = false;
                }
                break;
            }
        }
        
        return ret;
    };

    Conferencia.prototype.pendencias = function() {
        var that = this;

        $ajax.post('/_15090/api/conferencia/pendentes',that).then(function(response){

//            that.PENDENTES = response;

            angular.forEach(response, function(item, key) {
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

            angular.forEach(response, function(item, key) {
                var adicionar = true;
                angular.forEach(that.GRUPOS2, function(iten, key) {
                    if((item.FAMILIA + ' ').trim() == iten.VALOR){
                        adicionar = false;
                    }
                });

                if(adicionar == true){
                    that.GRUPOS2.push({VALOR: (item.FAMILIA + ' ').trim(), CHECKED: true});
                }
            });

            var grupos = gcCollection.groupBy(response, [
                'GRUPO',
                'TIPO_ID',
                'DOCUMENTO',
                'TIPO',
                'LOCALIZACAO'
            ], 'ITENS'); 
            
            gcCollection.merge(that.PENDENTES, grupos, ['GRUPO']);
       
        });

    };

    Conferencia.prototype.pendenciasLote = function() {
        var that = this;

        $ajax.post('/_15090/api/conferencia/pendenciasLote',that).then(function(response){
            that.PENDENTESLOTE = response;
        });

    };         
    
    
    Conferencia.prototype.clearData = function() {
        gScope.Filtro.CODIGO_BARRAS = ''; 
        this.DADOS = []; 
        this.ITENS = [];
        
        $('.input-codigo-barras:focusable').first().focus();
    };     
    
    

    Conferencia.prototype.keypress = function(item,$event) {
        
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

    Conferencia.prototype.modalOperador = {
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
                        gScope.Conferencia.OPERADOR_BARRAS = '';
                
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
    return Conferencia;
};
angular
    .module('app')
    .factory('Filtro', Filtro);
    

	Filtro.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Filtro($ajax, $httpParamSerializer, $rootScope, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Filtro = this; 
        
        this.CODIGO_BARRAS = '';
    }
    
    Filtro.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve,reject){

            $ajax.post('/_15090/api/conferencia/itens',that).then(function(response){

                that.merge(response);

                resolve(response);
            },function(){
                reject(reject);
            });
        });
    };
   
    
    Filtro.prototype.merge = function(response) {
        
        function isNumber(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }

        for ( var i in response ) {
            var item = response[i];
            
            for (var k in item){
                if (item.hasOwnProperty(k)) {
                    
                    if ( isNumber(item[k]) && (String(item[k]).substr(0, 1) !== '0' || String(item[k]).indexOf('.') !== -1) ) {               
                        item[k] = parseFloat(item[k]);
                    }
                }
            }            
        }
        
        gcCollection.merge(gScope.Conferencia.DADOS, response, [
            'PRODUTO_ID',
            'TAMANHO',
            'PECA_ID'
        ]);

        for ( var i in gScope.Conferencia.DADOS ) {
            var item = gScope.Conferencia.DADOS[i];
            
            item.CONFERIR = item.CONFERENCIA;
        }

    };

    /**
     * Return the constructor function
     */
    return Filtro;
};
angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        'Filtro',
        'Conferencia'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        Filtro,
        Conferencia
    ) {

		var vm = this;

		vm.Filtro      = new Filtro();
		vm.Conferencia = new Conferencia();


	}   
  
//# sourceMappingURL=_15090.js.map
