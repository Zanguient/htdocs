<?php
	$path = base_path(env('PATH_WKHTMLTOPDF'));

return array(

    'pdf' => array(
        'enabled' => true,
        //'binary'  => '/usr/local/bin/wkhtmltopdf', // PADRAO DE INSTALAÇÃO
		'binary'  => $path,  
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        //'binary'  => '/usr/local/bin/wkhtmltoimage',  // PADRAO DE INSTALAÇÃO
		'binary' =>  $path,
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);
