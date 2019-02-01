<?php

  require 'EnforceSession.php';

  $noteId = $_POST['noteId'];

  require 'EnforceSqliteConnection.php';

  // TODO: If the page is public then the server still needs to check if the user
  // is allowed to edit.
  $changeNoteStmt = $db->prepare("DELETE FROM notes WHERE id=? AND owner_id=?");
  $changeNoteStmt->bindValue(1, $noteId, SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(2, $_SESSION['pageOwnerId'], SQLITE3_INTEGER);

  $changeNoteStmt->execute();

  $db->close();

?>
