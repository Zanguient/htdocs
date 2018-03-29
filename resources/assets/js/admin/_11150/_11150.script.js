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