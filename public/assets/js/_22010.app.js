/**
 * _22010 - Registro de Produção
 */
'use strict';

angular
	.module('app', [
		'vs-repeat', 
        'gc-find',
		'gc-ajax',
		'gc-transform',
		'gc-form',
		'gc-utils',
        'ngSanitize',
        'gc-pessoal'
	])
;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


angular
    .module('app')
    .service('Consulta', Consulta);
    
    
	Consulta.$inject = [];

	function Consulta() {
        
    	// MÉTODOS (REFERÊNCIAS)
        this.consultarUsuario = consultarUsuario;
    	this.consultarTarefa  = consultarTarefa;

    	// MÉTODOS
    	
    	/**
    	 * Consultar usuário.
    	 */
	    function consultarUsuario() {

	    	return $ajax.post('/_11010/listarTodos', null, {contentType: 'application/json'});
		}

        /**
         * Consultar tarefa.
         */
        function consultarTarefa(param) {

            return $ajax.post('/_29010/consultarTarefa', JSON.stringify(param), {contentType: 'application/json'});
        }
	}    
angular
    .module('app')
    .factory('TotalizadorDiario', TotalizadorDiario);
    

	TotalizadorDiario.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$timeout',
        'gScope',
        'gcCollection',
        'gcObject'
    ];

function TotalizadorDiario($ajax, $q, $rootScope, $timeout, gScope, gcCollection, gcObject) {

    /**
     * Constructor, with class name
     */
    function TotalizadorDiario(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = [];
        this.TOTALIZADOR = [];
    }
    
    /**
     * Private property
     */
    var url_base        = '_22010/api/totalizador-diario';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    TotalizadorDiario.prototype = { 
        consultar : function (args) {
            var that = this;
            return $q(function(resolve) {
        
                var args = {
                    estabelecimento_id	: $('.estab').val(),
                    gp_id               : $('._gp_id').val(),
                    perfil_up_id		: $('._perfil_up_id').val(),
                    up_id				: $('._up_id').val(),
                    up_todos			: $('._up_todos').val(),
                    estacao				: $('._estacao_id').val(),
                    estacao_todos		: $('._estacao_todos').val(),
                    data_ini			: $('.filtro-periodo .data-ini').val(),
                    data_fim			: $('.filtro-periodo .data-fim').val(),
                    turno				: $('#turno').val(),
                    turno_hora_ini		: $('#turno').find(':selected').data('hora-ini'),
                    turno_hora_fim		: $('#turno').find(':selected').data('hora-fim')
                };
                 

                $ajax.post(url_base, args).then(function(res){

                    that.DADOS = res;
                    
                    that.totalizadorCalc();
                    
                    resolve(true);
                });
            });            
               
        },
        totalizadorCalc : function () {
            var that = this;
            var dados = that.DADOS;
        
            var total = {
                CAPACIDADE_DISPONIVEL       : 0,
                CARGA_PROGRAMADA            : 0,
                QUANTIDADE_TALAO_PROGRAMADA : 0,
                QUANTIDADE_CARGA_PROGRAMADA : 0,
                QUANTIDADE_PARES_PROGRAMADA : 0,
                PERC_CARGA_PROGRAMADA       : 0,
                CARGA_PENDENTE              : 0,
                QUANTIDADE_TALAO_PENDENTE   : 0,
                QUANTIDADE_CARGA_PENDENTE   : 0,
                QUANTIDADE_PARES_PENDENTE   : 0,
                CARGA_UTILIZADA             : 0,
                QUANTIDADE_TALAO_UTILIZADA  : 0,
                QUANTIDADE_CARGA_UTILIZADA  : 0,
                QUANTIDADE_PARES_UTILIZADA  : 0,
                EFICIENCIA                  : 0,
                PERC_APROVEITAMENTO         : 0,
                UM                          : ''
            };

            if ( dados != undefined ) {
                
                var qtd = 0;
                
                for ( var i in dados ) {
                    var item = dados[i];
                    
                    qtd++;
           
                    total.CAPACIDADE_DISPONIVEL       += parseFloat(item.CAPACIDADE_DISPONIVEL      || 0);
                    total.CARGA_PROGRAMADA            += parseFloat(item.CARGA_PROGRAMADA           || 0);
                    total.QUANTIDADE_TALAO_PROGRAMADA += parseFloat(item.QUANTIDADE_TALAO_PROGRAMADA|| 0);
                    total.QUANTIDADE_CARGA_PROGRAMADA += parseFloat(item.QUANTIDADE_CARGA_PROGRAMADA|| 0);
                    total.QUANTIDADE_PARES_PROGRAMADA += parseFloat(item.QUANTIDADE_PARES_PROGRAMADA|| 0);
                    total.PERC_CARGA_PROGRAMADA       += parseFloat(item.PERC_CARGA_PROGRAMADA      || 0);
                    total.CARGA_PENDENTE              += parseFloat(item.CARGA_PENDENTE             || 0);
                    total.QUANTIDADE_TALAO_PENDENTE   += parseFloat(item.QUANTIDADE_TALAO_PENDENTE  || 0);
                    total.QUANTIDADE_CARGA_PENDENTE   += parseFloat(item.QUANTIDADE_CARGA_PENDENTE  || 0);
                    total.QUANTIDADE_PARES_PENDENTE   += parseFloat(item.QUANTIDADE_PARES_PENDENTE  || 0);
                    total.CARGA_UTILIZADA             += parseFloat(item.CARGA_UTILIZADA            || 0);
                    total.QUANTIDADE_TALAO_UTILIZADA  += parseFloat(item.QUANTIDADE_TALAO_UTILIZADA || 0);
                    total.QUANTIDADE_CARGA_UTILIZADA  += parseFloat(item.QUANTIDADE_CARGA_UTILIZADA || 0);
                    total.QUANTIDADE_PARES_UTILIZADA  += parseFloat(item.QUANTIDADE_PARES_UTILIZADA || 0);
                    total.EFICIENCIA                  += parseFloat(item.EFICIENCIA                 || 0);
                    total.PERC_APROVEITAMENTO         += parseFloat(item.PERC_APROVEITAMENTO        || 0);
                }
//                console.log(total.PERC_CARGA_PROGRAMADA);
//                console.log(qtd);
                
                total.PERC_CARGA_PROGRAMADA = total.PERC_CARGA_PROGRAMADA / qtd;
                total.EFICIENCIA            = total.EFICIENCIA            / qtd;
                total.PERC_APROVEITAMENTO   = total.PERC_APROVEITAMENTO   / qtd;
                
                total.UM = dados[0].UM;
            }
                 
            that.TOTALIZADOR = [total];

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
    TotalizadorDiario.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    TotalizadorDiario.build = function (data) {
        
        if (!checkRole(data.role)) {
          return;
        }
        
        return new TotalizadorDiario(data);
    };

    /**
     * Return the constructor function
     */
    return TotalizadorDiario;
};
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
angular
    .module('app')
    .factory('TalaoFicha', TalaoFicha);
    

	TalaoFicha.$inject = [
        '$ajax',
        '$timeout',
        '$q',
        'gScope',
        'gcCollection'
    ];

function TalaoFicha($ajax,$timeout,$q,gScope,gcCollection) {

    /**
     * Constructor, with class name
     */
    function TalaoFicha(data) {
        if (data) {
            this.setData(data);
        }
    }
    
    /**
     * Private property
     */
    var url_base        = '_22010/defeitos';

    /**
     * Public method, assigned to prototype
     */
    TalaoFicha.prototype = {
        OLD_VALUE : 0,
        selectionar : function (ficha) {
            
            if ( ficha != undefined ) {
            
                this.SELECTED       = ficha;
                
                this.OLD_VALUE = ficha.QUANTIDADE;
                $('input[ficha="' + ficha.TIPO_ID  + '"').select();

            }
                
        }, 
        gravar : function (ficha) {
            return $q(function(resolve){
                
                var dados = {
                    ID                  : gScope.TalaoProduzir.SELECTED.ID,
                    REMESSA_ID          : gScope.TalaoProduzir.SELECTED.REMESSA_ID,
                    REMESSA_TALAO_ID    : gScope.TalaoProduzir.SELECTED.REMESSA_TALAO_ID,
                    MODELO_ID           : gScope.TalaoProduzir.SELECTED.MODELO_ID,
                    TIPO_ID             : ficha.TIPO_ID,
                    QUANTIDADE          : ficha.QUANTIDADE
                };
                
                $ajax.post('/_22010/api/ficha/post',dados,{progress : false}).then(function(response){
                    
                    gcCollection.merge(gScope.TalaoComposicao.DADOS.FICHA, response.FICHA, 'TIPO_ID');                    
                    
                    resolve(true);
                });
            });
        },
        keydown : function (ficha,$event,model_old) {
            var that = this;
                /* Verifica se existe um evento */
                if ( !($event === undefined) ) {

                    if ( $event.key == 'ArrowUp' || $event.key == 'Enter' || $event.key == 'ArrowDown' ) {
                        $event.preventDefault();
                        $event.stopPropagation();
                        
                        var idx_selected = that.FILTERED.indexOf(that.SELECTED);

                        switch ($event.key) {
                            case 'Enter':
                            case 'ArrowDown':
                                var idx = idx_selected+1;
                                break;
                            case 'ArrowUp':
                                var idx = idx_selected-1;
                                break;
                        }
                        
                        var tabIndex = function (idx) {
                            if (  that.FILTERED[idx] != undefined ) { 
                                $('input[ficha="' + that.FILTERED[idx].TIPO_ID  + '"').select();
                            }
                        };
                        
                        if ( parseFloat(that.OLD_VALUE) != parseFloat(ficha.QUANTIDADE) ) {
                            
                            that.OLD_VALUE = ficha.QUANTIDADE;
                            
                            console.log('val 1 ' + parseFloat(that.OLD_VALUE));
                            console.log('val 2 ' + parseFloat(ficha.QUANTIDADE));
                            that.gravar(ficha).then(function(){                        
                                tabIndex(idx);
                            });
                        } else {
                            tabIndex(idx);
                        }  
                    }
                }            
        },
        setData: function(data) {
            angular.extend(this, data);
        }
    };

    /**
     * Private function
     */
//    function func(role) {
//      
//    }

    /**
     * Static property
     * Using copy to prevent modifications to private property
     */
//    TalaoFicha.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
//    TalaoFicha.build = function (data) {
//        
//        if (!checkRole(data.role)) {
//          return;
//        }
//        
//        return new TalaoFicha(data);
//    };

    /**
     * Return the constructor function
     */
    return TalaoFicha;
};
angular
    .module('app')
    .factory('TalaoDefeito', TalaoDefeito);
    

	TalaoDefeito.$inject = [        
        '$ajax',
        '$q',
        '$timeout',
        'gScope',
        'gcObject',
        'gcCollection'
    ];

function TalaoDefeito($ajax,$q,$timeout,gScope,gcObject,gcCollection) {

    /**
     * Constructor, with class name
     */
    function TalaoDefeito(data) {        
        if (data) {
            this.setData(data);
        }
        
        this.dynanmicEvents();
    }

        
    /**
     * Private property
     */
    var url_base        = '_22010/api/talao/consumo';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    TalaoDefeito.prototype = {
        justificativa: null,
        justiOperador: null,
        OBJ_EDITING : {
            QUANTIDADE : null,
            OBSERVACAO : ''
        },
        openRegistrarProblema : function () {
            that = this;

            var dados_autenticacao = {
                operacao_id   : 29,
                modal_show    : true,
                verificar_up  : gScope.Filtro.UP_ID,
                success       : function(e) {

                    that.justiOperador = e.OPERADOR_ID;
                    that.justificativas();

                    $('#modal-justificar').modal();
                }
            };

            autenticacao(dados_autenticacao);
            
        },
        justificar: function(descricao, justificativa){
            var that = this;

            addConfirme('Justificativa',
                'Observação para o registro <b>' + descricao + '<b>:'+
                '<p>'+
                '<input type="search" class="form-control input-medio justificativa_ineficiencia_reg" maxlength="90" autocomplete="off">'+
                ''

                ,[obtn_ok,obtn_cancelar],
            [
                {ret:1,func:function(e){

                    var ds = {
                            TABELA_ID       : gScope.TalaoProduzir.SELECTED.REMESSA_TALAO_ID,
                            TABELA          : 'PRODUCAO',
                            STATUS          : justificativa,
                            VINCULO_ID      : gScope.TalaoProduzir.SELECTED.REMESSA_ID,
                            SUBVINCULO_ID   : gScope.TalaoProduzir.SELECTED.ID,
                            OPERADOR_ID     : that.justiOperador,
                            OBSERVACAO      : $('.justificativa_ineficiencia_reg').val()
                        };

                    $ajax.post('/_22130/justIneficiencia',JSON.stringify(ds),{contentType: 'application/json'})
                        .then(function(response) {
                            
                            if(response.length > 0){
                                if((gScope.TalaoProduzir.SELECTED.JUSTIFICATIVA + '').length > 0 && gScope.TalaoProduzir.SELECTED.JUSTIFICATIVA != null){
                                    gScope.TalaoProduzir.SELECTED.JUSTIFICATIVA = gScope.TalaoProduzir.SELECTED.JUSTIFICATIVA +',<br>'+ descricao + ' - '+ ds.OBSERVACAO ;
                                }else{
                                    gScope.TalaoProduzir.SELECTED.JUSTIFICATIVA = descricao + ' - '+ ds.OBSERVACAO ;
                                }
                            }

                            $('#modal-justificar').modal('hide');

                            showSuccess('Talão Justificado!');
                        }
                    );

                }},
                {ret:2,func:function(e){


                }},
                ]  
            );

            setTimeout(function(){$('.justificativa_ineficiencia_reg').focus();},300);
        },
        justificativas: function(){
            that = this;
            $ajax.post('/_22010/consultaJustificativa',{}).then(function(response){
                that.justificativa = response;       
            });
        },
        registrar : function () {
            
            var that          = this;
            var talao_detalhe = gScope.TalaoDetalhe.SELECTED;
            var objDefeito    = this.API.DEFEITOS;
            that.OBJ_EDITING = {};
            if ( talao_detalhe.QUANTIDADE_PRODUCAO > 0 ) {
                showErro('Para registrar defeitos, é necessário que não haja quantidade produzida. Operação cancelada.');
                return false;
                
            }
            
            that.OBJ_EDITING = {};                                     
            
            objDefeito.consultar().then(function(){
                
                if ( !(objDefeito.DADOS.length > 0) ) {
                    showErro('Não há defeitos cadastrados para esta familía de produtos');
                    return false;
                }  
            
                $('#modal-registrar-defeito')
                    .modal('show')
                    .one('shown.bs.modal', function(){
                            var table = $(this).find('input').first().focus();                        
                    })
                ;
            });
       

        },
        gravar : function () {
            
            var that = this;
            
            that.OBJ_EDITING.DEFEITO_ID               = that.API.DEFEITOS.SELECTED.DEFEITO_ID;
            that.OBJ_EDITING.ESTABELECIMENTO_ID       = gScope.Filtro.ESTABELECIMENTO_ID;
            that.OBJ_EDITING.GP_ID                    = gScope.Filtro.GP_ID;
            that.OBJ_EDITING.REMESSA_ID               = gScope.TalaoDetalhe.SELECTED.REMESSA_ID;
            that.OBJ_EDITING.REMESSA_TALAO_DETALHE_ID = gScope.TalaoDetalhe.SELECTED.REMESSA_TALAO_DETALHE_ID;
            that.OBJ_EDITING.PRODUTO_ID               = gScope.TalaoDetalhe.SELECTED.PRODUTO_ID;
            that.OBJ_EDITING.TAMANHO                  = gScope.TalaoDetalhe.SELECTED.TAMANHO;
            that.OBJ_EDITING.OPERADOR_ID              = $('#_operador-id').val();
            
            return $q(function(resolve){
                $ajax.post('/_22010/api/defeitos/post',that.OBJ_EDITING).then(function(){
                    
                    gScope.TalaoProduzir.current(true).then(function(){

                        $('#modal-registrar-defeito')
                            .modal('hide');                    
                    });   
                    
                    resolve(true);
                });
            });
            

        },
        excluir : function (id) {
            var that = this;

            return $q(function(resolve){
                $ajax.post('/_22010/api/defeitos/exclude',{DEFEITO_TRANSACAO_ID : id}).then(function(){
                    
                    $('.popover').remove();
                    
                    gScope.TalaoProduzir.current(true).then(function(){

                        $('#modal-registrar-defeito')
                            .modal('hide');                    
                    });   
                    
                    resolve(true);
                });
            });
        },
        dynanmicEvents : function () {
            
            var that = this;
            
            /**
             * Ativa o evento de exclusão de defeitos
             */
            $(document).off('click', '.defeito-excluir').on('click', '.defeito-excluir', function() {
                that.excluir($(this).data('item-id'));
            });      
        },
        API : {
            DEFEITOS : {
                DADOS : [],
                SELECTED : {},
                M_FILTRO : '',
                consultar : function () {
                    var that = this;
                    var objDefeito = this;
                    return $q(function(resolve){

                        that.M_FILTRO = '';
                        that.SELECTED = null;
                        
                        var data = {
                            FAMILIA_ID : gScope.TalaoDetalhe.SELECTED.FAMILIA_ID
                        };
                        
                        $ajax.post('/_22010/api/defeitos', data).then(function(resposta){

                            objDefeito.DADOS = resposta;

                            $timeout(function(){
                                if ( that.FILTERED.length > 0 ) {
                                    that.SELECTED = that.FILTERED[0];
                                }
                            });

                            resolve(true);
                        });  
                    });   
                },
                selecionar : function (defeito) {
                    this.SELECTED = defeito;
                },
                keydown : function (defeito, $event) {
                    
                    /* Verifica se existe um evento */
                    if ( !($event === undefined) ) {

                        if ( $event.key == 'Enter' ) {
                            var table = $('#modal-registrar-defeito .table-registrar-defeito');
                            var input = table.find('input').first();
                            
                            input.focus();
                        }
                    }    
                },
                mFiltroChange : function(oldValue) {
                    var that = this;
                    
                    if ( that.M_FILTRO.length > oldValue.length ) {
                        $timeout(function(){
                            if ( that.FILTERED.length > 0 ) {
                                that.SELECTED = that.FILTERED[0];
                            }
                        });
                    }
                }
            }
        },
        selectionar : function (consumo) {
            this.SELECTED = consumo;
        },
        setData: function(data) {
            angular.extend(this, data);
        }
    };

    /**
     * Private function
     */
    function fn () {
        
    }

    /**
     * Static property
     * Using copy to prevent modifications to private property
     */
    TalaoDefeito.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    TalaoDefeito.build = function (data) {
        
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
    return TalaoDefeito;
};

        
angular
    .module('app')
    .factory('TalaoDetalhe', TalaoDetalhe);
    

	TalaoDetalhe.$inject = [
        '$ajax',
        '$timeout',
        'gScope'
    ];

function TalaoDetalhe($ajax,$timeout,gScope) {

    /**
     * Constructor, with class name
     */
    function TalaoDetalhe(data) {
        if (data) {
            this.setData(data);
        }
    }
    
    /**
     * Private property
     */
    var url_base        = '_22010/defeitos';

    /**
     * Public method, assigned to prototype
     */
    TalaoDetalhe.prototype = {
        QUANTIDADE_ALTERANDO : [],
        QUANTIDADE_ALTERNATIVA_ALTERANDO : [],
        selectionar : function (detalhe) {
            
            if ( detalhe != undefined ) {
            
                this.SELECTED       = detalhe;
                this.SELECTED_RADIO = detalhe.ID;

            }
                
        }, 
        alterarQuantidade : function (detalhe) {
            
//            if ( gScope.TalaoDetalhe.SELECTED != detalhe ) gScope.TalaoDetalhe.selectionar(detalhe);
            
            this.QUANTIDADE_ALTERANDO.push(detalhe);
            detalhe.EDITANDO_QUANTIDADE = true;
            
            $timeout(function(){
                $('#detalhe tr.selected td.qtd input.qtd').select();
            });
            
        },
        cancelarQuantidade : function (detalhe) {
            
//            if ( gScope.TalaoDetalhe.SELECTED != detalhe ) gScope.TalaoDetalhe.selectionar(detalhe);
            
            detalhe.M_QUANTIDADE_PRODUCAO = detalhe.QUANTIDADE_PRODUCAO;
            detalhe.EDITANDO_QUANTIDADE = false; 
            
            var index = this.QUANTIDADE_ALTERANDO.indexOf(detalhe);
            this.QUANTIDADE_ALTERANDO.splice(index, 1);        
        },
        gravarQuantidade : function (detalhe,$event) {

//            if ( gScope.TalaoDetalhe.SELECTED != detalhe ) gScope.TalaoDetalhe.selectionar(detalhe);
            
            var that               = this;
            var btn                = $('#detalhe tr.selected .qtd .qtd-gravar');
            var qtd                = detalhe.M_QUANTIDADE_PRODUCAO || 0;
            var talao_id	       = detalhe.ID;
            var qtd_proj	       = (detalhe.QUANTIDADE - detalhe.QUANTIDADE_DEFEITO).toFixed(4);
            var qtd_unim	       = detalhe.UM;
            var qtd_max            = detalhe.TOLERANCIAM;
            var qtd_min            = detalhe.TOLERANCIAN;
            var qtd_tip            = detalhe.TOLERANCIA_TIPO;
            var sobra_tipo         = detalhe.SOBRA_TIPO;
            var qtd_aproveitamento = detalhe.APROVEITAMENTO_ALOCADO;
            var REMESSA_ID         = detalhe.REMESSA_ID;
            var REMESSA_TALAO_ID   = detalhe.REMESSA_TALAO_ID;
            var cmp_sob            = detalhe.QUANTIDADE_SOBRA;

            var cb = cmp_sob;

            var input		= 'input.qtd';
            var url			= '/_22010/alterarQtdTalaoDetalhe';
            var retorno		= 'QUANTIDADE';

            //converter antes para reutilizar
            var valide1 = isNaN(parseFloat(qtd));
            var valide2 = isNaN(parseFloat(qtd_proj));
            var valide3 = isNaN(parseFloat(qtd_aproveitamento));

            //converter antes para reutilizar
            var v1 = parseFloat(qtd);
            var v2 = parseFloat(qtd_proj);
            var v4 = parseFloat(qtd_aproveitamento);

            // Qtd. Proj.  |  Qtd. Aprov.  |  Qtd. Prod.
            //        50   |          20   |         10
            //	
            // 10 - (50 - 20) = -20 SOBRA      
            //  
            // Qtd. Proj.  |  Qtd. Aprov.  |  Qtd. Prod.
            //        50   |          20   |         30
            //	
            // 30 - (50 - 20) = 0 SOBRA
            //  
            // Qtd. Proj.  |  Qtd. Aprov.  |  Qtd. Prod.
            //        50   |          20   |         40
            //	
            // 40 - (50 - 20) = 10 SOBRA

            var v3 = (v1-(v2-v4)).toFixed(2);

            if ( (valide1 === false) && (valide2 === false) && (valide3 === false)){

                if( qtd_tip == 'Q'){
                    var toleranciamais = parseFloat(qtd_max);
                    var toleranciamens = parseFloat(qtd_min) * -1;
                }else{
                    if( qtd_tip == 'P'){
                        var toleranciamais = parseFloat((qtd_max/100)*v2);
                        var toleranciamens = parseFloat((qtd_min/100)*v2) * -1;
                    }else{
                        var toleranciamais = parseFloat(999999);
                        var toleranciamens = parseFloat(999999) * -1;
                    }
                }

                console.log('Tolerancia Mais:'+toleranciamais);
                console.log('Tolerancia Menos:'+toleranciamens);
                console.log('Dif:'+v3);

                if ((v3 > toleranciamais) || (v3 < toleranciamens) && (v1 > 0)) {

                    if (sobra_tipo == 'P'){
                        if (v1 > 0){
                            validar_prod(v1,v2,v3,v4,toleranciamais,toleranciamens,qtd_unim,talao_id,btn);
                        }
                    }else{
                        showErro('Este produto não permite sobra de Produção!');
                    }

                }else{

                   execAjax1(
                       'POST',
                       url, 
                       { 
                           retorno				: retorno,
                           qtd					: qtd,
                           sbr					: 0,
                           talao_detalhe_id	: talao_id,
                           REMESSA_ID          : REMESSA_ID,
                           REMESSA_TALAO_ID    : REMESSA_TALAO_ID
                       },
                      function(data) {

                          if(v1 > 0){
                              validarRet(data,btn);
                          }

                          if(ret == 0){

                             detalhe.QUANTIDADE_PRODUCAO_TMP = qtd;
                             detalhe.QUANTIDADE_SOBRA_TMP    = 0;
                             detalhe.EDITANDO_QUANTIDADE     = false; 

                             var index = that.QUANTIDADE_ALTERANDO.indexOf(detalhe);
                             that.QUANTIDADE_ALTERANDO.splice(index, 1); 

                             angular.element('#AppCtrl').scope().vm.TalaoComposicao.consultar();

                             showSuccess('Quantidade alterada com sucesso.');
                          }else{
                              ret = 0;
                          }
                      }

                   );
                }
            }            
        },
        gravarTodos : function ( tipo ) {
			var tr_selec		 = $('.table-talao-produzir').find('.selected');
			var remessa_id		 = $(tr_selec).find('._remessa-id').val();
			var remessa_talao_id = $(tr_selec).find('._remessa-talao-id').val();
            var data = {
                REMESSA_ID			: remessa_id,
                REMESSA_TALAO_ID	: remessa_talao_id,
                TIPO                : tipo
            };

			$ajax.post('/_22010/alterarTodasQtdTalaoDetalhe',data).then(function(data) {
                
                gScope.TalaoComposicao.consultar();

                validarRet(data);

                showSuccess('Quantidade alterada com sucesso.');
            });            
        },
        keydownQuantidade : function (detalhe,$event) {
            var that = this;
                /* Verifica se existe um evento */
                if ( !($event === undefined) ) {

                    if ( $event.key == 'Enter' ) {
                        that.gravarQuantidade(detalhe);
                    }
                    if ( $event.key == 'Escape' ) {
                        that.cancelarQuantidade(detalhe);
                    }
                }            
        },
        alterarQuantidadeAlternativa : function (detalhe) {
            
            this.QUANTIDADE_ALTERNATIVA_ALTERANDO.push(detalhe);
            detalhe.EDITANDO_QUANTIDADE_ALTERNATIVA = true;
        },
        cancelarQuantidadeAlternativa : function (detalhe) {
            
            detalhe.M_QUANTIDADE_ALTERN_PRODUCAO = detalhe.QUANTIDADE_ALTERN_PRODUCAO;
            detalhe.EDITANDO_QUANTIDADE_ALTERNATIVA = false;
            
            var index = this.QUANTIDADE_ALTERNATIVA_ALTERANDO.indexOf(detalhe);
            this.QUANTIDADE_ALTERNATIVA_ALTERANDO.splice(index, 1);        
        },
        setData: function(data) {
            angular.extend(this, data);
        }
    };

    /**
     * Private function
     */
//    function func(role) {
//      
//    }

    /**
     * Static property
     * Using copy to prevent modifications to private property
     */
//    TalaoDetalhe.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
//    TalaoDetalhe.build = function (data) {
//        
//        if (!checkRole(data.role)) {
//          return;
//        }
//        
//        return new TalaoDetalhe(data);
//    };

    /**
     * Return the constructor function
     */
    return TalaoDetalhe;
};
angular
    .module('app')
    .factory('TalaoConsumo', TalaoConsumo);
    

	TalaoConsumo.$inject = [        
        '$ajax',
        '$q',
        '$window',
        'gScope',
        'gcObject',
        'gcCollection'
    ];

function TalaoConsumo($ajax,$q,$window,gScope,gcObject,gcCollection) {

    /**
     * Constructor, with class name
     */
    function TalaoConsumo(data) {
        if (data) {
            this.setData(data);
        }
    }
        
    /**
     * Private property
     */
    var url_base        = '_22010/api/talao/consumo';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    TalaoConsumo.prototype = {
        selectionar : function (consumo) {
            this.SELECTED = consumo;
        },
        setData: function(data) {
            angular.extend(this, data);
        }
    };

    /**
     * Private function
     */
    function fn () {
        
    }

    /**
     * Static property
     * Using copy to prevent modifications to private property
     */
    TalaoConsumo.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    TalaoConsumo.build = function (data) {
        
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
    return TalaoConsumo;
};

angular
    .module('app')
    .factory('TalaoComposicao', TalaoComposicao);
    

	TalaoComposicao.$inject = [        
        '$ajax',
        '$q',
        '$rootScope',
        '$timeout',
        'gScope',
        'gcObject',
        'gcCollection'
    ];

function TalaoComposicao($ajax,$q,$rootScope,$timeout,gScope,gcObject,gcCollection) {

    /**
     * Constructor, with class name
     */
    function TalaoComposicao(data) {
        if (data) {
            this.setData(data);
        }
    }
        
    /**
     * Private property
     */
    var url_base        = '_22010/api/talao/composicao';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    TalaoComposicao.prototype = {        
        TIMEOUT : null,
        VINCULO_MODELOS : [],
        consultar : function () {
            
            var that = this;
            
            return $q(function(resolve) {

                $ajax.post(url_base, getDados(),{progress: false}).then(function(resposta){

                    that.setComposicao(resposta);

                    resolve(true);
                });
            });
            
        },
        consultarVinculoModelos : function(talao_id){
            var that = this;
            $ajax.post('_22010/api/talao-vinculo-modelos',{TALAO_ID :talao_id }).then(function(response){
                that.VINCULO_MODELOS = response;
                
                $('#modal-vinculo-modelos').modal('show');
            });
        },
        producao : function (bool) {
            this.EM_PRODUCAO = bool;
        },
        setComposicao : function (data) {
            var that = this;
            
            if ( that.DADOS == undefined ) {
                that.DADOS = [];
            }

            if ( that.DADOS.DETALHE == undefined ) {
                that.DADOS.DETALHE = [];
            }

            if ( that.DADOS.CONSUMO == undefined ) {
                that.DADOS.CONSUMO = [];
            }

            if ( that.DADOS.CONSUMO_ALOCACAO == undefined ) {
                that.DADOS.CONSUMO_ALOCACAO = [];
            }

            if ( that.DADOS.CONSUMO_PECAS_DISPONIVEIS == undefined ) {
                that.DADOS.CONSUMO_PECAS_DISPONIVEIS = [];
            }

            if ( that.DADOS.HISTORICO == undefined ) {
                that.DADOS.HISTORICO = [];
            }

            if ( that.DADOS.DEFEITO == undefined ) {
                that.DADOS.DEFEITO = [];
            }

            if ( that.DADOS.FICHA == undefined ) {
                that.DADOS.FICHA = [];
            }

            gcCollection.merge(that.DADOS.DETALHE                  , data.DETALHE                  , 'ID');
            gcCollection.merge(that.DADOS.CONSUMO                  , data.CONSUMO                  , 'ID');
            gcCollection.merge(that.DADOS.CONSUMO_ALOCACAO         , data.CONSUMO_ALOCACAO         , 'ID');
            gcCollection.merge(that.DADOS.CONSUMO_PECAS_DISPONIVEIS, data.CONSUMO_PECAS_DISPONIVEIS, 'ID');
            gcCollection.merge(that.DADOS.HISTORICO                , data.HISTORICO                , 'ID');
            gcCollection.merge(that.DADOS.DEFEITO                  , data.DEFEITO                  , 'DEFEITO_TRANSACAO_ID');
            gcCollection.merge(that.DADOS.FICHA                    , data.FICHA                    , 'TIPO_ID');

            gcCollection.bind(that.DADOS.DETALHE, that.DADOS.DEFEITO, 'REMESSA_TALAO_DETALHE_ID', 'DEFEITOS');
            
            gcCollection.bind(that.DADOS.CONSUMO, that.DADOS.CONSUMO_ALOCACAO         , 'CONSUMO_ID', 'ALOCACOES');
            gcCollection.bind(that.DADOS.CONSUMO, that.DADOS.CONSUMO_PECAS_DISPONIVEIS, 'PRODUTO_ID', 'PECAS_DISPONIVEIS');

            $timeout(function(){

                $rootScope.$broadcast('bs-init');
                
                acoesTalaoDetalhe();
            });                 
        },
        setData: function(data) {
            angular.extend(this, data);
        }
    };

    /**
     * Private function
     */
    function getDados() {

        var table;
        var tr_selec;
        var dados = {};
        var produzir_selecionado = gScope.Filtro.GUIA_ATIVA == 'TALAO_PRODUZIR';

        //definir tabela
        table		= gScope.Filtro.GUIA_ATIVA == 'TALAO_PRODUZIR' ? $('#talao-produzir') : $('#talao-produzido');

        //linha selecionada
        tr_selec	= $(table).find('tbody').find('tr.selected');

        if ( gScope.Filtro.GUIA_ATIVA == 'TALAO_PRODUZIDO' && gScope.TalaoProduzido.SELECTED.ID > 0 ) {
            dados = {
                id      			: gScope.TalaoProduzido.SELECTED.ID,
                remessa_id			: gScope.TalaoProduzido.SELECTED.REMESSA_ID,
                remessa_talao_id	: gScope.TalaoProduzido.SELECTED.REMESSA_TALAO_ID,
                programacao_id		: gScope.TalaoProduzido.SELECTED.PROGRAMACAO_ID,
                status				: '1'
            };
        } else 
        if ( gScope.TalaoProduzir.SELECTED != undefined && gScope.TalaoProduzir.SELECTED.ID > 0 ) {

            dados = {
                id					 : gScope.TalaoProduzir.SELECTED.ID,
                remessa_id			 : gScope.TalaoProduzir.SELECTED.REMESSA_ID,
                remessa_talao_id	 : gScope.TalaoProduzir.SELECTED.REMESSA_TALAO_ID,
                programacao_id		 : gScope.TalaoProduzir.SELECTED.PROGRAMACAO_ID,
                status				 : gScope.Filtro.GUIA_ATIVA == 'TALAO_PRODUZIR' ? '0' : '1',
                gp_pecas_disponiveis : gScope.Filtro.GP_PECAS_DISPONIVEIS
            };


        }
        else {
            dados = {};
        }

        return dados;
    }

		function preencheDetalhe(conteudo) {

			var div_table		= $('#detalhe .table-detalhe');
			var scr				= new $window.Scroll();
			var scroll_posicao	= scr.getX(div_table);

			$(div_table)
				.html(conteudo)
			;

			$window.ativarDatatable(div_table.find('table'));
			$window.ativarSelecLinhaRadio();
			$window.editarQtdDetalhe();
			$window.acoesTalaoDetalhe();

			scr.setX(div_table, scroll_posicao);

			if ( gScope.TalaoProduzir.EM_PRODUCAO ) {
				$window.habilitarBtnDetalhe(true);
				$window.habilitarBtnEditarQtd(true);
			}
			else {
				$window.habilitarBtnDetalhe(false);
				$window.habilitarBtnEditarQtd(false);
			}
			

		}

		/**
		 * Preencher tabela de histórico do talão.
		 * @param {view} conteudo
		 */
		function preencheHistorico(conteudo) {

			var div_table = $('#historico .table-historico');

			$(div_table)
				.html(conteudo)
			;

			ativarDatatable(div_table.find('table'));

//			new tempoProducao().tempoRealizado(div_table, false);

			verificarTalaoSelecEmProducao();
			
		}

		/**
		 * Preencher tabela de matéria-prima.
		 * @param {view} conteudo
		 */
		function preencheMateriaPrima(conteudo) {

			var div_table = $('#materia-prima');

			$(div_table)
				.html(conteudo)
			;

			ativarDatatable(div_table.find('table'));
			ativarSelecLinhaRadio();
			editarQtdMateriaPrima();

			if ( gScope.TalaoProduzir.EM_PRODUCAO )
				habilitarBtnMateriaPrima(true);
			else
				habilitarBtnMateriaPrima(false);
			
		}

		/**
		 * Preencher tabela de defeitos do talão.
		 * @param {view} conteudo
		 */
		function preencheDefeito(conteudo) {

			var div_table = $('#defeito');

			$(div_table)
				.html(conteudo)
			;

			ativarDatatable(div_table.find('table'));
			
		}


    /**
     * Static property
     * Using copy to prevent modifications to private property
     */
    TalaoComposicao.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    TalaoComposicao.build = function (data) {
        
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
    return TalaoComposicao;
};
angular
    .module('app')
    .factory('TalaoProduzido', TalaoProduzido);
    

	TalaoProduzido.$inject = [        
        '$ajax',
        '$timeout',
        '$q',
        '$rootScope',
        'gScope',
        'gcCollection',
        'gcObject'
    ];

function TalaoProduzido($ajax,$timeout,$q,$rootScope,gScope,gcCollection,gcObject) {

    /**
     * Constructor, with class name
     */
    function TalaoProduzido(data) {
        if (data) {
            this.setData(data);
        }
        this.TOTALIZADOR = {};
        this.DADOS = [];
    }
    
    /**
     * Private property
     */
    var url_base = '_22010/api/talao/produzido/';
    var dados    = {};

    /**
     * Coleta ou atualiza da variável dados do talão selecionado
     * @returns {void}
     */
    var dadosTalao = function()
    {
        var f = gScope.Filtro;
        var t = gScope.TalaoProduzido.SELECTED;
        
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
            tempo_realizado		: tempo_realizado
        };
    };  

    /**
     * Public method, assigned to prototype
     */
    TalaoProduzido.prototype = {    
        selectionar : function (talao,setfocus) {
            
            if ( talao != undefined ) {
            
                this.SELECTED       = talao;
                this.SELECTED_RADIO = talao.ID;
                
                if ( setfocus ) {
                    $timeout(function(){
                        $('.table-talao-produzido.table-lc-body tr.selected').focus();
                    },50);                      
                }

                gScope.TalaoComposicao.consultar();
            }
                
        },        
        all : function () {
            
            var that = this;
              
            return $q(function(resolve) {
        
                var args = {
                    estabelecimento_id	: $('.estab').val(),
                    gp_id				: $('._gp_id').val(),
                    up_id				: $('._up_id').val(),
                    up_todos			: $('._up_todos').val(),
                    up_origem			: $('._up_origem_descricao').val(),
                    estacao				: $('._estacao_id').val(),
                    estacao_todos		: $('._estacao_todos').val(),
                    remessa				: $('#remessa').val(),
                    data_producao		: $('#data-destaque').find('.valor').text(),
                    data_ini			: $('.filtro-periodo .data-ini').val(),
                    data_fim			: $('.filtro-periodo .data-fim').val(),
                    periodo_todos		: $('#periodo-todos').is(':checked'),
                    _perfil_gp			: $('._perfil-gp').val().trim(),
                    ver_pares			: $('._ver-pares-gp').val().trim(),
                    turno				: $('#turno').val(),
                    turno_hora_ini		: $('#turno').find(':selected').data('hora-ini'),
                    turno_hora_fim		: $('#turno').find(':selected').data('hora-fim')
                };

                $ajax.post(url_base+'all', JSON.stringify(args), {contentType: 'application/json'})
                .then(function(res){

                    for ( var i in res ) {
                        var item = res[i];
                
                        item.DATAHORA_REALIZADO_FIM  = new Date(item.DATAHORA_REALIZADO_FIM);
                    }
                    
                    gcCollection.merge(gScope.TalaoProduzido.DADOS, res, 'ID');

                    that.totalizadorCalc();
                                         
                    if ( gScope.TalaoProduzido.DADOS.length > 0 && gScope.TalaoProduzido.SELECTED != undefined ) {
                        gScope.TalaoProduzido.selectionar(gScope.TalaoProduzido.SELECTED);
                    }
                    

                    resolve(true);
                });
            });
        },
        totalizadorCalc : function () {
            var that = this;
            var dados = gScope.TalaoProduzido.DADOS;
        
            that.TOTALIZADOR.QUANTIDADE_PROJETADA   = 0;
            that.TOTALIZADOR.QUANTIDADE_PRODUZIDA   = 0;
            that.TOTALIZADOR.TEMPO_PREVISTO         = 0;
            that.TOTALIZADOR.TEMPO_REALIZADO        = 0;
            that.TOTALIZADOR.PAR_PRODUZIDO          = 0;
            that.TOTALIZADOR.QUANTIDADE_UM          = '';

            if ( dados != undefined ) {
                for ( var i in dados ) {
                    var item = dados[i];

                    that.TOTALIZADOR.QUANTIDADE_PROJETADA   += parseFloat( ( item.UM_ALTERNATIVA != '' ? item.QUANTIDADE_ALTERNATIVA : item.QUANTIDADE ) );
                    that.TOTALIZADOR.QUANTIDADE_PRODUZIDA   += parseFloat( ( item.UM_ALTERNATIVA != '' ? item.QUANTIDADE_ALTERNATIVA_PRODUCAO : item.QUANTIDADE_PRODUCAO ));
                    that.TOTALIZADOR.TEMPO_PREVISTO         += parseFloat(item.TEMPO);
                    that.TOTALIZADOR.TEMPO_REALIZADO        += parseFloat(item.TEMPO_REALIZADO);
                    
                    if ( gScope.Filtro.VER_PARES == '1' && item.PARES != undefined && item.PARES != '' ) {
                        that.TOTALIZADOR.PAR_PRODUZIDO += parseFloat(item.PARES);
                    }
                }
            }
            
            if ( dados.length > 0 ) {
                var item = gScope.TalaoProduzido.DADOS[0];
                if ( item.UM_ALTERNATIVA != '' ) {
                    that.TOTALIZADOR.QUANTIDADE_UM = item.UM_ALTERNATIVA;
                } else {
                    that.TOTALIZADOR.QUANTIDADE_UM = item.UM;
                }
            }            

        },
        setData: function(data) {
            angular.extend(this, data);
        }
    };

    /**
     * Private function
     */
    function fn() {
        //
    }

    /**
     * Return the constructor function
     */
    return TalaoProduzido;
};

        
angular
    .module('app')
    .factory('TalaoProduzir', TalaoProduzir);
    

	TalaoProduzir.$inject = [        
        '$ajax',
        '$timeout',
        '$q',
        '$rootScope',
        'gScope',
        'gcCollection'
    ];

function TalaoProduzir($ajax,$timeout,$q,$rootScope,gScope,gcCollection) {

    /**
     * Constructor, with class name
     */
    function TalaoProduzir(data) {
        if (data) {
            this.setData(data);
        }
        
        this.TOTALIZADOR = {};
    }
    
    this.EM_PRODUCAO = false;
    
    /**
     * Private property
     */
    var url_base        = '_22010/api/talao/produzir/';
    var possibleRoles   = ['admin', 'editor', 'guest'];
    var dados = {};

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
            tempo_realizado		: tempo_realizado
        };
    };  

    /**
     * Public method, assigned to prototype
     */
    TalaoProduzir.prototype = {    
        DADOS           : [],
        TEMPO_REALIZADO : 0,
        selectionar : function (talao,setfocus) {
            
            if ( talao != undefined ) {
            
                this.SELECTED       = talao;
                this.SELECTED_RADIO = talao.ID;
                
                if ( setfocus ) {
                    $timeout(function(){
                        $('.table-talao-produzir.table-lc-body tr.selected').focus();
                    },50);                      
                }

                gScope.TalaoTempo.calcRealTime();
                gScope.Filtro.TALAO_SELECTED = talao.ID;
                gScope.Filtro.uriHistory();
                gScope.TalaoComposicao.consultar();
            }
                
        },        
        all : function () {
            
            var that = this;
            
            return $q(function(resolve) {
        
                var args = {
                    estabelecimento_id	: $('.estab').val(),
                    gp_id				: $('._gp_id').val(),
                    up_id				: $('._up_id').val(),
                    up_todos			: $('._up_todos').val(),
                    up_origem			: $('._up_origem_descricao').val(),
                    estacao				: $('._estacao_id').val(),
                    estacao_todos		: $('._estacao_todos').val(),
                    remessa				: $('#remessa').val(),
                    data_producao		: $('#data-destaque').find('.valor').text(),
                    data_ini			: $('.filtro-periodo .data-ini').val(),
                    data_fim			: $('.filtro-periodo .data-fim').val(),
                    periodo_todos		: $('#periodo-todos').is(':checked'),
                    _perfil_gp			: $('._perfil-gp').val().trim(),
                    ver_pares			: $('._ver-pares-gp').val().trim(),
                    turno				: $('#turno').val(),
                    turno_hora_ini		: $('#turno').find(':selected').data('hora-ini'),
                    turno_hora_fim		: $('#turno').find(':selected').data('hora-fim')
                };

                $ajax.post(url_base+'all', JSON.stringify(args), {contentType: 'application/json'})
                .then(function(res){

                    for (var i in res) {
                        var item = res[i];
                        item.SEQUENCIA_PRODUCAO =  JSON.parse(item.SEQUENCIA_PRODUCAO);
                    }
                    
                    gcCollection.merge(gScope.TalaoProduzir.DADOS, res, 'ID');
            
                    that.totalizadorCalc();

                    if ( gScope.TalaoProduzir.DADOS.length > 0 ) {
                        
                        $timeout(function(){
                            $('#filtrar-toggle[aria-expanded="true"]').click(); 
                        });


                        if ( gScope.Filtro.TALAO_SELECTED > 0 ) {
                            var idx = gScope.indexOfAttr(gScope.TalaoProduzir.DADOS,'ID',gScope.Filtro.TALAO_SELECTED);
                            gScope.TalaoProduzir.selectionar(gScope.TalaoProduzir.DADOS[idx]);
                        }
                    }
                    
                    resolve(true);
                });
            });
        },
        current : function (consultar_composicao) {
            
            var that = this;
            
            return $q(function(resolve, reject) {
                dadosTalao();

                var data = {};
                var options = {progress : false};
                
                angular.copy(dados, data);
                
                if (consultar_composicao) {
                    data.talao_composicao     = '1';
                    data.gp_pecas_disponiveis = gScope.Filtro.GP_PECAS_DISPONIVEIS;
                    options.progress          = true;
                }
                
                $ajax.post('_22010/recarregarStatus',data,options).then(function(resposta) {

                            if ( resposta.TALAO_COMPOSICAO != undefined ) {
                                gScope.TalaoComposicao.setComposicao(resposta.TALAO_COMPOSICAO);
                            }
                                
                            gcCollection.merge(gScope.TalaoProduzir.DADOS, resposta.TALAO, 'ID', true);
                            
                            that.totalizadorCalc();

                            var em_producao = false;

                            //se o talão estiver em produção
                            if (resposta.PROGRAMACAO_STATUS.trim() == '2') {
                                em_producao = true;
                            }

                            gScope.TalaoProduzir.INICIADO = em_producao;

                            resumoProducao();

                            resolve(true);
                    },
                    function() {
                        reject(false);
                    }
                );
            });            
        },
        producao : function (bool) {
            this.EM_PRODUCAO = bool;
        },
        totalizadorCalc : function () {
            var that = this;
            var dados = gScope.TalaoProduzir.DADOS;
        
            that.TOTALIZADOR.QUANTIDADE_PROJETADA = 0;
            that.TOTALIZADOR.TEMPO_PREVISTO       = 0;
            that.TOTALIZADOR.PAR_PRODUZIR         = 0;
            that.TOTALIZADOR.QUANTIDADE_UM        = '';

            if ( dados != undefined ) {
                for ( var i in dados ) {
                    var item = dados[i];

                    that.TOTALIZADOR.QUANTIDADE_PROJETADA   += parseFloat( ( item.UM_ALTERNATIVA != '' ? item.QUANTIDADE_ALTERNATIVA : item.QUANTIDADE ) );
                    that.TOTALIZADOR.TEMPO_PREVISTO         += parseFloat(item.TEMPO);
                    
                    if ( gScope.Filtro.VER_PARES == '1' && item.PARES != undefined && item.PARES != '' ) {
                        that.TOTALIZADOR.PAR_PRODUZIR += parseFloat(item.PARES);
                    }
                }
            }
            
            if ( dados.length > 0 ) {
                var item = gScope.TalaoProduzir.DADOS[0];
                if ( item.UM_ALTERNATIVA != '' ) {
                    that.TOTALIZADOR.QUANTIDADE_UM = item.UM_ALTERNATIVA;
                } else {
                    that.TOTALIZADOR.QUANTIDADE_UM = item.UM;
                }
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
    TalaoProduzir.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    TalaoProduzir.build = function (data) {
        
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
    return TalaoProduzir;
};

        
angular
    .module('app')
    .factory('Defeito', Defeito);
    

	Defeito.$inject = [
        '$ajax'
    ];

function Defeito($ajax) {

    /**
     * Constructor, with class name
     */
    function Defeito(firstName, lastName, role) {
        
        // Public properties, assigned to the instance ('this')
        this.firstName      = firstName;
        this.lastName       = lastName;
        this.role           = role;
    }
    
    /**
     * Private property
     */
    var url_base        = '_22010/defeitos';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    Defeito.prototype = {
        
        load : function (args) {
            return $ajax.post(url_base+'all', JSON.stringify(args), {contentType: 'application/json'});
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
    Defeito.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    Defeito.build = function (data) {
        
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
    return Defeito;
};

        
angular
    .module('app')
    .factory('Filtro', Filtro);
    

	Filtro.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gScope'
    ];

function Filtro($ajax, $httpParamSerializer, $rootScope, $timeout, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }
    }
    
    this.GUIA_ATIVA = 'TALAO_PRODUZIR';
    
    /**
     * Private property
     */
    var url_base        = '_22010/defeitos';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    Filtro.prototype = {
        submit : function() {
            $timeout(function(){
                $('.btn-filtrar').click();
            });
        },
        setData: function(data) {
            angular.extend(this, data);
        },        
        consultar : function (args) {
            this.AUTO_LOAD = 1;
            this.uriHistory();
            
            switch(this.GUIA_ATIVA) {
                case 'TALAO_PRODUZIR':
                    
                    if ( gScope.TalaoProduzir.EM_PRODUCAO ) {
                        gScope.TalaoProduzir.current(true);
                    } else {
                        gScope.TalaoProduzir.all();
                    }
                    
                    break;
                case 'TALAO_PRODUZIDO':
                    gScope.TalaoProduzido.all();                    
                    break;
                case 'TOTALIZADOR_DIARIO':
                    gScope.TotalizadorDiario.consultar();                    
                    break;
            }
        },
        uriHistory : function() {
            window.history.replaceState('', '', encodeURI(urlhost + '/_22010?'+$httpParamSerializer(this)));
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
    Filtro.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    Filtro.build = function (data) {
        
        if (!checkRole(data.role)) {
          return;
        }
        
        return new Filtro(data);
    };

    /**
     * Return the constructor function
     */
    return Filtro;
};
angular
    .module('app')
    .factory('Acao', Acao);
    

	Acao.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$q',
        '$rootScope',
        '$filter',
        'gScope',
        'gcCollection'
    ];

function Acao($ajax, $httpParamSerializer, $q, $rootScope, $filter, gScope, gcCollection) {

    /**
     * Constructor, with class name
     */
    function Acao(data) {
        if (data) {
            this.setData(data);
        }
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
            justificativa_id    : gScope.Acao.API.JUSTIFICATIVA.SELECTED.ID
        };
    };
    

    /**
     * Public method, assigned to prototype
     */
    Acao.prototype = {   
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
											registraAcao({rota_ajax: '/_22010/acao/iniciar'})
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
											registraAcao({rota_ajax: '/_22010/acao/iniciar'})
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
                                    registraAcao({rota_ajax: '/_22010/acao/pausar'})
                                        .then(function(){

                                            if (em_producao !== true) {
                                                gScope.TalaoProduzir.current(true);
//                                                infoDestaqueLimpar();
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
                                registraAcao({rota_ajax: '/_22010/acao/finalizar'})
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
//                                                    infoDestaqueLimpar();
                                                })
                                                .catch(function(){

//                                                    infoDestaqueLimpar();
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
                case 'justificar':
                    
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
                    }
                    
                    break;
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
//                        ret.status    = false;
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
                                gScope.Acao.pausar().then(function(){

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
                        
                        gScope.Acao.pausar().then(function(){
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
    function registraAcao (param)
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
    Acao.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    Acao.build = function (data) {
        
        if (!checkRole(data.role)) {
          return;
        }
        
        return new Acao(data);
    };

    /**
     * Return the constructor function
     */
    return Acao;
};
angular
    .module('app')
    .value('gScope', {
        indexOfAttr : function(array,attr, value) {
            for(var i in array) {
                if(array[i][attr] === value) {
                    return i;
                }
            }
            return -1;
        }
    })
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$compile',
        '$timeout',
        '$sce',
        'gScope',
        'Acao', 
        'Filtro', 
        'TalaoProduzir', 
        'TalaoProduzido', 
        'TalaoComposicao',
        'TalaoDetalhe', 
        'TalaoDefeito',
        'TalaoConsumo',
        'TalaoTempo',
        'TalaoFicha',
        'TotalizadorDiario',
        'ColaboradorCentroDeTrabalho'
    ];

	function Ctrl( 
        $scope, 
        $compile, 
        $timeout, 
        $sce, 
        gScope, 
        Acao, 
        Filtro, 
        TalaoProduzir, 
        TalaoProduzido, 
        TalaoComposicao, 
        TalaoDetalhe, 
        TalaoDefeito, 
        TalaoConsumo, 
        TalaoTempo, 
        TalaoFicha, 
        TotalizadorDiario, 
        ColaboradorCentroDeTrabalho 
    ) {

		var vm = this;

        vm.Acao              = new Acao();
		vm.Filtro            = new Filtro();
        vm.TalaoProduzir     = new TalaoProduzir();
        vm.TalaoProduzido    = new TalaoProduzido();
        vm.TalaoComposicao   = new TalaoComposicao();
		vm.TalaoDetalhe      = new TalaoDetalhe();
		vm.TalaoDefeito      = new TalaoDefeito();
		vm.TalaoConsumo      = new TalaoConsumo();
        vm.TalaoTempo        = new TalaoTempo();
        vm.TalaoFicha        = new TalaoFicha();
        vm.TotalizadorDiario = new TotalizadorDiario();
        
        vm.ColaboradorCentroDeTrabalho = new ColaboradorCentroDeTrabalho();
        
        gScope.Acao              = vm.Acao             ; 
		gScope.Filtro            = vm.Filtro           ; 
        gScope.TalaoProduzir     = vm.TalaoProduzir    ; 
        gScope.TalaoProduzido    = vm.TalaoProduzido   ; 
        gScope.TalaoComposicao   = vm.TalaoComposicao  ; 
		gScope.TalaoDetalhe      = vm.TalaoDetalhe     ; 
		gScope.TalaoDefeito      = vm.TalaoDefeito     ; 
		gScope.TalaoConsumo      = vm.TalaoConsumo     ; 
        gScope.TalaoTempo        = vm.TalaoTempo       ; 
        gScope.TalaoFicha        = vm.TalaoFicha       ; 
        gScope.TotalizadorDiario = vm.TotalizadorDiario; 
               
        
        vm.trustedHtml = function (plainText) {
            return $sce.trustAsHtml(plainText);
        };        
        
        $scope.$on('bs-init', function(ngRepeatFinishedEvent) {
            bootstrapInit();
        });
        
        /**
         * Escuta do filtro que realiza o autofiltro
         */
        $scope.$watch('vm.Filtro.ESTABELECIMENTO_ID', function (newValue, oldValue, scope) {
            
            if ( newValue > 0 ) {
                $timeout(function(){
                    if ( vm.Filtro.AUTO_LOAD ) {
                        $('.btn-filtrar').click();
                    }
                },50);
            } 
        });
        
        /**
         * Escuta da guia ativa
         */
        $scope.$watch('vm.Filtro.GUIA_ATIVA', function (newValue, oldValue, scope) {
            
            if (newValue == 'TALAO_PRODUZIR') {
                
                if ( vm.TalaoProduzir.INICIADO ) {                  
                    vm.TalaoProduzir.EM_PRODUCAO = true;
                } else {
                    vm.TalaoProduzir.EM_PRODUCAO = false;
                }
                
                $('#periodo-todos').prop('disabled',false);
                $('#turno').attr('disabled', true);
            } else {
                $('#turno').attr('disabled', false);
                $('#periodo-todos').prop('disabled',true);
                
                $('#filtrar-toggle[aria-expanded="false"]').click();
            } 
            
            if ( newValue == 'TOTALIZADOR_DIARIO') {
                $('.filtro-periodo .data-ini').prop('required',true);
                $('.filtro-periodo .data-fim').prop('required',true);
            } else {
                $('.filtro-periodo .data-ini').prop('required',false);
                $('.filtro-periodo .data-fim').prop('required',false);
            }
            
            if ( oldValue != undefined && oldValue != newValue ) {
                vm.TalaoComposicao.DADOS = [];
            }
            
            if ( oldValue != undefined && oldValue != newValue && newValue == 'TALAO_PRODUZIR' ) {                
                $timeout(function(){
                    $('.table-talao-produzir.table-lc-body tr.selected').focus();
//                    vm.TalaoComposicao.consultar();
                },500);                 
            }
        }, true);
        

        $scope.$watch('vm.Filtro.GP_ID', function (newValue, oldValue, scope) {
            
            if ( oldValue != undefined && newValue == '' ) {
                vm.TalaoProduzir.DADOS = [];
                vm.TalaoProduzido.DADOS = [];
                vm.TotalizadorDiario.DADOS = [];
                vm.TalaoProduzir.SELECTED = null;
                vm.TalaoProduzido.SELECTED = null;
            }
        }, true);

        $scope.$watch('vm.Filtro.PERFIL_UP', function (newValue, oldValue, scope) {
            
            if ( oldValue != undefined && newValue == '' ) {
                vm.TalaoProduzir.DADOS = [];
                vm.TalaoProduzido.DADOS = [];
                vm.TotalizadorDiario.DADOS = [];
                vm.TalaoProduzir.SELECTED = null;
                vm.TalaoProduzido.SELECTED = null;
            }
        }, true);

        $scope.$watch('vm.Filtro.UP_ID', function (newValue, oldValue, scope) {
            
            if ( oldValue != undefined && newValue == '' ) {
                vm.TalaoProduzir.DADOS = [];
                vm.TalaoProduzido.DADOS = [];
                vm.TotalizadorDiario.DADOS = [];
                vm.TalaoProduzir.SELECTED = null;
                vm.TalaoProduzido.SELECTED = null;
            }
        }, true);

        $scope.$watch('vm.Filtro.ESTACAO', function (newValue, oldValue, scope) {
            
            if ( oldValue != undefined && newValue == '' ) {
                vm.TalaoProduzir.DADOS = [];
                vm.TalaoProduzido.DADOS = [];
                vm.TotalizadorDiario.DADOS = [];
                vm.TalaoProduzir.SELECTED = null;
                vm.TalaoProduzido.SELECTED = null;
            }
        }, true);
        
        /**
         * Escuta do talão em produção
         */
        $scope.$watch('vm.TalaoProduzir.INICIADO', function (newValue, oldValue, scope) {
            
            if (newValue) {
                if ( vm.Filtro.GUIA_ATIVA == 'TALAO_PRODUZIR' ) {
                    vm.TalaoProduzir.EM_PRODUCAO = true;
                } else {
                    vm.TalaoProduzir.EM_PRODUCAO = false;
                }
            } else {
                vm.TalaoProduzir.EM_PRODUCAO = false;
                $timeout(function(){
                    $('.table-talao-produzir.table-lc-body tr.selected').focus();
                },100);
            }
            
            gScope.TalaoTempo.calcRealTime();
        }, true);
             
        /**
         * Escuta do talão selecionado
         */
        $scope.$watch('vm.TalaoProduzir.SELECTED', function (newValue, oldValue, scope) {

            if ( newValue === null ) {
                
                vm.TalaoProduzir.INICIADO = false;
                delete vm.Filtro.TALAO_SELECTED;
                
					vm.TalaoComposicao.DADOS = [];
            } else
            if ( oldValue == undefined && newValue != null ) {
                $timeout(function(){
                    $('.table-talao-produzir.table-lc-body tr.selected').focus();
                },50);  
            }        
            
        }, true);
        
        $scope.$watch('vm.TalaoProduzir.SELECTED.ID', function (newValue, oldValue, scope) {

            if ( oldValue != undefined ) {
                vm.TalaoDetalhe.SELECTED = null;
                vm.TalaoConsumo.SELECTED = null;
                vm.TalaoDetalhe.QUANTIDADE_ALTERANDO = [];
                vm.TalaoDetalhe.QUANTIDADE_ALTERNATIVA_ALTERANDO = [];  
            }          
        }, true);
        
        
        /**
         * Escuta do status EM PRODUÇÃO
         */
        $scope.$watch('vm.TalaoProduzir.EM_PRODUCAO', function (newValue, oldValue, scope) {
            
            if ( newValue ) {
                $(document).scrollTop(0);
            }
        }, true);
        
             
        /**
         * Escuta do talão selecionado
         */
        $scope.$watch('vm.TalaoProduzido.SELECTED', function (newValue, oldValue, scope) {

            if ( newValue === null ) {
                vm.TalaoComposicao.DADOS = [];
            } else
            if ( oldValue == undefined && newValue != null ) {
                $timeout(function(){
                    $('.table-talao-produzido.table-lc-body tr.selected').focus();
                },50);  
            }        
            
        }, true);
        
        $scope.$watch('vm.TalaoProduzido.SELECTED.ID', function (newValue, oldValue, scope) {

            if ( oldValue != undefined ) {
                vm.TalaoDetalhe.SELECTED = null;
                vm.TalaoConsumo.SELECTED = null;
            }          
        }, true);
             
                        
        $timeout(function () {
            $('.recebe-puxador-talao, .recebe-puxador-detalhe, .recebe-puxador-consumo, .recebe-puxador-historico, .recebe-puxador-ficha')
                .resizable({
                    resize  : function( event, ui ) {
                        $scope.$apply(function(){
                            $(document).resize();
                        });

                    },
                    handles  : 's',
                    minHeight : 80
                })
            ;
        }, 500);
        
			
        //Aumentar tabela com duplo clique no puxador
        $(document)
            .on('dblclick','.ui-resizable-s', function(e) {

                var table = $(this).closest('.ui-resizable');

                if ( $(table).data('original-size') == undefined || $(table).data('original-size') == 0 ) {
                    $(table).data('original-size',$(table).height());
                }  
                
                var bool = $(table).data('height-full') || false;
                $(table).data('height-full', ! bool);
                
                if ( $(table).data('height-full') == true ) {
        
                    var datatable_scrollbody = $(table).find('.table-lc-body');;
                    var tbody_height	     = datatable_scrollbody.height(),
                        window_height	     = $(window).height(),
                        vh_context		     = window_height * 0.01,	//converter px para vh - parte I
                        tbody_height_vh      = tbody_height / vh_context //converter px para vh - parte II
                    ;

                    //Se a altura do tbody for maior que 70vh, tbody_height terá 70vh de altura, pois esse é o valor máximo permitido (altura da tela);
                    //senão, a altura será a altura inicial + 34, que é a altura do cabeçalho da tabela.
                    if (tbody_height_vh > 70) {

                        var datatable_scroll     = $(table).find('.table-container');

                        tbody_height = '70vh';

                        //Posicionar scroll.
                        //posição da tabela - altura do cabeçalho - altura da barra de ações - 50
                        $(document)
                            .scrollTop( datatable_scroll.offset().top - $('nav.navbar').outerHeight() - $('ul.acoes').outerHeight() - 50 )
                        ;

                    }
                    else {
                        tbody_height = tbody_height + 45;
                    }
                
                    $(table)
                        .height( tbody_height )
                    ;
                } else {
                    $(table)
                        .height( $(table).data('original-size') )
                    ;                    
                }
            })
        ;
	}   
    
//# sourceMappingURL=_22010.app.js.map
