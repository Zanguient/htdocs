@extends('helper.include.view.modal', ['id' => 'modal-remessa-intermediaria', 'class_size' => 'modal-full'])


@section('modal-start')
    
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Geração de Remessa Intermediária
	</h4>

@overwrite

@section('modal-header-right')
    <style>

    @media (min-width: 769px) {
        #main .modal-header .list-inline.acoes {
            position:initial;
            display: inline-block;
            margin: 0;
            padding: 0;
            width: auto
        }
    }

    </style>
    <ul class="list-inline acoes">

        <li>
            <button 
                type="button" 
                class="btn btn-success"
                ng-disabled="vm.RemessaIntermediaria.checkGravar()" 
                ng-click="vm.RemessaIntermediaria.gravar()"
                >
                <span class="glyphicon glyphicon-ok"></span>
                Gravar
            </button>
        </li>

        <li>
            <button 
                type="button" 
                class="btn btn-primary"
                ng-click="vm.RemessaIntermediaria.processarAuto()"
                ng-disabled="!vm.RemessaIntermediaria.checkGravar()" 
                >
                <span class="glyphicon glyphicon-flash"></span>
                Processar Automaticamente
            </button>
        </li>

    </ul>

	<button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc">
		<span class="glyphicon glyphicon-chevron-left"></span> 
		Voltar
	</button>
@overwrite

@section('modal-body')

<div id="remessa-intermediaria">


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
    <form class="form-inline" ng-submit="vm.RemessaIntermediaria.consultarTaloesVinculo()">
        <div id="form-filtro" class="table-filter collapse in" aria-expanded="true">             




            <div class="form-group">
                <label>Remessa Principal:</label>
                <div class="input-group">
                    <input 
                        type="text" 
                        id="remessa" 
                        class="form-control" 
                        required 
                        autofocus 
                        autocomplete="off" 
                        ng-readonly="vm.RemessaIntermediaria.FILTRO.REMESSA_SELECTED"
                        ng-model="vm.RemessaIntermediaria.FILTRO.REMESSA"
                        />

                    <button 
                        ng-if="!vm.RemessaIntermediaria.FILTRO.REMESSA_SELECTED" 
                        ng-click="vm.RemessaIntermediaria.FILTRO.REMESSA.trim().length > 0 ? vm.RemessaIntermediaria.FILTRO.REMESSA_SELECTED = true : ''" 
                        type="button" 
                        class="input-group-addon btn-filtro" 
                        tabindex="-1"
                        >
                        <span class="glyphicon glyphicon-triangle-right"></span>
                    </button>
                    <button 
                        ng-if="vm.RemessaIntermediaria.FILTRO.REMESSA_SELECTED" 
                        ng-click="vm.RemessaIntermediaria.FILTRO.REMESSA_SELECTED = false; vm.RemessaIntermediaria.FILTRO.REMESSA = ''" 
                        type="button" 
                        class="input-group-addon btn-filtro" 
                        tabindex="-1"
                        >
                        <span class="fa fa-close"></span>
                    </button>
                </div>
            </div>                    

            <div class="consulta-remessa-vinculo"></div>
            <div class="consulta-gp"></div>


            <button type="submit" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
                <span class="glyphicon glyphicon-filter"></span> Filtrar
            </button>
        </div>                
    </form>    
            
    <div class="table-ec" style="height: calc(100vh - 558px); min-height: 128px;">
        <table class="table table-bordered table-middle table-striped table-condensed">
            <thead>
                <tr>
                    <th>Ctrl.</th>
                    <th>Modelo</th>
                    <th class="text-center">Tam.</th>
                    <th>Perfil Sku</th>
                    <th>Classe de Cor</th>
                    <th class="text-right">Qtd.</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="item in vm.RemessaIntermediaria.TALOES" ng-class="{'disabled':item.PROGRAMADO}">
                    <td>@{{ item.TALAO_CONTROLE }}</td>
                    <td>
                        <span>@{{ item.TALAO_MODELO_ID }} - @{{ item.TALAO_MODELO_DESCRICAO }}</span>

                        <span
                            style="float: right"
                            class="glyphicon glyphicon-info-sign" 
                            data-toggle="popover" 
                            data-placement="top"
                            title="Detalhamento do Talão"
                            data-element-content="#info-talao-@{{ item.TALAO_CONTROLE }}"
                        ></span>
                        <div id="info-talao-@{{ item.TALAO_CONTROLE }}" style="display: none">
                            <div class="table-ec" style="max-height: 300px">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Ctrl.</th>
                                            <th>Produto</th>
                                            <th>Tam.</th>
                                            <th class="text-right">Qtd.</th>
                                            <th class="text-center">Medidas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="detalhe in item.ITENS">
                                            <td class="text-center">@{{ detalhe.REMESSA_TALAO_ID | lpad: [4,0] }}</td>
                                            <td>@{{ detalhe.PRODUTO_ID }} - @{{ detalhe.PRODUTO_DESCRICAO }}</td>
                                            <td title="@{{ detalhe.TAMANHO }}">@{{ detalhe.TAMANHO_DESCRICAO }}</td>
                                            <td class="text-right text-lowercase">@{{ detalhe.QUANTIDADE | number: 4 }} @{{ detalhe.UM }}</td>
                                            <td class="text-center text-lowercase">@{{ detalhe.LARGURA | number: 2 }}m x @{{ detalhe.COMPRIMENTO | number: 2 }}m</td>
                                        </tr>
                                    </tbody>
                                </table>                                    
                            </div>
                        </div>                        
                        
                    </td>
                    <td class="text-center" title="@{{ item.TALAO_TAMANHO }}">@{{ item.TALAO_TAMANHO_DESCRICAO }}</td>
                    <td>@{{ item.TALAO_PERFIL_SKU }} - @{{ item.TALAO_PERFIL_SKU_DESCRICAO }}</td>
                    <td>@{{ item.TALAO_COR_CLASSE }}.@{{ item.TALAO_COR_SUBCLASSE | lpad: [3,0] }}</td>
                    <td class="text-right text-lowercase">@{{ item.TALAO_QUANTIDADE | number : 4 }} @{{ item.UM }}</td>
                </tr>
            </tbody>
        </table>
    </div>

            
    <fieldset>
        <legend>UP's</legend>
        <div class="up-container">
            <div class="up-bloco" ng-repeat="up in vm.RemessaIntermediaria.UPS">
                <label>UP: @{{ up.UP_ID }} - @{{ up.UP_DESCRICAO }}</label>
                <div class="estacao-container">
                    <div class="estacao-bloco" ng-repeat="estacao in up.ESTACOES">
                        
                        <div class="top-left">
                            <button type="button" class="btn btn-xs btn-default btn-subir" title="Subir item selecionado." disabled="">
                                <span class="glyphicon glyphicon-chevron-up"></span>
                            </button>
                            <button type="button" class="btn btn-xs btn-default btn-descer" title="Descer item selecionado." disabled="">
                                <span class="glyphicon glyphicon-chevron-down"></span>
                            </button>
                        </div>

                        <div class="top-center">
                            <label>Estação: @{{ estacao.ESTACAO_DESCRICAO }} <x class="text-lowercase">(@{{ vm.RemessaIntermediaria.estacaoQuantidade(estacao) | number: 0 }} @{{ estacao.QUANTIDADE_UM }})</x></label>
                            <label class="estacao-perfil" ttitle="@{{ estacao.PERFIL_SKU_HTML }}<br/>@{{ estacao.PERFIL_SKU_DESCRICAO_HTML }}">Perfil: <x ng-bind-html="vm.trustedHtml(estacao.PERFIL_SKU_HTML)"></x></label>
                        </div>

                        <div class="top-right">
                            <button type="button" class="btn btn-xs btn-primary btn-incluir-consumo" title="Incluir item selecionado da tabela acima." disabled="">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                            <button type="button" class="btn btn-xs btn-danger btn-excluir-consumo" title="Excluir item selecionado da tabela abaixo." disabled="">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </div>
                        
                        <div class="table-ec" style="height: calc(100% - 25px);">
                            <table class="table table-bordered table-middle table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th>Ctrl.</th>
                                        <th>Modelo</th>
                                        <th class="text-center">Tam.</th>
                                        <th class="text-center" ttitle="Perfil SKU">PS</th>
                                        <th class="text-right">Qtd.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in estacao.TALOES">
                                        <td>@{{ item.TALAO_CONTROLE }}</td>
                                        <td>
                                            <span>@{{ item.TALAO_MODELO_ID }} - @{{ item.TALAO_MODELO_DESCRICAO }}</span>

                                            <span
                                                style="float: right"
                                                class="glyphicon glyphicon-info-sign" 
                                                data-toggle="popover" 
                                                data-placement="top"
                                                title="Detalhamento do Talão"
                                                data-element-content="#info-talao-@{{ item.TALAO_CONTROLE }}"
                                            ></span>
                                            <div id="info-talao-@{{ item.TALAO_CONTROLE }}" style="display: none">
                                                <div class="table-ec" style="max-height: 300px">
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Ctrl.</th>
                                                                <th>Produto</th>
                                                                <th>Tam.</th>
                                                                <th class="text-right">Qtd.</th>
                                                                <th class="text-center">Medidas</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr ng-repeat="detalhe in item.ITENS">
                                                                <td class="text-center">@{{ detalhe.REMESSA_TALAO_ID | lpad: [4,0] }}</td>
                                                                <td>@{{ detalhe.PRODUTO_ID }} - @{{ detalhe.PRODUTO_DESCRICAO }}</td>
                                                                <td title="@{{ detalhe.TAMANHO }}">@{{ detalhe.TAMANHO_DESCRICAO }}</td>
                                                                <td class="text-right text-lowercase">@{{ detalhe.QUANTIDADE | number: 4 }} @{{ detalhe.UM }}</td>
                                                                <td class="text-center text-lowercase">@{{ detalhe.LARGURA | number: 2 }}m x @{{ detalhe.COMPRIMENTO | number: 2 }}m</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>                                    
                                                </div>
                                            </div>                        

                                        </td>
                                        <td class="text-center" title="@{{ item.TALAO_TAMANHO }}">@{{ item.TALAO_TAMANHO_DESCRICAO }}</td>
                                        <td class="text-center" title="@{{ item.TALAO_PERFIL_SKU_DESCRICAO }}">@{{ item.TALAO_PERFIL_SKU }}</td>
                                        <td class="text-right text-lowercase">@{{ item.TALAO_QUANTIDADE | number : 4 }} @{{ item.UM }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </fieldset>            
</div>
    
@overwrite

@section('modal-end')
    
@overwrite