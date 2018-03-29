<fieldset class="detalhe">
    <legend>{{ Lang::get($menu.'.detalhamento') }}</legend>

    <div class="botao-container">

        <button 
            type="button" 
            class="btn btn-sm btn-warning"
            ng-click="vm.TalaoDefeito.openRegistrarProblema()"
            ng-disabled="vm.TalaoProduzir.SELECTED == null || !vm.Acao.check('justificar').status">
            <span class="glyphicon glyphicon-tags"></span>
            Justificar
        </button>    

        <button 
            type="button" 
            class="btn btn-sm btn-warning" 
            ng-disabled="!vm.Acao.check('pausar').status || vm.TalaoDetalhe.SELECTED == null"
            ng-click="vm.TalaoDefeito.registrar()">
            <span class="fa fa-list-ol"></span>
            Registrar Defeitos
        </button>        
        
        @if ( userControl(211) == '1' )
        <button 
            type="button" 
            class="btn btn-sm btn-warning" 
            id="registrar-aproveitamento" 
            title="{{ Lang::get($menu.'.registrar-aproveitamento') }}" 
            data-hotkey="alt+p" 
            data-toggle="modal" 
            data-target="#modal-registrar-aproveitamento" 
            ng-disabled="!vm.Acao.check('pausar').status || vm.TalaoDetalhe.SELECTED == null">
            <span class="glyphicon glyphicon-edit"></span>
            {{ Lang::get($menu.'.registrar-aproveitamento') }}
        </button>
        @endif

        @if ( userControl(203) == '1' )
        <button type="button" class="btn btn-sm btn-success" id="qtd-gravar-todos" ng-click="vm.TalaoDetalhe.gravarTodos(1);" title="{{ Lang::get($menu.'.gravar-todos') }}" data-hotkey="alt+g"  ng-disabled="!vm.Acao.check('pausar').status">
            <span class="glyphicon glyphicon-ok"></span>
            {{ Lang::get($menu.'.gravar-todos') }}
        </button>
        @endif
    </div>
    <div class="table-detalhe">
        <div class="recebe-puxador-detalhe">
            <div class="table-ec">
                <table class="table table-striped table-bordered table-hover table-condensed table-middle table-no-break table-detalhe">
                    <thead>
                        <tr>
                            <th class="t-status" title="Status do Detalhamento do Talão"></th>
                            <th class="wid-talao talao text-center">Talão</th>
                            <!--<th class="wid-produto produto text-center" title="Código do produto">Cód. Prod.</th>-->
                            <th class="cor">Cor</th>
                            <th class="wid-qtd-um qtd-projetada text-right" title="Quantidade Projetada">Qtd. Proj.</th>
                            <th ng-if="vm.TalaoComposicao.DADOS.DETALHE[0].UM_ALTERNATIVA != ''" class="wid-qtd-um qtd-projetada-alternativa text-right" title="Quantidade Alternativa Projetada">Qtd. Alt. Proj.</th>
                            <th class="wid-qtd-um text-right" title="Quantidade de Defeitos">Defeitos</th>
                            <th 
                                class="wid-qtd qtd text-right" 
                                title="Quantiadde Produzida"
                                ng-class="{
                                    'editando' : vm.TalaoDetalhe.QUANTIDADE_ALTERANDO.length > 0
                                }">Qtd. Prod.</th>
                            <th 
                                ng-if="vm.TalaoComposicao.DADOS.DETALHE[0].UM_ALTERNATIVA != ''"
                                class="wid-qtd qtd-alternativa text-right" 
                                title="Quantidade Alternativa Produzida"
                                ng-class="{
                                    'editando' : vm.TalaoDetalhe.QUANTIDADE_ALTERNATIVA_ALTERANDO.length > 0
                                }">Qtd. Alt. Prod.</th>
                            <!--<th class="sobra-tipo text-center" title="Tipo de Sobra">Tip. Sob.</th>-->
                            <th class="wid-qtd-min sobra text-right" title="Sobra de Produção">Sobra</th>
                            <!--<th class="wid-qtd aproveitamento text-right" title="Aproveitamento de Produção Alocado">Aprov. Aloc.</th>-->
                            <th class="wid-qtd-min saldo text-right" title="Quantidade de Saldo à Produzir">Saldo</th>
                            <th ng-if="vm.TalaoComposicao.DADOS.DETALHE[0].UM_ALTERNATIVA != ''" class="wid-qtd-min saldo-alt text-right" title="Quantidade de Saldo Alternativo à Produzir">Saldo Alt.</th>                    
                        </tr>
                    </thead>                        
                    <tbody>
                        <tr ng-repeat="detalhe in vm.TalaoComposicao.DADOS.DETALHE
                            | orderBy : ['ID']"
                            tabindex="0" 
                            ng-focus="vm.TalaoDetalhe.SELECTED != detalhe ? vm.TalaoDetalhe.selectionar(detalhe) : ''"
                            ng-click="vm.TalaoDetalhe.SELECTED != detalhe ? vm.TalaoDetalhe.selectionar(detalhe) : ''"
                            ng-class="{'selected' : vm.TalaoDetalhe.SELECTED == detalhe }"
                            >
                            <td class="t-status text-center status@{{ detalhe.STATUS }}" title="@{{ detalhe.STATUS_DESCRICAO }}"></td>
                            <td class="wid-talao text-center" ttitle="Id do produto: @{{ detalhe.PRODUTO_ID }}">@{{ detalhe.ID }}</td>
                            <!--<td class="wid-produto text-center">@{{ detalhe.PRODUTO_ID }}</td>-->
                            <td class="cor cor-amostra ellipsis" autotile>
                                <span
                                    ng-class="{'disabled' : (detalhe.COR_AMOSTRA <= 0)}"
                                    style="background-image: linear-gradient(to right top, @{{ detalhe.COR_AMOSTRA | toColor }} 45% , @{{ detalhe.COR_AMOSTRA2 | toColor }} 55%);"></span>
                                <span class="descricao ng-binding">
                                    @{{ detalhe.COR_ID }} - @{{ detalhe.COR_DESCRICAO }}
                                </span>

                            </td>
                            <td class="wid-qtd-um um qtd-projetada">@{{ detalhe.QUANTIDADE | number : 4 }} @{{ detalhe.UM }}</td>
                            <td ng-if="vm.TalaoComposicao.DADOS.DETALHE[0].UM_ALTERNATIVA != ''" class="wid-qtd-um um qtd-projetada-alternativa">@{{ detalhe.QUANTIDADE_ALTERN | number : 4 }} @{{ detalhe.UM_ALTERNATIVA }}</td>
                            <td class="wid-qtd-um um">

                                <span
                                    style="float: left"
                                    ng-if="detalhe.DEFEITOS.length > 0"
                                    class="item-popover glyphicon glyphicon-info-sign detalhe-defeito" 
                                    data-toggle="popover" 
                                    data-placement="top" 
                                    title="Defeitos"
                                    data-element-content="#info-defeito-@{{ detalhe.REMESSA_TALAO_DETALHE_ID }}"
                                ></span>
                                <div id="info-defeito-@{{ detalhe.REMESSA_TALAO_DETALHE_ID }}" style="display: none">
                                    <div class="detalhe-defeito-container">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Transação</th>
                                                    <th>Defeito</th>
                                                    <th class="text-right">Qtd.</th>
                                                    <th title="Observação">Obs.</th>
                                                    <th title="Ações" ng-if="vm.Acao.check('pausar').status"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="defeito in detalhe.DEFEITOS | orderBy : ['DEFEITO_TRANSACAO_ID*1']">
                                                    <td>@{{ defeito.DEFEITO_TRANSACAO_ID }}</td>
                                                    <td>@{{ defeito.DEFEITO_ID | lpad : [4,'0'] }} - @{{ defeito.DEFEITO_DESCRICAO }}</td>
                                                    <td class="text-right um">@{{ defeito.QUANTIDADE | number: 4 }} @{{ detalhe.UM }}</td>
                                                    <td>@{{ defeito.OBSERVACAO || "-" }}</td>
                                                    <td ng-if="vm.Acao.check('pausar').status">
                                                        <button 
                                                            ng-click="vm.TalaoComposicao.DADOS.DETALHE.splice(0, 1);"
                                                            type="button" 
                                                            class="btn btn-danger btn-xs defeito-excluir" 
                                                            title="Excluir Defeito" 
                                                            data-item-id="@{{ defeito.DEFEITO_TRANSACAO_ID }}">
                                                            <span class="glyphicon glyphicon-trash"></span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>                                    
                                    </div>
                                </div>

                                @{{ detalhe.QUANTIDADE_DEFEITO | number : 4 }} @{{ detalhe.UM }}
                            </td>

                            {{-- Quantidade Produzida --}}
                            <td 
                                class="wid-qtd um qtd field-um"
                                ng-class="{
                                    'editando' : detalhe.EDITANDO_QUANTIDADE
                                }">

                                <div 
                                    style="display: inline-block; width: calc(100% - 25px);"
                                    ng-if="!detalhe.EDITANDO_QUANTIDADE">
                                    <span class="valor">@{{ detalhe.QUANTIDADE_PRODUCAO | number : 4 }}</span>
                                    <span>@{{ detalhe.UM }}</span>
                                </div>

                                <input
                                    type="number" 
                                    name="quantidade"
                                    class="qtd"
                                    step="0.0001"
                                    min="0" 
                                    valid-max-value
                                    string-to-number 
                                    ng-keydown="vm.TalaoDetalhe.keydownQuantidade(detalhe,$event)"
                                    ng-model="detalhe.M_QUANTIDADE_PRODUCAO" 
                                    ng-value="detalhe.QUANTIDADE_PRODUCAO"
                                    ng-style="{'display': detalhe.EDITANDO_QUANTIDADE ? '' : 'none'}">

                                {{-- Botão da Balança --}}
                                <button 
                                    ng-if="detalhe.UM_ALTERNATIVA != ''" 
                                    ng-disabled="!vm.Acao.check('pausar').status" 
                                    ng-click="" 
                                    tabindex="-1"
                                    type="button" 
                                    class="btn btn-sm btn-warning btn-balanca" 
                                    title="Coletar Peso" 
                                    data-toggle="modal" 
                                    data-target="#modal-registrar-balanca">
                                        <span class="glyphicon glyphicon-scale"></span>
                                </button>

                                {{-- Botão para Editar --}}
                                <button 
                                    ng-if="detalhe.UM_ALTERNATIVA == '' && !detalhe.EDITANDO_QUANTIDADE" 
                                    ng-disabled="!vm.Acao.check('pausar').status" 
                                    ng-click="vm.TalaoDetalhe.alterarQuantidade(detalhe)" 
                                    type="button" 
                                    class="btn btn-sm btn-primary qtd-editar"
                                    tabindex="-1">
                                        <span class="glyphicon glyphicon-edit"></span>
                                </button>

                                {{-- Botão para Gravar --}}
                                <button 
                                    ng-if="detalhe.UM_ALTERNATIVA == '' && detalhe.EDITANDO_QUANTIDADE" 
                                    ng-disabled="!vm.Acao.check('pausar').status" 
                                    ng-click="vm.TalaoDetalhe.gravarQuantidade(detalhe)" 
                                    type="button" 
                                    class="btn btn-sm btn-success qtd-gravar"	 
                                    title="Gravar"
                                    tabindex="-1">
                                        <span class="glyphicon glyphicon-ok"></span>
                                </button>

                                {{-- Botão para Cancelar --}}
                                <button 
                                    ng-if="detalhe.UM_ALTERNATIVA == '' && detalhe.EDITANDO_QUANTIDADE" 
                                    ng-disabled="!vm.Acao.check('pausar').status" 
                                    ng-click="vm.TalaoDetalhe.cancelarQuantidade(detalhe)" 
                                    type="button" 
                                    class="btn btn-sm btn-danger qtd-cancelar" 
                                    title="Cancelar"
                                    tabindex="-1">
                                        <span class="glyphicon glyphicon-ban-circle"></span>
                                </button>

                            </td>

                            {{-- Quantidade Alternativa Produzida --}}
                            <td 
                                ng-if="vm.TalaoComposicao.DADOS.DETALHE[0].UM_ALTERNATIVA != ''"
                                class="wid-qtd um qtd-alternativa field-um"
                                ng-class="{
                                    'editando' : vm.TalaoDetalhe.QUANTIDADE_ALTERNATIVA_ALTERANDO.length > 0
                                }">

                                <div 
                                    style="display: inline-block; width: calc(100% - 25px);"
                                    ng-if="!detalhe.EDITANDO_QUANTIDADE_ALTERNATIVA">
                                    <span class="valor">@{{ detalhe.QUANTIDADE_ALTERN_PRODUCAO | number : 4 }}</span>
                                    <span>@{{ detalhe.UM_ALTERNATIVA }}</span>
                                </div>

                                <input
                                    type="number" 
                                    name="quantidade"
                                    class="qtd"
                                    step="0.0001"
                                    min="0" 
                                    valid-max-value
                                    string-to-number 
                                    ng-model="detalhe.M_QUANTIDADE_ALTERN_PRODUCAO" 
                                    ng-value="detalhe.QUANTIDADE_ALTERN_PRODUCAO"
                                    ng-style="{'display': detalhe.EDITANDO_QUANTIDADE_ALTERNATIVA ? '' : 'none'}">

                                {{-- Botão para Editar --}}
                                <button 
                                    ng-if="!detalhe.EDITANDO_QUANTIDADE_ALTERNATIVA" 
                                    ng-disabled="true" 
                                    ng-click="vm.TalaoDetalhe.EDITANDO_QUANTIDADE_ALTERNATIVA = true; detalhe.EDITANDO_QUANTIDADE_ALTERNATIVA = true" 
                                    type="button" 
                                    class="btn btn-sm btn-primary qtd-editar"
                                    tabindex="-1">
                                        <span class="glyphicon glyphicon-edit"></span>
                                </button>

                                {{-- Botão para Gravar --}}
                                <button 
                                    ng-if="detalhe.EDITANDO_QUANTIDADE_ALTERNATIVA" 
                                    ng-disabled="true" 
                                    ng-click="" 
                                    type="button" 
                                    class="btn btn-sm btn-success qtd-gravar"	 
                                    title="Gravar"
                                    tabindex="-1">
                                        <span class="glyphicon glyphicon-ok"></span>
                                </button>

                                {{-- Botão para Cancelar --}}
                                <button 
                                    ng-if="detalhe.EDITANDO_QUANTIDADE_ALTERNATIVA" 
                                    ng-disabled="true" 
                                    ng-click="vm.TalaoDetalhe.EDITANDO_QUANTIDADE_ALTERNATIVA = false; detalhe.EDITANDO_QUANTIDADE_ALTERNATIVA = false; detalhe.M_QUANTIDADE_ALTERN_PRODUCAO = detalhe.QUANTIDADE_ALTERN_PRODUCAO" 
                                    type="button" 
                                    class="btn btn-sm btn-danger qtd-cancelar" 
                                    title="Cancelar">
                                        <span class="glyphicon glyphicon-ban-circle"></span>
                                </button>

                            </td>
<!--                                <td class="text-center sobra-tipo sobra-tipo-@{{detalhe.SOBRA_TIPO}}" title="@{{ detalhe.SOBRA_TIPO_DESCRICAO }}">
                                <span>@{{ detalhe.SOBRA_TIPO }}</span>
                            </td>-->
                            <td class="wid-qtd-min um sobra-prod">
                                @{{ detalhe.QUANTIDADE_SOBRA | number : 4 }}
                                <span>@{{ detalhe.UM }}</span>
                            </td>                        
                            <!--<td class="wid-qtd text-right aproveitamento">@{{ detalhe.APROVEITAMENTO_ALOCADO | number : 4 }}</td>-->

                            <td class="wid-qtd-min um saldo">
                                @{{ detalhe.SALDO_A_PRODUZIR | number : 4 }}
                                <span>@{{ detalhe.UM }}</span>
                            </td>
                            <td
                                ng-if="vm.TalaoComposicao.DADOS.DETALHE[0].UM_ALTERNATIVA != ''"
                                class="wid-qtd-min um saldo-altern">
                                @{{ detalhe.SALDO_A_PRODUZIR_ALTERN | number : 4 }}
                                <span>@{{ detalhe.UM_ALTERNATIVA }}</span>
                            </td>

                            <input type="hidden" name="_REMESSA_ID"							class="_remessa-id"							value="@{{ detalhe.REMESSA_ID }}" />
                            <input type="hidden" name="_REMESSA_TALAO_ID"					class="_remessa-talao-id"					value="@{{ detalhe.REMESSA_TALAO_ID }}" />
                            <input type="hidden" name="_talao_id"							class="_talao-id"							value="@{{ detalhe.ID }}" />
                            <input type="hidden" name="_produto_id"							class="_produto-id"							value="@{{ detalhe.PRODUTO_ID }}" />
                            <input type="hidden" name="_quantidade"							class="_quantidade"							value="@{{ detalhe.QUANTIDADE_PRODUCAO }}" />
                            <input type="hidden" name="_quantidade_alternativa"				class="_quantidade-alternativa"				value="@{{ detalhe.QUANTIDADE_ALTERN_PRODUCAO }}" />
                            <input type="hidden" name="_quantidade_projetada"				class="_quantidade-projetada"				value="@{{ detalhe.QUANTIDADE }}"/>
                            <input type="hidden" name="_quantidade_projetada_altern"		class="_quantidade-projetada-altern"		value="@{{ detalhe.QUANTIDADE_ALTERN }}"/>
                            <input type="hidden" name="_quantidade_projetada_um"			class="_quantidade-projetada-um"			value="@{{ detalhe.UM }}"/>
                            <input type="hidden" name="_quantidade_aproveitamento"			class="_quantidade-aproveitamento"			value="@{{ detalhe.APROVEITAMENTO_ALOCADO }}"/>
                            <input type="hidden" name="_quantidade_aproveitamento_altern"	class="_quantidade-aproveitamento-altern"	value="@{{ detalhe.APROVEITAMENTO_ALOCADO_ALTERN }}"/>
                            <input type="hidden" name="_saldo_produzir"						class="_saldo-produzir"						value="@{{ detalhe.SALDO_A_PRODUZIR }}"/>
                            <input type="hidden" name="_saldo_produzir_altern"				class="_saldo-produzir-altern"				value="@{{ detalhe.SALDO_A_PRODUZIR_ALTERN }}"/>
                            <input type="hidden" name="_um"									class="_um"									value="@{{ detalhe.UM }}"/>
                            <input type="hidden" name="_um_alternativa"						class="_um-alternativa"						value="@{{ detalhe.UM_ALTERNATIVA }}"/>
                            <input type="hidden" name="_tolerancia_max"						class="_tolerancia-max"						value="@{{ detalhe.TOLERANCIAM }}"/>
                            <input type="hidden" name="_tolerancia_min"						class="_tolerancia-min"                     value="@{{ detalhe.TOLERANCIAN }}"/>
                            <input type="hidden" name="_tolerancia_tip"						class="_tolerancia-tip"                     value="@{{ detalhe.TOLERANCIA_TIPO }}"/>
                            <input type="hidden" name="_sobra_tipo"						    class="_sobra-tipo"                         value="@{{ detalhe.SOBRA_TIPO }}"/>
                            <input type="hidden" name="_peca_conjunto"					    class="_peca-conjunto"                      value="@{{ detalhe.PECA_CONJUNTO }}"/>

                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</fieldset>