<form class="form-inline" ng-submit="vm.Filtro.start(vm.Itens.ferramentaColor)">
    
    <div id="filtro" class="table-filter collapse in" aria-expanded="true">   
  
		<div>
			<label>{{ Lang::get('master.periodo') }}:</label>
			<input type="date" class="data-inicial" id="data-inicial" ng-model="vm.Filtro.DADOS.DATA_1" />
			<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>
			<input type="date" class="data-final" id="data-final" ng-model="vm.Filtro.DADOS.DATA_2" />
		</div>
<!--        <md-button type="submit"  class="md-primary md-raised md-filter">
			<span class="glyphicon glyphicon-filter"></span>
			{{ Lang::get('master.filtrar') }}
        </md-button>-->
        <button type="submit" class="btn btn-sm btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
			<span class="glyphicon glyphicon-filter"></span>
			{{ Lang::get('master.filtrar') }}
		</button>
    </div>
</form>