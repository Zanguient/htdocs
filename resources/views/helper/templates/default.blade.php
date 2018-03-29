<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>{{ $RELATORIO['RELATORIO']->DESCRICAO}}</title>
<link rel="important stylesheet" href="">
<style>div.headerdisplayname {font-weight:bold;}</style></head>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
<style>
/*estilo para impressão*/
@page {
        size: A4 landscape;
        margin: 30pt 25pt 30pt 25pt;
}
/**/

body {
    font-family: Tahoma,"Arial Narrow", Helvetica, Arial;
    font-size: 10px;
}

.pagina {
        position: relative;
}

.cabecalho {
        position: relative;
}

.cab-geral {
        position: relative;
        height: 27px;
        font-weight: bold;
}

.gc {
        position: absolute;
}

.titulo-tela {
        position: absolute;
        top: 15px;
}

.periodo {
        position: absolute;
        top: 15px;
        width: 100%;
        text-align: center;
}

.data-hora {
    position: absolute;
        right: 0px;
}

.num-pagina {
    position: absolute;
        top: 15px;
        right: 0;
}

.cab-detalhes {
        position: relative;
        margin-top: 3px;
        font-size: 10px;
}

.cab-detalhes div {
        position: absolute;
        right: 0;
        top: 0;
}

table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
}

table {
        font-size: 10px;
        width: 100%;
        /*table-layout: fixed;*/
}

thead {
    border-bottom: 2px solid black;
}

th {
        width: 7%;
        background-color: lightgray;
        text-align: left;
}

tr.lin-colorida {
        background-color: rgb(235,235,235);
}

td.col-numerica {
    text-align: right;
}

td.col-sem-borda-topleft {
    /*border-top-color: transparent;
    border-left-color: transparent;*/
}

td span.alerta {
    color: red;
}

td span.alerta.vazio {
    color: transparent;
}

.grupo {
    font-weight: bold;
    background-color: rgb(192, 220, 192);
}

.col-id {
    width: 50px;
}

.col-descricao {
    width: 300px;
}

p.page-break {
    page-break-after: always;
}

.rodape {
        margin-top: 5px;
        border-top: 2px solid black;
        color: gray;
}

/*mobile*/
@media (max-width: 767px) {
        .periodo {
                top: 30px;
                text-align: left;
        }

        .cab-geral {
                height: 42px;
        }
        .cab-detalhes div {
                top: -13px;
        }

        .conteudo {
                position: relative;
                overflow: auto;
        }
}

@media (max-width: 480px) {
        .periodo {
                text-align: left;
        }
}

td.col-String {
    text-align: left;
}

td.Tol-numerica {
    text-align: right;
    font-weight: bold;
}

td.Tol-String {
    text-align: left;
    font-weight: bold;
}

td.TolG-numerica {
    text-align: right;
    font-weight: bold;
    margin-top: 5px;
    border-top: 2px solid black;
    margin-top: 2cm;
}

td.TolG-String {
    text-align: left;
    font-weight: bold;
    margin-top: 5px;
    border-top: 2px solid black;
    margin-top: 2cm;
}

    @php $cont = 0;
    @foreach ($RELATORIO['DETALHE'] as $coluna)    
        {{'th.col-'.$cont.' {width: '.$coluna->PERCENTUAL.'%;}'}}
        @php $cont++;
    @endforeach
    
</style>
</head>
<body>
<div class="pagina">

   <div class="cabecalho">
			<div class="cab-geral">
				<label class="gc">GESTÃO CORPORATIVA - DELFA</label>
				<label class="titulo-tela">{{ $RELATORIO['RELATORIO']->DESCRICAO}}</label>
				<label class="data-hora">{{ $DATAHORA }}</label>
				<label class="num-pagina">Pág.1</label>
				<label class="periodo"></label>
			</div>
			<div class="cab-detalhes">
				<label>{{ $RELATORIO['RELATORIO']->FILTRO}}</label>
				<label></label>
				<label></label>
				<label></label>
				<label></label>
				<div>
					<label>{{ $RELATORIO['RELATORIO']->VERCAO}}</label>
					<label>/{{ $RELATORIO['RELATORIO']->USUARIO}}</label>
				</div>
			</div>
		</div>


<div class="Conteudo">
<table >
    <thead>
        <tr>
            @php $cont = 0;
            @foreach ($RELATORIO['DETALHE'] as $coluna)    
                <th class="{{'col-'.$cont}}"  >{{$coluna->DESCRICAO}}</th>
                @php $cont++;
                @php $linhas = $cont;
            @endforeach
        </tr>
    </thead>
    
    @php $cont_linhas = 0;
    @php $grupo_old  = '';
    
    @foreach ($DADOS as $item)
    @php $cont_linhas++;
    @php $tipo_linha = ($cont_linhas%2) == 0;
    
    
    <tbody >
    <tr>
  
        @if ( $RELATORIO['RELATORIO']->AGRUPAR == 1)
            @php $grupo = strtoupper($RELATORIO['RELATORIO']->AGRUPAMENTO);
            @php $grupo_desc = $item[$grupo];
            
            @if ( $grupo_old != $grupo_desc)
                @php $grupo_old = $grupo_desc;
                <td class="grupo" colspan="{{ $linhas }}">{{$grupo_old}}</td>
            @endif
        @endif
        
        </tr>
            @if ($tipo_linha)
                <tr class="lin-colorida">
            @else
                <tr>
            @endif

            @foreach ($RELATORIO['DETALHE'] as $coluna)
                @php $campo = strtoupper($coluna->CAMPO);
                
                @if ($coluna->CLASS == 'col-numerica')
                    @php $pos = stripos($coluna->MASCARA,'.');
                    
                    @if ($pos == 1)
                        @php $nuber = number_format($item[$campo],strlen($coluna->MASCARA)-2,",",".");
                    @else
                        @php $nuber = str_pad( $item[$campo], strlen($coluna->MASCARA));
                    @endif
                    
                    <td class="{{$coluna->CLASS}}" colspan="{{ $coluna->COLSPANT }}" rowspan="{{ $coluna->COLSPANL }}" id="{{$coluna->CLASS}}">{{$nuber}}</td>
                @else
                    <td class="{{$coluna->CLASS}}" colspan="{{ $coluna->COLSPANT }}" rowspan="{{ $coluna->COLSPANL }}" id="{{$coluna->CLASS}}">{{$item[$campo]}}</td>
                @endif

            @endforeach
                
        </tr>
        </tbody>
        
    @endforeach
    
</table>
    
</div>
    
<div class="Rodape">
<TEXT></TEXT>
</div>
</body>
</html>
