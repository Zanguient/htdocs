<div class="estacao-container">
    <div class="panel-group accordion@{{ gp_up.GP_ID }}" id="accordion@{{ gp_up.GP_ID }}" role="tablist" aria-multiselectable="true">
        <div
            ng-repeat="estacao in gp_up.COLLECTION | orderBy:'ESTACAO_DESCRICAO'" 
            ng-class="(gp_up.GP_ID == vm.Gp.selected_id && estacao.ESTACAO == vm.Estacao.selected_id) ? 'selected' : ''" 
            class="panel panel-default">
            <div
                class="panel-heading" 
                role="tab" 
                id="heading@{{ gp_up.GP_ID }}-@{{ $index }}">
                <input
                    ng-model="vm.Estacao.radiobox"
                    ng-change="vm.Estacao.Select(estacao)"
                    value="@{{ gp_up.GP_ID }}-@{{ $index }}" 
                    type="radio" 
                    name="estacao-radio" style="display: none"
                />
                <div class="check"  ng-click="vm.Estacao.Check(estacao)">
                    <i class="check fa" ng-class="estacao.checked ? 'fa-check-square-o' : 'fa-square-o'"></i>
                </div>
                <a ng-click="vm.Estacao.Select(estacao)" class="accordion" role="button" data-toggle="collapse" href="#collapse@{{ gp_up.GP_ID }}-@{{ $index }}" aria-controls="collapse@{{ gp_up.GP_ID }}-@{{ $index }}">
                    <span class="descricao">
                        @{{ estacao.ESTACAO_DESCRICAO }}
                    </span>
                    <span class="pares-programados">
                        @{{ estacao.PARES_PROGRAMADOS | number : 0 }}
                    </span>
                    <span class="wid-medidas text-center"
                        ng-class="{'bg-info-danger' : ( estacao.checked && !estacao.FERRAMENTA_DISPONIVEL  )} ">
                        @{{ estacao.ESTACAO_LARGURA | number: 4 }} x @{{ estacao.ESTACAO_COMPRIMENTO | number: 4 }} x @{{ estacao.ESTACAO_ALTURA | number: 4 }} M
                    </span>
                    <span class="wid-setup text-center">
                        @{{ estacao.QUANTIDADE_FERRAMENTAS | number: 0 }}
                    </span>
                    <div class="tempo-disponivel">
                        <div class="tempo-barra-progresso-container" title="@{{ estacao.MINUTOS_PROGRAMADOS | number : 2 }}' / @{{ estacao.PERCENTUAL_UTILIZADO_ASC | number : 1 }}%">
                            <span class="tempo-barra-progresso" style="width: @{{ estacao.PERCENTUAL_UTILIZADO_DESC }}%"></span>
                            <span class="tempo-alocado">@{{ estacao.MINUTOS_PROGRAMADOS | number : 2 }}' / @{{ estacao.PERCENTUAL_UTILIZADO_ASC | number : 1 }}%</span>
                        </div>
                    </div>
                    <span class="minutos-total">
                    </span>
                </a>
                <div class="input-tempo">
                    <input
                        type="number" 
                        min="0" 
                        string-to-number 
                        ng-model="estacao.MINUTOS">
                </div>
                <div class="check tempo"  ng-click="estacao.check_tempo_utilizado = !estacao.check_tempo_utilizado; estacao.MINUTOS_PROGRAMADOS = parseFloat(estacao.MINUTOS_PROGRAMADOS) - parseFloat(estacao.MINUTOS_PROGRAMADOS_ORIGNAL)">
                    <i class="check fa" ng-class="estacao.check_tempo_utilizado ? 'fa-check-square-o' : 'fa-square-o'"></i>
                </div>
            </div>
            <div id="collapse@{{ gp_up.GP_ID }}-@{{ $index }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading@{{ gp_up.GP_ID }}-@{{ $index }}">
                <div class="panel-body">
                    @include('ppcp._22100.create.gp-container.estacao-container.table-itens-programado')
                </div>
            </div>
        </div>	
    </div>
</div>