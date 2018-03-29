<div class="bloco-resultado" ng-if="$ctrl.Create.pesquisa.SATISFACAO >= 0">

	<div class="form-group" ng-if="$ctrl.Create.pesquisa.NOTA_DELFA">

		<label>{{ Lang::get($menu.'.label-nota-delfa') }}:</label>

		<label 
			ng-bind="$ctrl.Create.pesquisa.NOTA_DELFA | number:1"></label>

	</div>

	<div class="form-group">

		<label>{{ Lang::get($menu.'.label-satisfacao') }}:</label>

		<label 
			class="label-satisfacao"
			ng-bind="$ctrl.Create.pesquisa.SATISFACAO | number:1"></label>

	</div>

	<div 
		class="satisfacao fa"
		ng-class="{
			'fa-frown-o pessimo': $ctrl.Create.pesquisa.SATISFACAO < 6,
			'fa-frown-o ruim'	: $ctrl.Create.pesquisa.SATISFACAO >= 6 && $ctrl.Create.pesquisa.SATISFACAO < 7,
			'fa-meh-o regular'  : $ctrl.Create.pesquisa.SATISFACAO >= 7 && $ctrl.Create.pesquisa.SATISFACAO < 8,
			'fa-smile-o bom'	: $ctrl.Create.pesquisa.SATISFACAO >= 8 && $ctrl.Create.pesquisa.SATISFACAO < 9,
			'fa-smile-o otimo'	: $ctrl.Create.pesquisa.SATISFACAO >= 9
		}"></div>
		
</div>