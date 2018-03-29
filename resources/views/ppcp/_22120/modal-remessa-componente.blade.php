@extends('helper.include.view.modal', ['id' => 'modal-remessa-componente', 'class_size' => 'modal-full'])


@section('modal-start')
    
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Geração de Remessa de Componente
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
                ng-disabled="vm.RemessaComponente.checkGravar()" 
                ng-click="vm.RemessaComponente.gravar()"
                >
                <span class="glyphicon glyphicon-ok"></span>
                Gravar
            </button>
        </li>

        <li>
            <button 
                type="button" 
                class="btn btn-primary"
                ng-click="vm.RemessaComponente.processarAuto()"
                ng-disabled="!vm.RemessaComponente.checkGravar()" 
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
    <form class="form-inline" ng-submit="vm.RemessaComponente.consultarTaloesVinculo()">
        <div id="form-filtro" class="table-filter collapse in" aria-expanded="true">             


            <div class="form-group">
                <label>Tipo da Remessa:</label>
                <select 
                    ng-init="vm.RemessaComponente.FILTRO.REMESSA_TIPO = ''" 
                    ng-model="vm.RemessaComponente.FILTRO.REMESSA_TIPO" 
                    class="form-control"
                    id="remessa-componente-tipo"
                    form-validade="true"
                    required
                    >
                    <option value="" disabled>-- Selecione --</option>
                    <option value="1">Componente</option>
                    <option value="2">Pedido</option>
                    <option value="3">Reposição de Estoque</option>
                    <option value="4">Requisição</option>
                </select>
            </div>  
            
            <div class="animate-switch-container" ng-switch on="vm.RemessaComponente.FILTRO.REMESSA_TIPO">
                
                <div class="form-group" ng-switch-when="1|2" ng-switch-when-separator="|">
                    <label ng-switch on="vm.RemessaComponente.FILTRO.REMESSA_TIPO">
                        <span ng-switch-when="1">Remessa de Origem:</span>
                        <span ng-switch-when="2">Nº do Pedido:</span>
                    </label>
                    <div class="input-group">
                        <input 
                            type="text" 
                            class="form-control" 
                            required 
                            autocomplete="off" 
                            ng-readonly="vm.RemessaComponente.FILTRO.ORIGEM_SELECTED"
                            ng-model="vm.RemessaComponente.FILTRO.ORIGEM"
                            />

                        <button 
                            ng-if="!vm.RemessaComponente.FILTRO.ORIGEM_SELECTED" 
                            ng-click="vm.RemessaComponente.FILTRO.ORIGEM.trim().length > 0 ? vm.RemessaComponente.consultarOrigemDados() : ''" 
                            type="button" 
                            class="input-group-addon btn-filtro" 
                            tabindex="-1"
                            >
                            <span class="glyphicon glyphicon-triangle-right"></span>
                        </button>
                        <button 
                            ng-if="vm.RemessaComponente.FILTRO.ORIGEM_SELECTED" 
                            ng-click="vm.RemessaComponente.FILTRO.ORIGEM_SELECTED = false; vm.RemessaComponente.FILTRO.ORIGEM = ''" 
                            type="button" 
                            class="input-group-addon btn-filtro" 
                            tabindex="-1"
                            >
                            <span class="fa fa-close"></span>
                        </button>
                    </div>
                </div>       
            </div>              
                                

            <div class="rc-consulta-gp"></div>


            <button type="submit" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
                <span class="glyphicon glyphicon-filter"></span> Filtrar
            </button>
        </div>                
    </form>    
            
    <div class="table-ec" style="height: calc(100vh - 558px); min-height: 128px;">
        <table class="table table-bordered table-middle table-striped table-condensed">
            <thead>
                <tr>
                    <th>Modelo</th>
                    <th class="text-center">Tam.</th>
                    <th>Cor</th>
                    <th class="text-right" title="Densidade">Dens.</th>
                    <th class="text-right" title="Espessura">Esp.</th>
                    <th>Perfil Sku</th>
                    <th class="text-right">Classe de Cor</th>
                    <th class="text-right" title="Cota acumulada">Cota Acum.</th>
                    <th class="text-right" title="Cota detalhamento">Cota Det.</th>
                    <th class="text-right" title="Quantidade necessária a programar">Qtd. Nec.</th>
                    <th class="text-right" title="Quantidade à programar">Qtd. Prog.</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="item in vm.RemessaComponente.SKUS" ng-class="{'disabled':item.PROGRAMADO}">
                    <td>
                        <span>@{{ item.MODELO_ID | lpad: [4,0] }} - @{{ item.MODELO_DESCRICAO }}</span>

                        <span
                            style="float: right"
                            class="glyphicon glyphicon-info-sign" 
                            data-toggle="popover" 
                            data-placement="right"
                            title="Informações da Origem"
                            data-element-content="#info-talao-@{{ item.CONTROLE }}"
                        ></span>
                        <div id="info-talao-@{{ item.CONTROLE }}" style="display: none">
                            <div class="table-ec" style="max-height: 300px">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Ctrl.</th>
                                            <th>Produto</th>
                                            <th>Tam.</th>
                                            <th class="text-right">Qtd. Orig.</th>
                                            <th class="text-right">Qtd. Cons.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="detalhe in item.ORIGEM">
                                            <td class="text-center">@{{ detalhe.REMESSA_TALAO_ID | lpad: [4,0] }}</td>
                                            <td>@{{ detalhe.PRODUTO_ID }} - @{{ detalhe.PRODUTO_DESCRICAO }}</td>
                                            <td title="@{{ detalhe.TAMANHO }}">@{{ detalhe.TAMANHO_DESCRICAO }}</td>
                                            <td class="text-right text-lowercase">@{{ detalhe.QUANTIDADE_ORIGINAL | number: 4 }} @{{ detalhe.UM_ORIGINAL }}</td>
                                            <td class="text-right text-lowercase">@{{ detalhe.QUANTIDADE | number: 4 }} @{{ detalhe.UM }}</td>
                                        </tr>
                                    </tbody>
                                </table>                                    
                            </div>
                        </div>                        
                        
                    </td>
                    <td class="text-center" title="Grade Id: @{{ item.GRADE_ID }} | Tam Id: @{{ item.TAMANHO }}">@{{ item.TAMANHO_DESCRICAO }}</td>
                    <td>@{{ item.COR_ID | lpad: [4,0] }} - @{{ item.COR_DESCRICAO }}</td>
                    <td class="text-right">D@{{ item.DENSIDADE }}</td>
                    <td class="text-right text-lowercase">@{{ item.ESPESSURA | number: 2 }} mm</td>
                    <td>@{{ item.PERFIL_SKU }} - @{{ item.PERFIL_SKU_DESCRICAO }}</td>
                    <td class="text-right">@{{ item.CLASSE }}.@{{ item.SUBCLASSE | lpad: [3,0] }}</td>
                    <td class="text-right text-lowercase">@{{ item.FATOR_DIVISAO | number : 4 }} @{{ item.UM }}</td>
                    <td class="text-right text-lowercase">@{{ item.FATOR_DIVISAO_DETALHE | number : 4 }} @{{ item.UM }}</td>
                    <td class="text-right text-lowercase">@{{ item.QUANTIDADE | number : 4 }} @{{ item.UM }}</td>
                    <td class="text-right text-lowercase">@{{ item.QUANTIDADE_PROGRAMAR || item.QUANTIDADE | number : 4 }} @{{ item.UM }}</td>
                </tr>
            </tbody>
        </table>
    </div>

            
    <fieldset>
        <legend>UP's</legend>
        <div class="up-container">
            <div class="up-bloco" ng-repeat="up in vm.RemessaComponente.UPS | orderBy : ['UP_DESCRICAO']">
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
                            <label>Estação: @{{ estacao.ESTACAO_DESCRICAO }} <x class="text-lowercase">(@{{ vm.RemessaComponente.estacaoQuantidade(estacao) | number: 0 }} @{{ estacao.QUANTIDADE_UM }})</x></label>
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
                                        <td>@{{ item.CONTROLE }}</td>
                                        <td>
                                            <span>@{{ item.MODELO_ID }} - @{{ item.MODELO_DESCRICAO }}</span>

                                            <span
                                                style="float: right"
                                                class="glyphicon glyphicon-info-sign" 
                                                data-toggle="popover" 
                                                data-placement="top"
                                                title="Detalhamento do Talão"
                                                data-element-content="#info-talao-@{{ item.ID_REFER }}"
                                            ></span>
                                            <div id="info-talao-@{{ item.ID_REFER }}" style="display: none">
                                                <div class="table-ec" style="max-height: 300px">
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Ctrl.</th>
                                                                <th>Produto</th>
                                                                <th>Tam.</th>
                                                                <th class="text-right">Qtd Orig.</th>
                                                                <th class="text-right">Qtd Cons.</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr ng-repeat="detalhe in item.ORIGENS">
                                                                <td class="text-center">@{{ detalhe.REMESSA_TALAO_ID | lpad: [4,0] }}</td>
                                                                <td>@{{ detalhe.PRODUTO_ID }} - @{{ detalhe.PRODUTO_DESCRICAO }}</td>
                                                                <td title="@{{ detalhe.TAMANHO }}">@{{ detalhe.TAMANHO_DESCRICAO }}</td>
                                                                <td class="text-right text-lowercase">@{{ detalhe.QUANTIDADE_ORIGINAL | number: 4 }} @{{ detalhe.UM_ORIGINAL }}</td>
                                                                <td class="text-right text-lowercase">@{{ detalhe.QUANTIDADE | number: 4 }} @{{ detalhe.UM }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>                                    
                                                </div>
                                            </div>                        

                                        </td>
                                        <td class="text-center" title="@{{ item.TAMANHO }}">@{{ item.TAMANHO_DESCRICAO }}</td>
                                        <td class="text-center" title="@{{ item.PERFIL_SKU_DESCRICAO }}">@{{ item.PERFIL_SKU }}</td>
                                        <td class="text-right text-lowercase">@{{ item.QUANTIDADE | number : 4 }} @{{ item.UM }}</td>
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