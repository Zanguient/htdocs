<div id="filtro" class="table-filter collapse in" aria-expanded="true">   
    <form class="form-inline" ng-submit="vm.filtrar()">      
        <div class="form-group">
            <label for="genealogia" data-toggle="tooltip" data-original-title="Identifica a árvore genealogica da remessas">Genealogia</label>
            <input
                ng-model="vm.marcar_irmaos" 
                type="checkbox" 
                id="genealogia"
                class="form-control" />
        </div>
        <div class="btn-inline remessas">
            <span
                ng-repeat="remessa in vm.itens"
                ng-click="vm.selectItemAcao(remessa,'REMESSA_VIEW','REMESSA_ID')"
                ng-class="{'ocultar' : vm.selectedItemAcao(remessa,'REMESSA_VIEW','REMESSA_ID')}" title="Id da remessa: @{{ remessa.REMESSA_ID }}">@{{ remessa.REMESSA }}</span>
        </div>
        <div class="btn-inline">
            <button type="button" class="btn btn-default btn-circle bottom" title="Atualizar dados" ng-click="vm.estruturaAction.ConsultarRemessa()">
                <span class="fa fa-refresh"></span>
            </button>
        </div>
        <div class="btn-inline">
            <button type="button" class="btn btn-default btn-circle bottom" title="Liberar seleção (Botão direito do mouse)" ng-click="vm.filtrar_arvore = false"><span class="fa fa-unlock-alt"></span></button>
        </div>
    </form>
</div>