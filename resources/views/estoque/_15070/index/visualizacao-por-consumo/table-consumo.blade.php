        <div id="talao" class="header-item">
            <input 
                type="text" 
                class="form-control input-action fast-filter-table" 
                ng-model="vm.Filtro.REMESSA" 
                placeholder="Filtragem por Produto de Consumo..." />
            <button 
                type="button" 
                class="btn btn-warning btn-xs" 
                ng-click="vm.Consumo.ModalAvulso.open()"
                ng-disabled="vm.Remessa.SELECTED.PRODUTOS.indexOf(vm.Talao.SELECTED) < 0 || !(vm.Talao.SELECTED.QUANTIDADE_ESTOQUE > 0)"
                ttitle="
                    @{{ !(vm.Talao.SELECTED.CONSUMO_LOCALIZACAO_ID_PROCESSO > 0) ? 'Família do produto sem locaização de processo configurada.<br/>' : '' }}
                    @{{ !(vm.Talao.SELECTED.QUANTIDADE_ESTOQUE > 0) ? 'Produto sem estoque disponível.<br/>' : '' }}
                    Atalho: Tecla 'Enter'
                ">
                <span class="fa fa-level-down"></span>
                Baixa Avulsa
            </button>            
            <button 
                type="button" 
                class="btn btn-warning btn-xs" 
                ng-click="vm.Consumo.ModalPeca.open()"
                ng-disabled="vm.Remessa.SELECTED.PRODUTOS.indexOf(vm.Talao.SELECTED) < 0 || !(vm.Talao.SELECTED.QUANTIDADE_ESTOQUE > 0)"
                ttitle="
                    @{{ !(vm.Talao.SELECTED.CONSUMO_LOCALIZACAO_ID_PROCESSO > 0) ? 'Família do produto sem locaização de processo configurada.<br/>' : '' }}
                    @{{ !(vm.Talao.SELECTED.QUANTIDADE_ESTOQUE > 0) ? 'Produto sem estoque disponível.<br/>' : '' }}
                    Atalho: Tecla 'Espaço'
                ">
                <span class="fa fa-cubes"></span>
                Baixa por Peça
            </button>            
            <!--<div class="resize resize-talao">-->
                <div class="table-container">
                    <table class="table table-bordered table-header table-lc table-talao">
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
                        <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body table-talao">
                            <tbody>
                                <tr ng-repeat="talao in vm.Remessa.SELECTED.PRODUTOS
                                    | orderBy : ['CONSUMO_FAMILIA_ID*1','CONSUMO_PRODUTO_DESCRICAO']"
                                    tabindex="0" 
                                    ng-focus="vm.Talao.SELECTED != talao ? vm.Talao.pick(talao) : ''"
                                    ng-click="vm.Talao.SELECTED != talao ? vm.Talao.pick(talao) : ''"
                                    ng-class="{'selected' : vm.Talao.SELECTED == talao }"
                                    ng-keypress="vm.Consumo.keypress($event)"
                                    >
                                    <td class="wid-produto" autotitle>
                                        <a tabindex="-1" title="Clique aqui para consultar o estoque deste produto" href="{{ url('/_15060?PRODUTO_ID=') }}@{{ talao.CONSUMO_PRODUTO_ID }}&LOCALIZACAO_ID=@{{ talao.CONSUMO_LOCALIZACAO_ID }}" target="_blank">@{{ talao.CONSUMO_PRODUTO_ID }}</a>
                                        - 
                                        <span>@{{ talao.CONSUMO_PRODUTO_DESCRICAO }}</span></td>
                                    <td class="wid-tamanho text-center" ttitle="@{{ talao.CONSUMO_TAMANHO > 0 ? 'Id Grade: ' + talao.CONSUMO_GRADE_ID + ' / Id Tamanho:' + talao.CONSUMO_TAMANHO : '' }}">@{{ talao.CONSUMO_TAMANHO_DESCRICAO }}</td>
                                    <td class="wid-quantidade text-right um">@{{ talao.QUANTIDADE | number : 4 }} @{{ talao.CONSUMO_UM }}</td>
                                    <td class="wid-quantidade text-right um">
                                        <a tabindex="-1" title="Clique aqui para consultar as transações deste item" href ng-if="talao.QUANTIDADE_CONSUMO > 0" ng-click="vm.Consumo.ModalTransacao.consultar(talao.REMESSA_ID,null,talao.CONSUMO_PRODUTO_ID,talao.CONSUMO_TAMANHO)">@{{ talao.QUANTIDADE_CONSUMO | number : 4 }} @{{ talao.CONSUMO_UM }}</a>
                                        <span ng-if="talao.QUANTIDADE_CONSUMO == 0" >@{{ talao.QUANTIDADE_CONSUMO | number : 4 }} @{{ talao.CONSUMO_UM }}</span>
                                    </td>
                                    <td class="wid-quantidade text-right um">@{{ talao.QUANTIDADE_SALDO | number : 4 }} @{{ talao.CONSUMO_UM }}</td>
                                    <td 
                                        class="wid-quantidade text-right um"
                                        ng-class="{'text-alert' : talao.QUANTIDADE_ESTOQUE == 0}"
                                        >
                                        @{{ talao.QUANTIDADE_ESTOQUE | number : 4 }} @{{ talao.CONSUMO_UM }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <!--</div>-->
        </div>
