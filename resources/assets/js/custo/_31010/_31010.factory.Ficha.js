angular
    .module('app')
    .factory('Ficha', Ficha);
    

	Ficha.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope',
        '$compile'
    ];

function Ficha($ajax, $httpParamSerializer, $rootScope, $q, $timeout, gcCollection, gScope, $compile) {

    /**
     * Constructor, with class name
     */
    function Ficha(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Ficha = this; 
        
        this.ITENS = [];
        this.DADOS = [];
        this.FILTRO = '';
        this.ORDEM = 'NIVEL';
        this.SELECTED = [];
        this.MO = [];
        this.AGRUPAMENTO = [];
        this.FILHOS = [];
        this.DADOS_ABSORCAO = [];
        this.ItensAbsorvidos = [];
        this.DADOS_PROPRIO = [];
        this.TEMPO_MODELO = 0;

        this.OrdemAbs  = 'ABRANGENCIA';
        this.OrdemPro  = 'ORIGEM_DESCRICAO';
        this.OrdemMdo1 = 'CCUSTO_DESCRICAO';
        this.OrdemMdo2 = 'CARGO';
        this.OrdemMdo3 = 'CCUSTO_DESCRICAO';

        this.OrdemDespesa1 = 'DESCRICAO';
        this.OrdemDespesa2 = 'DESCRICAO';
        this.OrdemDespesa3 = 'DESCRICAO';

        this.TOTALAbs = {
            VALOR : 0,
            CUSTO : 0,
            CUSTOT: 0,
            RATEAMENTO : 0
        };
        this.TOTALProp = {
            VALOR : 0,
            CUSTO : 0,
            CUSTOT: 0
        };
    }

    Ficha.prototype.replaceLast = function(texto, substituir, substituto) {
        // Retorna o índice da última ocorrência de "substituir"
        var pos = texto.lastIndexOf(substituir); 
        // Se encontrar o índice
        if (pos > -1) { 
           // Retorna os caracteres antecedentes de "substituir"
           return texto.substring(0, pos) 
            + substituto
            // Retorna os caracteres posteriores a "substituir"
            + texto.substring(pos + substituir.length, texto.length); 
        } else 
       // Se a palavra especificada em "substituir" não for encontrada não altera nada
       return texto;
    }

    Ficha.prototype.esconderFilhos = function(item){
        var that = this;

        angular.forEach(item.FILHOS, function(iten, key) {
            iten.ABERTO = false;

            if(iten.ABERTO == false){
                that.esconderFilhos(iten);   
            }
        });
    }

    Ficha.prototype.MontarFilhos = function(item, flag){
        var html = '';
        var that = this;

        if(item.MONTADO == true){

            //item.ABERTO = !item.ABERTO;
            angular.forEach(item.FILHOS, function(iten, key) {
                iten.ABERTO = !iten.ABERTO;

                if(iten.ABERTO == false){
                    that.esconderFilhos(iten);   
                }
            });

        }else{
            angular.forEach(that.ITENS, function(iten, key) {

                if(iten.ORIGEM == item.PRODUTO_CONSUMO_ID){

                    that.FILHOS.push(iten);
                    item.FILHOS.push(iten);

                    iten.NIVEL  = parseInt(item.NIVEL) + 1;
                    iten.ABERTO = true;

                    var cont = that.FILHOS.length - 1;

                    //var add  = '';
                    //for (var i = 0; i <= iten.NIVEL; i++) {
                    //        add += '&nbsp;&nbsp;';
                    //}

                    html += '<tr';
                    html += '    tabindex="-1"';     
                    html += '    class="tr-fixed-1 filho_'+item.PRODUTO_CONSUMO_ID+' pai_'+iten.PRODUTO_CONSUMO_ID+' nivel_'+iten.NIVEL+'"';
                    html += '    ng-if="vm.Ficha.FILHOS['+cont+'].ABERTO == true"';

                    if( item.PRODUTO_CONSUMO_ID != iten.PRODUTO_CONSUMO_ID){
                        html += '    ng-click="vm.Ficha.MontarFilhos(vm.Ficha.FILHOS['+cont+'],1)"';
                    }

                    html += '    >';

                    html += '    <td auto-title >{{vm.Ficha.FILHOS['+cont+'].NIVEL}}</td>';
                    html += '    <td auto-title >{{vm.Ficha.FILHOS['+cont+'].DESCRICAO}}</td>';
                    html += '    <td auto-title >{{vm.Ficha.FILHOS['+cont+'].T_VALOR}}</td>';
                    html += '    <td auto-title >{{vm.Ficha.FILHOS['+cont+'].C_VALOR}}</td>';
                    html += '    <td auto-title >{{vm.Ficha.FILHOS['+cont+'].TO_VALOR}}</td>';
                    html += '    <td auto-title >{{vm.Ficha.FILHOS['+cont+'].TS_VALOR}}</td>';
                    html += '    <td auto-title >{{vm.Ficha.FILHOS['+cont+'].CMS_VALOR}}</td>';
                    html += '    <td auto-title >{{vm.Ficha.FILHOS['+cont+'].CMO_VALOR}}</td>';
                    html += '    <td auto-title >{{vm.Ficha.FILHOS['+cont+'].FATOR_CONVERSAO}}</td>';

                    html += '</tr>';
                    html += '<tr style="display: none;" class="_pai_'+iten.NIVEL+'_'+iten.PRODUTO_CONSUMO_ID+'"></tr>';

                }
            });

            //html = that.replaceLast(html,'╠➧','╚➧');

            item.MONTADO = true;

            that._controller   = function(){return $('#main').find('[ng-controller]')};
            var scope = that._controller().scope();

            $('._pai_'+parseInt(item.NIVEL)+'_' + item.PRODUTO_CONSUMO_ID ).replaceWith( $compile(html)(scope) );
        }

    };

    Ficha.prototype.voltarItem = function() {
        var that  = this;
        var index = that.ItensAbsorvidos.length - 1;

        if (index > -1) {
            that.ItensAbsorvidos.splice(index, 1);
        }

        if(index > 0){
            if(index == 1){
                that.ConsultarAbsorcao(2);
            }else{
                that.DetalharAbsorcao(that.ItensAbsorvidos[index - 1],2);
            }
        }else{
            $('#modal-absorcao').modal('hide');
        }      
    }

    Ficha.prototype.consultar = function(flag) {
        var that = this;

        var ds = {
                MODELO  : gScope.Modelo.ConsultaModelo.selected,
                COR     : gScope.Cor.SELECTED,
                TAMANHO : gScope.Tamanho.SELECTED,
                DATA    : gScope.DATA
            };

            

        $ajax.post('/_31010/ConsultarFicha',ds,{contentType: 'application/json'})
            .then(function(response) {
                that.ITENS = response;
                that.FILHOS = [];

                angular.forEach(that.ITENS, function(item, key) {
                    item.MONTADO = false;
                    item.ABERTO  = false;
                    item.FILHOS  = [];
                });

                that.calcular();            
            }
        );
    }

    Ficha.prototype.ConsultarProprio = function(flag) {
        var that = this;

        var ds = {
                DATA  : gScope.DATA,
                CONFIGURACAO : gScope.Item.Ficha.CONFIGURACAO
            };

        $ajax.post('/_31010/ConsultarProprio',ds,{contentType: 'application/json'})
            .then(function(response) {
                that.DADOS_PROPRIO = response;

                angular.forEach(that.DADOS_PROPRIO, function(item, key) {

                    item.RATEAMENTO      = Number(item.RATEAMENTO);
                    item.VALOR           = Number(item.VALOR);

                    var t_s = (Number(gScope.Item.SETUP_MODELO) / gScope.Item.Quantidade);
                    var Custo = 0;
                    var custo_minuto         = item.VALOR   / Number(gScope.Item.Ficha.FATOR);
                    var custo_proprio_modelo = custo_minuto * Number(gScope.Item.TEMPO_MODELO);
                    var custo_proprio_setup  = custo_minuto * t_s;
                    
                    item.CUSTO_PROPRIO  = (custo_proprio_modelo  + custo_proprio_setup);
                    item.CUSTO_PROPRIOT = ((custo_proprio_modelo + custo_proprio_setup) * gScope.Item.Quantidade) ;    

                });

                that.calTotalProp(1);          
            }
        );
    }

    Ficha.prototype.calTotalAbs = function(flag) {
        var that = this;

        that.TOTALAbs = {
            VALOR : 0,
            CUSTO : 0,
            CUSTOT: 0,
            RATEAMENTO : 1
        };

        angular.forEach(that.DADOS_ABSORCAO,function(item){
            that.TOTALAbs.VALOR = Number(that.TOTALAbs.VALOR) + Number(item.VALOR);
            that.TOTALAbs.CUSTO  = item.CUSTO_ABSORVIDO  + that.TOTALAbs.CUSTO;
            that.TOTALAbs.CUSTOT = item.CUSTO_ABSORVIDOT + that.TOTALAbs.CUSTOT; 

            if(item.ORIGEM_DESCRICAO == 'Salário'){
                that.TOTALAbs.RATEAMENTO = item.RATEAMENTO; 
            }
        });
    }

    Ficha.prototype.calTotalProp = function(flag) {
        var that = this;

        that.TOTALProp = {
            VALOR : 0,
            CUSTO : 0,
            CUSTOT: 0
        };

        var v = 0;
        angular.forEach(that.DADOS_PROPRIO,function(item){
            v = Number(v) + Number(item.VALOR);   
        });

        var t_s = (Number(gScope.Item.SETUP_MODELO) / gScope.Item.Quantidade);

        var custo_minuto         = Number(v)    / Number(gScope.Item.Ficha.FATOR);
        var custo_proprio_modelo = custo_minuto * Number(gScope.Item.TEMPO_MODELO);
        var custo_proprio_setup  = custo_minuto * t_s;

        that.TOTALProp.VALOR  = v;
        that.TOTALProp.CUSTO  = (custo_proprio_modelo  + custo_proprio_setup);
        that.TOTALProp.CUSTOT = ((custo_proprio_modelo + custo_proprio_setup) * gScope.Item.Quantidade) ;
    }

    Ficha.prototype.ConsultarAbsorcao = function(flag) {
        var that = this;

        if(flag == 1){
            that.ItensAbsorvidos.push({FLAG: flag, ORIGEM_DESCRICAO : 'Geral', BASE: 1 });
        }

        var ds = {
                DATA  : gScope.DATA,
                CONFIGURACAO : gScope.Item.Ficha.CONFIGURACAO
            };

        $ajax.post('/_31010/ConsultarAbsorcao',ds,{contentType: 'application/json'})
            .then(function(response) {
                that.DADOS_ABSORCAO = response;  
                that.DADOS_ABSORCAO.FLAG = 1; 

                angular.forEach(that.DADOS_ABSORCAO, function(item, key) {

                    item.RATEAMENTO      = Number(item.RATEAMENTO);
                    item.VALOR           = Number(item.VALOR);
                    item.ABRANGENCIA     = Number(item.ABRANGENCIA);

                    var Custo = 0;
                    var custo_minuto     =  item.VALOR  / gScope.Item.Ficha.FATOR;
                    var custo_abs_modelo = custo_minuto * gScope.Item.TEMPO_MODELO;
                    var custo_abs_setup  = custo_minuto * gScope.Item.SETUP_MODELO;
                    
                    item.CUSTO_ABSORVIDO = custo_abs_modelo  + (custo_abs_setup / gScope.Item.Quantidade);
                    item.CUSTO_ABSORVIDOT= (custo_abs_modelo * gScope.Item.Quantidade)  + custo_abs_setup;    

                });

                that.calTotalAbs(1);       
            }
        );
    }

    Ficha.prototype.OrdemD1 = function(filtro){
        if(this.OrdemDespesa1 == filtro){
            this.OrdemDespesa1 = '-'+filtro;
        }else{
            this.OrdemDespesa1 = filtro;
        }
    };

    Ficha.prototype.OrdemD2 = function(filtro){
        if(this.OrdemDespesa2 == filtro){
            this.OrdemDespesa2 = '-'+filtro;
        }else{
            this.OrdemDespesa2 = filtro;
        }
    };

    Ficha.prototype.OrdemD3 = function(filtro){
        if(this.OrdemDespesa3 == filtro){
            this.OrdemDespesa3 = '-'+filtro;
        }else{
            this.OrdemDespesa3 = filtro;
        }
    };

    Ficha.prototype.OrdemAbsorvido = function(filtro){
        if(this.OrdemAbs == filtro){
            this.OrdemAbs = '-'+filtro;
        }else{
            this.OrdemAbs = filtro;
        }
    };

    Ficha.prototype.OrdemProprio = function(filtro){
        if(this.OrdemPro == filtro){
            this.OrdemPro = '-'+filtro;
        }else{
            this.OrdemPro = filtro;
        }
    };

    Ficha.prototype.OrdemMaoDeObra1 = function(filtro){
        if(this.OrdemMdo1 == filtro){
            this.OrdemMdo1 = '-'+filtro;
        }else{
            this.OrdemMdo1 = filtro;
        }
    };

    Ficha.prototype.OrdemMaoDeObra2 = function(filtro){
        if(this.OrdemMdo2 == filtro){
            this.OrdemMdo2 = '-'+filtro;
        }else{
            this.OrdemMdo2 = filtro;
        }
    };

    Ficha.prototype.OrdemMaoDeObra3 = function(filtro){
        if(this.OrdemMdo3 == filtro){
            this.OrdemMdo3 = '-'+filtro;
        }else{
            this.OrdemMdo3 = filtro;
        }
    };

    Ficha.prototype.ConsultarAbsorcao2 = function(flag) {
        var that = this;

        that.ItensAbsorvidos = [];
        that.ItensAbsorvidos.push({FLAG: flag, ORIGEM_DESCRICAO : 'Geral', BASE: 1 });

        var ds = {
                DATA  : gScope.DATA,
                CONFIGURACAO : gScope.Item.Ficha.CONFIGURACAO
            };

        $ajax.post('/_31010/ConsultarAbsorcao',ds,{contentType: 'application/json'})
            .then(function(response) {
                that.DADOS_ABSORCAO = response;
                that.DADOS_ABSORCAO.FLAG = 1; 

                angular.forEach(that.DADOS_ABSORCAO, function(item, key) {

                    item.RATEAMENTO      = Number(item.RATEAMENTO);
                    item.VALOR           = Number(item.VALOR);
                    item.ABRANGENCIA     = Number(item.ABRANGENCIA);

                    var Custo = 0;
                    var custo_minuto     =  item.VALOR  / gScope.Item.Ficha.FATOR;
                    var custo_abs_modelo = custo_minuto * gScope.Item.TEMPO_MODELO;
                    var custo_abs_setup  = custo_minuto * gScope.Item.SETUP_MODELO;
                    
                    item.CUSTO_ABSORVIDO = custo_abs_modelo  + (custo_abs_setup / gScope.Item.Quantidade);
                    item.CUSTO_ABSORVIDOT= (custo_abs_modelo * gScope.Item.Quantidade)  + custo_abs_setup;    

                });

                that.calTotalAbs(1);         
            }
        );
    };

    Ficha.prototype.DetalharAbsorcao = function(item,flag) {
        var that = this;

        item.BASE = 0;

        if(flag == 1){
            that.ItensAbsorvidos.push(item);
        }

        var ds = {
                DATA : gScope.DATA,
                ITEM : item,
                CONFIGURACAO : gScope.Item.Ficha.CONFIGURACAO
            };

            $ajax.post('/_31010/ConsultarAbsorcao',ds,{contentType: 'application/json'})
                .then(function(response) {
                    that.DADOS_ABSORCAO = response;    
                    that.DADOS_ABSORCAO.FLAG = 2;

                    angular.forEach(that.DADOS_ABSORCAO, function(item, key) {

                        item.RATEAMENTO      = Number(item.RATEAMENTO);
                        item.VALOR           = Number(item.VALOR);
                        item.ABRANGENCIA     = Number(item.ABRANGENCIA);

                        var Custo = 0;
                        var custo_minuto     =  item.VALOR  / gScope.Item.Ficha.FATOR;
                        var custo_abs_modelo = custo_minuto * gScope.Item.TEMPO_MODELO;
                        var custo_abs_setup  = custo_minuto * gScope.Item.SETUP_MODELO;
                        
                        item.CUSTO_ABSORVIDO = custo_abs_modelo  + (custo_abs_setup / gScope.Item.Quantidade);
                        item.CUSTO_ABSORVIDOT= (custo_abs_modelo * gScope.Item.Quantidade)  + custo_abs_setup;    

                    });

                that.calTotalAbs(2);          
            }
        );
    }

    Ficha.prototype.DetalharAbsorcao2 = function(iten,flag) {
        var that = this;
        var deletar = false;
        var list = [];

        angular.forEach(that.ItensAbsorvidos, function(item, key) {
            if(item == iten){
                deletar = true;
            }else{
                if(deletar == true){
                    list.push(key);
                    //that.ItensAbsorvidos.splice(key,1);
                }
            }
        });

        list.reverse();

        angular.forEach(list, function(index, key) {
            that.ItensAbsorvidos.splice(index,1);
        });

        var ds = {
                DATA : gScope.DATA,
                ITEM : iten,
                CONFIGURACAO : gScope.Item.Ficha.CONFIGURACAO
            };

        $ajax.post('/_31010/ConsultarAbsorcao',ds,{contentType: 'application/json'})
            .then(function(response) {
                that.DADOS_ABSORCAO = response;
                that.DADOS_ABSORCAO.FLAG = flag; 
                that.calTotalAbs(flag);             
            }
        );
    }

    Ficha.prototype.consultarTempo = function(flag) {
        var that = this;

        var ds = {
                MODELO  : gScope.Modelo.ConsultaModelo.selected,
                COR     : gScope.Cor.SELECTED,
                TAMANHO : gScope.Tamanho.SELECTED,
                DATA    : gScope.DATA
            };

        $ajax.post('/_31010/ConsultarFichaTempo',ds,{contentType: 'application/json'})
            .then(function(response) {
                that.DADOS = response;

                that.calcularCusto();            
            }
        );
    }

    Ficha.prototype.consultarTempoModelo = function(flag) {
        var that = this;

        var ds = {
                MODELO  : gScope.Item.Modelo.ConsultaModelo.selected,
                COR     : gScope.Item.Cor.SELECTED,
                TAMANHO : gScope.Item.Tamanho.SELECTED,
                DATA    : gScope.DATA
            };

        $ajax.post('/_31010/ConsultarTempo',ds,{contentType: 'application/json'})
            .then(function(response) {
                that.TEMPO_MODELO = response;           
            }
        );
    }

    Ficha.prototype.calcular = function(flag) {
        var that = this;
        var tamanho = gScope.Tamanho.SELECTED;

        function pad(n, width, z) {
          z = z || '0';
          n = n + '';
          return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
        }

        angular.forEach(that.ITENS, function(item, key) {

            var tamanho = pad(item.TAMANHO_PROD, 2);

            var t     = 'T'     + tamanho;
            var c     = 'C'     + tamanho;
            var to    = 'TO'    + tamanho;
            var ts    = 'TS'    + tamanho;
            var cs    = 'CS'    + tamanho;
            var co    = 'CO'    + tamanho;
            var cmoip = 'CMOIP' + tamanho;
            var cmoia = 'CMOIA' + tamanho;

            var t_valor     = item[t];
            var c_valor     = item[c];
            var to_valor    = item[to];
            var ts_valor    = item[ts];
            var cs_valor    = item[cs];
            var co_valor    = item[co];
            var cmoip_valor = item[cmoip];
            var cmoia_valor = item[cmoia];

            item.T_VALOR     = t_valor;
            item.C_VALOR     = c_valor;
            item.TO_VALOR    = to_valor;
            item.TS_VALOR    = ts_valor;
            item.CS_VALOR     = cs_valor;
            item.CO_VALOR     = co_valor;
            item.CMOIP_VALOR = cmoip_valor;
            item.CMOIA_VALOR = cmoia_valor;

            gScope.Modelo.PERFIL = item.PERFIL;

        });
        
    }

    Ficha.prototype.calcularCusto = function(flag) {
        var that = this;
        var tamanho = gScope.Tamanho.SELECTED.ID;

        function pad(n, width, z) {
          z = z || '0';
          n = n + '';
          return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
        }

        angular.forEach(that.DADOS, function(item, key) {

            tamanho = pad(tamanho, 2);

            var t     = 'T'     + tamanho;
            var c     = 'C'     + tamanho;
            var to    = 'TO'    + tamanho;
            var ts    = 'TS'    + tamanho;
            var cs    = 'CS'    + tamanho;
            var co    = 'CO'    + tamanho;
            var cmoip = 'CMOIP' + tamanho;
            var cmoia = 'CMOIA' + tamanho;

            var t_valor     = item[t];
            var c_valor     = item[c];
            var to_valor    = item[to];
            var ts_valor    = item[ts];
            var cs_valor    = item[cs];
            var co_valor    = item[co];
            var cmoip_valor = item[cmoip];
            var cmoia_valor = item[cmoia];

            item.T_VALOR     = t_valor;
            item.C_VALOR     = c_valor;
            item.TO_VALOR    = to_valor;
            item.TS_VALOR    = ts_valor;
            item.CS_VALOR     = cs_valor;
            item.CO_VALOR     = co_valor;
            item.CMOIP_VALOR = cmoip_valor;
            item.CMOIA_VALOR = cmoia_valor;

            gScope.Gasto.Custo.Direto.MaoObraDireta.CustoSetup       = Number(cs_valor);
            gScope.Gasto.Custo.Direto.MaoObraDireta.CustoOperacional = Number(co_valor);
            gScope.Gasto.Custo.Indireto.Proprio                      = Number(cmoip_valor);
            gScope.Gasto.Custo.Indireto.Absorvido                    = Number(cmoia_valor);
            gScope.Gasto.Custo.MateriaPrima                          = Number(c_valor);

            var CustoSetup        = Number(gScope.Gasto.Custo.Direto.MaoObraDireta.CustoSetup) / Number(gScope.Fatores.QuantidadePedido);
            var CustoFrete        = Number(gScope.Fatores.Frete.Valor) / Number(gScope.Fatores.QuantidadePedido);
            var CustoOperacional  = Number(gScope.Gasto.Custo.Direto.MaoObraDireta.CustoOperacional);
            var IndiretoAbsorvido = Number(gScope.Gasto.Custo.Indireto.Absorvido);
            var IndiretoProprio   = Number(gScope.Gasto.Custo.Indireto.Proprio);
            var IndiretoProprio   = Number(gScope.Gasto.Custo.Indireto.Proprio);
            var MateriaPrima      = Number(gScope.Gasto.Custo.MateriaPrima);

            gScope.Fatores.CustoProduto         = MateriaPrima + CustoSetup + IndiretoAbsorvido + IndiretoProprio + CustoOperacional;
            gScope.Fatores.Incentivo.Resultado  = Number(gScope.Fatores.CustoProduto) * Number(gScope.Fatores.Incentivo.Valor / 100);
            gScope.Gasto.Despesa.CustoProduto  = Number(gScope.Fatores.CustoProduto)  - Number(gScope.Fatores.Incentivo.Resultado);
            gScope.Gasto.Despesa.Comissao       = Number(gScope.Fatores.CustoProduto) * Number(gScope.Fatores.Comissao / 100);
            gScope.Fatores.CustoProduto         = Number(gScope.Fatores.CustoProduto) + Number(gScope.Gasto.Despesa.Comissao) + Number(CustoFrete);

        });
        
    }

    Ficha.prototype.Selectionar = function (Ficha) {
        var that = this;

        if(that.SELECTED != Ficha){
            that.SELECTED = Ficha; 
        }
        
    }

    /**
     * Return the constructor function
     */
    return Ficha;
};