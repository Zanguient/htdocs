<div class="turno-container">
	<div class="turno">
		<div class="form-group">
			<label for="turno-descricao">Turno:</label>
			<div class="input-group">
				<input type="search" name="turno_descricao" id="turno-descricao" class="form-control input-medio {{ $CLASSE }}" autocomplete="off" required />
				<button type="button" class="input-group-addon btn-filtro btn-filtro-turno search-button  {{ $CLASSE }}"><span class="fa fa-search"></span></button>
				<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro-turno search-button  {{ $CLASSE }}" style="display: none;" ><span class="fa fa-close"></span></button>
			</div>
			<div class="pesquisa-res-container lista-turno-container">
				<div class="pesquisa-res lista-turno">
				</div>
			</div>
			<input type="hidden" class="_turno_id" name="_turno_id" />
		</div>
	</div>
</div>	