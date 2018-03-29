/**
 * _11140 - Cadastro de paineis de Casos
 */
'use strict';

angular
	.module('app', [
		'vs-repeat', 
		'gc-ajax',
		'gc-transform',
		'gc-form',
		'gc-utils',
		'gc-find'
	]);
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
        '$ajax',
        '$scope',
        '$window',
        '$timeout',
        'gScope',
        '$consulta',
        '$rootScope',
        '$sce'
    ];

    function Ctrl($ajax, $scope, $window, $timeout, gScope, $consulta,$rootScope, $sce) {

        var vm = this;

        $scope.trustAsHtml = function(string) {
            return $sce.trustAsHtml(string);
        };

        vm.filtroItens  = '';
        vm.Consulta     = new $consulta();
        gScope.Consulta = vm.Consulta;
        vm.ordem        = '-NUMERO_NOTAFISCAL';

        vm.Etiqueta = {
            Modelos:[]
        };

        function dataInicio(){
            var data = new Date();
            var dia = data.getDate();
            if (dia.toString().length == 1)
              dia = "0"+dia;
            var mes = data.getMonth()+1;
            if (mes.toString().length == 1)
              mes = "0"+mes;
            var ano = data.getFullYear();  
            return mes+"/01/"+ano;
        }

        function dataFim(){
            var data = new Date();
            var dia = data.getDate();
            if (dia.toString().length == 1)
              dia = "0"+dia;
            var mes = data.getMonth()+1;
            if (mes.toString().length == 1)
              mes = "0"+mes;
            var ano = data.getFullYear();  
            return mes+"/"+dia+"/"+ano;
        }

        vm.TratarOrdem = function(filtro){
            if(vm.ordem == filtro){
                vm.ordem = '-'+filtro;
            }else{
                vm.ordem = filtro;
            }
        };

        vm.DataInicio = moment(dataInicio()).toDate();
        vm.DataFim    = moment(dataFim()).toDate();
        vm.NOTAS      = [];
        vm.NUMERO_NOTA = 0;
        vm.SERIE_NOTA  = 0;
        vm.PEDIDO      = 0;

        vm.NOTA = {
            ITENS : [],
            INFO  : [],
            XML   : {VER: false},
            PDF   : {VER: false}
        };

        vm.Consulta_Representante  = vm.Consulta.getNew();
        vm.Consulta_Cliente        = vm.Consulta.getNew();

        vm.Consulta_Representante.componente             = '.Consulta_Representante',
        vm.Consulta_Representante.model                  = 'vm.Consulta_Representante',
        vm.Consulta_Representante.option.label_descricao = 'Representante:',
        vm.Consulta_Representante.option.obj_consulta    = '/_12100/consultarRepresentante',
        vm.Consulta_Representante.option.tamanho_input   = 'input-maior';
        vm.Consulta_Representante.option.class           = 'Consulta_Representante';
        vm.Consulta_Representante.option.tamanho_tabela  = 500;
        vm.Consulta_Representante.option.obj_ret         = ['ID','DESCRICAO'];
        vm.Consulta_Representante.option.campos_tabela   = [['ID','ID'],['UF','UF'],['DESCRICAO','RAZÃO SOCIAL']];

        vm.Consulta_Cliente.componente             = '.Consulta_Cliente',
        vm.Consulta_Cliente.model                  = 'vm.Consulta_Cliente',
        vm.Consulta_Cliente.option.label_descricao = 'Cliente:',
        vm.Consulta_Cliente.option.obj_consulta    = '/_12100/consultarCliente',
        vm.Consulta_Cliente.option.tamanho_input   = 'input-maior';
        vm.Consulta_Cliente.option.class           = 'Consulta_Cliente';
        vm.Consulta_Cliente.option.tamanho_tabela  = 600;
        vm.Consulta_Cliente.option.obj_ret         = ['ID','DESCRICAO'];
        vm.Consulta_Cliente.option.campos_tabela   = [['ID','ID'],['NOMEFANTASIA','NOME FANTASIA'],['DESCRICAO','CLIENTE']];

        vm.Consulta_Representante.compile();
        vm.Consulta_Cliente.compile();

        vm.Consulta_Cliente.require    = vm.Consulta_Representante;
        vm.Consulta_Cliente.vincular();

        vm.ngWinPopUp = function (url,id,params) {
            winPopUp(url,id,params);
        };


        vm.Etiqueta.MudarMarcado = function(item){
            if(item.MARCADO == 1){
                item.MARCADO = 0;
            }else{
                item.MARCADO = 1;
            }
        };

        vm.Etiqueta.MarcarTodos = function(item){
            angular.forEach(vm.Etiqueta.NOTA, function(item, key) {
                item.MARCADO = 1;
            });

            vm.Etiqueta.ValidarImprimir();
        };

        vm.Etiqueta.DesmarcarTodos = function(item){
            angular.forEach(vm.Etiqueta.NOTA, function(item, key) {
                item.MARCADO = 0;
            });

            vm.Etiqueta.ValidarImprimir();
        };        

        vm.Etiqueta.AbilitarImprimir = 0;

        vm.Etiqueta.ValidarImprimir = function(){
            vm.Etiqueta.AbilitarImprimir = 0;

            var v1 = 0;
            var v2 = 0;

            angular.forEach(vm.Etiqueta.Modelos, function(item, key) {
                if(vm.Etiqueta.SELECT == item){
                    v1 = 1;
                }
            });

            angular.forEach(vm.Etiqueta.NOTA, function(item, key) {
                if(item.MARCADO == 1 && item.VOLUMES1 > 0 && item.VOLUMES1 <= item.VOLUMES2){
                    v2 = 1;
                }
            });

            if(v1 == 1 && v2 == 1){
                vm.Etiqueta.AbilitarImprimir = 1;
            }
        }

        vm.Etiqueta.Imprimir = function(modelo){

            modelo = vm.Etiqueta.SELECT;

            var dados = {
                NOTA_FISCAL: vm.NOTA
            };

            String.prototype.replaceAll = String.prototype.replaceAll || function(needle, replacement) {
                return this.split(needle).join(replacement);
            };

            $ajax.post('/_12100/DadosEtiqueta', dados)
                .then(function(response) {

                    var string = '';
                    var script = modelo.SCRIPT;
                    var produto_item = null;
                    vm.Etiqueta.Dados = response;

                    angular.forEach(vm.Etiqueta.Dados, function(nota, key) {

                        produto_item = null;

                        angular.forEach(vm.Etiqueta.NOTA, function(item, key) {
                            if(item.ID == nota.ID && item.MARCADO == 1){
                                produto_item = item;
                            }
                        });

                        if(produto_item != null){

                            if(nota.MULTIPLO       == 0){nota.MULTIPLO = 1;}
                            if(nota.COTA_EMBALAGEM == 0){nota.COTA_EMBALAGEM = 1;}
                            if(nota.COTA_EMBALAGEM == 0){nota.COTA_EMBALAGEM = 1;}

                            nota.MULTIPLO       = Number(nota.MULTIPLO);
                            nota.COTA_EMBALAGEM = Number(nota.COTA_EMBALAGEM);
                            nota.PESO_BRUTO     = Number(nota.PESO_BRUTO);
                            nota.PESO_LIQUIDO   = Number(nota.PESO_LIQUIDO);
                            nota.QUANTIDADE     = Number(produto_item.VOLUMES1) * nota.COTA_EMBALAGEM;

                            nota.QUANTIDADE_SUBEMBALAGEM = nota.COTA_EMBALAGEM  / nota.MULTIPLO;
                            nota.VOLUMES                 = nota.QUANTIDADE      / nota.COTA_EMBALAGEM ; 
                            nota.PESO_BRUTO_VOLUME       = nota.PESO_BRUTO      / produto_item.VOLUMES2;
                            nota.PESO_LIQUIDO_VOLUME     = nota.PESO_LIQUIDO    / produto_item.VOLUMES2;
                            nota.VOLUMES2                = Number(produto_item.VOLUMES1) * (nota.VOLUMES2 / Number(produto_item.VOLUMES2));

                            nota.MULTIPLO                = nota.MULTIPLO.toLocaleString('pt-BR');
                            nota.COTA_EMBALAGEM          = nota.COTA_EMBALAGEM.toLocaleString('pt-BR');
                            nota.PESO_BRUTO              = nota.PESO_BRUTO.toLocaleString('pt-BR');
                            nota.PESO_LIQUIDO            = nota.PESO_LIQUIDO.toLocaleString('pt-BR');
                            nota.QUANTIDADE              = nota.QUANTIDADE.toLocaleString('pt-BR');
                            nota.VOLUMES                 = nota.VOLUMES.toLocaleString('pt-BR');
                            nota.VOLUMES2                = nota.VOLUMES2.toLocaleString('pt-BR');
                            nota.PESO_BRUTO_VOLUME       = nota.PESO_BRUTO_VOLUME.toLocaleString('pt-BR');
                            nota.PESO_LIQUIDO_VOLUME     = nota.PESO_LIQUIDO_VOLUME.toLocaleString('pt-BR');
                            nota.QUANTIDADE_SUBEMBALAGEM = nota.QUANTIDADE_SUBEMBALAGEM.toLocaleString('pt-BR');

                            var str_temp = script + '';

                            str_temp = str_temp.replaceAll('#NR_ETIQUETAS#',           '1');
                            str_temp = str_temp.replaceAll('#NUMERO_NOTAFISCAL#',      nota.NUMERO_NOTAFISCAL);      
                            str_temp = str_temp.replaceAll('#DATA_EMISSAO#',           nota.DATA_EMISSAO);
                            str_temp = str_temp.replaceAll('#PEDIDO#',                 nota.PEDIDO);
                            str_temp = str_temp.replaceAll('#PEDIDO_CLIENTE#',         nota.PEDIDO_CLIENTE);
                            str_temp = str_temp.replaceAll('#PRODUTO_ID#',             nota.PRODUTO_ID);
                            str_temp = str_temp.replaceAll('#MODELO_ID#',              nota.MODELO_ID);
                            str_temp = str_temp.replaceAll('#MODELO_DESCRICAO#',       nota.MODELO_DESCRICAO);
                            str_temp = str_temp.replaceAll('#DESCRICAO_NF#',           nota.DESCRICAO_NF);
                            str_temp = str_temp.replaceAll('#COR_ID#',                 nota.COR_ID);
                            str_temp = str_temp.replaceAll('#COR_DESCRICAO#',          nota.COR_DESCRICAO);
                            str_temp = str_temp.replaceAll('#TAMANHO_ID#',             nota.TAMANHO_ID);
                            str_temp = str_temp.replaceAll('#TAMANHO#',                nota.TAMANHO);
                            str_temp = str_temp.replaceAll('#CODIGO_EDI#',             nota.CODIGO_EDI);
                            str_temp = str_temp.replaceAll('#MULTIPLO#',               nota.MULTIPLO);
                            str_temp = str_temp.replaceAll('#COTA_EMBALAGEM#',         nota.COTA_EMBALAGEM);
                            str_temp = str_temp.replaceAll('#QUANTIDADE#',             nota.QUANTIDADE);
                            str_temp = str_temp.replaceAll('#PESO_LIQUIDO#',           nota.PESO_LIQUIDO);
                            str_temp = str_temp.replaceAll('#PESO_BRUTO#',             nota.PESO_BRUTO);
                            str_temp = str_temp.replaceAll('#MEDIDAS#',                nota.MEDIDAS);
                            str_temp = str_temp.replaceAll('#QUANTIDADE_SUBEMBALAGEM#',nota.QUANTIDADE_SUBEMBALAGEM);
                            str_temp = str_temp.replaceAll('#VOLUMES#',                nota.VOLUMES);
                            str_temp = str_temp.replaceAll('#PESO_BRUTO_VOLUME#',      nota.PESO_BRUTO_VOLUME);
                            str_temp = str_temp.replaceAll('#PESO_LIQUIDO_VOLUME#',    nota.PESO_LIQUIDO_VOLUME);
                            str_temp = str_temp.replaceAll('#VOLUMES_SUBEMBALAGEM#',   nota.VOLUMES2);

                            string = string + str_temp;
                        }
                    });

                    if(string != ''){
                        postprint(string);
                    }else{
                        showErro('Sem dados para impressão');
                    }
                }
            );
        };

        vm.Acoes = {
            imprimir : function(){
                var valido = true;
                var cliente = '' ;
                var representante = '' ;

                if(vm.Consulta_Cliente.item.selected == true){
                    cliente = 'Cli.: ' + vm.Consulta_Cliente.item.dados.ID + '-' + vm.Consulta_Cliente.item.dados.DESCRICAO + '    ' ;
                }

                if(vm.Consulta_Representante.item.selected == true){
                    representante = 'Rep.: ' + vm.Consulta_Representante.item.dados.ID + '-' + vm.Consulta_Representante.item.dados.DESCRICAO + '    ' ;
                }

                var user = $('#usuario-descricao').val();
                var filtro = cliente + representante + ' Data:' + moment(vm.DataInicio).format('L') +' a '+ moment(vm.DataFim).format('L');
                printHtml('container-de-notas', 'Relatório de Notas Fiscais', filtro, user, '1.0.0',1,'');

            },
            export1 : function(){
                exportTableToCsv('notas.csv', 'itens-de-notas');
            },
            export2 : function(){
                exportTableToXls('notas.xls', 'tabela-de-notas');
            },
            ModalEtiqueta: function(){
                var dados = {
                    NOTA_FISCAL: vm.NOTA
                };

                $ajax.post('/_12100/modeloEtiqueta', dados)
                    .then(function(response) {

                        vm.Etiqueta.Modelos = response;

                        vm.Etiqueta.NOTA = angular.copy(vm.NOTA.ITENS);

                        console.log(vm.NOTA.ITENS);

                        angular.forEach(vm.Etiqueta.NOTA, function(item, key) {
                            item.MARCADO = 1;

                            var qtd = (item.VOLUMES + '').replace(".", "");
                            qtd = qtd.replace(".", "");
                            qtd = qtd.replace(",", ".");
                            item.VOLUMES2 = Number(qtd);
                            item.VOLUMES1 = Number(qtd);
                        });

                        $('#modal-etiqueta').modal();
                    }
                );
            },
            BaixarNota: function(){
                var dados = {
                    FILE: vm.NOTA.XML.BINARIO,
                    NOME: vm.NOTA.XML.NOME,
                    DIR : vm.NOTA.XML.DIR,
                    CAMINHO : vm.NOTA.XML.CAMINHO
                };

                $ajax.post('/_12100/pdf', dados)
                    .then(function(response) {
                        vm.NOTA.PDF.NOME = '';
                        vm.NOTA.PDF.VER  = true;
                        $('.conteudoPDF').html(response);

                        setTimeout(function(){
                           var caminho = $('._caminhoPDF').val();
                           $('.pdf-ver').find('.download-arquivo').attr('href', caminho );
                        },300); 
                    }
                );
            },
            BaixarXML: function(){

            },
            tratarPedidosNota: function(){
                var cliente = vm.Consulta_Cliente.item.dados.ID;
                var representante = vm.Consulta_Representante.item.dados.ID;
                var link = '';

                angular.forEach(vm.NOTAS, function(nota, key) {
                    link = '';
                    var pedidos = (nota.PEDIDO + '').split(", ");
                    var representante = nota.REPRESENTANTE_CODIGO
                    var cliente = nota.CLIENTE_CODIGO

                    angular.forEach(pedidos, function(pedido, index) {
                        if(pedido > 0){
                            link = link + '<a href="'+urlhost+'/_12040?pedidoId='+pedido+'&clienteId='+cliente+'&representanteId='+representante+'" target="_blank" >'+pedido+'</a>,';
                        }
                    });

                    if(link.length > 0){
                        nota.PEDIDO = link.substring(0, link.length -1);
                    }
                });
                
            },
            tratarPedidosItens: function(){
                var cliente = vm.Consulta_Cliente.item.dados.ID;
                var representante = vm.Consulta_Representante.item.dados.ID;
                var link = '';

                angular.forEach(vm.NOTA.ITENS, function(nota, key) {
                    link = '';
                    var pedidos = (nota.PEDIDO + '').split(", ");
                    var representante = nota.REPRESENTANTE_CODIGO
                    var cliente = nota.CLIENTE_CODIGO

                    angular.forEach(pedidos, function(pedido, index) {
                        if(pedido > 0){
                            link = link + '<a href="'+urlhost+'/_12040?pedidoId='+pedido+'&clienteId='+cliente+'&representanteId='+representante+'" target="_blank" >'+pedido+'</a>,';
                        }
                    });

                    if(link.length > 0){
                        nota.PEDIDO = link.substring(0, link.length -1);
                    }
                });
                
            },
            consultarNotas: function(flag){
                var that = this;
                var valido = 0;

                if( vm.Consulta_Cliente.item.selected == false){
                    //vm.Consulta_Cliente.setErro();
                    //valido = 1;
                }

                if( vm.Consulta_Representante.item.selected == false){
                    //vm.Consulta_Representante.setErro();
                    //valido = 1
                }

                if(valido == 0){

                    var dados = {
                        DATA_NICIO: vm.DataInicio,
                        DATA_FIM  : vm.DataFim,
                        CLIENTE   : vm.Consulta_Cliente.item.dados.ID,
                        NOTA      : vm.NUMERO_NOTA,
                        SERIE     : vm.SERIE_NOTA,
                        PEDIDO    : vm.PEDIDO,
                        FLAG      : flag
                    };

                    $ajax.post('/_12100/consultarNotas', dados)
                        .then(function(response) {
                            vm.NOTAS =  response.RETORNO;

                            that.listaFocus = 0;
                            //vm.NUMERO_NOTA  = 0;
                            //vm.SERIE_NOTA   = 0;
                            //vm.PEDIDO       = 0;

                            that.tratarPedidosNota();

                            setTimeout(function(){

                                var linhas = $('tr');

                                if($(linhas).length > 1){
                                    $(linhas[1]).focus();

                                    if(response.RETORNO.length == 1){
                                       var td = $(linhas[1]).find('td');
                                        $(td[0]).trigger('click');
                                    }
                                }
                                
                            },300);     
                        }
                    );
                }
            },
            abrirNota: function(nota,$event){

                var that = this;

                var dados = {
                        ID : nota.ID
                    };

                $ajax.post('/_12100/consultarItens', dados)
                    .then(function(response) {
                        vm.NOTA.ITENS =  response.RETORNO;
                        vm.NOTA.XML   =  response.XML;

                        vm.NOTA.XML.VER = false;

                        that.tratarPedidosItens();

                        vm.NOTA.INFO  = angular.copy(nota);
                        $('#modal-nota').modal();   
                    },function(e){
                        console.log(e);
                    }
                );

            },

            time  : null,
            class : '.tabela-nota',
            listaFocus: 0,
            listaKeydown: function($event){
                var that = this;

                if($event.keyCode == 40){
                    clearTimeout(that.time);
                    var itens = $(that.class).find('.lista-itens');
                    
                    that.listaFocus = that.listaFocus + 1;

                    if(itens.length >= that.listaFocus){
                        $(itens[that.listaFocus - 1]).focus();
                    }

                    if(that.listaFocus > itens.length){
                        that.listaFocus = itens.length;
                    }                
                }

                if($event.keyCode == 38){
                    var itens = $(that.class).find('.lista-itens');
                    
                    that.listaFocus = that.listaFocus - 1;

                    if(itens.length >= that.listaFocus && that.listaFocus > 0){
                        clearTimeout(that.time);
                        $(itens[that.listaFocus - 1]).focus();
                    }

                    if(that.listaFocus < 0){

                        that.listaFocus = 1;
                    }         
                }
            },
            keypress : function(nota, $event){
                var that = this;
                if($event.keyCode == 13){
                   that.abrirNota(nota); 
                }
            },
            btnVoltar: function(){
                $('#modal-nota').modal('hide');
            }
        };

        function init(){

            var representante = $('._representante').val();
            var representante2= $('._representante2').val();
            var cliente       = $('._cliente').val();
            var nota          = $('._nota').val();
            var serie         = $('._serie').val();
            var pedido        = $('._pedido').val();

            vm.NUMERO_NOTA = nota; 
            vm.SERIE_NOTA  = serie; 
            vm.PEDIDO      = pedido; 

            vm.Consulta_Representante.option.paran = {REPRESENTANTE_CODIGO : representante};
            vm.Consulta_Cliente.option.paran = {CLIENTE_CODIGO : cliente};

            if(representante > 0){
                vm.Consulta_Representante.filtrar();
            }else{
                if(representante2 > 0){
                    vm.Consulta_Representante.filtrar();
                }
            }

            vm.Consulta_Cliente.onFilter = function(){
                vm.Consulta_Cliente.option.paran = {CLIENTE_CODIGO : 0};
            }

            vm.Consulta_Representante.onFilter = function(){
                vm.Consulta_Representante.option.paran = {REPRESENTANTE_CODIGO : 0};
            }

            vm.Consulta_Cliente.onSelect = function(){

                if(representante > 0 && cliente > 0){
                    vm.Acoes.consultarNotas(0);
                }

                vm.Consulta_Cliente.onSelect = function(){
                    if(representante > 0 && cliente > 0){
                        vm.Acoes.consultarNotas(1);
                    }
                };

            };

            vm.Consulta_Cliente.onClear = function(){
                
                var linhas = $('.notas-itens-linha');

                if($(linhas).length > 0){
                    $(linhas).each(function( index ) {
                        $(this).remove();
                    });
                }

            };

        }

        init();
	}
      
    
//# sourceMappingURL=_12100.app.js.map
