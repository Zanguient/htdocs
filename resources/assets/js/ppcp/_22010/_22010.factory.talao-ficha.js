angular
    .module('app')
    .factory('TalaoFicha', TalaoFicha);
    

	TalaoFicha.$inject = [
        '$ajax',
        '$timeout',
        '$q',
        'gScope',
        'gcCollection'
    ];

function TalaoFicha($ajax,$timeout,$q,gScope,gcCollection) {

    /**
     * Constructor, with class name
     */
    function TalaoFicha(data) {
        if (data) {
            this.setData(data);
        }
    }
    
    /**
     * Private property
     */
    var url_base        = '_22010/defeitos';

    /**
     * Public method, assigned to prototype
     */
    TalaoFicha.prototype = {
        OLD_VALUE : 0,
        selectionar : function (ficha) {
            
            if ( ficha != undefined ) {
            
                this.SELECTED       = ficha;
                
                this.OLD_VALUE = ficha.QUANTIDADE;
                $('input[ficha="' + ficha.TIPO_ID  + '"').select();

            }
                
        }, 
        gravar : function (ficha) {
            return $q(function(resolve){
                
                var dados = {
                    ID                  : gScope.TalaoProduzir.SELECTED.ID,
                    REMESSA_ID          : gScope.TalaoProduzir.SELECTED.REMESSA_ID,
                    REMESSA_TALAO_ID    : gScope.TalaoProduzir.SELECTED.REMESSA_TALAO_ID,
                    MODELO_ID           : gScope.TalaoProduzir.SELECTED.MODELO_ID,
                    TIPO_ID             : ficha.TIPO_ID,
                    QUANTIDADE          : ficha.QUANTIDADE
                };
                
                $ajax.post('/_22010/api/ficha/post',dados,{progress : false}).then(function(response){
                    
                    gcCollection.merge(gScope.TalaoComposicao.DADOS.FICHA, response.FICHA, 'TIPO_ID');                    
                    
                    resolve(true);
                });
            });
        },
        keydown : function (ficha,$event,model_old) {
            var that = this;
                /* Verifica se existe um evento */
                if ( !($event === undefined) ) {

                    if ( $event.key == 'ArrowUp' || $event.key == 'Enter' || $event.key == 'ArrowDown' ) {
                        $event.preventDefault();
                        $event.stopPropagation();
                        
                        var idx_selected = that.FILTERED.indexOf(that.SELECTED);

                        switch ($event.key) {
                            case 'Enter':
                            case 'ArrowDown':
                                var idx = idx_selected+1;
                                break;
                            case 'ArrowUp':
                                var idx = idx_selected-1;
                                break;
                        }
                        
                        var tabIndex = function (idx) {
                            if (  that.FILTERED[idx] != undefined ) { 
                                $('input[ficha="' + that.FILTERED[idx].TIPO_ID  + '"').select();
                            }
                        };
                        
                        if ( parseFloat(that.OLD_VALUE) != parseFloat(ficha.QUANTIDADE) ) {
                            
                            that.OLD_VALUE = ficha.QUANTIDADE;
                            
                            console.log('val 1 ' + parseFloat(that.OLD_VALUE));
                            console.log('val 2 ' + parseFloat(ficha.QUANTIDADE));
                            that.gravar(ficha).then(function(){                        
                                tabIndex(idx);
                            });
                        } else {
                            tabIndex(idx);
                        }  
                    }
                }            
        },
        setData: function(data) {
            angular.extend(this, data);
        }
    };

    /**
     * Private function
     */
//    function func(role) {
//      
//    }

    /**
     * Static property
     * Using copy to prevent modifications to private property
     */
//    TalaoFicha.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
//    TalaoFicha.build = function (data) {
//        
//        if (!checkRole(data.role)) {
//          return;
//        }
//        
//        return new TalaoFicha(data);
//    };

    /**
     * Return the constructor function
     */
    return TalaoFicha;
};