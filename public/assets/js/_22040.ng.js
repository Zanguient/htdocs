/**
 * _22010 - Registro de Produção
 */
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
    .factory('Reposicao', Reposicao);
    

	Reposicao.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Reposicao($ajax, $httpParamSerializer, $rootScope, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Reposicao(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Reposicao = this; 
        
        this.DADOS     = [];
        this.DETALHAMENTO     = [];
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
    

    Reposicao.prototype.pick = function(item,action) {
        
        var that = this;

        if ( item != undefined ) {
        
            this.SELECTED = item;

        }

    };     
       

    Reposicao.prototype.consultarOrigem = function(produto_id,tamanho) {
        
        var that = this;

        var data = {
            ESTABELECIMENTO_ID  : gScope.DADOS.estabelecimento_id,
            FAMILIA_ID          : gScope.DADOS.familia,
            PRODUTO_ID          : produto_id,
            TAMANHO             : tamanho
        };
        
        that.DATA = data; 
        
        $ajax.post('/_22040/api/reposicao',data)
            .then(function(response) {
        
                that.DADOS = response;
        
                $('#modal-reposicao-origem').modal('show');
                
            })
        ;

    };     
       

    Reposicao.prototype.consultarDetalhamento = function(tipo) {
        
        var that = this;

        that.ORIGEM_TIPO = tipo;
        
        $ajax.post('/_22040/api/reposicao/'+tipo,that.DATA)
            .then(function(response) {
        
                that.DETALHAMENTO = response;
                        
            })
        ;

    };     
       

    Reposicao.prototype.toggleCheck = function(item,type) {
              
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

    Reposicao.prototype.checkAll = function() {
                
        for ( var i in this.DADOS ) {
            var item = this.DADOS[i];
            
            this.toggleCheck(item,true);
        }
    };

    Reposicao.prototype.Conferir = function(COD_BARRAS,TIPO) {

        $('#tab1-tab').trigger('click');

            gScope.Filtro.CONFERENCIA_TIPO = TIPO;
            gScope.Filtro.CODIGO_BARRAS    = COD_BARRAS;
            gScope.Filtro.consultar();

    };

    Reposicao.prototype.checkVisibility = function(item) {
        
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

    Reposicao.prototype.checkVisibility2 = function(item) {
        
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

    Reposicao.prototype.pendencias = function() {
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

    Reposicao.prototype.pendenciasLote = function() {
        var that = this;

        $ajax.post('/_15090/api/conferencia/pendenciasLote',that).then(function(response){
            that.PENDENTESLOTE = response;
        });

    };         
    
    
    Reposicao.prototype.clearData = function() {
        gScope.Filtro.CODIGO_BARRAS = ''; 
        this.DADOS = []; 
        this.ITENS = [];
        
        $('.input-codigo-barras:focusable').first().focus();
    };     
    
    

    Reposicao.prototype.keypress = function(item,$event) {
        
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

    Reposicao.prototype.modalOperador = {
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
                        gScope.Reposicao.OPERADOR_BARRAS = '';
                
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
    return Reposicao;
};
angular
    .module('app')
    .value('gScope', {
        indexOfAttr : function(array,attr, value) {
            for(var i in array) {
                if(array[i][attr] === value) {
                    return i;
                }
            }
            return -1;
        }
    })
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$compile',
        '$timeout',
        'gScope',
        'Reposicao'
    ];

	function Ctrl( $scope, $compile, $timeout, gScope, Reposicao ) {

		var vm = this;

        vm.Reposicao         = new Reposicao();
        vm.gScope            = gScope;
	}   
    
//# sourceMappingURL=_22040.ng.js.map
