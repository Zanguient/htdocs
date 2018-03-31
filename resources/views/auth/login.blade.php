<!DOCTYPE html> 
<html> 
<head> 
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
 	<meta name="csrf_token" content="{{ csrf_token() }}" />
	<link rel="stylesheet" href="{{ elixir('assets/css/auth.css') }}">
	<title>HD - Sistemas</title> 
</head> 
<body style="
	background-color: #fffff !important;
	background-image: linear-gradient(#fffff, #fffff, #fffff) !important;
    background-attachment: fixed;">

	<div class="container">

		<div class="login col-md-8 col-md-offset-2" style="/* padding-top: 0px; */">
	
			<span id="gc" style="
				background: url(../../../../assets/images/logo.jpg) no-repeat center;
				background-size: 230px;
    			height: 134px;
    			margin-top: -144px;
    			border-radius: 17px;
    			width: 200px;
			">
				
			</span>
			
			<div class="row">
	        
				<div class="col-md-8 col-md-offset-2">

					<div class="panel panel-default">

						<div class="panel-heading">Entrar no sistema</div>

						<div class="panel-body">						
							
						    <div class="alert alert-danger alert-principal <?php if (count($errors) === 0) echo 'esconder'; ?>">
								{{-- @if (count($errors) > 0) --}}
						        <strong>Erro</strong> <br />
						            @foreach ($errors->all() as $error)
									<span>{{ $error }}</span><br />
						            @endforeach
								{{-- @endif --}}
						    </div>

						    <div class="baloes"></div>							
		
							<form class="form-horizontal" class="js-gravar" role="form" method="POST" action="/auth/login">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">  
								
								<div class="form-group">
									<label class="control-label">Login</label>
									<input 
										type="text" 
										name="login" 
										id="login" 
										class="form-control normal-case" 
										placeholder="Usuário" 
										value="{{ old('USUARIO') }}" 
										autofocus="autofocus" 
										autocomplete="off" 
										maxlength="50" 
										required 
									/>
								</div>
								
								<div class="form-group">
									<label class="control-label">Senha</label>
									<input 
										type="password" 
										name="password"
										id="password"
										class="form-control"   
										required 
									/>
								</div>
								
								<input type="hidden" name="status" value="1" />
			
								<div class="form-group">
									<button type="submit" class="btn btn-alpha js-gravar">Login</button>
									<a href="/primeiroAcesso" class="esqueceu">Primeiro acesso?</a>
								</div>
								
								{{-- 
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<div class="checkbox">
											<label>
											<input type="checkbox" name="remember"> Remember Me
											</label>
										</div>
									</div>
								</div>
								--}}
							</form>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    
    <div class="stat-zoom" style="display:none">
		<div class="stat-zoom2 glyphicon glyphicon-zoom-in">
			100
		</div>
	</div>

	<script src="{{ elixir('assets/js/login.js') }}"></script>   
	
</body>
</html>