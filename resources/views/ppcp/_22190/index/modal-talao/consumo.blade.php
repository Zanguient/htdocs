<fieldset class="fieldset-consumo">
		
	<legend>Consumo</legend>

    <div 
        style="
        position: absolute;
        top: -2px;
        right: 138px;
        padding: 3px;
        "
        title="Talões vinculados">
        <div
        class="label label-default" 
        style="
            margin-right: 
            5px;padding: 6px;
            padding-top: 5px;
            padding-bottom: 7px;"
        ng-repeat="componente in vm.Talao.SELECTED.COMPONENTES">
            @{{ componente.REMESSA_TALAO_ID }} / @{{ componente.PROGRAMACAO_STATUS_DESCRICAO }} 
            <button 
                style="margin-right: -5px;"
                type="button" 
                class="btn btn-danger btn-xs" 
                ng-click="vm.TalaoConsumo.componenteAlocadoDelete(componente)"
                ng-if="componente.ALOCADO == 1" 
                ng-disabled="!vm.TalaoProduzir.check('pausar').status"
                ttitle="Excluir alocação">x</button>
        </div>
    </div>        
    <button 
        type="button" 
        id="registrar-componente" 
        class="btn btn-sm btn-warning" 
        data-hotkey="Alt+C" 
        ng-disabled="!vm.TalaoProduzir.check('pausar').status" 
        ng-click="vm.TalaoConsumo.componenteModalOpen()"
        style="
            position: absolute;
            top: 0;
            right: 0;
            padding: 3px;
        ">
            <span class="glyphicon glyphicon-edit"></span>
            Registrar Componente
        </button> 
    
    <div class="resize table-consumo">
        <div class="table-ec">
            <table class="table table-striped table-bordered table-low">
                <thead>
                    <tr>
                        <th></th>
                        <th title="Id do Consumo">Id. Cons.</th>
                        <th class="wid-produto">Produto</th>
                        <th class="text-center">Tam.</th>
                        <th class="text-right" ttitle="Quantidade projetada para consumo">Qtd. Proj.</th>
                        <th class="text-right" ttitle="Quantidade alocada">Qtd. Aloc.</th>
                        <th class="text-right" ttitle="Quantidade consumoida">Qtd. Cons.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="consumo in vm.Talao.SELECTED.CONSUMOS | orderBy : ['ESTOQUE_STATUS','FAMILIA_ID', 'CONSUMO_STATUS*1','CONSUMO_ID']"
                        ng-focus="vm.TalaoConsumo.SELECTED != consumo ? vm.TalaoConsumo.selectionar(consumo) : ''"
                        ng-click="vm.TalaoConsumo.SELECTED != consumo ? vm.TalaoConsumo.selectionar(consumo) : ''"
                        ng-class="{'selected' : vm.TalaoConsumo.SELECTED == consumo }"
                        tabindex="0" data-componente="@{{ consumo.COMPONENTE }}" consumo-id="@{{ consumo.CONSUMO_ID }}" 
                        >
                        <td style="text-align: center;">
                            <i 
                                class="fa status-consumo-@{{ consumo.ESTOQUE_STATUS }}" 
                                style="font-size: 14px;"
                                ng-class="{
                                    'fa-check-circle' : consumo.CONSUMO_STATUS == 1,
                                    'fa-circle'       : consumo.CONSUMO_STATUS == 0 
                                }"
                            ></i>
                        </td>
                        <td>
                            @{{ consumo.CONSUMO_ID }}
                        </td>
                        <td class="wid-produto" autotitle>
                            <a title="Clique aqui para consultar o estoque deste produto" href="{{ url('/_15060?PRODUTO_ID=') }}@{{ consumo.PRODUTO_ID }}&LOCALIZACAO_ID=@{{ consumo.LOCALIZACAO_ID }}" target="_blank">@{{ consumo.PRODUTO_ID }}</a> - 
                            @{{ consumo.PRODUTO_DESCRICAO }}                                   
                        </td>
                        <td class="text-center"	>
                            @{{ consumo.TAMANHO_DESCRICAO }}
                        </td>
                        <td class="text-right text-lowercase">   
                            @{{ consumo.QUANTIDADE_PROJETADA | number: 4 }} @{{ consumo.UM }}
                        </td>
                        <td class="text-right text-lowercase">   

                            <span
                                style="float: left"
                                ng-if="consumo.ALOCACOES.length > 0"
                                class="item-popover glyphicon glyphicon-alert alocado-show" 
                                data-toggle="popover" 
                                data-placement="top" 
                                title="Itens Alocados"
                                data-element-content="#info-alocados-@{{ consumo.CONSUMO_ID }}"
                            ></span>
                            <div id="info-alocados-@{{ consumo.CONSUMO_ID }}" style="display: none">
                                <div class="alocado-content">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Identificação</th>
                                                <th title="Localização de Estoque da Peça">Localização</th>
                                                <th class="text-right">Qtd.</th>
                                                <th title="Observações">Obs.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="alocado in consumo.ALOCACOES">
                                                <td style="padding-top: 2px; padding-bottom: 2px; vertical-align: middle;">@{{ alocado.TIPO }} / @{{ alocado.TABELA_ID || "-" | lpad : [8,'0'] }}</td>
                                                <td style="padding-top: 2px; padding-bottom: 2px; vertical-align: middle;">@{{ alocado.LOCALIZACAO_ID }} - @{{ alocado.LOCALIZACAO_DESCRICAO }}</td>
                                                <td style="padding-top: 2px; padding-bottom: 2px; vertical-align: middle;" class="text-right um">@{{ alocado.QUANTIDADE | number: 4 }} @{{ alocado.UM }} @{{ alocado.QUANTIDADE_ALTERNATIVA > 0 ? '/ ' : '' }}@{{ alocado.QUANTIDADE_ALTERNATIVA > 0 ? alocado.QUANTIDADE_ALTERNATIVA : null | number : 2 }}@{{ alocado.QUANTIDADE_ALTERNATIVA > 0 ? ' ' + alocado.UM_ALTERNATIVA + ' ' : '' }}</td>
                                                <td style="padding-top: 2px; padding-bottom: 2px; vertical-align: middle;">@{{ alocado.OB == '' || alocado.OB == 0  ? '' : 'OB: ' + alocado.OB }}</td>
                                            </tr>
                                        </tbody>
                                    </table>                                      

                                </div>
                            </div>                              
                            
                            @{{ consumo.QUANTIDADE_ALOCADA | number: 4 }} @{{ consumo.UM }}
                        </td>
                        <td class="text-right text-lowercase">
                            @{{ consumo.QUANTIDADE_CONSUMIDA | number: 4 }} @{{ consumo.UM }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
	
	<ul class="legenda">
        <li>
            <div class="texto-legenda">Status:</div>
        </li>           
		<li>
			<i class="fa fa-circle status-consumo-0" style="font-size: 14px;float: left;margin-right: 2px;margin-top: -1px;"></i>  
			<div class="texto-legenda">Estoque indisponível |</div>  
		</li>
		<li>
			<i class="fa fa-circle status-consumo-1" style="font-size: 14px;float: left;margin-right: 2px;margin-top: -1px;"></i>  
			<div class="texto-legenda">Estoque disponível | </div> 
		</li>
		<li>
			<i class="fa fa-check-circle status-consumo-1" style="font-size: 14px;float: left;margin-right: 2px;margin-top: -1px;"></i>  
			<div class="texto-legenda">Consumido |</div>  
		</li>
		<li>
        <span class="glyphicon glyphicon-alert" style="font-size: 11px;float: left;margin-right: 2px;margin-top: -1px;"></span>
			<div class="texto-legenda">Materia-prima alocada</div>
		</li>
                  
	</ul>
	
</fieldset>