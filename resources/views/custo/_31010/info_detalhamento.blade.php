<div class="form-group">
        <label  title="Quantidade Pedido">Modelo.:</label>
        <div class="input-group" style="width: 300px;">
            <input type="text"  style="width: 100% !important; height: 36px; border-radius: 5px;" class="form-control" readonly="" ng-model="vm.Item.ConsultaModelo.selected.DESCRICAO">
        </div>
    </div>

    <div class="form-group">
        <label  title="Quantidade Pedido">Cor.:</label>
        <div class="input-group" style="width: 250px;">
            <input type="text"  style="width: 100% !important; height: 36px; border-radius: 5px;" class="form-control" readonly="" ng-model="vm.Item.ConsultaCor.selected.DESCRICAO">
        </div>
    </div>

    <div class="form-group">
        <label  title="Quantidade Pedido">Tamanho.:</label>
        <div class="input-group" style="width: 200px;">
            <input type="text"  style="width: 100% !important; height: 36px; border-radius: 5px;" class="form-control" readonly="" ng-model="vm.Item.ConsultaTamanho.selected.DESCRICAO">
        </div>
    </div>

    <div class="form-group">
        <label  title="Tempo Operacional do Modelo">Tempo Oper.:</label>
        <div class="input-group" style="width: 130px;">
            <input type="text"  style="width: 100% !important; height: 36px; border-radius: 5px;" class="form-control" readonly="" ng-model="vm.Item.TEMPO_MODELO" ng-value="vm.Item.TEMPO_MODELO | number:4">
        </div>
    </div>

    <div class="form-group">
        <label  title="Tempo Operacional do Modelo">Tempo Setup:</label>
        <div class="input-group" style="width: 130px;">
            <input type="text"  style="width: 100% !important; height: 36px; border-radius: 5px;" class="form-control" readonly="" ng-model="vm.Item.SETUP_MODELO" ng-value="vm.Item.SETUP_MODELO | number:4">
        </div>
    </div>

    <div class="form-group">
        <label  title="Tempo Operacional do Modelo">Estações:</label>
        <div class="input-group" style="width: 130px;">
            <input type="text"  style="width: 100% !important; height: 36px; border-radius: 5px;" class="form-control" readonly="" ng-model="vm.Item.Ficha.ESTACOES" ng-value="vm.Item.Ficha.ESTACOES | number:0">
        </div>
    </div>

    <div class="form-group">
        <label  title="Tempo Operacional do Modelo">% de Perda:</label>
        <div class="input-group" style="width: 130px;">
            <input type="text"  style="width: 100% !important; height: 36px; border-radius: 5px;" class="form-control" readonly="" ng-model="vm.Item.PERCENTUAL_PERDA" ng-value="(vm.Item.PERCENTUAL_PERDA * 100) | number:2">
        </div>
    </div>
    
    <div class="form-group">
        <label  title="Quantidade Pedido">Quantidade:</label>
        <div class="input-group left-icon" style="width: 130px;">
            <div class="input-group-addon">@{{vm.Item.UnidadeMedida}}</div>
            <input type="number"  style="height: 36px;" ng-min="1" min="1" class="form-control" ng-keyup="vm.Item.keyupQuantidade()" readonly="" ng-model="vm.Item.Quantidade">
        </div>
    </div>

    <div class="form-group">
        <label  title="Custo Unitário do Produto">Custo Unitário:</label>
        <div class="input-group left-icon" style="width: 170px;">
            <div class="input-group-addon" style="background-color: #3ec33e;color: white; border-color: #3ec33e;">R$</div>
            <input type="text"  style="height: 36px;" class="form-control" readonly="" ng-model="vm.Item.Cst_u_Produto" ng-value="vm.Item.Cst_u_Produto | number:5">
        </div>
    </div>

    <div class="form-group">
        <label  title="Custo Total do Produto">Custo Total:</label>
        <div class="input-group left-icon" style="width: 170px;">
            <div class="input-group-addon" style="background-color: #3ec33e;color: white; border-color: #3ec33e;">R$</div>
            <input type="text"  style="height: 36px;" class="form-control" readonly="" ng-model="vm.Item.Cst_t_Produto" ng-value="vm.Item.Cst_t_Produto | number:5">
        </div>
    </div>

    <div class="form-group">
        <label  title="Margem de Contribuição">Markup:</label>
        <div class="input-group left-icon" style="width: 110px;">
            <div class="input-group-addon">%</div>
            <input type="text"  style="height: 34px;" class="form-control" readonly="" ng-value="vm.Item.MarckUp | number:2">
        </div>
    </div>                 

    <div class="form-group" style="width: 300px;">
        <label  title="Margem de Contribuição" style="display: block;">Margem de Contribuição:</label>
        <div class="input-group left-icon" style="width: 140px; display: inline-table;">
            <div class="input-group-addon">%</div>
            <input type="text"  style="height: 34px;" class="form-control" readonly="" ng-value="vm.Item.Contribuicao | number:4">
        </div>
        <div class="input-group left-icon" style="width: 140px; display: inline-table;">
            <div class="input-group-addon">R$</div>
            <input type="text"  style="height: 34px;" class="form-control" readonly="" ng-model="vm.Item.ContribuicaoReal" ng-value="vm.Item.ContribuicaoReal | number:2">
        </div>
    </div>              

    <div class="form-group">
        <label title="Custo Setup">C. Setup:</label>
        <div class="input-group left-icon" style="width: 170px;">
            <div class="input-group-addon">R$</div>
            <input type="text"  style="height: 36px;" class="form-control" readonly="" ng-model="vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoSetup.Valor" ng-value="vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoSetup.Valor | number:5">
        </div>
    </div>

    <div class="form-group">
        <label title="Custo Operacional">C. Operacional:</label>
        <div class="input-group left-icon" style="width: 170px;">
            <div class="input-group-addon">R$</div>
            <input type="text"  style="height: 36px;" class="form-control" readonly="" ng-model="vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoOperacional.Valor" ng-value="vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoOperacional.Valor | number:5">
        </div>
    </div>

    <div class="form-group">
        <label title="Custo Indireto do Próprio Setor">C. Indireto Próprio:</label>
        <div class="input-group left-icon" style="width: 170px;">
            <div class="input-group-addon">R$</div>
            <input type="text"  style="height: 36px;" class="form-control" readonly="" ng-model="vm.Item.Gasto.Custo.Indireto.Proprio.Valor" ng-value="vm.Item.Gasto.Custo.Indireto.Proprio.Valor | number:5">
        </div>
    </div>

    <div class="form-group">
        <label title="Custo Indireto Absorvido de Outros Setores">C. Indireto Absorvido:</label>
        <div class="input-group left-icon" style="width: 170px;">
            <div class="input-group-addon">R$</div>
            <input type="text"  style="height: 36px;" class="form-control" readonly="" ng-model="vm.Item.Gasto.Custo.Indireto.Absorvido.Valor" ng-value="vm.Item.Gasto.Custo.Indireto.Absorvido.Valor | number:5">
        </div>
    </div>

    <div class="form-group">
        <label title="Custo da Matéria-prima">C. Matéria-prima:</label>
        <div class="input-group left-icon" style="width: 170px;">
            <div class="input-group-addon">R$</div>
            <input type="text"  style="height: 36px;" class="form-control" readonly="" ng-model="vm.Item.Gasto.Custo.MateriaPrima.Valor" ng-value="vm.Item.Gasto.Custo.MateriaPrima.Valor | number:5">
        </div>
    </div>

    <div class="form-group">
        <label  title="Imposto de Renda">IR:</label>
        <div class="input-group left-icon" style="width: 170px;">
            <div class="input-group-addon" style="background-color: #3ec33e;color: white; border-color: #3ec33e;">R$</div>
            <input type="text"  style="height: 34px;" class="form-control" readonly="" ng-model="vm.Item.ImpostoDeRenda" ng-value="vm.Item.ImpostoDeRenda | number:2">
        </div>
    </div>