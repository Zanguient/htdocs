<div class="consulta-itens">
	<div class="input-group input-group-pesquisa">
		<input type="search" name="filtro_pesquisa" class="form-control filtro-obj imp-filtrar-menu" placeholder="Pesquise..." autocomplete="off" autofocus="">
		<button type="button" class="input-group-addon btn-filtro btn-filtrar btn-filtrar-menu">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>

<div class="sub-acoes">
    <button class="btn btn-primary adicionar-menu" data-iduser="{{$id}}">
        <span class="glyphicon glyphicon-plus"></span>
        Alterar
    </button>
</div>   

<table class="table table-striped table-bordered table-hover lista-obj tabela-menu">
    <thead>
        <tr data-title="titulo">
            <th class="col-min-small-normal">Descrição</th>
            <th class="col-min-small-normal">Controle</th>
            <th class="col-min-small-normal">Grupo</th>
            <th class="col-min-small-normal">Subgrupo</th>
            <th class="col-min-small-normal">Negar</th>

            <th class="col-min-small-normal">Incluir</th>
            <th class="col-min-small-normal">Alterar</th>
            <th class="col-min-small-normal">Excluir</th>
            <th class="col-min-small-normal">Imprimir</th>            

            <th class="col-min-small-normal">Origem</th>
        </tr>
    </thead>
    <tbody>

        @foreach ( $menus as $menu )
        <tr tabindex="0" data-id="{{$menu->ID}}">
            <td class="col-min-small-normal">{{$menu->DESCRICAO}}</td>
            <td class="col-min-small-normal">{{$menu->CONTROLE}}</td>
            <td class="col-min-small-normal">{{$menu->GRUPO}}</td>
            <td class="col-min-small-normal">{{$menu->SUBGRUPO}}</td>
            
            <td class="col-min-small-normal"><span class="glyphicon {{$menu->NEGAR  ? 'glyphicon-ok' : 'glyphicon-remove'}}" style="color: {{$menu->NEGAR ? 'green' : 'red'}};"></span></td>

            <td class="col-min-small-normal"><span class="glyphicon {{$menu->INCLUIR ? 'glyphicon-ok' : 'glyphicon-remove'}}" style="color: {{$menu->INCLUIR ? 'green' : 'red'}};"></span></td>
            <td class="col-min-small-normal"><span class="glyphicon {{$menu->ALTERAR ? 'glyphicon-ok' : 'glyphicon-remove'}}" style="color: {{$menu->ALTERAR ? 'green' : 'red'}};"></span></td>
            <td class="col-min-small-normal"><span class="glyphicon {{$menu->EXCLUIR ? 'glyphicon-ok' : 'glyphicon-remove'}}" style="color: {{$menu->EXCLUIR ? 'green' : 'red'}};"></span></td>
            <td class="col-min-small-normal"><span class="glyphicon {{$menu->IMPRIMIR ? 'glyphicon-ok' : 'glyphicon-remove'}}" style="color: {{$menu->IMPRIMIR ? 'green' : 'red'}};"></span></td>            

            <td class="col-min-small-normal">{{$menu->ORIGEM_I.','.$menu->ORIGEM_A.','.$menu->ORIGEM_E.','.$menu->ORIGEM_M}}</td>
        </tr>
        @endforeach
        
    </tbody>
</table>