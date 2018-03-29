<div class="table-container table-ec" style="height: calc(100vh - 350px);">
    <table class="table table-bordered table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th class="coluna-descricao">{{$DESC}}</th>
                <th class="coluna-quantidade-{{$TIPO}}" title="Quantidade de defeitos por {{$DESC}}">Quantidade</th>
                <th class="coluna-quantidade-{{$TIPO}}" title="Percentual de defeitos por {{$DESC}}">% Quantidade</th>
                <th class="coluna-quantidade-{{$TIPO}}" title="Total produzido por {{$DESC}}" ng-if="'{{$TIPO}}' != 'DEFEITO'">Prod. {{$DESC}}</th>
                <th class="coluna-quantidade-{{$TIPO}}" title="Percentual de defeitos em relação a produção por {{$DESC}}" ng-if="'{{$TIPO}}' != 'DEFEITO'">% {{$DESC}}</th>
                <th class="coluna-quantidade-{{$TIPO}}" title="Percentual de defeitos da(o) {{$DESC}} em relação a produção total">% Def. {{$DESC}}</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-if="iten.DESCRICAO.length > 0" ng-dblclick="vm.Acao.AddFilter('{{$TIPO}}',iten.KEY,iten.DESCRICAO)" ng-repeat="iten in vm.DADOS.DEFEITO.{{$TIPO}} | orderBy : '-QUANTIDADE * 1'">

                <td class="coluna-descricao">
                    @{{iten.DESCRICAO}}   
                </td>

                <td class="coluna-quantidade-{{$TIPO}}">
                    @{{(iten.QUANTIDADE | number : 2)}}
                    <span class="glyphicon glyphicon-info-sign"
                    data-toggle="popover" 
                    data-placement="left" 
                    title="Por Turno"

                    on-finish-render="bs-init"

                    data-element-content="#{{$TIPO}}-@{{ iten.KEY }}-1"

                    ></span>

                    <div id="{{$TIPO}}-@{{ iten.KEY }}-1" style="display: none">
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th class='text-left'>Turno</th>
                                    <th class='text-left'>Qtd.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class='text-left'>1</td>
                                    <td class='text-left'>@{{iten.QTD_TURNO1 | number : 2}}
                                </tr>
                                <tr>
                                    <td class='text-left'>2</td>
                                    <td class='text-left'>@{{iten.QTD_TURNO2 | number : 2}}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>

                <td class="coluna-quantidade-{{$TIPO}}">
                    @{{(iten.QUANTIDADE / vm.DADOS.DEFEITO.TOTAL.QUANTIDADE) * 100  | number : 2}}%
                    <span class="glyphicon glyphicon-info-sign"
                    data-toggle="popover" 
                    data-placement="left" 
                    title="Por Turno"

                    on-finish-render="bs-init"

                    data-element-content="#{{$TIPO}}-@{{ iten.KEY }}-2"

                    ></span>

                    <div id="{{$TIPO}}-@{{ iten.KEY }}-2" style="display: none">
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th class='text-left'>Turno</th>
                                    <th class='text-left'>Qtd.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class='text-left'>1</td>
                                    <td class='text-left'>@{{(iten.QTD_TURNO1 / vm.DADOS.DEFEITO.TOTAL.QUANTIDADE) * 100  | number : 2}}%
                                </tr>
                                <tr>
                                    <td class='text-left'>2</td>
                                    <td class='text-left'>@{{(iten.QTD_TURNO2 / vm.DADOS.DEFEITO.TOTAL.QUANTIDADE) * 100  | number : 2}}%
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>

                <td class="coluna-quantidade-{{$TIPO}}"
                    ng-if="'{{$TIPO}}' != 'DEFEITO'">
                    @{{iten.PRODUCAO | number : 2}}
                    <span class="glyphicon glyphicon-info-sign"
                    data-toggle="popover" 
                    data-placement="left" 
                    title="Por Turno"

                    on-finish-render="bs-init"

                    data-element-content="#{{$TIPO}}-@{{ iten.KEY }}-3"

                    ></span>

                    <div id="{{$TIPO}}-@{{ iten.KEY }}-3" style="display: none">
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th class='text-left'>Turno</th>
                                    <th class='text-left'>Qtd.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class='text-left'>1</td>
                                    <td class='text-left'>@{{iten.PQTD_TURNO1 | number : 2}}
                                </tr>
                                <tr>
                                    <td class='text-left'>2</td>
                                    <td class='text-left'>@{{iten.PQTD_TURNO2 | number : 2}}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>

                <td class="coluna-quantidade-{{$TIPO}}"
                    ng-if="'{{$TIPO}}' != 'DEFEITO'">
                    @{{(iten.QUANTIDADE / iten.PRODUCAO) * 100  | number : 2}}%
                    <span class="glyphicon glyphicon-info-sign"
                    data-toggle="popover" 
                    data-placement="left" 
                    title="Por Turno"

                    on-finish-render="bs-init"

                    data-element-content="#{{$TIPO}}-@{{ iten.KEY }}-4"

                    ></span>

                    <div id="{{$TIPO}}-@{{ iten.KEY }}-4" style="display: none">
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th class='text-left'>Turno</th>
                                    <th class='text-left'>Qtd.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class='text-left'>1</td>
                                    <td class='text-left'>@{{(iten.QTD_TURNO1 / iten.PQTD_TURNO1 ) * 100  | number : 2}}%
                                </tr>
                                <tr>
                                    <td class='text-left'>2</td>
                                    <td class='text-left'>@{{(iten.QTD_TURNO2 / iten.PQTD_TURNO2 ) * 100  | number : 2}}%
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>

                <td class="coluna-quantidade-{{$TIPO}}">
                    @{{(iten.QUANTIDADE / vm.DADOS.DEFEITO.TOTAL.PRODUCAO) * 100  | number : 2}}%
                    <span class="glyphicon glyphicon-info-sign"
                    data-toggle="popover" 
                    data-placement="left" 
                    title="Por Turno"

                    on-finish-render="bs-init"

                    data-element-content="#{{$TIPO}}-@{{ iten.KEY }}-5"

                    ></span>

                    <div id="{{$TIPO}}-@{{ iten.KEY }}-5" style="display: none">
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th class='text-left'>Turno</th>
                                    <th class='text-left'>Qtd.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class='text-left'>1</td>
                                    <td class='text-left'>@{{(iten.QTD_TURNO1 / vm.DADOS.DEFEITO.TOTAL.PQTD_TURNO1) * 100  | number : 2}}%
                                </tr>
                                <tr>
                                    <td class='text-left'>2</td>
                                    <td class='text-left'>@{{(iten.QTD_TURNO2 / vm.DADOS.DEFEITO.TOTAL.PQTD_TURNO2) * 100  | number : 2}}%
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>

            @php /*  Total  */
            <tr class="linha-total">

                <td class="coluna-descricao">
                    TOTAL 
                </td>

                <td class="coluna-quantidade-{{$TIPO}}">
                    @{{(vm.DADOS.DEFEITO.TOTAL.QUANTIDADE | number : 2)}}
                    <span class="glyphicon glyphicon-info-sign"
                    data-toggle="popover" 
                    data-placement="left" 
                    title="Por Turno"

                    on-finish-render="bs-init"

                    data-element-content="{{$TIPO}}-total-1"

                    ></span>

                    <div id="{{$TIPO}}-total-1" style="display: none">
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th class='text-left'>Turno</th>
                                    <th class='text-left'>Qtd.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class='text-left'>1</td>
                                    <td class='text-left'>@{{vm.DADOS.DEFEITO.TOTAL.QTD_TURNO1 | number : 2}}
                                </tr>
                                <tr>
                                    <td class='text-left'>2</td>
                                    <td class='text-left'>@{{vm.DADOS.DEFEITO.TOTAL.QTD_TURNO2 | number : 2}}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>

                <td class="coluna-quantidade-{{$TIPO}}">
                    @{{(vm.DADOS.DEFEITO.TOTAL.QUANTIDADE / vm.DADOS.DEFEITO.TOTAL.QUANTIDADE) * 100  | number : 2}}%
                    <span class="glyphicon glyphicon-info-sign"
                    data-toggle="popover" 
                    data-placement="left" 
                    title="Por Turno"

                    on-finish-render="bs-init"

                    data-element-content="#{{$TIPO}}-total-2"

                    ></span>

                    <div id="{{$TIPO}}-total-2" style="display: none">
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th class='text-left'>Turno</th>
                                    <th class='text-left'>Qtd.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class='text-left'>1</td>
                                    <td class='text-left'>@{{(vm.DADOS.DEFEITO.TOTAL.QTD_TURNO1 / vm.DADOS.DEFEITO.TOTAL.QUANTIDADE) * 100  | number : 2}}%
                                </tr>
                                <tr>
                                    <td class='text-left'>2</td>
                                    <td class='text-left'>@{{(vm.DADOS.DEFEITO.TOTAL.QTD_TURNO2 / vm.DADOS.DEFEITO.TOTAL.QUANTIDADE) * 100  | number : 2}}%
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>

                <td class="coluna-quantidade-{{$TIPO}}"
                    ng-if="'{{$TIPO}}' != 'DEFEITO'">
                    @{{vm.DADOS.DEFEITO.TOTAL.PRODUCAO | number : 2}}
                    <span class="glyphicon glyphicon-info-sign"
                    data-toggle="popover" 
                    data-placement="left" 
                    title="Por Turno"

                    on-finish-render="bs-init"

                    data-element-content="{{$TIPO}}-total-3"

                    ></span>

                    <div id="{{$TIPO}}-total-3" style="display: none">
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th class='text-left'>Turno</th>
                                    <th class='text-left'>Qtd.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class='text-left'>1</td>
                                    <td class='text-left'>@{{vm.DADOS.DEFEITO.TOTAL.PQTD_TURNO1 | number : 2}}
                                </tr>
                                <tr>
                                    <td class='text-left'>2</td>
                                    <td class='text-left'>@{{vm.DADOS.DEFEITO.TOTAL.PQTD_TURNO2 | number : 2}}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>

                <td class="coluna-quantidade-{{$TIPO}}"
                    ng-if="'{{$TIPO}}' != 'DEFEITO'">
                    @{{(vm.DADOS.DEFEITO.TOTAL.QUANTIDADE / vm.DADOS.DEFEITO.TOTAL.PRODUCAO) * 100  | number : 2}}%
                    <span class="glyphicon glyphicon-info-sign"
                    data-toggle="popover" 
                    data-placement="left" 
                    title="Por Turno"

                    on-finish-render="bs-init"

                    data-element-content="{{$TIPO}}-total-4"

                    ></span>

                    <div id="{{$TIPO}}-total-4" style="display: none">
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th class='text-left'>Turno</th>
                                    <th class='text-left'>Qtd.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class='text-left'>1</td>
                                    <td class='text-left'>@{{(vm.DADOS.DEFEITO.TOTAL.QTD_TURNO1 / vm.DADOS.DEFEITO.TOTAL.PQTD_TURNO1 ) * 100  | number : 2}}%
                                </tr>
                                <tr>
                                    <td class='text-left'>2</td>
                                    <td class='text-left'>@{{(vm.DADOS.DEFEITO.TOTAL.QTD_TURNO2 / vm.DADOS.DEFEITO.TOTAL.PQTD_TURNO2 ) * 100  | number : 2}}%
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>

                <td class="coluna-quantidade-{{$TIPO}}">
                    @{{(vm.DADOS.DEFEITO.TOTAL.QUANTIDADE / vm.DADOS.DEFEITO.TOTAL.PRODUCAO) * 100  | number : 2}}%
                    <span class="glyphicon glyphicon-info-sign"
                    data-toggle="popover" 
                    data-placement="left" 
                    title="Por Turno"

                    on-finish-render="bs-init"

                    data-element-content="#{{$TIPO}}-total-5"

                    ></span>

                    <div id="{{$TIPO}}-total-5" style="display: none">
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th class='text-left'>Turno</th>
                                    <th class='text-left'>Qtd.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class='text-left'>1</td>
                                    <td class='text-left'>@{{(vm.DADOS.DEFEITO.TOTAL.QTD_TURNO1 / vm.DADOS.DEFEITO.TOTAL.PQTD_TURNO1) * 100  | number : 2}}%
                                </tr>
                                <tr>
                                    <td class='text-left'>2</td>
                                    <td class='text-left'>@{{(vm.DADOS.DEFEITO.TOTAL.QTD_TURNO2 / vm.DADOS.DEFEITO.TOTAL.PQTD_TURNO2) * 100  | number : 2}}%
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>

            </tr>  
        </tbody>
    </table>
</div>