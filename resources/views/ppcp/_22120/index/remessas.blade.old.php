<div class="remessas">
    <div 
        class="remessa-container"
        ng-class="{'ocultar' : vm.selectedItemAcao(remessa,'REMESSA_VIEW','REMESSA_ID')}"
        ng-repeat="remessa in vm.itens">
        <div
        ng-init="remessa.ACAO = []" class="remessa-wrapper">
            <label title="Id da remessa: @{{ remessa.REMESSA_ID }}">Remessa: @{{ remessa.REMESSA }} / @{{ remessa.FAMILIA_ID }} - @{{ remessa.FAMILIA_DESCRICAO }} / @{{ remessa.DATA | parseDate | date:'dd/MM/yy' : '+0' }}</label>
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
                      <li><a href="#" class="gerar-historico" data-toggle="modal" data-target="#modal-historico" ng-click="vm.setRemessaHistorico(remessa)">Histórico</a></li>
                      <li class="disabled"><a href="#">Imprimir Programação</a></li>
                      <li><a href="#" data-toggle="modal" data-target="#modal-consumo" ng-click="vm.familias_consumo = remessa.CONSUMO_FAMILIAS; vm.consumo_dados.remessa_id = remessa.REMESSA_ID; vm.consumo_dados.familia_id_consumo = '';">Imprimir Consumo</a></li>
                      <li class="divider"></li>
                      <li class="dropdown-header">Ações do talão</li>
                      <li><a href="#" ng-click="vm.Excluir('Confirma a exclusão dos talões selecionados?',remessa,'REMESSA_ID','TALAO')">Excluir</a></li>
                    </ul>
                </div>
            </div>
            <div class="talao table-container">
                <table class="table table-bordered table-header">
                    <thead>
                        <tr>
                            <th class="wid-status"></th>
                            <th class="wid-status"></th>
                            <th class="wid-talao">Talão</th>
                            <th class="wid-modelo">Modelo</th>
                            <th class="wid-tamanho text-right" title="Tamanho">Tam.</th>
                            <th class="wid-cor" ng-if="(remessa.TALOES[0].COR_ID)">Cor</th>
                            <th class="wid-@{{ (remessa.TALOES[0].UP_ID > 0) ? 'up' : 'gp' }}" title="@{{ (remessa.TALOES[0].UP_ID > 0) ? 'Unidade Produtiva' : 'Grupo de Produção' }}">@{{ (remessa.TALOES[0].UP_ID > 0) ? 'UP' : 'GP' }}</th>
                            <th class="wid-quantidade text-right" title="Quantidade programada na unidade de medida padrão do modelo">Qtd.</th>
                            <th
                                class="wid-quantidade text-right" 
                                title="Quantidade programada na unidade de medida alternativa do modelo"
                                ng-if="(remessa.UM_ALTERNATIVA != '')">
                                Qtd. Alt.
                            </th>
                            <th class="wid-tempo text-center" ng-if="(remessa.TALOES[0].PROGRAMACAO_STATUS)" title="Tempo programado">T. Prog.</th>
                            <th class="wid-tempo text-center" ng-if="(remessa.TALOES[0].PROGRAMACAO_STATUS)" title="Tempo realizado">T. Real.</th>
                            <th class="wid-data-hora text-center" title="Data e Hora de Produção">Dt. Hr. Prod.</th>
                            <th class="wid-vinculo" 
                                title="Remessa/Talão que o talão está vinculado"
                                ng-if="(remessa.VINCULO)">Vinculos</th>
                        </tr>
                    </thead>
                </table>
                <div class="scroll-table">
                    <table class="table table-striped table-bordered table-hover table-body">
                        <col class="wid-status"/>
                        <col class="wid-status"/>
                        <col class="wid-talao"/>
                        <col class="wid-modelo"/>
                        <col class="wid-tamanho"/>                      
                        <col class="wid-cor" ng-if="(remessa.TALOES[0].COR_ID)"/>                      
                        <col class="wid-@{{ (remessa.TALOES[0].UP_ID > 0) ? 'up' : 'gp' }}"/>                      
                        <col class="wid-quantidade"/>                      
                        <col class="wid-quantidade" ng-if="(remessa.UM_ALTERNATIVA != '')"/>                     
                        <col class="wid-tempo" ng-if="(remessa.TALOES[0].PROGRAMACAO_STATUS)"/>                     
                        <col class="wid-tempo" ng-if="(remessa.TALOES[0].PROGRAMACAO_STATUS)"/>  
                        <col class="wid-data-hora"/>   
                        <col class="wid-vinculo" ng-if="(remessa.VINCULO)"/>
                        <tbody vs-repeat vs-scroll-parent=".table-container" vs-options="{latch: false}">
                            <tr
                                ng-repeat="
                                    talao in remessa.TALOES | 
                                    filter: vm.FiltrarArvore | 
                                    find: {
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
                                    }"
                                ng-class="{'selected' : (vm.IndexOfAttr(vm.class,'ID',talao.ID) >= 0)}"
                                ng-click="vm.selectTalao(talao)"
                                ng-dblclick="vm.filtrar_arvore = true; vm.FiltrarChange();">
                                <td class="chk" ng-click="vm.selectItemAcao(talao,'TALAO','ID');  $event.stopPropagation();"><input readonly type="checkbox" ng-checked="vm.selectedItemAcao(talao,'TALAO','ID')"></td>
                                <td class="t-status programacao-@{{ talao.PROGRAMACAO_STATUS }} talao-@{{ talao.STATUS }}"
                                    title="@{{ talao.PROGRAMACAO_STATUS_DESCRICAO || talao.STATUS_DESCRICAO }}"></td>
                                <td>@{{ talao.REMESSA_TALAO_ID }}</td>
                                <td title="@{{ talao.MODELO_DESCRICAO }}">@{{ talao.MODELO_DESCRICAO }}</td>
                                <td class="text-right">@{{ talao.TAMANHO_DESCRICAO }}</td>
                                <td class="cor-amostra" ng-if="(remessa.TALOES[0].COR_ID)"
                                    title="@{{ talao.COR_ID }} - @{{ talao.COR_DESCRICAO }}">
                                    <span
                                        ng-class="{'disabled' : (talao.COR_AMOSTRA <= 0)}"
                                        style="background-image: linear-gradient(to right top, @{{ talao.COR_AMOSTRA | toColor }} 45% , @{{ talao.COR_AMOSTRA2 | toColor }} 55%);"></span>
                                    <span class="descricao">

                                        @{{ talao.COR_ID }} - @{{ talao.COR_DESCRICAO }}
                                    </span>
                                </td>
                                <td>@{{ talao.UP_DESCRICAO || talao.GP_DESCRICAO }}</td>
                                <td class="text-right">@{{ talao.QUANTIDADE | number: 4 }} @{{ talao.UM }}</td>
                                <td class="text-right"
                                    ng-if="(remessa.UM_ALTERNATIVA != '')"
                                    >@{{ talao.QUANTIDADE_ALTERNATIVA | number: 4 }} @{{ talao.UM_ALTERNATIVA }}</td>
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
            <div class="accordion-composicao panel-group accordion@{{ remessa.REMESSA_ID }}" id="accordion@{{ remessa.REMESSA_ID }}" role="tablist" aria-multiselectable="true">
                <div class="talao-detalhe panel panel-default">
                    <div class="panel-heading" role="tab" id="heading@{{ remessa.REMESSA_ID }}-talao-detalhe">
                        <a class="accordion" ng-click="$emit('vsRepeatTrigger')" role="button" data-toggle="collapse" href="#collapse@{{ remessa.REMESSA_ID }}-talao-detalhe" aria-controls="collapse@{{ remessa.REMESSA_ID }}-talao-detalhe">
                            <span class="descricao">
                                Detalhamento do Talão
                            </span>
                        </a>
                        <div class="dropup acoes">
                            <button title="Ações" class="btn btn-default toggle" data-toggle="dropdown">
                                <span class="fa fa-ellipsis-v"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                              <li class="dropdown-header">Ações do detalhamento</li>
                              <li class="disabled"><a href="#">Desmembrar</a></li>
                              <li class="disabled"><a href="#">Encerrar</a></li>
                              <li><a href="#" ng-click="vm.Excluir('Confirma a exclusão dos talões detalhados selecionados?',remessa,'REMESSA_ID','DETALHE')">Excluir</a></li>
                            </ul>
                        </div>
                    </div>
                    <div id="collapse@{{ remessa.REMESSA_ID }}-talao-detalhe" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading@{{ remessa.REMESSA_ID }}-talao-detalhe">
                        <div class="panel-body">
                            
                            {{-- Table Talão Detalhado --}}
                            <div class="recebe-puxador-consumo"
                                <div class="talao-consumo table-container">
                                    <table class="table table-bordered table-header">
                                        <thead>
                                            <tr>
                                                <th class="wid-status"></th>
                                                <th class="wid-status"></th>
                                                <th class="wid-talao" title="Código do talão">Talão</th>
                                                <th class="wid-talao-detalhe" title="Código do talão detalhado">Id</th>
                                                <th class="wid-produto">Produto</th>
                                                <th class="wid-quantidade text-right" title="Quantidade projetada à produzir">Qtd. Proj.</th>
                                                <th class="wid-quantidade text-right" title="Quantidade produzida na unidade de medida padrão do produto">Qtd. Prod.</th>
                                                <th class="wid-quantidade text-right" title="Quantidade projetada à produzir na unidade de medida padrão do produto">Qtd. Proj. Alt.</th>
                                                <th class="wid-quantidade text-right" title="Quantidade produzida na unidade de medida padrão do produto">Qtd. Prod. Alt.</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <div class="scroll-table">
                                        <table class="table table-striped table-bordered table-hover table-body">
                                            <col class="wid-status"/>
                                            <col class="wid-status"/>
                                            <col class="wid-talao"/>
                                            <col class="wid-talao-detalhe"/>
                                            <col class="wid-produto"/>                
                                            <col class="wid-quantidade"/>                      
                                            <col class="wid-quantidade"/>                      
                                            <col class="wid-quantidade"/>                      
                                            <col class="wid-quantidade"/>                      
                                            <tbody vs-repeat vs-autoresize vs-scroll-parent=".table-container">
                                                <tr ng-repeat="detalhe in remessa.TALOES_DETALHE | filter: vm.FiltrarTalaoDetalhe">
                                                    <td class="chk" ng-click="vm.selectItemAcao(detalhe,'DETALHE','ID')"><input readonly type="checkbox" ng-checked="vm.selectedItemAcao(detalhe,'DETALHE','ID')"></td>
                                                    <td class="t-status talao-detalhe-status-@{{ detalhe.STATUS }}"
                                                        title="@{{ detalhe.STATUS_DESCRICAO }}"></td>
                                                    <td>@{{ detalhe.REMESSA_TALAO_ID }}</td>
                                                    <td>@{{ detalhe.ID }}</td>
                                                    <td class="cor-amostra"
                                                        title="@{{ detalhe.PRODUTO_ID }} - @{{ detalhe.PRODUTO_DESCRICAO }}">
                                                        <span
                                                            ng-class="{'disabled' : (detalhe.COR_AMOSTRA <= 0)}"
                                                            style="background-image: linear-gradient(to right top, @{{ detalhe.COR_AMOSTRA | toColor }} 45% , @{{ detalhe.COR_AMOSTRA2 | toColor }} 55%);"></span>
                                                        <span class="descricao">
                                                            @{{ detalhe.PRODUTO_ID }} - @{{ detalhe.PRODUTO_DESCRICAO }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right">@{{ detalhe.QUANTIDADE | number: 4 }} @{{ detalhe.UM }}</td>
                                                    <td class="text-right">@{{ detalhe.QUANTIDADE_PRODUCAO | number: 4 }} @{{ detalhe.UM }}</td>
                                                    <td class="text-right">@{{ detalhe.QUANTIDADE_ALTERN | number: 4 }} @{{ detalhe.UM_ALTERNATIVA }}</td>
                                                    <td class="text-right">@{{ detalhe.QUANTIDADE_ALTERN_PRODUCAO | number: 4 }} @{{ detalhe.UM_ALTERNATIVA }}</td>
                                                </tr>                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	
                <div class="consumo panel panel-default">
                    <div class="panel-heading" role="tab" id="heading@{{ remessa.REMESSA_ID }}-consumo">
                        <a class="accordion" ng-click="$emit('vsRepeatTrigger');"  role="button" data-toggle="collapse" href="#collapse@{{ remessa.REMESSA_ID }}-consumo" aria-controls="collapse@{{ remessa.REMESSA_ID }}-consumo">
                            <span class="descricao">
                                Consumo
                            </span>
                        </a>
                        <div class="dropup acoes">
                            <button title="Ações" class="btn btn-default toggle" data-toggle="dropdown">
                                <span class="fa fa-ellipsis-v"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                              <li class="dropdown-header">Ações do consumo</li>
                              <li><a href="#" ng-click="vm.Excluir('Confirma a exclusão dos consumos selecionados?',remessa,'REMESSA_ID','CONSUMO')">Excluir</a></li>
                            </ul>
                        </div>
                    </div>
                    <div id="collapse@{{ remessa.REMESSA_ID }}-consumo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading@{{ remessa.REMESSA_ID }}-consumo">
                        <div class="panel-body">
                            
                            {{-- Talão Consumo --}}
                            <div class="talao-consumo table-container">
                                <table class="table table-bordered table-header">
                                    <thead>
                                        <tr>
                                            <th class="wid-status"></th>
                                            <th class="wid-status"></th>
                                            <th class="wid-consumo" title="Código do consumo">Id</th>
                                            <th class="wid-produto">Produto</th>
                                            <th class="wid-quantidade text-right" title="Quantidade projetada à consumir na unidade de medida padrão do produto">Qtd. Proj.</th>
                                            <th class="wid-quantidade text-right" title="Quantidade consumida na unidade de medida padrão do produto">Qtd. Cons.</th>
                                            <th class="wid-quantidade text-right" title="Quantidade projetada à consumir na unidade de medida alternativa do produto">Qtd. Proj. Alt.</th>
                                            <th class="wid-quantidade text-right" title="Quantidade consumida na unidade de medida alternativa do produto">Qtd. Cons. Alt.</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="scroll-table">
                                    <table class="table table-striped table-bordered table-hover table-body">
                                        <col class="wid-status"/>
                                        <col class="wid-status"/>
                                        <col class="wid-consumo"/>
                                        <col class="wid-produto"/>                
                                        <col class="wid-quantidade"/>                      
                                        <col class="wid-quantidade"/>                      
                                        <col class="wid-quantidade"/>                      
                                        <col class="wid-quantidade"/>                      
                                        <tbody vs-repeat vs-scroll-parent=".table-container">
                                            <tr ng-repeat="consumo in remessa.CONSUMOS | filter: vm.FiltrarTalaoDetalhe">
                                                <td class="chk" ng-click="vm.selectItemAcao(consumo,'CONSUMO','ID')"><input readonly type="checkbox" ng-checked="vm.selectedItemAcao(consumo,'CONSUMO','ID')"></td>
                                                <td class="t-status consumo-tipo-@{{ consumo.COMPONENTE }} consumo-status-@{{ consumo.STATUS }}"
                                                    title="@{{ consumo.COMPONENTE_DESCRICAO }} @{{ consumo.STATUS_DESCRICAO }}"></td>
                                                <td>@{{ consumo.ID }}</td>
                                                <td class="cor-amostra"
                                                    title="@{{ consumo.PRODUTO_ID }} - @{{ consumo.PRODUTO_DESCRICAO }}">
                                                    <span
                                                        ng-class="{'disabled' : (consumo.COR_AMOSTRA <= 0)}"
                                                        style="background-image: linear-gradient(to right top, @{{ consumo.COR_AMOSTRA | toColor }} 45% , @{{ consumo.COR_AMOSTRA2 | toColor }} 55%);"></span>
                                                    <span class="descricao">
                                                        @{{ consumo.PRODUTO_ID }} - @{{ consumo.PRODUTO_DESCRICAO }}
                                                    </span>
                                                </td>
                                                <td class="text-right">@{{ consumo.QUANTIDADE | number: 4 }} @{{ consumo.UM }}</td>
                                                <td class="text-right">@{{ consumo.QUANTIDADE_CONSUMO | number: 4 }} @{{ consumo.UM }}</td>
                                                <td class="text-right">@{{ consumo.QUANTIDADE_ALTERNATIVA | number: 4 }} @{{ consumo.UM_ALTERNATIVA }}</td>
                                                <td class="text-right">@{{ consumo.QUANTIDADE_ALTERNATIVA_CONSUMO | number: 4 }} @{{ consumo.UM_ALTERNATIVA }}</td>
                                            </tr>                
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>	
            </div>
            
            
            
        </div>
    </div>
</div>