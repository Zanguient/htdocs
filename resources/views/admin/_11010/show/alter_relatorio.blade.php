<div class="consulta-itens">
	<div class="input-group input-group-pesquisa">
		<input type="search" name="filtro_pesquisa" class="form-control filtro-obj imp-filtrar-relatorio" placeholder="Pesquise..." autocomplete="off" autofocus="">
		<button type="button" class="input-group-addon btn-filtro btn-filtrar btn-filtrar-relatorio">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>

    <div class="sub-acoes">
        <button class="btn btn-success gravar-relatorio" data-iduser="{{$id}}">
            <span class="glyphicon btn-success"></span>
            Gravar
        </button>

        <button class="btn btn-danger canselar-relatorio" data-iduser="{{$id}}">
            <span class="glyphicon btn-success"></span>
            Cancelar
        </button>
    </div>    

<table class="table table-striped table-bordered table-hover lista-obj tabela-relatorio tabela-relatorio-editar">
    <thead>
    <tr data-title="titulo">
        <th class="col-min-small-normal">ID</th>
        <th class="col-min-small-normal">Descrição</th>
        <th class="col-min-small-normal">Menu Grupo</th>
        <th class="col-min-small-normal">Incluir</th>
    </tr>
    </thead>
    <tbody>

        @foreach ( $relatorios as $relatorio )
        <tr tabindex="0" data-id="{{$relatorio->ID}}" data-flag="{{$relatorio->FLAG}}" class="chec-relatorio-editar">
            
            <td class="col-min-small-normal">{{28000+$relatorio->ID}}</td>
            <td class="col-min-small-normal">{{$relatorio->NOME}}</td>
            <td class="col-min-small-normal">{{$relatorio->MENU_GRUPO}}</td>
            <td class="col-min-small-normal">
                <span class="glyphicon {{$relatorio->FLAG ? 'glyphicon-ok' : 'glyphicon-remove'}}" style="color: {{$relatorio->FLAG ? 'green' : 'red'}};">
            </td>
            
            <input type="hidden" class="FLAG" value="{{$relatorio->FLAG}}">
            <input type="hidden" class="CHEC" value="{{$relatorio->CHEC}}">
            
        </tr>
        @endforeach

    </tbody>
</table>