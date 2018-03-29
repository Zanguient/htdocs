angular
    .module('app')
    .factory('TalaoProduzido', TalaoProduzido);
    

	TalaoProduzido.$inject = [        
        '$ajax',
        '$timeout',
        '$q',
        '$rootScope',
        'gScope',
        'gcCollection',
        'gcObject'
    ];

function TalaoProduzido($ajax,$timeout,$q,$rootScope,gScope,gcCollection,gcObject) {

    /**
     * Constructor, with class name
     */
    function TalaoProduzido(data) {
        if (data) {
            this.setData(data);
        }
        this.TOTALIZADOR = {};
        this.DADOS = [];
    }
    
    /**
     * Private property
     */
    var url_base = '_22010/api/talao/produzido/';
    var dados    = {};

    /**
     * Coleta ou atualiza da variável dados do talão selecionado
     * @returns {void}
     */
    var dadosTalao = function()
    {
        var f = gScope.Filtro;
        var t = gScope.TalaoProduzido.SELECTED;
        
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
    TalaoProduzido.prototype = {    
        selectionar : function (talao,setfocus) {
            
            if ( talao != undefined ) {
            
                this.SELECTED       = talao;
                this.SELECTED_RADIO = talao.ID;
                
                if ( setfocus ) {
                    $timeout(function(){
                        $('.table-talao-produzido.table-lc-body tr.selected').focus();
                    },50);                      
                }

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

                    for ( var i in res ) {
                        var item = res[i];
                
                        item.DATAHORA_REALIZADO_FIM  = new Date(item.DATAHORA_REALIZADO_FIM);
                    }
                    
                    gcCollection.merge(gScope.TalaoProduzido.DADOS, res, 'ID');

                    that.totalizadorCalc();
                                         
                    if ( gScope.TalaoProduzido.DADOS.length > 0 && gScope.TalaoProduzido.SELECTED != undefined ) {
                        gScope.TalaoProduzido.selectionar(gScope.TalaoProduzido.SELECTED);
                    }
                    

                    resolve(true);
                });
            });
        },
        totalizadorCalc : function () {
            var that = this;
            var dados = gScope.TalaoProduzido.DADOS;
        
            that.TOTALIZADOR.QUANTIDADE_PROJETADA   = 0;
            that.TOTALIZADOR.QUANTIDADE_PRODUZIDA   = 0;
            that.TOTALIZADOR.TEMPO_PREVISTO         = 0;
            that.TOTALIZADOR.TEMPO_REALIZADO        = 0;
            that.TOTALIZADOR.PAR_PRODUZIDO          = 0;
            that.TOTALIZADOR.QUANTIDADE_UM          = '';

            if ( dados != undefined ) {
                for ( var i in dados ) {
                    var item = dados[i];

                    that.TOTALIZADOR.QUANTIDADE_PROJETADA   += parseFloat( ( item.UM_ALTERNATIVA != '' ? item.QUANTIDADE_ALTERNATIVA : item.QUANTIDADE ) );
                    that.TOTALIZADOR.QUANTIDADE_PRODUZIDA   += parseFloat( ( item.UM_ALTERNATIVA != '' ? item.QUANTIDADE_ALTERNATIVA_PRODUCAO : item.QUANTIDADE_PRODUCAO ));
                    that.TOTALIZADOR.TEMPO_PREVISTO         += parseFloat(item.TEMPO);
                    that.TOTALIZADOR.TEMPO_REALIZADO        += parseFloat(item.TEMPO_REALIZADO);
                    
                    if ( gScope.Filtro.VER_PARES == '1' && item.PARES != undefined && item.PARES != '' ) {
                        that.TOTALIZADOR.PAR_PRODUZIDO += parseFloat(item.PARES);
                    }
                }
            }
            
            if ( dados.length > 0 ) {
                var item = gScope.TalaoProduzido.DADOS[0];
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
    function fn() {
        //
    }

    /**
     * Return the constructor function
     */
    return TalaoProduzido;
};