<div class="consulta-itens">
	<div class="input-group input-group-pesquisa">
		<input type="search" name="filtro_pesquisa" class="form-control filtro-obj imp-filtrar-perfil" placeholder="Pesquise..." autocomplete="off" autofocus="">
		<button type="button" class="input-group-addon btn-filtro btn-filtrar btn-filtrar-perfil">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>

    <div class="sub-acoes">
        <button class="btn btn-success gravar-perfil" data-iduser="{{$id}}">
            <span class="glyphicon btn-success"></span>
            Gravar
        </button>

        <button class="btn btn-danger canselar-perfil" data-iduser="{{$id}}">
            <span class="glyphicon btn-success"></span>
            Cancelar
        </button>
    </div>    

<table class="table table-striped table-bordered table-hover lista-obj tabela-perfil tabela-perfil-editar">
    <thead>
    <tr data-title="titulo">
        <th class="col-min-small-normal">ID</th>
        <th class="col-min-small-normal">Descrição</th>
        <th class="col-min-small-normal">Incluir</th>
    </tr>
    </thead>
    <tbody>

        @foreach ( $perfils as $perfil )
        <tr tabindex="0" data-id="{{$perfil->ID}}" data-flag="{{$perfil->FLAG}}" class="chec-perfil-editar">
            
            <td class="col-min-small-normal">{{$perfil->ID}}</td>
            <td class="col-min-small-normal">{{$perfil->DESCRICAO}}</td>
            <td class="col-min-small-normal"><span class="glyphicon {{$perfil->FLAG ? 'glyphicon-ok' : 'glyphicon-remove'}}" style="color: {{$perfil->FLAG ? 'green' : 'red'}};"></td>
            
            <input type="hidden" class="FLAG" value="{{$perfil->FLAG}}">
            <input type="hidden" class="CHEC" value="{{$perfil->CHEC}}">
            
        </tr>
        @endforeach

    </tbody>
</table>