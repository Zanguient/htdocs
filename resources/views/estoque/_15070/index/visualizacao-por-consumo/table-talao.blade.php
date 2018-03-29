    <div id="consumo" class="footer-item">
        <input type="text" class="form-control  fast-filter-table" ng-model="vm.Filtro.REMESSA" placeholder="Filtragem por Talao...">
        <!--<div class="resize resize-consumo">-->
            <div class="table-container">
                <table class="table table-bordered table-header table-lc table-consumo">
                    <thead>
                        <tr>
                            <th class="wid-talao text-center">Talão</th>
                            <th class="wid-modelo">Modelo</th>
                            <th class="wid-tamanho text-center" ttitle="Tamanho">Tam.</th>
                            <th class="wid-cor">Cor</th>
                            <th class="wid-quantidade text-right" ttitle="Quantidade do talão">Qtd. Tal.</th>
                            <th class="wid-quantidade text-right" ttitle="Quantidade de consumo projetada">Qtd. Proj.</th>
                            <th class="wid-quantidade text-right" ttitle="Quantidade baixada da projeção de consumo">Qtd. Baixada</th>
                            <th class="wid-quantidade text-right" ttitle="Quantidade restante à baixar da projeção de consumo">Qtd. Saldo</th>
                        </tr>
                    </thead>
                </table>
                <div class="scroll-table">
                    <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body table-consumo">
                        <tbody>
                            <tr ng-repeat="consumo in vm.Talao.SELECTED.TALOES
                                | orderBy : ['REMESSA_TALAO_ID*1']"
                                tabindex="0" 
                                ng-focus="vm.Consumo.SELECTED != consumo ? vm.Consumo.pick(consumo) : ''"
                                ng-click="vm.Consumo.SELECTED != consumo ? vm.Consumo.pick(consumo) : ''"
                                ng-class="{'selected' : vm.Consumo.SELECTED == consumo }"
                                >
                                <td class="wid-talao text-center">@{{ consumo.REMESSA_TALAO_ID }}</td>
                                <td class="wid-modelo">@{{ consumo.MODELO_ID }} - @{{ consumo.MODELO_DESCRICAO }}</td>
                                <td class="wid-tamanho text-center" ttitle="@{{ consumo.TAMANHO > 0 ? 'Id Grade: ' + consumo.GRADE_ID + ' / Id Tamanho:' + consumo.TAMANHO : '' }}">@{{ consumo.TAMANHO_DESCRICAO }}</td>
                                <td class="wid-cor">@{{ consumo.COR_ID }} - @{{ consumo.COR_DESCRICAO }}</td>
                                <td class="wid-quantidade text-right um">@{{ consumo.QUANTIDADE_TALAO | number : 4 }} @{{ consumo.UM_TALAO }}</td>
                                <td class="wid-quantidade text-right um">@{{ consumo.QUANTIDADE | number : 4 }} @{{ consumo.CONSUMO_UM }}</td>
                                <td class="wid-quantidade text-right um">@{{ consumo.QUANTIDADE_CONSUMO | number : 4 }} @{{ consumo.CONSUMO_UM }}</td>
                                <td class="wid-quantidade text-right um">@{{ consumo.QUANTIDADE_SALDO | number : 4 }} @{{ consumo.CONSUMO_UM }}</td>
                           
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <!--</div>-->
    </div>