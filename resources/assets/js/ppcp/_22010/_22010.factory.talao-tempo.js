angular
    .module('app')
    .factory('TalaoTempo', TalaoTempo);
    

	TalaoTempo.$inject = [        
        '$ajax',
        '$filter',
        '$rootScope',
        'gScope'
    ];

function TalaoTempo($ajax,$filter,$rootScope,gScope) {

    /**
     * Constructor, with class name
     */
    function TalaoTempo(data) {
        if (data) {
            this.setData(data);
        }
    }
    
    this.TOTAL          = 0;
    var time_interval = null;
    
    /**
     * Private property
     */
    var url_base        = '_22010/api/talao/';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    TalaoTempo.prototype = {
        calcRealTime : function () {
            var that = this;
            var talao = gScope.TalaoProduzir.SELECTED;
            
            clearInterval(time_interval);
            that.INTERVAL_TIME = null;
                
            if ( talao == undefined ) return false;
            
            if ( talao.PROGRAMACAO_STATUS == '2' ) {
                
                var calc = function () {
                    var ultima_data =	
                    $('#historico')
                        .find('tbody')
                        .find('td.data-historico')
                        .data('datahora') || new Date()
                    ;

                    var diferenca		 =	moment(Clock.DATETIME_SERVER).diff(moment(ultima_data));
                    var tempo            = diferenca + talao.TEMPO_REALIZADO * 60 * 1000;

                    var tempo_total_obj = moment.duration(tempo).add(1, 's');
                    
                    talao.TEMPO_REALIZADO_RELOGIO = tempo_total_obj.asMinutes().toFixed(4);
                    talao.TEMPO_REALIZADO_HUMANIZE = tempo_total_obj.format('mm[m] ss[s]');

                    $('#_tempo-realizado')
                        .val( talao.TEMPO_REALIZADO_RELOGIO )	//fração de minutos
                    ;
                };
                
                time_interval = setInterval(function() {
                    $rootScope.$apply(function(){
                        calc();
                    });
                }, 1000);  
                
                calc();
            } else {
                
                var duracao = moment.duration(talao.TEMPO_REALIZADO * 60 * 1000);
                talao.TEMPO_REALIZADO_RELOGIO  = talao.TEMPO_REALIZADO_RELOGIO  || duracao.asMinutes().toFixed(4);
                talao.TEMPO_REALIZADO_HUMANIZE = talao.TEMPO_REALIZADO_HUMANIZE || duracao.format('mm[m] ss[s]');
                
                $('#_tempo-realizado')
                    .val( talao.TEMPO_REALIZADO_RELOGIO )	//fração de minutos
                ;
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
    TalaoTempo.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    TalaoTempo.build = function (data) {
        
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
    return TalaoTempo;
};