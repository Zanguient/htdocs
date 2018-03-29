(function($) {
	
	/**
	 * Ativar Datatable.
     * @param {element} table
     * @returns {void}
     */
	function ativarDatatable(table) {
		var table = table || $('.table');
        
		var data_table = $.extend({}, table_default);
			data_table.scrollY = '70vh';

		$(table).DataTable(data_table);
	}
	
	/**
	 * Gerar histórico.
	 */
	function eventoGerarHistorico() {

		$(document)
			.on('click','.gerar-historico', function(e) {

				var hist_corpo	= $('#modal-historico .historico-corpo');
				var tabela		= $(hist_corpo).data('tabela');
				var id			= $(hist_corpo).data('id');
                
                hist_corpo.empty();

				//ajax
				var type	= "POST",
					url		= urlhost + "/historico",
					data	= {'tabela': tabela, 'id' : id},
					success	= function(data) {

						hist_corpo.html(data);
						ativarDatatable('.historico-corpo table');
						bootstrapInit();

					},
					error	= function (xhr) {
						hist_corpo.empty();
					}
				;

				execAjax1(type, url, data, success, error);

			})
		;

	}

	$(function() {
		
		eventoGerarHistorico();
		
	});
	
})(jQuery);
//# sourceMappingURL=historico.js.map
