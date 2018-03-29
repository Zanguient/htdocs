<div class="consulta-itens">
	<div class="input-group input-group-pesquisa">
		<input type="search" name="filtro_pesquisa" class="form-control filtro-obj imp-filtrar-parametro" placeholder="Pesquise..." autocomplete="off" autofocus="">
		<button type="button" class="input-group-addon btn-filtro btn-filtrar btn-filtrar-parametro">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>

<table class="table table-striped table-bordered table-hover lista-obj tabela-parametro">
    <thead>
    <tr data-title="titulo">
        <th class="col-min-small-normal">Descrição</th>
        <th class="col-min-small-normal">Valor</th>
        <th class="col-min-small-normal">Comentário</th>
    </tr>
    </thead>
    <tbody>

        @foreach ( $permicoes as $permicao )
        <tr tabindex="0" data-id="{{$permicao->ID}}">
            <td class="col-min-small-normal">{{$permicao->PARAMETRO}}</td>
            <td title="{{$permicao->VALOR_EXT}}" class="col-min-small-normal">@php if(strlen($permicao->VALOR_EXT) > 3){echo substr($permicao->VALOR_EXT, 0, 3).'...';}else{echo $permicao->VALOR_EXT;}
            </td>
            <td class="col-min-small-normal">{{$permicao->COMENTARIO}}</td>
        </tr>
        @endforeach

    </tbody>
</table>