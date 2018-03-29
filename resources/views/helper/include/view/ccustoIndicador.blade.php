<div class="ccustoindicador-container">
	<div class="ccustoindicador">
		<div class="form-group">
			<label for="ccustoindicador-descricao">Centro de custo:</label>
			<div class="input-group">
				<input type="search" name="ccustoindicador_descricao" id="ccustoindicador-descricao" class="form-control input-medio {{ $CLASSE }}" autocomplete="off" autofocus required />            
				<button type="button" class="input-group-addon btn-filtro btn-filtro-ccustoindicador search-button  {{ $CLASSE }}"><span class="fa fa-search"></span></button>
				<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-ccustoindicador search-button  {{ $CLASSE }}"><span class="fa fa-close"></span></button>
			</div>
			<div class="pesquisa-res-container lista-ccustoindicador-container">
				<div class="pesquisa-res lista-ccustoindicador">
				</div>
			</div>
			<input type="hidden" class="_ccustoindicador_id" name="_ccustoindicador_id" />
		</div>
	</div>
</div>	