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
			font-size: 13px;
            border-collapse: collapse;    
			margin-top: 4px;
			border-color: rgb(220, 220, 220);
		}

		table thead {
			border-bottom: 1px solid rgb(0, 0, 0);
            /*display:table-header-group;*/
		}
        
		table thead tr {
			background-color: rgb(51, 122, 183);
			color: rgb(255, 255, 255);
			height: 25px;
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

        .info-cliente{
            background-color: #eeeeee;
            padding: 10px;
            font-size: 15px;
            height: 110px;
        }

        .info-complementar{
            padding: 10px;
            font-size: 15px;
            height: 90px;
        }
        
        .linha-c{
            background-color: #eeeeee;
            height: 30px;
            padding: 7px;
        }

        .linha-a{
            background-color: white;
            height: 30px;
            padding: 7px;
        }

        .p1{
            width: 60%;
            float: left;
        }
        .p2{
            width: 20%;
            float: left;
        }
        .p3{
            width: 20%;
            float: left;
        }
        .nowrap{
            white-space: nowrap;
        }
        .bold{
            font-weight: bold;
        }

        .f1{
            width: 70%;
            float: left;   
        }
        .f2{
            width: 30%;
            float: left;       
        }
        
        .codigo{
            max-width: 40px;
            font-size: 13px;
        }

        .valor-total {
            min-width: 110px;
            max-width: 110px;
            width: 110px;
            font-size: 13px;
        }

        .valor-unit {
            min-width: 70px;
            max-width: 70px;
            width: 70px;
            font-size: 13px;
        }

        .quantidade {
            min-width: 65px;
            max-width: 65px;
            width: 65px;
            font-size: 13px;
        }

        .produto {
            min-width: 300px;
            font-size: 13px;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .info-totais{
            width: 100%;
            font-size: 15px;
            height: 150px;
            max-height: 150px;
            page-break-inside: avoid !important;
        }

        .info-totais-obs{
            background-color: #eeeeee;
            width: 70%;
            margin-right: 1%;
            float: left;
            padding: 5px;
            height: 100%;
        }

        .info-totais-tot{
            background-color: #eeeeee;
            width: 29%;
            float: left;
            padding: 5px;
            height: 100%;
        }

        tr{
            height: 25px;
        }

        .info-ass{
            width: 100%;
            margin-top: 20px;
        }

        .ass1{
            width: 49.9%;
            float: left;   
        }

        .ass2{
            width: 49.9%;
            float: left;      
        }

        .email{
            max-height: 56px;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 396px;
        }
               
        
	</style>
</head>

<body>
	<page size="A4">
		<section id="center">
            <div class="bloco-container info-table">

                <div class="info-cliente">
                    <div class="p1">
                        <div><span class="bold">DADOS DO CLIENTE</span><br></div>
                        <div class="bold">{{$info['PEDIDO']['RAZAOSOCIAL']}}<br></div>
                        <div>{{$info['PEDIDO']['ENDERECO']}} {{$info['PEDIDO']['NUMERO']}} <span class="bold">Bairro: </span> {{$info['PEDIDO']['BAIRRO']}}<br></div>
                        <div><span class="bold">Cidade: </span> {{$info['PEDIDO']['CIDADE']}} <br><span class="bold">Cep: </span>{{$info['PEDIDO']['CEP']}}<br></div>    
                    </div>
                    <div class="p2">
                        <div><span class="bold">CNPJ: </span> {{$info['PEDIDO']['CNPJ']}}<br></div>   
                        <div><span class="bold">INSC.EST: </span> {{$info['PEDIDO']['IE']}}<br></div> 
                        <div class="email"><span class="bold">EMAIL:</span> {{str_replace(';',', ',$info['PEDIDO']['EMAIL'])}}<br></div>
                    </div>
                    <div class="p3">
                        <div><span class="bold">FONE: </span>  {{$info['PEDIDO']['FONE']}}<br></div>
                        <div><span class="bold">FAX: </span>  {{$info['PEDIDO']['FAX']}}<br></div>
                    </div>

                </div>

                <div class="info-complementar">
                    <div class="f1">
                        <div><span class="bold">INFORMAÇÕES COMPLEMENTARES</span><br></div>
                        <div><span class="bold">TRANSPORTADORA: </span> {{$info['PEDIDO']['TRANSPORTADORA_DESCRICAO']}}<br></div>
                        <div><span class="bold">CONDIÇÕES DE PAGAMENTO: </span> {{$info['PEDIDO']['PAGAMENTO_CONDICAO_DESCRICAO']}}<br></div>
                        <div><span class="bold">FORMA DE PAGAMENTO: </span> {{$info['PEDIDO']['PAGAMENTO_FORMA_DESCRICAO']}}<br></div>
                    </div>
                    <div  class="f2">
                        <div><span class="bold">FRETE: </span> {{$info['PEDIDO']['FRETE_DESCRICAO']}}<br></div>
                        <div><span class="bold">PREV.FATURAMENTO: </span> {{$info['PEDIDO']['PREVFAT']}}<br></div>
                        <div><span class="bold">PED.CLIENTE: </span> {{$info['PEDIDO']['PEDIDO_CLIENTE']}}<br></div>
                    </div>
                </div>

                
                @php $cont   = 0;
                @php $qtd    = 0;
                @php $vlr    = 0;
                @php $st     = 0;

                @php $paginas = array_chunk($info['ITENS'], 44);
                
                @foreach ($paginas AS $itens)

                    @php $linhas = count($itens);

                    <div class="info-itens" style="max-height: {{($linhas * 25) + 25}}px;">
            
                         <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="codigo      text-left">CÓDIGO</th>
                                    <th class="produto     text-left">DESCRIÇÃO</th>
                                    <th class="quantidade text-right">QTD.</th>
                                    <th class="valor-unit text-right">VR.UNIT.</th>
                                    <th class="valor-total text-right">VALOR TOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                
                                
                                @foreach ($itens AS $ITEN)
                                    @php $cont++;
                                    @php $qtd = $ITEN['quantidade'] + $qtd;
                                    @php $vlr = $ITEN['valorTotal'] + $vlr;
                                    @php $st  = $ITEN['valor_st']   + $st;
                                    <tr>
                                        <td class="codigo       text-left">{{str_pad($ITEN['produto']['CODIGO'],6,"0",STR_PAD_LEFT)}} - {{str_pad($ITEN['tamanhoId'],2,"0",STR_PAD_LEFT)}}</td>
                                        <td class="produto      text-left">- {{$ITEN['modelo']['MODELO_DESCRICAO']}} Cor:{{$ITEN['cor']['CODIGO']}} - {{$ITEN['cor']['DESCRICAO']}} Tam.{{$ITEN['tamanhoDescricao']}}</td>
                                        <td class="quantidade  text-right">{{number_format($ITEN['quantidade'],    2, ',' , '.')}}</td>
                                        <td class="valor-unit  text-right">{{number_format($ITEN['valorUnitario'], 2, ',' , '.')}}</td>
                                        <td class="valor-total text-right">R$ {{number_format($ITEN['valorTotal'], 2, ',' , '.')}}</td>
                                    </tr>
                                 @endforeach
                            </tbody>
                        </table>
                    
                    </div>
                @endforeach

                <br>

                <div class="linha-c">
                    QUANTIDADE DE ITENS: {{ str_pad($cont ,3,"0",STR_PAD_LEFT)}}
                </div>

                <br>

                <div class="info-totais">
                    <div class="info-totais-obs">
                        <div><span class="bold">OBSERVAÇÕES</span><br></div>
                        <div><span class="">{{$info['PEDIDO']['OBSERVACAO']}}</span></div>
                        <div class="text-center" style="font-size: 12px; margin-top: 100px; background-color: white;padding: 3px">{{$info['PEDIDO']['MENSAGEM']}}</div>
                    </div> 

                    <div class="info-totais-tot">
                        <div class="bold text-center">TOTAIS<br></div>
                        <div><span class="bold ">QUANT.TOTAL:</span>    <span style="float: right;">{{number_format($qtd, 0, ',' , '.')}}</span><br></div>
                        <div class="bold text-center">-------------------------------------------<br></div>
                        <div><span class="bold ">TOTAL PROD.</span>     <span style="float: right;"> R$ {{number_format($vlr, 2, ',' , '.')}}</span><br></div>
                        <div><span class="bold ">SUBST.TRIB.:</span>    <span style="float: right;"> R$ {{number_format($st, 2, ',' , '.')}}</span><br></div>
                        <div><span class="bold ">FRETE:</span>          <span style="float: right;"> R$ {{number_format($info['PEDIDO']['VALOR_FRETE'], 2, ',' , '.')}}</span><br></div>
                        <div class="bold text-center">-------------------------------------------<br></div>

                        @php $tot_geral = $vlr + $info['PEDIDO']['VALOR_FRETE'] + $st;

                        <div><span class="bold ">VALOR TOTAL:</span>    <span style="float: right;"> R$ {{number_format($tot_geral, 2, ',' , '.')}}</span><br></div>
                    </div>    
                </div>


                <div class="info-ass">
                    <div class="ass1 text-center">_____________________________________________________<br>Cliente</div> 
                    <div class="ass2 text-center">_____________________________________________________<br>Representante / Dept. De vendas</div>  
                </div>
                
                
            </div>
		</section>
	</page>	
</body>

</html>