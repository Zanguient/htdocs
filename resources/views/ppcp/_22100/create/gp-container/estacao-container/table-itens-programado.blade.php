<div class="itens-programado-container">
    <div class="table-container modelos">
        <table class="table table-bordered table-header">
            <thead>
                <tr>
                    <th class="wid-linha">Linha</th>
                    <th class="wid-modelo">Modelo</th>
                    <th class="wid-cor">Cor</th>
                    <th class="wid-tamanho">Tam.</th>
                    <th class="wid-quantidade text-right" title="Quantidade do modelo programado na linha">Qtd.</th>
                    <th class="wid-ferramenta">Ferramenta</th>
                    <th class="wid-acoes text-center">Ações</th>
                    <th class="wid-check"></th>
                </tr>
            </thead>
        </table>
        <div class="scroll-table">
            <table class="table table-striped table-bordered table-hover table-body">
                <col class="wid-linha"/>
                <col class="wid-modelo"/>
                <col class="wid-cor"/>
                <col class="wid-tamanho"/>
                <col class="wid-quantidade"/>
                <col class="wid-ferramenta"/>
                <col class="wid-acoes"/>                        
                <col class="wid-check"/>                        
                <tbody>
                    <tr ng-repeat="item in estacao.itens_programados"
                        ng-class="{'talao-programado' : item.DATAHORA_INICIO != undefined }"
                        >
                        <td title="@{{ item.LINHA_ID }} - @{{ item.LINHA_DESCRICAO }}">@{{ item.LINHA_DESCRICAO }}</td>
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
                        <td>@{{ item.TAMANHO_DESCRICAO }}</td>
                        <td class="wid-quantidade text-right">@{{ item.QUANTIDADE_PROGRAMADA || item.QUANTIDADE | number: 0 }}</td>
                        <td class="wid-ferramenta" title="@{{ item.FERRAMENTA_ID }} - @{{ item.FERRAMENTA_DESCRICAO }}">@{{ item.FERRAMENTA_ID }} - @{{ item.FERRAMENTA_DESCRICAO }}</td>
                        <td class="wid-acoes">
                            <button ng-if="!item.DATAHORA_INICIO" class="btn btn-default btn-xs" ng-click="vm.Item.Excluir(estacao,item)">
                                <span class="fa fa-trash"></span>
                                Excluir</button>
                        </td>
                        <td class="wid-check">
                            <span class="glyphicon glyphicon-info-sign operacao-descricao"
                            data-toggle="popover" 
                            data-placement="left" 
                            title="Informações"
                            data-element-content="#item-programado-@{{ item.GP_ID }}-@{{ item.ESTACAO }}-@{{ $index }}"
                            on-finish-render="bs-init"
                            ng-class="{
                                'info-danger' : !(item.TEMPO_PAR > 0) || ( item.checked && !item.FERRAMENTA_DISPONIVEL )
                            }"></span>
                            <div id="item-programado-@{{ item.GP_ID }}-@{{ item.ESTACAO }}-@{{ $index }}" style="display: none">
                                <div ng-if="!item.DATAHORA_INICIO">
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
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="agrup in item.AGRUPAMENTO">
                                                    <td class="text-center">@{{ agrup.SEMANA | lpad: [2,'0'] }}</td>
                                                    <td class="text-center">@{{ agrup.DATA_COMPLETA }}</td>
                                                    <td class="text-center">@{{ agrup.TABELA_ID | lpad: [6,'0'] }}</td>
                                                    <td>@{{ agrup.CLIENTE_NOMEFANTASIA }}</td>
                                                    <td class="text-right">@{{ agrup.QUANTIDADE_TOTAL | number: 0 }}</td>
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
                                    <fieldset style="padding-bottom: 10px">
                                        <legend>Tempo</legend>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-right" title="Minuto inicial">Min. Ini.</th>
                                                    <th class="text-right" title="Minuto final">Min. Fin.</th>
                                                    <th class="text-right" title="Tempo operacional">Tp. Oper.</th>
                                                    <th class="text-right" title="Tempo de setup de troca da ferramenta">Set. Fet.</th>
                                                    <th class="text-right" title="Tempo de setup de aquecimento da ferramenta">Fer. Aq.</th>
                                                    <th class="text-right" title="Tempo de setup de troca de cor">Set. Cor</th>
                                                    <th class="text-right" title="Tempo de setup de aprovação de cor">Cor Ap.</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="text-right">@{{ item.TEMPO_INICIO }}'</td>
                                                    <td class="text-right">@{{ item.TEMPO_FIM }}'</td>
                                                    <td class="text-right">@{{ item.TEMPO_ITEM }}'</td>
                                                    <td class="text-right">@{{ (item.HABILITA_FERRAMENTA_SETUP) ? item.TEMPO_FERRAMENTA_SETUP : 0 }}'</td>
                                                    <td class="text-right">@{{ (item.HABILITA_FERRAMENTA_SETUP_AQUECIMENTO) ? item.TEMPO_FERRAMENTA_SETUP_AQUECIMENTO : 0 }}'</td>
                                                    <td class="text-right">@{{ (item.HABILITA_COR_SETUP) ? item.COR_TEMPO_SETUP : 0 }}'</td>
                                                    <td class="text-right">@{{ (item.HABILITA_COR_SETUP_APROVACAO) ? item.COR_TEMPO_SETUP_APROVACAO : 0 }}'</td>
                                                </tr>
                                            </tbody>
                                        </table>   
                                    </fieldset>
                                </div>
                                <div ng-if="item.DATAHORA_INICIO">
                                    <fieldset>
                                        <legend>Dt/Hr Prevista para o Talão</legend>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Início</th>
                                                    <th>Fim</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>@{{ item.DATAHORA_INICIO | toDate | date:'dd/MM/yy HH:mm' }}</td>
                                                    <td>@{{ item.DATAHORA_FIM | toDate | date:'dd/MM/yy HH:mm' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>   
                                    </fieldset>
                                </div>
                            </div>
                        </td>
                    </tr>                
                </tbody>
            </table>
        </div>
    </div>
</div>