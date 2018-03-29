<button type="button" style="margin: 1px;" class="btn btn-primary" ng-click="vm.export1('tabela-preco','Preco_por_Modelo.csv')">
    <span class="glyphicon glyphicon-save"></span> 
    Exportar para CSV
</button>

<button type="button" style="margin: 1px;" class="btn btn-primary" ng-click="vm.export2('tabela-preco','Preco_por_Modelo.xls')">
    <span class="glyphicon glyphicon-save"></span> 
    Exportar para XLS
</button>

<button type="button" style="margin: 1px;" class="btn btn-primary" ng-click="vm.export3('div-preco',vm.Empresa.SELECTED.EMPRESA_RAZAO_SOCIAL)">
    <span class="glyphicon glyphicon-save"></span> 
    Exportar para PDF
</button>

<button type="button" style="margin: 1px;" class="btn btn-primary" ng-click="vm.Imprimir('div-preco',vm.Empresa.SELECTED.EMPRESA_RAZAO_SOCIAL)">
    <span class="glyphicon glyphicon-print"></span> 
    Imprimir
</button>

<input 
    type="text" 
    class="form-control ng-pristine ng-valid ng-empty ng-touched" 
    ng-model="vm.Empresa.MODELO_PRECO_FILTRO" 
    ng-init="vm.Empresa.MODELO_PRECO_FILTRO = ''" 
    placeholder="Filtragem por Produto..."
    style="
        margin-bottom: 3px;
        margin-top: 5px;
        height: 23px;
        width: 100%;
    "
    />
<div class="table-ec" style="height: calc(100% - 105px);"  id="div-preco">
    <table class="table table-striped table-bordered table-condensed" id="tabela-preco" style="border-collapse: collapse; width: 100%; font-size: 9px;">
        <thead>
            <tr style="background-color: #337ab7;">
                <th  style="border-top: 1px solid #ddd; border: 1px solid #ddd;" class="text-center">Tipo</th>
                <th  style="border-top: 1px solid #ddd; border: 1px solid #ddd;" >Modelo / Produto</th>
                <th  style="border-top: 1px solid #ddd; border: 1px solid #ddd;" class="text-center">Tam.</th>
                <th  style="border-top: 1px solid #ddd; border: 1px solid #ddd;" class="text-right">Preço (R$)</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="
                    item in vm.Empresa.SELECTED.MODELOS_PRECO
                    | find: {
                        model : vm.Empresa.MODELO_PRECO_FILTRO,
                        fields : [    
                            'TIPO',
                            'TIPO_DESCRICAO',
                            'ID',
                            'DESCRICAO',
                            'TAMANHO_DESCRICAO'
                        ]
                    }
                    | orderBy : ['TIPO','DESCRICAO', 'TAMANHO_DESCRICAO']                
                ">
                <td style="border-top: 1px solid #ddd; border: 1px solid #ddd; " class="text-center" title="@{{ item.TIPO_DESCRICAO }}">@{{ item.TIPO }}</td>
                <td style="border-top: 1px solid #ddd; border: 1px solid #ddd; " >@{{ item.ID | lpad : [6,0] }} - @{{ item.DESCRICAO }}</td>
                <td  style="border-top: 1px solid #ddd; border: 1px solid #ddd;" class="text-center">@{{ item.TAMANHO_DESCRICAO }}</td>
                <td  style="border-top: 1px solid #ddd; border: 1px solid #ddd;" class="text-lowercase text-right">@{{ item.PRECO | number : 5 }}</td>
            </tr>
        </tbody>
    </table>
</div>