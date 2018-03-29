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
    