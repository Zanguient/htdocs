<div class="header-right">
    Soma: @{{ remessa.QUANTIDADE_SOMA | number: 4 }} @{{ remessa.UM }}
    <span ng-if="(remessa.UM_ALTERNATIVA != '')">
        / @{{ remessa.QUANTIDADE_ALTERNATIVA_SOMA | number: 4 }} @{{ remessa.UM_ALTERNATIVA }}
    </span>
    <div class="dropdown acoes">
        <button title="Ações" class="btn btn-default toggle" data-toggle="dropdown">
            <span class="fa fa-ellipsis-v"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right">
            <li class="dropdown-header">Ações da Remessa</li>
            <li>
                <a data-consulta-historico data-tabela="TBREMESSA" data-tabela-id="@{{ remessa.REMESSA_ID }}" href class="gerar-historico">Histórico</a>
            </li>
            <li>
                <a href="{{ url('_28000/74?AUTO=1&REMESSA=') }}@{{ remessa.REMESSA }}" target="_blank">Imprimir Consumo Geral</a>
            <li>
                <a href data-toggle="modal" data-target="#modal-consumo" ng-click="vm.familias_consumo = remessa.CONSUMO_FAMILIAS; vm.consumo_dados.remessa_id = remessa.REMESSA_ID; vm.consumo_dados.familia_id_consumo = '';">Imprimir Consumo Detalhado</a>
            </li>
            <li ng-if="{{ $permissaoMenu->INCLUIR }}">
                <a href ng-click="vm.winPopUp('{{ url('/_22040/create?remessa=') }}' + remessa.REMESSA, 'remessa-componente',{width:1290,height:700})">Gerar Remessa de Componente</a>
            </li>
            <li ng-if="{{ $permissaoMenu->INCLUIR }}">
                <a href ng-click="vm.RemessaIntermediaria.modalOpen(remessa.REMESSA)">Gerar Remessa Intermediária</a>
            </li>            
            <li ng-if="remessa.CONSUMOS.length == 0 && {{ $permissaoMenu->INCLUIR }}">
                <a href ng-click="vm.Talao.verificarSobras(remessa.REMESSA_ID)">1. Processar Aproveitamento de Sobras</a>
            </li>
            <li ng-if="remessa.CONSUMOS.length == 0 && {{ $permissaoMenu->INCLUIR }}">
                <a href ng-click="vm.TaloesExtra.Consultar(remessa.REMESSA_ID)">2. Gerar Talões Extras</a>
            </li>
            <li ng-if="remessa.CONSUMOS.length == 0 && {{ $permissaoMenu->INCLUIR }}">
                <a href ng-click="vm.Consumo.ListarFamilias({familia_id: remessa.FAMILIA_ID}); vm.Remessa.SELECTED = remessa">3. Gerar Consumo</a>
            </li>
            <li ng-if="{{ $permissaoMenu->EXCLUIR }}">
                <a href ng-click="vm.selected_itens_acao['REMESSA'][0] = remessa; vm.Acao('remover','<div class=\'alert alert-danger\'><b>ATENÇÃO: Esta ação não poderá ser desfeita!</b></div>Confirma a exclusão desta remessa?',remessa,'REMESSA_ID','REMESSA')">Excluir Remessa</a>
            </li>
            <li class="divider"></li>
            <li class="dropdown-header">Ações do talão</li>
            <li ng-if="remessa.FAMILIA_ID == 3 && {{ $permissaoMenu->ALTERAR }}">
                <a href ng-click="vm.Talao.liberacaoCancelar()">Cancelar Liberação</a>
            </li>
            <li ng-if="remessa.REMESSA_WEB != 3 && {{ $permissaoMenu->ALTERAR }}">
                <a href ng-click="vm.Acao('reabrir','Confirma a reabertura dos talões selecionados?',remessa,'REMESSA_ID','TALAO')">Reabrir Talão</a>
            </li>
            <li ng-if="{{ $permissaoMenu->EXCLUIR }}">
                <a href ng-click="vm.Acao('remover','Confirma a exclusão dos talões selecionados?',remessa,'REMESSA_ID','TALAO')">Excluir Talão</a>
            </li>
        </ul> 
    </div>
</div>
<div class="recebe-puxador-talao">
    <div class="talao table-ec">
        <table class="table table-striped table-bordered table-hover table-body">
            <thead>
                <tr gc-order-by="vm.TALAO_ORDER_BY">
                    <th class="wid-status"></th>
                    <th class="wid-status"></th>
                    <th class="wid-talao" field="REMESSA_TALAO_ID">Talão</th>
                    <th class="wid-modelo" field="MODELO_DESCRICAO,TAMANHO_DESCRICAO,COR_DESCRICAO">Modelo</th>
                    <th class="wid-tamanho text-right" field="TAMANHO_DESCRICAO" title="Tamanho">Tam.</th>
                    <th class="wid-cor" field="COR_DESCRICAO" ng-show="(remessa.TALOES[0].COR_ID)">Cor</th>
                    <th class="wid-@{{ (remessa.TALOES[0].UP_ID > 0) ? 'up' : 'gp' }}" field="GP_DESCRICAO,UP_DESCRICAO" title="@{{ (remessa.TALOES[0].UP_ID > 0) ? 'Unidade Produtiva' : 'Grupo de Produção' }}">@{{ (remessa.TALOES[0].UP_ID > 0) ? 'UP' : 'GP' }}</th>
                    <th class="wid-quantidade text-right" title="Quantidade programada na unidade de medida padrão do modelo">Qtd.</th>
                    <th
                        class="wid-quantidade text-right" 
                        title="Quantidade programada na unidade de medida alternativa do modelo"
                        ng-if="(remessa.UM_ALTERNATIVA != '')">
                        Qtd. Alt.
                    </th>
                    <th class="wid-quantidade text-right" ng-if="!(remessa.TALOES[0].UP_ID > 0)" title="Quantidade aproveitada de sobra, que será descontada do consumo">Qtd. Sobra</th>
                    <th class="wid-quantidade text-right" ng-if="remessa.TALOES[0].QUANTIDADE_APROVEITAMENTO != null" title="Quantidade aproveitada de placas, que será descontada do consumo">Qtd. Aprov.</th>
                    <th class="wid-tempo text-center" ng-if="(remessa.TALOES[0].PROGRAMACAO_STATUS)" title="Tempo programado">T. Prog.</th>
                    <th class="wid-tempo text-center" ng-if="(remessa.TALOES[0].PROGRAMACAO_STATUS)" title="Tempo realizado">T. Real.</th>
                    <th class="wid-data-hora text-center" title="Data e Hora de Produção">Dt. Hr. Prod.</th>
                    <th class="wid-vinculo" 
                        title="Remessa/Talão que o talão está vinculado"
                        ng-if="(remessa.VINCULO)">Vinculos</th>
                </tr>
            </thead>            
            <col class="wid-status"/>
            <col class="wid-status"/>
            <col class="wid-talao"/>
            <col class="wid-modelo"/>
            <col class="wid-tamanho"/>                      
            <col class="wid-cor" ng-if="(remessa.TALOES[0].COR_ID)"/>                      
            <col class="wid-@{{ (remessa.TALOES[0].UP_ID > 0) ? 'up' : 'gp' }}"/>                      
            <col class="wid-quantidade"/>                      
            <col class="wid-quantidade" ng-if="(remessa.UM_ALTERNATIVA != '')"/>                     
            <col class="wid-quantidade" ng-if="!(remessa.TALOES[0].UP_ID > 0)"/>                     
            <col class="wid-tempo" ng-if="(remessa.TALOES[0].PROGRAMACAO_STATUS)"/>                     
            <col class="wid-tempo" ng-if="(remessa.TALOES[0].PROGRAMACAO_STATUS)"/>  
            <col class="wid-data-hora"/>   
            <col class="wid-vinculo" ng-if="(remessa.VINCULO)"/>
            <tbody vs-repeat vs-scroll-parent=".table-ec" vs-options="{latch: false}">
                <tr
                    tabindex="0"
                    ng-repeat="
                        talao in remessa.TALOES  
                        | filter: vm.FiltrarArvore 
                        | find: {
                            model : vm.filtrar_talao,
                            fields : [
                                'MODELO_DESCRICAO',
                                'MODELO_DESCRICAO',
                                'TAMANHO_DESCRICAO',
                                'COR_DESCRICAO',
                                'GP_DESCRICAO',
                                'UP_DESCRICAO',
                                'REMESSA',
                                'REMESSA_TALAO_ID'
                            ]
                        }
                        | orderBy: vm.TALAO_ORDER_BY"
                    ng-class="{'selected' : (vm.IndexOfAttr(vm.class,'ID',talao.ID) >= 0)}"
                    ng-click="vm.selected_itens_acao['CONSUMO'] = []; vm.selected_itens_acao['DETALHE'] = []; vm.selectTalao(talao)"
                    ng-focus="vm.selected_itens_acao['CONSUMO'] = []; vm.selected_itens_acao['DETALHE'] = []; vm.selectTalao(talao)"
                    ng-dblclick="vm.filtrar_arvore = true; vm.FiltrarChange();"
                    ng-keypress="vm.RemessaKeypress(talao,'TALAO' $event)">
                    <td class="chk" ng-click="vm.selectItemAcao(talao,'TALAO','ID');  $event.stopPropagation();"><input tabindex="-1" readonly type="checkbox" ng-checked="vm.selectedItemAcao(talao,'TALAO','ID')"></td>
                    <td class="t-status programacao-@{{ talao.PROGRAMACAO_STATUS }} talao-@{{ talao.STATUS }}"
                        title="@{{ talao.PROGRAMACAO_STATUS_DESCRICAO || talao.STATUS_DESCRICAO }}"></td>
                    <td title="Id do talão: @{{ talao.ID }}">@{{ talao.REMESSA_TALAO_ID }}</td>
                    <td title="Id do modelo: @{{ talao.MODELO_ID }}">@{{ talao.MODELO_DESCRICAO }}</td>
                    <td class="text-right" title="Cód. Tam.: @{{ talao.TAMANHO }}">@{{ talao.TAMANHO_DESCRICAO }}</td>
                    <td class="cor-amostra" ng-if="(remessa.TALOES[0].COR_ID)"
                        title="@{{ talao.COR_ID }} - @{{ talao.COR_DESCRICAO }}">
                        <span
                            ng-class="{'disabled' : (talao.COR_AMOSTRA <= 0)}"
                            style="background-image: linear-gradient(to right top, @{{ talao.COR_AMOSTRA | toColor }} 45% , @{{ talao.COR_AMOSTRA2 | toColor }} 55%);"></span>
                        <span class="descricao">

                            @{{ talao.COR_ID }} - @{{ talao.COR_DESCRICAO }}
                        </span>
                    </td>
                    <td title="@{{ talao.UP_ID > 0 ? talao.UP_ID : talao.GP_ID }} - @{{ talao.UP_DESCRICAO || talao.GP_DESCRICAO }}">@{{ talao.UP_DESCRICAO || talao.GP_DESCRICAO }}</td>
                    <td class="text-right">@{{ talao.QUANTIDADE | number: 4 }} @{{ talao.UM }}</td>
                    <td class="text-right"
                        ng-if="(remessa.UM_ALTERNATIVA != '')"
                        >@{{ talao.QUANTIDADE_ALTERNATIVA | number: 4 }} @{{ talao.UM_ALTERNATIVA }}</td>
                    <td class="text-right"
                        ng-if="!(remessa.TALOES[0].UP_ID > 0)"
                        >@{{ talao.QUANTIDADE_SOBRA_APROVEITAMENTO | number: 4 }} @{{ talao.UM }}</td>
                    <td class="text-right"
                        ng-if="remessa.TALOES[0].QUANTIDADE_APROVEITAMENTO != null"
                        >@{{ talao.QUANTIDADE_APROVEITAMENTO | number: 4 }} @{{ talao.UM }}</td>
                    <td ng-if="(remessa.TALOES[0].PROGRAMACAO_STATUS)" class="text-right">@{{ talao.TEMPO | number: 2 }}'</td>
                    <td ng-if="(remessa.TALOES[0].PROGRAMACAO_STATUS)" class="text-right">@{{ talao.TEMPO_REALIZADO | number: 2 }}'</td>
                    <td class="text-center">@{{ talao.HORA_PRODUCAO | parseDate | date:'HH:mm:ss dd/MM/yy' }}</td>
                    <td ng-if="(remessa.VINCULO)">@{{ talao.VINCULOS }}</td>
                    @{{ vm.somaTaloes(remessa) }}
                </tr>                
            </tbody>
        </table>
    </div>
</div>