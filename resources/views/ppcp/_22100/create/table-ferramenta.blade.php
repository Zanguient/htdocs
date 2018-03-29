<div class="container-ferramenta">
    <fieldset>
        <legend>Ferramentas Disponíveis</legend>
        <div class="recebe-puxador-ferramenta">
            <div class="table-container table-ferramenta">

                <table class="table table-bordered table-header">
                    <thead>
                        <tr>
                            <th class="wid-ferramenta">Ferramenta</th>
                            <th class="wid-serie text-right">Série</th>
                            <th class="wid-data text-center">Data</th>
                            <th class="wid-matriz">Matriz</th>
                            <th class="wid-medidas text-center" title="Largura x Comprimento x Altura">Larg. x Comp. x Alt.</th>
                            <th class="wid-escala">Escala de Tempo Alocado</th>
                        </tr>
                    </thead>
                </table>
                <div class="scroll-table">
                    <table class="table table-striped table-bordered table-hover table-body">
                        <col class="wid-ferramenta"/>
                        <col class="wid-serie"/>
                        <col class="wid-data"/>
                        <col class="wid-matriz"/>
                        <col class="wid-medidas"/>
                        <col class="wid-escala"/>
                        <tbody>
                            <tr
                                ng-repeat="item in vm.Linha.selected.FERRAMENTAS
                               "
                                data-values="@{{ vm.Ferramenta.Utilizado(item,true) }}"
                                >
                                <td class="" title="@{{ item.ID }} - @{{ item.DESCRICAO }}"> @{{ item.ID }} - @{{ item.DESCRICAO }}</td>
                                <td class="text-right">@{{ item.SERIE }}</td>
                                <td class="text-center">@{{ item.DATA | toDate | date:'dd/MM/yyyy'  }}</td>
                                <td title="@{{ item.MATRIZ_ID }} - @{{ item.MATRIZ_DESCRICAO }}">@{{ item.MATRIZ_ID }} - @{{ item.MATRIZ_DESCRICAO }}</td>
                                <td class="text-center">@{{ item.LARGURA | number: 4 }} x @{{ item.COMPRIMENTO | number: 4 }} x @{{ item.ALTURA | number: 4 }} M</td>
                                <!--<td class=""  title="@{{ item.OBSERVACAO }}">@{{ item.OBSERVACAO }}</td>-->
                                <td class="wid-escala">
                                    <b 
                                        ng-if="item.ALOCACAO_REMESSA != null"
                                        data-toggle="popover" 
                                        data-placement="top" 
                                        data-element-content="#ferramenta-programada-@{{ item.ID }}"                                        
                                        >
                                        Programada na remessa: @{{ item.ALOCACAO_REMESSA }}
                                        <div id="ferramenta-programada-@{{ item.ID }}" style="display: none">
                                            <fieldset style="padding-bottom: 10px">
                                                <legend>Localização</legend>
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th title="Grupo de Produção">GP</th>
                                                            <th>Estação</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>@{{ item.ALOCACAO_GP_DESCRICAO }}</td>
                                                            <td>@{{ item.ALOCACAO_ESTACAO_DESCRICAO }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </fieldset>
                                        </div>                                        
                                    </b>
                                    <div ng-if="item.ALOCACAO_REMESSA == null" class="escala" title="Passe o mouse sobre a faixa verde para identificar os tempos alocados.">
                                        <div ng-repeat="minuto in item.MINUTOS_ALOCACAO track by $index" style="left: @{{ ((minuto.TEMPO_INICIO / item.MINUTOS)*100).toFixed(3) }}%; width: @{{ ((( minuto.MINUTOS_PROGRAMADOS / item.MINUTOS)*100)+0.01).toFixed(3) }}%"
                                            data-toggle="popover" 
                                            data-placement="top" 
                                            data-element-content="#ferramenta-@{{ minuto.GP_ID }}-@{{ minuto.ESTACAO }}-@{{ minuto.TEMPO_INICIO }}"
                                            on-finish-render="bs-init"
                                            >
                                            <div id="ferramenta-@{{ minuto.GP_ID }}-@{{ minuto.ESTACAO }}-@{{ minuto.TEMPO_INICIO }}" style="display: none">
                                                <fieldset style="padding-bottom: 10px">
                                                    <legend>Localização</legend>
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th title="Grupo de Produção">GP</th>
                                                                <th>Estação</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>@{{ minuto.GP_DESCRICAO }}</td>
                                                                <td>@{{ minuto.ESTACAO_DESCRICAO }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </fieldset>
                                                <fieldset style="padding-bottom: 10px">
                                                    <legend>Modelo</legend>
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Modelo</th>
                                                                <th>Cor</th>
                                                                <th title="Quantidade programada">Qtd.</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>@{{ minuto.MODELO_ID }} - @{{ minuto.MODELO_DESCRICAO }}</td>
                                                                <td>@{{ minuto.COR_ID }} - @{{ minuto.COR_DESCRICAO }}</td>
                                                                <td>@{{ minuto.QUANTIDADE_PROGRAMADA | number: 0 }}</td>
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
                                                                <th class="text-right" title="Tempo de setup">Tp. Set.</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-right">@{{ minuto.TEMPO_INICIO }}'</td>
                                                                <td class="text-right">@{{ minuto.TEMPO_FIM }}'</td>
                                                                <td class="text-right">@{{ minuto.TEMPO_ITEM }}'</td>
                                                                <td class="text-right">@{{ minuto.MINUTOS_PROGRAMADOS - minuto.TEMPO_ITEM }}'</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>   
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>                
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </fieldset>
</div>