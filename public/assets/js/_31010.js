'use strict';

angular
	.module('app', [
		'vs-repeat', 
        'gc-find',
		'gc-ajax',
		'gc-transform',
		'gc-form',
		'gc-utils'
	])
;
     
angular
    .module('app')
    .factory('Modelo', Modelo);
    

	Modelo.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope',
        '$consulta'
    ];

function Modelo($ajax, $httpParamSerializer, $rootScope, $q, $timeout, gcCollection, gScope, $consulta) {

    /**
     * Constructor, with class name
     */
    function Modelo(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Modelo = this; 
        
        this.ITENS = [];
        this.FILTRO = '';
        this.ORDEM = 'MODELO_DESCRICAO';
        this.SELECTED = [];
        this.TIME = [];
        this.Perfil = [];

        this.Consulta   = new $consulta();
        
        this.ConsultaModelo = this.Consulta.getNew();
            
        this.ConsultaModelo.componente                  = '.consulta-modelo';
        this.ConsultaModelo.option.class                = 'modeloctrl';
        this.ConsultaModelo.model                       = 'vm.Modelo.ConsultaModelo';
        this.ConsultaModelo.option.label_descricao      = 'Modelo:';
        this.ConsultaModelo.option.obj_consulta         = '/_31010/Consultar';
        this.ConsultaModelo.option.tamanho_input        = 'input-maior';
        this.ConsultaModelo.option.tamanho_tabela       = 427;
        this.ConsultaModelo.compile();

    }
    
    Modelo.prototype.open = function(item) {
        var that = this;

        $('#modal-sku').modal();
    }

    Modelo.prototype.close = function() {
        var that = this;

        $('#modal-sku').modal('hide');
    }

    Modelo.prototype.ConsultarPerfil = function() {
        var that = this;

        var ds = {
                MODELO  : gScope.Modelo.ConsultaModelo.selected,
                COR     : gScope.Cor.SELECTED,
                TAMANHO : gScope.Tamanho.SELECTED
            };

        $ajax.post('/_31010/ConsultarPerfil',ds,{contentType: 'application/json'})
            .then(function(response) {
                that.Perfil = response; 
     
            }
        );
    }

    Modelo.prototype.consultar = function() {
        var that = this;

        var ds = {
                ID : 0
            };

        $ajax.post('/_31010/Consultar',ds,{contentType: 'application/json'})
            .then(function(response) {
                that.ITENS = response;

                var grupos = gcCollection.groupBy(response, [
                    'MODELO_CODIGO',
                    'MODELO_DESCRICAO'
                ], 'COR'); 
                
                gcCollection.merge(that.ITENS, grupos, ['COR_CODIGO', 'COR_DESCRICAO']);             
            }
        );
    }

    Modelo.prototype.Selectionar = function (modelo) {
        var that = this;

        if(that.SELECTED.MODELO_CODIGO != modelo.MODELO_CODIGO){
            that.SELECTED = modelo; 
        }
        
    }

    /**
     * Return the constructor function
     */
    return Modelo;
};
angular
    .module('app')
    .factory('Cor', Cor);
    

	Cor.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Cor($ajax, $httpParamSerializer, $rootScope, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Cor(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Cor = this; 
        
        this.ITENS = [];
        this.FILTRO = '';
        this.ORDEM = 'DESCRICAO';
        this.SELECTED = [];
    }

    Cor.prototype.consultar = function() {
        var that = this;

        var cor = gScope.Modelo.ConsultaModelo.selected.COR_ID;

        var ds  = {
                MODELO : gScope.Modelo.ConsultaModelo.selected
            };

        $ajax.post('/_31010/ConsultarCor',ds,{contentType: 'application/json'})
            .then(function(response) {
                that.ITENS = response;

                angular.forEach(that.ITENS, function(item, key) {
                    item.PADRAO = 0;
                    
                    if( parseInt(item.ID) == parseInt(cor)){
                        item.PADRAO = 1;
                        that.SELECTED = item;

                        setTimeout(function(){
                            $('.item_modelo_'+item.ID).focus();
                        },300);
                    }
                });               
            }
        );
    }

    Cor.prototype.Selectionar = function (cor) {
        var that = this;

        if(that.SELECTED != cor){
            that.SELECTED = cor;
        }
        
    }

    /**
     * Return the constructor function
     */
    return Cor;
};
angular
    .module('app')
    .factory('Tamanho', Tamanho);
    

	Tamanho.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Tamanho($ajax, $httpParamSerializer, $rootScope, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Tamanho(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Tamanho = this; 
        
        this.ITENS = [];
        this.FILTRO = '';
        this.ORDEM = 'DESCRICAO';
        this.SELECTED = [];
    }

    Tamanho.prototype.consultar = function() {
        var that = this;

        var tamanho = gScope.Modelo.ConsultaModelo.selected.TAMANHO;

        var ds = {
                MODELO : gScope.Modelo.ConsultaModelo.selected
            };

        $ajax.post('/_31010/ConsultarTamanho',ds,{contentType: 'application/json'})
            .then(function(response) {
                that.ITENS = response;  

                angular.forEach(that.ITENS, function(item, key) {
                    item.PADRAO = 0;
                    
                    if( parseInt(item.ID) == parseInt(tamanho)){
                        item.PADRAO = 1;
                        that.SELECTED = item;

                        setTimeout(function(){
                            $('.item_tamanho_'+item.ID).focus();
                        },300);
                    }
                });              
            }
        );
    }

    Tamanho.prototype.Selectionar = function (Tamanho) {
        var that = this;

        if(that.SELECTED != Tamanho){
            that.SELECTED = Tamanho;
        }
        
    }

    /**
     * Return the constructor function
     */
    return Tamanho;
};
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
angular
    .module('app')
    .factory('Filtro', Filtro);
    

	Filtro.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Filtro($ajax, $httpParamSerializer, $rootScope, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Filtro = this; 
        
        this.CODIGO_BARRAS = '';
    }
    
    Filtro.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve,reject){

            $ajax.post('/_15090/api/conferencia/itens',that).then(function(response){

                that.merge(response);

                resolve(response);
            },function(){
                reject(reject);
            });
        });
    };
   
    
    Filtro.prototype.merge = function(response) {
        
        function isNumber(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }

        for ( var i in response ) {
            var item = response[i];
            
            for (var k in item){
                if (item.hasOwnProperty(k)) {
                    
                    if ( isNumber(item[k]) && (String(item[k]).substr(0, 1) !== '0' || String(item[k]).indexOf('.') !== -1) ) {               
                        item[k] = parseFloat(item[k]);
                    }
                }
            }            
        }
        
        gcCollection.merge(gScope.Conferencia.DADOS, response, [
            'PRODUTO_ID',
            'TAMANHO',
            'PECA_ID'
        ]);

        for ( var i in gScope.Conferencia.DADOS ) {
            var item = gScope.Conferencia.DADOS[i];
            
            item.CONFERIR = item.CONFERENCIA;
        }

    };

    /**
     * Return the constructor function
     */
    return Filtro;
};
angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        'Modelo',
        'Cor',
        'Tamanho',
        'Ficha',
        'ItenCusto',
        'gScope',
        '$compile',
        '$consulta',
        '$ajax',
        '$q',
        '$rootScope'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        Modelo, 
        Cor,
        Tamanho,
        Ficha,
        ItenCusto,
        gScope,
        $compile,
        $consulta,
        $ajax,
        $q,
        $rootScope
    ) {

	var vm = this;

    gScope.scope = $scope;

    vm.ID = 0;
    vm.Descricao = '';

    vm.MARGEM = 100;
    gScope.MARGEM = vm.MARGEM;

    vm.ListaItens = [];
    gScope.ListaItens = vm.ListaItens;

    vm.Item       = {};
    vm.MarckUp    = 0;
    vm.MargemContribuicao = 0;
    vm.Comicao    = 0;
    vm.Total      = {
        Quantidade: 0,
        Custo:0,
        CustoT : 0,
        UnidadeMedida: '',
        Venda : 0,
        Despesa: 0
    };

    vm.FLAG_RECALCULAR = false;

    vm.PERC_FATURAMENTO = {
        VALOR : 0,
        FLAG  : 0
    };

    gScope.PERC_FATURAMENTO = vm.PERC_FATURAMENTO;

    vm.DataInvalida = true;

    gScope.Item    = vm.Item;
    gScope.Total   = vm.Total;
    gScope.MarckUp = vm.MarckUp;

    vm.Lista = {
        MODELO : [],
        COR    : [],
        TAMANHO: [],
        FICHA  : []
    };

    vm.LISTA_ANO  = [];
    vm.LISTA_MES  = [];
    vm.LISTA_ANO2 = [];
    vm.LISTA_MES2 = [];

    vm.DATA = {
        ANO : 0,
        MES : 0,
        ANO2: 0,
        MES2: 0
    };

    vm.Filtro  = new Filtro();
    vm.Modelo  = new Modelo();
    vm.Cor     = new Cor();
    vm.Tamanho = new Tamanho();
    vm.Ficha   = new Ficha();
    vm.Custo   = new ItenCusto();

    vm.Fatores = {
        Frete: {
            Tipo: 'FOB',
            Valor: 0
        },
        ConsiderarPerdas: true,
        MarckUp: 100,
    }

    gScope.Fatores = vm.Fatores;
    gScope.DATA    = vm.DATA;

    vm.Frete = {
        DADOS : [],
        CALCULADO : false,
        PERCENTUAL: 0
    };

    gScope.Frete = vm.Frete;

    vm.FRETES = [];
    vm.FRETES.push({Tipo: 'FOB', Valor: 0});
    vm.FRETES.push({Tipo: 'CIF - Rodoviário', Valor: 200});
    vm.FRETES.push({Tipo: 'CIF - Aéreo', Valor: 500});

    function init(){

        var id = $('._id_simulacao').val();

        vm.consultarIncentivo();

        vm.MontarListaMes();
        vm.MontarListaAno();
        vm.MontarListaMes2();
        vm.MontarListaAno2();

        vm.CalcularMeses();

        if(id > 0){
            vm.ID = id;

            vm.ConsultaSimular.selected.DESCRICAO = '';
            vm.ConsultaSimular.selected.ID        = id;

            vm.Simulacao().then(function(){
                
            });

        }
    }

    function pad(n, width, z) {
       z = z || '0';
       n = n + '';
       return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
    }


    vm.ReplicarTamanhos = function(item){

        var tamanhos = item.ConsultaTamanho.selected.LISTA   + '';
        var replicas = item.ConsultaTamanho.selected.REPLICA + '';

        var res = tamanhos.split(",");
        var rep = replicas.split("#@#");

        angular.forEach(rep, function(iten, key) {
            
            var res = iten.split("#$#");

            var dados = {
                REPLICA   : '',
                LISTA     : res[0],
                ID        : res[1],
                GRADE_ID  : res[2],
                DESCRICAO : res[3],
            };

            vm.Custo.getNew(true);

            var obj = gScope.ListaItens[gScope.ListaItens.length - 1];

            obj.ConsultaModelo.setSelected(  angular.copy(item.ConsultaModelo.selected)  , angular.copy(item.ConsultaModelo.Input.value)  );
            obj.ConsultaCor.setSelected(     angular.copy(item.ConsultaCor.selected)     , angular.copy(item.ConsultaCor.Input.value)     );
            obj.ConsultaTamanho.setSelected( dados , res[3]);

            obj.CALCULADO = 0;
            obj.consultarTempo(1);

        });

        //vm.RemoverItem(item);

    }

    vm.Simulacao = function(){

        return $q(function(resolve,reject){

            var ds = {
                    ID : vm.ConsultaSimular.selected.ID
                };

            $ajax.post('/_31010/Simulacao',ds,{contentType: 'application/json'})
                .then(function(response) {

                    vm.ListaItens = [];
                    gScope.ListaItens = [];

                    vm.add_confime = 1;
                    
                    if(response.PARAMETROS.length > 0){

                        var paran = response.PARAMETROS[0];

                        paran.MERCADO           = paran.MERCADO         == null ? '{}' : paran.MERCADO; 
                        paran.MERCADO_ITENS     = paran.MERCADO_ITENS   == null ? '{}' : paran.MERCADO_ITENS; 
                        paran.TRANSPORTADORA    = paran.TRANSPORTADORA  == null ? '{}' : paran.TRANSPORTADORA; 
                        paran.CLIENTE           = paran.CLIENTE         == null ? '{}' : paran.CLIENTE; 
                        paran.CIDADE            = paran.CIDADE          == null ? '{}' : paran.CIDADE; 
                        paran.FRETE             = paran.FRETES          == null ? '{}' : paran.FRETES; 
                        paran.DATAS             = paran.DATAS           == null ? '{}' : paran.DATAS; 
                        paran.FATORES           = paran.FATORES         == null ? '{}' : paran.FATORES; 
                        paran.ITENS             = paran.ITENS           == null ? '{}' : paran.ITENS; 
                        paran.MARGEM            = paran.MARGEM          == null ? 0    : Number(paran.MARGEM); 
                        paran.ID                = paran.ID              == null ? 0    : Number(paran.ID); 
                        paran.DESCRICAO         = paran.DESCRICAO       == null ? ''   : paran.DESCRICAO; 

                        var MERCADO         = JSON.parse(paran.MERCADO);
                        var MERCADO_ITENS   = JSON.parse(paran.MERCADO_ITENS);
                        var TRANSPORTADORA  = JSON.parse(paran.TRANSPORTADORA);
                        var CLIENTE         = JSON.parse(paran.CLIENTE);
                        var CIDADE          = JSON.parse(paran.CIDADE);
                        var FRETE           = JSON.parse(paran.FRETE);
                        var DATAS           = JSON.parse(paran.DATAS);
                        var FATORES         = JSON.parse(paran.FATORES);
                        var ITENS           = JSON.parse(paran.ITENS);
                        var MARGEM          = paran.MARGEM;
                        var DESCRICAO       = paran.DESCRICAO;

                        vm.ConsultaPadrao.setSelected(MERCADO);
                        vm.Consultatransportadora.setSelected(TRANSPORTADORA);
                        vm.ConsultaCliente.setSelected(CLIENTE);
                        vm.ConsultaCidade.setSelected(CIDADE);

                        vm.Descricao    = DESCRICAO;
                        vm.Frete        = FRETE;
                        vm.Fatores      = FATORES;
                        vm.DATA         = DATAS;
                        vm.PadraoItem   = MERCADO_ITENS;
                        vm.MARGEM       = MARGEM;

                        gScope.Descricao    = vm.Descricao;
                        gScope.Frete        = vm.Frete;
                        gScope.Fatores      = vm.Fatores;
                        gScope.DATA         = vm.DATA;
                        gScope.PadraoItem   = vm.PadraoItem;
                        gScope.MARGEM       = vm.MARGEM;

                        var link = encodeURI(urlhost + '/_31010?SIMULACAO_ID='+paran.ID);
                        window.history.replaceState(document.title, 'Title', link);

                        vm.ConsultaSimular.setSelected({ID: paran.ID, DESCRICAO: paran.DESCRICAO});
                    }

                    if(response.ITENS.length > 0){
                        var itens = response.ITENS[0].VALOR;

                            itens = itens == null ? '{}' : itens;
                            itens = JSON.parse(itens);

                            angular.forEach(itens, function(iten, key) {
                                
                                vm.Custo.getNew(true);
                                vm.ListaItens = gScope.ListaItens;

                                var obj = vm.ListaItens[vm.ListaItens.length - 1];

                                iten.MODELO             = iten.MODELO         == null ? '{}' : iten.MODELO ; 
                                iten.TAMANHO            = iten.TAMANHO        == null ? '{}' : iten.TAMANHO;  
                                iten.COR                = iten.COR            == null ? '{}' : iten.COR; 
                                iten.QUANTIDADE         = iten.QUANTIDADE     == null ? 0    : Number(iten.QUANTIDADE); 
                                iten.PRECO_VENDA        = iten.PRECO_VENDA    == null ? 0    : Number(iten.PRECO_VENDA); 
                                iten.MARGEM             = iten.MARGEM         == null ? 0    : Number(iten.MARGEM) ; 
                                iten.PRODUTO_TROCA      = iten.PRODUTO_TROCA  == null ? ''   : iten.PRODUTO_TROCA; 
                                iten.LST_TROCA          = iten.LST_TROCA      == null ? []   : iten.LST_TROCA ;

                                obj.MUDAR_PRECO         = (iten.MUDAR_PRECO == true);
                                obj.MUDAR_CONTRIBUICAO  = (iten.MUDAR_CONTRIBUICAO == true);

                                obj.Ficha.PRODUTO_TROCA = iten.PRODUTO_TROCA;
                                obj.Ficha.LST_TROCA     = iten.LST_TROCA;
                                obj.Quantidade          = iten.QUANTIDADE;
                                obj.PrecoVenda          = iten.PRECO_VENDA;
                                obj.Contribuicao        = iten.MARGEM;

                                obj.ConsultaModelo.setSelected(iten.MODELO);
                                obj.ConsultaCor.setSelected(iten.COR);
                                obj.ConsultaTamanho.setSelected(iten.TAMANHO);

                                vm.ListaItens[vm.ListaItens.length - 1].CALCULADO = 0;
                            });
                     }

                     vm.RecalcularCusto();

                    resolve(true);
                },function(e){
                    reject(e);
                }
            );

        });    
    }

    vm.PrecoMedioVenda = function(item){

        return $q(function(resolve,reject){

            var ds = {
                    TAMANHO : item.ConsultaTamanho.selected,
                    MODELO  : item.ConsultaModelo.selected,
                    COR     : item.ConsultaCor.selected,
                    DATA    : vm.DATA
                };

            $ajax.post('/_31010/ConsultarPrecoVenda',ds,{contentType: 'application/json'})
                .then(function(response) {
                    item.MUDAR_PRECO = true;
                    item.PrecoVenda  = Number(Number(response).toFixed(2));

                    resolve(true);
                },function(e){
                    reject(e);
                }
            );

        });    
    }

    vm.excluirSimulacao = function(){

        if(vm.ConsultaSimular.selected.ID > 0){
            addConfirme('<h4>Confirmação</h4>',
                    'Excluir simulação "'+vm.ConsultaSimular.selected.ID+'-'+vm.ConsultaSimular.selected.DESCRICAO+'"?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){
                        $rootScope.$apply(function(){

                            var ds = {
                                ID  : vm.ConsultaSimular.selected.ID
                            };

                        $ajax.post('/_31010/excluirSimulacao',ds,{contentType: 'application/json'})
                            .then(function(response) {

                                vm.add_confime = 0;

                                vm.ConsultaSimular.apagar();

                                vm.ConsultaSimular.selected = {
                                    DESCRICAO : '',
                                    ID        : 0
                                };

                                var link = encodeURI(urlhost + '/_31010');
                                window.history.replaceState(document.title, 'Title', link); 

                                vm.InitVariaveis();

                                showSuccess('Simulação Excluida!');

                                resolve(true);
                            },function(e){
                                reject(e);
                            }
                        );                             
                                 
                        });
                    }}]     
            );

        }else{
            showErro('Selecione uma simulação');
        } 
    
    }

    vm.gravarSimulacao = function(){


        return $q(function(resolve,reject){

            if(vm.Descricao != ''){
                var itens = [];

                vm.ConsultaSimular.selected.DESCRICAO = vm.Descricao;

                angular.forEach(vm.ListaItens, function(iten, key) {
                    itens.push({
                        MODELO        : iten.ConsultaModelo.selected,
                        TAMANHO       : iten.ConsultaTamanho.selected, 
                        COR           : iten.ConsultaCor.selected,
                        QUANTIDADE    : iten.Quantidade,
                        PRECO_VENDA   : iten.PrecoVenda,
                        MARGEM        : iten.Contribuicao,
                        PRODUTO_TROCA : iten.Ficha.PRODUTO_TROCA,
                        LST_TROCA     : iten.Ficha.LST_TROCA,
                        MUDAR_PRECO         : iten.MUDAR_PRECO,
                        MUDAR_CONTRIBUICAO  : iten.MUDAR_CONTRIBUICAO  
                    });
                });  

                var ds = {
                        MERCADO         : JSON.stringify(vm.ConsultaPadrao.selected),
                        MERCADO_ITENS   : JSON.stringify(vm.PadraoItem),
                        TRANSPORTADORA  : JSON.stringify(vm.Consultatransportadora.selected),
                        CLINETE         : JSON.stringify(vm.ConsultaCliente.selected),
                        CIDADE          : JSON.stringify(vm.ConsultaCidade.selected),
                        FRETE           : JSON.stringify(vm.Frete),
                        DATAS           : JSON.stringify(vm.DATA),
                        FATORES         : JSON.stringify(vm.Fatores),
                        MARGEM          : vm.MARGEM,
                        DESCRICAO       : vm.ConsultaSimular.selected.DESCRICAO,
                        ID              : vm.ConsultaSimular.selected.ID,
                        ITENS           : JSON.stringify(itens)
                    };

                $ajax.post('/_31010/gravarSimulacao',ds,{contentType: 'application/json'})
                    .then(function(response) {

                        vm.ID = response;

                        vm.add_confime = 1;

                        var link = encodeURI(urlhost + '/_31010?SIMULACAO_ID='+ vm.ID);
                        window.history.replaceState(document.title, 'Title', link);

                        vm.ConsultaSimular.setSelected({ID: vm.ID , DESCRICAO: vm.ConsultaSimular.selected.DESCRICAO});

                        showSuccess('Simulação gravada!');

                        resolve(true);
                    },function(e){
                        reject(e);
                    }
                );
            }else{
                showErro('Descrição da simulação Inválida');
            }
        });    
    }

    vm.RemoverItem = function(item){
        angular.forEach(vm.ListaItens, function(iten, key) {
            if(iten == item){
                vm.ListaItens.splice(key, 1);
                vm.Custo.CalcularTotal();
            }
        });    
    }

    vm.MontarListaMes = function(){
        var data = new Date();
        var dia = data.getDate();
        var mes = data.getMonth() + 1;
        var ano = data.getFullYear();
        var tmp = 13;

        vm.DATA.MES = pad((mes - 1),2);

        if(vm.DATA.MES == '00'){
            vm.DATA.MES = '12';
        }

        for (var i = 0; i < 12; i++) {
            tmp = tmp - 1; 
            if(tmp > 0){
                vm.LISTA_MES.push(pad(tmp,2));
            }
        }
    };


    vm.MontarListaAno = function(){
        var data = new Date();
        var dia = data.getDate();
        var mes = data.getMonth() + 1;
        var ano = data.getFullYear();

        vm.DATA.ANO = pad(ano,4);

        if(vm.DATA.MES == '12'){
           vm.DATA.ANO =  pad((Number(ano) - 1),4);
        }

        for (var i = 0; i < 10; i++) {
            vm.LISTA_ANO.push(pad((ano - i),2));
        }
    };

    vm.MontarListaMes2 = function(){
        var data = new Date();
        var dia = data.getDate();
        var mes = data.getMonth() + 1;
        var ano = data.getFullYear();
        var tmp = 13;

        vm.DATA.MES2 = pad((mes - 1),2);

        if(vm.DATA.MES2 == '00'){
            vm.DATA.MES2 = '12';
        }

        for (var i = 0; i < 12; i++) {
            tmp = tmp - 1; 
            if(tmp > 0){
                vm.LISTA_MES2.push(pad(tmp,2));
            }
        }
    };

    vm.MontarListaAno2 = function(){
        var data = new Date();
        var dia = data.getDate();
        var mes = data.getMonth() + 1;
        var ano = data.getFullYear();

        vm.DATA.ANO2 = pad(ano,4);

        if(vm.DATA.MES2 == '12'){
           vm.DATA.ANO2 =  pad((Number(ano) - 1),4);
        }

        for (var i = 0; i < 10; i++) {
            vm.LISTA_ANO2.push(pad((ano - i),2));
        }
    };

    vm.CalcularMeses= function(){
        var ano1 =  vm.DATA.ANO;
        var ano2 =  vm.DATA.ANO2;

        var mes1 =  vm.DATA.MES;
        var mes2 =  vm.DATA.MES2;

        var ret = 0;

        if(ano1 <= ano2){
            if(ano2 == ano1){
                if(mes2 >= mes1){
                    ret = new Number(mes2) - Number(mes1);
                    vm.DataInvalida = false;
                }else{
                    vm.DataInvalida = true;
                }
            }else{
                ret = (Number(mes2) + 12) - Number(mes1);
                vm.DataInvalida = false;
            }
        }else{
            vm.DataInvalida = true;
        }

        vm.DATA.FATOR = ret + 1;

    };

    vm.keyupMarckUp = function(){
        angular.forEach(vm.ListaItens, function(item, key) {
            if(!(item.ConsultaModelo.selected == null) && !(item.ConsultaCor.selected == null) && !(item.ConsultaTamanho.selected == null)){
                item.CALCULADO = 0;
                item.calcularCusto(1);
            }
        }); 
    }

    vm.keyupMargem = function(){
        gScope.MARGEM = vm.MARGEM;

        angular.forEach(vm.ListaItens, function(item, key) {
            if(!(item.ConsultaModelo.selected == null) && !(item.ConsultaCor.selected == null) && !(item.ConsultaTamanho.selected == null)){
                item.CALCULADO = 0;
                item.calcularCusto(1);
            }
        }); 
    }

    vm.keyupComicao = function(){
        angular.forEach(vm.ListaItens, function(item, key) {
            if(!(item.ConsultaModelo.selected == null) && !(item.ConsultaCor.selected == null) && !(item.ConsultaTamanho.selected == null)){
                item.CALCULADO = 0;
                item.calcularCusto(1);
            }
        }); 
    }

    vm.AdicionarItensCusto = function(){

        if(vm.ListaItens.length == 0){
            vm.FLAG_RECALCULAR = false;
        }

        gScope.ListaItens = vm.ListaItens;

        vm.Custo.getNew();
        //var item = vm.Custo.getNew();
        //vm.ListaItens.push(item);

        //gScope.ListaItens = vm.ListaItens;
        //vm.ListaItens     = gScope.ListaItens;
    }

    $scope.$watch('vm.Fatores.ConsiderarPerdas', function (newValue, oldValue, scope) {
        if(newValue != oldValue){
            angular.forEach(vm.ListaItens, function(item, key) {
                if(!(item.ConsultaModelo.selected == null) && !(item.ConsultaCor.selected == null) && !(item.ConsultaTamanho.selected == null)){
                    item.CALCULADO = 0;
                    item.calcularCusto(1);
                }
            }); 
        }
    }, true);

    $scope.$watch('vm.DATA.ANO', function (newValue, oldValue, scope) {
        if(newValue != oldValue){

            vm.CalcularMeses();

            vm.FLAG_RECALCULAR = true;
            vm.Frete.CALCULADO = false;
        }
    }, true);

    $scope.$watch('vm.DATA.MES', function (newValue, oldValue, scope) {
        if(newValue != oldValue){

            vm.CalcularMeses();

            vm.FLAG_RECALCULAR = true;
            vm.Frete.CALCULADO = false;
        }
    }, true);

    $scope.$watch('vm.DATA.ANO2', function (newValue, oldValue, scope) {
        if(newValue != oldValue){

            vm.CalcularMeses();

            vm.FLAG_RECALCULAR = true;
            vm.Frete.CALCULADO = false;
        }
    }, true);

    $scope.$watch('vm.DATA.MES2', function (newValue, oldValue, scope) {
        if(newValue != oldValue){

            vm.CalcularMeses();

            vm.FLAG_RECALCULAR = true;
            vm.Frete.CALCULADO = false;
        }
    }, true);
    
    vm.DetalharCusto = function(item,flag){
        if(item.Ficha.ITENS.length > 0){

            $('.img-loading').css('display','block');

            vm.Item = item;
            gScope.Item = vm.Item;
            vm.MontarGrafico();

            //vm.Item.Ficha.ConsultarConfiguracao();
            
            $('#modal-detalhar').modal();

        }else{
            showErro('Produto sem dados de Custo');
        }
    }

    vm.DetalharNivel = function(item,flag){

        if(flag == 9){
            vm.Ficha.ConsultarAbsorcao(1);
            $('#modal-absorcao').modal();
        }

        if(flag == 8){
            vm.Ficha.ConsultarProprio(1);
            $('#modal-proprio').modal();
        }

        if(flag == 4){
            //vm.Item.Ficha.consultar(1);
            $('#modal-materia').modal();
        }

        if(flag == 7){
            vm.Item.Ficha.MaoDeObra(1);
            $('#modal-maodeobra').modal();
        }

        if(flag == 3){
            vm.Item.Ficha.Despesa(1);
            $('#modal-despesas').modal();
        }
    }

    vm.export1 = function(tabela,nome){
        exportTableToCsv(nome, tabela);
    };

    vm.export2 = function(tabela,nome){
        exportTableToXls(nome, tabela);
    };

    vm.Imprimir = function(div,descricao){
        var user = $('#usuario-descricao').val();
        var filtro = 'Modelo:' + vm.Item.ConsultaModelo.selected.DESCRICAO + '    Cor:' + vm.Item.ConsultaCor.selected.DESCRICAO + '    Tamanho:' + vm.Item.ConsultaTamanho.selected.DESCRICAO;
        printHtml(div, 'Custos Gerenciais - ' + descricao, filtro, user, '1.0.0',1,'');
    }            

    vm.FecharCusto = function(item){
        $('.google-visualization-orgchart-table').remove();
    }

    vm.MontarGrafico = function(){
        
        google.charts.load('current', {packages:["orgchart"]});
        google.charts.setOnLoadCallback(drawChart);

        that._controller   = function(){return $('#main').find('[ng-controller]')};
        var scope = that._controller().scope();

        //$('#chart_div').empty();

        var obj = $('#chart_div');
        var pai = $(obj).closest('.ficha');

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Name');
            data.addColumn('string', 'Manager');
            data.addColumn('string', 'ToolTip');

            var btn01 = '';//<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,01)">Detalhar</button>';
            var btn02 = '';//<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,02)">Detalhar</button>';
            var btn03 = '<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,03)">Detalhar</button>';
            var btn04 = '<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,04)">Detalhar</button>';
            var btn05 = '';//'<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,05)">Detalhar</button>';
            var btn06 = '';//'<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,06)">Detalhar</button>';
            var btn07 = '<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,07)">Detalhar</button>';
            var btn08 = '<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,08)">Detalhar</button>';
            var btn09 = '<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,09)">Detalhar</button>';
            var btn10 = '';//'<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,10)">Detalhar</button>';
            var btn11 = '';//'<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,11)">Detalhar</button>';
            var btn12 = '';//<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,12)">Detalhar</button>';
            var btn13 = '';//'<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,11)">Detalhar</button>';
            var btn14 = '';//<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,12)">Detalhar</button>';

            // For each orgchart box, provide the name, manager, and tooltip to show.

            var divInicio = '<div style="color: red; font-weight: bold; font-size: 16px;">';
            var spanInicio = '<span style="color: black; font-weight: bold; font-size: 13px;">';
            var roll = [
              [{v:'Gasto'            ,  f:'Gasto'+divInicio+'{{vm.Item.Gasto.Valor | number:5}}<br>'+btn01+'</span></div>'}, '', ''],
              [{v:'Custo'            ,  f:'Custo'+divInicio+'{{vm.Item.Gasto.Custo.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.Valor / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn02+'</span></div>'}, 'Gasto', ''],
              [{v:'Despesa'          ,  f:'Despesa'+divInicio+'{{vm.Item.Gasto.Despesa.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Despesa.Valor / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn14+'</span></div>'}, 'Gasto', ''],
              [{v:'Contas'           ,  f:'Contas'+divInicio+'{{vm.Item.Gasto.Despesa.Contas | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Despesa.Contas / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn03+'</span></div>'}, 'Despesa', ''],
              [{v:'Tributos'         ,  f:'Tributos'+divInicio+'{{vm.Item.Gasto.Despesa.Tributos | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Despesa.Tributos / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn13+'</span></div>'}, 'Despesa', ''],
              [{v:'MatariaPrima'     ,  f:'Matéria-prima'+divInicio+'{{vm.Item.Gasto.Custo.MateriaPrima.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.MateriaPrima.Valor/ vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn04+'</span></div>'}, 'Direto', ''],
              [{v:'Direto'           ,  f:'Direto'+divInicio+'{{vm.Item.Gasto.Custo.Direto.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.Direto.Valor  / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn05+'</span></div>'}, 'Custo', ''],
              [{v:'Indireto'         ,  f:'Indireto'+divInicio+'{{vm.Item.Gasto.Custo.Indireto.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.Indireto.Valor / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn06+'</span></div>'}, 'Custo', ''],
              [{v:'MaoObraDireta'    ,  f:'Mão de Obra'+divInicio+'{{vm.Item.Gasto.Custo.Direto.MaoObraDireta.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.Direto.MaoObraDireta.Valor / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn07+'</span></div>'}, 'Direto', ''],
              [{v:'Proprio'          ,  f:'Próprio'+divInicio+'{{vm.Item.Gasto.Custo.Indireto.Proprio.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.Indireto.Proprio.Valor / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn08+'</span></div>'}, 'Indireto', ''],
              [{v:'Absorvido'        ,  f:'Absorvido'+divInicio+'{{vm.Item.Gasto.Custo.Indireto.Absorvido.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.Indireto.Absorvido.Valor / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn09+'</span></div>'}, 'Indireto', ''],
              [{v:'CustoSetup'       ,  f:'Custo Setup'+divInicio+'{{vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoSetup.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoSetup.Valor / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn10+'</span></div>'}, 'MaoObraDireta', ''],
              [{v:'CustoOperacional' ,  f:'Custo Operacional'+divInicio+'{{vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoOperacional.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoOperacional.Valor / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn11+'</span></div>'}, 'MaoObraDireta', ''],
              [{v:'CustoOeSAbsorvido',  f:'Absorvido'+divInicio+'{{vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoOperacional.Valor2 + vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoSetup.Valor2 | number:5}} '+spanInicio+'{{((vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoOperacional.Valor2 + vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoSetup.Valor2) / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn11+'</span></div>'}, 'MaoObraDireta', '']
            ];

            angular.forEach(vm.Item.Gasto.Despesa.Itens, function(iten, key) {
                
                roll.push([{
                    v: iten.DESCRICAO, 
                    f: iten.DESCRICAO + divInicio + '{{vm.Item.Gasto.Despesa.Itens['+key+'].VALOR | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Despesa.Itens['+key+'].VALOR / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn12+'</span></div>'},
                    'Tributos', '']
                );               

            });

            data.addRows(roll);

            var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
            chart.draw(data, {allowHtml:true});

            $timeout(function(){
            
                $('.img-loading').css('display','none');
                $(obj).replaceWith( $compile($(obj).html())(scope) );
                $(pai).append('<div style="width: 99%; height: 100%;" id="chart_div"></div>');

                ///$scope.$apply(function () {
                //    $scope.message = "Timeout called!";
                //});

            },200);
        }
    }

    vm.Consulta = new $consulta();
                
    vm.Consultatransportadora                        = vm.Consulta.getNew(true);
    vm.Consultatransportadora.componente             = '.consulta-frete-transportadora';
    vm.Consultatransportadora.model                  = 'vm.Consultatransportadora';
    vm.Consultatransportadora.option.label_descricao = 'Transportadora:';
    vm.Consultatransportadora.option.obj_consulta    = '/_14020/api/transportadora';
    vm.Consultatransportadora.option.tamanho_input   = 'input-maior';
    vm.Consultatransportadora.option.campos_tabela   = [['TRANSPORTADORA_ID', 'Id'],['RAZAOSOCIAL','Razão Social'],['NOMEFANTASIA', 'Nome Fantasia'],['CLASSIFICACAO','CLASSIFICAÇÃO']];
    vm.Consultatransportadora.option.obj_ret         = ['TRANSPORTADORA_ID','RAZAOSOCIAL','CLASSIFICACAO'];
    vm.Consultatransportadora.option.required        = true;
    vm.Consultatransportadora.compile();

    vm.Consultatransportadora.onSelect = function() {
        vm.Frete.CALCULADO = false;
    };

    vm.Consultatransportadora.onClear = function() {
        vm.Frete.CALCULADO = false;
    };  

    vm.ConsultaCliente                        = vm.Consulta.getNew(true);
    vm.ConsultaCliente.componente             = '.consulta-cliente';
    vm.ConsultaCliente.model                  = 'vm.ConsultaCliente';
    vm.ConsultaCliente.option.label_descricao = 'Cliente:';
    vm.ConsultaCliente.option.obj_consulta    = '/_14020/api/cliente';
    vm.ConsultaCliente.option.tamanho_input   = 'input-maior';
    vm.ConsultaCliente.option.tamanho_tabela  = 780;
    vm.ConsultaCliente.option.campos_tabela   = [['ID', 'Id'],['RAZAOSOCIAL','Razão Social'],['NOMEFANTASIA', 'Nome Fantasia'],['UF','UF'],['CIDADE','Cidade']];
    vm.ConsultaCliente.option.obj_ret         = ['ID','RAZAOSOCIAL'];
    vm.ConsultaCliente.option.required        = false;
    vm.ConsultaCliente.compile();

    vm.ConsultaCliente.onSelect = function() {
        vm.Frete.CALCULADO = false;
        vm.ConsultaCidade.setSelected({ID: 0, DESCRICAO: vm.ConsultaCliente.CIDADE, UF: vm.ConsultaCliente.UF, FILTRO: ''}, vm.ConsultaCliente.UF + ' - ' + vm.ConsultaCliente.CIDADE);
    };

    vm.ConsultaCliente.onClear = function() {
        vm.Frete.CALCULADO = false;

        if(vm.ConsultaCidade.item.selected == true){
            vm.ConsultaCidade.apagar(true);
        }
    };  

    vm.ConsultaCidade                        = vm.Consulta.getNew(true);
    vm.ConsultaCidade.componente             = '.consulta-cidade';
    vm.ConsultaCidade.model                  = 'vm.ConsultaCidade';
    vm.ConsultaCidade.option.label_descricao = 'Cidade:';
    vm.ConsultaCidade.option.obj_consulta    = '/_14020/api/cidade';
    vm.ConsultaCidade.option.campos_tabela   = [['UF', 'UF'],['DESCRICAO','Cidade']];
    vm.ConsultaCidade.option.obj_ret         = ['UF','DESCRICAO'];
    vm.ConsultaCidade.option.required        = true;
    vm.ConsultaCidade.compile();

    vm.ConsultaCidade.onSelect = function() {
        vm.Frete.CALCULADO = false;
    };

    vm.ConsultaCidade.onClear = function() {
        vm.Frete.CALCULADO = false;

        if(vm.ConsultaCliente.item.selected == true){
            vm.ConsultaCliente.apagar(true);
        }              
    };

    vm.ConsultaSimular = vm.Consulta.getNew();
    vm.ConsultaSimular.componente                  = '.consultar-simulacao';
    vm.ConsultaSimular.option.class                = 'consultar-simulacao-class';
    vm.ConsultaSimular.model                       = 'vm.ConsultaSimular';
    vm.ConsultaSimular.option.label_descricao      = 'Simulação:';
    vm.ConsultaSimular.option.obj_consulta         = '/_31010/ConsultarSimulacao';
    vm.ConsultaSimular.option.tamanho_input        = 'input-maior';
    vm.ConsultaSimular.option.campos_tabela        = [['ID','ID'],['DESCRICAO','DESCRIÇÃO'],['DATA_HORA','DATA']];
    vm.ConsultaSimular.option.tamanho_tabela       = 690;
    vm.ConsultaSimular.autoload                    = false;
    vm.ConsultaSimular.compile();


    vm.ConsultaSimular.selected = {
        DESCRICAO : '',
        ID        : 0
    };

    vm.ConsultaSimular.onSelect= function(){

        if(vm.ConsultaSimular.selected.ID > 0){

            vm.Descricao = vm.ConsultaSimular.selected.DESCRICAO;
            vm.ID        = vm.ConsultaSimular.selected.ID;

            vm.Simulacao().then(function(){
                
            });
        }
    }

    vm.add_confime = 0;

    vm.ConsultaSimular.onClear = function(){

        if(vm.add_confime > 0){

            vm.add_confime = 0;

            addConfirme('<h4>Confirmação</h4>',
                    'Limpar itens da simulação?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){
                        $rootScope.$apply(function(){

                            vm.InitVariaveis();                               
                                 
                        });
                    }}]     
            );
        };

        vm.Descricao    = '';
        vm.ID = 0;
        vm.ConsultaSimular.selected = {
            DESCRICAO : '',
            ID        : 0
        };

        var link = encodeURI(urlhost + '/_31010');
        window.history.replaceState(document.title, 'Title', link); 
    }
    
    vm.InitVariaveis = function(){
        vm.ConsultaPadrao.apagar();
        vm.Consultatransportadora.apagar();
        vm.ConsultaCliente.apagar();
        vm.ConsultaCidade.apagar();
        vm.ListaItens = []; 

        vm.Descricao    = '';
        
        vm.Frete = {
            DADOS : [],
            CALCULADO : false,
            PERCENTUAL: 0
        };

        vm.Fatores = {
            Frete: {
                Tipo: 'FOB',
                Valor: 0
            },
            ConsiderarPerdas: true,
            MarckUp: 100,
        };

        vm.DATA = {
            ANO : 0,
            MES : 0,
            ANO2: 0,
            MES2: 0
        };

        vm.MontarListaMes();
        vm.MontarListaAno();
        vm.MontarListaMes2();
        vm.MontarListaAno2();
        vm.CalcularMeses();

        vm.PadraoItem = {};
        vm.MARGEM     = 100;

        gScope.Descricao    = vm.Descricao;
        gScope.Frete        = vm.Frete;
        gScope.Fatores      = vm.Fatores;
        gScope.DATA         = vm.DATA;
        gScope.PadraoItem   = vm.PadraoItem;
        gScope.MARGEM       = vm.MARGEM;
    }

    vm.ConsultaPadrao = vm.Consulta.getNew(true);
    vm.ConsultaPadrao.componente                  = '.consulta-padrao';
    vm.ConsultaPadrao.option.class                = 'custo-padrao';
    vm.ConsultaPadrao.model                       = 'vm.ConsultaPadrao';
    vm.ConsultaPadrao.option.label_descricao      = 'Mercado:';
    vm.ConsultaPadrao.option.obj_consulta         = '/_31010/custoPadrao';
    vm.ConsultaPadrao.option.tamanho_input        = 'input-maior';
    vm.ConsultaPadrao.option.campos_tabela        = [['ID','ID'],['FAMILIA_DESCRICAO','FAMÍLIA'],['DESCRICAO','DESCRIÇÃO']];
    vm.ConsultaPadrao.option.tamanho_tabela       = 690;
    vm.ConsultaPadrao.autoload                    = false;
    vm.ConsultaPadrao.compile();

    vm.ConsultaProduto = vm.Consulta.getNew(true);
    vm.ConsultaProduto.componente                  = '.consulta-produto';
    vm.ConsultaProduto.option.class                = 'produt-troca';
    vm.ConsultaProduto.model                       = 'vm.ConsultaProduto';
    vm.ConsultaProduto.option.label_descricao      = 'Produto:';
    vm.ConsultaProduto.option.obj_consulta         = '/_31010/consultarProduto';
    vm.ConsultaProduto.option.tamanho_input        = 'input-maior';
    vm.ConsultaProduto.option.campos_tabela        = [['ID','ID'],['DESCRICAO','DESCRIÇÃO']];
    vm.ConsultaProduto.option.tamanho_tabela       = 436;
    vm.ConsultaProduto.autoload                    = false;
    vm.ConsultaProduto.option.paran                = {MERCADO: vm.ConsultaPadrao.item.dados}; 
    vm.ConsultaProduto.compile();

    vm.ConsultaTamanho = vm.Consulta.getNew(true);
    vm.ConsultaTamanho.componente                  = '.consulta-tamanho';
    vm.ConsultaTamanho.option.class                = 'produt-troca';
    vm.ConsultaTamanho.model                       = 'vm.ConsultaTamanho';
    vm.ConsultaTamanho.option.label_descricao      = 'Tamanho:';
    vm.ConsultaTamanho.option.obj_consulta         = '/_31010/ConsultarTamanho2';
    vm.ConsultaTamanho.option.tamanho_input        = 'input-medio';
    vm.ConsultaTamanho.option.campos_tabela        = [['ID','ID'],['DESCRICAO','DESCRIÇÃO']];
    vm.ConsultaTamanho.option.tamanho_tabela       = 436;
    vm.ConsultaTamanho.autoload                    = false;
    vm.ConsultaTamanho.option.paran                = {PRODUTO: {GRADE_CODIGO : 0}}; 
    vm.ConsultaTamanho.compile();

    vm.ConsultaProduto.onSelect= function(){
        vm.Item.Ficha.NEW_PRODUTO =  vm.ConsultaProduto.item.dados;
        vm.ConsultaTamanho.option.paran = {PRODUTO: vm.ConsultaProduto.item.dados   , PADRAO: 1};

        vm.Item.Ficha.NEW_PRODUTO.TAMANHO = 0;

        vm.ConsultaTamanho.filtrar();
    }

    vm.ConsultaTamanho.onSelect= function(){
        vm.Item.Ficha.NEW_PRODUTO.TAMANHO =  vm.ConsultaTamanho.item.dados.ID;
        vm.Item.Ficha.NEW_PRODUTO.DESC_TAMANHO =  vm.ConsultaTamanho.item.dados.DESCRICAO;
        vm.ConsultaTamanho.option.paran   = {PRODUTO: vm.ConsultaProduto.item.dados   , PADRAO: 0};
    }

    vm.ConsultaProduto.onClear = function(){
        vm.Item.Ficha.NEW_PRODUTO =  {};
        vm.ConsultaTamanho.apagar();
        vm.ConsultaTamanho.option.paran = {PRODUTO: {GRADE_CODIGO : 0, MODELO_ID: 0, TAMANHO: 0}, PADRAO: 0};
    }

    vm.ConsultaTamanho.onClear = function(){
        vm.Item.Ficha.NEW_PRODUTO.TAMANHO = 0;
        vm.Item.Ficha.NEW_PRODUTO.DESC_TAMANHO = '0';
    }

    gScope.ConsultaProduto = vm.ConsultaProduto;
    gScope.ConsultaPadrao  = vm.ConsultaPadrao;
    vm.PadraoItem = {};
    gScope.PadraoItem = vm.PadraoItem;

    vm.ListaIncentivo = {};
    vm.consultarIncentivo = function(){

        var ds = {
                FLAG : 0
            };

        $ajax.post('/_31010/consultarIncentivo',ds,{contentType: 'application/json'})
            .then(function(response) {
                vm.ListaIncentivo = response;
                gScope.ListaIncentivo = vm.ListaIncentivo;
            }
        );
    };

    vm.consultarPadraoItens = function(){

        var ds = {
                PADRAO : vm.ConsultaPadrao.item.dados
            };

        $ajax.post('/_31010/custoPadraoItem',ds,{contentType: 'application/json'})
            .then(function(response) {
                vm.PadraoItem = response;

                angular.forEach(vm.PadraoItem, function(item, key) {
                    
                    item.FATOR      = Number(item.FATOR);
                    item.AVOS       = Number(item.AVOS);
                    item.PERCENTUAL = Number(item.PERCENTUAL);
                    item.FRETE      = Number(item.FRETE);
                    item.OLD_FRETE  = Number(item.OLD_FRETE);

                    if(item.USAR_FATOR == 1){
                        vm.CalcularFator(item);
                    }else{
                        item.VALOR      = Number(item.PERCENTUAL);
                    }
                });

                gScope.PadraoItem = vm.PadraoItem; 

                vm.FLAG_RECALCULAR = true;
                vm.Frete.CALCULADO = false; 
            }
        );
    };

    vm.RecalcularCusto = function(){

        $timeout(function() {
            vm.FLAG_RECALCULAR = false;

            angular.forEach(vm.ListaItens, function(item, key) {
                if(!(item.ConsultaModelo.selected == null) && !(item.ConsultaCor.selected == null) && !(item.ConsultaTamanho.selected == null)){
                    item.CALCULADO = 0;
                    item.consultarTempo(1);
                }
            });
        });

    }

    vm.recalcularPadrao = function(){
        angular.forEach(vm.ListaItens, function(item, key) {
            if(!(item.ConsultaModelo.selected == null) && !(item.ConsultaCor.selected == null) && !(item.ConsultaTamanho.selected == null)){
                item.CALCULADO = 0;
                item.calcularCusto(1);
            }
        });       
    };

    $scope.$watch('gScope.PadraoItem', function (newValue, oldValue, scope) {
        if(newValue != oldValue){
            vm.FLAG_RECALCULAR = true;
            vm.Frete.CALCULADO = false;
        }
    }, true);

    $scope.$watch('vm.Fatores.Incentivo', function (newValue, oldValue, scope) {
        if(newValue != oldValue){
            vm.recalcularPadrao();
        }
    }, true);

    vm.CalcularFator = function(item) {
        item.VALOR = ((Number(item.PERCENTUAL) / Number(item.AVOS)) * Number(item.FATOR));
        vm.recalcularPadrao();
    }

    vm.ConsultaPadrao.onSelect= function(){

        vm.PERC_FATURAMENTO.VALOR = 0;
        vm.PERC_FATURAMENTO.FLAG  = 0;

        vm.Fatores.Incentivo = ''+vm.ConsultaPadrao.selected.PERC_INCENTIVO+'';

        vm.ConsultaProduto.option.paran = {MERCADO: vm.ConsultaPadrao.item.dados}; 

        vm.PadraoItem = {};
        vm.consultarPadraoItens();

        angular.forEach(vm.ListaItens, function(item, key) {
            item.ConsultaModelo.disable(false);
            item.ConsultaCor.disable(false);
            item.ConsultaTamanho.disable(false);

            item.ConsultaModelo.option.paran = {MERCADO: gScope.ConsultaPadrao.item.dados}; 
        });
    };

    vm.ConsultaPadrao.onClear= function(){

        vm.PERC_FATURAMENTO.VALOR = 0;
        vm.PERC_FATURAMENTO.FLAG  = 0;

        vm.PadraoItem = {};   

        angular.forEach(vm.ListaItens, function(item, key) {
            item.ConsultaModelo.disable(true);
            item.ConsultaCor.disable(true);
            item.ConsultaTamanho.disable(true);
        });
    };
    
    vm.DetalharFrete = function(){
        $('#modal-frete').modal();
    }

    vm.LimparFrete = function(){
        vm.Frete.PERCENTUAL = 0;

        angular.forEach(vm.PadraoItem, function(item, key) {
            if(item.FRETE == 1){
                item.VALOR = item.OLD_FRETE;    
            }
        });

        vm.ReprocessarCusto();
    }

    vm.ReprocessarCusto = function(){
        angular.forEach(vm.ListaItens, function(item, key) {
            if(!(item.ConsultaModelo.selected == null) && !(item.ConsultaCor.selected == null) && !(item.ConsultaTamanho.selected == null)){
                item.CALCULADO = 0;
                item.calcularCusto();
            }
        });
    }

    vm.CalcularFrete = function() {
        
        var origem    = '';
        var origem_id = '';

        if( vm.ConsultaCliente.item.selected == true ) {
                origem = 'SIMULADOR';
                origem_id = vm.ConsultaCliente.selected.ID;
            } else {
                origem    = 'SIMULADOR_CIDADE';
                origem_id = vm.ConsultaCidade.selected.ID;
        }

        var itens = [];

        angular.forEach(vm.ListaItens, function(item, key) {
            if(!(item.ConsultaModelo.selected == null) && !(item.ConsultaCor.selected == null) && !(item.ConsultaTamanho.selected == null)){
                
                var tamanhos = item.ConsultaTamanho.selected.LISTA   + '';
                var replicas = item.ConsultaTamanho.selected.REPLICA + '';
                
                var res = tamanhos.split(",");
                var rep = replicas.split("#@#");

                if(res.length > 1){
                    angular.forEach(rep, function(iten, key) {
                        
                        var res = iten.split("#$#");

                        itens.push({
                            MODELO_ID       : item.ConsultaModelo.selected.ID,
                            COR_ID          : item.ConsultaCor.selected.ID,
                            TAMANHO         : res[1],
                            QUANTIDADE      : (item.Quantidade / rep.length),
                            VALOR_UNITARIO  : item.PrecoVenda  
                        });

                    });
                }else{
                    itens.push({
                        MODELO_ID       : item.ConsultaModelo.selected.ID,
                        COR_ID          : item.ConsultaCor.selected.ID,
                        TAMANHO         : item.ConsultaTamanho.selected.ID,
                        QUANTIDADE      : item.Quantidade,
                        VALOR_UNITARIO  : item.PrecoVenda  
                    });
                }

            }
        }); 

        var filtro = {
            ORIGEM            : origem,
            ORIGEM_ID         : origem_id,
            TRANSPORTADORA_ID : vm.Consultatransportadora.selected.TRANSPORTADORA_ID,
            ITENS             : itens,
            RETURN            : true
        };

        return $q(function(resolve, reject){
            $ajax.post('/_14020/api/frete/calcular',filtro).then(function(response){
                
                sanitizeJson(response);

                vm.Frete.DADOS = response;

                vm.Frete.CALCULADO = true;

                vm.Frete.ORIGEM    = vm.Frete.DADOS.ORIGEM;
                vm.Frete.ORIGEM_ID = vm.Frete.DADOS.ORIGEM_ID;

                vm.Frete.PERCENTUAL = Number(response.VALOR_FINAL) / Number(response.VALOR_TOTAL);
                vm.Frete.PERCENTUAL = vm.Frete.PERCENTUAL.toFixed(4);

                vm.Frete.DADOS.COMPOSICOES = [
                    {
                        DESCRICAO : 'Dados da Carga',
                        DADOS : response.DADOS_CARGA
                    },
                    {
                        DESCRICAO : 'Composição dos Valores',
                        DADOS : response.DADOS_COMPOSICAO
                    }                            
                ];

                angular.forEach(vm.PadraoItem, function(item, key) {
                    if(item.FRETE == 1){
                        item.VALOR = 0;    
                    }
                });


                vm.ReprocessarCusto();
                
                resolve(response);
            },function(e){
                reject(e);
            });
        });
    }    

    init();

}   
  
//# sourceMappingURL=_31010.js.map
