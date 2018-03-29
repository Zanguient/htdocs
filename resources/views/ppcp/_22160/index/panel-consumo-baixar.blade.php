    <ul class="list-inline acoes" style="left: 15px;">    
        <li>
            <button 
                ng-click="vm.ConsumoBaixarProduto.DADOS[0] != undefined ? vm.ConsumoBaixarTalao.SELECTED = vm.ConsumoBaixarProduto.DADOS[0] : ''; vm.ConsumoBaixarBalanca.open()"
                tabindex="-1" 
                type="button" 
                class="btn btn-warning" 
                title="Coletar Peso" 
                >
                    <span class="glyphicon glyphicon-scale"></span> Coletar Peso Avulso
            </button>
        </li>               
    </ul>    


        <input type="text" class="form-control input-filter-table" ng-model="vm.ConsumoBaixarTalao.FILTRO" placeholder="Filtragem rápida...">
        <div class="table-container table-taloes">
            <table class="table table-bordered table-header table-lc table-consumo">
                <thead>
                    <tr>
                        <th class="wid-talao text-center" colspan="2">Talão</th>
                        <th class="wid-consumo text-center" colspan="3">Consumo</th>
                    </tr>
                    <tr>
                        <th class="wid-remessa">Remessa / Talão</th>
                        <th class="wid-quantidade-talao text-right" ttitle="Quantidade do talão">Qtd.</th>
                        <th class="wid-quantidade text-right" ttitle="Quantidade projetada">Qtd. Proj.</th>
                        <th class="wid-quantidade text-right" ttitle="Quantidade consumida">Qtd. Cons.</th>
                        <th class="wid-quantidade text-right" ttitle="Quantidade à consumir">Qtd. Saldo</th>
                    </tr>
                </thead>
            </table>
            <div class="scroll-table">
                <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body table-consumo">
                    <tbody>
                        <tr ng-repeat-start="
                            produto in vm.ConsumoBaixarProduto.DADOS
                            | orderBy : ['CONSUMO_PRODUTO_DESCRICAO*1','CONSUMO_TAMANHO_DESCRICAO*1']
                            "
                            ng-if="produto.FILTERED.length > 0"
                            tabindex="0"      
                            style="background: rgb(185, 215, 232); font-weight: bold;"
                            >
                            <td class="wid-produto row-fixed row-fixed-1" autotitle>
                                <a tabindex="-1" title="Clique aqui para consultar o estoque deste produto" href="{{ url('/_15060?PRODUTO_ID=') }}@{{ produto.CONSUMO_PRODUTO_ID }}&LOCALIZACAO_ID=@{{ produto.CONSUMO_PROCESSO_LOCALIZACAO_ID }}" target="_blank">@{{ produto.CONSUMO_PRODUTO_ID }}</a>
                                - 
                                @{{ produto.CONSUMO_PRODUTO_DESCRICAO }}@{{ produto.CONSUMO_TAMANHO > 0 ? ' / ' + produto.CONSUMO_TAMANHO_DESCRICAO : '' }}
                            </td>   
                            <td class="wid-quantidade-talao row-fixed row-fixed-1 text-right um" autotitle>
                                @{{ produto.TALAO_QUANTIDADE | number: 0 }} @{{ produto.TALAO_UM }}
                            </td> 
                            <td class="wid-quantidade row-fixed row-fixed-1 text-right um" autotitle>
                                @{{ produto.QUANTIDADE_PROJECAO | number: 4 }} @{{ produto.CONSUMO_UM }}
                            </td>
                            <td class="wid-quantidade row-fixed row-fixed-1 text-right um" autotitle>
                                @{{ produto.QUANTIDADE_CONSUMO | number: 4 }} @{{ produto.CONSUMO_UM }}
                            </td>
                            <td class="wid-quantidade row-fixed row-fixed-1 text-right um" autotitle>
                                @{{ produto.QUANTIDADE_SALDO | number: 4 }} @{{ produto.CONSUMO_UM }}
                            </td>
                        </tr>    
                        <tr ng-repeat="
                            item in produto.FILTERED = (produto.TALOES
                            | find: {
                                model : vm.ConsumoBaixarTalao.FILTRO,
                                fields : [    
                                    'REMESSA',
                                    'REMESSA_TALAO_ID',
                                    'TALAO_MODELO_ID',
                                    'TALAO_MODELO_DESCRICAO',
                                    'TALAO_COR_ID',
                                    'TALAO_COR_DESCRICAO',
                                    'TALAO_TAMANHO_DESCRICAO',
                                    'CONSUMO_PRODUTO_ID',
                                    'CONSUMO_PRODUTO_DESCRICAO',
                                    'CONSUMO_TAMANHO_DESCRICAO'
                                ]
                            }
                            | orderBy : ['DATAHORA_INICIO','REMESSA','REMESSA_TALAO_ID*1'])
                            "
                            tabindex="0" 
                            ng-focus="vm.ConsumoBaixarTalao.SELECTED != item ? vm.ConsumoBaixarTalao.pick(item) : ''"
                            ng-click="vm.ConsumoBaixarTalao.SELECTED != item ? vm.ConsumoBaixarTalao.pick(item) : ''; vm.ConsumoBaixarBalanca.open()"
                            ng-class="{'selected' : vm.ConsumoBaixarTalao.SELECTED == item }"
                            ng-keypress="vm.ConsumoBaixarTalao.keypress($event)"                                             
                            >
                            <td class="wid-remessa" autotitle>
                                @{{ item.REMESSA }} / @{{ item.REMESSA_TALAO_ID }}
                            </td>
                            <td class="wid-quantidade-talao text-right um" autotitle>
                                @{{ item.TALAO_QUANTIDADE | number: 0 }} @{{ item.TALAO_UM }}
                            </td> 
                            <td class="wid-quantidade text-right um" autotitle>
                                @{{ item.QUANTIDADE_PROJECAO | number: 4 }} @{{ item.CONSUMO_UM }}
                            </td>
                            <td class="wid-quantidade text-right um" autotitle>
                                @{{ item.QUANTIDADE_CONSUMO | number: 4 }} @{{ item.CONSUMO_UM }}
                            </td>
                            <td class="wid-quantidade text-right um" autotitle>
                                @{{ item.QUANTIDADE_SALDO | number: 4 }} @{{ item.CONSUMO_UM }}
                            </td>
                        </tr>  
                        <tr ng-repeat-end ng-if="false"></tr>
                    </tbody>
                </table>
            </div>
        </div>