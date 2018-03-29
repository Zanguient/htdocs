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
		'gc-find',
        'ngFileUpload',
        'ngSanitize'
	])

	.filter('inArray', function($filter){
	    return function(list, arrayFilter, element){
	        if(arrayFilter){
	            return $filter("filter")(list, function(listItem){
	                return arrayFilter.indexOf(listItem[element]) != -1;
	            });
	        }
	    };
	});
;
angular
    .module('app')
    .factory('ScriptCompile', ScriptCompile);

	ScriptCompile.$inject = [
        '$ajax',
        '$window',
        '$timeout',
        '$rootScope',
        '$compile'
    ];

function ScriptCompile($ajax,$window,$timeout,$rootScope,$compile) {

    var lista = [];
    /**
     * Constructor, with class name
     */
    function ScriptCompile(data) {
        if (data) {
            this.setData(data);
        }
    }

    /**
     * Public method, assigned to prototype
     */
    ScriptCompile.prototype = {
        /* Variaveis */
        script     :'',
        funcoes    :{
            0 : {
                CHAVE : 'ISNULL',
                EXEC  : function(valor){
                    var ret = false;
                    valor = valor[0];

                    if(valor == ''       ){ret = true}
                    if(valor == undefined){ret = true}

                    return ret;
                },
                DESC  : 'Verifica se um valor é vazio ou não definido'
            },
            1 : {
                CHAVE : 'TRIM',
                EXEC  : function(valor){
                    valor = valor[0];
                    valor = (valor+'').trim();
                    return valor;
                },
                DESC  : 'Remove os espaços no inicio e fim de uma string'
            },
            2 : {
                CHAVE : 'SOMA',
                EXEC  : function(valor){
                    var v1 = (valor[0]+'').trim();
                    var v2 = (valor[1]+'').trim();

                    var valor = Number(v1)+Number(v2);
                    return valor;
                },
                DESC  : 'Soma dois valores'
            },
            3 : {
                CHAVE : 'DIVISAO',
                EXEC  : function(valor){
                    var v1 = (valor[0]+'').trim();
                    var v2 = (valor[1]+'').trim();

                    var valor = Number(v1)/Number(v2);
                    return valor;
                },
                DESC  : 'Soma dois valores'
            }
        },
        variaveis  :[],
        formulas   :[],
        operadores :[],
        /* funções */
        setData: function(data) {
            angular.extend(this, data);
        },
        mimifi: function(string){
            var regex = new RegExp('\\n', 'g');
            string = string.replace(regex, ' ');
            
            var regex = new RegExp('  ', 'g');
            string = string.replace(regex, ' ');

            //var regex = new RegExp(, 'g');
            string = string.replace('/ (/g', '(');

            return string;
        },
        removerAspas: function(string){
            var aa = string.trim();

            var e = aa.indexOf('\'');
            if(e == 0){
                var f = aa.substring(1,aa.length);
                f = f.indexOf('\'');
                if((f+2) == aa.length){
                    aa = aa.substring(1,aa.length -1);
                }
            }

            return aa;
        },
        encontrarValor: function(chave_ini,chave_fim,string,flag){
            var that = this;

            var ret = [];
            var string_temp   = ' '+(string+'').trim()+' ';
            var string_temp_a = '';
            var string_temp_b = '';

            var controle = 0;
            var contador = 0;
            
            var encontrado_em_ini = 0;
            var encontrado_em_fim = 0;
            var ret_valor = '';

            var encontrado_em_ini = string_temp.indexOf(chave_ini);
            var encontrado_em_fim = string_temp.indexOf(chave_fim);

            var c = 0;
            var d = 0;
            while (c == 0) {

                if(encontrado_em_fim >= encontrado_em_ini){
                    c = 1;    
                }else{
                    var e = string_temp.substring(encontrado_em_fim + 1, string_temp.length);
                    var f = e.indexOf(chave_fim);
                    if(f > 0){
                        encontrado_em_fim = encontrado_em_fim + f + 1;
                    }   
                }

                d++;

                if(d > 10){
                    c = 1;
                }
            }

            if(encontrado_em_ini >= 0){
                var variavel  = string_temp.substring(encontrado_em_ini,encontrado_em_fim + chave_fim.length);
                var a         = string_temp.substring(0, encontrado_em_ini);
                var b         = string_temp.substring(encontrado_em_fim + chave_fim.length, string_temp.length);
                var x         = string_temp.substring(encontrado_em_ini + chave_ini.length,encontrado_em_fim - chave_fim.length +1);

                string_temp   = a+b;
                string_temp_a = a;
                string_temp_b = b;

                variavel      = variavel.trim();

                if(flag == 1){
                    variavel = x;
                    variavel = variavel.trim();

                }else{
                    if((' '+variavel).indexOf(chave_ini) < 1){
                        variavel = '';
                    } 
                }

                ret_valor = variavel;
            }else{
                controle = 1;    
            }          

            return {VALOR: ret_valor, STRING: string_temp, STRING_A: string_temp_a, STRING_B: string_temp_b}

        },
        removerComentario: function(string){
            var that = this;
            var chave_ini = "/*";
            var chave_fim = "*/";

            var controle = 0;

            var contador = 0;
            var string_temp = string;

            var ret = [];

            while (controle < 1) {

                var a = that.encontrarValor(chave_ini,chave_fim,string_temp,0);

                var variavel = a.VALOR;
                var string_temp_a = a.STRING_A;
                var string_temp_b = a.STRING_B;

                if(variavel != ''){
                    string_temp = string_temp_a+string_temp_b;
                    ret.push(variavel.trim());
                }else{
                    controle = 1;    
                } 

                contador++;
                if(contador > 20){
                    controle = 1;  
                }         
            }

            return {COMENTARIOS: ret, STRING: string_temp}
        }, 
        encontrarFuncoes: function(string){

            var that = this;

            var ret = [];
            var string_temp = string;

            angular.forEach(this.funcoes, function(iten, key) { 
                
                var chave_ini = iten.CHAVE + '(';
                var chave_fim = ")";

                var controle = 0;
                var contador = 0;
                
                var encontrado_em_ini = 0;
                var encontrado_em_fim = 0;

                while (controle < 1) {

                    var a = that.encontrarValor(chave_ini,chave_fim,string_temp,0);

                    var variavel = a.VALOR;
                    var string_temp_a = a.STRING_A;
                    var string_temp_b = a.STRING_B;

                    if(variavel != ''){
                        var aa = variavel.substring(chave_ini.length, variavel.length - chave_fim.length);

                        aa = that.removerAspas(aa);

                        aa = iten.EXEC(aa.split(","));

                        string_temp = string_temp_a+aa+string_temp_b;

                        ret.push(variavel);
                    }else{
                        controle = 1;    
                    } 

                    contador++;
                    if(contador > 20){
                        controle = 1;  
                    }         
                }

            });

            return {FUNCAO: ret, STRING: string_temp}
        },
        encontrarVariaveis: function(string){

            var that = this;

            var ret = [];
            var string_temp = string;
                
            var chave_ini = 'VAR';
            var chave_fim = ";";

            var controle = 0;
            var contador = 0;

            while (controle < 1) {

                var a = that.encontrarValor(chave_ini,chave_fim,string_temp,0);

                var variavel = a.VALOR;
                var string_temp = a.STRING;

                if(variavel != ''){

                    var c = that.encontrarValor('VAR ','=',variavel,1);
                    var d = that.encontrarValor('=',';',variavel,1);
                    var e = c.VALOR;
                    var f = d.VALOR;

                    ret.push({VAR:e,VALOR:f});
                }else{
                    controle = 1;    
                } 

                contador++;
                if(contador > 20){
                    controle = 1;  
                }         
            }

            return {VARIAVEIS: ret, STRING: string_temp}
        },
        ScriptCompile: function(data) {
            if (data) {
                this.setData(data);
            }
        },
        getNew: function() {
            return true;
        },
        formulaCompilar: function(formula,itens){

            return true;
        },
        encontrarFormula: function(){
            var formulas = [];
            var tratado  = this.script.toUpperCase();
            tratado      = this.mimifi(tratado);
            tratado      = this.removerComentario(tratado);
            tratado      = this.encontrarFuncoes(tratado.STRING);    var funcoes = tratado.FUNCAO;
            tratado      = this.encontrarVariaveis(tratado.STRING);  var variavel = tratado.VARIAVEIS;
            
            var string = tratado.STRING;
            console.log(funcoes);
            console.log(variavel);
            console.log(string);

            return formulas;
        },
        exprecoes : [
            {
                NOME:'IF',
                SCOPO:'IF(){}'
            }
        ],
        testarfuncao: function(itens){
            var sct   = '';
                sct  += 'if ( ){ ';
                sct  += '/* TESTE */';
                sct  += ' var a = TRIM(\' OI ANDERSON \');';
                sct  += ' var b = ISNULL(\'ANDERSON\');';
                sct  += ' var c = SOMA(1,2);';
                sct  += ' var d = DIVISAO( 2, 2 );';
                sct  += ' var e = SOMA(:C,:D);';
                sct  += '}';

            this.script = sct;
            var itens   = itens;
            var retorno = this.encontrarFormula();
        },
        executar: function(){
            var script = document.getElementById("textareaCode").value;
            var valor  = this.compile(script);
            console.log(valor);
        },
        compile: function(script){

            function sleep(ms) {
                var unixtime_ms = new Date().getTime();
                while(new Date().getTime() < unixtime_ms + ms) {}
            }
            
            var valor;

            try{

                var text  = "";
                text += "<!DOCTYPE html>";
                text += "<html>";
                text += "  <body onload='onload()'>";
                text += "    <input type='checkbox' id='valor-retorno' value='NOT'>";
                text += "    <script>";
                text += "    function silentErrorHandler(){return true;}"; 
                text += "    window.onerror=silentErrorHandler;";
                text += "      var result;";
                text += "      function onload() {";
                text += "        try{";
                text +=            script;
                text += "          if(result == undefined){result = 'a variavel \"result\" não foi definida';}";
                text += "          var ret = document.getElementById('valor-retorno');";
                text += "          ret.checked = result;";
                text += "          ret.value = result;";
                text += "        }catch(erro) {";
                text += "          ret.value = erro.message;";
                text += "        }";
                text += "      }";
                text += "    </script>";
                text += "  </body>";
                text += "</html>";

                var ifr = document.createElement("iframe");

                ifr.setAttribute("frameborder", "0");
                ifr.setAttribute("id", "iframeResult");
                ifr.setAttribute("name", "iframeResult");  
                document.getElementById("iframewrapper").innerHTML = "";
                document.getElementById("iframewrapper").appendChild(ifr);

                var ifrw = (ifr.contentWindow) ? ifr.contentWindow : (ifr.contentDocument.document) ? ifr.contentDocument.document : ifr.contentDocument;
                ifrw.document.open();
                ifrw.onerror = function(e){
                    if(valor == undefined){
                        valor = e;
                    }
                };
                ifrw.document.write(text);  
                ifrw.document.close();
                if (ifrw.document.body && !ifrw.document.body.isContentEditable) {
                    ifrw.document.body.contentEditable = true;
                    ifrw.document.body.contentEditable = false;
                }

            }catch(err) {
                valor = err.message;
            }

            var cont = 0;
            while(valor == undefined){
                valor = $( "#iframeResult" ).contents().find( "#valor-retorno" ).val();
                sleep(100);
                cont++;
                if(cont > 19){
                    valor = false;
                }
            }

            return valor;
            
        }
    }

    /**
     * Return the constructor function
     */
    return ScriptCompile;
};
angular
    .module('app')
    .factory('Create', Create);
    
Create.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        'gScope',
        '$compile',
        '$consulta'
    ];

function Create($ajax, $httpParamSerializer, $rootScope, gScope, $compile, $consulta) {

    /**
     * Constructor, with class name
     */
    function Create(data) {
        if (data) {
            this.setData(data);
        }
    }

    var itens_html = [];

    var consulta = new $consulta();
    gScope.consulta = consulta;

    var Input_temp = {
        EDIT     : 0,
        NOME     : '',
        ID       : 0,
        TIPO     : 0,
        VALOR    : 0,
        TEXTO    : 'Texto',
        DEFAULT  : 0,
        MIN      : 0,
        MAX      : 100,
        TAMANHO  : '1',
        REQUERED : '1',
        VINCULO  : '',
        STEP     : 1,
        CONSULTA : null,
        DISABLED : false,
        ITENS    : [{TEXTO : 'ITEM 1', SELECTED: false, VALOR : '1'}]
    }

    function validarInput(item,Inputs){

    }

    /**
     * Public method, assigned to prototype
     */
    Create.prototype = {
        itens : [],
        model : '',
        validarCampos: function(){
            var validar = true;

            angular.forEach(this.itens, function(iten, key) {
                var item_invalido = false;

                if(iten.REQUERED == 1){

                    if((iten.TIPO == 1 || iten.TIPO == 2 || iten.TIPO == 3 || iten.TIPO == 5 || iten.TIPO == 6 || iten.TIPO == 8 || iten.TIPO == 10) && iten.VALOR == ""){
                        item_invalido = true;                            
                    }

                    if(iten.TIPO == 4){
                        var not_selected_item = true;
                        angular.forEach(iten.ITENS, function(a, key) {
                            if(a.SELECTED == true){
                                not_selected_item = false;
                            }
                        });

                        if(not_selected_item){
                            item_invalido = true;
                        }
                    }

                    if(iten.TIPO == 7){
                        var valor = iten.CONSULTA.item.dados[iten.CAMPO_GRAVAR];
                        if(valor == undefined){
                            item_invalido = true;
                        }
                    }

                    try{
                        //console.log(iten.VALOR);
                        if((iten.TIPO == 9 ) && !(iten.VALOR.VALOR > 0)){
                            //console.log(iten.VALOR);
                            item_invalido = true;                            
                        }
                    }catch(erro){
                        item_invalido = true;     
                    }
                    

                    if(item_invalido){
                        showErro("\""+iten.NOME+"\" é obrigatório");
                        validar = false;
                    }
                }
            });

            return validar;
        },
        tratarCampos: function(){
            var itens = [];

            angular.forEach(this.itens, function(iten, key) {
                var valor, id, tipo, arr_itens, comitens,temp, json;

                id    = iten.ID;
                valor = "";
                arr_itens = [];
                tipo  = iten.TIPO;
                json  = ""; 

                comitens = false;

                if(iten.TIPO == 1 || iten.TIPO == 2 || iten.TIPO == 3 || iten.TIPO == 5 || iten.TIPO == 6 || iten.TIPO == 8 || iten.TIPO == 10){
                    valor = iten.VALOR;                            
                }

                if(iten.TIPO == 4){
                    comitens = true;
                    angular.forEach(iten.ITENS, function(a, key) {
                        if(a.SELECTED){temp = 1;}else{temp = 0;}
                        arr_itens.push({VALOR: temp, CAMPO_VALOR: a.VALOR});
                    });
                }

                if(iten.TIPO == 7){
                    valor = iten.CONSULTA.item.dados[iten.CAMPO_GRAVAR];
                    json = JSON.stringify(iten.CONSULTA.item.dados);
                }

                if(iten.TIPO == 9 ){
                    try{
                        valor = iten.VALOR.VALOR;
                    }catch(erro){
                        valor = 0;
                    }                        
                }

                if(valor == undefined){
                    valor = 0;
                }

                itens.push({VALOR:valor, ID:id, TIPO:tipo, ITENS:arr_itens, COMITENS:comitens, JSON:json});
                
            });

            return itens;
        },
        montarHtml:function(item,id,flag){
            that = this;

            var ng_model = ''; if(that.model.length > 0){ng_model = 'ng-model="'+that.model+'['+id+'].VALOR"'; }else{ng_model = '';}
            var ng_modelvalor = ''; if(that.model.length > 0){ng_modelvalor = that.model+'['+id+']'; }else{ng_modelvalor = '';}

            var Input    = '';
            var disabled = ''; if(item.DISABLED){disabled = 'ng-disabled="'+that.model+'['+id+'].DISABLED == true"'; }else{disabled = '';}
            var required = ''; if(item.REQUERED == 1){required = 'required'; }else{required = '';}
            var tamanho  = ''; if(item.TAMANHO == 1){tamanho = 'input-menor';}else{if(item.TAMANHO == 2){tamanho = 'input-medio';}else{tamanho = 'input-maior';}}

            var required2 = ''; if(item.REQUERED == 1){required2 = '<span style="color:red;">*</span>'; }else{required2 = '';}
            
            if(flag == 0){
                Input  += '<div class="div-obj-input item-'+id+'">';
            }

            if(flag < 2){
                    Input  += '<button ng-click="vm.Create.editInput('+id+')" class="btn btn-primary action-items">';
                    Input  += '        <span class="glyphicon glyphicon-pencil"></span>';
                    Input  += '</button>';
                    Input  += '<button ng-click="vm.Create.deleteInput('+id+')" class="btn btn-danger action-items">';
                    Input  += '        <span class="glyphicon glyphicon-remove"></span>';
                    Input  += '</button>';
            }
            
            if(flag == 2){
                Input  += '<div class="itens-inputs">';
            }

            switch(item.TIPO) {
            case '1':
                Input += '<div class="form-group">'+
                         '   <label>'+item.TEXTO+':</label>'+
                         '   <input '+ng_model+' '+disabled+' type="text" name="titulo" class="form-control '+tamanho+'"  value="'+item.VALOR+'" '+required+' autocomplete="off" '+required+'>'+
                         '</div> ';
                break;
            case '2':
                Input += '<div class="form-group">'+
                         '   <label>'+item.TEXTO+':</label>'+
                         '   <input  '+ng_model+' '+disabled+' type="number" name="titulo" autocomplete="off" value="'+item.VALOR+'" min="'+item.MIN+'" max="'+item.MAX+'" step="'+item.STEP+'" class="form-control '+tamanho+'"  '+required+'>'+
                         '</div> ';
                break;
            case '3':
                Input += '<div class="form-group">'+
                         '   <label>'+item.TEXTO+':</label>'+
                         '   <input  ng-change="console.log('+that.model+'['+id+'].VALOR)" '+ng_model+' '+disabled+' placeholder="yyyy-MM-dd" type="date" autocomplete="off" name="data_utilizacao" class="form-control " style="width: 163px;" '+required+'>'+
                         '</div> ';
                break;
            case '4':
                Input += '<div class="form-group">'+
                         '   <label>'+item.TEXTO+':'+required2+'</label><div></div><div class="itens-group">';

                    angular.forEach(item.ITENS, function(iten, key) {
                        var ch = '';
                        if(iten.SELECTED){
                            ch = 'checked';
                        }

                        if(that.model.length > 0){ng_model = 'ng-model="'+that.model+'['+id+'].ITENS['+key+'].SELECTED"'; }else{ng_model = '';}
                        Input += '<div class="item-checkbox"><input '+ng_model+' '+disabled+' type="checkbox" autocomplete="off" value="'+iten.VALOR+'" '+ch+' class="form-control" '+required+'><span class="label-checkbox">'+iten.TEXTO+'</span></div>';
                        
                    });

                Input += '</div></div>';

                break;
            case '5':
                Input += '<div class="form-group">'+
                         '   <label>'+item.TEXTO+':'+required2+'</label><div></div><div class="itens-group">';

                    angular.forEach(item.ITENS, function(iten, key) {
                        var ch = '';
                        if(iten.SELECTED){
                            ch = 'checked';
                        }
                        //if(that.model.length > 0){ng_model = 'ng-model="'+that.model+'['+(that.itens.length - 1)+'].ITENS['+key+'].SELECTED"'; }else{ng_model = '';}
                        Input += '<div class="item-checkbox"><input '+ng_model+' '+disabled+' type="radio" autocomplete="off" name="'+item.NOME+'" value="'+iten.VALOR+'" '+ch+' class="form-control"><span class="label-checkbox">'+iten.TEXTO+'</span></div>';
                        
                    });

                Input += '</div></div>';
                break;
            case '6':
                Input += '<div class="form-group">'+
                         '   <label>'+item.TEXTO+':</label>';
                Input += '      <form><div class="item-checkbox" style="display: inline-flex;">';
                Input += '          <input  '+ng_model+' '+disabled+' class="input-range  '+tamanho+'" type="range"  autocomplete="off" name="amountRange" min="'+item.MIN+'" max="'+item.MAX+'" step="'+item.STEP+'" value="'+item.VALOR+'" oninput="this.form.amountInput.value=this.value" '+required+' />';
                Input += '          <input class="form-control valor-range" disabled type="number" autocomplete="off" name="amountInput" min="'+item.MIN+'" max="'+item.MAX+'" step="'+item.STEP+'" value="'+item.VALOR+'" oninput="this.form.amountRange.value=this.value" '+required+' />';
                Input += '      </div></form>';
                Input += '</div> ';
                break;
            case '7':

                item.CONSULTA = consulta.getNew();
                item.CONSULTA.option.filtro_sql = [];
                item.CONSULTA.option.filtro_sql.push({SQL_ID : item.SQL_ID, PAINEL_ID: item.PAINEL_ID});

                item.CONSULTA.autoload = item.AUTOLOAD;

                    alerta  = '';
                    vinculo = '';
                    if(item.VINCULO_CAMPO.length > 0){
                        alerta += '<span style="margin-left: 5px;"';
                        alerta += 'class="glyphicon glyphicon-info-sign" ';
                        alerta += 't-title="Depende de \''+item.VINCULO_DESCRICAO+'\'"> ';
                        alerta += '</span>';

                        item.CONSULTA.require = [];
                        
                        angular.forEach(item.VINCULO_CAMPO, function(a, key) {
                            item.CONSULTA.require.push(that.model_itens[a].CONSULTA);
                        });
                        
                        item.CONSULTA.vincular();

                        var a = item.CONSULTA.option.filtro_sql;
                        item.CONSULTA.option.filtro_sql = [];
                        item.CONSULTA.option.filtro_sql.push({SQL_ID : item.SQL_ID, PAINEL_ID: item.PAINEL_ID});

                        angular.forEach(a, function(b, key) {
                            item.CONSULTA.option.filtro_sql.push(b);
                        });

                        
                    }

                var ng_model = ''; if(that.model.length > 0){ng_model = that.model+'['+id+'].CONSULTA'; }else{ng_model = '';}

                var tamanho  = ''; if(item.TAMANHO == 1){tamanho = 'input-menor';}else{if(item.TAMANHO == 2){tamanho = 'input-medio';}else{tamanho = 'input-maior';}}

                var CAMPO_TABELA   = (item.CAMPO_TABELA   + '').split(',');
                var CAMPOS_RETORNO = (item.CAMPOS_RETORNO + '').split(',');
                var DESC_TABELA    = (item.DESC_TABELA    + '').split(',');

                item.CONSULTA.option.obj_ret = [];
                angular.forEach(CAMPOS_RETORNO, function(iten, key) {
                    item.CONSULTA.option.obj_ret.push(iten);
                });

                item.CONSULTA.option.campos_tabela = [];
                angular.forEach(CAMPO_TABELA, function(iten, key) {
                    item.CONSULTA.option.campos_tabela.push([iten,DESC_TABELA[key]]);
                });

                item.CONSULTA.disable(item.DISABLED);           

                item.CONSULTA.componente             = '.consulta_' + id,
                item.CONSULTA.model                  = ng_model,
                item.CONSULTA.option.label_descricao = item.TEXTO + alerta,
                item.CONSULTA.option.obj_consulta    = item.URL_CONSULTA,
                item.CONSULTA.option.tamanho_Input   = tamanho;
                item.CONSULTA.option.class           = 'consulta_item_' + id,
                item.CONSULTA.option.tamanho_tabela  = item.TAMANHO_TABELA;
                item.CONSULTA.option.required        = item.REQUERED == 1;

                var h = item.CONSULTA.html();

                Input += '<div class="consulta_'+id+'" >'+h+'</div>';

                break;
            case '8':
                Input += '<div class="form-group">'+
                         '   <label>'+item.TEXTO+':</label>'+
                         '   <input  '+ng_model+' '+disabled+' type="time" name="titulo" class="form-control " autocomplete="off" style="width: 94px;"  '+required+'>'+
                         '</div> ';
                break;
            case '9':

                var ng_model2 = ''; if(that.model.length > 0){ng_model2 = 'ng-model="'+that.model+'['+id+'].VALOR"'; }else{ng_model2 = '';}
                var ng_model3 = ''; if(that.model.length > 0){ng_model3 = that.model+'['+id+']'; }else{ng_model3 = '';}
                var vinculo   = '';
                var alerta    = '';

                //item.VALOR = {};

                        vinculo = '';
                        if(item.VINCULO_CAMPO > 0){
                            alerta += '<span  style="margin-left: 5px;"';
                            alerta += 'class="glyphicon glyphicon-info-sign" ';
                            alerta += 't-title="Depende de \''+item.VINCULO_DESCRICAO+'\'"> ';
                            alerta += '</span>';

                            var model = ''; if(that.model.length > 0){model = that.model+'['+item.VINCULO_CAMPO+'].VALOR.VALOR'; }else{model = '';}

                                var array = []; 
                                var tmp   = 0;

                                angular.forEach(item.VINCULO_ITENS, function(n, k) {
                                    if(tmp != n.VALOR_VINCULO){
                                        tmp = n.VALOR_VINCULO;
                                        if(array[tmp] == undefined){
                                            array[tmp] = [];
                                        }
                                        if(n.STATUS == 1){
                                            array[tmp].push(n.VALOR_CAMPO);
                                        }
                                    }
                                });

                            item.VINCULO_ITENS = array;
                            vinculo += '| inArray:'+ng_model3+'.VINCULO_ITENS['+model+']:"VALOR"';

                        }

                if(that.model.length > 0){ng_model = 'ng-options=\'iten.TEXTO for iten in '+that.model+'['+id+'].ITENS '+vinculo+'\''; }else{ng_model = '';}
                

                Input +='<div class="form-group"><label>'+item.TEXTO+':'+alerta+'</label>';

                if(that.model.length > 0){
                    Input += '   <select '+ng_model2+' '+disabled+' class="form-control '+tamanho+'" '+required+'  '+ng_model+'>';
                    Input += '   <option value=""> - SELECIONE - </option>'
                }else{

                    Input += '   <select class="form-control '+tamanho+'" '+required+' >';

                    console.log(item.VINCULO_CAMPO);
                    
                    angular.forEach(item.ITENS, function(iten, key) {
                        var sl = '';
                        if(iten.SELECTED){
                            sl = 'selected';
                        }

                        Input += '<option value="'+iten.VALOR+'" '+sl+'>'+iten.TEXTO+'</option>';
                    });
                }

                Input +='   </select></div>';

                break;
            case '10':

                Input +='<div class="form-group"><label>'+item.TEXTO+':'+required2+'</label>';
                Input += '<textarea '+disabled+' name="" '+ng_model+' class="form-control" rows="5" cols="70"></textarea><span class="contador"><span>{{('+ng_modelvalor+'.VALOR + \'\').length}}</span> caracteres</span>';
                Input +='</div>';

                break;
            default:
                showErro('Tipo do Input não encontrado, Tipo:'+item.TIPO);
            }

            if(flag == 2 || flag == 0){
                Input  += '</div>';
            }

            return Input;    
        },
        setData: function(data) {
            angular.extend(this, data);
        },        
        consultar : function (args) {

        },
        addNewItem : function () {
            this.Input.ITENS.push({TEXTO : 'ITEM '+(this.Input.ITENS.length + 1),SELECTED: false, VALOR : ''+(this.Input.ITENS.length + 1)});
        },
        validarInfo : function (tipo) {

            this.ocultarInfo();
            $('.info_padrao').css('display','block');

            switch(tipo) {
            case '1': //Texto
                $('.info_tamanho').css('display','block');
                break;
            case '2': //Número
                $('.info_tamanho').css('display','block');
                $('.info_min_max').css('display','block');
                break;
            case '3': //Data
                //$('.info_tamanho').css('display','block');
                break;
            case '4': //Check
                $('.info_new_item').css('display','block');
                break;
            case '5': //Radio
                $('.info_new_item').css('display','block');
                break;
            case '6': //Range
                $('.info_tamanho').css('display','block');
                $('.info_min_max').css('display','block');
                break;
            case '7': //Search
                $('.info_tamanho').css('display','block');
                $('.info_search').css('display','block');
                break;
            case '8': //Time
               // $('.info_tamanho').css('display','block');
            case '9': //Time
                $('.info_new_item').css('display','block');
                break;
            case '10': //Time
                //$('.info_new_item').css('display','block');
                break;
            default:
                this.ocultarInfo();
            }
        },
        ocultarInfo : function (tipo){
            $('.item_info').css('display','none');    
        },
        uriHistory : function() {
            window.history.replaceState('', '', encodeURI(urlhost + '/_11140?'+$httpParamSerializer(this)));
        },
        gravar: function() {
            window.history.replaceState('', '', encodeURI(urlhost + '/_11140?'+$httpParamSerializer(this)));
        },
        edtInput: function(id) {
            var item  = null;
            var index = 0;
            angular.forEach(this.Inputs, function(obj, key) {
                if(obj.ID == id){
                    index = key;
                    item = obj;
                }
            });

            var html = this.montarHtml(this.Input,id,1);
            angular.copy(this.Input,this.Inputs[index]);

            if(item.TIPO == 7){

                var tamanho  = ''; if(item.TAMANHO == 1){tamanho = 'input-menor';}else{if(item.TAMANHO == 2){tamanho = 'input-medio';}else{tamanho = 'input-maior';}}
                    
                item.CONSULTA.componente             = '.consulta_' + id,
                item.CONSULTA.model                  = 'vm.Create.Inputs['+id+'].CONSULTA',
                item.CONSULTA.option.label_descricao = item.TEXTO,
                item.CONSULTA.option.obj_consulta    = '/_11140/Consultar',
                item.CONSULTA.option.tamanho_Input   = tamanho;
                item.CONSULTA.option.class           = 'consulta_item_' + id,
                item.CONSULTA.option.tamanho_tabela  = 300;
                item.CONSULTA.option.required        = (item.REQUERED == 1);

                console.log(item.CONSULTA);

                item.CONSULTA.compile();

            }else{
                var obj   = $('.item-'+id+'');
                var scope = obj.scope(); 
                obj.html(html);
                var obj   = $('.item-'+id+'');
                $compile(obj.contents())(scope);
            }

            $('#modal-add-Inputs').modal('hide');
        },
        addInput: function() {

            var Input   = '';
            var item    = angular.copy(this.Input, item);
            var erro    = 0;
            var msgErro = '';

            function validarString(value){
                var ret = 0;

                if(/[!@#$%*()_+^&{}}:;?.]/gm.test(value)){
                    ret = 1;
                }

                if(item.NOME == ''){
                    ret = 1;    
                }

                return ret;
            };

            if(validarString(item.NOME)){    
                msgErro = 'Este nome é inválido. contem "!@#$%*()_+^&{}}:;?." ou ""';
                erro    = 1;
            }

            if(item.TIPO == 0){    
                msgErro = 'Selecione um tipo';
                erro    = 1;
            }

            angular.forEach(this.Inputs, function(obj, key) {
              if(obj.NOME == item.NOME){
                msgErro = 'Este nome já existe';
                erro    = 1;
              }
            });

            if(erro == 0){
                var that = this;
                Input = this.montarHtml(item,this.Inputs.length,0);

                item.EDIT = 0;
                item.ID   = this.Inputs.length;

                this.Inputs.push(item);
                this.Input.EDIT = 0;

                var obj   = $('.conteiner-Inputs');
                var scope = obj.scope(); 
                obj.append(Input);
                var obj   = $('.conteiner-Inputs').find('.div-obj-input').last();
                $compile(obj.contents())(scope);

                if(item.TIPO == 7){
                    var id = (that.Inputs.length -1);
                    item.CONSULTA = consulta.getNew();

                    var tamanho  = ''; if(item.TAMANHO == 1){tamanho = 'input-menor';}else{if(item.TAMANHO == 2){tamanho = 'input-medio';}else{tamanho = 'input-maior';}}
                    
                    item.CONSULTA.componente             = '.consulta_' + id,
                    item.CONSULTA.model                  = 'vm.Create.Inputs['+id+'].CONSULTA',
                    item.CONSULTA.option.label_descricao = item.TEXTO,
                    item.CONSULTA.option.obj_consulta    = '/_11140/Consultar',
                    item.CONSULTA.option.tamanho_Input   = tamanho;
                    item.CONSULTA.option.class           = 'consulta_item_' + id,
                    item.CONSULTA.option.tamanho_tabela  = 300;
                    item.CONSULTA.option.required        = (item.REQUERED == 1);

                    console.log(item.CONSULTA);

                    item.CONSULTA.compile();
                    
                }


                $('#modal-add-Inputs').modal('hide');

            }else{
                showErro(msgErro);
            }
        },
        editInput: function(id) {
            var index = 0;
            angular.forEach(this.Inputs, function(obj, key) {
                if(obj.ID == id){
                    index = key;
                }
            }); 

            var item = angular.copy(this.Inputs[index], item); ;

            item.EDIT = 1;

            this.Input = item;

            this.validarInfo(item.TIPO);
            $('#modal-add-Inputs').modal('show');
        },
        deleteInput: function(id) {

            var index = 0;
            angular.forEach(this.Inputs, function(obj, key) {
                if(obj.ID == id){
                    index = key;
                }
            });

            var item = this.Inputs[index];
            var obj = this.Inputs;

            addConfirme('Excluir Input?',
                    'Deseja realmente excluir o Input:'+item.TEXTO+' ('+item.NOME+')'
                    ,[obtn_ok,obtn_cancelar],
                [
                {ret:1,func:function(e){

                    obj.splice(index, 1);
                    $('.item-'+id).remove();

                }},
                {ret:2,func:function(e){


                }},
                ]  
            );
            

        },
        modalAddInput: function() {
            angular.copy(Input_temp, this.Input);
            this.Input.NOME = 'OBJETO'+(this.Inputs.length + 1);
            this.ocultarInfo();

            console.log('Teste');
            $('#modal-add-Inputs').modal('show');
        },
        model_itens : [],
        Input: {},
        Inputs : []
    };

    /**
     * Return the constructor function
     */
    return Create;
};
/*! 12.2.13 */
!window.XMLHttpRequest||window.FileAPI&&FileAPI.shouldLoad||(window.XMLHttpRequest.prototype.setRequestHeader=function(a){return function(b,c){if("__setXHR_"===b){var d=c(this);d instanceof Function&&d(this)}else a.apply(this,arguments)}}(window.XMLHttpRequest.prototype.setRequestHeader));var ngFileUpload=angular.module("ngFileUpload",[]);ngFileUpload.version="12.2.13",ngFileUpload.service("UploadBase",["$http","$q","$timeout",function(a,b,c){function d(d){function e(a){j.notify&&j.notify(a),k.progressFunc&&c(function(){k.progressFunc(a)})}function h(a){return null!=d._start&&g?{loaded:a.loaded+d._start,total:d._file&&d._file.size||a.total,type:a.type,config:d,lengthComputable:!0,target:a.target}:a}function i(){a(d).then(function(a){if(g&&d._chunkSize&&!d._finished&&d._file){var b=d._file&&d._file.size||0;e({loaded:Math.min(d._end,b),total:b,config:d,type:"progress"}),f.upload(d,!0)}else d._finished&&delete d._finished,j.resolve(a)},function(a){j.reject(a)},function(a){j.notify(a)})}d.method=d.method||"POST",d.headers=d.headers||{};var j=d._deferred=d._deferred||b.defer(),k=j.promise;return d.disableProgress||(d.headers.__setXHR_=function(){return function(a){a&&a.upload&&a.upload.addEventListener&&(d.__XHR=a,d.xhrFn&&d.xhrFn(a),a.upload.addEventListener("progress",function(a){a.config=d,e(h(a))},!1),a.upload.addEventListener("load",function(a){a.lengthComputable&&(a.config=d,e(h(a)))},!1))}}),g?d._chunkSize&&d._end&&!d._finished?(d._start=d._end,d._end+=d._chunkSize,i()):d.resumeSizeUrl?a.get(d.resumeSizeUrl).then(function(a){d._start=d.resumeSizeResponseReader?d.resumeSizeResponseReader(a.data):parseInt((null==a.data.size?a.data:a.data.size).toString()),d._chunkSize&&(d._end=d._start+d._chunkSize),i()},function(a){throw a}):d.resumeSize?d.resumeSize().then(function(a){d._start=a,d._chunkSize&&(d._end=d._start+d._chunkSize),i()},function(a){throw a}):(d._chunkSize&&(d._start=0,d._end=d._start+d._chunkSize),i()):i(),k.success=function(a){return k.then(function(b){a(b.data,b.status,b.headers,d)}),k},k.error=function(a){return k.then(null,function(b){a(b.data,b.status,b.headers,d)}),k},k.progress=function(a){return k.progressFunc=a,k.then(null,null,function(b){a(b)}),k},k.abort=k.pause=function(){return d.__XHR&&c(function(){d.__XHR.abort()}),k},k.xhr=function(a){return d.xhrFn=function(b){return function(){b&&b.apply(k,arguments),a.apply(k,arguments)}}(d.xhrFn),k},f.promisesCount++,k["finally"]&&k["finally"]instanceof Function&&k["finally"](function(){f.promisesCount--}),k}function e(a){var b={};for(var c in a)a.hasOwnProperty(c)&&(b[c]=a[c]);return b}var f=this;f.promisesCount=0,this.isResumeSupported=function(){return window.Blob&&window.Blob.prototype.slice};var g=this.isResumeSupported();this.isUploadInProgress=function(){return f.promisesCount>0},this.rename=function(a,b){return a.ngfName=b,a},this.jsonBlob=function(a){null==a||angular.isString(a)||(a=JSON.stringify(a));var b=new window.Blob([a],{type:"application/json"});return b._ngfBlob=!0,b},this.json=function(a){return angular.toJson(a)},this.isFile=function(a){return null!=a&&(a instanceof window.Blob||a.flashId&&a.name&&a.size)},this.upload=function(a,b){function c(b,c){if(b._ngfBlob)return b;if(a._file=a._file||b,null!=a._start&&g){a._end&&a._end>=b.size&&(a._finished=!0,a._end=b.size);var d=b.slice(a._start,a._end||b.size);return d.name=b.name,d.ngfName=b.ngfName,a._chunkSize&&(c.append("_chunkSize",a._chunkSize),c.append("_currentChunkSize",a._end-a._start),c.append("_chunkNumber",Math.floor(a._start/a._chunkSize)),c.append("_totalSize",a._file.size)),d}return b}function h(b,d,e){if(void 0!==d)if(angular.isDate(d)&&(d=d.toISOString()),angular.isString(d))b.append(e,d);else if(f.isFile(d)){var g=c(d,b),i=e.split(",");i[1]&&(g.ngfName=i[1].replace(/^\s+|\s+$/g,""),e=i[0]),a._fileKey=a._fileKey||e,b.append(e,g,g.ngfName||g.name)}else if(angular.isObject(d)){if(d.$$ngfCircularDetection)throw"ngFileUpload: Circular reference in config.data. Make sure specified data for Upload.upload() has no circular reference: "+e;d.$$ngfCircularDetection=!0;try{for(var j in d)if(d.hasOwnProperty(j)&&"$$ngfCircularDetection"!==j){var k=null==a.objectKey?"[i]":a.objectKey;d.length&&parseInt(j)>-1&&(k=null==a.arrayKey?k:a.arrayKey),h(b,d[j],e+k.replace(/[ik]/g,j))}}finally{delete d.$$ngfCircularDetection}}else b.append(e,d)}function i(){a._chunkSize=f.translateScalars(a.resumeChunkSize),a._chunkSize=a._chunkSize?parseInt(a._chunkSize.toString()):null,a.headers=a.headers||{},a.headers["Content-Type"]=void 0,a.transformRequest=a.transformRequest?angular.isArray(a.transformRequest)?a.transformRequest:[a.transformRequest]:[],a.transformRequest.push(function(b){var c,d=new window.FormData;b=b||a.fields||{},a.file&&(b.file=a.file);for(c in b)if(b.hasOwnProperty(c)){var e=b[c];a.formDataAppender?a.formDataAppender(d,c,e):h(d,e,c)}return d})}return b||(a=e(a)),a._isDigested||(a._isDigested=!0,i()),d(a)},this.http=function(b){return b=e(b),b.transformRequest=b.transformRequest||function(b){return window.ArrayBuffer&&b instanceof window.ArrayBuffer||b instanceof window.Blob?b:a.defaults.transformRequest[0].apply(this,arguments)},b._chunkSize=f.translateScalars(b.resumeChunkSize),b._chunkSize=b._chunkSize?parseInt(b._chunkSize.toString()):null,d(b)},this.translateScalars=function(a){if(angular.isString(a)){if(a.search(/kb/i)===a.length-2)return parseFloat(1024*a.substring(0,a.length-2));if(a.search(/mb/i)===a.length-2)return parseFloat(1048576*a.substring(0,a.length-2));if(a.search(/gb/i)===a.length-2)return parseFloat(1073741824*a.substring(0,a.length-2));if(a.search(/b/i)===a.length-1)return parseFloat(a.substring(0,a.length-1));if(a.search(/s/i)===a.length-1)return parseFloat(a.substring(0,a.length-1));if(a.search(/m/i)===a.length-1)return parseFloat(60*a.substring(0,a.length-1));if(a.search(/h/i)===a.length-1)return parseFloat(3600*a.substring(0,a.length-1))}return a},this.urlToBlob=function(c){var d=b.defer();return a({url:c,method:"get",responseType:"arraybuffer"}).then(function(a){var b=new Uint8Array(a.data),e=a.headers("content-type")||"image/WebP",f=new window.Blob([b],{type:e}),g=c.match(/.*\/(.+?)(\?.*)?$/);g.length>1&&(f.name=g[1]),d.resolve(f)},function(a){d.reject(a)}),d.promise},this.setDefaults=function(a){this.defaults=a||{}},this.defaults={},this.version=ngFileUpload.version}]),ngFileUpload.service("Upload",["$parse","$timeout","$compile","$q","UploadExif",function(a,b,c,d,e){function f(a,b,c){var e=[i.emptyPromise()];return angular.forEach(a,function(d,f){0===d.type.indexOf("image/jpeg")&&i.attrGetter("ngfFixOrientation",b,c,{$file:d})&&e.push(i.happyPromise(i.applyExifRotation(d),d).then(function(b){a.splice(f,1,b)}))}),d.all(e)}function g(a,b,c,e){var f=i.attrGetter("ngfResize",b,c);if(!f||!i.isResizeSupported()||!a.length)return i.emptyPromise();if(f instanceof Function){var g=d.defer();return f(a).then(function(d){h(d,a,b,c,e).then(function(a){g.resolve(a)},function(a){g.reject(a)})},function(a){g.reject(a)})}return h(f,a,b,c,e)}function h(a,b,c,e,f){function g(d,g){if(0===d.type.indexOf("image")){if(a.pattern&&!i.validatePattern(d,a.pattern))return;a.resizeIf=function(a,b){return i.attrGetter("ngfResizeIf",c,e,{$width:a,$height:b,$file:d})};var j=i.resize(d,a);h.push(j),j.then(function(a){b.splice(g,1,a)},function(a){d.$error="resize",(d.$errorMessages=d.$errorMessages||{}).resize=!0,d.$errorParam=(a?(a.message?a.message:a)+": ":"")+(d&&d.name),f.$ngfValidations.push({name:"resize",valid:!1}),i.applyModelValidation(f,b)})}}for(var h=[i.emptyPromise()],j=0;j<b.length;j++)g(b[j],j);return d.all(h)}var i=e;return i.getAttrWithDefaults=function(a,b){if(null!=a[b])return a[b];var c=i.defaults[b];return null==c?c:angular.isString(c)?c:JSON.stringify(c)},i.attrGetter=function(b,c,d,e){var f=this.getAttrWithDefaults(c,b);if(!d)return f;try{return e?a(f)(d,e):a(f)(d)}catch(g){if(b.search(/min|max|pattern/i))return f;throw g}},i.shouldUpdateOn=function(a,b,c){var d=i.attrGetter("ngfModelOptions",b,c);return d&&d.updateOn?d.updateOn.split(" ").indexOf(a)>-1:!0},i.emptyPromise=function(){var a=d.defer(),c=arguments;return b(function(){a.resolve.apply(a,c)}),a.promise},i.rejectPromise=function(){var a=d.defer(),c=arguments;return b(function(){a.reject.apply(a,c)}),a.promise},i.happyPromise=function(a,c){var e=d.defer();return a.then(function(a){e.resolve(a)},function(a){b(function(){throw a}),e.resolve(c)}),e.promise},i.updateModel=function(c,d,e,h,j,k,l){function m(f,g,j,l,m){d.$$ngfPrevValidFiles=f,d.$$ngfPrevInvalidFiles=g;var n=f&&f.length?f[0]:null,o=g&&g.length?g[0]:null;c&&(i.applyModelValidation(c,f),c.$setViewValue(m?n:f)),h&&a(h)(e,{$files:f,$file:n,$newFiles:j,$duplicateFiles:l,$invalidFiles:g,$invalidFile:o,$event:k});var p=i.attrGetter("ngfModelInvalid",d);p&&b(function(){a(p).assign(e,m?o:g)}),b(function(){})}function n(){function a(a,b){return a.name===b.name&&(a.$ngfOrigSize||a.size)===(b.$ngfOrigSize||b.size)&&a.type===b.type}function b(b){var c;for(c=0;c<r.length;c++)if(a(b,r[c]))return!0;for(c=0;c<s.length;c++)if(a(b,s[c]))return!0;return!1}if(j){q=[],t=[];for(var c=0;c<j.length;c++)b(j[c])?t.push(j[c]):q.push(j[c])}}function o(a){return angular.isArray(a)?a:[a]}function p(){function a(){b(function(){m(w?r.concat(v):v,w?s.concat(u):u,j,t,x)},z&&z.debounce?z.debounce.change||z.debounce:0)}var f=y?q:v;g(f,d,e,c).then(function(){y?i.validate(q,w?r.length:0,c,d,e).then(function(b){v=b.validsFiles,u=b.invalidsFiles,a()}):a()},function(){for(var b=0;b<f.length;b++){var c=f[b];if("resize"===c.$error){var d=v.indexOf(c);d>-1&&(v.splice(d,1),u.push(c)),a()}}})}var q,r,s,t=[],u=[],v=[];r=d.$$ngfPrevValidFiles||[],s=d.$$ngfPrevInvalidFiles||[],c&&c.$modelValue&&(r=o(c.$modelValue));var w=i.attrGetter("ngfKeep",d,e);q=(j||[]).slice(0),("distinct"===w||i.attrGetter("ngfKeepDistinct",d,e)===!0)&&n(d,e);var x=!w&&!i.attrGetter("ngfMultiple",d,e)&&!i.attrGetter("multiple",d);if(!w||q.length){i.attrGetter("ngfBeforeModelChange",d,e,{$files:j,$file:j&&j.length?j[0]:null,$newFiles:q,$duplicateFiles:t,$event:k});var y=i.attrGetter("ngfValidateAfterResize",d,e),z=i.attrGetter("ngfModelOptions",d,e);i.validate(q,w?r.length:0,c,d,e).then(function(a){l?m(q,[],j,t,x):(z&&z.allowInvalid||y?v=q:(v=a.validFiles,u=a.invalidFiles),i.attrGetter("ngfFixOrientation",d,e)&&i.isExifSupported()?f(v,d,e).then(function(){p()}):p())})}},i}]),ngFileUpload.directive("ngfSelect",["$parse","$timeout","$compile","Upload",function(a,b,c,d){function e(a){var b=a.match(/Android[^\d]*(\d+)\.(\d+)/);if(b&&b.length>2){var c=d.defaults.androidFixMinorVersion||4;return parseInt(b[1])<4||parseInt(b[1])===c&&parseInt(b[2])<c}return-1===a.indexOf("Chrome")&&/.*Windows.*Safari.*/.test(a)}function f(a,b,c,d,f,h,i,j){function k(){return"input"===b[0].tagName.toLowerCase()&&c.type&&"file"===c.type.toLowerCase()}function l(){return t("ngfChange")||t("ngfSelect")}function m(b){if(j.shouldUpdateOn("change",c,a)){var e=b.__files_||b.target&&b.target.files,f=[];if(!e)return;for(var g=0;g<e.length;g++)f.push(e[g]);j.updateModel(d,c,a,l(),f.length?f:null,b)}}function n(a,d){function e(b){a.attr("id","ngf-"+b),d.attr("id","ngf-label-"+b)}for(var f=0;f<b[0].attributes.length;f++){var g=b[0].attributes[f];"type"!==g.name&&"class"!==g.name&&"style"!==g.name&&("id"===g.name?(e(g.value),u.push(c.$observe("id",e))):a.attr(g.name,g.value||"required"!==g.name&&"multiple"!==g.name?g.value:g.name))}}function o(){if(k())return b;var a=angular.element('<input type="file">'),c=angular.element("<label>upload</label>");return c.css("visibility","hidden").css("position","absolute").css("overflow","hidden").css("width","0px").css("height","0px").css("border","none").css("margin","0px").css("padding","0px").attr("tabindex","-1"),n(a,c),g.push({el:b,ref:c}),document.body.appendChild(c.append(a)[0]),a}function p(c){if(b.attr("disabled"))return!1;if(!t("ngfSelectDisabled",a)){var d=q(c);if(null!=d)return d;r(c);try{k()||document.body.contains(x[0])||(g.push({el:b,ref:x.parent()}),document.body.appendChild(x.parent()[0]),x.bind("change",m))}catch(f){}return e(navigator.userAgent)?setTimeout(function(){x[0].click()},0):x[0].click(),!1}}function q(a){var b=a.changedTouches||a.originalEvent&&a.originalEvent.changedTouches;if(b){if("touchstart"===a.type)return w=b[0].clientX,v=b[0].clientY,!0;if("touchend"===a.type){var c=b[0].clientX,d=b[0].clientY;if(Math.abs(c-w)>20||Math.abs(d-v)>20)return a.stopPropagation(),a.preventDefault(),!1}return!0}}function r(b){j.shouldUpdateOn("click",c,a)&&x.val()&&(x.val(null),j.updateModel(d,c,a,l(),null,b,!0))}function s(a){if(x&&!x.attr("__ngf_ie10_Fix_")){if(!x[0].parentNode)return void(x=null);a.preventDefault(),a.stopPropagation(),x.unbind("click");var b=x.clone();return x.replaceWith(b),x=b,x.attr("__ngf_ie10_Fix_","true"),x.bind("change",m),x.bind("click",s),x[0].click(),!1}x.removeAttr("__ngf_ie10_Fix_")}var t=function(a,b){return j.attrGetter(a,c,b)};j.registerModelChangeValidator(d,c,a);var u=[];t("ngfMultiple")&&u.push(a.$watch(t("ngfMultiple"),function(){x.attr("multiple",t("ngfMultiple",a))})),t("ngfCapture")&&u.push(a.$watch(t("ngfCapture"),function(){x.attr("capture",t("ngfCapture",a))})),t("ngfAccept")&&u.push(a.$watch(t("ngfAccept"),function(){x.attr("accept",t("ngfAccept",a))})),u.push(c.$observe("accept",function(){x.attr("accept",t("accept"))}));var v=0,w=0,x=b;k()||(x=o()),x.bind("change",m),k()?b.bind("click",r):b.bind("click touchstart touchend",p),-1!==navigator.appVersion.indexOf("MSIE 10")&&x.bind("click",s),d&&d.$formatters.push(function(a){return(null==a||0===a.length)&&x.val()&&x.val(null),a}),a.$on("$destroy",function(){k()||x.parent().remove(),angular.forEach(u,function(a){a()})}),h(function(){for(var a=0;a<g.length;a++){var b=g[a];document.body.contains(b.el[0])||(g.splice(a,1),b.ref.remove())}}),window.FileAPI&&window.FileAPI.ngfFixIE&&window.FileAPI.ngfFixIE(b,x,m)}var g=[];return{restrict:"AEC",require:"?ngModel",link:function(e,g,h,i){f(e,g,h,i,a,b,c,d)}}}]),function(){function a(a){return"img"===a.tagName.toLowerCase()?"image":"audio"===a.tagName.toLowerCase()?"audio":"video"===a.tagName.toLowerCase()?"video":/./}function b(b,c,d,e,f,g,h,i){function j(a){var g=b.attrGetter("ngfNoObjectUrl",f,d);b.dataUrl(a,g)["finally"](function(){c(function(){var b=(g?a.$ngfDataUrl:a.$ngfBlobUrl)||a.$ngfDataUrl;i?e.css("background-image","url('"+(b||"")+"')"):e.attr("src",b),b?e.removeClass("ng-hide"):e.addClass("ng-hide")})})}c(function(){var c=d.$watch(f[g],function(c){var k=h;if("ngfThumbnail"===g&&(k||(k={width:e[0].naturalWidth||e[0].clientWidth,height:e[0].naturalHeight||e[0].clientHeight}),0===k.width&&window.getComputedStyle)){var l=getComputedStyle(e[0]);l.width&&l.width.indexOf("px")>-1&&l.height&&l.height.indexOf("px")>-1&&(k={width:parseInt(l.width.slice(0,-2)),height:parseInt(l.height.slice(0,-2))})}return angular.isString(c)?(e.removeClass("ng-hide"),i?e.css("background-image","url('"+c+"')"):e.attr("src",c)):void(!c||!c.type||0!==c.type.search(a(e[0]))||i&&0!==c.type.indexOf("image")?e.addClass("ng-hide"):k&&b.isResizeSupported()?(k.resizeIf=function(a,e){return b.attrGetter("ngfResizeIf",f,d,{$width:a,$height:e,$file:c})},b.resize(c,k).then(function(a){j(a)},function(a){throw a})):j(c))});d.$on("$destroy",function(){c()})})}ngFileUpload.service("UploadDataUrl",["UploadBase","$timeout","$q",function(a,b,c){var d=a;return d.base64DataUrl=function(a){if(angular.isArray(a)){var b=c.defer(),e=0;return angular.forEach(a,function(c){d.dataUrl(c,!0)["finally"](function(){if(e++,e===a.length){var c=[];angular.forEach(a,function(a){c.push(a.$ngfDataUrl)}),b.resolve(c,a)}})}),b.promise}return d.dataUrl(a,!0)},d.dataUrl=function(a,e){if(!a)return d.emptyPromise(a,a);if(e&&null!=a.$ngfDataUrl||!e&&null!=a.$ngfBlobUrl)return d.emptyPromise(e?a.$ngfDataUrl:a.$ngfBlobUrl,a);var f=e?a.$$ngfDataUrlPromise:a.$$ngfBlobUrlPromise;if(f)return f;var g=c.defer();return b(function(){if(window.FileReader&&a&&(!window.FileAPI||-1===navigator.userAgent.indexOf("MSIE 8")||a.size<2e4)&&(!window.FileAPI||-1===navigator.userAgent.indexOf("MSIE 9")||a.size<4e6)){var c=window.URL||window.webkitURL;if(c&&c.createObjectURL&&!e){var f;try{f=c.createObjectURL(a)}catch(h){return void b(function(){a.$ngfBlobUrl="",g.reject()})}b(function(){if(a.$ngfBlobUrl=f,f){g.resolve(f,a),d.blobUrls=d.blobUrls||[],d.blobUrlsTotalSize=d.blobUrlsTotalSize||0,d.blobUrls.push({url:f,size:a.size}),d.blobUrlsTotalSize+=a.size||0;for(var b=d.defaults.blobUrlsMaxMemory||268435456,e=d.defaults.blobUrlsMaxQueueSize||200;(d.blobUrlsTotalSize>b||d.blobUrls.length>e)&&d.blobUrls.length>1;){var h=d.blobUrls.splice(0,1)[0];c.revokeObjectURL(h.url),d.blobUrlsTotalSize-=h.size}}})}else{var i=new FileReader;i.onload=function(c){b(function(){a.$ngfDataUrl=c.target.result,g.resolve(c.target.result,a),b(function(){delete a.$ngfDataUrl},1e3)})},i.onerror=function(){b(function(){a.$ngfDataUrl="",g.reject()})},i.readAsDataURL(a)}}else b(function(){a[e?"$ngfDataUrl":"$ngfBlobUrl"]="",g.reject()})}),f=e?a.$$ngfDataUrlPromise=g.promise:a.$$ngfBlobUrlPromise=g.promise,f["finally"](function(){delete a[e?"$$ngfDataUrlPromise":"$$ngfBlobUrlPromise"]}),f},d}]),ngFileUpload.directive("ngfSrc",["Upload","$timeout",function(a,c){return{restrict:"AE",link:function(d,e,f){b(a,c,d,e,f,"ngfSrc",a.attrGetter("ngfResize",f,d),!1)}}}]),ngFileUpload.directive("ngfBackground",["Upload","$timeout",function(a,c){return{restrict:"AE",link:function(d,e,f){b(a,c,d,e,f,"ngfBackground",a.attrGetter("ngfResize",f,d),!0)}}}]),ngFileUpload.directive("ngfThumbnail",["Upload","$timeout",function(a,c){return{restrict:"AE",link:function(d,e,f){var g=a.attrGetter("ngfSize",f,d);b(a,c,d,e,f,"ngfThumbnail",g,a.attrGetter("ngfAsBackground",f,d))}}}]),ngFileUpload.config(["$compileProvider",function(a){a.imgSrcSanitizationWhitelist&&a.imgSrcSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|webcal|local|file|data|blob):/),a.aHrefSanitizationWhitelist&&a.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|webcal|local|file|data|blob):/)}]),ngFileUpload.filter("ngfDataUrl",["UploadDataUrl","$sce",function(a,b){return function(c,d,e){if(angular.isString(c))return b.trustAsResourceUrl(c);var f=c&&((d?c.$ngfDataUrl:c.$ngfBlobUrl)||c.$ngfDataUrl);return c&&!f?(!c.$ngfDataUrlFilterInProgress&&angular.isObject(c)&&(c.$ngfDataUrlFilterInProgress=!0,a.dataUrl(c,d)),""):(c&&delete c.$ngfDataUrlFilterInProgress,(c&&f?e?b.trustAsResourceUrl(f):f:c)||"")}}])}(),ngFileUpload.service("UploadValidate",["UploadDataUrl","$q","$timeout",function(a,b,c){function d(a){var b="",c=[];if(a.length>2&&"/"===a[0]&&"/"===a[a.length-1])b=a.substring(1,a.length-1);else{var e=a.split(",");if(e.length>1)for(var f=0;f<e.length;f++){var g=d(e[f]);g.regexp?(b+="("+g.regexp+")",f<e.length-1&&(b+="|")):c=c.concat(g.excludes)}else 0===a.indexOf("!")?c.push("^((?!"+d(a.substring(1)).regexp+").)*$"):(0===a.indexOf(".")&&(a="*"+a),b="^"+a.replace(new RegExp("[.\\\\+*?\\[\\^\\]$(){}=!<>|:\\-]","g"),"\\$&")+"$",b=b.replace(/\\\*/g,".*").replace(/\\\?/g,"."))}return{regexp:b,excludes:c}}function e(a,b){null==b||a.$dirty||(a.$setDirty?a.$setDirty():a.$dirty=!0)}var f=a;return f.validatePattern=function(a,b){if(!b)return!0;var c=d(b),e=!0;if(c.regexp&&c.regexp.length){var f=new RegExp(c.regexp,"i");e=null!=a.type&&f.test(a.type)||null!=a.name&&f.test(a.name)}for(var g=c.excludes.length;g--;){var h=new RegExp(c.excludes[g],"i");e=e&&(null==a.type||h.test(a.type))&&(null==a.name||h.test(a.name))}return e},f.ratioToFloat=function(a){var b=a.toString(),c=b.search(/[x:]/i);return b=c>-1?parseFloat(b.substring(0,c))/parseFloat(b.substring(c+1)):parseFloat(b)},f.registerModelChangeValidator=function(a,b,c){a&&a.$formatters.push(function(d){if(a.$dirty){var e=d;d&&!angular.isArray(d)&&(e=[d]),f.validate(e,0,a,b,c).then(function(){f.applyModelValidation(a,e)})}return d})},f.applyModelValidation=function(a,b){e(a,b),angular.forEach(a.$ngfValidations,function(b){a.$setValidity(b.name,b.valid)})},f.getValidationAttr=function(a,b,c,d,e){var g="ngf"+c[0].toUpperCase()+c.substr(1),h=f.attrGetter(g,a,b,{$file:e});if(null==h&&(h=f.attrGetter("ngfValidate",a,b,{$file:e}))){var i=(d||c).split(".");h=h[i[0]],i.length>1&&(h=h&&h[i[1]])}return h},f.validate=function(a,c,d,e,g){function h(b,c,h){if(a){for(var i=a.length,j=null;i--;){var n=a[i];if(n){var o=f.getValidationAttr(e,g,b,c,n);null!=o&&(h(n,o,i)||(-1===k.indexOf(b)?(n.$error=b,(n.$errorMessages=n.$errorMessages||{})[b]=!0,n.$errorParam=o,-1===m.indexOf(n)&&m.push(n),l||a.splice(i,1),j=!1):a.splice(i,1)))}}null!==j&&d.$ngfValidations.push({name:b,valid:j})}}function i(c,h,i,n,o){function p(b,d,e){function f(f){if(f())if(-1===k.indexOf(c)){if(d.$error=c,(d.$errorMessages=d.$errorMessages||{})[c]=!0,d.$errorParam=e,-1===m.indexOf(d)&&m.push(d),!l){var g=a.indexOf(d);g>-1&&a.splice(g,1)}b.resolve(!1)}else{var h=a.indexOf(d);h>-1&&a.splice(h,1),b.resolve(!0)}else b.resolve(!0)}null!=e?n(d,e).then(function(a){f(function(){return!o(a,e)})},function(){f(function(){return j("ngfValidateForce",{$file:d})})}):b.resolve(!0)}var q=[f.emptyPromise(!0)];a&&(a=void 0===a.length?[a]:a,angular.forEach(a,function(a){var d=b.defer();return q.push(d.promise),!i||null!=a.type&&0===a.type.search(i)?void("dimensions"===c&&null!=f.attrGetter("ngfDimensions",e)?f.imageDimensions(a).then(function(b){p(d,a,j("ngfDimensions",{$file:a,$width:b.width,$height:b.height}))},function(){d.resolve(!1)}):"duration"===c&&null!=f.attrGetter("ngfDuration",e)?f.mediaDuration(a).then(function(b){p(d,a,j("ngfDuration",{$file:a,$duration:b}))},function(){d.resolve(!1)}):p(d,a,f.getValidationAttr(e,g,c,h,a))):void d.resolve(!0)}));var r=b.defer();return b.all(q).then(function(a){for(var b=!0,e=0;e<a.length;e++)if(!a[e]){b=!1;break}d.$ngfValidations.push({name:c,valid:b}),r.resolve(b)}),r.promise}d=d||{},d.$ngfValidations=d.$ngfValidations||[],angular.forEach(d.$ngfValidations,function(a){a.valid=!0});var j=function(a,b){return f.attrGetter(a,e,g,b)},k=(f.attrGetter("ngfIgnoreInvalid",e,g)||"").split(" "),l=f.attrGetter("ngfRunAllValidations",e,g);if(null==a||0===a.length)return f.emptyPromise({validFiles:a,invalidFiles:[]});a=void 0===a.length?[a]:a.slice(0);var m=[];h("pattern",null,f.validatePattern),h("minSize","size.min",function(a,b){return a.size+.1>=f.translateScalars(b)}),h("maxSize","size.max",function(a,b){return a.size-.1<=f.translateScalars(b)});var n=0;if(h("maxTotalSize",null,function(b,c){return n+=b.size,n>f.translateScalars(c)?(a.splice(0,a.length),!1):!0}),h("validateFn",null,function(a,b){return b===!0||null===b||""===b}),!a.length)return f.emptyPromise({validFiles:[],invalidFiles:m});var o=b.defer(),p=[];return p.push(i("maxHeight","height.max",/image/,this.imageDimensions,function(a,b){return a.height<=b})),p.push(i("minHeight","height.min",/image/,this.imageDimensions,function(a,b){return a.height>=b})),p.push(i("maxWidth","width.max",/image/,this.imageDimensions,function(a,b){return a.width<=b})),p.push(i("minWidth","width.min",/image/,this.imageDimensions,function(a,b){return a.width>=b})),p.push(i("dimensions",null,/image/,function(a,b){return f.emptyPromise(b)},function(a){return a})),p.push(i("ratio",null,/image/,this.imageDimensions,function(a,b){for(var c=b.toString().split(","),d=!1,e=0;e<c.length;e++)Math.abs(a.width/a.height-f.ratioToFloat(c[e]))<.01&&(d=!0);return d})),p.push(i("maxRatio","ratio.max",/image/,this.imageDimensions,function(a,b){return a.width/a.height-f.ratioToFloat(b)<1e-4})),p.push(i("minRatio","ratio.min",/image/,this.imageDimensions,function(a,b){return a.width/a.height-f.ratioToFloat(b)>-1e-4})),p.push(i("maxDuration","duration.max",/audio|video/,this.mediaDuration,function(a,b){return a<=f.translateScalars(b)})),p.push(i("minDuration","duration.min",/audio|video/,this.mediaDuration,function(a,b){return a>=f.translateScalars(b)})),p.push(i("duration",null,/audio|video/,function(a,b){return f.emptyPromise(b)},function(a){return a})),p.push(i("validateAsyncFn",null,null,function(a,b){return b},function(a){return a===!0||null===a||""===a})),b.all(p).then(function(){if(l)for(var b=0;b<a.length;b++){var d=a[b];d.$error&&a.splice(b--,1)}l=!1,h("maxFiles",null,function(a,b,d){return b>c+d}),o.resolve({validFiles:a,invalidFiles:m})}),o.promise},f.imageDimensions=function(a){if(a.$ngfWidth&&a.$ngfHeight){var d=b.defer();return c(function(){d.resolve({width:a.$ngfWidth,height:a.$ngfHeight})}),d.promise}if(a.$ngfDimensionPromise)return a.$ngfDimensionPromise;var e=b.defer();return c(function(){return 0!==a.type.indexOf("image")?void e.reject("not image"):void f.dataUrl(a).then(function(b){function d(){var b=h[0].naturalWidth||h[0].clientWidth,c=h[0].naturalHeight||h[0].clientHeight;h.remove(),a.$ngfWidth=b,a.$ngfHeight=c,e.resolve({width:b,height:c})}function f(){h.remove(),e.reject("load error")}function g(){c(function(){h[0].parentNode&&(h[0].clientWidth?d():i++>10?f():g())},1e3)}var h=angular.element("<img>").attr("src",b).css("visibility","hidden").css("position","fixed").css("max-width","none !important").css("max-height","none !important");h.on("load",d),h.on("error",f);var i=0;g(),angular.element(document.getElementsByTagName("body")[0]).append(h)},function(){e.reject("load error")})}),a.$ngfDimensionPromise=e.promise,a.$ngfDimensionPromise["finally"](function(){delete a.$ngfDimensionPromise}),a.$ngfDimensionPromise},f.mediaDuration=function(a){if(a.$ngfDuration){var d=b.defer();return c(function(){d.resolve(a.$ngfDuration)}),d.promise}if(a.$ngfDurationPromise)return a.$ngfDurationPromise;var e=b.defer();return c(function(){return 0!==a.type.indexOf("audio")&&0!==a.type.indexOf("video")?void e.reject("not media"):void f.dataUrl(a).then(function(b){function d(){var b=h[0].duration;a.$ngfDuration=b,h.remove(),e.resolve(b)}function f(){h.remove(),e.reject("load error")}function g(){c(function(){h[0].parentNode&&(h[0].duration?d():i>10?f():g())},1e3)}var h=angular.element(0===a.type.indexOf("audio")?"<audio>":"<video>").attr("src",b).css("visibility","none").css("position","fixed");h.on("loadedmetadata",d),h.on("error",f);var i=0;g(),angular.element(document.body).append(h)},function(){e.reject("load error")})}),a.$ngfDurationPromise=e.promise,a.$ngfDurationPromise["finally"](function(){delete a.$ngfDurationPromise}),a.$ngfDurationPromise},f}]),ngFileUpload.service("UploadResize",["UploadValidate","$q",function(a,b){var c=a,d=function(a,b,c,d,e){var f=e?Math.max(c/a,d/b):Math.min(c/a,d/b);return{width:a*f,height:b*f,marginX:a*f-c,marginY:b*f-d}},e=function(a,e,f,g,h,i,j,k){var l=b.defer(),m=document.createElement("canvas"),n=document.createElement("img");return n.setAttribute("style","visibility:hidden;position:fixed;z-index:-100000"),document.body.appendChild(n),n.onload=function(){var a=n.width,b=n.height;if(n.parentNode.removeChild(n),null!=k&&k(a,b)===!1)return void l.reject("resizeIf");try{if(i){var o=c.ratioToFloat(i),p=a/b;o>p?(e=a,f=e/o):(f=b,e=f*o)}e||(e=a),f||(f=b);var q=d(a,b,e,f,j);m.width=Math.min(q.width,e),m.height=Math.min(q.height,f);var r=m.getContext("2d");r.drawImage(n,Math.min(0,-q.marginX/2),Math.min(0,-q.marginY/2),q.width,q.height),l.resolve(m.toDataURL(h||"image/WebP",g||.934))}catch(s){l.reject(s)}},n.onerror=function(){n.parentNode.removeChild(n),l.reject()},n.src=a,l.promise};return c.dataUrltoBlob=function(a,b,c){for(var d=a.split(","),e=d[0].match(/:(.*?);/)[1],f=atob(d[1]),g=f.length,h=new Uint8Array(g);g--;)h[g]=f.charCodeAt(g);var i=new window.Blob([h],{type:e});return i.name=b,i.$ngfOrigSize=c,i},c.isResizeSupported=function(){var a=document.createElement("canvas");return window.atob&&a.getContext&&a.getContext("2d")&&window.Blob},c.isResizeSupported()&&Object.defineProperty(window.Blob.prototype,"name",{get:function(){return this.$ngfName},set:function(a){this.$ngfName=a},configurable:!0}),c.resize=function(a,d){if(0!==a.type.indexOf("image"))return c.emptyPromise(a);var f=b.defer();return c.dataUrl(a,!0).then(function(b){e(b,d.width,d.height,d.quality,d.type||a.type,d.ratio,d.centerCrop,d.resizeIf).then(function(e){if("image/jpeg"===a.type&&d.restoreExif!==!1)try{e=c.restoreExif(b,e)}catch(g){setTimeout(function(){throw g},1)}try{var h=c.dataUrltoBlob(e,a.name,a.size);f.resolve(h)}catch(g){f.reject(g)}},function(b){"resizeIf"===b&&f.resolve(a),f.reject(b)})},function(a){f.reject(a)}),f.promise},c}]),function(){function a(a,c,d,e,f,g,h,i,j,k){function l(){return c.attr("disabled")||s("ngfDropDisabled",a)}function m(b,c,d){if(b){var e;try{e=b&&b.getData&&b.getData("text/html")}catch(f){}q(b.items,b.files,s("ngfAllowDir",a)!==!1,s("multiple")||s("ngfMultiple",a)).then(function(a){a.length?n(a,c):o(d,e).then(function(a){n(a,c)})})}}function n(b,c){i.updateModel(e,d,a,s("ngfChange")||s("ngfDrop"),b,c)}function o(b,c){if(!i.shouldUpdateOn(b,d,a)||"string"!=typeof c)return i.rejectPromise([]);var e=[];c.replace(/<(img src|img [^>]* src) *=\"([^\"]*)\"/gi,function(a,b,c){e.push(c)});var f=[],g=[];if(e.length){angular.forEach(e,function(a){f.push(i.urlToBlob(a).then(function(a){g.push(a)}))});var h=k.defer();return k.all(f).then(function(){h.resolve(g)},function(a){h.reject(a)}),h.promise}return i.emptyPromise()}function p(a,b,c,d){var e=s("ngfDragOverClass",a,{$event:c}),f="dragover";if(angular.isString(e))f=e;else if(e&&(e.delay&&(w=e.delay),e.accept||e.reject)){var g=c.dataTransfer.items;if(null!=g&&g.length)for(var h=e.pattern||s("ngfPattern",a,{$event:c}),j=g.length;j--;){if(!i.validatePattern(g[j],h)){f=e.reject;break}f=e.accept}else f=e.accept}d(f)}function q(b,c,e,f){function g(a,b){var c=k.defer();if(null!=a)if(a.isDirectory){var d=[i.emptyPromise()];if(m){var e={type:"directory"};e.name=e.path=(b||"")+a.name,n.push(e)}var f=a.createReader(),h=[],p=function(){f.readEntries(function(e){try{e.length?(h=h.concat(Array.prototype.slice.call(e||[],0)),p()):(angular.forEach(h.slice(0),function(c){n.length<=j&&l>=o&&d.push(g(c,(b?b:"")+a.name+"/"))}),k.all(d).then(function(){c.resolve()},function(a){c.reject(a)}))}catch(f){c.reject(f)}},function(a){c.reject(a)})};p()}else a.file(function(a){try{a.path=(b?b:"")+a.name,m&&(a=i.rename(a,a.path)),n.push(a),o+=a.size,c.resolve()}catch(d){c.reject(d)}},function(a){c.reject(a)});return c.promise}var j=i.getValidationAttr(d,a,"maxFiles");null==j&&(j=Number.MAX_VALUE);var l=i.getValidationAttr(d,a,"maxTotalSize");null==l&&(l=Number.MAX_VALUE);var m=s("ngfIncludeDir",a),n=[],o=0,p=[i.emptyPromise()];if(b&&b.length>0&&"file:"!==h.location.protocol)for(var q=0;q<b.length;q++){if(b[q].webkitGetAsEntry&&b[q].webkitGetAsEntry()&&b[q].webkitGetAsEntry().isDirectory){var r=b[q].webkitGetAsEntry();if(r.isDirectory&&!e)continue;null!=r&&p.push(g(r))}else{var t=b[q].getAsFile();null!=t&&(n.push(t),o+=t.size)}if(n.length>j||o>l||!f&&n.length>0)break}else if(null!=c)for(var u=0;u<c.length;u++){var v=c.item(u);if((v.type||v.size>0)&&(n.push(v),o+=v.size),n.length>j||o>l||!f&&n.length>0)break}var w=k.defer();return k.all(p).then(function(){if(f||m||!n.length)w.resolve(n);else{for(var a=0;n[a]&&"directory"===n[a].type;)a++;w.resolve([n[a]])}},function(a){w.reject(a)}),w.promise}var r=b(),s=function(a,b,c){return i.attrGetter(a,d,b,c)};if(s("dropAvailable")&&g(function(){a[s("dropAvailable")]?a[s("dropAvailable")].value=r:a[s("dropAvailable")]=r}),!r)return void(s("ngfHideOnDropNotAvailable",a)===!0&&c.css("display","none"));null==s("ngfSelect")&&i.registerModelChangeValidator(e,d,a);var t,u=null,v=f(s("ngfStopPropagation")),w=1;c[0].addEventListener("dragover",function(b){if(!l()&&i.shouldUpdateOn("drop",d,a)){if(b.preventDefault(),v(a)&&b.stopPropagation(),navigator.userAgent.indexOf("Chrome")>-1){var e=b.dataTransfer.effectAllowed;b.dataTransfer.dropEffect="move"===e||"linkMove"===e?"move":"copy"}g.cancel(u),t||(t="C",p(a,d,b,function(d){t=d,c.addClass(t),s("ngfDrag",a,{$isDragging:!0,$class:t,$event:b})}))}},!1),c[0].addEventListener("dragenter",function(b){!l()&&i.shouldUpdateOn("drop",d,a)&&(b.preventDefault(),v(a)&&b.stopPropagation())},!1),c[0].addEventListener("dragleave",function(b){!l()&&i.shouldUpdateOn("drop",d,a)&&(b.preventDefault(),
v(a)&&b.stopPropagation(),u=g(function(){t&&c.removeClass(t),t=null,s("ngfDrag",a,{$isDragging:!1,$event:b})},w||100))},!1),c[0].addEventListener("drop",function(b){!l()&&i.shouldUpdateOn("drop",d,a)&&(b.preventDefault(),v(a)&&b.stopPropagation(),t&&c.removeClass(t),t=null,m(b.dataTransfer,b,"dropUrl"))},!1),c[0].addEventListener("paste",function(b){navigator.userAgent.toLowerCase().indexOf("firefox")>-1&&s("ngfEnableFirefoxPaste",a)&&b.preventDefault(),!l()&&i.shouldUpdateOn("paste",d,a)&&m(b.clipboardData||b.originalEvent.clipboardData,b,"pasteUrl")},!1),navigator.userAgent.toLowerCase().indexOf("firefox")>-1&&s("ngfEnableFirefoxPaste",a)&&(c.attr("contenteditable",!0),c.on("keypress",function(a){a.metaKey||a.ctrlKey||a.preventDefault()}))}function b(){var a=document.createElement("div");return"draggable"in a&&"ondrop"in a&&!/Edge\/12./i.test(navigator.userAgent)}ngFileUpload.directive("ngfDrop",["$parse","$timeout","$window","Upload","$http","$q",function(b,c,d,e,f,g){return{restrict:"AEC",require:"?ngModel",link:function(h,i,j,k){a(h,i,j,k,b,c,d,e,f,g)}}}]),ngFileUpload.directive("ngfNoFileDrop",function(){return function(a,c){b()&&c.css("display","none")}}),ngFileUpload.directive("ngfDropAvailable",["$parse","$timeout","Upload",function(a,c,d){return function(e,f,g){if(b()){var h=a(d.attrGetter("ngfDropAvailable",g));c(function(){h(e),h.assign&&h.assign(e,!0)})}}}])}(),ngFileUpload.service("UploadExif",["UploadResize","$q",function(a,b){function c(a,b,c,d){switch(b){case 2:return a.transform(-1,0,0,1,c,0);case 3:return a.transform(-1,0,0,-1,c,d);case 4:return a.transform(1,0,0,-1,0,d);case 5:return a.transform(0,1,1,0,0,0);case 6:return a.transform(0,1,-1,0,d,0);case 7:return a.transform(0,-1,-1,0,d,c);case 8:return a.transform(0,-1,1,0,0,c)}}function d(a){for(var b="",c=new Uint8Array(a),d=c.byteLength,e=0;d>e;e++)b+=String.fromCharCode(c[e]);return window.btoa(b)}var e=a;return e.isExifSupported=function(){return window.FileReader&&(new FileReader).readAsArrayBuffer&&e.isResizeSupported()},e.readOrientation=function(a){var c=b.defer(),d=new FileReader,e=a.slice?a.slice(0,65536):a;return d.readAsArrayBuffer(e),d.onerror=function(a){return c.reject(a)},d.onload=function(a){var b={orientation:1},d=new DataView(this.result);if(65496!==d.getUint16(0,!1))return c.resolve(b);for(var e=d.byteLength,f=2;e>f;){var g=d.getUint16(f,!1);if(f+=2,65505===g){if(1165519206!==d.getUint32(f+=2,!1))return c.resolve(b);var h=18761===d.getUint16(f+=6,!1);f+=d.getUint32(f+4,h);var i=d.getUint16(f,h);f+=2;for(var j=0;i>j;j++)if(274===d.getUint16(f+12*j,h)){var k=d.getUint16(f+12*j+8,h);return k>=2&&8>=k&&(d.setUint16(f+12*j+8,1,h),b.fixedArrayBuffer=a.target.result),b.orientation=k,c.resolve(b)}}else{if(65280!==(65280&g))break;f+=d.getUint16(f,!1)}}return c.resolve(b)},c.promise},e.applyExifRotation=function(a){if(0!==a.type.indexOf("image/jpeg"))return e.emptyPromise(a);var f=b.defer();return e.readOrientation(a).then(function(b){return b.orientation<2||b.orientation>8?f.resolve(a):void e.dataUrl(a,!0).then(function(g){var h=document.createElement("canvas"),i=document.createElement("img");i.onload=function(){try{h.width=b.orientation>4?i.height:i.width,h.height=b.orientation>4?i.width:i.height;var g=h.getContext("2d");c(g,b.orientation,i.width,i.height),g.drawImage(i,0,0);var j=h.toDataURL(a.type||"image/WebP",.934);j=e.restoreExif(d(b.fixedArrayBuffer),j);var k=e.dataUrltoBlob(j,a.name);f.resolve(k)}catch(l){return f.reject(l)}},i.onerror=function(){f.reject()},i.src=g},function(a){f.reject(a)})},function(a){f.reject(a)}),f.promise},e.restoreExif=function(a,b){var c={};return c.KEY_STR="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",c.encode64=function(a){var b,c,d,e,f,g="",h="",i="",j=0;do b=a[j++],c=a[j++],h=a[j++],d=b>>2,e=(3&b)<<4|c>>4,f=(15&c)<<2|h>>6,i=63&h,isNaN(c)?f=i=64:isNaN(h)&&(i=64),g=g+this.KEY_STR.charAt(d)+this.KEY_STR.charAt(e)+this.KEY_STR.charAt(f)+this.KEY_STR.charAt(i),b=c=h="",d=e=f=i="";while(j<a.length);return g},c.restore=function(a,b){a.match("data:image/jpeg;base64,")&&(a=a.replace("data:image/jpeg;base64,",""));var c=this.decode64(a),d=this.slice2Segments(c),e=this.exifManipulation(b,d);return"data:image/jpeg;base64,"+this.encode64(e)},c.exifManipulation=function(a,b){var c=this.getExifArray(b),d=this.insertExif(a,c);return new Uint8Array(d)},c.getExifArray=function(a){for(var b,c=0;c<a.length;c++)if(b=a[c],255===b[0]&225===b[1])return b;return[]},c.insertExif=function(a,b){var c=a.replace("data:image/jpeg;base64,",""),d=this.decode64(c),e=d.indexOf(255,3),f=d.slice(0,e),g=d.slice(e),h=f;return h=h.concat(b),h=h.concat(g)},c.slice2Segments=function(a){for(var b=0,c=[];;){if(255===a[b]&218===a[b+1])break;if(255===a[b]&216===a[b+1])b+=2;else{var d=256*a[b+2]+a[b+3],e=b+d+2,f=a.slice(b,e);c.push(f),b=e}if(b>a.length)break}return c},c.decode64=function(a){var b,c,d,e,f,g="",h="",i=0,j=[],k=/[^A-Za-z0-9\+\/\=]/g;k.exec(a)&&console.log("There were invalid base64 characters in the input text.\nValid base64 characters are A-Z, a-z, 0-9, NaNExpect errors in decoding."),a=a.replace(/[^A-Za-z0-9\+\/\=]/g,"");do d=this.KEY_STR.indexOf(a.charAt(i++)),e=this.KEY_STR.indexOf(a.charAt(i++)),f=this.KEY_STR.indexOf(a.charAt(i++)),h=this.KEY_STR.indexOf(a.charAt(i++)),b=d<<2|e>>4,c=(15&e)<<4|f>>2,g=(3&f)<<6|h,j.push(b),64!==f&&j.push(c),64!==h&&j.push(g),b=c=g="",d=e=f=h="";while(i<a.length);return j},c.restore(a,b)},e}]);
angular
    .module('app')
    .factory('Arquivos', Arquivos);

Arquivos.$inject = [
        '$ajax',
        '$window',
        '$timeout',
        '$httpParamSerializer',
        '$rootScope',
        '$compile',
        'Upload'
    ];



function Arquivos($ajax, $window, $timeout,$httpParamSerializer,$rootScope, $compile, Upload) {

	/**
     * Constructor, with class name
     */
    function Arquivos(data) {
        if (data) {
            this.setData(data);
        }
    }

    var arquivoPadrao = {
    		ID      : 0,
			NOME 	: null,
			TABELA 	: null,
			TIPO 	: null,
			TAMANHO	: null,
			BINARIO	: null,
			CONTEUDO: null,
			CSS     : null
	};

    /**
     * Public method, assigned to prototype
     */
    Arquivos.prototype = {
    	data: [],
    	data_excluir: [],
    	comentario: '',
    	vGravar: false,
    	editando: false,
    	de: '',
    	para: '',
    	cc:'',
    	cco: '',
    	assunto: '',
    	setData: function(data) {
            angular.extend(this, data);
        },
    	addArquivo: function() {

    		if(typeof this.data != 'array' && typeof this.data != 'object'){
    			this.data = [];
    		}

    		var validar = true;
    		angular.forEach(this.data, function(iten, key) {

    			if(iten.NOME == null){
    				validar = false;
    			}
    		});

    		if(validar){
				var arquivoNovo = {};
				angular.copy(arquivoPadrao, arquivoNovo);
				this.data.push(arquivoNovo);
			}

			setTimeout(function(){
				var imputs = $('.arquivo-binario');
				if (imputs.length > 0){
					$(imputs[0]).trigger('click');
				}
			},200);

		},
		gravar:function(painel_id, caso_id, user, feed, tipo){
			that = this;
			var dados = {};

			dados.FEED_ID = 0;
			dados.FILES   = 1;

			dados.TIPO	= tipo;

			if(that.editando == true){
				dados.FEED_ID = feed.ID;
			}

			dados.SUBFEED = 0;
			if(tipo == 99){
				dados.SUBFEED = feed.ID;
				dados.TIPO	  = feed.TIPO;
			}

			if(feed.SUBFEED > 0){
				dados.SUBFEED = feed.SUBFEED;
			}

			dados.PAINEL_ID 	  = painel_id;
			dados.CASO_ID 		  = caso_id;
			dados.DE 			  = that.de; 
			dados.PARA			  = that.para; 
			dados.EM_COPIA 		  = that.cc; 
			dados.EM_COPIA_OCULTA = that.cco; 
			dados.MENSAGEM	      = that.comentario; 
			dados.ASSUNTO 		  = that.assunto;
			dados.COMENTARIO      = that.coment;
			dados.USUARIO_ID	  = user.CODIGO;

			dados.ARQUIVOS   	  = that.data;
			dados.EXCLUIR         = that.data_excluir;

			console.log(dados);

			var msg = CKEDITOR.instances.editor1.getData();

			dados.MENSAGEM	      = msg;

			if((msg+' ').length > 10){
				$('.carregando-pagina').fadeIn(200);


	            var upload = Upload
	                            .upload({
	                                url : '/_11150/gravarFeed', 
	                                data: dados
	                            });

	            upload
	                .finally(
	                    function(e) {

							$('#modal-file').modal('hide');  

							that.data = [];
							that.data_excluir = [];
							that.comentario = '';
							that.editando = false;
							that.vGravar = false;
	                    
	                        $('.carregando-pagina').fadeOut(200);

	                        setTimeout(function() {
	                            $('.carregando-pagina .progress .progress-bar')
	                                .attr({'aria-valuenow': 0,'aria-valuemax': 0})
	                                .css('width', 0);

	                            if(that.coment == 0){
	                            	$('.atualizar-files').trigger('click');
	                            }else{
	                            	$('#tab-files').trigger('click');
	                            }

	                        }, 300);
	                    }
	                );

	            return upload;
	        }else{
	        	showErro('A mensagem deve ter no minimo 10 caracteres!');
	        }
		},
		canselar:function(){
			that = this;

			$('#modal-file').modal('hide'); 
			that.data = [];
    		that.data_excluir = [];
    		that.comentario = '';
    		that.vGravar = true;
    		that.editando = false;
		},
		processarArquivo: function(event, arquivo) {

			that = this;
			var arquivoAdicionado = false;

			angular.forEach(event.target.files, function(file, key) {

				var size = (file.size / 1048576);

				if(size <= 2){

					var validar = true;
		    		angular.forEach(that.data, function(iten, key) {
		    			if(iten.NOME == null){
		    				validar = false;
		    			}
		    		});

		    		if(validar){
						var arquivoNovo = {};
						angular.copy(arquivoPadrao, arquivoNovo);
						that.data.push(arquivoNovo);

						arquivo = that.data[that.data.length - 1];
					}

					that.vGravar = true;

					arquivo.NOME 	 = file.name;
					arquivo.TABELA 	 = 'TBCASO_REGISTRO';
					arquivo.TIPO 	 = file.type;
					arquivo.TAMANHO	 = file.size;

					arquivo.BINARIO = file;

					arquivo.CSS = 'unknown';

					if(arquivo.TIPO.indexOf('pdf'				) >= 0 ){arquivo.CSS = 'pdf'; }
					if(arquivo.TIPO.indexOf('octet-stream'		) >= 0 ){arquivo.CSS = 'exe'; }
					if(arquivo.TIPO.indexOf('zip'   			) >= 0 ){arquivo.CSS = 'zip'; }
					if(arquivo.TIPO.indexOf('msword'   			) >= 0 ){arquivo.CSS = 'doc'; }
					if(arquivo.TIPO.indexOf('vnd.ms-excel'   	) >= 0 ){arquivo.CSS = 'xls'; }
					if(arquivo.TIPO.indexOf('vnd.ms-powerpoint' ) >= 0 ){arquivo.CSS = 'ppt'; }
					if(arquivo.TIPO.indexOf('gif'   			) >= 0 ){arquivo.CSS = 'gif'; }
					if(arquivo.TIPO.indexOf('png'   			) >= 0 ){arquivo.CSS = 'png'; }
					if(arquivo.TIPO.indexOf('jpg'   			) >= 0 ){arquivo.CSS = 'jpg'; }
					if(arquivo.TIPO.indexOf('jpeg'   			) >= 0 ){arquivo.CSS = 'jpeg';}
					if(arquivo.TIPO.indexOf('mpeg'   			) >= 0 ){arquivo.CSS = 'mpeg';}
					if(arquivo.TIPO.indexOf('text/plain'   		) >= 0 ){arquivo.CSS = 'txt'; }
					if(arquivo.TIPO.indexOf('sheet'   			) >= 0 ){arquivo.CSS = 'xls'; }
					if(arquivo.TIPO.indexOf('wordprocessingml'  ) >= 0 ){arquivo.CSS = 'doc'; }
					if(arquivo.TIPO.indexOf('presentation'   	) >= 0 ){arquivo.CSS = 'ppt'; }

					arquivoAdicionado = true;
				}else{
					showErro('Não é possível adicionar anexos maiores de 2MB e "'+file.name+'" tem '+size.toLocaleString('pt-BR')+'MB, diminua a resolução ou comprima o arquivo e tente novamente.');
				}
			});

			if(arquivoAdicionado == true){
				setTimeout(function() {
					$('.arquivo-container .scroll .form-group:last-of-type input.arquivo-binario').focus();
				}, 100);
			}else{
				that.data.splice(that.data.length - 1, 1);
			}

		},
		excluirArquivo: function(arquivo) {

			// Só adiciona para excluir do banco de dados se o arquivo tiver ID, ou seja, já está gravado no banco.
			if (arquivo.ID > 0) {
				this.data_excluir = (typeof this.data_excluir != 'undefined') ? this.data_excluir : [];
				this.data_excluir.push(arquivo);
			}

			this.data.splice(this.data.indexOf(arquivo), 1);
			// Adiciona um arquivo vazio se não tiver mais nenhum outro.
			if (this.data.length == 0){
				this.vGravar = false;
			}
		}
	}

	/**
     * Return the constructor function
     */
    return Arquivos;

}   
    
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
        'Create',
        '$consulta',
        '$httpParamSerializer',
        '$rootScope',
        '$compile',
        'ScriptCompile',
        'Arquivos',
        '$sce'
    ];

	function Ctrl($ajax, $scope, $window, $timeout, gScope, Create, $consulta,$httpParamSerializer,$rootScope, $compile,ScriptCompile,Arquivos,$sce) {

        function htmlDecode(str) {
            
            str = str.replace(/&QUOT;/g,    '&quot;'   );
            str = str.replace(/&NBSP;/g,    '&nbsp;'   );
            str = str.replace(/&AACUTE;/g,  '&Aacute;' );
            str = str.replace(/&ACIRC;/g,   '&Acirc;'  );
            str = str.replace(/&AGRAVE;/g,  '&Agrave;' );
            str = str.replace(/&ARING;/g,   '&Aring;'  );
            str = str.replace(/&ATILDE;/g,  '&Atilde;' );
            str = str.replace(/&AUML;/g,    '&Auml;'   );
            str = str.replace(/&AELIG;/g,   '&AElig;'  );
            str = str.replace(/&EACUTE;/g,  '&Eacute;' );
            str = str.replace(/&ECIRC;/g,   '&Ecirc;'  );
            str = str.replace(/&EGRAVE;/g,  '&Egrave;' );
            str = str.replace(/&EUML;/g,    '&Euml;'   );
            str = str.replace(/&ETH;/g,     '&ETH;'    );
            str = str.replace(/&IACUTE;/g,  '&Iacute;' );
            str = str.replace(/&ICIRC;/g,   '&Icirc;'  );
            str = str.replace(/&IGRAVE;/g,  '&Igrave;' );
            str = str.replace(/&IUML;/g,    '&Iuml;'   );
            str = str.replace(/&OACUTE;/g,  '&Oacute;' );
            str = str.replace(/&OCIRC;/g,   '&Ocirc;'  );
            str = str.replace(/&OGRAVE;/g,  '&Ograve;' );
            str = str.replace(/&OSLASH;/g,  '&Oslash;' );
            str = str.replace(/&OTILDE;/g,  '&Otilde;' );
            str = str.replace(/&OUML;/g,    '&Ouml;'   );
            str = str.replace(/&UACUTE;/g,  '&Uacute;' );
            str = str.replace(/&UCIRC;/g,   '&Ucirc;'  );
            str = str.replace(/&UGRAVE;/g,  '&Ugrave;' );
            str = str.replace(/&UUML;/g,    '&Uuml;'   );
            str = str.replace(/&CCEDIL;/g,  '&Ccedil;' );

            return str;
        }

        $scope.trustAsHtml = function(string, feed) {
            var html = htmlDecode(string);
            return $sce.trustAsHtml(html);
        };

        var ckConfig = {
                        toolbar: [{
                            name: "document",
                            items: ["Print"]
                        }, {
                            name: "clipboard",
                            items: ["Undo", "Redo"]
                        }, {
                            name: "styles",
                            items: ["Format", "Font", "FontSize"]
                        }, {
                            name: "basicstyles",
                            items: ["Bold", "Italic", "Underline", "Strike", "RemoveFormat", "CopyFormatting"]
                        }, {
                            name: "colors",
                            items: ["TextColor", "BGColor"]
                        }, {
                            name: "align",
                            items: ["JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock"]
                        }, {
                            name: "links",
                            items: ["Link", "Unlink"]
                        }, {
                            name: "paragraph",
                            items: ["NumberedList", "BulletedList", "-", "Outdent", "Indent", "-", "Blockquote"]
                        }, {
                            name: "insert",
                            items: ["Table"]
                        }],
                        removePlugins: "autoembed,embedsemantic,image2,sourcedialog",
                        disallowedContent: "img{width,height,float}",
                        extraAllowedContent: "img[width,height,align]",
                        bodyClass: "document-editor"
                    };

        //loading($('#divgeral'));

        document.addEventListener('keyup', function(evt) {

            if(vm.caso_id > 0){
                if(evt.altKey && evt.key == 'o'){
                    $('#tab-caso').trigger('click');    
                }

                if(evt.altKey && evt.key == 'f'){
                    $('#tab-feed').trigger('click');    
                }

                if(evt.altKey && evt.key == 'i'){
                    $('#tab-files').trigger('click');    
                }

                if(evt.altKey && evt.key == 'h'){
                    $('#tab-history').trigger('click');  
                }
            }

            if(evt.altKey && evt.key == 'd'){
                $('#tab-cadastro').trigger('click');    
            }

            if(evt.altKey && evt.key == 'n'){
                $('#tab-contatos').trigger('click');  
            }

            if(evt.altKey && evt.key == 'b'){
                $('#tab-lebretes').trigger('click');  
            }


            if(evt.altKey && evt.key == 'r'){
                $('#tab-acordeon').trigger('click');  
            }

            if(evt.altKey && evt.key == 't'){
                $('#tab-tabela').trigger('click');
                setTimeout(function(){
                    vm.Acoes.Canselar();
                },100);
            }


        }, false);

        function lpad(string, padString, length) {
            var str = string;
            while (str.length < length)
                str = padString + str;
            return str;
        }

		var vm = this;
        vm.loading = 0;

        vm.ordem = '-CODIGO';

        vm.TratarOrdem = function(filtro){
            if(vm.ordem == filtro){
                vm.ordem = '-'+filtro;
            }else{
                vm.ordem = filtro;
            }
        };

		vm.DADOS = [];

        vm.filtroCaso = '';
        vm.filtroFeed = '';
        vm.ordemFeed  = 0;
        vm.qtd_casos  = 0;

        vm.tipo_feed  = 0;

        vm.PainelCaso = {};
        vm.ConfConato = {};
        vm.Validacao  = {};
        vm.CasoIten   = {};
        vm.Feed       = {};

        vm.editar_contato = false;

        vm.tabFeed = [];
        vm.tabFeed.btn = [];
        vm.tabFeed.btn.visivel = true;
        vm.tabFeed.dados = [];

        vm.tabHistory = [];
        vm.tabHistory.btn = [];
        vm.tabHistory.btn.visivel = true;
        vm.tabHistory.dados = [];

        vm.tabCaso = [];
        vm.tabCaso.btn = [];
        vm.tabCaso.btn.visivel = true;
        vm.tabCaso.dados = [];

        vm.tabCaso.btn.click = function(){
            setTimeout(function(){
                $('.modal-caso').find('.modal-body').scrollTop(0);
            },100);
        };

        vm.hideTabs = function(flag){
            vm.tabHistory.btn.visivel = !flag;
            vm.tabFeed.btn.visivel    = !flag;
            vm.tabCaso.btn.visivel    = !flag;
            vm.tabFiles.btn.visivel   = !flag;
        }

        vm.tabFiles = [];
        vm.tabFiles.btn = [];
        vm.tabFiles.btn.visivel = true;
        vm.tabFiles.dados = [];

        vm.lista = {}

        vm.PainelConfEdit = {};

        vm.casos = [];
        vm.user = [];
 
        vm.btnGravar  = {};
        vm.btnGravar.disabled = false;

        vm.status_tela = 0;
        vm.itens = [];
        vm.feed_editar = [];

        vm.Arquivos = new Arquivos();
		
        vm.Create        = new Create();
        vm.Create.model  = 'vm.Create.itens';
        gScope.Create    = vm.Create;

        vm.ScriptCompile        = new ScriptCompile();
        gScope.ScriptCompile    = vm.ScriptCompile;

        vm.tabComentario = {};

        vm.PainelID = 0;

        vm.abaAberta = 0;

        vm.filterCaso = function($event,item){
            if($event.key == 'Enter'){
                //if( Object.keys(vm.lista).length == 0){
                    vm.Acoes.openCaso(vm.filtroCaso, $event);
                //}
            } 
        }

        vm.filterCaso2 = function($event,item){
            if($event.key == 'Enter'){
                vm.filterCaso3();
            } 
        }

        vm.filterCaso3 = function($event,item){
            if(vm.FILTRO_CASO.length > 0){
                vm.getCasos(2,vm.FILTRO_CASO);
            }else{
                showErro('O filtro deve conter ao menos uma palavra');
            }
        }

        vm.PrepararFiltro = function(status){
            vm.FILTRO_CASO = '';
            vm.casos = [];
        }

        vm.getCasos = function(status,filtro){

            vm.abaAberta  = status;
            vm.filtroCaso = '';

            vm.loading = 1;

            vm.painel_id = $('._painel_id').val();

            $ajax.post('/_11150/getCasos', {PAINEL_ID: vm.painel_id, STATUS : status, FILTRO : filtro})
            .then(function(response) {

                vm.casos     = response.CASOS;
                vm.user      = response.USUARIO;
                vm.status    = response.STATUS;
                vm.conf      = response.CONF;
                vm.parametro = response.PARAMETRO;

                vm.caso = [];

                setTimeout(function(){
                    angular.forEach(vm.casos, function(caso, ordem) {
                        var html = '';
                        vm.caso[caso.ID]= [];
                        vm.Create.model = 'vm.caso['+caso.ID+']';

                        angular.forEach(vm.conf['CAMPO'], function(iten, key) {

                            var valor;
                            var def = caso['C'+key];
                            iten.DEFAULT = caso['C'+key];

                            if(iten.TIPO == 7){
                                iten.TIPO = 1;
                            }
                            
                            var obj = {
                                VAR_NOME : iten.VAR_NOME,
                                VALOR    : def,
                                EDIT     : 0,
                                NOME     : iten.DESCRICAO,
                                ID       : iten.ID,
                                TIPO     : iten.TIPO + '',
                                TEXTO    : iten.DESCRICAO,
                                DEFAULT  : def,
                                MIN      : iten.MIN,
                                MAX      : iten.MAX,
                                TAMANHO  : iten.TAMANHO,
                                REQUERED : iten.REQUERED,
                                VINCULO  : '',
                                STEP     : iten.STEP,
                                CONSULTA : null,
                                ITENS    : iten.ITENS,
                                DISABLED : true,
                                AUTOLOAD : iten.AUTOLOAD,
                                JSON     : iten.JSON,

                                CAMPO_GRAVAR: iten.CAMPO_GRAVAR,
                                PAINEL_ID: vm.painel_id,

                                SQL_ID         : iten.SQL_ID,
                                TAMANHO_TABELA : iten.TAMANHO_TABELA,
                                URL_CONSULTA   : iten.URL_CONSULTA,
                                CAMPO_TABELA   : iten.CAMPO_TABELA,
                                CAMPOS_RETORNO : iten.CAMPOS_RETORNO,
                                DESC_TABELA    : iten.DESC_TABELA,

                                VINCULO_CAMPO     : iten.VINCULO_CAMPO,
                                VINCULO_ITENS     : iten.VINCULO_ITENS,
                                VINCULO_DESCRICAO : iten.VINCULO_DESCRICAO,

                                setValor : function(valor){
                                    this.VALOR = valor;
                                },
                                log:function(valor){
                                    console.log(valor);
                                }
                            };

                            if(iten.TIPO  == 2 ||
                                iten.TIPO == 6){

                                if(iten.DEFAULT == ''){
                                    valor = 0;
                                }else{
                                    valor = Number(iten.DEFAULT);
                                }    
                            }else{
                                valor = iten.DEFAULT + ''; 
                            }                        

                            if(iten.TIPO == 8 || iten.TIPO == 4 || iten.TIPO == 5){
                                obj.setValor('');    
                            }else{
                                obj.setValor(valor);  
                            }

                            if(iten.TIPO  == 9){
                                angular.forEach(obj.ITENS, function(t, i) {
                                    if(valor == t.VALOR){
                                        obj.VALOR = t;
                                    }
                                });   
                            }

                            if(iten.TIPO == 3){
                                var momentDate = moment(def);
                                def = momentDate.toDate();
                                obj.setValor(def);
                            }

                            vm.Create.itens.push(obj);
                            
                            vm.caso[caso.ID][iten.ID] = obj;
                            
                            html += vm.Create.montarHtml(obj,iten.ID,2);

                        });
                            
                        var obj   = $('.corpo-caso-'+caso.ID);
                        var scope = obj.scope(); 
                        obj.html(html);
                        var obj   = $('.corpo-caso-'+caso.ID);
                        $compile(obj.contents())(scope);
                    });
                    vm.Acoes.Canselar();
                },300);
            });
        }

        vm.init = function(){

            vm.painel_id = $('._painel_id').val();
            if(vm.caso_id != -1){
                vm.caso_id   = $('._caso_id').val();
            }

            vm.PainelCaso = [];
            vm.PainelConf = [];
            vm.Validacao  = [];
            vm.CasoIten   = [];
            vm.response   = [];
            vm.Feed       = [];
            vm.Contatos   = [];
            vm.Envolvidos.dados = {};

            var caso = vm.caso_id;
            if(vm.caso_id == -1){
                caso = 0;
            }

            $ajax.post('/_11150/getPainel', {PAINEL_ID: vm.painel_id,CASO_ID: caso})
                .then(function(response) {

                    vm.PainelCaso = response.PAINEl_CASO;
                    vm.PainelConf = response.PAINEl_CONF;
                    vm.Validacao  = response.VALIDACAO;
                    vm.CasoIten   = response.CASO_ITEN;
                    vm.response   = response;
                    vm.Feed       = response.FEED;
                    vm.Contatos   = response.CONTATOS;

                    vm.Consulta_Motivos.option.filtro_sql      = {PAINEL_CASO: vm.PainelCaso};
                    vm.Consulta_Responsavel.option.filtro_sql  = {PAINEL_CASO: vm.PainelCaso};
                    vm.Consulta_Contato.option.filtro_sql      = {PAINEL_CASO: vm.PainelCaso};

                    if(caso == 0){
                        vm.Consulta_Status.option.filtro_sql   = {PAINEL_CASO: vm.PainelCaso, ABERTO: 1};
                    }else{
                        vm.Consulta_Status.option.filtro_sql   = {PAINEL_CASO: vm.PainelCaso, ABERTO: 0};
                    }

                    vm.Consulta_Motivos.option.paran['ID']     = 0;
                    vm.Consulta_Responsavel.option.paran['ID'] = 0;
                    vm.Consulta_Contato.option.paran['ID']     = 0;
                    vm.Consulta_Status.option.paran['ID']      = 0;
                    vm.Consulta_Tipos.option.paran['ID']       = 0;
                    vm.Consulta_Origens.option.paran['ID']     = 0;

                    if(vm.caso_id == -1){
                        vm.Acoes.tratarItens(0,false,true);
                    }else{
                        if(vm.caso_id == 0){
                            vm.Acoes.tratarItens(0,false);
                            vm.Consulta_Motivos.filtrar();
                            $('.modal-caso').find('.modal-body').scrollTop(0);
                            vm.hideTabs(true);
                            setTimeout(function(){
                                $('#tab-caso').trigger('click');
                                $('#tab-caso').focus();
                            },100);
                        }else{
                            vm.Acoes.tratarItens(1,true);
                            vm.hideTabs(false);

                            setTimeout(function(){
                                $('#tab-feed').trigger('click');
                            },100);
                        }

                        $('#modal-caso').modal();
                        setTimeout(function(){
                            $('.modal-caso').find('.modal-body').scrollTop(0);
                        },500);

                        vm.btnGravar.disabled = false;
                    }

                    vm.caso_id   = $('._caso_id').val();
     
                }
            );
        }

        vm.newLembrete = {
            ID          : 0,
            TIPO        : 0,
            TODOS       : 0,
            MENU_ID     : 0,
            TITULO      : 'Lembrete Casos',
            LEITURA     : 0,
            ENVIO       : 0,
            EMITENTE    : 0,
            AGENDAMENTO : 0,
            EXECUTADO   : 0,
            TABELA      : 'TBCASO',
            TABELA_ID   : 0,
            PAINEL_ID   : 0
        },

        vm.lembrete = {
            datahora   : '',
            comentario : '',
            iten       : {},
            dados      : {},
            min        : new Date(),
            add: function(){
                $('#modal-add-lembrete').modal();

                this.iten = angular.copy(vm.newLembrete);
                this.iten.TABELA_ID = angular.copy(vm.caso_id);
                this.iten.PAINEL_ID = angular.copy(vm.painel_id);

                this.iten.AGENDAMENTO = moment().toDate();
                this.min = moment().toDate();

                CKEDITOR.instances.editor5.setData('');

            },
            editar: function(iten){
                $('#modal-add-lembrete').modal();

                var obj             = angular.copy(iten);
                this.iten           = obj;
                this.iten.PAINEL_ID = angular.copy(vm.painel_id);

                CKEDITOR.instances.editor5.setData(obj.MENSAGEM);

            },
            gravar: function(){
                var that = this;

                addConfirme('Gravar',
                        'Deseja realmente gravar lembrete:'
                        ,[obtn_ok,obtn_cancelar],
                    [
                    {ret:1,func:function(e){

                        var dados = {
                            MENSAGEM    : CKEDITOR.instances.editor5.getData(),
                            ID          : that.iten.ID,
                            TIPO        : 0,
                            TITULO      : that.iten.TITULO,
                            AGENDAMENTO : that.iten.AGENDAMENTO,
                            EXECUTADO   : 0,
                            TABELA      : that.iten.TABELA,
                            TABELA_ID   : that.iten.TABELA_ID,
                            PAINEL_ID   : that.iten.PAINEL_ID,
                        };

                        $ajax.post('/_11190/gravarLembrete', dados)
                            .then(function(response) {
                                that.canselar(); 
                                that.atualizar();   
                            }
                        );

                    }},
                    {ret:2,func:function(e){


                    }},
                    ]  
                );
                
            },
            excluir: function(){
                var that = this;

                addConfirme('Excluir',
                        'Deseja realmente excluir lembrete:'
                        ,[obtn_ok,obtn_cancelar],
                    [
                    {ret:1,func:function(e){

                        var dados = {
                            MENSAGEM    : CKEDITOR.instances.editor5.getData(),
                            ID          : that.iten.ID,
                            TIPO        : 0,
                            TITULO      : that.iten.TITULO,
                            AGENDAMENTO : that.iten.AGENDAMENTO,
                            EXECUTADO   : 0,
                            TABELA      : that.iten.TABELA,
                            TABELA_ID   : that.iten.TABELA_ID,
                        };

                        $ajax.post('/_11190/excluirLembrete', dados)
                            .then(function(response) {
                                that.canselar(); 
                                that.atualizar();   
                            }
                        );

                    }},
                    {ret:2,func:function(e){


                    }},
                    ]  
                );
                
            },
            canselar: function(){
                $('#modal-add-lembrete').modal('hide');
            },
            atualizar: function(){
                var that   = this;
                var TABELA_ID = angular.copy(vm.caso_id);

                var dados = {
                    TABELA      : 'TBCASO',
                    TABELA_ID   : TABELA_ID,
                };

                $ajax.post('/_11190/getNotifCasos', dados)
                    .then(function(response) {
                        that.dados = response; 

                        angular.forEach(that.dados, function(iten, key) {
                            iten.DATA_HORA   = moment(iten.DATA_HORA).toDate();
                            iten.AGENDAMENTO = moment(iten.AGENDAMENTO).toDate();
                        });   
                    }
                );
                    
            }
        };

        vm.Envolvidos = {
            dados      : {},
            add: function(){
                var that = this;

                var id   = vm.Consulta_Envolvidos.item.dados.ID
                var nome = vm.Consulta_Envolvidos.item.dados.DESCRICAO

                addConfirme('Remover',
                        'Deseja realmente adicionar '+nome+'?'
                        ,[obtn_ok,obtn_cancelar],
                    [
                    {ret:1,func:function(e){

                        var dados = {
                            PAINEL_ID  : vm.painel_id,
                            CASO_ID    : vm.caso_id,
                            USUARIO_ID : id
                        };

                        $ajax.post('/_11150/grvEnvolvidos', dados)
                            .then(function(response) {
                                that.atualizar();
                                showSuccess('Adicionado!'); 
                            }
                        );

                        vm.Consulta_Envolvidos.apagar();

                    }},
                    {ret:2,func:function(e){
                        vm.Consulta_Envolvidos.apagar();
                    }},
                    ]  
                );
                
            },
            excluir: function(item){
                var that = this;

                addConfirme('Remover',
                        'Deseja realmente remover '+item.NOME+'?'
                        ,[obtn_ok,obtn_cancelar],
                    [
                    {ret:1,func:function(e){

                        $ajax.post('/_11150/rmvEnvolvidos', item)
                            .then(function(response) {
                                that.atualizar();
                                showSuccess('Removido!'); 
                            }
                        );

                    }},
                    {ret:2,func:function(e){


                    }},
                    ]  
                );
                
            },
            atualizar: function(){
                vm.Consulta_Envolvidos.option.paran = {PAINEL_ID: vm.painel_id}

                var that   = this;
                var TABELA_ID = angular.copy(vm.caso_id);

                var dados = {
                    PAINEL_ID : vm.painel_id,
                    CASO_ID   : vm.caso_id,
                };

                $ajax.post('/_11150/getEnvolvidos', dados)
                    .then(function(response) {
                        that.dados = response; 
                    }
                );
                    
            }
        };

        vm.Acoes = {

            modalFeed: function(feeed){
                var modal = $('.feed-caso'+feeed.ID);
                if($(modal).hasClass('email_model')){
                    $(modal).removeClass('email_model');
                }else{
                    $(modal).addClass('email_model');
                }
            },            
            fimCaso:function(){
                that = this;
                vm.painel_id = $('._painel_id').val();
                vm.caso_id   = $('._caso_id').val();

                vm.finalizar.problema = CKEDITOR.instances.editor2.getData();
                vm.finalizar.solucao  = CKEDITOR.instances.editor3.getData(); 

                if((vm.finalizar.problema + '').length >= 30){
                    if((vm.finalizar.solucao + '').length >= 30){

                        addConfirme('Finalizar caso',
                                'Deseja realmente finalizar caso:'+vm.caso_id 
                                ,[obtn_ok,obtn_cancelar],
                            [
                            {ret:1,func:function(e){

                                $ajax.post('/_11150/finalizar', vm.finalizar)
                                    .then(function(response) {

                                        $('#modal-finalizar').modal('hide');
                                        showSuccess('Caso finalizado');

                                        setTimeout(function(){
                                            $('.atualizar-files').trigger('click');
                                        },300);  

                                    }
                                );

                            }},
                            {ret:2,func:function(e){


                            }},
                            ]  
                        );

                    }else{
                        showErro("Solução para este caso, tem menos de 30 caracteres");     
                    }
                }else{
                    showErro("Descrição técnica do caso, tem menos de 30 caracteres");   
                }

            },
            CanselarFinalizar: function(){           
                $('#modal-finalizar').modal('hide');
            },
            finalizarCaso:function(caso_id){
                that = this;
                vm.painel_id = $('._painel_id').val();
                vm.caso_id   = $('._caso_id').val();

                vm.finalizar = {};

                vm.finalizar.caso_id   = vm.caso_id;
                vm.finalizar.painel_id = vm.painel_id;
                vm.finalizar.problema  = '';
                vm.finalizar.solucao   = '';

                $('#modal-finalizar').modal();

                CKEDITOR.instances.editor2.setData('');
                CKEDITOR.instances.editor3.setData(''); 

            },
            editarFeedArquivo: function(feed){
                var tmp = angular.copy(feed);

                $('#modal-file').modal();  
                vm.Arquivos.data = tmp.FILE;
                vm.Arquivos.data_excluir = [];
                vm.Arquivos.comentario = tmp.MENSAGEM;
                vm.Arquivos.coment = tmp.COMENT;

                vm.Arquivos.assunto = tmp.ASSUNTO;

                vm.EmailContato1.itens = [];
                vm.EmailContato2.itens = [];
                vm.EmailContato3.itens = [];
                vm.EmailContato4.itens = [];

                vm.Arquivos.de      = tmp.DE;
                vm.Arquivos.para    = tmp.PARA;
                vm.Arquivos.cc      = tmp.EM_COPIA;
                vm.Arquivos.cco     = tmp.EM_COPIA_OCULTA;

                if((tmp.DE   + '').length > 0)
                vm.EmailContato1.itens = (tmp.DE   + '').split(",");

                if((tmp.PARA   + '').length > 0)
                vm.EmailContato2.itens = (tmp.PARA + '').split(",");

                if((tmp.EM_COPIA   + '').length > 0)
                vm.EmailContato3.itens = (tmp.EM_COPIA   + '').split(",");

                if((tmp.EM_COPIA_OCULTA   + '').length > 0)
                vm.EmailContato4.itens = (tmp.EM_COPIA_OCULTA  + '').split(",");

                CKEDITOR.instances.editor1.setData(tmp.MENSAGEM);

                vm.Arquivos.vGravar = true;
                vm.Arquivos.editando = true;

                vm.tipo_feed   = tmp.TIPO;

                vm.feed_editar = tmp;
                    
            },
            excluirFeedArquivo:function(painel_id, caso_id, user, feed){
                that = this;
                var temp_feed = angular.copy(feed);

                addConfirme('Excluir Feed',
                        'Deseja realmente excluir este Feed:'+feed.ID
                        ,[obtn_ok,obtn_cancelar],
                    [
                    {ret:1,func:function(e){

                        var dados = {};

                        dados.FEED_ID         = feed.ID;

                        dados.PAINEL_ID       = painel_id;
                        dados.CASO_ID         = caso_id;
                        dados.DE              = user.EMAIL; 
                        dados.PARA            = ''; 
                        dados.EM_COPIA        = ''; 
                        dados.EM_COPIA_OCULTA = ''; 
                        dados.MENSAGEM        = that.comentario; 
                        dados.ASSUNTO         = ''; 
                        dados.FILES           = 1;
                        dados.SUBFEED         = 0;
                        dados.TIPO            = 2;
                        dados.USUARIO_ID      = user.CODIGO;

                        dados.ARQUIVOS        = that.data;
                        dados.EXCLUIR         = that.data_excluir;
                        console.log(dados);

                        $ajax.post('/_11150/excluirFeed', dados)
                            .then(function(response) {
                                vm.Arquivos.data         = [];
                                vm.Arquivos.data_excluir = [];
                                vm.Arquivos.comentario   = [];
                                vm.Arquivos.vGravar      = false;
                                vm.Arquivos.editando     = false;
                                vm.feed_editar           = [];

                                $('#modal-file').modal('hide'); 

                                setTimeout(function(){

                                    if(temp_feed.COMENT == 0){
                                        $('.atualizar-files').trigger('click');
                                    }else{
                                        $('#tab-files').trigger('click');
                                    }

                                },300);

                            }
                        );

                    }},
                    {ret:2,func:function(e){


                    }},
                    ]  
                );

            },
            comentarFeed: function(feed){

                console.log(feed);

                var tmp = angular.copy(feed);
                var id = tmp.CASO_ID;

                vm.Arquivos.data = [];
                vm.Arquivos.data_excluir = [];
                vm.Arquivos.comentario = [];
                vm.Arquivos.vGravar = true;
                vm.Arquivos.editando = false;
                vm.Arquivos.coment = tmp.COMENT;

                CKEDITOR.instances.editor1.setData('');

                vm.Arquivos.de      = vm.user.EMAIL;
                vm.Arquivos.para    = tmp.DE;
                vm.Arquivos.cc      = '';
                vm.Arquivos.cco     = '';

                vm.EmailContato1.itens = [];
                vm.EmailContato2.itens = [];
                vm.EmailContato3.itens = [];
                vm.EmailContato4.itens = [];

                vm.EmailContato1.itens.push(vm.user.EMAIL);
                vm.EmailContato2.itens.push(tmp.DE);
                
               
                vm.Arquivos.assunto = 'Re: [CASO: '+lpad(id,"0", 8)+'] - ' + vm.Consulta_Motivos.dados.DESCRICAO;

                vm.tipo_feed = 99;

                vm.feed_editar = feed;

                $('#modal-file').modal(); 
                    
            },
            gosteiFeed: function(feed){

                $ajax.post('/_11150/gostei', {FEED_ID: feed.ID},{progress: false})
                .then(function(response) {

                    feed.USUARIO_GOSTOU = response.USUARIO_GOSTOU;
                    feed.QTD_GOSTOU     = response.QTD_GOSTOU;
                   
                },function(){
                    //vm.btnGravar.disabled = false;
                }); 

            },
            responderEmail: function(feed,flag){

            },
            feedFile: function(comentario){

                var id = vm.caso_id;

                vm.Arquivos.de      = vm.user.EMAIL;
                vm.Arquivos.para    = vm.PainelCaso.EMAIL;
                vm.Arquivos.cc      = '';
                vm.Arquivos.cco     = '';
                vm.Arquivos.coment  = comentario;

                vm.EmailContato1.itens = [];
                vm.EmailContato2.itens = [];
                vm.EmailContato3.itens = [];
                vm.EmailContato4.itens = [];

                vm.EmailContato1.itens.push(vm.user.EMAIL);
                vm.EmailContato2.itens.push(vm.PainelCaso.EMAIL);
                
                vm.Arquivos.assunto = 'Arquivos: [Caso: '+lpad(id,"0", 8)+'] - ' + vm.Consulta_Motivos.dados.DESCRICAO;
                CKEDITOR.instances.editor1.setData('');

                vm.tipo_feed = 2;
                $('#modal-file').modal();     
            },
            feedEmail: function(comentario){

                var id = vm.caso_id;

                vm.Arquivos.de      = vm.user.EMAIL;
                vm.Arquivos.para    = ''; //vm.PainelCaso.EMAIL;
                vm.Arquivos.cc      = '';
                vm.Arquivos.cco     = '';
                vm.Arquivos.coment  = comentario;

                vm.EmailContato1.itens = [];
                vm.EmailContato2.itens = [];
                vm.EmailContato3.itens = [];
                vm.EmailContato4.itens = [];

                vm.EmailContato1.itens.push(vm.user.EMAIL);

                var caso_contato = (vm.PainelCaso.EMAIL_MONITOR + '').split(',');

                for (var i = 0; i < caso_contato.length; i++) {
                    vm.EmailContato4.itens.push((caso_contato[i] + '').trim());
                }
                

                vm.Arquivos.assunto = 'Re: [CASO: '+lpad(id,"0", 8)+'] - ' + vm.Consulta_Motivos.dados.DESCRICAO;
                CKEDITOR.instances.editor1.setData('');

                vm.editar_contato = false;
                vm.tipo_feed = 1;
                $('#modal-file').modal();     
            },
            openListaContato: function(){
                var imput = $("input[name*='filtro_pesquisa']");
                if(imput.length > 0){
                    imput.focus();
                }
            },
            openCaso: function(id,event){
                if(event == null || event.key == 'Enter'){
                    $('._caso_id').val(id)
                    vm.init();
                }
            },
            Canselar: function(){
                var id = $('._caso_id').val();
                var linhas = $('.tabela-itens-caso').find('tr');
                var iten = $('.caso_iten_'+id);

                if(iten.length > 0){
                    $('.caso_iten_'+id).focus();
                }else{
                    if(linhas.length > 0){
                        $(linhas[0]).focus();
                    }
                }
            },
            addCaso: function(){
                $('._caso_id').val(0);
                vm.init();
            },
            removerEnter: function(str){
                str = str + '';
                str = str.replace(/(?:\r\n|\r|\n)/g, '<br/>');
                return str;
            },
            validarGravar: function(){
                var that = this;
                var ret = false;
                vm.btnGravar.disabled = true;

                var script = '';

                angular.forEach(vm.Create.model_itens, function(iten, key) {
                    var val;

                    if(iten.TIPO == 1 || iten.TIPO == 3 || iten.TIPO == 8 || iten.TIPO == 10){
                        val = '\''+iten.VALOR+'\'';
                    }
                    
                    if(iten.TIPO == 2 || iten.TIPO == 6){
                        val = iten.VALOR;
                    }

                    if(iten.TIPO == 4 || iten.TIPO == 5 || iten.TIPO == 9){
                        val = JSON.stringify(iten.VALOR);
                    }

                    if(iten.TIPO == 7){
                        val = JSON.stringify(iten.CONSULTA.item.dados);
                    }

                    if(val == undefined){
                        val = '\'\'';
                    }

                    script += 'var '+iten.VAR_NOME+' = '+that.removerEnter(val)+';\n';
                });

                angular.forEach(vm.parametro, function(iten, key) {
                    var val = '';

                    if($.isNumeric(iten.VALOR)){
                        val = iten.VALOR;
                    }else{
                        val = '\''+iten.VALOR+'\'';
                    }

                    script += 'var '+iten.NOME+' = '+that.removerEnter(val)+';\n';
                });

                

                var val; val = JSON.stringify(vm.Consulta_Motivos.item.dados);
                script += 'var MOTIVO = '+that.removerEnter(val)+';\n';

                var val; val = JSON.stringify(vm.Consulta_Tipos.item.dados);
                script += 'var TIPO = '+that.removerEnter(val)+';\n';

                var val; val = JSON.stringify(vm.Consulta_Origens.item.dados);
                script += 'var ORIGEM = '+that.removerEnter(val)+';\n';

                var val; val = JSON.stringify(vm.Consulta_Responsavel.item.dados);
                script += 'var RESPONSAVEL = '+that.removerEnter(val)+';\n';

                var val; val = JSON.stringify(vm.Consulta_Status.item.dados);
                script += 'var STATUS = '+that.removerEnter(val)+';\n';

                var val; val = JSON.stringify(vm.Consulta_Contato.item.dados);
                script += 'var CONTATO = '+that.removerEnter(val)+';\n';


                var erro = 0;
                angular.forEach(vm.Validacao, function(iten, key) {
                    var formula = script+' \n '+iten.FORMULA;
                    console.log(formula);
                    var valor   = vm.ScriptCompile.compile(formula);
 
                    if(valor == 'true' || valor == 'false'){
                        if(valor == 'false'){
                            showErro(iten.MENSAGEM);
                            erro = 1;    
                        }
                    }else{
                        showErro('Erro nas regras de validação:('+valor+')');
                        erro = 1;
                    }
                });

                if(erro == 0){

                    if(!vm.Consulta_Motivos.item.selected){
                        showErro('Motivo é obrigatório!');
                        vm.Consulta_Motivos.filtrar();
                        vm.btnGravar.disabled = false;
                    }else{

                    if(!vm.Consulta_Tipos.item.selected){
                        showErro('Tipo é obrigatório!');
                        vm.Consulta_Tipos.filtrar();
                        vm.btnGravar.disabled = false;
                    }else{ 

                    if(!vm.Consulta_Origens.item.selected){
                        showErro('Tipo de Origem é obrigatório!');
                        vm.Consulta_Origens.filtrar();
                        vm.btnGravar.disabled = false;
                    }else{

                    if(!vm.Consulta_Responsavel.item.selected){
                        showErro('Responsável é obrigatório!');
                        vm.Consulta_Responsavel.filtrar();
                        vm.btnGravar.disabled = false;
                    }else{

                    if(!vm.Consulta_Status.item.selected){
                        showErro('Status é obrigatório!');
                        vm.Consulta_Status.filtrar();
                        vm.btnGravar.disabled = false;
                    }else{

                        vm.Create.itens = [];
                        var obj_temp = angular.copy(vm.itens);
                        angular.forEach(obj_temp, function(iten, key) {
                            if(iten != undefined){
                                vm.Create.itens.push(iten);
                            }
                        });

                        var validar = vm.Create.validarCampos();
                        if(validar){
                            
                            ret = true;
                            
                        }else{
                            vm.btnGravar.disabled = false;
                        }

                    }}}}}
                }else{
                    vm.btnGravar.disabled = false;
                }

                return ret;
            },
            gravarCaso: function(){
                var that    = this;
                var validar = that.validarGravar();
                
                if(validar){
                    var campos = {};
                        campos.MOTIVO      = vm.Consulta_Motivos.item.dados.ID;
                        campos.TIPO        = vm.Consulta_Tipos.item.dados.ID;
                        campos.ORIGEM      = vm.Consulta_Origens.item.dados.ID;
                        campos.RESPONSAVEL = vm.Consulta_Responsavel.item.dados.CODIGO;
                        campos.STATUS      = vm.Consulta_Status.item.dados.ID;
                        campos.CONTATO     = vm.Consulta_Contato.item.dados.ID;

                    vm.painel_id = $('._painel_id').val();
                    vm.caso_id   = $('._caso_id').val();
                    
                    vm.Create.itens = [];
                    var obj_temp = angular.copy(vm.itens);
                    angular.forEach(obj_temp, function(iten, key) {
                        if(iten != undefined){
                            vm.Create.itens.push(iten);
                        }
                    });

                    var itens    = vm.Create.tratarCampos();

                    $ajax.post('/_11150/gravarCaso', {ITENS: itens, CAMPOS: campos, PAINEL_ID: vm.painel_id, CASO_ID: vm.caso_id})
                    .then(function(response) {

                        showSuccess('Gravado!');

                        vm.getCasos(vm.abaAberta);
                        that.openCaso(response,null);
                       
                    },function(){
                        vm.btnGravar.disabled = false;
                    });
                    
                }

            },
            canselarAlteracaoCaso: function(){ 
                vm.hideTabs(false);
                this.tratarItens(1,true);
            },
            tratarItens: function(tela,disable,filtro){
                vm.status_tela = tela;
                vm.PainelConfEdit = [];
                vm.PainelConfEdit = angular.copy(vm.PainelConf);

                if(disable){
                    vm.PainelCaso.OLD_CONTATO_CADASTRO = vm.PainelCaso.CONTATO_CADASTRO;
                    vm.PainelCaso.CONTATO_CADASTRO = 0;
                }

                vm.Consulta_Motivos.apagar();
                vm.Consulta_Responsavel.apagar();
                vm.Consulta_Contato.apagar();
                vm.Consulta_Status.apagar();
                vm.Consulta_Tipos.apagar();
                vm.Consulta_Origens.apagar();

                vm.Consulta_Motivos.disable(true);
                vm.Consulta_Responsavel.disable(true);
                vm.Consulta_Contato.disable(true);
                vm.Consulta_Status.disable(true);
                vm.Consulta_Tipos.disable(true);
                vm.Consulta_Origens.disable(true);

                var motivo;
                motivo = angular.copy(vm.response['MOTIVO']);
                vm.Consulta_Motivos.dados = motivo;
                vm.Consulta_Motivos.selecionarItem(motivo);

                var responsavel;
                responsavel = angular.copy(vm.response['RESPONSAVEL']);
                vm.Consulta_Responsavel.dados = responsavel;
                vm.Consulta_Responsavel.selecionarItem(responsavel);

                var contato;
                contato = angular.copy(vm.response['CONTATO']);
                vm.Consulta_Contato.dados = contato;
                vm.Consulta_Contato.selecionarItem(contato);

                var status;
                status = angular.copy(vm.response['STATUS']);
                vm.Consulta_Status.dados = status;
                vm.Consulta_Status.selecionarItem(status);

                var tipo;
                tipo = angular.copy(vm.response['TIPO']);
                vm.Consulta_Tipos.dados = tipo;
                vm.Consulta_Tipos.selecionarItem(tipo);

                var origen;
                origen = angular.copy(vm.response['ORIGEM']);
                vm.Consulta_Origens.dados = origen;
                vm.Consulta_Origens.selecionarItem(origen);

                vm.Consulta_Motivos.disable(disable);
                vm.Consulta_Responsavel.disable(disable);
                vm.Consulta_Contato.disable(disable);
                vm.Consulta_Status.disable(disable);
                vm.Consulta_Tipos.disable(disable);
                vm.Consulta_Origens.disable(disable);

                ////////////////////////////////////////////////////////////////////////
                    var grupo_id = 0;
                    var html  = '';
                    vm.Create.itens = [];
                    vm.itens        = [];
                    vm.Create.model = 'vm.itens';

                    angular.forEach(vm.PainelConfEdit, function(iten, key) { 

                        if(grupo_id != iten.GRUPO_ID){
                            grupo_id = iten.GRUPO_ID;
                            html += '<div class="barra_descricao ng-binding">'+iten.AGRUP+'</div>';
                        }

                        var valor;
                        var def = iten.DEFAULT;
                        
                        var obj = {
                            VAR_NOME : iten.VAR_NOME,
                            VALOR    : def,
                            EDIT     : 0,
                            NOME     : iten.DESCRICAO,
                            ID       : iten.ID,
                            TIPO     : iten.TIPO + '',
                            TEXTO    : iten.DESCRICAO,
                            DEFAULT  : def,
                            MIN      : iten.MIN,
                            MAX      : iten.MAX,
                            TAMANHO  : iten.TAMANHO,
                            REQUERED : iten.REQUERED,
                            VINCULO  : '',
                            STEP     : iten.STEP,
                            CONSULTA : null,
                            ITENS    : iten.ITENS,
                            DISABLED : disable,
                            AUTOLOAD : iten.AUTOLOAD,
                            JSON     : iten.JSON,

                            CAMPO_GRAVAR: iten.CAMPO_GRAVAR,
                            PAINEL_ID: vm.painel_id,

                            SQL_ID         : iten.SQL_ID,
                            TAMANHO_TABELA : iten.TAMANHO_TABELA,
                            URL_CONSULTA   : iten.URL_CONSULTA,
                            CAMPO_TABELA   : iten.CAMPO_TABELA,
                            CAMPOS_RETORNO : iten.CAMPOS_RETORNO,
                            DESC_TABELA    : iten.DESC_TABELA,

                            VINCULO_CAMPO     : iten.VINCULO_CAMPO,
                            VINCULO_ITENS     : iten.VINCULO_ITENS,
                            VINCULO_DESCRICAO : iten.VINCULO_DESCRICAO,

                            setValor : function(valor){
                                this.VALOR = valor;
                            },
                            log:function(valor){
                                console.log(valor);
                            }
                        };

                        if(iten.TIPO  == 2 ||
                            iten.TIPO == 6){

                            if(iten.DEFAULT == ''){
                                valor = 0;
                            }else{
                                valor = Number(iten.DEFAULT);
                            }    
                        }else{
                            valor = iten.DEFAULT + ''; 
                        }                        

                        if(iten.TIPO == 8 || iten.TIPO == 4 || iten.TIPO == 5){
                            obj.setValor('');    
                        }else{
                            obj.setValor(valor);  
                        }

                        if(iten.TIPO  == 9){
                            angular.forEach(obj.ITENS, function(t, i) {
                                if(valor == t.VALOR){
                                    obj.VALOR = t;
                                }
                            });   
                        }

                        if(iten.TIPO == 3){
                            var momentDate = moment(def);
                            def = momentDate.toDate();
                            obj.setValor(def);
                        }

                        vm.Create.itens.push(obj);
                        vm.itens[iten.ID] = obj;

                        vm.Create.model_itens[iten.ID] = obj;
                        
                        html += vm.Create.montarHtml(obj,iten.ID,2);

                    });
                    
                    if(filtro){
                        var obj   = $('.painel_imputs2');
                        var scope = obj.scope(); 
                        obj.html(html);
                        var obj   = $('.painel_imputs2');
                        $compile(obj.contents())(scope);
                    }else{    
                        var obj   = $('.painel_imputs');
                        var scope = obj.scope(); 
                        obj.html(html);
                        var obj   = $('.painel_imputs');
                        $compile(obj.contents())(scope);
                    }

                    angular.forEach(vm.Create.itens, function(iten, key) { 
                        if(iten.TIPO == 7){
                            iten.CONSULTA.getScale();
                        }
                    });

                    angular.forEach(vm.itens, function(iten, key) {
                        if(iten.TIPO == 7){
                            if(iten.VALOR != 0 && iten.VALOR != '' && iten.VALOR != '{}'){
                                if(iten.JSON != '' && iten.JSON != '{}'){
                                    var a = JSON.parse(iten.JSON);
                                    var B = iten.CONSULTA.actionsSelct;
                                    iten.CONSULTA.actionsSelct = [];
                                    iten.CONSULTA.dados = a;
                                    iten.CONSULTA.selecionarItem(a);
                                    iten.CONSULTA.disable(disable);
                                    iten.CONSULTA.actionsSelct = B;
                                }
                            }
                        }
                    });

                    //loading('hide');

                    $('.modal-caso').find('.modal-body').scrollTop(0);

            },
            alterarCaso: function(){
                this.tratarItens(2,false);
                vm.PainelCaso.CONTATO_CADASTRO = vm.PainelCaso.OLD_CONTATO_CADASTRO;
                vm.hideTabs(true);
                $('#tab-caso').trigger('click');
                $('.modal-caso').find('.modal-body').scrollTop(0);
            },
            excluirCaso: function(){

                vm.painel_id = $('._painel_id').val();
                vm.caso_id   = $('._caso_id').val();

                addConfirme('Excluir Input?',
                        'Deseja realmente excluir o caso de ID:'+vm.caso_id
                        ,[obtn_ok,obtn_cancelar],
                    [
                    {ret:1,func:function(e){

                        $ajax.post('/_11150/excluirCaso', {PAINEL_ID:vm.painel_id, CASO_ID:vm.caso_id})
                        .then(function(response) {
                            vm.getCasos(vm.abaAberta);
                            showSuccess('Caso excluído!');
                            $('._caso_id').val(0);
                            $('#modal-caso').modal('hide');
                        });

                    }},
                    {ret:2,func:function(e){


                    }},
                    ]  
                );

            },
            btnVoltar: function(){

            },
            btnGravar: function(){
                var validar = vm.Create.validarCampos();

                if(validar){

                    var itens = vm.Create.tratarCampos();

                    $ajax.post('/_11150/gravarContato', {ITENS: itens, PAINEL_ID: vm.PainelCaso['ID']})
                    .then(function(response) {
                        $('#modal-cad-contato').modal('hide');
                        showSuccess('CADASTRO EFETUADO COM SUCESSO!');

                        if(vm.Consulta_Contato.selected == null){
                            vm.Consulta_Contato.option.filtro_sql = {PAINEL_CASO: vm.PainelCaso,CONTATO_ID: response};
                            vm.Consulta_Contato.filtrar();
                            vm.Consulta_Contato.option.filtro_sql = {PAINEL_CASO: vm.PainelCaso};
                        }
                    });

                }

            },
            historico: function(){
                vm.tabHistory.dados = [];

                var painel_id = $('._painel_id').val();
                var caso_id   = $('._caso_id').val();

                $ajax.post('/_11150/historico', {PAINEL_ID: painel_id, CASO_ID: caso_id})
                .then(function(response) {
                    vm.tabHistory.dados = response;
                });

            },
            comentario: function(){
                vm.tabComentario.dados = [];

                var painel_id = $('._painel_id').val();
                var caso_id   = $('._caso_id').val();

                $ajax.post('/_11150/comentario', {PAINEL_ID: painel_id, CASO_ID: caso_id})
                .then(function(response) {
                    vm.tabComentario.dados = response;
                });

            },
            selectContato: function(id){
                $('#modal-cad-contato').modal('hide');
                if(vm.Consulta_Contato.selected != null){
                    vm.Consulta_Contato.apagar();    
                }

                vm.Consulta_Contato.option.filtro_sql = {PAINEL_CASO: vm.PainelCaso,CONTATO_ID: id};
                vm.Consulta_Contato.filtrar();
                vm.Consulta_Contato.option.filtro_sql = {PAINEL_CASO: vm.PainelCaso};
            },
            modalAddContato: function(){

                var grupo_id = 0;

                $ajax.post('/_11150/confContato', {PAINEL_CASO: vm.PainelCaso})
                .then(function(response) {

                        vm.ConfConato = response['IMPUTS'];
                        vm.ListaConato = response['CONTATOS'];

                        var html  = '';
                        vm.Create.itens = [];
                        vm.Create.model = 'vm.Create.itens';

                        angular.forEach(response['IMPUTS'], function(iten, key) { 

                            if(grupo_id != iten.GRUPO_ID){
                                grupo_id = iten.GRUPO_ID;
                                html += '<div class="barra_descricao ng-binding">'+iten.AGRUP+'</div>';
                            }

                            var valor;
                            var obj = {
                                VALOR    : null,
                                EDIT     : 0,
                                NOME     : iten.DESCRICAO,
                                ID       : iten.ID,
                                TIPO     : iten.TIPO + '',
                                TEXTO    : iten.DESCRICAO,
                                DEFAULT  : iten.DEFAULT,
                                MIN      : iten.MIN,
                                MAX      : iten.MAX,
                                TAMANHO  : iten.TAMANHO,
                                REQUERED : iten.REQUERED,
                                VINCULO  : '',
                                STEP     : iten.STEP,
                                CONSULTA : null,
                                ITENS    : iten.ITENS,
                                DISABLED : false,
                                CAMPO_GRAVAR: iten.CAMPO_GRAVAR,
                                setValor : function(valor){
                                    this.VALOR = valor;
                                }
                            };

                            if(iten.TIPO  == 2 ||
                                iten.TIPO == 6){

                                if(iten.DEFAULT == ''){
                                    valor = 0;
                                }else{
                                    valor = Number(iten.DEFAULT);
                                }    
                            }else{
                                valor = iten.DEFAULT + '';    
                            }

                            if(iten.TIPO  == 3 || iten.TIPO == 8 || iten.TIPO == 4 || iten.TIPO == 5){
                                obj.setValor('');    
                            }else{
                                obj.setValor(valor);  
                            }

                            if(iten.TIPO  == 9){
                                angular.forEach(obj.ITENS, function(t, i) {

                                    if(valor == t.VALOR){
                                        obj.VALOR = t;
                                    }
                                });   
                            }

                            //vm.Create.itens.push(obj);
                            vm.Create.itens[iten.ID] = obj;
                            
                            html += vm.Create.montarHtml(obj,iten.ID,2);

                        });
                        
                        var obj   = $('#modal-cad-contato').find('.imput-itens-cad-contato');
                        var scope = obj.scope(); 
                        obj.html(html);
                        var obj   = $('#modal-cad-contato').find('.imput-itens-cad-contato');
                        $compile(obj.contents())(scope);

                        angular.forEach(response['IMPUTS'], function(iten, key) { 
                            if(iten.TIPO == 7){
                                iten.CONSULTA.getScale();
                            }
                        });

                        
                        $('#modal-cad-contato').modal();

                        setTimeout(function(){
                            $('#tab-cadastro').trigger('click');

                            $('imput-itens-cad-contato').focus();
                        },200);
                    }
                ); 
            }
        };

        vm.Consulta     = new $consulta();
        gScope.Consulta = vm.Consulta;

        vm.Consulta_Motivos     = vm.Consulta.getNew();
        vm.Consulta_Tipos       = vm.Consulta.getNew();
        vm.Consulta_Origens     = vm.Consulta.getNew();
        vm.Consulta_Responsavel = vm.Consulta.getNew();
        vm.Consulta_Status      = vm.Consulta.getNew();
        vm.Consulta_Contato     = vm.Consulta.getNew();
        vm.Consulta_Envolvidos  = vm.Consulta.getNew();

        vm.Consulta_Envolvidos.componente               = '.consulta_envolvidos',
        vm.Consulta_Envolvidos.model                    = 'vm.Consulta_Envolvidos',
        vm.Consulta_Envolvidos.option.label_descricao   = 'Usuários:',
        vm.Consulta_Envolvidos.option.obj_consulta      = '/_11150/listEnvolvidos',
        vm.Consulta_Envolvidos.option.tamanho_input     = 'input-medio';
        vm.Consulta_Envolvidos.option.class             = 'consulta_Envolvidos_caso';
        vm.Consulta_Envolvidos.option.tamanho_tabela    = 450;

        vm.Consulta_Motivos.componente                  = '.consulta_motivos',
        vm.Consulta_Motivos.model                       = 'vm.Consulta_Motivos',
        vm.Consulta_Motivos.option.label_descricao      = 'Motivo do caso:',
        vm.Consulta_Motivos.option.obj_consulta         = '/_11150/Motivos',
        vm.Consulta_Motivos.option.tamanho_input        = 'input-medio';
        vm.Consulta_Motivos.option.class                = 'consulta_motivos_caso';
        vm.Consulta_Motivos.option.tamanho_tabela       = 300;

        vm.Consulta_Tipos.componente                    = '.consulta_tipos',
        vm.Consulta_Tipos.model                         = 'vm.Consulta_Tipos',
        vm.Consulta_Tipos.option.label_descricao        = 'Tipo:',
        vm.Consulta_Tipos.option.obj_consulta           = '/_11150/Tipos',
        vm.Consulta_Tipos.option.tamanho_input          = 'input-medio';
        vm.Consulta_Tipos.option.class                  = 'consulta_tipos_caso';
        vm.Consulta_Tipos.option.tamanho_tabela         = 300;

        vm.Consulta_Origens.componente                  = '.consulta_origens',
        vm.Consulta_Origens.model                       = 'vm.Consulta_Origens',
        vm.Consulta_Origens.option.label_descricao      = 'Tipo de Origem:',
        vm.Consulta_Origens.option.obj_consulta         = '/_11150/Origens',
        vm.Consulta_Origens.option.tamanho_input        = 'input-maior';
        vm.Consulta_Origens.option.class                = 'consulta_origens_caso';
        vm.Consulta_Origens.option.tamanho_tabela       = 385;

        vm.Consulta_Responsavel.componente              = '.consulta_responsavel',
        vm.Consulta_Responsavel.model                   = 'vm.Consulta_Responsavel',
        vm.Consulta_Responsavel.option.label_descricao  = 'Responsável:',
        vm.Consulta_Responsavel.option.obj_consulta     = '/_11150/Responsavel',
        vm.Consulta_Responsavel.option.tamanho_input    = 'input-medio';
        vm.Consulta_Responsavel.option.obj_ret          = ['CODIGO','USUARIO'],
        vm.Consulta_Responsavel.option.campos_tabela    = [['CODIGO','ID'],['USUARIO','NOME']],
        vm.Consulta_Responsavel.option.class            = 'consulta_responsavel_caso';
        vm.Consulta_Responsavel.option.tamanho_tabela   = 300;

        vm.Consulta_Contato.componente                  = '.consulta_contato',
        vm.Consulta_Contato.model                       = 'vm.Consulta_Contato',
        vm.Consulta_Contato.option.label_descricao      = 'Nome do contato:',
        vm.Consulta_Contato.option.obj_consulta         = '/_11150/Contatos',
        vm.Consulta_Contato.option.tamanho_input        = 'input-medio';
        vm.Consulta_Contato.option.class                = 'consulta_contato_caso';
        vm.Consulta_Contato.option.required             = false;
        vm.Consulta_Contato.option.tamanho_tabela       = 300;

        vm.Consulta_Status.componente                   = '.consulta_status',
        vm.Consulta_Status.model                        = 'vm.Consulta_Status',
        vm.Consulta_Status.option.label_descricao       = 'Status:',
        vm.Consulta_Status.option.obj_consulta          = '/_11150/Status',
        vm.Consulta_Status.option.tamanho_input         = 'input-medio';
        vm.Consulta_Status.option.class                 = 'consulta_status_caso';
        vm.Consulta_Status.option.tamanho_tabela        = 300;

        vm.Consulta_Motivos.compile();
        vm.Consulta_Tipos.compile();
        vm.Consulta_Origens.compile();
        vm.Consulta_Responsavel.compile();
        vm.Consulta_Status.compile();
        vm.Consulta_Contato.compile();

        vm.Consulta_Envolvidos.compile();

        vm.Consulta_Tipos.require    = vm.Consulta_Motivos;
        vm.Consulta_Origens.require  = [vm.Consulta_Motivos,vm.Consulta_Tipos];
        vm.Consulta_Tipos.vincular();
        vm.Consulta_Origens.vincular();

        vm.Consulta_Envolvidos.onSelect = function(){
            if(vm.Consulta_Envolvidos.selected != null){
                vm.Envolvidos.add();
            }   
        }

        vm.Consulta_Origens.onSelect = function(){
            if(vm.Consulta_Responsavel.selected == null){
                vm.Consulta_Responsavel.filtrar();
            }   
        }

        vm.Consulta_Responsavel.onSelect = function(){
            if(vm.Consulta_Status.selected == null){
                vm.Consulta_Status.filtrar();
            }   
        }

        vm.Consulta_Status.onSelect = function(){
            if(vm.Consulta_Contato.selected == null){
                //vm.Consulta_Contato.filtrar();
            }   
        }

        vm.Consulta_Contato.onSelect = function(){
            /*
            var imputs = $('.itens-inputs');

            if(imputs.length > 0){
                var item = imputs[0];

                var imput = $(item).find('input');

                if(imput.length > 0){
                    $(imput[0]).focus();
                }
                
            }
            */
        }      

        vm.caso_id = $('._caso_id').val();
        if(vm.caso_id > 0){
            vm.init();
        }

        if(vm.loading == 0){
            vm.getCasos(vm.abaAberta);
        }

        CKEDITOR.replace('editor1',ckConfig);
        CKEDITOR.replace('editor2',ckConfig);
        CKEDITOR.replace('editor3',ckConfig);
        CKEDITOR.replace('editor5',ckConfig);

        function validacaoEmail(field) {
            var usuario = field.substring(0, field.indexOf("@"));
            var dominio = field.substring(field.indexOf("@")+ 1, field.length);

            var ret = false;

            if ((usuario.length >=1) &&
                (dominio.length >=3) && 
                (usuario.search("@")==-1) && 
                (dominio.search("@")==-1) &&
                (usuario.search(" ")==-1) && 
                (dominio.search(" ")==-1) &&
                (dominio.search(".")!=-1) &&      
                (dominio.indexOf(".") >=1)&& 
                (dominio.lastIndexOf(".") < dominio.length - 1)) {
                ret = true;
            }

            return ret;
        }

        vm.EmailContato = {
            itens: [],
            valor: '',
            focus: false,
            class: '.EmailContato',
            listaFocus: 0,
            unico: false,
            exec : function(){},
            keypress : function($event){
                var that = this;
                if(($event.keyCode == 32 || $event.keyCode == 13) && that.valor != ''){
                    if(validacaoEmail(that.valor)){
                        var validar = true;
                        angular.forEach(that.itens, function(iten, key) { 
                            if(iten == that.valor){
                                validar = false;
                            }
                        });

                        if(validar == true){
                            if(that.unico == false || that.itens.length == 0){
                                that.itens.push(that.valor.toLowerCase());
                                that.valor = '';
                                that.setFoco();
                                that.exec();
                            }else{
                                showErro('Deve conter apenas um endereço de e-mail');    
                            }
                        }else{
                            showErro('Endereço de e-mail já na lista');
                            that.valor = '';
                            that.setFoco();
                            that.exec();   
                        }

                    }else{
                        showErro('Endereço de e-mail inválido');
                        that.valor = '';
                        that.setFoco();
                        that.exec();
                    }

                    that.listaFocus = 0;
                }
            },
            keydown: function($event){
                var that = this;
                //console.log($event);
                if(($event.keyCode == 8 || $event.keyCode == 46) && (that.valor == '' || that.valor == undefined)){
                    if(that.itens.length > 0){
                        clearTimeout(that.time);
                        that.itens.splice(that.itens.length - 1, 1);
                        that.setFoco();
                        that.exec();
                    }
                }

                if($event.keyCode == 40){

                    var itens = $(that.class).find('.lista-itens');

                    if(itens.length > 0){
                        clearTimeout(that.time);
                        $(itens[0]).focus();
                    }

                    that.listaFocus = 1;                 
                }
            },
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
            time:null,
            blur:function(){
                var that = this;               
            },
            deletarItem : function(key){
                this.itens.splice(key, 1);
                this.exec();
            },
            setFoco: function(){
                $(this.class).find('input').focus();
            },
            addEmail: function(contato){
                if(validacaoEmail(contato.EMAIL)){
                    var validar = true;
                        angular.forEach(this.itens, function(iten, key) { 
                            if(iten == contato.EMAIL){
                                validar = false;
                            }
                        });

                        if(validar == true){
                            if(this.unico == false || this.itens.length == 0){
                                this.itens.push(contato.EMAIL.toLowerCase());
                                this.valor = '';
                                this.setFoco();
                                this.exec();
                            }else{
                                showErro('Deve conter apenas um endereço de e-mail');    
                            }
                        }else{
                            showErro('Endereço de e-mail já na lista');
                            this.valor = '';
                            this.setFoco();
                            this.exec();  
                        }
                }else{
                    showErro('Endereço de e-mail inválido');
                    this.valor = '';
                    this.setFoco();
                    this.exec();
                }
            },
        }

        function tratarRet(itens){
            var ret = '';
            angular.forEach(itens, function(iten, key) { 
                if(key == 0){
                    ret = iten;
                }else{
                    ret = ret + ', '+ iten;
                }
            });

            return ret;
        }

        vm.EmailContato1 = angular.copy(vm.EmailContato); vm.EmailContato1.class = '.EmailContato1'; vm.EmailContato1.unico = true;
        vm.EmailContato1.exec = function(){
            vm.Arquivos.de = tratarRet(vm.EmailContato1.itens);
        }

        vm.EmailContato2 = angular.copy(vm.EmailContato); vm.EmailContato2.class = '.EmailContato2';
        vm.EmailContato2.exec = function(){
            vm.Arquivos.para = tratarRet(vm.EmailContato2.itens);
        }

        vm.EmailContato3 = angular.copy(vm.EmailContato); vm.EmailContato3.class = '.EmailContato3';
        vm.EmailContato3.exec = function(){
            vm.Arquivos.cc = tratarRet(vm.EmailContato3.itens);
        }

        vm.EmailContato4 = angular.copy(vm.EmailContato); vm.EmailContato4.class = '.EmailContato4';
        vm.EmailContato4.exec = function(){
            vm.Arquivos.cco = tratarRet(vm.EmailContato4.itens);
        }

	}   
    
//# sourceMappingURL=_11150.app.js.map
