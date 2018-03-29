angular
    .module('app')
    .factory('TalaoProduzir', TalaoProduzir);
    

	TalaoProduzir.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function TalaoProduzir($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function TalaoProduzir(data) {
        if (data) {
            this.setData(data);
        }

		gScope.TalaoProduzir = this; 
        
        this.DADOS = [];
        this.SELECTED = {};
        this.FILTRO = {};
        
        var that = this;
        $timeout(function(){
            
            if ( that.FILTRO.ESTABELECIMENTO_ID > 0 ) {
                
                gScope.ConsultaGp.autoload = false;
                gScope.ConsultaEstabelecimento.option.data_request.ESTABELECIMENTO_ID = [that.FILTRO,'ESTABELECIMENTO_ID'];
                gScope.ConsultaEstabelecimento.filtrar();
                delete gScope.ConsultaEstabelecimento.option.data_request.ESTABELECIMENTO_ID;
            }
            
            gScope.ConsultaEstabelecimento.onSelect = function(){
                if ( that.FILTRO.GP_ID > 0 ) {
                    gScope.ConsultaUp.autoload = false;
                    gScope.ConsultaGp.option.data_request.GP_ID = [that.FILTRO,'GP_ID'];
                    gScope.ConsultaGp.filtrar();
                    delete gScope.ConsultaGp.option.data_request.GP_ID;   
                }
            };

            gScope.ConsultaGp.onSelect = function(){
                gScope.ConsultaGp.autoload = true;
                if ( that.FILTRO.UP_ID > 0 ) {
                    gScope.ConsultaEstacao.autoload = false;
                    gScope.ConsultaUp.option.data_request.UP_ID = [that.FILTRO,'UP_ID'];
                    gScope.ConsultaUp.filtrar();
                    delete gScope.ConsultaUp.option.data_request.UP_ID;   
                }
            };

            gScope.ConsultaUp.onSelect = function(){
                gScope.ConsultaUp.autoload = true;
                if ( that.FILTRO.ESTACAO > 0 ) {
                    gScope.ConsultaEstacao.option.data_request.ESTACAO = [that.FILTRO,'ESTACAO'];
                    gScope.ConsultaEstacao.filtrar();
                    delete gScope.ConsultaEstacao.option.data_request.ESTACAO;   
                }
            };
            
            gScope.ConsultaEstacao.onSelect = function() {
                if ( that.FILTRO.ESTACAO > 0 ) {
                    gScope.Filtro.consultar().then(function(){
                        if ( that.FILTRO.TALAO_ID > 0 ) {
                            $timeout(function(){
                                $('[data-talao-id="' + that.FILTRO.TALAO_ID + '"]:focusable').focus().click();
                            });
                        }
                    });
                }
            };
//

//                    
//
//                    if ( that.FILTRO.UP_ID > 0 ) {
//                        gScope.ConsultaUp.option.filtro_sql = { UP_ID: that.FILTRO.UP_ID };
//                        gScope.ConsultaUp.filtrar();
//                        gScope.ConsultaUp.option.filtro_sql = {};
//                        
//
//                        if ( that.FILTRO.ESTACAO > 0 ) {
//                            gScope.ConsultaEstacao.option.filtro_sql = { ESTACAO: that.FILTRO.ESTACAO };
//                            gScope.ConsultaEstacao.filtrar();
//                            gScope.ConsultaEstacao.option.filtro_sql = {};
//                        }  
//                        
//                    }   
//
//                }         
              
            
            
        });        
    }
    
    
    TalaoProduzir.prototype.consultar = function() {
        
        var that = this;
        
//        loading('.main-ctrl');     
        

        
        var data = {};

        angular.copy(that, data);
        
        if ( this.DATA_TODOS ) {
            delete data.DATA_1;
            delete data.DATA_2;
        }
        
        data.PROGRAMACAO_STATUS = "< 3";
        data.GP_ID              = gScope.ConsultaGp.GP_ID;
        data.UP_ID              = gScope.ConsultaUp.UP_ID;
        data.ESTACAO            = gScope.ConsultaEstacao.ESTACAO;
        
        $ajax.post('/_22190/api/talao',data,{progress: false}).then(function(response){
            
            that.merge(response);
            
//            loading('hide');
            
        });
    };
   
    TalaoProduzir.prototype.acao = function (tipo) {
        var that = this;

        var talao = gScope.Talao.SELECTED;
        talao.ULTIMO_TALAO = true;
        for ( var i in talao.DETALHES ) {
            var detalhe = talao.DETALHES[i];
            
            if ( detalhe.TALAO_DETALHE_STATUS < 2 ) {
                var idx = gScope.TalaoDetalhe.SELECTEDS_PRODUZIR.indexOf(detalhe);
                
                if ( idx == -1 ) {
                    talao.ULTIMO_TALAO = false;
                    break;
                }
            }
        }

        var dados = {
            FILTRO: gScope.Filtro,
            DADOS: {
                ITENS              : gScope.TalaoDetalhe.SELECTEDS_PRODUZIR,
                TALAO              : gScope.Talao.SELECTED,
                ESTABELECIMENTO_ID : gScope.ConsultaEstabelecimento.ESTABELECIMENTO_ID,
                UP_ID              : gScope.ConsultaUp.UP_ID,
                ESTACAO            : gScope.ConsultaEstacao.ESTACAO,
                OPERADOR_ID        : gScope.Operador.SELECTED.OPERADOR_ID
            }
        };
                
        $ajax.post('/_22190/api/taloes/acao/'+tipo,dados).then(function(response){

            if ( response.ETIQUETAS != undefined && response.ETIQUETAS.trim() != '' ) {
                postprint(response.ETIQUETAS);
            }          

            if ( response.DATA_RETURN != undefined ) {
                gScope.Filtro.merge(response.DATA_RETURN);
            }
            
            if ( tipo == 'finalizar' ) {
                
                gScope.TalaoDetalhe.SELECTEDS_PRODUZIR = [];
                
                if ( indexOfAttr(gScope.TalaoProduzir.DADOS,'TALAO_ID',gScope.Talao.SELECTED.TALAO_ID) == -1 ) {
                    gScope.Talao.close();
                }
            }  
        },function(){
            
        });
    };
    
    TalaoProduzir.prototype.check = function (acao,arg_talao) {

        var ret         = {
            status    : true,
            descricao : ''
        };

        var em_producao = gScope.TalaoProduzir.EM_PRODUCAO || false;
        var talao       = arg_talao != undefined ? arg_talao : gScope.Talao.SELECTED ;

        if ( talao.PROGRAMACAO_STATUS > 2 ) {
            ret.status = false;
        } else
        if ( isEmpty(gScope.Operador.SELECTED) ) {
            ret.status = false;
            ret.descricao = 'Operador não autenticado.';
        }
                        
        if ( ret.status ) {
            switch(acao) {
                case 'iniciar':

                    if ( talao.REMESSA_LIBERADA == 0 ) {
                        ret.status = false;
                        ret.descricao = 'Remessa bloqueada para produção';
                    } else
                    if ( talao.ESTOQUE_STATUS == '0' ) {
                        ret.status = false;
                        ret.descricao = 'Talão sem estoque para o consumo';
                    } else                    
                    if ( talao.PROGRAMACAO_STATUS == 1 ) {
                        ret.descricao = 'Talão parado';
                    } else                    
                    if ( talao.PROGRAMACAO_STATUS > 1 ) {
                        ret.status = false;
                    }                

                    break;
                case 'pausar':

                    // Se estiver em produção 
                    if ( talao.PROGRAMACAO_STATUS != 2 ) {
                        ret.status = false;
                    }  

                    break;
                case 'finalizar':

                    // Se estiver em produção 
                    if ( talao.PROGRAMACAO_STATUS < 2 ) {
                        ret.status = false;
                    }  
                    else                 
                    if ( gScope.TalaoDetalhe.SELECTEDS_PRODUZIR.length == 0 ) {
                        ret.status = false;
                        ret.descricao = 'Selecione um detalhamento não produzido.';
                    }
//                
//                    // Se estiver em produção 
////                    if ( gScope.Talao.SELECTED.PROGRAMACAO_STATUS < 2 ) {
//                        ret.status = false;
////                    }  

                    break;
                case 'imprimir':

                    var talao_produzido = gScope.TalaoProduzido.SELECTED;


                    // Se não estiver na tela de produção
                    if ( gScope.Filtro.GUIA_ATIVA != 'TALAO_PRODUZIDO' ) {
                        ret.status    = false;
                    } else                 
                    if ( talao_produzido == undefined ) {
                        ret.status    = false;
                        ret.descricao = 'Selecione um talão';
                    }

                    break;
            }
        }
        
        return ret;
    };
    
    

    /**
     * Return the constructor function
     */
    return TalaoProduzir;
};