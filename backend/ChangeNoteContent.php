<?php

  require 'EnsureNoteEditAccess.php';

  $projectName = $_POST['projectName'];
  checkNoteEditStatus($projectName);

  $noteId = $_POST['noteId'];
  $bodyContent = $_POST['content'];

  if (strlen($bodyContent) > 500) {
    return;
  }

  require 'EnforceSqliteConnection.php';

  $changeNoteStmt = $db->prepare("UPDATE notes SET content=? WHERE id=? AND owner_id=? AND project_name=?");
  $changeNoteStmt->bindValue(1, $bodyContent, SQLITE3_TEXT);
  $changeNoteStmt->bindValue(2, $noteId, SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(3, $_SESSION['pageOwnerId'], SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(4, $projectName, SQLITE3_TEXT);

  $changeNoteStmt->execute();

  $db->close();


?>
