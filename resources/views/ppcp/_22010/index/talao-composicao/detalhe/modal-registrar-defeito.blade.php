@extends('helper.include.view.modal', ['id' => 'modal-registrar-defeito', 'class_size' => 'modal-lg'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.TalaoDefeito.gravar()">
@overwrite

@section('modal-header-left')
	<h4 class="modal-title">
		Registrar Defeitos
	</h4>
@overwrite

@section('modal-header-right')
    <button tabindex="-1" type="submit" id="imprimir-consumo" class="btn btn-success" data-hotkey="f10" data-loading-text="Confirmando...">
        <span class="glyphicon glyphicon-ok"></span> 
        Confirmar
    </button>
    <button tabindex="-1" type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-chevron-left"></span> 
        Voltar
    </button>
@overwrite

@section('modal-body')
    <div class="row">
        <div class="input-group input-group-filtro-obj" style="margin-bottom: 10px;">
            <input 
                type="search" 
                name="filtro_obj" 
                class="form-control pesquisa filtro-obj ng-pristine ng-valid ng-empty ng-touched" 
                placeholder="Pesquise..." 
                autocomplete="off" 
                title="Filtragem por: Ferramenta, GP, Estação" 
                ng-model="vm.TalaoDefeito.API.DEFEITOS.M_FILTRO"
                ng-change="vm.TalaoDefeito.API.DEFEITOS.mFiltroChange('@{{ vm.TalaoDefeito.API.DEFEITOS.M_FILTRO }}')"
                ng-keydown="vm.TalaoDefeito.API.DEFEITOS.keydown(null,$event)"
                >
            <button type="button" class="input-group-addon btn-filtro btn-filtro-obj btn-pesquisar" tabindex="-1">
                <span class="fa fa-search"></span>
            </button>
        </div>        
        <div class="table-container" style="height: 250px">
            <table class="table table-bordered table-header table-lc table-defeito">
                <thead>
                    <tr>
                        <th class="wid-defeito">Defeito</th>
                    </tr>
                </thead>
            </table>
            <div class="scroll-table" style="height: calc(100% - 31px);">
                <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body table-defeito">
                    <tbody>
                        <tr 
                            ng-repeat="
                                defeito in vm.TalaoDefeito.API.DEFEITOS.FILTERED = (vm.TalaoDefeito.API.DEFEITOS.DADOS 
                                | find: {
                                    model : vm.TalaoDefeito.API.DEFEITOS.M_FILTRO,
                                    fields : [
                                        'DEFEITO_ID',
                                        'DEFEITO_DESCRICAO'
                                    ]
                                }                                
                                | orderBy : ['DESCRICAO'])
                            "
                            tabindex="0" 
                            ng-focus="vm.TalaoDefeito.API.DEFEITOS.SELECTED != defeito ? vm.TalaoDefeito.API.DEFEITOS.selecionar(defeito) : ''"
                            ng-click="vm.TalaoDefeito.API.DEFEITOS.SELECTED != defeito ? vm.TalaoDefeito.API.DEFEITOS.selecionar(defeito) : ''"
                            ng-class="{'selected' : vm.TalaoDefeito.API.DEFEITOS.SELECTED == defeito }"
                            ng-keydown="vm.TalaoDefeito.API.DEFEITOS.keydown(defeito,$event)"
                            >
                            <td class="wid-operador operador-defeito">@{{ defeito.DEFEITO_ID }} - @{{ defeito.DEFEITO_DESCRICAO }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>        
        
        <table class="table table-striped table-condensed table-lc table-registrar-defeito">
            <tbody>
                <tr>
                    <td class="descricao">Defeito</td>
                    <td><b>@{{ vm.TalaoDefeito.API.DEFEITOS.SELECTED.DEFEITO_ID }} - @{{ vm.TalaoDefeito.API.DEFEITOS.SELECTED.DEFEITO_DESCRICAO }}</b></td>
                </tr>
                <tr>
                    <td class="descricao">Quantidade:</td>
                    <td>
                        <input 
                            type="number" 
                            placeholder="Informe a Quantidade de Defeitos" 
                            form-validade="true"
                            required
                            min="0.0001" 
                            max="@{{ (vm.TalaoDetalhe.SELECTED.QUANTIDADE - vm.TalaoDetalhe.SELECTED.QUANTIDADE_DEFEITO).toFixed(4) }}" 
                            step="0.0001"
                            ng-model="vm.TalaoDefeito.OBJ_EDITING.QUANTIDADE"/>                        
                    </td>
                </tr>
                <tr>
                    <td class="descricao">Observação:</td>
                    <td>
                        <input 
                            type="text" 
                            placeholder="Informe uma observação" 
                            form-validade="true"
                            title="O código de barras deve ser correspondente ao da ferramenta selecionada"
                            ng-model="vm.TalaoDefeito.OBJ_EDITING.OBSERVACAO"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

@overwrite

@section('modal-end')
    </form>
@overwrite