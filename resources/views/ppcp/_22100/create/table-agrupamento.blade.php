<div class="agrupamento-container" ng-init="vm.filtered_itens_agrupamento = {};">
    <div class="modelo-top">
        <input
            type="text" 
            class="input-filtrar-modelo" 
            placeholder="Filtragem por Modelo..." 
            ng-model="vm.Agrupamento.filter" 
            ng-change="vm.Agrupamento.FixVsRepeat()" 
        />
        <div class="form-group">
            <label title="Previsão máxima do faturamento, representa o campo 'Necessidade especial'">Prev. Máx. Fat.:</label>
            <input type="date" id="data-prod" class="form-control" 
                ng-model="vm.filtro.previsao_max_faturamento" 
                ng-change="vm.Agrupamento.FiltrarEspecial()"
            />
        </div>     
    </div>
    <div class="table-container">
        <table class="table table-bordered table-header">
            <thead>
                <tr>
                    <th class="wid-check check agrupamento" title="Marcar/desmarcar todos itens visíveis (Atalho: Tecla '*')" ng-click="vm.Agrupamento.CheckAll(!vm.Agrupamento.checked)">
                        <i class="check fa" ng-class="vm.Agrupamento.checked ? 'fa-check-square-o' : 'fa-square-o'"></i>
                    </th>
                    <th class="wid-modelo">Modelo</th>
                    {{--<th class="wid-tamanho" title="Tamanho">Tam.</th>--}}
                    <th class="wid-cor">Cor</th>
                    <th class="wid-perfil">Perfil Sku</th>
                    <th class="wid-quantidade text-right" ng-class="vm.filtro.previsao_max_faturamento ? '' : 'empty-necessidade-especial'" title="Necessidade especial (Quantidade com previsão de faturamento até a data informada no campo 'Prev.Máx.Fat.')">Nec.Espe.</th>
                    <th class="wid-quantidade text-right" title="Quantidade restante de disponível para programar">Qtd. Saldo</th>
                    <th class="wid-quantidade text-right" title="Quantidade a programar">Qtd. Prog.</th>
                    <th class="wid-quantidade text-right" title="Quantidade já programada">Qtd. Util.</th>
                    <th class="wid-check"></th>
                </tr>
            </thead>
        </table>
        <div class="agrupamento scroll-table">
            <table class="table table-striped table-bordered table-hover table-body" id="table-agrupamento">
                <col class="wid-check"/>
                <col class="wid-modelo"/>
                <col class="wid-cor"/>
                <col class="wid-perfil"/>
                <col class="wid-quantidade text-right" ng-class="vm.filtro.previsao_max_faturamento ? '' : 'empty-necessidade-especial'"/>
                <col class="wid-quantidade text-right"/>
                <col class="wid-quantidade text-right"/>
                <col class="wid-quantidade text-right"/>
                <col class="wid-check"/>
                <tbody>
                    <tr
                        ng-repeat="item in vm.Agrupamento.filtered = ( 
                        vm.Linha.selected.MODELOS
                        | find: {
                            model : vm.Agrupamento.filter,
                            fields : [
                                'MODELO_DESCRICAO',
                                'TAMANHO_DESCRICAO',
                                'COR_DESCRICAO',
                                'PERFIL_SKU_DESCRICAO'
                            ]
                        })  
                       "
                        ng-class="{
                            'disabled' : (item.QUANTIDADE_SALDO == 0),
                            'selected' : (vm.Agrupamento.selected == item)
                        }"
                        class="tr-agrupamento"
                        >
                        <td class="check" title="(Atalho: Tecla '+')" ng-click="item.checked = !item.checked; verificaAgrupamento();">
                            <i class="check fa"  ng-class="item.checked ? 'fa-check-square-o' : 'fa-square-o'"></i>
                        </td>
                        <td class="wid-modelo" title="@{{ item.MODELO_ID }} - @{{ item.MODELO_DESCRICAO }}">@{{ item.MODELO_DESCRICAO }}</td>
                        <td 
                            class="wid-cor cor-amostra" 
                            title="@{{ item.COR_ID }} - @{{ item.COR_DESCRICAO }}">
                            @{{ item.COR_CLASSE }}
                            <span
                                ng-class="{'disabled' : (item.COR_AMOSTRA <= 0)}"
                                style="background-image: linear-gradient(to right top, @{{ item.COR_AMOSTRA | toColor }} 45% , @{{ item.COR_AMOSTRA2 | toColor }} 55%);"></span>
                            <span class="descricao">
                                @{{ item.COR_DESCRICAO }}
                            </span>
                        </td>
                        </td>
                        <td class="wid-perfil">@{{ item.PERFIL_SKU_DESCRICAO }}</td>
                        <td class="wid-quantidade text-right" ng-class="vm.filtro.previsao_max_faturamento ? 'necessidade-especial' : 'empty-necessidade-especial'">@{{ item.QUANTIDADE_ESPECIAL | number: 0 }}</td>
                        <td class="wid-quantidade text-right">@{{ item.QUANTIDADE_SALDO | number: 0 }}</td>
                        <td class="wid-quantidade text-right">
                            <input
                                type="number" 
                                step="@{{ item.TALAO_DETALHE_COTA }}"
                                min="0" 
                                valid-max-value
                                string-to-number 
                                ng-model="item.QUANTIDADE_PROGRAMADA" 
                                ng-value="item.QUANTIDADE_SALDO"
                                ng-max="item.QUANTIDADE_SALDO" 
                                ng-keydown="vm.Agrupamento.KeyDown(item,$event)"
                                ng-focus="vm.Agrupamento.Select(item)"
                                ng-class="{'consumo-indisponivel' : (item.CONSUMO_DISPONIVEL == 0)}"
                                ng-change="item.checked = true; vm.Consumo.Disponibilidade(item);">
                        </td>
                        <td class="wid-quantidade text-right">@{{ item.QUANTIDADE_UTILIZADA | number: 0 }}</td>
                        <td class="check">
                            <span class="glyphicon glyphicon-info-sign operacao-descricao"
                            data-toggle="popover" 
                            data-placement="right" 
                            title="Informações"
                            data-element-content="#pedido-@{{ item.PRODUTO_ID }}"
                            on-finish-render="bs-init"
                            ng-class="{
                                'info-danger' : !(item.TEMPO_PAR > 0) || ( item.checked && !item.FERRAMENTA_DISPONIVEL )
                            }"></span>
                            <div id="pedido-@{{ item.PRODUTO_ID }}" style="display: none">
                                <fieldset>
                                    <legend>Sobre Pedidos</legend>
                                    <table class="table table-striped table-bordered" style="margin-bottom: 10px">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Semana</th>
                                                <th class="text-center" title="Previsão de faturamento / Data de Emissão">Prev. Fatur. / Dt. Emissão</th>
                                                <th class="text-center" title="Número do pedido">Pedido</th>
                                                <th>Cliente</th>
                                                <th class="text-right" title="Quantidade">Qtd.</th>
                                                <th title="Id Agrupamento / Id do Item do Agrupamento">Agrupamento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="agrup in item.AGRUPAMENTO">
                                                <td class="text-center">@{{ agrup.SEMANA | lpad: [2,'0'] }}</td>
                                                <td class="text-center">@{{ agrup.DATA_COMPLETA }}</td>
                                                <td class="text-center">@{{ agrup.TABELA_ID | lpad: [6,'0'] }}</td>
                                                <td>@{{ agrup.CLIENTE_NOMEFANTASIA }}</td>
                                                <td class="text-right">@{{ agrup.QUANTIDADE_TOTAL | number: 0 }}</td>
                                                <td>@{{ agrup.AGRUPAMENTO_ID | lpad: [3,0] }} / @{{ agrup.ID | lpad: [7,0] }} </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </fieldset>
                                <fieldset>
                                    <legend>Sobre o Modelo</legend>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Matriz</th>
                                                <th class="text-center" title="Unidade de Medida">UM</th>
                                                <th class="text-center" title="Tempo por @{{ item.UM }}">Tp/@{{ item.UM }}</th>
                                                <th class="text-center" title="Tempo de Setup de Cor">Tp.Set.Cor</th>
                                                <th class="text-center" title="Tempo de Setup para Aprovação">Tp.Set.Aprov.</th>
                                                <th class="text-center" title="Cota por Talão (Acumulado)">Ct. Tal.</th>
                                                <th class="text-center" title="Cota por Talão Detalhado">Ct. Tal. Det.</th>
                                                <th class="text-center" ng-if="item.HABILITA_PERCENTUAL_EXTRA" title="Percentual extra baseado em defeitos das 3 ultimas remessas">% Extra</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td ng-class="{'bg-info-danger' : ( item.checked && !item.FERRAMENTA_DISPONIVEL )}">@{{ item.MATRIZ_ID | lpad: [4,'0'] }} - @{{ item.MATRIZ_DESCRICAO }}</td>
                                                <td class="text-right">@{{ item.UM }}</td>
                                                <td class="text-right" ng-class="{'bg-info-danger' : !(item.TEMPO_PAR > 0)}">@{{ item.TEMPO_PAR | number: 4 }}'</td>
                                                <td class="text-right">@{{ item.COR_TEMPO_SETUP | number: 4 }}'</td>
                                                <td class="text-right">@{{ item.COR_TEMPO_SETUP_APROVACAO | number: 4 }}'</td>
                                                <td class="text-right">@{{ item.TALAO_COTA | number: 0 }}</td>
                                                <td class="text-right">@{{ item.TALAO_DETALHE_COTA | number: 0 }}</td>
                                                <td class="text-right" ng-if="item.HABILITA_PERCENTUAL_EXTRA">@{{ (item.PERCENTUAL_DEFEITO*100) | number: 2 }}%</td>
                                            </tr>
                                        </tbody>
                                    </table>   
                                </fieldset>
                            </div>
                        </td>
                    </tr>                
                </tbody>
            </table>
        </div>
    </div>
</div>