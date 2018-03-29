<?php
  
	/**
	* Rotas do objeto Arquivo
	* @package Helper
	* @category Rotas
	*/
		
    Route::get('arquivos/anuncio', function(){
        $img = '//Srv-Files/Arquivos/Arquivos/Ini/anuncio.jpg';
        $getInfo = getimagesize($img);
        header('Content-type: ' . $getInfo['mime']);
        readfile($img);
    });
    

    Route::any('api/svn/generate', 'Helper\ArquivoController@svnGenerate');    

    Route::post('/enviarArquivo', 'Helper\ArquivoController@enviarArquivo');
    Route::post('/excluirArquivoTmpPorUsuario', 'Helper\ArquivoController@excluirArquivoTmpPorUsuario'); 	// via Angular
    Route::post('/downloadArquivo', 'Helper\ArquivoController@downloadArquivo');