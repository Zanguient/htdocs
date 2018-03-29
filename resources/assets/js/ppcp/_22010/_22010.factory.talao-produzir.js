
        
angular
    .module('app')
    .factory('TalaoProduzir', TalaoProduzir);
    

	TalaoProduzir.$inject = [        
        '$ajax',
        '$timeout',
        '$q',
        '$rootScope',
        'gScope',
        'gcCollection'
    ];

function TalaoProduzir($ajax,$timeout,$q,$rootScope,gScope,gcCollection) {

    /**
     * Constructor, with class name
     */
    function TalaoProduzir(data) {
        if (data) {
            this.setData(data);
        }
        
        this.TOTALIZADOR = {};
    }
    
    this.EM_PRODUCAO = false;
    
    /**
     * Private property
     */
    var url_base        = '_22010/api/talao/produzir/';
    var possibleRoles   = ['admin', 'editor', 'guest'];
    var dados = {};

    /**
     * Coleta ou atualiza da variável dados do talão selecionado
     * @returns {void}
     */
    var dadosTalao = function()
    {
        var f = gScope.Filtro;
        var t = gScope.TalaoProduzir.SELECTED;
        
        var estabelecimento_id	= f.ESTABELECIMENTO_ID;
        var gp_id				= f.GP_ID;
        var up_id				= f.UP_ID;
        var estacao				= f.ESTACAO;
        var operador_id			= $('#_operador-id').val();
        var remessa_id			= t.REMESSA_ID;
        var remessa_talao_id	= t.REMESSA_TALAO_ID;
        var talao_id			= t.ID;
        var programacao_id		= t.PROGRAMACAO_ID;
        var tempo_realizado		= t.TEMPO_REALIZADO_RELOGIO;

        dados = {
            estabelecimento_id	: estabelecimento_id,
            gp_id				: gp_id,
            up_id				: up_id,
            estacao				: estacao,
            operador_id			: operador_id,
            remessa_id			: remessa_id,
            remessa_talao_id	: remessa_talao_id,
            talao_id			: talao_id,
            programacao_id		: programacao_id,
            tempo_realizado		: tempo_realizado
        };
    };  

    /**
     * Public method, assigned to prototype
     */
    TalaoProduzir.prototype = {    
        DADOS           : [],
        TEMPO_REALIZADO : 0,
        selectionar : function (talao,setfocus) {
            
            if ( talao != undefined ) {
            
                this.SELECTED       = talao;
                this.SELECTED_RADIO = talao.ID;
                
                if ( setfocus ) {
                    $timeout(function(){
                        $('.table-talao-produzir.table-lc-body tr.selected').focus();
                    },50);                      
                }

                gScope.TalaoTempo.calcRealTime();
                gScope.Filtro.TALAO_SELECTED = talao.ID;
                gScope.Filtro.uriHistory();
                gScope.TalaoComposicao.consultar();
            }
                
        },        
        all : function () {
            
            var that = this;
            
            return $q(function(resolve) {
        
                var args = {
                    estabelecimento_id	: $('.estab').val(),
                    gp_id				: $('._gp_id').val(),
                    up_id				: $('._up_id').val(),
                    up_todos			: $('._up_todos').val(),
                    up_origem			: $('._up_origem_descricao').val(),
                    estacao				: $('._estacao_id').val(),
                    estacao_todos		: $('._estacao_todos').val(),
                    remessa				: $('#remessa').val(),
                    data_producao		: $('#data-destaque').find('.valor').text(),
                    data_ini			: $('.filtro-periodo .data-ini').val(),
                    data_fim			: $('.filtro-periodo .data-fim').val(),
                    periodo_todos		: $('#periodo-todos').is(':checked'),
                    _perfil_gp			: $('._perfil-gp').val().trim(),
                    ver_pares			: $('._ver-pares-gp').val().trim(),
                    turno				: $('#turno').val(),
                    turno_hora_ini		: $('#turno').find(':selected').data('hora-ini'),
                    turno_hora_fim		: $('#turno').find(':selected').data('hora-fim')
                };

                $ajax.post(url_base+'all', JSON.stringify(args), {contentType: 'application/json'})
                .then(function(res){

                    for (var i in res) {
                        var item = res[i];
                        item.SEQUENCIA_PRODUCAO =  JSON.parse(item.SEQUENCIA_PRODUCAO);
                    }
                    
                    gcCollection.merge(gScope.TalaoProduzir.DADOS, res, 'ID');
            
                    that.totalizadorCalc();

                    if ( gScope.TalaoProduzir.DADOS.length > 0 ) {
                        
                        $timeout(function(){
                            $('#filtrar-toggle[aria-expanded="true"]').click(); 
                        });


                        if ( gScope.Filtro.TALAO_SELECTED > 0 ) {
                            var idx = gScope.indexOfAttr(gScope.TalaoProduzir.DADOS,'ID',gScope.Filtro.TALAO_SELECTED);
                            gScope.TalaoProduzir.selectionar(gScope.TalaoProduzir.DADOS[idx]);
                        }
                    }
                    
                    resolve(true);
                });
            });
        },
        current : function (consultar_composicao) {
            
            var that = this;
            
            return $q(function(resolve, reject) {
                dadosTalao();

                var data = {};
                var options = {progress : false};
                
                angular.copy(dados, data);
                
                if (consultar_composicao) {
                    data.talao_composicao     = '1';
                    data.gp_pecas_disponiveis = gScope.Filtro.GP_PECAS_DISPONIVEIS;
                    options.progress          = true;
                }
                
                $ajax.post('_22010/recarregarStatus',data,options).then(function(resposta) {

                            if ( resposta.TALAO_COMPOSICAO != undefined ) {
                                gScope.TalaoComposicao.setComposicao(resposta.TALAO_COMPOSICAO);
                            }
                                
                            gcCollection.merge(gScope.TalaoProduzir.DADOS, resposta.TALAO, 'ID', true);
                            
                            that.totalizadorCalc();

                            var em_producao = false;

                            //se o talão estiver em produção
                            if (resposta.PROGRAMACAO_STATUS.trim() == '2') {
                                em_producao = true;
                            }

                            gScope.TalaoProduzir.INICIADO = em_producao;

                            resumoProducao();

                            resolve(true);
                    },
                    function() {
                        reject(false);
                    }
                );
            });            
        },
        producao : function (bool) {
            this.EM_PRODUCAO = bool;
        },
        totalizadorCalc : function () {
            var that = this;
            var dados = gScope.TalaoProduzir.DADOS;
        
            that.TOTALIZADOR.QUANTIDADE_PROJETADA = 0;
            that.TOTALIZADOR.TEMPO_PREVISTO       = 0;
            that.TOTALIZADOR.PAR_PRODUZIR         = 0;
            that.TOTALIZADOR.QUANTIDADE_UM        = '';

            if ( dados != undefined ) {
                for ( var i in dados ) {
                    var item = dados[i];

                    that.TOTALIZADOR.QUANTIDADE_PROJETADA   += parseFloat( ( item.UM_ALTERNATIVA != '' ? item.QUANTIDADE_ALTERNATIVA : item.QUANTIDADE ) );
                    that.TOTALIZADOR.TEMPO_PREVISTO         += parseFloat(item.TEMPO);
                    
                    if ( gScope.Filtro.VER_PARES == '1' && item.PARES != undefined && item.PARES != '' ) {
                        that.TOTALIZADOR.PAR_PRODUZIR += parseFloat(item.PARES);
                    }
                }
            }
            
            if ( dados.length > 0 ) {
                var item = gScope.TalaoProduzir.DADOS[0];
                if ( item.UM_ALTERNATIVA != '' ) {
                    that.TOTALIZADOR.QUANTIDADE_UM = item.UM_ALTERNATIVA;
                } else {
                    that.TOTALIZADOR.QUANTIDADE_UM = item.UM;
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
    function checkRole(role) {
      return possibleRoles.indexOf(role) !== -1;
    }

    /**
     * Static property
     * Using copy to prevent modifications to private property
     */
    TalaoProduzir.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    TalaoProduzir.build = function (data) {
        
        if (!checkRole(data.role)) {
          return;
        }
        
        return new Defeito(
            data.first_name,
            data.last_name,
            data.role
//            Organisation.build(data.organisation) // another model
        );
    };

    /**
     * Return the constructor function
     */
    return TalaoProduzir;
};