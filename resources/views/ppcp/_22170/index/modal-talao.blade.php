@extends('helper.include.view.modal', ['id' => 'modal-talao', 'class_size' => 'modal-big'])

@section('modal-header-left')

<h4 class="modal-title">
    Detalhamento do Talão
</h4>

@overwrite

@section('modal-header-right')


    <button type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
      <span class="glyphicon glyphicon-chevron-left"></span> Voltar
    </button>

@overwrite

@section('modal-body')


    @include('ppcp._22170.index.panel-destaque')  

    <div
        ng-if="vm.TalaoProduzir.check('iniciar').descricao != ''" 
        class="panel panel-danger modal-header-center" 
        style="
        display: block;
        position: absolute;
        width: 378px;
        top: 51px;
        overflow: hidden;
        background: rgb(169, 15, 15);
        margin: 0 auto;
        /* left: 0; */
        /* right: 0; */
        text-align: center;
        color: white;
        font-weight: bold;
    ">Atenção: @{{ vm.TalaoProduzir.check('iniciar').descricao }}</div>    
    
    <div style="
        height: 53px;
    ">
        <button 
            type="button" 
            class="btn btn-success" 
            id="iniciar" 
            data-hotkey="home"
            ng-disabled="!vm.TalaoProduzir.check('iniciar').status"            
            ng-click="vm.TalaoProduzir.acao('iniciar')"
            >
            <span class="glyphicon glyphicon-play"></span> {{ Lang::get('master.iniciar') }}
        </button>

        <button 
            type="button" 
            class="btn btn-primary" 
            id="pausar" 
            data-hotkey="pause"
            ng-disabled="!vm.TalaoProduzir.check('pausar').status"            
            ng-click="vm.TalaoProduzir.acao('pausar')"
            >
            <span class="glyphicon glyphicon-pause"></span> {{ Lang::get('master.pausar') }}
        </button>

        <button 
            type="button" 
            class="btn btn-danger" 
            id="finalizar" 
            data-hotkey="end"
            ng-disabled="!vm.TalaoProduzir.check('finalizar').status"            
            ng-click="vm.TalaoProduzir.acao('finalizar')"
            >
            <span class="glyphicon glyphicon-stop"></span> {{ Lang::get('master.finalizar') }}
        </button>
<!--        <button ng-click="vm.Acoes.justInefic(vm.MODAL)" type="button" class="btn btn-temp btn-warning">
            <span class="glyphicon glyphicon-tags"></span> Justificar
        </button>-->
    </div>
    <fieldset>
        <legend>Talão @{{ vm.Talao.SELECTED.PROGRAMACAO_STATUS_DESCRICAO }}</legend>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Remessa</th>
                    <th>Talão</th>
                    <th>Cód. Prod.</th>
                    <th>Modelo</th>
                    <th>Cor</th>
                    <th>Tam.</th>
                    <th>Qtd.</th>
                    <th>Temp. Prev.</th>
                    <th>Temp. Real.</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>@{{ vm.Talao.SELECTED.REMESSA_DATA | toDate | date:'dd/MM' : '+0' }}</td>
                    <td title="Id: @{{ vm.Talao.SELECTED.REMESSA_ID }}">@{{ vm.Talao.SELECTED.REMESSA }}</td>
                    <td title="Id: @{{ vm.Talao.SELECTED.TALAO_ID }}">@{{ vm.Talao.SELECTED.REMESSA_TALAO_ID }}</td>
                    <td>@{{ vm.Talao.SELECTED.PRODUTO_ID }}</td>
                    <td>@{{ vm.Talao.SELECTED.MODELO_ID }} - @{{ vm.Talao.SELECTED.MODELO_DESCRICAO }}</td>
                    <td>@{{ vm.Talao.SELECTED.COR_ID }} - @{{ vm.Talao.SELECTED.COR_DESCRICAO }}</td>
                    <td title="Grade Id: @{{ vm.Talao.SELECTED.GRADE_ID }} - Tamanho Id: @{{ vm.Talao.SELECTED.TAMANHO }}">@{{ vm.Talao.SELECTED.TAMANHO_DESCRICAO }}</td>
                    <td class="um">@{{ vm.Talao.SELECTED.QUANTIDADE_PROJETADA | number : 0 }} @{{ vm.Talao.SELECTED.UM }}</td>
                    <td class="um">@{{ vm.Talao.SELECTED.TEMPO_PREVISTO | number : 2 }} min</td>
                    <td class="um">@{{ vm.Talao.SELECTED.TEMPO_REALIZADO | number : 2 }} min</td>
                </tr>
            </tbody>
        </table>
    </fieldset>

    @include('ppcp._22170.index.modal-talao.consumo')
    @include('ppcp._22170.index.modal-talao.historico')
@overwrite