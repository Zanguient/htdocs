<fieldset class="fieldset-consumo">
		
	<legend>Consumo</legend>

    <div class="resize table-consumo">
        <div class="table-ec">
            <table class="table table-striped table-bordered table-low">
                <thead>
                    <tr>
                        <th></th>
                        <th title="Id do Consumo">Id. Cons.</th>
                        <th class="wid-produto">Produto</th>
                        <th class="text-center">Tam.</th>
                        <th class="text-right" title="Quantidade projetada para consumo">Qtd. Proj.</th>
                        <th class="text-right" title="Quantidade consumoida">Qtd. Cons.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="consumo in vm.Talao.SELECTED.CONSUMOS | orderBy : ['FAMILIA_ID', 'CONSUMO_ID']"
                        ng-focus="vm.TalaoConsumo.SELECTED != consumo ? vm.TalaoConsumo.selectionar(consumo) : ''"
                        ng-click="vm.TalaoConsumo.SELECTED != consumo ? vm.TalaoConsumo.selectionar(consumo) : ''"
                        ng-class="{'selected' : vm.TalaoConsumo.SELECTED == consumo }"
                        tabindex="0" data-componente="@{{ consumo.COMPONENTE }}" consumo-id="@{{ consumo.CONSUMO_ID }}" 
                        >
                        <td class="t-status status-@{{ consumo.FAMILIA_ID == 6 ? consumo.CONSUMO_STATUS : ((consumo.ESTOQUE_SALDO >= consumo.QUANTIDADE_SALDO) ? '1' : '0') }}"></td>
                        <td>
                            @{{ consumo.CONSUMO_ID }}
                        </td>
                        <td class="wid-produto" autotitle>
                            <a title="Clique aqui para consultar o estoque deste produto" href="{{ url('/_15060?PRODUTO_ID=') }}@{{ consumo.PRODUTO_ID }}&LOCALIZACAO_ID=@{{ consumo.FAMILIA_ID == 6 ? consumo.LOCALIZACAO_ID : consumo.LOCALIZACAO_ID_PROCESSO }}" target="_blank">@{{ consumo.PRODUTO_ID }}</a> - 
                            @{{ consumo.PRODUTO_DESCRICAO }}      
                        </td>
                        <td class="text-center"	>
                            @{{ consumo.TAMANHO_DESCRICAO }}
                        </td>
                        <td class="text-right um">   
                            @{{ consumo.QUANTIDADE_PROJETADA | number: 4 }} @{{ consumo.UM }}
                        </td>
                        <td class="text-right um">
                            @{{ consumo.QUANTIDADE_CONSUMIDA | number: 4 }} @{{ consumo.UM }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
	
	<ul class="legenda">
		<li>
			<div class="cor-legenda consumo-status-0"></div>
			<div class="texto-legenda">Indisponível</div>
		</li>
		<li>
			<div class="cor-legenda consumo-status-1"></div>
			<div class="texto-legenda">Disponível</div>
		</li>
	</ul>
	
</fieldset>