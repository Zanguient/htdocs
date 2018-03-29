/**
 * _12050 - RELATORIO DE PEDIDOS X FATURAMENTO X PRODUCAO
 */
(function($) {
 
 var cores = [
        'rgb(0, 0, 255)',
        'rgb(90, 170, 224)',
        'rgb(255, 185, 0)',
        'rgb(0, 128, 0)',
        'rgb(255, 0, 0)',
        'rgb(0, 255, 0)',
        'rgb(255, 0, 255)',
        'rgb(255, 140, 0)',
        'rgb(105, 105, 105)',
        'rgb(147, 112, 219)',
        'rgb(178, 34, 34)',
        'rgb(176, 48, 96)',
        'rgb(255, 105, 180)',
        'rgb(112, 128, 144)',
        'rgb(0, 0, 128)',
        'rgb(100, 149, 237)',
        'rgb(102, 205, 170)',
        'rgb(184, 134, 11)',
        'rgb(0, 206, 209)',
        'rgb(90, 200, 90)',
        'rgb(0, 100, 0)',
        'rgb(176, 48, 96)',
        'rgb(107, 142, 35)',
        'rgb(189, 183, 107)',
        'rgb(255, 215, 0)',
        'rgb(184, 134, 11)',
        'rgb(139, 69, 19)',
        'rgb(70, 130, 180)',
        'rgb(200, 100, 100)',
        'rgb(255, 105, 180)',
        'rgb(60, 179, 113)',
        'rgb(47, 79, 79)',
        
    ];  
    
var grafico_tipo_1 = '<select class="select-tipo-grafico1">'
                    +'<option value="LineChart">Linhas</option>'
                    +'<option value="AreaChart">Áreas</option>'
                    +'<option value="SteppedAreaChart">Andares</option>'
                    +'</select>';
            
var grafico_tipo_2 = '<select class="select-tipo-grafico2">'
                    +'<option value="PieChart">Pizza</option>'
                    +'<option value="Bar">Barras</option>'
                    +'</select>';

/**
* Ativar Datatable.
* @returns {void}
*/
function ativarDatatable2() {
   var table = $('.historico-corpo-tabela');

   //var data_table = $.extend({}, table_default);
   //    data_table.scrollY = '100%';
   //$(table).DataTable(data_table);
   
   //ativarRedimensionamento(table);
}
    
function grafico2(grafico){
     
    //*
    var chec = '';
    
    function getData(){

        var tabela  = $('.tabela-fat-familias');
        var linhas  = $(tabela).find('.linhas-fat-fam');
        var valores = [];
        valores.push(['Famílias', 'Faturamento',{ role: 'style' },{ role: 'certainty' }]);

        $.each( linhas, function( i, linha ) {
            var item   = $(linha).find('.descricao');
            var descricao   = $(item).data('descricao');
            var familia   = $(item).data('familia');
            
            var faturamento = parseFloat($(linha).find('.faturamento').data('faturamento'));
            var valor  = [descricao,faturamento,cores[i],familia];

            valores.push(valor);
        });
        
        return valores;

    }
    
    function getlines(){
        var tabela  = $('.tabela-fat-familias');
        var linhas  = $(tabela).find('.linhas-fat-fam');
        var valores = [];

        $.each( linhas, function( i, linha ) {
            var descricao   = $(linha).find('.descricao').data('descricao');
            valores.push(descricao);
        });
        
        return valores;

    }
    
    var dados_familias  = getData();
    var dados_linhas    = getlines();
    var dados_temp      = [];
    var corres_temp     = [];
    
    $.each( dados_linhas, function( i, linha ) {
        chec = chec + '<li><input type="checkbox" id="chk2'+i+'" class="relatorio-table val-grafico" value="'+i+'" checked><label class="label-grafico" for="chk2'+i+'">'+linha+'</label></li>';
    });
    
    $('#totalizador-grafico-filter3').html(chec);
    
    $.each( $('.val-grafico'), function( i, linha ) {
        var v = $(linha).val();
        $(linha).parent().css('border-color',cores[i]);    
    });
    
    $('.val-grafico').off('change');
    $(document).on('change','.val-grafico', function(e) {
        drawChart();
    });
            
    if(dados_familias.length > 1){
        
        google.charts.load('current', {packages: ['corechart', 'line', 'controls'], 'language': 'pt-br'});
        google.charts.setOnLoadCallback(drawChart);
        
        function drawChart() {

            var linhas  = $('.val-grafico');
            dados_temp  = [];
            corres_temp = [];
            
            var marcadas = 0;
            $.each( linhas, function( i, linha ) {
                var v = $(linha).val();
                if($(linha).prop('checked')){
                    marcadas = 1;
                }   
            });
            if(marcadas == 0){$(linhas).prop('checked',true);};
            
            dados_temp.push(dados_familias[0]);


            $.each( linhas, function( i, linha ) {
                var v = parseInt( $(linha).val());

                if($(linha).prop('checked')){
                    dados_temp.push(dados_familias[v+1]);
                    corres_temp.push(cores[v]);
                }
            });

            
            var area = {'width'	: '70%','height': '70%'};
            
            if(grafico == 'PieChart'){
                area = {'width'	: '85%','height': '85%'};
            }else{
                if(grafico == 'ColumnChart'){
                    area = {'width'	: '85%','height': '60%'};
                }else{
                    if(grafico == 'BarChart'){
                        area = {'width'	: '60%','height': '85%'};
                    }
                }
            }
            
            var pieChart = new google.visualization.ChartWrapper({
                'chartType': grafico,
                'containerId': 'totalizador-diario-grafico',
                'options': {
                    'is3D'  : true,
                    'legend': 'none',
                    'colors': corres_temp,
                    'chartArea': area 
                }
            });

            var dataPie = google.visualization.arrayToDataTable(dados_temp);
            
            var donutRangeSlider = new google.visualization.ControlWrapper({
              controlType : 'CategoryFilter',
              containerId : 'totalizador-diario-grafico-filter',
              options: {
                filterColumnLabel: 'Famílias',
                ui			: {
                    allowTyping	: false,
                    caption		: 'Filtrar por...',
                    label       : ''
                }
              }
            });
            
            
            var dashboard = new google.visualization.Dashboard(document.getElementById('totalizador-diario-grafico-dashboard'));
            dashboard.bind(donutRangeSlider, pieChart);
            dashboard.draw(dataPie);
            
            function selectHandler() {
                var selectedItem = dashboard.getSelection()[0];
                if (selectedItem) {
                  //var topping = data.getValue(selectedItem.row, 0);
                  var linha = selectedItem.row;
                  var dados = dados_temp[linha+1];
                  $('#modal-historico').css('display','block');
                  $('#modal-historico').css('background','rgba(0, 0, 0, 0.5)');
                  
                  $('#myModalLabel').html(dados[0]);
                  $('historico-corpo').data('id',dados[3]);
                  faturamentoDetalhado(dados[3]);    
                }
              }

            google.visualization.events.addListener(pieChart, 'select', selectHandler);    
            

        }
        
        $('.btn-screem-grafico2').off('click');
        $(document).on('click','.btn-screem-grafico2', function(e) {
            setTimeout(function(){
                drawChart();
            },500);
        });
            
    }
    //*/
}
    
function grafico1(grafico){
    var chec = '';
   
    function getData(){
        var tabela  = $('.table-talao-produzido');
        var linhas  = $(tabela).find('.linhas-prod');
        var valores = [];
        var retorno = [];
        
        $.each( linhas, function( i, linha ) {
            var colunas = $(linha).find('.coll-prod');
            
            valores = [];
            
            $.each( colunas, function( i, coluna ) {
                
                if(i == 0){
                    var valor = $(coluna).data('d')+'/'+$(coluna).data('m');
                }else{
                    var valor = parseFloat($(coluna).data('dados'));   
                }
                
                valores.push(valor);
            });
            
            retorno.push(valores);
        });
        
        return retorno;

    }
    
    function getlines(){
        var tabela  = $('.table-talao-produzido');
        var linhas  = $(tabela).find('.title-prod');
        var valores = [];
        
        $.each( linhas, function( i, linha ) {
            var valor = $(linha).html();
            
            if(valor.indexOf("</div>") < 1){
                valores.push(valor);
            }
        });
        
        return valores;

    }
    
    var dados_producao = getData();
    var dados_linhas   = getlines();
    
    if(dados_producao.length > 1){
        
        google.charts.load('current', {packages: ['corechart', 'line', 'controls'], 'language': 'pt-br'});
        google.charts.setOnLoadCallback(drawChart);
        
        function drawChart() {

            var dado = new google.visualization.DataTable();

            dado.addColumn('string', 'X');
            $.each( dados_linhas, function( i, linha ) {
                dado.addColumn('number', linha);
            });

            dado.addRows(dados_producao);
            
            var columns_table = new google.visualization.DataTable();
			columns_table.addColumn('number', 'colIndex');
			columns_table.addColumn('string', 'Filtro');
			
			var initState = {selectedValues: []};
			
            chec = '';
            for (var i = 1; i < dado.getNumberOfColumns(); i++) {
				columns_table.addRow([i, dado.getColumnLabel(i)]);
                chec = chec + '<li><input type="checkbox" id="chk1'+i+'" class="val-grafico2" value="'+i+'" checked><label class="label-grafico2" for="chk1'+i+'">'+dado.getColumnLabel(i)+'</label></li>';
			}
            
            for (var i = 1; i < dado.getNumberOfColumns(); i++) {
				initState.selectedValues.push(dado.getColumnLabel(i));
			}

            var	column_filter		= new google.visualization.ControlWrapper({		// Criando o filtro.
					
					controlType		: 'CategoryFilter',
					containerId		: 'totalizador-grafico-filter',
					dataTable		: columns_table,
					options			: {
						filterColumnLabel: 'Filtro',
						
						ui				: {
							allowTyping	: false,
							caption		: 'Filtrar por...',
							label		: ''
						}
					},
					state			: initState
					
				}),
						
				chart = new google.visualization.ChartWrapper({		// Criando o gráfico.
					
					chartType	: grafico,
					containerId	: 'totalizador-grafico',
					dataTable	: dado,
					options		: {
						
						allowHtml: true,
						
						chartArea: {
							width	: '80%',
                            height  : '80%'
						},
						
						crosshair: {
							trigger: 'both'
						},
						
						enableInteractivity: true,
						
						explorer: {
                            actions: ['dragToZoom', 'rightClickToReset'],
                            maxZoomIn: 0,
                            maxZoomOut:0
                        },
                        
                        fontSize:11,
						legend:'none',
                        pointSize: 3
					}
				}),
                cores_series = cores
			;
            
            function setChartView () {

				var linhas  = $('.val-grafico2');
                initState = {selectedValues: []};
                
                var marcadas = 0;
                $.each( linhas, function( i, linha ) {
                    var v = $(linha).val();
                    if($(linha).prop('checked')){
                        marcadas = 1;
                    }   
                });
                if(marcadas == 0){$(linhas).prop('checked',true);};
                
                $.each( linhas, function( i, linha ) {
                    var v = $(linha).val();
                    if($(linha).prop('checked')){
                        initState.selectedValues.push(dado.getColumnLabel(v-1));
                    }   
                });
                
                var state = column_filter.getState(),
					row,
					view = {
						columns: [0]
					}
				;
                
                $.each( linhas, function( i, linha ) {
                    var v = $(linha).val();
                    if($(linha).prop('checked')){
                        row = columns_table.getFilteredRows([{column: 1, value: state.selectedValues[v-1]}])[0];
                        view.columns.push(columns_table.getValue(row, 0));
                    }  
                });
                
                view.columns.sort(function (a, b) {
					return (a - b);
				});                

                chart.getOptions().series = [];
                $.each( linhas, function( i, linha ) {
                    var v = $(linha).val();
                    $(linha).parent().css('border-color',cores_series[v-1]);
                    
                    if($(linha).prop('checked')){
                        chart.getOptions().series.push({color:cores_series[v-1]});
                        
                        var coluna = chart.getOptions().series[chart.getOptions().series.length - 1];
                        coluna.pos = v;
                    }  
                    
                });
                
				chart.setView(view);
				chart.draw();
                
			}
            
            $('.btn-screem-grafico1').off('click');
            $(document).on('click','.btn-screem-grafico1', function(e) {
                setTimeout(function(){
                    setChartView();
                },500);
            });
            
            $('.val-grafico2').off('change');
            $(document).on('change','.val-grafico2', function(e) {
                setChartView();
            });
            
            $('.label-grafico2').off('mouseenter');
            $(document).on('mouseenter','.label-grafico2', function(e) {
                try {
                    var coll = $(this).parent().find('.val-grafico2').val();
                    var colunas = chart.getOptions().series;
                    var cont_desmarc = 0;
                    var linhas  = $('.val-grafico2');
                    
                    $.each( colunas, function( i, coluna ) {
                        var v = coluna.pos;

                        if(v == coll){
                            coluna.lineDashStyle = [10, 2];
                            chart.draw();
                        }  

                    });
                    
                }catch(err) {}
            });
            
            $('.label-grafico2').off('mouseout');
            $(document).on('mouseout','.label-grafico2', function(e) {
                try {
                    var coll = $(this).parent().find('.val-grafico2').val();
                    var colunas = chart.getOptions().series;
                    var cont_desmarc = 0;
                    var linhas  = $('.val-grafico2');
                    
                    $.each( colunas, function( i, coluna ) {
                        var v = coluna.pos;

                        if(v == coll){
                            coluna.lineDashStyle = [0, 0];
                            chart.draw();
                        }  

                    });
                    
                }catch(err) {}
            });

            $('#totalizador-grafico-filter2').html(chec);
			setChartView();
			column_filter.draw();
            
        }
    }
}

 /**
	 * Ativar redimensionamento das tabelas.
	 * @param {element} table
	 */
	function ativarRedimensionamento(table) {
		
		//Laço necessário para aplicar redimensionamento nas tabelas individualmente,
		//evitando que ao redimensionar uma tabela, as outras também se redimensionem.
		$(table).each(function() {	
			
			var datatable_scroll		= $(this).closest('.dataTables_scroll');
			var datatable_scrollbody	= $(datatable_scroll).find('.dataTables_scrollBody');
			var instancia_resizable		= $(datatable_scroll).resizable('instance');

			//verificar se 'resizable' já está aplicado na tabela antes de destruí-lo.
			if( typeof instancia_resizable !== 'undefined' ) {

				$(datatable_scroll).resizable('destroy');
				$(datatable_scrollbody).resizable('destroy');

			}

			$(datatable_scroll)
				.resizable({
					handles		: 's',
					alsoResize	: datatable_scrollbody,
					minHeight	: 65,
					stop		: function( event, ui ) {
						datatable_scrollbody.height(ui.size.height - 35);	//fix para remover espaçamento do puxador ao redimensionar várias vezes.
					}
				})
			;

			$(datatable_scrollbody)
				.resizable({
					handles		: 's',
					minHeight	: 33	//precisa ser aplicado no CSS também, pois esta div é redimensionada pelo 'datatable_scroll'
				})
			;
			
			//Aumentar tabela com duplo clique no puxador
			$(datatable_scroll)
				.children('.ui-resizable-s')
				.off('dblclick')
				.on('dblclick', function() {
					
					var tbody_height	= $(table).find('tbody').height(),
						window_height	= $(window).height()
					;

					if ((tbody_height * 0.65) > 70) {
						
						tbody_height = '70vh';
						
						//Posicionar scroll.
						//posição da tabela - altura do cabeçalho - altura da barra de ações - 50
						$(document)
							.scrollTop( datatable_scroll.offset().top - $('nav.navbar').outerHeight() - $('ul.acoes').outerHeight() - 50 )
						;
						
					}
					else {
						tbody_height = tbody_height + 35;
					}
					
					$(datatable_scroll)
						.height( tbody_height )
					;
					$(datatable_scrollbody)
						.height( datatable_scroll.height() - 35 )
					;
	
				})
			;
			
		});
	}
	
	/**
	 * Ativar Datatable.
     * @param {element} table
     * @returns {void}
     */
	function ativarDatatable(table) {
		
        var tamanho = $(window).width();
        var table = table || $('.tabele-datatable');
        var data_table = $.extend({}, table_default);

        if(tamanho > 500){   	
			data_table.scrollY = '100%';
        }else{
            data_table.scrollY = '60vh';    
        }

		//$(table).DataTable(data_table);
		
		//ativarRedimensionamento(table);
		
		delete data_table;
		delete table;
	}
	
	/**
	 * Manobra para corrigir problema de alinhamento do DataTable.
	 */
	function fixDatatable() {
		
		$('#talao-produzir-tab')
			.click(function() {
				
				$('.table-talao-produzir')
					.trigger('resize')
				;
				
			})
		;
		
		$('#talao-produzido-tab')
			.click(function() {
				
				$('.table-talao-produzido')
					.trigger('resize')
				;
				
			})
		;
		
	}

    function getDetalhamento(url,dia_ini,dia_fim,title,fam,qtd,qtd2,filtro){
        
        sql_filtro = filtro;

        var estabelecimento     = $('._input_estab').val();
        var familias            = fam;
        var periodo_pedidod     = $('.checkbox-perildod').val();
        var periodo_pedidop     = $('.checkbox-perildop').val();
        
        var perfil_grupo = 0;
        var periodo_pedido = 0;
        
        if(periodo_pedidod == 1){
            periodo_pedido = 'd';
        }else{
            if(periodo_pedidop == 1){
                periodo_pedido = 'p';
            }
        }
        
        var url_action = urlhost + "/_12050/" + url;
        
        var continuar = true;
        
        if((typeof estabelecimento  == 'undefined') || (estabelecimento == '')){ showAlert('Selecione um Estabelecimento');   continuar = false;}
        if((typeof familias         == 'undefined') || (familias        == '')){ showAlert('Selecione uma Família');          continuar = false;}
        if((typeof periodo_pedido   == 'undefined') || (periodo_pedido  == '')){ showAlert('Selecione o período de pedidos'); continuar = false;}
        
        if(qtd == 0){
            qtd = 100;
        }else{
            qtd = parseInt(qtd);
        }

        if(qtd2 == 0){
            qtd2 = 100;
        }else{
            qtd2 = parseInt(qtd2);
        }

        if(continuar){
            var dados = {
                'estabelecimento'   :estabelecimento,
                'familias'          :familias,
                'periodo_inicial'   :dia_ini,
                'periodo_final'     :dia_fim,
                'perfil_grupo'      :perfil_grupo,
                'periodo_pedido'    :periodo_pedido,
                'val_base'          :qtd,
                'val_prod'          :qtd2,
                'sql_filtro'        :filtro,
                'titulo'            :title,
                'tipo'              :url
            };

            var type = "POST";

            function success(dados){

                $('#modal-historico').css('display','block');
                $('#modal-historico').css('background','rgba(0, 0, 0, 0.5)');
                $('.historico-corpo').html(dados);
                $('#myModalLabel').html(title);
                $('._qtd_base').val(qtd);

                if('defeitoDia' != url){
                    console.log(dados);
                    ativarDatatable2();
                    bootstrapInit();
                }
            }

            function erro(data){
                showErro(data);
            }

            var exp = ($('#relatorio-filtro').attr('aria-expanded') == 'true');

            if(exp){
                $('#filtrar-toggle').trigger('click'); 
            }

            execAjax1(type,url_action,dados,success,erro,false);
        }
    }


    function imprimirRelatorio(){
        
        $('.tabela-relatorio').html('');
        
        var estabelecimento     = $('._input_estab').val();
        var familias            = $('._familia_id').val();
        var periodo_inicial     = $('.data-ini').val();
        var periodo_final       = $('.data-fim').val();
        var periodo_pedidod     = $('.checkbox-perildod').val();
        var periodo_pedidop     = $('.checkbox-perildop').val();
        
        var perfil_grupo = 0;
        var periodo_pedido = 0;
        
        if(periodo_pedidod == 1){
            periodo_pedido = 'd';
        }else{
            if(periodo_pedidop == 1){
                periodo_pedido = 'p';
            }
        }
        
        var url_action = urlhost + "/_12050/relatorio";
        
        var continuar = true;
        
        if((typeof estabelecimento  == 'undefined') || (estabelecimento == '')){ showAlert('Selecione um Estabelecimento');   continuar = false;}
        if((typeof familias         == 'undefined') || (familias        == '')){ showAlert('Selecione uma Família');          continuar = false;}
        if((typeof periodo_inicial  == 'undefined') || (periodo_inicial == '')){ showAlert('Selecione o período inicial');    continuar = false;}
        if((typeof periodo_final    == 'undefined') || (periodo_final   == '')){ showAlert('Selecione o período final');      continuar = false;}
        if((typeof periodo_pedido   == 'undefined') || (periodo_pedido  == '')){ showAlert('Selecione o período de pedidos'); continuar = false;}
        
        if(continuar){
            var dados = {
                'estabelecimento'   :estabelecimento,
                'familias'          :familias,
                'periodo_inicial'   :periodo_inicial,
                'periodo_final'     :periodo_final,
                'perfil_grupo'      :perfil_grupo,
                'periodo_pedido'    :periodo_pedido
            };

            var type = "POST";

            function success(dados){
                //printPdf(dados);

                var res = dados.replace("table-talao-produzido", "");

                $('.tabela-relatorio-titulo').html(res);
                $('.tabela-relatorio-titulo').find('.table-talao-produzido').remove();
                $('.tabela-relatorio-titulo').find('.grafico-conteiner').remove();
                $('.tabela-relatorio-titulo').find('.grafico3-conteiner').remove();
                $('.tabela-relatorio-titulo').find('.modal').remove();
                $('.tabela-relatorio-titulo').find('.table-ec').attr('id', 'tabela-1');

                

                $('.tabela-relatorio').html(dados);
                $('.tabela-relatorio').find('.table-ec').attr('id', 'tabela-2');

                ativarDatatable();
                fixDatatable();
                grafico1('LineChart');
                grafico2('PieChart');

                var ngApp = angular.element('#AppCtrl').scope();
                ngApp.vm.Acao.Compile();

            }

            function erro(data){
                showErro(data);
            }

            var exp = ($('#relatorio-filtro').attr('aria-expanded') == 'true');


            if(exp){
                $('#filtrar-toggle').trigger('click'); 
            }

            execAjax1(type,url_action,dados,success,erro,false);
        }
    }
    
    function faturamentoDetalhado(familia){
        
        $('.historico-corpo').html('');
        
        var estabelecimento     = $('._input_estab').val();
        var familias            = familia;
        var periodo_inicial     = $('.data-ini').val();
        var periodo_final       = $('.data-fim').val();
        var periodo_pedidod     = $('.checkbox-perildod').val();
        var periodo_pedidop     = $('.checkbox-perildop').val();
        
        var perfil_grupo = 0;
        var periodo_pedido = 0;
        
        if(periodo_pedidod == 1){
            periodo_pedido = 'd';
        }else{
            if(periodo_pedidop == 1){
                periodo_pedido = 'p';
            }
        }
        
        var url_action = urlhost + "/_12050/detalharFamilia";
        
        var continuar = true;
        
        if((typeof estabelecimento  == 'undefined') || (estabelecimento == '')){ showAlert('Selecione um Estabelecimento');   continuar = false;}
        if((typeof familias         == 'undefined') || (familias        == '')){ showAlert('Selecione uma Família');          continuar = false;}
        if((typeof periodo_inicial  == 'undefined') || (periodo_inicial == '')){ showAlert('Selecione o período inicial');    continuar = false;}
        if((typeof periodo_final    == 'undefined') || (periodo_final   == '')){ showAlert('Selecione o período final');      continuar = false;}
        if((typeof periodo_pedido   == 'undefined') || (periodo_pedido  == '')){ showAlert('Selecione o período de pedidos'); continuar = false;}
        
        if(continuar){
            var dados = {
                'estabelecimento'   :estabelecimento,
                'familias'          :familias,
                'periodo_inicial'   :periodo_inicial,
                'periodo_final'     :periodo_final,
                'perfil_grupo'      :perfil_grupo,
                'periodo_pedido'    :periodo_pedido
            };

            var type = "POST";

            function success(dados){
                $('.historico-corpo').html(dados);
                ativarDatatable2();
                bootstrapInit();
            }

            function erro(data){
                showErro(data);
            }

            var exp = ($('#relatorio-filtro').attr('aria-expanded') == 'true');


            if(exp){
                $('#filtrar-toggle').trigger('click'); 
            }

            execAjax1(type,url_action,dados,success,erro,false);
        }
    }

    function faturamentoDetalhado2(familia){
        
        $('.historico-corpo').html('');
        
        var estabelecimento     = $('._input_estab').val();
        var familias            = familia;
        var periodo_inicial     = $('.data-ini').val();
        var periodo_final       = $('.data-fim').val();
        var periodo_pedidod     = $('.checkbox-perildod').val();
        var periodo_pedidop     = $('.checkbox-perildop').val();
        
        var perfil_grupo = 0;
        var periodo_pedido = 0;
        
        if(periodo_pedidod == 1){
            periodo_pedido = 'd';
        }else{
            if(periodo_pedidop == 1){
                periodo_pedido = 'p';
            }
        }
        
        var url_action = urlhost + "/_12050/detalharFamilia2";
        
        var continuar = true;
        
        if((typeof estabelecimento  == 'undefined') || (estabelecimento == '')){ showAlert('Selecione um Estabelecimento');   continuar = false;}
        if((typeof familias         == 'undefined') || (familias        == '')){ showAlert('Selecione uma Família');          continuar = false;}
        if((typeof periodo_inicial  == 'undefined') || (periodo_inicial == '')){ showAlert('Selecione o período inicial');    continuar = false;}
        if((typeof periodo_final    == 'undefined') || (periodo_final   == '')){ showAlert('Selecione o período final');      continuar = false;}
        if((typeof periodo_pedido   == 'undefined') || (periodo_pedido  == '')){ showAlert('Selecione o período de pedidos'); continuar = false;}
        
        if(continuar){
            var dados = {
                'estabelecimento'   :estabelecimento,
                'familias'          :familias,
                'periodo_inicial'   :periodo_inicial,
                'periodo_final'     :periodo_final,
                'perfil_grupo'      :perfil_grupo,
                'periodo_pedido'    :periodo_pedido
            };

            var type = "POST";

            function success(dados){
                $('.historico-corpo').html(dados);
                ativarDatatable2();
                bootstrapInit();
            }

            function erro(data){
                showErro(data);
            }

            var exp = ($('#relatorio-filtro').attr('aria-expanded') == 'true');


            if(exp){
                $('#filtrar-toggle').trigger('click'); 
            }

            execAjax1(type,url_action,dados,success,erro,false);
        }
    }
    
    $(document).on('click','.checkbox-perfil', function(e) {
        $('.checkbox-perfil').removeAttr('checked');
        $('.checkbox-perfil').val(0);
        $(this).prop('checked',true);
        $(this).val(1);
    });
    
    $(document).on('click','.checkbox-perildo', function(e) {
        $('.checkbox-perildo').removeAttr('checked');
        $('.checkbox-perildo').val(0);
        $(this).prop('checked',true);
        $(this).val(1);
    });
    
    $(document).on('click','.checkbox-opc', function(e) {
        var ch = $(this).is(":checked");
        if(ch){
            $(this).val(1);
        }else{
            $(this).val(0);
        }
    });
    
    $(document).on('click','#btn-imprimir', function(e) {
        imprimirRelatorio();
    });
    
    $(document).on('change','.select-tipo-grafico1', function(e) {
        var graf = $(this).val();
        grafico1(graf);
    });
    
    $(document).on('change','.select-tipo-grafico2', function(e) {
        var graf = $(this).val();
        grafico2(graf);
    });
    
    $(document).on('click','.btn-voltar', function(e) {
        $('#modal-historico').css('display','none');
    });

    $(document).on('click','.detalhar-result', function(e) {
        var dia_ini = $(this).data('data');
        var dia_fim = $(this).data('data');
        var vlr = $(this).data('dados');
        var vlr2 = 0
        vlr2 = $(this).data('dados2');

        if(typeof vlr2 == 'undefined'){
            var tit = $(this).data('titulo') + ' - ' + dia_ini.substr(0, 10).split('-').reverse().join('/') + ' - (' + vlr + ')';    
        }else{
            var tit = $(this).data('titulo') + ' - ' + dia_ini.substr(0, 10).split('-').reverse().join('/') + ' - (Def.:' + vlr + ')'+ ' - (Prod.:'+vlr2+')';
        }

        
        var url = $(this).data('tipo');
        var fam = $(this).data('familia');
        //var fam = $('._familia_id').val();

        getDetalhamento(url,dia_ini,dia_fim,tit,fam,vlr,vlr2,[['1','1']]);
    });

    $(document).on('click','.detalhar-result2', function(e) {
        var dia_ini = $(this).data('data');
        var dia_fim = $(this).data('data');
        var vlr = $(this).data('dados');
        var vlr2 = 0
        vlr2 = $(this).data('dados2');
        var tit = $(this).data('titulo') + ' - ' + dia_ini.substr(0, 10).split('-').reverse().join('/') + ' - (' + vlr + ')' ;
        var url = $(this).data('tipo');
        var fam = $(this).data('familia');

        getDetalhamento(url,dia_ini,dia_fim,tit,fam,vlr,vlr2,[['1','1']]);
    });

    $(document).on('click','.detalhar-result3', function(e) {
        var dia_ini = $('.data-ini').val();
        var dia_fim = $('.data-fim').val();
        var vlr = $(this).data('dados');
        var vlr2 = 0
        vlr2 = $(this).data('dados2');
        var tit = $(this).data('titulo') + ' - ' + dia_ini.substr(0, 10).split('-').reverse().join('/') +' a '+ dia_fim.substr(0, 10).split('-').reverse().join('/') + ' - (' + vlr + ')';
        var url = $(this).data('tipo');
        var fam = $(this).data('familia');

        getDetalhamento(url,dia_ini,dia_fim,tit,fam,vlr,vlr2,[['1','1']]);
    });

    $(document).on('click','.tab-detalhamento', function(e) {
        $('.historico-corpo-tabela').trigger('resize');
    }); 


    $(document).on('click','.detalhar-famila-tabela', function(e) {

        var titulo  = $(this).data('titulo');
        var familia = $(this).data('familia');

        $('#modal-historico').css('display','block');
        $('#modal-historico').css('background','rgba(0, 0, 0, 0.5)');

        $('#myModalLabel').html(titulo);
        $('historico-corpo').data('id',familia);
        faturamentoDetalhado(familia);
        
    });

    $(document).on('click','.detalhar-famila-tabela2', function(e) {

        var titulo  = $(this).data('titulo');
        var familia = $(this).data('familia');

        $('#modal-historico').css('display','block');
        $('#modal-historico').css('background','rgba(0, 0, 0, 0.5)');

        $('#myModalLabel').html(titulo);
        $('historico-corpo').data('id',familia);
        faturamentoDetalhado2(familia);
        
    });

    var sql_filtro = [];

    $(document).on('dblclick','.add-filtro', function(e) {
        var campo = $(this).data('campo-sql');
        var valor = $(this).data('valor-sql');
        
        var add = 0;

        function tratar(item, index, arr) {
            var c = item[0];
            var v = item[1];

            if(campo == c){add = 1;}
            if((campo == c) && (valor == v)){add = 2;}

            if(add == 1){
                arr[index][2] = 'or';
            }
        }

        sql_filtro.forEach(tratar);

        if(add == 0){
            sql_filtro.push([campo,valor,'and']);
        }else{
            if(add == 1){
                sql_filtro.push([campo,valor,'or']);
            }   
        }

        var html = " ";

        function montar(item, index, arr) {

            if(item[0] != 1){
                html = html + "<div class='filtro-item'>";
                html = html + "<div class='filtro-campo'>"+item[0]+":</div>";
                html = html + "<div class='filtro-valor'>"+item[1]+"</div>";
                html = html + "<div class='filtro-fechar' data-campo='"+item[0]+"' data-valor='"+item[1]+"'>x</div>";
                html = html + "</div>";
            }
            
        }

        sql_filtro.forEach(montar);

        html = html + "<button class='filtro-btn btn btn-sm btn-primary btn-filtrar-lista'>";
        html = html +     "<span class='glyphicon glyphicon-filter'></span>";
        html = html +     "Filtrar";
        html = html + "</button>";

        $('.conteiner-filtro').html(html);

    });

    $(document).on('click','.filtro-fechar', function(e) {
        $(this).parent().css('display','none');

        var campo = $(this).data('campo');
        var valor = $(this).data('valor');

        function tratar(item, index, arr) {
            var c = item[0];
            var v = item[1];

            if((campo == c) && (valor == v)){
                arr.splice(index, 1);
            }
        }

        sql_filtro.forEach(tratar);
    
    });

    $(document).on('click','.btn-filtrar-lista', function(e) {

        var dia_ini = $('._data_data1').val();
        var dia_fim = $('._data_data2').val();
        var vlr     = $('._data_dados').val();
        var vlr2    = $('._data_dados2').val();
        var tit     = $('._data_titulo').val()
        var url     = $('._data_tipo').val();
        var fam     = $('._data_familia').val();

        getDetalhamento(url,dia_ini,dia_fim,tit,fam,vlr,vlr2,sql_filtro);
    });

    document.addEventListener('scroll', function (event) {
        
        if (event.target.id === 'tabela-2') {
            $('#tabela-1').scrollLeft(event.target.scrollLeft);
        }

    }, true /*Capture event*/);

    

})(jQuery);


//# sourceMappingURL=_12050.js.map
