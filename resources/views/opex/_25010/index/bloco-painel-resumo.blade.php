<fieldset>

	<legend>{{ Lang::get($menu.'.legend-resumo') }}</legend>

	<div 
		class="geral-container"
		ng-if="vm.formulario.TIPO != 3">

		<div class="lbl-grupo">
			<label class="lbl-rotulo">{{ Lang::get($menu.'.label-qtd-esperada') }}:</label>
			<label class="lbl-dado">@{{ vm.painel.QTD_RESPOSTA_ESPERADA }}</label>
		</div>

		<div class="lbl-grupo">
			<label class="lbl-rotulo">{{ Lang::get($menu.'.label-qtd-respondida') }}:</label>
			<label class="lbl-dado">@{{ vm.painel.QTD_RESPONDIDA }}</label>
		</div>

		<div class="lbl-grupo">
			<label class="lbl-rotulo">{{ Lang::get($menu.'.label-faltam-responder') }}:</label>
			<label class="lbl-dado">@{{ vm.painel.QTD_RESPOSTA_ESPERADA - vm.painel.QTD_RESPONDIDA }}</label>
		</div>
	</div>

	<div 
		class="geral-container"
		ng-if="vm.formulario.TIPO == 3">

		<div class="lbl-grupo">
			<label class="lbl-rotulo">{{ Lang::get($menu.'.label-qtd-pesquisa') }}:</label>
			<label class="lbl-dado">@{{ vm.painel.QTD_PESQUISA }}</label>
		</div>
		
		<div class="lbl-grupo">
			<label class="lbl-rotulo">{{ Lang::get($menu.'.label-media-satisfacao') }}:</label>
			<label class="lbl-dado">@{{ vm.painel.MEDIA_SATISFACAO | number:1 }}</label>
		</div>

		<div class="lbl-grupo">
			<label class="lbl-rotulo">{{ Lang::get($menu.'.label-media-nota-delfa') }}:</label>
			<label class="lbl-dado">@{{ vm.painel.MEDIA_DELFA | number:1 }}</label>
		</div>
	</div>

	<div class="geral-grafico-container">
	    <div id="grafico-qtd-respondida"></div>
	    <div id="grafico-satisf-geral"></div>
	    <div id="grafico-satisf-delfa"></div>
	</div>

</fieldset>