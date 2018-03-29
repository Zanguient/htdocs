<div class="remessas">
    <div 
        class="remessa-container"
        ng-class="{'ocultar' : vm.selectedItemAcao(remessa,'REMESSA_VIEW','REMESSA_ID')}"
        ng-repeat="remessa in vm.itens">
        <div
        ng-init="remessa.ACAO = []" class="remessa-wrapper">
            <label title="Id da remessa: @{{ remessa.REMESSA_ID }}">Remessa: @{{ remessa.REMESSA }} / @{{ !(remessa.REMESSA_GP_ID > 0) remessa.FAMILIA_ID  ? remessa.REMESSA_GP_ID }}  - @{{ !(remessa.REMESSA_GP_ID > 0) remessa.FAMILIA_DESCRICAO ? remessa.REMESSA_GP_DESCRICAO }} / @{{ remessa.DATA | parseDate | date:'dd/MM/yy' : '+0' }}</label>
            
            @include('ppcp._22120.index.remessa.talao')
            
            <div class="accordion-composicao panel-group accordion@{{ remessa.REMESSA_ID }}" id="accordion@{{ remessa.REMESSA_ID }}" role="tablist" aria-multiselectable="true">

                @include('ppcp._22120.index.remessa.talao-detalhe')
                
                @include('ppcp._22120.index.remessa.talao-consumo')
            </div>
        </div>
    </div>
</div>