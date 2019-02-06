<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use SocketHandler\SocketHandlerController;

    require dirname(__DIR__) . '/vendor/autoload.php';

    $server = IoServer::factory(
       new HttpServer(
           new WsServer(
               new SocketHandlerController()
           )
       ),
       8080
   );

    $server->run();

?>
