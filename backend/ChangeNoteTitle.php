<?php

  require 'EnforceSession.php';

  $noteId = $_POST['noteId'];
  $title = $_POST['title'];

  if (!isset($title) || empty($title)) {
    return;
  }

  if (strlen($title) > 20) {
    return;
  }

  require 'EnforceSqliteConnection.php';

  // TODO: If the page is public then the server still needs to check if the user
  // is allowed to edit.
  $changeNoteStmt = $db->prepare("UPDATE notes SET title=? WHERE id=? AND owner_id=?");
  $changeNoteStmt->bindValue(1, $title, SQLITE3_TEXT);
  $changeNoteStmt->bindValue(2, $noteId, SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(3, $_SESSION['pageOwnerId'], SQLITE3_INTEGER);

  $changeNoteStmt->execute();

  $db->close();

?>
