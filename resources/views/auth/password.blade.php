<!DOCTYPE html> 
<html> 
<head> 
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
 	<meta name="csrf_token" content="{{ csrf_token() }}" />
	<link rel="stylesheet" href="{{ elixir('assets/css/auth.css') }}">
	<title>Delfa - GC</title> 
</head> 
<body>
	
	<div class="carregando-pagina">
		<div class="progress">
		  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
			<span class="sr-only">0% Complete</span>
		  </div>
		</div>
	</div>

	<div class="alert alert-success esconder">
		<button type="button" class="close"><i class="fa fa-close"></i></button>
		<span class="texto"></span>
	</div>

	<div class="baloes"></div>
	
	<div class="container">

		<div class="registrar col-md-8 col-md-offset-2">

			<span id="gc">GC</span>
			
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					
					<div class="panel panel-default">
						<div class="panel-heading">Recupere sua senha no sistema</div>
						<div class="panel-body">
					
							<form method="POST" action="/password/email" class="form-horizontal">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								
								<div class="form-group">
									<label class="control-label">E-mail</label>
									<input type="email" class="form-control normal-case" name="email" id="email" required autofocus autocomplete="off" />
								</div>

								<div class="form-group">
									<button type="submit" class="btn btn-alpha" data-loading-text="{{ Lang::get('master.gravando') }}"> Confirmar </button>
								</div>
								
							</form>

						</div>
					</div>
				</div>
			</div>

		</div>
		
	</div>
	
	<script src="{{ elixir('assets/js/password.js') }}"></script>

</body>
</html>