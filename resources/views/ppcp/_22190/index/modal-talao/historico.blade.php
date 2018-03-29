<fieldset class="fieldset-historico">
    <legend>Histórico de Produção</legend>
    <div class="resize table-historico">
        <div class="table-ec">
            <table class="table table-striped table-bordered table-low">
                <thead>
                    <tr>
                        <th class="text-center">Data/Hora</th>
                        <th title="Código do produto">Operador</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="historico in vm.Talao.SELECTED.HISTORICOS
                        | orderBy : ['-ID*1']"
                        tabindex="0" 
                        ng-focus="vm.TalaoHistorico.SELECTED != historico ? vm.TalaoHistorico.selectionar(historico) : ''"
                        ng-click="vm.TalaoHistorico.SELECTED != historico ? vm.TalaoHistorico.selectionar(historico) : ''"
                        ng-class="{'selected' : vm.TalaoHistorico.SELECTED == historico }"
                        >
                        <td class="text-center">@{{ historico.DATAHORA | toDate | date : 'dd/MM/yy HH:mm:ss' }}</td>
                        <td>@{{ historico.OPERADOR_ID }} - @{{ historico.OPERADOR_NOME }}</td>
                        <td>@{{ historico.JUSTIFICATIVA_DESCRICAO.trim() != '' ? historico.JUSTIFICATIVA_DESCRICAO : historico.STATUS_DESCRICAO }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</fieldset>