<div id="resumo-producao" class="scroll-dark collapse" aria-expanded="false">
	
	<label class="abrir-auto" title="{{ Lang::get($menu.'.abrir-auto-title') }}">{{ Lang::get($menu.'.abrir-auto-label') }}:</label>
	<input type="checkbox" class="chk-switch" checked="true" 
		   data-size="small" data-on-color="success" data-off-color="primary" data-on-text="{{ Lang::get($menu.'.abrir-auto') }}" 
		   data-off-text="{{ Lang::get($menu.'.abrir-manual') }}" data-label-width="14"
	/>
	
	<button type="button" class="btn btn-sm btn-default btn-voltar" id="fechar-resumo" data-hotkey="esc">
		<span class="glyphicon glyphicon-chevron-left"></span>
		{{ Lang::get('master.voltar') }}
	</button>
	<div class="status-container-resumo">
		<span id="status-icone-resumo" class="status-@{{ vm.TalaoProduzir.SELECTED.PROGRAMACAO_STATUS || '00' }}">
			<span 
                class="fa"
                ng-class="{
                    'fa-circle-thin' : !vm.TalaoProduzir.SELECTED,
                    'fa-circle'      : vm.TalaoProduzir.SELECTED
                }"
                >    
            </span>
		</span>
		<span id="status-resumo"> @{{ !vm.TalaoProduzir.SELECTED ? 'Selecione um talão' : vm.TalaoProduzir.SELECTED.PROGRAMACAO_STATUS_DESCRICAO }}</span>
	</div>
	<div class="modelo-container-resumo">
		<span id="modelo-label-resumo">{{ Lang::get($menu.'.modelo') }}:</span>
		<span id="modelo-resumo"> @{{ vm.TalaoProduzir.SELECTED.MODELO_DESCRICAO }}</span>
	</div>
	<div class="tempo-realizado-container-resumo">
		<span id="tempo-realizado-label-resumo">{{ Lang::get($menu.'.tempo-realiz') }}:</span>
		<span id="tempo-realizado-resumo"> @{{ vm.Filtro.GUIA_ATIVA != 'TALAO_PRODUZIR' ? '-' : vm.TalaoProduzir.SELECTED.TEMPO_REALIZADO_HUMANIZE }} </span>
	</div>
	<div class="qtd-container-resumo">
		<span id="qtd-label-resumo">{{ Lang::get($menu.'.qtd') }}:</span>
		<span id="qtd-resumo"> @{{ (vm.TalaoProduzir.SELECTED.QUANTIDADE_ALTERNATIVA > 0 ? vm.TalaoProduzir.SELECTED.QUANTIDADE_ALTERNATIVA : vm.TalaoProduzir.SELECTED.QUANTIDADE) | number: 4 }} @{{ vm.TalaoProduzir.SELECTED.QUANTIDADE_ALTERNATIVA > 0 ? vm.TalaoProduzir.SELECTED.UM_ALTERNATIVA : vm.TalaoProduzir.SELECTED.UM }}</span>
	</div>
	<div class="detalhe-container-resumo">
		<span id="detalhe-label-resumo">{{ Lang::get($menu.'.detalhamento') }}:</span>
		<div id="detalhe-resumo">
			<span>-</span>
		</div>
	</div>
    
    <div class="contador-Atualizar">
	</div>
</div>

