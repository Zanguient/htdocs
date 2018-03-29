/**
 * Realiza a autenticação do operador
 * @param {json} args Argumentos da função<br/>
 * <ul>
 *      <li>
 *          <b>btn_show</b>: elemento que faz a chamada do modal de autenticação. ex: $('#autenticar')<br/>
 *          Default: $('#iniciar')
 *      </li>
 *      <li>
 *          <b>modal</b>: selector do modal. ex: $('#modal')<br/>
 *          Default: $('#modal')
 *      </li>
 *      <li>
 *          <b>input</b>: nome do campo que recebe o código de barras do operador<br/>
 *          Default: $('#operador-barra')
 *      </li>
 *      <li>
 *          <b>label</b>: label para colocar os dados do operador na tela (nome e id)<br/>
 *          Default: $('#operador')
 *      </li>
 *      <li>
 *          <b>success</b>: função caso a autenticação seja bem sucedida<br/>
 *          Default: null<br/>
 *          Recebe como parametro: retorno da consulta de autenticação
 *      </li>
 *      <li>
 *          <b>error</b>: função caso a autenticação seja mal sucessida<br/>
 *          Default: null<br/>
 *          Recebe como parametro: retorno do erro da consulta
 *      </li>
 *      </li>
 *      <li>
 *          <b>modal_show</b>: Variável que define se o modal será chamado via javascript<br/>
 *          Default: false<br/>
 *          Recebe como parametro: true ou false
 *      </li>
 * </ul>
 * @returns {void}
 */
function autenticacao(args)
{    
    args				= (typeof args              !== 'undefined') ? args				 : {};
    args.btn_show   	= (typeof args.btn_show     !== 'undefined') ? args.btn_show	 : $('#iniciar');
    args.modal      	= (typeof args.modal        !== 'undefined') ? args.modal		 : $('#modal-autenticacao');
    args.input      	= (typeof args.input        !== 'undefined') ? args.input		 : $('#operador-barra');
    args.btn_confirm   	= (typeof args.btn_confirm  !== 'undefined') ? args.btn_confirm  : $('#btn-confirmar-operador');
    args.success    	= (typeof args.success      !== 'undefined') ? args.success		 : null;
    args.error      	= (typeof args.error        !== 'undefined') ? args.error		 : null;
    args.modal_show		= (typeof args.modal_show   !== 'undefined') ? args.modal_show	 : false;
    args.verificar_up	= (typeof args.verificar_up !== 'undefined') ? args.verificar_up : false;	//verificar se o operador está vinculado a determinada UP
    args.operacao_id	= (typeof args.operacao_id  !== 'undefined') ? args.operacao_id  : 7;	//Id da operacao. Default 7 - Registrar Produção
    args.valor          = (typeof args.valor        !== 'undefined') ? args.valor        : 1;	//Valor a ser buscado. Default 1 - (SIM)
    args.label      	= (args.label === false) ? false :  args.label || $('#operador');

	function consultaAutenticacao()
	{
        var dados = {
            operacao_id		: args.operacao_id,
            valor_ext		: args.valor,
            barras			: args.input.val(),
            abort			: true,
			verificar_up	: args.verificar_up
        };

        return	execAjax1(
					'POST',
					'/_22050/autenticacao',
					dados
				);
	}
	
    /**
     * Verificar se o operador é valido
     * @returns {void}
     */
    function validar()
    { 
        
        if ( requestRunning == 1 ) return false;
        
		$.when(consultaAutenticacao())
			.done(function(resposta){

                if (resposta.length === 1) { 
                    var dados = resposta[0];
                    
                    if ( args.label ) {
                        args.label
                            .find('span.valor')
                            .attr('title', dados['OPERADOR_NOME'])
                            .html( pegarPalavra(dados['OPERADOR_NOME'], 0, 2) )
                        ;

                        args.label
                            .find('#_operador-id')
                            .val(dados['OPERADOR_ID'])
                        ;
                    }
					
                    args.success ? args.success(dados) : null;
					
                    args.modal.modal('hide');

                    args.input.val('');
                    
                }
            })
			.fail(function(xhr){
                args.input
                    .val('')
                    .focus()
                ;

                args.error ? args.error(xhr) : null;
            })
		;	
    }

    /**
     * Realiza a confirmação da autenticação
     * @returns {void}
     */
    function confirmar()
    {
        
        args.modal
            .off('keydown', args.input.selector)
            .on('keydown', args.input.selector, 'return', function() {
                validar();
            })
			.off('click', args.btn_confirm.selector)
			.on('click', args.btn_confirm.selector, function() {
                validar();
            })
        ;
    }

    /**
     * Focar no input de autenticação ao ser exibido o modal.
     * @returns {void}
     */
    function focarInput()
    {
        args.btn_show
            .click(function() {

                //foco no campo do operador
                setTimeout(function() {

                    args.input
                        .val('')
                        .focus()
                    ;

                }, 500);
            })
        ;  
    }
    
    /**
     * Verifica se deverá realizar a chamado do modal manualmente
     * @returns {undefined}
     */
    function modalShow()
    {
        if ( args.modal_show === true ) {
            args.modal
                .modal('show')
                .on('shown.bs.modal', function () {
                    args.input.focus();
                })
            ;
        }
    }
    
    focarInput();
    confirmar();
    modalShow();
}