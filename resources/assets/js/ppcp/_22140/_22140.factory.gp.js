angular
    .module('app')
    .factory('Gp', Gp);
    

	Gp.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$q',
        '$rootScope',
        '$filter',
        'gScope',
        'gcCollection'
    ];

function Gp($ajax, $httpParamSerializer, $q, $rootScope, $filter, gScope, gcCollection) {

    /**
     * Constructor, with class name
     */
    function Gp(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = [];
        this.GPS = [];
        
        gScope.Gp = this;
    }
    
    
    /**
     * Private property
     */
    var url_base        = '/_22140/api/programacao/gp';
    var possibleRoles   = ['admin', 'editor', 'guest'];
    
    
    /**
     * Variável que recebe os valores do talão
     * @type type
     */
    var dados = {};

    /**
     * Valores para validar o status para iniciar um talão
     * @type {json}
     */
    var status_parado = 
    {
        status_talao       : [1], // 1 - Em aberto
        status_programacao : [0,1] // 0 - Parado ; 1 - Iniciado/Parado
    };

    /**
     * Valores para validar o status para pausar um talão
     * @type {json}
     */
    var status_andamento =
    {
        status_talao       : [1], // 1 - Em aberto
        status_programacao : [2] // 2 - Em Andamento
    };

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
            tempo_realizado		: tempo_realizado,
            justificativa_id    : gScope.Gp.API.JUSTIFICATIVA.SELECTED.ID
        };
    };
    

    /**
     * Public method, assigned to prototype
     */
    Gp.prototype = {   
        select : function (gp) {
            var bool = gp.SELECTED ? false : true;
            
            for ( var i in gp.ESTACAO ) {
                
                var estacao = gp.ESTACAO[i];
                
                gScope.Estacao.select(estacao,bool);
            }
        }
    };
    

    Gp.prototype.consultar = function (args) {
        
        var that = this;
        
        return $q(function(resolve){
            $ajax.get(url_base).then(function(response){
                
                that.GPS = response;
                
            });    
        });
        
    };       

    Gp.prototype.calendarioAtualizar = function (args) {
        
        var that = this;
        
        var data = {
            DATA : moment(that.DATA).format('YYYY.MM.DD'),
            HORARIO : that.HORARIO == 'personalizado' ? that.HORARIO_PERSONALIZADO : that.HORARIO,
            GPS : $filter('filter')(that.GPS, {CHECKED : '1'})
        };
        
        return $q(function(resolve,reject){
            $ajax.post(url_base+'/calendario/update',data).then(function(response){
                resolve(response);
            },function(erro){
                reject(erro);
            });    
        });
        
    };       

    /**
     * Private function
     */
    
    /**
     * Realiza o registro de inicio do talão selecionado
     * @param {json} param
     * @returns {void}
     */
    function registraGp (param)
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
    Gp.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    Gp.build = function (data) {
        
        if (!checkRole(data.role)) {
          return;
        }
        
        return new Gp(data);
    };

    /**
     * Return the constructor function
     */
    return Gp;
};