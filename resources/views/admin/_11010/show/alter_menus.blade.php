<div class="consulta-itens">
    <div class="input-group input-group-pesquisa">
        <input type="search" name="filtro_pesquisa" class="form-control filtro-obj imp-filtrar-menu" placeholder="Pesquise..." autocomplete="off" autofocus="">
        <button type="button" class="input-group-addon btn-filtro btn-filtrar btn-filtrar-menu">
            <span class="fa fa-search"></span>
        </button>
    </div>
</div>

    <div class="sub-acoes">
        <button class="btn btn-success gravar-menu" data-iduser="{{$id}}">
            <span class="glyphicon btn-success"></span>
            Gravar
        </button>

        <button class="btn btn-danger canselar-menu" data-iduser="{{$id}}">
            <span class="glyphicon btn-success"></span>
            Cancelar
        </button>
    </div>    

<table class="table table-striped table-bordered table-hover lista-obj tabela-menu">
    <thead>
        <tr data-title="titulo">
            <th class="col-min-small-normal">Descrição</th>
            <th class="col-min-small-normal">Controle</th>
            <th class="col-min-small-normal">Grupo</th>
            <th class="col-min-small-normal">Subgrupo</th>

            <th class="col-min-small-normal">Visualizar</th>
            
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
        <tr tabindex="0" class="item-menu-grupo" data-id="{{$menu->ID}}" data-origem="{{$menu->ORIGEM}}" data-controle="{{$menu->CONTROLE}}">
            <td class="col-min-small-normal">{{$menu->DESCRICAO}}</td>
            <td class="col-min-small-normal">{{$menu->CONTROLE}}</td>
            <td class="col-min-small-normal">{{$menu->GRUPO}}</td>
            <td class="col-min-small-normal">{{$menu->SUBGRUPO}}</td>

            <td class="col-min-small-normal fundo-perc chec-menu-editar {{$menu->FLAG == 1 ? 'menu-item-disabled' : ''}}" data-id="{{$menu->ID}}"><span class="glyphicon {{$menu->FLAG ? 'glyphicon-ok' : 'glyphicon-remove'}}" style="color: {{$menu->FLAG ? 'green' : 'red'}};">
                <input type="hidden" class="FLAG" value="{{$menu->FLAG}}">
                <input type="hidden" class="TAGS" value="{{$menu->FLAG}}">
            </td>

            <td class="col-min-small-normal chec-menu-item-editar {{$menu->FLAG == 1 ? '' : 'menu-item-disabled'}} "><span class="glyphicon {{$menu->FLAG ? '' : 'disable-menu-item'}} {{'Menu'.$menu->ID}} {{$menu->NEGAR  ? 'glyphicon-ok' : 'glyphicon-remove'}}" style="color: {{$menu->NEGAR ? 'green' : 'red'}};"></span>
                <input type="hidden" class="FLAG" value="{{$menu->NEGAR}}">
                <input type="hidden" class="TAGS" value="{{$menu->NEGAR}}">
            </td>

            <td class="col-min-small-normal chec-menu-item-editar {{$menu->TAG_I ? 'menu-item-disabled' : ''}} {{$menu->FLAG ? '' : 'menu-item-disabled'}}"><span class="glyphicon {{'Menu'.$menu->ID}} {{$menu->FLAG ? '' : 'disable-menu-item'}} {{$menu->INCLUIR  ? 'glyphicon-ok' : 'glyphicon-remove'}}" style="color: {{$menu->INCLUIR ? 'green' : 'red'}};"></span>
                <input type="hidden" class="FLAG" value="{{$menu->INCLUIR}}">
                <input type="hidden" class="TAGS" value="{{$menu->INCLUIR}}">
            </td>
            <td class="col-min-small-normal chec-menu-item-editar {{$menu->TAG_A ? 'menu-item-disabled' : ''}} {{$menu->FLAG ? '' : 'menu-item-disabled'}}"><span class="glyphicon {{'Menu'.$menu->ID}} {{$menu->FLAG ? '' : 'disable-menu-item'}} {{$menu->ALTERAR  ? 'glyphicon-ok' : 'glyphicon-remove'}}" style="color: {{$menu->ALTERAR ? 'green' : 'red'}};"></span>
                <input type="hidden" class="FLAG" value="{{$menu->ALTERAR}}">
                <input type="hidden" class="TAGS" value="{{$menu->ALTERAR}}">
            </td>
            <td class="col-min-small-normal chec-menu-item-editar {{$menu->TAG_E ? 'menu-item-disabled' : ''}} {{$menu->FLAG ? '' : 'menu-item-disabled'}}"><span class="glyphicon {{'Menu'.$menu->ID}} {{$menu->FLAG ? '' : 'disable-menu-item'}} {{$menu->EXCLUIR  ? 'glyphicon-ok' : 'glyphicon-remove'}}" style="color: {{$menu->EXCLUIR ? 'green' : 'red'}};"></span>
                <input type="hidden" class="FLAG" value="{{$menu->EXCLUIR}}">
                <input type="hidden" class="TAGS" value="{{$menu->EXCLUIR}}">
            </td>
            <td class="col-min-small-normal chec-menu-item-editar {{$menu->TAG_M ? 'menu-item-disabled' : ''}} {{$menu->FLAG ? '' : 'menu-item-disabled'}}"><span class="glyphicon {{'Menu'.$menu->ID}} {{$menu->FLAG ? '' : 'disable-menu-item'}} {{$menu->IMPRIMIR ? 'glyphicon-ok' : 'glyphicon-remove'}}" style="color: {{$menu->IMPRIMIR ? 'green' : 'red'}};"></span>
                <input type="hidden" class="FLAG" value="{{$menu->IMPRIMIR}}">
                <input type="hidden" class="TAGS" value="{{$menu->IMPRIMIR}}">
            </td>            

            <td class="col-min-small-normal">{{$menu->ORIGEM_I.','.$menu->ORIGEM_A.','.$menu->ORIGEM_E.','.$menu->ORIGEM_M}}</td>

            
        </tr>
        @endforeach
        
    </tbody>
</table>