<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<!--<link rel="stylesheet" href="{{ elixir('assets/css/13050.css') }}">--> 
	
	<style>
		
		@font-face {
			font-family: Tahoma;
			src: local("tahoma"), url("file:////var/www/html/GCWEB/public/assets/fonts/tahoma.ttf");
		}
	
		/*estilo para impressão*/
		@page {
			size: A4 portrait;
			margin: 30pt 25pt 30pt 25pt;
		}
		/**/

		page[size="A4"] {
			display: block;
			border: 2px solid rgb(0, 0, 0);
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
			width: 900px;
			margin: 0 auto;
			font-family: Tahoma, Arial, Verdana;
			font-size: 12px;
		}

		body * {
			box-sizing: border-box;
		}

		section {
			display: inline-block;
			width: 99.9%;
			overflow: hidden;
			margin: 0 1px;
		}

		section#top > div {
			float: left;
			margin: 10px 5px 0px 5px;
		}

		section#top > div.left {
			margin-left: 10px;
			width: 130px;
			height: 50px;
			background: no-repeat center;
			background-size: contain;
		}

		section#top > div.center label:first-child, section#top > div.right label:first-child {
			font-size: 14px;
		}

		section#top > div.center label:not(:first-child),
		section#top > div.right label:not(:first-child) {
			font-weight: normal;
		}

		section#top > div.right, section#top > div.right * {
			float: right;
			clear: right;
		}

		section#top > div.right label:first-child {
			font-size: 19px;
		}

		section#top > div.right label.pagina {
			width: 50px;
			font-size: 9px;
			font-weight: bold;
		}

		section#top > div.right label > span {
			margin-left: 0px;
			width: 13px;
		}

		section#top label {
			margin-bottom: 3px;
		}

		label {
			float: left;
			clear: left;
			margin-right: 5px;
			font-weight: bold;
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
			font-size: 11px;
			border-collapse: collapse;
		}

		table thead {
			border-bottom: 1px solid rgb(0, 0, 0);
		}

		table td, table th {
			padding-right: 5px;
		}

		table th {
			text-align: left;
		}

		table tfoot {
			font-weight: bold;
			border-top: 2px solid rgb(0, 0, 0);
			border-bottom: 2px solid rgb(0, 0, 0);
		}

		table th.center, table td.center {
			text-align: center;
		}

		table th.right, table td.right {
			text-align: right;
		}

		table.striped tbody tr:nth-child(odd) {
			background-color: rgb(211, 211, 211);
		}

		.bloco-container {
			float: left;
			clear: left;
			width: 100%;
		}

		.bloco-container .bloco {
			float: left;
			margin: 10px;
		}

		.bloco-container.info-delfa .bloco {
			margin-top: 5px;
		}

		.bloco-container.info-delfa label {
			width: 65px;
			text-align: right;
		}

		.bloco-container.info-fornecedor {
			background-color: rgb(211, 211, 211);
		}

		.bloco-container.info-fornecedor .bloco:first-of-type {
			width: 45%;
		}

		.bloco-container.info-fornecedor .bloco:nth-of-type(2) label {
			width: 71px;
			text-align: right;
		}

		.bloco-container.info-fornecedor .bloco:nth-of-type(3) label {
			width: 41px;
			text-align: right;
		}	
		
		.bloco-container.info-fornecedor .bloco:nth-of-type(3) a {
			float: left;
			width: 195px;
			word-break: break-all;
		}

		label#fornecedor {
			font-size: 14px;
		}

		#fornecedor-endereco label {
			font-weight: normal;
		}

		.bloco-container.info-complementar .bloco:first-of-type {
			width: 67%;
		}

		.bloco-container.info-complementar .bloco:first-of-type label {
			width: 177px;
			text-align: right;
		}

		.bloco-container.info-complementar .bloco:nth-of-type(2) label {
			width: 85px;
			text-align: right;
		}

		.bloco-container.info-produto {
			border-top: 2px solid;
		}

		.bloco-container.info-produto table tbody tr td:nth-child(2) {
			max-width: 340px;
		}
		
		.bloco-container.info-produto table tbody tr td.obs-item {
			padding-left: 70px;
		}


		#alerta {
			float: left;
			width: 100%;
			padding: 5px 10px;
			background-color: rgb(255, 255, 0);
			color: rgb(139, 0, 0);
			font-weight: bold;
		}

		.bloco-container.obs-gerais .bloco {
			margin-top: 5px;
			margin-left: 0;
		}

		.bloco-container.obs-gerais .bloco h4 {
			margin-top: 0px;
		}

		.bloco-container.obs-gerais .bloco.obs {
			width: 70%;
		}

		.bloco-container.obs-gerais .bloco.totais {
			float: right;
			width: 25%;
			padding: 5px;
			background-color: rgb(211, 211, 211);
		}

		.bloco-container.obs-gerais .bloco.totais h4 {
			margin-left: 0;
			text-align: center;
		}

		.bloco-container.obs-gerais .bloco.totais table tfoot {
			border-top-width: 1px;
			border-bottom: 0;
		}

		.bloco-container.obs-gerais .bloco.totais table tfoot td {
			padding-top: 5px;
		}

		#obs {
			margin: 0 10px;
			min-height: 85px;
		}

		#obs-info {
			margin-left: 10px;
		}

		.bloco .totais h4 {
			margin-left: 0;
		}

		section#bottom {
			margin-top: 30px;
		}

		section#bottom .bloco.assin {
			float: left;
			width: 50%;
			margin: 0;
		}

		section#bottom .bloco.assin label {
			float: none;
			position: relative;
			display: block;
			margin: 0 auto;
			width: 80%;
			text-align: center;
			border-top: 1px solid rgb(0, 0, 0);
		}

		section#bottom .bloco-container:last-child {
			margin-top: 10px;
			font-size: 9px;
			background-color: rgb(211, 211, 211);
		}

		section#bottom .bloco-container:last-child .bloco {
			margin: 5px;
		}

		section#bottom .bloco-container:last-child .bloco:last-child {
			float: right;
		}

		section#bottom .bloco-container:last-child .bloco span {
			margin-right: 5px;
		}

	</style>
	
</head>

<body>
	
	<page size="A4">
		
		<section id="top">

			<div class="left" <?php echo 'style="background-image: url(http://'. $_SERVER['HTTP_HOST'] .'/assets/images/logo1.jpg);"'?>></div>
			<div class="center">
				<label>{{ $estab->RAZAOSOCIAL }}</label>
				<label>{{ $estab->ENDERECO }} {{ $estab->NUMERO }}</label>
				<label>{{ $estab->BAIRRO }} CEP {{ $estab->CEP }} - {{ $estab->CIDADE }}</label>
			</div>
			<div class="right">
				<label>OC. {{ $oc }}</label>
				<label>{{ $data }}</label>
				<label class="pagina">Página <span>01</span></label>
			</div>

		</section>

		<section id="center">

			<div class="bloco-container info-delfa">

				<div class="bloco">
					<label>CNPJ:</label>
					<span>{{ $estab->CNPJ }}</span>
					<label>INSC. EST:</label>
					<span>{{ $estab->IE }}</span>
				</div>
				<div class="bloco">
					<label>FONE:</label>
					<span>{{ $estab->FONE }}</span>
					<label>EMAIL:</label>
					<span>{{ $estab->EMAIL }}</span>
				</div>
				<div class="bloco">
					<label>FAX:</label>
					<span>{{ $estab->FAX }}</span>
				</div>

			</div>

			<div class="bloco-container info-fornecedor">

				<h4>DADOS DO FORNECEDOR</h4>

				<div class="bloco">
					<label id="fornecedor">{{ $fornec->RAZAOSOCIAL }}</label>
					<div id="fornecedor-endereco">
						<label>{{ $fornec->ENDERECO }} {{ $fornec->NUMERO }}</label>
						<label>{{ $fornec->BAIRRO }} CEP {{ $fornec->CEP }} - {{ $fornec->CIDADE }}</label>
					</div>
				</div>

				<div class="bloco">
					<label id="fornecedor-cnpj">CNPJ:</label>
					<span>{{ $fornec->CNPJ }}</span>
					<label id="fornecedor-insc-est">INSC. EST:</label>
					<span>{{ $fornec->IE }}</span>
				</div>

				<div class="bloco">
					<label id="fornecedor-fone">FONE:</label>
					<span>{{ $fornec->FONE }}</span>
					<label id="fornecedor-fax">FAX:</label>
					<span>{{ $fornec->FAX }}</span>
					<label id="fornecedor-email">EMAIL:</label>
					<a href="mailto:{{ $fornec->EMAIL }}">{{ $fornec->EMAIL }}</a>
				</div>

			</div>

			<div class="bloco-container info-complementar">

				<h4>INFORMAÇÕES COMPLEMENTARES</h4>

				<div class="bloco">
					<label id="transportadora">TRANSPORTADORA:</label>
					<span>{{ $transp }}</span>
					<label id="pag-condicao">CONDIÇÕES DE PAGAMENTO:</label>
					<span>{{ $pag_cond }}</span>
					<label id="pag-forma">FORMA DE PAGAMENTO:</label>
					<span>{{ $pag_forma }}</span>
				</div>

				<div class="bloco">
					<label id="frete">FRETE:</label>
					<span>{{ $frete }}</span>
					<label id="comprador">COMPRADOR:</label>
					<span>{{ $comprador }}</span>
				</div>

			</div>

			<div class="bloco-container info-produto">

				<table class="striped">
					<thead>
						<tr>
							<th class="center">CÓDIGO</th>
							<th>DESCRIÇÃO</th>
							<th class="right">QUANTIDADE</th>
							<th class="right">VR.UNIT.</th>
							<th class="right">% IPI</th>
							<th class="right">ACRÉSCIMOS</th>
							<th class="right">DESCONTOS</th>
							<th class="right">VALOR TOTAL</th>
							<th>SAÍDA</th>
							<th>ENTREGA</th>
						</tr>
					</thead>
					<tbody>
						@php $i = 0
						@foreach($tab_prod_id as $id)
						<tr>
							<td class="center">{{ $tab_prod_id[$i] }}</td>
							<td>{{ $tab_prod_desc[$i] }}</td>
							<td class="right">{{ $tab_qtd[$i] }}</td>
							<td class="right">{{ $tab_valor[$i] }}</td>
							<td class="right">{{ $tab_ipi[$i] }}</td>
							<td class="right">{{ $tab_acresc[$i] }}</td>
							<td class="right">{{ $tab_desconto[$i] }}</td>
							<td class="right">{{ $tab_total[$i] }}</td>
							<td>{{ $tab_data_saida[$i] }}</td>
							<td>{{ $tab_data_entrega[$i] }}</td>
						</tr>
						<tr>
							<td colspan="10" class="obs-item">OBS.: {{ $tab_prod_info[$i] }}</td>
						</tr>
						
						<tr>
							<td class="center">{{ $tab_prod_id[$i] }}</td>
							<td>{{ $tab_prod_desc[$i] }}</td>
							<td class="right">{{ $tab_qtd[$i] }}</td>
							<td class="right">{{ $tab_valor[$i] }}</td>
							<td class="right">{{ $tab_ipi[$i] }}</td>
							<td class="right">{{ $tab_acresc[$i] }}</td>
							<td class="right">{{ $tab_desconto[$i] }}</td>
							<td class="right">{{ $tab_total[$i] }}</td>
							<td>{{ $tab_data_saida[$i] }}</td>
							<td>{{ $tab_data_entrega[$i] }}</td>
						</tr>
						<tr>
							<td colspan="10" class="obs-item">OBS.: {{ $tab_prod_info[$i] }}</td>
						</tr>
						@php $i++
						@endforeach
					</tbody>
					<tfoot>
						<tr>
							<td>TOTAIS:</td>
							<td class="center">{{ str_pad($i, 3, 0, STR_PAD_LEFT) }}</td>
							<td class="right">{{ $qtd_item }}</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tfoot>
				</table>

				<div id="alerta">
					Caso sua empresa emita Nota Fiscal Eletrônica, transmitir os arquivos XML para o seguinte email: nfe.ce@delfa.com.br nfe.ce@delfa.com.br
				</div>

			</div>

			<div class="bloco-container obs-gerais">

				<div class="bloco obs">
					<h4>OBSERVAÇÕES</h4>
					<span id="obs">{{ $obs }}</span>
					<label id="obs-info">OBRIGATÓRIO CONSTAR O NÚMERO DA ORDEM DE COMPRA NA NOTA FISCAL</label>
				</div>

				<div class="bloco totais">
					<h4>TOTAIS</h4>
					<table>
						<tbody>
							<tr>
								<td class="right">TOTAL PROD:</td>
								<td class="right">R$ {{ $subtotal }}</td>
							</tr>
							<tr>
								<td class="right">TOTAL IPI:</td>
								<td class="right">R$ {{ $ipi_total }}</td>
							</tr>
							<tr>
								<td class="right">ACRÉSCIMOS:</td>
								<td class="right">R$ {{ $acresc_total }}</td>
							</tr>
							<tr>
								<td class="right">DESCONTOS:</td>
								<td class="right">R$ {{ $desconto_total }}</td>
							</tr>
							<tr>
								<td class="right">FRETE:</td>
								<td class="right">R$ {{ $valor_frete }}</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td class="right">VALOR TOTAL:</td>
								<td class="right">R$ {{ $total_geral }}</td>
							</tr>
						</tfoot>
					</table>

				</div>

			</div>
		</section>

		<section id="bottom">

			<div class="bloco-container">

				<div class="bloco assin">
					<label>FORNECEDOR</label>
				</div>
				<div class="bloco assin">
					<label>DEPTO. DE COMPRAS</label>
				</div>

			</div>

			<div class="bloco-container ">

				<div class="bloco">
					@if ( $autorizacao == 1 )
					<span>EM ESPERA</span>
					@endif
					@if ( $autorizacao == 2 )
					<span>AUTORIZADA</span>
					@endif
					@if ( $autorizacao == 3 )
					<span>NEGADA</span>
					@endif
				</div>
				<div class="bloco">
					<span>{{ $usuario }} - {{ $datahora }}</span>
				</div>

			</div>

		</section>
	
	</page>
	
</body>

</html>