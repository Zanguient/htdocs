<div class="info-destaque">
    <div class="label label-warning" id="operador" ng-click="vm.Operador.open()">
        <span>Operador:</span>
        
        <button 
            type="button" 
            class="btn  btn-temp btn-warning btn-xs"
            style="top: 3px"
            ng-click="vm.Operador.open()"
            ng-if="!vm.Operador.AUTENTICADO">
            <span class="glyphicon glyphicon-user"></span> Autenticar Operador
        </button>        
        <span 
            class="valor"
            ng-if="vm.Operador.AUTENTICADO"
            autotitle
            >@{{ vm.Operador.SELECTED.OPERADOR_NOME }}
        </span>
    </div>
    <div class="label label-primary" id="operador" ng-click="vm.Gp.open()">
        <span ttitle="Grupo de Produção">GP:</span>
        
        <button 
            type="button" 
            class="btn  btn-temp btn-primary btn-xs"
            style="top: 3px"
            ng-click="vm.Gp.open()"
            ng-if="!vm.Gp.AUTENTICADO">
            <span class="glyphicon glyphicon-user"></span> Autenticar GP
        </button>        
        <span 
            class="valor"
            ng-if="vm.Gp.AUTENTICADO"
            autotitle
            >@{{ vm.Gp.SELECTED.GP_DESCRICAO }}
        </span>
    </div>
    <div class="label label-default" id="data-destaque">
        <span>Data Produção:</span>
        <span class="valor">@{{ vm.Clock.DATETIME_SERVER | toDate | date : 'dd/MM/yyyy' }}</span>
    </div>
</div> 