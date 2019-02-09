<?php

  require 'EnsureNoteEditAccess.php';

  $projectId = $_POST['projectId'];
  checkNoteEditStatus($projectId);

  $noteId = $_POST['noteId'];

  require 'EnforceSqliteConnection.php';

  $changeNoteStmt = $db->prepare("DELETE FROM user_notes WHERE note_id=? AND user_id=? AND project_id=?");
  $changeNoteStmt->bindValue(1, $noteId, SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(2, $_SESSION['pageOwnerId'], SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(3, $projectId, SQLITE3_INTEGER);

  $changeNoteStmt->execute();

  $db->close();

?>
