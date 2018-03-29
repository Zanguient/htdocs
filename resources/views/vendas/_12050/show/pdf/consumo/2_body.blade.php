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
        
        .zebra-0{
            background-color: rgb(243, 243, 243);
        }
        
        .zebra-1{
            background-color: rgb(255, 255, 255);
            font-weight: bold;
        }

        .zebra-0 .col-prod{
                background-color: rgb(201, 230, 195);
        }
        
        .zebra-1 .col-prod{
                background-color: rgb(228, 249, 224);
        }
        
        .zebra-0 .col-def{
                background-color: rgb(203, 223, 232);
        }
        
        .zebra-1 .col-def{
                background-color: rgb(227, 238, 243);
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
							<th class="left">DATA</th>
							<th class="left">PEDIDOS</th>
                            <th class="left">FATURAM.</th>
                            <th class="left">DEVOLUÇÃO</th>
                            <th class="left">PRODUÇÃO</th>
                            <th class="right">TURNO1</th>
							<th class="right">TURNO2</th>
                            <th class="right">DEFEITOS</th>
                            <th class="right">TURNO1</th>
							<th class="right">TURNO2</th>
                        </tr>
                    </thead>
                        <tr class="zebra-0">
                            <td class="left"></td>
                            <td class="left"></td>
                            <td class="left"></td>
                            <td class="left"></td>
                            <td class="col-prod left"></td>
                            <td class="col-prod left"></td>
                            <td class="col-prod left"></td>
                            <td class="col-def left"></td>
                            <td class="col-def left"></td>
                            <td class="col-def left"></td>
                        </tr>
                        <tr class="zebra-1">
                            <td class="left"></td>
                            <td class="left"></td>
                            <td class="left"></td>
                            <td class="left"></td>
                            <td class="col-prod left"></td>
                            <td class="col-prod left"></td>
                            <td class="col-prod left"></td>
                            <td class="col-def left"></td>
                            <td class="col-def left"></td>
                            <td class="col-def left"></td>
                        </tr>
                        <tr class="zebra-0">
                            <td class="left"></td>
                            <td class="left"></td>
                            <td class="left"></td>
                            <td class="left"></td>
                            <td class="col-prod left"></td>
                            <td class="col-prod left"></td>
                            <td class="col-prod left"></td>
                            <td class="col-def left"></td>
                            <td class="col-def left"></td>
                            <td class="col-def left"></td>
                        </tr>
                        <tr class="zebra-1">
                            <td class="left"></td>
                            <td class="left"></td>
                            <td class="left"></td>
                            <td class="left"></td>
                            <td class="col-prod left"></td>
                            <td class="col-prod left"></td>
                            <td class="col-prod left"></td>
                            <td class="col-def left"></td>
                            <td class="col-def left"></td>
                            <td class="col-def left"></td>
                        </tr>
                    <tbody>
                        
                    </tbody>
                   
            </div>
		</section>
	</page>	
</body>

</html>