<div role="tabpanel" class="tab-pane fade active in" id="tab-container-consulta">

    <style>

        #form-filtro .label-checkbox {
            top: 9px
        }

        #form-filtro [type=submit] {
            margin-top: 16px
        }    


    </style>    
    <form 
        class="form-inline"
        ng-submit="vm.Filtro.consultar()" 
    >

        <div id="form-filtro" class="table-filter collapse in" aria-expanded="true">

            <div class="form-group">
                <label>Situação:</label>             
                <div class="check-group">
                    <label>
                        <input 
                            type="radio" 
                            ng-click="vm.Filtro.STATUS = ''"
                            ng-checked="vm.Filtro.STATUS == ''">
                        <span>Todos</span>
                    </label>
                    <label>
                        <input 
                            type="radio" 
                            ng-click="vm.Filtro.STATUS = '1'"
                            ng-checked="vm.Filtro.STATUS == '1'">
                        <span>Não Cortado</span>
                    </label>
                    <label>
                        <input 
                            type="radio" 
                            ng-click="vm.Filtro.STATUS = '2'"
                            ng-checked="vm.Filtro.STATUS == '2'">
                        <span>Cortado</span>
                    </label>
                    <label>
                        <input 
                            type="radio" 
                            ng-click="vm.Filtro.STATUS = '3'"
                            ng-checked="vm.Filtro.STATUS == '3'">
                        <span>Liberado</span>
                    </label>
                </div>
            </div>  

            @php /*
<!--            <div class="form-group">
                <label>Turno:</label>             
                <div class="check-group">
                    <label>
                        <input 
                            type="radio" 
                            ng-click="vm.Filtro.TURNO = ''"
                            ng-checked="vm.Filtro.TURNO == ''"
                            checked>
                        <span>Todos</span>
                    </label>
                    <label>
                        <input 
                            type="radio" 
                            ng-click="vm.Filtro.TURNO = '1'"
                            ng-checked="vm.Filtro.TURNO == '1'">
                        <span>1º</span>
                    </label>
                    <label>
                        <input 
                            type="radio" 
                            ng-click="vm.Filtro.TURNO = '2'"
                            ng-checked="vm.Filtro.TURNO == '2'">
                        <span>2º</span>
                    </label>
                </div>
            </div>  -->
            @php */
            
            <div class="form-group filtro-periodo">
                <label ng-switch on="vm.Filtro.STATUS || 'null'">
                    Período 
                    <span ng-switch-when="1|null" ng-switch-when-separator="|">Remessa</span>
                    <span ng-switch-when="2">Corte</span>
                    <span ng-switch-when="3">Liberação</span>
                    :
                </label>
                
                <div class="input-group">
                    <input 
                        ng-model="vm.Filtro.DATA_1" 
                        ng-disabled="vm.Filtro.DATA_TODOS"
                        ng-required="!vm.Filtro.DATA_TODOS"
                        toDate
                        type="date" 
                        class="form-control" 
                        required />
                    <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                        <span class="fa fa-close"></span>
                    </button>
                </div>      
                <span style="margin-left: -12px;">à</span>
                <div class="input-group">
                    <input 
                        ng-model="vm.Filtro.DATA_2" 
                        ng-disabled="vm.Filtro.DATA_TODOS"
                        ng-required="!vm.Filtro.DATA_TODOS"
                        toDate
                        type="date" 
                        class="form-control" 
                        required />                
                    <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                        <span class="fa fa-close"></span>
                    </button>
                </div>            

                <!--<input ng-model="vm.Filtro.DATA_TODOS" ng-disabled="vm.Filtro.DATA_TODOS_DISABLED" style="top: -2px; margin-left: 7px; vertical-align: middle; width: 20px !important;" type="checkbox" id="periodo-todos" class="form-control periodo-todos" ttitle="Só é utilizado em Talões à Produzir" checked="">-->            
                <!--<label style="position: relative; top: 2px; margin-left: -1px; vertical-align: middle" for="periodo-todos" ttitle="Só é utilizado em Talões à Produzir">Todos</label>-->
            </div>


            <button type="submit" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
                <span class="glyphicon glyphicon-filter"></span>
                {{ Lang::get('master.filtrar') }}
            </button>


        </div>
    </form>	
    

    <style>
        #table-consulta th,td { font-size: 11px;}
        
        .item-stts {
            height: 15px;
            width: 15px;
            border-radius: 8px;
            border: 1px solid;
        }

        .t-status:before {
            border-radius: 10px !important;
            width: 15px !important;
            height: 15px !important;
        }
        
        .item-stts-1:before { 
            background-color: rgb(217, 83, 79) !important;
        }

        .item-stts-2:before {
            background-color: rgb(68, 157, 68) !important;
        }
        
        .item-stts-3:before {
            background-color: rgb(51, 122, 183) !important;
        }

    </style>
    
    
    <div class="main-container">
        <input type="text" class="form-control input-filter-table" ng-model="vm.Talao.CONSULTA_FILTRO" placeholder="Filtragem rápida..." style="width: 100%; margin-bottom: 3px; height: 23px;">
        <div class="table-cotas table-ec" id="table-consulta" style="min-height: 200px; height: calc(100vh - 305px);">
            <table class="table table-striped table-bordered table-condensed table-hover table-body table-lc table-lc-body table-consumo">
                <thead>
                    <tr>
                        <th></th>
                        <th class="text-center" ttitle="Data Remessa">Dt. Rem.</th>
                        <th style="width:3%" >Remessa</th>
                        <th class="text-center">Talão</th>
                        <th>Produto</th>
                        <th class="text-center" ttitle="Tamanho">Tam.</th>
                        <th class="text-right" ttitle="Quantidade">Qtd.</th>
                        <!--<th style="width:28px"  ttitle="Turno do Corte">T.Corte</th>-->
                        <th class="text-center" ttitle="Data e Hora do Corte">Dt/Hr Corte</th>
                        <th class="text-center" ttitle="Data e Hora da Liberação">Dt/Hr Lib.</th>
                        <th ttitle="Unidade Produtiva do Corte">UP Corte</th>
                        <th ttitle="Grupo de Produção da Liberação">GP Lib.</th>
                        <th ttitle="Operador do Corte">Oper. Corte</th>
                        <th ttitle="Operador da Liberação">Oper. Lib.</th>
                    </tr>
                </thead>                    
                <tbody vs-repeat vs-scroll-parent=".table-ec">
                    <tr 
                        ng-repeat="talao in vm.Talao.DADOS
                        | find: {
                            model : vm.Talao.CONSULTA_FILTRO,
                            fields : [    
                                'STATUS_DESCRICAO',
                                'REMESSA_DATA_TEXT',
                                'REMESSA',
                                'REMESSA_TALAO_ID',                                    
                                'PRODUTO_ID',
                                'PRODUTO_DESCRICAO',
                                'TAMANHO_DESCRICAO',
                                'GP_ID',
                                'GP_DESCRICAO',
                                'DATAHORA_PRODUCAO',
                                'DATAHORA_LIBERACAO'
                            ]
                        }" 
                        tabindex="0"
                        >
                        <td style="min-width:28px; max-width:28px; width:28px" class="t-status item-stts-@{{ talao.STATUS }}"></td>
                        <td style="min-width:55px; max-width:55px; width:55px" class="text-center">@{{ talao.REMESSA_DATA | toDate | date:'dd/MM' }}</td>
                        <td style="min-width:60px; max-width:60px; width:60px" ttitle="Id da Remessa: @{{ talao.REMESSA_ID }}">@{{ talao.REMESSA }}</td>
                        <td style="min-width:40px; max-width:40px; width:40px" class="text-center" ttitle="Id do Talão: @{{ talao.TALAO_ID }}">@{{ talao.REMESSA_TALAO_ID }}</td>
                        <td style="min-width:250px; max-width:250px; width:250px" class="ellipsis" autotitle>@{{ talao.PRODUTO_ID }} - @{{ talao.PRODUTO_DESCRICAO }}</td>
                        <td style="min-width:40px; max-width:40px; width:40px" class="text-center" ttitle="Grade: @{{ talao.GRADE_ID }} - Id do Tamanho: @{{ talao.TAMANHO }}">@{{ talao.TAMANHO_DESCRICAO }}</td>
                        <td style="min-width:70px; max-width:70px; width:70px" class="text-right text-lowercase">@{{ talao.QUANTIDADE_PROJETADA | number }} @{{ talao.UM }}</td>
                        <!--<td style="width:28px">@{{ talao.TURNO }}º</td>-->
                        <td style="min-width:75px; max-width:75px; width:75px" autotitle class="text-center">@{{ talao.DATAHORA_PRODUCAO | toDate | date:'dd/MM HH:mm' }}</td>
                        <td style="min-width:75px; max-width:75px; width:75px" autotitle class="text-center">@{{ talao.DATAHORA_LIBERACAO | toDate | date:'dd/MM HH:mm' }}</td>
                        <td style="min-width:160px; max-width:160px; width:160px" autotitle>@{{ talao.UP_ID }} - @{{ talao.UP_DESCRICAO }}</td>
                        <td style="min-width:75px; max-width:75px; width:75px" autotitle class="ellipsis" autotitle>@{{ talao.GP_ID }} - @{{ talao.GP_DESCRICAO }}</td>
                        <td style="min-width:160px; max-width:160px; width:160px" autotitle class="ellipsis"e>@{{ talao.PRODUCAO_OPERADOR_ID }} - @{{ talao.PRODUCAO_OPERADOR_DESCRICAO }}</td>
                        <td style="min-width:160px; max-width:160px; width:160px" autotitle class="ellipsis">@{{ talao.LIBERACAO_OPERADOR_ID }} - @{{ talao.LIBERACAO_OPERADOR_DESCRICAO }}</td>
                    </tr>
                </tbody>
            </table>
        </div>        
    </div>
    
</div>