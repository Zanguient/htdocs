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
        '$httpParamSerializer',
        '$rootScope',
        '$compile'
    ];

	function Ctrl($ajax, $scope, $window, $timeout, gScope,$httpParamSerializer,$rootScope,$compile) {

		var vm = this;
		vm.DADOS = {};

        vm.DADOS_DEFEITO = [];

        vm.DADOS.DEFEITO = {
            DADOS    : [],
            MODELO   : [],
            LINHA    : [],
            PERFIL   : [],
            DEFEITO  : [],
            COR      : [],
            GP       : [],
            DENSIDADE: [],
            ESPESSURA: [],
            TAMANHO  : [],
            DEFEITO_SETOR: [],
            TOTAL    : {
                DESCRICAO  : 'TOTAL',
                QTD_TURNO1 : 0,
                QTD_TURNO2 : 0,
                QUANTIDADE : 0,
                PRODUCAO   : 0,
                PQTD_TURNO1: 0,
                PQTD_TURNO2: 0
            },
            FILTRO   :[]
        };

        vm.Acao = {
            AddFilter : function(v_tipo,v_valor,v_descricao){
                var validar = true;
                angular.forEach(vm.DADOS.DEFEITO.FILTRO,function(i, v){
                    if(i.TIPO == v_tipo && i.DESC == v_descricao && i.VAL == v_valor){
                        validar = false;  
                    }
                }); 

                if(validar){
                    var item = {
                        TIPO : v_tipo,
                        DESC : v_descricao,
                        VAL  : v_valor
                    };

                    vm.DADOS.DEFEITO.FILTRO.push(item);

                    console.log(vm.DADOS.DEFEITO.FILTRO);
                }

                $('.js-filter-btn').removeClass('btn-success');
                $('.js-filter-btn').addClass('btn-primary');
            },
            validarFiltro:function(iten,flag){
                var validar_linha     = 0, contador_linha     = 0;
                var validar_modelo    = 0, contador_modelo    = 0;
                var validar_perfil    = 0, contador_perfil    = 0;
                var validar_defeito   = 0, contador_defeito   = 0;
                var validar_cor       = 0, contador_cor       = 0;
                var validar_gp        = 0, contador_gp        = 0;
                var validar_densidade = 0, contador_densidade = 0;
                var validar_tamanho   = 0, contador_tamanho   = 0;
                var validar_espessura = 0, contador_espessura = 0;
                var validar_defeito_setor = 0, contador_defeito_setor = 0;

                var ret = false;

                if(vm.DADOS.DEFEITO.FILTRO.length > 0){

                    angular.forEach(vm.DADOS.DEFEITO.FILTRO,function(i, v){
                        if(i.TIPO == 'MODELO'){
                            contador_modelo++;
                            if(iten.MODELO_ID == i.VAL){
                                validar_modelo++;     
                            }
                        }

                        if(i.TIPO == 'LINHA'){
                            contador_linha++;
                            if(iten.LINHA_ID == i.VAL){
                                validar_linha++;     
                            }
                        }

                        if(i.TIPO == 'PERFIL'){
                            contador_perfil++;
                            var a = iten.PERFIL_ID.trim();
                            var b = i.VAL.trim();
                            if( a == b){
                                validar_perfil++;     
                            }
                        }

                        if(i.TIPO == 'DEFEITO'){
                            contador_defeito++;
                            if(iten.DEFEITO_ID == i.VAL){
                                validar_defeito++;     
                            }
                        }

                        if(i.TIPO == 'COR'){
                            contador_cor++;
                            if(iten.COR_ID == i.VAL){
                                validar_cor++;     
                            }
                        }

                        if(i.TIPO == 'GP'){
                            contador_gp++;
                            if(iten.GP_ID == i.VAL){
                                validar_gp++;     
                            }
                        }

                        if(i.TIPO == 'DENSIDADE'){
                            contador_densidade++;
                            if(iten.DENSIDADE == i.VAL){
                                validar_densidade++;     
                            }
                        }

                        if(i.TIPO == 'TAMANHO'){
                            contador_tamanho++;
                            if(iten.TAMANHO == i.VAL){
                                validar_tamanho++;     
                            }
                        }

                        if(i.TIPO == 'ESPESSURA'){
                            contador_espessura++;
                            if(iten.ESPESSURA == i.VAL){
                                validar_espessura++;     
                            }
                        }

                        if(i.TIPO == 'DEFEITO_SETOR'){
                            contador_defeito_setor++;
                            if(iten.DEFEITO_SETOR == i.VAL){
                                validar_defeito_setor++;     
                            }
                        }
                    });

                    if(contador_defeito > 0){
                        if(validar_defeito > 0){
                            validar_defeito = 1;
                        }else{
                            validar_defeito = 0;
                        }
                    }else{
                        validar_defeito = 1;
                    }

                    if(contador_modelo > 0){
                        if(validar_modelo > 0){
                            validar_modelo = 1;
                        }else{
                            validar_modelo = 0;
                        }
                    }else{
                        validar_modelo = 1;
                    }

                    if(contador_linha > 0){
                        if(validar_linha > 0){
                            validar_linha = 1;
                        }else{
                            validar_linha = 0;
                        }
                    }else{
                        validar_linha = 1;
                    }

                    if(contador_perfil > 0){
                        if(validar_perfil > 0){
                            validar_perfil = 1;
                        }else{
                            validar_perfil = 0;
                        }
                    }else{
                        validar_perfil = 1;
                    }

                    if(contador_cor > 0){
                        if(validar_cor > 0){
                            validar_cor = 1;
                        }else{
                            validar_cor = 0;
                        }
                    }else{
                        validar_cor = 1;
                    }

                    if(contador_gp > 0){
                        if(validar_gp > 0){
                            validar_gp = 1;
                        }else{
                            validar_gp = 0;
                        }
                    }else{
                        validar_gp = 1;
                    }

                    if(contador_densidade > 0){
                        if(validar_densidade > 0){
                            validar_densidade = 1;
                        }else{
                            validar_densidade = 0;
                        }
                    }else{
                        validar_densidade = 1;
                    }

                    if(contador_tamanho > 0){
                        if(validar_tamanho > 0){
                            validar_tamanho = 1;
                        }else{
                            validar_tamanho = 0;
                        }
                    }else{
                        validar_tamanho = 1;
                    }

                    if(contador_espessura > 0){
                        if(validar_espessura > 0){
                            validar_espessura = 1;
                        }else{
                            validar_espessura = 0;
                        }
                    }else{
                        validar_espessura= 1;
                    }

                    if(contador_defeito_setor > 0){
                        if(validar_defeito_setor > 0){
                            validar_defeito_setor = 1;
                        }else{
                            validar_defeito_setor = 0;
                        }
                    }else{
                        validar_defeito_setor= 1;
                    }

                    if(flag == 0){
                        if(
                            validar_linha     == 1 &&
                            validar_modelo    == 1 &&
                            validar_perfil    == 1 &&
                            validar_defeito   == 1 &&
                            validar_cor       == 1 &&
                            validar_gp        == 1 &&
                            validar_densidade == 1 &&
                            validar_tamanho   == 1 &&
                            validar_espessura == 1 &&
                            validar_defeito_setor == 1
                        ){
                            ret = true;
                        }
                    }else{
                        if(
                            validar_linha     == 1 &&
                            validar_modelo    == 1 &&
                            validar_perfil    == 1 &&
                            validar_cor       == 1 &&
                            validar_gp        == 1 &&
                            validar_densidade == 1 &&
                            validar_tamanho   == 1 &&
                            validar_espessura == 1 &&
                            validar_defeito_setor == 1
                        ){
                            ret = true;
                        }   
                    }

                    

                } else {
                    ret = true;
                }

                return ret;
                
            },
            DeletarFiltro : function(iten){

                $('.js-filter-btn').removeClass('btn-success');
                $('.js-filter-btn').addClass('btn-primary');

                angular.forEach(vm.DADOS.DEFEITO.FILTRO,function(i, v){
                    if(i.TIPO == iten.TIPO && i.DESC == iten.DESC && i.VAL == iten.VAL){
                        vm.DADOS.DEFEITO.FILTRO.splice( v, 1);   
                    }
                });

                if(vm.DADOS.DEFEITO.FILTRO.length == 0){
                    this.tratar(vm.DADOS_DEFEITO);    
                } 

            },
            filtrar:function(){
                this.tratar(vm.DADOS_DEFEITO);
            },
            tratar : function(dados){

                that = this;

                vm.DADOS_DEFEITO = dados;

                vm.DADOS.DEFEITO.MODELO   = [{KEY : -1}];
                vm.DADOS.DEFEITO.LINHA    = [{KEY : -1}];
                vm.DADOS.DEFEITO.PERFIL   = [{KEY : -1}];
                vm.DADOS.DEFEITO.DEFEITO  = [{KEY : -1}];
                vm.DADOS.DEFEITO.COR      = [{KEY : -1}];
                vm.DADOS.DEFEITO.GP       = [{KEY : -1}];
                vm.DADOS.DEFEITO.DENSIDADE= [{KEY : -1}];
                vm.DADOS.DEFEITO.TAMANHO  = [{KEY : -1}];
                vm.DADOS.DEFEITO.ESPESSURA= [{KEY : -1}];
                vm.DADOS.DEFEITO.DEFEITO_SETOR= [{KEY : -1}];
                vm.DADOS.DEFEITO.TOTAL    = {
                        DESCRICAO  : 'TOTAL',
                        QTD_TURNO1 : 0,
                        QTD_TURNO2 : 0,
                        QUANTIDADE : 0,
                        PRODUCAO   : 0,
                        PQTD_TURNO1: 0,
                        PQTD_TURNO2: 0
                    };

                angular.forEach(dados.defeito,function(iten, key) {

                    var validar = that.validarFiltro(iten,0);

                    if(validar){
                        var index_linha     = 0;
                        var index_modelo    = 0;
                        var index_cor       = 0;
                        var index_defeito   = 0;
                        var index_gp        = 0;
                        var index_perfil    = 0;
                        var index_densidade = 0;
                        var index_tamanho   = 0;
                        var index_espessura = 0;
                        var index_defeito_setor = 0;

                        angular.forEach(vm.DADOS.DEFEITO.LINHA,function(i, v){
                            var id1 = (i.KEY + '').trim();
                            var id2 = (iten.LINHA_ID + '').trim();

                            if(id1 == id2){
                                index_linha = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.MODELO,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.MODELO_ID + '').trim()

                            if(id1 == id2){
                                index_modelo = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.COR,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.COR_ID + '').trim()

                            if(id1 == id2){
                                index_cor = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.DEFEITO,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.DEFEITO_ID + '').trim()

                            if(id1 == id2){
                                index_defeito = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.GP,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.GP_ID + '').trim()

                            if(id1 == id2){
                                index_gp = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.PERFIL,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.PERFIL_ID + '').trim()

                            if(id1 == id2){
                                index_perfil = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.DENSIDADE,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.DENSIDADE + '').trim()

                            if(id1 == id2){
                                index_densidade = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.TAMANHO,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.TAMANHO + '').trim()

                            if(id1 == id2){
                                index_tamanho = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.ESPESSURA,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.ESPESSURA + '').trim()

                            if(id1 == id2){
                                index_espessura = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.DEFEITO_SETOR,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.DEFEITO_SETOR + '').trim()

                            if(id1 == id2){
                                index_defeito_setor = v;    
                            }
                        });

                        if(index_linha == 0){
                            vm.DADOS.DEFEITO.LINHA.push({
                                DESCRICAO  : iten.LINHA,
                                KEY        : (iten.LINHA_ID + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_modelo == 0){
                            vm.DADOS.DEFEITO.MODELO.push({
                                DESCRICAO  : iten.MODELO,
                                KEY        : (iten.MODELO_ID + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_cor == 0){
                            vm.DADOS.DEFEITO.COR.push({
                                DESCRICAO  : iten.COR,
                                KEY        : (iten.COR_ID + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_defeito == 0){
                            vm.DADOS.DEFEITO.DEFEITO.push({
                                DESCRICAO  : iten.DEFEITO,
                                KEY        : (iten.DEFEITO_ID + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_gp == 0){
                            vm.DADOS.DEFEITO.GP.push({
                                DESCRICAO  : iten.GP,
                                KEY        : (iten.GP_ID + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_perfil == 0){
                            vm.DADOS.DEFEITO.PERFIL.push({
                                DESCRICAO  : iten.PERFIL,
                                KEY        : (iten.PERFIL_ID + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_densidade == 0){
                            vm.DADOS.DEFEITO.DENSIDADE.push({
                                DESCRICAO  : iten.DENSIDADE,
                                KEY        : (iten.DENSIDADE + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_tamanho == 0){
                            vm.DADOS.DEFEITO.TAMANHO.push({
                                DESCRICAO  : iten.TAMANHO,
                                KEY        : (iten.TAMANHO + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_espessura == 0){
                            vm.DADOS.DEFEITO.ESPESSURA.push({
                                DESCRICAO  : iten.ESPESSURA,
                                KEY        : (iten.ESPESSURA + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_defeito_setor == 0){
                            vm.DADOS.DEFEITO.DEFEITO_SETOR.push({
                                DESCRICAO  : iten.DEFEITO_SETOR,
                                KEY        : (iten.DEFEITO_SETOR + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_linha     == 0){index_linha      = vm.DADOS.DEFEITO.LINHA.length     -1;}
                        if(index_modelo    == 0){index_modelo     = vm.DADOS.DEFEITO.MODELO.length    -1;}
                        if(index_cor       == 0){index_cor        = vm.DADOS.DEFEITO.COR.length       -1;}
                        if(index_defeito   == 0){index_defeito    = vm.DADOS.DEFEITO.DEFEITO.length   -1;}
                        if(index_gp        == 0){index_gp         = vm.DADOS.DEFEITO.GP.length        -1;}
                        if(index_perfil    == 0){index_perfil     = vm.DADOS.DEFEITO.PERFIL.length    -1;}
                        if(index_densidade == 0){index_densidade  = vm.DADOS.DEFEITO.DENSIDADE.length -1;}
                        if(index_tamanho   == 0){index_tamanho    = vm.DADOS.DEFEITO.TAMANHO.length   -1;}
                        if(index_espessura == 0){index_espessura  = vm.DADOS.DEFEITO.ESPESSURA.length -1;}
                        if(index_defeito_setor == 0){index_defeito_setor  = vm.DADOS.DEFEITO.DEFEITO_SETOR.length -1;}

                        vm.DADOS.DEFEITO.LINHA[index_linha].QTD_TURNO1        = vm.DADOS.DEFEITO.LINHA[index_linha].QTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.LINHA[index_linha].QTD_TURNO2        = vm.DADOS.DEFEITO.LINHA[index_linha].QTD_TURNO2 + Number(iten.QTD_TURNO2);
                        vm.DADOS.DEFEITO.LINHA[index_linha].QUANTIDADE        = vm.DADOS.DEFEITO.LINHA[index_linha].QUANTIDADE + Number(iten.QUANTIDADE);

                        vm.DADOS.DEFEITO.MODELO[index_modelo].QTD_TURNO1      = vm.DADOS.DEFEITO.MODELO[index_modelo].QTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.MODELO[index_modelo].QTD_TURNO2      = vm.DADOS.DEFEITO.MODELO[index_modelo].QTD_TURNO2 + Number(iten.QTD_TURNO2);
                        vm.DADOS.DEFEITO.MODELO[index_modelo].QUANTIDADE      = vm.DADOS.DEFEITO.MODELO[index_modelo].QUANTIDADE + Number(iten.QUANTIDADE);

                        vm.DADOS.DEFEITO.COR[index_cor].QTD_TURNO1            = vm.DADOS.DEFEITO.COR[index_cor].QTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.COR[index_cor].QTD_TURNO2            = vm.DADOS.DEFEITO.COR[index_cor].QTD_TURNO2 + Number(iten.QTD_TURNO2);
                        vm.DADOS.DEFEITO.COR[index_cor].QUANTIDADE            = vm.DADOS.DEFEITO.COR[index_cor].QUANTIDADE + Number(iten.QUANTIDADE);

                        vm.DADOS.DEFEITO.DEFEITO[index_defeito].QTD_TURNO1    = vm.DADOS.DEFEITO.DEFEITO[index_defeito].QTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.DEFEITO[index_defeito].QTD_TURNO2    = vm.DADOS.DEFEITO.DEFEITO[index_defeito].QTD_TURNO2 + Number(iten.QTD_TURNO2);
                        vm.DADOS.DEFEITO.DEFEITO[index_defeito].QUANTIDADE    = vm.DADOS.DEFEITO.DEFEITO[index_defeito].QUANTIDADE + Number(iten.QUANTIDADE);

                        vm.DADOS.DEFEITO.GP[index_gp].QTD_TURNO1              = vm.DADOS.DEFEITO.GP[index_gp].QTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.GP[index_gp].QTD_TURNO2              = vm.DADOS.DEFEITO.GP[index_gp].QTD_TURNO2 + Number(iten.QTD_TURNO2);
                        vm.DADOS.DEFEITO.GP[index_gp].QUANTIDADE              = vm.DADOS.DEFEITO.GP[index_gp].QUANTIDADE + Number(iten.QUANTIDADE);

                        vm.DADOS.DEFEITO.PERFIL[index_perfil].QTD_TURNO1      = vm.DADOS.DEFEITO.PERFIL[index_perfil].QTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.PERFIL[index_perfil].QTD_TURNO2      = vm.DADOS.DEFEITO.PERFIL[index_perfil].QTD_TURNO2 + Number(iten.QTD_TURNO2);
                        vm.DADOS.DEFEITO.PERFIL[index_perfil].QUANTIDADE      = vm.DADOS.DEFEITO.PERFIL[index_perfil].QUANTIDADE + Number(iten.QUANTIDADE);

                        vm.DADOS.DEFEITO.DENSIDADE[index_densidade].QTD_TURNO1   = vm.DADOS.DEFEITO.DENSIDADE[index_densidade].QTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.DENSIDADE[index_densidade].QTD_TURNO2   = vm.DADOS.DEFEITO.DENSIDADE[index_densidade].QTD_TURNO2 + Number(iten.QTD_TURNO2);
                        vm.DADOS.DEFEITO.DENSIDADE[index_densidade].QUANTIDADE   = vm.DADOS.DEFEITO.DENSIDADE[index_densidade].QUANTIDADE + Number(iten.QUANTIDADE);

                        vm.DADOS.DEFEITO.TAMANHO[index_tamanho].QTD_TURNO1   = vm.DADOS.DEFEITO.TAMANHO[index_tamanho].QTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.TAMANHO[index_tamanho].QTD_TURNO2   = vm.DADOS.DEFEITO.TAMANHO[index_tamanho].QTD_TURNO2 + Number(iten.QTD_TURNO2);
                        vm.DADOS.DEFEITO.TAMANHO[index_tamanho].QUANTIDADE   = vm.DADOS.DEFEITO.TAMANHO[index_tamanho].QUANTIDADE + Number(iten.QUANTIDADE);

                        vm.DADOS.DEFEITO.ESPESSURA[index_espessura].QTD_TURNO1   = vm.DADOS.DEFEITO.ESPESSURA[index_espessura].QTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.ESPESSURA[index_espessura].QTD_TURNO2   = vm.DADOS.DEFEITO.ESPESSURA[index_espessura].QTD_TURNO2 + Number(iten.QTD_TURNO2);
                        vm.DADOS.DEFEITO.ESPESSURA[index_espessura].QUANTIDADE   = vm.DADOS.DEFEITO.ESPESSURA[index_espessura].QUANTIDADE + Number(iten.QUANTIDADE);

                        vm.DADOS.DEFEITO.DEFEITO_SETOR[index_defeito_setor].QTD_TURNO1   = vm.DADOS.DEFEITO.DEFEITO_SETOR[index_defeito_setor].QTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.DEFEITO_SETOR[index_defeito_setor].QTD_TURNO2   = vm.DADOS.DEFEITO.DEFEITO_SETOR[index_defeito_setor].QTD_TURNO2 + Number(iten.QTD_TURNO2);
                        vm.DADOS.DEFEITO.DEFEITO_SETOR[index_defeito_setor].QUANTIDADE   = vm.DADOS.DEFEITO.DEFEITO_SETOR[index_defeito_setor].QUANTIDADE + Number(iten.QUANTIDADE);

                        vm.DADOS.DEFEITO.TOTAL.QTD_TURNO1                       = vm.DADOS.DEFEITO.TOTAL.QTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.TOTAL.QTD_TURNO2                       = vm.DADOS.DEFEITO.TOTAL.QTD_TURNO2 + Number(iten.QTD_TURNO2);
                        vm.DADOS.DEFEITO.TOTAL.QUANTIDADE                       = vm.DADOS.DEFEITO.TOTAL.QUANTIDADE + Number(iten.QUANTIDADE);
                    }
                });

                angular.forEach(dados.producao,function(iten, key) {

                    var validar = that.validarFiltro(iten,1);

                    if(validar){   
                        var index_linha     = 0;
                        var index_modelo    = 0;
                        var index_cor       = 0;
                        var index_defeito   = 0;
                        var index_gp        = 0;
                        var index_perfil    = 0;
                        var index_densidade = 0;
                        var index_tamanho   = 0;
                        var index_espessura = 0;
                        var index_defeito_setor = 0;

                        angular.forEach(vm.DADOS.DEFEITO.LINHA,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.LINHA_ID + '').trim()

                            if(id1 == id2){
                                index_linha = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.MODELO,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.MODELO_ID + '').trim()

                            if(id1 == id2){
                                index_modelo = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.COR,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.COR_ID + '').trim()

                            if(id1 == id2){
                                index_cor = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.DEFEITO,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.DEFEITO_ID + '').trim()

                            if(id1 == id2){
                                index_defeito = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.GP,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.GP_ID + '').trim()

                            if(id1 == id2){
                                index_gp = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.PERFIL,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.PERFIL_ID + '').trim()

                            if(id1 == id2){
                                index_perfil = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.DENSIDADE,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.DENSIDADE + '').trim()

                            if(id1 == id2){
                                index_densidade = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.TAMANHO,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.TAMANHO + '').trim()

                            if(id1 == id2){
                                index_tamanho = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.ESPESSURA,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.ESPESSURA + '').trim()

                            if(id1 == id2){
                                index_espessura = v;    
                            }
                        });

                        angular.forEach(vm.DADOS.DEFEITO.DEFEITO_SETOR,function(i, v){
                            var id1 = (i.KEY + '').trim()
                            var id2 = (iten.DEFEITO_SETOR + '').trim()

                            if(id1 == id2){
                                index_defeito_setor = v;    
                            }
                        });

                        if(index_linha == 0){
                            vm.DADOS.DEFEITO.LINHA.push({
                                DESCRICAO  : iten.LINHA,
                                KEY        : (iten.LINHA_ID + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_modelo == 0){
                            vm.DADOS.DEFEITO.MODELO.push({
                                DESCRICAO  : iten.MODELO,
                                KEY        : (iten.MODELO_ID + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_cor == 0){
                            vm.DADOS.DEFEITO.COR.push({
                                DESCRICAO  : iten.COR,
                                KEY        : (iten.COR_ID + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_defeito == 0){
                            vm.DADOS.DEFEITO.DEFEITO.push({
                                DESCRICAO  : iten.DEFEITO,
                                KEY        : (iten.DEFEITO_ID + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_gp == 0){
                            vm.DADOS.DEFEITO.GP.push({
                                DESCRICAO  : iten.GP,
                                KEY        : (iten.GP_ID + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_perfil == 0){
                            vm.DADOS.DEFEITO.PERFIL.push({
                                DESCRICAO  : iten.PERFIL,
                                KEY        : (iten.PERFIL_ID + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_densidade == 0){
                            vm.DADOS.DEFEITO.DENSIDADE.push({
                                DESCRICAO  : iten.DENSIDADE,
                                KEY        : (iten.DENSIDADE + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_tamanho == 0){
                            vm.DADOS.DEFEITO.TAMANHO.push({
                                DESCRICAO  : iten.TAMANHO,
                                KEY        : (iten.TAMANHO + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_espessura == 0){
                            vm.DADOS.DEFEITO.ESPESSURA.push({
                                DESCRICAO  : iten.ESPESSURA,
                                KEY        : (iten.ESPESSURA + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_defeito_setor == 0){
                            vm.DADOS.DEFEITO.DEFEITO_SETOR.push({
                                DESCRICAO  : iten.DEFEITO_SETOR,
                                KEY        : (iten.DEFEITO_SETOR + '').trim(),
                                QTD_TURNO1 : 0,
                                QTD_TURNO2 : 0,
                                QUANTIDADE : 0,
                                PRODUCAO   : 0,
                                PQTD_TURNO1: 0,
                                PQTD_TURNO2: 0
                            });
                        }

                        if(index_linha     == 0){index_linha      = vm.DADOS.DEFEITO.LINHA.length     -1;}
                        if(index_modelo    == 0){index_modelo     = vm.DADOS.DEFEITO.MODELO.length    -1;}
                        if(index_cor       == 0){index_cor        = vm.DADOS.DEFEITO.COR.length       -1;}
                        if(index_defeito   == 0){index_defeito    = vm.DADOS.DEFEITO.DEFEITO.length   -1;}
                        if(index_gp        == 0){index_gp         = vm.DADOS.DEFEITO.GP.length        -1;}
                        if(index_perfil    == 0){index_perfil     = vm.DADOS.DEFEITO.PERFIL.length    -1;}
                        if(index_densidade == 0){index_densidade  = vm.DADOS.DEFEITO.DENSIDADE.length -1;}
                        if(index_tamanho   == 0){index_tamanho    = vm.DADOS.DEFEITO.TAMANHO.length -1;}
                        if(index_espessura == 0){index_espessura  = vm.DADOS.DEFEITO.ESPESSURA.length -1;}
                        if(index_defeito_setor == 0){index_defeito_setor  = vm.DADOS.DEFEITO.DEFEITO_SETOR.length -1;}

                        vm.DADOS.DEFEITO.LINHA[index_linha].PRODUCAO           = vm.DADOS.DEFEITO.LINHA[index_linha].PRODUCAO + Number(iten.QUANTIDADE);
                        vm.DADOS.DEFEITO.LINHA[index_linha].PQTD_TURNO1        = vm.DADOS.DEFEITO.LINHA[index_linha].PQTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.LINHA[index_linha].PQTD_TURNO2        = vm.DADOS.DEFEITO.LINHA[index_linha].PQTD_TURNO2 + Number(iten.QTD_TURNO2);

                        vm.DADOS.DEFEITO.MODELO[index_modelo].PRODUCAO         = vm.DADOS.DEFEITO.MODELO[index_modelo].PRODUCAO + Number(iten.QUANTIDADE);
                        vm.DADOS.DEFEITO.MODELO[index_modelo].PQTD_TURNO1      = vm.DADOS.DEFEITO.MODELO[index_modelo].PQTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.MODELO[index_modelo].PQTD_TURNO2      = vm.DADOS.DEFEITO.MODELO[index_modelo].PQTD_TURNO2 + Number(iten.QTD_TURNO2);

                        vm.DADOS.DEFEITO.COR[index_cor].PRODUCAO               = vm.DADOS.DEFEITO.COR[index_cor].PRODUCAO + Number(iten.QUANTIDADE);
                        vm.DADOS.DEFEITO.COR[index_cor].PQTD_TURNO1            = vm.DADOS.DEFEITO.COR[index_cor].PQTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.COR[index_cor].PQTD_TURNO2            = vm.DADOS.DEFEITO.COR[index_cor].PQTD_TURNO2 + Number(iten.QTD_TURNO2);

                        vm.DADOS.DEFEITO.DEFEITO[index_defeito].PRODUCAO       = vm.DADOS.DEFEITO.DEFEITO[index_defeito].PRODUCAO + Number(iten.QUANTIDADE);
                        vm.DADOS.DEFEITO.DEFEITO[index_defeito].PQTD_TURNO1    = vm.DADOS.DEFEITO.DEFEITO[index_defeito].PQTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.DEFEITO[index_defeito].PQTD_TURNO2    = vm.DADOS.DEFEITO.DEFEITO[index_defeito].PQTD_TURNO2 + Number(iten.QTD_TURNO2);

                        vm.DADOS.DEFEITO.GP[index_gp].PRODUCAO                 = vm.DADOS.DEFEITO.GP[index_gp].PRODUCAO + Number(iten.QUANTIDADE);
                        vm.DADOS.DEFEITO.GP[index_gp].PQTD_TURNO1              = vm.DADOS.DEFEITO.GP[index_gp].PQTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.GP[index_gp].PQTD_TURNO2              = vm.DADOS.DEFEITO.GP[index_gp].PQTD_TURNO2 + Number(iten.QTD_TURNO2);

                        vm.DADOS.DEFEITO.PERFIL[index_perfil].PRODUCAO         = vm.DADOS.DEFEITO.PERFIL[index_perfil].PRODUCAO + Number(iten.QUANTIDADE);
                        vm.DADOS.DEFEITO.PERFIL[index_perfil].PQTD_TURNO1      = vm.DADOS.DEFEITO.PERFIL[index_perfil].PQTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.PERFIL[index_perfil].PQTD_TURNO2      = vm.DADOS.DEFEITO.PERFIL[index_perfil].PQTD_TURNO2 + Number(iten.QTD_TURNO2);

                        vm.DADOS.DEFEITO.DENSIDADE[index_densidade].PRODUCAO    = vm.DADOS.DEFEITO.DENSIDADE[index_densidade].PRODUCAO + Number(iten.QUANTIDADE);
                        vm.DADOS.DEFEITO.DENSIDADE[index_densidade].PQTD_TURNO1 = vm.DADOS.DEFEITO.DENSIDADE[index_densidade].PQTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.DENSIDADE[index_densidade].PQTD_TURNO2 = vm.DADOS.DEFEITO.DENSIDADE[index_densidade].PQTD_TURNO2 + Number(iten.QTD_TURNO2);

                        vm.DADOS.DEFEITO.TAMANHO[index_tamanho].PRODUCAO    = vm.DADOS.DEFEITO.TAMANHO[index_tamanho].PRODUCAO + Number(iten.QUANTIDADE);
                        vm.DADOS.DEFEITO.TAMANHO[index_tamanho].PQTD_TURNO1 = vm.DADOS.DEFEITO.TAMANHO[index_tamanho].PQTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.TAMANHO[index_tamanho].PQTD_TURNO2 = vm.DADOS.DEFEITO.TAMANHO[index_tamanho].PQTD_TURNO2 + Number(iten.QTD_TURNO2);

                        vm.DADOS.DEFEITO.ESPESSURA[index_espessura].PRODUCAO    = vm.DADOS.DEFEITO.ESPESSURA[index_espessura].PRODUCAO + Number(iten.QUANTIDADE);
                        vm.DADOS.DEFEITO.ESPESSURA[index_espessura].PQTD_TURNO1 = vm.DADOS.DEFEITO.ESPESSURA[index_espessura].PQTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.ESPESSURA[index_espessura].PQTD_TURNO2 = vm.DADOS.DEFEITO.ESPESSURA[index_espessura].PQTD_TURNO2 + Number(iten.QTD_TURNO2);

                        vm.DADOS.DEFEITO.DEFEITO_SETOR[index_defeito_setor].PRODUCAO    = vm.DADOS.DEFEITO.DEFEITO_SETOR[index_defeito_setor].PRODUCAO + Number(iten.QUANTIDADE);
                        vm.DADOS.DEFEITO.DEFEITO_SETOR[index_defeito_setor].PQTD_TURNO1 = vm.DADOS.DEFEITO.DEFEITO_SETOR[index_defeito_setor].PQTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.DEFEITO_SETOR[index_defeito_setor].PQTD_TURNO2 = vm.DADOS.DEFEITO.DEFEITO_SETOR[index_defeito_setor].PQTD_TURNO2 + Number(iten.QTD_TURNO2);

                        vm.DADOS.DEFEITO.TOTAL.PRODUCAO                        = vm.DADOS.DEFEITO.TOTAL.PRODUCAO    + Number(iten.QUANTIDADE);
                        vm.DADOS.DEFEITO.TOTAL.PQTD_TURNO1                     = vm.DADOS.DEFEITO.TOTAL.PQTD_TURNO1 + Number(iten.QTD_TURNO1);
                        vm.DADOS.DEFEITO.TOTAL.PQTD_TURNO2                     = vm.DADOS.DEFEITO.TOTAL.PQTD_TURNO2 + Number(iten.QTD_TURNO2);
                    }
                });
                
                vm.DADOS.DEFEITO.LINHA.splice(0, 1);
                vm.DADOS.DEFEITO.MODELO.splice(0, 1);
                vm.DADOS.DEFEITO.COR.splice(0, 1);
                vm.DADOS.DEFEITO.DEFEITO.splice(0, 1);
                vm.DADOS.DEFEITO.GP.splice(0, 1);
                vm.DADOS.DEFEITO.PERFIL.splice(0, 1);
                vm.DADOS.DEFEITO.DENSIDADE.splice(0, 1);
                vm.DADOS.DEFEITO.TAMANHO.splice(0, 1);
                vm.DADOS.DEFEITO.ESPESSURA.splice(0, 1);
                vm.DADOS.DEFEITO.DEFEITO_SETOR.splice(0, 1);

                $('.js-filter-btn').addClass('btn-success');
                $('.js-filter-btn').removeClass('btn-primary');

                setTimeout(function(){
                    bootstrapInit();
                },500);

            },
            filterDefeito : function(fanilia,data,defeito,producao,flag){

                vm.DADOS.DEFEITO.FILTRO = [];

                var estabelecimento = $('._input_estab').val();
                var periodo_pedidod = $('.checkbox-perildod').val();
                var periodo_pedidop = $('.checkbox-perildop').val();

                var periodo_inicial = $('.data-ini').val();
                var periodo_final   = $('.data-fim').val();

                if(flag == 0){
                    periodo_inicial = data;
                    periodo_final   = data;
                }

                var perfil_grupo    = 0;
                var periodo_pedido  = 0;

                if(periodo_pedidod == 1){
                    periodo_pedido  = 'd';
                }else{
                    if(periodo_pedidop == 1){
                        periodo_pedido = 'p';
                    }
                }

                var filter = {
                    estabelecimento     :estabelecimento,
                    familias            :fanilia,
                    periodo_inicial     :periodo_inicial,
                    periodo_final       :periodo_final,
                    perfil_grupo        :perfil_grupo,
                    periodo_pedido      :periodo_pedido,
                    defeito             :defeito,
                    producao            :producao
                }

                var that = this; 

                $ajax.post('/_12050/defeitoDia', filter)
                    .then(function(response) {

                        that.tratar(response);

                        $('#modal-defeito').modal();
                    }
                );
            },
            Compile:function(){
                var html  = $('.tabela-relatorio').html();

                var obj   = $('.tabela-relatorio');
                var scope = obj.scope(); 
                obj.html(html);
                $compile(obj.contents())(scope);

                console.log(obj);
            }
        }



	}   
    