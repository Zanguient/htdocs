/**
 * _22100 - Geracao de Remessas de Bojo
 */

;(function(angular) {

    var Ctrl = function($scope,$ajax,$timeout,$filter,$window,$q, gcCollection,gcObject) {
        var vm = this;
        
        vm.PEDIDO_BLOQUEIO_USUARIOS = [];
        vm.dados    = [];
        vm.GPS = [];
        vm.MODELOS = [];
        vm.TOTAL_GERAL = 0;
        vm.filtro = {};
        var GRAVANDO_DADOS = false;
        var timer;
        var estacao_update = false;
        
        vm.filtrar = function() { 
            
            loading($('.container-programacao'));
                   
                   
            var data = {};
            
            angular.copy(vm.filtro,data);
            
            data.data_remessa         = moment(data.data_remessa        ).format('YYYY.MM.DD');
            data.data_disponibilidade = moment(data.data_disponibilidade).format('YYYY.MM.DD');
            
            $ajax.post('/_22100/getNecessidadeItens',data)
                .then(function(response) {

                    vm.dados = response;

                    var linhas = vm.dados.agrupamento_linhas;
                    for(var i in linhas)
                    {
                        var linha = linhas[i];
                                                
                        linha.PERCENTUAL_DEFEITO_CARREGADO = 0; // 0 = nao ; 1 = sim
                        linha.QUANTIDADE_PROGRAMADA = parseFloat(linha.QUANTIDADE_CAPACIDADE);
                        
                        // Calcula a quantidade utilizada
                        var itens = linha.MODELOS;
                        for ( var y in itens ) {
                            var item = itens[y];
                            vm.Agrupamento.Utilizado(item);
                        }  
                        
                        // Vincula os modelos da linha
                        linha.MODELOS = [];
                        var itens = vm.dados.agrupamento_itens;
                        for(var i in itens)
                        {
                            if ( itens[i].LINHA_ID == linha.LINHA_ID && itens[i].TAMANHO == linha.TAMANHO )
                            {
                                linha.MODELOS.push(itens[i]);
                            }
                        }
                        
                        // Vincula as ferramentas da linha
                        linha.FERRAMENTAS = [];
                        var ferramentas = vm.dados.ferramentas;
                        
                        FerramentaAlocacao(ferramentas);
                        
                        for (var j in ferramentas )
                        {
                            
                            if ( linha.LINHA_ID == ferramentas[j].LINHA_ID && linha.TAMANHO == ferramentas[j].TAMANHO ) {
                                linha.FERRAMENTAS.push(ferramentas[j]);
                            }
                        }
                        
                        vm.Linha.Saldo(linha);
                    }
                    
                    var itens = vm.dados.agrupamento_itens;
                    
                    // Cria os agrupamentos por grupo de produção
                    vm.MODELOS = gcCollection.distinct(itens, ['MODELO_ID','TAMANHO','COR_ID']);
                                        
                    for(var i in itens)
                    {
                        var item = itens[i];
                        
                        item.checked = true;
                        
                        item.HABILITA_PERCENTUAL_DEFEITO = true;
                        item.PERCENTUAL_DEFEITO = 0;
                        
                        gcObject.calcField('TEMPO_PAR',item, function(itemScope) {
                            
                            var percentual = 0;
                            if ( vm.HABILITA_PERCENTUAL_EXTRA ) {
                                percentual = itemScope.PERCENTUAL_DEFEITO;
                                itemScope.HABILITA_PERCENTUAL_EXTRA = true;
                            } else {
                                itemScope.HABILITA_PERCENTUAL_EXTRA = false;
                            }
                            
                            return parseFloat(itemScope.TEMPO_UNITARIO) * (1+parseFloat(percentual));
                        });	   
                        
                        
                        // Vincula os produtos de estoques aos modelos
                        item.ESTOQUE_PRODUTOS = [];
                        var consumos          = item.CONSUMO_ALOCACAO;
                        for ( var y in consumos ) {
                            var produtos_estoque = vm.dados.produtos_estoque;

                            for ( var i in produtos_estoque ) { 

                                if ( produtos_estoque[i].PRODUTO_ID == consumos[y].PRODUTO_ID ) {
                                    item.ESTOQUE_PRODUTOS.push(produtos_estoque[i]);
                                }
                            }
                        }
                        
                        vm.Consumo.Disponibilidade(item);
                    }
                    
                    
                    var taloes   = vm.dados.taloes_programados;  
                    var estacoes = vm.dados.estacoes;
                    for ( var i in estacoes ) {
                        var estacao = estacoes[i];
                        var ferramentas = vm.dados.ferramentas;
                        for (var j in ferramentas )
                        {
                            var minutos_estacao    = parseFloat(estacoes[i].MINUTOS);
                            var minutos_ferramenta = (ferramentas[j].MINUTOS == undefined) ? 0 : parseFloat(ferramentas[j].MINUTOS);
                            if ( minutos_estacao > minutos_ferramenta ) {
                                ferramentas[j].MINUTOS = minutos_estacao;
                            }
                        }
                        
                        estacao.itens_programados = [];
                        
                        for ( var y in taloes ) {
                            var talao = taloes[y];
                            
                            if ( 
                                talao.GP_ID   == estacao.GP_ID &&
                                talao.UP_ID   == estacao.UP_ID &&
                                talao.ESTACAO == estacao.ESTACAO
                            ) {
                                estacao.itens_programados.push(talao);                                
                            }
                        }
                    }
                    
                    
                    // Cria os agrupamentos por grupo de produção
                    vm.GPS = gcCollection.groupBy(vm.dados.estacoes, ['GP_DESCRICAO','GP_ID','UP_DESCRICAO','UP_ID']);
                    

                    // Insere os totalizadores iniciais para os grupos de produção
                    for ( var i in vm.GPS ) {

                        var estacoes = vm.GPS[i].COLLECTION;
                        for ( var y in estacoes ) {
                            var estacao = estacoes[y];
                            vm.Estacao.Totalizador(estacao);
                        }
                        
                        var gp = vm.GPS[i];
                        vm.Gp.Totalizador(gp);
                    }
                    loading('hide');
                }
            );
        };
        
        vm.gravar = function () {
            GRAVANDO_DADOS = true;
            $timeout(function(){
                
                var data = {};
                
                angular.copy(vm.filtro,data);

                data.data_remessa         = moment(data.data_remessa        ).format('YYYY.MM.DD');
                data.data_disponibilidade = moment(data.data_disponibilidade).format('YYYY.MM.DD');

                var dados = {
                    filtro : data,
                    linhas : vm.dados.agrupamento_linhas,
                    estacoes: vm.dados.estacoes,
                    agrupamentos : vm.dados.agrupamentos
                };

                $ajax.post('/_22100/gravar',dados)
                    .then(function(response) {
                        $window.location.href = '/_22120?remessa=' + response.remessa;
                    }
                );
                GRAVANDO_DADOS = false;
            });
        };
        
        
        vm.Linha = {
            selected : null,
            filter : '',
            Programar : function (linha) {
                
                try {

                    var estacoes = vm.Estacao.checks;

                    var estacao_selecionada = false;
                    
                    this.Select(linha);
                    vm.Agrupamento.AtualizarDefeitoPercentual().then(function(){
                        try {
                            for ( var j in estacoes )
                            {
                                if ( !estacoes[j].checked ) continue;

                                estacao_selecionada = true;


                                vm.Estacao.Select(estacoes[j]);

                                var itens = vm.Linha.selected.MODELOS;

                                for(var i in itens)
                                {

                                    if ( 
                                        itens[i].CONSUMO_DISPONIVEL == 1 && 
                                        itens[i].checked            == true
                                    ) {

                                        if ( itens[i].TEMPO_PAR <= 0 ) continue;
                                        if ( itens[i].QUANTIDADE_PROGRAMADA <= 0 ) continue;

                                        vm.Agrupamento.Select(itens[i]);

                                        vm.Item.Inserir(itens[i]);
                                    }
                                }
                            }

                            if ( !estacao_selecionada ) {
                                throw 'Selecione uma estação. Operação cancelada.';
                            }      
                        }
                        catch (err) {
                            showErro(err + 'Operação cancelada.');
                        }
                    });
                }
                catch (err) {
                    showErro(err + 'Operação cancelada.');
                }
            },
            Saldo : function(linha){
                var itens = linha.MODELOS;
                var summ = 0;

                for(var i in itens)
                {
                    var qtd = (itens[i].QUANTIDADE_TOTAL == undefined) ? 0 : parseFloat(itens[i].QUANTIDADE_TOTAL) - (parseFloat(itens[i].QUANTIDADE_UTILIZADA) || 0);
                    summ += qtd;
                }

                linha.QUANTIDADE_SALDO = summ;
                return summ;
            },
            RemessaHistorico : function(linha) {
                
                var modalHistorico = function() {
                    $('#modal-linha-remessa-historico').modal();
                    $('#modal-linha-remessa-historico').find('.scroll-table').scrollTop(0);
                };
                
                if ( linha.REMESSA_HISTORICO == undefined ) {
                    var dados = {
                        LINHA_ID : linha.LINHA_ID,
                        TAMANHO : linha.TAMANHO,
                        FAMILIA_ID : vm.filtro.familia_id
                    };

                    $ajax.post('/_22100/linha-remessa-historico',dados)
                        .then(function(response) {
                            linha.REMESSA_HISTORICO = response;      
                            modalHistorico();
                        }
                    );
                } else {
                    modalHistorico();
                }
            },
            Select : function (linha) {
                this.selected = linha;
                vm.Agrupamento.selected = null;
                
                if ( vm.Ferramenta.selected != undefined && (linha.LINHA_ID != vm.Ferramenta.selected.LINHA_ID || linha.TAMANHO != vm.Ferramenta.selected.TAMANHO) ) {
                    vm.Ferramenta.selected = null;
                }
                
                verificaEstacao();
                verificaAgrupamento();
            },
            FixVsRepeat : function() {
                $timeout(function(){
                    $('.linha.scroll-table').scrollTop(0);
                }, 10);
            }
        };

        vm.Agrupamento = {
            selected : null,
            index : null,
            filter : '',
            filtered : null,
            checked : true,
            KeyDown : function (item, $event) {
                
                /* Verifica se existe um evento */
                if ( !($event === undefined) ) {
                    
                    /* Verifica se a tecla pressionada foi 'Space' */
                    if ( $event.keyCode === 32 ) {
                        $event.preventDefault();
                        item.checked = !item.checked;
                        return false;
                    };
                    
//                    /* Verifica se a tecla pressionada foi 'Enter' */
//                    if ( $event.keyCode === 13 ) {
//                        
//                        var elemento = $event.target;
//                        var step     = parseFloat(elemento.step);
//                        var valor    = parseFloat(item.QUANTIDADE_PROGRAMADA);
//                        
//                        if( (valor % step) == 0 ) {
//                            vm.Item.Inserir(item); }
//                        else {
//                            showErro(valor + ' não é um múltiplo de ' + step + '. Operação cancelada!');
//                        }
//                    }
                }
            },
            FiltrarLinha : function(agrupamento) {
                var item = vm.Linha.selected;

                var result = false;

                if ( item != null ) {

                    if ( item.LINHA_ID == agrupamento.LINHA_ID && item.TAMANHO == agrupamento.TAMANHO ) {
                        result = true;
                    }           
                }
                
                return result;
            },
            FiltrarEspecial : function() {
                
                var itens = vm.Linha.selected.MODELOS;
                for(var i in itens)
                {
                    var item = itens[i];
                    
                    var quantidade_especial = 0;
                    for ( var j in item.AGRUPAMENTO ) {
                        var agrup = item.AGRUPAMENTO[j];

                        if ( moment(agrup.DATA).toDate() <= vm.filtro.previsao_max_faturamento ) {
                            quantidade_especial += parseFloat(agrup.QUANTIDADE_TOTAL);
                        }
                    }
                    item.QUANTIDADE_ESPECIAL = quantidade_especial;
                }
            },
            Select : function (agrupamento) {
                this.selected = agrupamento;
                this.index    = vm.dados.agrupamento_itens.indexOf(agrupamento);
            },
            CheckAll : function(bool) {
                var itens = this.filtered;
                
                for ( var i in itens ) {
                    itens[i].checked = bool;
                }
                
                this.checked = bool;
            },
            Utilizado : function(item){

                var estacoes = vm.dados.estacoes;
                var index    = vm.dados.agrupamento_itens.indexOf(item);
                var summ     = 0;

                for(var i in estacoes)
                {
                    if ( estacoes[i].itens_programados === undefined ) estacoes[i].itens_programados = [];

                    var itens_programados = estacoes[i].itens_programados;
                    for( var j in itens_programados )
                    {
                        if ( index == itens_programados[j].INDEX_AGRUPAMENTO_ORIGEM ) {
                            var qtd = (itens_programados[j].QUANTIDADE_PROGRAMADA == undefined) ? 0 : parseFloat(itens_programados[j].QUANTIDADE_PROGRAMADA);
                            summ += qtd;                             
                        }
                    }
                }

                item.QUANTIDADE_SALDO = item.QUANTIDADE_TOTAL - summ;
                item.QUANTIDADE_UTILIZADA = summ;

                return summ;
            },
            FixVsRepeat : function() {
                $timeout(function(){
                    $('.agrupamento.scroll-table').scrollTop(0);
                }, 10);
            },
            AtualizarTempo : function() {
                $ajax.post('/_22100/modelo-tempo',vm.MODELOS)
                    .then(function(response) {
                        gcCollection.merge(vm.dados.agrupamento_itens, response, ['MODELO_ID','TAMANHO','COR_ID'], true, false);
                        showSuccess('Os dados foram atualizado!');
                    }
                );
            },
            AtualizarDefeitoPercentual : function() {
                return $q(function(resolve, reject) {
                    var linha = vm.Linha.selected;
                    
                    if ( !linha.DEFEITOS_LOADED ) {
                    
                        $ajax.post('/_22100/sku-defeito-percentual',linha.MODELOS)
                            .then(function(response) {
                                for (var i in linha.MODELOS) {
                                    var modelo = linha.MODELOS[i];

                                    modelo.PERCENTUAL_DEFEITO = response[i].PERCENTUAL_DEFEITO;
                                }
                                linha.DEFEITOS_LOADED = true;
                                resolve(true);
                            }, function() {
                                reject(false);
                            }
                        );
                    } else {
                        resolve(true);
                    }
                });
            }
        };
        
        vm.Consumo = {
            filtered : {},
            Filtrar : function(produto) {
                var item = vm.Agrupamento.selected;

                var result = false;

                if ( item != null ) {

                    var alocacao = item.CONSUMO_ALOCACAO;

                    for (var i = 0, len = alocacao.length; i < len; i++) {
                        if (alocacao[i].PRODUTO_ID == produto.PRODUTO_ID) {
                            result = true;
                            break;
                        }
                    }                
                }

                return result;
            },
            Utilizado : function(item){
                var estacoes = vm.dados.estacoes;
                var summ = 0;

                for(var i in estacoes)
                {
                    if ( estacoes[i].itens_programados === undefined ) break;
                    
                    var itens_programados = estacoes[i].itens_programados;

                    for( var j in itens_programados)
                    {
                        var consumos = itens_programados[j].CONSUMO_ALOCACAO;

                        for( var y in consumos)
                        {
                            if (item.PRODUTO_ID == consumos[y].PRODUTO_ID) {
                                var qtd = (consumos[y].CONSUMO_QUANTIDADE == undefined) ? 0 : parseFloat(consumos[y].CONSUMO_QUANTIDADE);
                                summ += qtd;
                            }
                        }
                    }
                }

                item.UTILIZADO = summ;
                item.DISPONIVEL = item.ESTOQUE - item.EMPENHADO - item.UTILIZADO;
                return summ;
            },
            Disponibilidade : function(item) {

                item.CONSUMO_DISPONIVEL = 1;

                /* Incializar o array de produtos de consumo do itens a ser programado*/
                var consumos         = item.CONSUMO_ALOCACAO;
                var produtos_estoque = item.ESTOQUE_PRODUTOS;

                /* Verifica se ha materia-prima disponivel suficiente para o item a ser programado */
                for ( var y in consumos ) {

                    var consumo_quantidade = parseFloat(consumos[y].CONSUMO) * parseFloat((item.QUANTIDADE_PROGRAMADA || item.QUANTIDADE_SALDO)); 

                    for ( var i in produtos_estoque ) { 

                        vm.Consumo.Utilizado(produtos_estoque[i]);

                        if ( produtos_estoque[i].PRODUTO_ID == consumos[y].PRODUTO_ID ) {
                            var produtos_estoque_disponivel = parseFloat(produtos_estoque[i].DISPONIVEL);

                            if ( consumos[y].PROGRAMAR_SEM_ESTOQUE == 0 && produtos_estoque_disponivel < consumo_quantidade ) 
                            item.CONSUMO_DISPONIVEL = 0;

                            break;
                        }
                    }
                }
            }
        };
        
        vm.Ferramenta = {
            selected : null,
            AutoSelect : function(item) {
                
                if ( vm.Estacao.selected == null ) {
                    showErro('Selecione uma estação');
                    return false;
                }

                if ( vm.Agrupamento.selected == null ) {
                    showErro('Selecione um modelo para programar.');
                    return false;
                }

                var ferramentas_filtradas = [];

                ferramentas_filtradas = $filter('filter')(vm.Linha.selected.FERRAMENTAS, vm.Ferramenta.Filtrar.Modelo);
                ferramentas_filtradas = $filter('filter')(ferramentas_filtradas, vm.Ferramenta.Filtrar.Estacao);

                if ( ferramentas_filtradas[0] == undefined ) {
                    throw 'Não há ferramentas disponíveis para uso.<br/>Modelo ' + vm.Agrupamento.selected.MODELO_DESCRICAO + ' - ' + vm.Agrupamento.selected.COR_DESCRICAO + '<br/>GP ' + vm.Estacao.selected.GP_DESCRICAO + ' - Estação ' + vm.Estacao.selected.ESTACAO_DESCRICAO + '.<br/>';
                }
                
                /* Captura a quantidade programada da linha */
                vm.Linha.quantidade_programar = 0;
                for ( var i in vm.Estacao.selected.itens_programados ) {
                    var item_estacao = vm.Estacao.selected.itens_programados[i];
                    
                    if ( item_estacao.LINHA_ID == item.LINHA_ID && item_estacao.TAMANHO == item.TAMANHO ) {
                        vm.Linha.quantidade_programar += parseFloat(item_estacao.QUANTIDADE_PROGRAMADA);
                    }
                }
                
                for ( var i in ferramentas_filtradas ) {
                    
                    if ( ferramentas_filtradas[i].UP_UTILIZADA != undefined && ferramentas_filtradas[i].UP_UTILIZADA != vm.Estacao.selected.UP_ID+'-'+vm.Estacao.selected.ESTACAO ) {
                        continue;
                    }
                    
                    vm.Ferramenta.Select(ferramentas_filtradas[i]);

                    vm.Item.Tempo(item);
                    
                    var indisponibilidade = vm.Ferramenta.VerificarIndisponibilidade(item);

                    if ( !indisponibilidade ) {
                        ferramentas_filtradas[i].UP_UTILIZADA = vm.Estacao.selected.UP_ID+'-'+vm.Estacao.selected.ESTACAO;
                        break;
                    }
                }
            },
            Alocar : function(item) {
                
                vm.Ferramenta.AutoSelect(item);
                
                var indisponibilidade = vm.Ferramenta.VerificarIndisponibilidade(item);
                
                if ( ! ( item.QUANTIDADE_PROGRAMADA > 0 ) ) return false;
                
                if ( indisponibilidade ) throw new Error(indisponibilidade);
                
                if ( item.TEMPO_FIM > vm.Estacao.selected.MINUTOS ) {
                    throw  new Error('Capacidade de tempo da ferramenta execidida!');
                }
                
                item.FERRAMENTA_ID        = vm.Ferramenta.selected.ID;
                item.FERRAMENTA_DESCRICAO = vm.Ferramenta.selected.DESCRICAO;

            },
            VerificarIndisponibilidade : function(item) {
                var result = false;
                
                var item_alocar = item;
                
                if ( item_alocar.TEMPO_INICIO == undefined || item_alocar.TEMPO_FIM == undefined) {
                    result = 'Nao há tempo alimentado para comparação com a ferramenta.';
                } else {

                    if ( vm.Ferramenta.selected.MINUTOS_ALOCACAO == undefined ) vm.Ferramenta.selected.MINUTOS_ALOCACAO = [];

                    for ( var i in vm.Ferramenta.selected.MINUTOS_ALOCACAO ) {

                        var item = vm.Ferramenta.selected.MINUTOS_ALOCACAO[i];

                        if ( item_alocar.TEMPO_INICIO <= item.TEMPO_INICIO && item_alocar.TEMPO_FIM >= item.TEMPO_FIM ) {
                            result = 'Conflito de tempo na ferramenta selecionada.<br/>Intervalo desejado: ' + item_alocar.TEMPO_INICIO + ' à ' + item_alocar.TEMPO_FIM + '. Intervalo alocado: ' + item.TEMPO_INICIO + ' à ' + item.TEMPO_FIM + '.<br/>';
    //                        console.log('Tempo ja ocupado com um intervalo de tempo menor |::::::::::::::::|');
    //                        console.log('V' + item_alocar.TEMPO_INICIO + ' <= + ' + 'D' + item.TEMPO_INICIO + ' && ' + 'V' + item_alocar.TEMPO_FIM  + ' >= ' + 'D' + item.TEMPO_FIM);
    //                        break;
                        }

                        if ( item_alocar.TEMPO_INICIO >= item.TEMPO_INICIO && item_alocar.TEMPO_FIM <= item.TEMPO_FIM ) {
                            result = 'Conflito de tempo na ferramenta selecionada.<br/>Intervalo desejado: ' + item_alocar.TEMPO_INICIO + ' à ' + item_alocar.TEMPO_FIM + '. Intervalo alocado: ' + item.TEMPO_INICIO + ' à ' + item.TEMPO_FIM + '.<br/>';
    //                        console.log('Tempo ja ocupado com um intervalo de tempo maior :::::|::::::|:::::');
    //                        console.log('V' + item_alocar.TEMPO_INICIO + ' >= ' + 'D' + item.TEMPO_INICIO + ' && ' + 'V' + item_alocar.TEMPO_FIM + ' <= ' + 'D' + item.TEMPO_FIM);
    //                        break;
                        }

                        if ( item_alocar.TEMPO_INICIO <= item.TEMPO_INICIO && item_alocar.TEMPO_FIM <= item.TEMPO_FIM && item_alocar.TEMPO_FIM >= item.TEMPO_INICIO ) {
                            result = 'Conflito de tempo na ferramenta selecionada.<br/>Intervalo desejado: ' + item_alocar.TEMPO_INICIO + ' à ' + item_alocar.TEMPO_FIM + '. Intervalo alocado: ' + item.TEMPO_INICIO + ' à ' + item.TEMPO_FIM + '.<br/>';
    //                        console.log('Tempo ocupado com intervalo de tempo a frente |::::::::|::::::::');
    //                        console.log('V' + item_alocar.TEMPO_INICIO + ' <= ' + 'D' + item.TEMPO_INICIO + ' && ' + 'V' + item_alocar.TEMPO_FIM + ' <= ' + 'D' + item.TEMPO_FIM + ' && ' + 'V' + item_alocar.TEMPO_FIM + ' >= ' + 'D' + item.TEMPO_INICIO);
    //                        break;
                        }

                        if ( item_alocar.TEMPO_INICIO >= item.TEMPO_INICIO && item_alocar.TEMPO_FIM >= item.TEMPO_FIM && item_alocar.TEMPO_INICIO <= item.TEMPO_FIM ) {
                            result = 'Conflito de tempo na ferramenta selecionada.<br/>Intervalo desejado: ' + item_alocar.TEMPO_INICIO + ' à ' + item_alocar.TEMPO_FIM + '. Intervalo alocado: ' + item.TEMPO_INICIO + ' à ' + item.TEMPO_FIM + '.<br/>';
    //                        console.log('Tempo ocupado com intervalo de tempo atras ::::::::|::::::::|');
    //                        console.log('V' + item_alocar.TEMPO_INICIO + ' >= ' + 'D' + item.TEMPO_INICIO + ' && ' + 'V' + item_alocar.TEMPO_INICIO + ' <= ' + 'D' + item.TEMPO_INICIO + ' && ' + 'V' + item_alocar.TEMPO_FIM + ' >= ' + 'D' + item.TEMPO_FIM);
    //                        break;
                        }

                    }
                }
                
                return result;
            },
            Utilizado : function(ferramenta,to) {

//                    var estacoes = vm.dados.estacoes;
//                    ferramenta.MINUTOS_ALOCACAO = [];
//
//
//                    for (var i in ferramenta.ALOCACOES) {
//
//                        var clone_alocacoes = {};
//                        angular.copy(ferramenta.ALOCACOES[i], clone_alocacoes);
//
//                        var alocacao = clone_alocacoes;
//
//                        ferramenta.MINUTOS_ALOCACAO.push({
//                            TIPO                  : alocacao.TIPO,
//                            TABELA_ID             : alocacao.TABELA_ID,
//                            REMESSA_ID            : alocacao.REMESSA_ID,
//                            REMESSA               : alocacao.REMESSA,
//                            GP_ID                 : alocacao.GP_ID,
//                            GP_DESCRICAO          : alocacao.GP_DESCRICAO,
//                            UP_ID                 : alocacao.UP_ID,
//                            UP_DESCRICAO          : alocacao.UP_DESCRICAO,
//                            ESTACAO               : alocacao.ESTACAO,
//                            ESTACAO_DESCRICAO     : alocacao.ESTACAO_DESCRICAO,
//                            MODELO_ID             : alocacao.MODELO_ID,
//                            MODELO_DESCRICAO      : alocacao.MODELO_DESCRICAO,
//                            COR_ID                : alocacao.COR_ID,
//                            COR_DESCRICAO         : alocacao.COR_DESCRICAO,
//                            QUANTIDADE_PROGRAMADA : parseFloat(alocacao.QUANTIDADE_PROGRAMADA),
//                            DATAHORA_INICIO       : alocacao.DATAHORA_INICIO,
//                            DATAHORA_FIM          : alocacao.DATAHORA_FIM,
//                            TEMPO_INICIO          : parseFloat(alocacao.TEMPO_INICIO),
//                            TEMPO_FIM             : parseFloat(alocacao.TEMPO_FIM),
//                            MINUTOS_PROGRAMADOS   : parseFloat(alocacao.MINUTOS_PROGRAMADOS),
//                            TEMPO_ITEM            : parseFloat(alocacao.TEMPO_ITEM)
//                        });
//                    }
//
//    //                ferramenta.MINUTOS_ALOCACAO = clone_alocacoes;
//
//                    for(var i in estacoes)
//                    {
//                        var estacao = estacoes[i];
//                        if ( estacao.itens_programados === undefined ) estacao.itens_programados = [];
//
//                        var itens_programados = estacao.itens_programados;
//
//                        var tempo_inicio = 0;
//                        for( var j in itens_programados )
//                        {
//                            var item = itens_programados[j];
////                            var item_anterior = (itens_programados[j-1]) == undefined ? {} : itens_programados[j-1];
////
////
////                            var tempo_setup = parseFloat(item.TEMPO_FERRAMENTA_SETUP) + parseFloat(item.TEMPO_FERRAMENTA_SETUP_AQUECIMENTO);
////
////                            // Verifica se haverá um setup de ferramenta
////                            if (
////                                item.SETUP &&
////                                item_anterior.MATRIZ_ID     == item.MATRIZ_ID    &&
////                                item_anterior.LINHA_ID      == item.LINHA_ID     &&
////                                item_anterior.TAMANHO       == item.TAMANHO      &&
////                                item_anterior.FERRAMENTA_ID == item.FERRAMENTA_ID
////                            ) {
////                                item.SETUP = false;
////                                item.MINUTOS_PROGRAMADOS -= tempo_setup;
////                            } else if (
////                                !item.SETUP &&
////                               (itens_programados[0]        == item              ||
////                                item_anterior.MATRIZ_ID     != item.MATRIZ_ID    ||
////                                item_anterior.LINHA_ID      != item.LINHA_ID     ||
////                                item_anterior.TAMANHO       != item.TAMANHO      ||
////                                item_anterior.FERRAMENTA_ID != item.FERRAMENTA_ID)
////                            ) {
////                                item.SETUP = true;
////                                item.MINUTOS_PROGRAMADOS += tempo_setup;
////                            }
////
////
////                            item.TEMPO_INICIO = tempo_inicio;
////
////                            tempo_inicio += item.MINUTOS_PROGRAMADOS;
////
////                            item.TEMPO_FIM    = tempo_inicio - 1;
////
////                            if ( item.FERRAMENTA_ID == undefined ) continue;
//
//                            var clone_item = {};
//                            angular.copy(item, clone_item);
//
//                            if ( item.FERRAMENTA_ID ==  ferramenta.ID ) {
//                                ferramenta.MINUTOS_ALOCACAO.push(clone_item);
//                            }
//                        }
//                    }

            },
            Select : function (ferramenta) {
                this.selected = ferramenta;
            },
            Filtrar : {
                Linha : function(item) {
                    var linha  = vm.Linha.selected;
                    var result = false;

                    if ( linha != null ) {

                        if ( linha.LINHA_ID == item.LINHA_ID && linha.TAMANHO == item.TAMANHO ) {
                            result = true;
                        }          
                    }

                    return result;
                },
                Modelo : function(item) {
                    var modelo  = vm.Agrupamento.selected;
                    var result = true;

                    if ( modelo != null ) {

//                        if ( modelo.MATRIZ_ID != item.MATRIZ_ID ) {
//                            result = false;
//                            if ( item == vm.Ferramenta.selected ) vm.Ferramenta.selected = null;
//                        }          
                    }

                    return result;
                },
                Estacao : function(item) {
                    var estacao  = vm.Estacao.selected;
                    var result = true;

                    if ( estacao != null ) {

                        if ( 
                            estacao.ESTACAO_LARGURA     <  item.LARGURA     ||
                            estacao.ESTACAO_COMPRIMENTO <  item.COMPRIMENTO ||
                            estacao.ESTACAO_ALTURA      <  item.ALTURA      ||
                            estacao.ESTACAO_LARGURA     <= 0                ||
                            estacao.ESTACAO_COMPRIMENTO <= 0                ||
                            estacao.ESTACAO_ALTURA      <= 0 
                        ) {
                            result = false;
                            if ( item == vm.Ferramenta.selected ) vm.Ferramenta.selected = null;
                        }          
                    }

                    return result;
                }
            }
        };
        
        var FerramentaAlocacao = function (newValue, oldValue, scope) {
            for ( var i in newValue ) {
                
                var ferramenta = newValue[i];
                        
                vm.Ferramenta.Select(ferramenta);
                                
                ferramenta.MINUTOS_ALOCACAO = [];
                
                ferramenta.ALOCACAO_REMESSA = null;
                ferramenta.ALOCACAO_GP_DESCRICAO = null;
                ferramenta.ALOCACAO_ESTACAO_DESCRICAO = null;
                
                for (var i in ferramenta.ALOCACOES) {

                    var clone_alocacoes = {};
                    angular.copy(ferramenta.ALOCACOES[i], clone_alocacoes);

                    var alocacao = clone_alocacoes;

                    if ( ferramenta.ALOCACAO_REMESSA == null  ) {
                        ferramenta.ALOCACAO_REMESSA           = alocacao.REMESSA + ' / ' + alocacao.REMESSA_TALAO_ID;
                        ferramenta.ALOCACAO_GP_DESCRICAO      = alocacao.GP_DESCRICAO;
                        ferramenta.ALOCACAO_ESTACAO_DESCRICAO = alocacao.ESTACAO_DESCRICAO;
                    }
                    
                    ferramenta.MINUTOS_ALOCACAO.push({
                        TIPO                  : alocacao.TIPO,
                        TABELA_ID             : alocacao.TABELA_ID,
                        REMESSA_ID            : alocacao.REMESSA_ID,
                        REMESSA               : alocacao.REMESSA,
                        GP_ID                 : alocacao.GP_ID,
                        GP_DESCRICAO          : alocacao.GP_DESCRICAO,
                        UP_ID                 : alocacao.UP_ID,
                        UP_DESCRICAO          : alocacao.UP_DESCRICAO,
                        ESTACAO               : alocacao.ESTACAO,
                        ESTACAO_DESCRICAO     : alocacao.ESTACAO_DESCRICAO,
                        MODELO_ID             : alocacao.MODELO_ID,
                        MODELO_DESCRICAO      : alocacao.MODELO_DESCRICAO,
                        COR_ID                : alocacao.COR_ID,
                        COR_DESCRICAO         : alocacao.COR_DESCRICAO,
                        QUANTIDADE_PROGRAMADA : parseFloat(alocacao.QUANTIDADE_PROGRAMADA),
                        DATAHORA_INICIO       : alocacao.DATAHORA_INICIO,
                        DATAHORA_FIM          : alocacao.DATAHORA_FIM,
                        TEMPO_INICIO          : parseFloat(0),
                        TEMPO_FIM             : parseFloat(10000),
                        MINUTOS_PROGRAMADOS   : parseFloat(10000),
                        TEMPO_ITEM            : parseFloat(10000)
                    });
                    
                }
                
                var estacoes = vm.dados.estacoes;
                                
                for(var i in estacoes)
                {
                    var estacao = estacoes[i];
                    if ( estacao.itens_programados === undefined ) estacao.itens_programados = [];

                    var itens_programados = estacao.itens_programados;
                    var tempo_inicio = 0;
                    
                    for( var j in itens_programados )
                    {
                        var item = itens_programados[j];
                        
                        if ( item.DATAHORA_INICIO != undefined ) continue;
                        
                        var item_anterior = (itens_programados[j-1]) == undefined ? {} : itens_programados[j-1];

                        var tempo_setup = parseFloat(item.TEMPO_FERRAMENTA_SETUP) + parseFloat(item.TEMPO_FERRAMENTA_SETUP_AQUECIMENTO);

                        // Verifica se haverá um setup de ferramenta
                        if (
                            item.SETUP &&
//                            item_anterior.MATRIZ_ID     == item.MATRIZ_ID    &&
                            item_anterior.LINHA_ID      == item.LINHA_ID     &&
                            item_anterior.TAMANHO       == item.TAMANHO      &&
                            item_anterior.FERRAMENTA_ID == item.FERRAMENTA_ID
                        ) {
                            item.SETUP = false;
                            item.MINUTOS_PROGRAMADOS -= tempo_setup;
                        } else if (
                            !item.SETUP &&
                           (itens_programados[0]        == item              ||
//                            item_anterior.MATRIZ_ID     != item.MATRIZ_ID    ||
                            item_anterior.LINHA_ID      != item.LINHA_ID     ||
                            item_anterior.TAMANHO       != item.TAMANHO      ||
                            item_anterior.FERRAMENTA_ID != item.FERRAMENTA_ID)
                        ) {
                            item.SETUP = true;
                            item.MINUTOS_PROGRAMADOS += tempo_setup;
                        }

                        item.TEMPO_INICIO = tempo_inicio;

                        if ( item.MINUTOS_PROGRAMADOS != undefined ) {
                            tempo_inicio += item.MINUTOS_PROGRAMADOS;
                        }

                        item.TEMPO_FIM    = tempo_inicio - 1;
                        
//                        if ( vm.Ferramenta.VerificarIndisponibilidade(item) ) {
//                            vm.Item.Excluir(estacao,item);
//                        }

                        if ( item.FERRAMENTA_ID == undefined ) continue;

                        var clone_item = {};
                        angular.copy(item, clone_item);

                        if ( item.FERRAMENTA_ID == ferramenta.ID ) {
                            ferramenta.MINUTOS_ALOCACAO.push(clone_item);
                        }
                    }
                }
                
                if ( ferramenta.MINUTOS_ALOCACAO.length == 0 ) {
                    ferramenta.UP_UTILIZADA = undefined;
                } else {
                    ferramenta.UP_UTILIZADA = ferramenta.MINUTOS_ALOCACAO[0].UP_ID+'-'+ferramenta.MINUTOS_ALOCACAO[0].ESTACAO;
                }
            }
        };
        
        $scope.$watch('vm.Estacao.checks', function (newValue, oldValue, scope) {
            
            verificaEstacao();
        }, true);
        
        function verificaEstacao() {
            
            if ( vm.Linha.selected != undefined ) {
                
                for ( var j in vm.Estacao.checks ) {
                    var estacao = vm.Estacao.checks[j];

                    estacao.FERRAMENTA_DISPONIVEL = false;

                    for ( var i in vm.Linha.selected.FERRAMENTAS ) {
                        var ferramenta = vm.Linha.selected.FERRAMENTAS[i];

                        var ferramenta_disponivel = (ferramenta.MINUTOS_ALOCACAO == undefined || ferramenta.MINUTOS_ALOCACAO.length == 0);
                        var medidas_conferem      = (estacao.ESTACAO_LARGURA     >= ferramenta.LARGURA     &&
                                                     estacao.ESTACAO_COMPRIMENTO >= ferramenta.COMPRIMENTO &&
                                                     estacao.ESTACAO_ALTURA      >= ferramenta.ALTURA      && 
                                                     estacao.ESTACAO_LARGURA     > 0 &&
                                                     estacao.ESTACAO_COMPRIMENTO > 0 &&
                                                     estacao.ESTACAO_ALTURA      > 0 );

                        if ( ferramenta_disponivel && medidas_conferem ) {
                            estacao.FERRAMENTA_DISPONIVEL = true;
                            break;
                        }     
                    }
                }
            }
        }
        
        function verificaAgrupamento() {
            
            if ( vm.Linha.selected != undefined ) {
                
                for ( var j in vm.Linha.selected.MODELOS ) {
                    var modelo = vm.Linha.selected.MODELOS[j];

                    if ( modelo.checked ) {
                        
                        modelo.FERRAMENTA_DISPONIVEL = false;

                        for ( var i in vm.Linha.selected.FERRAMENTAS ) {
                            var ferramenta = vm.Linha.selected.FERRAMENTAS[i];

                            var ferramenta_disponivel = (ferramenta.MINUTOS_ALOCACAO == undefined || ferramenta.MINUTOS_ALOCACAO.length == 0);
                            var matriz_confere        = true;//(modelo.MATRIZ_ID == ferramenta.MATRIZ_ID);

                            if ( ferramenta_disponivel && matriz_confere ) {
                                modelo.FERRAMENTA_DISPONIVEL = true;
                                break;
                            }     
                        }
                    }
                }
            }
        }
        
        function AtualizarDadosGeral() {
            estacao_update = false;
            FerramentaAlocacao(vm.Linha.selected.FERRAMENTAS);

            var linhas = vm.dados.agrupamento_linhas;
            for ( var i in linhas ) {

                var itens = linhas[i].MODELOS;
                for ( var y in itens ) {
                    var item = itens[y];
                    vm.Agrupamento.Utilizado(item);
                    vm.Consumo.Disponibilidade(item);
                }                    

                vm.Linha.Saldo(linhas[i]);
            }

            vm.TOTAL_GERAL = 0;
            for ( var i in vm.GPS ) {

                var estacoes = vm.GPS[i].COLLECTION;
                for ( var y in estacoes ) {
                    var estacao = estacoes[y];
                    vm.Estacao.Totalizador(estacao);
                }

                var gp = vm.GPS[i];
                vm.Gp.Totalizador(gp);
                vm.TOTAL_GERAL += gp.QUANTIDADE_PROGRAMADA;
            }
        }
        
        $scope.$watch('vm.dados.estacoes', function (newValue, oldValue, scope) {
            
            if ( estacao_update ) {
                AtualizarDadosGeral();
            }
        }, true);
        
        vm.Gp = {
            selected_id : null,
            pares_programados : 0,
            minutos : 0,
            minutos_programados : 0,
            percentual_utilizado_desc : 0,
            percentual_utilizado_asc : 0,
            Totalizador : function (gp) {
                
                gp.QUANTIDADE_PROGRAMADA  = 0;
                gp.MINUTOS                = 0;
                gp.MINUTOS_PROGRAMADOS    = 0;
                gp.QUANTIDADE_FERRAMENTAS = 0;

                var estacoes = gp.COLLECTION;
                
                for(var i in estacoes) {
                    var estacao = estacoes[i];
                    gp.QUANTIDADE_PROGRAMADA   = gp.QUANTIDADE_PROGRAMADA  + (parseFloat(estacao.PARES_PROGRAMADOS  ) || 0);
                    gp.MINUTOS                 = gp.MINUTOS             + (parseFloat(estacao.MINUTOS            ) || 0);
                    gp.MINUTOS_PROGRAMADOS     = gp.MINUTOS_PROGRAMADOS + (parseFloat(estacao.MINUTOS_PROGRAMADOS) || 0);
                    gp.QUANTIDADE_FERRAMENTAS += estacao.QUANTIDADE_FERRAMENTAS;
                }

                /**
                 * Calculo de Percentual Utilizado
                 */
                var calculo = ((gp.MINUTOS > 0) ? (100 - ((gp.MINUTOS_PROGRAMADOS / gp.MINUTOS)*100)) : 100);
                var result  = (calculo < 0) ? 0 : calculo;
                
                gp.PERCENTUAL_UTILIZADO_ASC  = 100 - calculo;
                gp.PERCENTUAL_UTILIZADO_DESC = result.toFixed(2);
            }
        };
        
        vm.Estacao = {
            selected : null,
            selected_id : null,
            radiobox : false,
            index    : -1,
            checks : [],
            Check : function(estacao) {
                
                estacao.checked = !estacao.checked;
                
                if ( estacao.checked ) {
                    this.checks.push(estacao);
                } else {
                    var index = this.checks.indexOf(estacao);
                    this.checks.splice(index, 1);
                }
            },
            Select : function (estacao) {
                this.index_id     = vm.dados.estacoes.indexOf(estacao);
                this.selected     = estacao;
                this.selected_id  = estacao.ESTACAO;
                vm.Gp.selected_id = estacao.GP_ID;
            },
            Totalizador : function (estacao) {
                
                var itens                  = estacao.itens_programados;
                var pares_programados      = 0;
                var minutos_programados    = 0;
                var quantidade_ferramentas = 0;
                
                for(var i in itens) {
                    var item = itens[i];
                    
                    if ( item.DATAHORA_INICIO == undefined ) {
                        pares_programados   = pares_programados   + (parseFloat(item.QUANTIDADE_PROGRAMADA) || 0);
                        minutos_programados = minutos_programados + (parseFloat(item.MINUTOS_PROGRAMADOS) || 0);
                    
                        if ( itens[i-1] == undefined || itens[i-1].FERRAMENTA_ID != item.FERRAMENTA_ID  ) {
                            quantidade_ferramentas += 1;
                        }
                    }
                }

                estacao.QUANTIDADE_FERRAMENTAS = quantidade_ferramentas;
                estacao.PARES_PROGRAMADOS      = pares_programados;
                estacao.MINUTOS_PROGRAMADOS    = minutos_programados;
                
                /**
                 * Calculo de Percentual Utilizado
                 */
                var calculo = ((estacao.MINUTOS > 0) ? (100 - ((estacao.MINUTOS_PROGRAMADOS / estacao.MINUTOS)*100)) : 100);
                var result  = (calculo < 0) ? 0 : calculo;
                
                estacao.PERCENTUAL_UTILIZADO_ASC  = 100 - calculo;
                estacao.PERCENTUAL_UTILIZADO_DESC = result.toFixed(2);
            }
        };
        
        vm.Item = {
            linha_selected : null,
            estacao_selected : null,
            Tempo : function(item) {

                /* Bloqueio de Limite de Tempo da Estação */
                var estacao_minutos             = parseFloat(vm.Estacao.selected.MINUTOS);
                var estacao_minutos_programados = parseFloat(vm.Estacao.selected.MINUTOS_PROGRAMADOS);
                var item_programado_anterior = vm.Estacao.selected.itens_programados[vm.Estacao.selected.itens_programados.length-1];
                var quantidade_programada = parseFloat(item.QUANTIDADE_PROGRAMADA); 
                var tempo_unitario        = parseFloat(item.TEMPO_PAR);
                var tempo_operacional     = quantidade_programada * tempo_unitario;
                var tempo_setup           = 0;

                item.TEMPO_FERRAMENTA_SETUP = parseFloat(vm.Ferramenta.selected.TEMPO_SETUP);
                item.TEMPO_FERRAMENTA_SETUP_AQUECIMENTO = parseFloat(vm.Ferramenta.selected.TEMPO_SETUP_AQUECIMENTO);

                // Verifica se haverá um setup de ferramenta
                if (
                    vm.Estacao.selected.itens_programados.length < 1 || 
//                    item_programado_anterior.MATRIZ_ID     != item.MATRIZ_ID ||
                    item_programado_anterior.LINHA_ID      != item.LINHA_ID  ||
                    item_programado_anterior.TAMANHO       != item.TAMANHO   ||
                    item_programado_anterior.FERRAMENTA_ID != vm.Ferramenta.selected.ID
                ) {
                    item.SETUP = true;
                    item.HABILITA_FERRAMENTA_SETUP = true;
                    tempo_setup = parseFloat(item.TEMPO_FERRAMENTA_SETUP);


                    if ( vm.Estacao.selected.HABILITA_SETUP_AQUECIMENTO == '1' ) {
                        tempo_setup += parseFloat(item.TEMPO_FERRAMENTA_SETUP_AQUECIMENTO);
                        item.HABILITA_FERRAMENTA_SETUP_AQUECIMENTO = true;
                    } else {
                        delete item.HABILITA_FERRAMENTA_SETUP_AQUECIMENTO;
                    }

                } else {
                    delete item.SETUP;
                    delete item.HABILITA_FERRAMENTA_SETUP;
                    tempo_setup = 0;
                }

                // Verifica se havará setup de limpeza de matriz (só se não houver troca de matriz)
                if ( !item.SETUP && item_programado_anterior != undefined && item_programado_anterior.COR_ID != item.COR_ID ) {

                    item.HABILITA_COR_SETUP = true;
                    
                    for ( var i in item_programado_anterior.CORES_SIMILARES ) {
                        var cor_similiar = item_programado_anterior.CORES_SIMILARES[i];

                        if ( cor_similiar.COR_ID_2 == item.COR_ID ) {
                            
                            delete item.HABILITA_COR_SETUP;
                            break;
                        }
                    }
                    
                    if ( item.HABILITA_COR_SETUP ) {

                        tempo_setup += parseFloat(item.COR_TEMPO_SETUP);
                    }
                }

                // Verifica se havará tempo de aprovação
                if ( item_programado_anterior != undefined && item_programado_anterior.COR_ID != item.COR_ID ) {

                    tempo_setup += parseFloat(item.COR_TEMPO_SETUP_APROVACAO);
                    item.HABILITA_COR_SETUP_APROVACAO = true;
                } else {
                    delete item.HABILITA_COR_SETUP_APROVACAO;
                }

                var minutos_disponiveis = estacao_minutos - estacao_minutos_programados - tempo_setup;
                var quantidade_exata    = ( tempo_operacional > minutos_disponiveis) ? minutos_disponiveis / tempo_unitario : quantidade_programada;
                var quantidade_arrend   = quantidade_exata - (quantidade_exata % parseFloat(item.TALAO_DETALHE_COTA)); 

                if ( (vm.Linha.quantidade_programar + quantidade_arrend) > parseFloat(vm.Linha.selected.QUANTIDADE_PROGRAMADA) ) {
                    quantidade_arrend = parseFloat(vm.Linha.selected.QUANTIDADE_PROGRAMADA) - vm.Linha.quantidade_programar;
                }

                if ( ! ( quantidade_arrend > 0 ) ) throw new Error('aqui nao');


                item.QUANTIDADE_PROGRAMADA = quantidade_arrend;
                
                gcObject.calcField('MINUTOS_PROGRAMADOS',item, function(itemScope) {
                    
                    if ( GRAVANDO_DADOS ) {
                        itemScope.TEMPO_PAR = itemScope.TEMPO_UNITARIO;
                    } else {
                        var percentual = 0;
                        
                        if ( itemScope.HABILITA_PERCENTUAL_EXTRA ) {                            
                            percentual = itemScope.PERCENTUAL_DEFEITO;
                        }
                            
                        itemScope.TEMPO_PAR = parseFloat(itemScope.TEMPO_UNITARIO) * (1+parseFloat(percentual));
                    }
                    
                    return Math.ceil(parseFloat(itemScope.QUANTIDADE_PROGRAMADA) * parseFloat(itemScope.TEMPO_PAR)) + tempo_setup;
                });	                
                
                gcObject.calcField('TEMPO_ITEM',item, function(itemScope) {

                    return Math.ceil(parseFloat(itemScope.QUANTIDADE_PROGRAMADA) * parseFloat(itemScope.TEMPO_PAR));
                });	             
                
//                item.MINUTOS_PROGRAMADOS   = Math.ceil(parseFloat(item.QUANTIDADE_PROGRAMADA) * parseFloat(item.TEMPO_PAR)) + tempo_setup;
//                item.TEMPO_ITEM            = Math.ceil(parseFloat(item.QUANTIDADE_PROGRAMADA) * parseFloat(item.TEMPO_PAR));

                item.TEMPO_INICIO = estacao_minutos_programados;
                item.TEMPO_FIM    = estacao_minutos_programados + item.MINUTOS_PROGRAMADOS - 1;
            },
            Totalizador : function(itens) {
                return itens.reduce(function(sum, current){
                    return sum + current.QUANTIDADE_PROGRAMADA;
                }, 0);
            },
            Filtrar : {
                Linha : function (estacao) {
                    return function(item) {
                        var result = false;
                    
                        if ( 
                                vm.Item.linha_selected          != undefined     && 
                                vm.Item.estacao_selected        == estacao       && 
                                vm.Item.linha_selected.LINHA_ID == item.LINHA_ID && 
                                vm.Item.linha_selected.TAMANHO  == item.TAMANHO 
                            ) {
                            result = true;
                        }
                        
                        return result;
                    };
                }
            },
            Inserir : function(item) {
                try { 
                    if ( !(item.QUANTIDADE_PROGRAMADA > 0) ) throw 'Quantidade à programar deverá ser maior que zero.';
                    if ( !(vm.Estacao.selected_id > 0)) throw 'Selecione uma estação.';

                    /* Minutos total da estação            */ var estacao_minutos             = parseFloat(vm.Estacao.selected.MINUTOS);
                    /* Minutos utilizados da estação       */ var estacao_minutos_programados = parseFloat(vm.Estacao.selected.MINUTOS_PROGRAMADOS);
                    /* Inicializa a variável à ser clonada */ var clone_item                  = {};

                    if ( ! ( estacao_minutos > 0 ) ) throw 'Não há calendário de produção configurado para a estação selecionada na data de remessa informada.';

                    /* Inicializa o array de itens programados na estação selecionado se não existir */
                    if ( vm.Estacao.selected.itens_programados === undefined ) vm.Estacao.selected.itens_programados = [];

                    /* Clona o item do agrupamento de produção */
                    angular.copy(item, clone_item);

                    vm.Ferramenta.Alocar(clone_item);

                    if ( ! ( clone_item.QUANTIDADE_PROGRAMADA > 0 ) ) throw 'Não há mais espaço suficiente na estação para a quantidade informada.';

                    
                    /* Informa no item à programar o index do item do agrupamento que o orginiou */
                    clone_item.INDEX_AGRUPAMENTO_ORIGEM = vm.Agrupamento.index;


                    /* Incializar o array de produtos de consumo do items a ser programado*/
                    var consumos = clone_item.CONSUMO_ALOCACAO;
                    var produtos_estoque = item.ESTOQUE_PRODUTOS;
                    
                    /* Verifica se ha materia-prima disponivel suficiente para o item a ser programado */
                    for ( var y in consumos ) {

                        consumos[y].CONSUMO_QUANTIDADE = parseFloat(consumos[y].CONSUMO) * parseFloat(clone_item.QUANTIDADE_PROGRAMADA); 

                        for ( var i in produtos_estoque ) { 
                            
                            if ( produtos_estoque[i].PRODUTO_ID == consumos[y].PRODUTO_ID ) {
                                var produtos_estoque_disponivel = parseFloat(produtos_estoque[i].DISPONIVEL);
                                
                                if ( consumos[y].PROGRAMAR_SEM_ESTOQUE == 0 && produtos_estoque_disponivel < consumos[y].CONSUMO_QUANTIDADE )
                                throw 'Não há estoque de matéria-prima disponível para esta quantidade';
                            }
                        }
                    }
                     
                    clone_item.GP_ID              = vm.Estacao.selected.GP_ID;
                    clone_item.GP_DESCRICAO       = vm.Estacao.selected.GP_DESCRICAO;
                    clone_item.UP_ID              = vm.Estacao.selected.UP_ID;
                    clone_item.UP_DESCRICAO       = vm.Estacao.selected.UP_DESCRICAO;
                    clone_item.ESTACAO            = vm.Estacao.selected.ESTACAO;
                    clone_item.ESTACAO_DESCRICAO  = vm.Estacao.selected.ESTACAO_DESCRICAO;
                             
                    
                    estacao_update = true;
                    
                    /* Insere o item programado na estação */
                    vm.Estacao.selected.itens_programados.push(clone_item);
                    
                    item.QUANTIDADE_PROGRAMADA = parseFloat(item.QUANTIDADE_PROGRAMADA) - parseFloat(clone_item.QUANTIDADE_PROGRAMADA);
                    
                    vm.Agrupamento.Utilizado(item);
                    vm.Consumo.Disponibilidade(item);
                    vm.Estacao.Totalizador(vm.Estacao.selected);
                    vm.Ferramenta.Utilizado(vm.Ferramenta.selected);

                }
                catch(err) {
                    //showErro(err + ' Operação cancelada!'); 
                }
            },
            Excluir : function (estacao,item) {
                try { 

                    var item_index = estacao.itens_programados.indexOf(item);
                    var estacao_item_programado = estacao.itens_programados[item_index];
                    var agrupamento_item = vm.dados.agrupamento_itens[estacao_item_programado.INDEX_AGRUPAMENTO_ORIGEM];
                    var linhas = vm.dados.agrupamento_linhas;

                    agrupamento_item.QUANTIDADE_PROGRAMADA = parseFloat(agrupamento_item.QUANTIDADE_PROGRAMADA) + parseFloat(estacao_item_programado.QUANTIDADE_PROGRAMADA);

                    estacao_update = true;
                    
                    estacao.itens_programados.splice(item_index, 1);
                    

                    FerramentaAlocacao(vm.dados.ferramentas);
                    
                    $timeout(function(){
                        $scope.$apply(function(){
                            for(var i in linhas)
                            {
                                if (agrupamento_item.LINHA_ID == linhas[i].LINHA_ID && agrupamento_item.TAMANHO == linhas[i].TAMANHO) {
                                    vm.Linha.Select(linhas[i]);
                                    break;
                                }
                            }  

                            vm.Agrupamento.Select(agrupamento_item);  
                        });
                    },10);
                }
                catch(err) {
                    showErro(err + ' Operação cancelada!'); 
                }
            },
            Watches : function() {
                $scope.$on('bs-init', function(ngRepeatFinishedEvent) {
                    bootstrapInit();
                });
            }
        };
        
        $scope.$watch('vm.filtro.data_remessa', function (newValue, oldValue, scope) {
            if ( newValue != null ) {
                var data = moment(newValue);
                var dia_semana = data.weekday();
                if ( dia_semana == 1 ) {
                    vm.filtro.data_disponibilidade = data.subtract(2, 'day').toDate();
                } else {
                    vm.filtro.data_disponibilidade = newValue; 
                }
                    
            }            
        });
        

        vm.PedidoDesbloqueio = function (usuario) {
            $ajax.post('/_22100/pedidos-desbloqueio/post',{USUARIO:usuario}).then(function(response){
                vm.PEDIDO_BLOQUEIO_USUARIOS = response;
            });            
        };

        function boot() {
            
            $ajax.get('/_22100/pedido-bloqueio-usuario').then(function(response){
                vm.PEDIDO_BLOQUEIO_USUARIOS = response;
            });
        
            // $('.container-ferramenta .table-ferramenta')
            $('.recebe-puxador-ferramenta')
                .resizable({
                    resize  : function( event, ui ) {
                        $scope.$apply(function(){
                            $(document).resize();
                        });

                    },
                    handles  : 's',
                    minHeight : 114
                })
            ;
        }
        
        boot();
        vm.Item.Watches();
    };

    Ctrl.$inject = [
        '$scope',
        '$ajax',
        '$timeout',
        '$filter',
        '$window',
        '$q',
        'gcCollection',
        'gcObject'
    ];

    var validMaxValue = function() {
        return {
            require: 'ngModel',
            restrict: 'A',
            scope: { numberFixed:'=' },
            link: function($scope, element, attrs, ngModel) {
                $(document).ready(function() {
                    $(element).change(function () { //bind the change event to hidden input
                        if ( ngModel.$invalid ) {
                            var max_value = parseFloat(attrs.ngMax);
                            var new_value = parseFloat(ngModel.$viewValue);

                            if ( new_value > max_value ) {
                                element.val(max_value);
                                $scope.$apply(function () {
                                    ngModel.$setViewValue( max_value );
                                });
                            }
                        }
                    });
                });
            }
        };
    };
                    
        
/*
        <gc-search
            name="Agrupamento de Pedidos"
            label="Agrupamento"
            required="true"
            columns="[
                {
                    name: 'Company',
                    field: 'COMPANY',
                    width: '100px'
                },
                {
                    name: 'Contact',
                    field: 'CONTACT',
                    width: '50px'
                },
                {
                    name: 'Country',
                    field: 'COUNTRY',
                    width: '50px'
                }
            ]"
            rows="[
                {
                    COMPANY: 'delfa',
                    CONTACT: 'manoel',
                    COUNTRY: 'br',
                    OUTRO: 'teste3'
                },
                {
                    COMPANY: 'empresa',
                    CONTACT: 'emerson',
                    COUNTRY: 'us',
                    OUTRO: 'teste2'
                },
                {
                    COMPANY: 'local',
                    CONTACT: 'alexandre',
                    COUNTRY: 'br',
                    OUTRO: 'teste4'
                },
                {
                    COMPANY: 'lugar',
                    CONTACT: 'anderson',
                    COUNTRY: 'us',
                    OUTRO: 'teste5'
                },
                {
                    COMPANY: 'lugar',
                    CONTACT: 'anderson',
                    COUNTRY: 'br',
                    OUTRO: 'teste7'
                }                
            ]"
        ></gc-search>
        
    var gcSearch = {
        bindings: {
            name: '@',
            label: '@',
            columns: '=',
            rows: '='
        },
        controller : ['$scope','$timeout', function($scope,$timeout) {
            
            var vm = this;
            
            vm.fields_filter = [];
            
            vm.fieldsFilter = function() {
                for ( var i in vm.columns ) {
                    vm.fields_filter.push(vm.columns[i].field);
                }
            };
            
            
            vm.showModal = function ($event) {
                
                // Verifica se a tecla pressionada foi 'F4' 
                if ( $event.keyCode === 115 ) {
                    vm.fieldsFilter();
                    $('#model-search').modal();

                    $event.preventDefault();
                    return false;
                };
            };
            
            vm.fixVsRepeat = function () {
                $timeout(function(){
                    $('#model-search .scroll-table').scrollTop(0);
                });  
            };
            
                                
            $('#model-search').on('shown.bs.modal', function() {
                $('#model-search').find('input[type="search"]').focus();
                $timeout(function () {
                    $scope.$apply(function(){
                        $(document).resize();
                    });
                });
            });
        }],
        templateUrl: '/gc-search' 
    };
    
    */

    angular
    .module    ('app'           , ['angular.filter','vs-repeat','gc-ajax','gc-form','gc-find','gc-transform', 'gc-utils'])
    .directive ('validMaxValue' , validMaxValue )
//    .component ('gcSearch'     , gcSearch      )
    .controller('Ctrl'          , Ctrl          );
        
})(angular);


;(function($) {
        
	$(function() {    
        
        $('#btn-table-filter').click(function(){
            $('#filtrar-toggle').click();
        });
        
//        var scrollTimeout = null;
//        $('.linha.scroll-table, .agrupamento.scroll-table')
//            .on('scroll', function(event) {
//                var that = $(this);
//                if (scrollTimeout) clearTimeout(scrollTimeout);
//                scrollTimeout = setTimeout(function(){
//                    that.find('input:focus').closest('tr').click();
//                },50);
//            })
//        ;
        
        $(document).on('click', 'tr', function() {
            var input = $(this).find('input[type="number"]');
//            input.focus();
            input.select();
        });
        
		$(document)
            .on('keydown', 
                'table tbody tr', 
                'up', 
                function(e) {  
                    
                    var tr = $(this);
                    var scrollTimeout = null;
                    if (scrollTimeout) clearTimeout(scrollTimeout);
                    scrollTimeout = setTimeout(function(){
                        $('tr input:focus').closest('tr').click();
                    },10);
                    
                    return false;
                }
            )
            .on('keydown', 
                'table tbody tr',
                'down',
                function(e) {

                    var scrollTimeout = null;
                    if (scrollTimeout) clearTimeout(scrollTimeout);
                    scrollTimeout = setTimeout(function(){
                        $('tr input:focus').closest('tr').click();
                    },10);

                    return false;
                }
            )
            .on('keydown', 
                'body', 
                'f1', 
                function(e) {  
                    $('.linha-container .linha')
                        .find('tr.selected')
                        .find('button')
                        .click()
                    ;
                    
//                    return false;
                }
            )
            .keypress(
                function(event) { 
                    var keycode = event.keyCode || event.which;
                    if(keycode == '13') {
                        $('.linha-container .linha')
                            .find('tr.selected')
                            .find('button')
                            .click()
                        ;

                        return false;
                    }
                }
            )
            .on('keydown', 
                'body', 
                '*', 
                function(e) {  
                    $('.check.agrupamento')
                        .click()
                    ;
                    
                    return false;
                }
            )
            .on('keydown', 
                'body', 
                '+', 
                function(e) {  
                    $('.agrupamento.scroll-table')
                        .find('.tr-agrupamento.selected')
                        .find('td.check')
                        .click()
                    ;
                    
                    return false;
                }
            )
        ;
	});
})(jQuery);

//# sourceMappingURL=_22100.js.map
