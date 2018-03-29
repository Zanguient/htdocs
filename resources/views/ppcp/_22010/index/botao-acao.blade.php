<ul class="list-inline acoes">

    <li>
        <button ng-click="vm.Acao.iniciar()"  type="button" class="btn btn-success" id="iniciar" data-hotkey="home" ng-disabled="!vm.Acao.check('iniciar').status">
			<span class="glyphicon glyphicon-play"></span>
			{{ Lang::get('master.iniciar') }}
		</button>
	</li>
	<li>
		<button ng-click="vm.Acao.pausarProducao()" type="button" class="btn btn-primary" id="pausar" data-hotkey="pause" ng-disabled="!vm.Acao.check('pausar').status">
			<span class="glyphicon glyphicon-pause"></span>
			{{ Lang::get('master.pausar') }}
		</button>
	</li>
	<li>
		<button ng-click="vm.Acao.finalizar()" type="button" class="btn btn-danger" id="finalizar" data-hotkey="end" ng-disabled="!vm.Acao.check('finalizar').status">
			<span class="glyphicon glyphicon-stop"></span>
			{{ Lang::get('master.finalizar') }}
		</button>
	</li>
	<li>
		<button type="button" class="btn btn-warning" id="etiqueta" data-hotkey="alt+i" data-retorno="PRODUCAO"  ng-disabled="!vm.Acao.check('imprimir').status">
			<span class="glyphicon glyphicon-print"></span>
			{{ Lang::get($menu.'.imprimir-etiqueta') }}
		</button>
	</li>
	
</ul>