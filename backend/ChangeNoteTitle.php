<?php

  require 'EnsureNoteEditAccess.php';

  $projectName = $_POST['projectName'];
  checkNoteEditStatus($projectName);

  $noteId = $_POST['noteId'];
  $title = $_POST['title'];

  if (!isset($title) || empty($title)) {
    return;
  }

  if (strlen($title) > 20) {
    return;
  }

  require 'EnforceSqliteConnection.php';

  $changeNoteStmt = $db->prepare("UPDATE notes SET title=? WHERE id=? AND owner_id=? AND project_name=?");
  $changeNoteStmt->bindValue(1, $title, SQLITE3_TEXT);
  $changeNoteStmt->bindValue(2, $noteId, SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(3, $_SESSION['pageOwnerId'], SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(4, $projectName, SQLITE3_TEXT);

  $changeNoteStmt->execute();

  $db->close();

?>
