<?php

namespace SocketHandler;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class UserLock {
  public $pageOwnerId;
  public $currentProject;
  public $hasWriteAccess;
  public $noteId = -1;
}

class SocketHandlerController implements MessageComponentInterface {

  protected $clients;
  protected $userLocks;

  public function __construct() {
      $this->clients = new \SplObjectStorage;
  }

  public function onOpen(ConnectionInterface $conn) {
      $this->clients->attach($conn);
      $this->userLocks[$conn->resourceId] = new UserLock();
      $sessionId = explode('=', $conn->httpRequest->getHeader('Cookie')[0])[1];

      $context = stream_context_create(array(
          'http'=>array(
              'method'=>'GET',
              'header'=>"inSessionId: $sessionId\r\n"
          )));
      $json = file_get_contents('http://localhost:80/NotesSite/backend/GetProjectSession.php', false, $context);
      $responseData = json_decode($json);

      $this->userLocks[$conn->resourceId]->pageOwnerId = $responseData->{'pageOwnerId'};
      $this->userLocks[$conn->resourceId]->sessId = $responseData->{'sess_id'};
      $this->userLocks[$conn->resourceId]->currentProject = $responseData->{'currentProject'};
      $this->userLocks[$conn->resourceId]->hasWriteAccess = $responseData->{'hasWriteAccess'};

  }

  public function onMessage(ConnectionInterface $from, $msg) {

    if (!$this->userLocks[$from->resourceId]->hasWriteAccess == "false") {
      return;
    }

    $lockInfo = json_decode($msg);

    if ($lockInfo->{'type'} == "lock") {

      // They already have a lock no reason to give them another one.
      if ($this->userLocks[$from->resourceId]->noteId != -1) {
        $from->send(json_encode(array(
          "noteId" => $lockInfo->{'noteId'},
          "type" => "response",
          "state" => "fail"
        )));
        return;
      }

      if ($this->alreadyHasLock($from, $lockInfo)) {
        return;
      }

      $this->lockNote($from, $lockInfo);

    } else if ($lockInfo->{'type'} == "unlock") {

      $this->unlockNote($from, $lockInfo);

    } else if ($lockInfo->{'type'} == "addnote") {

      $this->addNote($from, $lockInfo);

    } else if ($lockInfo->{'type'} == "deletenote") {

      $this->deleteNote($from, $lockInfo);

    }
  }

  private function alreadyHasLock(ConnectionInterface $from, $lockInfo) {
    foreach ($this->userLocks as $userLock) {
      if ($userLock->noteId == $lockInfo->{'noteId'}) {
        // Telling the client that the note already has a lock.
        $from->send(json_encode(array(
          "noteId" => $userLock->noteId,
          "editing" => $lockInfo->{'editing'},
          "type" => "response",
          "state" => "fail"
        )));
        return true;
      }
    }
    return false;
  }

  private function lockNote(ConnectionInterface $from, $lockInfo) {
    $this->userLocks[$from->resourceId]->noteId = $lockInfo->{'noteId'};
    $from->send(json_encode(array(
      "noteId" => $lockInfo->{'noteId'},
      "type" => "response",
      "state" => "success",
      "editing" => $lockInfo->{'editing'}
    )));

    // Send everyone else information telling them the note is locked.
    $this->allButClient($from, function($client, $lockInfo) {
      $client->send(json_encode(array(
        "noteId" => $lockInfo->{'noteId'},
        "type" => "clientlocked"
      )));
    }, $lockInfo);
  }

  private function unlockNote(ConnectionInterface $from, $lockInfo) {
    $this->userLocks[$from->resourceId]->noteId = -1;
    // Send everyone else information telling them the note is unlocked.
    $this->allButClient($from, function($client, $lockInfo) {
      $client->send(json_encode(array(
        "noteId" => $lockInfo->{'noteId'},
        "type" => "clientunlocked",
        "editing" => $lockInfo->{'editing'},
        "editval" => $lockInfo->{'editval'}
      )));
    }, $lockInfo);
  }

  private function addNote(ConnectionInterface $from, $lockInfo) {
    $this->allButClient($from, function($client, $lockInfo) {
      $client->send(json_encode(array(
        "noteId" => $lockInfo->{'noteId'},
        "type" => "addnote"
      )));
    }, $lockInfo);
  }

  private function deleteNote(ConnectionInterface $from, $lockInfo) {
    $this->allButClient($from, function($client, $lockInfo) {
      $client->send(json_encode(array(
        "noteId" => $lockInfo->{'noteId'},
        "type" => "deletenote"
      )));
    }, $lockInfo);
  }

  public function onClose(ConnectionInterface $conn) {
      $this->clients->detach($conn);

      $this->allButClient($conn, function($client, $noteId) {
        $client->send(json_encode(array(
          "noteId" => $noteId,
          "type" => "clientunlocked",
          "editing" => "nochange"
        )));
      }, $this->userLocks[$conn->resourceId]->noteId);

      unset($this->userLocks[$conn->resourceId]);
  }

  private function allButClient(ConnectionInterface $conn, $callback, $lockInfo) {
    foreach ($this->clients as $client) {

      if ($client == $conn) {
        continue;
      } else if (
        // Must be working on the same project.
        $this->userLocks[$conn->resourceId]->pageOwnerId != $this->userLocks[$client->resourceId]->pageOwnerId ||
        $this->userLocks[$conn->resourceId]->currentProject != $this->userLocks[$client->resourceId]->currentProject
      ) {
        continue;
      }

      $callback($client, $lockInfo);
    }
  }

  public function onError(ConnectionInterface $conn, \Exception $e) {
      echo "An error has occurred: {$e->getMessage()}\n";
      $conn->close();
  }
}

?>
