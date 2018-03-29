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
                <li ng-if="{{ $permissaoMenu->ALTERAR }}">
                    <a href data-toggle="modal" data-target="#modal-alterar-consumo">Alterar Consumo</a>
                </li>
                <li ng-if="{{ $permissaoMenu->EXCLUIR }}">
                    <a href ng-click="vm.Acao('remover','Confirma a exclusão dos consumos selecionados?',remessa,'REMESSA_ID','CONSUMO')">Excluir</a>
                </li>
            </ul>
        </div>
    </div>
    <div id="collapse@{{ remessa.REMESSA_ID }}-consumo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading@{{ remessa.REMESSA_ID }}-consumo">
        <div class="panel-body">

            {{-- Talão Consumo --}}
            <div class="recebe-puxador-comum">
                <div class="talao-consumo table-container">
                    <table class="table table-bordered table-header">
                        <thead>
                            <tr>
                                <th class="wid-status"></th>
                                <th class="wid-status"></th>
                                <th class="wid-consumo" title="Código do consumo">Id</th>
                                <th class="wid-produto">Produto</th>
                                <th class="wid-tamanho">Tam.</th>
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
                            <col class="wid-tamanho" />                
                            <col class="wid-quantidade"/>                      
                            <col class="wid-quantidade"/>                      
                            <col class="wid-quantidade"/>                      
                            <col class="wid-quantidade"/>                      
                            <tbody vs-repeat vs-scroll-parent=".table-container">
                                <tr ng-repeat="consumo in remessa.CONSUMOS | filter: vm.FiltrarTalaoDetalhe">
                                    <td class="chk" ng-click="vm.selectItemAcao(consumo,'CONSUMO','ID')"><input readonly type="checkbox" ng-checked="vm.selectedItemAcao(consumo,'CONSUMO','ID')"></td>
                                    <td class="t-status consumo-tipo-@{{ consumo.COMPONENTE }} consumo-status-@{{ consumo.STATUS }}"
                                        ttitle="@{{ consumo.COMPONENTE_DESCRICAO }} @{{ consumo.STATUS_DESCRICAO }}<br/>@{{ consumo.VINCULOS }}"></td>
                                    <td>@{{ consumo.ID }}</td>
                                    <td class="cor-amostra"
                                        title="@{{ consumo.PRODUTO_ID }} - @{{ consumo.PRODUTO_DESCRICAO }}">
                                        <span
                                            ng-class="{'disabled' : (consumo.COR_AMOSTRA <= 0)}"
                                            style="background-image: linear-gradient(to right top, @{{ consumo.COR_AMOSTRA | toColor }} 45% , @{{ consumo.COR_AMOSTRA2 | toColor }} 55%);">    
                                        </span>
                                        <span class="descricao">
                                            <a title="Clique aqui para consultar o estoque deste produto" href="{{ url('/_15060?PRODUTO_ID=') }}@{{ consumo.PRODUTO_ID }}&LOCALIZACAO_ID=@{{ consumo.COMPONENTE == '1' ? '14' : consumo.LOCALIZACAO_ID }}" target="_blank">@{{ consumo.PRODUTO_ID }}</a> - 
                                            @{{ consumo.PRODUTO_DESCRICAO }}
                                        </span>
                                        <span
                                            style="float:right"
                                            ng-if="consumo.ALOCACOES.length > 0"
                                            class="item-popover glyphicon glyphicon-alert alocado-show" 
                                            data-toggle="popover" 
                                            data-placement="top" 
                                            title="Itens Alocados"
                                            data-element-content="#info-alocados-@{{ consumo.ID }}"
                                        ></span>
                                        <div id="info-alocados-@{{ consumo.ID }}" style="display: none">
                                            <div class="alocado-content">
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Identificação</th>
                                                            <th title="Localização de Estoque da Peça">Localização</th>
                                                            <th class="text-right">Qtd.</th>
                                                            <th title="Observações">Obs.</th>
                                                            <th>Ações</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="alocado in consumo.ALOCACOES">
                                                            <td style="padding-top: 2px; padding-bottom: 2px; vertical-align: middle;">@{{ alocado.TIPO }} / @{{ alocado.TABELA_ID || "-" | lpad : [8,'0'] }}</td>
                                                            <td style="padding-top: 2px; padding-bottom: 2px; vertical-align: middle;">@{{ alocado.LOCALIZACAO_ID }} - @{{ alocado.LOCALIZACAO_DESCRICAO }}</td>
                                                            <td style="padding-top: 2px; padding-bottom: 2px; vertical-align: middle;" class="text-right um">@{{ alocado.QUANTIDADE | number: 4 }} @{{ alocado.UM }} @{{ alocado.QUANTIDADE_ALTERNATIVA > 0 ? '/ ' : '' }}@{{ alocado.QUANTIDADE_ALTERNATIVA > 0 ? alocado.QUANTIDADE_ALTERNATIVA : null | number : 2 }}@{{ alocado.QUANTIDADE_ALTERNATIVA > 0 ? ' ' + alocado.UM_ALTERNATIVA + ' ' : '' }}</td>
                                                            <td style="padding-top: 2px; padding-bottom: 2px; vertical-align: middle;">@{{ alocado.OB == '' || alocado.OB == 0  ? '' : 'OB: ' + alocado.OB }}</td>
                                                            <td style="padding-top: 2px; padding-bottom: 2px; vertical-align: middle; text-align: center" class="acoes">
                                                                <button 
                                                                    type="button" 
                                                                    class="btn btn-danger btn-xs alocado-excluir" 
                                                                    title="Excluir item alocado" 
                                                                    data-talao-vinculo-id="@{{ alocado.ID }}" 
                                                                    ng-if="alocado.STATUS == 0"
                                                                    ng-disabled="!vm.Acao.check('pausar').status">
                                                                    <span class="glyphicon glyphicon-trash"></span>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>        
                                            </div>
                                        </div>     
                                    </td>
                                    <td class="wid-tamanho" title="Id tam.: @{{ consumo.TAMANHO }}">@{{ consumo.TAMANHO_DESCRICAO }}</td>
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