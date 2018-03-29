angular
    .module('app')
    .factory('Estacao', Estacao);
    

	Estacao.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$q',
        '$rootScope',
        '$filter',
        'gScope',
        'gcCollection',
        'gcObject'
    ];

function Estacao($ajax, $httpParamSerializer, $q, $rootScope, $filter, gScope, gcCollection, gcObject) {

    /**
     * Constructor, with class name
     */
    function Estacao(data) {
        if (data) {
            this.setData(data);
        }
        
        gScope.Estacao = this;
        
        this.DADOS = [];
        this.SELECTEDS = [];
        
    }
    
    
    /**
     * Private property
     */
    var url_base        = '_22140/api/programacao-estacao';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    Estacao.prototype = { 
        select : function(estacao, bool) {
            var that = this;
        
            if ( bool != undefined ) {
                if ( bool ) {
                    that.SELECTEDS.push(estacao);
                    estacao.SELECTED = true;
                } else {
                    that.SELECTEDS.splice(idx, 1);
                    estacao.SELECTED = false;
                }
            } else {
            
                var idx = that.SELECTEDS.indexOf(estacao);
                if ( idx >= 0 ) {
                    that.SELECTEDS.splice(idx, 1);
                    estacao.SELECTED = false;
                } else {
                    that.SELECTEDS.push(estacao);
                    estacao.SELECTED = true;
                }
            }
        },
        reprocessar : function () {
            
            var args = {
                data_return : true,
                estacoes    : this.SELECTEDS,
                data_hora   : moment(gScope.Filtro.DATAHORA).utcOffset(-180).format(),
                agora       : gScope.Filtro.AGORA,
                em_producao : gScope.Filtro.EM_PRODUCAO,
                ordem_data_remessa : gScope.Filtro.ORDEM_DATA_REMESSA
            };
            
            $ajax.post(url_base+'/post',args).then(function(response){
                
            });
        },
        setValues : function (values) {
            
            return $q(function(resolve){

                gcCollection.merge(gScope.Estacao.DADOS, values, ['UP_ID','ESTACAO']);

                var gps = gcCollection.groupBy(gScope.Estacao.DADOS,['FAMILIA_ID','FAMILIA_DESCRICAO','GP_ID','GP_DESCRICAO','UP_ID','UP_DESCRICAO'],'ESTACAO');

                gcCollection.merge(gScope.Gp.DADOS, gps, 'GP_ID');

                var familias = gcCollection.groupBy(gScope.Gp.DADOS, ['FAMILIA_ID','FAMILIA_DESCRICAO'],'GP');

                gcCollection.merge(gScope.Familia.DADOS, familias, 'FAMILIA_ID');

                for ( var i in gScope.Familia.DADOS ) {
                    var item = gScope.Familia.DADOS[i];

                    gcObject.calcField('SELECTED',item, function(itemScope) {

                        var ret = false;
                        for ( var y in itemScope.GP ) {
                            var gp = itemScope.GP[y];

                            if ( gp.SELECTED == true ) {
                                ret = true;
                                break;
                            }
                        }

                        return ret;
                    });	 
                }

                for ( var i in gScope.Gp.DADOS ) {
                    var item = gScope.Gp.DADOS[i];

                    gcObject.calcField('SELECTED',item, function(itemScope) {

                        var ret = false;
                        for ( var y in itemScope.ESTACAO ) {
                            var estacao = itemScope.ESTACAO[y];

                            if ( estacao.SELECTED == true ) {
                                ret = true;
                                break;
                            }
                        }

                        return ret;
                    });	 
                }

                console.log(gScope.Familia);
                
                resolve(true);
            });
        }
    };

    /**
     * Private function
     */
    
    /**
     * Realiza o registro de inicio do talão selecionado
     * @param {json} param
     * @returns {void}
     */
    function registraEstacao (param)
    {
        dadosTalao();

        return $q(function(resolve, reject) {
            execAjax1('POST',param.rota_ajax,dados,
            function() {
                resolve(true);
            },
            function() {
                reject(false);
            });
        });
    }

    /**
     * Realiza a validação do talão
     * @param {json} param
     * @returns {void}
     */
    function validaTalao (param)
    {   
        //Realiza a coleta dos dados do talão selecionado
        dadosTalao();

        //Realiza a cópia do dados do talão selecionado (mantem o original inalterado)
        var dadosAjax = $.extend({}, dados);
        dadosAjax.status_talao       = param.status_talao;
        dadosAjax.status_programacao = param.status_programacao;

        return $q(function(resolve) {
            
                $ajax.post('/_22010/talaoValido', JSON.stringify(dadosAjax), {contentType: 'application/json', progress : false})
                    .then(function(){
                        resolve(true);
                    })
                ;
            }, 
            function(error) {

//                new TalaoFiltrar().filtrar();	//atualiza o status
                reject(false);

            }
        );
    }

    /**
     * Autenticar UP.
     * @returns {Promise}
     */
    function autenticarUp () {

        return $q(function(resolve, reject) {

            var modal		= $('#modal-autenticar-up');
            var input_barra = $('#up-barra');

            function consultar() {

                //ajax
                var type	= 'POST',
                    url		= '/_22010/autenticarUp',
                    data	= {
                        up_barra		: $(input_barra).val(),
                        up_selecionada	: gScope.Filtro.UP_ID
                    }
                ;

                return execAjax1(type, url, data);

            }

            function autenticar() {

                $.when(consultar())
                    .done(function() {
                        $(modal).modal('hide');
                        resolve(true);
                    })
                    .fail(function() {
                        $(input_barra).val('').focus();
                    })
                ;
            }

            $(modal)
                .modal('show')
                .off('shown.bs.modal')
                .on('shown.bs.modal', function () {
                    $(input_barra).focus();
                })
                .off('hidden.bs.modal')
                .on('hidden.bs.modal', function () {
                    $(input_barra).val('');
                })
                .off('keydown', '#up-barra')
                .on('keydown', '#up-barra', 'return', function () {
                    autenticar();
                })
                .off('click', '#btn-confirmar-up')
                .on('click', '#btn-confirmar-up', function () {
                    autenticar();
                })
            ;

        });

    }


    /**
     * Static property
     * Using copy to prevent modifications to private property
     */
    Estacao.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    Estacao.build = function (data) {
        
        if (!checkRole(data.role)) {
          return;
        }
        
        return new Estacao(data);
    };

    /**
     * Return the constructor function
     */
    return Estacao;
};