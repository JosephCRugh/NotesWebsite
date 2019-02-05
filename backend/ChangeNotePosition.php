<?php

  require 'EnsureNoteEditAccess.php';

  $projectName = $_POST['projectName'];
  checkNoteEditStatus($projectName);

  $noteId = $_POST['noteId'];
  $posX = $_POST['posX'];
  $posY = $_POST['posY'];

  require 'EnforceSqliteConnection.php';

  $changeNoteStmt = $db->prepare("UPDATE notes SET offset_x=?, offset_y=? WHERE id=? AND owner_id=? AND project_name=?");
  $changeNoteStmt->bindValue(1, $posX, SQLITE3_FLOAT);
  $changeNoteStmt->bindValue(2, $posY, SQLITE3_FLOAT);
  $changeNoteStmt->bindValue(3, $noteId, SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(4, $_SESSION['pageOwnerId'], SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(5, $projectName, SQLITE3_TEXT);

  $changeNoteStmt->execute();

  $db->close();

?>
