/* global moment */

(function($) {
    
    
    Clock = {
        DATETIME_SERVER : null,
        DATETIME_FERER : null,
        DATETIME_FERRER_SERVER : null,
        PC_HORA_INICIO : null,
        PC_HORA_ATUAL : null,
        PC_DIFERENCA : null,
        NOW : null,
        Init : function() {
//            this.Increment();
            this.Refresh();
            this.SetTimeServer();
        },    
        SetTimeServer : function() {
            var that = this;
            setInterval(function() {
                $.get( "/current-time-server", function( data ) {
                    that.DATETIME_FERER = data;
                });
            },300000);
        },
        Refresh : function() {
            var that = this;
            setInterval(function() {
                that.Increment();
            }, 1000);            
        },
        Increment : function() {
            
            var cur = new Date();
            var startTime = moment(Clock.PC_HORA_INICIO).format();
            var endTime   = moment(cur).format();

            var duration     = moment.duration(moment(endTime).diff(startTime));            
            Clock.PC_DIFERENCA = duration.asSeconds();            
            
			time_server = moment(Clock.DATETIME_FERRER_SERVER).add(Clock.PC_DIFERENCA, 's');
            this.DATETIME_SERVER = moment(time_server).format("YYYY-MM-DD HH:mm:ss"); 
            document.getElementById("_hora-servidor").value = this.DATETIME_SERVER;
            document.querySelector('#relogio #data').innerHTML = moment(time_server).format("DD/MM/YYYY");
            document.querySelector('#relogio #hora').innerHTML = moment(time_server).format("HH:mm:ss");
        }
        
    };

    Clock.watch('DATETIME_FERER', function (id, oldval, newval) {
        that = Clock;
        that.PC_HORA_INICIO = new Date();
        that.DATETIME_SERVER = newval;
        that.DATETIME_FERRER_SERVER = newval;
      });
	
	$(function() {	
		if( $(window).width() > 768 && window.name == '') {
            Clock.Init();
        }
			
		
	});
	
})(jQuery);
//# sourceMappingURL=relogio.js.map
