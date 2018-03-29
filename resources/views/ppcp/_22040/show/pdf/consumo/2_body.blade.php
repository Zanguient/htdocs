<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<style>
		/*estilo para impress*/
		@page {
			size: A4 portrait;
			margin: 30pt 25pt 30pt 25pt;
		}
		/**/

		page[size="A4"] {
			display: block;
			/*
			background: white;
			height: 21cm;
			width: 29.7cm;
			margin: 0 auto;
			margin-bottom: 0.5cm;
			box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
			*/
		}

		.page-break {
			page-break-after: always;
		}

		body {
			position: relative;
			width: 1000px;
			margin: 0 auto;
			font-family: Tahoma, Arial, Verdana;
			font-size: 12px;
		}

		body * {
			box-sizing: border-box;
		}
/*
		section {
			display: inline-block;
			width: 99.9%;
			overflow: hidden;
			margin: 5px 0px;
		}*/

		label {
			float: left;
			clear: left;
			margin-right: 5px;
		}

		span {
			float: left;
		}

		h4 {
			margin: 10px 0 0 10px;
		}

		a {
			color: rgb(0, 0, 0);
			text-decoration: none;
		}

		table {
            width: 100%;
			font-size: 9px;
            border-collapse: collapse;    
			margin-top: 4px;
			border-color: rgb(220, 220, 220);
            page-break-inside:avoid;
		}

		table thead {
			border-bottom: 1px solid rgb(0, 0, 0);
            /*display:table-header-group;*/
		}
        
		table thead tr {
			background-color: rgb(51, 122, 183);
			color: rgb(255, 255, 255);
			height: 20px;
		}
		
		table tr.ccusto {
			font-weight: bold;
			background-color: rgb(160, 190, 225) !important;
			font-size: 8px;
		}	

		table td, table th {
			padding-right: 5px;
			padding-left: 5px;
		}
		
		table th.t-sm-lw {
			width: 75px;
		}	
		
		table, tr, td, th, tbody, thead, tfoot {
            border-bottom: 1px solid rgb(221, 221, 221);
            page-break-inside: avoid !important;
            page-break-after: always !important;
        }
        
		.group-header {
            display:table-header-group;
        }
		
		table th.t-low-med {
			width: 12%;
		}	
		
		table th.t-med-larg {
			width: 18%;
		}	
		
		table td.t-numb, table th.t-numb {
			text-align: right;
		}	

		th.t-total-th {
			width: 80px;
		}

		table tfoot {
			font-weight: bold;
			border-top: 2px solid rgb(0, 0, 0);
			border-bottom: 2px solid rgb(0, 0, 0);
		}

		table th.left, table td.left {
			text-align: left;
		}

		table th.center, table td.center {
			text-align: center;
		}

		table th.right, table td.right {
			text-align: right;
		}

		table.table-striped tbody tr:nth-child(odd) {
			background-color: rgb(240, 240, 240);
		}
		
		table td {
			height: 23px;
		}	
        
        tr.divisor-bloco {
            border-top: solid 2px;
            border-bottom: solid 2px;
            border-color: rgb(150,150,150);
        }
        
        tr.densidade {
            background-color: rgb(255, 200, 150);
            font-weight: bold;
        }
        
        tr.modelo {
            background-color: rgb(170, 200, 170);
            font-weight: bold;
        }
        
        tr.footer {
            font-weight: bold;
        }
        
        tr.item:nth-child(odd) {
            background-color: rgb(243, 243, 243);
        }
       
        
	</style>
</head>

<body>
	<page size="A4">
		<section id="center">
            <div class="bloco-container info-table">

                    @php $quantidade                        = 0
                    @php $quantidade_alternativa            = 0
                    @php $quantidade_conjunto               = 0
                    @php $quantidade_conjunto_alternativa   = 0
                    @php $quantidade_densidade              = 0
                    @php $quantidade_densidade_alternativa  = 0
                    @php $quantidade_geral                  = 0
                    @php $quantidade_geral_alternativa      = 0
                    @php $n_rows = count($consumos)
                    
                    @foreach ($consumos as $key => $consumo)
                    
                        @php $quantidade                        += $consumo->QUANTIDADE_PROJECAO
                        @php $quantidade_alternativa            += $consumo->QUANTIDADE_PROJECAO_ALTERNATIVA
                        @php $quantidade_conjunto               += $consumo->QUANTIDADE_PROJECAO
                        @php $quantidade_conjunto_alternativa   += $consumo->QUANTIDADE_PROJECAO_ALTERNATIVA
                        @php $quantidade_densidade              += $consumo->QUANTIDADE_PROJECAO
                        @php $quantidade_densidade_alternativa  += $consumo->QUANTIDADE_PROJECAO_ALTERNATIVA
                        @php $quantidade_geral                  += $consumo->QUANTIDADE_PROJECAO
                        @php $quantidade_geral_alternativa      += $consumo->QUANTIDADE_PROJECAO_ALTERNATIVA
                        
                        @if ( !isset($consumos[$key-1]) || $consumos[$key-1]->UP_ID != $consumo->UP_ID )
                <table >                   
                    <thead >
                        <tr>
                            <th class="center">Talão Controle</th>
                            <th class="left">Modelo</th>
                            <th class="left">Dens.</th>
                            <th class="left">Esp.</th>
                            <th class="left">Perfil Sku</th>
                            <th class="center">OB</th>
                            <th class="center">Talão</th>
                            <th class="center">Classe Cor</th>
                            <th class="left">Produto</th>
                            <th class="right">Qtd.</th>
                            <th class="right">Qtd. Alt.</th>
                            <th class="right">Soma Qtd. Alt.</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr class="densidade">
                            <td colspan="12">UP: {{ $consumo->UP_ID }} - {{ $consumo->UP_DESCRICAO }}</td>
                        </tr>
                        @endif
                        
                        @if ( !isset($consumos[$key-1]) || $consumos[$key-1]->REMESSA_TALAO_ID != $consumo->REMESSA_TALAO_ID )
                        <tr class="modelo">
                            <td class="center">{{ lpad($consumo->REMESSA_TALAO_ID,4,'0') }}</td>
                            <td>{{ $consumo->TALAO_MODELO_ID }} - {{ $consumo->TALAO_MODELO_DESCRICAO }}</td>
                            <td>D{{ $consumo->DENSIDADE }}</td>
                            <td>{{ number_format($consumo->ESPESSURA, 2, ',', '.') }}MM</td>
                            <td colspan="8">{{ $consumo->PERFIL_SKU_DESCRICAO }}</td>
                        </tr>
                        @endif

                        {{-- Verifica a quebra do talão --}}
                        @if ( !isset($consumos[$key+1]) || $consumos[$key+1]->REMESSA_TALAO_ID != $consumo->REMESSA_TALAO_ID )
                            @php $divisor_bloco = 'divisor-bloco'
                        @else
                            @php $divisor_bloco = ''
                        @endif
                        
                        <tr class="item">
                            <td colspan="5"></td>
                            <td class="center">{{ $consumo->OB }}</td>
                            <td class="center">{{ $consumo->REMESSA_TALAO_DETALHE_ID }}</td>
                            <td class="center">{{ $consumo->CLASSE }}.{{ lpad($consumo->SUBCLASSE,3,'0') }}</td>
                            <td>{{ $consumo->PRODUTO_ID }} - {{ $consumo->PRODUTO_DESCRICAO }}</td>
                            <td class="right">{{ number_format($consumo->QUANTIDADE_PROJECAO, 4, ',', '.') }} {{ $consumo->UM }}</td>
                            <td class="right">{{ number_format($consumo->QUANTIDADE_PROJECAO_ALTERNATIVA, 4, ',', '.') }} {{ $consumo->UM_ALTERNATIVA }}</td>
                            @if ( (!isset($consumos[$key+1]) || ($consumos[$key+1]->PECA_CONJUNTO != $consumo->PECA_CONJUNTO)) || (isset($consumos[$key-1]) && ($consumos[$key-1]->CONTROLE == $consumo->CONTROLE)) )
                            <td class="right">{{ number_format($quantidade_conjunto, 4, ',', '.') }} {{ $consumo->UM }} / {{ number_format($quantidade_conjunto_alternativa, 4, ',', '.') }} {{ $consumo->UM_ALTERNATIVA }}</td>
                                @php $quantidade_conjunto = 0
                                @php $quantidade_conjunto_alternativa = 0
                            @else
                            <td></td>
                            @endif
                        </tr>
                        
                        @if ( !isset($consumos[$key+1]) || $consumos[$key+1]->REMESSA_TALAO_ID != $consumo->REMESSA_TALAO_ID )
                        <tr class="footer footer-talao {{ $divisor_bloco }}">
                            <td class="right" colspan="9">Total Talão {{ lpad($consumo->REMESSA_TALAO_ID,4,'0') }}</td>
                            <td class="right">{{ number_format($quantidade, 4, ',', '.') }} {{ $consumo->UM }}</td>
                            <td class="right">{{ number_format($quantidade_alternativa, 4, ',', '.') }} {{ $consumo->UM_ALTERNATIVA }}</td>
                            <td></td>
                        </tr>
                            @php $quantidade = 0
                            @php $quantidade_alternativa = 0
                        @endif
                        
                        @if ( !isset($consumos[$key+1]) || $consumos[$key+1]->UP_ID != $consumo->UP_ID )
                        <tr class="footer footer-densidade {{ $divisor_bloco }}">
                            <td class="right" colspan="9">Total {{ $consumo->UP_ID }} - {{ $consumo->UP_DESCRICAO }}</td>
                            <td class="right">{{ number_format($quantidade_densidade, 4, ',', '.') }} {{ $consumo->UM }}</td>
                            <td class="right">{{ number_format($quantidade_densidade_alternativa, 4, ',', '.') }} {{ $consumo->UM_ALTERNATIVA }}</td>
                            <td></td>
                        </tr>              
                        @if ( $n_rows-1 == $key )
                            <tr class="footer footer-geral {{ $divisor_bloco }}">
                                <td class="right" colspan="9">Total Geral</td>
                                <td class="right">{{ number_format($quantidade_geral, 4, ',', '.') }} {{ $consumo->UM }}</td>
                                <td class="right">{{ number_format($quantidade_geral_alternativa, 4, ',', '.') }} {{ $consumo->UM_ALTERNATIVA }}</td>
                                <td></td>
                            </tr>
                            @php $quantidade_geral = 0
                            @php $quantidade_geral_alternativa = 0
                        @endif                        
                    </tbody>
                </table>
                            @php $quantidade_densidade = 0
                            @php $quantidade_densidade_alternativa = 0
                        @endif
                        
                    @endforeach
            </div>
		</section>
	</page>	
</body>

</html>