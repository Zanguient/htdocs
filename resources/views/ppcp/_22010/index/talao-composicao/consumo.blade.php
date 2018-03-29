<fieldset class="materia-prima">
		
	<legend>Consumo</legend>
	
	<div class="botao-container">
		@if ( userControl(209) )
		<button 
            type="button" 
            id="registrar-materia-prima"	
            class="btn btn-sm btn-warning" 
            data-hotkey="Alt+M" 
            data-toggle="modal" 
            data-target="#modal-registrar-materia" 
            ng-disabled="!vm.Acao.check('pausar').status || vm.TalaoConsumo.SELECTED.COMPONENTE != '0'">
			<span class="glyphicon glyphicon-edit"></span>
			Registrar Matéria-prima
		</button>
		@endif
		@if ( userControl(210) )
		<button 
            type="button" 
            id="registrar-componente"
            class="btn btn-sm btn-warning"
            data-hotkey="Alt+C"
            data-toggle="modal"
            data-target="#modal-registrar-componente"
            ng-disabled="!vm.Acao.check('pausar').status">
			<span class="glyphicon glyphicon-edit"></span>
			Registrar Componente
		</button>
		@endif
	</div>
	
<div class="recebe-puxador-consumo">
    <div class="table-ec">
        <table class="table table-striped table-bordered table-hover table-condensed table-middle table-no-break table-consumo">
            <thead>
                <tr>
                    <th class="t-status"></th>
                    <th class="id-consumo" title="Id do Consumo">Id. Cons.</th>
                    <th class="produto">Produto</th>
                    <th class="tamanho text-right">Tam.</th>
                    <th class="wid-qtd-um qtd-total text-right" title="Quantidade projetada à consumir na unidade de medida padrão do produto.">Qtd.</th>
                    <th class="wid-qtd text-right qtd-alocada" title="Quantidade alocada para consumir na unidade de medida padrão do produto.">Qtd. Aloc.</th>
                    <th class="wid-qtd-um qtd-alternativa text-right" title="Quantidade projetada à consumir na unidade de medida alternativa do produto.">Qtd. Alt.</th>
                    <th class="wid-qtd text-right qtd-alternativa-aloc" title="Quantidade alocada para consumir na unidade de medida alternativa do produto.">Qtd. Alt. Aloc.</th>
                    <th class="wid-qtd-min sobra text-right" title="Sobra de matéria-prima na unidade de medida padrão do produto">Sobra</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="consumo in vm.TalaoComposicao.DADOS.CONSUMO | orderBy : ['COMPONENTE*1','FAMILIA_ID', 'CONSUMO_ID']"
                    ng-focus="vm.TalaoConsumo.SELECTED != consumo ? vm.TalaoConsumo.selectionar(consumo) : ''"
                    ng-click="vm.TalaoConsumo.SELECTED != consumo ? vm.TalaoConsumo.selectionar(consumo) : ''"
                    ng-class="{'selected' : vm.TalaoConsumo.SELECTED == consumo }"
                    tabindex="0" data-componente="@{{ consumo.COMPONENTE }}" consumo-id="@{{ consumo.CONSUMO_ID }}"
                    >
                    <td class="t-status @{{ consumo.COMPONENTE == '1' ? 'status-componente-reduzido-'+consumo.COMPONENTE_STATUS : 'status-materia-prima-'+consumo.STATUS }}" ttitle="@{{ consumo.STATUS_DESCRICAO }}"></td>
                    <td class="id-consumo">@{{ consumo.CONSUMO_ID }}</td>
                    <td class="produto ellipsis" title="@{{ consumo.PRODUTO_ID }} - @{{ consumo.PRODUTO_DESCRICAO }}">
                        <a title="Clique aqui para consultar o estoque deste produto" href="{{ url('/_15060?PRODUTO_ID=') }}@{{ consumo.PRODUTO_ID }}&LOCALIZACAO_ID=@{{ consumo.LOCALIZACAO_ID }}" target="_blank">@{{ consumo.PRODUTO_ID }}</a> - 
                        @{{ consumo.PRODUTO_DESCRICAO }}
                        <span
                            ng-if="consumo.QUANTIDADE_ALOCADA > 0"
                            class="item-popover glyphicon glyphicon-alert alocado-show" 
                            data-toggle="popover" 
                            data-placement="top" 
                            title="Itens Alocados"
                            data-element-content="#info-alocados-@{{ consumo.CONSUMO_ID }}"
                        ></span>
                        <div id="info-alocados-@{{ consumo.CONSUMO_ID }}" style="display: none">
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
                    <td class="text-right tamanho"	>@{{ consumo.TAMANHO_DESCRICAO }}</td>
                    <td class="wid-qtd-um um qtd-total">

                        <span>
                            @{{ consumo.QUANTIDADE | number: 4 }} @{{ consumo.UM }}
                        </span>
                        <span
                            ng-if="consumo.PECAS_DISPONIVEIS.length > 0 && consumo.STATUS_MATERIA_PRIMA > 0"
                            class="glyphicon glyphicon-info-sign pecas-disponiveis" 
                            data-toggle="popover" 
                            data-placement="top" 
                            title="Peças Disponíveis"
                            style="    top: 2px;"
                            data-element-content="#info-pecas-@{{ consumo.PECAS_DISPONIVEIS.length > 0 ? consumo.CONSUMO_ID : null }}"
                        ></span>
                        <div id="info-pecas-@{{ consumo.CONSUMO_ID }}" style="display: none">
                            <div class="pecas-disponiveis-container">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Identificação</th>
                                            <th>Remessa</th>
                                            <th>Talão</th>
                                            <th>Talão Det.</th>
                                            <th class="text-center" title="Endereçamento">Endereç.</th>
                                            <th title="Observações">Obs.</th>
                                            <th class="text-right">Qtd.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="peca in consumo.PECAS_DISPONIVEIS | orderBy : ['PRODUTO_ID', 'SALDO*1']">
                                            <td>@{{ peca.REFERENCIA_TIPO }} / @{{ peca.REFERENCIA_ID || "-" | lpad : [8,'0'] }}</td>
                                            <td>@{{ peca.REMESSA || "-" }}</td>
                                            <td>@{{ peca.REMESSA_TALAO_ID || "-" }}</td>
                                            <td>@{{ peca.REMESSA_TALAO_DETALHE_ID || "-" }}</td>
                                            <td class="text-center">@{{ peca.ENDERECAMENTO || "-" }}</td>
                                            <td>@{{ peca.OBSERVACAO || "-" }}</td>
                                            <td class="text-right um">@{{ peca.SALDO | number: 4 }} @{{ consumo.UM }}</td>
                                        </tr>
                                    </tbody>
                                </table>                                    
                            </div>                                
                        </div>   
                    </td>
                    <td class="wid-qtd um qtd-alocada">
                        <span class="valor">@{{ consumo.QUANTIDADE_ALOCADA | number: 4 }}</span>
                        <span class="um"> @{{ consumo.UM }}</span>

                        <input
                            type="number" 
                            name="quantidade_alocada"
                            class="qtd-alocada"
                            step="0.0001"
                            min="0" 
                            valid-max-value
                            string-to-number 
                            ng-keydown="vm.TalaoConsumo.keydownQuantidade(consumo,$event)"
                            ng-model="consumo.M_QUANTIDADE" 
                            ng-value="consumo.QUANTIDADE_ALOCADA"
                            ng-style="{'display': consumo.EDITANDO_QUANTIDADE ? '' : 'none'}">                            


                        <!--<button type="button" class="btn btn-sm btn-primary qtd-editar"	 title="Editar"	 disabled><span class="glyphicon glyphicon-edit"></span></button>-->
                        <!--<button type="button" class="btn btn-sm btn-success qtd-gravar"	 title="Gravar"	 ><span class="glyphicon glyphicon-ok"></span></button>;-->
                        <!--<button type="button" class="btn btn-sm btn-danger qtd-cancelar" title="Cancelar"><span class="glyphicon glyphicon-ban-circle"></span></button>-->
                    </td>
                    <td class="wid-qtd-um um qtd-alternativa">
                        <span ng-if="consumo.UM_ALTERNATIVA != ''">@{{ consumo.QUANTIDADE_ALTERNATIVA | number: 4 }}</span>
                        <span>@{{ consumo.UM_ALTERNATIVA }}</span>
                    </td>
                    <td class="wid-qtd um qtd-alternativa-aloc">
                        <span ng-if="consumo.UM_ALTERNATIVA != ''" class="valor">@{{ consumo.QUANTIDADE_ALTERNATIVA_ALOCADA | number: 4 }}</span>
                        <span class="um">@{{ consumo.UM_ALTERNATIVA }}</span>

                        <input
                            type="number" 
                            name="quantidade_alternativa_aloc"
                            class="qtd-alternativa-aloc"
                            step="0.0001"
                            min="0" 
                            valid-max-value
                            string-to-number 
                            ng-keydown="vm.TalaoConsumo.keydownQuantidadeAlernativa(consumo,$event)"
                            ng-model="consumo.M_QUANTIDADE_ALTERNATIVA" 
                            ng-value="consumo.QUANTIDADE_ALTERNATIVA_ALOCADA"
                            ng-style="{'display': consumo.EDITANDO_QUANTIDADE_ALTERNATIVA ? '' : 'none'}">                                


                        <!--<button type="button" class="btn btn-sm btn-primary qtd-editar"	 title="Editar"	 disabled><span class="glyphicon glyphicon-edit"></span></button>-->
<!--                            <button type="button" class="btn btn-sm btn-success qtd-gravar"	 title="Gravar"	 ><span class="glyphicon glyphicon-ok"></span></button>
                        <button type="button" class="btn btn-sm btn-danger qtd-cancelar" title="Cancelar"><span class="glyphicon glyphicon-ban-circle"></span></button>-->
                    </td>
                    <td class="wid-qtd-min sobra um">
                        @{{ consumo.QUANTIDADE_SOBRA | number: 4 }}
                        <span class="um"> @{{ consumo.UM }}</span>
                    </td>

                    <input type="hidden" name="_sobra_material" class="_sobra-material" value="@{{ consumo.QUANTIDADE_SOBRA }}" />						
                    <input type="hidden" name="_produto_id"	class="_produto-id"	value="@{{ consumo.PRODUTO_ID }}"	/>
                    <input type="hidden" name="_talao_detalhe_id"	class="_talao-detalhe-id"	value="@{{ consumo.REMESSA_TALAO_DETALHE_ID }}"	/>
                    <input type="hidden" name="_consumo_id"	class="_consumo-id"	value="@{{ consumo.CONSUMO_ID }}"	/>
                    <input type="hidden" class="_tamanho" value="@{{ consumo.TAMANHO_DESCRICAO }}" />
                    <input type="hidden" class="_tamanho_id" value="@{{ consumo.TAMANHO }}" />
                    <input type="hidden" class="_quantidade-total" value="@{{ consumo.QUANTIDADE }}" />
                    <input type="hidden" name="_quantidade-alocada" class="_quantidade-alocada" value="@{{ consumo.QUANTIDADE_ALOCADA }}" />
                    <input type="hidden" name="_quantidade-alternativa-aloc" class="_quantidade-alternativa-aloc" value="@{{ consumo.QUANTIDADE_ALTERNATIVA_ALOCADA }}" />
                </tr>
            </tbody>
        </table>
    </div>
</div>
	
	<ul class="legenda status-materia-prima">
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">Disponível</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">Indisponível</div>
		</li>
	</ul>
	
</fieldset>