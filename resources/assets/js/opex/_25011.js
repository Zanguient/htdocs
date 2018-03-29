/**
 * _25011 - Formulários
 */
;(function(angular) {

	/**
	 * Controller.
	 */
	var ctrl = function($ajax, $filter, $timeout) {

		var vm = this;

		vm.tipoTela			= "incluir";
		vm.listaFormulario	= [];	// Lista de formulários cadastrados.
		vm.formulario		= {};	// Formulário a ser exibido.
		vm.pergunta 		= [];	// Pergunta a ser exibida.
		vm.autenticacao		= {};	// Autenticação.
		
		/**
		 * Listar formulários.
		 */
		vm.listarFormulario = function() {
            
			$ajax
				.post('/_25011/listar')
				.then(function(response) {

					vm.listaFormulario = response;					

				})
			;

		};

		/**
		 * Exibir formulário.
		 */
		vm.exibirFormulario = function(formulario) {
			
			var pergunta = []
				resposta = []
			;
			
			vm.tipoTela 								= (formulario.TIPO == 2) ? 'exibir' : 'alterar';
			vm.formulario.ID 							= formulario.ID;
			vm.formulario.TIPO 							= formulario.TIPO;
			vm.formulario.TITULO 						= formulario.TITULO;
			vm.formulario.DESCRICAO 					= formulario.DESCRICAO;
			vm.formulario.STATUS 						= formulario.STATUS.trim();
			vm.formulario.PERIODO_INI					= moment(formulario.PERIODO_INI).format('DD/MM/YYYY');
			vm.formulario.PERIODO_FIM					= moment(formulario.PERIODO_FIM).format('DD/MM/YYYY');
			vm.formulario.DESTINATARIO_TIPO				= formulario.DESTINATARIO_TIPO.trim();
			vm.formulario.DESTINATARIO_STATUS_RESPOSTA	= formulario.DESTINATARIO_STATUS_RESPOSTA.trim();

			vm.pergunta = [];

			// Perguntas.
			for (var j in vm.listaFormulario.PERGUNTA) {

				pergunta 						= vm.listaFormulario.PERGUNTA[j];
				pergunta.ALTERNATIVA 			= [];
				pergunta.ALTERNATIVA_EXCLUIR 	= [];
				pergunta.RESPOSTA 				= [];

				if ( parseInt(pergunta.FORMULARIO_ID) == parseInt(formulario.ID) ) {

					// Serve apenas para exigir que a resposta tenha uma justificativa (VIEW).
					pergunta.JUSTIFICATIVA_OBRIGATORIA = 0;

					// Alternativas.
					for (var k in vm.listaFormulario.ALTERNATIVA) {

						alternativa = vm.listaFormulario.ALTERNATIVA[k];

						if ( ( parseInt(alternativa.FORMULARIO_ID) == parseInt(formulario.ID) ) 
						  && ( parseInt(alternativa.FORMULARIO_PERGUNTA_ID) == parseInt(pergunta.ID) ) 
						) {
							pergunta.ALTERNATIVA.push(alternativa);
						}

					}

					// Respostas.
					for (var l in vm.listaFormulario.RESPOSTA) {

						resposta = vm.listaFormulario.RESPOSTA[l];

						if ( ( parseInt(resposta.FORMULARIO_ID) == parseInt(formulario.ID) ) 
						  && ( parseInt(resposta.FORMULARIO_PERGUNTA_ID) == parseInt(pergunta.ID) ) 
						) {
							//vm.tipoTela = 'exibir';		//Usuário já respondeu.
							pergunta.RESPOSTA.push(resposta);
						}

					}

					vm.pergunta.push(pergunta);

				}

			}

			$('#modal-create')
				.modal('show')
				.find('.modal-body')
				.animate({ scrollTop: 0 }, 'fast')
			;

		};

		/**
		 * Limpar tela.
		 */
		vm.limparTela = function() {

			vm.formulario		= {};
			vm.pergunta 		= [];
			vm.autenticacao		= {};

		};

		/**
		 * Limpar campos.
		 */
		vm.limparCampos = function() {

			var perg = '';

			if (vm.autenticacao.AUTENTICADO)
				vm.tipoTela	= 'exibir';
			
			vm.autenticacao = {};

           	// Limpar alternativas.
			for(var i in vm.pergunta) {

				perg = vm.pergunta[i];

				perg.RESPOSTA[0].ALTERNATIVA_ESCOLHIDA_ID 	= '';
				perg.RESPOSTA[0].DESCRICAO 					= '';

			}

		};

		/**
		 * Autenticação.
		 */
		vm.autenticar = function() {

			var dados = {
				CODIGO 			: vm.autenticacao.CODIGO,
				FORMULARIO_ID 	: vm.formulario.ID
			};

			$ajax
				.post('/_25011/autenticarColaborador', JSON.stringify(dados), {contentType: 'application/json'})
				.then(function(response) {

					vm.autenticacao.AUTENTICADO 		= response.AUTENTICADO;
					vm.autenticacao.COLABORADOR_ID	 	= response.COLABORADOR_ID;
					vm.autenticacao.COLABORADOR_NOME 	= response.COLABORADOR_NOME;
					vm.autenticacao.COLABORADOR_CCUSTO	= response.COLABORADOR_CCUSTO;

					if (vm.autenticacao.AUTENTICADO) 
						vm.tipoTela = 'alterar';

				})
			;

		};

		/**
		 * Atalhos para autenticação.
		 */
		vm.atalhoAutenticar = function($event) {

			if ($event.keyCode == 13)
				vm.autenticar();

		};

		/**
		 * Ações no sucesso ao gravar resposta.
		 */
		vm.sucessoGravarResposta = function() {

           	showSuccess('Formulário finalizado com sucesso.');

           	$('#modal-create').modal('hide');
           	vm.limparCampos();
            vm.listarFormulario();

		};

		/**
		 * Gravar resposta.
		 */
		vm.gravarResposta = function() {

			var dados 	= {
		            pergunta 	: vm.pergunta,
		            autenticacao: vm.autenticacao
		        },
	        	url 	= '/_25011/gravarResposta'
	        ;
	    
	        $ajax
	        	.post(url, JSON.stringify(dados), {contentType: 'application/json'})
	            .then(function(response) {  

					vm.sucessoGravarResposta();

	            })
	        ;

	    };

		vm.listarFormulario();
	};

	//Injetando Components ao Controller.
	ctrl.$inject = ['$ajax', '$filter', '$timeout'];

    angular
    	.module('app', ['vs-repeat', 'gc-ajax', 'gc-find', 'gc-transform'])
    	.controller('ctrl', ctrl)
	; 
    
    // angular.bootstrap(document.getElementById('main'), ['app']);

})(angular);