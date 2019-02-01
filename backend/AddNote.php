<?php

  require 'EnforceSession.php';

  $title = $_POST['title'];
  $posX = $_POST['posX'];
  $posY = $_POST['posY'];

  if (!isset($title) || empty($title)) {
    return;
  }

  if (strlen($title) > 20) {
    return;
  }

  require 'EnforceSqliteConnection.php';

  $largestNoteIdStmt = $db->Prepare("SELECT MAX(id) FROM notes WHERE owner_id=?");
  // TODO: If the page is public then the server still needs to check if the user
  // is allowed to edit.
  $largestNoteIdStmt->bindValue(1, $_SESSION['pageOwnerId'], SQLITE3_INTEGER);

  $largestNoteIdResult = $largestNoteIdStmt->execute();
  $maxId = $largestNoteIdResult->fetchArray()[0];

  $insertStr = "INSERT INTO notes (id, owner_id, title, content, container_id, offset_x, offset_y) VALUES (?, ?, ?, ?, ?, ?, ?)";

  // Checking to see if the user currently has any notes.
  if (isset($maxId)) {

    $newNoteStmt = $db->prepare($insertStr);
    $newNoteStmt->bindValue(1, $maxId + 1, SQLITE3_INTEGER);

  } else {

    $newNoteStmt = $db->prepare($insertStr);
    $newNoteStmt->bindValue(1, 0, SQLITE3_INTEGER);

  }

  $newNoteStmt->bindValue(2, intval($_SESSION['sess_id']), SQLITE3_INTEGER);
  $newNoteStmt->bindValue(3, $title, SQLITE3_TEXT);
  $newNoteStmt->bindValue(4, "", SQLITE3_TEXT);
  $newNoteStmt->bindValue(5, -1, SQLITE3_INTEGER);
  $newNoteStmt->bindValue(6, $posX, SQLITE3_FLOAT);
  $newNoteStmt->bindValue(7, $posY, SQLITE3_FLOAT);

  $newNoteStmt->execute();

  $db->close();

?>
