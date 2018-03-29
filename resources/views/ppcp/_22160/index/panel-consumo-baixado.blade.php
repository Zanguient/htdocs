    <ul class="list-inline acoes" style="left: 15px;">    
        <li>
            <button 
                ng-click="vm.ConsumoBaixadoTalao.imprimirEtiqueta()"
                tabindex="-1" 
                type="button" 
                class="btn btn-warning" 
                title="Coletar Peso" 
                ng-disabled="!(vm.ConsumoBaixadoTalao.SELECTED.TALAO_ID > 0)"
                >
                <span class="glyphicon glyphicon-print"></span> Imprimir Etiqueta
            </button>
        </li>               
    </ul>    


    <form class="form-inline table-filter" ng-submit="vm.ConsumoBaixadoFiltro.consultar()">    
        {{-- ConsumoBaixadoProduto --}}
        
        <div class="form-group">
            <label title="Data para produção da remessa">Data Inicio:</label>
            <div class="input-group">
                <input type="date" ng-model="vm.ConsumoBaixadoFiltro.DATA_1" toDate max="@{{ vm.ConsumoBaixadoFiltro.DATA_2 | date: 'yyyy-MM-dd' }}" class="form-control" required />
                <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                    <span class="fa fa-close"></span>
                </button>
            </div>
        </div>
        <div class="form-group">
            <label title="Data para produção da remessa">Data Fim:</label>
            <div class="input-group">
                <input type="date" ng-model="vm.ConsumoBaixadoFiltro.DATA_2" toDate id="data-prod" class="form-control" required />
                <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                    <span class="fa fa-close"></span>
                </button>
            </div>
        </div>
        
        <button type="submit" class="btn btn-xs btn-primary btn-filtrar btn-table-filter" data-hotkey="alt+f">
            <span class="glyphicon glyphicon-filter"></span>
            {{ Lang::get('master.filtrar') }}
        </button>
    </form>

    <div class="table-panel">
        <div class="header">
            <input type="text" class="form-control input-filter-table" ng-model="vm.ConsumoBaixadoTalao.FILTRO" placeholder="Filtragem rápida...">
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
                                produto in vm.ConsumoBaixadoProduto.DADOS
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
                                    model : vm.ConsumoBaixadoTalao.FILTRO,
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
                                | orderBy : ['REMESSA','REMESSA_TALAO_ID*1'])
                                "
                                tabindex="0" 
                                ng-focus="vm.ConsumoBaixadoTalao.SELECTED != item ? vm.ConsumoBaixadoTalao.pick(item) : ''"
                                ng-click="vm.ConsumoBaixadoTalao.SELECTED != item ? vm.ConsumoBaixadoTalao.pick(item) : ''"
                                ng-class="{'selected' : vm.ConsumoBaixadoTalao.SELECTED == item }"
                                ng-keypress="vm.ConsumoBaixadoTalao.keypress($event)"                            
                                ng-dblclick="vm.Balanca.open()"                            
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
        </div>
        <div class="bottom">
            <div class="table-container table-taloes">
                <table class="table table-bordered table-header table-lc table-consumo">
                    <thead>
                        <tr>
                            <th class="wid-datahora text-center" title="Data e hora da operação">Dt./Hr.</th>
                            <th class="wid-quantidade text-right">Qtd.</th>
                            <th class="wid-usuario">Usuário</th>
                            <th class="wid-acoes text-center" title="Ações">Ações</th>
                        </tr>
                    </thead>
                </table>
                <div class="scroll-table">
                    <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body table-consumo">
                        <tbody>
                            <tr ng-repeat="
                                transacao in vm.ConsumoBaixadoTransacao.DADOS
                                | orderBy : ['-DATAHORA*1','REMESSA','REMESSA_TALAO_ID*1']
                                "
                                tabindex="0" 
                                ng-focus="vm.ConsumoBaixadoTalao.SELECTED != item ? vm.ConsumoBaixadoTalao.pick(item) : ''"
                                ng-click="vm.ConsumoBaixadoTalao.SELECTED != item ? vm.ConsumoBaixadoTalao.pick(item) : ''"
                                ng-class="{'selected' : vm.ConsumoBaixadoTalao.SELECTED == item }"                        
                                >
                                <td class="wid-datahora text-center">@{{ transacao.DATAHORA_TEXT }}</td>
                                <td class="wid-quantidade text-right um">@{{ transacao.QUANTIDADE | number : 4 }} @{{ transacao.UM }}</td>
                                <td class="wid-usuario" autotitle>@{{ transacao.USUARIO_ID }} - @{{ transacao.USUARIO_DESCRICAO }}</td>
                                <td class="wid-acoes text-center">
                                    <button 
                                        type="button" 
                                        class="btn btn-danger btn-xs" 
                                        title="Excluir Transação"
                                        ng-disabled="transacao.STATUS == 1"
                                        ng-click="vm.ConsumoBaixadoTransacao.delete(transacao)"
                                        >
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </button>
                                </td>
                            </tr>  
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>