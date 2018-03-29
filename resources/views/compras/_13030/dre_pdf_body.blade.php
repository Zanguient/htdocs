<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<style>
		/*estilo para impress*/
		@page {
			size: A4 landscape;
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
			width: 1585px;
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
			font-size: 9px;
            border-collapse: collapse;    
			margin-top: 4px;
			border-color: rgb(220, 220, 220);
		}

		table thead {
			border-bottom: 1px solid rgb(0, 0, 0);
            display:table-header-group;
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
		}
		
		table th.t-sm-lw {
			width: 75px;
		}	
		
		table, tr, td, th, tbody, thead, tfoot {
            page-break-inside: avoid !important;
            page-break-after: always !important;
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
	</style>
</head>

<body>
	<page size="A4">
		<section id="center">
            <div class="bloco-container info-table">
                @php echo $table
            </div>
		</section>
	</page>	
</body>

</html>