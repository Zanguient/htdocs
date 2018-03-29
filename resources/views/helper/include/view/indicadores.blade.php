<div class="indicadores-container">
	<div class="indicadores">
		<div class="form-group">
			<label for="indicadores-descricao">Indicador:</label>
			<div class="input-group">
				<input type="search" name="indicadores_descricao" id="indicadores-descricao" class="form-control input-medio {{ $CLASSE }}" autocomplete="off" autofocus required />
				<button type="button" class="input-group-addon btn-filtro btn-filtro-indicadores search-button  {{ $CLASSE }}"><span class="fa fa-search"></span></button>
				<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro-indicadores search-button  {{ $CLASSE }}" style="display: none;" ><span class="fa fa-close"></span></button>
			</div>
			<div class="pesquisa-res-container lista-indicadores-container">
				<div class="pesquisa-res lista-indicadores">
				</div>
			</div>
			<input type="hidden" class="_indicadores_id" name="_indicadores_id" />
		</div>
	</div>
</div>	