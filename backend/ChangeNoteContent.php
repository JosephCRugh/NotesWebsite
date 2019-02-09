<?php

  require 'EnsureNoteEditAccess.php';

  $projectId = $_POST['projectId'];
  checkNoteEditStatus($projectId);

  $noteId = $_POST['noteId'];
  $bodyContent = $_POST['content'];

  if (strlen($bodyContent) > 500) {
    return;
  }

  require 'EnforceSqliteConnection.php';

  $changeNoteStmt = $db->prepare("UPDATE user_notes SET content=? WHERE note_id=? AND user_id=? AND project_id=?");
  $changeNoteStmt->bindValue(1, $bodyContent, SQLITE3_TEXT);
  $changeNoteStmt->bindValue(2, $noteId, SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(3, $_SESSION['pageOwnerId'], SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(4, $projectId, SQLITE3_INTEGER);

  $changeNoteStmt->execute();

  $db->close();


?>
