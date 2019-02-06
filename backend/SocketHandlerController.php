<?php

namespace SocketHandler;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class SocketHandlerController implements MessageComponentInterface {

  protected $clients;

  public function __construct() {
      $this->clients = new \SplObjectStorage;
      echo "Started Server";
  }

  public function onOpen(ConnectionInterface $conn) {
      $this->clients->attach($conn);
      echo "New connection! ({$conn->resourceId})\n";
  }

  public function onMessage(ConnectionInterface $from, $msg) {

  }

  public function onClose(ConnectionInterface $conn) {
      $this->clients->detach($conn);
      echo "Connection {$conn->resourceId} has disconnected\n";
  }

  public function onError(ConnectionInterface $conn, \Exception $e) {
      echo "An error has occurred: {$e->getMessage()}\n";
      $conn->close();
  }
}

?>
