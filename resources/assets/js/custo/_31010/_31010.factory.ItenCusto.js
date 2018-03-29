angular
    .module('app')
    .factory('ItenCusto', ItenCusto);
    

	ItenCusto.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope',
        '$compile',
        '$consulta',
    ];

function ItenCusto($ajax, $httpParamSerializer, $rootScope, $q, $timeout, gcCollection, gScope, $compile, $consulta) {

    function ItenCusto(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.ItenCusto = this;

        this.Consulta   = new $consulta();

        this.Contador = 0;
    }

    ItenCusto.prototype.CalcularTotal = function(){
        var that = this;

        gScope.Total.Custo         = 0;
        gScope.Total.CustoT        = 0;
        gScope.Total.Quantidade    = 0;
        gScope.Total.Venda         = 0;
        gScope.Total.Despesa       = 0;
        gScope.Total.UnidadeMedida = '';
        gScope.VlrComissao         = 0;

        angular.forEach(gScope.ListaItens, function(item, key) {
            gScope.Total.Custo      = Number(gScope.Total.Custo)      + Number(item.Cst_Produto);
            gScope.Total.Quantidade = Number(gScope.Total.Quantidade) + Number(item.Quantidade);
            gScope.Total.CustoT     = Number(gScope.Total.CustoT)     + Number(item.Cst_t_Produto);
            gScope.Total.Venda      = Number(gScope.Total.Venda)      + Number(item.TotalPrecoVenda);
            gScope.Total.Despesa    = Number(gScope.Total.Despesa)    + (Number(item.Despesa) * Number(gScope.PERC_FATURAMENTO.VALOR));

            var vlr = gScope.Total.Venda;
            
            gScope.VlrComissao = (Number(gScope.Total.Venda) * (Number(gScope.Fatores.Comissao) / 100));

            if(gScope.Fatores.Comissao > 0){
            // vlr = Number(gScope.Total.Venda) + gScope.VlrComissao;
            }
            
            gScope.Total.Venda = vlr;

            if(gScope.Total.UnidadeMedida == ''){
                gScope.Total.UnidadeMedida = item.UnidadeMedida;
            }else{
                if(gScope.Total.UnidadeMedida != item.UnidadeMedida){
                    gScope.Total.UnidadeMedida = '*';
                }
            }

        });
    };

    var obj_item = {
        CALCULADO        : 0,
        DADOS            : [],
        SELECTED         : [],
        Modelo           : {},
        Cor              : {},
        Tamanho          : {},
        Perfil           : {},
        ConsultaModelo   : {},
        ConsultaCor      : {},
        ConsultaTamanho  : {},
        UnidadeMedida    : '',
        Quantidade       : 200,
        Cst_u_Produto    : 0,
        Cst_t_Produto    : 0,
        Margem           : 0,
        PrecoVenda       : 0,
        Despesa          : 0,
        MarckUp          : 0,
        TotalPrecoVenda  : 0,
        Contribuicao     : 0,
        ContribuicaoReal : 0,
        MUDAR_PRECO      : false,
        MUDAR_CONTRIBUICAO: false, 
        PERCENTUAL_PERDA : 0,
        TEMPO_MODELO     : 0,
        SETUP_MODELO     : 0,
        DESPESA          : 0,
        CM_DESPESA       : 0,
        C_DESPESA        : 0,
        PERC_DESPESA     : 0,
        ImpostoDeRenda   : 0,
        DESPESA_DETALHE  : [],
        DESPESA_ITEM     : [],
        Ficha            : {
            PAI          : null, 
            ITENS        : [],
            DADOS        : [],
            FILTRO       : '',
            ORDEM        : 'NIVEL',
            SELECTED     : [],
            MO           : [],
            PRODUTO_TROCA: '',
            OLD_PRODUTO  : {},
            NEW_PRODUTO  : {},
            LST_TROCA    : [],
            AGRUPAMENTO  : [],
            FILHOS       : [],
            MAO_DE_OBRA1 : [],
            MAO_DE_OBRA2 : [],
            MAO_DE_OBRA3 : [],
            VALOR_DESPESA: 0,
            DESPESA1     : [],
            DESPESA2     : [],
            VALOR_ICMS   : 0,
            LISTA_MATERIA: [],
            LISTA_DENSIDADE   : {DADOS : [], VISIVEL : false},
            TOTAL_MAO_DE_OBRA : {
                COLABORADORES : 0,
                CUSTO_MINUTO  : 0,
                CUSTO_OPE     : 0,
                CUSTO_SET     : 0,
                CUSTO_OPE_T   : 0,
                CUSTO_SET_T   : 0,
                CUSTO_TOTAL   : 0,
                SALARIO       : 0
            },
            ESTACOES     : 0,
            MINUTOS_DIA  : 0,
            FATOR        : 0,
            CONFIGURACAO : [],
            ConfirmarTroca : function(){
                var that = this;
                var add =  true;

                if(that.LST_TROCA.length > 0){

                    angular.forEach(that.LST_TROCA, function(item, key) {
                        if(item.PRODUTO == that.OLD_PRODUTO.PRODUTO_CONSUMO && item.NIVEL == that.OLD_PRODUTO.NIVEL){
                            add = false;
                            item.PRODUTO      = that.NEW_PRODUTO.ID;
                            item.TROCA        = that.NEW_PRODUTO.ID;
                            item.TAMANHO      = that.NEW_PRODUTO.TAMANHO,
                            item.DESC_TAMANHO = that.NEW_PRODUTO.DESC_TAMANHO;
                        }
                    });

                }

                if(add == true){

                    console.log('Trocar Item:');
                    console.log(that.LST_TROCA);

                    that.LST_TROCA.push({
                        NIVEL        : that.OLD_PRODUTO.NIVEL,
                        ORIGINAL     : that.OLD_PRODUTO.PRODUTO_CONSUMO,
                        PRODUTO      : that.NEW_PRODUTO.ID,
                        TROCA        : that.NEW_PRODUTO.ID,
                        TAMANHO      : that.NEW_PRODUTO.TAMANHO,
                        DESC_TAMANHO : that.NEW_PRODUTO.DESC_TAMANHO,
                    });
                }

                that.PRODUTO_TROCA = '';

                angular.forEach(that.LST_TROCA, function(item, key) {
                    that.PRODUTO_TROCA = that.PRODUTO_TROCA+'|' +item.ORIGINAL+','+item.TROCA +','+item.TAMANHO;
                });

                if(that.PRODUTO_TROCA.length > 0){
                    that.consultar(5);
                    $('#modal-trocar-produto').modal('hide');
                }else{
                    showErro('Erro ao trocar produto. Troca:{'+that.PRODUTO_TROCA+'}');
                }

            },
            selectDensidade : function(item){
                var that = this;

                that.NEW_PRODUTO.TAMANHO      = that.OLD_PRODUTO.TAMANHO_CONSUMO;
                that.NEW_PRODUTO.DESC_TAMANHO = that.OLD_PRODUTO.DESC_TAMANHO;
                that.NEW_PRODUTO.ID           = item.PRODUTO_ID;
                that.NEW_PRODUTO.DESCRICAO    = item.PRODUTO_DESCRICAO;

                that.LISTA_DENSIDADE.ITEM     = item.PRODUTO_DESCRICAO;
                that.LISTA_DENSIDADE.VISIVEL  = false;
                that.LISTA_DENSIDADE.SELECTED = true;
            },
            limparDensidade : function(item){
                var that = this;

                that.NEW_PRODUTO.TAMANHO      = '0';
                that.NEW_PRODUTO.DESC_TAMANHO = '0';
                that.NEW_PRODUTO.ID           = '0';
                that.NEW_PRODUTO.DESCRICAO    = '' ;

                that.LISTA_DENSIDADE.ITEM     = '';
                that.LISTA_DENSIDADE.VISIVEL  = false;
                that.LISTA_DENSIDADE.SELECTED = false;
            },
            consultarDensidade : function(){
                var that = this;

                that.LISTA_DENSIDADE.VISIVEL  = false;
                that.LISTA_DENSIDADE.SELECTED = false;
                that.LISTA_DENSIDADE.DADOS    = [];

                return $q(function(resolve,reject){

                    var ds = {
                        DENSIDADE  : that.DENSIDADE,
                        ESPESSURA  : that.ESPESSURA,
                        PRODUTO_ID : that.OLD_PRODUTO.PRODUTO_CONSUMO
                    };

                    $ajax.post('/_31010/consultarDensidade',ds,{contentType: 'application/json'})
                        .then(function(response) {

                            that.LISTA_DENSIDADE.DADOS    = response;
                            that.LISTA_DENSIDADE.VISIVEL  = true;
                            that.LISTA_DENSIDADE.SELECTED = false;

                            console.log(response);
                            resolve(true);

                        },function(e){
                            reject(e);
                        }
                    );

                });
            },
            TrocarProduto : function(item){
                var that = this;

                gScope.ConsultaProduto.apagar();

                that.OLD_PRODUTO = item;

                that.DENSIDADE = 0;
                that.ESPESSURA = 0;

                that.LISTA_DENSIDADE.ITEM     = '';
                that.LISTA_DENSIDADE.VISIVEL  = false;
                that.LISTA_DENSIDADE.SELECTED = false;

                $('#modal-trocar-produto').modal();
            },
            ConsultarConfiguracao : function(flag) {
                var that = this;

                return $q(function(resolve,reject){

                    var ds = {
                        MODELO  : that.PAI.ConsultaModelo.selected,
                        COR     : that.PAI.ConsultaCor.selected,
                        TAMANHO : that.PAI.ConsultaTamanho.selected,
                        DATA    : gScope.DATA
                    };

                    $ajax.post('/_31010/ConsultarConfiguracao',ds,{contentType: 'application/json'})
                        .then(function(response) {
                            that.CONFIGURACAO = response;

                            resolve(true);

                        },function(e){
                            reject(e);
                        }
                    );

                });

            },
            ConsultarDensidade : function(produto) {
                var that = this;

                return $q(function(resolve,reject){

                    var ds = {
                        MODELO  : that.PAI.ConsultaModelo.selected,
                        COR     : that.PAI.ConsultaCor.selected,
                        TAMANHO : that.PAI.ConsultaTamanho.selected,
                        DATA    : gScope.DATA
                    };

                    $ajax.post('/_31010/ConsultarConfiguracao',ds,{contentType: 'application/json'})
                        .then(function(response) {
                            that.CONFIGURACAO = response;

                            resolve(true);

                        },function(e){
                            reject(e);
                        }
                    );

                });

            },
            ConsultarEstacoes : function(flag) {
                var that = this;

                return $q(function(resolve,reject){

                    var ds = {
                            DATA : gScope.DATA,
                            CONF : that.CONFIGURACAO
                        };

                    $ajax.post('/_31010/ConsultarEstacoes',ds,{contentType: 'application/json'})
                        .then(function(response) {
                            that.ESTACOES       = response.ESTACOES; 
                            that.FATOR          = response.FATOR;
                            that.MINUTOS_DIA    = response.TOTAL_MINUTOS_DIA; 

                            resolve(true);
                            //that.PAI.calcularCusto();      
                        },function(e){
                            reject(e);
                        }
                    );

                });
            },
            ConsultarFaturamento : function(flag) {
                var that = this;

                return $q(function(resolve,reject){

                    var ds = {
                            DATA    : gScope.DATA,
                            MERCADO : gScope.ConsultaPadrao.selected
                        };

                    $ajax.post('/_31010/FaturamentoFamilia',ds,{contentType: 'application/json'})
                        .then(function(response) {

                            gScope.PERC_FATURAMENTO.VALOR = response;
                            gScope.PERC_FATURAMENTO.FLAG  = 1;

                            //that.PAI.calcularCusto();

                            resolve(true);      
                        },
                        function(e){
                            reject(e);
                        }
                    );

                });
            },
            replaceLast : function(texto, substituir, substituto) {
                var pos = texto.lastIndexOf(substituir); 
                if (pos > -1) {
                   return texto.substring(0, pos) 
                    + substituto
                    + texto.substring(pos + substituir.length, texto.length); 
                } else
               return texto;
            },
            MaoDeObra : function(flag) {
                var that = this;

                var ds = {
                        MODELO  : that.PAI.ConsultaModelo.selected,
                        COR     : that.PAI.ConsultaCor.selected,
                        TAMANHO : that.PAI.ConsultaTamanho.selected,
                        DATA    : gScope.DATA,
                        CONFIGURACAO : that.CONFIGURACAO
                    };

                $ajax.post('/_31010/ConsultarMaoDeObra',ds,{contentType: 'application/json'})
                    .then(function(response) {

                        that.MAO_DE_OBRA1  = response.G1;
                        that.MAO_DE_OBRA2  = response.G2;
                        that.MAO_DE_OBRA3  = response.G3;

                        that.TOTAL_MAO_DE_OBRA = {
                            COLABORADORES : 0,
                            CUSTO_MINUTO  : 0,
                            CUSTO_OPE     : 0,
                            CUSTO_SET     : 0,
                            CUSTO_OPE_T   : 0,
                            CUSTO_SET_T   : 0,
                            CUSTO_TOTAL1  : 0,
                            CUSTO_TOTAL2  : 0,
                            SALARIO       : 0,
                            MINUTOS_DIA   : 0
                        };

                        angular.forEach(that.MAO_DE_OBRA3, function(iten, key) {
                            that.TOTAL_MAO_DE_OBRA.COLABORADORES = Number(that.TOTAL_MAO_DE_OBRA.COLABORADORES) + 1;
                            that.TOTAL_MAO_DE_OBRA.SALARIO  = Number(that.TOTAL_MAO_DE_OBRA.SALARIO) + Number(iten.SALARIO); 
                        });

                        that.TOTAL_MAO_DE_OBRA.MINUTOS_DIA = Number(that.MINUTOS_DIA * that.TOTAL_MAO_DE_OBRA.COLABORADORES);

                        angular.forEach(that.MAO_DE_OBRA1, function(iten, key) {

                            iten.SALARIO       = Number(iten.SALARIO);
                            iten.COLABORADOR   = Number(iten.COLABORADOR);

                            iten.CUSTO_MINUTO = Number(iten.SALARIO)      / Number(that.TOTAL_MAO_DE_OBRA.MINUTOS_DIA);
                            iten.CUSTO_OPE    = Number(iten.CUSTO_MINUTO) * Number(that.PAI.TEMPO_MODELO);
                            iten.CUSTO_SET    = Number(iten.CUSTO_MINUTO) * Number(that.PAI.SETUP_MODELO);
                            iten.CUSTO_OPE_T  = Number(iten.CUSTO_OPE)    * Number(that.PAI.Quantidade);
                            iten.CUSTO_SET_T  = Number(iten.CUSTO_SET)    / Number(that.PAI.Quantidade);
                            iten.CUSTO_TOTAL1 = Number(iten.CUSTO_OPE)    + Number(iten.CUSTO_SET_T);
                            iten.CUSTO_TOTAL2 = Number(iten.CUSTO_OPE_T)  + Number(iten.CUSTO_SET_T);

                        });

                        angular.forEach(that.MAO_DE_OBRA2, function(iten, key) {
                            iten.SALARIO       = Number(iten.SALARIO);
                            iten.COLABORADOR   = Number(iten.COLABORADOR);

                            iten.CUSTO_MINUTO = Number(iten.SALARIO)      / Number(that.TOTAL_MAO_DE_OBRA.MINUTOS_DIA);
                            iten.CUSTO_OPE    = Number(iten.CUSTO_MINUTO) * Number(that.PAI.TEMPO_MODELO);
                            iten.CUSTO_SET    = Number(iten.CUSTO_MINUTO) * Number(that.PAI.SETUP_MODELO);
                            iten.CUSTO_OPE_T  = Number(iten.CUSTO_OPE)    * Number(that.PAI.Quantidade);
                            iten.CUSTO_SET_T  = Number(iten.CUSTO_SET)    / Number(that.PAI.Quantidade);
                            iten.CUSTO_TOTAL1 = Number(iten.CUSTO_OPE)    + Number(iten.CUSTO_SET_T);
                            iten.CUSTO_TOTAL2 = Number(iten.CUSTO_OPE_T)  + Number(iten.CUSTO_SET_T);
                        });

                        angular.forEach(that.MAO_DE_OBRA3, function(iten, key) {

                            iten.DATA_ADMISSAO = moment(iten.DATA_ADMISSAO).toDate();
                            iten.DATA_DEMISSAO = moment(iten.DATA_DEMISSAO).toDate();
                            iten.SALARIO       = Number(iten.SALARIO);

                            iten.CUSTO_MINUTO = Number(iten.SALARIO)      / Number(that.TOTAL_MAO_DE_OBRA.MINUTOS_DIA);
                            iten.CUSTO_OPE    = Number(iten.CUSTO_MINUTO) * Number(that.PAI.TEMPO_MODELO);
                            iten.CUSTO_SET    = Number(iten.CUSTO_MINUTO) * Number(that.PAI.SETUP_MODELO);
                            iten.CUSTO_OPE_T  = Number(iten.CUSTO_OPE)    * Number(that.PAI.Quantidade);
                            iten.CUSTO_SET_T  = Number(iten.CUSTO_SET)    / Number(that.PAI.Quantidade);
                            iten.CUSTO_TOTAL1 = Number(iten.CUSTO_OPE)    + Number(iten.CUSTO_SET_T);
                            iten.CUSTO_TOTAL2 = Number(iten.CUSTO_OPE_T)  + Number(iten.CUSTO_SET_T);

                            if(iten.DATA_DEMISSAO == 'Invalid Date'){
                                iten.DATA_DEMISSAO = '';
                            } 
                        });
                        
                        that.TOTAL_MAO_DE_OBRA.CUSTO_MINUTO = Number(that.TOTAL_MAO_DE_OBRA.SALARIO)      / Number(that.TOTAL_MAO_DE_OBRA.MINUTOS_DIA);
                        that.TOTAL_MAO_DE_OBRA.CUSTO_OPE    = Number(that.TOTAL_MAO_DE_OBRA.CUSTO_MINUTO) * Number(that.PAI.TEMPO_MODELO);
                        that.TOTAL_MAO_DE_OBRA.CUSTO_SET    = Number(that.TOTAL_MAO_DE_OBRA.CUSTO_MINUTO) * Number(that.PAI.SETUP_MODELO);
                        that.TOTAL_MAO_DE_OBRA.CUSTO_OPE_T  = Number(that.TOTAL_MAO_DE_OBRA.CUSTO_OPE)    * Number(that.PAI.Quantidade);
                        that.TOTAL_MAO_DE_OBRA.CUSTO_SET_T  = Number(that.TOTAL_MAO_DE_OBRA.CUSTO_SET)   / Number(that.PAI.Quantidade);

                        that.TOTAL_MAO_DE_OBRA.CUSTO_TOTAL1 = Number(that.TOTAL_MAO_DE_OBRA.CUSTO_OPE)    + Number(that.TOTAL_MAO_DE_OBRA.CUSTO_SET_T);
                        that.TOTAL_MAO_DE_OBRA.CUSTO_TOTAL2 = Number(that.TOTAL_MAO_DE_OBRA.CUSTO_OPE_T)  + Number(that.TOTAL_MAO_DE_OBRA.CUSTO_SET_T);

                    }
                );
            },
            Despesa : function(flag) {
                var that = this;

                that.DESPESA1      = [];
                that.DESPESA2      = [];

                var ds = {
                        MODELO  : that.PAI.ConsultaModelo.selected,
                        COR     : that.PAI.ConsultaCor.selected,
                        TAMANHO : that.PAI.ConsultaTamanho.selected,
                        DATA    : gScope.DATA,
                        MERCADO : gScope.ConsultaPadrao.selected
                    };

                $ajax.post('/_31010/ConsultarDespesas',ds,{contentType: 'application/json'})
                    .then(function(response) {

                        that.DESPESA1       = response.G1;
                        that.DESPESA2       = response.G2;
                        that.DESPESA_MINUTO = 0;
                        that.DESPESA_OPE    = 0;
                        that.DESPESA_OPE_T  = 0;
                        that.DESPESA_SET    = 0;
                        that.DESPESA_SET_T  = 0;
                        that.DESPESA_TOTAL1 = 0;
                        that.DESPESA_TOTAL2 = 0;

                        that.DESPESA3 = [];
                        var grupo = '';
                        var obj = {FLAG : '0'};

                        angular.forEach(that.DESPESA1, function(iten, key) {

                            if(grupo != iten.GRUPO){
                                grupo = iten.GRUPO;
                                that.DESPESA3.push({ID: iten.GRUPO, DESCRICAO: iten.DESC_GRUPO, ITENS: [], DESPESA: 0, DESPESAM: 0, DESPESAT: 0, VALOR_DESPESA : 0});
                            }

                            iten.SALARIO      = Number(iten.SALARIO);
                            iten.VALOR        = Number(iten.VALOR) * Number(gScope.PERC_FATURAMENTO.VALOR);
                            iten.PERCENTUAL   = Number(iten.PERCENTUAL);

                            if(iten.TIPO == 1 || iten.TIPO == 2 || iten.PERCENTUAL > 1){
                                iten.PERCENTUAL = 100;
                            }else{
                                iten.PERCENTUAL = iten.PERCENTUAL * 100;    
                            }

                            iten.VALOR_DESPESA = Number(iten.VALOR_DESPESA);
                            iten.CUSTO_MINUTO  = Number(iten.VALOR)        / Number(that.FATOR);
                            iten.CUSTO_OPE     = Number(iten.CUSTO_MINUTO) * Number(that.PAI.TEMPO_MODELO);
                            iten.CUSTO_SET     = Number(iten.CUSTO_MINUTO) * Number(that.PAI.SETUP_MODELO);
                            iten.CUSTO_OPE_T   = Number(iten.CUSTO_OPE)    * Number(that.PAI.Quantidade);
                            iten.CUSTO_SET_T   = Number(iten.CUSTO_SET)    / Number(that.PAI.Quantidade);
                            iten.CUSTO_TOTAL1  = Number(iten.CUSTO_OPE)    + Number(iten.CUSTO_SET_T);
                            iten.CUSTO_TOTAL2  = Number(iten.CUSTO_OPE_T)  + Number(iten.CUSTO_SET_T);

                            that.DESPESA3[that.DESPESA3.length - 1].ITENS.push(iten);

                            that.DESPESA3[that.DESPESA3.length - 1].VALOR_DESPESA   += Number(iten.VALOR_DESPESA);
                            that.DESPESA3[that.DESPESA3.length - 1].DESPESA         += Number(iten.VALOR);
                            that.DESPESA3[that.DESPESA3.length - 1].DESPESAM        += Number(iten.CUSTO_TOTAL1);
                            that.DESPESA3[that.DESPESA3.length - 1].DESPESAT        += Number(iten.CUSTO_TOTAL2);

                        });

                        that.DESPESA1 = that.DESPESA3;

                        angular.forEach(that.DESPESA2, function(iten, key) {

                            iten.SALARIO     = Number(iten.SALARIO);
                            iten.VALOR       = Number(iten.VALOR) * Number(gScope.PERC_FATURAMENTO.VALOR);
                            iten.PERCENTUAL  = Number(iten.PERCENTUAL);

                            iten.PERCENTUAL = iten.PERCENTUAL * 1;    

                            if(iten.PERCENTUAL >= 100){ 
                                iten.PERCENTUAL = 100;
                            }

                            iten.CUSTO_MINUTO = Number(iten.VALOR)        / Number(that.FATOR);
                            iten.CUSTO_OPE    = Number(iten.CUSTO_MINUTO) * Number(that.PAI.TEMPO_MODELO);
                            iten.CUSTO_SET    = Number(iten.CUSTO_MINUTO) * Number(that.PAI.SETUP_MODELO);
                            iten.CUSTO_OPE_T  = Number(iten.CUSTO_OPE)    * Number(that.PAI.Quantidade);
                            iten.CUSTO_SET_T  = Number(iten.CUSTO_SET)    / Number(that.PAI.Quantidade);
                            iten.CUSTO_TOTAL1 = Number(iten.CUSTO_OPE)    + Number(iten.CUSTO_SET_T);
                            iten.CUSTO_TOTAL2 = Number(iten.CUSTO_OPE_T)  + Number(iten.CUSTO_SET_T);

                        });

                        that.DESPESA_MINUTO = (Number(that.PAI.DESPESA)   * Number(gScope.PERC_FATURAMENTO.VALOR))   / Number(that.FATOR);

                        that.DESPESA_OPE    = Number(that.DESPESA_MINUTO) * Number(that.PAI.TEMPO_MODELO);
                        that.DESPESA_OPE_T  = Number(that.DESPESA_OPE)    * Number(that.PAI.Quantidade);

                        that.DESPESA_SET    = Number(that.DESPESA_MINUTO) * Number(that.PAI.SETUP_MODELO);
                        that.DESPESA_SET_T  = Number(that.DESPESA_SET)    / Number(that.PAI.Quantidade);

                        that.DESPESA_TOTAL1 = Number(that.DESPESA_OPE)    + Number(that.DESPESA_SET_T);
                        that.DESPESA_TOTAL2 = Number(that.DESPESA_OPE_T)  + Number(that.DESPESA_SET_T);
                        
                    }
                );
            },
            FLAG_FICHA: 0,
            consultar : function(flag) {
                var that = this;

                that.PAI.CALCULADO = 0;

                return $q(function(resolve,reject){


                    var ds = {
                            MODELO  : that.PAI.ConsultaModelo.selected,
                            COR     : that.PAI.ConsultaCor.selected,
                            TAMANHO : that.PAI.ConsultaTamanho.selected,
                            MERCADO : gScope.ConsultaPadrao.selected,
                            DATA    : gScope.DATA,
                            TROCA   : that.PRODUTO_TROCA,
                            FLAG_TROCA : 1
                        };

                    that.FLAG_FICHA = 0;

                    if(that.FLAG_FICHA == 0){

                        $ajax.post('/_31010/ConsultarFicha',ds,{contentType: 'application/json'})
                            .then(function(response) {
                                that.ITENS  = response;
                                that.FILHOS = [];

                                angular.forEach(that.ITENS, function(item, key) {
                                    item.MONTADO     = false;
                                    item.ABERTO      = false;
                                    item.FILHOS      = [];
                                    item.TOTALIZADOR = 0;
                                });

                                if(flag == 5){
                                    that.PAI.calcularCusto();    
                                }else{
                                    //that.montar();
                                }

                                if(flag == 1){
                                    that.FLAG_FICHA = 1;
                                }

                                resolve(true);        
                            },
                            function(e){
                                reject(e);
                            }
                        );
                    }else{
                        resolve(true);
                    }
                });
            },
            montar : function() {
                var that = this;

                var maxNivel = 0;

                var that = this;

                var tamanho = that.PAI.ConsultaTamanho.selected.ID;

                that.VALOR_ICMS = 0;

                function pad(n, width, z) {
                  z = z || '0';
                  n = n + '';
                  return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
                }

                var maxNivel = 0;

                angular.forEach(that.ITENS, function(item, key) {

                    if(item.NIVEL >= 0){

                        item.ADD = false;

                        if(maxNivel < item.NIVEL){
                            maxNivel = Number(item.NIVEL);
                        }

                        var temp_tamanho1 = pad(item.TAMANHO_PROD, 2);
                        var temp_tamanho2 = pad(tamanho, 2);
                        var temp_tamanho3 = pad(tamanho, 2);

                        angular.forEach(that.ITENS, function(iten, key) {
                            if(item.ORIGEM == iten.PRODUTO_CONSUMO){
                                temp_tamanho3 = pad(iten.TAMANHO_CONSUMO, 2);
                            }
                        });

                        /*
                        var t             = 'T'      + temp_tamanho3;
                        var c             = 'C'      + temp_tamanho3;
                        var to            = 'TO'     + temp_tamanho3;
                        var ts            = 'TS'     + temp_tamanho3;
                        var cms           = 'CMS'    + temp_tamanho3;
                        var cmo           = 'CMO'    + temp_tamanho3;
                        var comoip        = 'COMOIP' + temp_tamanho3;
                        var comoia        = 'COMOIA' + temp_tamanho3;

                        var t_valor       = item[t];
                        var c_valor       = item[c];
                        var to_valor      = item[to];
                        var ts_valor      = item[ts];
                        var cms_valor     = item[cms];
                        var cmo_valor     = item[cmo];
                        var comoip_valor  = item[comoip];
                        var comoia_valor  = item[comoia];
                        */

                        var t      = 'T'     ;
                        var c      = 'C'     ;
                        var to     = 'TO'    ;
                        var ts     = 'TS'    ;
                        var cs     = 'CS'    ;
                        var co     = 'CO'    ;
                        var cms    = 'CMS'   ;
                        var cmo    = 'CMO'   ;
                        var cmoip  = 'CMOIP' ;
                        var cmoia  = 'CMOIA' ;
                        var csmoip = 'CSMOIP';
                        var csmoia = 'CSMOIA';
                        var perda  = 'PERDA' ;
                        var comoip = 'COMOIP';
                        var comoia = 'COMOIA';

                        var t_valor       = item[t];
                        var c_valor       = item[c];
                        var to_valor      = item[to];
                        var ts_valor      = item[ts];
                        var cs_valor      = item[cs];
                        var co_valor      = item[co];
                        var cms_valor     = item[cms];
                        var cmo_valor     = item[cmo];
                        var cmoip_valor   = item[cmoip];
                        var cmoia_valor   = item[cmoia];
                        var csmoip_valor  = item[csmoip];
                        var csmoia_valor  = item[csmoia];
                        var perda_valor   = item[perda];
                        var comoip_valor  = item[comoip];
                        var comoia_valor  = item[comoia];
                        var despesa       = item['DESPESA'];

                        var Incentivo = 0;
                        var vlr_icms  = 0;
                        if(item.ICMS > 0){
                            Incentivo = gScope.Fatores.Incentivo;

                            //if(item.NIVEL == 0){
                                angular.forEach(that.ITENS, function(filho, key){
                                    if(filho.ORIGEM == item.PRODUTO_CONSUMO && filho.PRODUTO_CONSUMO == item.PRODUTO_CONSUMO){
                                        if(filho.NIVEL > item.NIVEL){
                                            item.ICMS = 0;
                                        }
                                    }
                                });
                            //}

                            vlr_icms = Number(item.CUSTO_MEDIO) * ((Number(item.ICMS) * (Number(Incentivo) / 100)) / 100);

                        }

                        ///*
                        item.T_VALOR      = t_valor;
                        item.C_VALOR      = Number(c_valor) + Number(vlr_icms);
                        item.TO_VALOR     = to_valor;
                        item.TS_VALOR     = ts_valor;
                        item.CS_VALOR     = cs_valor;
                        item.CO_VALOR     = co_valor;
                        item.CMS_VALOR    = cms_valor;
                        item.CMO_VALOR    = cmo_valor;
                        item.CMOIP_VALOR  = cmoip_valor;
                        item.CMOIA_VALOR  = cmoia_valor;
                        item.CSMOIP_VALOR = csmoip_valor;
                        item.CSMOIA_VALOR = csmoia_valor;
                        item.COMOIP_VALOR = comoip_valor;
                        item.COMOIA_VALOR = comoia_valor;
                        //*/

                        /*
                        item.T_VALOR      = t_valor;
                        item.C_VALOR      = 
                        item.TO_VALOR     = to_valor;
                        item.TS_VALOR     = ts_valor;
                        item.CMS_VALOR    = cms_valor;
                        item.CMO_VALOR    = cmo_valor;
                        item.COMOIP_VALOR = comoip_valor;
                        item.COMOIA_VALOR = comoia_valor;
                        //*/

                        item.VLR_ICMS  = vlr_icms;
                        item.VLR_ICMS2 = vlr_icms;
                        item.CONSUMO   = (item.T_VALOR / item.FATOR_CONVERSAO);
                        item.CUSTO     = item.C_VALOR;
                        item.TOTAL     = item.CUSTO * that.PAI.Quantidade;              
                    }
                });

                var novo = [];

                for (var i = maxNivel; i >= 0; i--){

                    angular.forEach(that.ITENS, function(item, k) {

                        if(item.NIVEL == i){

                            item.SUB_ITENS = [];
                            item.ABERTO = false;

                            item.TOTAL_CUSTO = 0; 
                            item.TOTAL_ICMS  = 0;
                            item.TOTAL_GERAL = 0;

                            item.CUSTO2     = 0;
                            item.TOTAL2     = 0;

                            //adicionar os filhos ao pai
                            angular.forEach(that.ITENS, function(iten, y) {
                                if((item.PRODUTO_CONSUMO == iten.ORIGEM) && (Number(iten.NIVEL) > Number(item.NIVEL))){
                                    item.SUB_ITENS.push(iten);
                                }               
                            });

                            //calcula o valor do pai usando os filhos
                            angular.forEach(item.SUB_ITENS, function(iten, y) {

                                iten.CUSTO2      = Number(iten.CUSTO_MEDIO) * Number(iten.CONSUMO); 
                                iten.TOTAL2      = Number(iten.CUSTO2)      * Number(that.PAI.Quantidade);

                                item.CUSTO2      = Number(item.CUSTO2)      + Number(iten.CUSTO2);
                                item.TOTAL2      = Number(item.CUSTO2)      * Number(that.PAI.Quantidade);

                                if(Number(iten.VLR_ICMS2) > 0){
                                    item.VLR_ICMS2   = Number(item.VLR_ICMS2)   + (Number(iten.VLR_ICMS2) * Number(iten.CONSUMO));
                                }

                            });


                            item.TOTAL_CUSTO = Number(item.CUSTO2);
                            item.TOTAL_ICMS  = Number(item.VLR_ICMS2);
                            item.TOTAL_GERAL = Number(item.TOTAL_CUSTO) * Number(that.PAI.Quantidade);

                        }

                        if(item.NIVEL == 0 && i == 0){

                            that.VALOR_ICMS = Number(that.VALOR_ICMS) + item.VLR_ICMS; 

                            if(item.ADD == false){
                                novo.push(item);
                                item.ADD = true;
                            }
                            
                        }

                    }); 

                }

                angular.forEach(novo, function(item, y) {

                    if(item.NIVEL == 0){

                        if(item.SUB_ITENS == undefined){
                            item.SUB_ITENS = [];
                        }

                        if(item.SUB_ITENS.length == 0){
                            item.TOTAL_CUSTO = item.CUSTO_MEDIO;
                        } 

                    }

                }); 

                that.LISTA_MATERIA = novo;
                that.LISTA_MATERIA.TOTAL_CUSTO = 0; 
                that.LISTA_MATERIA.TOTAL_GERAL = 0; 
                that.LISTA_MATERIA.TOTAL_ICMS  = 0;

                var tmp = {
                    T_VALOR      : 0,
                    C_VALOR      : 0,
                    TO_VALOR     : 0,
                    TS_VALOR     : 0,
                    CS_VALOR     : 0,
                    CO_VALOR     : 0,
                    CMS_VALOR    : 0,
                    CMO_VALOR    : 0,
                    CMOIP_VALOR  : 0,
                    CMOIA_VALOR  : 0,
                    CSMOIP_VALOR : 0,
                    CSMOIA_VALOR : 0,
                    COMOIP_VALOR : 0,
                    COMOIA_VALOR : 0
                };

                angular.forEach(that.LISTA_MATERIA, function(item, y) {

                    item.VLR_ICMS2  = Number(item.VLR_ICMS2);//   * Number(item.CONSUMO);
                    item.CUSTO2     = Number(item.TOTAL_CUSTO) * Number(item.CONSUMO); 
                    item.TOTAL2     = Number(item.CUSTO2)      * Number(that.PAI.Quantidade);

                    that.LISTA_MATERIA.TOTAL_ICMS  = Number(that.LISTA_MATERIA.TOTAL_ICMS)  + (Number(item.VLR_ICMS2) * Number(item.CONSUMO)) ;
                    that.LISTA_MATERIA.TOTAL_CUSTO = Number(that.LISTA_MATERIA.TOTAL_CUSTO) + Number(item.CUSTO2);
                    that.LISTA_MATERIA.TOTAL_GERAL = Number(that.LISTA_MATERIA.TOTAL_GERAL) + Number(item.TOTAL2);

                    tmp.T_VALOR      = tmp.T_VALOR      + Number(item.T_VALOR);
                    tmp.C_VALOR      = tmp.C_VALOR      + Number(item.C_VALOR);
                    tmp.TO_VALOR     = tmp.TO_VALOR     + Number(item.TO_VALOR);
                    tmp.TS_VALOR     = tmp.TS_VALOR     + Number(item.TS_VALOR);
                    tmp.CS_VALOR     = tmp.CS_VALOR     + Number(item.CS_VALOR);
                    tmp.CO_VALOR     = tmp.CO_VALOR     + Number(item.CO_VALOR);
                    tmp.CMS_VALOR    = tmp.CMS_VALOR    + Number(item.CMS_VALOR);
                    tmp.CMO_VALOR    = tmp.CMO_VALOR    + Number(item.CMO_VALOR);
                    tmp.CMOIP_VALOR  = tmp.CMOIP_VALOR  + Number(item.CMOIP_VALOR);
                    tmp.CMOIA_VALOR  = tmp.CMOIA_VALOR  + Number(item.CMOIA_VALOR);
                    tmp.CSMOIP_VALOR = tmp.CSMOIP_VALOR + Number(item.CSMOIP_VALOR);
                    tmp.CSMOIA_VALOR = tmp.CSMOIA_VALOR + Number(item.CSMOIA_VALOR);
                    tmp.COMOIP_VALOR = tmp.COMOIP_VALOR + Number(item.COMOIP_VALOR);
                    tmp.COMOIA_VALOR = tmp.COMOIP_VALOR + Number(item.COMOIP_VALOR);

                });

                //console.log(tmp); 

                //console.log(that.LISTA_MATERIA);
                
            },
            Selectionar : function (Ficha) {
                var that = this;

                if(that.SELECTED != Ficha){
                    that.SELECTED = Ficha; 
                }
                
            }
        },
        Gasto : {
            Valor: 0,
            Custo: {
                Valor: 0,
                MateriaPrima : {Valor: 0},
                Direto: {
                    Valor: 0,
                    MaoObraDireta: {
                        Valor: 0,
                        CustoSetup: {Valor: 0, Valor2: 0},
                        CustoOperacional: {Valor: 0, Valor2: 0}
                    },
                    MateriaPrima: {Valor: 0}
                },
                Indireto: {
                    Valor: 0,
                    Proprio: {Valor: 0, Setup: 0, Proprio: 0},
                    Absorvido: {Valor: 0, Setup: 0, Absorvido: 0},

                }
            },
            Despesa:{
                Tributos: 0,
                Contas: 0,
                Valor: 0,
                Comissao:{Valor: 0},
                Itens:{}
            }
        },
        consultarTempo : function(flag) {
            var that = this;

            that.CALCULADO = 0;

            var T1 = 0;
            var T2 = 0;
            var T3 = 0;

            that.Ficha.ConsultarFaturamento().then(function(){
                T1 = 1;
                if(T1 == 1 && T2 == 1 && T3 == 1){
                    that.calcularCusto();
                }    
            });

            that.Ficha.ConsultarConfiguracao().then(function(){
               that.Ficha.ConsultarEstacoes().then(function(){
                    T2 = 1;

                    if(T1 == 1 && T2 == 1 && T3 == 1){
                        that.calcularCusto();
                    }    
                });    
            });

            that.Ficha.consultar().then(function(){
                T3 = 1;
                if(T1 == 1 && T2 == 1 && T3 == 1){
                    that.calcularCusto();
                }
            });
        },
        calcularCusto : function(flag) {

            var that = this;
            var tamanho = that.ConsultaTamanho.selected.ID;

            function pad(n, width, z) {
              z = z || '0';
              n = n + '';
              return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
            }

            //that.Ficha.calcular();

            that.Ficha.montar();

            angular.forEach(that.Ficha.ITENS, function(item, key) {

                if(item.NIVEL == -1){
                    tamanho = pad(tamanho, 2);

                    that.UnidadeMedida = item.UNIDADEMEDIDA_SIGLA;
                    that.PERCENTUAL_PERDA = item.PERDA;

                    var t     = 'T'     ;
                    var c     = 'C'     ;
                    var to    = 'TO'    ;
                    var ts    = 'TS'    ;
                    var cs    = 'CS'    ;
                    var co    = 'CO'    ;
                    var cms   = 'CMS'   ;
                    var cmo   = 'CMO'   ;
                    var cmoip = 'CMOIP' ;
                    var cmoia = 'CMOIA' ;
                    var csmoip= 'CSMOIP';
                    var csmoia= 'CSMOIA';
                    var perda = 'PERDA' ;


                    var t_valor       = item[t];
                    var c_valor       = item[c];
                    var to_valor      = item[to];
                    var ts_valor      = item[ts];
                    var cs_valor      = item[cs];
                    var co_valor      = item[co];
                    var cms_valor     = item[cms];
                    var cmo_valor     = item[cmo];
                    var cmoip_valor   = item[cmoip];
                    var cmoia_valor   = item[cmoia];
                    var csmoip_valor  = item[csmoip];
                    var csmoia_valor  = item[csmoia];
                    var perda_valor   = item[perda];
                    var despesa       = item['DESPESA'];

                    item.T_VALOR      = t_valor;
                    item.C_VALOR      = c_valor;
                    item.TO_VALOR     = to_valor;
                    item.TS_VALOR     = ts_valor;
                    item.CS_VALOR     = cs_valor;
                    item.CO_VALOR     = co_valor;
                    item.CMS_VALOR    = cms_valor;
                    item.CMO_VALOR    = cmo_valor;
                    item.CMOIP_VALOR  = cmoip_valor;
                    item.CMOIA_VALOR  = cmoia_valor;
                    item.CSMOIP_VALOR = csmoip_valor;
                    item.CSMOIA_VALOR = csmoia_valor;

                    //console.log(item);

                    var tbm_despesa   = (Number(despesa) * Number(gScope.PERC_FATURAMENTO.VALOR))

                    //item.DESPESA      = tbm_despesa;
                    that.DESPESA      = despesa;

                    that.TEMPO_MODELO = to_valor;
                    that.SETUP_MODELO = ts_valor;

                    that.PERCENTUAL_PERDA = Number(perda_valor);

                    //valores de materia prima, setup, mao de obra, absorvido e proprio
                    that.Gasto.Custo.Direto.MaoObraDireta.CustoSetup.Valor        = Number(cms_valor) / Number(that.Quantidade);
                    that.Gasto.Custo.Direto.MaoObraDireta.CustoOperacional.Valor  = Number(cmo_valor);
                    that.Gasto.Custo.Direto.MaoObraDireta.CustoSetup.Valor2       = Number(cs_valor) / Number(that.Quantidade);
                    that.Gasto.Custo.Direto.MaoObraDireta.CustoOperacional.Valor2 = Number(co_valor);
                    that.Gasto.Custo.Indireto.Proprio.Proprio                     = Number(cmoip_valor);
                    that.Gasto.Custo.Indireto.Absorvido.Absorvido                 = Number(cmoia_valor);
                    that.Gasto.Custo.Indireto.Proprio.Setup                       = Number(csmoip_valor) / Number(that.Quantidade);
                    that.Gasto.Custo.Indireto.Absorvido.Setup                     = Number(csmoia_valor) / Number(that.Quantidade);
                    that.Gasto.Custo.Indireto.Proprio.Valor                       = Number(cmoip_valor) + (Number(csmoip_valor) / Number(that.Quantidade));
                    that.Gasto.Custo.Indireto.Absorvido.Valor                     = Number(cmoia_valor) + (Number(csmoia_valor) / Number(that.Quantidade));

                    that.Gasto.Custo.MateriaPrima.Valor                           = Number(that.Ficha.LISTA_MATERIA.TOTAL_CUSTO) + Number(that.Ficha.LISTA_MATERIA.TOTAL_ICMS);//Number(c_valor) + Number(that.Ficha.VALOR_ICMS);

                    var CustoSetup          = Number(that.Gasto.Custo.Direto.MaoObraDireta.CustoSetup.Valor2)      + Number(that.Gasto.Custo.Direto.MaoObraDireta.CustoSetup.Valor);
                    var CustoOperacional    = Number(that.Gasto.Custo.Direto.MaoObraDireta.CustoOperacional.Valor) + Number(that.Gasto.Custo.Direto.MaoObraDireta.CustoOperacional.Valor2);
                    var IndiretoAbsorvido   = Number(that.Gasto.Custo.Indireto.Absorvido.Valor);
                    var IndiretoProprio     = Number(that.Gasto.Custo.Indireto.Proprio.Valor);
                    var IndiretoAbsorvidoSp = Number(that.Gasto.Custo.Indireto.Absorvido.Setup);
                    var IndiretoProprioSp   = Number(that.Gasto.Custo.Indireto.Proprio.Setup);
                    var MateriaPrima        = Number(that.Gasto.Custo.MateriaPrima.Valor);

                    //soma os valores para ir subindo no grafico, ex.: custo Direto = Mao de Obra Direta + Materia Prima
                    that.Gasto.Custo.Direto.MaoObraDireta.Valor = CustoSetup + CustoOperacional;
                    that.Gasto.Custo.Direto.Valor = that.Gasto.Custo.Direto.MaoObraDireta.Valor + MateriaPrima;
                    that.Gasto.Custo.Indireto.Valor =  IndiretoAbsorvido + IndiretoProprio;
                    that.Gasto.Custo.Valor = that.Gasto.Custo.Direto.Valor + that.Gasto.Custo.Indireto.Valor;

                    //calcula valor de perdas caso seja necessario
                    var perdas = 0;
                    if(gScope.Fatores.ConsiderarPerdas == true){
                        perdas = Number(that.Gasto.Custo.Valor) * Number(that.PERCENTUAL_PERDA);
                    }

                    //custo mais perdas
                    that.Gasto.Custo.Valor = that.Gasto.Custo.Valor + perdas;
                    that.Cst_u_Produto     = MateriaPrima + CustoSetup + IndiretoAbsorvido + IndiretoProprio + CustoOperacional + perdas;
                    that.Cst_t_Produto     = that.Cst_u_Produto * Number(that.Quantidade);

                    var vlr3 = 0;
                    var vlr2 = 0;
                    var vlr1 = 0;
                    var vlr0 = 0;

                    //pega despesa + fatores do mercado, calcula setup e custo minuto e adiciona ao produto
                    item.CM_DESPESA   = (Number(tbm_despesa)     / that.Ficha.FATOR);
                    item.C_DESPESA    = (Number(item.CM_DESPESA) * that.TEMPO_MODELO);
                    item.S_DESPESA    = (Number(item.CM_DESPESA) * that.SETUP_MODELO) /  Number(that.Quantidade);

                    that.Gasto.Despesa.Contas = item.C_DESPESA + item.S_DESPESA;

                    //se preco calculado ou manual
                    if(that.MUDAR_PRECO == true){
                        
                    }else{

                        if(that.MUDAR_CONTRIBUICAO == true){

                            vlr0 = 0;
                            vlr3 = 0;
                            angular.forEach(gScope.PadraoItem, function(item, key) {

                                var Incentivo = 0;
                                if(gScope.Fatores.Incentivo > 0 && item.INCENTIVO == 1){
                                    Incentivo = gScope.Fatores.Incentivo;
                                    vlr0 = ((Number(item.VALOR) * (Number(Incentivo) / 100)) / 100);
                                }else{
                                    vlr0 = (Number(item.VALOR) / 100);                           
                                }

                                if(item.MARGEM == 1){
                                    vlr0 = (vlr0 * (Number(gScope.MARGEM) / 100));
                                }

                                vlr3 = vlr3 + vlr0;
                            });

                            //frete calculado
                            if(gScope.Frete.PERCENTUAL > 0){
                                vlr3 = vlr3 + Number(gScope.Frete.PERCENTUAL);
                            }

                            var perc = 0;
                            angular.forEach(gScope.ListaIncentivo, function(item, key) {
                                if(item.PERCENTUAL == gScope.Fatores.Incentivo){
                                   perc = item.PERCENTUAL_IR;
                                }
                            });

                            perc = (perc * (Number(gScope.MARGEM) / 100));

                            //valor da contribuicao + imposto de renda
                            var a = 100 - Number(perc);
                            var b = (Number(that.Contribuicao) / a) * 100;

                            var d = ((vlr3 * 100) + b);
                            var v = (100 - d); //1 - tributos + contribuicao + imp renda
                            var c = (that.Gasto.Custo.Valor + item.C_DESPESA + item.S_DESPESA); //custo + contas
                            var z = (v * (1 - vlr3));
                            var x = (c * 100) / v;

                            that.PrecoVenda = x; 

                            if(d > 100){
                                showErro('O % de contribuição + tributos não pode superar 100%, (contribuição + tributos) = ' + d);
                                that.PrecoVenda = 0; 
                            }                  
                        
                        }else{
                            that.PrecoVenda = Number(that.Cst_u_Produto) + ((Number(gScope.Fatores.MarckUp) / 100) * that.Cst_u_Produto);
                        }

                        //that.PrecoVenda = Number(Number(that.PrecoVenda).toFixed(2)); 
                    }
                    

                    //total do preço
                    that.TotalPrecoVenda = that.PrecoVenda * Number(that.Quantidade);


                    //calcula fatores do custo, os fatores vem do mercado
                    that.Gasto.Despesa.Itens = [];
                    angular.forEach(gScope.PadraoItem, function(item, key) {

                        if(item.FRETE == 0 || (item.FRETE == 1 && gScope.Frete.PERCENTUAL <= 0)){

                            var Incentivo = 0;
                            if(gScope.Fatores.Incentivo > 0 && item.INCENTIVO == 1){
                                Incentivo = gScope.Fatores.Incentivo;
                                vlr0 = Number(that.TotalPrecoVenda) * ((Number(item.VALOR) * (Number(Incentivo) / 100)) / 100);
                            }else{
                                vlr0 = Number(that.TotalPrecoVenda) * (Number(item.VALOR) / 100);                           
                            }

                            if(item.MARGEM == 1){
                                vlr0 = (vlr0 * (Number(gScope.MARGEM) / 100));
                            }

                            vlr1 = Number(vlr0) / Number(that.Quantidade); 
                            
                            that.Gasto.Despesa.Itens.push({
                                DESCRICAO: item.DESCRICAO,
                                VALOR : vlr1,
                                TOTAL : vlr0
                            });

                            vlr3 = vlr3 + vlr1;
                        }
                    });

                    //frete calculado
                    if(gScope.Frete.PERCENTUAL > 0){

                        var Incentivo = 0;
                        
                        vlr0 = Number(that.TotalPrecoVenda) * Number(gScope.Frete.PERCENTUAL);                           

                        vlr1 = Number(vlr0) / Number(that.Quantidade); 
                        
                        that.Gasto.Despesa.Itens.push({
                            DESCRICAO: 'Frete Calculado',
                            VALOR : vlr1,
                            TOTAL : vlr0
                        });

                        vlr3 = vlr3 + vlr1;
                    }        

                    that.Gasto.Despesa.Tributos = vlr3;

                    item.C_DESPESA    = item.C_DESPESA + item.S_DESPESA + vlr3;

                    var despesa1      = ((item.C_DESPESA / that.Cst_u_Produto) * 100);

                    item.PERC_DESPESA = despesa1;

                    that.CM_DESPESA   = item.CM_DESPESA;
                    that.C_DESPESA    = item.C_DESPESA;
                    that.PERC_DESPESA = item.PERC_DESPESA;             

                    //calcula o MarckUp
                    vlr1 = Number(that.PrecoVenda) - Number(that.Cst_u_Produto);
                    vlr3 = (vlr1 / Number(that.Cst_u_Produto)) * 100;
                    that.MarckUp = vlr3;

                    that.Gasto.Despesa.Valor = item.C_DESPESA;
                    that.Gasto.Valor = that.Gasto.Custo.Valor + that.Gasto.Despesa.Valor;

                    if(that.MUDAR_CONTRIBUICAO == false){
                        that.Contribuicao     = ((Number(that.PrecoVenda) - Number(that.Gasto.Valor)) / Number(that.PrecoVenda)) * 100;
                    }

                    that.ContribuicaoReal = 0;
                    that.Despesa = (item.C_DESPESA * Number(that.Quantidade));

                    that.ImpostoDeRenda = 0;

                    if(that.Contribuicao > 0){

                        var perc = 0;
                        angular.forEach(gScope.ListaIncentivo, function(item, key) {
                            if(item.PERCENTUAL == gScope.Fatores.Incentivo){
                               perc = item.PERCENTUAL_IR;
                            }
                        });

                        perc = (perc * (Number(gScope.MARGEM) / 100));

                        var j = 0;
                        if(that.MUDAR_CONTRIBUICAO == true){
                            var a = 100 - Number(perc);
                            var p = (Number(perc) / 100) * (that.Contribuicao / 100); //imp. renda
                            var b = Number(that.Contribuicao) / a;

                            j = b;//(Number(that.Contribuicao) / 100) + ((Number(perc) / 100) * (that.Contribuicao / 100)); //imp. renda
                        }else{
                            j = Number(that.Contribuicao) / 100;
                        }

                        vlr1 = (j) * Number(that.TotalPrecoVenda);

                        var p = (Number(perc) / 100);
                        var v = vlr1 * p;

                        that.ImpostoDeRendaDesc = Number(perc);
                        that.ImpostoDeRenda     = v;

                        //that.Contribuicao     = ((vlr1 - v) / Number(that.Cst_t_Produto)) * 100;

                        if(that.MUDAR_CONTRIBUICAO == false){
                            that.Contribuicao       = ((Number(that.TotalPrecoVenda) - ((Number(that.Gasto.Valor)  * Number(that.Quantidade)) + that.ImpostoDeRenda)) / Number(that.TotalPrecoVenda)) * 100;
                        }

                        that.ContribuicaoReal   = (Number(that.Contribuicao) / 100) * Number(that.TotalPrecoVenda);

                        that.ContribuicaoReal2  = (Number(that.ContribuicaoReal) / Number(that.Quantidade));

                    }
                }

            });
            
            gScope.ItenCusto.CalcularTotal();

            that.CALCULADO = 1;
            
        },
        DetalharDespesa: function(item,flag){
            var that = this;

            that.DESPESA_ITEM = item;
            that.DESPESA_DETALHE = [];

            var ds = {
                    FLAG    : flag,
                    ITEM    : item,
                    DATA    : gScope.DATA,
                    MERCADO : gScope.ConsultaPadrao.selected
                };

            $ajax.post('/_31010/DetalharDespesa',ds,{contentType: 'application/json'})
                .then(function(response) {
                    that.DESPESA_DETALHE = response;  

                    angular.forEach(that.DESPESA_DETALHE, function(item, key) {
                        item.VALOR = Number(item.VALOR);
                        item.PERCENTUAL = Number(item.PERCENTUAL) * 100;

                        if(item.PERCENTUAL > 100){
                            item.PERCENTUAL = 100;
                        }
                    });         
                }
            );

            if(flag == 2){
               $('#modal-detalhar-despesa2').modal();
            }else{
               $('#modal-detalhar-despesa').modal(); 
            }
        },
        Alternar: function(flag){

            if(flag == 1){
                //alterar preço

                this.MUDAR_PRECO = !this.MUDAR_PRECO;

                if(this.MUDAR_PRECO == false){
                    this.calcularCusto();
                }else{
                    if(this.MUDAR_CONTRIBUICAO == true){
                        this.MUDAR_CONTRIBUICAO = false;  
                        this.calcularCusto();
                    }else{
                        this.MUDAR_CONTRIBUICAO = false;
                    }  
                }

            }else{
                //alerar contribuição
                this.MUDAR_CONTRIBUICAO = !this.MUDAR_CONTRIBUICAO;

                if(this.MUDAR_CONTRIBUICAO == false){
                    this.calcularCusto();
                }else{
                    if(this.MUDAR_PRECO == true){
                        this.MUDAR_PRECO = false; 
                        this.calcularCusto();
                    }else{
                        this.MUDAR_PRECO = false; 
                    }
                }
            }           

        },
        keyupQuantidade : function(){

            if(!(this.ConsultaModelo.selected == null) && !(this.ConsultaCor.selected == null) && !(this.ConsultaTamanho.selected == null)){
                this.calcularCusto();
            }
        },
        keyupValor : function(){

            if(!(this.ConsultaModelo.selected == null) && !(this.ConsultaCor.selected == null) && !(this.ConsultaTamanho.selected == null)){
                this.calcularCusto();
            }
        }
    };

    var contador = 0;
    ItenCusto.prototype.getNew = function(){
        var that = this;
        var item = angular.copy(obj_item);

        var id = contador;//gScope.ListaItens.length;

        item.id = id;
        item.Ficha.PAI = item; 

        //this.Lista.push(item);
        //gScope.ListaItens.push(item);

        gScope.ListaItens.push(item);

        item.SelectModelo_1 = 0;
        item.SelectModelo_2 = 0;

        gScope.scope.$watch('vm.ListaItens['+id+'].PrecoVenda', function (newValue, oldValue, scope) {
            if(newValue != oldValue){
                gScope.Frete.CALCULADO = false;
            }
        }, true);

        //var obj = $('.container-consulta-itens');
        //id = $(obj).length;

        item.Ficha.PRODUTO_TROCA = '';

        item.ConsultaModelo = this.Consulta.getNew(true);
        item.ConsultaModelo.componente                  = '.consulta-modelo-'+id;
        item.ConsultaModelo.option.class                = 'modeloctrl1-'+id;
        item.ConsultaModelo.model                       = 'itemCusto.ConsultaModelo';
        item.ConsultaModelo.option.label_descricao      = 'Modelo:';
        item.ConsultaModelo.option.obj_consulta         = '/_31010/Consultar';
        item.ConsultaModelo.option.tamanho_input        = 'input-medio';
        item.ConsultaModelo.option.tamanho_tabela       = 240;
        item.ConsultaModelo.autoload                    = false;
        item.ConsultaModelo.option.paran                 = {MERCADO: gScope.ConsultaPadrao.item.dados}; 

        item.ConsultaCor = this.Consulta.getNew(true);
        item.ConsultaCor.componente                      = '.consulta-cor-'+id;
        item.ConsultaCor.option.class                    = 'modeloctrl2-'+id;
        item.ConsultaCor.model                           = 'itemCusto.ConsultaCor';
        item.ConsultaCor.option.label_descricao          = 'Cor:';
        item.ConsultaCor.option.obj_consulta             = '/_31010/ConsultarCor';
        item.ConsultaCor.option.tamanho_input            = 'input-medio';
        item.ConsultaCor.option.tamanho_tabela           = 240;
        item.ConsultaCor.autoload                        = false;
        item.ConsultaCor.option.paran = {'PADRAO': 0};

        item.ConsultaTamanho = this.Consulta.getNew(true);
        item.ConsultaTamanho.componente                  = '.consulta-tamanho-'+id;
        item.ConsultaTamanho.option.class                = 'modeloctrl3-'+id;
        item.ConsultaTamanho.model                       = 'itemCusto.ConsultaTamanho';
        item.ConsultaTamanho.option.label_descricao      = 'Tamanho:';
        item.ConsultaTamanho.option.obj_consulta         = '/_31010/ConsultarTamanho';
        item.ConsultaTamanho.option.tamanho_input        = 'input-menor';
        item.ConsultaTamanho.option.obj_ret              = ['DESCRICAO'];
        item.ConsultaTamanho.option.tamanho_tabela       = 150;
        item.ConsultaTamanho.autoload                    = false;
        item.ConsultaTamanho.option.paran = {'PADRAO': 0};

        item.ConsultaModelo.disable(!gScope.ConsultaPadrao.item.selected);
        item.ConsultaCor.disable(!gScope.ConsultaPadrao.item.selected);
        item.ConsultaTamanho.disable(!gScope.ConsultaPadrao.item.selected);

        item.ConsultaModelo.onClear = function(){
            item.Ficha.PRODUTO_TROCA = '';
            item.Ficha.LST_TROCA     = [];
        }

        item.ConsultaCor.onClear = function(){
            item.Ficha.PRODUTO_TROCA = '';
            item.Ficha.LST_TROCA     = [];
        }

        item.ConsultaTamanho.onClear = function(){
            item.Ficha.PRODUTO_TROCA = '';
            item.Ficha.LST_TROCA     = [];
        }

        item.ConsultaModelo.onSelect = function(){

            item.Ficha.PRODUTO_TROCA = '';
            item.Ficha.LST_TROCA     = [];

            gScope.Frete.CALCULADO = false;

            item.SelectModelo_1 = 0;
            item.SelectModelo_2 = 0;

            if(item.ConsultaModelo.item.dados.COR_ID >= 0){
                item.ConsultaCor.option.paran = {'PADRAO': 1};
                item.ConsultaCor.filtrar();
            }else{
                showAlert('Modelo sem cor padrão');

            }
            
            if(item.ConsultaModelo.item.dados.TAMANHO >= 0){
                item.ConsultaTamanho.option.paran = {'PADRAO': 1};
                item.ConsultaTamanho.filtrar();
            }else{
                showAlert('Modelo sem tamanho padrão');                
            }
        }

        item.ConsultaCor.onSelect = function(){

            item.Ficha.PRODUTO_TROCA = '';
            item.Ficha.LST_TROCA     = [];

            gScope.Frete.CALCULADO = false;

            item.Ficha.FLAG_FICHA = 0;

            item.SelectModelo_1 = 1;
            item.ConsultaCor.option.paran = {'PADRAO': 0};

            if(item.ConsultaModelo.item.selected == true && item.ConsultaTamanho.item.selected == true){
                item.consultarTempo();
            }
        }

        item.ConsultaTamanho.onSelect = function(){

            item.Ficha.PRODUTO_TROCA = '';
            item.Ficha.LST_TROCA     = [];

            gScope.Frete.CALCULADO = false;

            item.Ficha.FLAG_FICHA = 0;

            item.SelectModelo_2 = 1;
            item.ConsultaTamanho.option.paran = {'PADRAO': 0};

            if(item.ConsultaModelo.item.selected == true && item.ConsultaCor.item.selected == true){
                item.consultarTempo();
            }
        }

        $timeout(function(){

            item.ConsultaModelo.compile();
            item.ConsultaCor.compile();
            item.ConsultaTamanho.compile();

            item.ConsultaCor.require = item.ConsultaModelo;
            item.ConsultaCor.vincular();

            item.ConsultaTamanho.require = item.ConsultaModelo;
            item.ConsultaTamanho.vincular();

        });

        contador = contador + 1;

        return item;
    }

    return ItenCusto;
};