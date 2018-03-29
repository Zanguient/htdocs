<fieldset class="historico">
    <legend>{{ Lang::get($menu.'.historico-prod') }}</legend>
    <div class="botao-container">
        <span>Tempo Realizado: </span>
        <span id="tempo-realizado"> @{{ vm.Filtro.GUIA_ATIVA != 'TALAO_PRODUZIR' ? '-' : vm.TalaoProduzir.SELECTED.TEMPO_REALIZADO_HUMANIZE }} </span>
        <input type="hidden" id="_tempo-realizado" />
    </div>
    <div class="table-historico">
        <div class="recebe-puxador-historico">
            <div class="table-ec">
                <table class="table table-striped table-bordered table-hover table-condensed table-middle table-no-break table-historico">
                    <thead>
                        <tr>
                            <th class="text-center">Data/Hora</th>
                            <th class="" title="Código do produto">Operador</th>
                            <th class="">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="historico in vm.TalaoComposicao.DADOS.HISTORICO
                            | orderBy : ['-ID*1']"
                            tabindex="0" 
                            ng-focus="vm.TalaoHistorico.SELECTED != historico ? vm.TalaoHistorico.selectionar(historico) : ''"
                            ng-click="vm.TalaoHistorico.SELECTED != historico ? vm.TalaoHistorico.selectionar(historico) : ''"
                            ng-class="{'selected' : vm.TalaoHistorico.SELECTED == historico }"
                            >
                            <td class="data-historico text-center" ng-attr-data-datahora="@{{historico.DATAHORA}}">@{{ historico.DATAHORA | toDate | date : 'dd/MM/yy HH:mm:ss' }}</td>
                            <td class="operador-historico">@{{ historico.OPERADOR_ID }} - @{{ historico.OPERADOR_NOME }}</td>
                            <td class="status-historico" data-status="@{{ historico.STATUS.trim() }}">@{{ historico.JUSTIFICATIVA_DESCRICAO.trim() != '' ? historico.JUSTIFICATIVA_DESCRICAO : historico.STATUS_DESCRICAO }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</fieldset>