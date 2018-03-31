
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html id="html" xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf_token" content="{{ csrf_token() }}" />
		<meta name="user_id" content="{{ isset(Auth::user()->CODIGO) ? Auth::user()->CODIGO : 0 }}" />
		<meta name="menu" content="{{ isset($menu) ? $menu : '' }}" />

		<link rel="stylesheet" href="{{ elixir('assets/css/master.css') }}" />@yield('estilo')<title>@yield('titulo') - HD - Sistemas</title> 
        <script>
            var WEBSOCKET_CONSOLE = {{ env('WEBSOCKET_CONSOLE',0) ? '1' : '0' }};
            var WEBSOCKET_SERVER  = '{{ env('WEBSOCKET_SERVER' ,'wss://gc.delfa.com.br/wss/') }}';
        </script>

        <script src="{{ elixir('assets/js/master.js') }}"></script>
		
		<base href="./">
	</head> 

	<body id="fundo-tela"> 
		
		<div class="carregando-pagina">
			<div class="progress">
			  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
				<span class="sr-only">0% Complete</span>
			  </div>
			</div>
		</div>

		@yield('modal')

		<nav class="navbar navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">

					@if (empty(Auth::user()->CLIENTE_ID))
					<button 
						type="button" 
						class="navbar-toggle" 
						aria-controls="navbar" 
						title="Menu Principal (Alt+Z / Pause Break)"
					>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					@endif
                    <style>
                        nav.navbar .navbar-header .navbar-toggle {
                            display: block;
                            float: left;
                            margin-top: 11px;
                            margin-right: 10px;
                            padding: 6px 7px;
                            border: 1px solid rgb(255, 255, 255);
                        }

                        #navbar-acoes-toggle {
                            height: 28px;
                            width: 40px;
                            background: rgba(0, 0, 0, 0);
                            border-color: rgb(255, 255, 255);
                            color: rgb(255, 255, 255);
                            position: initial;
                            margin: 0;
                            float: left;
                            margin-top: 11px;
                            padding: 6px 7px;              
                        }
                        
                        #navbar-acoes-toggle span {
                                top: -1px;
                        }
                        
                        nav.navbar .navbar-right {
                            padding: 0 0 0 10px;                            
                        }
                        
                        @media (min-width: 768px) {
                            #navbar-title {
                                width: calc(100% - 621px);
                            }
                        }
                        
                        @media (max-width: 768px) {
                            #navbar-title {
                                width: calc(100% - 270px);
                            }
                        }
                        
                        @media (max-width: 510px) {
                            #navbar-title {
                                display: none !important;
                            }
                        }
                    </style>
                    <button class="btn btn-primary" id="navbar-acoes-toggle" data-acoes="toggle">
						<span class="glyphicon glyphicon-option-vertical"></span>
					</button>
                    
					<a class="navbar-brand" style="background-size: 45px;" href="{{ url('') }}"><span></span></a>

					<a href="{{ Request::fullUrl() }}" class="btn btn-alpha duplicar-tela duplicar-tela-mobile" data-hotkey="alt+f10" target="_blank" title="{{ Lang::get('master.duplicar-tela-title') }}">
						<span class="glyphicon glyphicon-new-window"></span>
					</a>

					<button type="button" class="btn btn-alpha go-fullscreen go-fullscreen-mobile" gofullscreen="html" data-hotkey="alt+f11" title="{{ Lang::get('master.tela-cheia-title') }}">
						<span class="glyphicon glyphicon-fullscreen"></span>
					</button>

				</div>
                
		        <div id="navbar" class="navbar-collapse">

                    <div
                        id="navbar-title"
                        style="
                            display: table;
                            height: 40px;
                            overflow: hidden;
                            
                            margin: 4px 0;
                            float: left;
                            border-left: 1px solid rgb(255, 255, 255);                            
                        ">
                        <div style="display: table-cell; vertical-align: middle;">
                            <div
                                style="
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                    display: -webkit-box;
                                    -webkit-line-clamp: 2;
                                    -webkit-box-orient: vertical;  
                                    margin-left: 5px;                                       
                                 ">
                                @yield('titulo')
                            </div>
                        </div>
                    </div>
         
					<div class="navbar-right">

						<a href="{{ Request::fullUrl() }}" class="btn btn-alpha duplicar-tela" data-hotkey="alt+f10" target="_blank" title="{{ Lang::get('master.duplicar-tela-title') }}">
							<span class="glyphicon glyphicon-new-window"></span>
						</a>
						<button type="button" class="btn btn-alpha go-fullscreen" gofullscreen="html" data-hotkey="alt+f11" title="{{ Lang::get('master.tela-cheia-title') }}">
							<span class="glyphicon glyphicon-fullscreen"></span>
						</button>
						
						@include('helper.include.view.relogio')

						<div class="sessao" style="margin-top: 0; height: 100%;">	

                            
							@if (Auth::check())
                            
                            <div style="display: table;height: 100%;overflow: hidden;width: 114px; margin-top: 2px;">
                                <div style="display: table-cell; vertical-align: middle; text-align: center; font-size: 12px;">
                                    <div title="Seu id: {{ Auth::user()->CODIGO }}"
                                         style="
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                            display: -webkit-box;
                                            -webkit-line-clamp: 3;
                                            -webkit-box-orient: vertical;                                         
                                            -moz-line-clamp: 3;
                                            -moz-box-orient: vertical;                                         
                                         ">
                                        {{ ucwords(mb_strtolower(Auth::user()->NOME ? Auth::user()->NOME : Auth::user()->USUARIO)) }}
                                    </div>
                                </div>
                            </div>                            
							<input type="hidden" name="_usuario_id" id="usuario-id" value="{{ Auth::user()->CODIGO }}">
							<input type="hidden" id="usuario-descricao" value="{{ Auth::user()->NOME ? Auth::user()->NOME : Auth::user()->USUARIO }}">
							<input type="hidden" id="usuario-antigo" value="{{ Auth::user()->NOME ? Auth::user()->OLD_CODIGO : 0}}">
							<input type="hidden" id="usuario-cliente-id" value="{{ Auth::user()->CLIENTE_ID }}">
							<input type="hidden" id="usuario-representante-id" value="{{ Auth::user()->REPRESENTANTE_ID }}">
                            
								
								@if (Auth::user()->OLD_CODIGO < 1)
                                <a style="top: 15px;" href="{{ url('auth/logout') }}" id="logout" class="btn btn-xs btn-alpha" title="Sair (Alt+S)">
								<span class="glyphicon glyphicon-log-out"></span>Sair</a>
								@else
								<button style="background-color: #FFEB3B;color: black !important;" type="button" id="voltar-User" class="btn btn-xs btn-alpha" title="Sair (Alt+S)">
									<span style="color: black !important;" class="glyphicon glyphicon-log-out"></span>Sair</a>
								</button>
								@endif

							@endif
						</div>
					</div>
		        </div>
	    	</div>
	    </nav>

		<div class="container-fluid">

			<div class="row">

				<div class="main" id="main" ng-app="app">

					<div class="alert-container">
						
						@if (Session::has('flash_message_error')) 
							@php $class_tipo_msg = 'alert-danger';
							@php $msg_alert = Session::get('flash_message_error');
							
						@elseif (Session::has('flash_message_warning')) 
							@php $class_tipo_msg = 'alert-warning';
							@php $msg_alert = Session::get('flash_message_warning');
							
						@else (Session::has('flash_message')) 
							@php $class_tipo_msg = 'alert-success';
							@php $msg_alert = Session::get('flash_message');
							
						@endif
						
						<div class="alert {{ $class_tipo_msg or '' }} alert-principal {{ $msg_alert ? '' : 'esconder' }}">
							<div id="alert-texto" class="texto">
								{{ $msg_alert or '' }}
							</div>
							<div class="botao-alert-container">
								<button type="button" class="btn btn-default btn-clipboard" data-clipboard-response="{{ Lang::get('master.copiado') }}!" data-clipboard-target="#alert-texto">
									<span class="fa fa-copy"></span>
									{{ Lang::get('master.copiar-texto') }}
								</button>
								<button type="button" class="btn btn-default fechar">
									<span class="fa fa-close"></span>
									{{ Lang::get('master.fechar') }}
								</button>
							</div>
						</div>
					</div>
					
					
					
					<button class="btn btn-primary acoes-toggle">
						<span class="glyphicon glyphicon-option-vertical"></span>
					</button>
					<div id="main-app">
					@yield('conteudo')
      
                    @if ( $__env->yieldContent('titulo') != null ) 
                        @php $str = explode(' - ', $__env->yieldContent('titulo') ) 

                        @php $menu = is_numeric( trim($str[0]) ) ? trim($str[0]) : 0
                        
                        @if ( $menu > 0 )
              
                            @php $con = new App\Models\Conexao\_Conexao
                            @php $qry = $con->query('SELECT FIRST 1 S.* FROM TBSVN S, TBSVN_FILE F WHERE S.REVISION = F.REVISION AND (UPPER("FILE") CONTAINING UPPER(\'' . $menu . '\')) ORDER BY S.REVISION DESC')
                            
                            @if ( isset($qry[0]) )
                                @php $qry = $qry[0]

                                <div 
                                    class="alert alert-warning version-of-system"
                                    style="
                                        position: fixed;
                                        right: 4px;
                                        bottom: -15px;
                                        z-index: 99999;
                                        background-color: rgb(234, 234, 234);
                                        border-color: rgb(0, 0, 0);
                                        color: rgb(0, 0, 0);
                                        padding: 1px 6px 0 6px;
                                        font-size: 10px;
                                        cursor: default;
                                    "
                                    > Vs.{{ date('Y.n.j-H:i',strtotime($qry->DATE)) }} / {{ $qry->REVISION }}</div>
                            @endif
                        @endif
                    @endif
                    
                    </div>
					
				</div>
				

				<!--<footer></footer>-->
			</div> 
			
            <div class="idNotificacao" id="idNotificacao" ng-app="appNotificacao">
                <div class="notificacoes-container" ng-controller="CrtNotificacao as vm" ng-cloak ng-init="vm.init()"> 
                    <div class="contagem-regresiva" ng-if="vm.contador.visivel == true">
                        <div class="painel-contagem-regresiva">
                            <div ng-click="vm.contador.fechar()" class="fechar-contador" title="@{{vm.contador.msg2}}">X</div>
                            <div class="mensagem">@{{vm.contador.msg1}}</div>	
                            <div class="numero">@{{vm.acaoContador.tempo}}</div>
                            <div class="mensagem">segundos</div>	
                        </div>
                    </div>
                </div>
            </div>
		</div>

		@include('menu.menu')

	    
	    <input  type="hidden" class="iniciado-pelo-app" value="0">
	    <button type="button" class="ativar-post-print" value="0" style="display: none"></button>
	    <input  type="hidden" class="_socket_token"     value="0" name="_socket_token">
	    <input  type="hidden" class="user_nome"         value="{{ isset(Auth::user()->CODIGO) ? Auth::user()->NOME : 0 }}" />

	    
	    <div class="stat-zoom" style="display:none">
			<div class="stat-zoom2 glyphicon glyphicon-zoom-in">
				100
			</div>
		</div>
	    

	    <div class="popup">
			@yield('popup-form-start')
			
			<div class="modal-header popup-header">
				
				<button type="button" class="btn btn-primary popup-acoes-toggle">
					<span class="glyphicon glyphicon-option-vertical"></span>
				</button>
				
				<ul class="popup-acoes">
					
					@yield('popup-head-button')
					
					<li class="li-popup-right">
						<button type="button" class="btn btn-default btn-voltar popup-close" data-hotkey="f11" data-dismiss="modal" aria-label="Close">
							<span class="glyphicon glyphicon-chevron-left"></span>
							{{ Lang::get('master.voltar') }}
						</button>
					</li>
					
				</ul>
				
				<div class="popup-title">
					
					@yield('popup-head-title')
					
				</div>
				
			</div>
			<div class="modal-body popup-body">
				
				@yield('popup-body')
				
			</div>
			
			@yield('popup-form-end')
			
		</div>
		

        <div class="baloes"></div>

        <script src="{{ elixir('assets/js/menu.js') }}"></script>
    	<script src="{{ elixir('assets/js/app.notificacoes.js') }}"></script>
	    
	    @yield('script') 		
		@yield('script2')

        <script>
            Clock.DATETIME_FERER = '{{ date("Y-m-d H:i:s") }}';
        </script>
	</body> 

</html>