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
	])
;
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
      
angular
    .module('app')
    .factory('Index', Index);
    

	Index.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        'gScope'
    ];

function Index($ajax, $httpParamSerializer, $rootScope, gScope) {

    /**
     * Constructor, with class name
     */
    function Index(data) {
        if (data) {
            this.setData(data);
        }
    }
    
    var url_base  = '/_11140/';

    /**
     * Public method, assigned to prototype
     */
    Index.prototype = {
		PAINEIS : [],
        setData: function(data) {
            angular.extend(this, data);
        },        
        consultar : function (args) {

        },
        uriHistory : function() {
            window.history.replaceState('', '', encodeURI(urlhost + '/_11140?'+$httpParamSerializer(this)));
        },
		init: function (){
			var ds = {
				STATUS : 1
			};
			
			var that = this;

			$ajax.post('/_11140/Consultar',ds)
				.then(function(response) {
					that.PAINEIS = response;					
				}
			);
		},
        openLink:function(id){
            window.location.href = urlhost + url_base +  id;
        }
    };

    /**
     * Return the constructor function
     */
    return Index;
};
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
        'Index',
        '$consulta',
        '$httpParamSerializer'
    ];

	function Ctrl($ajax, $scope, $window, $timeout, gScope, Create, Index, $consulta,$httpParamSerializer) {

		var vm = this;
		vm.DADOS = [];
		
        var pagina = window.location.pathname;
        if(pagina == '/_11140'){
            
            vm.Index         = new Index();
            gScope.Index     = vm.Index;
            vm.DADOS.PAINEIS = [];
            vm.Index.init();

        }else{

            vm.Create        = new Create();
            gScope.Create    = vm.Create;

            $scope.$watch('vm.Create.Input.TIPO', function (newValue, oldValue, scope) {
                if ( newValue != oldValue) {
                    vm.Create.validarInfo(newValue);
                } 
            });

            /*
            vm.Consulta     = new $consulta();
            gScope.Consulta = vm.Consulta;

            vm.Consulta_GP1 = vm.Consulta.getNew();
            vm.Consulta_GP2 = vm.Consulta.getNew();
            vm.Consulta_GP3 = vm.Consulta.getNew();

            vm.Consulta_GP1.componente             = '.consulta_angularjs1',
            vm.Consulta_GP1.model                  = 'vm.Consulta_GP1',
            vm.Consulta_GP1.option.label_descricao = 'GP1:',
            vm.Consulta_GP1.option.obj_consulta    = '/_11140/Consultar',
            vm.Consulta_GP1.option.tamanho_Input   = 'input-medio';
            vm.Consulta_GP1.option.class           = 'consulta_gp_grup';
            vm.Consulta_GP3.option.tamanho_tabela  = 250;

            vm.Consulta_GP2.componente             = '.consulta_angularjs2',
            vm.Consulta_GP2.model                  = 'vm.Consulta_GP2',
            vm.Consulta_GP2.option.label_descricao = 'GP2:',
            vm.Consulta_GP2.option.obj_consulta    = '/_11140/Consultar',
            vm.Consulta_GP2.option.tamanho_Input   = 'input-medio';
            vm.Consulta_GP2.option.class           = 'consulta_gp_grup2';
            vm.Consulta_GP3.option.tamanho_tabela  = 250;

            vm.Consulta_GP3.componente             = '.consulta_angularjs3',
            vm.Consulta_GP3.model                  = 'vm.Consulta_GP3',
            vm.Consulta_GP3.option.label_descricao = 'GP3:',
            vm.Consulta_GP3.option.obj_consulta    = '/_11140/getClientes',
            vm.Consulta_GP3.option.tamanho_Input   = 'input-medio';
            vm.Consulta_GP3.option.class           = 'consulta_gp_grup3';
            vm.Consulta_GP3.option.tamanho_tabela  = 480;
            vm.Consulta_GP3.option.campos_tabela   = [['ID','ID'],['DESCRICAO','DESCRIÇÃO'],['STATUS','STATUS']],

            vm.Consulta_GP1.compile();
            vm.Consulta_GP2.compile();
            vm.Consulta_GP3.compile();

            vm.Consulta_GP2.require  = vm.Consulta_GP1;
            vm.Consulta_GP3.require  = [vm.Consulta_GP1,vm.Consulta_GP2];
            vm.Consulta_GP2.vincular();
            vm.Consulta_GP3.vincular();

            var arr = vm.Consulta.getHistory();

            //vm.Consulta_GP3.option.filtro_sql = {GP1: vm.Consulta_GP2.item};

            vm.Consulta_GP3.onSelect = function(){
                var put = {TESTE:'teste'};
                vm.Consulta.postHistory(put,'/_11140/create');
            };

            vm.Consulta_GP3.onClear = function(){
                vm.Consulta.clearHistory('/_11140/create');
            };

            vm.Consulta_GP3.validarInput = function(){
                var ret = true;
                if(vm.Consulta_GP1.selected == null){
                    //showSuccess('Selecione o GP1 e GP2');
                    ret = false;    
                }
                return ret;
            }
            */


        }
	}   
    
//# sourceMappingURL=_11140.app.js.map
