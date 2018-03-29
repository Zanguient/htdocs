angular
    .module('app')
    .factory('Produto', Produto);
    

	Produto.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        '$q',
        'gcCollection',
        'gScope'
    ];

function Produto($ajax, $rootScope, $timeout, $q, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Produto(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Produto = this; 
        
        this.DADOS = [];
        this.PRODUTOS = [];
        this.LOCALIZACOES = [];
        this.FAMILIAS = [];
        this.FILTRO = '';
    }
    
    this.GUIA_ATIVA = 'TALAO_PRODUZIR';
    
    /**
     * Private property
     */
    var url_base        = '_15070/api';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */



    Produto.prototype.filtrarMaiorQueZero = function(produto) {
        
        var that = this;

        var ret = false;
        
        if ( gScope.Filtro.NECESSIDADE == 'maior-que-zero' ) {
            if ( produto.ESTOQUE_NECESSIDADE > 0 ) {
                ret = true;
            }
        } else {
            ret = true;
        }
        
        return ret;

    };

    Produto.prototype.checkVisibility = function(produto) {
        
        var that = this;
        var ret  = true;
        
        for ( var i in that.FAMILIAS ) {
            var familia = that.FAMILIAS[i];
            
            if ( familia.FAMILIA_ID == produto.FAMILIA_ID ) {
                if ( !familia.CHECKED ) {
                    ret = false;
                }
                break;
            }
        }
        
        return ret;

    };

    Produto.prototype.pick = function(produto,setfocus) {
        
        var that = this;

        if ( produto != undefined ) {
        
            this.SELECTED = produto;

            if ( setfocus ) {
                that.setFocus();
            }
        }

    };
    
    Produto.prototype.setFocus = function() {
        
        $timeout(function(){
            $('.table-container.table-produtos .table-lc-body tr.selected:focusable').focus();
        },50);                      
    };
    
    
    Produto.prototype.keypress = function($event) {

        $event.preventDefault();
         
        
        switch ($event.key) {

            case 'Enter':

                gScope.Reposicao.Modal.open();

                break;

        }
    };
    
        
    /**
     * Extende propriedades para o objeto
     * @param {object} data
     * @returns {void}
     */
    Produto.prototype.setData = function(data) {
            angular.extend(this, data);
    };


    /**
     * Return the constructor function
     */
    return Produto;
};