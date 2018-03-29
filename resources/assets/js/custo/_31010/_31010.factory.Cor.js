angular
    .module('app')
    .factory('Cor', Cor);
    

	Cor.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Cor($ajax, $httpParamSerializer, $rootScope, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Cor(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Cor = this; 
        
        this.ITENS = [];
        this.FILTRO = '';
        this.ORDEM = 'DESCRICAO';
        this.SELECTED = [];
    }

    Cor.prototype.consultar = function() {
        var that = this;

        var cor = gScope.Modelo.ConsultaModelo.selected.COR_ID;

        var ds  = {
                MODELO : gScope.Modelo.ConsultaModelo.selected
            };

        $ajax.post('/_31010/ConsultarCor',ds,{contentType: 'application/json'})
            .then(function(response) {
                that.ITENS = response;

                angular.forEach(that.ITENS, function(item, key) {
                    item.PADRAO = 0;
                    
                    if( parseInt(item.ID) == parseInt(cor)){
                        item.PADRAO = 1;
                        that.SELECTED = item;

                        setTimeout(function(){
                            $('.item_modelo_'+item.ID).focus();
                        },300);
                    }
                });               
            }
        );
    }

    Cor.prototype.Selectionar = function (cor) {
        var that = this;

        if(that.SELECTED != cor){
            that.SELECTED = cor;
        }
        
    }

    /**
     * Return the constructor function
     */
    return Cor;
};