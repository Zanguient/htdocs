
(function($) {
    
    var xhr = function(param)
    {
        return new Promise(function(resolve, reject) {
            execAjax1('POST',param.url,param.dados,
            function() {
                resolve(true);
            },
            function() {
                reject(false);
            });
        });  
    };

    function initDataTable(tabela,param)
    {
        var param                   = param || {};
        var data_table_def          = $.extend({}, table_default);
            data_table_def.sScrollY = '47vh';

        $('.'+tabela).DataTable(data_table_def);
    }

    function objIndex()
    {
        this.inicializarDataTable = inicializarDataTable;        
        this.filtragem            = filtragem;       
        this.filtrar              = filtrar;       
        
        function inicializarDataTable(param)
        {
            var param                   = param || {};
			var data_table_def			= $.extend({}, table_default);
                data_table_def.sScrollY = '65vh'; 
				
            $('.lista-obj').DataTable(data_table_def);
        }
        
        /**
         * Realiza filtros
         */
        function filtragem()
        {

            var qtd_por_pag   = 30;
            var pagina_atual  = 1;
            var pagina_inc    = 0;
            var final_pag	  = false;

            /**
             * Realiza a coleta dos dados<br/>
             * Realiza a chamada ajax
             */
            function tratarDados(param) {

                var param = param || {};

                if( final_pag ) {
                    pagina_atual = 1;
                    pagina_inc = 0;
                    return false;
                }

                var dados          = {
                    retorno : ['USUARIO'],
                    first   : qtd_por_pag,
                    skip    : pagina_inc
                };   

                var campo_pesquisa = $('#filter-btn-find');
                var campo_status   = $('#filter-status'  );

                var pesquisa = campo_pesquisa.val();
                var status   = campo_status.val();

                if ( pesquisa !== '' ) dados.filtro = pesquisa;
                if ( status   !== '' ) dados.status = status;

                function success(data) {						
                    if(data) {
                        $('table.lista-obj tbody').append(data);
                        
                        var show = new objShow();
                            show.load();
                        
                    } else {
                        final_pag = true;
                    }
                }

                execAjax1('POST','/_11010/index',dados,success,null,null,param.progress);
            }

            /**
             * Realiza a parametrização inicial<br/>
             * Realiza o acionamento da consulta
             */
            function acionarFiltro() {

                var campo_pesquisa = $('#filter-btn-find');
                var btn_filtrar    = $('#filter-btn');
                var scroll_timer   = 0;

                btn_filtrar.click(function(){
                    final_pag	  = false;
                    pagina_atual  = 1;
                    pagina_inc    = 0;
                    $('table.lista-obj tbody').empty();
                    tratarDados();
                });

                campo_pesquisa.keyEnter(function(){
                    final_pag	  = false;
                    pagina_atual  = 1;
                    pagina_inc    = 0;
                    $('table.lista-obj tbody').empty();
                    tratarDados();
                });

                //carregar página com scroll
                $('.dataTables_scrollBody').scroll(function() {

                    if (popUpShowing) return false; 

                    var div = $(this);

                    clearTimeout(scroll_timer);

                    scroll_timer = setTimeout(function() {

                        //final do scroll da tabela
                        if( ( div.scrollTop() + div.height() ) >= div.children('table').height() - 1500 ) {
                            
                            if (requestRunning) return false;

                            pagina_atual += 1;
                            pagina_inc = pagina_atual * qtd_por_pag - qtd_por_pag;                        
                            tratarDados({progress : false});
                        }
                    }, 100);
                });

                //carregar página com clique
                $('.carregar-pagina').click(function() {

                    pagina_atual += 1;
                    pagina_inc = pagina_atual * qtd_por_pag - qtd_por_pag;                                        
                    tratarDados();
                });            
            }

            acionarFiltro();
        }

        
        function filtrar()
        {
            $('#filter-btn').click();
        }
        
    }
    
    function objShow(selector)
    {
        this.load = load;
        var id;
                
        function load()
        {
            /**
             * Realiza a chamada ajax
             */
            function carregarDados() {           

                function success(data) {						
                    if(data) {
                        $('div.modal-body').html(data);
                        selector.popUp();
                        initFiltro();
                        initDataTable('tabela-menu');   
                    }
                }

                execAjax1('GET','/_11010/'+id,null,success);
            }

            function clickItem() {
                $('tr[data-id]')
                    .off('click')
                    .click(function(){
                        selector = $(this);
                        id = selector.data('id');
                        carregarDados();
                    })
                ;
            }
            
            clickItem();
//            acoes();
        }
        
    }
    
    function loadIndex()
    {
        
        var index = new objIndex();
            index.inicializarDataTable();
            index.filtragem();
            index.filtrar();
    }

    /**
     * Document Ready
     */
	$(function() {
        loadIndex();
	});
    
   function getMenusUser(e){
       var user_id = $(e).data('iduser');
       
       var dados = {
         user_id : user_id  
       };
       
       function success(data) {                     
            $('#menu-usuario-container').html(data);
            initFiltro();
            initDataTable('tabela-menu');
        }

        execAjax1('POST','/_11010/getMenusUser',dados,success);
   }

   function getRelatorioUser(e){
       var user_id = $(e).data('iduser');
       
       var dados = {
         user_id : user_id  
       };
       
       function success(data) {                     
            $('#relatorio-container').html(data);
            initFiltro();
            initDataTable('tabela-relatorio');
        }

        execAjax1('POST','/_11010/getRelatorioUser',dados,success);
   }

   function setMenusUser(e){
        var user_id = $(e).data('iduser');

        var itens = [];
        var item  = [];

        $('.item-menu-grupo').each(function( index ) {
            item = [];
            item.push($(this).data('controle'));
            var mudou = 0;

            var tag = $(this).find('.TAGS').val();
            var flg = $(this).find('.FLAG').val();
            item.push(flg);

            if(tag != flg){
               mudou = 1;
            }

            $(this).find('.chec-menu-item-editar').each(function( index ) {
                var tag = $(this).find('.TAGS').val();
                var flg = $(this).find('.FLAG').val();

                if(tag != flg){
                   mudou = 1;
                }

                item.push(flg);
            });

            item.push($(this).data('origem'));

            if(mudou == 1){
                itens.push(item);
            }
        });

        var dados = {
            user_id : user_id,
            menus   : itens
        };
       
       function success(data) {                     
            $('#menu-usuario-container').html(data);
            initFiltro();
            initDataTable('tabela-menu');
        }

        console.log('Teste');
        execAjax1('POST','/_11010/setMenusUser',dados,success);
   }
   
   function getPermicoesUser(e){
       var user_id = $(e).data('iduser');
       
       var dados = {
         user_id : user_id  
       };
       
       function success(data) {						
            $('#parametro-container').html(data);
            initFiltro();
            initDataTable('tabela-parametro');
        }

        execAjax1('POST','/_11010/getPermicoesUser',dados,success);
   }
   
   function getCcustoUser(e){
       var user_id = $(e).data('iduser');
       
       var dados = {
         user_id : user_id  
       };
       
       function success(data) {						
            $('#ccusto-container').html(data);
            initFiltro();
            initDataTable();
        }

        execAjax1('POST','/_11010/getCcustoUser',dados,success);
   }

   
   function getPerfilUser(e){
       var user_id = $(e).data('iduser');
       
       var dados = {
         user_id : user_id  
       };
       
       function success(data) {                     
            $('#perfil-container').html(data);
            initFiltro();
            initDataTable('tabela-perfil');
        }

        execAjax1('POST','/_11010/getPerfilUser',dados,success);
   }

   function setPerfilUser(e){
       var user_id = $(e).data('iduser');
       var perfil  = [];

       $('.chec-perfil-editar').each(function( index ) {
            perfil.push([
                $( this ).data('id'),
                $( this ).data('flag'),
                $( this ).find('.CHEC').val()
            ]);
        });
       
        var dados = {
            user_id : user_id,
            perfil  : perfil 
        };
       
        function success(data) {
            getPerfilUser(e);                  
        }

        execAjax1('POST','/_11010/setPerfilUser',dados,success);
   }

   function setRelatorioUser(e){
       var user_id = $(e).data('iduser');
       var relatorio  = [];

       $('.chec-relatorio-editar').each(function( index ) {
            relatorio.push([
                $( this ).data('id'),
                $( this ).data('flag'),
                $( this ).find('.CHEC').val()
            ]);
        });
       
        var dados = {
            user_id     : user_id,
            relatorio   : relatorio 
        };
       
        function success(data) {
            getRelatorioUser(e);                  
        }

        execAjax1('POST','/_11010/setRelatorioUser',dados,success);
   }
   
   function getEditarPerfil(e){
       var user_id = $(e).data('iduser');
       
       var dados = {
         user_id : user_id  
       };
       
       function success(data) {                     
            $('#perfil-container').html(data);
            initFiltro();
            initDataTable('tabela-perfil');

            showAlert('Marque com um <span class="glyphicon glyphicon-ok" style="color: green;"></span> os itens que deseja adicionar.<br>'
                     //+'* Um clique com o mause marcar e desmarcar.<br> ou backspace marcar e desmarcar.<br> '
                     //+'* backspace marcar e desmarcar.<br> '
                     //+'* Tab para avançar um item.<br> '
                     //+'* Shift + Tab para retroceder um item.<br> '
                     
                   );
        }

        execAjax1('POST','/_11010/getPerfil',dados,success);
   }


   function getEditarMenu(e){
       var user_id = $(e).data('iduser');
       
       var dados = {
         user_id : user_id  
       };
       
       function success(data) {                     
            $('#menu-usuario-container').html(data);
            initFiltro();
            initDataTable('tabela-menu');

            showAlert('Marque com um <span class="glyphicon glyphicon-ok" style="color: green;"></span> os itens que deseja adicionar.<br>'
                     //+'* Um clique com o mause marcar e desmarcar.<br> ou backspace marcar e desmarcar.<br> '
                     //+'* backspace marcar e desmarcar.<br> '
                     //+'* Tab para avançar um item.<br> '
                     //+'* Shift + Tab para retroceder um item.<br> '
                     
                   );
        }

        execAjax1('POST','/_11010/getMenus',dados,success);
   }

   function getEditarRelatorio(e){
       var user_id = $(e).data('iduser');
       
       var dados = {
         user_id : user_id  
       };
       
       function success(data) {
         
            $('#relatorio-container').html(data);
            initFiltro();
            initDataTable('tabela-relatorio');

            showAlert('Marque com um <span class="glyphicon glyphicon-ok" style="color: green;"></span> os itens que deseja adicionar.<br>'
                     //+'* Um clique com o mause marcar e desmarcar.<br> ou backspace marcar e desmarcar.<br> '
                     //+'* backspace marcar e desmarcar.<br> '
                     //+'* Tab para avançar um item.<br> '
                     //+'* Shift + Tab para retroceder um item.<br> '
                     
                   );
        }

        execAjax1('POST','/_11010/getRelatorios',dados,success);
   }
   
   function resetarSenha(e){
       var user_id = $(e).data('iduser');
       
       var dados = {
         user_id : user_id  
       };
       
        function success(data) {                        
            showSuccess('Usuário criado.');
        }

        function erro(data) {                        
            showAlert('Usuário ja existe no banco!');
        }

        execAjax1('POST','/_11010/ResetarPass',dados,success,erro);
   }

   function criarusuariodb(e){
       var USERNAME = $(e).data('username');
       
       var dados = {
         USERNAME : USERNAME  
       };
       
       function success(data) {                     
            showSuccess('Usuário Criado.');
        }

        execAjax1('POST','/_11010/CriarUsuarioDB',dados,success);
   }
    
    $(document).on('click','.resetar-senha-web', function(e) {
        var obj = this;
        event.preventDefault();
        addConfirme('<h4>Resetar senha</h4>',
                ' Deseja realmente resetar a senha deste usuário?',[obtn_sim,obtn_cancelar],
                    [
                        {ret:1,func:function(){
                            resetarSenha(obj);
                        }},
                        {ret:2,func:function(){
                                
                        }}
                    ]     
                );                       
    });

    $(document).on('click','.criar-usuario-db', function(e) {
        var obj = this;
        event.preventDefault();
        addConfirme('<h4>Criar usuário no DB</h4>',
                ' Deseja realmente criar usuário no banco?',[obtn_sim,obtn_cancelar],
                    [
                        {ret:1,func:function(){
                            criarusuariodb(obj);
                        }},
                        {ret:2,func:function(){
                                
                        }}
                    ]     
                );                       
    });

    
    
    $(document).on('click','.btn-incluir', function(e) {
        var obj = this;
        event.preventDefault();
        addConfirme('<h4>Alerta</h4>',
                'Esta função ainda não esta ativa',[obtn_cancelar],
                    [
                        {ret:2,func:function(){
                                
                        }}
                    ]     
                );                       
    });

    $(document).on('click','.btn-alterar', function(e) {
        var obj = this;
        event.preventDefault();
        addConfirme('<h4>Alerta</h4>',
                'Esta função ainda não esta ativa',[obtn_cancelar],
                    [
                        {ret:2,func:function(){
                                
                        }}
                    ]     
                );                       
    });
    
    $(document).on('click','.entrar-como-usuario', function(e) {
        
        var user_id   = $(this).data('iduser');
        var user_name = $(this).data('username');

        event.preventDefault();
        addConfirme('<h4>Alerta</h4>',
            'Entrar no sistema como '+ user_name + '?',[obtn_sim,obtn_cancelar],
                [
                    {ret:1,func:function(){
                           
                        var dados = {
                            CODIGO : user_id,
                            USER   : user_name 
                        };
                       
                        function success(data) {
                            
                            window.localStorage.removeItem('ngStorage-menus');
                            window.location.href = urlhost + '/home';

                        }

                        execAjax1('POST','_11010/loginUser',dados,success);

                    }},
                    {ret:2,func:function(){
                            
                    }}
                ]    
            );                       
    });

    $(document).on('click','.btn-excluir', function(e) {
        var obj = this;
        event.preventDefault();
        addConfirme('<h4>Alerta</h4>',
                'Esta função ainda não esta ativa',[obtn_cancelar],
                    [
                        {ret:2,func:function(){
                                
                        }}
                    ]     
                );                       
    });
   
    function initFiltro(){
        
        $('.imp-filtrar-ccusto').keyup(function(){
            $('.btn-filtrar-ccusto').trigger('click');
        });
        
        $('.imp-filtrar-parametro').keyup(function(){
            $('.btn-filtrar-parametro').trigger('click');
        });
        
        $('.imp-filtrar-menu').keyup(function(){
            $('.btn-filtrar-menu').trigger('click');
        });
        
        $('.imp-filtrar-perfil').keyup(function(){
            $('.btn-filtrar-perfil').trigger('click');
        });

        $('.imp-filtrar-relatorio').keyup(function(){
            $('.btn-filtrar-relatorio').trigger('click');
        });
        
        $(document).on('click', '.btn-filtrar-parametro', function(ev){

            var texto = $('.imp-filtrar-parametro').val();

            $('.tabela-parametro').find("tr").css("display", "table-row");

            $('.tabela-parametro').find("tr").each(function(){
                var textos = ($(this).text()).toUpperCase();
                var filtro = texto.toUpperCase();
                var title  = $(this).data('title');
                
                if(textos.indexOf(filtro) < 0){
                   if(title != 'titulo'){
                        $(this).css("display", "none");
                    }
                }
            });
            
        });
        
        $(document).on('click', '.btn-filtrar-menu', function(ev){

            var texto = $('.imp-filtrar-menu').val();

            $('.tabela-menu').find("tr").css("display", "table-row");

            $('.tabela-menu').find("tr").each(function(){
                var textos = ($(this).text()).toUpperCase();
                var filtro = texto.toUpperCase();
                var title  = $(this).data('title');
                
                if(textos.indexOf(filtro) < 0){
                   if(title != 'titulo'){
                        $(this).css("display", "none");
                    }
                }
            });                      
        });

        $(document).on('click', '.btn-filtrar-ccusto', function(ev){

            var texto = $('.imp-filtrar-ccusto').val();

            $('.tabela-ccusto').find("tr").css("display", "table-row");

            $('.tabela-ccusto').find("tr").each(function(){
                var textos = ($(this).text()).toUpperCase();
                var filtro = texto.toUpperCase();
                var title  = $(this).data('title');
                
                if(textos.indexOf(filtro) < 0){
                   if(title != 'titulo'){
                        $(this).css("display", "none");
                    }
                }
            });                      
        });
        
        $(document).on('click', '.btn-filtrar-perfil', function(ev){

            var texto = $('.imp-filtrar-perfil').val();

            $('.tabela-perfil').find("tr").css("display", "table-row");

            $('.tabela-perfil').find("tr").each(function(){
                var textos = ($(this).text()).toUpperCase();
                var filtro = texto.toUpperCase();
                var title  = $(this).data('title');
                
                if(textos.indexOf(filtro) < 0){
                   if(title != 'titulo'){
                        $(this).css("display", "none");
                    }
                }
            });                      
        });

        $(document).on('click', '.btn-filtrar-relatorio', function(ev){

            var texto = $('.imp-filtrar-relatorio').val();

            $('.tabela-relatorio').find("tr").css("display", "table-row");

            $('.tabela-relatorio').find("tr").each(function(){
                var textos = ($(this).text()).toUpperCase();
                var filtro = texto.toUpperCase();
                var title  = $(this).data('title');
                
                if(textos.indexOf(filtro) < 0){
                   if(title != 'titulo'){
                        $(this).css("display", "none");
                    }
                }
            });                      
        });
    }
    
    $(document).on('click', '.adicionar-perfil', function(){
        event.preventDefault();
        getEditarPerfil(this);
    });

    $(document).on('click', '.adicionar-menu', function(){
        event.preventDefault();
        getEditarMenu(this);
    });

    $(document).on('click', '.adicionar-relatorio', function(){
        event.preventDefault();
        getEditarRelatorio(this);
    });

    $(document).on('click', '.canselar-relatorio', function(){
        event.preventDefault();
        getRelatorioUser(this);
    });
    
    $(document).on('click', '.canselar-perfil', function(){
        event.preventDefault();
        getPerfilUser(this);
    });
    
    $(document).on('click', '.gravar-perfil', function(){
        event.preventDefault();
        setPerfilUser(this);
    });

    $(document).on('click', '.gravar-relatorio', function(){
        event.preventDefault();
        setRelatorioUser(this);
    });

    $(document).on('click', '.canselar-menu', function(){
        event.preventDefault();
        getMenusUser(this);
    });
    
    $(document).on('click', '.gravar-menu', function(){
        event.preventDefault();
        setMenusUser(this);
    });
    
    $(document).on('click', '.chec-perfil-editar', function(){
        event.preventDefault();
        
        var iten  = $(this);
        var flag  = $(iten).find('.FLAG');
        var chec  = $(iten).find('.CHEC');
        var marc  = $(iten).find('.glyphicon');
        var v     = $(flag).val();
        
        if(v == '0'){  
            
            $(marc).removeClass('glyphicon-remove');
            $(marc).addClass('glyphicon-ok');
            $(marc).css('color','green');
            
            $(flag).val('1');
            $(chec).val('1');
            
        }else{
            
            $(marc).removeClass('glyphicon-ok');
            $(marc).addClass('glyphicon-remove');
            $(marc).css('color','red');

            $(flag).val('0');
            $(chec).val('0');
            
        }
   
    });

    $(document).on('click', '.chec-relatorio-editar', function(){
        event.preventDefault();
        
        var iten  = $(this);
        var flag  = $(iten).find('.FLAG');
        var chec  = $(iten).find('.CHEC');
        var marc  = $(iten).find('.glyphicon');
        var v     = $(flag).val();
        
        if(v == '0'){  
            
            $(marc).removeClass('glyphicon-remove');
            $(marc).addClass('glyphicon-ok');
            $(marc).css('color','green');
            
            $(flag).val('1');
            $(chec).val('1');
            
        }else{
            
            $(marc).removeClass('glyphicon-ok');
            $(marc).addClass('glyphicon-remove');
            $(marc).css('color','red');

            $(flag).val('0');
            $(chec).val('0');
            
        }
   
    });

    $(document).on('click', '.chec-menu-editar', function(){
        event.preventDefault();
        
        if(!$(this).hasClass('menu-item-disabled')){
            var iten  = $(this);
            var flag  = $(iten).find('.FLAG');
            var menu  = $(iten).data('id');
            var marc  = $(iten).find('.glyphicon');
            var v     = $(flag).val();

            if(v == '0'){  
                
                $(marc).removeClass('glyphicon-remove');
                $(marc).addClass('glyphicon-ok');
                $(marc).css('color','green');

                $('.Menu' + menu).removeClass('disable-menu-item');
                $('.Menu' + menu).parent().removeClass('menu-item-disabled');

                $(flag).val('1');
                
            }else{
                
                $(marc).removeClass('glyphicon-ok');
                $(marc).addClass('glyphicon-remove');
                $(marc).css('color','red');

                $('.Menu' + menu).addClass('disable-menu-item');
                $('.Menu' + menu).parent().addClass('menu-item-disabled');

                $(flag).val('0');
                
            }
        }
    });

    $(document).on('click', '.chec-menu-item-editar', function(){
        event.preventDefault();
        
        if(!$(this).hasClass('menu-item-disabled')){

            var iten  = $(this);
            var flag  = $(iten).find('.FLAG');
            var marc  = $(iten).find('.glyphicon');
            var v     = $(flag).val();

            if(v == '0'){  
                
                $(marc).removeClass('glyphicon-remove');
                $(marc).addClass('glyphicon-ok');
                $(marc).css('color','green');
                
                $(flag).val('1');
                
            }else{
                
                $(marc).removeClass('glyphicon-ok');
                $(marc).addClass('glyphicon-remove');
                $(marc).css('color','red');

                $(flag).val('0');
                
            }
        }
    });
    
    $(document).on('click', '#menu-usuario-tab', function(){  getMenusUser(this);     });
    $(document).on('click', '#ccusto-tab', function(){        getCcustoUser(this);    });
    $(document).on('click', '#parametro-tab', function(){     getPermicoesUser(this); });
    $(document).on('click', '#perfil-tab', function(){        getPerfilUser(this);    });
    $(document).on('click', '#relatorio-tab', function(){     getRelatorioUser(this); });
        
})(jQuery);


//# sourceMappingURL=_11010.js.map
