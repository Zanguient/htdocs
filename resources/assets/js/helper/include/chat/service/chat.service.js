
ChatService.$inject = ['$ajax', '$filter'];

function ChatService($ajax, $filter) {

	// MÉTODOS (REFERÊNCIAS)
	this.gravar 					= gravar;
	this.consultarHistoricoConversa = consultarHistoricoConversa;

	// MÉTODOS

	/**
	 * Gravar conversa.
	 */
	function gravar(dadoMsg) {

        var url = '/chat/gravar',
        	param = {
				REMETENTE_ID	: dadoMsg.DE,
				DESTINATARIO_ID	: dadoMsg.PARA,
				MENSAGEM		: dadoMsg.MSG
			}
		;

        return $ajax.post(url, JSON.stringify(param), {contentType: 'application/json', progress: false});
    }

    /**
	 * Consultar histórico de conversas.
	 */
	function consultarHistoricoConversa(param) {

        var url = '/chat/consultarHistoricoConversa';

        return $ajax.post(url, JSON.stringify(param), {contentType: 'application/json'});
    }
}