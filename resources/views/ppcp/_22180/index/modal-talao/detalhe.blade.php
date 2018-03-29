<fieldset class="fieldset-detalhe">
		
	<legend>Detalhamento do Talão</legend>

    <div class="resize table-detalhe">
        <div class="table-ec">
            <table class="table table-striped table-bordered table-low">
                <thead>
                    <tr>
                        <th></th>
                        <th title="Id do Consumo">Id. Talão</th>
                        <th class="wid-produto">Produto</th>
                        <th class="text-center">Tam.</th>
                        <th class="text-right" title="Quantidade projetada para produzir">Qtd. Proj.</th>
                        <th class="text-right" title="Quantidade produzida">Qtd. Prod.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="detalhe in vm.Talao.SELECTED.DETALHES | orderBy : ['REMESSA_TALAO_DETALHE_ID']"
                        ng-focus="vm.TalaoDetalhe.SELECTED != detalhe ? vm.TalaoDetalhe.pick(detalhe) : ''"
                        ng-click="vm.TalaoDetalhe.SELECTED != detalhe ? vm.TalaoDetalhe.pick(detalhe) : ''"
                        ng-class="{'selected' : vm.TalaoDetalhe.SELECTED == detalhe }"
                        tabindex="0" data-componente="@{{ detalhe.COMPONENTE }}" detalhe-id="@{{ detalhe.CONSUMO_ID }}" 
                        >
                        <td class="t-status detalhe-status-@{{ detalhe.TALAO_DETALHE_STATUS }}"></td>
                        <td>
                            @{{ detalhe.REMESSA_TALAO_DETALHE_ID }}
                        </td>
                        <td class="wid-produto" autotitle>
                            <a title="Clique aqui para consultar o estoque deste produto" href="{{ url('/_15060?PRODUTO_ID=') }}@{{ detalhe.PRODUTO_ID }}&LOCALIZACAO_ID=@{{ detalhe.LOCALIZACAO_ID }}" target="_blank">@{{ detalhe.PRODUTO_ID }}</a> - 
                            @{{ detalhe.PRODUTO_DESCRICAO }}      
                        </td>
                        <td class="text-center"	>
                            @{{ detalhe.TAMANHO_DESCRICAO }}
                        </td>
                        <td class="text-right um">   
                            @{{ detalhe.QUANTIDADE_PROJETADA | number: 4 }} @{{ detalhe.UM }}
                        </td>
                        <td class="text-right um">
                            @{{ detalhe.QUANTIDADE_PRODUCAO | number: 4 }} @{{ detalhe.UM }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
	
	<ul class="legenda">
		<li>
			<div class="cor-legenda detalhe-status-1"></div>
			<div class="texto-legenda">Em Aberto</div>
		</li>
<!--		<li>
			<div class="cor-legenda detalhe-status-2"></div>
			<div class="texto-legenda">Em Produção</div>
		</li>-->
		<li>
			<div class="cor-legenda detalhe-status-3"></div>
			<div class="texto-legenda">Produzido</div>
		</li>
		<li>
			<div class="cor-legenda detalhe-status-6"></div>
			<div class="texto-legenda">Encerrado</div>
		</li>
	</ul>
	
</fieldset>