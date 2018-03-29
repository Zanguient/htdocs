        <div id="talao" class="header-item">
            <input type="text" class="form-control  fast-filter-table" ng-model="vm.Filtro.REMESSA" placeholder="Filtragem por Talao...">
            <!--<div class="resize resize-talao">-->
                <div class="table-container">
                    <table class="table table-bordered table-header table-lc table-talao">
                        <thead>
                            <tr>
                                <th class="wid-talao text-center">Talão</th>
                                <th class="wid-modelo">Modelo</th>
                                <th class="wid-tamanho text-center" ttitle="Tamanho">Tam.</th>
                                <th class="wid-cor">Cor</th>
                                <th class="wid-quantidade text-right" ttitle="Quantidade do Talão">Qtd. Tal.</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="scroll-table">
                        <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body table-talao">
                            <tbody>
                                <tr ng-repeat="talao in vm.Remessa.SELECTED.TALOES
                                    | orderBy : ['REMESSA_TALAO_ID*1']"
                                    tabindex="0" 
                                    ng-focus="vm.Talao.SELECTED != talao ? vm.Talao.pick(talao) : ''"
                                    ng-click="vm.Talao.SELECTED != talao ? vm.Talao.pick(talao) : ''"
                                    ng-class="{'selected' : vm.Talao.SELECTED == talao }"
                                    >
                                    <td class="wid-talao text-center">@{{ talao.REMESSA_TALAO_ID }}</td>
                                    <td class="wid-modelo">@{{ talao.MODELO_ID }} - @{{ talao.MODELO_DESCRICAO }}</td>
                                    <td class="wid-tamanho text-center" ttitle="@{{ talao.TAMANHO > 0 ? 'Id Grade: ' + talao.GRADE_ID + ' / Id Tamanho:' + talao.TAMANHO : '' }}">@{{ talao.TAMANHO_DESCRICAO }}</td>
                                    <td class="wid-cor">@{{ talao.COR_ID }} - @{{ talao.COR_DESCRICAO }}</td>
                                    <td class="wid-quantidade text-right um">@{{ talao.QUANTIDADE_TALAO | number : 4 }} @{{ talao.UM_TALAO }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <!--</div>-->
        </div>
