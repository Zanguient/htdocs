<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script>
            function subst() {
              var vars={};
              var x=document.location.search.substring(1).split('&');
              for (var i in x) {var z=x[i].split('=',2);vars[z[0]] = unescape(z[1]);}
              var x=['frompage','topage','page','webpage','section','subsection','subsubsection','date','time'];
              for (var i in x) {
                var y = document.getElementsByClassName(x[i]);
                for (var j=0; j<y.length; ++j) y[j].textContent = vars[x[i]];
              }
            }
        </script>
        <style>
			
			@font-face {
				font-family: Tahoma;
				src: local("tahoma"), url("file:////var/www/html/GCWEB/public/assets/fonts/tahoma.ttf");
			}
			
            /*estilo para impress*/
            @page {
                size: A4 portrait;
                margin: 40pt 25pt 30pt 25pt;
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
                width: 1000px;
                margin: 0 auto;
                font-family: Tahoma, Arial, Verdana;
                font-size: 12px;
            }

            body * {
                box-sizing: border-box;
            }

            section {
                display: inline-block;
                width: 100%;
                overflow: hidden;
                /*margin: 5px 0px;*/
            }

            section#top {
                margin-bottom: 0px;
                padding: 5px;
            }
            
            section#top > div {
                float: left;
                /*margin: 10px 5px 0px 5px;*/
            }

            section#top > div.left {
                margin-left: 10px;
                width: 130px;
                height: 50px;
                background: no-repeat center;
                background-size: contain;
            }

            section#top > div.center label:first-child {
                font-size: 14px;
                font-weight: bold;
            }

            section#top > div.center label:not(:first-child),
            section#top > div.right label:not(:first-child) {
                font-weight: bold;
            }

            section#top > div.right, section#top > div.right * {
                float: right;
                clear: right;
            }
            
            section#top > div.right label.pagina span {
                float: none;
                clear: none;
            }

            section#top > div.right label.pagina {
                font-size: 9px;
                font-weight: bold;
            }
            
            section#top > div.right span.date {
                float: left;
                clear: none;
                margin-right: 5px;
            }

            section#top label {
                margin-bottom: 3px;
            }

            label {
                float: left;
                clear: left;
                margin-right: 5px;
            }

            h4 {
                margin: 10px 0 0 10px;
            }
        </style>        
    </head>
    <body onload="subst()">
        <page size="A4">
            <section id="top">
                <div class="center">
                    <label>GESTÃO CORPORATIVA - DELFA</label>
                    <label>22021 - Relatório de peças disponíveis para consumo</label>
					<label>
						Estabelecimento: {{ $header->ESTABELECIMENTO_ID }} 
						/ Grupo de Produção: {{ $header->GP_ID }} - {{ $header->GP_DESCRICAO }}
						/ Somente sobras: {{ $header->SOMENTE_SOBRA }}
						/ Período: 
							@if (!empty($header->DATA_INI) && !empty($header->DATA_FIM))
								{{ date('d/m/Y', strtotime($header->DATA_INI)) }} à {{ date('d/m/Y', strtotime($header->DATA_FIM)) }}
							@else
								Todos
							@endif
					</label>
                </div>
                <div class="right">
                    <label>{{ date('d/m/Y H:i:s') }}</label>
                    <label class="pagina">Pág. <span class="page"></span> de <span class="topage"></span></label>
                </div>
            </section>        
        </page>	
    </body>
    <html>