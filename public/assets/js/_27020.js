'use strict';

angular
	.module('app', [
		'vs-repeat', 
        'gc-find',
		'gc-ajax',
		'gc-transform',
		'gc-form',
		'gc-utils',
        'infinite-scroll'
	])
;
     
angular
    .module('app')
    .factory('Filtro', Filtro);
    

	Filtro.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        '$q',
        'gcCollection',
        'gScope'
    ];

function Filtro($ajax, $httpParamSerializer, $rootScope, $timeout, $q, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }
        
        
//        this.DATA_1 = new Date(Clock.DATETIME_SERVER);
//        this.DATA_2 = new Date(Clock.DATETIME_SERVER);

		gScope.Filtro = this; 
        
    }
    
    Filtro.prototype.consultar = function(progress) {
        
        var that = this;
        
        return $q(function(resolve){
    //        loading('.main-ctrl');     

//            this.DATAHORA = {
//                DATAHORA_1 : moment(this.DATA_1).format('YYYY.MM.DD 00:00:00'),
//                DATAHORA_2 : moment(this.DATA_2).format('YYYY.MM.DD 23:59:59')
//            };

            $ajax.post('/_13030/api/cotas',that,{progress: progress == undefined ? false : progress}).then(function(response){

                that.merge(response);
                resolve(response);
    //            loading('hide');

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

        gcCollection.merge(gScope.Cota.DADOS, response, 'ID');      
        
        
        
        /**
         * Vinculo dos ConsumoBaixadoProdutos - Incio
         */
        var periodos = gcCollection.groupBy(gScope.Cota.DADOS, [
            'CCUSTO',
            'CCUSTO_MASK',
            'CCUSTO_DESCRICAO',
            'MES',
            'ANO',
            'PERIODO_DESCRICAO'
        ], 'CCONTABEIS',function(periodo,ccontabil){

            periodo.VALOR     == undefined ? periodo.VALOR     = 0 : '';
            periodo.EXTRA     == undefined ? periodo.EXTRA     = 0 : '';
            periodo.TOTAL     == undefined ? periodo.TOTAL     = 0 : '';
            periodo.OUTROS    == undefined ? periodo.OUTROS    = 0 : '';
            periodo.UTIL      == undefined ? periodo.UTIL      = 0 : '';
//            periodo.PERC_UTIL == undefined ? periodo.PERC_UTIL = 0 : '';
            periodo.SALDO     == undefined ? periodo.SALDO     = 0 : '';

            periodo.VALOR     += ccontabil.VALOR    ;
            periodo.EXTRA     += ccontabil.EXTRA    ;
            periodo.TOTAL     += ccontabil.TOTAL    ;
            periodo.OUTROS    += ccontabil.OUTROS   ;
            periodo.UTIL      += ccontabil.UTIL     ;
//            periodo.PERC_UTIL += ccontabil.PERC_UTIL;
            periodo.SALDO     += ccontabil.SALDO    ;        


            if ( periodo.TOTAL > 0 ) {
                periodo.PERC_UTIL = ((1-(periodo.SALDO/periodo.TOTAL))*100);
            } else {
                if ( periodo.TOTAL == 0 && periodo.SALDO < 0 ) {
                    periodo.PERC_UTIL = 100;
                } else {
                    periodo.PERC_UTIL = 0;  
                }
            }

//            if ( (periodo.VALOR + periodo.EXTRA) = 0 && periodo.SALDO )
//            IIF(A.VALOR+A.EXTRA = 0 AND A.SALDO < 0, 100, 0))

        });
        
        gcCollection.merge(gScope.CotaPeriodo.DADOS, periodos, ['CCUSTO','MES','ANO']);
        
        /////
        
        
        /**
         * Vinculo dos ConsumoBaixadoProdutos - Incio
         */
        var ccustos = gcCollection.groupBy(gScope.CotaPeriodo.DADOS, [
            'CCUSTO',
            'CCUSTO_MASK',
            'CCUSTO_DESCRICAO'
        ], 'PERIODOS',function(ccusto,periodo){

            ccusto.VALOR     == undefined ? ccusto.VALOR     = 0 : '';
            ccusto.EXTRA     == undefined ? ccusto.EXTRA     = 0 : '';
            ccusto.TOTAL     == undefined ? ccusto.TOTAL     = 0 : '';
            ccusto.OUTROS    == undefined ? ccusto.OUTROS    = 0 : '';
            ccusto.UTIL      == undefined ? ccusto.UTIL      = 0 : '';
//            ccusto.PERC_UTIL == undefined ? ccusto.PERC_UTIL = 0 : '';
            ccusto.SALDO     == undefined ? ccusto.SALDO     = 0 : '';     

            ccusto.VALOR     += periodo.VALOR    ;
            ccusto.EXTRA     += periodo.EXTRA    ;
            ccusto.TOTAL     += periodo.TOTAL    ;
            ccusto.OUTROS    += periodo.OUTROS   ;
            ccusto.UTIL      += periodo.UTIL     ;
//            ccusto.PERC_UTIL += periodo.PERC_UTIL;
            ccusto.SALDO     += periodo.SALDO    ;        

            if ( ccusto.TOTAL > 0 ) {
                ccusto.PERC_UTIL = ((1-(ccusto.SALDO/ccusto.TOTAL))*100);
            } else {
                if ( ccusto.TOTAL == 0 && ccusto.SALDO < 0 ) {
                    ccusto.PERC_UTIL = 100;
                } else {
                    ccusto.PERC_UTIL = 0;  
                }
            }
        });
        
        gcCollection.merge(gScope.CotaCcusto.DADOS, ccustos, ['CCUSTO','MES','ANO','CCONTABIL']);
        
        /////
                
        
//        console.log(gScope.CotaCcusto.DADOS);
    };
    
    Filtro.prototype.uriHistory = function() { 
        window.history.replaceState('', '', encodeURI(urlhost + '/_13030/ng?'+$httpParamSerializer(this)));        
    };    

    /**
     * Return the constructor function
     */
    return Filtro;
};
(function(window, angular) {
    'use strict';

    angular
        .module('app')
        .factory('Modelo', Modelo);

	Modelo.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$filter',
        '$timeout',
        '$consulta',
        'gScope',
        'gcCollection',
        'gcObject'
    ];

	function Modelo($ajax, $q, $rootScope, $filter,$timeout, $consulta,gScope, gcCollection, gcObject) {

        // Private variables.
        var that = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function Modelo() {
            
            that = this; 

            // Public methods         
            this.consultar     = consultar; 
            this.consultarMais = consultarMais; 
            this.merge         = merge;
            this.emptyData     = emptyData;
            this.virifyChange  = virifyChange;
            this.viewPdf       = viewPdf;
            this.Modal         = Modal; 
            
                  

            this.CONF_PAGE = {
                FIRST : 50,
                SKIP: 0
            };
            
            this.FILTRO = {
                STATUS : '1',
                GET_FILES : true
            };
            this.CONSULTAS = [];
            this.AJAX_LOCKED = false;
            this.DADOS = [];
            this.SELECTED = {};
            this.SELECTED_BACKUP = {};
            this.TIPOS = [];
            this.INCLUINDO = false;
            this.ALTERANDO = false;
            
	    }
        
        
        function consultar(def_page) {
            
            

            var options = {};

            if ( def_page ) {
                angular.extend(that.FILTRO,that.CONF_PAGE);
            } else {
                options.progress = false;            
            }

            that.AJAX_LOCKED = true;
            var consulta = $ajax;

            that.CONSULTAS.push(consulta);

            return $q(function(resolve,reject){
                consulta.post('/_27020/api/modelos',that.FILTRO,options).then(function(response){

                    that.merge(response,def_page);

                    if ( def_page ) {
                        $('.table-ec').scrollTop(0);                
                    }

                    if ( response.length >= that.CONF_PAGE.FIRST ) {
                        that.AJAX_LOCKED = false;
                    }
                    resolve(response);
                },function(e){
                    reject(e);
                });            
            });    
        }        
          
        
        function consultarMais() {

            that.FILTRO.SKIP   = that.FILTRO.SKIP || 0;
            that.FILTRO.SKIP  += that.CONF_PAGE.FIRST;
            that.FILTRO.FIRST  = that.CONF_PAGE.FIRST;

            that.consultar();
        }        
          
        
        function merge(response,def_page) {

            sanitizeJson(response);

            response = $filter('orderBy')(response,'DESCRICAO');
            
            for ( var i in response ) {
                var modelo = response[i];
                
                for ( var j in modelo.FILES ) {
                    var file = modelo.FILES[j];
                    
                    if ( file.SEQUENCIA == '999' ) {
                        modelo.PDF_FICHA = file.ID;
                        
                        var idx = modelo.FILES.indexOf(file);
                        
                        modelo.FILES.splice(idx,1);
                        
                        break;
                    }
                }
            }

            var preserve_main = def_page == true ? false : true;
            gcCollection.merge(this.DADOS, response, 'ID',preserve_main);     

        }        
        
    
        function emptyData (newvalue,oldvalue) {

            that.AJAX_LOCKED = true;

            for ( var i in that.CONSULTAS ) {
                var consulta = that.CONSULTAS[i];

                consulta.abort();
            }

            that.CONSULTAS = [];

            that.DADOS = [];
        };     
    
    
        function virifyChange (newvalue,oldvalue) {

            if ( newvalue.toUpperCase() != oldvalue.toUpperCase() ) {

                that.emptyData();
            }
        };       
        
    
        function viewPdf (id) {


            $ajax.get('/_27020/api/consultar-arquivo-conteudo/'+id).then(function(response){

                if (response) {
                    printPdf(response);
                }
            });
        };       
        
        
        var Modal = {
            
            _modal : function(){
                return $('#modal-modelo');
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
	    return Modelo;
	};
   
})(window, window.angular);
angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        '$consulta',
        'gScope',
        'Filtro',
        'Modelo',
        'Historico'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        $consulta,
        gScope, 
        Filtro, 
        Modelo,
        Historico
    ) {

		var vm = this;

		vm.Filtro    = new Filtro();
		vm.Modelo    = new Modelo();
		vm.Historico = new Historico();
        
        vm.Consulta  = new $consulta();
        
        vm.Modelo.consultar(true);

//        $timeout(function(){
//            vm.Filtro.consultar().then(function(){
//
//                loading('hide');
//                $timeout(function(){
//                    if ( vm.Filtro.COTA_ID > 0 ) {
//                        var cota = $('[data-cota-id="' + vm.Filtro.COTA_ID + '"]:focusable');
//
//                        cota.focus();
//
//                        $timeout(function(){
//                            if ( vm.Filtro.COTA_OPEN == 1 && gScope.Cota.SELECTED.ID != undefined ) {
//                                vm.Cota.dblPick(vm.Cota.SELECTED);
//                            }
//                        },100);
//                    }
//                },50);
//
//            });
//
//        },50);
//        
	}   
  
//# sourceMappingURL=_27020.js.map
