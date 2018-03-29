<fieldset class="familia">
    <legend>{{ Lang::get($menu.'.familia-prod') }}</legend>
    <div class="botao-container">
        <span>Tempo Realizado: </span>
        <span id="tempo-realizado"> @{{ vm.Filtro.GUIA_ATIVA != 'TALAO_PRODUZIR' ? '-' : vm.TalaoProduzir.SELECTED.TEMPO_REALIZADO_HUMANIZE }} </span>
        <input type="hidden" id="_tempo-realizado" />
    </div>
    <div class="table-familia">
        <div class="recebe-puxador-familia">
            <div class="table-container">
                <table class="table table-bordered table-header table-lc table-familia">
                    <thead>
                        <tr>
                            <th class="wid-datahora text-center">Data/Hora</th>
                            <th class="wid-operador" title="Código do produto">Operador</th>
                            <th class="wid-status">Status</th>
                        </tr>
                    </thead>
                </table>
                <div class="scroll-table">
                    <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body table-familia">
                        <tbody>
                            <tr ng-repeat="familia in vm.ProgramacaoFamilia.DADOS | orderBy : ['FAMILIA_DESCRICAO*1']"
                                tabindex="0" 
                                ng-focus="vm.ProgramacaoFamilia.SELECTED != familia ? vm.ProgramacaoFamilia.selectionar(familia) : ''"
                                ng-click="vm.ProgramacaoFamilia.SELECTED != familia ? vm.ProgramacaoFamilia.selectionar(familia) : ''"
                                ng-class="{'selected' : vm.ProgramacaoFamilia.SELECTED == familia }"
                                >
                                <td class="wid-datahora data-familia text-center" ng-attr-data-datahora="@{{familia.DATAHORA}}">@{{ familia.DATAHORA | toDate | date : 'dd/MM/yy HH:mm:ss' }}</td>
                                <td class="wid-operador operador-familia">@{{ familia.OPERADOR_ID }} - @{{ familia.OPERADOR_NOME }}</td>
                                <td class="wid-status status-familia" data-status="@{{ familia.STATUS.trim() }}">@{{ familia.JUSTIFICATIVA_DESCRICAO.trim() != '' ? familia.JUSTIFICATIVA_DESCRICAO : familia.STATUS_DESCRICAO }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</fieldset>