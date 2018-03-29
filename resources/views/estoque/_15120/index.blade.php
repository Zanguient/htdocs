@extends('master')

@section('titulo')
    {{ Lang::get('estoque/_15120.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/15120.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

    <fieldset>
        <legend>Consulta de Estoque</legend>
            <form class="form-inline" ng-submit="vm.Estoque.consultar()"> 
                <style>
                    #form-filtro {
                        background: rgba(221,221,221,.33);
                        padding: 2px 10px 7px;
                        border-radius: 5px;
                    }
                </style>
                <div id="form-filtro" class="table-filter collapse in" aria-expanded="true">
                    
                    <div class="form-group famila-estoque"></div>

                    <div class="form-group">
                        <label>Filtro: <span style="margin-left: 5px;" class="glyphicon glyphicon-info-sign" ttitle="Espaços em branco são ignorados na consulta."></span></label>
                        <input 
                            type="text" 
                            ng-model="vm.Estoque.FILTRO.FILTRO" 
                            ng-change="vm.Estoque.virifyChange(vm.Estoque.FILTRO.FILTRO,'@{{ vm.Estoque.FILTRO.FILTRO }}')" 
                            class="form-control input-maior" 
                            form-validade="true"
                            placeholder="Filtragem por Modelo, Cor, Tamanho, Estabelecimento e Localização"
                            autofocus
                            />
                    </div>
                    <div class="form-group">
                        <label>Qtd. Reg.: <span style="margin-left: 5px;" class="glyphicon glyphicon-info-sign" ttitle="Quantidade de registros a serem listados na consulta."></span></label>
                        <input 
                            type="text" 
                            ng-init="vm.Estoque.FILTRO.FIRST = 100"
                            ng-model="vm.Estoque.FILTRO.FIRST" 
                            ng-change="vm.Estoque.virifyChange(vm.Estoque.FILTRO.FILTRO,'@{{ vm.Estoque.FILTRO.FILTRO }}')" 
                            class="form-control input-menor" 
                            form-validade="true"
                            placeholder="Filtrragem por Modelo, Cor e Tamanho" />
                    </div>
                    
                    <div class="form-group" style="margin-top: -5px;">
                        <label style="margin-bottom: -4px;">Qtd. Saldo > 0: <span style="margin-left: 5px;" class="glyphicon glyphicon-info-sign" ttitle="Saldo maior do que 0."></span></label>
                        <input style="margin-top: -8px;" type="checkbox" ng-model="vm.SALDO.STATUS">
                    </div>

                    <button type="submit" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter" style="vertical-align: text-top;top: 2px;">
                        <span class="glyphicon glyphicon-filter"></span> Filtrar
                    </button> 

                                      
                </div>
            </form>
            <style>
                
                .wid-estabelecimento {
                    max-width: 55px;
                }                
                
                .wid-localizacao {
                    max-width: 55px;
                }                
                
                .wid-familia {
                    max-width: 55px;
                }                
                .wid-produto {
                    max-width: 180px;
                }  
                
                .wid-tamanho {
                    max-width: 45px;
                }                
            </style>
    
            <div style="margin-bottom: 5px;">
                <button type="button" ng-click="vm.Estoque.CSV()" class="btn btn-primary ">
                    <span class="glyphicon glyphicon-save"></span> Exportar para CSV
                </button> 

                <button type="button" ng-click="vm.Estoque.XLS()" class="btn btn-primary ">
                    <span class="glyphicon glyphicon-save"></span> Exportar para XLS
                </button> 

                <button type="button" ng-click="vm.Estoque.IMPRIMIR()" class="btn btn-primary ">
                    <span class="glyphicon glyphicon-print"></span> Imprimir
                </button> 
            </div>

            <div  id="pai-tabela-estoque">
                <div class="table-ec filho-tabela-estoque">
                    <table class="table table-bordered table-striped table-hover table-scroll table-middle table-condensed" id="tabela-estoque">
                        <thead>
                            <tr>
                                <th class="wid-estabelecimento ellipsis"  autotitle>Estabelecimento</th>
                                <th class="wid-localizacao ellipsis" autotitle>Localização de Estoque</th>
                                <th class="wid-familia ellipsis" autotitle>Família de Produto</th>
                                <th>Produto</th>
                                <th class="wid-tamanho text-center" ttitle="Tamanho">Tam.</th>
                                <th class="text-right" style="max-width:55px;" ttitle="Saldo em estoque">Est. Físico</th>
                                <th class="text-right" style="max-width:55px;" ttitle="Saldo de estoque em terceiros">Em Terceiros</th>
                                <th class="text-right" style="max-width:55px;" ttitle="Saldo em Revisão">Em Revisão</th>
                                <th class="text-right" style="max-width:55px;" ttitle="Saldo de estoque estragado">Estragado</th>
                                <th class="text-right" style="max-width:55px;" ttitle="Quantidade em ordens de compra">Comprado</th>
                                <th class="text-right" style="max-width:55px;" ttitle="Estoque Mínimo">Est. Mínimo</th>
                                <th class="text-right" style="max-width:55px;" ttitle="Estoque Disponível">Est. Disponível</th>
                            </tr>
                        </thead>
                        <tbody infinite-scroll="vm.Estoque.getMoreData()" infinite-scroll-container='".table-ec"' infinite-scroll-disabled='vm.Estoque.AJAX_LOCKED'  infinite-scroll-distance='3'>
                            <tr 
                                ng-repeat="item in vm.Estoque.DADOS"
                                ng-dblclick="vm.Estoque.open()"
                                ng-focus="vm.Estoque.pick(item)"
                                ng-click="vm.Estoque.pick(item)"
                                ng-class="{'selected' : vm.Estoque.SELECTED == item }"  
                                ng-keydown="$event.key == 'Enter' && vm.Estoque.open()"
                                tabindex="0"
                                >
                                <td class="wid-estabelecimento ellipsis" autotitle>@{{ item.ESTABELECIMENTO_ID | lpad : [3,0] }} - @{{ item.ESTABELECIMENTO_NOMEFANTASIA }} (@{{ item.ESTABELECIMENTO_UF }})</td>
                                <td class="wid-localizacao ellipsis" autotitle>@{{ item.LOCALIZACAO_ID | lpad : [3,0] }} - @{{ item.LOCALIZACAO_DESCRICAO }}</td>
                                <td class="wid-familia ellipsis" autotitle>@{{ item.FAMILIA_ID | lpad : [4,0] }} - @{{ item.FAMILIA_DESCRICAO }}</td>
                                <td class="wid-produto ellipsis" autotitle>@{{ item.PRODUTO_ID }} - @{{ item.PRODUTO_DESCRICAO }}</td>
                                <td class="wid-tamanho text-center" title="Grade: @{{ item.GRADE_ID }} - Tam.: @{{ item.TAMANHO }}">@{{ item.TAMANHO_DESCRICAO }}</td>
                                <td style="background-color: #d4ffe9;" class="no-break text-right text-lowercase">@{{ item.SALDO | number: 4 }} @{{ item.UM }}</td>
                                <td style="background-color: #fffbd4;" class="no-break text-right text-lowercase">@{{ item.SALDO_EMTERCEIRO | number: 4 }} @{{ item.UM }}</td>
                                <td style="background-color: #f9d7a6;" class="no-break text-right text-lowercase">@{{ item.SALDO_REVISAO    | number: 4 }} @{{ item.UM }}</td>
                                <td style="background-color: #fdc5c5;" class="no-break text-right text-lowercase">@{{ item.SALDO_ESTRAGADO  | number: 4 }} @{{ item.UM }}</td>
                                <td style="background-color: #c5dffd;" class="no-break text-right text-lowercase">@{{ item.OC               | number: 4 }} @{{ item.UM }}</td>
                                <td style="background-color: #f5ff96;" class="no-break text-right text-lowercase">@{{ item.ESTOQUE_MINIMO   | number: 4 }} @{{ item.UM }}</td>
                                <td style="background-color: #d4e2ff;" class="no-break text-right text-lowercase">@{{ (item.SALDO + item.SALDO_REVISAO) | number: 4 }} @{{ item.UM }}</td>                        </tr>                        
                        </tbody>
                    </table>
                </div>
        
                <div class="table-ec" style="margin-top: 20px" >
                    <table class="table table-bordered table-striped table-hover table-scroll table-middle table-condensed">
                        <thead>
                            <tr>
                                <th class="text-right" style="max-width:55px;" ttitle="Saldo em estoque">Est. Físico</th>
                                <th class="text-right" style="max-width:55px;" ttitle="Saldo de estoque em terceiros">Em Terceiros</th>
                                <th class="text-right" style="max-width:55px;" ttitle="Saldo em Revisão">Em Revisão</th>
                                <th class="text-right" style="max-width:55px;" ttitle="Saldo de estoque estragado">Estragado</th>
                                <th class="text-right" style="max-width:55px;" ttitle="Quantidade em ordens de compra">Comprado</th>
                                <th class="text-right" style="max-width:55px;" ttitle="Estoque Mínimo">Est. Mínimo</th>
                                <th class="text-right" style="max-width:55px;" ttitle="Estoque Disponível">Est. Disponível</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td style="background-color: #d4ffe9;" class="no-break text-right text-lowercase">@{{ vm.Estoque.TOTAL.SALDO | number: 4 }} @{{ item.UM }}</td>
                                <td style="background-color: #fffbd4;" class="no-break text-right text-lowercase">@{{ vm.Estoque.TOTAL.SALDO_EMTERCEIRO | number: 4 }} @{{ item.UM }}</td>
                                <td style="background-color: #f9d7a6;" class="no-break text-right text-lowercase">@{{ vm.Estoque.TOTAL.SALDO_REVISAO    | number: 4 }} @{{ item.UM }}</td>
                                <td style="background-color: #fdc5c5;" class="no-break text-right text-lowercase">@{{ vm.Estoque.TOTAL.SALDO_ESTRAGADO  | number: 4 }} @{{ item.UM }}</td>
                                <td style="background-color: #c5dffd;" class="no-break text-right text-lowercase">@{{ vm.Estoque.TOTAL.OC               | number: 4 }} @{{ item.UM }}</td>
                                <td style="background-color: #f5ff96;" class="no-break text-right text-lowercase">@{{ vm.Estoque.TOTAL.ESTOQUE_MINIMO   | number: 4 }} @{{ item.UM }}</td>
                                <td style="background-color: #d4e2ff;" class="no-break text-right text-lowercase">@{{ (vm.Estoque.TOTAL.SALDO + vm.Estoque.TOTAL.SALDO_REVISAO) | number: 4 }} @{{ item.UM }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>  
    </fieldset>

</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_15120.js') }}"></script>
@append
