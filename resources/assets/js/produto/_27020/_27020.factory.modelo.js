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