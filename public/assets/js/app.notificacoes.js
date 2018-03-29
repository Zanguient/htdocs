/**
 * _11190 - Notificacao
 */

;(function(angular) {

    var Notificacao = function($scope,$ajax,$timeout) {

        var vm    = this;
        vm.acaoContador = {};
        vm.acaoContador.tempo = 5;   

        vm.acaoContador.fechar = function(){
            if(vm.contador.visivel == true){
                vm.contador.visivel = false;
                $timeout.cancel(vm.contador.time);
                $timeout.cancel(vm.acaoContador.ftime);
                vm.contador.acao();
            }
        };

        vm.acaoContador.ftime  = function(){

            vm.acaoContador.tempo = vm.acaoContador.tempo - 1;

            if(vm.acaoContador.tempo > 0){
                $timeout(vm.acaoContador.ftime,1000);
            }else{
                vm.acaoContador.fechar();
            }
        };

        vm.contador = {
            tempo  : 5,
            acao   : null,
            time   : null,
            visivel: false,
            msg1   : 'Sua tela sera atualizada em',
            msg2   : 'Fechar o contador atualizara sua tela agora',
            fechar : function(){
                vm.contador.visivel = false;
            },
            iniciar: function() {
                var that     = this;
                that.visivel = true;
                that.time    = $timeout(vm.acaoContador.ftime,1000); 

                vm.acaoContador.tempo = that.tempo;
            }
        };

		// Funções para o web socket.
		var metodos = [
			{
				METHOD  :'NOTIFICACAO',
				FUNCTION:function(ret){
					var mensagem = ret.MENSAGE.DADOS.MENSAGEM;
					var titulo   = ret.MENSAGE.DADOS.TITULO;
                    var id       = ret.MENSAGE.DADOS.MENSAGE_SOCKET_ID;
                    var agd_id   = ret.MENSAGE.DADOS.AGENDAMENTO_ID;

					addNotificacao(mensagem,titulo,id, 0 ,agd_id);
				}
			},
			{
				METHOD  :'UPDATETELA',
				FUNCTION:function(ret){
                    vm.contador.acao  = function(){location.reload();};
                    vm.contador.msg1  = 'Sua tela sera atualizada em';
                    vm.contador.msg2  = 'Fechar o contador atualizara sua tela agora';
                    vm.contador.tempo = 5;
                    vm.contador.iniciar();
				}
			},
			{
				METHOD  :'UPDATEMENUS',
				FUNCTION:function(ret){
                    vm.contador.acao  = function(){
                        window.localStorage.removeItem('ngStorage-menus');
                        location.reload();
                    };
                    vm.contador.msg1  = 'Seus menus serão atualizados em';
                    vm.contador.msg2  = 'Fechar o contador atualizara seus menus agora';
                    vm.contador.tempo = 5;
                    vm.contador.iniciar();
				}
			}

		];

		// Iniciar web socket.
		SocketWeb.SILENT = true;
		var reconet = null;

		SocketWeb.ERROR_EVENT = function (error){

            SocketWeb.setStatus(2);

			reconet = setTimeout(function(e){
				if(SocketWeb.ERROS < 3){
					console.log('Tentando conectar...');
					SocketWeb.create(metodos);
					clearTimeout(reconet);
				}
			},6000);

            if (error.type == 'close') {
                //showErro('Servidor WebSocket desconectado.');
            }
            else {
                //showErro('Erro no servidor WebSocket.');
            }

        };

        $(document).on('click', '.websocket-desconectado', function(event) {
            console.log('Tentando conectar...');
            clearTimeout(reconet);
            SocketWeb.create(metodos);
        });

        vm.init = function(){
            SocketWeb.create(metodos);
        }
    };

    Notificacao.$inject = [
		'$scope',
		'$ajax',
		'$timeout'
	];
 
    angular
    .module    ('appNotificacao' , ['angular.filter','vs-repeat','gc-ajax','gc-form','gc-find','gc-transform'])
    .controller('CrtNotificacao', Notificacao);

    angular.bootstrap(document.getElementById('idNotificacao'), ['appNotificacao']);
        
})(angular);

(function($) {

    $(document).on('click', '.btn-agendar-notificacao', function(event) {
        $(this).attr('disabled','disabled');
        var id    = $(this).data('id');
        var ag    = $('.agd-id-'+id).val();
        var balao = $('.agd-id-'+id).closest('.balao');

        var ds = {
                    AGD_ID : id,
                    AGD_TM : ag
                };

        execAjax1(
            'POST', 
            urlhost + '/_11190/agendamento',ds,
            function(data){
                var id = $(balao).data('id');

                $(balao).find('.btn-agendar-notificacao').removeAttr('disabled');
                $(balao).find('.'+id+'-fechar').trigger('click');

                showSuccess('Notificação adiada');
            }
        );

    });
    
})(jQuery);
//# sourceMappingURL=app.notificacoes.js.map
