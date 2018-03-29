/**
 * _11190 - Notificacao
 */

;(function(angular) {

    var Ctrl = function($scope,$ajax,$timeout, $sce) {

        var vm    = this;
        vm.USERS  = {};
        vm.filtro = '';
        vm.ordem  = 'NOME';
        vm.ordem2 = '-ID';
        vm.user_marcados = [];
        vm.lista  = [];
        vm.lista2 = [];
        vm.NOTIFICACAO = {};
        vm.msg    = {
            MENSAGEM : '',
            TITULO   : '',
        };

        vm.PERMICAO = {
            INCLUIR  : 0,
            ALTERAR  : 0,
            EXCLUIR  : 0,
            IMPRIMIR : 0
        };

        $scope.trustAsHtml = function(string) {
            return $sce.trustAsHtml(string);
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

        

        var init = function(){

            vm.PERMICAO.INCLUIR  = $('.menu-incluir' ).val();
            vm.PERMICAO.ALTERAR  = $('.menu-alterar' ).val();
            vm.PERMICAO.EXCLUIR  = $('.menu-excluir' ).val();
            vm.PERMICAO.IMPRIMIR = $('.menu-imprimir').val();

        	var ds = {
					ID : 0
				};

            if(vm.PERMICAO.INCLUIR == 1){
    			$ajax.post('_11190/getUsuarios',ds)
                    .then(function(response) {
                        vm.USERS = response;                   
                    }
                );
            }

            $ajax.post('_11190/getNotificacao',ds)
                .then(function(response) {
                    vm.NOTIFICACAO = response;

                    angular.forEach(vm.NOTIFICACAO, function (iten) {
                      iten.ID = parseFloat(iten.ID);
                    });

                }
            );
                
        };

        vm.Acoes = {
            StatusIten: function(iten){
                if(iten.SELECTED == 1){
                    iten.SELECTED = 0;
                }else{
                    iten.SELECTED = 1;
                }
            },
        	atualizar: function(){
        		init();
        	},
        	TratarOrdem : function(filtro){
                if(vm.ordem == filtro){
                    vm.ordem = '-'+filtro;
                }else{
                    vm.ordem = filtro;
                }
            },
            TratarOrdem2 : function(filtro){
                if(vm.ordem2 == filtro){
                    vm.ordem2 = '-'+filtro;
                }else{
                    vm.ordem2 = filtro;
                }
            },
        	open: function(){
        		//init();
        	},
        	marcarTodos: function(){
        		angular.forEach(vm.lista, function(iten, key) {
                	iten.SELECTED = 1;    
                });
        	},
        	desmarcarTodos: function(){
        		angular.forEach(vm.lista, function(iten, key) {
                	iten.SELECTED = 0;    
                });
        	},
        	ivertMarcar: function(){
        		angular.forEach(vm.lista, function(iten, key) {
        			if(iten.SELECTED == 1){
                		iten.SELECTED = 0;
                	}else{
                		iten.SELECTED = 1;
                	} 
                });
        	},
            enviarNotificacoes: function(metodo, msg){
                var that = this;
                var str  = '<div class="envio-user">';

                vm.msg.MENSAGEM  = CKEDITOR.instances.editorHtml.getData();
                
                vm.user_marcados = [];
                angular.forEach(vm.USERS, function(iten, key) {
                    if(iten.SELECTED == 1){
                        vm.user_marcados.push(iten.ID);
                        str = str + '<b>' + iten.USUARIO + '</b><br>';
                    }
                });

                str = str + '</div>';

                if(vm.user_marcados.length > 0){

                    addConfirme('<h4>Notificação</h4>',
                    ' Deseja realmente enviar '+msg+' para:<br>' +str
                    ,[obtn_sim,obtn_cancelar],
                        [
                            {ret:1,func:function(){
                                var ds = {
                                    USERS : vm.user_marcados,
                                    MSG   : vm.msg
                                };

                                $ajax.post('_11190/'+metodo,ds)
                                    .then(function(response) {
                                        showSuccess(msg+' enviados.');
                                        that.desmarcarTodos();
                                        that.fecharModal();                    
                                    }
                                );
                            }},
                            {ret:2,func:function(){
                                    
                            }}
                        ]     
                    );
                }else{
                    showErro('Selecione ao menos um usuário');
                }
            },
            modal: function(){
                vm.user_marcados = [];
                angular.forEach(vm.USERS, function(iten, key) {
                    if(iten.SELECTED == 1){
                        vm.user_marcados.push(iten.ID);
                    }
                });

                if(vm.user_marcados.length > 0){
                    $('#modal-mensagem').modal();
                    CKEDITOR.instances.editorHtml.setData('');
                    vm.msg.TITULO = '';
                }else{
                    showErro('Selecione ao menos um usuário');
                }
            },
            fecharModal: function(){
                $('#modal-mensagem').modal('hide');    
            }
        };

        init();
        CKEDITOR.replace('editorHtml',ckConfig);

    };

    Ctrl.$inject = [
		'$scope',
		'$ajax',
		'$timeout',
        '$sce'
	];
 
    angular
    .module    ('app' , ['angular.filter','vs-repeat','gc-ajax','gc-form','gc-find','gc-transform'])
    .controller('Ctrl', Ctrl);
        
})(angular);

;(function($){

        

})(jQuery);
//# sourceMappingURL=_11190.js.map
