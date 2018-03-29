<div class="consulta-itens">
	<div class="input-group input-group-pesquisa">
		<input type="search" name="filtro_pesquisa" class="form-control filtro-obj imp-filtrar-ccusto" placeholder="Pesquise..." autocomplete="off" autofocus="">
		<button type="button" class="input-group-addon btn-filtro btn-filtrar btn-filtrar-ccusto">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>

<table class="table table-striped table-bordered table-hover lista-obj tabela-ccusto">
    <thead>
    <tr data-title="titulo">
        <th class="col-min-small-normal">Descrição</th>
        <th class="col-min-small-normal">C. Custo</th>
    </tr>
    </thead>
    <tbody>

        @foreach ( $custo as $ccusto )
        <tr tabindex="0" data-id="{{$ccusto->CCUSTO}}">
            <td class="col-min-small-normal">{{$ccusto->DESCRICAO}}</td>
            <td class="col-min-small-normal">{{$ccusto->CCUSTO}}</td>
        </tr>
        @endforeach

    </tbody>
</table>