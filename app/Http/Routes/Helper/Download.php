<?php
  
	/**
	* Rotas do objeto Download
	* @package Helper
	* @category Rotas
	*/
	
	Route::get('download/{tamanho}/{nome}', function($tamanho,$nome)
	{
		$dir    = env('APP_TEMP', '');
		$obj    = $dir.$nome;
		$tipo   = substr($obj, -3);

		switch ($tipo) {
			case "pdf": $tipo = "application/pdf";                  break;
			case "exe": $tipo = "application/octet-stream";         break;
			case "zip": $tipo = "application/zip";                  break;
			case "doc": $tipo = "application/msword";               break;
            case "xls": $tipo = "application/vnd.ms-excel";         break;
			case "ppt": $tipo = "application/vnd.ms-powerpoint";    break;
			case "gif": $tipo = "image/gif";                        break;
			case "png": $tipo = "image/png";                        break;
			case "jpg": $tipo = "image/jpg";                        break;
			case "mp3": $tipo = "audio/mpeg";                       break;
			case "php": $tipo = '';                                 break;
			case "csv": $tipo = '';                                 break;
			case "htm": $tipo = '';                                 break;
			case "html":$tipo = '';                                 break;
			default: 	$tipo = '';                                 break;
		}
		
		$headers = array(
            'Content-Type'	 => $tipo,
            'Content-Length' => $tamanho
		);
		
		return \Response::download($dir.$nome, $nome, $headers);
	});	