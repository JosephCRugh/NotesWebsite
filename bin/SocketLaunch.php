<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Session\SessionProvider;
use SocketHandler\SocketHandlerController;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler;

    require dirname(__DIR__) . '/vendor/autoload.php';

    $memcached = new Memcached();

    $memcached->addServers(array(
      array('localhost',11211),
    ));

    $wsServer = new WsServer(new SocketHandlerController());

    $hd = new Handler\MemcachedSessionHandler($memcached);
    $session = new SessionProvider($wsServer, $hd);

    $httpServer = new HttpServer($session);
    $address = file('../WebSocketAddress', FILE_IGNORE_NEW_LINES);
    $server = IoServer::factory($httpServer, 8080, $address[0] === 'localhost' ? '127.0.0.1' : $address[0]);

    $server->run();

?>
