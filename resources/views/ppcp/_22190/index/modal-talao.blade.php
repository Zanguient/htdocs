@extends('helper.include.view.modal', ['id' => 'modal-talao', 'class_size' => 'modal-big'])

@section('modal-header-left')

<h4 class="modal-title">
    Detalhamento do Talão
</h4>

@overwrite

@section('modal-header-right')


    <button ng-click="vm.Talao.irPara('|<')" ttitle="Ir para o primeiro talão.<br/>Atalho: Ctrl + Seta para Cima" type="button" class="btn btn-default hotkey-no-label" data-hotkey="ctrl+up">
      |<
    </button>

    <button ng-click="vm.Talao.irPara('<')" ttitle="Ir para o talão anterior<br/>Atalho: Ctrl + Seta para Esquerda" type="button" class="btn btn-default hotkey-no-label" data-hotkey="ctrl+left">
      <
    </button>

    <button ng-click="vm.Talao.irPara('>')" ttitle="Ir para o próximo talão<br/>Atalho: Ctrl + Seta para Direita" type="button" class="btn btn-default hotkey-no-label" data-hotkey="ctrl+right">
      >
    </button>

    <button ng-click="vm.Talao.irPara('>|')" ttitle="Ir para o útltimo talão<br/>Atalho: Ctrl + Seta para Baixo" type="button" class="btn btn-default hotkey-no-label" data-hotkey="ctrl+down">
      >|
    </button>

    <button type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc" data-hotkey="esc">
      Voltar
    </button>

@overwrite

@section('modal-body')


    @include('ppcp._22190.index.panel-destaque')  

    <div
        ng-if="vm.TalaoProduzir.check('iniciar').descricao != '' && vm.TalaoProduzir.SELECTED.PROGRAMACAO_STATUS != 2" 
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

    <div
        ng-if="vm.TalaoProduzir.check('finalizar').descricao != '' && vm.Talao.SELECTED.PROGRAMACAO_STATUS == 2" 
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
    ">Atenção: @{{ vm.TalaoProduzir.check('finalizar').descricao }}</div>    
    
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
                    <th class="text-center">Data</th>
                    <th>Remessa</th>
                    <th class="text-center">Talão</th>
                    <th>Modelo</th>
                    <th class="text-center">Tam.</th>
                    <th class="text-right">Qtd.</th>
                    <th class="text-right">Temp. Prev.</th>
                    <th class="text-right">Temp. Real.</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">@{{ vm.Talao.SELECTED.REMESSA_DATA | toDate | date:'dd/MM' : '+0' }}</td>
                    <td>
                        <a ttitle="Id da remessa: @{{ vm.Talao.SELECTED.REMESSA_ID }} <br/>Clique aqui para consultar a remessa deste talão" href="{{ url('/_22120?remessa=') }}@{{ vm.Talao.SELECTED.REMESSA_PRINCIPAL }}" target="_blank">
                        @{{ vm.Talao.SELECTED.REMESSA }}
                        </a>
                    </td>
                    <td class="text-center" title="Id: @{{ vm.Talao.SELECTED.TALAO_ID }}">@{{ vm.Talao.SELECTED.REMESSA_TALAO_ID }}</td>
                    <td>@{{ vm.Talao.SELECTED.MODELO_ID }} - @{{ vm.Talao.SELECTED.MODELO_DESCRICAO }}</td>
                    <td class="text-center" title="Grade Id: @{{ vm.Talao.SELECTED.GRADE_ID }} - Tamanho Id: @{{ vm.Talao.SELECTED.TAMANHO }}">@{{ vm.Talao.SELECTED.TAMANHO_DESCRICAO }}</td>
                    <td class="text-lowercase text-right">@{{ vm.Talao.SELECTED.QUANTIDADE_PROJETADA | number : 0 }} @{{ vm.Talao.SELECTED.UM }}</td>
                    <td class="text-lowercase text-right">@{{ vm.Talao.SELECTED.TEMPO_PREVISTO | number : 2 }} min</td>
                    <td class="text-lowercase text-right">@{{ vm.Talao.SELECTED.TEMPO_REALIZADO | number : 2 }} min</td>
                </tr>
            </tbody>
        </table>
    </fieldset>

    @include('ppcp._22190.index.modal-talao.detalhe')
    @include('ppcp._22190.index.modal-talao.consumo')
    @include('ppcp._22190.index.modal-talao.historico')
@overwrite