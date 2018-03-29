<ul class="list-inline acoes">

    <li>
		<button type="button" class="btn btn-success" id="iniciar" data-hotkey="home" data-toggle="modal" data-target="#modal-autenticacao" disabled>
			<span class="glyphicon glyphicon-play"></span>
			{{ Lang::get('master.iniciar') }}
		</button>
	</li>
	<li>
		<button type="button" class="btn btn-primary" id="pausar" data-hotkey="pause" disabled>
			<span class="glyphicon glyphicon-pause"></span>
			{{ Lang::get('master.pausar') }}
		</button>
	</li>
	<li>
		<button type="button" class="btn btn-danger" id="finalizar" data-hotkey="end" disabled>
			<span class="glyphicon glyphicon-stop"></span>
			{{ Lang::get('master.finalizar') }}
		</button>
	</li>
	<li>
		<button type="button" class="btn btn-warning" id="etiqueta" data-hotkey="alt+i" data-retorno="PRODUCAO" disabled>
			<span class="glyphicon glyphicon-print"></span>
			{{ Lang::get($menu.'.imprimir-etiqueta') }}
		</button>
	</li>
	
</ul>