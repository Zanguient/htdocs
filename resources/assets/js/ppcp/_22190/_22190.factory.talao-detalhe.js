angular
    .module('app')
    .factory('TalaoDetalhe', TalaoDetalhe);
    

	TalaoDetalhe.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function TalaoDetalhe($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function TalaoDetalhe(data) {
        if (data) {
            this.setData(data);
        }

		gScope.TalaoDetalhe = this; 
        
        this.DADOS = [];
        this.SELECTED = {};
        this.SELECTEDS = [];
        this.SELECTEDS_PRODUZIR = [];
    }
    
    TalaoDetalhe.prototype.checkAll = function() {
        
        var taloes = gScope.Talao.SELECTED.DETALHES;
        for ( var i in taloes ) {
            var detalhe = taloes[i];
            
            this.SELECTEDS.push(detalhe);
            
            if ( detalhe.TALAO_DETALHE_STATUS < 2 ) {
                this.SELECTEDS_PRODUZIR.push(detalhe);
            }            
        }
    };
    
    TalaoDetalhe.prototype.uncheckAll = function() {
        this.SELECTEDS = [];
        this.SELECTEDS_PRODUZIR = [];
    };
   
    


    TalaoDetalhe.prototype.pick = function(item,action) {

        
        
        var that = this;
        
        var idx = this.SELECTEDS.indexOf(item);
        var idx_prod = this.SELECTEDS_PRODUZIR.indexOf(item);
        
        if ( idx == -1 ) {
            this.SELECTEDS.push(item);


            if ( item.TALAO_DETALHE_STATUS < 2 ) {
                this.SELECTEDS_PRODUZIR.push(item);
            }
            
        } else {
            this.SELECTEDS.splice(idx,1);
        }
        
        if ( idx_prod > -1 ) {
            this.SELECTEDS_PRODUZIR.splice(idx_prod,1);
        }
        
    };    


    TalaoDetalhe.prototype.confirm = function () {
        var that = this;

        var dados = {
            FILTRO: gScope.ConsumoBaixarFiltro,
            DADOS: {
                ITENS : that.ITENS_BAIXAR,
                PESO : that.PESO
            }
        };
        
        
        that.enableButton(false);
        
        $ajax.post('/_22160/api/consumo-baixar/post',dados,{complete: function(){
                
            that.enableButton(true);
            
        }}).then(function(response){
        
            postprint(response.ETIQUETAS);        
        
            gScope.ConsumoBaixarFiltro.merge(response.DATA_RETURN);
            that.close();
            
        });        
    };  

    TalaoDetalhe.prototype.setItens = function () {
        
        this.ITENS_BAIXAR = [];
        var array = this.ITENS_BAIXAR;
        
        if ( this.SELECTED.FILTERED == undefined ) {
            array.push(this.SELECTED);
        } else {
            
            var quantidade = 0;
            for ( var i in this.SELECTED.FILTERED ) {
                
                var item = this.SELECTED.FILTERED[i];
                
                quantidade += item.QUANTIDADE_SALDO;
                
                if ( quantidade <= (this.PESO + item.QUANTIDADE_SALDO) ) {
                    array.push(item);                    
                } else {
                    break;
                }
            }
        }
    };  

    TalaoDetalhe.prototype.inputKeydown = function($event) {
         
        if ( this.PESO_AUTOMATICO ) {
            if ( isNumber($event.key) || $event.key == 'Backspace' || $event.key == 'Delete' ) {
                $event.preventDefault();
            }
        }
    };
    


    /**
     * Return the constructor function
     */
    return TalaoDetalhe;
};