/**
 * _11080 - Criar Relatorio
 */

 function handleDragStart(e) {
  this.style.opacity = '0.4';  // this / e.target is the source node.
}

function handleDragOver(e) {
  if (e.preventDefault) {
    e.preventDefault(); // Necessary. Allows us to drop.
  }

  e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

  return false;
}

function handleDragEnter(e) {
  // this / e.target is the current hover target.
  this.classList.add('over');
}

function handleDragLeave(e) {
  this.classList.remove('over');  // this / e.target is previous target element.
}

var cols = document.querySelectorAll('#columns .column');
[].forEach.call(cols, function(col) {
  col.addEventListener('dragstart', handleDragStart, false);
  col.addEventListener('dragenter', handleDragEnter, false);
  col.addEventListener('dragover', handleDragOver, false);
  col.addEventListener('dragleave', handleDragLeave, false);
});

function handleDrop(e) {
  // this / e.target is current target element.

  if (e.stopPropagation) {
    e.stopPropagation(); // stops the browser from redirecting.
  }

  // See the section on the DataTransfer object.

  return false;
}

function handleDragEnd(e) {
  // this/e.target is the source node.

  [].forEach.call(cols, function (col) {
    col.classList.remove('over');
  });
}

var cols = document.querySelectorAll('#columns .column');
[].forEach.call(cols, function(col) {
  col.addEventListener('dragstart', handleDragStart, false);
  col.addEventListener('dragenter', handleDragEnter, false)
  col.addEventListener('dragover', handleDragOver, false);
  col.addEventListener('dragleave', handleDragLeave, false);
  col.addEventListener('drop', handleDrop, false);
  col.addEventListener('dragend', handleDragEnd, false);
});

(function($) {

function formataDinheiro (number, decimals,decPoint,thousandsSep) {

  number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
  var n = !isFinite(+number) ? 0 : +number
  var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
  var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
  var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
  var s = ''

  var toFixedFix = function (n, prec) {
    var k = Math.pow(10, prec)
    return '' + (Math.round(n * k) / k)
      .toFixed(prec)
  }

  // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || ''
    s[1] += new Array(prec - s[1].length + 1).join('0')
  }

  return s.join(dec)
}

function encode_utf8(ua) {
	var s;
	var decoder = new TextDecoder('utf-8')
	s = decoder.decode(ua);

    return s; 
}

function removerAcentos( newStringComAcento ) {
  var string = newStringComAcento;
	var mapaAcentosHex 	= {
		a : /[\xE0-\xE6]/g,
		e : /[\xE8-\xEB]/g,
		i : /[\xEC-\xEF]/g,
		o : /[\xF2-\xF6]/g,
		u : /[\xF9-\xFC]/g,
		c : /\xE7/g,
		n : /\xF1/g
	};

	for ( var letra in mapaAcentosHex ) {
		var expressaoRegular = mapaAcentosHex[letra];
		string = string.replace( expressaoRegular, letra );
	}

	return string;
}

 var cores = [
        '#FFFAFA',
        '#F8F8FF',
        '#F5F5F5',
        '#DCDCDC',
        '#FFFAF0',
        '#FDF5E6',
        '#FAF0E6',
        '#FAEBD7',
        '#FFEFD5',
        '#FFEBCD',
        '#FFE4C4',
        '#FFDAB9',
        '#FFDEAD',
        '#FFE4B5',
        '#FFF8DC',
        '#FFFFF0',
        '#FFFACD',
        '#FFF5EE',
        '#F0FFF0',
        '#F5FFFA',
        '#F0FFFF',
        '#F0F8FF',
        '#E6E6FA',
        '#FFF0F5',
        '#FFE4E1',
        '#FFFFFF'
    ];
    
	function acharVariavel(){

		var ret = [];

		var itens = $('.relatorio-IMPUTS');

		$.each( itens, function( key, value ) {

		  var valor = $(value).data('parametro');

		  ret.push(valor);

		});

		return ret;

	}

	function acharCampo(){

		var ret = [];

		var itens = $('.relatorio-CAMPOS');

		$.each( itens, function( key, value ) {

		  var valor = $(value).data('campo');

		  ret.push(valor);

		});

		return ret;

	}

	function ativarDatatable(colunas) {

		var table = $('.historico-corpo-tabela')
		var fonte = $('.relatorio-fonte').val();

		var buttonCommon1 = {
	        exportOptions: {
	            format: {
	                body: function ( data, row, column, node ) {
	                   	var vcel = data.replace('.', '');
		                	vcel = vcel.replace(',', '.');
		                var tipo = colunas[column][4];

						if(tipo == 1){
							vcel = data;
						}

	                    return  vcel;
	                }
	            }
	        }
	    };

	    var buttonCommon3 = {
	        exportOptions: {
	            format: {
	                body: function ( data, row, column, node ) {
	                   	var vcel = data.replace('.', '');
		                var tipo = colunas[column][4];

						if(tipo == 1){
							vcel = data;
						}

	                    return  vcel;
	                }
	            }
	        }
	    };

	    var buttonCommon2 = {
	        exportOptions: {
	            format: {
	                body: function ( data, row, column, node ) {
	                    return  data;
	                }
	            }
	        }
	    };

		$(table).DataTable( {
			"scrollX": true,
			aoColumnDefs: [ {
		      "aTargets": [0],
		      "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {

		      	var cont = 0;
		      	var ativar = 0;
		      	function tamanhoColunas0(item, index){
		      		if(index > 0){
		      			if(item != ''){
		      				ativar = 1;
		      			}
		      		}
		      		cont++;
				}

				oData.forEach(tamanhoColunas0);

		      	if(ativar == 0){
		          $(nTd).attr('colSpan',cont);
		      	}

		      }
		    } ],
		    fnCreatedRow: function( nRow, aData, iDataIndex ) {

				$(nRow).find('[colSpan]').each( function(index,element){

					for( i = 1; parseInt( $(element).attr('colSpan') ) > i ; i++ ){
						var nextIndex = $(element).index() + i;
						$(nRow).find("td:eq("+ nextIndex +")").hide();
					}
				});

		    },
		    scrollY:'74vh',
        	autoFill: true,
			oLanguage: {
				buttons: {
		            copyTitle: 'Dados copiados',
		            copyKeys: 'Use o teclado ou o menu para colar',
		            copySuccess: {
				        1: "Copiado uma linha",
				        _: "Copiado %d linhas"
				    },
		        },
			    "sEmptyTable": "Nenhum registro encontrado",
			    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
			    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
			    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
			    "sInfoPostFix": "",
			    "sInfoThousands": ".",
			    "sLengthMenu": "_MENU_ resultados por página",
			    "sLoadingRecords": "Carregando...",
			    "sProcessing": "Processando...",
			    "sZeroRecords": "Nenhum registro encontrado",
			    "sSearch": "Filtrar",
			    "oPaginate": {
			        "sNext": "Próximo",
			        "sPrevious": "Anterior",
			        "sFirst": "Primeiro",
			        "sLast": "Último"
			    },
			    "oAria": {
			        "sSortAscending": ": Ordenar colunas de forma ascendente",
			        "sSortDescending": ": Ordenar colunas de forma descendente"
			    }
			},
			sPaginationType: "full_numbers",
			bPaginate: false,
			bInfo:false,
	        dom: 'Bfrtip',
	        buttons: [
            	$.extend( true, {}, buttonCommon2, {
                	extend: 'copyHtml5',
                	text: 'Copiar'
                	
	            } ),
	            $.extend( true, {}, buttonCommon3, {
                	extend: 'csvHtml5',
                	text: 'CSV',
	            } ),
	            $.extend( true, {}, buttonCommon1, {
	                extend: 'excelHtml5',
	                text: 'Excel',
	                customize: function(xlsx) {
		                var sheet = xlsx.xl.worksheets['sheet1.xml'];
		 	
		 				//class_title(sheet);

		                // Loop over the cells in column `F`
		                $('row c[r^="E"]', sheet).each( function () {

		                	var vcel = $(this).text().replace('.', '');
		                	    vcel = vcel.replace(',', '.');
		                	
		                	var valor = vcel;

		                    if ( $('is t', this).text().replace(/[^\d]/g, '') * 1 >= 100 ) {
		                        $(this).attr( 's', '20' );
		                    }
		                });
		            }
	            } ),
	            $.extend( true, {}, buttonCommon2, {
	                extend: 'pdfHtml5',
	                text: 'PDF',
	                customize: function ( win ) {

	                	win['footer']=(function(page, pages) {
							return {
								columns: [
									'',
									{
										alignment: 'right',
										text: [
											'Página ',
											{text: page.toString(), italics: true },
											' de ',
											{ text: pages.toString(), italics: true }
										]
									}
								],
								margin: [50, 0]
							}
						});

	                	///*
	                	var titulo = $('.titulo-relatorio'  ).val();
	                	var filtro = $('.filtro-relatorio'  ).val();
	                	var user   = $('.user-relatorio'    ).val();
	                	var versao = $('.relatorio-versao'  ).val();
	                	var pagina = $('.relatorio-paisagem').val();

	                	var tamanhos_colunas = [];

	                	if(pagina > 0){

	                		win.pageOrientation = 'landscape';
	                		
							function tamanhoColunas1(item, index){
								tamanhos_colunas.push({width:'*'});	
							}

							var itens = win.content[1].table.body[0];

							itens.forEach(tamanhoColunas1);
							win.content[1].table.widths = tamanhos_colunas;

	                	}else{

	                		win.pageOrientation = 'portrait';

	                		function tamanhoColunas2(item, index){
								if(index > 0){
									tamanhos_colunas.push({width:'auto'});	
								}else{
									tamanhos_colunas.push({width:'*'});	
								}
							}

							var itens = win.content[1].table.body[0];

							itens.forEach(tamanhoColunas2);
							win.content[1].table.widths = tamanhos_colunas;
	                	}
						
						win.content[0] = {};
						var f = (parseInt(fonte)+2);

						win.styles.collCabe1 = {
							alignment:"left",
							bold:true,
							fontSize:f
						};

						win.styles.collCabe0 = {
							alignment:"right",
							bold:true,
							fontSize:f-2
						};

						win.content[0].table = {
							widths: ['*', 'auto'],
							body: [
								[
									{
										border: [true, true, false, false],
										text: 'GESTÃO CORPORATIVA ­ DELFA',
										style:'collCabe1'
									},
									{
										border: [false, true, true, false],
										text: moment().format('L') + ' ' + moment().format('LT'),
										style:'collCabe0'
									}
								],
								[
									{
										border: [true, false, false, false],
										text: titulo,
										style:'collCabe1'
									},
									{
										border: [false, false, true, false],
										text: user,
										style:'collCabe0'
									}
								],
								[
									{
										border: [true, false, false, true],
										text: filtro,
										style:'collCabe1'
									},
									{
										border: [false, false, true, true],
										text: 'Vr:'+versao,
										style:'collCabe0'
									}
								]
							]
						};

						//*/.styles.tableBodyEven
						win.styles.collNumber1 = {
							alignment:"right",
							fontSize:fonte,
							fillColor:"#f3f3f3"
						};

						win.styles.collNumber0 = {
							alignment:"right",
							fontSize:fonte,
						};

						win.styles.collString1 = {
							alignment:"left",
							fontSize:fonte,
							fillColor:"#f3f3f3"
						};

						win.styles.collString0 = {
							alignment:"left",
							fontSize:fonte,
						};

						win.styles.collTitle0 = {
							alignment:"right",
							bold:true,
							color:"white",
							fillColor:"#2d4154",
							fontSize:f
						};

						win.styles.collTitle1 = {
							alignment:"left",
							bold:true,
							color:"white",
							fillColor:"#2d4154",
							fontSize:f
						};

						var linhas = win.content[1].table.body;
						function tratar(linha, l){
							var resto = (l % 2);

							if(l > 0){
								
								function validar1(coluna, c){
									var item  = colunas[c][4];
									if(item == 2){
										win.content[1].table.body[l][c].style = "collNumber"+resto;
									}
									if(item == 1){
										win.content[1].table.body[l][c].style = "collString"+resto;
									}
								}
								linha.forEach(validar1);

							}else{
								function validar2(coluna, c){
									var item  = colunas[c][4];
									if(item == 2){
										win.content[1].table.body[l][c].style = "collTitle0";
									}
									if(item == 1){
										win.content[1].table.body[l][c].style = "collTitle1";
									}
								}
								linha.forEach(validar2);
							}

						}
						linhas.forEach(tratar);

						//win.styles.tableHeader.alignment = "left";

						////class_title(win);
	                }
	            } ),
	            $.extend( true, {}, buttonCommon2, {
	                extend: 'print',
	                text: 'Imprimir',
	                footer: true,
	                header:false,
	                autoPrint:true,          
	                customize: function ( win ) {
	                	
	                	var f1 = (parseInt(fonte)+2);
	                	var f2 = (parseInt(fonte)+4);

	                	var titulo = $('.titulo-relatorio').val();
	                	var filtro = $('.filtro-relatorio').val();
	                	var user   = $('.user-relatorio').val();
	                	var pagina = $('.relatorio-paisagem').val();
	                	var versao = $('.relatorio-versao'  ).val();

		                if(pagina == 1){
		                	var head = $(win.document.head).html() + '<link rel="stylesheet" href="'+urlhost+'/build/assets/images/page_l.css">';
		                }else{
		                	var head = $(win.document.head).html() + '<link rel="stylesheet" href="'+urlhost+'/build/assets/images/page.css">';
		                }

	                	$(win.document.head).html(head);

	                	//class_title($(win.document.table));

	                	$(win.document.body).find( 'tbody' )
	                        .css( 'font-size', f1+'px' );

	                    $(win.document.body).find( 'th' )
	                        .css( 'font-size', f2+'px' );

	                    $(win.document.body).find( 'th' )
	                        .css( 'font-weight', '600' ); 

	                    $(win.document.body)
	                        .css( 'font-size', f2+'px' )
	                        .prepend(
	                            '<page size="A4">'+
    	                            '<section id="top">'+
        	                            '<div class="center">'+
            	                            '<label style="font-size: '+f2+'px; font-weight: 600;">GESTÃO CORPORATIVA - DELFA</label>'+
            	                            '<label style="font-size: '+f2+'px; font-weight: 600;">'+titulo+'</label>'+
				                            '<label style="font-size: '+f2+'px; font-weight: 600;">'+filtro+'</label>'+
        	                            '</div>'+
        	                            '<div class="right">'+
            	                            '<label style="font-size: '+(f2-2)+'px; font-weight: 600;">'+moment().format('L')+' '+moment().format('LT')+'</label>'+
            	                            '<label  style="font-size:'+(f2-2)+'px; font-weight: 600;" class="pagina">'+user+'</label>'+
            	                            '<label  style="font-size:'+(f2-2)+'px; font-weight: 600;" class="pagina">Vr:'+versao+'</label>'+
        	                            '</div>'+
    	                            '</section> '+      
	                            '</page>'
	                        );

	                    $(win.document).find('title')
	                        .css( 'font-size', f2+'px' )
	                        .html('');
	 
	                    $(win.document.body).find( 'table' )
	                        .addClass( 'compact' )
	                        .css( 'font-size', 'inherit' );
	                    ;

	                    $(win.document.body).find( 'tr' ).each(function( index ) {

	                    	var td = $(this).find('td');

							function validar3(coluna, c){

								var item  = colunas[c][4];
								var cell  = td[c];

								$(cell).addClass('dados-tipo-'+item);
								
							}
							colunas.forEach(validar3);

						});

	                    //////////////////////////////////////////////////////////

						$(win.document.body).find( 'tr' ).each(function( index ) {

	                    	var tr = $(this);
	                    	var ativar = 0;
	                    	var colunas = 0;

							$(tr).find( 'td' ).each(function(c) {
								if(c > 0){
					      			if($(this).html() != ''){
					      				ativar = 1;
					      			}
					      		}
					      		colunas = c;								
							});

							colunas = colunas +1;

							if(ativar == 0){
								$(tr).css({
									'font-weight': '800'
								});

								$(tr).find( 'td' ).each(function(c) {
									if(c > 0){
						      			$(this).remove();
						      		}else{
						      			$(this).attr({'colspan': colunas});	
						      		}								
								});
							}

						});
	                        
	                }
	            })
        ],
	        bSort: false
	    } );

		//$('.dt-buttons').append('<span><input type="checkbox" class="tipo-pagina" name="vehicle" value="0"> PAISAGEM</span>');

	}

    function mostrarResultado(dados,colunas,agrupamento,campos_todos){

    	$('.lista-resultado').html('');

    	var col_agrups = [];
    	var cont = 0;
        var tab = '';

    	function getAgrup(item, index) {
    		var c = [item[0],item[2],tab+item[3],'','',0,item[10],[],item[13]];
    		col_agrups.push(c);
    		tab = '';//tab+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    	}

    	agrupamento.forEach(getAgrup);

    	var rel_nome = $('.relatorio-NOME').val();
    	var regex 	 = new RegExp(' ', 'g');
		rel_nome 	 = rel_nome.replace(regex, '_');
		var regex 	 = new RegExp('-', 'g');
		rel_nome 	 = rel_nome.replace(regex, '_');
		rel_nome 	 = removerAcentos(rel_nome) + '.csv'

		var agrupado = 0;

    	var fonte_html  = $('.relatorio-fonteHTML').val();
    	var totalizador = $('.relatorio-totalizador').val();
    	var total_grupo = [];
    	var total_geral = [];

    	var temp_geral = [];
    	var temp_grupo = [];

    	var contador_geral = 0;

    	var grupoA = [];
    	var grupoB = [];

    	col_agrups.forEach(function(iten, i) {
			total_grupo[iten[0]] = [];

			colunas.forEach(function(item, index) {
				total_grupo[iten[0]][item[11]] = 0;
			});

		});

		col_agrups.forEach(function(iten, i) {

			colunas.forEach(function(item, index) {
				total_grupo[iten[0]][item[11]] = 0;
			});

		});

        var ultima_linha = [];
        var ultimo_item  = [];

    	var cor_agrup = $('.relatorio-cor').val();

    	html = '<table id="tabeladedados" class="table table-striped table-bordered table-hover historico-corpo-tabela"><thead><tr>';

    			//cabecario
                colunas.forEach(function(item, index) {
                	html = html + ' <th class="estacao title-prod" style="font-size:'+(parseInt(fonte_html)+2)+'px;">'+item[3]+'</th>';
				});

        		html =  html + '</tr></thead><tbody style="font-size:'+fonte_html+'px; text-transform: none;">';

        		//montar linhas
        		dados.forEach(function(item, index) {

    			if(col_agrups.length > 0){

    				var str = '';

    				var arr3 =  Object.keys(item).map(function (key) {return key;});

    				ultima_linha = arr3;
    				ultimo_item  = item;

    				arr3.reverse().forEach(function(key, j) {

    					var tot = [];
        				var tot = jQuery.extend(true, {}, total_grupo);

    					col_agrups.forEach(function(iten, index) {
				    		if(iten[0] == key){

        						if(iten[3] == item[key]){

        						}else{

    								if((totalizador == 1) && (((agrupado +1)- col_agrups.length) > 0) && (iten[6] > 0)){

		        						html =  html + '<tr tabindex="0" class="">';

			        					colunas.forEach(function(item, index) {

			        						var valor = formataDinheiro(total_grupo[iten[0]][item[11]],item[6],',','.');

			        						tot[iten[0]][index] = valor;

			        						var formula = item[13];

											if(formula != '#'+item[0]+'#' && formula != ''){
												var temp = formula + '';

												for(var k = 0; k < 10; k++){

													colunas.forEach(function(aa, indexj) {

														var v = tot[iten[0]][indexj] + '';
															v = v.replace('.', '' );
															v = v.replace(',', '.');

									    				temp = temp.replace( '#'+aa[0].trim()+'#' , v);
										    		});

												}

												try {
													valor = eval(temp);
													valor = formataDinheiro(valor,item[6],',','.');
												} catch(e) {
													valor = 'ERRO';	
													console.log(e)
												}
									    		
									    	}

			        						total_grupo[iten[0]][item[11]] = 0;

			        						if(item[5] == 1){
							        			html = html + '<td  style="font-weight: bold;font-style: italic;" class="descricao coll-prod dados-tipo-'+item[4]+'" data-tipo="'+item[4]+'" title="" style="background-color:'+item[2]+'";>'+item[14]+valor+item[15]+'</td>';
											}else{
												if(item[5] == 3){
								        			html = html + '<td  style="font-weight: bold;font-style: italic;" class="descricao coll-prod dados-tipo-'+item[4]+'" data-tipo="'+item[4]+'" title="" style="background-color:'+item[2]+'";>TOTAL GRUPO ('+iten[2]+')</td>';
												}else{
													html = html + '<td  style="font-weight: bold;font-style: italic;" class="descricao coll-prod dados-tipo-'+item[4]+'" data-tipo="'+item[4]+'" title="" style="background-color:'+item[2]+'";></td>';
												}
											}
										});

										html =  html + '</tr>';
									}

		        					agrupado++;
			        			}	
        					}
				    	});
    				});

    				grupoA = [];
    				
    				var arr2 = Object.keys(item).map(function (key) {
				    	col_agrups.forEach(function(iten, index) {

				    		if(iten[0] == key){

        						if((iten[3]+'').trim() == (item[key] + '').trim()){

        						}else{

		        					agrupado++;
		        					//*
		        					
		        					for ($w = index + 1; $w < col_agrups.length; $w++) {

									    if(((agrupado +1)- col_agrups.length) > 0){
		
										    if(grupoA.indexOf(col_agrups[$w][2]) == -1){
												grupoA.push(col_agrups[$w][2]);
												grupoB.push(col_agrups[$w]);
										    }
										}

									}

									var formula = iten[8];
									var temp    = formula + '';
									var valor   = item[key];

									if(formula != '#'+iten[0]+'#' && formula != '#' && formula != ''){
										
										temp = temp.replace( '#', '');
										temp = temp.replace( '#', '');

										valor = item[temp];
							    	}

									//*/

		        					html =  html + '<tr tabindex="0" class="">';
		        					html =  html + '<td class="descricao coll-prod" title="" style="font-weight: bold; border-bottom: 4px; background-color:'+iten[1]+'";>'+iten[2]+' - '+(valor+'').trim()+'</td>';
		        					
		        					var cont = 0;
		        					$.each(colunas,function(index, el) {

		        						if(cont > 0){
		        							html =  html + '<td style="background-color:'+cor_agrup+'";></td>';
		        						}	

		        						cont = cont +1;	
		        					});

		        					html =  html + '</tr>';

        						}

	        					iten[3] = item[key];
	        					iten[4] = item[key];	
        					}
				    	});

				    	return key;
    				});

    				col_agrups.forEach(function(iten, index) {

    					if(grupoA.indexOf(iten[2]) >= 0){
							//iten[3] = '';
	        				//iten[4] = '';
						}

    				});

    			}

				html =  html + '<tr tabindex="0" class="">';

    			var arr = Object.keys(item).map(function (key) { return item[key]; });

    			var vari = acharVariavel();

				var data_imput = '';
				colunas.forEach( function(data_iten, index) {
					data_imput += ' data-col_'+data_iten[0]+'="'+arr[data_iten[8]]+'" ';	
				});

    			colunas.forEach( function(item, index) {

					var valor = arr[item[8]];

    				if(item[4] == 2){
    					try {
    						valor = formataDinheiro(valor,item[6],',','.');
						} catch(e) {}
					}

					if(item[4] == 3){
    					try {
    						valor = moment(valor).format('DD/MM/YYYY');
						} catch(e) {}
					}

					var tamanhoCol = '';
					if(item[16] > 0){
						tamanhoCol = 'min-width:'+item[16]+'px;';
					}

					var data_link = item[17] + ' ';
					var val_item;

					if(data_link.length > 1){
						
						data_link = data_link.replace('#URLHOST#',urlhost);

						campos_todos.forEach( function(data_iten, index) {
							data_link = data_link.replace('#'+data_iten[0]+'#',arr[data_iten[8]]);
						});

						function tratar_vari(item, index) {
							var classe = item;
							var valor = $('.relatorio-IMPUTS-'+classe).val();
							data_link = data_link.replace('#'+classe+'#',valor);
						};

						vari.forEach(tratar_vari);

						valor = '<a href="'+data_link+'" target="_blank">'+valor+'</a>';
					}

                	html = html + '<td class="descricao coll-prod dados-tipo-'+item[4]+'" '+data_imput+' data-tipo="'+item[4]+'" title="" style="background-color:'+item[2]+';'+tamanhoCol+'">'+valor+'</td>';
					
					if(totalizador == 1){

						var v1 = 0;
						var v2 = arr[item[8]];
						var val_campo = arr[item[8]];

						var v3   = 0;
						var v4   = arr[item[8]];
						var tipo = item[12];

						var no = true;

						var result1 = total_geral[index] != undefined;

						if(result1){
							v1 = total_geral[index];
						}

						try {
							v2 = Number(v2);
						} catch(e) {
							no = false;
						}

						try {
							v1 = Number(v1);
						} catch(e) {
							no = false;
						}

						if(tipo == 1){
							total_geral[index] = v1 + v2;
						}else{
							if(tipo == 2){
								total_geral[index] = v1 + 1;
							}else{
								if(tipo == 3){
									if(v2>0){total_geral[index] = v1 + 1;}
								}else{
									if(tipo == 4){

										var a = temp_geral[index] == undefined;
										if(a){
											temp_geral[index] = [];
										}

										var validar = true;
										temp_geral[index].forEach(function(key, j) {
											if(key == val_campo){
												validar = false;
											}
										});
										if(validar){temp_geral[index].push(val_campo);}

										total_geral[index] = temp_geral[index].length;
									}else{
										total_geral[index] = '';
									}
								}
							}
						}

						if(!no){
							total_geral[index] = '';
						}				

						col_agrups.forEach(function(iten, i) {								

				    		var result2 = total_grupo[iten[0]][item[0]] != undefined;

					    	if(result2){
								v3 = total_grupo[iten[0]][item[0]];
							}

							try {
								v4 = Number(v4);
							} catch(e) {
								no = false;
							}

							try {
								v3 = Number(v3);
							} catch(e) {
								no = false;
							}

							if(tipo == 1){
								total_grupo[iten[0]][item[0]] = v3 + v4;
							}else{
								if(tipo == 2){
									total_grupo[iten[0]][item[0]] = v3 + 1;
								}else{
									if(tipo == 3){
										if(v2>0){total_grupo[iten[0]][item[0]] = v3 + 1;}
									}else{
										if(tipo == 4){

											var a = temp_grupo[iten[0]] == undefined;
											if(a){temp_grupo[iten[0]] = [];}

											var a = temp_grupo[iten[0]][item[0]] == undefined;
											if(a){temp_grupo[iten[0]][item[0]] = [];}

											if(total_grupo[iten[0]][item[0]] < 1){
												temp_grupo[iten[0]][item[0]] = [];
											}

											var validar = true;
											temp_grupo[iten[0]][item[0]].forEach(function(key, j) {
												if(key == val_campo){
													validar = false;
												}
											});

											if(validar){temp_grupo[iten[0]][item[0]].push(val_campo);}

											total_grupo[iten[0]][item[0]] = temp_grupo[iten[0]][item[0]].length;
										}else{
											total_grupo[index] = '';
										}
									}
								}
							}

							if(!no){
								total_grupo[iten[0]][index] = '';
							}

				    	});

					}
				});

				html =  html + '</tr>';
			});


			ultima_linha.forEach(function(key, j) {
			var item = ultimo_item;

			var tot = [];
        	var tot = jQuery.extend(true, {}, total_grupo);

			col_agrups.forEach(function(iten, index) {
	    		if(iten[0] == key){

					if((totalizador == 1) && (iten[6] > 0)){

						html =  html + '<tr tabindex="0" class="">';

    					function iniTotalGrupo9(item, index) {

    						var valor = formataDinheiro(total_grupo[iten[0]][item[11]],item[6],',','.');

    						tot[iten[0]][index] = valor;

    						var formula = item[13];

							if(formula != '#'+item[0]+'#' && formula != ''){
								var temp = formula +'';

								for(var k = 0; k < 10; k++){

									colunas.forEach(function(aa, indexj) {
										var v = tot[iten[0]][indexj] + '';
											v = v.replace('.', '' );
											v = v.replace(',', '.');

					    				temp = temp.replace( '#'+aa[0].trim()+'#' , v);
						    		});

								}

								try {
									valor = eval(temp);
									valor = formataDinheiro(valor,item[6],',','.');
								} catch(e) {
									valor = 'ERRO';	
									console.log(e)
								}
					    		
					    	}

    						total_grupo[iten[0]][item[11]] = 0;

    						if(item[5] == 1){
			        			html = html + '<td  style="font-weight: bold;font-style: italic;" class="descricao coll-prod dados-tipo-'+item[4]+'" data-tipo="'+item[4]+'" title="" style="background-color:'+item[2]+'";>'+item[14]+valor+item[15]+'</td>';
							}else{
								if(item[5] == 3){
				        			html = html + '<td  style="font-weight: bold;font-style: italic;" class="descricao coll-prod dados-tipo-'+item[4]+'" data-tipo="'+item[4]+'" title="" style="background-color:'+item[2]+'";>TOTAL GRUPO ('+iten[2]+')</td>';
								}else{
									html = html + '<td  style="font-weight: bold;font-style: italic;" class="descricao coll-prod dados-tipo-'+item[4]+'" data-tipo="'+item[4]+'" title="" style="background-color:'+item[2]+'";></td>';
								}
							}
						}

						colunas.forEach(iniTotalGrupo9);

						html =  html + '</tr>';
					}
						
				}
	    	});

		});


		if(((totalizador == 1) && (agrupado > 1)) || ((totalizador == 1) && (agrupado == 0))){
			html =  html + '<tr tabindex="0" class="">';
			function iniTotalGrupo2(item, index) {

				var valor = total_geral[index];
					valor = formataDinheiro(valor,item[6],',','.');

				var formula = item[13];

				if(formula != '#'+item[0]+'#'  && formula != ''){
					var temp = formula + '';

					for(var k = 0; k < 10; k++){

						colunas.forEach(function(aa, indexj) {
			    				temp = temp.replace( '#'+aa[0].trim()+'#' , total_geral[indexj] );
			    				temp = temp.replace( '#CONTADOR#' , contador_geral);	
			    		});

					}

					try {
						valor = eval(temp);
						valor = formataDinheiro(valor,item[6],',','.');
					} catch(e) {
						valor = 'ERRO';	
						console.log(e)
					}
		    		
		    	}

				if(item[5] == 1){
        			html = html + '<td  style="font-weight: bold;font-style: italic;" class="descricao coll-prod dados-tipo-'+item[4]+'" data-tipo="'+item[4]+'" title="" style="background-color:'+item[2]+'";>'+item[14]+valor+item[15]+'</td>';
				}else{
					if(item[5] == 3){
	        			html = html + '<td  style="font-weight: bold;font-style: italic;" class="descricao coll-prod dados-tipo-'+item[4]+'" data-tipo="'+item[4]+'" title="" style="background-color:'+item[2]+'";>TOTAL</td>';
					}else{
						html = html + '<td  style="font-weight: bold;font-style: italic;" class="descricao coll-prod dados-tipo-'+item[4]+'" data-tipo="'+item[4]+'" title="" style="background-color:'+item[2]+'";></td>';
					}
				}
			}

			colunas.forEach(iniTotalGrupo2);
			total_grupo = [];
			html =  html + '</tr>';
		}

		html =  html + '</tbody></table><br>';

        $('.preview-relatorio').html(html);

		ativarDatatable(colunas);

    }

    function filtrar(){

    	$('.preview-relatorio').html('');

    	var validar = 0;

    	var sql = $('.relatorio-SQL').val() + '';
		//var regex = new RegExp('\\n', 'g');
		//var sql = sql.replace(regex, ' ');

			var variaveis = acharVariavel();

			var sql_tratado = sql;

			var param = '';

			function tratar(item, index) {

				var classe = item;

				var valor = $('.relatorio-IMPUTS-'+classe).val();
				var imput = $('.relatorio-IMPUTS-VALOR-'+classe);

				var descricao = $(imput).data('DESCRICAO');
				var tipo 	  = $(imput).data('tipo');

				var regex = new RegExp(':'+item, 'g');

				if(param.length > 0){
					param += '&'+classe+'='+valor;
				}else{
					param += '?'+classe+'='+valor;	
				}

				if(tipo == 1){
					sql_tratado = sql_tratado.replace(regex, "'"+valor+"'");
				}else{
					if(tipo == 3){
						var d = moment(valor).format('DD.MM.YYYY');
						sql_tratado = sql_tratado.replace(regex, "'"+d+"'");
					}else{
						sql_tratado = sql_tratado.replace(regex, valor);	
					}
				}

				if((tipo == 2) || (tipo == 3)){

					if(valor == ''){
						validar = 1;	
					}
				}

			};

			variaveis.forEach(tratar);

			if(validar == 0){

				var campos_agrup	= [];
				var campos_viziveis = [];
				var campos_todos    = [];
		        var cont = 0;

		        function listar(item, index) {

					var obj_campo = $('.relatorio-CAMPOS-'+item);

					var cor    		= $(obj_campo).data('cor'       );
					var desc   		= $(obj_campo).data('descricao' );
					var visi   		= $(obj_campo).data('visivel'   );
					var clss   		= $(obj_campo).data('classe'    );
					var tota   		= $(obj_campo).data('totalizar' );
					var casa   		= $(obj_campo).data('casas'     );
					var orde   		= $(obj_campo).data('ordem'     );
					var inde   		= $(obj_campo).data('index'     );
					var agrup  		= $(obj_campo).data('agrup'     );
					var tagrup 		= $(obj_campo).data('tagrup'    );
					var total_tipo  = $(obj_campo).data('total_tipo');
					var formula     = $(obj_campo).data('formula'   );
					var prefix      = $(obj_campo).data('prefix'    );
					var sufix       = $(obj_campo).data('sufix'     );
					var campo  		= $(obj_campo).data('campo'     );
					var tamanho  	= $(obj_campo).data('tamanho'   );
					var link     	= $(obj_campo).data('link'      );

					if(agrup == 1){
						campos_agrup.push([item,cont,cor,desc,clss,tota,casa,orde,inde,agrup,tagrup,campo,total_tipo,formula,prefix,sufix,tamanho,link]);
					}

					if(visi == 1){
						campos_viziveis.push([item,cont,cor,desc,clss,tota,casa,orde,inde,agrup,tagrup,campo,total_tipo,formula,prefix,sufix,tamanho,link]);
					}

					campos_todos.push([item,cont,cor,desc,clss,tota,casa,orde,inde,agrup,tagrup,campo,total_tipo,formula,prefix,sufix,tamanho,link]);

					cont++;
					
				};

				var campos = acharCampo();

				campos.forEach(listar);

				var url_action = urlhost + "/_11080/getRetorno";

				console.log(sql_tratado);

				var dados = {
		            'SQL' :sql_tratado
		        };

		        var type = "POST";

		        function success2(dados){

		        	if(dados.length > 0){

		        		campos_viziveis = campos_viziveis.sort(function(a, b){
			        		return a[7]-b[7]
			        	});

			        	//class_title(campos_viziveis);

		        		mostrarResultado(dados,campos_viziveis,campos_agrup,campos_todos);
		        	}else{
						showAlert('Sem dados de retorno!,<br>altere o valor dos parametros!');
						setTimeout(function(){
							$('#filtrar-toggle').trigger('click');
						},1000);
					}
			    }

		        function erro2(data){
		            showErro(data);
		            setTimeout(function(){
						$('#filtrar-toggle').trigger('click');
					},1000);
		        }

		        execAjax1(type,url_action,dados,success2,erro2,false);
			}else{
				showAlert('Há parametros sem valor definido');
				setTimeout(function(){
					$('#filtrar-toggle').trigger('click');
				},1000);
			}	
    }

    $(document).on('click','.relatorio-filtrar', function(e) {
    	var rel_ID = $('.relatorio-ID').val();

    	var variaveis = acharVariavel();
		var param = '';

		variaveis.forEach(function(item, index) {

			var classe = item;

			var valor = $('.relatorio-IMPUTS-'+classe).val();

			var regex = new RegExp(':'+item, 'g');

			if(param.length > 0){
				param += '&'+classe+'='+valor;
			}else{
				param += '?'+classe+'='+valor;	
			}

		});

		var link = encodeURI(urlhost + '/_28000/'+rel_ID+ param);
        window.history.replaceState('Delfa - GC', 'Title', link);

    	filtrar(); 
    }); 

    $(document).ready(function() {
		var auto = $('.auto-filtro-relatorio').data('valor');
		if(auto > 0){
			$('.relatorio-filtrar').trigger('click');
		}
	});

	/////////////////////////////////////////////// - Cadastro - ////////////////////////////////////////////////////

	function cad_getSQL(){
    	var sql = $('.cad_relatorio-sql').val()  + '';
		var regex = new RegExp('\\n', 'g');
		sql = sql.replace(regex, ' ');

		return sql;
    }

    function cad_getSQL2(){
    	var sql = $('.cad_relatorio-sql').val()  + '';
		//var regex = new RegExp('\\n', 'g');
		//sql = sql.replace(regex, ' ');

		return sql;
    }

	function cad_acharVariavel(sql){

		var controle = 0;

		var contador = 0;
		var sql_temp = ' '+sql+' ';

		var encontrado_em_ini = 0;
		var encontrado_em_fim = 0;

		var ret = [];

		while (controle < 1) {
			
			sql_temp = sql_temp.substring(encontrado_em_ini + encontrado_em_fim,sql_temp.length);

			var encontrado_em_ini = sql_temp.search(":");

			var sub_temp = sql_temp.substring(encontrado_em_ini,sql_temp.length);

			var encontrado_em_fimR = [];
			var obj = [' ',',',')','||']

			var encontrado_em_fim = -1;

			for(var i = 0; i < (obj.length); i++){
				var aa = sub_temp.indexOf(obj[i]);

				if(aa >=0 ){
					encontrado_em_fimR.push(aa);
				}
			}

			var menor = encontrado_em_fimR.sort(function(a, b){return a-b});
			if(menor.length > 0){
				var encontrado_em_fim = menor[0];
			}

			var variavel = sql_temp.substring(encontrado_em_ini,encontrado_em_fim + encontrado_em_ini);

			contador++;


			if((encontrado_em_fim == -1) || (encontrado_em_fim == -1)){
				controle = 1;
			}else{

			if((encontrado_em_ini == -1) || (encontrado_em_ini == -1)){
				controle = 1;
			}else{

				if(contador == 20){
					controle = 1;
				}else{
					ret.push(variavel.trim());	
				}
			}}

		}

		return ret;
	}

	function cad_tratarSql(){

		var sql = cad_getSQL();

		var variaveis2 = cad_acharVariavel(sql);

		var variaveis = variaveis2.filter(function(este, i) {
		    return variaveis2.indexOf(este) == i;
		});


		$('.lista-variaveis').html('');

		function listar(item, index) {

			var html = $('.lista-variaveis').html();

			var regex = new RegExp(':', 'g');
			var classe = item.replace(regex, '');

			html = html + '<div class="campos-tabela-conteiner"><div class="campos-tabela"><div class="campo-tabela-desc">'+classe+'</div>'+
						  ' Descrição: <input type="text" class="c-variavel-'+classe+'-desc parametro-imput" name="fname" data-imput="'+classe+'" value="'+classe+'">'+
						  ' Valor para pré-Visualizar: <input type="text" class="c-variavel-'+classe+' parametro-imput" name="fname" data-imput="'+classe+'" value="">'+
						  ' Tipo: <select style="width: 196px;" class="c-variavel-'+classe+'-chec" required="">'+
						  ' 	<option value="1">01 - string</option>'+
						  ' 	<option value="2">02 - número</option>'+
						  ' 	<option value="3">03 - data</option>'+
						  '</select></div></div>';

			$('.lista-variaveis').html(html);
			
		};

		variaveis.forEach(listar);

		var html = $('.lista-variaveis').html();
			html = html + '<BR><button type="button" class="btn btn-success tratar-campos">'
	        html = html + '<span class="glyphicon glyphicon-ok"></span>'
	        html = html + 'Listar Campos'
	        html = html + '</button>'
		$('.lista-variaveis').html(html);		
	}

	function cad_getCamposSql(sql){

        var url_action = urlhost + "/_11080/getRetornoSql";
        
        var continuar = true;
        
        var dados = {
            'SQL' :sql
        };

        var type = "POST";

        function success(dados){

        	if(dados.length > 0){

        		var html = '<div>';

        		var cont = 0;
	            function listar(item, index) {

					html = html + '<div data-ordem="'+cont+'" class="campos-tabela-conteiner" draggable="true">'+
					' <input type="hidden" class="visivel-'+item+'-ordem ordem-item" value="'+cont+'">'+
					' <input type="hidden" class="visivel-'+item+'-index ordem-item" value="'+cont+'">'+
					' <div class="campos-tabela"><div class="campo-tabela-desc">'+item+'</div>'+
					' <input type="checkbox" class="visivel-'+item+'-chec" name="vehicle" value="0" checked> Visivel?<br>'+

					'<input type="checkbox" class="visivel-'+item+'-agrupar" name="vehicle" value="0"> Agrupar?<br>'+
					'<input type="checkbox" class="visivel-'+item+'-tagrupar" name="vehicle" value="0"> Total grupo?<br>'+

					' Totalizar: <select style="width: 196px;" class="visivel-'+item+'-total" required="">'+
					' 	<option value="0">00 - Não</option>'+
					' 	<option value="1">01 - Sim</option>'+
					' 	<option value="3">03 - Título</option>'+
					' </select>'+

					' Total Tipo: <select style="width: 196px;" class="visivel-'+item+'-total-tipo" required="">'+
					' 	<option value="1">01 - Soma</option>'+
					' 	<option value="2">02 - Contador</option>'+
					' 	<option value="3">03 - Contador>0</option>'+
					' </select>'+

					' Formula: <input type="text" class="visivel-'+item+'-formula" name="fname" value="#'+item+'#">'+
					' Prefixo: <input type="text" class="visivel-'+item+'-prefix" name="fname" value="">'+
					' Sufixo : <input type="text" class="visivel-'+item+'-sufix"  name="fname" value="">'+

					' Descrição: <input type="text" class="visivel-'+item+'-desc" name="fname" value="'+item+'">'+
					' Cor:<input type="color" style="width: 195px;" class="visivel-'+item+'-cor" name="fname" value="'+cores[cont]+'">'+
					' Decimais: <input type="text" class="visivel-'+item+'-casas" name="fname" value="0">'+

					' Tam. Min.: <input type="text" class="visivel-'+item+'-tam_min" name="fname" value="0">'+
					' Link: <input type="text" class="visivel-'+item+'-link" name="fname" value="">'+

					' Tipo: <select style="width: 196px;" class="visivel-'+item+'-tipo" required="">'+
					' 	<option value="1">01 - string</option>'+
					' 	<option value="2">02 - número</option>'+
					' 	<option value="3">03 - data</option>'+
					' </select><br>'+
					' </div></div>';

					cont++;
				};

				dados.forEach(listar);

				$('.lista-campos').html( html + '</div>');

				var html = $('.lista-campos').html();
				html = html + '<input type="hidden" class="agrupar" name="vehicle" value="0">'+
							  '<input type="hidden" class="agrupar-campo" name="fname" value="'+dados[0]+'">'+
							  '<input type="hidden" class="visivel-agrup-cor" name="fname" value="#00CED1"></div>';

				$('.lista-campos').html(html);

				var html = $('.lista-campos').html();
				html = html + '<BR><button type="button" class="btn btn-success tratar-resultado" >'
		        html = html + '<span class="glyphicon glyphicon-ok"></span>'
		        html = html + 'Pré-Visualizar'
		        html = html + '</button>'
				$('.lista-campos').html(html);


				var cols = document.querySelectorAll('.campos-tabela-conteiner');
				[].forEach.call(cols, function(col) {
				  col.addEventListener('dragstart', handleDragStart, false);
				  col.addEventListener('dragenter', handleDragEnter, false)
				  col.addEventListener('dragover', handleDragOver, false);
				  col.addEventListener('dragleave', handleDragLeave, false);
				  col.addEventListener('drop', handleDrop, false);
				  col.addEventListener('dragend', handleDragEnd, false);
				});

				var dragSrcEl = null;
				var oldDeag = null;

				function handleDragEnd(e) {
				  // this/e.target is the source node.

				  [].forEach.call(cols, function (col) {
				    col.classList.remove('over');
				  });
				}

				function handleDragStart(e) {
				  // Target (this) element is the source node.
				  
				  this.style.opacity = '0.4';
				  oldDeag = this;

				  dragSrcEl = this;

				  e.dataTransfer.effectAllowed = 'move';
				  e.dataTransfer.setData('text/html', this.innerHTML);
				}

				function handleDrop(e) {
				  // this/e.target is current target element.

				  if (e.stopPropagation) {
				    e.stopPropagation(); // Stops some browsers from redirecting.
				  }

				  // Don't do anything if dropping the same column we're dragging.
				  if (dragSrcEl != this) {

				  	var ini = $(this).data('ordem');
				  	var fim = $(dragSrcEl).data('ordem');

				  	$(this).data('',fim);
				  	$(dragSrcEl).data('',ini);

				    // Set the source column's HTML to the HTML of the columnwe dropped on.
				    dragSrcEl.innerHTML = this.innerHTML;
				    this.innerHTML = e.dataTransfer.getData('text/html');
				  }

				  oldDeag.style.opacity = '1';

				  return false;
				}


				function handleDragOver(e) {
				  if (e.preventDefault) {
				    e.preventDefault(); // Necessary. Allows us to drop.
				  }

				  e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

				  return false;
				}

				function handleDragEnter(e) {
				  // this / e.target is the current hover target.
				  this.classList.add('over');
				}

				function handleDragLeave(e) {
				  this.classList.remove('over');  // this / e.target is previous target element.
				}

			}else{
				showAlert('Sem dados de retorno!,<br>altere o valor dos parametros!');
			}
	    }

        function erro(data){
            showErro(data);
        }

        execAjax1(type,url_action,dados,success,erro,false);
    }

    function cad_getRetorno(sql){

        var url_action = urlhost + "/_11080/getRetorno";
        
        var continuar = true;
        
        var dados = {
            'SQL' :sql
        };

        var type = "POST";

        function success(dados){
	    }

        function erro(data){
            showErro(data);
        }

        execAjax1(type,url_action,dados,success,erro,false);
    }

    function cad_getRelatorios(filtro){

        var url_action = urlhost + "/_11080/getRelatorios";
        
        var continuar = true;
        
        var dados = {
            'filtro' : filtro
        };

        var type = "POST";

        function success(dados){
        	$('.lista-obj-11080').html(dados);
	    }

        function erro(data){
            showErro(data);
        }

        execAjax1(type,url_action,dados,success,erro,false);
    }

    $(document).on('click','.btn-filtro-obj', function(e) {
    	var filtro = $("input[name='filtro_obj']").val();
        cad_getRelatorios(filtro);
    });

    $(document).on('keypress','input[name=\'filtro_obj\']', function(e) {
    	if(e.which == 13) {
	    	var filtro = $("input[name='filtro_obj']").val();
	        cad_getRelatorios(filtro);
	    }
    });

    $(document).on('keyup','input[name=\'filtro_obj\']', function(e) {
    	var filtro = $("input[name='filtro_obj']").val();

    	if(filtro == '') {
	        cad_getRelatorios(filtro);
	    }
    });
    
    $(document).on('click','.tratar-sql', function(e) {

        cad_tratarSql(); 
    });

    $(document).on('click','.tratar-campos', function(e) {
    	var validar = 0;

        var sql = cad_getSQL();

		var variaveis2 = cad_acharVariavel(sql);

		var variaveis = variaveis2.filter(function(este, i) {
		    return variaveis2.indexOf(este) == i;
		});

		var sql_tratado = sql;

		$('.lista-campos').html('');

		function listar(item, index) {

			var regex = new RegExp(':', 'g');
			var classe = item.replace(regex, '');

			var valor = $('.c-variavel-'+classe).val();
			var chec = $('.c-variavel-'+classe+'-chec').val();

			if(chec == 0){
				validar = 1;
			}

			var regex = new RegExp(item, 'g');

			if((chec == 1) || (chec == 3)){
				sql_tratado = sql_tratado.replace(regex, "'"+valor+"'");
			}else{
				sql_tratado = sql_tratado.replace(regex, valor);	
			}

			if(valor == ''){
				validar = 1;	
			}

		};

		variaveis.forEach(listar);

		$('.lista-campos').html(sql_tratado);

		if(validar == 0){
			cad_getCamposSql(sql_tratado);
		}else{
			showAlert('Há parametros sem valor para "TESTE DE SQL" ou tipo definido');
		}
    });

    function preGravar(){

		var var_imputis = [];
		var var_campos  = [];
		var var_agrup 	= [];
		var var_info  	= [];

		var agrupar_chk = $('.agrupar:checked').length;
    	var agrupar_tag = $('.agrupar-campo').val();
    	var cor_agrup   = $('.visivel-agrup-cor').val();

    	var Nome 		= $('.relatorio-nome').val();
    	var Filtro 		= $('.relatorio-filtro').val();
    	var Tipo   		= $('.relatorio-tipo').val();
    	var Template	= $('.relatorio-template').val();
    	var versao  	= $('.relatorio-versao').val();
    	var fontew  	= $('.relatorio-fonteweb').val();
    	var fontex  	= $('.relatorio-fonteexp').val();
    	var totalizar 	= $('.relatorio-totalizador').val();
    	var grupo 		= $('.relatorio-grupo').val();
    	var zebrado	    = $('.relatorio-zebrado:checked').length;
    	var paisagem    = $('.relatorio-paisagem:checked').length;

    	var gravar = 0;

    	if(Nome.length 		== 0){ showAlert('Nome é obrigatório');					 gravar = 1; $('.relatorio-nome').focus();}else{
    	if(Filtro.length	== 0){ showAlert('Filtro é obrigatório');				 gravar = 1; $('.relatorio-filtro').focus();}else{
    	if(Tipo.length		== 0){ showAlert('Tipo é obrigatório');					 gravar = 1; $('.relatorio-tipo').focus();}else{
    	if(Template.length	== 0){ showAlert('Template é obrigatório');	  			 gravar = 1; $('.relatorio-template').focus();}else{
    	if(versao.length	== 0){ showAlert('Versao é obrigatório');	 			 gravar = 1; $('.relatorio-versao').focus();}else{
    	if(fontew.length	== 0){ showAlert('Fonte Web é obrigatório');			 gravar = 1; $('.relatorio-fonteweb').focus();}else{
    	if(fontex.length	== 0){ showAlert('Fonte para exportação é obrigatório'); gravar = 1; $('.relatorio-fonteexp').focus();}else{
    	if(totalizar.length	== 0){ showAlert('totalizador é obrigatório');			 gravar = 1; $('.relatorio-totalizador').focus();}else{
    	if(grupo.length		== 0){ showAlert('Menu Grupo é obrigatório');			 gravar = 1; $('.relatorio-grupo').focus();}		
    	}}}}}}}}

    	if(gravar == 0){

    		var sql2 = cad_getSQL();

    		var sql = $('.cad_relatorio-sql').val()  + ' ';
    		var variaveis2 = cad_acharVariavel(sql2);

			var variaveis = variaveis2.filter(function(este, i) {
			    return variaveis2.indexOf(este) == i;
			});

    		var_agrup.push(agrupar_tag,agrupar_chk,cor_agrup);
	    	var_info.push(Nome,Filtro,Tipo,Template,versao,zebrado,sql,fontew,fontex,totalizar,grupo,paisagem);

			var sql_tratado = sql;

			function tratar(item, index) {

				var regex = new RegExp(':', 'g');
				var classe = item.replace(regex, '');

				var valor = $('.c-variavel-'+classe).val();
				var regex = new RegExp(item, 'g');

				var valor = $('.c-variavel-'+classe).val();
				var desc  = $('.c-variavel-'+classe+'-desc').val();
				var chec = $('.c-variavel-'+classe+'-chec').val();

				var_imputis.push([classe,desc,chec]);

				var regex = new RegExp(item, 'g');

				if((chec == 1) || (chec == 3)){
					sql_tratado = sql_tratado.replace(regex, "'"+valor+"'");
				}else{
					sql_tratado = sql_tratado.replace(regex, valor);	
				}

			};

			variaveis.forEach(tratar);

			var url_action = urlhost + "/_11080/getRetornoSql";
		    
		    var continuar = true;
		    
		    var dados = {
		        'SQL' :sql_tratado
		    };

		    var type = "POST";

		    function success(dados){

		    	var campos_viziveis = [];
		    	var cont = 0;

		        function listar(item, index) {

					var campo   = $('.visivel-'+item+'-chec:checked'    ).length;
					var agrup   = $('.visivel-'+item+'-agrupar:checked' ).length;
					var tagrup  = $('.visivel-'+item+'-tagrupar:checked').length;
					var cor     = $('.visivel-'+item+'-cor'       ).val();
					var desc    = $('.visivel-'+item+'-desc'      ).val();
					var clss    = $('.visivel-'+item+'-tipo'      ).val();
					var tota    = $('.visivel-'+item+'-total'     ).val();
					var casa    = $('.visivel-'+item+'-casas'     ).val();
					var ordem   = $('.visivel-'+item+'-casas'     ).parent().parent().data('ordem');
					var index   = $('.visivel-'+item+'-index'     ).val();
					var ttipo   = $('.visivel-'+item+'-total-tipo').val();
					var formula = $('.visivel-'+item+'-formula'   ).val();
					var prefix  = $('.visivel-'+item+'-prefix'    ).val();
					var sufix   = $('.visivel-'+item+'-sufix'     ).val();
					var tam_min = $('.visivel-'+item+'-tam_min'   ).val();
					var link    = $('.visivel-'+item+'-link'      ).val();
					
					var_campos.push([item,campo,cont,cor,desc,clss,tota,casa,ordem,index,agrup,tagrup,ttipo,formula,prefix,sufix,tam_min,link]);

					if(campo == 1){
						campos_viziveis.push([item,campo,cont,cor,desc,clss,tota,casa,ordem,index,agrup,tagrup,ttipo,formula,prefix,sufix,tam_min,link]);
					}

					cont++;
				
				};

				dados.forEach(listar);

				var dado2 = {
					'grupo' 	: var_agrup,
					'info' 		: var_info,
					'campos' 	: var_campos,
					'imputis' 	: var_imputis
				}

					///////////////////////////////////////////////////////////////
					var url_action = urlhost + "/_11080/Gravar";

					var dados = {
			            'SQL' :sql_tratado
			        };

			        var type = "POST";

			        function success2(dados){
			        	showSuccess('Gravado com sucesso');
			        	setTimeout(function(){
			        		 window.location.assign(urlhost + "/_11080/"+dados)
			        	},1500);
				    }

			        function erro2(data){
			            showErro(data);
			        }

			        execAjax1(type,url_action,dado2,success2,erro2,false);
			        ///////////////////////////////////////////////////////////////

		    }

	        function erro(data){
	            showErro(data);
	        }

	        execAjax1(type,url_action,dados,success,erro,false);
    	}
    }

    $(document).on('click','.tratar-resultado', function(e) {

    	var sql = cad_getSQL2();

    	var variaveis2 = cad_acharVariavel(sql);

		var variaveis = variaveis2.filter(function(este, i) {
		    return variaveis2.indexOf(este) == i;
		});

    	$('.imputs-relatorio').html('');

    	$('.imputs-relatorio').append('<input type="hidden" name="relatorio-SQL"   class="relatorio-SQL"   value="'+sql+'">');
    	$('.imputs-relatorio').append('<input type="hidden" name="user-relatorio"  class="user-relatorio"  value="USER TESTE">');

    	var nome 		= $('.relatorio-nome').val();
    	var filtro 		= $('.relatorio-filtro').val();
    	var tipo 		= $('.relatorio-tipo').val();
    	var grupo 		= $('.relatorio-grupo').val();
    	var template 	= $('.relatorio-template').val();
    	var versao 		= $('.relatorio-versao').val();
    	var fonteweb 	= $('.relatorio-fonteweb').val();
    	var fonteexp 	= $('.relatorio-fonteexp').val();
    	var totalizador = $('.relatorio-totalizador').val();
    	var zebrado 	= $('.relatorio-zebrado:checked').length;
    	var paisagem 	= $('.relatorio-paisagem:checked').length;

    	$('.imputs-relatorio').append('<input type="hidden" name="titulo-relatorio"        class="titulo-relatorio"        value="TELATORIO TESTE">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-ID"            class="relatorio-ID"            value="0">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-NOME"          class="relatorio-NOME"          value="'+nome+'">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-DESCRICAO"     class="relatorio-DESCRICAO"     value="TESTE">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-TIPO"          class="relatorio-TIPO"          value="'+tipo+'">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-TEMPLATE_ID "  class="relatorio-TEMPLATE_ID "  value="'+template+'">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-STATUS "       class="relatorio-STATUS "       value="1">');

        $('.imputs-relatorio').append('<input type="hidden" name="filtro-relatorio"        class="filtro-relatorio"        value="'+filtro+'">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-relatorio_id"  class="relatorio-relatorio_id"  value="0">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-filtro"        class="relatorio-filtro"        value="'+filtro+'">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-agrupamento"   class="relatorio-agrupamento"   value="0">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-agrupar "      class="relatorio-agrupar "      value="0">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-zebrado "      class="relatorio-zebrado "      value="'+zebrado+'">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-versao "       class="relatorio-versao "       value="'+versao+'">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-cor "          class="relatorio-cor "          value="0">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-fonte "        class="relatorio-fonte "        value="'+fonteweb+'">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-fonteHTML "    class="relatorio-fonteHTML "    value="'+fonteweb+'">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-totalizador "  class="relatorio-totalizador "  value="'+totalizador+'">');
        $('.imputs-relatorio').append('<input type="hidden" name="relatorio-paisagem "     class="relatorio-paisagem "     value="'+paisagem+'">');

        var sql_tratado = sql;

		variaveis.forEach(function(item, index){

			var regex = new RegExp(':', 'g');
			var classe = item.replace(regex, '');

			var valor = $('.c-variavel-'+classe).val();
			var regex = new RegExp(item, 'g');

			var valor = $('.c-variavel-'+classe).val();
			var desc = $('.c-variavel-'+classe+'-desc').val();
			var chec = $('.c-variavel-'+classe+'-chec').val();

			var regex = new RegExp(item, 'g');

			if((chec == 1) || (chec == 3)){
				sql_tratado = sql_tratado.replace(regex, "'"+valor+"'");
			}else{
				sql_tratado = sql_tratado.replace(regex, valor);	
			}

			$('.imputs-relatorio').append('<input type="hidden"'+
				        '	name="relatorio-IMPUTS"'+
				        '	class="relatorio-IMPUTS relatorio-IMPUTS-'+classe+'"'+
				        '	data-PARAMETRO="'+classe+'"'+
				        '	data-DESCRICAO="'+desc+'"'+
				        '	data-TIPO="'+chec+'"'+
				        '	value="'+valor+'"'+
				        '>');

			$('.imputs-relatorio').append('<input type="hidden"'+
				        '	name="relatorio-IMPUTS"'+
				        '	class="relatorio-IMPUTS relatorio-IMPUTS-VALOR-'+classe+'"'+
				        '	data-PARAMETRO="'+classe+'"'+
				        '	data-DESCRICAO="'+desc+'"'+
				        '	data-TIPO="'+chec+'"'+
				        '	value="'+valor+'"'+
				        '>');

		});

    	var url_action = urlhost + "/_11080/getRetornoSql";
        
        var continuar = true;
        
        var dados = {
            'SQL' :sql_tratado
        };

        var type = "POST";

        function success(dados){

        	var campos_agrup	= [];
        	var campos_viziveis = [];
        	var cont = 0;

            function listar(item, index) {

            	var campo   = $('.visivel-'+item+'-chec:checked' ).length;
				var agrup   = $('.visivel-'+item+'-agrupar:checked' ).length;
				var tagrup  = $('.visivel-'+item+'-tagrupar:checked' ).length;
				var cor     = $('.visivel-'+item+'-cor'       ).val();
				var desc    = $('.visivel-'+item+'-desc'      ).val();
				var clss    = $('.visivel-'+item+'-tipo'      ).val();
				var tota    = $('.visivel-'+item+'-total'     ).val();
				var casa    = $('.visivel-'+item+'-casas'     ).val();
				var ordem   = $('.visivel-'+item+'-casas'     ).parent().parent().data('ordem');
				var index   = $('.visivel-'+item+'-index'     ).val();
				var ttipo   = $('.visivel-'+item+'-total-tipo').val();
				var formula = $('.visivel-'+item+'-formula'   ).val();
				var prefix  = $('.visivel-'+item+'-prefix'    ).val();
				var sufix   = $('.visivel-'+item+'-sufix'     ).val();
				var link    = $('.visivel-'+item+'-link'      ).val();
				var tamanho = $('.visivel-'+item+'-tam_min'   ).val();

            	$('.imputs-relatorio').append('<input type="hidden"'+
						    '    name                ="relatorio-CAMPOS"'+
						    '    class               ="relatorio-CAMPOS relatorio-CAMPOS-'+item+'"'+
						    '    data-PERCENTUAL     ="'+0+'"'+
						    '    data-DESCRICAO      ="'+desc+'"'+
						    '    data-CLASSE         ="'+clss+'"'+
						    '    data-CAMPO          ="'+item+'"'+
						    '    data-ORDEM          ="'+ordem+'"'+
						    '    data-MASCARA        ="'+casa+'"'+
						    '    data-VISIVEL        ="'+campo+'"'+
						    '    data-COR            ="'+cor+'"'+
						    '    data-TOTALIZAR      ="'+tota+'"'+
						    '    data-CASAS          ="'+casa+'"'+
						    '    data-INDEX          ="'+index+'"'+
						    '    data-AGRUP          ="'+agrup+'"'+
						    '    data-TAGRUP         ="'+tagrup+'"'+
						    '    data-TOTAL_TIPO     ="'+ttipo+'"'+
						    '    data-FORMULA        ="'+formula+'"'+
						    '    data-PREFIX         ="'+prefix+'"'+
						    '    data-SUFIX          ="'+sufix+'"'+
						    '    data-TAMANHO        ="'+tamanho+'"'+
						    '    data-LINK           ="'+link+'"'+
					        '>');

				cont++;
				
			};

			dados.forEach(listar);

			//$('.imputs-relatorio').html(htm_imputs);

			filtrar();

	    }

        function erro(data){
            showErro(data);
        }

        execAjax1(type,url_action,dados,success,erro,false);  
    });

    $(document).on('click','.tratar-resultado2', function(e) {

    	var sql = cad_getSQL();

			var variaveis2 = cad_acharVariavel(sql);

			var variaveis = variaveis2.filter(function(este, i) {
			    return variaveis2.indexOf(este) == i;
			});

			var sql_tratado = sql;

			function tratar(item, index) {

				var regex = new RegExp(':', 'g');
				var classe = item.replace(regex, '');

				var valor = $('.c-variavel-'+classe).val();
				var regex = new RegExp(item, 'g');

				var valor = $('.c-variavel-'+classe).val();
				var chec = $('.c-variavel-'+classe+'-chec').val();

				var regex = new RegExp(item, 'g');

				if((chec == 1) || (chec == 3)){
					sql_tratado = sql_tratado.replace(regex, "'"+valor+"'");
				}else{
					sql_tratado = sql_tratado.replace(regex, valor);	
				}

			};

			variaveis.forEach(tratar);

    	var url_action = urlhost + "/_11080/getRetornoSql";
        
        var continuar = true;
        
        var dados = {
            'SQL' :sql_tratado
        };

        var type = "POST";

        function success(dados){

        	var campos_agrup	= [];
        	var campos_viziveis = [];
        	var cont = 0;

            function listar(item, index) {

				var campo   = $('.visivel-'+item+'-chec:checked' ).length;
				var agrup   = $('.visivel-'+item+'-agrupar:checked' ).length;
				var tagrup  = $('.visivel-'+item+'-tagrupar:checked' ).length;
				var cor     = $('.visivel-'+item+'-cor'       ).val();
				var desc    = $('.visivel-'+item+'-desc'      ).val();
				var clss    = $('.visivel-'+item+'-tipo'      ).val();
				var tota    = $('.visivel-'+item+'-total'     ).val();
				var casa    = $('.visivel-'+item+'-casas'     ).val();
				var ordem   = $('.visivel-'+item+'-casas'     ).parent().parent().data('ordem');
				var index   = $('.visivel-'+item+'-index'     ).val();
				var ttipo   = $('.visivel-'+item+'-total-tipo').val();
				var formula = $('.visivel-'+item+'-formula'   ).val();
				var prefix  = $('.visivel-'+item+'-prefix'    ).val();
				var sufix   = $('.visivel-'+item+'-sufix'     ).val();

				if(agrup == 1){
					campos_agrup.push([item,cont,cor,desc,clss,tota,casa,ordem,index,agrup,tagrup,ttipo,formula,prefix,sufix]);
				}

				if(campo == 1){
					campos_viziveis.push([item,cont,cor,desc,clss,tota,casa,ordem,index,agrup,tagrup,ttipo,formula,prefix,sufix]);
				}

				cont++;
				
			};

			dados.forEach(listar);

				var url_action = urlhost + "/_11080/getRetorno";

				var dados = {
		            'SQL' :sql_tratado
		        };

		        var type = "POST";

		        function success2(dados){
		        	//mostrarResultado(dados,campos_viziveis);

		        	campos_viziveis = campos_viziveis.sort(function(a, b){
		        		//console.log(a);
		        		//console.log(b);
		        		return a[7]-b[7]
		        	});


		        	mostrarResultado2(dados,campos_viziveis,campos_agrup);
			    }

		        function erro2(data){
		            showErro(data);
		        }

		        execAjax1(type,url_action,dados,success2,erro2,false);

	    }

        function erro(data){
            showErro(data);
        }

        execAjax1(type,url_action,dados,success,erro,false);  
    });

    $(document).on('click','.relatorio-tratar-sql', function(e) {

    	cad_tratarSql();
    });

    $(document).on('click','.gravar-rel-personalizado', function(e) {

    	preGravar();
    });

    $(document).on('click','.itemexcluir', function(e) {

    	var id = $(this).data('id');

    	addConfirme('Excluir registro','Deseja realmente excluir este relatório?',
    		[obtn_sim,obtn_nao],
            [
               {ret:1,func:function(){
               		var url_action = urlhost + "/_11080/Excluir";

					var dados = {
			            'id' :id
			        };

			        var type = "POST";

			        function success2(dados){
			        	showSuccess('Excluido com sucesso!');
			        	setTimeout(function(){
			        		 window.location.assign(urlhost + "/_11080")
			        	},1500);
				    }

			        function erro2(data){
			            showErro(data);
			        }

			        execAjax1(type,url_action,dados,success2,erro2,false);
               }},
               {ret:2,func:function(){

               }}
            ]     
        );
	});

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////

})(jQuery);

