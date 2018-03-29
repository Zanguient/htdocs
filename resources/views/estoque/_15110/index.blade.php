@extends('master')

@section('titulo')
    {{ Lang::get('estoque/_15110.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/15110.css') }}" />
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

                    <div class="form-group">
                        <label>Filtro: <span style="margin-left: 5px;" class="glyphicon glyphicon-info-sign" ttitle="Espaços em branco são ignorados na consulta."></span></label>
                        <input 
                            type="text" 
                            ng-model="vm.Estoque.FILTRO.FILTRO" 
                            ng-change="vm.Estoque.virifyChange(vm.Estoque.FILTRO.FILTRO,'@{{ vm.Estoque.FILTRO.FILTRO }}')" 
                            class="form-control input-maior" 
                            form-validade="true"
                            placeholder="Filtragem por Modelo, Cor e Tamanho"
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
                            placeholder="Limite" />
                    </div>
                    <button type="submit" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter" style="vertical-align: text-top;top: 2px;">
                        <span class="glyphicon glyphicon-filter"></span> Filtrar
                    </button>                    
                </div>
            </form>
            <style>
                
                .wid-estabelecimento {
                    max-width: 48px;
                }                
                
                .wid-localizacao {
                    max-width: 48px;
                }                
                
                .wid-familia {
                    max-width: 53px;
                }                
                .wid-produto {
                    max-width: 180px;
                }  
                
                .wid-tamanho {
                    max-width: 45px;
                }                
            </style>
            <div class="table-ec" style="height: calc(100vh - 265px); min-height: 270px;">
                <table class="table table-bordered table-striped table-hover table-scroll table-middle table-condensed">
<!--                    <col width="0.5%">
                    <col width="0.5%">
                    <col width="0.5%">
                    <col width="1%">
                    <col width="1%">
                    <col width="7%">
                    <col width="5%">
                    <col width="3%">
                    <col width="3%">
                    <col width="1%">
                    <col width="3%">
                    <col width="5%">-->
                    <thead>
                        <tr>
                            <th class="wid-estabelecimento ellipsis"  autotitle>Estabelecimento</th>
                            <th class="wid-localizacao ellipsis" autotitle>Localização de Estoque</th>
                            <th class="wid-familia ellipsis" autotitle>Família de Produto</th>
                            <th>Produto</th>
                            <th class="wid-tamanho text-center" ttitle="Tamanho">Tam.</th>
                            <th class="text-right" ttitle="Saldo disponível em estoque">Qtd. Saldo</th>
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
                            <td class="no-break text-right text-lowercase">@{{ item.SALDO | number: 4 }} @{{ item.UM }}</td>
                        </tr>                        
                    </tbody>
                </table>
            </div>
        </form>  
    </fieldset>

</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_15110.js') }}"></script>
@append
