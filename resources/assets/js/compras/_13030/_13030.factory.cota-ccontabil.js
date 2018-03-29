angular
    .module('app')
    .factory('CotaCcontabil', CotaCcontabil);
    

	CotaCcontabil.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function CotaCcontabil($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function CotaCcontabil(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = [];
        this.ITENS = [];
        this.SELECTED = {};
        this.FILTRO = '';
		gScope.CotaCcontabil = this; 
        
    }
    
    CotaCcontabil.prototype.pick = function(ccontabil,setfocus) {
        
        var that = this;

        if ( ccontabil != undefined ) {
        
            this.SELECTED = ccontabil;

            if ( setfocus ) {
                that.setFocus();
            }
        }

    };    

    /**
     * Return the constructor function
     */
    return CotaCcontabil;
};