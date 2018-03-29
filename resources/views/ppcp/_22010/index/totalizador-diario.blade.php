
<div class="table-totalizador-diario">
    <div class="recebe-puxador-totalizador-diario">
        <div class="table-container">
            <table class="table table-bordered table-header table-lc table-totalizador-diario">
                <thead>
                    <tr>
                        <th class="text-center data"		title="Data da Remessa"                                                                             >Dt. Rem.           </th>
                        <th class="up"                      title="Unidade Produtiva" ng-if="vm.Filtro.UP_ID == ''"                                             >UP                 </th>
                        <th class="text-right capac-disp" 	title="Capacidade Disponível"                                                                       >Capac. Disp.       </th>
                        <th class="text-right carga-prog" 	title="Carga Programada por Data de Remessa (o % é em relação à Capacidade Disponível)"             >Carga Programada   </th>
                        <th class="text-right carga-pend" 	title="Carga Pendente por Data de Remessa"                                                          >Carga Pendente     </th>
                        <th class="text-right carga-util" 	title="Carga Produzida por Data de Produção"                                                        >Carga Produzida    </th>
                        <th class="text-center eficiencia" 	title="Percentual de Eficiência ( ((Qtd. Proj. / Min. Proj.) / (Qtd. Prod. / Min. Prod.)) * 100 )"  >% Efic.            </th>
                        <th class="text-center perc-aprov" 	title="Percentual de Aproveitamento (% da Carga Utilizada em relação à Capacidade Disponível)"      >% Aproveit.        </th>
                    </tr>
                </thead>
            </table>
            <div class="scroll-table">
                <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body table-totalizador-diario">
                    <tbody>
                        <tr ng-repeat="item in vm.TotalizadorDiario.DADOS"
                            data-date="@{{ item.REMESSA_DATA == '' ? null : item.REMESSA_DATA | toDate | date:'dd/MM' : '+0' }}"
                            >
                            <td class="text-center data">@{{ item.REMESSA_DATA == '' ? null : item.REMESSA_DATA | toDate | date:'dd/MM' : '+0' }}</td>
                            <td ng-if="vm.Filtro.UP_ID == ''" class="up" title="@{{ item.UP_DESCRICAO }}">@{{ item.UP_DESCRICAO }}</td>
                            <td class="text-right capac-disp">@{{ item.CAPACIDADE_DISPONIVEL | number : 0 }} min</td>
                            <td class="text-right carga-prog">
                                <div class="label">@{{ item.CARGA_PROGRAMADA | number : 2 }} min</div>
                                <div class="label qtd-talao">@{{ item.QUANTIDADE_TALAO_PROGRAMADA }} tal</div>
                                <div class="label qtd-carga">@{{ item.QUANTIDADE_CARGA_PROGRAMADA | number : 1 }} @{{ item.UM }}</div>
                                <div ng-if="vm.Filtro.VER_PARES" class="label qtd-pares">@{{ item.QUANTIDADE_PARES_PROGRAMADA || 0 | number : 0 }} prs</div>
                                <div
                                    class="label percentual"
                                    ng-class="{
                                        'label-warning' : item.PERC_CARGA_PROGRAMADA <= 90,
                                        'label-success' : item.PERC_CARGA_PROGRAMADA > 90 && item.PERC_CARGA_PROGRAMADA <= 100,
                                        'label-danger' :  item.PERC_CARGA_PROGRAMADA > 100
                                    }">
                                    @{{ item.PERC_CARGA_PROGRAMADA | number : 2 }}%
                                </div>
                            </td>
                            <td class="text-right carga-pend">	
                                <div class="label">@{{ item.CARGA_PENDENTE | number : 2 }} min</div>						
                                <div class="label qtd-talao">@{{ item.QUANTIDADE_TALAO_PENDENTE }} tal</div>
                                <div class="label qtd-carga">@{{ item.QUANTIDADE_CARGA_PENDENTE | number : 1 }} @{{ item.UM }}</div>
                                <div ng-if="vm.Filtro.VER_PARES" class="label qtd-pares">@{{ item.QUANTIDADE_PARES_PENDENTE || 0 | number : 0 }} prs</div>
                            </td>
                            <td class="text-right carga-util">
                                <div class="label">@{{ item.CARGA_UTILIZADA | number : 2 }} min</div>						 
                                <div class="label qtd-talao">@{{ item.QUANTIDADE_TALAO_UTILIZADA }} tal</div>
                                <div class="label qtd-carga">@{{ item.QUANTIDADE_CARGA_UTILIZADA | number : 1 }} @{{ item.UM }}</div>
                                <div ng-if="vm.Filtro.VER_PARES" class="label qtd-pares">@{{ item.QUANTIDADE_PARES_UTILIZADA || 0 | number : 0 }} prs</div>
                            </td>
                            <td class="text-center eficiencia">
                                <div 
                                    class="label percentual"
                                    ng-class="{
                                        'label-danger' : item.EFICIENCIA <= 90,
                                        'label-warning' : item.EFICIENCIA > 90 && item.EFICIENCIA <= 100,
                                        'label-success' :  item.EFICIENCIA > 100
                                    }"
                                    >
                                    @{{ item.EFICIENCIA | number : 2 }}%
                                </div>
                            </td>
                            <td class="text-center perc-aprov">
                                <div
                                    class="label percentual"
                                    ng-class="{
                                        'label-warning' : item.PERC_APROVEITAMENTO <= 90,
                                        'label-success' : item.PERC_APROVEITAMENTO > 90 && item.PERC_APROVEITAMENTO <= 100,
                                        'label-danger' :  item.PERC_APROVEITAMENTO > 100
                                    }"
                                    >
                                    @{{ item.PERC_APROVEITAMENTO | number : 2 }}%
                                </div>
                            </td>
                        </tr>                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="table-totalizador-diario-resumo">
    <div class="recebe-puxador-totalizador-diario-resumo">
        <div class="table-container">
            <table class="table table-bordered table-header table-lc table-totalizador-diario-resumo">
                <thead>
                    <tr>
                        <th class="text-right capac-disp" 	title="Capacidade Disponível"                                                                       >Capac. Disp.       </th>
                        <th class="text-right carga-prog" 	title="Carga Programada por Data de Remessa (o % é em relação à Capacidade Disponível)"             >Carga Programada   </th>
                        <th class="text-right carga-pend" 	title="Carga Pendente por Data de Remessa"                                                          >Carga Pendente     </th>
                        <th class="text-right carga-util" 	title="Carga Produzida por Data de Produção"                                                        >Carga Produzida    </th>
                        <th class="text-center eficiencia" 	title="Percentual de Eficiência ( ((Qtd. Proj. / Min. Proj.) / (Qtd. Prod. / Min. Prod.)) * 100 )"  >% Efic.            </th>
                        <th class="text-center perc-aprov" 	title="Percentual de Aproveitamento (% da Carga Utilizada em relação à Capacidade Disponível)"      >% Aproveit.        </th>
                    </tr>
                </thead>
            </table>
            <div class="scroll-table">
                <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body table-totalizador-diario-resumo">
                    <tbody>
                        <tr ng-repeat="item in vm.TotalizadorDiario.TOTALIZADOR">
                            <td class="text-right capac-disp">@{{ item.CAPACIDADE_DISPONIVEL | number : 0 }} min</td>
                            <td class="text-right carga-prog">
                                <div class="label">@{{ item.CARGA_PROGRAMADA | number : 2 }} min</div>
                                <div class="label qtd-talao">@{{ item.QUANTIDADE_TALAO_PROGRAMADA }} tal</div>
                                <div class="label qtd-carga">@{{ item.QUANTIDADE_CARGA_PROGRAMADA | number : 1 }} @{{ item.UM }}</div>
                                <div ng-if="vm.Filtro.VER_PARES" class="label qtd-pares">@{{ item.QUANTIDADE_PARES_PROGRAMADA || 0 | number : 0 }} prs</div>
                                <div
                                    class="label percentual"
                                    ng-class="{
                                        'label-warning' : item.PERC_CARGA_PROGRAMADA <= 90,
                                        'label-success' : item.PERC_CARGA_PROGRAMADA > 90 && item.PERC_CARGA_PROGRAMADA <= 100,
                                        'label-danger' :  item.PERC_CARGA_PROGRAMADA > 100
                                    }">
                                    @{{ item.PERC_CARGA_PROGRAMADA | number : 2 }}%
                                </div>
                            </td>
                            <td class="text-right carga-pend">	
                                <div class="label">@{{ item.CARGA_PENDENTE | number : 2 }} min</div>						
                                <div class="label qtd-talao">@{{ item.QUANTIDADE_TALAO_PENDENTE }} tal</div>
                                <div class="label qtd-carga">@{{ item.QUANTIDADE_CARGA_PENDENTE | number : 1 }} @{{ item.UM }}</div>
                                <div ng-if="vm.Filtro.VER_PARES" class="label qtd-pares">@{{ item.QUANTIDADE_PARES_PENDENTE || 0 | number : 0 }} prs</div>
                            </td>
                            <td class="text-right carga-util">
                                <div class="label">@{{ item.CARGA_UTILIZADA | number : 2 }} min</div>						 
                                <div class="label qtd-talao">@{{ item.QUANTIDADE_TALAO_UTILIZADA }} tal</div>
                                <div class="label qtd-carga">@{{ item.QUANTIDADE_CARGA_UTILIZADA | number : 1 }} @{{ item.UM }}</div>
                                <div ng-if="vm.Filtro.VER_PARES" class="label qtd-pares">@{{ item.QUANTIDADE_PARES_UTILIZADA || 0 | number : 0 }} prs</div>
                            </td>
                            <td class="text-center eficiencia">
                                <div 
                                    class="label percentual"
                                    ng-class="{
                                        'label-danger' : item.EFICIENCIA <= 90,
                                        'label-warning' : item.EFICIENCIA > 90 && item.EFICIENCIA <= 100,
                                        'label-success' :  item.EFICIENCIA > 100
                                    }"
                                    >
                                    @{{ item.EFICIENCIA | number : 2 }}%
                                </div>
                            </td>
                            <td class="text-center perc-aprov">
                                <div
                                    class="label percentual"
                                    ng-class="{
                                        'label-warning' : item.PERC_APROVEITAMENTO <= 90,
                                        'label-success' : item.PERC_APROVEITAMENTO > 90 && item.PERC_APROVEITAMENTO <= 100,
                                        'label-danger' :  item.PERC_APROVEITAMENTO > 100
                                    }"
                                    >
                                    @{{ item.PERC_APROVEITAMENTO | number : 2 }}%
                                </div>
                            </td>
                        </tr>                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!--<div class="area-full-grafico" id="area-full-grafico">
    <fieldset class="grafico">
        <legend>Gráfico</legend>
        <div id="totalizador-diario-grafico-dashboard" class="grafico-conteiner">
            <div class="area-filtro-grafico">
                <button type="button" class="btn btn-screem-grafico btn-screem-grafico go-fullscreen" gofullscreen="area-full-grafico" title="Tela cheia">
                    <span class="glyphicon glyphicon-fullscreen"></span>
                </button>
                <select class="select-tipo-grafico">
                    <option value="LineChart">Linhas</option>
                    <option value="AreaChart">Áreas</option>
                    <option value="SteppedAreaChart">Andares</option>
                </select>
                <ul id="totalizador-grafico-filter" class=""><ul>
            </div>

            <div id="totalizador-diario-grafico-filter"  style="display: none;"></div>

            <div id="totalizador-diario-grafico"></div>
        </div>
    </fieldset>
</div>-->