/**
 * _11080 - Criar Relatorio
 */

;(function(angular) {

    var Ctrl = function($scope,$ajax,$timeout) {

        var vm     = this;
        vm.FILTRO_REL = '';
        vm.DADOS      = [];

        function init(){
			var ds = {
					FILTRO : ''
				};

			$ajax.post('/_11080/Consultar',ds)
				.then(function(response) {
					vm.DADOS = response;                
				}
			);
        }

        vm.openRel = function(link, id){
        	window.location.href = link + '/' + id;        	
        }
		
        init();        
    };

    Ctrl.$inject = [
		'$scope',
		'$ajax',
		'$timeout'
	];
 
    angular
    .module    ('app' , ['angular.filter','vs-repeat','gc-ajax','gc-form','gc-find','gc-transform'])
    .controller('Ctrl', Ctrl);
        
})(angular);

(function($) {
  
    function cad_getSQL(){
    	var sql = $('.relatorio-sql').val()  + '';
		var regex = new RegExp('\\n', 'g');
		sql = sql.replace(regex, ' ');

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

        var url_action = urlhost + "/_11080/cad_getRetornoSql";
        
        var continuar = true;

        console.log(sql);
        
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

        var url_action = urlhost + "/_11080/cad_getRetorno";
        
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

        var url_action = urlhost + "/_11080/cad_getRelatorios";
        
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


   // $(document).on('click','.btn-filtro-obj', function(e) {
    	//var filtro = $("input[name='filtro_obj']").val();
        //cad_getRelatorios(filtro);
    //});

    //$(document).on('keypress','input[name=\'filtro_obj\']', function(e) {
    	//if(e.which == 13) {
	    	//var filtro = $("input[name='filtro_obj']").val();
	        //cad_getRelatorios(filtro);
	    //}
    //});


    //$(document).on('keyup','input[name=\'filtro_obj\']', function(e) {
    	//var filtro = $("input[name='filtro_obj']").val();

    	//if(filtro == '') {
	        //cad_getRelatorios(filtro);
	    //}
    //});
    
    
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

    		var sql = $('.relatorio-sql').val()  + ' ';
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

    	var sql = cad_getSQL();

    	var variaveis2 = cad_acharVariavel(sql);

		var variaveis = variaveis2.filter(function(este, i) {
		    return variaveis2.indexOf(este) == i;
		});

    	$('.imputs-relatorio').html('');

    	var htm_imputs  = '';

    	htm_imputs += '<input type="hidden" name="relatorio-SQL" clas="relatorio-SQL"value="'+sql+'">';
    	htm_imputs += '<input type="hidden" name="user-relatorio"  class="user-relatorio"  value="USER TESTE">';

    	var nome 		= $('.relatorio-nome').val();
    	var filtro 		= $('.relatorio-filtro').val();
    	var tipo 		= $('.relatorio-tipo').val();
    	var grupo 		= $('.relatorio-grupo').val();
    	var template 	= $('.relatorio-template').val();
    	var versao 		= $('.relatorio-versao').val();
    	var fonteweb 	= $('.relatorio-fonteweb').val();
    	var fonteexp 	= $('.relatorio-fonteexp').val();
    	var totalizador = $('.relatorio-totalizador').val();
    	var zebrado 	= $('.relatorio-zebrado').val();
    	var paisagem 	= $('.relatorio-paisagem').val();

    	htm_imputs += '<input type="hidden" name="titulo-relatorio"        class="titulo-relatorio"        value="TELATORIO TESTE">';
        htm_imputs += '<input type="hidden" name="relatorio-ID"            class="relatorio-ID"            value="0">';
        htm_imputs += '<input type="hidden" name="relatorio-NOME"          class="relatorio-NOME"          value="'+nome+'">';
        htm_imputs += '<input type="hidden" name="relatorio-DESCRICAO"     class="relatorio-DESCRICAO"     value="TESTE">';
        htm_imputs += '<input type="hidden" name="relatorio-TIPO"          class="relatorio-TIPO"          value="'+tipo+'">';
        htm_imputs += '<input type="hidden" name="relatorio-TEMPLATE_ID "  class="relatorio-TEMPLATE_ID "  value="'+template+'">';
        htm_imputs += '<input type="hidden" name="relatorio-STATUS "       class="relatorio-STATUS "       value="1">';

        htm_imputs += '<input type="hidden" name="filtro-relatorio"        class="filtro-relatorio"        value="'+filtro+'">';
        htm_imputs += '<input type="hidden" name="relatorio-relatorio_id"  class="relatorio-relatorio_id"  value="0">';
        htm_imputs += '<input type="hidden" name="relatorio-filtro"        class="relatorio-filtro"        value="'+filtro+'">';
        htm_imputs += '<input type="hidden" name="relatorio-agrupamento"   class="relatorio-agrupamento"   value="0">';
        htm_imputs += '<input type="hidden" name="relatorio-agrupar "      class="relatorio-agrupar "      value="0">';
        htm_imputs += '<input type="hidden" name="relatorio-zebrado "      class="relatorio-zebrado "      value="'+zebrado+'">';
        htm_imputs += '<input type="hidden" name="relatorio-versao "       class="relatorio-versao "       value="'+versao+'">';
        htm_imputs += '<input type="hidden" name="relatorio-cor "          class="relatorio-cor "          value="0">';
        htm_imputs += '<input type="hidden" name="relatorio-fonte "        class="relatorio-fonte "        value="'+fonteweb+'">';
        htm_imputs += '<input type="hidden" name="relatorio-fonteHTML "    class="relatorio-fonteHTML "    value="'+fonteweb+'">';
        htm_imputs += '<input type="hidden" name="relatorio-totalizador "  class="relatorio-totalizador "  value="'+totalizador+'">';
        htm_imputs += '<input type="hidden" name="relatorio-paisagem "     class="relatorio-paisagem "     value="'+paisagem+'">';

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

			htm_imputs+='<input type="hidden"'+
				        '	name="relatorio-IMPUTS"'+
				        '	class="relatorio-IMPUTS relatorio-IMPUTS-VALOR-'+classe+'"'+
				        '	data-PARAMETRO="'+classe+'"'+
				        '	data-DESCRICAO="'+desc+'"'+
				        '	data-TIPO="'+chec+'"'+
				        '>';

		});

    	var url_action = urlhost + "/_11080/getRetornoSql";
        
        var continuar = true;

        console.log(sql_tratado);
        
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

            	htm_imputs+='<input type="hidden"'+
						    '    name                ="relatorio-CAMPOS"'+
						    '    class               ="relatorio-CAMPOS relatorio-CAMPOS-'+item+'"'+
						    '    data-PERCENTUAL     ="'+0+'"'+
						    '    data-DESCRICAO      ="'+desc+'"'+
						    '    data-CLASSE         ="'+clss+'"'+
						    '    data-CAMPO          ="'+campo+'"'+
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
					        '>';

				if(agrup == 1){
					campos_agrup.push([item,cont,cor,desc,clss,tota,casa,ordem,index,agrup,tagrup,ttipo,formula,prefix,sufix]);
				}

				if(campo == 1){
					campos_viziveis.push([item,cont,cor,desc,clss,tota,casa,ordem,index,agrup,tagrup,ttipo,formula,prefix,sufix]);
				}

				cont++;
				
			};

			dados.forEach(listar);

			$('.imputs-relatorio').html('');

			var url_action = urlhost + "/_11080/getRetorno";

			console.log(sql_tratado);

			var dados = {
	            'SQL' :sql_tratado
	        };

	        var type = "POST";

	        function success2(dados){
	        	campos_viziveis = campos_viziveis.sort(function(a, b){
	        		return a[7]-b[7]
	        	});

	        	mostrarResultado(dados,campos_viziveis,campos_agrup);
		    }

	        function erro2(data){
	            showErro(data);
	        }

	        $('.imputs-relatorio').html(htm_imputs);

	        execAjax1(type,url_action,dados,success2,erro2,false);

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

    

})(jQuery);

