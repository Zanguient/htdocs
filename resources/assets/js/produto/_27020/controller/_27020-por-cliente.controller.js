ModeloPorClienteController.$inject = ['ModeloPorClienteService'];

function ModeloPorClienteController(ModeloPorClienteService) {

	var ctrl = this;

	// MÉTODOS (REFERÊNCIAS)
	ctrl.consultarModeloPorCliente 		= consultarModeloPorCliente;
	ctrl.selecionarModeloPorCliente 	= selecionarModeloPorCliente;
	ctrl.verArquivo 					= verArquivo;
	ctrl.excluirArquivo					= excluirArquivo;
	ctrl.excluirArquivoPorUsuario		= excluirArquivoPorUsuario;
	ctrl.fecharModal					= fecharModal;

	// VARIÁVEIS
	ctrl.listaModeloPorCliente	= [];

	this.$onInit = function() {

		ctrl.pedidoIndex12040.modeloPorCliente = this;
		ctrl.pedidoItem12040.modeloPorCliente = this;

	};


	// MÉTODOS

	/**
	 * Consultar modelo por cliente.
	 */
	function consultarModeloPorCliente() {

		var filtro = {
			CLIENTE_ID: (ctrl.pedidoIndex12040.filtroCliente.cliente !== undefined) 
							? ctrl.pedidoIndex12040.filtroCliente.cliente.CODIGO 
							: 0
		};

		ModeloPorClienteService
			.consultarModeloPorCliente(filtro)
			.then(function(response) { 
				ctrl.listaModeloPorCliente = response; 
			})
		;

		ctrl.filtrarModeloPorCliente = '';
		
		setTimeout(function() {

			// Fix para vs-repeat.
			$('.table-container-modelo-por-cliente')
				.find('.scroll-table')
				.trigger('resize')
				.scrollTop(0);

			// Foco no input de filtrar.
			$('.input-filtrar-modelo').focus();

		}, 500);

	}

	/**
	 * Selecionar modelo.
	 */
	function selecionarModeloPorCliente($event, modelo) {

		// Não selecionar caso seja clicado para ver amostra.
		if ( $($event.target).hasClass('amostra') || $($event.target).hasClass('amostra-icone') )
			return false;

		ctrl.pedidoItem12040.pedidoItem.modelo = modelo;
		ctrl.fecharModal();

	}

	/**
	 * Ver amostra.
	 */
	function verArquivo(modeloId) {

		var nome = modeloId+'.JPG',
			tipo = 'JPG'
		;

		$('.visualizar-arquivo')
			.children('a')
			.attr('href', '/assets/temp/modelo/'+nome)
			.parent()
			.children('input.arquivo_nome_deletar')
			.val(nome)
			.parent()
			.children('object')
			.attr('data', '/assets/temp/modelo/'+nome)
			.removeClass()
			.addClass(tipo)
			.parent()				
			.fadeIn()
		;
		/*
	    //ajax
		var type	= 'POST',
			url		= '/_27020/verArquivo',
			data	= {
				modeloId: modeloId
			},
			success = function(data) {

	        	var nome = data,
					tipo = nome.split(".").pop()
				;
		
				$('.visualizar-arquivo')
					.children('a')
					.attr('href', '/assets/temp/modelo/'+nome)
					.parent()
					.children('input.arquivo_nome_deletar')
					.val(nome)
					.parent()
					.children('object')
					.attr('data', '/assets/temp/modelo/'+nome)
					.removeClass()
					.addClass(tipo)
					.parent()				
					.fadeIn()
				;

	        }
	    ;
		
		execAjax1(type, url, data, success, null, null, false);
		*/
	}

	/**
	 * Excluir amostra.
	 */
	function excluirArquivo() {

		$('.visualizar-arquivo').fadeOut();

	    //ajax
		var type	= 'POST',
			url		= '/_27020/excluirArquivo',
			data	= {
				arquivo: $('.arquivo_nome_deletar').val()
			}
	    ;
		
		execAjax1(type, url, data, null, null, null, false);
	}

	function excluirArquivoPorUsuario() {

	    //ajax
		var type	= 'POST',
			url		= '/_27020/excluirArquivoPorUsuario'
	    ;
		
		execAjax1(type, url, null, null, null, null, false);
	}

	/**
	 * Fechar modal.
	 */
	function fecharModal() {

		$('#modal-consultar-modelo-por-cliente')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast')
		;

		ctrl.excluirArquivoPorUsuario();
	}

}