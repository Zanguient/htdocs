angular
    .module('app')
    .factory('TotalizadorDiario', TotalizadorDiario);
    

	TotalizadorDiario.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$timeout',
        'gScope',
        'gcCollection',
        'gcObject'
    ];

function TotalizadorDiario($ajax, $q, $rootScope, $timeout, gScope, gcCollection, gcObject) {

    /**
     * Constructor, with class name
     */
    function TotalizadorDiario(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = [];
        this.TOTALIZADOR = [];
    }
    
    /**
     * Private property
     */
    var url_base        = '_22010/api/totalizador-diario';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    TotalizadorDiario.prototype = { 
        consultar : function (args) {
            var that = this;
            return $q(function(resolve) {
        
                var args = {
                    estabelecimento_id	: $('.estab').val(),
                    gp_id               : $('._gp_id').val(),
                    perfil_up_id		: $('._perfil_up_id').val(),
                    up_id				: $('._up_id').val(),
                    up_todos			: $('._up_todos').val(),
                    estacao				: $('._estacao_id').val(),
                    estacao_todos		: $('._estacao_todos').val(),
                    data_ini			: $('.filtro-periodo .data-ini').val(),
                    data_fim			: $('.filtro-periodo .data-fim').val(),
                    turno				: $('#turno').val(),
                    turno_hora_ini		: $('#turno').find(':selected').data('hora-ini'),
                    turno_hora_fim		: $('#turno').find(':selected').data('hora-fim')
                };
                 

                $ajax.post(url_base, args).then(function(res){

                    that.DADOS = res;
                    
                    that.totalizadorCalc();
                    
                    resolve(true);
                });
            });            
               
        },
        totalizadorCalc : function () {
            var that = this;
            var dados = that.DADOS;
        
            var total = {
                CAPACIDADE_DISPONIVEL       : 0,
                CARGA_PROGRAMADA            : 0,
                QUANTIDADE_TALAO_PROGRAMADA : 0,
                QUANTIDADE_CARGA_PROGRAMADA : 0,
                QUANTIDADE_PARES_PROGRAMADA : 0,
                PERC_CARGA_PROGRAMADA       : 0,
                CARGA_PENDENTE              : 0,
                QUANTIDADE_TALAO_PENDENTE   : 0,
                QUANTIDADE_CARGA_PENDENTE   : 0,
                QUANTIDADE_PARES_PENDENTE   : 0,
                CARGA_UTILIZADA             : 0,
                QUANTIDADE_TALAO_UTILIZADA  : 0,
                QUANTIDADE_CARGA_UTILIZADA  : 0,
                QUANTIDADE_PARES_UTILIZADA  : 0,
                EFICIENCIA                  : 0,
                PERC_APROVEITAMENTO         : 0,
                UM                          : ''
            };

            if ( dados != undefined ) {
                
                var qtd = 0;
                
                for ( var i in dados ) {
                    var item = dados[i];
                    
                    qtd++;
           
                    total.CAPACIDADE_DISPONIVEL       += parseFloat(item.CAPACIDADE_DISPONIVEL      || 0);
                    total.CARGA_PROGRAMADA            += parseFloat(item.CARGA_PROGRAMADA           || 0);
                    total.QUANTIDADE_TALAO_PROGRAMADA += parseFloat(item.QUANTIDADE_TALAO_PROGRAMADA|| 0);
                    total.QUANTIDADE_CARGA_PROGRAMADA += parseFloat(item.QUANTIDADE_CARGA_PROGRAMADA|| 0);
                    total.QUANTIDADE_PARES_PROGRAMADA += parseFloat(item.QUANTIDADE_PARES_PROGRAMADA|| 0);
                    total.PERC_CARGA_PROGRAMADA       += parseFloat(item.PERC_CARGA_PROGRAMADA      || 0);
                    total.CARGA_PENDENTE              += parseFloat(item.CARGA_PENDENTE             || 0);
                    total.QUANTIDADE_TALAO_PENDENTE   += parseFloat(item.QUANTIDADE_TALAO_PENDENTE  || 0);
                    total.QUANTIDADE_CARGA_PENDENTE   += parseFloat(item.QUANTIDADE_CARGA_PENDENTE  || 0);
                    total.QUANTIDADE_PARES_PENDENTE   += parseFloat(item.QUANTIDADE_PARES_PENDENTE  || 0);
                    total.CARGA_UTILIZADA             += parseFloat(item.CARGA_UTILIZADA            || 0);
                    total.QUANTIDADE_TALAO_UTILIZADA  += parseFloat(item.QUANTIDADE_TALAO_UTILIZADA || 0);
                    total.QUANTIDADE_CARGA_UTILIZADA  += parseFloat(item.QUANTIDADE_CARGA_UTILIZADA || 0);
                    total.QUANTIDADE_PARES_UTILIZADA  += parseFloat(item.QUANTIDADE_PARES_UTILIZADA || 0);
                    total.EFICIENCIA                  += parseFloat(item.EFICIENCIA                 || 0);
                    total.PERC_APROVEITAMENTO         += parseFloat(item.PERC_APROVEITAMENTO        || 0);
                }
//                console.log(total.PERC_CARGA_PROGRAMADA);
//                console.log(qtd);
                
                total.PERC_CARGA_PROGRAMADA = total.PERC_CARGA_PROGRAMADA / qtd;
                total.EFICIENCIA            = total.EFICIENCIA            / qtd;
                total.PERC_APROVEITAMENTO   = total.PERC_APROVEITAMENTO   / qtd;
                
                total.UM = dados[0].UM;
            }
                 
            that.TOTALIZADOR = [total];

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
    TotalizadorDiario.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    TotalizadorDiario.build = function (data) {
        
        if (!checkRole(data.role)) {
          return;
        }
        
        return new TotalizadorDiario(data);
    };

    /**
     * Return the constructor function
     */
    return TotalizadorDiario;
};