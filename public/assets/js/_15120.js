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

        this.TOTAL = {
                SALDO : 0,
                SALDO_DETERCEIRO : 0,
                SALDO_EMTERCEIRO : 0,
                SALDO_REVISAO : 0,
                SALDO_ESTRAGADO : 0,
                OC : 0,
                ESTOQUE_MINIMO : 0,
                ALOCADO: 0
            };

    }

    Estoque.prototype.pick = function(item,action) {
        
        var that = this;

        if ( item != undefined ) {
        
            this.SELECTED = item;

        }

    };

    Estoque.prototype.CSV = function(){
        exportTableToCsv('Estoque_Familia.csv', 'tabela-estoque');
    };

    Estoque.prototype.XLS = function(){
        exportTableToXls('Estoque_Familia.xls', 'tabela-estoque');
    };

    Estoque.prototype.IMPRIMIR = function(){
        var user = $('#usuario-descricao').val();
        var filtro = 'Família:' + gScope.Consulta_Familia.selected.DESCRICAO + '   Filtro:' + this.FILTRO.FILTRO + ' Qtd. Reg.:' + this.FILTRO.FIRST;
        printHtml('pai-tabela-estoque', 'Consulta Resumida de Estoque Por Família', filtro, user, '1.0.0',1,'');
    }; 

    Estoque.prototype.consultar = function() {
        
        var that = this;

        var data = {};
        
        angular.copy(this.FILTRO,data);

        data.FAMILIA = gScope.Consulta_Familia.item.dados;  
        data.SALDO   = gScope.SALDO.STATUS;

        that.DADOS = [];

        that.TOTAL.SALDO            = 0; 
        that.TOTAL.SALDO_DETERCEIRO = 0; 
        that.TOTAL.SALDO_EMTERCEIRO = 0; 
        that.TOTAL.SALDO_REVISAO    = 0;  
        that.TOTAL.SALDO_ESTRAGADO  = 0;  
        that.TOTAL.OC               = 0; 
        that.TOTAL.ESTOQUE_MINIMO   = 0; 
        that.TOTAL.ALOCADO          = 0;     
        
        return $q(function(resolve,reject){

            $ajax.post('/_15120/api/estoque',data)
                .then(function(response) {

                    angular.forEach(response, function(value, key) {

                        var SALDO            = Number(value.SALDO); 
                        var SALDO_DETERCEIRO = Number(value.SALDO_DETERCEIRO); 
                        var SALDO_EMTERCEIRO = Number(value.SALDO_EMTERCEIRO); 
                        var SALDO_REVISAO    = Number(value.SALDO_REVISAO); 
                        var SALDO_ESTRAGADO  = Number(value.SALDO_ESTRAGADO); 
                        var OC               = Number(value.OC); 
                        var ESTOQUE_MINIMO   = Number(value.ESTOQUE_MINIMO); 
                        var ALOCADO          = Number(value.ALOCADO);

                        that.TOTAL.SALDO            = Number(that.TOTAL.SALDO            ) + SALDO; 
                        that.TOTAL.SALDO_DETERCEIRO = Number(that.TOTAL.SALDO_DETERCEIRO ) + SALDO_DETERCEIRO; 
                        that.TOTAL.SALDO_EMTERCEIRO = Number(that.TOTAL.SALDO_EMTERCEIRO ) + SALDO_EMTERCEIRO; 
                        that.TOTAL.SALDO_REVISAO    = Number(that.TOTAL.SALDO_REVISAO    ) + SALDO_REVISAO; 
                        that.TOTAL.SALDO_ESTRAGADO  = Number(that.TOTAL.SALDO_ESTRAGADO  ) + SALDO_ESTRAGADO; 
                        that.TOTAL.OC               = Number(that.TOTAL.OC               ) + OC; 
                        that.TOTAL.ESTOQUE_MINIMO   = Number(that.TOTAL.ESTOQUE_MINIMO   ) + ESTOQUE_MINIMO; 
                        that.TOTAL.ALOCADO          = Number(that.TOTAL.ALOCADO          ) + ALOCADO;

                    });

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

        $ajax.post('/_15120/api/conferencia/pendentes',that).then(function(response){

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
angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        'Estoque',
        '$consulta',
        'gScope'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        Estoque,
        $consulta,
        gScope
    ) {

		var vm = this;

		vm.Estoque = new Estoque();
        vm.SALDO = {STATUS : true};

        vm.Consulta = new $consulta();
        vm.Consulta_Familia  = vm.Consulta.getNew();

        vm.Consulta_Familia.componente              = '.famila-estoque',
        vm.Consulta_Familia.model                   = 'vm.Consulta_Familia',
        vm.Consulta_Familia.option.label_descricao  = 'Família:',
        vm.Consulta_Familia.option.obj_consulta     = '/_15120/api/familia',
        vm.Consulta_Familia.option.tamanho_input    = 'input-medio';
        vm.Consulta_Familia.option.class            = 'Consulta_Familia';
        vm.Consulta_Familia.option.tamanho_tabela   = 200;
        vm.Consulta_Familia.option.required         = false;

        vm.Consulta_Familia.compile();

        gScope.Consulta_Familia = vm.Consulta_Familia; 
        gScope.SALDO = vm.SALDO;


	}   
  
//# sourceMappingURL=_15120.js.map
