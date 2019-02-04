<?php

  require 'EnsureNoteEditAccess.php';

  $projectName = $_POST['projectName'];
  checkNoteEditStatus($projectName);

  $noteId = $_POST['noteId'];

  require 'EnforceSqliteConnection.php';

  $changeNoteStmt = $db->prepare("DELETE FROM notes WHERE id=? AND owner_id=? AND project_name=?");
  $changeNoteStmt->bindValue(1, $noteId, SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(2, $_SESSION['pageOwnerId'], SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(3, $projectName, SQLITE3_TEXT);

  $changeNoteStmt->execute();

  $db->close();

?>
