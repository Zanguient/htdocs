<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<style>
		/*estilo para impress*/
		@page {
			size: A4 portrait;
			margin: 40pt 25pt 30pt 25pt;
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
			page-break-before: always;
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

		table td, table th {
			padding-right: 5px;
			padding-left: 5px;
		}
		
		table th.t-sm-lw {
			width: 75px;
		}	
		
		table, tr, td, th, tbody, thead, tfoot {
            /*border-bottom: 1px solid rgb(221, 221, 221);*/
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
        
        tr.footer {
            font-weight: bold;
        }
		
		tr.claro {
			background-color: rgb(255, 255, 255);
		}
		
		tr.escuro {
			background-color: rgb(211, 211, 211);
		}
        
	</style>
</head>

<body>
	<page size="A4">
		<section id="center">
            <div class="bloco-container info-table">
                    
				<table>
                    <thead>
                        <tr>
							<th class="left">Dt.Início</th>
							<th class="left">UP/Estação</th>
                            <th class="left">Remessa/Talão</th>
                            <th class="left">Dt.Rem.</th>
                            <th class="left">Produto/Tamanho</th>
                            <th class="left">Identif. Peça</th>
							<th class="right">Saldo Peça</th>
                            <th class="right">Qtd.Consumo</th>
                            <th class="right">Rest.Peça</th>
                        </tr>
                    </thead>
                    <tbody>
						@php $tr_class = 'claro';
                        @foreach ($talao as $key => $t)							
						
							@if ( isset($talao[$key-1]) && ($talao[$key-1]->O_UP_ID != $t->O_UP_ID) )
								</tbody>
								</table>
								<table>
									<thead>
										<tr>
											<th class="left">Dt.Início</th>
											<th class="left">UP/Estação</th>
											<th class="left">Remessa/Talão</th>
											<th class="left">Dt.Rem.</th>
											<th class="left">Produto/Tamanho</th>
											<th class="left">Identif. Peça</th>
											<th class="right">Saldo Peça</th>
											<th class="right">Qtd.Consumo</th>
											<th class="right">Rest.Peça</th>
										</tr>
									</thead>
									<tbody>
							@endif
							
							@if ( isset($talao[$key-1]) && ($talao[$key-1]->O_PECA_ID != $t->O_PECA_ID) )
								@php $tr_class = ($tr_class == 'claro') ? 'escuro' : 'claro';
							@endif							
								
							<tr class="{{ $tr_class }}">
								<td class="left">{{ !empty($t->O_DATAHORA_INICIO) ? date('d/m H:i', strtotime($t->O_DATAHORA_INICIO)) : '-' }}</td>
								<td class="left">{{ $t->O_UP_ID }} - {{ $t->UP_DESCRICAO }} / {{ $t->O_ESTACAO }}</td>
								<td class="left">{{ $t->REMESSA }} / {{ $t->O_REMESSA_TALAO_ID }}</td>
								<td class="left">{{ date('d/m', strtotime($t->O_REMESSA_DATA)) }}</td>
								<td class="left">{{ $t->O_PRODUTO_ID }} - {{ $t->PRODUTO_DESCRICAO }} {{ empty($t->O_TAMANHO_DESC_CONSUMO) ? '' : '/ '.$t->O_TAMANHO_DESC_CONSUMO }}</td>
								<td class="left">
									Peça: {{ $t->O_PECA_ID or '-' }}
									| Rem.: {{ $t->O_REMESSA_PECA or '-' }}
									| Talão: {{ $t->O_TALAO_ID or '-' }}
								</td>
								<td class="right">{{ number_format($t->O_QUANTIDADE_ALOCACAO, 4, ',', '.') }}</td>
								<td class="right">{{ number_format($t->O_QUANTIDADE, 4, ',', '.') }}</td>
								<td class="right">{{ number_format($t->SALDO_RESTANTE_PECA, 4, ',', '.') }}</td>
							</tr>							
													
                        @endforeach                      
                    </tbody>
                </table>
                        
                    
            </div>
		</section>
	</page>	
</body>

</html>