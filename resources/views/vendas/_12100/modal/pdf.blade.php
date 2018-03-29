<?php
ini_set('display_errors', 'Off');
//include_once 'nfephp/bootstrap.php';

use NFePHP\Extras\Danfe;
use NFePHP\Common\Files\FilesFolders;

// 'C:\xampp\htdocs\public\assets\temp\files\\'

$xml = $CAMINHO . $NOME;

$docxml = FilesFolders::readFile($xml);

$danfe = new Danfe($docxml, 'P', 'A4', '..\public\assets\images\logo.jpg', 'I', '');
$id = $danfe->montaDANFE() . '-nfe';
$teste = $danfe->printDANFE($CAMINHO . $id.'.pdf', 'F');
?> 

<input type="hidden" class="_caminhoPDF" value="{{$DIR}}{{$id}}.pdf">
<object data="{{$DIR}}{{$id}}.pdf" type="application/pdf"></object>