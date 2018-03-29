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