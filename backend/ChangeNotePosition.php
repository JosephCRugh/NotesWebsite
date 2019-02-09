<?php

  require 'EnsureNoteEditAccess.php';

  $projectId = $_POST['projectId'];
  checkNoteEditStatus($projectId);

  $noteId = $_POST['noteId'];
  $posX = $_POST['posX'];
  $posY = $_POST['posY'];

  require 'EnforceSqliteConnection.php';

  $changeNoteStmt = $db->prepare("UPDATE user_notes SET pos_x=?, pos_y=? WHERE note_id=? AND user_id=? AND project_id=?");
  $changeNoteStmt->bindValue(1, $posX, SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(2, $posY, SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(3, $noteId, SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(4, $_SESSION['pageOwnerId'], SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(5, $projectId, SQLITE3_TEXT);

  $changeNoteStmt->execute();

  $db->close();

?>
