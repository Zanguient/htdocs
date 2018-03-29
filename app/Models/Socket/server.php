<?php

require 'C:/xampp/htdocs/bootstrap/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Models\Socket\_Socket;

	$server = IoServer::factory(
	    new HttpServer(
	    	new WsServer(
	            new _Socket()
	    )),
	    81
	);

	$server->run();
