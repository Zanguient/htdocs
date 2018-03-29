<fieldset>
    <legend>Saldo por Grade</legend>
    <div class="resize resize-grade table-lc">
        <div class="table-container table-lc">
            <table class="table table-bordered table-header table-lc table-grade">
                <thead>
                <tr>
                    <th class="wid-tamanho" title="Tamanho">Tam.</th>
                    <th class="wid-quantidade text-right" title="Saldo Físico">Físico</th>
                    <th class="wid-quantidade text-right" title="Saldo Alocado">Alocado</th>
                    <th class="wid-quantidade text-right" title="Saldo Disponível = Saldo Físico - Saldo Alocado">Disponível</th>
                </tr>
                </thead>
            </table>
            <div class="scroll-table">
                <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body table-grade">
                    <tbody>
                        <tr
                            ng-repeat="item in vm.EstoqueLocalizacao.selected.ESTOQUE_GRADES">
                            <td class="wid-tamanho" title="Grade @{{ item.GRADE_ID }} Id @{{ item.TAMANHO }}">@{{ item.TAMANHO_DESCRICAO }}</td>
                            <td class="wid-quantidade text-right">@{{ item.SALDO            || 0 | number: 5 }} @{{ item.UM }}</td>
                            <td class="wid-quantidade text-right">@{{ item.SALDO_ALOCADO    || 0 | number: 5 }} @{{ item.UM }}</td>
                            <td class="wid-quantidade text-right">@{{ item.SALDO_DISPONIVEL || 0 | number: 5 }} @{{ item.UM }}</td>
                        </tr> 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</fieldset>