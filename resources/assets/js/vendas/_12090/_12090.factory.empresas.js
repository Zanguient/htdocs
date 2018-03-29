angular
    .module('app')
    .factory('Empresas', Empresas);
    

	Empresas.$inject = [
        '$ajax',
        '$filter',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Empresas($ajax, $filter, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Empresas(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Empresas = this; 
          
        this.AJAX_LOCKED = true;
        this.DADOS = [];
        this.SELECTED = {};
        
        this.CONF_PAGE = {
            FIRST : 50,
            SKIP: 0
        };
        
        this.FILTRO = {
            HABILITA_CLIENTE : '1',
            STATUS : '1'
        };
        
        this.CONSULTAS = [];
        
        this.events();
    }
    
    Empresas.prototype.consultar = function(def_page) {
        
        var that = this;
        
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
            consulta.post('/_12090/api/empresas',that.FILTRO,options).then(function(response){

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
        
        
    };
        
    Empresas.prototype.getMoreData = function(empresa,setfocus) {
        
        this.FILTRO.SKIP   = this.FILTRO.SKIP || 0;
        this.FILTRO.SKIP  += this.CONF_PAGE.FIRST;
        this.FILTRO.FIRST  = this.CONF_PAGE.FIRST;
        
        this.consultar();
    };   
    
    Empresas.prototype.merge = function(response,def_page) {
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

        response = $filter('orderBy')(response,'EMPRESA_RAZAO_SOCIAL');
        
        var preserve_main = def_page == true ? false : true;
        gcCollection.merge(this.DADOS, response, 'EMPRESA_ID',preserve_main);     
        
        
    };
    
    
    
    Empresas.prototype.emptyData = function(newvalue,oldvalue) {
        
        this.AJAX_LOCKED = true;

        for ( var i in this.CONSULTAS ) {
            var consulta = this.CONSULTAS[i];

            consulta.abort();
        }

        this.CONSULTAS = [];

        this.DADOS = [];
    };     
    
    
    
    Empresas.prototype.virifyChange = function(newvalue,oldvalue) {
        
        if ( newvalue.toUpperCase() != oldvalue.toUpperCase() ) {
            
            this.emptyData();
        }
    };     

    
    Empresas.prototype.pick = function(empresa,setfocus) {
        
        
        if ( this.SELECTED != empresa && empresa != undefined ) {
            
            this.SELECTED = empresa;
            gScope.Empresa.SELECTED = this.SELECTED;

            if ( setfocus ) {
                this.setFocus();
            }
        }
    };  

         
    
    Empresas.prototype.dblPick = function(item,action) {
        
        var that = this;

        if ( item != undefined ) {
        
            this.SELECTED = item;
            
            gScope.Filtro.COTA_ID = item.ID;
            gScope.Filtro.COTA_OPEN = 1;
            gScope.Filtro.uriHistory();
            
            that.consultar();
            that.ModalShow(null,function(){   
                that.ALTERANDO = false;
                delete gScope.Filtro.COTA_OPEN;
                gScope.Filtro.uriHistory();
            });

        }

    };    

    
    var modal = $('#modal-empresa');
    
    Empresas.prototype.ModalShow = function(shown,hidden) {

        modal
            .modal('show')
        ;                         
        
        if ( shown ) {
            modal
                .one('shown.bs.modal', function(){
                    shown();
                })
            ;     
        }
        
        if ( hidden ) {
            modal
                .one('hidden.bs.modal', function(){
                    hidden();
                })
            ;              
        }
    };

    Empresas.prototype.ModalClose = function(hidden) {

        modal
            .modal('hide')
        ;
        
        if ( hidden ) {
            modal
                .one('hidden.bs.modal', function(){
                    hidden ? hidden() : '';
                })
            ;                      
        }
    };
    
    Empresas.prototype.inputKeydown = function($event) {
         
        if ( this.PESO_AUTOMATICO ) {
            if ( isNumber($event.key) || $event.key == 'Backspace' || $event.key == 'Delete' ) {
                $event.preventDefault();
            }
        }
    };
    
    Empresas.prototype.events = function($event) {
//        var that = this;
//        var cancel_bf_unload = false;
//        //
//        $(document).on('click','[type="submit"]',function(e) {
//            var form = $(this).closest('form');
//            var action = $(form).attr('action') == undefined ? '' : $(form).attr('action');
//
//            if ( action != '' ) {
//                cancel_bf_unload = true;
//            }
//        });
//
//        var bf_load_timeout;
//
//        function warning() {
//            if ( that.ALTERANDO && cancel_bf_unload == false ) {
//                return 'oi';
//            }
//        }
//
//        function noTimeout() {
//            clearTimeout(bf_load_timeout);
//        }
//
//        window.onbeforeunload = warning;
//        window.unload = noTimeout;         
         
    };
        
    
    /**
     * Return the constructor function
     */
    return Empresas;
};