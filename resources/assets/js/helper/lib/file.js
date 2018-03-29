/**
 * Script para arquivos.
 */

/**
 * Enviar arquivos para o banco. <br>
 * OBS.: Necessário passar o parâmetro 'url_pag' pois a página pode conter parâmetros.
 * Esse parâmetro é o identificador da página. Ex.: '_1000'
 * 
 * @param {object} input
 * @param {string} tabela
 */
function enviarArquivo(input, tabela) {
		
	var file		= jQuery(input);
	var fdata		= new FormData();
	var x			= jQuery('.CLArquivo').length - 1;
	var edit_desc	= jQuery(input).parent('.form-group').parent('.item-dinamico').children('.form-group').first().children('input[name="anexo_descricao"]');
	var edit_file	= file;
	
	edit_desc.val(edit_file.val());

	jQuery.each(jQuery(':file')[x].files, function(i, file) {
		fdata.append('file-0', file);
	});

	var vinc		= jQuery('input[name="_vinculo_id"]').val();

	var arquivo		= jQuery(':file')[x].files[0];
	var ftipo		= arquivo.type;
	var ftamanho	= arquivo.size;

	fdata.append('vinc', vinc);
	fdata.append('tabela', tabela);
	fdata.append('tamanho', ftamanho);
	fdata.append('tipo', ftipo);

	jQuery.ajax({
		type		: 'POST',
		url			: '/enviarArquivo',
		data		: fdata,
		cache		: false,
		contentType	: false,
		processData	: false,
		
		beforeSend	: function() { 
			jQuery('progress').attr({value:20, max:100}); 
		},
		
		success		: function(resposta) {
			
			//sucesso
			if (resposta['0'] === 'sucesso') {
				jQuery('progress').attr({value:100, max:100});
			}
			//erro
			else {
				
				jQuery('progress').attr({value:0, max:100});

				jQuery('.alert').removeClass('alert-success').addClass('alert-danger');
				//jQuery('body').scrollTop(0);

				var excecao;
				if ( resposta['1'] )
					excecao = resposta['1'].match(/exception 1 ... (.*) At trigger (.*)/i);

				//se for exceção de trigger
				if ( excecao ) {
					if(excecao['1'] !== 'Duplicado') {		//acontece devido ao problema de arquivo duplicado no banco
						jQuery('.alert .texto').html(excecao['1']).parent().fadeIn();
						jQuery('body').scrollTop(0);
					}
				}
				else {
					jQuery('.alert .texto').html(resposta['1']).parent().fadeIn();
					jQuery('body').scrollTop(0);
				}
			}
		},
		
		error		: function() { 
			jQuery('progress').attr({value:0, max:100}); jQuery(file).parent().next().hide(); 
		}
	});	
}

/**
 * Clique para fechar arquivo.
 */
function ativarCliqueFecharArquivo() {
	
	$('.esconder-arquivo').click(function(){

		$('.visualizar-arquivo').fadeOut();

		var arq_nome = $('.arquivo_nome_deletar').val();

		//$('.visualizar-arquivo').html(' ');

		//ajax
		var url		= '/deletararquivo',
			type	= 'POST',
			data	= {'arquivo': arq_nome}
		;
		
		execAjax1(type, url, data);
	});
}