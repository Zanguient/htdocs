    <div id="consumo" class="footer-item">
        <input 
            type="text" 
            class="form-control input-action  fast-filter-table" 
            ng-model="vm.Filtro.REMESSA" 
            placeholder="Filtragem por Produto de Consumo..." />
        <button
            type="button" 
            class="btn btn-warning btn-xs" 
            ng-click="vm.Consumo.ModalAvulso.open()"
            ng-disabled="vm.Remessa.SELECTED.TALOES.indexOf(vm.Talao.SELECTED) < 0 || vm.Talao.SELECTED.CONSUMOS.indexOf(vm.Consumo.SELECTED) < 0  || !(vm.Consumo.SELECTED.QUANTIDADE_ESTOQUE > 0)"
            ttitle="
                    @{{ !(vm.Consumo.SELECTED.CONSUMO_LOCALIZACAO_ID_PROCESSO > 0) ? 'Família do produto sem locaização de processo configurada.<br/>' : '' }}
                    @{{ !(vm.Consumo.SELECTED.QUANTIDADE_ESTOQUE > 0) ? 'Produto sem estoque disponível.<br/>' : '' }}
                    Atalho: Tecla 'Enter'
                ">
            <span class="fa fa-level-down"></span>
            Baixa Avulsa
        </button>            
        <button 
            type="button" 
            class="btn btn-warning btn-xs" 
            ng-click="vm.Consumo.ModalPeca.open()"
            ng-disabled="vm.Remessa.SELECTED.TALOES.indexOf(vm.Talao.SELECTED) < 0 || vm.Talao.SELECTED.CONSUMOS.indexOf(vm.Consumo.SELECTED) < 0  || !(vm.Consumo.SELECTED.QUANTIDADE_ESTOQUE > 0)"
            ttitle="
                @{{ !(vm.Consumo.SELECTED.CONSUMO_LOCALIZACAO_ID_PROCESSO > 0) ? 'Família do produto sem locaização de processo configurada.<br/>' : '' }}
                @{{ !(vm.Consumo.SELECTED.QUANTIDADE_ESTOQUE > 0) ? 'Produto sem estoque disponível.<br/>' : '' }}
                Atalho: Tecla 'Espaço'
            ">
            <span class="fa fa-cubes"></span>
            Baixa por Peça
        </button>    
        
        <!--<div class="resize resize-consumo">-->
            <div class="table-container">
                <table class="table table-bordered table-header table-lc table-consumo">
                    <thead>
                        <tr>
                            <th class="wid-produto">Produto</th>
                            <th class="wid-tamanho text-center" ttitle="Tamanho">Tam.</th>
                            <th class="wid-quantidade text-right" ttitle="Quantidade de consumo projetada">Qtd. Proj.</th>
                            <th class="wid-quantidade text-right" ttitle="Quantidade baixada da projeção de consumo">Qtd. Baixada</th>
                            <th class="wid-quantidade text-right" ttitle="Quantidade restante à baixar da projeção de consumo">Qtd. Saldo</th>
                            <th class="wid-quantidade text-right" ttitle="Quantidade disponível em estoque">Qtd. Est.</th>
                        </tr>
                    </thead>
                </table>
                <div class="scroll-table">
                    <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body table-consumo">
                        <tbody>
                            <tr ng-repeat="consumo in vm.Talao.SELECTED.CONSUMOS
                                | orderBy : ['-REMESSA_DATA*1','REMESSA*1']"
                                tabindex="0" 
                                ng-focus="vm.Consumo.SELECTED != consumo ? vm.Consumo.pick(consumo) : ''"
                                ng-click="vm.Consumo.SELECTED != consumo ? vm.Consumo.pick(consumo) : ''"
                                ng-class="{'selected' : vm.Consumo.SELECTED == consumo }"
                                ng-keypress="vm.Consumo.keypress($event)"
                                >
                                <td class="wid-produto" autotitle>
                                    <a tabindex="-1" title="Clique aqui para consultar o estoque deste produto" href="{{ url('/_15060?PRODUTO_ID=') }}@{{ consumo.CONSUMO_PRODUTO_ID }}&LOCALIZACAO_ID=@{{ consumo.CONSUMO_LOCALIZACAO_ID }}" target="_blank">@{{ consumo.CONSUMO_PRODUTO_ID }}</a>
                                    - 
                                    <span >@{{ consumo.CONSUMO_PRODUTO_DESCRICAO }}</span>                                    
                                </td>
                                <td class="wid-tamanho text-center" ttitle="@{{ consumo.CONSUMO_TAMANHO > 0 ? 'Id Grade: ' + consumo.CONSUMO_GRADE_ID + ' / Id Tamanho:' + consumo.CONSUMO_TAMANHO : '' }}">@{{ consumo.CONSUMO_TAMANHO_DESCRICAO }}</td>
                                <td class="wid-quantidade text-right um">@{{ consumo.QUANTIDADE | number : 4 }} @{{ consumo.CONSUMO_UM }}</td>
                                <td class="wid-quantidade text-right um">
                                    <a tabindex="-1" title="Clique aqui para consultar as transações deste item" href ng-if="consumo.QUANTIDADE_CONSUMO > 0" ng-click="vm.Consumo.ModalTransacao.consultar(consumo.REMESSA_ID,consumo.REMESSA_TALAO_ID,consumo.CONSUMO_PRODUTO_ID,consumo.CONSUMO_TAMANHO)">@{{ consumo.QUANTIDADE_CONSUMO | number : 4 }} @{{ consumo.CONSUMO_UM }}</a>
                                    <span ng-if="consumo.QUANTIDADE_CONSUMO == 0" >@{{ consumo.QUANTIDADE_CONSUMO | number : 4 }} @{{ consumo.CONSUMO_UM }}</span>
                                </td>                                
                                <td class="wid-quantidade text-right um">@{{ consumo.QUANTIDADE_SALDO | number : 4 }} @{{ consumo.CONSUMO_UM }}</td>
                                <td 
                                    class="wid-quantidade text-right um"
                                    ng-class="{'text-alert' : consumo.QUANTIDADE_ESTOQUE == 0}"
                                    >
                                    @{{ consumo.QUANTIDADE_ESTOQUE | number : 4 }} @{{ consumo.CONSUMO_UM }}
                                </td>
                           
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <!--</div>-->
    </div>