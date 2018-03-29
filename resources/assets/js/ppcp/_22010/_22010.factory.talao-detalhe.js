
        
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