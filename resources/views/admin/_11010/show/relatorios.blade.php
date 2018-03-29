<div class="consulta-itens">
	<div class="input-group input-group-pesquisa">
		<input type="search" name="filtro_pesquisa" class="form-control filtro-obj imp-filtrar-relatorio" placeholder="Pesquise..." autocomplete="off" autofocus="">
		<button type="button" class="input-group-addon btn-filtro btn-filtrar btn-filtrar-relatorio">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>

<div class="sub-acoes">
    <button class="btn btn-primary adicionar-relatorio" data-iduser="{{$id}}">
        <span class="glyphicon glyphicon-plus"></span>
        Alterar
    </button>
</div>   

<table class="table table-striped table-bordered table-hover lista-obj tabela-relatorio">
    <thead>
        <tr data-title="titulo">
            <th class="col-min-small-normal">ID</th>
            <th class="col-min-small-normal">Descrição</th>
            <th class="col-min-small-normal">Menu Grupo</th>
        </tr>
    </thead>
    <tbody>

        @foreach ( $relatorios as $relatorio )
        <tr tabindex="0" data-id="{{$relatorio->ID}}">
            <td class="col-min-small-normal">{{28000+$relatorio->ID}}</td>
            <td class="col-min-small-normal">{{$relatorio->NOME}}</td>
            <td class="col-min-small-normal">{{$relatorio->MENU_GRUPO}}</td>
        </tr>
        @endforeach
        
    </tbody>
</table>