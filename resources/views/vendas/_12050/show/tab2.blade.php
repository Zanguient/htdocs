<div class="table-container" style="height: calc(100vh - 350px);">
                <table class="table table-bordered table-header">
                    <thead>
                        <tr>
                            <th class="coluna-descricao">{{$TIPO}}</th>
                            <th class="coluna-quantidade" title="{{$col1}}">Quantidade</th>
                            <th class="coluna-quantidade" title="{{$col2}}">% Quantidade</th>
                            <th class="coluna-quantidade" title="{{$col5}}">% Def. Produção</th>
                        </tr>
                    </thead>
                </table>
                <div class="scroll-table">
                    <table class="table table-striped table-bordered table-hover table-body">
                        <tbody>
                            <tr ng-dblclick="vm.Acao.AddFilter('{{$TIPO}}',iten.KEY,iten.DESCRICAO)" ng-repeat="iten in vm.DADOS.DEFEITO.{{$TIPO}} | orderBy : '-QUANTIDADE * 1'">

                                <td class="coluna-descricao">
                                    @{{iten.DESCRICAO}}   
                                </td>

                                <td class="coluna-quantidade">
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

                                <td class="coluna-quantidade">
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

                                <td class="coluna-quantidade">
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

                                <td class="coluna-quantidade">
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

                                <td class="coluna-quantidade">
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

                                <td class="coluna-quantidade">
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
            </div>