<fieldset class="ficha">
    <legend>Ficha de Produção</legend>
    <div class="botao-container">       
    </div>
    <div class="table-ficha">
        <div class="recebe-puxador-ficha">
            <div class="table-ec">
                <table class="table table-striped table-bordered table-hover table-condensed table-middle table-ficha">
                    <thead>
                        <tr>
                            <th class="wid-descricao">Descrição</th>
                            <th class="wid-padrao">Qtd. Padrão</th>
                            <th class="wid-talao">Qtd. Talão</th>
                        </tr>
                    </thead>                        
                    <tbody>
                        <tr ng-repeat="ficha in vm.TalaoFicha.FILTERED = (vm.TalaoComposicao.DADOS.FICHA | orderBy : ['TIPO_DESCRICAO*1'])"
                            tabindex="-1" 
                            ng-focus="vm.TalaoFicha.SELECTED != ficha ? vm.TalaoFicha.selectionar(ficha) : ''"
                            ng-click="vm.TalaoFicha.SELECTED != ficha ? vm.TalaoFicha.selectionar(ficha) : ''"
                            ng-class="{'selected' : vm.TalaoFicha.SELECTED == ficha }"
                            >
                            <td class="wid-descricao">@{{ ficha.TIPO_DESCRICAO }}</td>
                            <td class="wid-padrao text-right">@{{ ficha.QUANTIDADE_PADRAO | number : 4 }}</td>
                            <td class="wid-talao text-right">
                                <div ng-if="!vm.Acao.check('pausar').status">
                                    @{{ ficha.QUANTIDADE | number : 4 }}
                                </div>
                                <input
                                    ng-attr-ficha="@{{ ficha.TIPO_ID }}"
                                    type="number" 
                                    min="0" 
                                    valid-max-value
                                    string-to-number 
                                    data-old-value="@{{ ficha.QUANTIDADE }}"
                                    ng-if="vm.Acao.check('pausar').status"
                                    ng-model="ficha.QUANTIDADE" 
                                    ng-keydown="vm.TalaoFicha.keydown(ficha,$event)"
                                    ng-focus="vm.TalaoFicha.selectionar(ficha)">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</fieldset>