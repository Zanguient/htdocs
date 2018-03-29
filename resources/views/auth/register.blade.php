<!DOCTYPE html> 
<html> 
<head> 
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
 	<meta name="csrf_token" content="{{ csrf_token() }}" />
	<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
	<link rel="stylesheet" href="{{ elixir('assets/css/auth.css') }}">
	<title>Delfa - GC</title> 
</head> 
<body>

	<div class="container registrar">
		<span id="gc">GC</span>
		
		<div class="row">
        
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-default">
					<div class="panel-heading">Registre-se no sistema</div>
					<div class="panel-body">
					
						@if (count($errors) > 0)
					    <div class="alert alert-danger">
					        <strong>Erro</strong> <br />
					        <ul>
					            @foreach ($errors->all() as $error)
					            <li>{{ $error }}</li>
					            @endforeach
					        </ul>
					    </div>
						@endif
	
						<form class="form-horizontal" role="form" method="POST" action="/auth/register">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
		
							{{-- 
							<div class="form-group">
								<label class="control-label">Codigo</label>
								<input type="text" class="form-control" name="codigo" value="{{ old('codigo') }}" />
							</div>
							--}}
							
							<div class="form-group">
								<label class="control-label">Nome</label>
								<input type="text" class="form-control" name="name" value="{{ old('name') }}" />
							</div>
							
							<div class="form-group">
								<label class="control-label">Usuário</label>
								<input type="text" class="form-control" name="usuario" value="{{ old('usuario') }}" />
							</div>
							
							<div class="form-group">
								<label class="control-label">E-mail</label>
								<input type="email" class="form-control" name="email" value="{{ old('email') }}" />
							</div>
		
							<div class="form-group">
								<label class="control-label">Senha</label>
								<input type="password" class="form-control" name="password" />
							</div>
							
							<div class="form-group">
								<label class="control-label">Confirmar senha</label>
								<input type="password" class="form-control" name="password_confirmation" />
							</div>
		
							<div class="form-group">
								<button type="submit" class="btn btn-alpha"> Registrar </button>
							</div>
							
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</body>
</html>