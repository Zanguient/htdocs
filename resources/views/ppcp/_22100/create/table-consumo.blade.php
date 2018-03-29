<div class="container-consumo">
    <fieldset>
        <legend>Estoque de Consumo Disponível</legend>
        <div class="table-container table-consumo">

            <table class="table table-bordered table-header">
                <thead>
                    <tr>
                        <th class="wid-modelo">Produto</th>
                        <th class="wid-quantidade text-right" title="Consumo unitário">Consumo</th>
                        <th class="wid-quantidade text-right" title="Quantidade projetada de consumo com base na quantidade à programar">Projeção</th>
                        <th class="wid-quantidade text-right" title="Quantidade já utilizada nesta remessa">Utilizado</th>
                        <th class="wid-quantidade text-right" title="Quantidade total em estoque">Estoque</th>
                        <th class="wid-quantidade text-right" title="Quantidade reservada para alocação dinâmica de remessas já geradas">Empenhado</th>
                        <th class="wid-quantidade text-right" title="Quantidade restante de disponível para programar">Disponível</th>
                        <th class="wid-quantidade text-right" title="Quantidade reservada para utilização em remessas jà geradas">Alocado</th>
                        <th class="wid-quantidade text-right" title="Quantidade em análise para entrada no estoque">Revisão</th>
                    </tr>
                </thead>
            </table>
            <div class="scroll-table">
                <table class="table table-striped table-bordered table-hover table-body">
                    <col class="wid-modelo"/>
                    <col class="wid-quantidade"/>
                    <col class="wid-quantidade"/>
                    <col class="wid-quantidade"/>
                    <col class="wid-quantidade"/>                        
                    <col class="wid-quantidade"/>
                    <col class="wid-quantidade"/>
                    <col class="wid-quantidade"/>
                    <col class="wid-quantidade"/>                        
                    <tbody>
                        <tr
                            ng-repeat="item in vm.Agrupamento.selected.ESTOQUE_PRODUTOS">
                            <td class="wid-modelo" title="@{{ item.PRODUTO_ID }} - @{{ item.PRODUTO_DESCRICAO }}">@{{ item.PRODUTO_ID }} - @{{ item.PRODUTO_DESCRICAO }}</td>
                            <td class="text-right">@{{ vm.Agrupamento.selected.CONSUMO_ALOCACAO[$index].CONSUMO || 0 | number: 4 }} @{{ item.UM }}/@{{ vm.Agrupamento.selected.UM }}</td>
                            <td class="text-right">@{{ (vm.Agrupamento.selected.CONSUMO_ALOCACAO[$index].CONSUMO * vm.Agrupamento.selected.QUANTIDADE_PROGRAMADA) || 0 | number: 4 }} @{{ item.UM }}</td>
                            <td class="text-right">@{{ item.UTILIZADO  || 0 | number: 4 }} @{{ item.UM }}</td>
                            <td class="text-right">@{{ item.ESTOQUE    || 0 | number: 4 }} @{{ item.UM }}</td>
                            <td class="text-right">@{{ item.EMPENHADO  || 0 | number: 4 }} @{{ item.UM }}</td>
                            <td class="text-right">@{{ item.DISPONIVEL || 0 | number: 4 }} @{{ item.UM }}</td>
                            <td class="text-right">@{{ item.ALOCADO    || 0 | number: 4 }} @{{ item.UM }}</td>
                            <td class="text-right">@{{ item.REVISAO    || 0 | number: 4 }} @{{ item.UM }}</td>
                        </tr>                
                    </tbody>
                </table>
            </div>
        </div>
    </fieldset>
</div>