        <div id="remessa" class="header-item">
            <style>
                #form-filtro {
                    background: rgba(221,221,221,.33);
                    padding: 2px 10px 7px;
                    border-radius: 5px
                }

                #form-filtro .consulta-container {
                    margin-right: initial;
                    margin-bottom: initial
                }

                #form-filtro input {
                    width: calc(100% - 27px)!important
                }

                #form-filtro .label-checkbox {
                    top: 9px
                }

                #form-filtro [type=submit] {
                    margin-top: 16px
                }    
                
                #form-filtro .check-group {
                    padding: 0 0 4px 10px;
                    border-radius: 6px;
                    background: rgb(226, 226, 226);
                    margin-top: -1px;
                }
                
                #form-filtro .check-group .lbl {
                    display: inline-block;
                    margin-right: 10px;
                }
                
                #form-filtro .check-group .lbl input[type="checkbox"], 
                #form-filtro .check-group .lbl input[type="radio"] {
                    margin-top: 0;
                    margin-bottom: 0;
                    top: 5px;
                    position: relative;
                    width: 20px!important;
                    height: 20px;
                    vertical-align: baseline;
                    box-shadow: none;
                }
                
                #form-filtro .check-group .lbl [checked] ~ span {
                    font-weight: bold;
                }
                
            </style>
            <form class="form-inline" ng-submit="vm.Remessa.consultar()">
                <div id="form-filtro" class="table-filter collapse in" aria-expanded="true">
                    <div class="form-group">
                        <label>Tipo:</label>             
                        <div class="check-group">
                            <label class="lbl">
                                <input 
                                    type="radio" 
                                    ng-click="vm.Remessa.CONSUMO_PERCENTUAL = '< 1'; vm.Remessa.DATA_1 = '01.01.1989'; vm.Remessa.DATA_2 = '01.01.2500';"
                                    ng-checked="vm.Remessa.CONSUMO_PERCENTUAL == '< 1'">
                                <span>Pendente</span>
                            </label>
                            <label class="lbl">
                                <input 
                                    type="radio" 
                                    ng-click="vm.Remessa.CONSUMO_PERCENTUAL = '>= 1'"
                                    ng-checked="vm.Remessa.CONSUMO_PERCENTUAL == '>= 1'">
                                <span>Completa</span>
                            </label>
                        </div>
                    </div>             
                    
                    <div class="form-group">
                        <label>Famílias de Produto:</label>             
                        <div class="check-group">
                            <label class="lbl" ng-repeat="familia in vm.Remessa.FAMILIAS | orderBy: 'REMESSA_FAMILIA_DESCRICAO'">
                                <input 
                                    type="checkbox" 
                                    ng-click="vm.Remessa.toggleCheckFamilia(familia)"
                                    ng-checked="familia.CHECKED">
                                <span>@{{ familia.REMESSA_FAMILIA_DESCRICAO }}</span>
                            </label>
                        </div>
                    </div>             
                    
                    <div class="form-group" ng-if="vm.Remessa.CONSUMO_PERCENTUAL == '>= 1'">
                        <label title="Data para produção da remessa">Data Inicio:</label>
                        <div class="input-group">
                            <input type="date" ng-model="vm.Remessa.DATA_1" toDate max="@{{ vm.Remessa.DATA_2 | date: 'yyyy-MM-dd' }}" class="form-control" required />
                            <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                                <span class="fa fa-close"></span>
                            </button>
                        </div>
                    </div>
                    <div class="form-group" ng-if="vm.Remessa.CONSUMO_PERCENTUAL == '>= 1'">
                        <label title="Data para produção da remessa">Data Fim:</label>
                        <div class="input-group">
                            <input type="date" ng-model="vm.Remessa.DATA_2" toDate id="data-prod" class="form-control" required />
                            <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                                <span class="fa fa-close"></span>
                            </button>
                        </div>
                    </div>                    
                    
                    <button type="submit" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
                        <span class="glyphicon glyphicon-filter"></span> Filtrar
                    </button>
                </div>                
            </form>
            <input type="text" class="form-control fast-filter-table" ng-model="vm.Remessa.FILTRO" placeholder="Filtragem por Remessa...">
            <!--<div class="resize resize-remessa">-->
                <div class="table-ec" style="height: calc(100% - 95px);">
                        <table class="table table-striped table-bordered table-hover table-remessa">
                            <thead>
                                <tr>
                                    <th class="wid-data text-center">Data</th>
                                    <th class="wid-remessa">Remessa</th>
                                    <th class="wid-familia">Família</th>
                                    <th class="wid-quantidade text-right" ttitle="Percentual concluído">% Conc.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="remessa in vm.Remessa.DADOS
                                    | find: {
                                        model : vm.Remessa.FILTRO,
                                        fields : [
                                            'REMESSA',
                                            'REMESSA_DATA_TEXT',
                                            'REMESSA_FAMILIA_ID',
                                            'REMESSA_FAMILIA_DESCRICAO',
                                        ]
                                    }
                                    | orderBy : ['REMESSA_DATA','REMESSA_FAMILIA_DESCRICAO*1','REMESSA*1']"
                                    tabindex="0" 
                                    ng-focus="vm.Remessa.click(remessa);"
                                    ng-click="vm.Remessa.click(remessa);"
                                    ng-class="{'selected' : vm.Remessa.SELECTED == remessa }"
                                    ng-dblclick="vm.Remessa.dblClick();"
                                    >
                                    <td class="wid-data text-center">@{{ remessa.REMESSA_DATA_TEXT }}</td>
                                    <td class="wid-remessa" title="Id Remessa: @{{ remessa.REMESSA_ID }}">@{{ remessa.REMESSA }} @{{ remessa.CONSUMO_STATUS == '1' ? ' (Remessa Concluída)' : '' }}</td>
                                    <td class="wid-familia" autotitle>@{{ remessa.REMESSA_FAMILIA_ID }} - @{{ remessa.REMESSA_FAMILIA_DESCRICAO }}</td>
                                    <td class="wid-quantidade text-right">@{{ remessa.CONSUMO_PERCENTUAL * 100 | number : 2 }} %</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
            <!--</div>-->
        </div>