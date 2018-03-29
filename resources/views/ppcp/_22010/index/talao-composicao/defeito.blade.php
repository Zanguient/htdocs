<fieldset class="defeito">

	<legend>{{ Lang::get('master.defeitos') }}</legend>

    <div class="recebe-puxador-talao">
        <div class="table-container">
            <table class="table table-bordered table-header table-lc table-talao-produzir">
                <thead>
                    <tr>
                        <th class="wid-descricao">Descrição</th>
                        <th class="wid-quantidade text-right" title="Quantidade">Qtd.</th>
                        <th class="wid-observacao">Observação</th>
                    </tr>
                </thead>
            </table>
            <div class="scroll-table">
                <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body table-talao-produzir">
                    <tbody>
                        <tr ng-repeat="item in vm.TalaoDefeito.DADOS
                            | orderBy : ['DEFEITO_DESCRICAO']"
                            tabindex="0" 
                            ng-focus="vm.TalaoDefeito.SELECTED != item ? vm.TalaoDefeito.selectionar(item) : ''"
                            ng-class="{'selected' : vm.TalaoDefeito.SELECTED == item }"
                            >
                            <td class="wid-descricao">@{{ item.DEFEITO_ID | lpad : [4,'0'] }} - @{{ item.DEFEITO_DESCRICAO }}</td>
                            <td class="wid-quantidade text-right">@{{ item.QUANTIDADE | number : 4 }}</td>
                            <td class="wid-observacao">@{{ item.OBSERVACAO }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</fieldset>