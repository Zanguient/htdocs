<fieldset>
    <legend>Saldo por Localização</legend>   
    <div class="resize resize-localizacao table-lc">
        <div class="table-container table-lc">
            <table class="table table-bordered table-header table-lc table-localizacao">
                <thead>
                <tr>
                    <th class="wid-estabelecimento" title="Estabelecimento">Estab.</th>
                    <th class="wid-localizacao">Localização</th>
                    <th class="wid-quantidade text-right" title="Saldo Físico">Físico</th>
                    <th class="wid-quantidade text-right" title="Saldo Alocado">Alocado</th>
                    <th class="wid-quantidade text-right" title="Saldo Disponível = Saldo Físico - Saldo Alocado">Disponível</th>
                    <th class="wid-quantidade text-right" title="Saldo em Terceiros">Em Terceiros</th>
                    <th class="wid-quantidade text-right" title="Saldo de Terceiros">De Terceiros</th>
                </tr>
                </thead>
            </table>
            <div class="scroll-table">
                <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body table-localizacao">
                    <tbody>
                        <tr
                            ng-repeat="item in vm.DADOS"
                            ng-class="{'selected' : (vm.EstoqueLocalizacao.selected == item)}"
                            ng-click="vm.EstoqueLocalizacao.selected = item"
                            ng-focus="vm.EstoqueLocalizacao.selected = item"
                            tabindex="0"
                            >
                            <td class="wid-estabelecimento" title="@{{ item.ESTABELECIMENTO_ID }} - @{{ item.ESTABELECIMENTO_NOMEFANTASIA }}">@{{ item.ESTABELECIMENTO_ID }}</td>
                            <td class="wid-localizacao">@{{ item.LOCALIZACAO_ID }} - @{{ item.LOCALIZACAO_DESCRICAO }}</td>
                            <td class="wid-quantidade text-right">@{{ item.SALDO            || 0 | number: 5 }} @{{ item.UM }}</td>
                            <td class="wid-quantidade text-right">@{{ item.SALDO_ALOCADO    || 0 | number: 5 }} @{{ item.UM }}</td>
                            <td class="wid-quantidade text-right">@{{ item.SALDO_DISPONIVEL || 0 | number: 5 }} @{{ item.UM }}</td>
                            <td class="wid-quantidade text-right">@{{ item.SALDO_EMTERCEIRO || 0 | number: 5 }} @{{ item.UM }}</td>
                            <td class="wid-quantidade text-right">@{{ item.SALDO_DETERCEIRO || 0 | number: 5 }} @{{ item.UM }}</td>
                        </tr>  
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</fieldset>