angular
    .module('app')
    .factory('Talao', Talao);
    

	Talao.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Talao($ajax, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Talao(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Talao = this; 
        
        this.DADOS = [];
        this.SELECTED = {};
    }
        
    Talao.prototype.pick = function(talao,setfocus) {
        
        var that = this;

        if ( talao != undefined ) {

            if ( talao != this.SELECTED ) {
                gScope.Consumo.pick({});
            }   

            this.SELECTED       = talao;

            if ( setfocus ) {
                that.setFocus();
            }
        }

    };
    
    Talao.prototype.setFocus = function() {
        
        $timeout(function(){
            $('.table-talao.table-lc-body tr.selected:focusable').focus();
        },50);                      
    };
    
    /**
     * Extende propriedades para o objeto
     * @param {object} data
     * @returns {void}
     */
    Talao.prototype.setData = function(data) {
            angular.extend(this, data);
    };

    /**
     * Return the constructor function
     */
    return Talao;
};