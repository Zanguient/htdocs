<!DOCTYPE html> 
<html> 
<head> 
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
 	<meta name="csrf_token" content="{{ csrf_token() }}" />
	<!--<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">-->
	<link rel="stylesheet" href="{{ elixir('assets/css/auth.css') }}">
	<title>Delfa - GC</title> 
</head> 
<body>

	<div class="alert alert-success esconder">
		<button type="button" class="close"><i class="fa fa-close"></i></button>
		<span class="texto"></span>
	</div>
	
	<div class="container">

		<div class="registrar col-md-8 col-md-offset-2">

			<span id="gc">GC</span>
			
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					
					<div class="panel panel-default">
						<div class="panel-heading">Registre sua senha no sistema</div>
						<div class="panel-body">
					
							<form method="POST" action="/primeiroAcesso" url-redirect="/home" class="form-horizontal js-gravar">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								
								<div class="form-group">
									<label class="control-label">Login</label>
									<input 
										type="text" 
										name="login"
										id="login" 
										class="form-control normal-case" 
										placeholder="Usuário, e-mail ou CNPJ"  
										autofocus 
										autocomplete="off"
										maxlength="50" 
										required
									/>
								</div>
								
								<div class="form-group">
									<label class="control-label">Senha</label>
									<input type="password" class="form-control" name="password" id="password" required />
								</div>

								<div class="form-group">
									<label class="control-label">Confirmar Senha</label>
									<input type="password" class="form-control" name="password_confirmation" id="password-confirmation" required />
								</div>

								<div class="form-group">
									<button type="submit" class="btn btn-alpha js-gravar" data-loading-text="{{ Lang::get('master.gravando') }}"> Confirmar </button>
									<a href="/resetarSenha" class="esqueceu">Alterar senha?</a>
									<a href="/password/email" class="esqueceu">Esqueceu sua senha?</a>
								</div>
								
							</form>
						</div>
					</div>
				</div>
			</div>

		</div>
		
	</div>
	
	<script src="{{ elixir('assets/js/reset.js') }}"></script>
	
</body>
</html>