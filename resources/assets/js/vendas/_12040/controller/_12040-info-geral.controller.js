
	InfoGeralController.$inject = ['$scope','InfoGeralService', 'Historico'];

	function InfoGeralController($scope, InfoGeralService, Historico) {

		var ctrl = this;

		// MÉTODOS (REFERÊNCIAS).
		ctrl.consultarInfoGeral = consultarInfoGeral;
		ctrl.marcarProgramado 	= marcarProgramado;
		ctrl.ngWinPopUp         = ngWinPopUp;
		ctrl.gerarChave 		= gerarChave;
		ctrl.gerarPDF           = gerarPDF;
        ctrl.Historico          = new Historico('$ctrl.Historico',$scope);

		// VARIÁVEIS
		ctrl.infoGeral 			= {};

		this.$onInit = function() {

			ctrl.pedidoIndex12040.infoGeral  = this;
			ctrl.pedidoCreate12040.infoGeral = this;

			ctrl.FILE = {VISIVEL: false, DATA: null}
		};


		// MÉTODOS

		/**
		 * Consultar Informações Gerais.
		 */
		function consultarInfoGeral() {

			var clienteId = ctrl.pedidoIndex12040.filtroCliente.cliente
							? parseInt(ctrl.pedidoIndex12040.filtroCliente.cliente.CODIGO) 
							: 0
			;
 
			InfoGeralService
				.consultarInfoGeral(clienteId)
				.then(function(response) { 
					ctrl.infoGeral = response;
					$('#modal-create').modal('show');
				})
			;

		}

		/**
		 * Consultar Informações Gerais.
		 */
		function gerarPDF() {

			var dados = {
				PEDIDO : ctrl.infoGeral,
				ITENS  : ctrl.pedidoIndex12040.pedidoItemEscolhido.pedidoItemEscolhido
			}

			console.log(InfoGeralService);

			InfoGeralService
				.getPDF(dados)
				.then(function(response) { 
					
					ctrl.FILE.DATA   = response;
					

					$('.visualizar-arquivo').css('display','block');
					$('.visualizar-arquivo').find('object').attr('data',response);
					$('.visualizar-arquivo').find('a').attr('href',response);
					
					if(ctrl.FILE.VISIVEL == false){
					  $(document).on('click','.esconder-arquivo', function(){
					  	$('.visualizar-arquivo').css('display','none');
					  });
					}

					ctrl.FILE.VISIVEL = true;
					

				})
			; 

			//console.log();
			//console.log();

		}

		/**
		 * Gerar chave para liberação de nova quantidade mínima para cor.
		 */
		function gerarChave() {
 
			InfoGeralService
				.gerarChave()
				.then(function(response) { 
					ctrl.infoGeral.CHAVE = response.CHAVE;
				})
			;
		}

		/**
		 * Ações ao marcar a opção 'Programado'.
		 */
		function marcarProgramado() {
			
			if (ctrl.infoGeral.PROGRAMADO == '1') {

				if (ctrl.infoGeral.DATA_CLIENTE === undefined) {

					ctrl.infoGeral.DATA_MIN_CLIENTE 	= moment().add(1, "month").format('YYYY-MM-DD');
					ctrl.infoGeral.DATA_CLIENTE 		= moment().add(1, "month").toDate();
				}

			}
			// else {

			// 	ctrl.infoGeral.DATA_MIN_CLIENTE 	= '';
			// 	ctrl.infoGeral.DATA_CLIENTE 		= '';

			// }

		}
        
        
        function ngWinPopUp(url,id,params) {
            var modal = winPopUp(url,id,params);
            
//            modal.onbeforeunload = function(){ 
//                $scope.$apply(function(){
//
//                    ctrl.pedidoIndex12040.consultarPedido().then(function(response){
//                        var objeto = selectById(response,id,'PEDIDO');
//
//                        ctrl.pedidoIndex12040.exibirPedido(objeto);
//                    });
//                    // processar após fechar modal
//                });
//            };
        };        

	}