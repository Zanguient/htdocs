@extends('helper.include.view.modal', ['id' => 'modal-taloes-extra', 'class_size' => 'modal-big'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.TaloesExtra.Gravar()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Geração de Talões Extras
	</h4>

@overwrite

@section('modal-header-right')

    <button type="submit" class="btn btn-success" data-hotkey="f10" data-loading-text="Gravando...">
		<span class="glyphicon glyphicon-ok"></span> 
		Gravar
	</button>
	<button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc">
		<span class="glyphicon glyphicon-chevron-left"></span> 
		Voltar
	</button>

@overwrite

@section('modal-body')
    <fieldset class="fieldset-left">
        <legend>Percentual de Defeitos por Sku</legend>
        <table datatable="ng" dt-options="vm.dtOptions" class="table-bordered table-striped row-border hover">
            <thead>
                <tr>
                    <th>Modelo</th>
                    <th>Cor</th>
                    <th>Tam.</th>
                    <th class="text-right">% Base</th>
                    <th class="text-right input">Percentual</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    ng-repeat="item in vm.TaloesExtra.DADOS.SKUS | orderBy:['-PERCENTUAL_BASE*1','MODELO_DESCRICAO', 'COR_DESCRICAO', 'TAMANHO_DESCRICAO*1']"
                    ng-click="vm.TaloesExtra.DADOS.SELECTED = item"
                    ng-class="{'selected' : vm.TaloesExtra.DADOS.SELECTED == item}"
                    >
                    <td>@{{ item.MODELO_ID | lpad : [4,'0'] }} - @{{ item.MODELO_DESCRICAO }}</td>
                    <td>@{{ item.COR_ID | lpad : [4,'0'] }} - @{{ item.COR_DESCRICAO }}</td>
                    <td>@{{ item.TAMANHO_DESCRICAO }}</td>
                    <td class="text-right">
                        @{{ item.PERCENTUAL_BASE | number: 2 }} %
                        
                    
                        <span class="glyphicon glyphicon-info-sign operacao-descricao"
                        data-toggle="popover" 
                        data-placement="right" 
                        title="Base do Percentual de Defeitos por Remessa"
                        data-element-content="#sku-@{{ item.MODELO_ID + '-' + item.COR_ID + '-' + item.TAMANHO }}"
                        on-finish-render="bs-init"></span>    
                        <div id="sku-@{{ item.MODELO_ID + '-' + item.COR_ID + '-' + item.TAMANHO }}" style="display: none">
                            <table class="table table-striped table-bordered" style="margin-bottom: 10px">
                                <thead>
                                    <tr>
                                        <th>Remessa</th>
                                        <th>Data Remessa</th>
                                        <th class="text-center" title="Data e Hora do Ultimo Talão Produzido">Dt./Hr. Ult. Tal.</th>
                                        <th title="Quantidade produzida">Qtd. Prod.</th>
                                        <th title="Quantidade defeitos">Qtd. Def.</th>
                                        <th title="Percentual de Defeitos">% Def.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="defeito in item.DEFEITO_ORIGEM">
                                        <td>@{{ defeito.REMESSA_ID }}</td>
                                        <td>@{{ defeito.REMESSA_DATA | parseDate | date:'dd/MM/yy' }}</td>
                                        <td class="text-center">@{{ defeito.DATAHORA | parseDate | date:'HH:mm:ss dd/MM/yy' }}</td>
                                        <td class="text-right">@{{ defeito.QUANTIDADE_PRODUCAO | number: 0 }}</td>
                                        <td class="text-right">@{{ defeito.QUANTIDADE_DEFEITO | number: 0 }}</td>
                                        <td class="text-right">@{{ defeito.PERCENTUAL_DEFEITO * 100 | number: 2 }} %</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td class="text-right input">
                        <input
                            type="number" 
                            min="0" 
                            step="any"
                            string-to-number 
                            ng-model="item.PERCENTUAL" 
                            ng-value="item.PERCENTUAL_BASE"
                            ng-focus="vm.TaloesExtra.DADOS.SELECTED = item"
                        >
                    </td>
                </tr>  
            </tbody>
        </table>
    </fieldset>
    <fieldset class="fieldset-right">
        <legend>Talões Extras</legend>
        <table datatable="ng" dt-options="vm.dtOptions" class="table-bordered table-striped row-border hover">
            <thead>
                <tr>
                    <th title="Grupo de Produção">GP</th>
                    <th>Modelo</th>
                    <th>Cor</th>
                    <th>Tam.</th>
                    <th class="text-right" title="Quantidade total do sku para o Grupo de Produção">Qtd. Total</th>
                    <th class="text-right" title="Quantidade total a ser gerado">Qtd. Total + %</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    ng-repeat="item in vm.TaloesExtra.DADOS.TALOES_EXTRA | orderBy:['GP_DESCRICAO', 'MODELO_DESCRICAO', 'COR_DESCRICAO', 'TAMANHO_DESCRICAO*1']"
                    tabindex="0"
                    ng-class="{'selected' : vm.TaloesExtra.DADOS.SELECTED == item.EXTEND}"
                    ng-click="vm.TaloesExtra.DADOS.SELECTED = item.EXTEND"
                    ng-focus="vm.TaloesExtra.DADOS.SELECTED = item.EXTEND"
                    data-calc-1="@{{ item.QUANTIDADE = vm.Math.ceil((item.QUANTIDADE_TOTAL_TALOES * item.EXTEND.PERCENTUAL) / 100) }}"
                    data-calc-2="@{{ item.PERCENTUAL = item.EXTEND.PERCENTUAL }}"
                    >
                    <td>@{{ item.GP_ID | lpad : [3,'0'] }} - @{{ item.GP_DESCRICAO }}</td>
                    <td>@{{ item.MODELO_ID | lpad : [4,'0'] }} - @{{ item.MODELO_DESCRICAO }}</td>
                    <td>@{{ item.COR_ID | lpad : [4,'0'] }} - @{{ item.COR_DESCRICAO }}</td>
                    <td>@{{ item.TAMANHO_DESCRICAO }}</td>
                    <td class="text-right">@{{ item.QUANTIDADE_TOTAL_TALOES | number: 0 }}</td>
                    <td class="text-right">@{{ item.QUANTIDADE | number: 0 }}</td>
                </tr>  
            </tbody>
        </table>
    </fieldset>

@overwrite

@section('modal-end')
    </form>
@overwrite