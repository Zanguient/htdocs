/**
 * _22400 - Geracao de Remessas de Bojo
 */

;(function(angular) {

    var Ctrl = function($scope,$ajax,$timeout,$filter,$window,$interval) {

        var vm = this;

        vm.composicao = {Dados:{}, Componente:{}};

        vm.OPERADOR = {
            operacao_id     :7, //registrar producao
            valor_ext       :1,
            barras          : '',
            abort           :true,
            verificar_up    :true,
            LOGADO          :false,
            OPERADOR_NOME   :'',
            OPERADOR_ID     :0,
            OPERADOR_BARRAS : ''
        };

        vm.TURNO = {
            TURNO_SELECT : 1,
            TURNO_ATUAL  : 0
        };

        vm.FERRAMENTAS = [];

        vm.DADOS = {
            PARADOS1 : [],
            PARADOS2 : [],
            ESTACOES : [{DESCRICAO: 'ESTAÇÕES',TALOES:[]}]
        };

        vm.PRODUCAO = [];

        vm.TIPO_EFICIENCIA = 0;

        vm.DADOS_TEMP = [];
        vm.DADOS_TEMP.ESTACOES = [];

        vm.TURNO = {
            CODIGO      : 0,
            DESCRICAO   : '',
        };

        vm.DESC_EFIC = 'EFICÁCIA';

        vm.FILTRO = {
            DATA_FINAL          : moment().toDate(),
            DATA_INICIAL        : moment().toDate(),
            DATA_FINAL2         : moment().toDate(),
            DATA_INICIAL2       : moment().toDate(),
            ESTABELECIMENTO     : 0,
            GP_DESCRICAO        : '',
            GP_ID               : 0,
            UP_DESCRICAO        : '',
            UP_ID               : 0,
            UPO_ID              : 0,
            UPO_DESCRICAO       : '',
            ESTACAO_ID          : 0,
            ESTACAO_DESCRICAO   : '',
            FLAG_DATA           : true,
            FLAG_DATA2          : true,
            TURNO               : 0,
            TALAO_ID            : 0,
            MATRIZ_BARRAS       : '',
        };

        vm.MODAL         = {};
        vm.ESTACAO       = {};
        vm.ESTACAO_MODAL = {};
        vm.TALAO_MODAL   = {};

        vm.PRODUCAO = [];
        vm.PRODUCAO_TOTAL = [];

        vm.SETUP = {ESTACAO:0};

        function init(){
            for(var i in [1,2,3,4,5,6]){
                vm.DADOS.PARADOS1.push([]);
                vm.DADOS.PARADOS2.push([]);
                vm.DADOS.ESTACOES[0].TALOES.push([]);
            }

            vm.PRODUCAO.push({
                    DESCRICAO  : '',
                    ESTACAO    : '',
                    META         : 0,
                    META_T       : 0,
                    META_G       : 0,
                    PERDA_T      : 0,
                    PERDA_G      : 0,
                    PRODUCAO_T   : 0,
                    PRODUCAO_G   : 0,
                    EFICIENCIA_T : 0,
                    EFICIENCIA_G : 0,
                    PERDAP_T     : 0,
                    TURNO        : 0
                });
        }

        /*
        $(document).on('change','#turno',function(){
            var turno = $(this).val();
            setTimeout(function(){
                $scope.$apply(function () {

                    var desc  = $('#_turno_cadastrado').val();

                    vm.TURNO.DESC_SELECT = desc;

                    vm.FILTRO.TURNO = parseInt(turno);
                    montarIndicador();
                });
            },300);
            
        });
        */

        function montarIndicador(){

            vm.PRODUCAO = [];

            vm.PRODUCAO_TOTAL = {
                T_META         : 0,
                T_META_T       : 0,
                T_META_G       : 0,
                T_PERDA_T      : 0,
                T_PERDA_G      : 0,
                T_PRODUCAO_T   : 0,
                T_PRODUCAO_G   : 0,
                T_EFICIENCIA_T : 0,
                T_EFICIENCIA_G : 0,
                T_PERDAP_T     : 0,
                T_PERDAP_G     : 0,
                PREVISTO_T     : 0,
                REALIZADO_T    : 0,
                TMPEFIC_T      : 0,
                PREVISTO_G     : 0,
                REALIZADO_G    : 0,
                TMPEFIC_G      : 0,

                INFO_T         : [],
                INFO_G         : [],

                EFICIENCIA_A1  : 0,
                EFICIENCIA_B1  : 0,
                PERDA_A1       : 0,
                PERDA_B1       : 0,

                EFICIENCIA_A2  : 0,
                EFICIENCIA_B2  : 0,
                PERDA_A2       : 0,
                PERDA_B2       : 0,
            };


            angular.forEach(vm.DADOS.ESTACOES, function(estacao, key) {

                if(vm.FILTRO.TURNO > 0){
                    vm.TURNO.TURNO_SELECT = vm.FILTRO.TURNO;
                }else{
                    vm.TURNO.TURNO_SELECT = estacao.TURNO;
                        
                    vm.FILTRO.TURNO  = parseInt(estacao.TURNO);
                }

                vm.TURNO.TURNO_ATUAL  = estacao.TURNO;

                var prod_estacao = [];

                prod_estacao = {
                    DESCRICAO      : estacao.DESCRICAO,
                    ESTACAO        : estacao.ESTACAO,
                    META           : 0,
                    META_T         : 0,
                    META_G         : 0,
                    PERDA_T        : 0,
                    PERDA_G        : 0,
                    PRODUCAO_T     : 0,
                    PRODUCAO_G     : 0,
                    EFICIENCIA_T   : 0,
                    EFICIENCIA_G   : 0,
                    PERDAP_T       : 0,
                    PERDAP_G       : 0,
                    COR_EFIC       : 0,

                    EFICIENCIA_A1  : 0,
                    EFICIENCIA_B1  : 0,
                    PERDA_A1       : 0,
                    PERDA_B1       : 0,

                    EFICIENCIA_A2  : 0,
                    EFICIENCIA_B2  : 0,
                    PERDA_A2       : 0,
                    PERDA_B2       : 0,
                    INFO_T         : [],
                    INFO_G         : [],

                    TURNO          : vm.TURNO.TURNO_SELECT
                };

                angular.forEach(vm.DADOS.PRODUCAO.META, function(item, key) {
                    if(item.ESTACAO == estacao.ESTACAO){
                        prod_estacao.META  = item.QUANTIDADE;
                        vm.PRODUCAO_TOTAL.T_META = vm.PRODUCAO_TOTAL.T_META + Number(item.QUANTIDADE);   
                    }
                });

                angular.forEach(vm.DADOS.PRODUCAO.META_G, function(item, key) {
                    if(item.ESTACAO == estacao.ESTACAO){
                        prod_estacao.META_G  = item.QUANTIDADE;   
                        vm.PRODUCAO_TOTAL.T_META_G = vm.PRODUCAO_TOTAL.T_META_G + Number(item.QUANTIDADE);
                    }
                });

                angular.forEach(vm.DADOS.PRODUCAO.META_T, function(item, key) {
                    if(item.ESTACAO == estacao.ESTACAO && item.TURNO == vm.TURNO.TURNO_SELECT){
                        vm.PRODUCAO_TOTAL.META_T   = item.QUANTIDADE;   
                        vm.PRODUCAO_TOTAL.T_META_T = vm.PRODUCAO_TOTAL.T_META_T + Number(item.QUANTIDADE);
                    }
                });

                angular.forEach(vm.DADOS.PRODUCAO.PERDA_G, function(item, key) {
                    if(item.ESTACAO == estacao.ESTACAO){
                        prod_estacao.PERDA_G  = item.QUANTIDADE;   
                        vm.PRODUCAO_TOTAL.T_PERDA_G = vm.PRODUCAO_TOTAL.T_PERDA_G + Number(item.QUANTIDADE);
                    }
                });

                angular.forEach(vm.DADOS.PRODUCAO.PERDA_T, function(item, key) {
                    if(item.ESTACAO == estacao.ESTACAO && item.TURNO == vm.TURNO.TURNO_SELECT){
                        prod_estacao.PERDA_T  = item.QUANTIDADE;   
                        vm.PRODUCAO_TOTAL.T_PERDA_T = vm.PRODUCAO_TOTAL.T_PERDA_T + Number(item.QUANTIDADE);
                    }
                });

                var a = 0;
                angular.forEach(vm.DADOS.PRODUCAO.EFICIENCIA_T, function(item, key) {
                    if(item.ESTACAO == '4'){
                        console.log(item);
                    }

                    if(item.ESTACAO == estacao.ESTACAO && item.TURNO == vm.TURNO.TURNO_SELECT){
                        prod_estacao.EFICIENCIA_T  = item.EFICIENCIA;
                        prod_estacao.PRODUCAO_T  = item.QUANTIDADE;
                        vm.PRODUCAO_TOTAL.T_EFICIENCIA_T = vm.PRODUCAO_TOTAL.T_EFICIENCIA_T + Number(item.EFICIENCIA);

                        prod_estacao.PERDA_A2      = item.PERDAS_A;
                        prod_estacao.PERDA_B2      = item.PERDAS_B;

                        prod_estacao.COR_EFIC_T = item.COR_EFIC;

                        prod_estacao.PRODUCAO_T  = item.QUANTIDADE;
                        vm.PRODUCAO_TOTAL.T_PRODUCAO_T  = vm.PRODUCAO_TOTAL.T_PRODUCAO_T  + Number(item.QUANTIDADE);

                        vm.PRODUCAO_TOTAL.EFICIENCIA_A1 = vm.PRODUCAO_TOTAL.EFICIENCIA_A1 + (item.EFICIENCIA_A * item.QUANTIDADE);
                        vm.PRODUCAO_TOTAL.EFICIENCIA_B1 = vm.PRODUCAO_TOTAL.EFICIENCIA_B1 + (item.EFICIENCIA_B * item.QUANTIDADE);
                        vm.PRODUCAO_TOTAL.PERDA_A1      = vm.PRODUCAO_TOTAL.PERDA_A1      + (item.PERDAS_A * item.QUANTIDADE);
                        vm.PRODUCAO_TOTAL.PERDA_B1      = vm.PRODUCAO_TOTAL.PERDA_B1      + (item.PERDAS_B * item.QUANTIDADE);  

                        vm.PRODUCAO_TOTAL.PREVISTO_T    = vm.PRODUCAO_TOTAL.PREVISTO_T    + Number(item.TEMPO_PREVISTO_OPERACIONAL);
                        vm.PRODUCAO_TOTAL.REALIZADO_T   = vm.PRODUCAO_TOTAL.REALIZADO_T   + Number(item.TEMPO_REALIZADO_OPERACIONAL);
                        vm.PRODUCAO_TOTAL.TMPEFIC_T     = vm.PRODUCAO_TOTAL.TMPEFIC_T     + Number(item.TEMPO_EFIC);
                    }

                    if(item.ESTACAO == estacao.ESTACAO){
                        a = a + 1;
                        prod_estacao.EFICIENCIA_A2 = Number(prod_estacao.EFICIENCIA_A2) + Number(item.EFICIENCIA_A);
                        prod_estacao.EFICIENCIA_B2 = Number(prod_estacao.EFICIENCIA_B2) + Number(item.EFICIENCIA_B);
                    }

                });

                if(a < 1){a = 1;}
                prod_estacao.EFICIENCIA_A2 = Number(prod_estacao.EFICIENCIA_A2)/ a;
                prod_estacao.EFICIENCIA_B2 = Number(prod_estacao.EFICIENCIA_B2)/ a;

                angular.forEach(vm.DADOS.PRODUCAO.EFICIENCIA_G, function(item, key) {
                    if(item.ESTACAO == estacao.ESTACAO){
                        prod_estacao.EFICIENCIA_G  = item.EFICIENCIA;
                        prod_estacao.PRODUCAO_G  = item.QUANTIDADE;

                        prod_estacao.EFICIENCIA_A1 = item.EFICIENCIA_A;
                        prod_estacao.EFICIENCIA_B1 = item.EFICIENCIA_B;
                        prod_estacao.PERDA_A1      = item.PERDAS_A;
                        prod_estacao.PERDA_B1      = item.PERDAS_B;

                        prod_estacao.COR_EFIC_G = item.COR_EFIC;

                        prod_estacao.PRODUCAO_G  = item.QUANTIDADE;
                        vm.PRODUCAO_TOTAL.T_PRODUCAO_G  = vm.PRODUCAO_TOTAL.T_PRODUCAO_G  + Number(item.QUANTIDADE);

                        vm.PRODUCAO_TOTAL.EFICIENCIA_A2 = vm.PRODUCAO_TOTAL.EFICIENCIA_A2 + (item.EFICIENCIA_A * item.QUANTIDADE);
                        vm.PRODUCAO_TOTAL.EFICIENCIA_B2 = vm.PRODUCAO_TOTAL.EFICIENCIA_B2 + (item.EFICIENCIA_B * item.QUANTIDADE);

                        vm.PRODUCAO_TOTAL.PERDA_A2      = vm.PRODUCAO_TOTAL.PERDA_A2 + (item.PERDAS_A * item.QUANTIDADE);
                        vm.PRODUCAO_TOTAL.PERDA_B2      = vm.PRODUCAO_TOTAL.PERDA_B2 + (item.PERDAS_B * item.QUANTIDADE); 

                        vm.PRODUCAO_TOTAL.PREVISTO_G    = vm.PRODUCAO_TOTAL.PREVISTO_G    + Number(item.TEMPO_PREVISTO_OPERACIONAL);
                        vm.PRODUCAO_TOTAL.REALIZADO_G   = vm.PRODUCAO_TOTAL.REALIZADO_G   + Number(item.TEMPO_REALIZADO_OPERACIONAL);
                        vm.PRODUCAO_TOTAL.TMPEFIC_G     = vm.PRODUCAO_TOTAL.TMPEFIC_G     + Number(item.TEMPO_EFIC);
                    }
                });

                prod_estacao.INFO_T.push(['Meta A',prod_estacao.EFICIENCIA_A1]);
                prod_estacao.INFO_T.push(['Meta B',prod_estacao.EFICIENCIA_B1]);
                prod_estacao.INFO_G.push(['Meta A',prod_estacao.EFICIENCIA_A2]);
                prod_estacao.INFO_G.push(['Meta B',prod_estacao.EFICIENCIA_B2]);

                if(vm.TIPO_EFICIENCIA == 1){

                    var disponivel_t = 0;
                    var decorrido_t  = 0;
                    var disponivel_g = 0;
                    var decorrido_g  = 0;

                    if(vm.TURNO.TURNO_SELECT == 1){
                        var disponivel_t = vm.DADOS.PRODUCAO.TEMPO_PROD[0].DISPONIVEL_T1;
                        var decorrido_t  = vm.DADOS.PRODUCAO.TEMPO_PROD[0].CORRIDO_T1;
                    }else{
                        var disponivel_t = vm.DADOS.PRODUCAO.TEMPO_PROD[0].DISPONIVEL_T2;
                        var decorrido_t  = vm.DADOS.PRODUCAO.TEMPO_PROD[0].CORRIDO_T2;
                    }

                    var disponivel_g = Number(vm.DADOS.PRODUCAO.TEMPO_PROD[0].DISPONIVEL_T1) + Number(vm.DADOS.PRODUCAO.TEMPO_PROD[0].DISPONIVEL_T2);
                    var decorrido_g  = Number(vm.DADOS.PRODUCAO.TEMPO_PROD[0].CORRIDO_T1)    + Number(vm.DADOS.PRODUCAO.TEMPO_PROD[0].CORRIDO_T2);

                        function tratar(meta,disponivel,decorrido,produzido){
                            var ret = 0;

                            ret = (produzido * 100) / meta;

                            if( meta       == 0 ){ret = 0;}
                            if( disponivel == 0 ){ret = 0;}
                            if( decorrido  == 0 ){ret = 0;}
                            if( produzido  == 0 ){ret = 0;}

                            return ret;
                        }

                    var meta_t =  ((prod_estacao.META_G / disponivel_g) *  decorrido_t);
                    var meta_g =  ((prod_estacao.META_G / disponivel_g) *  decorrido_g);

                    var efic_t  = tratar(meta_t ,disponivel_t, decorrido_t, prod_estacao.PRODUCAO_T);
                    var efic_g  = tratar(meta_g ,disponivel_g, decorrido_g, prod_estacao.PRODUCAO_G);

                    prod_estacao.EFICIENCIA_T = Number(efic_t);
                    prod_estacao.EFICIENCIA_G = Number(efic_g);

                    prod_estacao.INFO_T.push(['Temp. Disp.',disponivel_t]);
                    prod_estacao.INFO_T.push(['Temp. Deco.',decorrido_t] );
                    prod_estacao.INFO_T.push(['Meta Prod.' ,meta_t]      );  

                    prod_estacao.INFO_G.push(['Temp. Disp.',disponivel_g]);
                    prod_estacao.INFO_G.push(['Temp. Deco.',decorrido_g] );
                    prod_estacao.INFO_G.push(['Meta Prod.' ,meta_g]      );              

                    if(Number(efic_t) <  Number(prod_estacao.EFICIENCIA_A1)){prod_estacao.COR_EFIC_T = 1;}
                    if(Number(efic_t) >= Number(prod_estacao.EFICIENCIA_A1) && efic_t <= Number(prod_estacao.EFICIENCIA_B2)){prod_estacao.COR_EFIC_T = 2;}
                    if(Number(efic_t) >  Number(prod_estacao.EFICIENCIA_B1)){prod_estacao.COR_EFIC_T = 3;}

                    if(Number(efic_g) <  Number(prod_estacao.EFICIENCIA_A2)){prod_estacao.COR_EFIC_G = 1;}
                    if(Number(efic_g) >= Number(prod_estacao.EFICIENCIA_A2) && Number(efic_g) <= Number(prod_estacao.EFICIENCIA_B2)){prod_estacao.COR_EFIC_G = 2;}
                    if(Number(efic_g) >  Number(prod_estacao.EFICIENCIA_B2)){prod_estacao.COR_EFIC_G = 3;}                    
                }

                if(prod_estacao.PRODUCAO_T > 0){
                    prod_estacao.PERDAP_T = (prod_estacao.PERDA_T / prod_estacao.PRODUCAO_T) * 100;
                }else{
                    if(prod_estacao.PERDA_T  == 0){
                        prod_estacao.PERDAP_T = 0;
                    }else{
                        prod_estacao.PERDAP_T = 100.00;
                    }
                }

                if(prod_estacao.PRODUCAO_G > 0){
                    prod_estacao.PERDAP_G = (prod_estacao.PERDA_G / prod_estacao.PRODUCAO_G) * 100;
                }else{
                    if(prod_estacao.PERDA_G  == 0){
                        prod_estacao.PERDAP_G = 0;
                    }else{
                        prod_estacao.PERDAP_G = 100.00;
                    }
                }

                

                if(prod_estacao.EFICIENCIA_T >= 100){
                    prod_estacao.EFICIENCIA_T = 100;
                }

                if(prod_estacao.EFICIENCIA_G >= 100){
                    prod_estacao.EFICIENCIA_G = 100;
                }

                if(prod_estacao.PERDAP_G >= 100){
                    prod_estacao.PERDAP_G = 100;
                }

                if(prod_estacao.PERDAP_G >= 100){
                    prod_estacao.PERDAP_G = 100;
                }

                vm.PRODUCAO.push(prod_estacao);
                
            });

            if(vm.PRODUCAO_TOTAL.TMPEFIC_T > 0){
                vm.PRODUCAO_TOTAL.T_EFICIENCIA_T = (vm.PRODUCAO_TOTAL.PREVISTO_T / vm.PRODUCAO_TOTAL.TMPEFIC_T) * 100;
            }else{
                if(vm.PRODUCAO_TOTAL.PREVISTO_T > 0){
                    vm.PRODUCAO_TOTAL.T_EFICIENCIA_T = 0;
                }else{
                    vm.PRODUCAO_TOTAL.T_EFICIENCIA_T = 100;
                }
            }

            if(vm.PRODUCAO_TOTAL.TMPEFIC_G > 0){
                vm.PRODUCAO_TOTAL.T_EFICIENCIA_G = (vm.PRODUCAO_TOTAL.PREVISTO_G / vm.PRODUCAO_TOTAL.TMPEFIC_G) * 100;
            }else{
                if(vm.PRODUCAO_TOTAL.PREVISTO_G > 0){
                    vm.PRODUCAO_TOTAL.T_EFICIENCIA_G = 0;
                }else{
                    vm.PRODUCAO_TOTAL.T_EFICIENCIA_G = 100;
                }
            }

            if(vm.PRODUCAO_TOTAL.T_PRODUCAO_T > 0){
                vm.PRODUCAO_TOTAL.EFICIENCIA_A1 = vm.PRODUCAO_TOTAL.EFICIENCIA_A1 / vm.PRODUCAO_TOTAL.T_PRODUCAO_T;
                vm.PRODUCAO_TOTAL.EFICIENCIA_B1 = vm.PRODUCAO_TOTAL.EFICIENCIA_B1 / vm.PRODUCAO_TOTAL.T_PRODUCAO_T;
                vm.PRODUCAO_TOTAL.PERDA_A1      = vm.PRODUCAO_TOTAL.PERDA_A1      / vm.PRODUCAO_TOTAL.T_PRODUCAO_T;
                vm.PRODUCAO_TOTAL.PERDA_B1      = vm.PRODUCAO_TOTAL.PERDA_B1      / vm.PRODUCAO_TOTAL.T_PRODUCAO_T;
            }else{
                vm.PRODUCAO_TOTAL.EFICIENCIA_A1 = 0;
                vm.PRODUCAO_TOTAL.EFICIENCIA_B1 = 0;
                vm.PRODUCAO_TOTAL.PERDA_A1      = 0;
                vm.PRODUCAO_TOTAL.PERDA_B1      = 0;
            }

            if(vm.PRODUCAO_TOTAL.T_PRODUCAO_G > 0){
                vm.PRODUCAO_TOTAL.EFICIENCIA_A2 = vm.PRODUCAO_TOTAL.EFICIENCIA_A2 / vm.PRODUCAO_TOTAL.T_PRODUCAO_G;
                vm.PRODUCAO_TOTAL.EFICIENCIA_B2 = vm.PRODUCAO_TOTAL.EFICIENCIA_B2 / vm.PRODUCAO_TOTAL.T_PRODUCAO_G;
                vm.PRODUCAO_TOTAL.PERDA_A2      = vm.PRODUCAO_TOTAL.PERDA_A2      / vm.PRODUCAO_TOTAL.T_PRODUCAO_G;
                vm.PRODUCAO_TOTAL.PERDA_B2      = vm.PRODUCAO_TOTAL.PERDA_B2      / vm.PRODUCAO_TOTAL.T_PRODUCAO_G;
            }else{
                vm.PRODUCAO_TOTAL.EFICIENCIA_A2 = 0;
                vm.PRODUCAO_TOTAL.EFICIENCIA_B2 = 0;
                vm.PRODUCAO_TOTAL.PERDA_A2      = 0;
                vm.PRODUCAO_TOTAL.PERDA_B2      = 0;
            }

            vm.PRODUCAO_TOTAL.INFO_T.push(['Meta A',vm.PRODUCAO_TOTAL.EFICIENCIA_A1]);
            vm.PRODUCAO_TOTAL.INFO_T.push(['Meta B',vm.PRODUCAO_TOTAL.EFICIENCIA_B1]);
            vm.PRODUCAO_TOTAL.INFO_G.push(['Meta A',vm.PRODUCAO_TOTAL.EFICIENCIA_A2]);
            vm.PRODUCAO_TOTAL.INFO_G.push(['Meta B',vm.PRODUCAO_TOTAL.EFICIENCIA_B2]);

            if(vm.TIPO_EFICIENCIA == 1){

                vm.DESC_EFIC = 'EFICIÊNCIA';

                var disponivel_t = 0;
                var decorrido_t  = 0;
                var disponivel_g = 0;
                var decorrido_g  = 0;

                if(vm.TURNO.TURNO_SELECT == 1){
                    var disponivel_t = vm.DADOS.PRODUCAO.TEMPO_PROD[0].DISPONIVEL_T1;
                    var decorrido_t  = vm.DADOS.PRODUCAO.TEMPO_PROD[0].CORRIDO_T1;
                }else{
                    var disponivel_t = vm.DADOS.PRODUCAO.TEMPO_PROD[0].DISPONIVEL_T2;
                    var decorrido_t  = vm.DADOS.PRODUCAO.TEMPO_PROD[0].CORRIDO_T2;
                }

                var disponivel_g = Number(vm.DADOS.PRODUCAO.TEMPO_PROD[0].DISPONIVEL_T1) + Number(vm.DADOS.PRODUCAO.TEMPO_PROD[0].DISPONIVEL_T2);
                var decorrido_g  = Number(vm.DADOS.PRODUCAO.TEMPO_PROD[0].CORRIDO_T1)    + Number(vm.DADOS.PRODUCAO.TEMPO_PROD[0].CORRIDO_T2);

                    function tratar(meta,disponivel,decorrido,produzido){
                        var ret = 0;

                        ret = (produzido * 100) / meta;

                        if( meta       == 0 ){ret = 0;}
                        if( disponivel == 0 ){ret = 0;}
                        if( decorrido  == 0 ){ret = 0;}
                        if( produzido  == 0 ){ret = 0;}

                        return ret;
                    }

                var meta_tt =  ((vm.PRODUCAO_TOTAL.T_META / disponivel_g) *  decorrido_t);
                var meta_tg =  ((vm.PRODUCAO_TOTAL.T_META / disponivel_g) *  decorrido_g);

                var efic_tt = tratar(meta_tt ,disponivel_t, decorrido_t, vm.PRODUCAO_TOTAL.T_PRODUCAO_T);
                var efic_tg = tratar(meta_tg ,disponivel_g, decorrido_g, vm.PRODUCAO_TOTAL.T_PRODUCAO_G);

                vm.PRODUCAO_TOTAL.T_EFICIENCIA_T = efic_tt;
                vm.PRODUCAO_TOTAL.T_EFICIENCIA_G = efic_tg;

                vm.PRODUCAO_TOTAL.INFO_T.push(['Temp. Disp.',disponivel_t]);
                vm.PRODUCAO_TOTAL.INFO_T.push(['Temp. Deco.',decorrido_t] );
                vm.PRODUCAO_TOTAL.INFO_T.push(['Meta Prod.' ,meta_tt]     );  

                vm.PRODUCAO_TOTAL.INFO_G.push(['Temp. Disp.',disponivel_g]);
                vm.PRODUCAO_TOTAL.INFO_G.push(['Temp. Deco.',decorrido_g] );
                vm.PRODUCAO_TOTAL.INFO_G.push(['Meta Prod.' ,meta_tg]     );
                
            }else{
                vm.DESC_EFIC = 'EFICÁCIA';
            }

            if(vm.PRODUCAO_TOTAL.T_EFICIENCIA_T > 100){
                vm.PRODUCAO_TOTAL.T_EFICIENCIA_T = 100;
            } 

            if(vm.PRODUCAO_TOTAL.T_EFICIENCIA_G > 100){
                vm.PRODUCAO_TOTAL.T_EFICIENCIA_G = 100;
            }          

            if(vm.PRODUCAO_TOTAL.T_PRODUCAO_T > 0){
                vm.PRODUCAO_TOTAL.T_PERDAP_T = (vm.PRODUCAO_TOTAL.T_PERDA_T / vm.PRODUCAO_TOTAL.T_PRODUCAO_T) * 100;
            }else{
                if(vm.PRODUCAO_TOTAL.T_PERDA_T  == 0){
                    vm.PRODUCAO_TOTAL.T_PERDAP_T = 0;
                }else{
                    vm.PRODUCAO_TOTAL.T_PERDAP_T = 100;
                }
            }

            if(vm.PRODUCAO_TOTAL.T_PRODUCAO_G > 0){
                vm.PRODUCAO_TOTAL.T_PERDAP_G = (vm.PRODUCAO_TOTAL.T_PERDA_G / vm.PRODUCAO_TOTAL.T_PRODUCAO_G) * 100;
            }else{
                if(vm.PRODUCAO_TOTAL.T_PERDA_G  == 0){
                    vm.PRODUCAO_TOTAL.T_PERDAP_G = 0;
                }else{
                    vm.PRODUCAO_TOTAL.T_PERDAP_G = 100;
                }
            }

        }

        function validarImput(newValue,oldValue,imputValor,classConsulta){
            if(newValue > 0){
                $('._consulta_filtro[objcampo='+imputValor+']').val(newValue);
            }else{
                $('._consulta_filtro[objcampo='+imputValor+']').val(0);
            }

            if(newValue > 0 && newValue != oldValue){
                setTimeout(function(){
                    $('.'+classConsulta).find('.btn-filtro-consulta').trigger('click');
                },400);
            }

            if((newValue == '' || newValue == 0) && newValue != oldValue){
                setTimeout(function(){
                    $('.'+classConsulta).find('.btn-apagar-filtro-consulta').trigger('click');
                },400);
            }  
        }

        function iniciarEstacao(estacao){

            vm.ESTACAO_MODAL = estacao;

            var ds = {
                TABELA_ID       : vm.ESTACAO_MODAL.ESTACAO,
                TABELA          : 'ESTACAO',
                STATUS          : 0,
                VINCULO_ID      : vm.FILTRO.GP_ID,
                SUBVINCULO_ID   : vm.FILTRO.UP_ID,
                OPERADOR_ID     : vm.OPERADOR.OPERADOR_ID
            };
            
            $ajax.post('/_22130/pararEstacao',JSON.stringify(ds),{contentType: 'application/json'})
                .then(function(response) {

                    vm.MODAL = vm.ESTACAO_MODAL.TALOES[0];
                    vm.MODAL.MAQUINA = vm.ESTACAO_MODAL; 

                    if(vm.MODAL.FLAG_REPROGRAMADO == 0 && vm.MODAL.PROGRAMACAO_STATUS == 1){
                        $('#modal-parar-estacao').modal('hide');
                        showSuccess('Estação foi iniciada');
                        showAlert('Iniciando Talão...');
                        vm.Acoes.validarMatriz();
                    }else{
                        $('#modal-parar-estacao').modal('hide');
                        vm.Acoes.filtrar();
                        showSuccess('Estação foi iniciada');
                    }
                    
                }
            );
        }

        function trocarFerramenta(ferramenta){

            var temp2 = angular.copy(vm.MODAL, temp2);
            var temp = angular.copy(vm.MODAL, temp);
            var talao = vm.MODAL;

            temp2.MAQUINA = [];

            var temp1 = {
                FERRAMENTA  : ferramenta,
                TALAO       : temp2,
                FILTRO      : vm.FILTRO
            }

            $ajax.post('/_22130/trocarFerramenta',JSON.stringify(temp1),{contentType: 'application/json'})
                .then(function(response) {
                    
                    vm.DADOS = [];
                    vm.MODAL = [];

                    var validar = true;

                    vm.DADOS = response.DADOS;

                    $status  = response.INFO_STATUS;
                    $mensage = response.INFO_MENSAGE;

                    if($status == 1){showSuccess($mensage); }
                    if($status == 2){showErro($mensage);    }
                    if($status == 3){showAlert($mensage);   }

                    angular.forEach(vm.DADOS.ESTACOES, function(estacao, key) {
                        estacao.LIVRE = true;
                        estacao.TALAO_EM_PRODUCAO = 0;

                        angular.forEach(estacao.TALOES, function(talao, key) {

                            if(talao.PROGRAMACAO_STATUS == 2){
                                estacao.LIVRE = false;
                                estacao.TALAO_EM_PRODUCAO = talao.ID;
                            }

                            if(temp.ID == talao.ID){
                                vm.MODAL = talao
                                vm.MODAL.MAQUINA = estacao;
                                validar = false;                               
                            };
                        });

                    });

                    vm.MODAL = temp;

                    vm.MODAL.INFO_TALAO.MATRIZ.FERRAMENTA_CODIGO = ferramenta.ID;
                    vm.MODAL.FERRAMENTA_ID = ferramenta.ID;

                    $('#modal-troca-ferramenta').modal('hide');
                }
            );

        }

        function acaoTalao(talao,acao){

            var temp = angular.copy(talao, temp);

            var estacao_setup = vm.SETUP.ESTACAO;

            temp.MAQUINA = temp.MAQUINA.ESTACAO;

            temp.DATA_SETUP = moment().format('YYYY-MM-DD HH:m:s');

            var info = JSON.stringify({
                FILTRO   : vm.FILTRO,
                TALAO    : temp,
                ACAO     : acao,
                OPERADOR : vm.OPERADOR
            });

            $ajax.post('/_22130/acoesTaloes',info,{contentType: 'application/json'})
                .then(function(response) {

                    vm.DADOS = [];
                    vm.MODAL = [];

                    var validar = true;

                    vm.DADOS = null;
                    vm.DADOS = response.DADOS;

                    vm.TURNO = response.DADOS.TURNO;

                    $status  = response.INFO_STATUS;
                    $mensage = response.INFO_MENSAGE;

                    if($status == 1){showSuccess($mensage); $('#modal-troca-estacao').modal('hide');}
                    if($status == 2){showErro($mensage);                                            }
                    if($status == 3){showAlert($mensage);                                           }

                    angular.forEach(vm.DADOS.ESTACOES, function(estacao, key) {

                        if(estacao_setup == estacao.ESTACAO){
                           vm.Acoes.fecharSetap();
                           vm.Acoes.telaSetup(estacao,false);
                        }

                    });
                    //*/

                    $('#modal-validar-matriz').modal('hide');
                    setTimeout(function(){
                        $('.btn-fechar-modal').trigger('click');
                    },300);

                },function(erro){
                    $('.matriz_barra').val('');
                }
            );
        }

        function padLeft(nr, n, str){
            return Array(n-String(nr).length+1).join(str||'0')+nr;
        }

        function calcTempo(itens){
   
            angular.forEach(itens, function(iten, key) {

                var finalizado      = iten.FINALIZADO;
                var tempo_decorrido = iten.TEMPO_DECORRIDO;
                var datahora_inicio = iten.DATAHORA_INICIO;
                var setup_id        = iten.SETUP_ID;

                datahora_inicio = datahora_inicio + "";

                if(datahora_inicio.length > 0){

                    //if(tempo_decorrido == 0){
                    if(finalizado == 0){
                        
                        var date1 = new Date(datahora_inicio);
                        var date2 = new Date();
                        var timeDiff = (date2 - date1);

                    }else{

                        var timeDiff = tempo_decorrido;

                    }

                    var segundos = Math.trunc(timeDiff / 1000);
                    var minutos  = Math.trunc(segundos / 60  );
                    var horas    = Math.trunc(minutos  / 60  );
                    var dias     = Math.trunc(horas    / 24  );

                    horas = horas - (dias * 24);
                    minutos = minutos - (horas * 60) - (dias * 24 * 60);
                    segundos = segundos - (minutos * 60) - (horas * 60 * 60) - (dias * 24 * 60 * 60);

                    dias = padLeft(dias,2);
                    horas = padLeft(horas,2);
                    minutos = padLeft(minutos,2);
                    segundos = padLeft(segundos,2);

                    if(dias == 'NaN'){dias = '00';}
                    if(horas == 'NaN'){horas = '00';}
                    if(minutos == 'NaN'){minutos = '00';}
                    if(segundos == 'NaN'){segundos = '00';}

                    $('.item-setup_'+setup_id).find('.tempo-setup').html(horas+':'+minutos+':'+segundos);
                }

            });
        }

        vm.TIME = null;

        vm.Acoes = {

            detalharProducao:function(estacao,desc){

                vm.PRODUCAO.ESTACAO = desc;
                var temp = angular.copy(vm.FILTRO, temp);
                    temp.ESTACAO = estacao;

                var filtro = JSON.stringify(temp);
                temp = null;

                $ajax.post('/_22130/getProducao',filtro,{contentType: 'application/json'})
                    .then(function(response) {

                        vm.PRODUCAO.ITENS     = response.PRODUCAO;
                        vm.PRODUCAO.ESTACOES  = [];
                        vm.PRODUCAO.ESTACOES2 = [];
                        vm.PRODUCAO.PARADAS_A = response.PARADAS_A;
                        vm.PRODUCAO.PARADAS_S = response.PARADAS_S;

                        vm.PRODUCAO.TOTAL = {
                                PERDAS_PERC                 : 0,
                                EFICIENCIA                  : 0,
                                SETUP_PREVISTO              : 0,
                                SETUP_REALIZADO             : 0,
                                QUANTIDADE                  : 0,
                                TROCAS                      : 0,
                                TEMPO_PREVISTO              : 0,
                                TEMPO_REALIZADO             : 0,
                                TEMPO_PREVISTO_OPERACIONAL  : 0,
                                TEMPO_PREVISTO_FERRAMENTA   : 0,
                                TEMPO_PREVISTO_AQUECIMENTO  : 0,
                                TEMPO_PREVISTO_APROVACAO    : 0,
                                TEMPO_REALIZADO_OPERACIONAL : 0,
                                TEMPO_REALIZADO_FERRAMENTA  : 0,
                                TEMPO_REALIZADO_AQUECIMENTO : 0,
                                TEMPO_REALIZADO_APROVACAO   : 0,
                                TEMPO_PARADO                : 0,
                                TEMPO_EXTRA                 : 0,
                                PERDAS                      : 0,
                                EFICIENCIA_A                : 0,
                                EFICIENCIA_B                : 0,
                                PERDAS_A                    : 0,
                                PERDAS_B                    : 0,
                                FERRAMENTA_ID               : 0,
                                CONTADOR                    : 0,
                                
                                REMESSA                     : 0,
                                REMESSA_TALAO_ID            : 0,
                                REQUISICAO_ID               : 0,
                                MODELO_ID                   : 0,
                                COR_ID                      : 0,
                                TAMANHO                     : 0,

                                REMESSA_C                   : 0,
                                REMESSA_TALAO_ID_C          : 0,
                                REQUISICAO_ID_C             : 0,
                                MODELO_ID_C                 : 0,
                                COR_ID_C                    : 0,
                                TAMANHO_C                   : 0,

                                ESTACAO                     : 0,
                                ESTACAO2                    : 0,
                                COR_EFIC                    : 0,

                                PARADAS_A                   : [],
                                PARADAS_S                   : []
                            };

                        var cont_setu_troc = 0;
                        var cont_setu_aque = 0;
                        var cont_setu_apro = 0;

                        angular.forEach(vm.PRODUCAO.ITENS, function(iten, key) {

                            vm.PRODUCAO.TOTAL.CONTADOR = vm.PRODUCAO.TOTAL.CONTADOR+1;

                            if(vm.PRODUCAO.TOTAL.ESTACAO != iten.ESTACAO){
                                vm.PRODUCAO.TOTAL.ESTACAO = iten.ESTACAO;

                                vm.PRODUCAO.ESTACOES.push({
                                    ID: iten.ESTACAO,
                                    DESCRICAO: iten.ESTACAO_DESCRICAO
                                });
                            }                            

                            if(vm.PRODUCAO.TOTAL.FERRAMENTA_ID == 0){vm.PRODUCAO.TOTAL.FERRAMENTA_ID = iten.FERRAMENTA_ID;}
                            if(vm.PRODUCAO.TOTAL.FERRAMENTA_ID != iten.FERRAMENTA_ID){vm.PRODUCAO.TOTAL.FERRAMENTA_ID = iten.FERRAMENTA_ID; vm.PRODUCAO.TOTAL.TROCAS = vm.PRODUCAO.TOTAL.TROCAS + 1;}

                            if(vm.PRODUCAO.TOTAL.REMESSA == 0){vm.PRODUCAO.TOTAL.REMESSA = iten.REMESSA;}
                            if(vm.PRODUCAO.TOTAL.REMESSA != iten.REMESSA){vm.PRODUCAO.TOTAL.REMESSA = iten.REMESSA; vm.PRODUCAO.TOTAL.REMESSA_C = vm.PRODUCAO.TOTAL.REMESSA_C + 1;}

                            if(vm.PRODUCAO.TOTAL.REMESSA_TALAO_ID == 0){vm.PRODUCAO.TOTAL.REMESSA_TALAO_ID = iten.REMESSA_TALAO_ID;}
                            if(vm.PRODUCAO.TOTAL.REMESSA_TALAO_ID != iten.REMESSA_TALAO_ID){vm.PRODUCAO.TOTAL.REMESSA_TALAO_ID = iten.REMESSA_TALAO_ID; vm.PRODUCAO.TOTAL.REMESSA_TALAO_ID_C = vm.PRODUCAO.TOTAL.REMESSA_TALAO_ID_C + 1;}

                            if(vm.PRODUCAO.TOTAL.REQUISICAO_ID == 0){vm.PRODUCAO.TOTAL.REQUISICAO_ID = iten.REQUISICAO_ID;}
                            if(vm.PRODUCAO.TOTAL.REQUISICAO_ID != iten.FERRAMENTA_ID){vm.PRODUCAO.TOTAL.REQUISICAO_ID = iten.REQUISICAO_ID; vm.PRODUCAO.TOTAL.REQUISICAO_ID_C = vm.PRODUCAO.TOTAL.REQUISICAO_ID_C + 1;}

                            if(vm.PRODUCAO.TOTAL.MODELO_ID == 0){vm.PRODUCAO.TOTAL.MODELO_ID = iten.MODELO_ID;}
                            if(vm.PRODUCAO.TOTAL.MODELO_ID != iten.MODELO_ID){vm.PRODUCAO.TOTAL.MODELO_ID = iten.MODELO_ID; vm.PRODUCAO.TOTAL.MODELO_ID_C = vm.PRODUCAO.TOTAL.MODELO_ID_C + 1;}

                            if(vm.PRODUCAO.TOTAL.COR_ID == 0){vm.PRODUCAO.TOTAL.COR_ID = iten.COR_ID;}
                            if(vm.PRODUCAO.TOTAL.COR_ID != iten.FERRAMENTA_ID){vm.PRODUCAO.TOTAL.COR_ID = iten.COR_ID; vm.PRODUCAO.TOTAL.COR_ID_C = vm.PRODUCAO.TOTAL.COR_ID_C + 1;}

                            if(vm.PRODUCAO.TOTAL.TAMANHO == 0){vm.PRODUCAO.TOTAL.TAMANHO = iten.TAMANHO;}
                            if(vm.PRODUCAO.TOTAL.TAMANHO != iten.TAMANHO){vm.PRODUCAO.TOTAL.TAMANHO = iten.TAMANHO; vm.PRODUCAO.TOTAL.TAMANHO_C = vm.PRODUCAO.TOTAL.TAMANHO_C + 1;}

                            iten.PERDAS_PERC = Number(iten.PERDAS_PERC);

                            if(Number(iten.TEMPO_REALIZADO_FERRAMENTA) > 0){
                                cont_setu_troc = cont_setu_troc + 1;
                            }

                            if(Number(iten.TEMPO_REALIZADO_AQUECIMENTO) > 0){
                                cont_setu_aque = cont_setu_aque + 1;
                            }

                            if(Number(iten.TEMPO_REALIZADO_APROVACAO) > 0){
                                cont_setu_apro = cont_setu_apro + 1;
                            }

                            vm.PRODUCAO.TOTAL.PERDAS_PERC                 =  Number(vm.PRODUCAO.TOTAL.PERDAS_PERC                ) + Number(iten.PERDAS_PERC                );
                            vm.PRODUCAO.TOTAL.EFICIENCIA                  =  Number(vm.PRODUCAO.TOTAL.EFICIENCIA                 ) + Number(iten.EFICIENCIA                 );
                            vm.PRODUCAO.TOTAL.SETUP_PREVISTO              =  Number(vm.PRODUCAO.TOTAL.SETUP_PREVISTO             ) + Number(iten.SETUP_PREVISTO             );
                            vm.PRODUCAO.TOTAL.SETUP_REALIZADO             =  Number(vm.PRODUCAO.TOTAL.SETUP_REALIZADO            ) + Number(iten.SETUP_REALIZADO            );
                            vm.PRODUCAO.TOTAL.QUANTIDADE                  =  Number(vm.PRODUCAO.TOTAL.QUANTIDADE                 ) + Number(iten.QUANTIDADE                 );
                            vm.PRODUCAO.TOTAL.TEMPO_PREVISTO              =  Number(vm.PRODUCAO.TOTAL.TEMPO_PREVISTO             ) + Number(iten.TEMPO_PREVISTO             );
                            vm.PRODUCAO.TOTAL.TEMPO_REALIZADO             =  Number(vm.PRODUCAO.TOTAL.TEMPO_REALIZADO            ) + Number(iten.TEMPO_REALIZADO            );
                            vm.PRODUCAO.TOTAL.TEMPO_PREVISTO_OPERACIONAL  =  Number(vm.PRODUCAO.TOTAL.TEMPO_PREVISTO_OPERACIONAL ) + Number(iten.TEMPO_PREVISTO_OPERACIONAL );
                            vm.PRODUCAO.TOTAL.TEMPO_PREVISTO_FERRAMENTA   =  Number(vm.PRODUCAO.TOTAL.TEMPO_PREVISTO_FERRAMENTA  ) + Number(iten.TEMPO_PREVISTO_FERRAMENTA  );
                            vm.PRODUCAO.TOTAL.TEMPO_PREVISTO_AQUECIMENTO  =  Number(vm.PRODUCAO.TOTAL.TEMPO_PREVISTO_AQUECIMENTO ) + Number(iten.TEMPO_PREVISTO_AQUECIMENTO );
                            vm.PRODUCAO.TOTAL.TEMPO_PREVISTO_APROVACAO    =  Number(vm.PRODUCAO.TOTAL.TEMPO_PREVISTO_APROVACAO   ) + Number(iten.TEMPO_PREVISTO_APROVACAO   );
                            vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_OPERACIONAL =  Number(vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_OPERACIONAL) + Number(iten.TEMPO_REALIZADO_OPERACIONAL);
                            vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_FERRAMENTA  =  Number(vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_FERRAMENTA ) + Number(iten.TEMPO_REALIZADO_FERRAMENTA );
                            vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_AQUECIMENTO =  Number(vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_AQUECIMENTO) + Number(iten.TEMPO_REALIZADO_AQUECIMENTO);
                            vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_APROVACAO   =  Number(vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_APROVACAO  ) + Number(iten.TEMPO_REALIZADO_APROVACAO  );
                            vm.PRODUCAO.TOTAL.TEMPO_PARADO                =  Number(vm.PRODUCAO.TOTAL.TEMPO_PARADO               ) + Number(iten.TEMPO_PARADO               );
                            vm.PRODUCAO.TOTAL.TEMPO_EXTRA                 =  Number(vm.PRODUCAO.TOTAL.TEMPO_EXTRA                ) + Number(iten.TEMPO_EXTRA                );
                            vm.PRODUCAO.TOTAL.PERDAS                      =  Number(vm.PRODUCAO.TOTAL.PERDAS                     ) + Number(iten.PERDAS                     );
                            vm.PRODUCAO.TOTAL.EFICIENCIA_A                =  Number(vm.PRODUCAO.TOTAL.EFICIENCIA_A               ) + Number(iten.EFICIENCIA_A               );
                            vm.PRODUCAO.TOTAL.EFICIENCIA_B                =  Number(vm.PRODUCAO.TOTAL.EFICIENCIA_B               ) + Number(iten.EFICIENCIA_B               );
                            vm.PRODUCAO.TOTAL.PERDAS_A                    =  Number(vm.PRODUCAO.TOTAL.PERDAS_A                   ) + Number(iten.PERDAS_A                   );
                            vm.PRODUCAO.TOTAL.PERDAS_B                    =  Number(vm.PRODUCAO.TOTAL.PERDAS_B                   ) + Number(iten.PERDAS_B                   );
                            
                        });

                        if(cont_setu_troc == 0){cont_setu_troc=1;}
                        if(cont_setu_aque == 0){cont_setu_aque=1;}
                        if(cont_setu_apro == 0){cont_setu_apro=1;}

                        vm.PRODUCAO.TOTAL.MEDIA_REALIZADO_FERRAMENTA  =  Number(vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_FERRAMENTA ) / cont_setu_troc;
                        vm.PRODUCAO.TOTAL.MEDIA_REALIZADO_AQUECIMENTO =  Number(vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_AQUECIMENTO) / cont_setu_aque;
                        vm.PRODUCAO.TOTAL.MEDIA_REALIZADO_APROVACAO   =  Number(vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_APROVACAO  ) / cont_setu_apro;

                        vm.PRODUCAO.TOTAL.QUANT_REALIZADO_FERRAMENTA  =  cont_setu_troc;
                        vm.PRODUCAO.TOTAL.QUANT_REALIZADO_AQUECIMENTO =  cont_setu_aque;
                        vm.PRODUCAO.TOTAL.QUANT_REALIZADO_APROVACAO   =  cont_setu_apro;

                        var temp_perdas  = vm.PRODUCAO.TOTAL.PERDAS;
                        if(vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_OPERACIONAL < 1){vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_OPERACIONAL = 1;}
                        if(vm.PRODUCAO.TOTAL.CONTADOR < 1){vm.PRODUCAO.TOTAL.CONTADOR = 1;}
                        if(vm.PRODUCAO.TOTAL.PERDAS < 1){vm.PRODUCAO.TOTAL.PERDAS = 1;}

                        vm.PRODUCAO.TOTAL.EFICIENCIA_A   =  Number(vm.PRODUCAO.TOTAL.EFICIENCIA_A ) / vm.PRODUCAO.TOTAL.CONTADOR;
                        vm.PRODUCAO.TOTAL.EFICIENCIA_B   =  Number(vm.PRODUCAO.TOTAL.EFICIENCIA_B ) / vm.PRODUCAO.TOTAL.CONTADOR;
                        vm.PRODUCAO.TOTAL.PERDAS_A       =  Number(vm.PRODUCAO.TOTAL.PERDAS_A     ) / vm.PRODUCAO.TOTAL.CONTADOR;
                        vm.PRODUCAO.TOTAL.PERDAS_B       =  Number(vm.PRODUCAO.TOTAL.PERDAS_B     ) / vm.PRODUCAO.TOTAL.CONTADOR;
                        
                        var tempo_cal = vm.PRODUCAO.TOTAL.TEMPO_PARADO + vm.PRODUCAO.TOTAL.TEMPO_EXTRA + vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_OPERACIONAL;
                        if(tempo_cal < 1){tempo_cal = 1}
                            
                        vm.PRODUCAO.TOTAL.PERDAS_PERC    =  Number((vm.PRODUCAO.TOTAL.PERDAS / vm.PRODUCAO.TOTAL.QUANTIDADE) * 100);
                        vm.PRODUCAO.TOTAL.EFICIENCIA     =  (vm.PRODUCAO.TOTAL.TEMPO_PREVISTO_OPERACIONAL / tempo_cal) * 100;

                        if(vm.PRODUCAO.TOTAL.PERDAS_PERC > 100){vm.PRODUCAO.TOTAL.PERDAS_PERC = 100;}
                        if(vm.PRODUCAO.TOTAL.EFICIENCIA  > 100){vm.PRODUCAO.TOTAL.EFICIENCIA  = 100;}

                        vm.PRODUCAO.TOTAL.PERDAS = temp_perdas;

                        if(response.PRODUCAO.length > 0){
                            $('#modal-detalhar-producao').modal();
                        }else{
                            showSuccess('Sem registros para listar');
                        }

                        var cont_paradas = 0;
                        angular.forEach(vm.PRODUCAO.PARADAS_A, function(iten, key) {

                            cont_paradas++;

                            if(vm.PRODUCAO.TOTAL.ESTACAO2 != iten.ESTACAO){
                                vm.PRODUCAO.TOTAL.ESTACAO2 = iten.ESTACAO;

                                vm.PRODUCAO.ESTACOES2.push({
                                    ID: iten.ESTACAO,
                                    DESCRICAO: iten.ESTACAO_DESCRICAO
                                });
                            }

                            if(iten.TIPO == 1){
                                vm.PRODUCAO.TOTAL.PARADAS_A = Number(vm.PRODUCAO.TOTAL.PARADAS_A) + Number(iten.TEMPO_PARADO);
                            }

                        });

                        vm.PRODUCAO.TOTAL.MOTIVOS = [];
                        var i = 0;

                        angular.forEach(vm.PRODUCAO.PARADAS_S, function(iten, key) {
                            i = 0;
                            angular.forEach(vm.PRODUCAO.TOTAL.MOTIVOS, function(iten2, key) {
                                if(iten2.DESCRICAO == iten.MOTIVO_PARADA){
                                    i = 1;
                                }
                            });

                            if(i == 0){

                                var obj = {
                                    SOMA: 0,
                                    DESCRICAO: iten.MOTIVO_PARADA,
                                    MARCAR: iten.MARCAR
                                };

                                vm.PRODUCAO.TOTAL.MOTIVOS.push(obj);  
                            }

                            angular.forEach(vm.PRODUCAO.TOTAL.MOTIVOS, function(iten2, key) {
                                if(iten2.DESCRICAO == iten.MOTIVO_PARADA){
                                        iten2.SOMA = Number(iten2.SOMA) + Number(iten.TEMPO_PARADO);
                                }
                            });

                            if(iten.TIPO == 1){
                                vm.PRODUCAO.TOTAL.PARADAS_S = Number(vm.PRODUCAO.TOTAL.PARADAS_S) + Number(iten.TEMPO_PARADO);
                            }

                        });

                        if(cont_paradas == 0){
                            vm.PRODUCAO.ESTACOES2.push({
                                ID: 0,
                                DESCRICAO: "SEM REGISTROS"
                            });

                            var obj = {
                                SOMA: 0,
                                DESCRICAO: "SEM REGISTROS"
                            };

                            vm.PRODUCAO.TOTAL.MOTIVOS.push(obj); 
                        }

                    },function(erro){
                            
                    }
                );
            },

            modalProdTalao:function(item){

                var temp = angular.copy(vm.FILTRO, temp);
                    temp.ITEM = item;
                    temp.ESTACAO  = item.ESTACAO;
                    temp.TALAO_ID = item.TALAO_ID;
                    temp.FILTRAR_TALAO = 999;

                var filtro = JSON.stringify(temp);
                temp = null;

                $ajax.post('/_22130/getProducaoTalao',filtro,{contentType: 'application/json'})
                    .then(function(response) {

                        vm.MODAL = [];

                        angular.forEach(response.ESTACOES, function(estacao, key) {
                            if(estacao.ESTACAO == item.ESTACAO){
                                vm.MODAL = estacao.TALOES[0];
                                vm.MODAL.MAQUINA = [];
                                vm.MODAL.ITEM_PRODUCAO = item;
                            }
                        });

                        if(vm.MODAL.ID > 0){
                            $ajax.post('/_22130/getInfoTalao',JSON.stringify(vm.MODAL),{contentType: 'application/json'})
                            .then(function(response) {

                                vm.MODAL.INFO_TALAO = [];
                                vm.MODAL.INFO_TALAO = response;
                            });

                            $('#modal-producao-talao').modal();
                        }

                    },function(erro){
                            
                    }
                );
            },

            confirmeJornada:function(estacao){

                var info = JSON.stringify({
                    ESTACAO : estacao,
                    UP      : vm.FILTRO.UP_ID
                });

                $ajax.post('/_22130/jornadaIntervalo',info,{contentType: 'application/json'})
                    .then(function(response) {

                        addConfirme('Alterar jornada de trabalho',
                                '<span style="font-size: 1.5vw;">Utilizar <input type="number" max="" min="1" style=" font-size: 1.5vw;width: 6vw;display: inline-flex;" class="form-control minutos_trabalhar" value="0">'+
                                ' minutos do próximo intervalo disponível como minutos produtivos</span>'+
                                '<br>'+
                                '<br>'+
                                '<span style="font-size: 1.5vw;">Intervalo:<span class="tempo-intervalo1"></span> a <span class="tempo-intervalo2"></span></span>'
                                ,[obtn_ok,obtn_cancelar],
                            [
                            {ret:1,func:function(e){

                                var info = JSON.stringify({
                                    ESTACAO : estacao,
                                    UP      : vm.FILTRO.UP_ID,
                                    MINUTOS : $('.minutos_trabalhar').val()
                                });

                                $ajax.post('/_22130/jornadaGravar',info,{contentType: 'application/json'})
                                    .then(function(response) {

                                    },function(erro){
                                            
                                    }
                                );

                            }},
                            {ret:2,func:function(e){


                            }},
                            ]  
                        );

                        setTimeout(function(){

                            $('.tempo-intervalo1').html(response[0].DATAHORA_INICIO);
                            $('.tempo-intervalo2').html(response[0].DATAHORA_FIM);

                            $('.minutos_trabalhar').val(response[0].MINUTOS_DESCANSO);
                            $('.minutos_trabalhar').attr('max',response[0].MINUTOS_DESCANSO);
                            $('.minutos_trabalhar').val(response[0].MINUTOS_DESCANSO);
                            $('.minutos_trabalhar').focus(); 

                        }, 100);

                    },function(erro){
                            
                    }
                );

            },

            fecharSetap:function(){
                clearInterval(vm.TIME);
            },

            telaSetup:function(estacao,show){
                clearInterval(vm.TIME);

                vm.SETUP = estacao.TALOES[0];

                if(show){
                    $('.item-setup_1').find('.tempo-setup').html('00:00:00');
                    $('.item-setup_2').find('.tempo-setup').html('00:00:00');
                    $('.item-setup_3').find('.tempo-setup').html('00:00:00');

                    $('#modal-setup').modal('show');
                }

                vm.TIME = setInterval(function(){
                    calcTempo(vm.SETUP.SETUP);
                },1000);

            },

            iniciarSetup:function(setup_id,ultimo,talao){

                var temp = angular.copy(talao, temp);

                temp.MAQUINA = null;

                temp.SETUP_ID     = setup_id;
                temp.SETUP_ULTIMO = ultimo;
                temp.DATA_SETUP   = moment().format('YYYY-MM-DD HH:m:s');

                var estacao = talao.ESTACAO;

                $ajax.post('/_22130/iniciarSetup',JSON.stringify(temp),{contentType: 'application/json'})
                    .then(function(response) {
                        vm.Acoes.filtrar();
                    }
                );

            },

            filtrar:function(){

                vm.FILTRO.TALAO_ID = 0;

                var estacao_setup = vm.SETUP.ESTACAO;

                $ajax.post('/_22130/filtarTaloes',JSON.stringify(vm.FILTRO),{contentType: 'application/json'})
                    .then(function(response) {

                        var link = encodeURI(urlhost + '/_22130/'+vm.FILTRO.ESTABELECIMENTO+'/'+vm.FILTRO.GP_ID+'/'+vm.FILTRO.GP_DESCRICAO+'/'+vm.FILTRO.UP_ID+'/'+vm.FILTRO.UP_DESCRICAO+'/'+vm.FILTRO.ESTACAO_ID+'/'+vm.FILTRO.ESTACAO_DESCRICAO);
                        window.history.replaceState('Delfa - GC', 'Title', link);

                        var togle = $('.btn-toggle-filter').hasClass('collapsed');

                        if(!togle){
                            $('.btn-toggle-filter').trigger('click');
                        }

                        vm.DADOS = null;
                        vm.DADOS = response;

                        vm.TURNO = response.TURNO;
                        
                        angular.forEach(vm.DADOS.ESTACOES, function(estacao, key) {

                            if(estacao_setup == estacao.ESTACAO){
                               vm.Acoes.fecharSetap();
                               vm.Acoes.telaSetup(estacao,false);
                            }

                        });

                       montarIndicador();
                    }
                );
            },

            ferramentasLivres:function(){

                var temp = {
                    FERRAMENTA  : vm.MODAL.FERRAMENTA_ID,
                    ESTACAO     : vm.MODAL.ESTACAO,
                    UP          : vm.FILTRO.UP_ID,
                    DATA_INICIO : vm.MODAL.DATAHORA_INICIO,
                    DATA_FIM    : vm.MODAL.DATAHORA_FIM
                }

                vm.FERRAMENTAS = [];

                $('#modal-troca-ferramenta').modal();
                

                $ajax.post('/_22130/ferramentasLivres',JSON.stringify(temp),{contentType: 'application/json'})
                    .then(function(response) {
                        vm.FERRAMENTAS = response;
                    }
                );

            },

            trocaFerramenta:function(ferramenta){
                trocarFerramenta(ferramenta);
            },

            paradaEstacao:function(justificativa,descricao){

                var ds = {
                    TABELA_ID       : vm.ESTACAO_MODAL.ESTACAO,
                    TABELA          : 'ESTACAO',
                    STATUS          : justificativa,
                    VINCULO_ID      : vm.FILTRO.GP_ID,
                    SUBVINCULO_ID   : vm.FILTRO.UP_ID,
                    OPERADOR_ID     : vm.OPERADOR.OPERADOR_ID
                };
                
                $ajax.post('/_22130/pararEstacao',JSON.stringify(ds),{contentType: 'application/json'})
                    .then(function(response) {
                    
                        $('#modal-parar-estacao').modal('hide');

                        vm.Acoes.filtrar();

                        showSuccess('Estação foi parada');
                    }
                );
            },

            justIneficiencia:function(justificativa,descricao){

                if(true){
                    addConfirme('Justificativa',
                            'Observação para o registro <b>' + descricao + '<b>:'+
                            '<p>'+
                            '<input type="search" class="form-control input-medio justificativa_ineficiencia_reg" maxlength="90" autocomplete="off">'+
                            ''

                            ,[obtn_ok,obtn_cancelar],
                        [
                        {ret:1,func:function(e){

                            var ds = {
                                    TABELA_ID       : vm.TALAO_MODAL.PROGRAMACAO_ID,
                                    TABELA          : 'INEFICIENCIA',
                                    STATUS          : justificativa,
                                    VINCULO_ID      : vm.FILTRO.GP_ID,
                                    SUBVINCULO_ID   : vm.FILTRO.UP_ID,
                                    OPERADOR_ID     : vm.OPERADOR.OPERADOR_ID,
                                    OBSERVACAO      : $('.justificativa_ineficiencia_reg').val()
                                };

                            $ajax.post('/_22130/justIneficiencia',JSON.stringify(ds),{contentType: 'application/json'})
                                .then(function(response) {
                                    
                                    if(response.length > 0){
                                        vm.MODAL.JUSTIFICATIVA_INEFIC = response[0].DESC;
                                    }

                                    $('#modal-just-inefic').modal('hide');

                                    showSuccess('Talão Justificado!');
                                }
                            );

                        }},
                        {ret:2,func:function(e){


                        }},
                        ]  
                    );

                    setTimeout(function(){$('.justificativa_ineficiencia_reg').focus();},300);

                }else{
                    var ds = {
                            TABELA_ID       : vm.TALAO_MODAL.PROGRAMACAO_ID,
                            TABELA          : 'INEFICIENCIA',
                            STATUS          : justificativa,
                            VINCULO_ID      : vm.FILTRO.GP_ID,
                            SUBVINCULO_ID   : vm.FILTRO.UP_ID,
                            OPERADOR_ID     : vm.OPERADOR.OPERADOR_ID,
                            OBSERVACAO      : ""
                        };

                    $ajax.post('/_22130/justIneficiencia',JSON.stringify(ds),{contentType: 'application/json'})
                        .then(function(response) {
                            
                            if(response.length > 0){
                                vm.MODAL.JUSTIFICATIVA_INEFIC = response[0].DESC;
                            }

                            $('#modal-just-inefic').modal('hide');                          

                            showSuccess('Talão Justificado!');
                        }
                    );   
                }
            },

            paradaTalao:function(justificativa,descricao){

                var ds = {
                    TABELA_ID       : vm.TALAO_MODAL.ID,
                    TABELA          : 'TALAO_ACUMULADO',
                    STATUS          : justificativa,
                    VINCULO_ID      : vm.FILTRO.GP_ID,
                    SUBVINCULO_ID   : vm.FILTRO.UP_ID,
                };

                vm.TALAO_MODAL.STATUS_PARADA = justificativa;

                $('#modal-parar-talao').modal('hide');

                acaoTalao(vm.MODAL,'PAUSAR'); 
               
            },

            initModal:function(talao,estacao){
                if(Object.keys(talao).length > 0){

                    vm.MODAL = [];
                    vm.MODAL = talao;

                    vm.MODAL.MAQUINA = [];

                    if(vm.MODAL.ID > 0){
                        $ajax.post('/_22130/getInfoTalao',JSON.stringify(vm.MODAL),{contentType: 'application/json'})
                        .then(function(response) {

                            vm.MODAL.INFO_TALAO = [];
                            vm.MODAL.INFO_TALAO = response;
                        });

                        angular.forEach(vm.DADOS.ESTACOES, function(estacao, key) {
                            if(estacao.ESTACAO == vm.MODAL.ESTACAO){
                                vm.MODAL.MAQUINA   = estacao;
                            }
                        });

                        var data = vm.MODAL.DATA_PRODUCAO + '';
                        vm.MODAL.DATA_PRODUCAO  = data.substr(0, 10).split('-').reverse().join('/');
                        $('#modal-detalhar-talao').modal();
                    }

                }
            },

            modalTrocaEstacao:function(){

                $('#modal-troca-estacao').modal();

                var temp = angular.copy(vm.DADOS.ESTACOES, temp);
                vm.DADOS_TEMP.ESTACOES = null;

                setTimeout(function() {
                    $scope.$apply(function () {  
                        vm.DADOS_TEMP.ESTACOES = temp;
                    });
                },500);

                setTimeout(function(){

                    $( ".sortable" ).sortable({
                        revert: true,
                        cancel      : '.itens_nao_troca',   //Quais itens não podem ser selecionados
                        items       : '.sortable_itens',    //Quais os itens que podem ser selecionados
                        appendTo    : ".sortable",          //Por onde os itens selecionados podem percorrer
                        cursor      : 'move',               //Cursor enquanto arrasta itens
                        tolerance   : 'pointer',            //Em que ponto poderá ser soltados os itens
                        delay       : 150,                  //Tempo em milisegundos que demora para começar a arrastar os itens
                        distance    : 1,    
                        revert      : false,                //Ativa o efeito ao soltar suavisado
                        tolerance   : "pointer",
                        placeholder : {
                            element: function(currentItem) {
                                return $('<div class="scoll-talao"><div class="table-cell"><div></div><div class="grup-valores"><div class="div-info-1"><div></div><div></div></div><div class="div-info-2"><div class="remessa"></div>    <div></div>    <div class="remessa"></div></div></div><div class="grup-legenda">    <div>    <div></div>    <div></div>    <div></div>    </div></div></div></div>')[0];
                            },
                            update: function(container, p) {
                                return;
                            }
                        },
                        stop: function(e, ui){

                            //*
                            var est = $(ui.item).prev('.scoll-talao').attr('estacao');
                            var hor = '2000-01-01 00:00:00';

                            vm.ESTACAO = est;
                            vm.MODAL.ESTACAO = est;

                            var item = $(ui.item).prev('.scoll-talao');
                            var encontrado = 0;

                            for(var k = 0; k < 6; k++){

                                var t = $(item).attr('horafim');

                                item = $(item).prev('.scoll-talao');

                                if(typeof t != 'undefined' && encontrado == 0){
                                    hor = t;
                                    encontrado = 1;
                                }
                                
                            }

                            $scope.$apply(function () {
                                vm.MODAL.ABILITAR_TROCA = 1;
                                vm.MODAL.HORA_TALAO_ANTERIOR = hor;
                            });
                            

                        }
                    });

                    $( ".sortable_itens" ).find('div').disableSelection();

                },500);
            },

            pararEstacao:function(estacao){
                vm.ESTACAO_MODAL = estacao;
                $('#modal-parar-estacao').modal();
            },

            justInefic:function(talao){
                vm.TALAO_MODAL = talao;
                $('#modal-just-inefic').modal();
            },

            pararTalao:function(talao){
                vm.TALAO_MODAL = talao;
                $('#modal-parar-talao').modal();
            },

            trocarEstacao:function(estacao_id){
                acaoTalao(vm.MODAL,'TROCAR');
            },

            validarMatriz:function(){

                vm.FILTRO.MATRIZ_BARRAS ='';

                if(vm.MODAL.TROCA_MATRIZ.trim() == 'M' && vm.MODAL.TALAO_ENCERRADO == 0 && ((vm.MODAL.FLAG_REPROGRAMADO == 1 && vm.MODAL.PROGRAMACAO_STATUS == 1) || (vm.MODAL.PROGRAMACAO_STATUS == 0))){
                    $('#modal-validar-matriz').modal();

                    setTimeout(function(){
                        $('.matriz_barra').focus();
                    },500);

                }else{
                    acaoTalao(vm.MODAL,'INICIAR');
                }

            },

            modalLogin:function(){
                $('#modal-autenticar').modal();
                $('.usuario_barra').val('');

                setTimeout(function(){
                    $('.usuario_barra').focus();
                },700);
            },

            modalLogin2:function(estacao){

                vm.ESTACAO_MODAL = estacao;

                if(vm.OPERADOR.OPERADOR_ID > 0){
                    iniciarEstacao(estacao);    
                }else{
                    $('#modal-autenticar2').modal();
                    $('.usuario_barra2').val('');

                    setTimeout(function(){
                        $('.usuario_barra2').focus();
                    },500);
                }
            },

            logarUser:function(){
                $ajax.post('/_22050/autenticacao',JSON.stringify(vm.OPERADOR),{contentType: 'application/json'})
                    .then(function(response) {

                        vm.OPERADOR = response[0];
                        vm.OPERADOR.LOGADO = true;
                        $('#modal-autenticar').modal('hide'); 

                    },function(erro){

                        $('.usuario_barra').val('');

                    }
                );
            },

            logarUser2:function(estacao){
                $ajax.post('/_22050/autenticacao',JSON.stringify(vm.OPERADOR),{contentType: 'application/json'})
                    .then(function(response) {
                        vm.OPERADOR = response[0];
                        vm.OPERADOR.LOGADO = true;
                        $('#modal-autenticar2').modal('hide'); 

                        iniciarEstacao(estacao);
                    }
                );
            },

            LogOff:function(){
                vm.OPERADOR = null;
                vm.OPERADOR = {
                    operacao_id     :7, //registrar producao
                    valor_ext       :1,
                    barras          : '',
                    abort           :true,
                    verificar_up    :true,
                    LOGADO          :false,
                    OPERADOR_NOME   :'',
                    OPERADOR_ID     :'',
                    OPERADOR_BARRAS : ''
                };
            },

            iniciarTalao:function(){
                acaoTalao(vm.MODAL,'INICIAR');
            },

            pausarTalao:function(){
                acaoTalao(vm.MODAL,'PAUSAR');
            },

            finalizarTalao:function(){
                acaoTalao(vm.MODAL,'FINALIZAR');
            },

            consultarMatriz:function(){

                var MTZ = {
                    MATRIZ_BARRAS : vm.FILTRO.MATRIZ_BARRAS,
                    FERRAMENTA_ID : vm.MODAL.FERRAMENTA_ID
                };

                $ajax.post('/_22130/consultarMatriz',JSON.stringify(MTZ),{contentType: 'application/json'})
                    .then(function(response) {

                        var validacao = response['VALIDACAO'];
                        var mensagem  = response['MENSAGEM'];

                        if(validacao){
                            acaoTalao(vm.MODAL,'INICIAR');
                        }else{
                            addBalao(mensagem,'danger');
                        }

                    },function(response) {

                    }
                );
            },

            infoComponentes: function(Componente){
                console.log(Componente);
                $ajax.post('/_22130/getComposicao',Componente)
                    .then(function(response) {
                        vm.composicao = {Dados:response, Componente: Componente};
                    },function(response) {

                    }
                );
                $('#modal-detalhar-componentes').modal('show');
            }

        };

        $scope.$watch('vm.FILTRO.ESTABELECIMENTO', function (newValue, oldValue, scope) {
            if(newValue > 0 && newValue != oldValue && (vm.FILTRO.GP_ID < 1 || vm.FILTRO.GP_ID == '') ){
                setTimeout(function(){

                    if($('._auto_filtro').val() > 0){
                        setTimeout(function(){

                            if($('._auto_filtro').val() > 0){
                                ///*
                                var GP_ID             = $('._auto_gp_id').val();
                                var UP_ID             = $('._auto_up_id').val();
                                var ESTACAO_ID        = $('._auto_estacao_id').val();
                                var GP_DESCRICAO      = $('._auto_gp_descricao').val();
                                var UP_DESCRICAO      = $('._auto_up_descricao').val();
                                var ESTACAO_DESCRICAO = $('._auto_estacao_descricao').val();

                                $('._gp_id').val(GP_ID);
                                $('._up_id').val(UP_ID);
                                $('._estacao_id').val(ESTACAO_ID);
                                $('._gp_descricao').val(GP_DESCRICAO);
                                $('._up_descricao').val(UP_DESCRICAO);
                                $('._estacao_descricao').val(ESTACAO_DESCRICAO);

                                $scope.$apply(function () {
                                    vm.FILTRO.GP_ID             = GP_ID;
                                    vm.FILTRO.UP_ID             = UP_ID;
                                    vm.FILTRO.ESTACAO_ID        = ESTACAO_ID;
                                    vm.FILTRO.GP_DESCRICAO      = GP_DESCRICAO;
                                    vm.FILTRO.UP_DESCRICAO      = UP_DESCRICAO;
                                    vm.FILTRO.ESTACAO_DESCRICAO = ESTACAO_DESCRICAO;
                                });
                                //*/

                                $('.btn-filtrar').trigger('click');
                            }  

                        },200);

                    }else{

                        if(!($('._gp_id').val() > 0)){
                            $('.consulta_gp_grup').find('.btn-filtro-consulta').trigger('click');
                        }

                    }
                },400);
            }
        }, true);

        $scope.$on('bs-init', function(ngRepeatFinishedEvent) {
            bootstrapInit();
        });

        $scope.$watch('vm.FILTRO.GP_ID', function (newValue, oldValue, scope) {
            if(!($('._up_id').val() > 0)){
                validarImput(newValue,oldValue,'GP','consulta_up_group');
            }
        }, true);

        $scope.$watch('vm.FILTRO.UP_ID', function (newValue, oldValue, scope) {
            if(!($('._estacao_id').val() !== '')){
                validarImput(newValue,oldValue,'UP','consulta_estacao_group');
            } 
        }, true);

        $scope.$watch('vm.TIPO_EFICIENCIA', function (newValue, oldValue, scope) {
            if(newValue != oldValue){
                montarIndicador();
            } 
        }, true);

        $scope.$watch('vm.FILTRO.TURNO', function (newValue, oldValue, scope) {
            if(newValue != oldValue){
                montarIndicador();
            } 
        }, true);

        init();
        
    };

    Ctrl.$inject = ['$scope','$ajax','$timeout','$filter','$window','$interval'];

    var bsInit = function() {
        return function(scope, element, attrs) {         
            bootstrapInit();
        };
    };
    
    var parseData = function() {
        return function(input) {
            if ( input ) return new Date(input);
        };
    };
        
    angular
    .module    ('app'           , ['angular.filter','vs-repeat','gc-ajax','gc-form','gc-utils','gc-find','gc-transform','ngSanitize'])
    .filter    ('parseDate'     , parseData)
    .controller('Ctrl'          , Ctrl     );
        
})(angular);

 ;(function($){

    $(document).on( "click", ".btn-atualizat-tela", function() {
        $('.btn-filtrar').trigger('click');
    });

    $(document).on( "click", "#fechar-resumo", function() {
        $('#resumo-producao').collapse('hide');
    });

    $(document).on( "click", "#abrir-resumo", function() {
        $('#resumo-producao').collapse('show');
    });

    function padLeft(nr, n, str){
        return Array(n-String(nr).length+1).join(str||'0')+nr;
    }

    function calcTempo2(){
        $('.itens_tempo').each(function(i, el){

            var data   = $(el).data('data');
            var server = $('#_hora-servidor').val();

            var date1 = new Date(data);
            var date2 = new Date(server);
            var timeDiff = date2 - date1;

            var segundos = Math.trunc(timeDiff / 1000);
            var minutos  = Math.trunc(segundos / 60  );
            var horas    = Math.trunc(minutos  / 60  );
            var dias     = Math.trunc(horas    / 24  );

            horas = horas - (dias * 24);
            minutos = minutos - (horas * 60) - (dias * 24 * 60);
            segundos = segundos - (minutos * 60) - (horas * 60 * 60) - (dias * 24 * 60 * 60);

            dias = padLeft(dias,2);
            horas = padLeft(horas,2);
            minutos = padLeft(minutos,2);
            segundos = padLeft(segundos,2);

            var corrido = $(el).find('.tempo-corrido');

            if(dias > 0){
                $(corrido).html(dias+'dias e '+horas+':'+minutos+':'+segundos);
            }else{
                $(corrido).html(horas+':'+minutos+':'+segundos);    
            }

        });
    }

    var atualiza_tempo = null;
    var atualizar = null;
    var abrir_auto = true;
    var exibir_por_tempo = true;

    setInterval(function(){
        calcTempo2();    
    },1000);


//    function exibir(){
//
//        $('#resumo-producao').collapse('hide');
//
//        clearTimeout(atualizar);
//        clearInterval(atualiza_tempo);
//
//        if(abrir_auto){
//
//            atualizar = setTimeout(function(){
//
//                clearTimeout(atualizar);
//
//                if(abrir_auto){
//                    $('#resumo-producao').collapse('show');
//
//                    atualiza_tempo = setInterval(function(){
//                        calcTempo2();
//                        console.log('CalcTempo2');    
//                    },1000);
//                }
//
//            },30000);        
//        }
//    };


//    $(document)
//            .on('mousemove', function() {
//                exibir();
//           })
//            .on('keydown', function() {
//                exibir();
//            })
//            .on('click', '#status-producao', function() {
//                exibir_por_tempo = $(this).hasClass('collapsed') ? true : false;
//            })
//            .on('switchChange.bootstrapSwitch', '.chk-switch', function(event, state) {
//                abrir_auto = state ? true : false;
//                console.log(abrir_auto);
//            })
//        ;

    

})(jQuery);
//# sourceMappingURL=_22130.js.map
