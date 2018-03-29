<div class="linha-container">
    <input
        type="text" 
        class="input-filtrar-modelo filtrar-linha" 
        placeholder="Filtragem por Linha..." 
        ng-model="vm.Linha.filter"
        ng-change="vm.Linha.selected = null; vm.Linha.FixVsRepeat()">
    <div 
        type="button" 
        class="btn btn-default btn-circle bottom btn-percentual-extra" 
        ng-init="vm.HABILITA_PERCENTUAL_EXTRA = true"
        ng-click="vm.HABILITA_PERCENTUAL_EXTRA = !vm.HABILITA_PERCENTUAL_EXTRA"
        ng-class="{'item-active' : vm.HABILITA_PERCENTUAL_EXTRA}"
        >
        @{{ vm.HABILITA_PERCENTUAL_EXTRA ? 'Incluindo' : 'Ignorando' }} Percentual Extra
    </div>
    <button type="button" class="btn btn-default btn-circle bottom btn-atualizar" title="Atualizar dados (itens já programados não serão afetados)" ng-click="vm.Agrupamento.AtualizarTempo()">
        <span class="fa fa-refresh"></span>
    </button>
    <div class="table-container">
        <table class="table table-bordered table-header">
            <thead>
                <tr>
                    <th class="wid-linha">Linha</th>
                    <th class="wid-tamanho" title="Tamanho">Tam.</th>
                    <th class="wid-quantidade text-right" title="Quantidade de saldo a programar">Qtd. Saldo</th>
                    <th class="wid-quantidade text-right" title="Quantidade a programar">Qtd. Prog</th>
                    <th class="wid-acoes">Ações</th>
                    <th class="wid-check"></th>
                </tr>
            </thead>
        </table>
        <div class="linha scroll-table">
            <table class="table table-striped table-bordered table-hover table-body" id="table-linha">
                <tbody vs-repeat vs-scroll-parent=".table-container">
                    <tr
                        ng-repeat="item in vm.dados.agrupamento_linhas
                        | find: {
                            model : vm.Linha.filter,
                            fields : [
                                'LINHA_DESCRICAO',
                                'TAMANHO_DESCRICAO'
                            ]
                        }
                        | orderBy:['LINHA_DESCRICAO','TAMANHO_DESCRICAO*1']
                       "
                        ng-class="{
                            'disabled' : (item.QUANTIDADE_TOTAL == 0),
                            'selected' : (vm.Linha.selected == item)
                        }"
                        ng-click="vm.Linha.Select(item); vm.Agrupamento.FiltrarEspecial()"
                        class="tr-linha">
                        <td class="wid-linha" title="@{{ item.LINHA_ID }} - @{{ item.LINHA_DESCRICAO }}">@{{ item.LINHA_DESCRICAO }}</td>
                        <td class="wid-tamanho">@{{ item.TAMANHO_DESCRICAO }}</td>
                        <td class="wid-quantidade text-right"><b>@{{ item.QUANTIDADE_SALDO | number: 0 }}<b/></td>
                        <td class="wid-quantidade text-right">
                            <input
                                type="number" 
                                step="@{{ item.TALAO_DETALHE_COTA }}"
                                min="0" 
                                string-to-number 
                                ng-model="item.QUANTIDADE_PROGRAMADA" 
                                ng-value="item.QUANTIDADE_CAPACIDADE"
                                ng-focus="vm.Linha.Select(item)"
                            >
                        </td>
                        <td class="wid-acoes">
                            <button tabindex="-1" class="btn btn-default btn-xs" title="Programar linha (Atalho: Tecla F1 ou Enter)" ng-click="vm.Linha.Programar(item)">
                                <span class="glyphicon glyphicon-plus"></span> Incluir
                            </button>
                        </td>
                        <td class="wid-check">
                            <span class="glyphicon glyphicon-info-sign" ng-click="vm.Linha.RemessaHistorico(item)" title="Clique para visualizar o histórico de remessas"></span>
                        </td> 
                    </tr>                
                </tbody>
            </table>
        </div>
    </div>
</div>