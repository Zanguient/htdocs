angular
    .module('app')
    .factory('Tamanho', Tamanho);
    

	Tamanho.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Tamanho($ajax, $httpParamSerializer, $rootScope, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Tamanho(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Tamanho = this; 
        
        this.ITENS = [];
        this.FILTRO = '';
        this.ORDEM = 'DESCRICAO';
        this.SELECTED = [];
    }

    Tamanho.prototype.consultar = function() {
        var that = this;

        var tamanho = gScope.Modelo.ConsultaModelo.selected.TAMANHO;

        var ds = {
                MODELO : gScope.Modelo.ConsultaModelo.selected
            };

        $ajax.post('/_31010/ConsultarTamanho',ds,{contentType: 'application/json'})
            .then(function(response) {
                that.ITENS = response;  

                angular.forEach(that.ITENS, function(item, key) {
                    item.PADRAO = 0;
                    
                    if( parseInt(item.ID) == parseInt(tamanho)){
                        item.PADRAO = 1;
                        that.SELECTED = item;

                        setTimeout(function(){
                            $('.item_tamanho_'+item.ID).focus();
                        },300);
                    }
                });              
            }
        );
    }

    Tamanho.prototype.Selectionar = function (Tamanho) {
        var that = this;

        if(that.SELECTED != Tamanho){
            that.SELECTED = Tamanho;
        }
        
    }

    /**
     * Return the constructor function
     */
    return Tamanho;
};