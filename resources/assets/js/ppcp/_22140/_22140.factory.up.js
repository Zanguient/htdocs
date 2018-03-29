angular
    .module('app')
    .factory('Up', Up);
    

	Up.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$q',
        '$rootScope',
        '$filter',
        'gScope',
        'gcCollection'
    ];

function Up($ajax, $httpParamSerializer, $q, $rootScope, $filter, gScope, gcCollection) {

    /**
     * Constructor, with class name
     */
    function Up(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = [];
        
        gScope.Up = this;
    }
    
    
    /**
     * Private property
     */
    var url_base        = '/_22010/';
    var possibleRoles   = ['admin', 'editor', 'guest'];
    
    
    /**
     * Variável que recebe os valores do talão
     * @type type
     */
    var dados = {};

    /**
     * Valores para validar o status para iniciar um talão
     * @type {json}
     */
    var status_parado = 
    {
        status_talao       : [1], // 1 - Em aberto
        status_programacao : [0,1] // 0 - Parado ; 1 - Iniciado/Parado
    };

    /**
     * Valores para validar o status para pausar um talão
     * @type {json}
     */
    var status_andamento =
    {
        status_talao       : [1], // 1 - Em aberto
        status_programacao : [2] // 2 - Em Andamento
    };

    /**
     * Coleta ou atualiza da variável dados do talão selecionado
     * @returns {void}
     */
    var dadosTalao = function()
    {
        var f = gScope.Filtro;
        var t = gScope.TalaoProduzir.SELECTED;
        
        var estabelecimento_id	= f.ESTABELECIMENTO_ID;
        var gp_id				= f.GP_ID;
        var up_id				= f.UP_ID;
        var estacao				= f.ESTACAO;
        var operador_id			= $('#_operador-id').val();
        var remessa_id			= t.REMESSA_ID;
        var remessa_talao_id	= t.REMESSA_TALAO_ID;
        var talao_id			= t.ID;
        var programacao_id		= t.PROGRAMACAO_ID;
        var tempo_realizado		= t.TEMPO_REALIZADO_RELOGIO;

        dados = {
            estabelecimento_id	: estabelecimento_id,
            gp_id				: gp_id,
            up_id				: up_id,
            estacao				: estacao,
            operador_id			: operador_id,
            remessa_id			: remessa_id,
            remessa_talao_id	: remessa_talao_id,
            talao_id			: talao_id,
            programacao_id		: programacao_id,
            tempo_realizado		: tempo_realizado,
            justificativa_id    : gScope.Up.API.JUSTIFICATIVA.SELECTED.ID
        };
    };
    

    /**
     * Public method, assigned to prototype
     */
    Up.prototype = {   
        iniciar : function (args) {
            var that            = this;
            
			/**
			 * Verifica se a Estação está ocupada (em produção).
			 * @returns {ajax}
			 */
            var verificaEstacaoOcupada = function()
            {
				
				/**
				 * Consulta o estado da Estação.
				 * @returns {ajax}
				 */
				var consultar = function()
                {
					var dados = {
						estabelecimento_id : gScope.Filtro.ESTABELECIMENTO_ID,
						up_id              : gScope.Filtro.UP_ID,
						estacao_id         : gScope.Filtro.ESTACAO
					};

					return $ajax.post('/_22010/verificarEstacaoAtiva', JSON.stringify(dados), {contentType: 'application/json', progress : false});	
				};
                
                return $q(function(resolve, reject) {
                    consultar()
                        .then(function(resposta) {

                            //se a estação estiver em produção
                            if ( resposta[0]['EM_PRODUCAO'].trim() === "1" ) {
                                                             

                                var talao_id		= (typeof resposta[0]['TALAO_ID'   ] == 'string' ) ? resposta[0]['TALAO_ID'   ].trim() : resposta[0]['TALAO_ID'   ];
                                var operador_id		= (typeof resposta[0]['OPERADOR_ID'] == 'string' ) ? resposta[0]['OPERADOR_ID'].trim() : resposta[0]['OPERADOR_ID'];
                                var operador_nome	= pegarPalavra(resposta[0]['OPERADOR_NOME'], 0, 2);
                                

                                var idx = gScope.indexOfAttr(gScope.TalaoProduzir.DADOS,'ID',talao_id);
                                gScope.TalaoProduzir.selectionar(gScope.TalaoProduzir.DADOS[idx],true);   
                                
                                //colocar operador na área de destaque
                                $('#operador span.valor')
                                    .text(operador_nome)
                                ;
                                $('#_operador-id')
                                    .val(operador_id)
                                ;

                                showAlert('A Estação selecionada está em produção com o operador '+ operador_nome +'. O Talão em andamento será Retomado.');

                                resolve(true);
                            }
							
							else {
								resolve(false);
							}

                        })
                    ;
                });
			};
            
					
            /**
             * Retorno da função
             * @param {type} resolve
             * @param {type} reject
             * @returns {undefined}
             */
            return $q(function(resolve, reject) {
                verificaEstacaoOcupada()
                    .then(function(em_producao) {
                        
						//se estiver em produção, só autentica na função pausar
						if (em_producao == true) {
						
							//pausar
							that.pausar(em_producao)
								.then(function() {
							
									//iniciar (sem autenticar, pois a autenticação está em pausar)
									validaTalao(status_parado)
										.then(function() {
											
											//Registra o início
											registraUp({rota_ajax: '/_22010/acao/iniciar'})
												.then(function(){

                                                        gScope.TalaoProduzir.current(true);


                                                        resolve(true);

												}, 
												function(error) {
													reject(false);
												})
											;
				
										})
									;
									
								})
							;
						
						}                        
						else {

							//iniciar
							validaTalao(status_parado)
								.then(function() {
									
									//dados da autenticação
									var dados_autenticacao = {

										modal_show		: true,
										verificar_up	: gScope.Filtro.UP_ID,
										success			: function() {

											//Registra o início
											registraUp({rota_ajax: '/_22010/acao/iniciar'})
												.then(function(){
                                                    
                                                        gScope.TalaoProduzir.current(true);

                                                        resolve(true);

												}, 
												function(error) {
													reject(false);
												})
											;

										}
									};
									
									//se o talão está pausado
									if( gScope.TalaoProduzir.SELECTED.PROGRAMACAO_STATUS == 1 ) {
										
										//verifica permissão ps227
										if( $('#ps227').val() == '1' ) {
											
											//autenticar UP
											autenticarUp()
												.then(function() {

													//Autenticar operador
													autenticacao(dados_autenticacao);

												})
											;
											
										}
										else {
											
											//Autenticar operador
											autenticacao(dados_autenticacao);
											
										}
										
									}
									else {
										
										//autenticar UP
										autenticarUp()
											.then(function() {

												//Autenticar operador
												autenticacao(dados_autenticacao);

											})
										;
										
									}
									
								})
							;

						}
                    });					
            });	
        },
        pausar : function (em_producao) {
            var that = this;
            return $q(function(resolve, reject) {
                validaTalao(status_andamento)
                    .then(function() {

						var dados_autenticacao = {
							modal_show : true,
                            success    : function() 
                            {			
                                
                                var registrar = function () {
                                    //Registra a pausa
                                    registraUp({rota_ajax: '/_22010/acao/pausar'})
                                        .then(function(){

                                            if (em_producao !== true) {
                                                gScope.TalaoProduzir.current(true);
                                                infoDestaqueLimpar();
                                                showSuccess('Pausado com sucesso.');                  
                                            } 
                                            resolve(true);

                                        })
                                    ;  
                                };
                                
                                registrar();                                 
                                                              
                            }
						};						
						
						//Se estiver em produção, a pausa será seguida de um início, 
						//sendo assim precisa verificar a UP.
						if( em_producao ) {
							
							dados_autenticacao.verificar_up = gScope.Filtro.UP_ID;
							
							//verifica permissão ps227
							if( $('#ps227').val() == '1' ) {
								
								//Autenticar UP
								autenticarUp()
									.then(function() {

										//Autenticar operador
										autenticacao(dados_autenticacao);

									})
								;
								
							}
							else {
								
								//Autenticar operador
								autenticacao(dados_autenticacao);
								
							}
							
						}
						else {
							
							//Autenticar operador
							autenticacao(dados_autenticacao);
							
						}
                    })
                ;
            });            
        },
        finalizar : function () {
            return $q(function(resolve, reject) {
                validaTalao(status_andamento)
                    .then(function() {
                        
                        //Autenticar operador
//                        autenticacao(
//                        {
//                            modal_show : true,
//                            success    : function() 
//                            {																
                                //Registra a finalização
                                registraUp({rota_ajax: '/_22010/acao/finalizar'})
                                    .then(function(){

                                            var id                 = gScope.TalaoProduzir.SELECTED.ID;
                                            var operador_id        = $('#_operador-id').val();
                                            var operador_descricao = $('#operador').find('.valor').text();

                                            var dados = {
                                                id                  : id,
                                                operador_id         : operador_id,
                                                operador_descricao  : operador_descricao
                                            };

                                            getEtiqueta(dados)
                                                .then(function(result){

                                                    postprint(result);
                                                    infoDestaqueLimpar();
                                                })
                                                .catch(function(){

                                                    infoDestaqueLimpar();
                                                })
                                            ;

                                            //indicar que o talão não está iniciado
                                            gScope.TalaoProduzir.EM_PRODUCAO = false;
                                            gScope.TalaoProduzir.SELECTED    = null;
                                            gScope.Filtro.consultar();
                                            $(document).scrollTop(0);
                                            showSuccess('Finalizado com sucesso.');

                                            resolve(true);
                                    })
                                ;
                                
//                            }
//                        });
                    })
                ;
            });            
        },
        check : function (acao) {
                        
            var ret         = {
                status    : true,
                descricao : ''
            };
            
            var em_producao = gScope.TalaoProduzir.EM_PRODUCAO || false;
            var talao       = gScope.TalaoProduzir.SELECTED;
            
            switch(acao) {
                case 'iniciar':
                    
                    // Se não estiver na tela de produção
                    if ( gScope.Filtro.GUIA_ATIVA != 'TALAO_PRODUZIR' ) {
                        ret.status = false;
                    } else                 
                    // Se não houver talao selecionado
                    if ( talao == undefined ) {
                        ret.status = false;
                    } else                    
                    // Se não houver estacao selecionada
                    if ( gScope.Filtro.ESTACAO == '' ) {
                        ret.status = false;
                        ret.descricao = 'É necessário selecionar uma estação individual para iniciar um talão.';
                    } else                    
                    // Se estiver em produção 
                    if ( em_producao ) {
                        ret.status = false;
                    } else
                    // Se houverem consumos não disponíveis
                    if ( talao.STATUS_MP_CP == '0' ) {
                        ret.status    = false;
                        ret.descricao = 'Há consumos com materia prima indisponível';
                    } else
                    // Se a remessa estiver fora do prazo para produção
                    if ( talao.REMESSA_TIPO == '1' ) {
                        
                        if ( gScope.Filtro.GP_REMESSA_DIAS >= 0 || $('#_pu212').val() == '0' ) {
                            var remessas_normais = $filter('filter')(gScope.TalaoProduzir.DADOS,{REMESSA_TIPO : '1'});
                            var remessas_normais = $filter('orderBy')(remessas_normais,['PROGRAMACAO_DATA', '+DATAHORA_INICIO', 'REMESSA_ID', 'REMESSA_TALAO_ID']);

                            if ( $('#_pu212').val() == '0' ) {
                                var idx = remessas_normais.indexOf(talao);
                                
                                if ( idx > 0 ) {
                                    ret.status    = false;
                                    ret.descricao = 'Usuário não possui permissão para quebrar sequenciamento de talões';
                                }
                            }

                            if ( gScope.Filtro.GP_REMESSA_DIAS >= 0 ) {
                                var data_base = remessas_normais[0] != undefined ? remessas_normais[0].REMESSA_DATA : null;


                                var data_limite = moment(data_base).add(gScope.Filtro.GP_REMESSA_DIAS, 'days');

                                if ( moment(talao.REMESSA_DATA) > data_limite ) {
                                    ret.status    = false;
                                    ret.descricao = 'Remessa fora do prazo permitido de ' + gScope.Filtro.GP_REMESSA_DIAS + ' dias. Produza remessas normais com data até ' + data_limite.format("DD/MM");
                                }
                            }
                        }
                    }
                    
                    break;
                case 'pausar':
                    
                    // Se não estiver na tela de produção
                    if ( gScope.Filtro.GUIA_ATIVA != 'TALAO_PRODUZIR' ) {
                        ret.status    = false;
                    } else                 
                    if ( talao == undefined ) {
                        ret.status    = false;
                        ret.descricao = 'Selecione um talão';
                    } else                    
                    // Se estiver em produção 
                    if ( !em_producao ) {
                        ret.status    = false;
                    }
                
                    break;
                case 'finalizar':
                    
                    // Se não estiver na tela de produção
                    if ( gScope.Filtro.GUIA_ATIVA != 'TALAO_PRODUZIR' ) {
                        ret.status    = false;
                    } else                 
                    if ( talao == undefined ) {
                        ret.status    = false;
                        ret.descricao = 'Selecione um talão';
                    } else                    
                    // Se estiver em produção 
                    if ( !em_producao ) {
                        ret.status    = false;
                    }
                
                    break;
                case 'imprimir':
                    
                    var talao_produzido = gScope.TalaoProduzido.SELECTED;
                    
                    
                    // Se não estiver na tela de produção
                    if ( gScope.Filtro.GUIA_ATIVA != 'TALAO_PRODUZIDO' ) {
                        ret.status    = false;
                    } else                 
                    if ( talao_produzido == undefined ) {
                        ret.status    = false;
                        ret.descricao = 'Selecione um talão';
                    }
                    
                    break;
            }
            
            return ret;
        },
        pausarProducao : function () {
            var that = this;
            
            this.API.JUSTIFICATIVA.registrar();
        },
        setData : function (data) {
            angular.extend(this, data);
        },
        API : {
            JUSTIFICATIVA : {
                DADOS : [],
                SELECTED : {},
                consultar : function () {
                    var that = this;
                    return $q(function(resolve){

                        $ajax.get('/_22010/api/justificativa').then(function(response){
                            gcCollection.merge(that.DADOS, response.JUSTIFICATIVA, 'ID');
                            resolve(true);
                        });
                    });
                    
                },
                registrar : function () {
                    var that = this;
                    return $q(function(resolve){
                        
                        that.consultar().then(function(){
                            
                            if ( that.DADOS.length > 0 ) {
                                $('#modal-parada-justificativa').modal('show');

                                resolve(true);
                            } else {
                                that.SELECTED = {};
                                gScope.Up.pausar().then(function(){

                                    resolve(true);
                                });
                            }
                        });
                        
                    });
                    
                },
                selecionar : function ( item ) {     
                    var that = this;
                    return $q(function(resolve){
                        
                        that.SELECTED = item;
                        
                        gScope.Up.pausar().then(function(){
                            $('#modal-parada-justificativa').modal('hide');
                            that.SELECTED = {};
                            resolve(true);
                        });
                        
                    });                                       
                }
            }
        }
    };

    /**
     * Private function
     */
    
    /**
     * Realiza o registro de inicio do talão selecionado
     * @param {json} param
     * @returns {void}
     */
    function registraUp (param)
    {
        dadosTalao();

        return $q(function(resolve, reject) {
            execAjax1('POST',param.rota_ajax,dados,
            function() {
                resolve(true);
            },
            function() {
                reject(false);
            });
        });
    }

    /**
     * Realiza a validação do talão
     * @param {json} param
     * @returns {void}
     */
    function validaTalao (param)
    {   
        //Realiza a coleta dos dados do talão selecionado
        dadosTalao();

        //Realiza a cópia do dados do talão selecionado (mantem o original inalterado)
        var dadosAjax = $.extend({}, dados);
        dadosAjax.status_talao       = param.status_talao;
        dadosAjax.status_programacao = param.status_programacao;

        return $q(function(resolve) {
            
                $ajax.post('/_22010/talaoValido', JSON.stringify(dadosAjax), {contentType: 'application/json', progress : false})
                    .then(function(){
                        resolve(true);
                    })
                ;
            }, 
            function(error) {

//                new TalaoFiltrar().filtrar();	//atualiza o status
                reject(false);

            }
        );
    }

    /**
     * Autenticar UP.
     * @returns {Promise}
     */
    function autenticarUp () {

        return $q(function(resolve, reject) {

            var modal		= $('#modal-autenticar-up');
            var input_barra = $('#up-barra');

            function consultar() {

                //ajax
                var type	= 'POST',
                    url		= '/_22010/autenticarUp',
                    data	= {
                        up_barra		: $(input_barra).val(),
                        up_selecionada	: gScope.Filtro.UP_ID
                    }
                ;

                return execAjax1(type, url, data);

            }

            function autenticar() {

                $.when(consultar())
                    .done(function() {
                        $(modal).modal('hide');
                        resolve(true);
                    })
                    .fail(function() {
                        $(input_barra).val('').focus();
                    })
                ;
            }

            $(modal)
                .modal('show')
                .off('shown.bs.modal')
                .on('shown.bs.modal', function () {
                    $(input_barra).focus();
                })
                .off('hidden.bs.modal')
                .on('hidden.bs.modal', function () {
                    $(input_barra).val('');
                })
                .off('keydown', '#up-barra')
                .on('keydown', '#up-barra', 'return', function () {
                    autenticar();
                })
                .off('click', '#btn-confirmar-up')
                .on('click', '#btn-confirmar-up', function () {
                    autenticar();
                })
            ;

        });

    }


    /**
     * Static property
     * Using copy to prevent modifications to private property
     */
    Up.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    Up.build = function (data) {
        
        if (!checkRole(data.role)) {
          return;
        }
        
        return new Up(data);
    };

    /**
     * Return the constructor function
     */
    return Up;
};