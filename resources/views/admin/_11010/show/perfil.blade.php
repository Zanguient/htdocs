<div class="consulta-itens">
	<div class="input-group input-group-pesquisa">
		<input type="search" name="filtro_pesquisa" class="form-control filtro-obj imp-filtrar-perfil" placeholder="Pesquise..." autocomplete="off" autofocus="">
		<button type="button" class="input-group-addon btn-filtro btn-filtrar btn-filtrar-perfil">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>

<div class="sub-acoes">
    <button class="btn btn-primary adicionar-perfil" data-iduser="{{$id}}">
        <span class="glyphicon glyphicon-plus"></span>
        Alterar
    </button>
</div>    

<table class="table table-striped table-bordered table-hover lista-obj tabela-perfil">
    <thead>
    <tr data-title="titulo">
        <th class="col-min-small-normal">ID</th>
        <th class="col-min-small-normal">Descrição</th>
    </tr>
    </thead>
    <tbody>

        @foreach ( $perfils as $perfil )
        <tr tabindex="0" data-id="{{$perfil->ID}}">
            <td class="col-min-small-normal">{{$perfil->ID}}</td>
            <td class="col-min-small-normal">{{$perfil->DESCRICAO}}</td>
        </tr>
        @endforeach

    </tbody>
</table>